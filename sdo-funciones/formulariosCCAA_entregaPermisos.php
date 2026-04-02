<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


date_default_timezone_set("America/Santiago");
setlocale(LC_TIME, 'es_CL.UTF-8', 'esp');
$now = date('Y-m-d H:i:s', time());

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
} else if($rolUsuario2 == 'ofcina tecnica'){
    $ruta = "sdo-oficinaTecnica";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}


if(isset($_POST['idFormRegAcceso'],$_POST['idFormFuncCCAA'],$_POST['nomCreador'],$_POST['detalleObs'],$_POST['folioFormRegAcceso'])){
    $idFormRegAcceso = $_POST['idFormRegAcceso'];

    if($idFormRegAcceso > 0) {

        $_SESSION['idFormRegAcceso'] = $idFormRegAcceso;

//        $nomCreador = $_POST['nomCreador'];
        $nomCreador = 'Yorgo Casimis Subiabre';
        $nomCreador2 = mb_convert_case($nomCreador,MB_CASE_TITLE,'UTF-8');
        $detalleObs = $_POST['detalleObs'];
        $idFormFuncCCAA = $_POST['idFormFuncCCAA'];

        $fechaObservacion = $_POST['fechaObservacion'];
        $folioFormRegAcceso = $_POST['folioFormRegAcceso'];

        if(empty($nomCreador) || empty($detalleObs)){
            header('Location: ../'.$ruta.'/formulariosCCAA_administracion_2.php?errorObservacion1=true');
        }else{

            $sqlUpdatePermisos = "UPDATE formularios_funcionarios SET permisosFormFuncCCAA = 1, fechaPermisoFormFuncCCAA = '".$now."' WHERE idFormFuncCCAA = ".$idFormFuncCCAA;

            $resUpdatePermisos = mysqli_query($conexion,$sqlUpdatePermisos);
            if($resUpdatePermisos){

                $nowFormat = date('d-m-Y H:i', strtotime($now));
                $observacion = $nomCreador2." realiza Entrega de Permisos a Funcionario con Fecha: ".$nowFormat;

                $sql = "INSERT INTO formularios_observaciones (idObservacion,detalleObs,fechaObs,creador,idFormRegAcceso,idFormFuncCCAA,folioFormRegAcceso) VALUES (NULL,'".$detalleObs."','".$now."','".$nomCreador2."',$idFormRegAcceso,$idFormFuncCCAA,'".$folioFormRegAcceso."')";
                $res = mysqli_query($conexion,$sql);

                if($res){
                    header('Location: ../'.$ruta.'/formulariosCCAA_administracion_2.php?exitoObservacion=true');
                }else{
                    header('Location: ../'.$ruta.'/formulariosCCAA_administracion_2.php?entregadoSinObservacion=true');
                }
            }else{
                header('Location: ../'.$ruta.'/formulariosCCAA_administracion_2.php?errorObservacion2=true');
            }
        }
    }else{

        header('Location: ../'.$ruta.'/formulariosCCAA_administracion_2.php?errorObservacion2=true');
    }

}else{
    header('Location: ../'.$ruta.'/formulariosCCAA_administracion_2.php?errorObservacion3=true');
}