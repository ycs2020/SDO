<?php
session_start();
require '../sdo-funciones/conexion.php';
require '../sdo-funciones/funciones.php';

header('Content-Type: application/json');

$response = ['status' => 'error', 'mensaje' => 'Error desconocido'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'mensaje' => 'Método no permitido']);
    exit;
}

// Función para limpiar entradas
function limpiar($valor) {
    return trim(htmlspecialchars($valor));
}

// Captura de variables
$idCredencial     = intval($_POST['idCredencial'] ?? 0);
$nombre           = limpiar($_POST['nombreCredencial'] ?? '');
$apellido         = limpiar($_POST['apellidoCredencial'] ?? '');
$rut              = limpiar($_POST['rutCredencial'] ?? '');
$telefono         = limpiar($_POST['telefonoCredencial'] ?? '');
$usuarioModifica  = $_SESSION['nomUsuario'] ?? 'Desconocido';

// Validación
if ($idCredencial <= 0 || $nombre === '' || $apellido === '' || $rut === '') {
    echo json_encode(['status' => 'error', 'mensaje' => 'Faltan campos obligatorios']);
    exit;
}

// Verificar si el RUT ya está asignado a otra credencial
$sqlVerifica = "SELECT idCredencial FROM credenciales_rrhh WHERE rutCredencial = ? AND idCredencial != ?";
$stmtVerifica = $conexion->prepare($sqlVerifica);

if (!$stmtVerifica) {
    echo json_encode(['status' => 'error', 'mensaje' => 'Error SQL: ' . $conexion->error]);
    exit;
}

$stmtVerifica->bind_param("si", $rut, $idCredencial);
$stmtVerifica->execute();
$stmtVerifica->store_result();

if ($stmtVerifica->num_rows > 0) {
    echo json_encode(['status' => 'error', 'mensaje' => 'Ese RUT ya está asignado a otra credencial.']);
    $stmtVerifica->close();
    exit;
}
$stmtVerifica->close();

// Actualizar credencial
$sql = "UPDATE credenciales_rrhh 
        SET nombreCredencial = ?, apellidoCredencial = ?, rutCredencial = ?, telefonoCredencial = ? 
        WHERE idCredencial = ?";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    echo json_encode(['status' => 'error', 'mensaje' => 'Error en prepare(): ' . $conexion->error]);
    exit;
}

$stmt->bind_param("ssssi", $nombre, $apellido, $rut, $telefono, $idCredencial);

if ($stmt->execute()) {
    // Registrar observación
    $observacion = "Modificación realizada por $usuarioModifica\n\n" .
        "Nuevo Nombre: $nombre $apellido\n" .
        "Nuevo RUT: $rut\n" .
        "Nuevo Teléfono: $telefono\n";

    $insertObs = $conexion->prepare("
        INSERT INTO credenciales_rrhh_observaciones (
            idCredencial, fechaObservacion, usuarioObservacion, tipoObservacion, observacion
        ) VALUES (?, NOW(), ?, 'MODIFICACIÓN', ?)
    ");
    if ($insertObs) {
        $insertObs->bind_param("iss", $idCredencial, $usuarioModifica, $observacion);
        $insertObs->execute();
        $insertObs->close();
    }

    echo json_encode([
        'status' => 'ok',
        'mensaje' => 'Credencial modificada correctamente.',
        'idCredencial' => $idCredencial
    ]);
} else {
    echo json_encode(['status' => 'error', 'mensaje' => 'Error al modificar: ' . $stmt->error]);
}
$stmt->close();
