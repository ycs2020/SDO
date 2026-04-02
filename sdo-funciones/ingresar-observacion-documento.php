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
    $selectEstado = $_POST['selectEstado'];
    $detalleObs = $_POST['detalleObs'];
    $fechaObs = date('Y-m-d H:i:s');

    $sql = "INSERT INTO recepcion_observacion (idObsRecepcion,detalleObservacion,fechaObs,nomCreador,idRecepcion,estado)
    VALUES (NULL,'".$detalleObs."','".$fechaObs."','".$nomCreador."','".$idRecepcion."','".$selectEstado."')";
    $res = mysqli_query($conexion,$sql);

    if($res){

        $sqlEstado = "UPDATE recepcion_documento SET estado = '$selectEstado' WHERE idRecepcion = $idRecepcion";
        $resEstado = mysqli_query($conexion,$sqlEstado);

        if($resEstado){
            header('Location: ../'.$ruta.'/listar_recepcion_documento.php?exitoFinDelTrabajo=true');
        }else{
            header('Location: ../'.$ruta.'/listar_recepcion_documento.php?errorFinDelTrabajo=true');
        }

    }else{
        header('Location: ../'.$ruta.'/listar_recepcion_documento.php?errorFinDelTrabajo=true');
    }

}else{
    header('Location: ../'.$ruta.'/listar_recepcion_documento.php?errorFinDelTrabajo=true');
}
