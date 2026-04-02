<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../sdo-funciones/conexion.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['editIdTurno'] ?? null;
    $fecha = $_POST['editFecha'] ?? null;
    $funcionario1 = $_POST['editFuncionario1'] ?? null;
    $funcionario2 = $_POST['editFuncionario2'] ?? null;

    if (!$id || !$fecha) {
        echo json_encode(['status' => 'error', 'message' => 'Faltan datos']);
        exit;
    }


    $stmt = $conexion->prepare("UPDATE calendario_turnos SET fecha = ?, funcionario_1 = ?, funcionario_2 = ? WHERE id = ?");
    $stmt->bind_param("sssi", $fecha, $funcionario1, $funcionario2, $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'ok']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al guardar.']);
    }

    $stmt->close();
    $conexion->close();
}
?>
