<?php
// Incluir conexión a la base de datos
include_once('conexion.php'); // Cambia la ruta según corresponda

// Verificar si se envió el ID de historial
if (isset($_POST['idObservacion']) && !empty($_POST['idObservacion']) && $_POST['idObservacion'] > 0) {
    $idHistorial = intval($_POST['idObservacion']); // Asegurarse de que sea un número entero

    // Verificar conexión a la base de datos
    if ($conexion->connect_error) {
        echo json_encode([
            "status" => "error",
            "message" => "Error al conectar con la base de datos: " . $conexion->connect_error
        ]);
        exit;
    }

    // Preparar y ejecutar la consulta para eliminar
    $sql = "DELETE FROM bms_alarmas_observacion WHERE idObservacion = ?";
    $stmt = $conexion->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $idHistorial);

        if ($stmt->execute()) {
            echo json_encode([
                "status" => "success",
                "message" => "Observación eliminada correctamente."
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Error al ejecutar la consulta: " . $stmt->error
            ]);
        }

        $stmt->close();
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Error al preparar la consulta: " . $conexion->error
        ]);
    }

    // Cerrar la conexión
    $conexion->close();
} else {
    // Respuesta si no se envió un ID válido
    echo json_encode([
        "status" => "error",
        "message" => "ID de historial inválido o no enviado."
    ]);
}
?>
