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
$idDepartamento = $_SESSION['idDepartamento'];
$rutaDireccion = "";

if ($rolUsuario2 == "personal") {
    $tipoUsuario = 'personal';
    $rutaDireccion  = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $tipoUsuario = 'administrativo';
    $rutaDireccion  = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $tipoUsuario = 'bodega pañol';
    $rutaDireccion  = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $tipoUsuario = 'jefe tecnico';
    $rutaDireccion  = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $tipoUsuario = 'administrador';
    $rutaDireccion  = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $tipoUsuario = 'directiva';
    $rutaDireccion  = "sdo-directiva";
}else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if(isset($_POST['idTG'])){
    $idTG = $_POST['idTG'];
    $nomCreador = $_POST['nomCreador'];
    $now = date('Y-m-d H:i:s');
    $detalleMateriales = $_POST['detalleMateriales'];
    $detalleSolic = $_POST['detalleSolic'];
    if($detalleSolic == '' || empty($detalleSolic)){
        $sql = "INSERT INTO `linea_vida_ot`(`idLineaVida`, `nomCreador`, `detalle`, `fechaSubida`,isMaterialSoliCompra,isPanol,`idTG`) 
        VALUES (NULL,'".$nomCreador."','".$detalleMateriales."','".$now."',0,1,$idTG)";
        $res = mysqli_query($conexion,$sql);
    }else{
        $sql = "INSERT INTO `linea_vida_ot`(`idLineaVida`, `nomCreador`, `detalle`, `fechaSubida`,isMaterialSoliCompra,isPanol,`idTG`) 
        VALUES (NULL,'".$nomCreador."','".$detalleSolic."','".$now."',1,0,$idTG)";
        $res = mysqli_query($conexion,$sql);
    }

    if($res){
        if($tipoUsuario !== 'personal'){
            header('Location: ../'.$rutaDireccion.'/detalle_ordenes_trabajo.php?exitolineavida=true');
        }else{
            header('Location: ../'.$rutaDireccion.'/visualizardetalles.php?exitolineavida=true');
        }
    }else{
        if($tipoUsuario !== 'personal'){
            header('Location: ../'.$rutaDireccion.'/detalle_ordenes_trabajo.php?errorlineavida=true');
        }else{
            header('Location: ../'.$rutaDireccion.'/visualizardetalles.php?errorlineavida=true');
        }
    }
}else{
    if($tipoUsuario !== 'personal'){
        header('Location: ../'.$rutaDireccion.'/detalle_ordenes_trabajo.php?errorlineavida=true');
    }else{
        header('Location: ../'.$rutaDireccion.'/visualizardetalles.php?errorlineavida=true');
    }
}