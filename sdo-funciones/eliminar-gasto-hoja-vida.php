<?php 

include 'conexion.php';

if(isset($_POST["id"])){

    $id = $_POST['id'];

    $sql = "DELETE FROM gastoEquipos WHERE idGasto  = $id";
    $res = mysqli_query($conexion, $sql);
}