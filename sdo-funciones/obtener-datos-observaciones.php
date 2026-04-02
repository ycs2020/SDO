<?php
include 'conexion.php';
include 'funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(isset($_POST['id'])) {
    $id = $_POST['id'];

    $datos = array(); // Array para almacenar datos

    // Obtener observaciones
    $sql = "SELECT * FROM recepcion_observacion WHERE idRecepcion  = $id AND estado = 'Derivado'";
    $res = mysqli_query($conexion, $sql);

    while($row = $res->fetch_assoc()){
        $observacion = array();
        $observacion['detalleObservacion'] = $row['detalleObservacion'];
        $datos['observaciones'][] = $observacion;
    }

    /*
    $sql1 = "SELECT * FROM recepcion_documento WHERE idRecepcion = $id";
    $res1 = mysqli_query($conexion, $sql1);

    while($row1 = $res1->fetch_assoc()){
        $documento = array();
        $documento['asignadoRecepcion'] = $row1['asignadoRecepcion'];
        $datos['documentos'][] = $documento;
    }
    */
    echo json_encode($datos);
}