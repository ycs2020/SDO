<?php
include_once '../sdo-funciones/conexion.php';

$debug = false;

$id = isset($_POST['turnoId']) ? (int)$_POST['turnoId'] : 0;
$fecha = $_POST['fecha'] ?? '';
$turno = $_POST['turno'] ?? '';
$funcionario_1 = trim($_POST['funcionario_1']) !== '' ? $_POST['funcionario_1'] : '';
$funcionario_2 = trim($_POST['funcionario_2']) !== '' ? $_POST['funcionario_2'] : '';

$sql = "UPDATE calendario_turnos SET 
            fecha = ?, 
            turno = ?, 
            funcionario_1 = ?, 
            funcionario_2 = ?
        WHERE id = ?";

// Mostrar sentencia como JSON para consola JS
if ($debug) {
    function sql_escape($val) {
        if (is_null($val)) return "NULL";
        return "'" . addslashes($val) . "'";
    }

    $sql_debug = sprintf(
        "UPDATE calendario_turnos SET fecha = %s, turno = %s, funcionario_1 = %s, funcionario_2 = %s WHERE id = %d;",
        sql_escape($fecha),
        sql_escape($turno),
        sql_escape($funcionario_1),
        sql_escape($funcionario_2),
        $id
    );

    echo json_encode([
        "debug" => true,
        "sql" => $sql_debug
    ]);
    exit;
}

// Ejecución real
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssssi", $fecha, $turno, $funcionario_1, $funcionario_2, $id);

if ($stmt->execute()) {
    echo json_encode(["status" => "OK"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => $stmt->error]);
}
?>
