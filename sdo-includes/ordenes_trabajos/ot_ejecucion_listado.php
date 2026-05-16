<?php
include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');
$nomUsuario = $_SESSION['nomUsuario'] ?? '';

$response = [
    "data" => []
];

/**
 * MEJORA CON NOT LIKE:
 * Al usar NOT LIKE '%trabajo terminado%' nos aseguramos de excluir cualquier registro
 * que contenga esa frase, ignorando espacios de más antes o después y problemas de Case Sensitivity.
 */
$queryTG = "SELECT * FROM trabajos_general 
            WHERE estadoTG NOT LIKE '%trabajo terminado%' 
              AND estadoTG NOT LIKE '%trabajoTerminado%' 
            ORDER BY fechaProgramacion DESC, folioTG DESC";

$resTG = mysqli_query($conexion, $queryTG);

if ($resTG) {
    while ($rowTG = $resTG->fetch_assoc()) {

        $idTG              = isset($rowTG['idTG']) ? trim($rowTG['idTG']) : '';
        $fechaTG           = $rowTG['fechaTG'] ?? '';
        $fechaProgramacion = $rowTG['fechaProgramacion'] ?? '';
        $turnoTG           = isset($rowTG['turnoTG']) ? trim($rowTG['turnoTG']) : '';
        $solicitanteTG     = isset($rowTG['solicitanteTG']) ? trim($rowTG['solicitanteTG']) : '';
        $estadoTG          = isset($rowTG['estadoTG']) ? trim($rowTG['estadoTG']) : '';
        $tecnico1          = $rowTG['tecnico1'] ?? '';
        $folioTG           = $rowTG['folioTG'] ?? '';
        $descripcion       = isset($rowTG['descripcion']) ? trim($rowTG['descripcion']) : '';

        // Contadores
        $contarMaterial       = contarMaterial($idTG);
        $contarObs            = contarObs($idTG);
        $contarPanol          = contarPanol($idTG);
        $contarRechazadoPanol = contarRechazadoPanol($idTG);
        $contarAceptadoPanol  = contarAceptadoPanol($idTG);

        // Lógica de prioridad de colores
        $styleRow = "";
        if ($tecnico1 == $nomUsuario) {
            $styleRow = "background-color: #FFC107; color: black;";
        } else if ($contarMaterial > 0) {
            $styleRow = "background-color: indianred; color: white;";
        } else if ($contarPanol > 0) {
            $styleRow = "background-color: blue; color: white;";
        } else if ($contarAceptadoPanol > 0) {
            $styleRow = "background-color: green; color: white;";
        } else if ($contarRechazadoPanol > 0) {
            $styleRow = "background-color: red; color: white;";
        }

        // Formateo seguro de fechaProgramacion
        $txtFechaProg = "No registra fecha";
        if ($fechaProgramacion != '0' && !empty($fechaProgramacion) && $fechaProgramacion != '0000-00-00') {
            $txtFechaProg = date('d/m/Y', strtotime(str_replace('/', '-', $fechaProgramacion)));
        }

        $txtTurno       = empty($turnoTG) ? "No registra Turno" : $turnoTG;
        $txtSolicitante = empty($solicitanteTG) ? "No registra Solicitante" : $solicitanteTG;
        $txtDesc        = empty($descripcion) ? "Sin informacion" : $descripcion;

        if (empty($estadoTG)) {
            $txtEstado = "No registra Estado";
        } else {
            $txtEstado = (strpos(strtolower($estadoTG), 'trabajoterminado') !== false || strpos(strtolower($estadoTG), 'trabajo terminado') !== false) ? "Trabajo Terminado" : $estadoTG;
        }

        // Lógica informativa de estados internos
        $txtInfo = "Sin Informacion";
        if ($contarPanol > 0) {
            $txtInfo = "Funcionario asignado ha solicitado materiales y/o herramientas";
        } else if ($contarMaterial > 0) {
            $txtInfo = "EL FUNCIONARIO ASIGNADO HA SOLICITADO MATERIALES POR SOLICITUD DE COMPRA";
        } else if ($contarAceptadoPanol > 0) {
            $txtInfo = "La solicitud hacia pañol se ha entregado con exito";
        } else if ($contarRechazadoPanol > 0) {
            $txtInfo = "La solicitud se ha rechazado por personal de pañol";
        }

        // Botón de acción para el detalle
        if (empty($idTG) || $idTG == "0") {
            $txtDetalles = "Error al Cargar Datos.";
        } else {
            $txtDetalles = '<form style="margin: 0; text-align: center;" action="detalle_ordenes_trabajo.php" method="post">
                                <input type="hidden" name="idTG" value="' . $idTG . '">
                                <button type="submit" class="btn btn-sm btn-block btn-info"><i class="fa fa-eye"></i></button>
                            </form>';
        }

        $response["data"][] = [
            "fecha_programacion"  => $txtFechaProg,
            "folio_trabajo"       => $folioTG,
            "turno_trabajo"       => $txtTurno,
            "solicitante"         => $txtSolicitante,
            "tecnico_responsable" => $tecnico1,
            "estado"              => $txtEstado,
            "descripcion"         => $txtDesc,
            "informacion"         => $txtInfo,
            "detalles"            => $txtDetalles,
            "DT_RowStyle"         => $styleRow
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;