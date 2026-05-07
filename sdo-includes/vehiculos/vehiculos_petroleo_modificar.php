<?php
include_once '../../sdo-funciones/conexion.php';
header('Content-Type: application/json');

$idPetroleo     = $_POST['idPetroleo'] ?? null;
$fechaRaw       = $_POST['fechaReporte'] ?? null;
$numGuia        = $_POST['numGuia'] ?? '';
$litros         = (float)($_POST['cantidadLitros'] ?? 0);
$valorUnitario  = (float)($_POST['valorUnitario'] ?? 0);

if (!$idPetroleo || $litros <= 0 || $valorUnitario <= 0) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos o los valores son 0.']);
    exit;
}

// Limpiar la fecha para formato MySQL
$fecha = str_replace('T', ' ', $fechaRaw);

// Recalculo
$totalSinIva  = round($litros * $valorUnitario);
$ivaCalculado = round($totalSinIva * 0.19);
$totalConIva  = $totalSinIva + $ivaCalculado;

// ... capturar el nuevo dato
$nomConductor = !empty($_POST['nomConductor']) ? $_POST['nomConductor'] : 'Sin Información';

// ... actualizar SQL
$sql = "UPDATE historial_petroleo_moviles SET 
            fechaRegistro = ?, 
            nomConductor = ?, 
            numGuia = ?, 
            cantidadLitros = ?, 
            valorUnitario = ?, 
            totalSinIva = ?, 
            IvaCalculado = ?, 
            totalConIva = ? 
        WHERE idPetroleo = ?";

$stmt = $conexion->prepare($sql);

// Ahora hay 9 parámetros (8 de datos + 1 de ID en el WHERE)
$stmt->bind_param('sssddiiii',
    $fecha,
    $nomConductor, // Nuevo
    $numGuia,
    $litros,
    $valorUnitario,
    $totalSinIva,
    $ivaCalculado,
    $totalConIva,
    $idPetroleo
);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Registro actualizado con éxito.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al guardar: ' . $conexion->error]);
}

$stmt->close();
$conexion->close();