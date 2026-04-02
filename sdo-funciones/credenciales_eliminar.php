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
} else if($rolUsuario2 == "oficina tecnica") {
    $tipoUsuario = 'oficina tecnica';
    $rutaDireccion = "sdo-oficinaTecnica";
} else if($rolUsuario2 == "funcionario personal") {
    $tipoUsuario = 'funcionario personal';
    $rutaDireccion = "sdo-usuariosPersonal";
}else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if (isset($_POST['idCredencial'])) {

    $idCredencial = trim($_POST['idCredencial']);

    if (empty($idCredencial) || $idCredencial == "" || $idCredencial == " ") {
        header('Location: ../'.$rutaDireccion . '/credenciales_administracion.php?errorEliminarCredencial=true');
    } else {

        $queryCredencial = "DELETE FROM credenciales_personal WHERE idCredencial = " . $idCredencial;
        $resCredencial = mysqli_query($conexion, $queryCredencial);

        if ($resCredencial) {
            header('Location: ../'.$rutaDireccion . '/credenciales_administracion.php?exitoEliminarCredencial=true');
        } else {
            header('Location: ../'.$rutaDireccion . '/credenciales_administracion.php?errorEliminarCredencial=true');
        }
//        echo $queryReg;
    }
} else {
    header('Location: ../'.$rutaDireccion . '/credenciales_administracion.php?errorIdCredencial=true');
}
