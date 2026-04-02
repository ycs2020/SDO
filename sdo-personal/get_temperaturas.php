<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

$idReporte = intval($_GET['idReporte']);

$sql = "SELECT * FROM temperaturas_transformadores WHERE idReporte = $idReporte ORDER BY trafo, linea, hora";
$res = mysqli_query($conexion, $sql);

$datos = [];
while ($row = mysqli_fetch_assoc($res)) {
    $trafo = $row['trafo'];
    $linea = $row['linea'];
    $hora = substr($row['hora'], 0, 5); // Normalizamos hora a formato "HH:MM"
    $temp = $row['temperatura'];

    $datos[$trafo][$linea][$hora] = $temp;
}

// Devolver como JSON
header('Content-Type: application/json');
echo json_encode($datos);

// Cerrar conexión (opcional pero recomendable)
mysqli_close($conexion);

