<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

date_default_timezone_set('America/Santiago');

$now = date('Y-m-d H:i:s');

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
} else if($rolUsuario2 == "oficina tecnica"){
    $tipoUsuario = 'oficina tecnica';
    $rutaDireccion = "sdo-oficinaTecnica";
}else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}


if(isset($_POST['id'])){
    $id = $_POST['id'];
    $observacion6_6 = $_POST['observacion6_6'];

    $update = "UPDATE `edan_control_centralizado` SET `detalleObs`='$observacion6_6' WHERE numObs = 6.6 AND id = $id";
    $res = mysqli_query($conexion,$update);
    if($res){
        header('Location: ../'.$rutaDireccion . '/editar_control_edan.php?exitoModi=true');
    }else{
        header('Location: ../'.$rutaDireccion . '/editar_control_edan.php?errorModi=true');
    }
}