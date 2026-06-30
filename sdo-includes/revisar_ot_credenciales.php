<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

header('Content-Type: application/json; charset=utf-8');

$sql = "SELECT idTG, idCredencial
        FROM datos_credencial_funcionarios
        GROUP BY idTG";

$stmt = $conexion->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$data = [];

while ($row = $result->fetch_assoc()) {

    $idTG = (int)$row['idTG'];
    $idCredencial = (int)$row['idCredencial'];

    $sql2 = "SELECT * 
             FROM trabajos_general 
             WHERE idTG = ? ORDER BY fechaProgramacion DESC";

    $stmt2 = $conexion->prepare($sql2);
    $stmt2->bind_param("i", $idTG);
    $stmt2->execute();

    $result2 = $stmt2->get_result();

    while ($row2 = $result2->fetch_assoc()) {

        $data[] = [
            "idTG"               => $row2["idTG"],
            "idCredencial"       => $row["idCredencial"],
            "folioTG"            => $row2["folioTG"],
            "tecnico1"           => $row2["tecnico1"],
            "estadoTG"           => $row2["estadoTG"],
            "fechaProgramacion"  => date("d-m-Y", strtotime($row2["fechaProgramacion"])),
            "turnoTG"            => $row2["turnoTG"]
        ];
    }

    $stmt2->close();
}


$stmt->close();
$conexion->close();

echo json_encode([
    "data" => $data
]);

exit;
?>