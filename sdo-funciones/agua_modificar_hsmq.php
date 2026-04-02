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

if(isset($_POST['idAA'],$_POST['fechaDocumento'],$_POST['tipoDocumento'],$_POST['nDocumento'],$_POST['proveedor'],$_POST['fechaLectura'],$_POST['nLectura'],$_POST['consumoAgua'],$_POST['valorNeto'],$_POST['valorIVA'],$_POST['valorTotal'],$_POST['estadoPagado'],$_POST['tipoHospital'],$_POST['selectMedidor'],$_POST['selectnCliente'])){

    $idAA = $_POST['idAA'];

    if($idAA > 0){
        $fechaDocumento = $_POST['fechaDocumento'];
        $tipoDocumento = trim($_POST['tipoDocumento']);
        $nDocumento = trim($_POST['nDocumento']);
        $proveedor = trim($_POST['proveedor']);
        $fechaLectura = trim($_POST['fechaLectura']);
        $nLectura = trim($_POST['nLectura']);
        $consumoAgua = $_POST['consumoAgua'];
        $valorNeto = $_POST['valorNeto'];
        $valorIVA = $_POST['valorIVA'];
        $valorTotal = $_POST['valorTotal'];
        $estadoPagado = trim($_POST['estadoPagado']);
        //$estadoPagado = mb_convert_case($estadoPagado,MB_CASE_UPPER,'UTF-8');
        $tipoHospital = trim($_POST['tipoHospital']);
        $nCliente = trim($_POST['nCliente1']);
        $medidor = trim($_POST['medidor1']);
        $nit = trim($_POST['nit']);
        $selectnCliente = trim($_POST['selectnCliente']);
        $nCompra = trim($_POST['nCompra']);
        $selectMedidor = trim($_POST['selectMedidor']);



        if(empty($fechaDocumento) || empty($tipoDocumento) || empty($proveedor) || empty($fechaLectura) || empty($consumoAgua) || empty($valorNeto) || empty($valorIVA) || empty($valorTotal) || empty($estadoPagado) || empty($tipoHospital) || empty($nCliente) || empty($medidor)){


            header('Location:../'.$rutaDireccion.'/agua_administracion_hsmq.php?errorModificarConsumo1=true');
        }else{

            $calcularSuma = $valorNeto + $valorIVA;

            if($calcularSuma == $valorTotal){

                if(empty($selectnCliente)){
                    $sqlAgua = "UPDATE agua_administracion SET numeroOC = '".$nCompra."',fechaDocumento='".$fechaDocumento."',tipoDocumento='".$tipoDocumento."',nDocumento='".$nDocumento."',proveedor='".$proveedor."',fechaLectura='".$fechaLectura."',nLectura='".$nLectura."',consumoAgua='".$consumoAgua."',neto=".$valorNeto.",iva=".$valorIVA.",montoTotalMes=".$valorTotal.",nCliente='".$nCliente."',medidor='".$medidor."',estado='".$estadoPagado."' WHERE idAA =".$idAA;
                }else{
                    $sqlAgua = "UPDATE agua_administracion SET numeroOC = '".$nCompra."',fechaDocumento='".$fechaDocumento."',tipoDocumento='".$tipoDocumento."',nDocumento='".$nDocumento."',proveedor='".$proveedor."',fechaLectura='".$fechaLectura."',nLectura='".$nLectura."',consumoAgua='".$consumoAgua."',neto=".$valorNeto.",iva=".$valorIVA.",montoTotalMes=".$valorTotal.",nCliente='".$selectMedidor."',medidor='".$selectMedidor."',estado='".$estadoPagado."' WHERE idAA =".$idAA;
                }
//                echo $sqlAgua;
//                exit();

                $res = mysqli_query($conexion,$sqlAgua);
                if($res){
                    header('Location:../'.$rutaDireccion.'/agua_administracion_hsmq.php?exitoModificarConsumo=true');
                }else{
                    header('Location:../'.$rutaDireccion.'/agua_administracion_hsmq.php?errorModificarConsumo2=true');
                }
            }else{
                header('Location:../'.$rutaDireccion.'/agua_administracion_hsmq.php?errorModificarConsumo3=true');
            }
        }
    }else{
        header('Location:../'.$rutaDireccion.'/agua_administracion_hsmq.php?errorModificarConsumo4=true');
    }
}else{
    header('Location:../'.$rutaDireccion.'/agua_administracion_hsmq.php?errorModificarConsumo5=true');
}