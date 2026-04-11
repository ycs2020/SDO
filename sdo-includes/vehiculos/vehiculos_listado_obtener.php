<?php
include_once '../../sdo-funciones/conexion.php';
include_once '../../sdo-funciones/funciones.php';

function formatearFechaCompletaAJAX($fechaRaw)
{
    $fechaCompleta = 'Fecha No Ingresada';

    if (!empty($fechaRaw)) {
        $timestamp = strtotime(str_replace('/', '-', $fechaRaw));

        if ($timestamp !== false && $timestamp > 0) {

            $inicioFecha = fechaEspañol2($timestamp);
            $fechaCompleta = $inicioFecha;
        }
    }

    return $fechaCompleta;
}

$sql = "
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
        ORDER BY m.idMovil DESC;
        ";
$res = mysqli_query($conexion, $sql);

$datos = array();

while ($row = $res->fetch_assoc()) {
    // Formatear el valor con IVA
    $valorFormateado = ($row['valorConIva'] > 0) ? '$' . number_format($row['valorConIva'], 0, ",", ".") : 'N/A';

    $estadoVehiculo = $row['estadoVehiculo'];
    $estadoVehiculo2 = mb_strtoupper($estadoVehiculo, 'UTF-8');
    $fechaReporte = formatearFechaCompletaAJAX($row['fechaReporte']);

    switch($estadoVehiculo2){
        case 'OPERATIVA':
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
        case 'OTRO':
            $badgeEstado = '<span class="badge badge-dark"><i class="fas fa-cog"></i>&nbsp;&nbsp;OTRO</span>';
            break;
        default:
            $badgeEstado = '<span class="badge badge-secondary"><i class="fas fa-question"></i>&nbsp;&nbsp;'.$estadoVehiculo.'</span>';
    }


    $datos[] = array(
        "idUnico" => $row['idUnico'],
        "nomVehiculo" => $row['nomVehiculo'],
        "patenteVehiculo" => $row['patenteVehiculo'],
        "marcaVehiculo" => $row['marcaVehiculo'],
        "modeloVehiculo" => $row['modeloVehiculo'],
        "servicioVehiculo" => $row['servicioVehiculo'],
        "valorConIva" => $valorFormateado,
        "estadoVehiculo" => $badgeEstado,
        "acciones" => '
            <button class="btn btn-block btn-info btn-xs btn-ver-movil" id="'.$row['idMovil'].'"><i class="fas fa-eye"></i></button>',
        "eliminar" => '
            <button class="btn btn-block btn-danger btn-xs btn-eliminar-movil" id="'.$row['idMovil'].'"><i class="fas fa-trash"></i></button>',
        "fechaReporte" => $fechaReporte,
    );
}

echo json_encode($datos);