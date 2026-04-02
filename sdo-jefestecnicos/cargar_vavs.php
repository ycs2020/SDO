<?php
include '../sdo-funciones/conexion.php';

$resultado = $conexion->query("SELECT * FROM vavs");

while ($fila = $resultado->fetch_assoc()) {
    echo "<div class='vav-label' style='left: {$fila['x']}px; top: {$fila['y']}px;'>{$fila['nombre']}</div>";
}

$conexion->close();
?>
