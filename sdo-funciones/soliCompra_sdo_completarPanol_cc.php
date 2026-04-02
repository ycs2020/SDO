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
}else if ($rolUsuario2 == "jefe tecnico") {

    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {

    $ruta = "sdo-administrador";
} else if($rolUsuario2 == "directiva"){
    $ruta = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}


if($_POST['id']){

    $idSoliCompra = $_POST['id'];

    $tipoActualizacion = "Solicitud Completada";

    //ACTUALIZA LA TABLA DE LAS SOLICITUDES DE COMPRA
    $sqlActualiza = "UPDATE solicitudes_compra_sdo_cc SET estadoSoliCompra = '$tipoActualizacion' WHERE idSoliCompra  = $idSoliCompra";
    $resActualiza = mysqli_query($conexion, $sqlActualiza);

    //ACTUALIZA ESTADO DE LA SOLICITUDES EN LA TABLA  seguimiento_sc_sdo
    $observacion = "Todos los materiales fueron entregados, solicitud completada.";

    $sqlSegumiento = "INSERT INTO  seguimiento_sc_sdo_cc (observacion, tipoActualizacion, idSoliCompra) VALUES ('$observacion', '$tipoActualizacion', $idSoliCompra)";
    $resSeguimiento = mysqli_query($conexion, $sqlSegumiento);

    //Actualiza tabla de resumen
    $sqlResumen = "UPDATE solicitudes_sc_sdo_resumen_cc SET estadoSolicitud = 'Completada' WHERE idSoliCompra = $idSoliCompra";
    $resResumen = mysqli_query($conexion, $sqlResumen);


}