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
    $sql = "SELECT * FROM historial_kilometraje_moviles WHERE idKilometraje  = $id";
    $res = mysqli_query($conexion, $sql);

    while($row = $res->fetch_assoc()){
        $datos = array();
        $datos['fechaRegistro'] = $row['fechaRegistro'];
        $datos['cantKilometraje'] = $row['cantKilometraje'];
        $datos['estadoVehiculo'] = $row['estadoVehiculo'];
    }


    echo json_encode($datos);

}