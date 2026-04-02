<?php
include_once 'conexion.php';
include_once 'funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//VARIABLES DE SESSION
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
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}


if(isset($_POST['idAE']) || $_POST['fechaDocumento']){
    $idAE = $_POST['idAE'];
    $fechaDocumento = $_POST['fechaDocumento'];
    $tipoDocumento = $_POST['tipoDocumento'];
    $nDocumento = $_POST['nDocumento'];
    $proveedor = $_POST['proveedor'];
    $fechaLectura = $_POST['fechaLectura'];
    $consumoElectrica = $_POST['consumoElectrica'];
    $valorNeto = $_POST['valorNeto'];
    $valorIVA = $_POST['valorIVA'];
    $valorExento = $_POST['valorExento'];
    $valorTotal = $_POST['valorTotal'];
    $selectEstado = $_POST['selectEstado'];
    $nCompra = trim($_POST['nCompra']);

    $fechaDocumento = strtotime($fechaDocumento);
    $fechaLectura = strtotime($fechaLectura);

    $fechaDocumento2 = date('Y-m-d H:i:s', $fechaDocumento);
    $fechaLectura2 = date('Y-m-d H:i:s', $fechaLectura);

    $fechaDocumento1 = $fechaDocumento.' 08:00:00';
    $fechaLectura1 = $fechaLectura.' 09:00:00';

    if(empty($tipoDocumento) || $tipoDocumento == '-'){
        $tipoDocumento = 'No registra tipo de documento';
    }

    if(empty($nDocumento) || $nDocumento == ''){
        $nDocumento = 0;
    }

    if(empty($proveedor) || $proveedor == ''){
        $proveedor = '';
    }

    if(empty($fechaLectura2) || $fechaLectura2 == '0000-00-00 00:00:00'){
        $fechaLectura2 = '0000-00-00 00:00:00';
    }

    if($consumoElectrica == '' || empty($consumoElectrica)){
        $consumoElectrica = 0;
    }
    if($valorNeto == '' || empty($valorNeto)){
        $valorNeto = 0;
    }
    if($valorIVA == '' || empty($valorIVA )){
        $valorIVA  = 0;
    }

    if($valorExento == '' || empty($valorExento )){
        $valorExento  = 0;
    }

    if($valorTotal == '' || empty($valorTotal)){
        $valorTotal  = 0;
    }


    $sqlUpdate = "UPDATE electricidad_administracion SET estado = '$selectEstado', fechaDocumento = '$fechaDocumento2', tipoDocumento = '$tipoDocumento', nDocumento = $nDocumento, proveedor = '$proveedor', fechaLectura = '$fechaLectura2', consumoEnergia = $consumoElectrica, neto = $valorNeto, iva = $valorIVA, exento = $valorExento, montoTotalMes = $valorTotal, numeroOC = '$nCompra' WHERE idAE = $idAE";
    $resUpdate = mysqli_query($conexion,$sqlUpdate);

    
    //???????

    if($resUpdate){
        header('Location:../'.$rutaDireccion.'/electricidad_administracion_hbqp.php?exitoModificar=true');
    }else{
        header('Location:../'.$rutaDireccion.'/electricidad_administracion_hbqp.php?errorModificar=true');
//        echo $sqlUpdate;
//        exit();
    }

}else{
    header('Location:../'.$rutaDireccion.'/electricidad_administracion_hbqp.php?errorModificar=true');
}