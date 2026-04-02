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

function limpiar($valor) {
    return trim(htmlspecialchars($valor));
}

// Variables
$nombre          = limpiar($_POST['nombreCredencial'] ?? '');
$apellido        = limpiar($_POST['apellidoCredencial'] ?? '');
$rut             = limpiar($_POST['rutCredencial'] ?? '');
$telefono        = limpiar($_POST['telefonoCredencial'] ?? '');
$usuarioRegistro = $_SESSION['nomUsuario'] ?? 'Desconocido';

// Validaciones básicas
if ($nombre === '' || $apellido === '' || $rut === '') {
    echo json_encode(['status' => 'error', 'mensaje' => 'Faltan campos obligatorios']);
    exit;
}

// Verificar duplicado
$verificaSQL = "SELECT idCredencial FROM credenciales_rrhh WHERE rutCredencial = ?";
$stmtVerifica = $conexion->prepare($verificaSQL);

if (!$stmtVerifica) {
    echo json_encode(['status' => 'error', 'mensaje' => 'Error SQL: ' . $conexion->error]);
    exit;
}

$stmtVerifica->bind_param("s", $rut);
$stmtVerifica->execute();
$stmtVerifica->store_result();

if ($stmtVerifica->num_rows > 0) {
    echo json_encode(['status' => 'error', 'mensaje' => 'Ya existe un(a) funcionario(a) con ese RUT.']);
    $stmtVerifica->close();
    exit;
}
$stmtVerifica->close();

// Insertar credencial
$sql = "INSERT INTO credenciales_rrhh (
    nombreCredencial,
    apellidoCredencial,
    rutCredencial,
    telefonoCredencial
) VALUES (?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);

if (!$stmt) {
    echo json_encode(['status' => 'error', 'mensaje' => 'Error en prepare(): ' . $conexion->error]);
    exit;
}

$stmt->bind_param("ssss", $nombre, $apellido, $rut, $telefono);

if ($stmt->execute()) {

    // ✅ ID recién generado
    $idCredencial = $stmt->insert_id;

    // Observación
    $observacion = "Credencial registrada por $usuarioRegistro\n\n" .
        "Nombre: $nombre $apellido\n" .
        "RUT: $rut\n" .
        "Teléfono: $telefono\n";

    $insertObs = $conexion->prepare("
        INSERT INTO credenciales_rrhh_observaciones (
            idCredencial, fechaObservacion, usuarioObservacion, tipoObservacion, observacion
        ) VALUES (?, NOW(), ?, 'REGISTRO', ?)
    ");

    if ($insertObs) {
        $insertObs->bind_param("iss", $idCredencial, $usuarioRegistro, $observacion);
        $insertObs->execute();
        $insertObs->close();
    }

    // ✅ RESPUESTA FINAL CON ID
    echo json_encode([
        'status'        => 'ok',
        'mensaje'       => 'Credencial registrada correctamente.',
        'idCredencial'  => $idCredencial
    ]);
    exit;

} else {
    echo json_encode([
        'status'  => 'error',
        'mensaje' => 'Error al guardar la credencial: ' . $stmt->error
    ]);
    exit;
}

$stmt->close();
