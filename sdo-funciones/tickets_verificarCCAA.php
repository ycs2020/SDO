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

if(isset($_POST['idUsuariosT'])){

    $idUsuariosT = $_POST['idUsuariosT'];

    if(empty($idUsuariosT) || $idUsuariosT == 0){
        header('Location: ../'.$rutaDireccion . '/usuarios_tickets.php?errorActivarCCAA=true');
    }else{

        date_default_timezone_set('America/Santiago');
        $now = date('Y-m-d H:i:s', time());

        $queryModificar = "UPDATE usuarios_tickets SET ccaaUsuariosT = 1, fechaCCAAUsuariosT = '" . $now . "' WHERE idUsuariosT = " . $idUsuariosT;

        $resModificar = mysqli_query($conexion, $queryModificar);

        if ($resModificar) {
            header('Location: ../'.$rutaDireccion . '/usuarios_tickets.php?exitoActivarCCAA=true');
        } else {
            header('Location: ../'.$rutaDireccion . '/usuarios_tickets.php?errorActivarCCAA=true');
        }

    }
}else{
    header('Location: ../'.$rutaDireccion . '/usuarios_tickets.php?errorActivarCCAA=true');
}