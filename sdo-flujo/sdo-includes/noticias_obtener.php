<?php
$url = 'https://www.cnnchile.com/rss.xml';

// Inicializar cURL
$ch = curl_init($url);

// Configurar opciones de cURL
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Ejecutar la solicitud
$contenido = curl_exec($ch);

// Verificar errores
if ($contenido === FALSE) {
    echo 'Error en cURL: ' . curl_error($ch);
} else {
    echo $contenido;
}

// Cerrar cURL
curl_close($ch);
