<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
// Conexión a la base de datos
require_once '../../sdo-funciones/conexion.php'; // Ajusta la ruta si es necesario
require_once '../../sdo-funciones/funciones.php'; // Ajusta la ruta si es necesario

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar que se haya recibido el ID
    if (isset($_POST['idAsistencia']) && is_numeric($_POST['idAsistencia'])) {
        $id = intval($_POST['idAsistencia']);

        // Preparar y ejecutar la consulta
        $stmt = $conexion->prepare("DELETE FROM asistencia_sdo WHERE idAsistencia = ?");
        if ($stmt) {
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                echo 'ok';
            } else {
                echo 'Error al eliminar el registro.';
            }

            $stmt->close();
        } else {
            echo 'Error al preparar la consulta.';
        }
    } else {
        echo 'ID inválido.';
    }
} else {
    echo 'Método no permitido.';
}

$conexion->close();
?>
