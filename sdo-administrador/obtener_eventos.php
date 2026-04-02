<?php

include '../sdo-funciones/conexion.php';

$sqlSelect = "SELECT * FROM evento_programado_equipo";
$resSelect = mysqli_query($conexion, $sqlSelect);

$eventos = array();

while($row = mysqli_fetch_assoc($resSelect)){
    $eventos[] = array(
        'title' => $row['titulo'],
        'description' => $row['descripcion'],
        'start' => $row['inicio'],
        'end' => $row['fin'],
        'color' => $row['color']
    );
}

$json = json_encode($eventos);

echo $json

?>