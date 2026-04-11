<?php
include_once '../../sdo-funciones/conexion.php';

$mes  = isset($_POST['mes']) ? (int)$_POST['mes'] : 0;
$anio = isset($_POST['anio']) ? (int)$_POST['anio'] : date('Y');

// Definir condiciones según periodo
if ($mes > 0) {
    $condicion = "MONTH(p.fechaRegistro) = ? AND YEAR(p.fechaRegistro) = ?";
    $params = "ii";
    $bindData = [$mes, $anio];
} else {
    $condicion = "YEAR(p.fechaRegistro) = ?";
    $params = "i";
    $bindData = [$anio];
}

$sql = "SELECT 
            m.idMovil,
            m.nomVehiculo, 
            m.patenteVehiculo,
            SUM(p.cantidadLitros) as total_litros,
            SUM(p.totalConIva) as gasto_total,
            COUNT(p.idPetroleo) as cargas_realizadas
        FROM moviles_movilizacion m
        INNER JOIN historial_petroleo_moviles p ON m.idMovil = p.idMovil
        WHERE $condicion
        GROUP BY m.idMovil
        ORDER BY gasto_total DESC";

$stmt = $conexion->prepare($sql);
$stmt->bind_param($params, ...$bindData);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        "idMovil"         => $row['idMovil'],
        "nomVehiculo"     => $row['nomVehiculo'],
        "patente"         => $row['patenteVehiculo'],
        "total_litros"    => (float)$row['total_litros'],
        "gasto_total"     => (int)$row['gasto_total'],
        "cargas"          => $row['cargas_realizadas'],
        // Formateo previo para la tabla
        "txt_litros"      => number_format($row['total_litros'], 2, ',', '.') . ' Lts',
        "txt_gasto"       => '$' . number_format($row['gasto_total'], 0, ',', '.')
    ];
}

echo json_encode($data);