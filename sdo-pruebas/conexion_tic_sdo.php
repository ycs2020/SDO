<?php
// URL del JSON
$url = "https://sigh.hsmq.cl/sighbp/consulta_sdo.php";

// Obtener contenido del JSON desde la URL
$json = file_get_contents($url);

// Validar que se obtuvo correctamente
if ($json === false) {
    die("No se pudo obtener el JSON desde la URL.");
}

// Decodificar JSON a array asociativo
$data = json_decode($json, true);

// Validar que se decodificó correctamente
if (!isset($data['solicitudes']) || !is_array($data['solicitudes'])) {
    die("El formato del JSON no es válido o no contiene solicitudes.");
}

// Inicializar array para clasificar etapas
$clasificacion_etapas = [];

// Recorrer solicitudes y contar las etapas
foreach ($data['solicitudes'] as $solicitud) {
    $etapa = $solicitud['etapa_actual'];

    if (!isset($clasificacion_etapas[$etapa])) {
        $clasificacion_etapas[$etapa] = 0;
    }
    $clasificacion_etapas[$etapa]++;
}

// Ordenar alfabéticamente por etapa
ksort($clasificacion_etapas);

// Mostrar resultado
echo "<h3>Clasificación por etapa_actual</h3>";
echo "<ul>";
foreach ($clasificacion_etapas as $etapa => $cantidad) {
    echo "<li><strong>$etapa</strong>: $cantidad solicitud(es)</li>";
}
echo "</ul>";
?>
