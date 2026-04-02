<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

$rutaDireccion = "";

if ($rolUsuario2 == "personal") {
    $rutaDireccion = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $rutaDireccion = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $rutaDireccion = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $rutaDireccion = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $rutaDireccion = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $rutaDireccion = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idSC = $_POST['idSC'];
    $codigoSoliCompra = $_POST['codigoSoliCompra'];

    $sqlUpdate = "UPDATE solicitudes_compra_sdo SET scSIGH = '$codigoSoliCompra' WHERE idSoliCompra = '$idSC'";
    $resultUpdate = mysqli_query($conexion, $sqlUpdate);
    if ($resultUpdate) {
        header('Location: ../'.$rutaDireccion.'/soliCompra_sdo_seguimiento.php?logro=true');
    }else{
        header('Location: ../'.$rutaDireccion.'/soliCompra_sdo_seguimiento.php?error=true');
    }
}