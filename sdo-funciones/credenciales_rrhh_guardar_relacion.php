<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../sdo-funciones/conexion.php';
require '../sdo-funciones/funciones.php';

header('Content-Type: application/json');

session_start();
$usuario = $_SESSION['nomUsuario'] ?? 'SISTEMA';

$idCredencial = (int)($_POST['idCredencial'] ?? 0);
$idCalidad    = (int)($_POST['idCalidad'] ?? 0);
$idCargo      = (int)($_POST['idCargo'] ?? 0);
$idUnidad     = (int)($_POST['idUnidad'] ?? 0);
$idJefatura   = (int)($_POST['idJefatura'] ?? 0);
$inicio       = $_POST['inicio'] ?? '';
$termino      = $_POST['termino'] ?? null;

$nomCalidad  = $_POST['nomCalidad'] ?? '';
$nomCargo    = $_POST['nomCargo'] ?? '';
$nomUnidad   = $_POST['nomUnidad'] ?? '';
$nomJefatura = $_POST['nomJefatura'] ?? '';

if (!$idCredencial || !$idCalidad || !$idCargo || !$idUnidad || !$idJefatura || !$inicio) {
    echo json_encode(['status' => 'error', 'mensaje' => 'Datos incompletos']);
    exit;
}

// Validar duplicado
$verifica = $conexion->prepare("SELECT COUNT(*) AS total FROM credenciales_rrhh_contratos WHERE idCredencial = ? AND idCalidad = ? AND idCargo = ? AND idUnidad = ? AND idJefatura = ? AND inicioContrato = ? AND terminoContrato = ?");
$verifica->bind_param("iiiiiss", $idCredencial, $idCalidad, $idCargo, $idUnidad, $idJefatura, $inicio, $termino);
$verifica->execute();
$existe = $verifica->get_result()->fetch_assoc()['total'] ?? 0;
$verifica->close();

if ($existe > 0) {
    echo json_encode(['status' => 'existe', 'mensaje' => 'Ya existe una relación con estos datos.']);
    exit;
}

// Insertar relación
$insert = $conexion->prepare("INSERT INTO credenciales_rrhh_contratos (idCredencial, idCalidad, idCargo, idUnidad, idJefatura, inicioContrato, terminoContrato) VALUES (?, ?, ?, ?, ?, ?, ?)");
$insert->bind_param("iiiiiss", $idCredencial, $idCalidad, $idCargo, $idUnidad, $idJefatura, $inicio, $termino);
$ok = $insert->execute();
$insert->close();

if ($ok) {
    // Registrar observación automática
    $textoObs = "Nueva relación laboral agregada el " . date('d-m-Y') . ":\n"
        . "• Calidad Jurídica: $nomCalidad\n"
        . "• Cargo: $nomCargo\n"
        . "• Unidad: $nomUnidad\n"
        . "• Jefatura: $nomJefatura\n"
        . "• Inicio: $inicio\n"
        . "• Término: " . ($termino ?: 'No definido');

    $tipoObs = "Nueva Relacion Laboral";

    $obs = $conexion->prepare("INSERT INTO credenciales_rrhh_observaciones (idCredencial, fechaObservacion, usuarioObservacion, tipoObservacion, observacion) VALUES (?, NOW(), ?, ?, ?)");
    $obs->bind_param("isss", $idCredencial, $usuario, $tipoObs, $textoObs);
    $obs->execute();
    $obs->close();

    echo json_encode(['status' => 'ok']);
} else {
    echo json_encode(['status' => 'error', 'mensaje' => 'Error al guardar relación.']);
}
