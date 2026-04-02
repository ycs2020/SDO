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
} else if ($rolUsuario2 == "oficina tecnica"){
    $ruta = "sdo-oficinaTecnica";
}else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if($_POST['nombreCategoriaNueva']) {
    $now = date('Y-m-d H:i:s');
    $nombreCategoriaAntigua = $_POST['nombreCategoriaAntigua'];
    $nombreCategoriaNueva = $_POST['nombreCategoriaNueva'];
    $nomCategoria = $_POST['nomCategoria'];
    $nomUsuario = $_POST['nomUsuario'];

    $sql = "UPDATE categorias_recepcion_conforme 
        SET nombre = '$nombreCategoriaNueva' 
        WHERE id = $nombreCategoriaAntigua";

    $res = mysqli_query($conexion,$sql);

    if($res){
        header('Location: ../'. $ruta .'/recepcion_conforme.php?exitoCrearCategoria=true');
    }else{
        header('Location: ../'. $ruta .'/recepcion_conforme.php?errorCrearCategoria=true');
    }
}else{
    header('Location: ../'. $ruta .'/recepcion_conforme.php?errorCrearCategoria=true');
}