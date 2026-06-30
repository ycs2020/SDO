<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
header('Content-Type: application/json');

require_once dirname(dirname(__DIR__)) . '/sdo-funciones/conexion.php';

if (!isset($conexion) && isset($db)) { $conexion = $db; }
if (!isset($conexion) && isset($con)) { $conexion = $con; }

if (!isset($conexion) || !($conexion instanceof mysqli)) {
    echo json_encode(['status' => 'error', 'message' => 'Sin conexión a la base de datos.']);
    exit;
}

$id_caldera = isset($_GET['id_caldera']) ? intval($_GET['id_caldera']) : 1;

try {
    // 🛠️ CONSULTA: Trae los últimos 24 registros individuales de la caldera
    // Usamos una subconsulta para ordenar los 24 registros finales de forma ascendente (cronológica) en el gráfico
    $sqlUltimos24 = "SELECT * FROM (
                        SELECT 
                            DATE_FORMAT(fecha_lectura, '%d-%m %H:%i') as fecha_corta,
                            temperatura,
                            estado
                        FROM calderas_lecturas
                        WHERE id_caldera = ?
                        ORDER BY fecha_lectura DESC
                        LIMIT 24
                    ) sub 
                    ORDER BY fecha_corta ASC";

    $stmt = $conexion->prepare($sqlUltimos24);
    $stmt->bind_param("i", $id_caldera);
    $stmt->execute();
    $res = $stmt->get_result();

    $labels = [];
    $datos = [];
    $estados = [];

    while ($f = $res->fetch_assoc()) {
        $labels[]  = $f['fecha_corta'];
        $datos[]   = (float)$f['temperatura'];
        $estados[] = $f['estado'] ? trim($f['estado']) : '';
    }
    $stmt->close();

    echo json_encode([
        'status' => 'success',
        'lineas' => [
            'labels'  => $labels,
            'datos'   => $datos,
            'estados' => $estados // Enviamos el arreglo de estados al JS
        ]
    ]);

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}