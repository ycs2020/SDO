<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../vendor/autoload.php';
include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('PUERTAS x GRUPO');

// NUEVOS encabezados
$sheet->setCellValue('A1', 'N°');
$sheet->setCellValue('B1', 'UCA');
$sheet->setCellValue('C1', 'N° PUERTA');
$sheet->setCellValue('D1', 'NOMBRE PUERTA');
$sheet->setCellValue('E1', 'GRUPOS DE PUERTA');
$sheet->getStyle('A1:E1')->getFont()->setBold(true);

$pisosArray = ["-P0", "-P1", "-P2", "-P3", "-P4", "-P5", "-P6"];
$data = [];

foreach ($pisosArray as $piso) {
    $queryUCA = 'SELECT * FROM ucas_listado WHERE name LIKE "%' . $piso . '%" ORDER BY name ASC';
    $resUCA = mysqli_query($conexion, $queryUCA);

    while ($rowUCA = mysqli_fetch_assoc($resUCA)) {
        $queryPuertas = 'SELECT * FROM ACS_DOOR_GROUP_DOOR WHERE DeviceID = ' . (int)$rowUCA['idUnique'] . ' ORDER BY DoorID ASC';
        $resPuertas = mysqli_query($conexion, $queryPuertas);

        while ($row = mysqli_fetch_assoc($resPuertas)) {
            $deviceID = $row['DeviceID'];
            $doorID = $row['DoorID'];
            $doorGroupID = $row['DoorGroupID'];

            if ($deviceID > 0 && $doorID > -1) {
                $nombrePuerta = mb_strtoupper(obtenerNomPuerta($deviceID, $doorID), "UTF-8");
                $nombreUCA = mb_strtoupper(obtenerNomUCA($deviceID), "UTF-8");
                $numeroPuerta = $doorID + 1;
                $nombreGrupo = ($doorGroupID > 0)
                    ? mb_strtoupper(obtenerNameGrupoPuerta($doorGroupID), "UTF-8")
                    : 'SIN GRUPO';

                $clave = $nombreUCA . '|' . $numeroPuerta . '|' . $nombrePuerta;
                if (!isset($data[$clave])) {
                    $data[$clave] = [];
                }
                if (!in_array($nombreGrupo, $data[$clave])) {
                    $data[$clave][] = $nombreGrupo;
                }
            }
        }
    }
}

$fila = 2;
$numnum = 1;
foreach ($data as $clave => $grupos) {
    list($uca, $nro, $nombre) = explode('|', $clave);
    $sheet->setCellValue('A' . $fila, $numnum);
    $sheet->setCellValue('B' . $fila, $uca);
    $sheet->setCellValue('C' . $fila, $nro);
    $sheet->setCellValue('D' . $fila, $nombre);
    $sheet->setCellValue('E' . $fila, implode("\n", $grupos));
    $sheet->getStyle('E' . $fila)->getAlignment()->setWrapText(true);
    $fila++;
    $numnum++;
}

foreach (range('A', 'E') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// LIMPIA BUFFER ANTES DE ENVIAR HEADERS
if (ob_get_contents()) ob_end_clean();

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="EXPORT_UCA_PUERTAS_GRUPOS.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
