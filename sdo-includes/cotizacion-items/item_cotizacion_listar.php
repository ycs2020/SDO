<?php
ob_clean();
header('Content-Type: application/json; charset=utf-8');

include '../../sdo-funciones/conexion.php';

// Ruta de las imágenes
$rutaImagenes = "/var/www/html/sdo/sdo-img-cotiza-productos/";
$rutaWeb = "/sdo-img-cotiza-productos/";

// Crear la carpeta si no existe
if (!file_exists($rutaImagenes)) {
    mkdir($rutaImagenes, 0755, true); // permisos y recursive = true
}

$sql = "SELECT idItem, nomItem, valorItem, cantidadItem, medidaItem, imagenItem FROM sc_sdo_cotizacion_item ORDER BY idItem DESC";
$res = $conexion->query($sql);

$data = [];

while ($row = $res->fetch_assoc()) {
    // Validar existencia de imagen
    $imagenNombre = $row['imagenItem'];
    $imagenRutaFS = $rutaImagenes . $imagenNombre;
    $imagenWeb = $rutaWeb . $imagenNombre;

    $row['imagenItem'] = (file_exists($imagenRutaFS) && !empty($imagenNombre))
        ? "<img src='$imagenWeb' alt='img' height='40'>"
        : "<span class='text-muted'>Sin imagen</span>";

    $data[] = $row;
}

echo json_encode(["data" => $data]);
exit;
