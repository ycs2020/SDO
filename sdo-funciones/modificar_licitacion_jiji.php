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
    $numeroMemo = $_POST['numeroMemo'];
    $nombreProceso = $_POST['nombreProceso'];
    $cargo = $_POST['cargo'];
    $tipo = $_POST['tipo'];
    $presupuesto = $_POST['presupuesto'];
    $encargado = $_POST['encargado'];
    $estado = $_POST['estado'];
    $idLicitacion = $_POST['idLicitacion'];
    $idLicitacionModi = $_POST['idLicitacionModi'];
    $fechaInicio = $_POST['fechaInicio'];
    $fechaTermino = $_POST['fechaTermino'];

    if(empty($fechaTermino)){
        $fechaTermino = '0000-00-00 00:00:00';
    }
    if(empty($fechaInicio)){
        $fechaInicio = "0000-00-00 00:00:00";
    }

    $sql = "UPDATE `licitaciones` SET fechaTermino='$fechaTermino',fechaInicio='$fechaInicio',idLicitacion='$idLicitacionModi',`numeroMemo`='$numeroMemo',`nombreProceso`='$nombreProceso',`cargo`='$cargo',`tipo`='$tipo',`presupuesto`='$presupuesto',`encargado`='$encargado',`estado`='$estado' WHERE id = $idLicitacion";
    $res = mysqli_query($conexion,$sql);

    if($res){
        header('Location: ../'. $ruta .'/licitaciones_admin.php?exitoModiLicitacion');
    }else{
        header('Location: ../'. $ruta .'/licitaciones_admin.php?errorModiLicitacion');
    }
}else{
    header('Location: ../'. $ruta .'/licitaciones_admin.php?errorModiLicitacion');
}