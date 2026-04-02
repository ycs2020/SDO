<?php
error_reporting(E_ALL); // Reporta todos los errores
ini_set('display_errors', 1); // Muestra los errores en la salida


include_once 'conexion.php';
include_once 'funciones.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id']) && is_numeric($_POST['id'])) {
        $id = intval($_POST['id']); // Asegúrate de que id es un número entero

        // Obtén la ruta del archivo
        $rutaArchivo = obtenerRutaDocumentacionEquipamiento($id);

        // Verifica si la ruta del archivo es válida
        if ($rutaArchivo && file_exists($rutaArchivo)) {
            // Intenta eliminar el archivo
            if (unlink($rutaArchivo)) {
                // Elimina el registro en la base de datos
                $sql = "DELETE FROM `archivos_equipamiento_hbqp` WHERE id = ?";
                if ($stmt = mysqli_prepare($conexion, $sql)) {
                    mysqli_stmt_bind_param($stmt, 'i', $id);
                    $resSQL = mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);

                    if ($resSQL) {
                        echo json_encode(['status' => 'success', 'message' => 'Documento fue eliminado con exito']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Error al intentar eliminar el archivo de la Base de Datos']);
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error en la consulta SQL']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al intentar eliminar el archivo']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'El archivo no existe o la ruta no es válida']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID no válido']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no permitido']);
}
?>
