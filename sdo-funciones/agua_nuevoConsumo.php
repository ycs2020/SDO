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

if(isset($_POST['fechaDocumento'],$_POST['tipoDocumento'],$_POST['proveedor'],$_POST['fechaLectura'],$_POST['nLectura'],$_POST['estadoPagado'],$_POST['tipoHospital'],$_POST['nCliente'],$_POST['medidor'])){

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
    $estadoPagado = mb_convert_case($estadoPagado,MB_CASE_UPPER,'UTF-8');
    $tipoHospital = trim($_POST['tipoHospital']);
    $nCliente = trim($_POST['nCliente']);
    $medidor = trim($_POST['medidor']);
    $personalAC = trim($_POST['personalAC']);
    $nCompra = trim($_POST['nCompra']);
    $notas = $_POST['notas'];

    $fechaDocumento1 = $fechaDocumento.' 08:00:00';
    $fechaLectura1 = $fechaLectura.' 09:00:00';


    if($personalAC == '' || empty($personalAC)){
        $personalAC = 'No se registra tecnico';
    }

    if(empty($fechaDocumento) || empty($tipoDocumento) || empty($nDocumento) || empty($proveedor) || empty($fechaLectura) || empty($estadoPagado) || empty($tipoHospital) || empty($nCliente) || empty($medidor)){
        header('Location:../'.$rutaDireccion.'/agua_administracion_hbqp.php?errorNuevoConsumo=true');
    }else{

        $calcularSuma = $valorNeto + $valorIVA;

        if($calcularSuma == $valorTotal){
            $sqlAgua = "INSERT INTO agua_administracion(idAA,fechaDocumento,tipoDocumento,nDocumento,proveedor,fechaLectura,nLectura,consumoAgua,neto,iva,montoTotalMes,tipohospital,ncliente,medidor,estado,personalAC,numeroOC) 
            VALUES (NULL,'".$fechaDocumento1."','".$tipoDocumento."','".$nDocumento."','".$proveedor."','".$fechaLectura1."','".$nLectura."','".$consumoAgua."','".$valorNeto."','".$valorIVA."','".$valorTotal."','".$tipoHospital."','".$nCliente."','".$medidor."','".$estadoPagado."','".$personalAC."','".$nCompra."')";
            $res = mysqli_query($conexion,$sqlAgua);
            if($res){
                date_default_timezone_set('America/Santiago');

                $now = date('Y-m-d H:i:s');

                $idAA = mysqli_insert_id($conexion);

                if(empty($notas) || $notas == ''){
                    header('Location:../'.$rutaDireccion.'/agua_administracion_hbqp.php?exitoNuevoConsumo=true');
                }else{
                    $sqlObservacion = "INSERT INTO agua_observacion (idObs,detalleObservacion,nomCreador,fechaIngreso,idAA,tipoHospital)
                      VALUES (NULL,'".$notas."','".$personalAC."','".$now."',$idAA,'".$tipoHospital."')";
                    $resObservacion = mysqli_query($conexion,$sqlObservacion);
                    if($resObservacion){
                        header('Location:../'.$rutaDireccion.'/agua_administracion_hbqp.php?exitoNuevoConsumo=true');
                    }else{
                        header('Location:../'.$rutaDireccion.'/agua_administracion_hbqp.php?errorNuevoConsumo=true');
                    }
                }

            }else{

                header('Location:../'.$rutaDireccion.'/agua_administracion_hbqp.php?errorNuevoConsumo=true');
            }
        }else{
            header('Location:../'.$rutaDireccion.'/agua_administracion_hbqp.php?errorNuevoConsumo2=true');
        }
    }

}