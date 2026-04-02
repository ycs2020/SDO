<?php
header('Content-Type: application/json');

include_once 'conexion.php';
include_once 'funciones.php';

if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Error de conexión']);
    exit;
}

// Obtener datos POST
$usuario = $_POST['usuario'] ?? '';
$claveIngresada = $_POST['clave'] ?? '';


// Verificar que el usuario sea exactamente 'ycasimis'
if ($usuario !== 'ycasimis') {
    echo json_encode(['status' => 'error', 'message' => 'Usuario no autorizado']);
    exit;
}

// Validar en BD (ejemplo: claves_trabajos con columnas usuario, clave)
$stmt = $conexion->prepare("SELECT idJefe FROM jefes_tecnicos WHERE userJefe LIKE ? AND passJefe LIKE ?");
$stmt->bind_param('ss', $usuario, $claveIngresada);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    echo json_encode(['status' => 'ok']);
} else {
    echo json_encode(['status' => 'error']);
}

$stmt->close();
$conexion->close();
?>
<?php
