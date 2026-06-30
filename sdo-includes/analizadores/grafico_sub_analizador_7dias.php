<?php
include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

header('Content-Type: application/json');
date_default_timezone_set('America/Santiago');

$subId = isset($_POST['subId']) ? (int)$_POST['subId'] : 0;
$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : 'voltaje'; // Capturamos el tipo

if ($subId <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Sub-analizador inválido.']);
    exit;
}

// Filtro SQL según el tipo solicitado
if ($tipo === 'fase') {
    $filtroLineas = "'Fase 1', 'Fase 2', 'Fase 3'";
    $tituloEjeY = 'Amperaje (A)';
} else {
    $filtroLineas = "'L1N', 'L2N', 'L3N', 'L1L2', 'L2L3', 'L3L1'";
    $tituloEjeY = 'Voltaje (V)';
}

$query = "SELECT fechaMedicion, valorMedicion, lineaMedicion 
          FROM analizadores_red_mediciones 
          WHERE idSubAnalizador = ? 
            AND lineaMedicion IN ($filtroLineas)
            AND fechaMedicion >= DATE_SUB(NOW(), INTERVAL 7 DAY)
          ORDER BY fechaMedicion ASC";

$labels = [];
$raw_data = [];
$lineas_encontradas = [];

if ($stmt = $conexion->prepare($query)) {
    $stmt->bind_param("i", $subId);
    $stmt->execute();
    $resultado = $stmt->get_result();

    while ($fila = $resultado->fetch_assoc()) {
        $fechaFormateada = date('d-M H:i', strtotime($fila['fechaMedicion']));

        if (!in_array($fechaFormateada, $labels)) {
            $labels[] = $fechaFormateada;
        }

        $linea = trim($fila['lineaMedicion']);
        if (!in_array($linea, $lineas_encontradas)) {
            $lineas_encontradas[] = $linea;
        }

        $raw_data[$linea][$fechaFormateada] = (float)$fila['valorMedicion'];
    }
    $stmt->close();
}

// Colores expandidos para incluir las Fases (Amperaje)
$coloresLineas = [
    'L1N'  => ['stroke' => '#dc3545', 'bg' => 'rgba(220, 53, 69, 0.08)'],
    'L2N'  => ['stroke' => '#ffc107', 'bg' => 'rgba(255, 193, 7, 0.08)'],
    'L3N'  => ['stroke' => '#007bff', 'bg' => 'rgba(0, 123, 255, 0.08)'],
    'L1L2' => ['stroke' => '#6f42c1', 'bg' => 'rgba(111, 66, 193, 0.08)'],
    'L2L3' => ['stroke' => '#28a745', 'bg' => 'rgba(40, 167, 69, 0.08)'],
    'L3L1' => ['stroke' => '#fd7e14', 'bg' => 'rgba(253, 126, 20, 0.08)'],
    'Fase 1' => ['stroke' => '#17a2b8', 'bg' => 'rgba(23, 162, 184, 0.08)'],
    'Fase 2' => ['stroke' => '#e83e8c', 'bg' => 'rgba(232, 62, 140, 0.08)'],
    'Fase 3' => ['stroke' => '#20c997', 'bg' => 'rgba(32, 201, 151, 0.08)']
];

$datasets = [];

foreach ($lineas_encontradas as $linea) {
    $data_ordenada = [];
    foreach ($labels as $label) {
        $data_ordenada[] = isset($raw_data[$linea][$label]) ? $raw_data[$linea][$label] : null;
    }

    $colorStroke = isset($coloresLineas[$linea]) ? $coloresLineas[$linea]['stroke'] : '#6c757d';
    $colorBg = isset($coloresLineas[$linea]) ? $coloresLineas[$linea]['bg'] : 'rgba(108, 117, 125, 0.05)';

    $datasets[] = [
        'label' => $linea,
        'data' => $data_ordenada,
        'borderColor' => $colorStroke,
        'backgroundColor' => $colorBg
    ];
}

echo json_encode([
    'status' => 'success',
    'labels' => $labels,
    'datasets' => $datasets,
    'ejeY' => $tituloEjeY // Enviamos el título para el gráfico
]);