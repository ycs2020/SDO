<?php
include_once '../../sdo-funciones/conexion.php';
include_once '../../sdo-funciones/funciones.php';

// Aseguramos que la conexión global esté disponible para las funciones de kilometraje
global $conexion;

function formatearFechaCompletaAJAX($fechaRaw)
{
    $fechaCompleta = 'Fecha No Ingresada';
    if (!empty($fechaRaw)) {
        $timestamp = strtotime(str_replace('/', '-', $fechaRaw));
        if ($timestamp !== false && $timestamp > 0) {
            $fechaCompleta = fechaEspañol2($timestamp);
        }
    }
    return $fechaCompleta;
}

// Capturamos el parámetro del switch (por defecto 0 si no se envía)
$verBaja = isset($_POST['verBaja']) ? trim($_POST['verBaja']) : '0';

// Estructura base de la consulta SQL
$sqlBase = "
    SELECT 
        m.idMovil, 
        m.idUnico, 
        m.nomVehiculo, 
        m.patenteVehiculo, 
        m.marcaVehiculo, 
        m.modeloVehiculo, 
        m.servicioVehiculo, 
        m.valorConIva, 
        m.estadoVehiculo,
        h.estadoMovil AS ultimoEstado,
        h.fechaReporte,
        h.motivo
    FROM moviles_movilizacion m
    LEFT JOIN (
        SELECT idMovil, MAX(idEstadoMovil) as max_id
        FROM historial_estados_moviles
        GROUP BY idMovil
    ) ultimo ON m.idMovil = ultimo.idMovil
    LEFT JOIN historial_estados_moviles h ON ultimo.max_id = h.idEstadoMovil
";

// Aplicamos el filtro en el WHERE según el botón presionado
if ($verBaja === '1') {
    // Modo "Separados": Muestra sólo los dados de baja
    $sql = $sqlBase . " WHERE m.estadoVehiculo LIKE 'DEBAJA' ORDER BY m.idMovil DESC;";
} else {
    // Modo por defecto: Oculta los dados de baja
    $sql = $sqlBase . " WHERE m.estadoVehiculo NOT LIKE 'DEBAJA' OR m.estadoVehiculo IS NULL ORDER BY m.idMovil DESC;";
}

$stmt = $conexion->prepare($sql);
if (!$stmt) {
    echo json_encode(["error" => "Error en la consulta: " . $conexion->error]);
    exit;
}

$stmt->execute();
$res = $stmt->get_result();

$datos = array();

while ($row = $res->fetch_assoc()) {
    $valorFormateado = ($row['valorConIva'] > 0) ? '$' . number_format($row['valorConIva'], 0, ",", ".") : 'N/A';
    $idMovil = $row['idMovil'];
    $estadoVehiculo = $row['estadoVehiculo'];
    $estadoVehiculo2 = mb_strtoupper($estadoVehiculo, 'UTF-8');
    $fechaReporte = formatearFechaCompletaAJAX($row['fechaReporte']);

    switch($estadoVehiculo2){
        case 'OPERATIVA':
            $badgeEstado = '<span class="badge badge-success w-100"><i class="fas fa-check"></i>&nbsp;&nbsp;OPERATIVA</span>';
            break;
        case 'MANTENCION PREVENTIVA':
            $badgeEstado = '<span class="badge badge-info w-100"><i class="fas fa-exclamation-triangle"></i>&nbsp;&nbsp;MANTENCION PREVENTIVA</span>';
            break;
        case 'MANTENCION CORRECTIVA':
            $badgeEstado = '<span class="badge badge-warning w-100"><i class="fas fa-exclamation-triangle"></i>&nbsp;&nbsp;MANTENCION CORRECTIVA</span>';
            break;
        case 'DEBAJA':
            $badgeEstado = '<span class="badge badge-danger w-100"><i class="fas fa-skull-crossbones"></i>&nbsp;&nbsp;DE BAJA</span>';
            break;
        case 'OTRO':
            $badgeEstado = '<span class="badge badge-dark w-100"><i class="fas fa-cog"></i>&nbsp;&nbsp;OTRO</span>';
            break;
        default:
            $badgeEstado = '<span class="badge badge-secondary w-100"><i class="fas fa-question"></i>&nbsp;&nbsp;'.$estadoVehiculo.'</span>';
    }

    // Cálculos de mantenimiento usando las funciones seguras con 'global $conexion'
    $ultimoMant = obtenerUltimoRegistroMantenimiento($idMovil);
    $proxMant = (int)$ultimoMant + 10000;
    $ultimoKm = obtenerUltimoRegistroKilometraje($idMovil);
    $diferencia = $proxMant - $ultimoKm;
    $difFmt = number_format(abs($diferencia), 0, ",", ".");

    if ($diferencia < 0) {
        $clase   = "badge bg-purple w-100 ";
        $mensaje = "<i class='fas fa-skull-crossbones'></i>&nbsp;&nbsp;<b>MANTENIMIENTO VENCIDO</b>";
    } elseif ($diferencia <= 1500) {
        $clase   = "badge bg-danger w-100 ";
        $mensaje = "<i class='fas fa-exclamation-triangle'></i>&nbsp;&nbsp;<b>CRÍTICO: RESTAN {$difFmt} KMS.</b>";
    } elseif ($diferencia <= 4000) {
        $clase   = "badge bg-warning text-dark w-100 ";
        $mensaje = "<i class='fas fa-clock'></i>&nbsp;&nbsp;<b>AVISO: PROX. EN {$difFmt} KMS.</b>";
    } else {
        $clase   = "badge bg-success w-100 ";
        $mensaje = "<i class='fas fa-check-circle'></i>&nbsp;&nbsp;<b>VEHÍCULO ÓPTIMO</b>";
    }

    $badgeMantenimiento = "<span class='{$clase}'>{$mensaje}</span>";

    $datos[] = array(
        "idUnico" => $row['idUnico'],
        "nomVehiculo" => $row['nomVehiculo'],
        "patenteVehiculo" => $row['patenteVehiculo'],
        "marcaVehiculo" => $row['marcaVehiculo'],
        "modeloVehiculo" => $row['modeloVehiculo'],
        "servicioVehiculo" => $row['servicioVehiculo'],
        "valorConIva" => $valorFormateado,
        "estadoVehiculo" => $badgeEstado,
        "badgeMantenimiento" => $badgeMantenimiento,
        "acciones" => '<button class="btn btn-block btn-info btn-xs btn-ver-movil" id="'.$row['idMovil'].'"><i class="fas fa-eye"></i></button>',
        "eliminar" => '<button class="btn btn-block btn-danger btn-xs btn-eliminar-movil" id="'.$row['idMovil'].'"><i class="fas fa-trash"></i></button>',
        "fechaReporte" => $fechaReporte,
    );
}

$stmt->close();
$conexion->close();

echo json_encode($datos);
exit;