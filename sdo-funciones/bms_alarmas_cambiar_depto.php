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
if (!isset($_POST['idCategoria'],$_POST['deptoSeleccion'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al Obtener Identificador de la Categoria o el Identificador del Departamento.'
    ]);
    exit();
}


// Sanitizar y validar datos
$idCategoria = intval($_POST['idCategoria']);
$deptoSeleccion = intval($_POST['deptoSeleccion']);


if ($idCategoria <= 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al Obtener Identificador de la Categoria.'
    ]);
    exit();
}

if ($deptoSeleccion <= 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al Obtener Identificador del Departamento.'
    ]);
    exit();
}

// Construir la consulta
$sqlModificar = "
    UPDATE bms_alarmas_categoria SET idDepartamento = $deptoSeleccion WHERE idCategoria = $idCategoria ";

// Ejecutar la consulta
if (mysqli_query($conexion, $sqlModificar)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Modificación Exitosa.'
    ]);
} else {
    // Log del error para depuración
    error_log("Error en consulta SQL: " . mysqli_error($conexion));
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al Modificar Departamento'
    ]);
}

// Cerrar la conexión
mysqli_close($conexion);
