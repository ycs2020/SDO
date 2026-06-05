<div class="row">

    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box-sm">
            <span class="info-box-text">Fecha Programación</span>
            <span class="info-box-number"><?php echo $fechaProgram2; ?></span>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box-sm">
            <span class="info-box-text">Turno</span>
            <span class="info-box-number text-truncate d-block"><?php echo mb_convert_case($turnoTG, MB_CASE_UPPER, "UTF-8"); ?></span>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box-sm" style="border-left-color:#6c757d;">
            <span class="info-box-text">Personal Solicitante</span>
            <span class="info-box-number text-truncate d-block"><?php echo mb_convert_case($solicitanteTG, MB_CASE_UPPER, "UTF-8"); ?></span>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box-sm" style="border-left-color:#6c757d;">
            <span class="info-box-text">Persona Asignada</span>
            <span class="info-box-number text-truncate d-block"><?php echo mb_convert_case($tecnico1, MB_CASE_UPPER, "UTF-8"); ?></span>
        </div>
    </div>
    <?php
    $estLCase = mb_strtolower(trim($estadoTG), 'UTF-8');

    // Definimos el color del badge y la clase de borde correspondiente
    if (strpos($estLCase, 'terminado') !== false || strpos($estLCase, 'finalizado') !== false) {
        $bColor = 'badge-danger';
        $borderColor = 'border-danger';
    } elseif (strpos($estLCase, 'proceso') !== false || strpos($estLCase, 'pendiente') !== false) {
        $bColor = 'badge-warning';
        $borderColor = 'border-warning';
    } else {
        $bColor = 'badge-success';
        $borderColor = 'border-success';
    }
    ?>

    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box-sm state-style <?php echo $borderColor; ?>" style="border-left-width: 4px !important; border-left-style: solid !important;">
            <span class="info-box-text">Estado de la OT</span>
            <div>
            <span class="badge <?php echo $bColor; ?> px-2 py-1 font-weight-bold" style="font-size:11px;">
                <?php echo mb_convert_case($estadoTG, MB_CASE_UPPER, "UTF-8"); ?>
            </span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box-sm" style="border-left-color:#6c757d;">
            <span class="info-box-text">Ticket Asociado</span>
            <span class="info-box-number text-truncate d-block"><?php echo ($ticket !== '' && !empty($ticket) && $ticket !== 0) ? $ticket : 'SIN TICKET ASOCIADO'; ?></span>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box-sm" style="border-left-color:#6c757d;">
            <span class="info-box-text">Tipo de Trabajo</span>
            <span class="info-box-number text-truncate d-block"><?php echo ($tipoTrabajo !== '' && !empty($tipoTrabajo)) ? mb_convert_case($tipoTrabajo, MB_CASE_UPPER, "UTF-8") : 'SIN TIPO REGISTRADO'; ?></span>
        </div>
    </div>
</div>

<?php
// Cambio de sintaxis alternativa a tradicional con llaves {}
if (($departamentos == 1 || $departamentos == 13) && (!empty($detalleTurnoRonda) || !empty($formularioRondas) || $servicio > 0)) {
    ?>
    <div class="row mb-2">
        <div class="col-12">
            <div class="p-2 bg-light rounded border d-flex align-items-center justify-content-start font-weight-bold text-secondary" style="font-size: 12px; gap: 20px;">
                <?php if (!empty($formularioRondas)) { ?>

                <span><i class="fas fa-file-alt text-primary mr-1"></i> FORMULARIO: <span class="text-dark"><?php echo ($formularioRondas == 1) ? 'CONSOLA DE ALARMAS' : 'PARÁMETROS DE UMAS'; ?></span>&nbsp;&nbsp;
                <?php } ?>

                    <?php if ($servicio > 0) { ?>
                        <span><i class="fas fa-hospital text-primary mr-1"></i> SERVICIO/UNIDAD: <span class="text-dark"><?php echo $buscarNombreServicio; ?></span></span>
                    <?php } ?>
            </div>
        </div>
    </div>
    <?php
}
?>

<div class="row mb-3">
    <div class="col-12">
        <div class="p-2 bg-light border rounded">
            <div class="row align-items-center justify-content-center" style="row-gap: 10px;">

                <?php if ($contarArchivos > 0) { ?>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 text-center">
                        <button type="button" class="btn btn-xs font-weight-bold px-3 w-100" style="background-color: deepskyblue; color: white;" data-toggle="modal" data-target="#mostrarDescargar">
                            <i class="fas fa-file mr-1"></i> Documentos Adjuntos
                        </button>
                    </div>
                <?php } ?>

                <?php
                if ($estadoTG !== "Trabajo Terminado") {
                    ?>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 text-center">
                        <form style="margin:0; display:inline;" action="../sdo-administrador/trabajos_general_modificar_antes.php" method="post">
                            <input type="hidden" name="idTG" value="<?php echo $idTG; ?>">
                            <button type="submit" class="btn btn-xs btn-success font-weight-bold px-3 w-100">
                                <i class="fa fa-pen mr-1"></i> Modificar OT
                            </button>
                        </form>
                    </div>
                    <?php
                }
                ?>

                <div class="col-xl-3 col-lg-4 col-md-6 col-12 text-center">
                    <button type="button" class="btn btn-xs btn-info text-white font-weight-bold px-3 w-100" data-toggle="modal" data-target="#modalLineaVidaOT">
                        <i class="fas fa-eye mr-1"></i> Observación Línea de Vida
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<hr>

<style>
    /* Estilo para simular el recuadro del fieldset */
    .fieldset-simulado {
        position: relative;
        border: 1px solid #dee2e6 !important; /* Borde gris suave de Bootstrap */
        padding: 20px 15px 15px 15px;
        border-radius: 4px;
        background-color: #fff;
    }

    /* Estilo para simular la leyenda integrada en la línea superior */
    .legend-simulada {
        position: absolute;
        top: -11px; /* Eleva el texto para centrarlo justo sobre la línea superior */
        left: 15px;
        background-color: #fff; /* Fondo blanco para tapar la línea por detrás del texto */
        padding: 0 8px;
        font-size: 13.5px;
        font-weight: 700;
        color: #495057;
        margin: 0;
        width: auto;
    }
</style>

<div class="row">
    <?php
    if ($departamentos == 1) {
        $bloquesTareas = [
            ['BMS', $detalleTG], ['CCTV', $detalleTurnoCamara], ['LLE', $detalleTurnoLLE],
            ['Control de Acceso', $detalleTurnoCA], ['Control de Rondas', $detalleTurnoRonda], ['Otros Trabajos', $detalleOtros]
        ];
        foreach ($bloquesTareas as $bTarea) {
            if (!empty(trim($bTarea[1]))) {
                // d-flex y align-items-stretch obligan a que todas las tarjetas midan lo mismo
                echo '<div class="col-md-6 col-12 mb-4 d-flex align-items-stretch">
                        <div class="fieldset-simulado w-100 shadow-sm">
                            <h6 class="legend-simulada">Detalle del Trabajo en ' . $bTarea[0] . '</h6>
                            <p class="desc-p mb-0" style="font-size: 13px; color: #333;">' . nl2br(htmlspecialchars($bTarea[1], ENT_QUOTES, 'UTF-8')) . '</p>
                        </div>
                      </div>';
            }
        }
    } else {
        ?>
        <div class="col-12">
            <div class="fieldset-simulado shadow-sm">
                <h6 class="legend-simulada">Detalle del Trabajo a Realizar</h6>
                <p class="desc-p mb-0" style="font-size: 13px; color: #333;"><?php echo nl2br(htmlspecialchars($detalleTG, ENT_QUOTES, 'UTF-8')); ?></p>
            </div>
        </div>
        <?php
    }
    ?>
</div>