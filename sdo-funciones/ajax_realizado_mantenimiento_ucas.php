<?php


// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Verificar si se recibieron datos a través de POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;

    // Validar que el ID no sea nulo
    if ($id !== null) {
        // Marcar como realizado actualizando la columna "realizado" y "fechaRealizado"
        $fechaRealizado = date('Y-m-d'); // Fecha actual
        $sql = "UPDATE asignacion_turnos 
                SET realizado = 1, fechaRealizado = ? 
                WHERE id = ?";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("si", $fechaRealizado, $id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "El registro se ha marcado como realizado."]);
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
