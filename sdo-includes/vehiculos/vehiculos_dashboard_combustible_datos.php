<?php
include_once '../../sdo-funciones/conexion.php';

// Sanitización de entrada
$mes  = isset($_POST['mes']) ? (int)$_POST['mes'] : 0;
$anio = isset($_POST['anio']) ? (int)$_POST['anio'] : date('Y');

if ($mes > 0) {
    // Consulta Mensual
    $sql = "SELECT 
                m.idMovil, 
                m.nomVehiculo, 
                m.patenteVehiculo,
                SUM(p.cantidadLitros) as total_litros,
                SUM(p.totalConIva) as gasto_total,
                COUNT(p.idPetroleo) as cargas
            FROM moviles_movilizacion m
            INNER JOIN historial_petroleo_moviles p ON m.idMovil = p.idMovil
            WHERE MONTH(p.fechaRegistro) = ? AND YEAR(p.fechaRegistro) = ?
            GROUP BY m.idMovil, m.nomVehiculo, m.patenteVehiculo"; // Agregamos campos al GROUP BY por estándar SQL
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $mes, $anio);
} else {
    // Consulta Anual
    $sql = "SELECT 
                m.idMovil, 
                m.nomVehiculo, 
                m.patenteVehiculo,
                SUM(p.cantidadLitros) as total_litros,
                SUM(p.totalConIva) as gasto_total,
                COUNT(p.idPetroleo) as cargas
            FROM moviles_movilizacion m
            INNER JOIN historial_petroleo_moviles p ON m.idMovil = p.idMovil
            WHERE YEAR(p.fechaRegistro) = ?
            GROUP BY m.idMovil, m.nomVehiculo, m.patenteVehiculo";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $anio);
}

$stmt->execute();
$result = $stmt->get_result();

$data = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Aseguramos que los valores no sean null para evitar errores en el JS
        $data[] = [
            'idMovil'         => $row['idMovil'],
            'nomVehiculo'     => $row['nomVehiculo'],
            'patenteVehiculo' => $row['patenteVehiculo'],
            'total_litros'    => (float)($row['total_litros'] ?? 0),
            'gasto_total'     => (int)($row['gasto_total'] ?? 0),
            'cargas'          => (int)($row['cargas'] ?? 0)
        ];
    }
}

// Limpiamos cualquier posible salida accidental antes del JSON
ob_clean();
header('Content-Type: application/json');
echo json_encode($data);
exit;