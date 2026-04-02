<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

header('Content-Type: application/json; charset=utf-8');

$sql = "SELECT * FROM credenciales_rrhh 
        ORDER BY apellidoCredencial ASC, nombreCredencial ASC";

$stmt = $conexion->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$data = [];

while ($row = $result->fetch_assoc()) {

    $idCredencial = (int)$row["idCredencial"];

    $data[] = [
        "nombreCredencial"   => $row["nombreCredencial"],
        "apellidoCredencial" => $row["apellidoCredencial"],
        "rutCredencial"      => $row["rutCredencial"],
        "telefonoCredencial" => $row["telefonoCredencial"],
        "buttonVisualizar"   => ($idCredencial > 0)
            ? "<button class='btn btn-primary btn-sm btn-block btn-vercred' data-idcred='{$idCredencial}'><i class='fas fa-eye'></i></button>"
            : "<span class='text-muted'>Deshabilitado</span>"
    ];
}

$stmt->close();
$conexion->close();

echo json_encode([
    "data" => $data
]);
exit;
