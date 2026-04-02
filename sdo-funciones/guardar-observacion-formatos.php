<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


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
} else if($rolUsuario2 == 'ofcina tecnica'){
    $ruta = "sdo-oficinaTecnica";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}


if(isset($_POST['idFormRegAcceso'],$_POST['idFormFuncCCAA'])){
    $idFormRegAcceso = $_POST['idFormRegAcceso'];
    $nomCreador = $_POST['nomCreador'];
    $detalleObs = $_POST['detalleObs'];
    $idFormFuncCCAA = $_POST['idFormFuncCCAA'];
    $fechaObservacion = $_POST['fechaObservacion'];
    $folioFormRegAcceso = $_POST['folioFormRegAcceso'];

    if(empty($nomCreador) || empty($detalleObs) || empty($fechaObservacion)){
        echo 'TODO MAL';
    }else{
        $sql = "INSERT INTO formularios_observaciones (idObservacion,detalleObs,fechaObs,creador,idFormRegAcceso,idFormFuncCCAA,folioFormRegAcceso)
        VALUES (NULL,'".$detalleObs."','".$fechaObservacion."','".$nomCreador."',$idFormRegAcceso,$idFormFuncCCAA,'".$folioFormRegAcceso."')";
        $res = mysqli_query($conexion,$sql);

        if($res){
            header('Location: ../'.$ruta.'/formulariosCCAA_administracion_2.php?exitoObservacion=true');
        }else{
            header('Location: ../'.$ruta.'/formulariosCCAA_administracion_2.php?errorObservacion=true');
        }

    }
}else{
    header('Location: ../'.$ruta.'/formulariosCCAA_administracion_2.php?errorObservacion=true');
}