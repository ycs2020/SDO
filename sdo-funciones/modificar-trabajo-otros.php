<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

date_default_timezone_set('America/Santiago');

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


if(isset($_POST['idTG'])){
    $idTG = $_POST['idTG'];
    $detalle = $_POST['detalle'];

    $sql = "UPDATE avances_trabajo_general SET detalleOBS = '$detalle' WHERE idTG = $idTG AND tipoTrabajo = 'OTROS'";
    $res = mysqli_query($conexion,$sql);

    if($res){
        if($tipoUsuario != 'personal'){
            header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?exitoMODIOTROS=true');
        }else{
            header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?exitoMODIOTROS=true');
        }
    }else{
        if($tipoUsuario != 'personal'){
            header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?errorMODIOTROS=true');
        }else{
            header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?errorMODIOTROS=true');
        }
    }
}else{
    if($tipoUsuario != 'personal'){
        header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?errorMODIOTROS=true');
    }else{
        header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?errorMODIOTROS=true');
    }
}