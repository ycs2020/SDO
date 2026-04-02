<?php

include 'conexion.php';

if(isset($_POST['id'])){

    $id = $_POST['id'];

    $sql = "DELETE FROM historial_petroleo_moviles WHERE idPetroleo  = $id ";
    $res = mysqli_query($conexion, $sql);

}