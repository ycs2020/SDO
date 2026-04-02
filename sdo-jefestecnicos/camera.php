<?php

// Configuración de la cámara Dahua
$camaraIp = ' ';
$usuario = 'tu_usuario';
$clave = 'tu_clave';

// URL de la API de Dahua para obtener una instantánea
$urlApi = "http://$camaraIp/cgi-bin/snapshot.cgi";

// Configurar la solicitud cURL
$ch = curl_init($urlApi);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, "$usuario:$clave");

// Ejecutar la solicitud cURL
$response = curl_exec($ch);

// Verificar errores
if (curl_errno($ch)) {
    echo 'Error al acceder a la cámara Dahua: ' . curl_error($ch);
} else {
    // Procesar la respuesta (puedes guardar la instantánea en un archivo o mostrarla en el navegador)
    header('Content-Type: image/jpeg');
    echo $response;
}

// Cerrar la sesión cURL
curl_close($ch);

?>