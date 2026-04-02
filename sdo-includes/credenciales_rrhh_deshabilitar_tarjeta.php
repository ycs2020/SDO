<?php
header('Content-Type: application/json');
require '../sdo-funciones/conexion.php';
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
date_default_timezone_set('America/Santiago');

// ======================================
// 🔐 Validaciones iniciales
// ======================================
$idRegistro = isset($_POST['idRegistro']) ? intval($_POST['idRegistro']) : 0;
$usuario    = $_SESSION['nomUsuario'] ?? 'Plataforma SDO';
$fechaAhora = date('Y-m-d H:i:s');

if ($idRegistro <= 0) {
    echo json_encode(['status' => 'error', 'mensaje' => 'ID de tarjeta inválido']);
    exit;
}

// ======================================
// 🔍 Buscar datos de la tarjeta
// ======================================
$sqlDatos = "SELECT crrt.idCredencial, crt.codigoTarjeta
             FROM credenciales_rrhh_registro_tarjetas crrt
             JOIN credenciales_rrhh_tarjetas crt ON crrt.idTarjeta = crt.idTarjeta
             WHERE crrt.idRegistro = ?";

$stmtDatos = $conexion->prepare($sqlDatos);
$stmtDatos->bind_param("i", $idRegistro);
$stmtDatos->execute();
$resDatos = $stmtDatos->get_result();

if ($resDatos->num_rows === 0) {
    echo json_encode(['status' => 'error', 'mensaje' => 'Registro no encontrado']);
    exit;
}

$row = $resDatos->fetch_assoc();
$idCredencial = $row['idCredencial'];
$codigoTarjeta = $row['codigoTarjeta'];
$stmtDatos->close();

// ======================================
// 🔄 Actualizar activación a "Por deshabilitar"
// ======================================
$sqlUpdate = "UPDATE credenciales_rrhh_registro_tarjetas 
              SET activacionCredencial = 3, terminoTarjeta = ?
              WHERE idRegistro = ?";

$stmtUpdate = $conexion->prepare($sqlUpdate);
$stmtUpdate->bind_param("si", $fechaAhora, $idRegistro);

if (!$stmtUpdate->execute()) {
    echo json_encode(['status' => 'error', 'mensaje' => 'Error al deshabilitar tarjeta']);
    exit;
}
$stmtUpdate->close();

// ======================================
// 📝 Registrar observación
// ======================================
$textoObs = "Se marcó como 'Por deshabilitar' la tarjeta código [$codigoTarjeta].";

$sqlObs = "INSERT INTO credenciales_rrhh_observaciones 
           (idCredencial, fechaObservacion, usuarioObservacion, tipoObservacion, observacion)
           VALUES (?, ?, ?, 'Deshabilitación manual', ?)";

$stmtObs = $conexion->prepare($sqlObs);
$stmtObs->bind_param("isss", $idCredencial, $fechaAhora, $usuario, $textoObs);
$stmtObs->execute();
$stmtObs->close();

// ======================================
// ✅ Éxito
// ======================================
echo json_encode(['status' => 'ok', 'mensaje' => 'Tarjeta marcada como deshabilitada correctamente']);
