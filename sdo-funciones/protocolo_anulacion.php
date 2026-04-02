<?php
include 'conexion.php';
include 'funciones.php';

date_default_timezone_set('America/Santiago');

$dateHoy = date('Y-m-d H:i:s');

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
if (!isset($_POST['idProtocolo'],$_POST['nomFuncionarioAnula'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al Obtener Identificador del Protocolo.'
    ]);
    exit();
}


// Sanitizar y validar datos
$idProtocolo = intval($_POST['idProtocolo']);
$nomFuncionarioControl = mysqli_real_escape_string($conexion, trim($_POST['nomFuncionarioAnula']));


if ($idProtocolo <= 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al Obtener Identificador del Protocolo.'
    ]);
    exit();
}

// Construir la consulta
$sqlModificar = "
    UPDATE protocolo_refrigeradores SET isAnulado = 1, fechaAnulado = '$dateHoy', nombreAnulado = '$nomFuncionarioControl' WHERE idProtocolo = '$idProtocolo' ";

// Ejecutar la consulta
if (mysqli_query($conexion, $sqlModificar)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Anulación Exitosa.'
    ]);
} else {
    // Log del error para depuración
    error_log("Error en consulta SQL: " . mysqli_error($conexion));
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al Anular Protocolo'
    ]);
}

// Cerrar la conexión
mysqli_close($conexion);
