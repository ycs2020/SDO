<?php
require '../vendor/autoload.php';
require 'conexion.php';
require 'funciones.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
    die('❌ Error al subir archivo.');
}

$idDepartamento = isset($_POST['idDepartamento']) ? (int)$_POST['idDepartamento'] : 0;
if ($idDepartamento <= 0) {
    die('❌ ID Departamento inválido.');
}

// Guardar temporalmente
$nombreTemporal = $_FILES['archivo']['tmp_name'];
$spreadsheet = IOFactory::load($nombreTemporal);
$sheet = $spreadsheet->getActiveSheet();
$rows = $sheet->toArray(null, true, true, true);

$now = date('Y-m-d H:i:s');
$creadorOT = 'Yorgo Casimis Subiabre';

$resultados = [];

foreach ($rows as $index => $row) {
    if ($index == 1) continue; // Saltar encabezado

    $postData = [
        'fechaMonitoreo'   => $row['A'],
        'tipoFormulario'   => $row['B'],
        'personaAsignada'  => $row['C'],
        'turnoAsignado'    => $row['D'],
        'creadorOT'        => $creadorOT
    ];

    $res = crearOrdenMonitoreo($conexion, $now, $idDepartamento, $postData);
    $res['filaExcel'] = $index;
    $resultados[] = $res;
}

// Mostrar resultados
echo "<h2>Resultado de inserciones</h2>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Fila Excel</th><th>Estado</th><th>Folio</th><th>Mensaje</th></tr>";
foreach ($resultados as $r) {
    echo "<tr>";
    echo "<td>{$r['filaExcel']}</td>";
    echo "<td>" . ($r['success'] ? '✅ OK' : '❌ Error') . "</td>";
    echo "<td>{$r['folioTG']}</td>";
    echo "<td>{$r['mensaje']}</td>";
    echo "</tr>";
}
echo "</table>";
?>
