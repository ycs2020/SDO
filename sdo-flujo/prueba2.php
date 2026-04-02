<?php
// Inicializa una sesión cURL
$curl = curl_init();

// Establece las opciones de cURL
curl_setopt_array($curl, [
    CURLOPT_URL => 'https://api.gael.cloud/general/public/previred/082024',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Accept: application/json'
    ]
]);

// Ejecuta la solicitud y obtiene la respuesta
$response = curl_exec($curl);

// Verifica si hubo algún error
if (curl_errno($curl)) {
    echo 'Error en cURL: ' . curl_error($curl);
} else {
    // Decodifica la respuesta JSON
    $data = json_decode($response, true);
    print_r($data);  // Muestra la respuesta
}

// Cierra la sesión cURL
curl_close($curl);
?>
