<?php
include 'conexion.php';
include 'funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(isset($_POST['id'])){

    $id = $_POST['id'];

    //Consultamos los datos del ticket
    $sql = "SELECT * FROM archivos_equipamiento_hbqp WHERE id  = $id";
    $res = mysqli_query($conexion, $sql);

    while($row = $res->fetch_assoc()){
        $datos = array();
        $datos['fecha'] = $row['fecha'];
        $datos['nomArchivo'] = $row['nomArchivo'];
        $datos['rutaArchivo'] = $row['rutaArchivo'];
        $datos['tipoArchivo'] = $row['tipoArchivo'];
    }


    echo json_encode($datos);

}