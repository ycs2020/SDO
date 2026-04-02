<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8');

include_once '../../sdo-funciones/conexion.php';
include_once '../../sdo-funciones/funciones.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function h($string)
{
    return htmlspecialchars((string)($string ?? ''), ENT_QUOTES, 'UTF-8');
}

function formatearVacio($valor, $reemplazo = '-')
{
    $valor = trim((string)($valor ?? ''));
    return $valor !== '' ? $valor : $reemplazo;
}

function formatearFechaCompletaAJAX($fechaRaw)
{
    $fechaCompleta = 'Fecha inválida';

    if (!empty($fechaRaw)) {
        $timestamp = strtotime(str_replace('/', '-', $fechaRaw));

        if ($timestamp !== false && $timestamp > 0) {
            $inicioFecha = date('d/m/Y', $timestamp);
            $fechaCompleta = $inicioFecha;
        }
    }

    return $fechaCompleta;
}

?>
<style>
    #tablaTrabajosVAVModal thead th,
    #tablaTrabajosVAVModal tfoot th {
        text-align: center;
        vertical-align: middle;
    }
</style>
<?php

$idEquipo = isset($_POST['idEquipo']) ? (int)$_POST['idEquipo'] : 0;

if ($idEquipo <= 0) {
    echo '<div class="alert alert-warning mb-0">Equipo no válido.</div>';
    exit;
}

$sqlEquipo = "
    SELECT id, nombreEquipo, marca, modelo
    FROM equipamiento_hbqp
    WHERE id = ?
    LIMIT 1
";

$stmtEquipo = mysqli_prepare($conexion, $sqlEquipo);

if (!$stmtEquipo) {
    echo '<div class="alert alert-danger mb-0">Error al preparar la consulta del equipo.</div>';
    exit;
}

mysqli_stmt_bind_param($stmtEquipo, 'i', $idEquipo);
mysqli_stmt_execute($stmtEquipo);
$resEquipo = mysqli_stmt_get_result($stmtEquipo);
$rowEquipo = mysqli_fetch_assoc($resEquipo);

$nombreEquipo = $rowEquipo ? formatearVacio($rowEquipo['nombreEquipo']) : 'Equipo';
$marcaEquipo = $rowEquipo ? formatearVacio($rowEquipo['marca']) : '-';
$modeloEquipo = $rowEquipo ? formatearVacio($rowEquipo['modelo']) : '-';

$sqlTrabajos = "SELECT * FROM tareas_carta_gantt WHERE idEquipamiento = ? ORDER BY inicioPlanTareaP ASC
";


$stmtTrabajos = mysqli_prepare($conexion, $sqlTrabajos);

if (!$stmtTrabajos) {
    echo '<div class="alert alert-danger mb-0">Error al preparar la consulta de trabajos.</div>';
    exit;
}

mysqli_stmt_bind_param($stmtTrabajos, 'i', $idEquipo);
mysqli_stmt_execute($stmtTrabajos);
$resTrabajos = mysqli_stmt_get_result($stmtTrabajos);
?>

    <div class="mb-3">
        <h5 class="font-weight-bold mb-1"><?php echo h($nombreEquipo); ?></h5>
        <div class="text-muted">
            <b>Marca:</b> <?php echo h($marcaEquipo); ?> &nbsp;|&nbsp;
            <b>Modelo:</b> <?php echo h($modeloEquipo); ?>
        </div>
    </div>

<?php
if ($resTrabajos && mysqli_num_rows($resTrabajos) > 0) {
    ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-sm" id="tablaTrabajosVAVModal">
            <thead class="thead-dark text-center">
            <tr>
                <th>Actividad</th>
                <th>Personal</th>
                <th>Inicio Planificado</th>
                <th>Termino Planificado</th>
                <th>Inicio Real</th>
                <th>Termino Real</th>
                <th>Porcentaje</th>
<!--                <th>Detalle</th>-->
            </tr>
            </thead>
            <tbody>
            <?php
            while ($rowTrabajo = mysqli_fetch_assoc($resTrabajos)) {

                $idTareaP = $rowTrabajo['idTareaP'];
                $tituloTarea = formatearVacio($rowTrabajo['tituloTarea']);
                $inicioPlanTareaP = formatearFechaCompletaAJAX($rowTrabajo['inicioPlanTareaP']);
                $finPlanTareaP = formatearFechaCompletaAJAX($rowTrabajo['finPlanTareaP']);
                $inicioRealTareaP = formatearFechaCompletaAJAX($rowTrabajo['inicioRealTareaP']);
                $finRealTareaP = formatearFechaCompletaAJAX($rowTrabajo['finRealTareaP']);

                if ($finPlanTareaP === 'Fecha inválida') {
                    $finPlanTareaP = 'No Finalizado';
                }

                if ($finRealTareaP === 'Fecha inválida') {
                    $finRealTareaP = 'No Finalizado';
                }


                $datosOTCartaGantt = obtenerOTCartaGantt($idTareaP);

                $btnOTS = '';

                $contarSubTareaP = contarSubTareaP($idTareaP);

                $porcentajeTareaP = obtenerPorcentajeTarea($idTareaP);

                $porcentajeTareaP = intval($porcentajeTareaP);


                if ($contarSubTareaP > 0) {
                    $contarTotalPorcentaje = contarTotalPorcentaje($idTareaP);

                    $contarCantidadPadre = contarCantidadPadre($idTareaP);

                    if ($contarTotalPorcentaje > 0) {
                        $porcentajePadre = $contarTotalPorcentaje / $contarCantidadPadre;
                    } else {
                        $porcentajePadre = 0;
                    }
                } else {
                    $contarTotalPorcentaje = $porcentajeTareaP;

                    if ($porcentajeTareaP > 0) {
                        $porcentajePadre = $porcentajeTareaP;
                    } else {
                        $porcentajePadre = 0;
                    }

                }

                if (is_array($datosOTCartaGantt)) {

                    $idTGOT = $datosOTCartaGantt['idTG'];
                    $tecnico1OT = $datosOTCartaGantt['tecnico1'];
                    $estadoTGOT = $datosOTCartaGantt['estadoTG'];
                    $destinoTGOT = '#';

                    $tecnico1OT = trim($tecnico1OT);
                    if ($idTGOT > 0 && !empty($tecnico1OT) && in_array($estadoTGOT, ['Trabajo Ingresado', 'Trabajo Terminado'])) {

                        if ($estadoTGOT == 'Trabajo Ingresado') {
                            $destinoTGOT = 'detalle_ordenes_trabajo.php';
                        } else if ($estadoTGOT == 'Trabajo Terminado') {
                            $destinoTGOT = 'visualizardetallesterminadas.php';
                        } else {
                            $destinoTGOT = '#';
                        }

                        $btnOTS = "<form style='margin: 0; text-align: center;' target='_blank' class='' action='" . h($destinoTGOT) . "' method='post'>";
                        $btnOTS .= "<input type='hidden' name='idTG' value='" . h($idTGOT) . "'>";
                        $btnOTS .= "<button type='submit' id='inputMozi' class='btn btn-block btn-info'>" . h($tecnico1OT) . "</button>";
                        $btnOTS .= "</form>";


                    } else {
                        $btnOTS = "No Aplica";
                    }


                } else {
                    $btnOTS = "No Aplica";
                }
                ?>
                <tr>

                    <td style="min-width: 200px;"><?php echo h($tituloTarea); ?></td>
                    <td><?php echo $btnOTS; ?></td>
                    <td><?php echo $inicioPlanTareaP; ?></td>
                    <td><?php echo $finPlanTareaP; ?></td>
                    <td><?php echo $inicioRealTareaP; ?></td>
                    <td><?php echo $finRealTareaP; ?></td>
                    <td>
                        <?php
                        if ($contarTotalPorcentaje == 0) {
                            echo $contarTotalPorcentaje;
                        } else {
                            ?>
                            <div class="form-group">

                                <?php
                                $porcentajeTareaP2 = round($porcentajePadre, 1);

                                ?>
                                <label><?php echo $porcentajeTareaP2 . '%'; ?></label>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped" role="progressbar" style="width: <?php echo $porcentajePadre; ?>%" aria-valuenow="<?php echo $porcentajePadre; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </td>
<!--                    <td>-->
<!--                        --><?php
//                        if ($idTareaP > 0) {
//                            ?>
<!--                            <button id="btnVerTrabajoVAV" class="btn btn-block btn-info btn-ver-trabajo-vav" data-idtarea="--><?php //echo h($idTareaP); ?><!--">-->
<!--                                <i class="fas fa-eye"></i>-->
<!--                            </button>-->
<!--                            --><?php
//                        } else {
//                            echo "Deshabilitado";
//                        }
//                        ?>
<!--                    </td>-->

                </tr>
                <?php
            }
            ?>
            </tbody>

            <tfoot class="thead-dark text-center">
            <tr>
                <th>Actividad</th>
                <th>Personal</th>
                <th>Inicio Planificado</th>
                <th>Termino Planificado</th>
                <th>Inicio Real</th>
                <th>Termino Real</th>
                <th>Porcentaje</th>
<!--                <th>Detalle</th>-->
            </tr>
            </tfoot>
        </table>
    </div>

    <script>
        (function () {
            if ($.fn.DataTable.isDataTable('#tablaTrabajosVAVModal')) {
                $('#tablaTrabajosVAVModal').DataTable().destroy();
            }

            $('#tablaTrabajosVAVModal').DataTable({
                lengthChange: false,
                searching: true,
                autoWidth: false,
                responsive: true,
                ordering: false,
                pageLength: 10,
                language: {
                    lengthMenu: 'Visualizando _MENU_ registros por página',
                    zeroRecords: 'No se encontraron registros',
                    info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                    infoEmpty: 'No hay registros disponibles',
                    infoFiltered: '(filtrado de _MAX_ registros totales)',
                    search: 'Buscar:',
                    paginate: {
                        first: 'Primero',
                        last: 'Último',
                        next: 'Siguiente',
                        previous: 'Anterior'
                    }
                },
                "createdRow": function (row, data, dataIndex) {
                    $('td', row).css('vertical-align', 'middle');
                    $('td', row).css('text-align', 'center');
                }
            });
        })();
    </script>
    <?php
} else {
    echo '<div class="alert alert-info mb-0">No se encontraron trabajos programados para esta VAV.</div>';
}

if (isset($stmtEquipo) && $stmtEquipo) {
    mysqli_stmt_close($stmtEquipo);
}

if (isset($stmtTrabajos) && $stmtTrabajos) {
    mysqli_stmt_close($stmtTrabajos);
}
?>