<?php
include_once '../sdo-funciones/conexion.php';
session_start(); // Asegúrate de tener la sesión iniciada

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idTurno     = isset($_POST['idTurno']) ? intval($_POST['idTurno']) : 0;
    $funcionario = trim($_POST['funcionarioTurno'] ?? '');
    $tipo        = trim($_POST['tipoTurno'] ?? '');
    $fecha       = $_POST['fechaTurno'] ?? '';
    $detalle     = trim($_POST['detalleReemplazo'] ?? '');

    if ($idTurno > 0 && $funcionario && $tipo && $fecha && $detalle) {

        // Validar duplicado
        $q = $conexion->prepare("SELECT COUNT(*) FROM turno_control_calendario WHERE funcionarioTurno = ? AND fechaTurno = ? AND idTurno <> ?");
        $q->bind_param("ssi", $funcionario, $fecha, $idTurno);
        $q->execute();
        $q->bind_result($existe);
        $q->fetch();
        $q->close();

        if ($existe > 0) {
            http_response_code(409);
            echo "El funcionario ya tiene un turno asignado ese día.";
            exit;
        }

        // Obtener valores antiguos
        $sqlPrevio = "SELECT funcionarioTurno, tipoTurno, fechaTurno FROM turno_control_calendario WHERE idTurno = ?";
        $prev = $conexion->prepare($sqlPrevio);
        $prev->bind_param("i", $idTurno);
        $prev->execute();
        $resPrev = $prev->get_result();
        $antiguo = $resPrev->fetch_assoc();
        $prev->close();

        // Actualizar el turno principal (sin detalle)
        $up = $conexion->prepare("UPDATE turno_control_calendario SET funcionarioTurno = ?, tipoTurno = ?, fechaTurno = ? WHERE idTurno = ?");
        $up->bind_param("sssi", $funcionario, $tipo, $fecha, $idTurno);

        if ($up->execute()) {
            $up->close();

            // Insertar en tabla de registro
            $usuarioEditor = $_SESSION['nombreUsuario'] ?? 'Desconocido';
            $bitacora = $conexion->prepare("
                INSERT INTO turnos_calendario_registro_ediciones 
                (idTurno, funcionarioAnterior, tipoAnterior, fechaAnterior, detalleReemplazo, usuarioEditor)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $bitacora->bind_param(
                "isssss",
                $idTurno,
                $antiguo['funcionarioTurno'],
                $antiguo['tipoTurno'],
                $antiguo['fechaTurno'],
                $detalle,
                $usuarioEditor
            );
            $bitacora->execute();
            $bitacora->close();

            echo "ok";

        } else {
            $up->close();
            http_response_code(500);
            echo "Error al actualizar en la base de datos.";
        }
    } else {
        http_response_code(400);
        echo "Datos incompletos.";
    }
}
