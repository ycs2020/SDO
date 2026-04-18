<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

include_once '../../sdo-funciones/conexion.php';
include_once '../../sdo-funciones/funciones.php';

header('Content-Type: application/json');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['nomUsuario']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'title' => 'Acceso Denegado', 'text' => 'No hay sesión activa.']);
    exit;
}

$tipo_solicitud = filter_input(INPUT_POST, 'tipo_solicitud', FILTER_SANITIZE_SPECIAL_CHARS);
$asunto         = filter_input(INPUT_POST, 'asunto', FILTER_SANITIZE_SPECIAL_CHARS);
$requerimiento  = filter_input(INPUT_POST, 'requerimiento', FILTER_SANITIZE_SPECIAL_CHARS);
$usuario        = $_SESSION['nomUsuario'];

// Iniciamos una transacción para asegurar que se guarden ambos o ninguno
$conexion->begin_transaction();

try {
    // 1. INSERTAR EL REQUERIMIENTO
    $sqlReq = "INSERT INTO sdo_requerimientos (
                fecha_solicitud, tipo_solicitud, usuario_solicita, asunto, requerimiento, estado
               ) VALUES (NOW(), ?, ?, ?, ?, 'Pendiente')";

    $stmtReq = $conexion->prepare($sqlReq);
    $stmtReq->bind_param("ssss", $tipo_solicitud, $usuario, $asunto, $requerimiento);

    if (!$stmtReq->execute()) {
        throw new Exception("Error al crear requerimiento: " . $stmtReq->error);
    }

    // 2. OBTENER EL ID GENERADO
    $nuevo_id_requerimiento = $conexion->insert_id;

    // 3. INSERTAR LA OBSERVACIÓN INICIAL
    $texto_observacion = "Registro inicial del requerimiento por el usuario.";
    $sqlObs = "INSERT INTO sdo_observaciones (id_requerimiento, fecha_observacion, observacion, usuario_observa) 
               VALUES (?, NOW(), ?, ?)";

    $stmtObs = $conexion->prepare($sqlObs);
    $stmtObs->bind_param("iss", $nuevo_id_requerimiento, $texto_observacion, $usuario);

    if (!$stmtObs->execute()) {
        throw new Exception("Error al crear observación inicial: " . $stmtObs->error);
    }

    // Si todo salió bien, confirmamos los cambios en la BD
    $conexion->commit();

    echo json_encode([
        'status' => 'success',
        'title'  => '¡Logrado!',
        'text'   => 'El requerimiento y su historial han sido creados.'
    ]);

} catch (Exception $e) {
    // Si algo falla, revertimos todo para no dejar datos huérfanos
    $conexion->rollback();

    echo json_encode([
        'status' => 'error',
        'title'  => 'Error en el proceso',
        'text'   => $e->getMessage()
    ]);
} finally {
    if (isset($stmtReq)) $stmtReq->close();
    if (isset($stmtObs)) $stmtObs->close();
}