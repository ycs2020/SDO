<?php
require '../sdo-funciones/conexion.php';
header('Content-Type: application/json');

$datos = [];
$sql = "SELECT idCargo AS id, UPPER(nomCargo) AS text FROM credenciales_rrhh_cargos WHERE estado = 1 ORDER BY nomCargo ASC";
$resultado = $conexion->query($sql);

while ($row = $resultado->fetch_assoc()) {
    $datos[] = $row;
}
echo json_encode($datos);
