<?php
require '../sdo-funciones/conexion.php';
header('Content-Type: application/json');

$datos = [];
$sql = "SELECT idCalidad AS id, UPPER(nomCalidad) AS text FROM credenciales_rrhh_calidades WHERE estado = 1 ORDER BY nomCalidad ASC";
$resultado = $conexion->query($sql);

while ($row = $resultado->fetch_assoc()) {
    $datos[] = $row;
}
echo json_encode($datos);
