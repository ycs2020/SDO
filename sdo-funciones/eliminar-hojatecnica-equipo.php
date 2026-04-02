<?php

include 'conexion.php';

// Verifica si se ha enviado el ID en la solicitud POST
if (isset($_POST['id'])) {
    // Escapa y convierte el valor del ID para evitar inyección de SQL
    $id = mysqli_real_escape_string($conexion, $_POST['id']);

    // Prepara una consulta SQL
    $stmt = $conexion->prepare("UPDATE ordenes_trabajos_general SET idCategoria = NULL, idEquipo = NULL WHERE idOrden = ?");

    // Comprueba si la preparación fue exitosa
    if (!$stmt) {
        header('Content-Type: application/json');
        echo json_encode(array('status' => 'error', 'message' => 'Error en la preparación de la consulta SQL'));
        exit();
    }

    // Asigna el ID a la consulta
    $stmt->bind_param("i", $id);

    // Ejecuta la consulta
    $result = $stmt->execute();

    // Cierra la declaración preparada
    $stmt->close();

    // Determina la respuesta
    header('Content-Type: application/json');
    if ($result) {
        // Si la operación fue exitosa, devuelve un JSON con un estado de éxito
        echo json_encode(array('status' => 'success', 'message' => 'El registro fue eliminado con éxito.'));
    } else {
        // Si la operación no fue exitosa, devuelve un JSON con un estado de error
        echo json_encode(array('status' => 'error', 'message' => 'Hubo un problema al eliminar el registro.'));
    }
} else {
    // Si el ID no está establecido, devuelve un error
    header('Content-Type: application/json');
    echo json_encode(array('status' => 'error', 'message' => 'No se envió el ID en la solicitud.'));
}
?>
