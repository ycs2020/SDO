<?php
$camera_ip = '10.10.115.16';
$username  = 'admin';
$password  = 'quillota123';

$url = "http://{$camera_ip}/cgi-bin/eventManager.cgi?action=attach&codes=[TrafficVehicle]";

echo "=== ESCUCHANDO CON FILTRO ULTRA-FLEXIBLE ===" . PHP_EOL;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
curl_setopt($ch, CURLOPT_USERPWD, "{$username}:{$password}");
curl_setopt($ch, CURLOPT_TIMEOUT, 0);
curl_setopt($ch, CURLOPT_BUFFERSIZE, 2048);

$buffer = "";

curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($ch, $data) use (&$buffer) {
    $buffer .= $data;

    // Si encontramos un quiebre de línea, procesamos el fragmento
    if (strpos($buffer, "\n") !== false) {

        // Buscamos "plate" sin importar si viene como PlateNumber, platenumber o Plate
        if (preg_match('/plate(?:number)?\s*=\s*([A-Z0-9\-]+)/i', $buffer, $matches)) {
            $plate = trim($matches[1]);

            if (!empty($plate) && strtolower($plate) !== 'unknown') {
                echo "[" . date('H:i:s') . "] 🚗 ¡PATENTE CAPTURADA!: " . strtoupper($plate) . PHP_EOL;
                ob_flush();
                flush();
            }
        }

        // Mantener el buffer limpio y corto para evitar que la cámara se sature y nos bote
        if (strlen($buffer) > 5000) {
            $buffer = "";
        }
    }
    return strlen($data);
});

curl_exec($ch);

if (curl_errno($ch)) {
    echo "❌ Conexión cerrada por la cámara: " . curl_error($ch) . PHP_EOL;
}

curl_close($ch);
?>