<?php
include_once '../../sdo-funciones/conexion.php';
include_once '../../sdo-funciones/funciones.php';

if (!isset($_POST['idCarpeta'])) {
    echo "<p class='text-danger'>Error: parámetros incompletos.</p>";
    exit;
}

$idCategoria = (int)$_POST['idCarpeta'];

function h($string)
{
    return htmlspecialchars((string)($string ?? ''), ENT_QUOTES, 'UTF-8');
}

function formatearFechaCompletaAJAX($fechaRaw)
{
    $fechaCompleta = 'Fecha inválida';

    if (!empty($fechaRaw)) {
        $timestamp = strtotime(str_replace('/', '-', $fechaRaw));

        if ($timestamp !== false && $timestamp > 0) {
            $fechaCompleta = fechaEspañol2($timestamp);
        }
    }

    return $fechaCompleta;
}

$query = "SELECT * 
          FROM ordenes_trabajos_general 
          WHERE idCategoria = ? 
            AND (ticketAsociado != '' AND ticketAsociado != '0') 
          ORDER BY fechaOrden DESC";

$stmt = $conexion->prepare($query);
$stmt->bind_param('i', $idCategoria);
$stmt->execute();
$res = $stmt->get_result();

if (!$res || $res->num_rows === 0) {
    echo "<p>No se encontraron registros para esta categoría.</p>";
    exit;
}

ob_start();
?>

    <style>
        #tablaTicketsCarpeta th,
        #tablaTicketsCarpeta td {
            text-align: center !important;
            vertical-align: middle !important;
        }
    </style>

    <table id="tablaTicketsCarpeta" class="table table-sm table-bordered table-striped">
        <thead class="bg-secondary text-white">
        <tr>
            <th>N°</th>
            <th>Hoja Técnica</th>
            <th>Fecha</th>
            <th>Personal</th>
            <th>Ticket</th>
            <th>Equipo</th>
            <th>Ver HT</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $contador = 1;

        while ($row = $res->fetch_assoc()) {
            $idOrden        = (int)($row['idOrden'] ?? 0);
            $folioOrden     = $row['folioOrden'] ?? '';
            $fechaOrden     = formatearFechaCompletaAJAX($row['fechaOrden'] ?? '');
            $nomSolicitante = mb_strtoupper((string)($row['nomSolicitante'] ?? ''), 'UTF-8');
            $ticketAsociado = mb_strtoupper((string)($row['ticketAsociado'] ?? ''), 'UTF-8');
            $idEquipo       = (int)($row['idEquipo'] ?? 0);

            $btnVerEquipo = '<em class="text-muted">Sin Equipo Asociado</em>';
            $btnVerHT     = '<em class="text-muted">Sin Hoja Técnica</em>';

            if ($idEquipo > 0) {
                $tipoMenu = obtenerTipoMenu($idEquipo);
                $tipoMenu = mb_strtolower((string)$tipoMenu, 'UTF-8');
                $tipoMenu = ($tipoMenu === 'nuevo') ? 'nuevo' : 'antiguo';

                $btnVerEquipo = "
                    <button 
                        type='button'
                        class='btn btn-block btn-sm btn-primary btn-equipo-ticket' 
                        data-idequipo='" . h($idEquipo) . "' 
                        data-menu='" . h($tipoMenu) . "'>
                        <i class='fas fa-eye'></i>
                    </button>
                ";
            }

            if ($idOrden > 0) {
                $btnVerHT = "
                    <button 
                        type='button'
                        class='btn btn-block btn-sm btn-success btn-ht-ticket' 
                        data-idorden='" . h($idOrden) . "'>
                        <i class='fas fa-file-alt'></i>
                    </button>
                ";
            }
            ?>
            <tr>
                <td><?= $contador++; ?></td>
                <td><?= h($folioOrden); ?></td>
                <td><?= h($fechaOrden); ?></td>
                <td><?= h($nomSolicitante); ?></td>
                <td><?= h($ticketAsociado); ?></td>
                <td><?= $btnVerEquipo; ?></td>
                <td><?= $btnVerHT; ?></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
        <tfoot class="bg-secondary text-white">
        <tr>
            <th>N°</th>
            <th>Hoja Técnica</th>
            <th>Fecha</th>
            <th>Personal</th>
            <th>Ticket</th>
            <th>Equipo</th>
            <th>Ver HT</th>
        </tr>
        </tfoot>
    </table>

    <script>
        if ($.fn.DataTable.isDataTable('#tablaTicketsCarpeta')) {
            $('#tablaTicketsCarpeta').DataTable().destroy();
        }

        $('#tablaTicketsCarpeta').DataTable({
            destroy: true,
            lengthChange: false,
            searching: true,
            autoWidth: false,
            pageLength: 10,
            responsive: true,
            ordering: false,
            processing: true,
            language: {
                lengthMenu: "Visualizando _MENU_ Registros por Página",
                zeroRecords: "No se han encontrado Registros.",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No existen Registros.",
                infoFiltered: "(Filtrado desde _MAX_ Registros Totales)",
                search: "Buscar:",
                processing: "Procesando...",
                paginate: {
                    previous: "Anterior",
                    next: "Siguiente"
                }
            }
        });

        $('#tablaTicketsCarpeta').off('click', '.btn-equipo-ticket').on('click', '.btn-equipo-ticket', function () {
            let idEquipo = $(this).data('idequipo');
            let tipoMenu = $(this).data('menu');
            let urlMenu = 'equipo_menuAntiguo.php';

            if (tipoMenu === 'nuevo') {
                urlMenu = 'equipo_menuNuevo.php';
            }

            let form = $('<form>', {
                action: urlMenu,
                method: 'POST',
                target: '_blank'
            });

            form.append($('<input>', {
                type: 'hidden',
                name: 'idEquipo',
                value: idEquipo
            }));

            $('body').append(form);
            form.trigger('submit');
            form.remove();
        });

        $('#tablaTicketsCarpeta').off('click', '.btn-ht-ticket').on('click', '.btn-ht-ticket', function () {
            let idOrden = $(this).data('idorden');

            let form = $('<form>', {
                action: '../sdo-pdf/SDO-OrdenTrabajoGeneral.php',
                method: 'POST',
                target: '_blank'
            });

            form.append($('<input>', {
                type: 'hidden',
                name: 'idOrden',
                value: idOrden
            }));

            $('body').append(form);
            form.trigger('submit');
            form.remove();
        });
    </script>

<?php
echo ob_get_clean();
?>