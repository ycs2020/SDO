<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

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
} else if ($rolUsuario2 == "bodega pañol") {
    $ruta = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $ruta = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $ruta = "sdo-directiva";
} else if ($rolUsuario2 == "mesa ayuda") {
    $ruta = "sdo-mesa-ayuda";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}


$idConvenio = $_POST['idConvenio'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $monto = $_POST['monto'];
    $oc = $_POST['oc'];
    $fecha = $_POST['fecha']; // ejemplo: "2026-03"
    $idRegistro = $_POST['idRegistro'];

// Convertimos a formato DATE MySQL (YYYY-MM-DD)
    $fecha_mysql = $fecha . '-01'; // "2026-03-01"}

    $sql = "UPDATE `registros_convenios` SET `fecha`='$fecha_mysql',`oc`='$oc',`monto`= $monto WHERE id = $idRegistro";
    $res = mysqli_query($conexion, $sql);

    if($res){
        header('Location: ../'.$ruta.'/revisar_registros.php?exitoRegistro=true&idConvenio='.$idConvenio);
        exit;

    }else{
        header('Location: ../'.$ruta.'/revisar_registros.php?errorRegistro=true&idConvenio='.$idConvenio);
        exit;
    }
}else{
    header('Location: ../'.$ruta.'/revisar_registros.php?errorRegistroGeneral=true&idConvenio='.$idConvenio);
    exit;
}