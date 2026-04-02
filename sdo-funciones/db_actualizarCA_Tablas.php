<?php
ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

if (session_status() === PHP_SESSION_NONE) session_start();

// Variables de sesión y rutas
$userUsuario = $_SESSION['userUsuario'] ?? null;
$rolUsuario = $_SESSION['rolUsuario'] ?? null;
$rolUsuario2 = strtolower($rolUsuario);
$rutasPorRol = [
    "personal" => "sdo-personal",
    "administrativo" => "sdo-admtec",
    "bodega pañol" => "sdo-bodega-panol",
    "jefe tecnico" => "sdo-jefestecnicos",
    "administrador" => "sdo-administrador",
    "directiva" => "sdo-directiva"
];
$ruta = $rutasPorRol[$rolUsuario2] ?? null;

if (!$ruta) {
    header("Location: ../sdo-flujo/login_index.php?errorSesion=true");
    exit();
}

include_once 'conexion.php';
include_once 'funciones.php';

date_default_timezone_set('America/Santiago');
setlocale(LC_ALL, "es_CL");
setlocale(LC_MONETARY, 'es_CL');

$start = microtime(true);
$mensajeAlerta = "";
$icon = "Correcto";

function insertarPorLotes($conexion, $tabla, $datos, $tamanoLote = 1000) {

    if (empty($datos)) return ['ok' => 0, 'error' => 0];

    $columnas = array_keys($datos[0]);
    $columnasSQL = implode(", ", $columnas);

    // crear carpeta logs si no existe
    $carpetaLogs = __DIR__ . "/logs_importacion";
    if (!is_dir($carpetaLogs)) {
        mkdir($carpetaLogs, 0777, true);
    }

    $archivoLog = $carpetaLogs . "/errores_importacion_" . date("Y-m-d_H-i-s") . ".txt";

    $conexion->query("SET FOREIGN_KEY_CHECKS=0");
    $conexion->begin_transaction();

    $ok = 0;
    $error = 0;

    $chunks = array_chunk($datos, $tamanoLote);

    foreach ($chunks as $lote) {

        $valoresSQL = [];

        foreach ($lote as $fila) {

            $filaEscapada = array_map(function ($valor) use ($conexion) {
                return "'" . $conexion->real_escape_string($valor) . "'";
            }, $fila);

            $valoresSQL[] = "(" . implode(", ", $filaEscapada) . ")";
        }

        $sqlInsert = "INSERT INTO $tabla ($columnasSQL) VALUES " . implode(", ", $valoresSQL);

        if ($conexion->query($sqlInsert)) {

            $ok += count($lote);

        } else {

            $error += count($lote);

            $mensajeError = PHP_EOL . "==============================" . PHP_EOL;
            $mensajeError .= "Fecha: " . date("Y-m-d H:i:s") . PHP_EOL;
            $mensajeError .= "Tabla: $tabla" . PHP_EOL;
            $mensajeError .= "Error MySQL: " . $conexion->error . PHP_EOL;
            $mensajeError .= "SQL:" . PHP_EOL;
            $mensajeError .= $sqlInsert . PHP_EOL;
            $mensajeError .= "==============================" . PHP_EOL;

            file_put_contents($archivoLog, $mensajeError, FILE_APPEND);
        }
    }

    $conexion->commit();
    $conexion->query("SET FOREIGN_KEY_CHECKS=1");

    return ['ok' => $ok, 'error' => $error];
}

// Validación archivo
if (!isset($_FILES['archivo_db'], $_POST['categoria'])) {
    $_SESSION["mensajeAlerta"] = "⚠️ <strong>Error:</strong> Favor seleccione un archivo.";
    $_SESSION["iconAlerta"] = "Error";
    header("Location: ../$ruta/control-acceso.php?mensajeVar=true");
    exit();
}

$categoria = $_POST['categoria'];
$archivo = $_FILES['archivo_db'];
$nombreArchivo = basename($archivo['name']);
$extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);

if ($extension !== 'db') {
    $_SESSION["mensajeAlerta"] = "❌ <strong>Error:</strong> El archivo debe tener extensión .db";
    $_SESSION["iconAlerta"] = "Error";
    header("Location: ../$ruta/control-acceso.php?mensajeVar=true");
    exit();
}

$archivosPermitidos = [
    "Funcionarios" => "ACSManagerDbFile.db",
    "Estacionamiento" => "VideoTalk.db"
];

if (!isset($archivosPermitidos[$categoria]) || $archivosPermitidos[$categoria] !== $nombreArchivo) {
    $_SESSION["mensajeAlerta"] = "❌ <strong>Error:</strong> Archivo incorrecto para la categoría seleccionada.";
    $_SESSION["iconAlerta"] = "Error";
    header("Location: ../$ruta/control-acceso.php?mensajeVar=true");
    exit();
}

$rutaDestino = 'cargaDatos/' . $nombreArchivo;
if (!move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
    $_SESSION["mensajeAlerta"] = "❌ <strong>Error:</strong> No se pudo mover el archivo al servidor.";
    $_SESSION["iconAlerta"] = "Error";
    header("Location: ../$ruta/control-acceso.php?mensajeVar=true");
    exit();
}

if (!file_exists($rutaDestino)) {
    $_SESSION["mensajeAlerta"] = "❌ <strong>Error:</strong> Archivo SQLite no encontrado.";
    $_SESSION["iconAlerta"] = "Error";
    header("Location: ../$ruta/control-acceso.php?mensajeVar=true");
    exit();
}

$db = new SQLite3($rutaDestino);

$tablasPorCategoria = [
    "Funcionarios" => ["ACM_owner", "ACS_DOOR_GROUP", "ACS_DOOR_GROUP_DOOR", "ACS_DOOR_GROUP_USER", "Department_table"],
    "Estacionamiento" => ["intercom_users", "intercom_department"]
];

$mapaTablas = [
    "intercom_users" => "Person",
    "intercom_department" => "Department"
];

$tablas = $tablasPorCategoria[$categoria];

foreach ($tablas as $tabla) {
    $tablaSqlite = $mapaTablas[$tabla] ?? $tabla;
    $query = "SELECT * FROM $tablaSqlite";
    $result = @$db->query($query);

    if ($result) {
        $datos = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $datos[] = $row;
        }

        if (!empty($datos)) {
            $conexion->query("SET FOREIGN_KEY_CHECKS=0");
            $conexion->query("DELETE FROM $tabla");
            $conexion->query("SET FOREIGN_KEY_CHECKS=1");

            $resultado = insertarPorLotes($conexion, $tabla, $datos, 1000);
            $mensajeAlerta .= "✅ <strong>$tabla:</strong> {$resultado['ok']} insertados | {$resultado['error']} errores.<br>";
        } else {
            $mensajeAlerta .= "⚠️ <strong>$tabla:</strong> Sin datos que cargar.<br>";
            $icon = "Advertencia";
        }
    } else {
        $mensajeAlerta .= "❌ <strong>Error al leer tabla $tablaSqlite.</strong><br>";
        $icon = "Error";
    }
}

unlink($rutaDestino);

// Fin del cronómetro
$end = microtime(true);
$duracion = round($end - $start, 2);
$mensajeAlerta .= "<hr><strong>⏱️ Tiempo total de carga:</strong> {$duracion} segundos.";

$_SESSION["mensajeAlerta"] = $mensajeAlerta;
$_SESSION["iconAlerta"] = $icon;
header("Location: ../$ruta/control-acceso.php?mensajeVar=true");
exit();
