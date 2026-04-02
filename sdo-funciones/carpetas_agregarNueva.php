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

if (isset($_POST['nomCarpeta'])) {

    $nomCarpeta = trim($_POST['nomCarpeta']);

    if(isset($_POST['idCarpetaPadre'])){

        $idCarpetaPadre = trim($_POST['idCarpetaPadre']);
        
    }else{
        $idCarpetaPadre = 0;
    }


    $carpeta = '../sdo-carpetas/'.$nomCarpeta; // Reemplaza esta ruta con la ubicación deseada para la carpeta

    if (!file_exists($carpeta)) {
        // Crear la carpeta con permisos 777
        mkdir($carpeta, 0777);

        // Asignar el propietario a la carpeta (reemplaza 'usuario' con el nombre del usuario deseado)
        chown($carpeta, 'adminSDO');

        $sqlCarpeta = "INSERT INTO carpeta_control(idCarpeta, nomCarpeta, rutaCarpeta, idCarpetaPadre) VALUES (NULL,'".$nomCarpeta."','".$carpeta."','".$idCarpetaPadre."')";

        echo $sqlCarpeta;

    } else {
        echo "La carpeta ya existe.";
    }


}