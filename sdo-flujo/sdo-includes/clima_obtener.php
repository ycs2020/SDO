<?php
// URL del API
$url = 'https://api.gael.cloud/general/public/clima/SCVM';

// Iniciar cURL
$ch = curl_init($url);

// Establecer opciones de cURL
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Accept: application/json'
));

// Ejecutar cURL
$response = curl_exec($ch);

// Verificar si hubo errores
if ($response === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener los datos del clima.']);
    exit;
}

// Cerrar cURL
curl_close($ch);

// Enviar respuesta en formato JSON
header('Content-Type: application/json');
echo $response;
?>
