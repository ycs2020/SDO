<?php
function obtenerOrdenCompra($numeroOC) {
    $apikey = "5A98F1FA-936D-420D-B68A-7EA5E580D931";
    $urlAPI = "https://api.mercadopublico.cl/servicios/v1/publico/ordenesdecompra.json?codigo={$numeroOC}&ticket={$apikey}";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $urlAPI);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) {
        return null; // error de conexión
    }

    // Devuelve array asociativo
    return json_decode($response, true);
}

// 🔹 Llamada a la función
$datosAPI = obtenerOrdenCompra("1704-1796-TD25");

// 👀 Mostrar datos (debug)
if ($datosAPI) {
    echo "<pre>";
    print_r($datosAPI);
    echo "</pre>";
} else {
    echo "No se pudo obtener la orden de compra.";
}
