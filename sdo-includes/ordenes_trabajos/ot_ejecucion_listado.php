<?php
include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');
$nomUsuario = $_SESSION['nomUsuario'] ?? '';
$rolBtn = $_POST['rolBtn'] ?? '';

$ipAdress = $_SERVER['REMOTE_ADDR'] ?? 'IP DESCONOCIDA';

function formatearFechaCompletaAJAX($fechaRaw)
{
    $fechaCompleta = 'FECHA NO INGRESADA';
    if (!empty($fechaRaw)) {
        $timestamp = strtotime(str_replace('/', '-', $fechaRaw));
        if ($timestamp !== false && $timestamp > 0) {
            $fechaCompleta = fechaEspañol2($timestamp);
        }
    }
    return $fechaCompleta;
}

$response = [
    "data" => []
];

// Query optimizada con el LEFT JOIN a departamentos
$queryTG = "SELECT tg.*,
       d.nomDepartamento,
       d.iconoDepartamento,
       COALESCE(obs.total_obs, 0) AS contarObs,
       COALESCE(lv.material, 0) AS contarMaterial,
       COALESCE(lv.panol, 0) AS contarPanol,
       COALESCE(lv.rechazado, 0) AS contarRechazadoPanol,
       COALESCE(lv.aceptado, 0) AS contarAceptadoPanol
FROM trabajos_general tg
LEFT JOIN departamentos d ON tg.departamento = d.idDepartamento 
LEFT JOIN (
    SELECT idTG, COUNT(*) AS total_obs 
    FROM observacion_ot 
    GROUP BY idTG
) obs ON tg.idTG = obs.idTG
LEFT JOIN (
    SELECT idTG,
           SUM(IF(isMaterialSoliCompra = 1, 1, 0)) AS material,
           SUM(IF(isPanol = 1, 1, 0)) AS panol,
           SUM(IF(rechazadoPanol = 1, 1, 0)) AS rechazado,
           SUM(IF(respuestaPanol = 1, 1, 0)) AS aceptado
    FROM linea_vida_ot
    GROUP BY idTG
) lv ON tg.idTG = lv.idTG
ORDER BY tg.fechaProgramacion DESC, tg.folioTG DESC;";

$resTG = mysqli_query($conexion, $queryTG);

if ($resTG) {
    while ($rowTG = $resTG->fetch_assoc()) {

        $idTG = isset($rowTG['idTG']) ? trim($rowTG['idTG']) : '';
        $fechaProgramacion = $rowTG['fechaProgramacion'] ?? '';
        $turnoTG = isset($rowTG['turnoTG']) ? trim($rowTG['turnoTG']) : '';
        $solicitanteTG = isset($rowTG['solicitanteTG']) ? trim($rowTG['solicitanteTG']) : '';
        $estadoTG = isset($rowTG['estadoTG']) ? trim($rowTG['estadoTG']) : '';
        $tecnico1 = $rowTG['tecnico1'] ?? '';
        $folioTG = $rowTG['folioTG'] ?? '';
        $descripcion = isset($rowTG['descripcion']) ? trim($rowTG['descripcion']) : '';

        // Capturar el nombre del departamento y armar HTML con comillas corregidas
        $nomDepartamento = isset($rowTG['nomDepartamento']) ? trim($rowTG['nomDepartamento']) : 'SIN DEPARTAMENTO';
        // FORZAR EL CAMBIO DE NOMBRE
        if (strcasecmp($nomDepartamento, 'Instrumentación') == 0) {
            $nomDepartamento = 'CONTROL CENTRALIZADO';
        }

        $iconoDep = !empty($rowTG['iconoDepartamento']) ? trim($rowTG['iconoDepartamento']) : '<i class="fas fa-laptop"></i>';
        $departamentoHTML = $iconoDep . ' ' . mb_strtoupper($nomDepartamento, 'UTF-8');

        // --- LÓGICA DE COLORES POR ESTADO DE TRABAJO ---
        $badgeEstado = "badge-warning"; // Por defecto: Amarillo
        $txtEstado = "NO REGISTRA ESTADO";

        if (!empty($estadoTG)) {
            $estadoNormalizado = strtolower(preg_replace('/\s+/', '', $estadoTG));

            if ($estadoNormalizado === 'trabajoingresado') {
                $badgeEstado = "badge-success"; // Verde
                $txtEstado = "TRABAJO INGRESADO";
            } elseif ($estadoNormalizado === 'trabajoterminado') {
                $badgeEstado = "badge-danger"; // Rojo
                $txtEstado = "TRABAJO TERMINADO";
            } else {
                $badgeEstado = "badge-warning text-dark"; // Amarillo
                $txtEstado = $estadoTG;
            }
        }

        // HTML del Estado usando los badges estéticos
        $estadoHTML = '<span class="badge ' . $badgeEstado . ' p-2 text-wrap d-block" style="font-size:11px; font-weight:600; letter-spacing: 0.5px;">' . mb_strtoupper($txtEstado, 'UTF-8') . '</span>';

        // Estilizado del Folio
        $folioHTML = '<span class="text-primary font-weight-bold" style="font-size: 1.05rem;">' . mb_strtoupper($folioTG, 'UTF-8') . '</span>';

        // Destacar si la fila es del técnico logueado
        $rowClass = "";
        if (!empty($tecnico1) && !empty($nomUsuario) && strcasecmp($tecnico1, $nomUsuario) == 0) {
            $rowClass = "ot-asignada-usuario";
        }

        // Botón Detalle
        if (empty($idTG) || $idTG == "0") {
            $txtDetalles = '<span class="text-danger small">Error al Cargar Datos</span>';
        } else {
            if($ipAdress === '10.69.250.151'){
                $txtEstado = 'YORGO';
            }
            if ($rolBtn == 'detalle') {

                switch ($txtEstado) {
                    case 'YORGO':
                        $txtDetalles = '<form style="margin: 0; text-align: center;" action="detalle_ordenes_trabajo_2.php" method="post" target="_blank">
                                        <input type="hidden" name="idTG" value="' . $idTG . '">
                                        <button type="submit" class="btn btn-sm btn-info btn-block shadow-sm" title="Ver Detalles"><i class="fa fa-eye"></i></button>
                                    </form>';
                        break;
                    case 'TRABAJO INGRESADO':
                        $txtDetalles = '<form style="margin: 0; text-align: center;" action="detalle_ordenes_trabajo.php" method="post" target="_blank">
                                        <input type="hidden" name="idTG" value="' . $idTG . '">
                                        <button type="submit" class="btn btn-sm btn-info btn-block shadow-sm" title="Ver Detalles"><i class="fa fa-eye"></i></button>
                                    </form>';
                        break;
                    case 'TRABAJO TERMINADO':
                        $txtDetalles = '<form style="margin: 0; text-align: center;" action="visualizardetallesterminadas.php" method="post" target="_blank">
                                        <input type="hidden" name="idTG" value="' . $idTG . '">
                                        <button type="submit" class="btn btn-sm btn-info btn-block shadow-sm" title="Ver Detalles"><i class="fa fa-eye"></i></button>
                                    </form>';
                        break;
                    default:
                        $txtDetalles = '<form style="margin: 0; text-align: center;" action="../sdo-pdf/SDO-Orden-Trabajo-Visor.php" method="post" target="_blank">
                                        <input type="hidden" name="idTG" value="' . $idTG . '">
                                        <button type="submit" class="btn btn-sm btn-info btn-block shadow-sm" title="Ver Detalles"><i class="fa fa-eye"></i></button>
                                    </form>';
                        break;
                }
            } else {
                $txtDetalles = '<form style="margin: 0; text-align: center;" action="../sdo-pdf/SDO-Orden-Trabajo-Visor.php" method="post" target="_blank">
                                <input type="hidden" name="idTG" value="' . $idTG . '">
                                <button type="submit" class="btn btn-sm btn-info btn-block shadow-sm" title="Ver Detalles"><i class="fa fa-eye"></i></button>
                            </form>';
            }
        }

        $txtFechaProg = formatearFechaCompletaAJAX($fechaProgramacion);
        $txtTurno = empty($turnoTG) ? "NO REGISTRA TURNO" : $turnoTG;
        $txtSolicitante = empty($solicitanteTG) ? "NO REGISTRA SOLICITANTE" : $solicitanteTG;
        $txtDesc = empty($descripcion) ? "SIN INFORMACION" : $descripcion;

        $response["data"][] = [
            "fecha_programacion" => mb_strtoupper($txtFechaProg, 'UTF-8'),
            "folio_trabajo" => $folioHTML,
            "turno_trabajo" => mb_strtoupper($txtTurno, 'UTF-8'),
            "departamento" => $departamentoHTML,
            "solicitante" => mb_strtoupper($txtSolicitante, 'UTF-8'),
            "tecnico_responsable" => mb_strtoupper($tecnico1, 'UTF-8'),
            "estado" => $estadoHTML,
            "descripcion" => mb_strtoupper($txtDesc, 'UTF-8'),
            "detalles" => $txtDetalles,
            "DT_RowClass" => $rowClass
        ];
    }
    $resTG->free();
}

header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;