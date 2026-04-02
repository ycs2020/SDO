<?php
require '../sdo-funciones/conexion.php';
$codigo = trim($_POST['codigoTarjeta']);

$query = "SELECT * FROM credenciales_rrhh_tarjetas WHERE codigoTarjeta = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param('s', $codigo);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        'existe' => true,
        'tipoTarjeta' => $row['tipoTarjeta'],
        'unidadTarjeta' => $row['unidadTarjeta'],
        'textoTarjeta' => $row['textoTarjeta']
    ]);
} else {
    echo json_encode(['existe' => false]);
}
