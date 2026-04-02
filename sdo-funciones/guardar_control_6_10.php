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
    $acusete6_10 = $_POST['acusete_6_10'];
    $obs_6_10 = $_POST['obs_6-10'];
    $numObs = '6.10';

    if (is_array($acusete6_10)) {
        sort($acusete6_10);
        // Unir los elementos del array con ' - ' como delimitador
        $acusete6_10 = implode(' - ', $acusete6_10);
    } else {
        $acusete6_10 = '';
    }
    $acusete6_10 = trim($acusete6_10);

    $sql = "INSERT INTO `edan_control_centralizado`(`id`, `nomCreador`, `detalleObs`, `fecha`, `idEdan`,numObs) 
    VALUES (NULL,'$acusete6_10','$obs_6_10','$now',$id,'$numObs')";
    $res = mysqli_query($conexion,$sql);

    if($res){
        header('Location: ../'.$rutaDireccion . '/listar_informe_edan.php?exito=true');
    }else{
        header('Location: ../'.$rutaDireccion . '/listar_informe_edan.php?error=true');
    }
}