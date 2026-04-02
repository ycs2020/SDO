<?php

include_once 'conexion.php';
include_once 'funciones.php';

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

if(isset($_POST['idGases']) || $_POST['fechaIngreso']) {
    $idGases = $_POST['idGases'];
    $fechaIngreso = $_POST['fechaIngreso'];
    $cantidadCilindros07M3 = $_POST['cantidadCilindros07M3'];
    $cantidadCilindros10M3 = $_POST['cantidadCilindros10M3'];
    $nPacientes = $_POST['nPacientes'];
    $valorCilindor10m3 = $_POST['valorCilindor10m3'];
    $valorCilindor07M3 = $_POST['valorCilindor07M3'];
    $valorNeto = $_POST['valorNeto'];

    $valorTotal = $valorNeto * 1.19;


    if($cantidadCilindros10M3 == '' || empty($cantidadCilindros10M3)){
        $cantidadCilindros10M3 = 0;
    }

    if($cantidadCilindros07M3 == '' || empty($cantidadCilindros07M3)){
        $cantidadCilindros07M3 = 0;
    }

    if($nPacientes == '' || empty($nPacientes)){
        $nPacientes = 0;
    }

    if($valorCilindor10m3 == '' || empty($valorCilindor10m3)){
        $valorCilindor10m3 = 0;
    }

    if($valorCilindor07M3 == '' || empty($valorCilindor07M3)){
        $valorCilindor07M3 = 0;
    }

    if($valorNeto == '' || empty($valorNeto)){
        $valorNeto = 0;
    }

    $sumacilindros = $valorCilindor10m3 + $valorCilindor07M3;

    if($sumacilindros == $valorNeto){
        $sqlUpdate = "UPDATE gases_clinicos SET fechaIngreso = '$fechaIngreso',cilindrosEntre10m3 = $cantidadCilindros10M3,cilindrosEntre07m3 = $cantidadCilindros07M3,nPacientes = $nPacientes,
        valor10M3 = $valorCilindor10m3, valor07M3 = $valorCilindor07M3, valorTotalNeto = $valorNeto, valorTotalIva = $valorTotal WHERE idGases = $idGases";
        $resUpdate = mysqli_query($conexion,$sqlUpdate);

        if($resUpdate){
            header('Location:../'.$rutaDireccion.'/gases_clinicos.php?exitoModificar=true');
        }else{
            header('Location:../'.$rutaDireccion.'/gases_clinicos.php?errorMofificacion=true');
        }
    }else{
        header('Location:../'.$rutaDireccion.'/gases_clinicos.php?errorSumacilindros=true');
    }
}else{
    header('Location:../'.$rutaDireccion.'/gases_clinicos.php?errorMofificacion=true');
}

