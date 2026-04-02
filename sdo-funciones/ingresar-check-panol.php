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
    $nomCreador = $_POST['nomCreador'];
    $now = date('Y-m-d H:i:s');
    $detalleAceptar = $_POST['detalleAceptar'];
    $idLineaVida = $_POST['idLineaVida'];

    $sql = "INSERT INTO `aceptados_panol`(`idAceptado`, `nomCreador`, `fecha`, `idTG`, `idLineaVida`, `detalle`) 
    VALUES (NULL,'".$nomCreador."','".$now."',$idTG,$idLineaVida,'".$detalleAceptar."')";
    $res = mysqli_query($conexion,$sql);

    if($res){
        $sqlUpdate = "UPDATE linea_vida_ot SET isPanol = 0,respuestaPanol = 1,fechaAceptadoPanol = '$now' WHERE idLineaVida = $idLineaVida";
        $resUpdate = mysqli_query($conexion,$sqlUpdate);

        if($tipoUsuario !== 'personal'){
            header('Location: ../'.$rutaDireccion.'/detalle_ordenes_trabajo.php?exitocheck=true');
        }else{
            header('Location: ../'.$rutaDireccion.'/visualizardetalles.php?exitocheck=true');
        }
    }else{
        if($tipoUsuario !== 'personal'){
            header('Location: ../'.$rutaDireccion.'/detalle_ordenes_trabajo.php?errorcheck1=true');
        }else{
            header('Location: ../'.$rutaDireccion.'/visualizardetalles.php?errorcheck1=true');
        }
    }

}else{
    if($tipoUsuario !== 'personal'){
        header('Location: ../'.$rutaDireccion.'/detalle_ordenes_trabajo.php?errorIngresoCheck=true');
    }else{
        header('Location: ../'.$rutaDireccion.'/visualizardetalles.php?errorIngresoCheck=true');
    }
}