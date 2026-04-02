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
} else if($rolUsuario2 == "oficina tecnica") {
    $tipoUsuario = 'oficina tecnica';
    $rutaDireccion = "sdo-oficinaTecnica";
}else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');

}


if(isset($_POST['fechaDocumento'])){
    $fechaDocumento = $_POST['fechaDocumento'];
    $cantidadCilindros10M3 = $_POST['cantidadCilindros10M3'];
    $cantidadCilindros07M3 = $_POST['cantidadCilindros07M3'];
    $nPacientes = $_POST['nPacientes'];
    $valorCilindor10m3 = $_POST['valorCilindor10m3'];
    $valorCilindor07M3 = $_POST['valorCilindor07M3'];
    $valorNeto = $_POST['valorNeto'];
    $personalAC = $_POST['personalAC'];

    $fechaTodo = $fechaDocumento . '-01'.' 08:00:00';


    if($personalAC == '' || empty($personalAC)){
        $personalAC = 'No se registra tecnico';
    }

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
        $sqlInsert = "INSERT INTO gases_clinicos (idGases,fechaIngreso,cilindrosEntre10m3,cilindrosEntre07m3,nPacientes,valor10M3,valor07M3,valorTotalNeto,valorTotalIva,personalAC)
        VALUES (NULL,'$fechaTodo',$cantidadCilindros10M3,$cantidadCilindros07M3,$nPacientes,$valorCilindor10m3,$valorCilindor07M3,$valorNeto,$valorTotal,'".$personalAC."')";


        $resInsert = mysqli_query($conexion,$sqlInsert);

        if($resInsert){
            header('Location:../'.$rutaDireccion.'/gases_clinicos.php?exito=true');
        }else{
            header('Location:../'.$rutaDireccion.'/gases_clinicos.php?errorInsert=true');
        }
    }else{
        header('Location:../'.$rutaDireccion.'/gases_clinicos.php?errorSumacilindros=true');
    }
}else{
    header('Location:../'.$rutaDireccion.'/gases_clinicos.php?errorInsert=true');
}


