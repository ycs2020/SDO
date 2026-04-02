<?php
include_once 'conexion.php';
include_once 'funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

$rutaDireccion = "";

if ($rolUsuario2 == "personal") {
    $tipoUsuario = 'personal';
    $rutaDireccion = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $tipoUsuario = 'administrativo';
    $rutaDireccion = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $tipoUsuario = 'bodega pañol';
    $rutaDireccion = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $tipoUsuario = 'jefe tecnico';
    $rutaDireccion = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $tipoUsuario = 'administrador';
    $rutaDireccion = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $tipoUsuario = 'directiva';
    $rutaDireccion = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}
if(isset($_POST['idAE']) || $_POST['fechaDocumento']){
    $idAE = $_POST['idAE'];
    $fechaDocumento = $_POST['fechaDocumento'];
    $tipoDocumento = $_POST['tipoDocumento'];
    $nDocumento = $_POST['nDocumento'];
    $proveedor = $_POST['proveedor'];
    $fechaLectura = $_POST['fechaLectura'];
    $consumoElectrica = $_POST['consumoElectrica'];
    $valorNeto = $_POST['valorNeto'];
    $valorIVA = $_POST['valorIVA'];
    $valorExento = $_POST['valorExento'];
    $valorTotal = $_POST['valorTotal'];
    $selectEstado = $_POST['selectEstado'];
    echo $selectEstado;
}
