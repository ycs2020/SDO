<?php
include_once '../sdo-funciones/conexion.php';

$sql = "SELECT DISTINCT YEAR(fecha) AS anio FROM calendario_turnos ORDER BY anio ASC";
$res = mysqli_query($conexion, $sql);

$anios = [];
while ($row = mysqli_fetch_assoc($res)) {
    $anios[] = $row['anio'];
}

echo json_encode($anios);
?>
