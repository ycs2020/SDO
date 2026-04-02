<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');

$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

$rutaDireccion = "";

if ($rolUsuario2 == "personal") {
    $tipoUsuario = 'personal';
    $rutaDireccion = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $tipoUsuario = 'administrativo';
    $rutaDireccion = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $tipoUsuario = 'bodega pañol';
    $rutaDireccion = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $tipoUsuario = 'jefe tecnico';
    $rutaDireccion = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $tipoUsuario = 'administrador';
    $rutaDireccion = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $tipoUsuario = 'directiva';
    $rutaDireccion = "sdo-directiva";
} else if($rolUsuario2 == "oficina tecnica") {
    $tipoUsuario = 'oficina tecnica';
    $rutaDireccion = "sdo-oficinaTecnica";
}else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}


if(isset($_POST['idTG'])){
   $idTG = $_POST['idTG'];
   $detalle = $_POST['detalleObs'];
   $nomCreador = $_POST['nomCreador'];
   $now = date('Y-m-d H:i:s');
   $rolUsuario = $_POST['rolUsuario'];

   $sql = "INSERT INTO observacion_ot (idObsOt,nomCreador,detalleObs,fecha,idTG)
    VALUES (NULL,'".$nomCreador."','".$detalle."','".$now."',$idTG)";
   $res = mysqli_query($conexion,$sql);


   if($res){
       if($rolUsuario != 'Personal'){
           header('Location: ../' . $rutaDireccion . '/detalle_ordenes_trabajo.php?exitoObservacion=true');
       }else{
           header('Location: ../' . $rutaDireccion . '/visualizardetalles.php?exitoObservacion=true');
       }
   }else{
       if($rolUsuario != 'Personal'){
           header('Location: ../' . $rutaDireccion . '/detalle_ordenes_trabajo.php?errorObservacion=true');
       }else{
           header('Location: ../' . $rutaDireccion . '/visualizardetalles.php?errorObservacion=true');
       }
   }
}else{
    if($rolUsuario != 'Personal'){
        header('Location: ../' . $rutaDireccion . '/detalle_ordenes_trabajo.php?errorObservacion=true');
    }else{
        header('Location: ../' . $rutaDireccion . '/visualizardetalles.php?errorObservacion=true');
    }
}