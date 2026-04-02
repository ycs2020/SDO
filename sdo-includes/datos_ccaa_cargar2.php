<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

$sql = "
    SELECT 
        o.OWNER_Name, 
        o.OWNER_CODE, 
        o.CARD_NUMBER, 
        o.END_DATE,
        a.calidadContractual AS calidad_contractual, 
        d.DEPT_FULL_NAME AS departamento
    FROM ACM_owner o
    LEFT JOIN (
        SELECT rutTarjeta, MAX(calidadContractual) AS calidadContractual
        FROM actas
        GROUP BY rutTarjeta
    ) a ON o.OWNER_CODE = a.rutTarjeta
    LEFT JOIN Department_table d ON o.DEPT_ID = d.DEPT_ID
    ORDER BY o.END_DATE ASC, o.OWNER_Name ASC
";

$stmt = $conexion->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = [
        "OWNER_Name" => mb_convert_case($row['OWNER_Name'] ?? '', MB_CASE_TITLE, 'UTF-8'),
        "OWNER_CODE" => $row['OWNER_CODE'] ?? '',
        "CARD_NUMBER" => $row['CARD_NUMBER'] ?? '',
        "calidad_contractual" => $row['calidad_contractual'] ?? '',
        "departamento" => $row['departamento'] ?? '',
        "END_DATE" => $row['END_DATE'] ?? '',
        "estado_credencial" => obtenerStatusCredencialCCAA($row['OWNER_CODE']),
        "intercom" => existeIntercom($row['OWNER_CODE']),
    ];
}

$stmt->close();
$conexion->close();

header('Content-Type: application/json');
echo json_encode(["data" => $data]);
