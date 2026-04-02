<?php
include 'conexion.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

$ruta = "";

if ($rolUsuario2 == "personal") {

    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {

    $ruta = "sdo-admtec";
}else if ($rolUsuario2 == "mesa ayuda") {

    $ruta = "sdo-mesa-ayuda";
} else if ($rolUsuario2 == "bodega pañol") {

    $ruta = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {

    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {

    $ruta = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $ruta = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}
$now = date('Y-m-d H:i:s');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomUsuario = $_POST['nomUsuario'];
    $idSoliCompra = $_POST['idSoliCompra'];
    $obs = $_POST['obsCompra'];
    $sql = "INSERT INTO `observaciones_obs_ca_cc`(`id`, `nomUsuario`, `obs`, `fecha`, `idSoliCompra`) 
    VALUES (NULL,'$nomUsuario','$obs','$now',$idSoliCompra)";
    $res = mysqli_query($conexion,$sql);
    if($res){
        header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento_nuevo.php?exitoAgregarObs=true');
    }else{
        header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento_nuevo.php?errorAgregarObs=true');
    }
}else{
    header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento_nuevo.php?errorAgregarObs=true');
}