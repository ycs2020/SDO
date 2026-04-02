<?php

include 'conexion.php';

if(isset($_POST['id'])){

    $id = $_POST['id'];

    $sql = "DELETE FROM historial_kilometraje_moviles WHERE idKilometraje = $id ";
    $res = mysqli_query($conexion, $sql);

}