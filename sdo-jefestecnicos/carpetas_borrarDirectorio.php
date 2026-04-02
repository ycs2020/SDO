<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$rolUsuario = $_SESSION['rolUsuario'];

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
} else if ($rolUsuario2 == "administrador") {

    $ruta = "sdo-administrador";
} else if ($rolUsuario2 == "mesa ayuda") {

    $ruta = "sdo-mesa-ayuda";
} else {

    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if(isset($_POST['idCarpeta'],$_POST['idCarpetaAnterior'])){

    $idCarpeta = trim($_POST['idCarpeta']);
    $idCarpetaAnterior = trim($_POST['idCarpetaAnterior']);

    if($idCarpeta > 0){

        $rutaArchivosCarpetas = getRutaArchivosCarpetas($idCarpeta);

        $rutaArchivosCarpetas2 = $rutaArchivosCarpetas.'/*';

        $cantidadArchivosCarpetas = getCantidadArchivosCarpetas($rutaArchivosCarpetas2);

        if($cantidadArchivosCarpetas == 0){

//            echo $rutaArchivosCarpetas;
            if(rmdir($rutaArchivosCarpetas)){

                $sqlBorrarCarpeta = "DELETE FROM carpeta_control WHERE idCarpeta = '".$idCarpeta."'";

                $resBorrarCarpeta = mysqli_query($conexion, $sqlBorrarCarpeta);

                if ($resBorrarCarpeta) {

                    header('Location: ../'.$ruta.'/carpetas_listado.php?exitoBorrarCarpeta=true');

                }else{
                    header('Location: ../'.$ruta.'/carpetas_listado.php?errorBorrarCarpeta=true');
                }
//                echo $sqlBorrarCarpeta;
            }else{
                header('Location: ../'.$ruta.'/carpetas_listado.php?errorBorrarCarpeta=true');
            }

        }else{
            header('Location: ../'.$ruta.'/carpetas_listado.php?errorCarpetaOcupada=true');
        }

    }else{
        header('Location: ../'.$ruta.'/carpetas_listado.php?errorBorrarCarpeta=true');
    }


}