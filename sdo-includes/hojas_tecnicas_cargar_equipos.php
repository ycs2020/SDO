<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';

$idDepartamento = intval($_POST['idDepartamento']);
$where = ($idDepartamento == 2 || $idDepartamento == 12)
    ? "idDepaPertenece IN (2, 12)"
    : "idDepaPertenece = $idDepartamento";

$sql = "SELECT * FROM equipamiento_hbqp WHERE $where ORDER BY nombreEquipo ASC";
$res = mysqli_query($conexion, $sql);

$data = [];
$num = 1;

while ($row = mysqli_fetch_assoc($res)) {
    // Obtener nombre del servicio
    $idServicio = intval($row['idServicio']);
    $nomServicio = '-';
    $resServ = mysqli_query($conexion, "SELECT nomServicio FROM servicios WHERE idServicio = $idServicio LIMIT 1");
    if ($rowServ = mysqli_fetch_assoc($resServ)) {
        $nomServicio = $rowServ['nomServicio'];
    }

    // Datos del equipo
    $data[] = [
        'num' => $num++,
        'fechaRegistro' => mb_convert_case($row['fechaRegistro'], MB_CASE_UPPER, 'UTF-8'),
        'nombreEquipo' => mb_convert_case($row['nombreEquipo'], MB_CASE_UPPER, 'UTF-8'),
        'marca' => $row['marca'],
        'recinto' => $row['recinto'] ?: '-',
        'nomServicio' => $nomServicio,
        'pma' => $row['pma'],
        'accion' => '<button type="button" onclick="obtenerid(' . $row['id'] . ')" class="btn btn-success btn-sm"><i class=\'fas fa-plus-circle\'></i> Añadir</button>'
    ];
}

header('Content-Type: application/json');
echo json_encode(['data' => $data], JSON_UNESCAPED_UNICODE);
exit;
