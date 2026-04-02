<?php
// Archivo: actas_cargar_listado.php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

$sql = "SELECT * FROM actas ORDER BY unidadTarjeta, nomTarjeta ASC;";

$stmt = $conexion->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$datos = array();

while ($row = $result->fetch_assoc()) {
    $row['nomTarjeta'] = mb_convert_case($row['nomTarjeta'], MB_CASE_UPPER, 'UTF-8');
    $row['rutTarjeta'] = mb_convert_case($row['rutTarjeta'], MB_CASE_UPPER, 'UTF-8');
    $row['codigoTarjeta'] = mb_convert_case($row['codigoTarjeta'], MB_CASE_UPPER, 'UTF-8');
    $row['unidadTarjeta'] = mb_convert_case($row['unidadTarjeta'], MB_CASE_UPPER, 'UTF-8');
    $datos[] = $row;
}

$stmt->close();
$conexion->close();

echo json_encode($datos);