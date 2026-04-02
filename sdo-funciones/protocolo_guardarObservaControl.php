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
if (!isset($_POST['idProtocolo'], $_POST['nomFuncionarioControl'], $_POST['controlAlarma'], $_POST['observaciones'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Datos incompletos. Verifique e intente nuevamente.'
    ]);
    exit();
}


// Sanitizar y validar datos
$idProtocolo = intval($_POST['idProtocolo']);
$nomFuncionarioControl = mysqli_real_escape_string($conexion, trim($_POST['nomFuncionarioControl']));
$controlAlarma = mysqli_real_escape_string($conexion, trim($_POST['controlAlarma']));
$observaciones = mysqli_real_escape_string($conexion, trim($_POST['observaciones']));


if($controlAlarma == 'Activado'){
    $controlAlarma2 = 1;
}else if($controlAlarma == 'No Activado'){
    $controlAlarma2 = 2;
}else{
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al Seleccionar Activacion de Alarma.'
    ]);
    exit();
}

if ($idProtocolo <= 0 || empty($nomFuncionarioControl) || empty($controlAlarma) || empty($observaciones)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Datos inválidos. Verifique e intente nuevamente.'
    ]);
    exit();
}

// Construir la consulta
$sqlModificar = "
    UPDATE protocolo_refrigeradores SET creadorControl = '$nomFuncionarioControl', controlEquipo = '$controlAlarma2', observacionControl = '$observaciones' WHERE idProtocolo = '$idProtocolo' ";

// Ejecutar la consulta
if (mysqli_query($conexion, $sqlModificar)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Observaciones guardadas exitosamente.'
    ]);
} else {
    // Log del error para depuración
    error_log("Error en consulta SQL: " . mysqli_error($conexion));
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al Guardar Observación.'
    ]);
}

// Cerrar la conexión
mysqli_close($conexion);
