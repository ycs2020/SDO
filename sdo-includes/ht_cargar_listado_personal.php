<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

header('Content-Type: application/json; charset=utf-8');

if (!isset($_POST['idDepartamento'])) {
    echo json_encode([]);
    exit;
}

$idDepartamento = intval(trim($_POST['idDepartamento']));
if ($idDepartamento <= 0) {
    echo json_encode([]);
    exit;
}

$sql = "
    SELECT o.*, 
           IFNULL(e.numeroSerie, 'Sin información') AS numeroSerie,
           IFNULL(e.nombreEquipo, 'Sin información') AS nombreEquipo,
           IFNULL(e.numeroInventario, 'Sin información') AS numeroInventario,
           (SELECT COUNT(*) FROM archivos_orden_general a WHERE a.idOrden = o.idOrden) AS cantidad
    FROM ordenes_trabajos_general o
    LEFT JOIN equipamiento_hbqp e ON o.idEquipo = e.id
    WHERE o.idDepartamento = ? AND o.anulada = 0
    ORDER BY o.fechaOrden DESC
";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idDepartamento);
$stmt->execute();

$result = $stmt->get_result();
$datos = [];

while ($row = $result->fetch_assoc()) {
    // ✅ Formato fecha y hora
    $fechaRaw = strtotime(str_replace('/', '-', $row['fechaOrden']));
    $fechaOrden = date('d/m/Y', $fechaRaw) . ' a las ' . date('H:i', $fechaRaw) . ' hrs.';

    // ✅ Diferencia de tiempo
    if (empty($row['horaInicio']) || empty($row['horaTermino'])) {
        $diferenciaFinal = 'Sin Información';
    } else {
        try {
            $horaInicio = new DateTime($row['horaInicio']);
            $horaTermino = new DateTime($row['horaTermino']);
            $diff = $horaTermino->diff($horaInicio);
            $diferenciaFinal = "Diferencia:<br> {$diff->days} días, {$diff->h} horas, {$diff->i} minutos";
        } catch (Exception $e) {
            $diferenciaFinal = 'Error al calcular tiempo';
        }
    }

    // ✅ Ticket asociado
    $ticketAsociado = intval($row['ticketAsociado']);
    $ticketAsociado = $ticketAsociado > 0 ? $ticketAsociado : 'No Aplica.';

    // ✅ Sanitización de strings
    $row['folioOrden'] = mb_convert_case($row['folioOrden'], MB_CASE_UPPER, 'UTF-8');
    $row['nomTecnicoRecepcionador'] = mb_convert_case($row['nomTecnicoRecepcionador'], MB_CASE_UPPER, 'UTF-8');
    $row['nomSolicitante'] = mb_convert_case($row['nomSolicitante'], MB_CASE_UPPER, 'UTF-8');

    // ✅ Datos formateados
    $row['fechaOrden'] = $fechaOrden;
    $row['ticketAsociado'] = $ticketAsociado;
    $row['diferencia'] = $diferenciaFinal;

    $datos[] = $row;
}

$stmt->close();
$conexion->close();

echo json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>
