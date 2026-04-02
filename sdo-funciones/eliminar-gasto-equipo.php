<?php

include 'conexion.php';
// echo '';

if(isset($_POST['id'])){

    $id = $_POST['id'];

    $sql = "DELETE FROM gasto_equipo_carpeta  WHERE idGasto  = $id";
    $res = mysqli_query($conexion, $sql);
}