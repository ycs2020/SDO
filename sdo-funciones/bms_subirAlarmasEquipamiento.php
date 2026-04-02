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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo_excel_alarmas'])) {
    $idEquipamiento = $_POST['idEquipamiento'];

    if ($idEquipamiento > 0) {

        $fileTmpPath = $_FILES['archivo_excel_alarmas']['tmp_name'];
        $fileName = $_FILES['archivo_excel_alarmas']['name'];
        $fileSize = $_FILES['archivo_excel_alarmas']['size'];
        $fileType = $_FILES['archivo_excel_alarmas']['type'];
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
                    'fechaAlarma',      // Cambiar según los títulos exactos en tu archivo Excel
                    'stateAlarma',
                    'sourceAlarma',
                    'classAlarma',
                    'mensajeAlarma',
                    'notasAlarma'
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
                $totalRepeticiones = 0;
                $uniqueSources = [];


                // Mostrar los datos en formato tabular (opcional)
                foreach ($data as $row) {
                    if (!$firstRowSkipped) {
                        $firstRowSkipped = true;
                        continue; // Saltar la primera iteración que corresponde a los títulos
                    }
                    $fechaAlarma = $row['fechaAlarma'];
                    $stateAlarma = $row['stateAlarma'];
                    $sourceAlarma = $row['sourceAlarma'];
                    $classAlarma = $row['classAlarma'];
                    $mensajeAlarma = $row['mensajeAlarma'];
                    $notasAlarma = $row['notasAlarma'];

                    $fechaAlarma2 = excelToDateSegundo($fechaAlarma);

                    $conteoRegistro = verificarExistenciaRegAlarmaEquipamiento($fechaAlarma, $stateAlarma, $sourceAlarma, $classAlarma, $mensajeAlarma, $notasAlarma);
//                    $conteoRegistro = 0;

                    if ($conteoRegistro > 0) {
//                        $totalRows = $totalRows - 1;
                        $totalRepeticiones++;

                        $sql2 = "INSERT INTO bms_alarmas_equipamiento2(idAlarma, fechaAlarma, stateAlarma, sourceAlarma, classAlarma, mensajeAlarma, notasAlarma, subidaAlarma) VALUES (NULL,'$fechaAlarma','$stateAlarma','$sourceAlarma','$classAlarma','$mensajeAlarma','$notasAlarma','$fechaNow')";

                        $res2 = mysqli_query($conexion, $sql2);

                        if ($res2) {
                            $totalBuenos2++;
                        } else {
                            $totalMalos2++;
                        }

                    } else {
                        $sql = "INSERT INTO bms_alarmas_equipamiento(idAlarma, fechaAlarma, stateAlarma, sourceAlarma, classAlarma, mensajeAlarma, notasAlarma, subidaAlarma) VALUES (NULL,'$fechaAlarma','$stateAlarma','$sourceAlarma','$classAlarma','$mensajeAlarma','$notasAlarma','$fechaNow')";

                        $res = mysqli_query($conexion, $sql);

                        if ($res) {
                            $totalBuenos++;
                            // Verificar si sourceAlarma ya está en el array
                            if (!in_array($sourceAlarma, $uniqueSources)) {
                                // Si no está, agregarlo al array
                                $uniqueSources[] = $sourceAlarma;
                            }
                        } else {
                            $totalMalos++;
                        }

                    }


                    $varRespuesta = "Se cargaron " . $totalBuenos . " Registros de " . $totalRows . ".";
                    $procedeRevision = 1;

                    if ($totalRepeticiones > 0) {
                        $varRespuesta .= " Existen $totalRepeticiones Registros Ya Ingresados.";
                        $procedeRevision = 1;
                    }

                }
                // Aquí puedes usar $data para cualquier procesamiento adicional que necesites

            } catch (Exception $e) {
//            echo 'Error al cargar el archivo Excel: ' . $e->getMessage();
                $varRespuesta = "Error al Subir Archivo Excel" . ".";
                $procedeRevision = 0;

            }
        } else {
//        echo 'Por favor, suba un archivo Excel (.xlsx).';
            $varRespuesta = "Error. Seleccione Archivo Excel (.xlsx)1" . ".";
            $procedeRevision = 0;
        }
    } else {

        $varRespuesta = "Error. No Existe ID de Equipo.";
        $procedeRevision = 0;
    }
} else {
//    echo 'No se ha subido ningún archivo.';

    $varRespuesta = "Error. Seleccione Archivo Excel (.xlsx)2" . ".";
    $procedeRevision = 0;
}

//echo $varRespuesta;

if ($procedeRevision == 1) {
    $contaSourceCorrecto = 0;
    $contaSourceIncorrecto = 0;
    $totalUniqueSources = count($uniqueSources);
    foreach ($uniqueSources as $source) {
        // Verifica si el source ya existe en bms_asocia_source
        $checkSql = "SELECT COUNT(*) AS count FROM bms_asocia_source WHERE sourceAsocia = '$source' AND idEquipoAsocia = " . $idEquipamiento;
        $checkRes = mysqli_query($conexion, $checkSql);

        if ($checkRes) {
            $row = mysqli_fetch_assoc($checkRes);
            $count = $row['count'];

            // Si el count es 0, el source no existe y podemos insertarlo
            if ($count == 0) {

                $insertSql = "INSERT INTO bms_asocia_source (sourceAsocia, idEquipoAsocia) VALUES ('$source', '$idEquipamiento')";
                $insertRes = mysqli_query($conexion, $insertSql);

                if ($insertRes) {
                    $contaSourceCorrecto++;
                } else {
                    $contaSourceInorrecto++;
                }
            } else {
                $contaSourceInorrecto++;
            }
        } else {
            $contaSourceInorrecto++;
        }
    }


    $varRespuesta .= "<br>Se cargaron " . $contaSourceCorrecto . " Sources de " . $totalUniqueSources . ".";
}

$idEquipamientoPadre = $_POST['idEquipamientoPadre'];
$tipoMenuVuelta = $_POST['tipoMenuVuelta'];

if ($tipoMenuVuelta == 'menuNuevo') {
    $sitioWeb = 'equipo_menuNuevo.php';

    if ($idEquipamientoPadre > 0) {
        $_SESSION['idEquipo'] = $idEquipamientoPadre;
        $idEquipo = $idEquipamiento;
    } else {
        $_SESSION['idEquipo'] = $idEquipamiento;
        $idEquipo = $idEquipamiento;
    }

} else {

    $_SESSION['idEquipo'] = $idEquipamiento;
    $idEquipo = $idEquipamiento;

    $tipoMenu = obtenerTipoMenu($idEquipamiento);

    if ($tipoMenuVuelta == 'nuevo') {
        $sitioWeb = 'equipo_menuNuevo.php';
    }else{
        $sitioWeb = 'equipo_menuAntiguo.php';
    }

}


$_SESSION['varRespuesta'] = $varRespuesta;

header('Location: ../' . $ruta . '/' . $sitioWeb . '?mensajeVar=true');
?>
