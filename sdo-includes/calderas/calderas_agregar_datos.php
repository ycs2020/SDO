<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) session_start();
header('Content-Type: application/json');

register_shutdown_function(function() {
    $error = error_get_last();
    if ($error && ($error['type'] === E_ERROR || $error['type'] === E_PARSE || $error['type'] === E_CORE_ERROR || $error['type'] === E_COMPILE_ERROR)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Ocurrió un error interno en el servidor. Por favor, inténtelo nuevamente.'
        ]);
        exit;
    }
});

require_once '../../sdo-funciones/conexion.php';

if (!isset($conexion) && isset($db)) { $conexion = $db; }
if (!isset($conexion) && isset($con)) { $conexion = $con; }

if (!isset($conexion) || !($conexion instanceof mysqli)) {
    echo json_encode(['status' => 'error', 'message' => 'No se pudo establecer la conexión con el sistema.']);
    exit;
}

if (!isset($_FILES['csv_estado']) || !isset($_FILES['csv_temperatura']) || !isset($_POST['id_caldera'])) {
    echo json_encode(['status' => 'error', 'message' => 'Por favor, complete todos los campos requeridos.']);
    exit;
}

function detectarDelimitador($rutaArchivo) {
    $fp = fopen($rutaArchivo, "r");
    if (!$fp) return ',';
    $linea = fgets($fp);
    fclose($fp);
    if (strpos($linea, ';') !== false) return ';';
    return ',';
}

try {
    $creado_por = '';
    if (isset($nomUsuario) && $nomUsuario !== '' && $nomUsuario !== null) {
        $creado_por = $nomUsuario;
    } elseif (isset($_SESSION['nomUsuario']) && $_SESSION['nomUsuario'] !== '' && $_SESSION['nomUsuario'] !== null) {
        $creado_por = $_SESSION['nomUsuario'];
    } else {
        $creado_por = 'Usuario Desconocido';
    }

    $id_caldera    = intval($_POST['id_caldera']);
    $nombre_estado = $_FILES['csv_estado']['name'];
    $nombre_temp   = $_FILES['csv_temperatura']['name'];

    $delimitador_estado = detectarDelimitador($_FILES['csv_estado']['tmp_name']);
    $delimitador_temp   = detectarDelimitador($_FILES['csv_temperatura']['tmp_name']);

    $datos_agrupados = [];

    // 1. PROCESAR ARCHIVO DE ESTADOS
    if (($handle = fopen($_FILES['csv_estado']['tmp_name'], "r")) !== FALSE) {
        $numeroFila = 1;
        while (($datos = fgetcsv($handle, 1000, $delimitador_estado)) !== FALSE) {
            if ($numeroFila <= 4) { $numeroFila++; continue; }

            if (isset($datos[0]) && isset($datos[3])) {
                $timestamp_texto = trim($datos[0]);
                $valor_estado    = trim($datos[3]);
                $epoch           = strtotime($timestamp_texto);

                if ($epoch) {
                    $datos_agrupados[$epoch] = [
                        'texto_fecha' => $timestamp_texto,
                        'estado'      => $valor_estado,
                        'temperatura' => null
                    ];
                }
            }
            $numeroFila++;
        }
        fclose($handle);
    }

    if (empty($datos_agrupados)) {
        throw new Exception("El archivo de Estados no contiene datos válidos.");
    }

    // 2. PROCESAR ARCHIVO DE TEMPERATURAS
    if (($handle = fopen($_FILES['csv_temperatura']['tmp_name'], "r")) !== FALSE) {
        $numeroFila = 1;
        while (($datos = fgetcsv($handle, 1000, $delimitador_temp)) !== FALSE) {
            if ($numeroFila <= 4) { $numeroFila++; continue; }

            if (isset($datos[0]) && isset($datos[3])) {
                $timestamp_texto = trim($datos[0]);
                $valor_temp      = trim($datos[3]);
                $valor_temp      = str_replace([" ", "°", "C", "c"], "", $valor_temp);
                $epoch           = strtotime($timestamp_texto);

                if ($epoch) {
                    $llave_asignada = $epoch;

                    if (isset($datos_agrupados[$epoch])) {
                        $llave_asignada = $epoch;
                    } elseif (isset($datos_agrupados[$epoch - 1])) {
                        $llave_asignada = $epoch - 1;
                    } elseif (isset($datos_agrupados[$epoch + 1])) {
                        $llave_asignada = $epoch + 1;
                    }

                    if (!isset($datos_agrupados[$llave_asignada])) {
                        $datos_agrupados[$llave_asignada] = [
                            'texto_fecha' => $timestamp_texto,
                            'estado'      => null,
                            'temperatura' => $valor_temp
                        ];
                    } else {
                        $datos_agrupados[$llave_asignada]['temperatura'] = $valor_temp;
                    }
                }
            }
            $numeroFila++;
        }
        fclose($handle);
    }

    if (!empty($datos_agrupados)) {
        ksort($datos_agrupados);
    }

    // 3. GUARDAR EN LA BASE DE DATOS (Manejando selectivo de duplicados)
    $conexion->begin_transaction();

    // Insertar cabecera de la importación
    $sqlImp = "INSERT INTO calderas_importaciones (archivo_estado, archivo_temperatura, creado_por, id_caldera) VALUES (?, ?, ?, ?)";
    $stmtImp = $conexion->prepare($sqlImp);
    if (!$stmtImp) { throw new Exception("Error al preparar la carga del historial."); }
    $stmtImp->bind_param("sssi", $nombre_estado, $nombre_temp, $creado_por, $id_caldera);
    if (!$stmtImp->execute()) { throw new Exception("No se pudo registrar el historial de la importación."); }
    $importacion_id = $conexion->insert_id;
    $stmtImp->close();

    // 🛠️ CONSULTA DE VERIFICACIÓN FILA POR FILA
    $sqlCheckFila = "SELECT id FROM calderas_lecturas WHERE id_caldera = ? AND epoch = ? LIMIT 1";
    $stmtCheckFila = $conexion->prepare($sqlCheckFila);

    // Consulta de inserción estándar
    $sqlLectura = "INSERT INTO calderas_lecturas (importacion_id, fecha_lectura, epoch, estado, temperatura, id_caldera) VALUES (?, ?, ?, ?, ?, ?)";
    $stmtLectura = $conexion->prepare($sqlLectura);

    $v_importacion_id = $importacion_id;
    $v_mysqlDatetime  = "";
    $v_epoch          = 0;
    $v_estado         = null;
    $v_temperatura    = null;
    $v_id_caldera     = $id_caldera;

    $stmtLectura->bind_param("isissi", $v_importacion_id, $v_mysqlDatetime, $v_epoch, $v_estado, $v_temperatura, $v_id_caldera);

    $data_respuesta = [];
    $contador_nuevos = 0;
    $contador_repetidos = 0;

    foreach ($datos_agrupados as $epoch => $fila) {
        // Verificar si esta marca de tiempo específica ya existe para esta caldera
        $stmtCheckFila->bind_param("ii", $id_caldera, $epoch);
        $stmtCheckFila->execute();
        $stmtCheckFila->store_result();

        if ($stmtCheckFila->num_rows > 0) {
            // El registro ya existe: Incrementamos contador y nos saltamos la inserción
            $contador_repetidos++;
            continue;
        }

        // Si no existe, preparamos las variables e insertamos de forma normal
        $v_mysqlDatetime = date("Y-m-d H:i:s", $epoch);
        $v_epoch         = $epoch;
        $v_estado        = ($fila['estado'] !== null && $fila['estado'] !== '') ? $fila['estado'] : null;
        $v_temperatura   = ($fila['temperatura'] !== null && $fila['temperatura'] !== '') ? $fila['temperatura'] : null;

        if ($stmtLectura->execute()) {
            $contador_nuevos++;

            // Solo mandamos al frontend para la tabla los registros que efectivamente se acaban de subir
            $data_respuesta[] = [
                'fecha'       => $fila['texto_fecha'],
                'estado'      => $fila['estado'],
                'temperatura' => $fila['temperatura']
            ];
        }
    }

    $stmtCheckFila->close();
    $stmtLectura->close();
    $conexion->commit();

    // 4. MENSAJE FINAL ADAPTADO
    if ($contador_nuevos === 0) {
        echo json_encode([
            'status'  => 'error',
            'message' => 'No se cargaron datos nuevos. Los ' . $contador_repetidos . ' registros procesados ya existían en el sistema.',
            'data'    => []
        ]);
    } else {
        $msg = '¡Importación finalizada! Se guardaron ' . $contador_nuevos . ' registros nuevos.';
        if ($contador_repetidos > 0) {
            $msg .= ' Se omitieron ' . $contador_repetidos . ' filas que ya estaban registradas.';
        }
        echo json_encode([
            'status'  => 'success',
            'message' => $msg,
            'data'    => $data_respuesta
        ]);
    }

} catch (Exception $e) {
    if (isset($conexion) && $conexion instanceof mysqli) {
        $conexion->rollback();
    }
    echo json_encode([
        'status'  => 'error',
        'message' => $e->getMessage()
    ]);
}