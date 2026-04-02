<?php
ob_clean();
header('Content-Type: application/json; charset=utf-8');
include '../../sdo-funciones/conexion.php';

$idItem = intval($_POST['idItem']);
$directorio = "/var/www/html/sdo/sdo-img-cotiza-productos/";

// Obtener imagen actual
$sql = "SELECT imagenItem FROM sc_sdo_cotizacion_item WHERE idItem=?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idItem);
$stmt->execute();
$stmt->bind_result($imagenActual);
$stmt->fetch();
$stmt->close();

// Eliminar producto
$sqlDelete = "DELETE FROM sc_sdo_cotizacion_item WHERE idItem=?";
$stmt2 = $conexion->prepare($sqlDelete);
$stmt2->bind_param("i", $idItem);
if (!$stmt2->execute()) {
    echo json_encode(["status" => "error", "msg" => "Error al eliminar: " . $conexion->error]);
    exit;
}

// Eliminar imagen del disco si existe
if (!empty($imagenActual)) {
    $ruta = $directorio . $imagenActual;
    if (file_exists($ruta)) {
        unlink($ruta);
    }
}

echo json_encode(["status" => "ok"]);
exit;
