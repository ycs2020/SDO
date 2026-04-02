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


if(isset($_POST['idAA'])){

    $idAA = $_POST['idAA'];
    $tipoHospital = $_POST['tipoHospital'];
    $nomCreador = $_POST['nomCreador'];
    $detalleObs = $_POST['detalleObs'];
    $now = date('Y-m-d H:i:s');

    if($idAA == '' || $tipoHospital == '' || $nomCreador == '' || $detalleObs == '' || $now == '' || empty($idAA) || empty($tipoHospital) || empty($nomCreador) || empty($detalleObs) || empty($now)){
        header('Location: ../'.$ruta.'/agua_administracion_hsmq.php?errorObservacion=true');
    }else{
        $sql = "INSERT INTO agua_observacion (idObs,detalleObservacion,nomCreador,fechaIngreso,idAA,tipoHospital)
        VALUES (NULL,'".$detalleObs."','".$nomCreador."','".$now."',$idAA,'".$tipoHospital."')";
        $res = mysqli_query($conexion,$sql);
        if($res){
            header('Location: ../'.$ruta.'/agua_administracion_hsmq.php?exitoObservacion=true');
        }else{
            header('Location: ../'.$ruta.'/agua_administracion_hsmq.php?errorObservacion=true');
        }
    }

}else{
    header('Location: ../'.$ruta.'/agua_administracion_hsmq.php?errorObservacion=true');
}