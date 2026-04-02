<?php



// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

date_default_timezone_set('America/Santiago');

// Verificar si se recibieron los datos a través de POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $turno = isset($_POST['turno']) ? $_POST['turno'] : null;
    $funcionario = isset($_POST['funcionario']) ? $_POST['funcionario'] : null;
    $equipo1 = isset($_POST['equipo1']) ? $_POST['equipo1'] : null;
    $equipo2 = isset($_POST['equipo2']) ? $_POST['equipo2'] : null;

    // Validar que el ID no sea nulo
    if ($id !== null) {

        $date = date('Y-m-d H:i:s');
        $revisado = 1;

        // Actualizar el registro en la base de datos
        $sql = "UPDATE asignacion_turnos 
                SET turno = ?, 
                    funcionario = ?, 
                    equipo1 = ?, 
                    equipo2 = ?
                WHERE id = ?";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssssi", $turno, $funcionario, $equipo1, $equipo2, $id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Registro actualizado correctamente."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al actualizar el registro: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "ID del registro no proporcionado."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método de solicitud no permitido."]);
}

$conexion->close();
?>
