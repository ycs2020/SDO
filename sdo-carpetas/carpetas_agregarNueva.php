<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$rolUsuario = $_SESSION['rolUsuario'];

$nomCreador = $_SESSION['nomUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

$ruta = "";

if ($rolUsuario2 == "personal") {

    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {

    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "administrativo") {

    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "jefe tecnico") {

    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "directiva") {

    $ruta = "sdo-directiva";

    $cargoUsuario = $_SESSION['cargoUsuario'];

    $cargoUsuario = trim($cargoUsuario);
} else if ($rolUsuario2 == "administrador") {

    $ruta = "sdo-administrador";

    $cargoUsuario = $_SESSION['cargoUsuario'];

    $cargoUsuario = trim($cargoUsuario);

    if (empty($cargoUsuario)) {
        header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    }
} else if ($rolUsuario2 == "mesa ayuda") {

    $ruta = "sdo-mesa-ayuda";
} else {

    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if (isset($_POST['nomCarpeta'],$_POST['idDepartamentoCarpeta'])) {

    $nomCarpeta = trim($_POST['nomCarpeta']);
    $idDepartamentoCarpeta = trim($_POST['idDepartamentoCarpeta']);

    if(isset($_POST['idCarpetaPadre'])){

        $idCarpetaPadre = trim($_POST['idCarpetaPadre']);

        if($idCarpetaPadre == 0){

            $carpeta = '../sdo-carpetas/'.$nomCarpeta;
        }else{
            $rutaCarpetaPadre = getRutaCarpetaPadre($idCarpetaPadre);

            $carpeta = $rutaCarpetaPadre.'/'.$nomCarpeta;
        }
    }else{
        $idCarpetaPadre = 0;

        $carpeta = '../sdo-carpetas/'.$nomCarpeta;
    }


    if (!file_exists($carpeta)) {
        // Crear la carpeta con permisos 777
        mkdir($carpeta, 0777);
        $dir = opendir($carpeta);

        $sqlCarpeta = "INSERT INTO carpeta_control(idCarpeta, nomCarpeta, rutaCarpeta, idCarpetaPadre, idDepartamento) VALUES (NULL,'".$nomCarpeta."','".$carpeta."','".$idCarpetaPadre."','".$idDepartamentoCarpeta."')";

        $resCarpeta = mysqli_query($conexion, $sqlCarpeta);

        if($resCarpeta){
            header('Location: ../'.$ruta.'/carpetas_listado.php?exitoCrearCarpeta=true');
        }else{
            header('Location: ../'.$ruta.'/carpetas_listado.php?errorCarpetaSQL=true');
        }


    } else {
        header('Location: ../'.$ruta.'/carpetas_listado.php?errorCarpetaExiste=true');
    }


}else{
    header('Location: ../'.$ruta.'/carpetas_listado.php?errorCrearCarpeta=true');
}