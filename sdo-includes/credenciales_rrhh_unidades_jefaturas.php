<?php
header('Content-Type: application/json');
include "../sdo-funciones/conexion.php";

$idUnidad = (int)($_POST['unidad'] ?? 0);

if ($idUnidad <= 0) {
    echo json_encode([]);
    exit;
}

$sql = $conexion->prepare("
    SELECT idJefatura AS id, nomJefatura AS nombre 
    FROM credenciales_rrhh_jefaturas 
    WHERE idUnidad = ? 
    ORDER BY nombre ASC
");
$sql->bind_param("i", $idUnidad);
$sql->execute();
$result = $sql->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);

$sql->close();
$conexion->close();
