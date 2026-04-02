<?php
include_once '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];
$idDepa = $_SESSION['idDepartamento'];

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
} else {
    header('Location: ../sdo-flujo/login.php?errorSesion=true');
}
date_default_timezone_set('America/Santiago');

if(isset($_POST['id'])){
    $idTG = $_POST['id'];

    $detalle = "La Orden de Trabajo se ha finalizado con exito";
    $now = date('Y-m-d H:i:s');


    $sqlAvance = "INSERT INTO avances_trabajo_general (idAvance,nomCreador,detalleObs,observacion,estadoAvance,folioHT,fechaSubida,idTG,personaDerivada,noavance)
    VALUES (NULL,'".$nomUsuario."','".$detalle."','','Trabajo Terminado','','".$now."',$idTG,'',1)";
    $resAvance = mysqli_query($conexion,$sqlAvance);

    $sqlUpdate = "UPDATE trabajos_general SET estadoTG = 'Trabajo Terminado',fechaTermino = '$now' WHERE idTG = $idTG";
    $resUpdate = mysqli_query($conexion,$sqlUpdate);

    if($resAvance && $resUpdate){
        header('Location: ../'.$ruta . '/trabajos_general_listado_finalizados.php?exitoFinOt=true');
    }else{
        header('Location: ../'.$ruta . '/visualizardetalles.php?errorFinOt=true');
    }

}else{
    header('Location: ../'.$ruta. '/visualizardetalles.php?errorFinOt=true');
}
