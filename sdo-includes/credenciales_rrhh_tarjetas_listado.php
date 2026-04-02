<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

header('Content-Type: application/json; charset=utf-8');

$sql = "SELECT * FROM credenciales_rrhh_tarjetas 
        ORDER BY unidadTarjeta ASC, textoTarjeta ASC";

$stmt = $conexion->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$data = [];

while ($row = $result->fetch_assoc()) {

    $idTarjeta = (int)$row["idTarjeta"];

    $data[] = [
        "tipoTarjeta"   => $row["tipoTarjeta"],
        "codigoTarjeta" => $row["codigoTarjeta"],
        "textoTarjeta"      => $row["textoTarjeta"],
        "unidadTarjeta" => $row["unidadTarjeta"],
        "fechaCreacion" => $row["fechaCreacion"],
        "buttonVisualizar"   => ($idTarjeta > 0)
            ? "<button class='btn btn-primary btn-sm btn-block btn-vertarjeta' data-idtarjeta='{$idTarjeta}'><i class='fas fa-eye'></i></button>"
            : "<span class='text-muted'>Deshabilitado</span>"
    ];
}

$stmt->close();
$conexion->close();

echo json_encode([
    "data" => $data
]);
exit;
