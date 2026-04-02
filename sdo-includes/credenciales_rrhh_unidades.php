<?php
require '../sdo-funciones/conexion.php';
header('Content-Type: application/json');

$datos = [];
$query = "SELECT idUnidad, nomUnidad FROM credenciales_rrhh_unidades ORDER BY nomUnidad ASC";
$resultado = $conexion->query($query);

while ($row = $resultado->fetch_assoc()) {
    $datos[] = [
        "id" => $row['idUnidad'],
        "text" => strtoupper($row['nomUnidad'])
    ];
}

echo json_encode($datos);
