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
        #tablaCheckVAVModal thead th,
        #tablaCheckVAVModal tfoot th {
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

$sqlCheckList = "SELECT * FROM datos_vav WHERE idEquipamiento = ? ORDER BY fecha DESC";


$stmtCheckList = mysqli_prepare($conexion, $sqlCheckList);

if (!$stmtCheckList) {
    echo '<div class="alert alert-danger mb-0">Error al preparar la consulta de CheckList.</div>';
    exit;
}

mysqli_stmt_bind_param($stmtCheckList, 'i', $idEquipo);
mysqli_stmt_execute($stmtCheckList);
$resCheckList = mysqli_stmt_get_result($stmtCheckList);
?>

    <div class="mb-3">
        <h5 class="font-weight-bold mb-1"><?php echo h($nombreEquipo); ?></h5>
        <div class="text-muted">
            <b>Marca:</b> <?php echo h($marcaEquipo); ?> &nbsp;|&nbsp;
            <b>Modelo:</b> <?php echo h($modeloEquipo); ?>
        </div>
    </div>

<?php
if ($resCheckList && mysqli_num_rows($resCheckList) > 0) {
    ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-sm" id="tablaCheckVAVModal">
            <thead class="thead-dark text-center">
            <tr>
                <th>Folio</th>
                <th>Fecha</th>
                <th>Personal Asignado</th>
                <th>Ver CheckList</th>
                <th>Ver OT</th>
            </tr>
            </thead>
            <tbody>
            <?php
            while ($rowTrabajo = mysqli_fetch_assoc($resCheckList)) {

                $idCheck = $rowTrabajo['idVav'];
                $folioCheck = formatearVacio($rowTrabajo['folio']);
                $fechaCheck = formatearFechaCompletaAJAX($rowTrabajo['fecha']);
                $obsCheck = formatearVacio($rowTrabajo['obsGeneral']);
                $idTGCheck = (int)$rowTrabajo['idTG'];
                $obtenerTecnicoTGUCA = obtenerTecnicoTGUCA($idTGCheck);
                $obtenerEstadoTGUCA = obtenerEstadoTGUCA($idTGCheck);

                if ($folioCheck === 'Fecha inválida') {
                    $folioCheck = 'No Aplica';
                }
                ?>
                <tr>

                    <td><?php echo h($folioCheck); ?></td>
                    <td><?php echo $fechaCheck; ?></td>
                    <td><?php echo h($obtenerTecnicoTGUCA); ?></td>
                    <td>
                        <?php

                        if ($idCheck > 0) {
                            ?>
                            <button class="btn bg-info btn-pdf-check" data-idcheck="<?php echo $idCheck ?>" data-idtg="<?php echo $idTGCheck ?>">
                                <i class="fas fa-eye"></i>
                            </button>
                            <?php
                        } else {
                            echo "Error al Obtener CheckList";
                        }
                        ?>
                    </td>
                    <td>
                        <?php

                        if ($idTGCheck > 0 && $obtenerEstadoTGUCA !== 'Error') {
                            ?>
                            <button class="btn bg-secondary btn-pdf-ot" data-idcheck="<?php echo $idCheck ?>" data-idtg="<?php echo $idTGCheck ?>" data-estado="<?php echo h($obtenerEstadoTGUCA); ?>">
                                <i class="fas fa-eye"></i>
                            </button>
                            <?php
                        } else {
                            echo "Error al Obtener OT";
                        }
                        ?>
                    </td>

                </tr>
                <?php
            }
            ?>
            </tbody>

            <tfoot class="thead-dark text-center">
            <tr>
                <th>Folio</th>
                <th>Fecha</th>
                <th>Personal Asignado</th>
                <th>Ver CheckList</th>
                <th>Ver OT</th>
            </tr>
            </tfoot>
        </table>
    </div>

    <script>
        (function () {
            if ($.fn.DataTable.isDataTable('#tablaCheckVAVModal')) {
                $('#tablaCheckVAVModal').DataTable().destroy();
            }

            $('#tablaCheckVAVModal').DataTable({
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


        // Función reutilizable para enviar formularios POST
        function enviarFormulario(url, datos = {}, target = '_self') {

            const form = $('<form>', {
                action: url,
                method: 'POST',
                target: target
            });

            $.each(datos, function (nombre, valor) {
                form.append($('<input>', {
                    type: 'hidden',
                    name: nombre,
                    value: valor
                }));
            });

            form.appendTo('body').submit();
        }

        // VER CHECKLIST
        $('#tablaCheckVAVModal').on('click', '.btn-pdf-check', function () {

            const idcheck = $(this).data('idcheck');
            const idTG = $(this).data('idtg');
            // const idEquipo = $(this).data('idequipo');

            if(idcheck > 0 && idTG > 0){
                enviarFormulario(
                    '../sdo-pdf/SDO-CheckList-VAV.php',
                    { idTG: idTG, idVAV: idcheck},
                    '_blank'
                );
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Error al Obtener CheckList',
                    text: 'No se puede visualizar CheckList',
                    confirmButtonColor: '#fa0000'
                });
            }


        });

        // VER OT
        $('#tablaCheckVAVModal').on('click', '.btn-pdf-ot', function () {

            const idcheck = $(this).data('idcheck');
            const idTG = $(this).data('idtg');
            const estado = $(this).data('estado');
            let url = 'detalle_ordenes_trabajo.php';

            if(idcheck > 0 && idTG > 0){
                if(estado === 'Trabajo Terminado'){
                    url = 'visualizardetallesterminadas.php';
                }

                enviarFormulario(
                    url,
                    {idCheck: idcheck, idTG: idTG},
                    '_blank'
                );

            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Error al Obtener OT',
                    text: 'No se puede visualizar OT',
                    confirmButtonColor: '#fa0000'
                });
            }


        });
    </script>
    <?php
} else {
    echo '<div class="alert alert-info mb-0">No se encontraron CheckList para esta UCA.</div>';
}

if (isset($stmtEquipo) && $stmtEquipo) {
    mysqli_stmt_close($stmtEquipo);
}

if (isset($stmtCheckList) && $stmtCheckList) {
    mysqli_stmt_close($stmtCheckList);
}
?>