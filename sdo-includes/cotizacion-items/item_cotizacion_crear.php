<?php
ob_clean();
header('Content-Type: application/json; charset=utf-8');

include '../../sdo-funciones/conexion.php';

// Captura de datos del formulario
$nom    = $_POST['nomItem'];
$enlace = $_POST['enlaceItem'] ?? '';
$carac  = $_POST['caracteristicaItem'] ?? '';
$valor  = (int)$_POST['valorItem'];
$cant   = (int)$_POST['cantidadItem'];
$medida = $_POST['medidaItem'];

// Inserta el producto sin imagen primero
$sql = "INSERT INTO sc_sdo_cotizacion_item 
(nomItem, enlaceItem, caracteristicaItem, valorItem, cantidadItem, medidaItem)
VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sssiis", $nom, $enlace, $carac, $valor, $cant, $medida);

if (!$stmt->execute()) {
    echo json_encode(["status" => "error", "msg" => "Error al insertar: " . $conexion->error]);
    exit;
}

$idInsertado = $stmt->insert_id;

// Procesar imagen si se cargó
if (!empty($_FILES['imagenItem']['name'])) {
    $tmp        = $_FILES['imagenItem']['tmp_name'];
    $original   = $_FILES['imagenItem']['name'];
    $extension  = strtolower(pathinfo($original, PATHINFO_EXTENSION));
    $permitidos = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($extension, $permitidos)) {
        echo json_encode(["status" => "error", "msg" => "Extensión de imagen no permitida"]);
        exit;
    }

    // Definir nombre final y ruta destino
    $imagenNombre = "prod-cot-$idInsertado.$extension";
    $directorioDestino = "/var/www/html/sdo/sdo-img-cotiza-productos/";
    $rutaDestino = $directorioDestino . $imagenNombre;

    // Validar existencia de carpeta
    if (!is_dir($directorioDestino)) {
        if (!mkdir($directorioDestino, 0755, true)) {
            echo json_encode(["status" => "error", "msg" => "No se pudo crear la carpeta destino"]);
            exit;
        }
    }

    // Verificar permisos de escritura
    if (!is_writable($directorioDestino)) {
        echo json_encode([
            "status" => "error",
            "msg" => "La carpeta destino no es escribible",
            "carpeta" => $directorioDestino
        ]);
        exit;
    }

    // Verificar si el archivo temporal existe
    if (!file_exists($tmp)) {
        echo json_encode([
            "status" => "error",
            "msg" => "Archivo temporal no existe",
            "tmp_name" => $tmp,
            "FILES" => $_FILES['imagenItem']
        ]);
        exit;
    }

    // Intentar mover el archivo a la carpeta destino
    if (!move_uploaded_file($tmp, $rutaDestino)) {
        echo json_encode([
            "status" => "error",
            "msg" => "Fallo al mover imagen",
            "debug" => [
                "tmp_name" => $tmp,
                "destino" => $rutaDestino,
                "file_exists_tmp" => file_exists($tmp),
                "is_writable_destino" => is_writable($directorioDestino),
                "FILES" => $_FILES['imagenItem']
            ]
        ]);
        exit;
    }

    // Actualizar nombre de imagen en la BD
    $sqlUpdate = "UPDATE sc_sdo_cotizacion_item SET imagenItem=? WHERE idItem=?";
    $stmt2 = $conexion->prepare($sqlUpdate);
    $stmt2->bind_param("si", $imagenNombre, $idInsertado);
    $stmt2->execute();
}

// Fin
echo json_encode(["status" => "ok"]);
exit;
