<?php

include 'conexion.php';

$id = $_POST['id'];

$sqlBorrar = "DELETE FROM moviles_movilizacion  WHERE idMovil = $id";
$resBorrar = mysqli_query($conexion, $sqlBorrar);

echo $sqlBorrar;