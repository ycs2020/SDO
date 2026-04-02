<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../vendor/autoload.php';
include_once '../sdo-funciones/conexion.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Función recursiva para obtener todos los equipos hijos
function obtenerEquiposRecursivos($conexion, $idsIniciales = []) {
    $equipos = [];
    $porBuscar = $idsIniciales;

    while (!empty($porBuscar)) {
        $in = implode(',', array_map('intval', $porBuscar));
        $sql = "SELECT * FROM equipamiento_hbqp WHERE idEquipoPadre IN ($in)";
        $res = mysqli_query($conexion, $sql);
        $porBuscar = [];

        while ($row = mysqli_fetch_assoc($res)) {
            $equipos[] = $row;
            $porBuscar[] = $row['id'];
        }
    }

    return $equipos;
}

// 1. Obtener equipos base
$sqlBase = "SELECT * FROM equipamiento_hbqp WHERE idDepaPertenece = 10";
$resBase = mysqli_query($conexion, $sqlBase);

$equiposBase = [];
$idsBase = [];

while ($row = mysqli_fetch_assoc($resBase)) {
    $equiposBase[] = $row;
    $idsBase[] = $row['id'];
}

// 2. Obtener hijos recursivos
$equiposRecursivos = obtenerEquiposRecursivos($conexion, $idsBase);
$todosEquipos = array_merge($equiposBase, $equiposRecursivos);

// 3. Eliminar duplicados por ID
$equiposUnicos = [];
$idsVistos = [];

foreach ($todosEquipos as $equipo) {
    if (!in_array($equipo['id'], $idsVistos)) {
        $equiposUnicos[] = $equipo;
        $idsVistos[] = $equipo['id'];
    }
}

// 4. Crear archivo Excel con formato de tabla
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Equipos Recursivos');

// Cabeceras
$headers = ['ID', 'Nombre Equipo', 'ID Padre', 'ID Categoría', 'Departamento', 'Estado', 'Recinto'];
$sheet->fromArray($headers, NULL, 'A1');

// Datos
$rowNumber = 2;
foreach ($equiposUnicos as $equipo) {
    $sheet->setCellValue("A$rowNumber", $equipo['id']);
    $sheet->setCellValue("B$rowNumber", $equipo['nombreEquipo']);
    $sheet->setCellValue("C$rowNumber", $equipo['idEquipoPadre']);
    $sheet->setCellValue("D$rowNumber", $equipo['idCategoria']);
    $sheet->setCellValue("E$rowNumber", $equipo['idDepaPertenece']);
    $sheet->setCellValue("F$rowNumber", $equipo['estadoEquipo']);
    $sheet->setCellValue("G$rowNumber", $equipo['recinto']);
    $rowNumber++;
}

// Crear rango como tabla
$lastRow = $rowNumber - 1;
$tableRange = "A1:G$lastRow";

$sheet->addTable([
    'name' => 'EquiposRecursivosTable',
    'range' => $tableRange,
    'showHeaderRow' => true,
    'showTotalsRow' => false,
    'columns' => [
        ['name' => 'ID'],
        ['name' => 'Nombre Equipo'],
        ['name' => 'ID Padre'],
        ['name' => 'ID Categoría'],
        ['name' => 'Departamento'],
        ['name' => 'Estado'],
        ['name' => 'Recinto'],
    ],
    'style' => [
        'theme' => 'TableStyleMedium9', // Estilo azul con filas rayadas
        'showRowStripes' => true,
    ]
]);

// Congelar fila de encabezado
$sheet->freezePane('A2');

// 5. Descargar archivo Excel
$filename = 'equipos_dep10_recursivos.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
