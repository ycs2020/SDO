<?php
// Mostrar todos los errores de PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);
$ruta = "";

if ($rolUsuario2 == "personal") {
    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $ruta = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $ruta = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $ruta = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

// Incluir la conexión a la base de datos y otras funciones si es necesario
include_once 'conexion.php';
include_once 'funciones.php';

date_default_timezone_set('America/Santiago');
setlocale(LC_ALL, "es_CL", 'Spanish_Chile', 'Spanish');
setlocale(LC_MONETARY, 'es_CL');

$fechaNow = date('Y-m-d H:i:s');

// Incluir la clase PHPExcel
require_once '../sdo-excelphp/Classes/PHPExcel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo_excel'])) {
    $fileTmpPath = $_FILES['archivo_excel']['tmp_name'];
    $fileName = $_FILES['archivo_excel']['name'];
    $fileSize = $_FILES['archivo_excel']['size'];
    $fileType = $_FILES['archivo_excel']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    // Verificar si el archivo subido es un archivo Excel
    $allowedfileExtensions = ['xlsx'];
    if (in_array($fileExtension, $allowedfileExtensions)) {
        try {
            // Cargar el archivo Excel usando PHPExcel
            $objPHPExcel = PHPExcel_IOFactory::load($fileTmpPath);

            // Obtener la primera hoja (worksheet)
            $worksheet = $objPHPExcel->getActiveSheet();

            // Array para almacenar los datos de las filas
            $data = [];

            // Obtener las cabeceras (títulos de las columnas)
            $columnTitles = [
                'fechaCreacionAlarma',      // Cambiar según los títulos exactos en tu archivo Excel
                'stateAlarma',
                'ackAlarma',
                'sourceAlarma',
                'classAlarma',
                'fechaNormalAlarma',
                'mensajeAlarma',
                'notasAlarma',
                'conteoAlarma'
            ];

            $rowIterator = $worksheet->getRowIterator();
            $rowIterator->rewind(); // Iniciar desde el principio
            foreach ($rowIterator as $row) {
                $rowData = [];
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false); // Incluir celdas vacías
                foreach ($cellIterator as $cell) {
                    $cellValue = $cell->getValue();
                    $columnIndex = PHPExcel_Cell::columnIndexFromString($cell->getColumn()) - 1;
                    $columnTitle = isset($columnTitles[$columnIndex]) ? $columnTitles[$columnIndex] : '';

                    // Guardar los datos en el array usando los títulos de las columnas como claves
                    if (!empty($columnTitle)) {
                        if (strpos($cellValue, 'AM GMT-04:00') !== false || strpos($cellValue, 'PM GMT-04:00') !== false) {
                            // Aplicar la expresión regular para eliminar AM/PM y zona horaria
                            $cellValue2 = preg_replace('/\s+(AM|PM)\s+GMT-04:00/', '', $cellValue);
                            $cellValue2 = date('Y-m-d H:i:s', strtotime($cellValue2));

                        } else if (strpos($cellValue, 'E-1-49s') !== false) {
                            $cellValue2 = str_replace('E-1-49s', 'E-1-49', $cellValue);

                        } else {
                            // No hacer ningún cambio si no se encuentra "AM GMT-04:00" ni "PM GMT-04:00"
                            $cellValue2 = $cellValue;
                        }
                        $rowData[$columnTitle] = $cellValue2;
                    }


                }
                // Agregar los datos de la fila al array principal
                $data[] = $rowData;
            }

            $totalRows = count($data) - 1;
            $totalBuenos = 0;
            $totalMalos = 0;

            // Mostrar los datos en formato tabular (opcional)
            foreach ($data as $row) {
                if (!$firstRowSkipped) {
                    $firstRowSkipped = true;
                    continue; // Saltar la primera iteración que corresponde a los títulos
                }
                $fechaCreacionAlarma = $row['fechaCreacionAlarma'];
                $stateAlarma = $row['stateAlarma'];
                $ackAlarma = $row['ackAlarma'];
                $sourceAlarma = $row['sourceAlarma'];
                $classAlarma = $row['classAlarma'];
                $fechaNormalAlarma = $row['fechaNormalAlarma'];
                $mensajeAlarma = $row['mensajeAlarma'];
                $notasAlarma = $row['notasAlarma'];
                $conteoAlarma = $row['conteoAlarma'];
                $fechaSubidaAlarma = $row['fechaSubidaAlarma'];

                $sql = "INSERT INTO bms_alarmas (idAlarma, fechaCreacionAlarma, stateAlarma, ackAlarma, sourceAlarma, classAlarma, fechaNormalAlarma, mensajeAlarma, notasAlarma, conteoAlarma, fechaSubidaAlarma)  VALUES (NULL, '$fechaCreacionAlarma', '$stateAlarma', '$ackAlarma', '$sourceAlarma', '$classAlarma', '$fechaNormalAlarma', '$mensajeAlarma', '$notasAlarma', '$conteoAlarma', '$fechaNow')";

                $res = mysqli_query($conexion, $sql);

                if ($res) {
                    $totalBuenos++;
                } else {
                    $totalMalos++;
                }

            }

            $varRespuesta = "Se cargaron ".$totalBuenos." Registros de ".$totalRows.".";

            echo 'TOTAL: '.$totalRows.'<br><br>';
            echo 'CORRECTOS: '.$totalBuenos.'<br><br>';
            echo 'ERROR: '.$totalMalos.'<br><br>';

            // Aquí puedes usar $data para cualquier procesamiento adicional que necesites

        } catch (Exception $e) {
//            echo 'Error al cargar el archivo Excel: ' . $e->getMessage();
            $varRespuesta = "Error al Subir Archivo Excel".".";

        }
    } else {
//        echo 'Por favor, suba un archivo Excel (.xlsx).';
        $varRespuesta = "Error. Seleccione Archivo Excel (.xlsx)".".";
    }
} else {
//    echo 'No se ha subido ningún archivo.';

    $varRespuesta = "Error. Seleccione Archivo Excel (.xlsx)".".";
}

//echo $varRespuesta;


$_SESSION['varRespuesta'] = $varRespuesta;

header('Location: ../'.$ruta.'/BMS_Alarmas.php?mensajeVar=true');
?>
