<?php
// bitacora_modificar_guardar.php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

header('Content-Type: application/json');
date_default_timezone_set('America/Santiago');

// Validar que los datos estén presentes
if (!isset($_POST['idBitacora'], $_POST['categoria'], $_POST['observacion'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Faltan datos obligatorios']);
    exit;
}

// Sanitizar datos
$idBitacora     = intval($_POST['idBitacora']);
$idSubcategoria = intval($_POST['categoria']);
$observacion    = trim($_POST['observacion']);

// Validaciones básicas
if ($idBitacora <= 0 || $idSubcategoria <= 0 || empty($observacion)) {
    http_response_code(422);
    echo json_encode(['error' => 'Datos inválidos']);
    exit;
}

try {
    // Preparar consulta
    $sql = "UPDATE bitacora 
            SET idSubcategoria = ?, 
                observacionBitacora = ?
            WHERE idBitacora = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("isi", $idSubcategoria, $observacion, $idBitacora);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'mensaje' => 'Bitácora actualizada correctamente']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Error al actualizar: ' . $stmt->error]);
    }

    $stmt->close();
    $conexion->close();

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Excepción: ' . $e->getMessage()]);
}
