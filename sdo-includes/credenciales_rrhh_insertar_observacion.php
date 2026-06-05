<?php
header('Content-Type: application/json; charset=utf-8');
include '../sdo-funciones/conexion.php';

$conexion->set_charset('utf8mb4');

$idCredencial = isset($_POST['idCredencial']) ? (int)$_POST['idCredencial'] : 0;
$usuario = trim($_POST['usuario'] ?? '');
$texto = trim($_POST['observacion'] ?? '');

if ($idCredencial <= 0 || empty($usuario) || empty($texto)) {
    echo json_encode(['status' => 'error', 'mensaje' => 'Datos incompletos']);
    exit;
}

$sql = $conexion->prepare("
    INSERT INTO credenciales_rrhh_observaciones (idCredencial, fechaObservacion, usuarioObservacion, tipoObservacion, observacion,privado)
    VALUES (?, NOW(), ?, 'MANUAL', ?,0)
");

if (!$sql) {
    echo json_encode(['status' => 'error', 'mensaje' => 'Error en la preparación de la consulta']);
    exit;
}

$sql->bind_param("iss", $idCredencial, $usuario, $texto);
$exito = $sql->execute();

if ($exito) {
    echo json_encode(['status' => 'ok']);
} else {
    echo json_encode(['status' => 'error', 'mensaje' => 'No se pudo registrar la observación']);
}

$sql->close();
$conexion->close();
