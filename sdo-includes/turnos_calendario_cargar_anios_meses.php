<?php
include_once '../sdo-funciones/conexion.php';

$query = "SELECT DISTINCT YEAR(fechaTurno) AS anio, MONTH(fechaTurno) AS mes
          FROM turno_control_calendario
          ORDER BY anio DESC, mes ASC";

$resultado = mysqli_query($conexion, $query);
$datos = [];

while ($row = mysqli_fetch_assoc($resultado)) {
    $anio = $row['anio'];
    $mes = $row['mes'];
    $datos[$anio][] = $mes;
}

echo json_encode($datos);
?>
