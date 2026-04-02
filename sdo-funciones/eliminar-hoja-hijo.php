<?php 


include 'conexion.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['id'])){

        $idEquipo = $_POST['id'];

        $sql = "UPDATE equipamiento_hbqp  SET idEquipoPadre = NULL WHERE id = '$idEquipo'";
        $resSQL = mysqli_query($conexion, $sql);
    }
}