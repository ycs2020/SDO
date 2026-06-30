<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

$anio = intval($_POST['anio']);

// RESUMEN
$sql = "
SELECT
    SUM(metrosConsumidos) as totalM3,
    SUM(valorTotalConIVA) as totalGastoIva,
    AVG(metrosConsumidos) as promedioM3,
    COUNT(*) as facturas
FROM criogenico_datos
WHERE YEAR(fecha) = '$anio'
";

$res = mysqli_query($conexion, $sql);
$row = mysqli_fetch_assoc($res);

// DATOS POR MES
$sqlMeses = "
SELECT
    MONTH(fecha) as mes,
    SUM(metrosConsumidos) as totalM3,
    SUM(valorTotalConIVA) as totalGastoIva,
    SUM(valorTotal) as totalNeto,
    COUNT(*) as facturas
FROM criogenico_datos
WHERE YEAR(fecha) = '$anio'
GROUP BY MONTH(fecha)
ORDER BY MONTH(fecha)
";

$resMeses = mysqli_query($conexion, $sqlMeses);

$nombresMeses = [
    1 => 'Ene',
    2 => 'Feb',
    3 => 'Mar',
    4 => 'Abr',
    5 => 'May',
    6 => 'Jun',
    7 => 'Jul',
    8 => 'Ago',
    9 => 'Sep',
    10 => 'Oct',
    11 => 'Nov',
    12 => 'Dic'
];

$consumos = array_fill(1, 12, 0);
$gastos = array_fill(1, 12, 0);

$detalle = [];

while($fila = mysqli_fetch_assoc($resMeses)){

    $mes = (int)$fila['mes'];

    $consumos[$mes] = (float)$fila['totalM3'];
    $gastos[$mes] = (float)$fila['totalGastoIva'];

    $detalle[] = [
        'mes' => $nombresMeses[$mes],
        'm3' => (float)$fila['totalM3'],
        'iva' => (float)$fila['totalGastoIva'],
        'neto' => (float)$fila['totalNeto'],
        'facturas' => (int)$fila['facturas']
    ];
}

$response = [
    'totalM3' => (float)($row['totalM3'] ?? 0),
    'totalGastoIva' => (float)($row['totalGastoIva'] ?? 0),
    'totalGastoNeto' => (float)($row['totalNeto'] ?? 0),
    'promedioM3' => (float)($row['promedioM3'] ?? 0),
    'facturas' => (int)($row['facturas'] ?? 0),

    'labels' => array_values($nombresMeses),
    'consumos' => array_values($consumos),
    'gastos' => array_values($gastos),

    'detalle' => $detalle
];

header('Content-Type: application/json');
echo json_encode($response);
exit;