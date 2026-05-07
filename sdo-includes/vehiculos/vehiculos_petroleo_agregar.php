<?php
include_once '../../sdo-funciones/conexion.php';

header('Content-Type: application/json');

try {
    $idMovil       = $_POST['idMovil'];
    // IMPORTANTE: Reemplazamos la 'T' de datetime-local por un espacio para SQL
    $fechaRaw      = $_POST['fechaReporte'];
    $fechaRegistro = str_replace('T', ' ', $fechaRaw);

    $numGuia       = $_POST['numGuia'];
    $cantidadLitros = $_POST['litros'];
    $valorUnitario = $_POST['valorUnitario'];

    $totalSinIva   = round($cantidadLitros * $valorUnitario);
    $ivaCalculado  = round($totalSinIva * 0.19);
    $totalConIva   = $totalSinIva + $ivaCalculado;

    $nomConductor  = !empty($_POST['nomConductor']) ? $_POST['nomConductor'] : 'Sin Información';

// ... actualizar SQL
    $sql = "INSERT INTO historial_petroleo_moviles 
        (fechaRegistro, nomConductor, numGuia, cantidadLitros, valorUnitario, totalSinIva, IvaCalculado, totalConIva, idMovil) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql);

// Tipos: s=string, d=double, i=int. Ahora hay 9 parámetros.
    $stmt->bind_param('sssddiiii',
        $fechaRegistro,
        $nomConductor,  // Nuevo
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