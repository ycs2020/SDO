<?php
include_once '../../sdo-funciones/conexion.php';
header('Content-Type: application/json');

// Recibir datos del AJAX
$idPetroleo     = $_POST['idPetroleo'] ?? null;
$fecha          = $_POST['fechaReporte'] ?? null;
$numGuia        = $_POST['numGuia'] ?? '';
$litros         = (float)($_POST['cantidadLitros'] ?? 0);
$valorUnitario  = (float)($_POST['valorUnitario'] ?? 0); // Precio Neto (Sin IVA)

if (!$idPetroleo || $litros <= 0 || $valorUnitario <= 0) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos o los valores son 0.']);
    exit;
}

// --- LÓGICA DE RECALCULO ---
// 1. Calcular el total neto (Sin IVA)
$totalSinIva  = round($litros * $valorUnitario);

// 2. Calcular el IVA (19% en Chile)
$ivaCalculado = round($totalSinIva * 0.19);

// 3. Calcular el total final (Con IVA)
$totalConIva  = $totalSinIva + $ivaCalculado;

// Preparar la actualización de TODAS las columnas afectadas
$sql = "UPDATE historial_petroleo_moviles SET 
            fechaRegistro = ?, 
            numGuia = ?, 
            cantidadLitros = ?, 
            valorUnitario = ?, 
            totalSinIva = ?, 
            IvaCalculado = ?, 
            totalConIva = ? 
        WHERE idPetroleo = ?";

$stmt = $conexion->prepare($sql);

// s = string, d = double/float, i = integer
$stmt->bind_param('ssddiiii',
    $fecha,
    $numGuia,
    $litros,
    $valorUnitario,
    $totalSinIva,
    $ivaCalculado,
    $totalConIva,
    $idPetroleo
);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Actualizado con éxito.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al guardar: ' . $conexion->error]);
}

$stmt->close();
$conexion->close();