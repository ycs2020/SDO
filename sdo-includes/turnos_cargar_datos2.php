<?php
include_once '../sdo-funciones/conexion.php';

$anio = isset($_GET['anio']) ? (int)$_GET['anio'] : date('Y');

$sql = "SELECT * FROM calendario_turnos WHERE YEAR(fecha) = $anio";
$res = mysqli_query($conexion, $sql);

$eventos = [];
while ($row = mysqli_fetch_assoc($res)) {
    $eventos[] = [
        'title' => $row['nombre_turno'] . ' - ' . $row['turno'],
        'start' => $row['fecha'],
        'color' => '#03cea4'
    ];
}

echo json_encode($eventos);
?>
