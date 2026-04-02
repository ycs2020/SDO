<?php
include 'conexion.php';
include 'funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

$ruta = "";

if ($rolUsuario2 == "personal") {
    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $ruta = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $ruta = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $ruta = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if(isset($_POST['addNombre'],$_POST['addRUT'])){


    $addNombre = $_POST['addNombre'];
    $addRUT = $_POST['addRUT'];

        $insertamosCategoria = "INSERT INTO `conductores_movilizacion`(`idConductor`, `nomConductor`, `rutConductor`,antesConductor) VALUES (null,'$addNombre','$addRUT',1) ";

        $respuesta = mysqli_query($conexion, $insertamosCategoria);

        if ($respuesta && mysqli_affected_rows($conexion) > 0) {
            header('Location: ../' . $ruta . '/conductores_administracion.php?exitoAgregarConductor=true;');
        } else {
            header('Location: ../' . $ruta . '/conductores_administracion.php?errorAgregarConductor=true');
        }
}else{
    header('Location: ../' . $ruta . '/conductores_administracion.php?errorAgregarConductor=true');
}
