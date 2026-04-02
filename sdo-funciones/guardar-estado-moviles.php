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
}else if ($rolUsuario2 == "jefe tecnico") {

    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {

    $ruta = "sdo-administrador";
} else if($rolUsuario2 == "directiva"){
    $ruta = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){

    //RECIBIMOS LOS DATOS Y LOS ALMACENAMOS EN VARIABLES
    $idMovil = $_POST['idMovil'];
    $fechaReporte = $_POST['fechaReporte'];
    $estadoMovil = $_POST['estadoMovil'];
    $motivo = $_POST['motivo'];

    $estadoMovil = strtoupper($estadoMovil);

    //INSERTAMOS EN LA BASE DE DATOS
    $sql = "INSERT INTO historial_estados_moviles (estadoMovil, motivo,idMovil)  VALUES ('$estadoMovil', '$motivo', $idMovil) ";
    $res = mysqli_query($conexion , $sql);

    //ACTUALIZAMOS LA TABLA DE LOS MOVILES EN LA BASE DE DATOS
    $sqlUpdate = "UPDATE moviles_movilizacion SET estadoVehiculo = '$estadoMovil' WHERE idMovil = $idMovil";
    $resUpdate = mysqli_query($conexion, $sqlUpdate);

    if($res){
        header('Location: ../'. $ruta .'/visualizarMovil.php?exitoEstado=true&&idMovil=' . $idMovil);
    }else{
        header('Location: ../'. $ruta .'/visualizarMovil.php?errorEstado=true&&idMovil=' . $idMovil);
    }
     
    
}
