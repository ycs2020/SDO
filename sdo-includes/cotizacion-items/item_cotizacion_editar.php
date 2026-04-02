<?php
ob_clean();
header('Content-Type: application/json; charset=utf-8');
include '../../sdo-funciones/conexion.php';

// Captura datos
$idItem  = intval($_POST['edit_idItem']);
$nom     = $_POST['edit_nomItem'];
$enlace  = $_POST['edit_enlaceItem'] ?? '';
$carac   = $_POST['edit_caracteristicaItem'] ?? '';
$valor   = (int)$_POST['edit_valorItem'];
$cant    = (int)$_POST['edit_cantidadItem'];
$medida  = $_POST['edit_medidaItem']; // texto

// Carpeta destino
$directorio = "/var/www/html/sdo/sdo-img-cotiza-productos/";

// Validar que existe
if (!is_dir($directorio)) {
    mkdir($directorio, 0755, true);
}

// 1. Obtener imagen actual
$sql = "SELECT imagenItem FROM sc_sdo_cotizacion_item WHERE idItem=?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idItem);
$stmt->execute();
$stmt->bind_result($imagenActual);
$stmt->fetch();
$stmt->close();

// 2. Actualizar datos principales
$sqlUpdate = "UPDATE sc_sdo_cotizacion_item 
              SET nomItem=?, enlaceItem=?, caracteristicaItem=?, valorItem=?, cantidadItem=?, medidaItem=?
              WHERE idItem=?";

$stmt2 = $conexion->prepare($sqlUpdate);
if (!$stmt2) {
    echo json_encode(["status" => "error", "msg" => "Prepare falló: " . $conexion->error]);
    exit;
}

$stmt2->bind_param("sssissi", $nom, $enlace, $carac, $valor, $cant, $medida, $idItem);

// SQL DEBUG
$sqlDebug = sprintf(
    "UPDATE sc_sdo_cotizacion_item SET nomItem='%s', enlaceItem='%s', caracteristicaItem='%s', valorItem=%d, cantidadItem=%d, medidaItem='%s' WHERE idItem=%d",
    $conexion->real_escape_string($nom),
    $conexion->real_escape_string($enlace),
    $conexion->real_escape_string($carac),
    $valor,
    $cant,
    $conexion->real_escape_string($medida),
    $idItem
);

// Ejecutar update
if (!$stmt2->execute()) {
    echo json_encode([
        "status" => "error",
        "msg" => "Error al actualizar",
        "sql_debug" => $sqlDebug,
        "mysql_error" => $stmt2->error
    ]);
    exit;
}

// 3. Procesar imagen si se subió
if (!empty($_FILES['edit_imagenItem']['name'])) {

    $tmp       = $_FILES['edit_imagenItem']['tmp_name'];
    $original  = $_FILES['edit_imagenItem']['name'];
    $ext       = strtolower(pathinfo($original, PATHINFO_EXTENSION));
    $permitidos = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($ext, $permitidos)) {
        echo json_encode(["status" => "error", "msg" => "Extensión no permitida", "ext" => $ext]);
        exit;
    }

    $nuevaImagen = "prod-cot-$idItem.$ext";
    $rutaDestino = $directorio . $nuevaImagen;

    // Eliminar imagen anterior si existe
    if (!empty($imagenActual) && file_exists($directorio . $imagenActual)) {
        unlink($directorio . $imagenActual);
    }

    // Subir nueva imagen
    if (!move_uploaded_file($tmp, $rutaDestino)) {
        echo json_encode([
            "status" => "error",
            "msg" => "Fallo al mover la nueva imagen",
            "tmp" => $tmp,
            "destino" => $rutaDestino,
            "file_exists_tmp" => file_exists($tmp),
            "is_writable_destino" => is_writable($directorio),
            "FILES" => $_FILES['edit_imagenItem']
        ]);
        exit;
    }

    // Guardar nombre nuevo
    $sqlImg = "UPDATE sc_sdo_cotizacion_item SET imagenItem=? WHERE idItem=?";
    $stmt3 = $conexion->prepare($sqlImg);
    $stmt3->bind_param("si", $nuevaImagen, $idItem);
    $stmt3->execute();
}

// 4. Final OK
echo json_encode([
    "status" => "ok",
//    "sql_debug" => $sqlDebug
]);
exit;
