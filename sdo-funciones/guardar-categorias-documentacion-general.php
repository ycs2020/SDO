<?php

include 'conexion.php';
include 'funciones.php';

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
$idDepartamento = $_SESSION['idDepartamento'];
$rutaDireccion = "";

if ($rolUsuario2 == "personal") {
    $rutaDireccion = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $rutaDireccion = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $rutaDireccion = "sdo-bodega-panol";
}else if ($rolUsuario2 == "jefe tecnico") {
    $rutaDireccion = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $rutaDireccion = "sdo-administrador";
} else if($rolUsuario2 == "directiva"){
    $rutaDireccion = "sdo-directiva";
} else if($rolUsuario2 == 'oficina tecnica'){
    $rutaDireccion = "sdo-oficinaTecnica";
}else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if($_POST['nomCategoria']) {
    $now = date('Y-m-d');
    $nomCategoria = $_POST['nomCategoria'];
    $sql = "INSERT INTO `categorias_documentacion_general`(`nomCategoria`, `nomCreador`, `fecha`) 
    VALUES ('$nomCategoria','$nomUsuario','$now')";
    $res = mysqli_query($conexion,$sql);
    if($res){
        header('Location: ../'. $rutaDireccion .'/documentacion_general.php?exitoCrearCategoria=true');
    }else{
        header('Location: ../'. $rutaDireccion .'/documentacion_general.php?errorCrearCategoria=true');
    }
}else{
    header('Location: ../'. $rutaDireccion .'/documentacion_general.php?errorCrearCategoria=true');
}