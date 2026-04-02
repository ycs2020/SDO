<?php
include_once '../sdo-funciones/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idTurno = isset($_POST['idTurno']) ? intval($_POST['idTurno']) : 0;

    if ($idTurno > 0) {
        $del = $conexion->prepare("DELETE FROM turno_control_calendario WHERE idTurno = ?");
        $del->bind_param("i", $idTurno);
        if ($del->execute()) {
            echo "ok";
        } else {
            http_response_code(500);
            echo "No se pudo eliminar.";
        }
        $del->close();
    } else {
        http_response_code(400);
        echo "ID inválido.";
    }
}
