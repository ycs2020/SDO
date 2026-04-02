<?php
include 'conexion.php';
include 'funciones.php';

date_default_timezone_set('America/Santiago');
session_start();
header('Content-Type: application/json; charset=utf-8');


// Variables de sesión
$userUsuario = $_SESSION['userUsuario'] ?? null;
$idUsuario = $_SESSION['idUsuario'] ?? null;
$nomUsuario = $_SESSION['nomUsuario'] ?? null;
$rolUsuario = $_SESSION['rolUsuario'] ?? null;

$rolUsuario2 = strtolower($rolUsuario);
$rolesRutas = [
    'personal' => 'sdo-personal',
    'administrativo' => 'sdo-admtec',
    'bodega pañol' => 'sdo-bodega-panol',
    'jefe tecnico' => 'sdo-jefestecnicos',
    'administrador' => 'sdo-administrador',
    'directiva' => 'sdo-directiva'
];
$ruta = $rolesRutas[$rolUsuario2] ?? null;

if (!$ruta) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Sesión inválida. Por favor, inicie sesión nuevamente.'
    ]);
    exit();
}

// Validar POST
if (!isset($_POST['idAsocia'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Datos incompletos. Verifique e intente nuevamente.'
    ]);
    exit();
}


// Sanitizar y validar datos
$idAsocia = intval($_POST['idAsocia']);

if ($idAsocia <= 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Datos inválidos. Verifique e intente nuevamente.'
    ]);
    exit();
}

// Construir la consulta
$sqlEliminar = "DELETE FROM bms_asocia_source WHERE idAsocia = $idAsocia";

// Ejecutar la consulta
if (mysqli_query($conexion, $sqlEliminar)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Source desasociado exitosamente.'
    ]);
} else {
    // Log del error para depuración
    error_log("Error en consulta SQL: " . mysqli_error($conexion));
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al Desasociar Source.'
    ]);
}

// Cerrar la conexión
mysqli_close($conexion);
