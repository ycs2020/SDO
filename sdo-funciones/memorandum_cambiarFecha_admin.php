<?php

include_once 'conexion.php';
include_once 'funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('America/Santiago');

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


if(isset($_POST['fechaEnvioMemo'],$_POST['idMemoCambio'])){

    $fechaEnvioMemo = trim($_POST['fechaEnvioMemo']);
    $idMemoCambio = trim($_POST['idMemoCambio']);

    if(!empty($fechaEnvioMemo) && !empty($idMemoCambio) && $idMemoCambio > 0){

        $sqlMemo = "UPDATE memorandum_admin SET fechaEnvioMemo = '".$fechaEnvioMemo."' WHERE idMemo = ".$idMemoCambio;
//
//        echo $sqlMemo;
//        exit;

        $resMemo = mysqli_query($conexion , $sqlMemo);

        if($resMemo){
            header('Location: ../'.$rutaDireccion . '/memorandum_listado.php?exitoFechaEnvioMemo=true');
        }else{
            header('Location: ../'.$rutaDireccion . '/memorandum_listado.php?errorFechaEnvioMemo=true');
        }

    }else{
        header('Location: ../'.$rutaDireccion . '/memorandum_listado.php?errorFechaEnvioMemo=true');
    }
}else{
    header('Location: ../'.$rutaDireccion . '/memorandum_listado.php?errorFechaEnvioMemo=true');
}