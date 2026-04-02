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


if(isset($_POST['fechaDocumento']) || $_POST['selectnCliente'] || $_POST['selectnMedidor']) {
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
    $selectnCliente = $_POST['selectnCliente'];
    $tipoHospital = 'HSMQ';
    $selectnMedidor = $_POST['selectnMedidor'];
    $selectEstado = $_POST['selectEstado'];
    $personalAC = $_POST['personalAC'];
    $nCompra = trim($_POST['nCompra']);
    $notas = $_POST['notas'];

    $fechaDocumento1 = $fechaDocumento.' 08:00:00';
    $fechaLectura1 = $fechaLectura.' 09:00:00';



    if($personalAC == '' || empty($personalAC)){
        $personalAC = 'No se registra tecnico';
    }

    if(empty($nDocumento) || $nDocumento == ''){
        $nDocumento = 0;
    }

    if(empty($proveedor) || $proveedor == ''){
        $proveedor = '';
    }

    if(empty($fechaLectura1) || $fechaLectura1 == '0000-00-00 00:00:00'){
        $fechaLectura1 = '0000-00-00 00:00:00';
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

/*PREGUNTAR SI DEBO DE ELIMINAR ESTA PARTE*/

    $selectnCliente2 = mb_convert_case($selectnCliente,MB_CASE_LOWER,'utf-8');

    if ($selectnCliente2 == 0 || $selectnCliente2 == 'Otro' || $selectnCliente2 == 'otro') {
        if(isset($_POST['otroCliente'],$_POST['otroMedidor'])){
            $otroCliente = $_POST['otroCliente'];
            $otroMedidor = $_POST['otroMedidor'];
            if(empty($otroCliente) || empty($otroMedidor)){
                echo 'TODO MAL';
            }else{
                $existeClienteLuz = existeClienteLuz($otroCliente);
                $existeMedidorLuz = existeMedidorLuz($otroCliente);
                if($existeClienteLuz == 0 || empty($existeClienteLuz)){
                    if($existeMedidorLuz == 0 || empty($existeMedidorLuz)){
                        $nCliente = $otroCliente;
                        $medidor = $otroMedidor;

                        $queryOtroServicio = "INSERT INTO cliente_electricidad(idCliente,nCliente,tipoHospital,medidor) VALUES (NULL,'" . $otroCliente . "','HSMQ','" . $otroMedidor . "')";
                        $resOtroServicio = mysqli_query($conexion, $queryOtroServicio);

                        $idNuevoCliente = mysqli_insert_id($conexion);
                    }else{
                        header('Location:../'.$rutaDireccion.'/electricidad_administracion_hsmq.php?error1=true');
                    }
                }else{
                    header('Location:../'.$rutaDireccion.'/electricidad_administracion_hsmq.php?error2=true');
                }
            }
        }else{
            header('Location:../'.$rutaDireccion.'/electricidad_administracion_hsmq.php?error3=true');
        }
    }

    if(empty($idNuevoCliente)){
        $sql = "INSERT INTO electricidad_administracion (idAE,fechaDocumento,tipoDocumento,nDocumento,proveedor,fechaLectura,consumoEnergia,neto,iva,exento,montoTotalMes,tipoHospital,nCliente,medidor,estado,personalAC,numeroOC)
            values (NULL,'".$fechaDocumento1."','".$tipoDocumento."',$nDocumento,'".$proveedor."','".$fechaLectura1."',$consumoElectrica,$valorNeto,$valorIVA,$valorExento,$valorTotal,'".$tipoHospital."','".$selectnCliente."','".$selectnCliente."','".$selectEstado."','".$personalAC."','".$nCompra."')";

        $res = mysqli_query($conexion,$sql);
    }else{
        $sql = "INSERT INTO electricidad_administracion (idAE,fechaDocumento,tipoDocumento,nDocumento,proveedor,fechaLectura,consumoEnergia,neto,iva,exento,montoTotalMes,tipoHospital,nCliente,medidor,estado,personalAC,numeroOC)
            values (NULL,'".$fechaDocumento1."','".$tipoDocumento."',$nDocumento,'".$proveedor."','".$fechaLectura1."',$consumoElectrica,$valorNeto,$valorIVA,$valorExento,$valorTotal,'".$tipoHospital."','".$idNuevoCliente."','".$idNuevoCliente."','".$selectEstado."','".$personalAC."','".$nCompra."')";
        $res = mysqli_query($conexion,$sql);
    }

    if($res){

        date_default_timezone_set('America/Santiago');
        $now = date('Y-m-d H:i:s');


        $idAE = mysqli_insert_id($conexion);

        if(empty($notas) || $notas == ''){
            header('Location:../'.$rutaDireccion.'/electricidad_administracion_hsmq.php?exito=true');
        }else{
            $sqlObservacion = "INSERT INTO electricidad_observacion (idObs,detalleObservacion,nomCreador,fechaIngreso,idAE,tipoHospital)
            VALUES (NULL,'".$notas."','".$personalAC."','".$now."',$idAE,'".$tipoHospital."')";
            $resObservacion = mysqli_query($conexion,$sqlObservacion);
            if($resObservacion){
                header('Location:../'.$rutaDireccion.'/electricidad_administracion_hsmq.php?exito=true');
            }else{
                header('Location:../'.$rutaDireccion.'/electricidad_administracion_hsmq.php?error=true');
            }
        }

        header('Location:../'.$rutaDireccion.'/electricidad_administracion_hsmq.php?exito=true');
    }else{
        header('Location:../'.$rutaDireccion.'/electricidad_administracion_hsmq.php?error5=true');
    }



}