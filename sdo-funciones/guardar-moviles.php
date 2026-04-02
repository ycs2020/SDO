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

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $idVehiculo =  mb_strtoupper(trim($_POST['idVehiculo']), 'UTF-8');
    $nomVehiculo =  mb_strtoupper(trim($_POST['nomVehiculo']), 'UTF-8');
    $patenteVehiculo = mb_strtoupper(trim($_POST['patenteVehiculo']), 'UTF-8');
    $marcaVehiculo = mb_strtoupper(trim($_POST['marcaVehiculo']), 'UTF-8');
    $modeloVehiculo = mb_strtoupper(trim($_POST['modeloVehiculo']), 'UTF-8');
    $numInventario = mb_strtoupper(trim($_POST['numInventario']), 'UTF-8');
    $servicioVehiculo = mb_strtoupper(trim($_POST['servicioVehiculo']), 'UTF-8');
    $numChasis = mb_strtoupper(trim($_POST['numChasis']), 'UTF-8');
    $numMotor = mb_strtoupper(trim($_POST['numMotor']), 'UTF-8');
    $frabricante = mb_strtoupper(trim($_POST['frabricante']), 'UTF-8');
    $fechaCompra = mb_strtoupper(trim($_POST['fechaCompra']), 'UTF-8');

    $ordenCompra = mb_strtoupper(trim($_POST['ordenCompra']), 'UTF-8');
    $valorConIva = mb_strtoupper(trim($_POST['valorConIva']), 'UTF-8');
    $vidaUtil = mb_strtoupper(trim($_POST['vidaUtil']), 'UTF-8');
    

    if($valorConIva !== ''){
    }else{
        $valorConIva = 'NULL';
    }

    $estadoVehiculo = "OPERATIVA";

    $sql = "INSERT INTO moviles_movilizacion(idMovil, idUnico ,nomVehiculo, patenteVehiculo, marcaVehiculo, modeloVehiculo, numInventario, servicioVehiculo, numChasis, numMotor, frabricante, ordenCompra, valorConIva, vidaUtil,estadoVehiculo) VALUES (NULL,'".$idVehiculo."','".$nomVehiculo."','".$patenteVehiculo."','".$marcaVehiculo."','".$modeloVehiculo."','".$numInventario."','".$servicioVehiculo."','".$numChasis."','".$numMotor."','".$frabricante."','".$ordenCompra."',".$valorConIva.", '".$vidaUtil."','".$estadoVehiculo."')";
    $res = mysqli_query($conexion, $sql);

    $idMovil = mysqli_insert_id($conexion);

    if(!empty($fechaCompra)){
        $sqlUpdate = "UPDATE FROM moviles_movilizacion SET fechaCompra = $fechaCompra WHERE idMovil  = $idMovil ";
        $resUpdate = mysqli_query($conexion, $sqlUpdate);
    }

    //CREA FOLIO
    $folio = "HBQP-MOVIL-" . $idMovil;

    $sqlFolio = "UPDATE moviles_movilizacion SET folio = '".$folio."' WHERE idMovil = '".$idMovil."'";
    $resFolio = mysqli_query($conexion, $sqlFolio);


    if($res && $resFolio){
        header('Location: ../'. $ruta .'/administrar-moviles.php?exitoGuardar=true');
    }else{
        header('Location: ../'. $ruta .'/administrar-moviles.php?errorGuardar=true');
    }

    echo $sql;

}else{
    echo "No método POST";
}