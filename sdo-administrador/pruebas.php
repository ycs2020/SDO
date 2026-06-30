<?php
/**
 * SDO - Módulo de Inteligencia Artificial para Control de Combustible (Versión MySQLi)
 * Archivo: sdo_ia_combustible.php
 * Propósito: Análisis de consumo e interanual evitando inyección SQL usando MySQLi.
 */

// 1. Configuración de Errores y Entorno
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2. Llamada obligatoria a tu archivo centralizado de conexión de SDO
require_once "../sdo-funciones/conexion.php";

// Definición de credenciales de la API de Gemini
define('GEMINI_API_KEY', 'AQ.Ab8RN6L6WNuPgcC8C7S9DLh4D9WPN9eMfSVIgBrCC0T0J_c1bA'); // Reemplaza con tu Key de Google AI Studio
define('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . GEMINI_API_KEY);

/**
 * Función segura para obtener los datos consolidados con MySQLi
 * Nota: Al no recibir parámetros externos del usuario en esta consulta en específico,
 * una query directa es segura, pero estructuramos el código bajo el estándar limpio de MySQLi.
 */
function obtenerConsolidadoHistoricoMySQLi($conn) {
    $sql = "SELECT 
                YEAR(fechaRegistro) as anio, 
                MONTH(fechaRegistro) as mes, 
                COUNT(idPetroleo) as total_cargas,
                ROUND(SUM(cantidadLitros), 2) as total_litros, 
                ROUND(SUM(totalConIva), 0) as gasto_total,
                ROUND(AVG(valorUnitario), 2) as precio_promedio_litro
            FROM historial_petroleo_moviles 
            WHERE fechaRegistro IS NOT NULL
            GROUP BY YEAR(fechaRegistro), MONTH(fechaRegistro) 
            ORDER BY anio ASC, mes ASC";

    $resultado = mysqli_query($conn, $sql);

    if (!$resultado) {
        throw new Exception("Error en la consulta SQL de SDO: " . mysqli_error($conn));
    }

    $datos = [];
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $datos[] = $fila;
    }

    mysqli_free_result($resultado);
    return $datos;
}

/**
 * Función para consumir la API de Gemini enviando la estructura limpia
 */
function consultarGeminiIA($datosHistoricos) {
    if (empty($datosHistoricos)) {
        return [
            'error' => true,
            'mensaje' => 'No hay datos históricos disponibles en la tabla para analizar.'
        ];
    }

    $prompt = "Actúa como un Auditor de Flotas e Ingeniero de Datos experto para la plataforma de gestión hospitalaria SDO.
Analiza los siguientes datos de consumo de combustible extraídos directamente de la tabla 'historial_petroleo_moviles':

" . json_encode($datosHistoricos, JSON_PRETTY_PRINT) . "

Tu tarea es procesar esta información y generar una estructura JSON estricta y limpia con los siguientes puntos:
1. Resumen por año (litros totales, gasto total en CLP, número de cargas).
2. Análisis comparativo interanual analizando el crecimiento/decrecimiento absoluto y porcentual de litros y costos de un año a otro.
3. Identificación de patrones estacionales o anomalías (ej: meses con picos de consumo injustificados).
4. Tres conclusiones clave u observaciones críticas orientadas a la optimización del presupuesto o el control físico de cargas por móvil/conductor.

Responde ÚNICA Y ESTRICTAMENTE con un objeto JSON válido, sin usar bloques de código markdown (como ```json) ni texto adicional fuera del JSON. El formato debe ser el siguiente:
{
  \"resumen_anual\": [
    { \"anio\": 2025, \"total_litros\": 0.0, \"gasto_total\": 0, \"total_cargas\": 0 }
  ],
  \"comparativa_interanual\": {
    \"analisis_porcentual\": \"Texto analítico de las diferencias...\",
    \"tendencia_costos\": \"Texto explicando el comportamiento del valor unitario e IVA...\"
  },
  \"anomalias_estacionales\": [
    { \"mes_critico\": \"Mes/Año\", \"observacion\": \"Por qué es una anomalía o pico notable...\" }
  ],
  \"recomendaciones_auditoria\": [
    \"Recomendación 1...\",
    \"Recomendación 2...\"
  ]
}";

    $payload = [
        "contents" => [[
            "parts" => [["text" => $prompt]]
        ]],
        "generationConfig" => [
            "responseMimeType" => "application/json",
            "temperature" => 0.2
        ]
    ];

    $ch = curl_init(GEMINI_API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        $errorMsg = curl_error($ch);
        curl_close($ch);
        throw new Exception("Error en cURL con Gemini: " . $errorMsg);
    }

    curl_close($ch);

    if ($httpCode !== 200) {
        return [
            'error' => true,
            'mensaje' => "La API retornó código HTTP " . $httpCode
        ];
    }

    $resultadoCompleto = json_decode($response, true);
    if (!isset($resultadoCompleto['candidates'][0]['content']['parts'][0]['text'])) {
        return ['error' => true, 'mensaje' => 'Formato de respuesta inesperado de la IA.'];
    }

    return json_decode($resultadoCompleto['candidates'][0]['content']['parts'][0]['text'], true);
}

// Determinar automáticamente qué variable de conexión inicializó tu archivo conexion.php
// (Soporta si la llamaste $link, $conexion, $conn o $db_link)
$conexion_activa = null;
if (isset($link)) { $conexion_activa = $link; }
elseif (isset($conexion)) { $conexion_activa = $conexion; }
elseif (isset($conn)) { $conexion_activa = $conn; }

if (!$conexion_activa) {
    $errorSistema = "No se detectó una variable de conexión válida proveniente de '../sdo-funciones/conexion.php'. Verifica si se llama \$link o \$conexion.";
}

// Procesar flujo de datos si la conexión está OK
if ($conexion_activa) {
    try {
        $datosSQL = obtenerConsolidadoHistoricoMySQLi($conexion_activa);

        $analisisIA = null;
        if (isset($_POST['ejecutar_auditoria'])) {
            $analisisIA = consultarGeminiIA($datosSQL);
        }
    } catch (Exception $e) {
        $errorSistema = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SDO - Módulo Combustible (MySQLi)</title>
    <style>
        body { background-color: #1a1e29; color: #e2e8f0; font-family: 'Segoe UI', sans-serif; margin: 0; padding: 20px; }
        .container { max-width: 1250px; margin: 0 auto; }
        .header { background-color: #242b3d; padding: 15px 25px; border-radius: 8px; margin-bottom: 25px; border-left: 5px solid #6366f1; }
        .header h1 { margin: 0; font-size: 22px; color: #ffffff; }
        .header p { margin: 5px 0 0 0; color: #94a3b8; font-size: 13px; }
        .grid { display: grid; grid-template-columns: 1fr; gap: 20px; }
        @media(min-width: 900px) { .grid { grid-template-columns: 1fr 1fr; } }
        .card { background-color: #242b3d; border-radius: 8px; padding: 20px; border: 1px solid #2e374d; }
        .card h2 { margin-top: 0; font-size: 16px; border-bottom: 1px solid #2e374d; padding-bottom: 10px; color: #38bdf8; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 13px; }
        th, td { padding: 10px 12px; text-align: left; }
        th { background-color: #1e2433; color: #94a3b8; font-size: 11px; text-transform: uppercase; }
        tr { border-bottom: 1px solid #1e2433; }
        tr:hover { background-color: #2d354a; }
        .btn { background-color: #6366f1; color: #ffffff; border: none; padding: 10px 20px; font-size: 14px; font-weight: 600; border-radius: 5px; cursor: pointer; }
        .btn:hover { background-color: #4f46e5; }
        .alert { background-color: #7f1d1d; border-left: 4px solid #f87171; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .badge-alerta { background-color: #f59e0b; color: #1e1b4b; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; }
        .inc-texto { line-height: 1.6; font-size: 13.5px; color: #cbd5e1; }
        ul { padding-left: 20px; }
        li { margin-bottom: 8px; color: #cbd5e1; font-size: 13.5px; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>SDO Analytics &bull; Control de Petróleo Móviles (Motor MySQLi Seguro)</h1>
        <p>Llamando exitosamente a: <code>../sdo-funciones/conexion.php</code></p>
    </div>

    <?php if (isset($errorSistema)): ?>
        <div class="alert">
            <strong>Aviso SDO:</strong> <?php echo htmlspecialchars($errorSistema); ?>
        </div>
    <?php endif; ?>

    <div class="grid">
        <div class="card">
            <h2>Datos Históricos Consolidados (MySQLi)</h2>
            <table>
                <thead>
                <tr>
                    <th>Año</th>
                    <th>Mes</th>
                    <th>Cargas</th>
                    <th>Litros</th>
                    <th>Gasto Total</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($datosSQL)): ?>
                    <?php foreach ($datosSQL as $fila): ?>
                        <tr>
                            <td><strong><?php echo $fila['anio']; ?></strong></td>
                            <td>Mes <?php echo str_pad($fila['mes'], 2, '0', STR_PAD_LEFT); ?></td>
                            <td><?php echo $fila['total_cargas']; ?></td>
                            <td><?php echo number_format($fila['total_litros'], 1, ',', '.'); ?> L</td>
                            <td>$<?php echo number_format($fila['gasto_total'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align: center; color: #94a3b8;">Sin datos disponibles.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>

            <?php if (!empty($datosSQL)): ?>
                <form method="POST" style="margin-top: 20px;">
                    <button type="submit" name="ejecutar_auditoria" class="btn">
                        ✨ Procesar Auditoría Interanual con Gemini IA
                    </button>
                </form>
            <?php endif; ?>
        </div>

        <div class="card">
            <h2>Análisis de Variación Interanual & Desviaciones</h2>
            <?php if ($analisisIA === null): ?>
                <div style="text-align: center; padding: 50px 20px; color: #94a3b8;">
                    <p>Haz clic en el botón para solicitar el procesamiento analítico a la infraestructura de Gemini.</p>
                </div>
            <?php elseif (isset($analisisIA['error'])): ?>
                <div class="alert" style="background-color: #7c2d12;">
                    <strong>Error de API:</strong> <?php echo htmlspecialchars($analisisIA['mensaje']); ?>
                </div>
            <?php else: ?>
                <h3 style="font-size: 14px; color: #a78bfa;">Resumen de Consumo</h3>
                <table>
                    <thead>
                    <tr>
                        <th>Año</th>
                        <th>Litros Totales</th>
                        <th>Costo Corporativo</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($analisisIA['resumen_anual'] as $r): ?>
                        <tr>
                            <td><strong><?php echo $r['anio']; ?></strong></td>
                            <td><?php echo number_format($r['total_litros'], 1, ',', '.'); ?> Lts</td>
                            <td>$<?php echo number_format($r['gasto_total'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

                <h3 style="font-size: 14px; color: #a78bfa; margin-top: 20px;">Comparativa del Comportamiento</h3>
                <div class="inc-texto">
                    <p><strong>Métricas de Volumen:</strong><br><?php echo htmlspecialchars($analisisIA['comparativa_interanual']['analisis_porcentual']); ?></p>
                    <p><strong>Impacto de Costos/IVA:</strong><br><?php echo htmlspecialchars($analisisIA['comparativa_interanual']['tendencia_costos']); ?></p>
                </div>

                <h3 style="font-size: 14px; color: #f87171; margin-top: 20px;">Comportamientos Inusuales Identificados</h3>
                <?php foreach ($analisisIA['anomalias_estacionales'] as $anomalia): ?>
                    <div style="margin-bottom: 10px; background-color: #1e2433; padding: 10px; border-radius: 5px;">
                        <span class="badge-alerta"><?php echo htmlspecialchars($anomalia['mes_critico']); ?></span>
                        <p style="margin: 5px 0 0 0; font-size: 13px;"><?php echo htmlspecialchars($anomalia['observacion']); ?></p>
                    </div>
                <?php endforeach; ?>

                <h3 style="font-size: 14px; color: #34d399; margin-top: 20px;">Sugerencias Directas de Auditoría</h3>
                <ul>
                    <?php foreach ($analisisIA['recomendaciones_auditoria'] as $rec): ?>
                        <li><?php echo htmlspecialchars($rec); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>