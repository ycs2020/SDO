<?php

include 'conexion.php';
include 'funciones.php';


if($_POST['id']){

    $id = $_POST['id'];

    $sqlDelete = "DELETE FROM viajes_movilizacion_vamoos_spa WHERE idViaje  = $id";
    $resDelete = mysqli_query($conexion, $sqlDelete);


}