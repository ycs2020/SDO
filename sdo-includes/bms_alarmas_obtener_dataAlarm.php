<?php
header('Content-Type: application/json; charset=utf-8');

include_once '../sdo-funciones/conexion.php';
date_default_timezone_set('America/Santiago');

function formatear_alarm_data_html(string $cadena): string {
    // Limpia espacios y posibles comas finales
    $cadena = trim($cadena, " \t\n\r\0\x0B,");
    if ($cadena === '') return '<em>Sin datos</em>';

    $pares = explode(',', $cadena);
    $data  = [];

    foreach ($pares as $par) {
        $partes = explode('=', $par, 2); // evita romper valores con "="
        $clave  = trim($partes[0] ?? '');
        $valor  = trim($partes[1] ?? '');
        if ($clave === '') continue;
        $data[$clave] = $valor;
    }

    if (empty($data)) return '<em>Sin datos</em>';

    // Orden alfabético por clave (natural, case-insensitive)
    ksort($data, SORT_NATURAL | SORT_FLAG_CASE);

    // Construye HTML con claves en negrita y mayúsculas
    $out = '';
    foreach ($data as $k => $v) {
//        $out .= '<div><b>' . strtoupper(htmlspecialchars($k, ENT_QUOTES, 'UTF-8')) . '</b>: ' .nl2br(htmlspecialchars($v, ENT_QUOTES, 'UTF-8')) . '</div>';
        $out .= '<div><b>' . htmlspecialchars($k, ENT_QUOTES, 'UTF-8') . '</b>: ' .nl2br(htmlspecialchars($v, ENT_QUOTES, 'UTF-8')) . '</div>';
    }
    return $out;
}

if (!isset($_POST['idHistorial']) || (int)$_POST['idHistorial'] <= 0) {
    echo json_encode(['success' => false, 'error' => 'idHistorial inválido']);
    exit;
}

$idHistorial = (int)$_POST['idHistorial'];

$sql = "SELECT alarmDataHistorial
        FROM bms_alarmas_historial
        WHERE idHistorial = ?
        LIMIT 1";

if (!$stmt = $conexion->prepare($sql)) {
    echo json_encode(['success' => false, 'error' => 'Error al preparar la consulta']);
    exit;
}

$stmt->bind_param("i", $idHistorial);
$stmt->execute();
$result = $stmt->get_result();
$row = $result ? $result->fetch_assoc() : null;

if ($row && isset($row['alarmDataHistorial'])) {
    $raw  = (string)$row['alarmDataHistorial'];
    $html = formatear_alarm_data_html($raw);

    echo json_encode([
        'success' => true,
        'idHistorial' => $idHistorial,
        'alarmDataHistorial_raw' => $raw,
        'alarmDataHistorial_html' => $html
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Sin resultados']);
}

$stmt->close();
$conexion->close();
