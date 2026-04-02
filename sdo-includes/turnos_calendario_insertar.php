<?php
include_once '../sdo-funciones/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $funcionario = trim(isset($_POST['funcionarioTurno']) ? $_POST['funcionarioTurno'] : '');
    $tipo = trim(isset($_POST['tipoTurno']) ? $_POST['tipoTurno'] : '');
    $fecha = isset($_POST['fechaTurno']) ? $_POST['fechaTurno'] : '';

    if ($funcionario && $tipo && $fecha) {
        // Validar duplicado por funcionario + fecha
        $q = $conexion->prepare("SELECT COUNT(*) FROM turno_control_calendario WHERE funcionarioTurno = ? AND fechaTurno = ?");
        $q->bind_param("ss", $funcionario, $fecha);
        $q->execute();
        $q->bind_result($existe);
        $q->fetch();
        $q->close();

        if ($existe > 0) {
            http_response_code(409);
            echo "El funcionario ya tiene un turno asignado ese día.";
            exit;
        }

        $ins = $conexion->prepare("INSERT INTO turno_control_calendario (funcionarioTurno, tipoTurno, fechaTurno) VALUES (?, ?, ?)");
        $ins->bind_param("sss", $funcionario, $tipo, $fecha);
        if ($ins->execute()) {
            echo "ok";
        } else {
            http_response_code(500);
            echo "Error al guardar en la base de datos.";
        }
        $ins->close();
    } else {
        http_response_code(400);
        echo "Datos incompletos";
    }
}
