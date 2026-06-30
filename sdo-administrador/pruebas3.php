<?php
$phone = '+56940748358';
$apikey = '7003377';
$message = 'Hola desde tu código 👋';

$params = http_build_query([
    'phone' => $phone,
    'text' => $message,
    'apikey' => $apikey
]);

$url = 'https://api.callmebot.com/whatsapp.php?' . $params;

// Inicializar cURL
$ch = curl_init();

// Configurar las opciones de cURL
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Para que devuelva el resultado como string

// Ejecutar la petición
$response = curl_exec($ch);

// Cerrar la conexión
curl_close($ch);

// Imprimir el resultado
echo $response;
?>