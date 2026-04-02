<?php
include '../sdo-funciones/conexion.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha = $_POST['addFechaTurno'] ?? null;
    $funcionario = $_POST['addFuncionario'] ?? null;

    if (!$fecha || !$funcionario) {
        echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
        exit;
    }

    // Validar si ya existe turno ese día para ese funcionario
    $stmt = $conexion->prepare("
        SELECT COUNT(*) AS total FROM calendario_turnos 
        WHERE fecha = ? AND turno IN ('Día', 'Noche', 'Extra')
        AND (funcionario_1 = ? OR funcionario_2 = ?)
    ");
    $stmt->bind_param("sss", $fecha, $funcionario, $funcionario);
    $stmt->execute();
    $stmt->bind_result($existe);
    $stmt->fetch();
    $stmt->close();

    if ($existe > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Este funcionario ya tiene un turno asignado ese día.']);
        exit;
    }

    // Insertar turno extra
    $turno = 'Extra';
    $funcionario2 = '';

    $stmt = $conexion->prepare("INSERT INTO calendario_turnos (fecha, turno, funcionario_1, funcionario_2, horasFuncionario) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $fecha, $turno, $funcionario, $funcionario2, $horas);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'ok']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al insertar']);
    }

    $stmt->close();
    $conexion->close();
}
