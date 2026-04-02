<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';
date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$response = ['error' => 'No se pudo obtener los datos'];

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = "SELECT * FROM presupuesto_equipos WHERE id = $id";
    $resultado = mysqli_query($conexion, $sql);
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $row = mysqli_fetch_assoc($resultado);
        $response = [
            'nombre' => $row['nombreEquipo'],
            'valor'=> $row['valorEquipo']
        ];
    } else {
        $response['error'] = 'No se encontraron datos para el ID proporcionado';
    }
} else {
    $response['error'] = 'No se proporcionó un ID válido';
}

header('Content-Type: application/json');
echo json_encode($response);
?>

