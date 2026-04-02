<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

$query = "SELECT * FROM equipos WHERE idSubCategoria = 4 ORDER BY nomEquipo ASC";

$res = mysqli_query($conexion, $query);

$numReporte = 1;

if (!$res) {
    die("Error");
} else {
    while ($data = mysqli_fetch_assoc($res)) {
        $arreglo["data"][] = $data;
    }

    echo json_encode($arreglo);
}

mysqli_free_result($res);
mysqli_close($conexion);
