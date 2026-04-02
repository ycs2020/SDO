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

if (isset($_POST['idArchivoC'])) {

    $idArchivoC = trim($_POST['idArchivoC']);

    if ($idArchivoC > 0) {

        $rutaArchivo = getRutaArchivoC($idArchivoC);

        $porciones = explode("../sdo-carpetas", $rutaArchivo);

        $rutaArchivo2 = __DIR__ . $porciones[1];
        

//        echo $rutaArchivo2;
        if (unlink($rutaArchivo2)) {

            $sqlBorrarArchivo = "DELETE FROM carpeta_archivos WHERE idArchivoC = '" . $idArchivoC . "'";

            $resBorrarArchivo = mysqli_query($conexion, $sqlBorrarArchivo);

            if ($resBorrarArchivo) {

                header('Location: ../' . $ruta . '/carpetas_listado.php?exitoBorrarArchivo=true');

            } else {
                header('Location: ../' . $ruta . '/carpetas_listado.php?errorBorrarArchivo=true');
            }
//                echo $sqlBorrarCarpeta;
        } else {
            header('Location: ../' . $ruta . '/carpetas_listado.php?errorBorrarArchivo=true');
        }


    } else {
        header('Location: ../' . $ruta . '/carpetas_listado.php?errorBorrarArchivo=true');
    }


}