<?php
require_once 'conexion.php';

$idReporte = isset($_GET['idReporte']) ? intval($_GET['idReporte']) : 0;

$upsList = ['SAI-EST', 'SAI-EST 1', 'SAI-EST 2', 'DIN 1', 'DIN IMA'];

$sql = "SELECT * FROM ups_registro WHERE idReporte = $idReporte AND ups IN ('" . implode("','", $upsList) . "')";
$res = mysqli_query($conexion, $sql);

$datos = [];

while ($row = mysqli_fetch_assoc($res)) {
    $ups = $row['ups'];
    $hora = trim($row['hora']);
    $datos[$ups][$hora] = [
        'temperatura' => $row['temperatura'],
        'alarma' => $row['alarma'],
        'ultimo_evento' => $row['ultimo_evento'],
    ];
}

echo json_encode($datos);
