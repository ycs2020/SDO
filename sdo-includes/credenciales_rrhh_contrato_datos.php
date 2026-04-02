<?php
require '../sdo-funciones/conexion.php';
require '../sdo-funciones/funciones.php';

header('Content-Type: application/json');

$idCredencial = isset($_POST['idCredencialContratos']) ? (int) $_POST['idCredencialContratos'] : 0;

if (!$idCredencial) {
    echo json_encode([]);
    exit;
}

$sql = "
    SELECT 
        c.idContrato,
        cj.nomCalidad AS calidadContrato,
        cr.nomCargo AS cargoContrato,
        u.nomUnidad AS unidadContrato,
        j.nomJefatura AS jefaturaContrato,
        c.inicioContrato,
        c.terminoContrato
    FROM credenciales_rrhh_contratos c
    LEFT JOIN credenciales_rrhh_calidades cj ON c.idCalidad = cj.idCalidad
    LEFT JOIN credenciales_rrhh_cargos cr ON c.idCargo = cr.idCargo
    LEFT JOIN credenciales_rrhh_unidades u ON c.idUnidad = u.idUnidad
    LEFT JOIN credenciales_rrhh_jefaturas j ON c.idJefatura = j.idJefatura
    WHERE c.idCredencial = ?
    ORDER BY c.inicioContrato DESC
";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idCredencial);
$stmt->execute();
$resultado = $stmt->get_result();

$datos = [];
while ($row = $resultado->fetch_assoc()) {
    $row['inicioContrato2']  = $row['inicioContrato'] ? date('d-m-Y', strtotime($row['inicioContrato'])) : '—';
    $row['terminoContrato2'] = $row['terminoContrato'] ? date('d-m-Y', strtotime($row['terminoContrato'])) : '—';
    $datos[] = $row;
}

$stmt->close();
echo json_encode($datos);
