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

$now = date('Y-m-d H:i:s');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $memos = $_POST['memos'];
    $formatos = $_POST['formatos'];

    $sqlInsert = "INSERT INTO memos_formatos (cantidadMemo,cantidadFormato,fecha) VALUES ('$memos','$formatos','$now')";
    $resInsert = mysqli_query($conexion, $sqlInsert);
    if ($resInsert) {
        header('Location: ../'.$rutaDireccion.'/formatosymemos.php?logro=true');
    }else{
        header('Location: ../'.$rutaDireccion.'/formatosymemos.php?error=true');
    }
}else{
    header('Location: ../'.$rutaDireccion.'/formatosymemos.php?error1=true');
}