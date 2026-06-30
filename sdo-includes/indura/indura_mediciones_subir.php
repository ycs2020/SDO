<?php
// Mostrar errores para depuración estricta en caso de cualquier detalle
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// VARIABLES DE SESIÓN
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);
$ruta = "";

$rutasRoles = [
    "personal"        => "sdo-personal",
    "administrativo"  => "sdo-admtec",
    "bodega pañol"    => "sdo-bodega-panol",
    "jefe tecnico"    => "sdo-jefestecnicos",
    "administrador"   => "sdo-administrador",
    "directiva"       => "sdo-directiva"
];

if (array_key_exists($rolUsuario2, $rutasRoles)) {
    $ruta = $rutasRoles[$rolUsuario2];
} else {
    header('Location: ../../sdo-flujo/login_index.php?errorSesion=true');
    exit;
}

include_once '../../sdo-funciones/conexion.php';
include_once '../../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
$fechaNow = date('Y-m-d H:i:s');

// Cambiar a true únicamente si necesitas volver a revisar la bitácora interna de errores
$MODO_DEBUG = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo_excel'])) {
    $fileTmpPath = $_FILES['archivo_excel']['tmp_name'];
    $fileName = $_FILES['archivo_excel']['name'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if ($MODO_DEBUG) {
        echo "<h2>=== MODO DEBUG ACTIVADO ===</h2>";
        echo "Archivo recibido: <b>$fileName</b><br>";
    }

    if ($fileExtension === 'csv') {

        $obtenerIDTanqueIndura = obtenerIDTanqueIndura();

        if ($obtenerIDTanqueIndura > 0) {
            $tanque = $obtenerIDTanqueIndura;

            // Leer contenido binario crudo del archivo temporal
            $contenidoOriginal = file_get_contents($fileTmpPath);

            // DETECCIÓN Y CONVERSIÓN ULTRA-ESTRICTA CON ICONV (Elimina residuos UTF-16 del CSV tabulado)
            if (strpos($contenidoOriginal, "\x00") !== false) {
                $contenidoUtf8 = iconv('UTF-16LE', 'UTF-8//IGNORE', $contenidoOriginal);
            } else {
                $contenidoUtf8 = mb_convert_encoding($contenidoOriginal, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252');
            }

            // Normalizar de forma absoluta todos los saltos de línea posibles
            $contenidoUtf8 = str_replace("\r\n", "\n", $contenidoUtf8);
            $contenidoUtf8 = str_replace("\r", "\n", $contenidoUtf8);

            // Segmentar las líneas en el array
            $lineas = explode("\n", $contenidoUtf8);

            if (count($lineas) > 0) {

                // Buscar el delimitador real inspeccionando la primera línea limpia
                $primeraLinea = $lineas[0];
                $delimitador = ",";
                if (strpos($primeraLinea, "\t") !== FALSE) {
                    $delimitador = "\t";
                } else if (strpos($primeraLinea, ';') !== FALSE) {
                    $delimitador = ';';
                }

                $totalBuenos = 0;
                $totalMalos = 0;
                $totalRepeticiones = 0;
                $totalRows = 0;
                $ultimoErrorSQL = "";

                // Preparar consulta de inserción segura apuntando a tu tabla principal
                $sqlInsert = "INSERT INTO medicion_tanque_ind_1 (lecturaMedicion, unidadMedicion, fechaMedicion, fuenteMedicion, tanqueMedicion, fechaSubidaMedicion) VALUES (?, ?, ?, ?, ?, ?)";

                // NOTA: Si estás usando medicion_tanque_indura, cámbialo abajo:
                $sqlInsert = "INSERT INTO medicion_tanque_indura (lecturaMedicion, unidadMedicion, fechaMedicion, fuenteMedicion, tanqueMedicion, fechaSubidaMedicion) VALUES (?, ?, ?, ?, ?, ?)";

                $stmtInsert = $conexion->prepare($sqlInsert);

                // Limpieza de marca BOM UTF-8 residual si existiera
                $utf8_bom = pack('CCC', 0xef, 0xbb, 0xbf);

                foreach ($lineas as $index => $lineaRaw) {
                    $lineaRaw = trim($lineaRaw);
                    if (empty($lineaRaw)) continue;

                    // Omitir cabecera (Fila 0)
                    if ($index === 0) continue;

                    // Parsear columnas usando el delimitador nativo detectado
                    $row = str_getcsv($lineaRaw, $delimitador);

                    if (!isset($row[1]) || !isset($row[2]) || !isset($row[3])) {
                        $totalMalos++;
                        continue;
                    }

                    $totalRows++;

                    $primerCelda = trim($row[0]);
                    if (substr($primerCelda, 0, 3) === $utf8_bom) {
                        $primerCelda = substr($primerCelda, 3);
                    }

                    // Datos limpios en UTF-8 nativo
                    $valor     = trim($primerCelda);
                    $udm       = trim($row[1]);
                    $fechaHora = trim($row[2]);
                    $fuente    = trim($row[3]);

                    $fechaHoraClean = trim($fechaHora);
                    $fechaBaseDatos = false;
                    $fechaValidacion = "";

                    // Forzar parseo interpretando el año corto de 2 dígitos del CSV ('y' minúscula, ej: 26)
                    $objFecha = DateTime::createFromFormat('d/m/y H:i', $fechaHoraClean);
                    if (!$objFecha) {
                        $objFecha = DateTime::createFromFormat('j/n/y H:i', $fechaHoraClean);
                    }

                    if ($objFecha !== false) {
                        // 1. Formato requerido por el campo DATETIME nativo de MySQL (YYYY-MM-DD HH:MM:SS) -> 2026-03-23 00:30:00
                        $fechaBaseDatos = $objFecha->format('Y-m-d H:i:s');

                        // 2. CORRECCIÓN FORMATO: Formateamos como 'Y-m-d H:i' -> 2026-03-23 00:30
                        // De esta forma, el LIKE '%$fechaMedicion%' de tu consulta calza perfecto con la columna DATETIME de la BD
                        $fechaValidacion = $objFecha->format('Y-m-d H:i');
                    } else {
                        // Fallback secundario
                        $fechaHoraCleanFormat = str_replace('/', '-', $fechaHoraClean);
                        $time = strtotime($fechaHoraCleanFormat);
                        $fechaBaseDatos = $time ? date('Y-m-d H:i:s', $time) : date('Y-m-d H:i:s');
                        $fechaValidacion = $time ? date('Y-m-d H:i', $time) : date('Y-m-d H:i');
                    }

                    // Ejecutar comparación exacta. El LIKE buscará '%2026-03-23 00:30%' detectando el duplicado de inmediato.
                    $conteoRegistro = verificarExistenciaRegTanque($valor, $udm, $fechaValidacion, $fuente, $tanque);

                    if ($conteoRegistro > 0) {
                        $totalRepeticiones++;
                    } else {
                        $valorEntero = (int)$valor;

                        // Ejecutar la inserción segura vinculando tipos estrictos (isssis)
                        $stmtInsert->bind_param("isssis", $valorEntero, $udm, $fechaBaseDatos, $fuente, $tanque, $fechaNow);

                        if ($stmtInsert->execute()) {
                            $totalBuenos++;
                        } else {
                            $totalMalos++;
                            $ultimoErrorSQL = $stmtInsert->error;
                        }
                    }
                }

                if (isset($stmtInsert)) { $stmtInsert->close(); }
                $conexion->close();

                // Mensaje consolidado final para el SweetAlert
                $varRespuesta = "Se cargaron " . $totalBuenos . " registros nuevos de " . $totalRows . " procesados.";
                if ($totalRepeticiones > 0) {
                    $varRespuesta .= " Se omitieron $totalRepeticiones duplicados.";
                }
                if ($totalMalos > 0) {
                    $varRespuesta .= " Fallaron $totalMalos inserciones por base de datos. Último error: " . $ultimoErrorSQL;
                }

                if ($MODO_DEBUG) {
                    echo "<br><h3>=== RESUMEN FINAL ===</h3>";
                    echo "<b>Resultado:</b> $varRespuesta<br>";
                    exit;
                }

            } else {
                $varRespuesta = "Error crítico al abrir el archivo temporal en el servidor.";
            }
        } else {
            $varRespuesta = "Error: No se encontró un ID de tanque activo asignado en la sesión.";
        }
    } else {
        $varRespuesta = "Error: Formato incorrecto. Por favor cargue un archivo delimitado plano (.csv).";
    }
} else {
    $varRespuesta = "Error: El servidor no detectó ninguna carga de datos de archivo.";
}

$_SESSION['varRespuesta'] = $varRespuesta;

// Redirección directa hacia la interfaz limpia original
header('Location: ../../'.$ruta.'/indura_mediciones.php?mensajeVar=true');
exit;
?>