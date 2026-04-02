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
}else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if (isset($_POST['idRegPab'])) {

    $idRegPab = trim($_POST['idRegPab']);

    if (empty($idRegPab) || $idRegPab == "" || $idRegPab == " ") {
        header('Location: ../'.$rutaDireccion . '/registros_pabellones.php?noIdRegPab=true');
    } else {

        $queryReg = "DELETE FROM pabellones_correo WHERE idRegistroPab = " . $idRegPab;
        $resReg = mysqli_query($conexion, $queryReg);

        if ($resReg) {
            header('Location: ../'.$rutaDireccion . '/registros_pabellones.php?exitoEliminarRegistro=true');
        } else {
            header('Location: ../'.$rutaDireccion . '/registros_pabellones.php?errorEliminarRegistro=true');
        }
//        echo $queryReg;
    }
} else {
    header('Location: ../'.$rutaDireccion . '/registros_pabellones.php?noIdRegPab=true');
}
