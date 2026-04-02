<?php
ob_clean();
header('Content-Type: application/json; charset=utf-8');
include '../../sdo-funciones/conexion.php';

$idItem = intval($_POST['idItem']);

$sql = "SELECT idItem, nomItem, enlaceItem, caracteristicaItem, valorItem, cantidadItem, medidaItem, imagenItem 
        FROM sc_sdo_cotizacion_item WHERE idItem=?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idItem);
$stmt->execute();

$res = $stmt->get_result();
echo json_encode($res->fetch_assoc());
exit;
