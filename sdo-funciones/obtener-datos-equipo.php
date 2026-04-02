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
    $sql = "SELECT * FROM equipamiento_hbqp WHERE id  = $id";
    $res = mysqli_query($conexion, $sql);

    while($row = $res->fetch_assoc()){
        $datos = array();
        $datos['id'] = $row['id'];
        $datos['nombreEquipo'] = $row['nombreEquipo'];
        $datos['marca'] = $row['marca'];
        $datos['modelo'] = $row['modelo'];
        $datos['numeroInventario'] = $row['numeroInventario'];
        $datos['numeroSerie'] = $row['numeroSerie'];
        $datos['idCategoria'] = $row['idCategoria'];
        $datos['idServicio'] = $row['idServicio'];
        $datos['idDepaPertenece'] = $row['idDepaPertenece'];
        $datos['idCategoria'] = $row['idCategoria'];
        $datos['piso'] = $row['piso'];

    }

    echo json_encode($datos);

}