<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

date_default_timezone_set('America/Santiago');

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
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if(isset($_POST['idTG'])){
    $idTG = $_POST['idTG'];
    $numSoliCompra = $_POST['numSoliCompra'];
    $now = date('Y-m-d H:i:s');
    $detalleOT = 'Se ha ingresado la solicitud de compra con número: '. $numSoliCompra;

    $sqlAvance = "INSERT INTO linea_vida_ot (idLineaVida,nomCreador,detalle,fechaSubida,respuestaSoli,numSoliCompra,idTG)
    VALUES (NULL,'".$nomUsuario."','".$detalleOT."','".$now."',1,'$numSoliCompra',$idTG)";
    $resAvance = mysqli_query($conexion,$sqlAvance);

    if($resAvance){
        if($tipoUsuario !== 'personal'){
            header('Location: ../'.$rutaDireccion.'/detalle_ordenes_trabajo.php?exitoIngresoSolicitudCompra=true');
        }else{
            header('Location: ../'.$rutaDireccion.'/visualizardetalles.php?exitoIngresoSolicitudCompra=true');
        }
    }else{
        if($tipoUsuario !== 'personal'){
            header('Location: ../'.$rutaDireccion.'/detalle_ordenes_trabajo.php?errorIngresoSolicitudCompra=true');
        }else{
            header('Location: ../'.$rutaDireccion.'/visualizardetalles.php?errorIngresoSolicitudCompra=true');
        }
    }

}else{
    if($tipoUsuario !== 'personal'){
        header('Location: ../'.$rutaDireccion.'/detalle_ordenes_trabajo.php?errorIngresoSolicitudCompra=true');
    }else{
        header('Location: ../'.$rutaDireccion.'/visualizardetalles.php?errorIngresoSolicitudCompra=true');
    }
}
