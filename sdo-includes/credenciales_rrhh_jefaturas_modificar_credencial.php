<?php
require_once '../sdo-funciones/conexion.php';

header('Content-Type: application/json');

if (!isset($_POST['idUnidad']) || !is_numeric($_POST['idUnidad'])) {
    echo json_encode([]);
    exit;
}

$idUnidad = intval($_POST['idUnidad']);
$jefaturas = [];

$sql = "SELECT idJefatura, nomJefatura FROM credenciales_rrhh_jefaturas WHERE idUnidad = ? ORDER BY nomJefatura ASC";
$stmt = $conexion->prepare($sql);

if ($stmt) {
    $stmt->bind_param('i', $idUnidad);
    $stmt->execute();
    $resultado = $stmt->get_result();

    while ($row = $resultado->fetch_assoc()) {
        $jefaturas[] = [
            'idJefatura' => $row['idJefatura'],
            'nomJefatura' => mb_convert_case($row['nomJefatura'], MB_CASE_TITLE, "UTF-8")
        ];
    }

    $stmt->close();
}

$conexion->close();
echo json_encode($jefaturas);
