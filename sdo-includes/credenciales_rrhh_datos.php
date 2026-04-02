<?php
require '../sdo-funciones/conexion.php';
require '../sdo-funciones/funciones.php';

header('Content-Type: application/json');

$id = $_POST['idCredencial'] ?? null;

if (!$id || !is_numeric($id)) {
    echo json_encode(['status' => 'error', 'mensaje' => 'ID inválido']);
    exit;
}

$data = getDatosCredencialesRRHH($id);

if (!empty($data)) {
    echo json_encode(['status' => 'ok', 'data' => $data]);
} else {
    echo json_encode(['status' => 'error', 'mensaje' => 'Registro no encontrado']);
}
exit;
