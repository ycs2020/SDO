<?php
header('Content-Type: application/json');
$remote_url = 'https://sigh.hsmq.cl/sighbp/consulta_sdo.php';
$correlativo_buscado = $_GET['correlativo'] ?? null;

if (!$correlativo_buscado) {
    echo json_encode(["success" => false, "error" => "Falta el parámetro 'correlativo'"]);
    exit;
}

// cURL
$ch = curl_init($remote_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
$solicitudes = $data['solicitudes'] ?? [];

foreach ($solicitudes as $s) {
    // Comparación exacta del correlativo
    if ($s['correlativo'] === $correlativo_buscado) {
        echo json_encode([
            "success" => true,
            "correlativo" => $s['correlativo'],
            "id_solicitud" => $s['id_solicitud'],
            "nombre_solicitante" => $s['nombre_solicitante'],
            "unidad_solicitante" => $s['unidad_solicitante'],
            "etapa_actual" => $s['etapa_actual']
        ]);
        exit;
    }
}

echo json_encode(["success" => false, "error" => "No se encontró correlativo exacto: $correlativo_buscado"]);
