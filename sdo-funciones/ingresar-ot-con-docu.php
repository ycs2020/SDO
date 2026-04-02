<?php

include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');


$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = trim($_SESSION['nomUsuario']);
$userUsuario = $_SESSION['userUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);

$rutaDireccion = '';

if ($rolUsuario2 == "personal") {
    $rutaDireccion  = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $rutaDireccion = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $rutaDireccion = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $rutaDireccion = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $rutaDireccion = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $rutaDireccion = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if(isset($_POST['idArchivoDocumento'])){
    $opcion = $_POST['opcion'];
    $idArchivoDocumento = $_POST['idArchivoDocumento'];
    $now = date('Y-m-d H:i:s');
    $textareabms = trim($_POST['textareabms']);
    $textareacctv = trim($_POST['textareacctv']);
    $textarealle = trim($_POST['textarealle']);
    $textareaca = trim($_POST['textareaca']);
    $textareaotros = trim($_POST['textareaotros']);
    $selectTurno = $_POST['selectTurno'];
    $fechaProgramada = $_POST['fechaProgramada'];
    $selectPersonal = $_POST['selectPersonal'];
    $idRecepcion = $_POST['idRecepcion'];
    $idDepartamento = 1;

    if($opcion == 1){
        $textareacctv = '';
        $textarealle = '';
        $textareaca = '';
        $textareaotros = '';
    }else if($opcion == 2){
        $textareabms = '';
        $textarealle = '';
        $textareaca = '';
        $textareaotros = '';
    }else if($opcion == 3){
        $textareaca = '';
        $textareaotros = '';
        $textareabms = '';
        $textareacctv = '';
    }else if($opcion == 4){
        $textareaotros = '';
        $textareabms = '';
        $textareacctv = '';
        $textarealle = '';
    }else{
        $textareabms = '';
        $textareacctv = '';
        $textarealle = '';
        $textareaca = '';
    }

    $sql = "INSERT INTO `trabajos_general`(`idTG`, `folioTG`, `solicitanteTG`, `detalleTG`, `detalleTurnoCamara`, `detalleTurnoLLE`, `detalleTurnoCA`, `detalleTurnoRondas`, `detalleOtros`, `fechaTG`, `estadoTG`, `turnoTG`, `tecnico1`, `fechaProgramacion`, `ticketAsociado`, `tipoTrabajo`, `departamento`,idRecepcionDouc) 
    VALUES (NULL,'HBQP','".$nomUsuario."','".$textareabms."','".$textareacctv."','".$textarealle."','".$textareaca."','','".$textareaotros."','".$now."','Trabajo Ingresado','".$selectTurno."','".$selectPersonal."','".$fechaProgramada."',0,'',$idDepartamento,$idRecepcion)";
    $res = mysqli_query($conexion,$sql);

    if($res){
        $idTG = mysqli_insert_id($conexion);

        if ($idTG < 10) {
            $idRecepcion2 = "000" . $idTG;
        } else if ($idTG > 9 && $idTG < 100) {
            $idRecepcion2 = "00" . $idTG;
        } else if ($idTG > 99 && $idTG < 1000) {
            $idRecepcion2 = "0" . $idTG;
        } else if ($idTG > 999) {
            $idRecepcion2 = $idTG;
        } else {
            $idRecepcion2 = $idTG;
        }

        if($idDepartamento == 1){
            $str = 'I';
        }else if($idDepartamento == 2){
            $str = 'EI';
        }else if($idDepartamento == 3){
            $str = 'CLIMA';
        }else if($idDepartamento == 4){
            $str = 'E';
        }else if($idDepartamento == 5){
            $str = 'INFRA';
        }else if($idDepartamento == 6){
            $str = 'EQMED';
        }else if($idDepartamento == 7){
            $str = 'LOG';
        }else if($idDepartamento == 8){
            $str = 'MOV';
        }else if($idDepartamento == 10){
            $str = 'EDAC';
        }else if($idDepartamento == 12){
            $str = 'INCEDIO';
        }else{
            $str = 'SDO';
        }

        $folioTG = 'OT' .'-'.$str.'-'. $idRecepcion2;

        $sqlUpdate = "UPDATE trabajos_general SET folioTG = '$folioTG' WHERE idTG = $idTG";
        $resUpdate = mysqli_query($conexion, $sqlUpdate);

        $detalleObs = 'Se ha ingresado la orden de trabajo '.$folioTG.' y se ha asignado a '.$selectPersonal.' para realizar el trabajo';

        $sqlAvance = "INSERT INTO linea_vida_ot (idLineaVida,nomCreador,detalle,fechaSubida,idTG)
        VALUES (NULL,'".$nomUsuario."','".$detalleObs."','".$now."',$idTG)";
        $resAvance = mysqli_query($conexion,$sqlAvance);

        $sqlUpdate2 = "UPDATE archivos_recepcion_documento SET idTG = $idTG WHERE idArchivosDocu = $idArchivoDocumento";
        $resUpdate2 = mysqli_query($conexion,$sqlUpdate2);

        if($resUpdate2){
            header('Location: ../' . $rutaDireccion . '/trabajos_general_listado.php?exitoEnviarOT=true');
        }else{
            header('Location: ../' . $rutaDireccion . '/listar_recepcion_documento.php?errorEnviarOT=true');
        }
    }else{
        header('Location: ../' . $rutaDireccion . '/listar_recepcion_documento.php?errorEnviarOT=true');
    }

}else{
    header('Location: ../' . $rutaDireccion . '/listar_recepcion_documento.php?errorEnviarOT=true');
}