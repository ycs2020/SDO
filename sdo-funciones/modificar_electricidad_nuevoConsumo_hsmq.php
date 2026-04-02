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


if(isset($_POST['idAE']) || $_POST['fechaDocumento']) {
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
    $selectnCliente = $_POST['selectnCliente'];
    $selectnMedidor = $_POST['selectnMedidor'];
    $nCliente1 = $_POST['nCliente1'];

    $nCompra = $_POST['nCompra'];

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

    if(empty($fechaLectura1) || $fechaLectura1 == '0000-00-00 00:00:00'){
        $fechaLectura = '0000-00-00 00:00:00';
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


    if(empty($selectnCliente)){
        $sql = "UPDATE electricidad_administracion SET fechaDocumento = '$fechaDocumento1', tipoDocumento = '$tipoDocumento', nDocumento = $nDocumento, proveedor = '$proveedor',
        fechaLectura = '$fechaLectura1', consumoEnergia = $consumoElectrica, neto = $valorNeto, iva = $valorIVA, exento = $valorExento, montoTotalMes = $valorTotal, nCliente = $nCliente1, medidor = $nCliente1, estado = '$selectEstado', numeroOC = '$nCompra' WHERE idAE = $idAE ";
        $res = mysqli_query($conexion,$sql);
    }else{
        $sql = "UPDATE electricidad_administracion SET fechaDocumento = '$fechaDocumento1', tipoDocumento = '$tipoDocumento', nDocumento = $nDocumento, proveedor = '$proveedor',
        fechaLectura = '$fechaLectura1', consumoEnergia = $consumoElectrica, neto = $valorNeto, iva = $valorIVA, exento = $valorExento, montoTotalMes = $valorTotal, nCliente = $selectnCliente, medidor = $selectnCliente, estado = '$selectEstado', numeroOC = '$nCompra' WHERE idAE = $idAE ";
        $res = mysqli_query($conexion,$sql);
    }

    if($res){
        header('Location:../'.$rutaDireccion.'/electricidad_administracion_hsmq.php?exitoModificar=true');
    }else{
        header('Location:../'.$rutaDireccion.'/electricidad_administracion_hsmq.php?errorModificar=true');
    }



}