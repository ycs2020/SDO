<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

include_once 'asistencia_enviar_correo_funcion.php'; // el archivo que contiene enviarCorreoTurnos()


if (!isset($_POST['turno']) || !isset($_POST['fecha'])) {
    echo json_encode(['status' => 'error', 'message' => 'Faltan parámetros.']);
    exit;
}

$turno = $_POST['turno'];
$fecha = $_POST['fecha']; // formato yyyy-mm-dd

$respuesta = enviarCorreoTurnos($turno, $fecha);

if (strpos($respuesta, 'Correo Enviado') !== false) {
    echo json_encode(['status' => 'success', 'message' => $respuesta]);
} else {
    echo json_encode(['status' => 'error', 'message' => $respuesta]);
}
