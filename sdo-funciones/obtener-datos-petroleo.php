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
    $sql = "SELECT * FROM historial_petroleo_moviles WHERE idPetroleo    = $id";
    $res = mysqli_query($conexion, $sql);

    while($row = $res->fetch_assoc()){
        $datos = array();
        $datos['fechaRegistro'] = $row['fechaRegistro'];
        $datos['numGuia'] = $row['numGuia'];
        $datos['cantidadLitros'] = $row['cantidadLitros'];
        $datos['valorUnitario'] = $row['valorUnitario'];

    }


    echo json_encode($datos);

}