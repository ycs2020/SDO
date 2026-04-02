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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha = $_POST['fecha'];
    $observacion = $_POST['obs'];
    $idContrato = $_POST['idContrato'];
    $idCategoria = $_POST['idCategoria'];

    $sql = "INSERT INTO `observaciones_recepcion_conforme`(`id`, `fecha`, `obs`, `idCategoria`, `idContrato`) 
    VALUES (NULL,'$fecha','$observacion',$idCategoria,$idContrato)";

    $res = mysqli_query($conexion, $sql);

    if($res){
        header('Location: ../'.$ruta.'/detalles-categorias-recepciones-conformes.php?status=ok&idCategoria='.$idCategoria);

    }else{
        header('Location: ../'.$ruta.'/detalles-categorias-recepciones-conformes.php?status=error&idCategoria='.$idCategoria);
    }
}