<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

$idBitacora = obtenerIDBitacora();

$sql = "SELECT idSubcategoria, nomSubcategoria FROM subcategorias WHERE idCategoria = 19 ORDER BY nomSubcategoria ASC";
$result = $conexion->query($sql);

$subcategorias = array();
while ($row = $result->fetch_assoc()) {
    $subcategorias[] = $row;
}

$conexion->close();
header('Content-Type: application/json');
echo json_encode($subcategorias);
