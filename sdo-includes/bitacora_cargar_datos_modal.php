<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

$id = $_GET['idBitacora'] ?? null;

$response = [];

if (!$id || !is_numeric($id)) {
    http_response_code(400);
    echo json_encode(['error' => 'ID inválido']);
    exit;
}

if ($id) {
    $stmt = $conexion->prepare("
        SELECT b.idBitacora, b.observacionBitacora, b.fechaBitacora, b.tecnicoBitacora, 
               b.idSubcategoria, s.nomSubcategoria
        FROM bitacora b
        JOIN subcategorias s ON b.idSubcategoria = s.idSubcategoria
        WHERE b.idBitacora = ?
        LIMIT 1
    ");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $fechaTime = strtotime($row['fechaBitacora']);
        $row['fechaCompleta'] = $fechaTime ? fechaEspañol2($fechaTime) . ' a las ' . date('H:i', $fechaTime) . ' hrs.' : 'Error al Cargar Fecha';
        $response = $row;
    }
    $stmt->close();
}

$conexion->close();
header('Content-Type: application/json');
echo json_encode($response);
