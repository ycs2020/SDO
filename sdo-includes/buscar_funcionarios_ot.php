<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

header('Content-Type: application/json; charset=utf-8');

$idTG = isset($_POST['idTG'])
    ? (int)$_POST['idTG']
    : 0;

$sql = "SELECT cr.*
        FROM datos_credencial_funcionarios dcf
        INNER JOIN credenciales_rrhh cr 
            ON cr.idCredencial = dcf.idCredencial
        WHERE dcf.idTG = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idTG);
$stmt->execute();

$result = $stmt->get_result();

$data = [];

while ($row = $result->fetch_assoc()) {

    $data[] = [
        "idCredencial"   => $row["idCredencial"],
        "nombreCompleto" => $row["nombreCredencial"] . " " . $row["apellidoCredencial"],
        "rutCredencial"  => $row["rutCredencial"]
    ];
}

$stmt->close();
$conexion->close();

echo json_encode([
    "data" => $data
]);

exit;
?>