<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../vendor/autoload.php';
include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

$spreadsheet = new Spreadsheet();
$sheet       = $spreadsheet->getActiveSheet();

function calcularAnchoColumna($texto)
{
    // Longitud del texto * 1.2 (ajuste visual)
    return strlen($texto) * 1.2;
}

// OJO: máx 31 caracteres
$sheet->setTitle('Perfiles y Grupos CCAA');

// =====================
// 1) OBTENER DATOS
// =====================

// Grupos (columnas)
$sqlGrupos = "
    SELECT idGrupo, nomGrupo
    FROM protoc_ccaa_grupo
    ORDER BY nomGrupo ASC
";
$resGrupos = mysqli_query($conexion, $sqlGrupos);

$grupos = [];
while ($row = mysqli_fetch_assoc($resGrupos)) {
    $grupos[] = [
        'idGrupo'  => (int)$row['idGrupo'],
        'nomGrupo' => mb_convert_case($row['nomGrupo'], MB_CASE_UPPER, 'UTF-8')
    ];
}

// Perfiles (filas)
$sqlPerfiles = "
    SELECT idPerfil, nomPerfil
    FROM protoc_ccaa_perfil
    ORDER BY nomPerfil ASC
";
$resPerfiles = mysqli_query($conexion, $sqlPerfiles);

$perfiles = [];
while ($row = mysqli_fetch_assoc($resPerfiles)) {
    $perfiles[] = [
        'idPerfil'  => (int)$row['idPerfil'],
        'nomPerfil' => mb_convert_case($row['nomPerfil'], MB_CASE_UPPER, 'UTF-8')
    ];
}

// Relaciones Perfil–Grupo
$sqlRel = "
    SELECT idPerfil, idGrupo
    FROM protoc_ccaa_perfil_grupo
";
$resRel = mysqli_query($conexion, $sqlRel);

$relaciones = []; // $relaciones[idPerfil][idGrupo] = true;
while ($row = mysqli_fetch_assoc($resRel)) {
    $idPerfil = (int)$row['idPerfil'];
    $idGrupo  = (int)$row['idGrupo'];

    if (!isset($relaciones[$idPerfil])) {
        $relaciones[$idPerfil] = [];
    }
    $relaciones[$idPerfil][$idGrupo] = true;
}

// =====================
// 2) ENCABEZADOS
// =====================

// A1 = título de la columna de perfiles
$sheet->setCellValue('A1', 'PERFILES');

// Columnas de grupos comienzan en B (índice 2)
$colIndex = 2;

foreach ($grupos as $g) {
    $colLetter = Coordinate::stringFromColumnIndex($colIndex);
    $sheet->setCellValue($colLetter . '1', $g['nomGrupo']);
    $colIndex++;
}

// Última columna usada (número e letra)
$lastColIndex  = $colIndex - 1;
$lastColLetter = Coordinate::stringFromColumnIndex($lastColIndex);

// =====================
// 3) FILAS DE PERFILES + ✓
// =====================

$rowIndex = 2; // empezamos en fila 2

foreach ($perfiles as $p) {
    $idPerfil  = $p['idPerfil'];
    $nomPerfil = $p['nomPerfil'];

    // Nombre del perfil en columna A
    $sheet->setCellValue('A' . $rowIndex, $nomPerfil);

    // Recorrer columnas de grupos
    $colIndex = 2;
    foreach ($grupos as $g) {
        $idGrupoRev   = $g['idGrupo'];
        $nomGrupo  = $g['nomGrupo'];

        $stmt = $conexion->prepare("
                SELECT 
                    ad.name AS puerta
                FROM protoc_ccaa_grupo_puerta pcgp
                INNER JOIN acs_door ad 
                    ON (pcgp.numPuerta - 1) = ad.no 
                    AND pcgp.numUCA = ad.idDevice
                INNER JOIN ucas_listado ul 
                    ON pcgp.numUCA = ul.idUnique
                WHERE idGrupo = ?
                ORDER BY
                    CASE
                        WHEN ul.name LIKE '%-P0' THEN 0
                        WHEN ul.name LIKE '%-P1' THEN 1
                        WHEN ul.name LIKE '%-P2' THEN 2
                        WHEN ul.name LIKE '%-P3' THEN 3
                        WHEN ul.name LIKE '%-P4' THEN 4
                        WHEN ul.name LIKE '%-P5' THEN 5
                        WHEN ul.name LIKE '%-P6' THEN 6
                        ELSE 99
                    END,
                    ul.name ASC,
                    ad.name ASC
            ");

        if (!$stmt) {
            die("Error en prepare: " . $conexion->error);
        }

        $stmt->bind_param('i', $idGrupoRev);
        $stmt->execute();
        $result = $stmt->get_result();

        $puertas = [];

        while ($row = $result->fetch_assoc()) {

            if (!empty($row['puerta'])) { // solo agregar si tiene valor
                $puertas[] = $row['puerta'];
            }
        }

        $cadenaTexto = implode('; ', $puertas);

        $colLetter = Coordinate::stringFromColumnIndex($colIndex);

        // Si existe relación, marcamos con ✓
        if (!empty($relaciones[$idPerfil][$idGrupoRev])) {
            $sheet->setCellValue($colLetter . $rowIndex, $cadenaTexto);
        }

        $colIndex++;
    }

    $rowIndex++;
}

$lastRow = $rowIndex - 1;

// =====================
// 4) ESTILOS (bordes, negrita, alineación)
// =====================

// Encabezados en negrita
$sheet->getStyle("A1:{$lastColLetter}1")->getFont()->setBold(true);
$sheet->getStyle("A1:A{$lastRow}")->getFont()->setBold(true);

// Bordes para toda la tabla
$sheet->getStyle("A1:{$lastColLetter}{$lastRow}")
    ->getBorders()->getAllBorders()
    ->setBorderStyle(Border::BORDER_THIN);

// Alinear textos
$sheet->getStyle("A1:{$lastColLetter}{$lastRow}")
    ->getAlignment()
    ->setVertical(Alignment::VERTICAL_CENTER);

$sheet->getStyle("B2:{$lastColLetter}{$lastRow}")
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

$sheet->getStyle("A2:A{$lastRow}")
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_LEFT);

// =====================
// 4.1) CENTRAR COLUMNA A Y FILA 1
// =====================

// Centrar horizontal + vertical la columna A completa
$sheet->getStyle("A1:A{$lastRow}")
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
    ->setVertical(Alignment::VERTICAL_CENTER);

// Centrar horizontal + vertical la fila 1 completa
$sheet->getStyle("A1:{$lastColLetter}1")
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
    ->setVertical(Alignment::VERTICAL_CENTER);

// =====================
// 5) WIDTH FIJO + AJUSTE VERTICAL
// =====================

for ($i = 1; $i <= $lastColIndex; $i++) {

    $colLetter = Coordinate::stringFromColumnIndex($i);

    // Ancho fijo de 30
    $sheet->getColumnDimension($colLetter)->setWidth(30);

    // Permitir que el texto se divida en líneas
    $sheet->getStyle($colLetter)->getAlignment()->setWrapText(true);
}

// Ajustar automáticamente el ALTO de TODAS las filas
for ($fila = 1; $fila <= $lastRow; $fila++) {
    $sheet->getRowDimension($fila)->setRowHeight(-1);
}

// Columna A → AutoSize
$sheet->getColumnDimension('A')->setAutoSize(true);

// Todas las demás → width fijo 30 + wrap text
for ($i = 2; $i <= $lastColIndex; $i++) {

    $colLetter = Coordinate::stringFromColumnIndex($i);

    $sheet->getColumnDimension($colLetter)->setWidth(30);
    $sheet->getStyle($colLetter)->getAlignment()->setWrapText(true);
}


// =====================
// 6) SALIDA DEL ARCHIVO
// =====================

// LIMPIA BUFFER ANTES DE ENVIAR HEADERS
if (ob_get_length()) {
    ob_end_clean();
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Export_Excel_Perfiles_Grupos.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
