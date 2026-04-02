<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once 'conexion.php';

$response = ['success' => false, 'message' => ''];

try {
    $nombre  = trim($_POST['nomProducto'] ?? '');
    $unidad  = trim($_POST['unidadProducto'] ?? '');
    $cantidad = (int)($_POST['cantidadProducto'] ?? 0);
    $valor    = (int)($_POST['valorProducto'] ?? 0);
    $enlace   = trim($_POST['enlaceProducto'] ?? '');
    $imagenNombre = null;

    // Validación básica
    if ($nombre === '' || $unidad === '' || $cantidad <= 0) {
        throw new Exception('Faltan campos obligatorios o valores inválidos.');
    }

    // 1. Insertar sin imagen
    $stmt = $conexion->prepare("INSERT INTO producto_solicitud (nombre, unidad_medida, cantidad, valor, enlace_archivo) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiis", $nombre, $unidad, $cantidad, $valor, $enlace);

    if ($stmt->execute()) {
        $idInsertado = $stmt->insert_id;

        // 2. Procesar imagen (opcional)
        if (isset($_FILES['imagenProducto']) && $_FILES['imagenProducto']['error'] === 0) {
            $directorio = "../sdo-material-solic/imagen-" . $idInsertado . '/';
            $directorioBD = "sdo-material-solic/imagen-" . $idInsertado . '/';

            if (!file_exists($directorio)) {
                if (mkdir($directorio, 0775, true)) {
                    chmod($directorio, 0775); // asegurar permisos
                } else {
                    throw new Exception("No se pudo crear el directorio: $directorio");
                }
            }

            $nombreOriginal = basename($_FILES['imagenProducto']['name']);
            $ext = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
            $nombreUnico = uniqid('img_') . '.' . $ext;

            $rutaFinal = $directorio . $nombreUnico;
            $rutaParaBD = $directorioBD . $nombreUnico; // ✅ Ruta completa para guardar en la BD

            if (move_uploaded_file($_FILES['imagenProducto']['tmp_name'], $rutaFinal)) {
                $stmtUpdate = $conexion->prepare("UPDATE producto_solicitud SET imagen = ? WHERE id = ?");
                $stmtUpdate->bind_param("si", $rutaParaBD, $idInsertado);
                $stmtUpdate->execute();
                $stmtUpdate->close();
            } else {
                $response['success'] = true;
                $response['message'] = 'Material guardado, pero no se pudo subir la imagen.';
                echo json_encode($response);
                exit;
            }
        }

        $response['success'] = true;
        $response['message'] = 'Material agregado correctamente.';
    } else {
        $response['message'] = 'Error al insertar en la base de datos.';
    }

    $stmt->close();
    $conexion->close();
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
