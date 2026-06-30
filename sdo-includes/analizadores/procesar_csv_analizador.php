<?php
// procesar_csv_analizador.php (PRODUCCIÓN - VERSIÓN CORREGIDA DE CABECERAS)
include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

header('Content-Type: application/json');
date_default_timezone_set('America/Santiago');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
    exit;
}

$idAnalizador = isset($_POST['idAnalizador']) ? (int)$_POST['idAnalizador'] : 0;
$idSubAnalizador = isset($_POST['idSubAnalizador']) ? (int)$_POST['idSubAnalizador'] : 0;

if ($idAnalizador <= 0 || $idSubAnalizador <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Parámetros de analizador o sub-analizador no válidos.']);
    exit;
}

$archivosDisponibles = [
    'csv_l1n'   => 'L1N', 'l1n' => 'L1N',
    'csv_l2n'   => 'L2N', 'l2n' => 'L2N',
    'csv_l3n'   => 'L3N', 'l3n' => 'L3N',
    'csv_l1l2'  => 'L1L2', 'l1l2' => 'L1L2',
    'csv_l2l3'  => 'L2L3', 'l2l3' => 'L2L3',
    'csv_l3l1'  => 'L3L1', 'l3l1' => 'L3L1',
    'csv_fase1' => 'Fase 1', 'fase1' => 'Fase 1', 'csv_fase_1' => 'Fase 1',
    'csv_fase2' => 'Fase 2', 'fase2' => 'Fase 2', 'csv_fase_2' => 'Fase 2',
    'csv_fase3' => 'Fase 3', 'fase3' => 'Fase 3', 'csv_fase_3' => 'Fase 3'
];

$totalInsertados = 0;
$totalOmitidos = 0;

$mesesDiccionario = [
    'Jan' => '01', 'Feb' => '02', 'Mar' => '03', 'Apr' => '04', 'May' => '05', 'Jun' => '06',
    'Jul' => '07', 'Aug' => '08', 'Sep' => '09', 'Oct' => '10', 'Nov' => '11', 'Dec' => '12'
];

$conexion->begin_transaction();

try {
    foreach ($archivosDisponibles as $inputName => $nombreLinea) {
        if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === UPLOAD_ERR_OK) {

            $rutaTemporal = $_FILES[$inputName]['tmp_name'];
            $gestor = fopen($rutaTemporal, 'r');

            if ($gestor !== FALSE) {

                $sqlInsert = "INSERT IGNORE INTO analizadores_red_mediciones 
                              (fechaMedicion, valorMedicion, lineaMedicion, idAnalizador, idSubAnalizador) 
                              VALUES (?, ?, ?, ?, ?)";

                $stmt = $conexion->prepare($sqlInsert);
                $esPrimeraLinea = true;

                while (($columnas = fgetcsv($gestor, 1000, ",")) !== FALSE) {

                    // 1. Si la fila está completamente vacía de estructura, pasar a la siguiente sin evaluar filtros
                    if (empty($columnas) || !isset($columnas[0]) || $columnas[0] === null) {
                        continue;
                    }

                    // Limpieza estricta de caracteres invisibles en la primera celda
                    $col0 = trim($columnas[0]);
                    if ($esPrimeraLinea) {
                        $col0 = preg_replace('/^[\xEF\xBB\xBF\xFE\xFF\xFF\xFE]/', '', $col0);
                        $col0 = trim($col0);
                        $esPrimeraLinea = false;
                    }

                    // 2. Filtro de cabeceras de texto del BMS (Evaluando solo contenido real)
                    if ($col0 === '' || strpos($col0, 'history:') !== false || strpos($col0, 'to') !== false || $col0 === 'Timestamp') {
                        continue;
                    }

                    // 3. PARSEAR MARCA DE TIEMPO MANUAL (Inmune a locales del sistema)
                    $fechaLimpia = preg_replace('/GMT[-+]\d{2}:\d{2}/', '', $col0);
                    $fechaLimpia = trim($fechaLimpia);
                    $fechaLimpia = preg_replace('/\s+/', ' ', $fechaLimpia);

                    $partesEspacio = explode(' ', $fechaLimpia);
                    if (count($partesEspacio) < 3) {
                        continue;
                    }

                    $bloqueFecha = explode('-', $partesEspacio[0]);
                    if (count($bloqueFecha) < 3) {
                        continue;
                    }

                    $dia = str_pad($bloqueFecha[0], 2, '0', STR_PAD_LEFT);
                    $mesTexto = trim($bloqueFecha[1]);
                    $mes = isset($mesesDiccionario[$mesTexto]) ? $mesesDiccionario[$mesTexto] : '01';
                    $anio = '20' . $bloqueFecha[2];

                    $bloqueHora = explode(':', $partesEspacio[1]);
                    if (count($bloqueHora) < 3) {
                        continue;
                    }

                    $horaRaw = (int)$bloqueHora[0];
                    $minuto = str_pad($bloqueHora[1], 2, '0', STR_PAD_LEFT);
                    $segundo = str_pad($bloqueHora[2], 2, '0', STR_PAD_LEFT);
                    $indicadorAMPM = strtoupper($partesEspacio[2]);

                    if ($indicadorAMPM === 'PM' && $horaRaw < 12) {
                        $horaRaw += 12;
                    } elseif ($indicadorAMPM === 'AM' && $horaRaw === 12) {
                        $horaRaw = 0;
                    }
                    $hora = str_pad($horaRaw, 2, '0', STR_PAD_LEFT);

                    $fechaFinalSQL = "$anio-$mes-$dia $hora:$minuto:$segundo";

                    // 4. PARSEAR VALOR DINÁMICO CELDA POR CELDA
                    $valorFloat = 0.0;
                    $valorEncontrado = false;

                    foreach ($columnas as $index => $celda) {
                        if ($celda === null) continue;

                        // CRÍTICO: Saltar la columna 0 (Timestamp) para no procesar letras de meses (Apr) o AM/PM
                        if ($index === 0) {
                            continue;
                        }

                        // Normalizar formato: Convertir comas decimales a puntos
                        $celdaLimpia = str_replace(',', '.', trim($celda));

                        // Validar si la celda contiene la unidad (A o V) y tiene dígitos
                        if ((strpos($celdaLimpia, 'A') !== false || strpos($celdaLimpia, 'V') !== false) && preg_match('/\d/', $celdaLimpia)) {
                            $strNumero = preg_replace('/[^\d.]/', '', $celdaLimpia);
                            $valorFloat = (float)$strNumero;
                            $valorEncontrado = true;
                            break;
                        }
                    }

                    // Extracción de respaldo si no se encontró con la regla anterior
                    if (!$valorEncontrado) {
                        // Iterar por las posiciones típicas de datos (omitinedo el índice 0)
                        foreach ([1, 2, 3] as $idx) {
                            if (isset($columnas[$idx]) && trim($columnas[$idx]) !== '') {
                                $celdaLimpia = str_replace(',', '.', trim($columnas[$idx]));
                                $strNumero = preg_replace('/[^\d.]/', '', $celdaLimpia);
                                if (is_numeric($strNumero)) {
                                    $valorFloat = (float)$strNumero;
                                    $valorEncontrado = true;
                                    break;
                                }
                            }
                        }
                    }

                    // 5. INSERCIÓN EN BASE DE DATOS
                    $stmt->bind_param("sdsii", $fechaFinalSQL, $valorFloat, $nombreLinea, $idAnalizador, $idSubAnalizador);
                    $stmt->execute();

                    if ($stmt->affected_rows > 0) {
                        $totalInsertados++;
                    } else {
                        $totalOmitidos++;
                    }
                }

                $stmt->close();
                fclose($gestor);
            }
        }
    }

    $conexion->commit();

    if ($totalInsertados > 0 || $totalOmitidos > 0) {
        echo json_encode([
            'status' => 'success',
            'message' => "Resumen del proceso:\n• Nuevas mediciones guardadas: {$totalInsertados}\n• Omitidas por duplicidad: {$totalOmitidos}"
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'No se encontraron registros procesables dentro de las estructuras de los archivos subidos.'
        ]);
    }

} catch (Exception $e) {
    $conexion->rollback();
    echo json_encode([
        'status' => 'error',
        'message' => 'Error crítico en el motor de base de datos: ' . $e->getMessage()
    ]);
}