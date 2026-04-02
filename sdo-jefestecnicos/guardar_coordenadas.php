<?php
include '../sdo-funciones/conexion.php';

$nombre = $_POST['nombre'];
$x = $_POST['x'];
$y = $_POST['y'];

if (empty($nombre) || !is_numeric($x) || !is_numeric($y)) {
    echo "Datos inválidos.";
    exit;
}

$conexion->query("INSERT INTO vavs (nombre, x, y) VALUES ('$nombre', $x, $y)");
$conexion->close();
echo "Guardado correctamente";
?>
