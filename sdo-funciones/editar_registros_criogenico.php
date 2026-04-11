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

if(isset($_POST['idConsumo'])){

    $idConsumo = $_POST['idConsumo'];


    $fechaInput = $_POST['fecha'];

    if (preg_match('/^\d{4}-\d{2}$/', $fechaInput)) {
        $fecha = $fechaInput . '-01';
    } else {
        $fecha = null; // o manejar error
    }
    $oc = $_POST['oc'];
    $nFactura = $_POST['nFactura'];
    $mConsumidos = $_POST['mConsumidos'];
    $precioUnitario = $_POST['precioUnitario'];
    $valorTotalNeto = $_POST['valorTotalNeto'];
    $valorTotalIVA = $_POST['valorTotalIVA'];


    $sql = "UPDATE `criogenico_datos` SET `fecha`='$fecha',`oc`='$oc',`nFactura`='$nFactura',`metrosConsumidos`=$mConsumidos,`precioUnitario` = $precioUnitario,`valorTotal`=$valorTotalNeto,`valorTotalConIVA`=$valorTotalIVA WHERE id = $idConsumo";
    $res = mysqli_query($conexion,$sql);
    if($res){
        header('Location:../'.$rutaDireccion.'/criogenico_datos.php?exitoEdicion=true');
        exit();
    }else{
        header('Location:../'.$rutaDireccion.'/criogenico_datos.php?errorEdicion=true');
        exit();
    }
}else{
    header('Location:../'.$rutaDireccion.'/criogenico_datos.php?errorGeneral=true');
    exit();
}

