<?php
include_once '../../sdo-funciones/conexion.php';

header('Content-Type: application/json');

try {
    // Recibir datos del formulario
    $idMovil       = $_POST['idMovil'];
    $fechaRegistro = $_POST['fechaReporte']; // Coincide con el name del input
    $numGuia       = $_POST['numGuia'];
    $cantidadLitros = $_POST['litros'];
    $valorUnitario = $_POST['valorUnitario']; // Valor Neto (sin IVA)

    // Cálculos según la estructura de tu tabla
    $totalSinIva   = round($cantidadLitros * $valorUnitario);
    $ivaCalculado  = round($totalSinIva * 0.19);
    $totalConIva   = $totalSinIva + $ivaCalculado;

    $sql = "INSERT INTO historial_petroleo_moviles 
            (fechaRegistro, numGuia, cantidadLitros, valorUnitario, totalSinIva, IvaCalculado, totalConIva, idMovil) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql);

    // Tipos: s (string), d (decimal/double), i (integer)
    $stmt->bind_param('ssddiiii',
        $fechaRegistro,
        $numGuia,
        $cantidadLitros,
        $valorUnitario,
        $totalSinIva,
        $ivaCalculado,
        $totalConIva,
        $idMovil
    );

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Carga de petróleo registrada con éxito.']);
    } else {
        throw new Exception($conexion->error);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

$stmt->close();
$conexion->close();