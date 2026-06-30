<?php
include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

header('Content-Type: application/json');
date_default_timezone_set('America/Santiago');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
    exit;
}

$idAnalizador = isset($_POST['idAnalizador']) ? (int)$_POST['idAnalizador'] : 0;
$nomSubAnalizador = isset($_POST['nomSubAnalizador']) ? trim($_POST['nomSubAnalizador']) : '';

if ($idAnalizador <= 0 || $nomSubAnalizador === '') {
    echo json_encode(['status' => 'error', 'message' => 'Parámetros obligatorios faltantes o inválidos.']);
    exit;
}

// Insertar de forma segura en tu tabla analizadores_red_sub
$queryInsert = "INSERT INTO analizadores_red_sub (idAnalizador, nomSubAnalizador) VALUES (?, ?)";

try {
    if ($stmt = $conexion->prepare($queryInsert)) {
        $stmt->bind_param("is", $idAnalizador, $nomSubAnalizador);

        if ($stmt->execute()) {
            echo json_encode([
                'status' => 'success',
                'message' => "El sub-analizador '{$nomSubAnalizador}' se ha configurado y vinculado de forma correcta."
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo ejecutar la inserción en la base de datos.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al preparar la consulta estructurada SQL.']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error en el motor SQL: ' . $e->getMessage()]);
}