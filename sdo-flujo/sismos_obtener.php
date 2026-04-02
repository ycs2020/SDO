<?php
// Inicializar cURL
$ch = curl_init();

// Establecer la URL
curl_setopt($ch, CURLOPT_URL, 'https://api.gael.cloud/general/public/sismos');

// Establecer la opción para recibir la respuesta como string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Establecer la cabecera de la solicitud
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Accept: application/json'
));

// Ejecutar la solicitud y obtener la respuesta
$response = curl_exec($ch);

// Verificar si hubo algún error
if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
} else {
    // Decodificar la respuesta JSON
    $data = json_decode($response, true);

    // Devolver la respuesta en formato JSON
    header('Content-Type: application/json');
    echo json_encode($data);
}

// Cerrar cURL
curl_close($ch);
?>
