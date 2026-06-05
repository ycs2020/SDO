<?php
// Desactivar visualización de errores que rompan el JSON
ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Capturar errores fatales y convertirlos a JSON para que el AJAX no muera en silencio
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error && ($error['type'] === E_ERROR || $error['type'] === E_PARSE)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error interno en el controlador de datos: ' . $error['message']
        ]);
        exit;
    }
});

// 🛠️ RUTA ABSOLUTA BLINDADA: Sube dinámicamente desde sdo-includes/calderas/ a la raíz
require_once dirname(dirname(__DIR__)) . '/sdo-funciones/conexion.php';

if (!isset($conexion) && isset($db)) { $conexion = $db; }
if (!isset($conexion) && isset($con)) { $conexion = $con; }

if (!isset($conexion) || !($conexion instanceof mysqli)) {
    echo json_encode(['status' => 'error', 'message' => 'Error: La conexión a la base de datos no está disponible.']);
    exit;
}

try {
    // Consultamos los últimos 2000 registros
    $sql = "SELECT fecha_lectura, id_caldera, estado, temperatura 
            FROM calderas_lecturas 
            ORDER BY fecha_lectura DESC 
            LIMIT 2000";

    $resultado = $conexion->query($sql);

    $data = [];
    if ($resultado) {
        while ($fila = $resultado->fetch_assoc()) {
            $data[] = [
                'fecha'       => $fila['fecha_lectura'],
                'caldera'     => $fila['id_caldera'],
                'estado'      => $fila['estado'],
                'temperatura' => $fila['temperatura']
            ];
        }
        $resultado->free();
    }

    echo json_encode([
        'status' => 'success',
        'data'   => $data
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Error al obtener el listado de la base de datos.'
    ]);
}