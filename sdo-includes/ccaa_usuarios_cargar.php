<?php
include_once '../sdo-funciones/conexion.php';
date_default_timezone_set('America/Santiago');

$sql = "
SELECT
    o.ID,
    o.OWNER_CODE,
    o.OWNER_Name,
    o.DEPT_ID,
    COALESCE(UPPER(d.DEPT_FULL_NAME), 'SIN INFORMACIÓN') AS departamentoAcceso,
    o.CARD_NUMBER,
    o.CARD_STATE,
    COALESCE(UPPER(a.calidadContractual), 'SIN INFORMACIÓN') AS calidad_contractual,
    CASE
        WHEN o.CARD_NUMBER IS NULL OR o.CARD_NUMBER = ''
            THEN 'SIN CREDENCIAL EN SMARTPSS'
        WHEN o.END_DATE <= NOW()
            THEN 'CREDENCIAL BLOQUEADA AUTOMÁTICAMENTE EN SMARTPSS'
        WHEN o.END_DATE <= NOW() + INTERVAL 20 DAY
            THEN 'CREDENCIAL SERÁ BLOQUEADA. NOTIFICAR.'
        WHEN o.CARD_STATE IN (0, 2)
            THEN 'CREDENCIAL ACTIVADA EN SMARTPSS'
        WHEN o.CARD_STATE IN (1, 3)
            THEN 'CREDENCIAL BLOQUEADA EN SMARTPSS'
        ELSE 'ESTADO DESCONOCIDO EN SMARTPSS'
    END AS estadoAcceso,
    CASE
        WHEN i.total > 0 THEN 'ESTACIONAMIENTO ACTIVADO'
        ELSE 'ESTACIONAMIENTO NO ACTIVO'
    END AS intercom,
    DATE_FORMAT(o.END_DATE, '%d-%m-%Y %H:%i:%s') AS bloqueoAutomatico
FROM
    ACM_owner o
LEFT JOIN (
    SELECT rutTarjeta, calidadContractual
    FROM actas
    WHERE (rutTarjeta, fechaActa) IN (
        SELECT rutTarjeta, MAX(fechaActa)
        FROM actas
        GROUP BY rutTarjeta
    )
) a ON a.rutTarjeta = o.OWNER_CODE
LEFT JOIN Department_table d ON d.DEPT_ID = o.DEPT_ID
LEFT JOIN (
    SELECT Name, COUNT(*) AS total
    FROM intercom
    GROUP BY Name
) i ON i.Name = o.OWNER_CODE
ORDER BY o.END_DATE ASC, o.OWNER_Name ASC
";

$result = $conexion->query($sql);
$datos = [];

while ($row = $result->fetch_assoc()) {
    $row['departamentoAcceso'] = mb_convert_case($row['departamentoAcceso'], MB_CASE_UPPER, "UTF-8");
    $row['calidad_contractual'] = mb_convert_case($row['calidad_contractual'], MB_CASE_UPPER, "UTF-8");
    $row['estadoAcceso'] = mb_convert_case($row['estadoAcceso'], MB_CASE_UPPER, "UTF-8");
    $row['intercom'] = mb_convert_case($row['intercom'], MB_CASE_UPPER, "UTF-8");
    $datos[] = $row;
}

$conexion->close();

header('Content-Type: application/json');
echo json_encode($datos);
