<?php
include_once '../../sdo-funciones/conexion.php';
include_once '../../sdo-funciones/funciones.php';

$idMovil = $_POST['idMovilAjax'] ?? null;

function formatearFechaCompletaAJAX($fechaRaw)
{
    $fechaCompleta = 'Fecha inválida';

    if (!empty($fechaRaw)) {
        $timestamp = strtotime(str_replace('/', '-', $fechaRaw));

        if ($timestamp !== false && $timestamp > 0) {
            $inicioFecha = fechaEspañol2($timestamp);
            $inicioHora  = date('H:i', $timestamp);
            $fechaCompleta = $inicioFecha . '<br>' . $inicioHora." hrs.";
        }
    }

    return $fechaCompleta;
}

if ($idMovil) {
    $sql = "SELECT * FROM historial_estados_moviles WHERE idMovil = ? ORDER BY fechaReporte DESC";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('i', $idMovil);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        // Formatear fecha y badges aquí o en el JS

        $estadoVehiculo = $row['estadoMovil'];
        $estadoVehiculo2 = mb_strtoupper($estadoVehiculo, 'UTF-8');

        switch($estadoVehiculo2){
            case 'OPERATIVA':
            case 'OPERATIVO':
                $badgeEstado = '<span class="badge badge-success"><i class="fas fa-check"></i>&nbsp;&nbsp;OPERATIVA</span>';
                break;
            case 'MANTENCION PREVENTIVA':
                $badgeEstado = '<span class="badge badge-info"><i class="fas fa-exclamation-triangle"></i>&nbsp;&nbsp;MANTENCION PREVENTIVA</span>';
                break;
            case 'MANTENCION CORRECTIVA':
                $badgeEstado = '<span class="badge badge-warning"><i class="fas fa-exclamation-triangle"></i>&nbsp;&nbsp;MANTENCION CORRECTIVA</span>';
                break;
            case 'DEBAJA':
                $badgeEstado = '<span class="badge badge-danger"><i class="fas fa-skull-crossbones"></i>&nbsp;&nbsp;DE BAJA</span>';
                break;
            default:
                $badgeEstado = '<span class="badge badge-secondary"><i class="fas fa-question"></i>&nbsp;&nbsp;'.$estadoVehiculo.'</span>';
        }

        $fechaReporteTime = formatearFechaCompletaAJAX($row['fechaReporte']);

        $row['fechaFormateada'] = $fechaReporteTime;
        $row['estadoVehiculoBadge'] = $badgeEstado;

        $data[] = $row;
    }
    echo json_encode($data);


}
?>