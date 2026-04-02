<?php

header('Content-Type: application/json; charset=utf-8');
require '../sdo-funciones/conexion.php';

if (!isset($_POST['ids']) || !is_array($_POST['ids'])) {
    echo json_encode(['success' => false, 'message' => 'IDs inválidos']);
    exit;
}

$ids = array_map('intval', $_POST['ids']);
$ids = array_values(array_unique(array_filter($ids)));

if (count($ids) === 0) {
    echo json_encode(['success' => false, 'message' => 'Sin IDs válidos']);
    exit;
}

$placeholders = implode(',', array_fill(0, count($ids), '?'));
$sql = "SELECT idHistorial, mensajeHistorial, alarmDataHistorial 
        FROM bms_alarmas_historial 
        WHERE idHistorial IN ($placeholders)";

$stmt = $conexion->prepare($sql);
$types = str_repeat('i', count($ids));
$stmt->bind_param($types, ...$ids);
$stmt->execute();
$res = $stmt->get_result();

$data = [];



function extraerClave($cadena, $clave) {
    if (preg_match('/' . preg_quote($clave, '/') . '=([^,]+)/', $cadena, $m)) {
        // Opción A: con etiqueta legible
        return '<b>Valor que originó alarma: ' . trim($m[1]).'</b>';
        // Opción B (solo valor): return trim($m[1]);
    }
    return '';
}

while ($row = $res->fetch_assoc()) {

    $alarmDataHistorial = $row['alarmDataHistorial'];
    $textoDataAlarma = extraerClave($alarmDataHistorial, 'alarmValue');



    $data[] = [
        'idHistorial' => (int)$row['idHistorial'],
        'mensajeHistorial' => $row['mensajeHistorial'],
        'textoDataAlarma' => $textoDataAlarma,
    ];
}

echo json_encode(['success' => true, 'data' => $data], JSON_UNESCAPED_UNICODE);
