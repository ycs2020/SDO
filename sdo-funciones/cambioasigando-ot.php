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
$rutaDireccion = "";

if ($rolUsuario2 == "personal") {
    $rutaDireccion  = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $rutaDireccion  = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $rutaDireccion  = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $rutaDireccion  = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $rutaDireccion  = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $rutaDireccion  = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if(isset($_POST['idTG'],$_POST['nomCreador'],$_POST['selectPersona'],$_POST['detalleObs'])) {
    $idTG = $_POST['idTG'];
    $nomCreador = $_POST['nomCreador'];
    $selectPersona = $_POST['selectPersona'];

    $detalleObs = $_POST['detalleObs'];
    $now = date('Y-m-d H:i:s');
    $detalle = 'Se ha derivado el funcionario asignado por las siguientes razones: '.$detalleObs;
    $selectturno = $_POST['selectturno'];

    /*INSERT INTO `linea_vida_ot`(`idLineaVida`, `nomCreador`, `detalle`, `fechaSubida`, `isMaterialSoliCompra`, `isPanol`, `respuestaPanol`, `respuestaSoli`, `rechazadoPanol`, `fechaAceptadoPanol`, `numSoliCompra`, `idTG`)
    VALUES (NULL,'".$nomCreador."','".$detalle."','".$now."',0,0,0,0,0,'0000-00-00 00:00:00','0',$idTG)*/
    /*
     INSERT INTO avances_trabajo_general (idAvance,nomCreador,detalleObs,estadoAvance,fechaSubida,idTG,personaDerivada)
    VALUES (NULL,'".$nomCreador."','".$detalleObs."','Derivado','".$now."',$idTG,'".$selectPersona."')*/

    $sql = "INSERT INTO `linea_vida_ot`(`idLineaVida`, `nomCreador`, `detalle`, `fechaSubida`, `isMaterialSoliCompra`, `isPanol`, `respuestaPanol`, `respuestaSoli`, `rechazadoPanol`, `fechaAceptadoPanol`, `numSoliCompra`, `idTG`)
    VALUES (NULL,'".$nomCreador."','".$detalle."','".$now."',0,0,0,0,0,'0000-00-00 00:00:00','0',$idTG)";
    $res = mysqli_query($conexion,$sql);

    if($res){
        $sqlUpdate = "UPDATE trabajos_general SET estadoTG = 'Derivado', tecnico1 = '$selectPersona', turnoTG = '$selectturno' WHERE idTG = $idTG";
        $resUpdate = mysqli_query($conexion,$sqlUpdate);

        if($resUpdate){
            header('Location: ../' . $rutaDireccion . '/trabajos_general_listado.php?exitoDerivado=true');
        }else{
            header('Location: ../' . $rutaDireccion . '/trabajos_general_listado.php?errorDerivado=true');
        }
    }else{
        header('Location: ../' . $rutaDireccion . '/trabajos_general_listado.php?errorDerivado=true');
    }
}else{
    header('Location: ../' . $rutaDireccion . '/trabajos_general_listado.php?errorDerivado=true');
}