<?php


include 'conexion.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['id'])){

        $idEquipo = $_POST['id'];

        $sql = "UPDATE detalle_insumos_inge_clinica  SET habilitado = 1 WHERE id = '$idEquipo'";
        $resSQL = mysqli_query($conexion, $sql);
    }
}