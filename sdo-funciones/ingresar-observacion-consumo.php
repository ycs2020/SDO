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

date_timezone_set("America/Santiago");

if(isset($_POST['idAE'])){
    $idAE = $_POST['idAE'];
    $tipoHospital = $_POST['tipoHospital'];
    $nomCreador = $_POST['nomCreador'];
    $detalleObs = $_POST['detalleObs'];
    $now = date('Y-m-d H:i:s');




    if($idAE == '' || $tipoHospital == '' || $nomCreador == '' || $detalleObs == '' || $now == '' || empty($idAE) || empty($tipoHospital) || empty($nomCreador) || empty($detalleObs) || empty($now)){
        header('Location: ../'.$ruta.'/electricidad_administracion_hbqp.php?errorObservacion=true');
    }else{
        $sql = "INSERT INTO electricidad_observacion (idObs,detalleObservacion,nomCreador,fechaIngreso,idAE,tipoHospital)
        VALUES (NULL,'".$detalleObs."','".$nomCreador."','".$now."',$idAE,'".$tipoHospital."')";
        $res = mysqli_query($conexion,$sql);
        if($res){
            header('Location: ../'.$ruta.'/electricidad_administracion_hbqp.php?exitoObservacion=true');
        }else{
            header('Location: ../'.$ruta.'/electricidad_administracion_hbqp.php?errorObservacion=true');
        }
    }

}