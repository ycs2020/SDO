<?php

include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = trim($_SESSION['nomUsuario']);
$userUsuario = $_SESSION['userUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);

$ruta = '';

if ($rolUsuario2 == "personal") {
    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $ruta = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $ruta = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $ruta = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if(isset($_POST['idRecepcion'])){
    $idRecepcion = $_POST['idRecepcion'];
    $nomCreador = $_POST['nomCreador'];
    $selectPersona = $_POST['selectPersona'];
    $detalleObs = $_POST['detalleObs'];

    $now = date('Y-m-d H:i:s');

    $sqlObs = "INSERT INTO recepcion_observacion (idObsRecepcion,detalleObservacion,fechaObs,nomCreador,idRecepcion,estado)
    VALUES (NULL,'".$detalleObs."','".$now."','".$nomCreador."',$idRecepcion,'Derivado')";
    $resObs = mysqli_query($conexion,$sqlObs);

    $sqlUpdate = "UPDATE recepcion_documento SET asignadoRecepcion = '$selectPersona', estado = 'Derivado' WHERE idRecepcion = $idRecepcion";
    $resUpdate = mysqli_query($conexion,$sqlUpdate);



    if($resUpdate){
        header('Location: ../'.$ruta.'/listar_recepcion_documento.php?exitoDerivacion=true');
    }else{
        header('Location: ../'.$ruta.'/listar_recepcion_documento.php?errorDerivacion=true');
    }


}

