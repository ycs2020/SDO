<?php
// REEMPLAZA con tu API Key de Tomorrow.io
$apikey = 'Q4KG2kqXK8pK2rCEisVz4gbPIE7l30Wg';
// Ubicación: Quillota, Chile (lat,lon)
$location = '-32.8801,-71.2472';
$url = "https://api.tomorrow.io/v4/weather/realtime?location=" . urlencode($location) . "&apikey={$apikey}&units=metric";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
$response = curl_exec($ch);
if (curl_errno($ch)) {
    $error = curl_error($ch);
}
curl_close($ch);

$data = json_decode($response, true);
$currentTemp = null;
if (isset($data['data']['value']) && isset($data['data']['value']['temperature'])) {
    $currentTemp = $data['data']['value']['temperature'];
} elseif (isset($data['data']['timelines'][0]['intervals'][0]['values']['temperature'])) {
    // Si usas /timelines
    $currentTemp = $data['data']['timelines'][0]['intervals'][0]['values']['temperature'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Temperatura en Quillota</title>
</head>
<body>
<h1>Temperatura actual en Quillota</h1>
<?php if ($currentTemp !== null): ?>
    <p><strong><?php echo round($currentTemp, 1); ?> °C</strong></p>
<?php else: ?>
    <p style="color: red;">No se pudo obtener la temperatura.</p>
    <?php if (!empty($url)): ?>
        <p>Error: <?php echo htmlspecialchars($url); ?></p>
    <?php endif; ?>
<?php endif; ?>
</body>
</html>
