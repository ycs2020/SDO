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

function my($texto){
    if ($texto === null || $texto === '') {
        return h("Sin Información");
    }

    return h(
        ($texto === 0 || $texto === '0')
            ? '0'
            : mb_convert_case($texto, MB_CASE_UPPER, 'UTF-8')
    );
}

$query = "SELECT
                equipoInventario, 
                marcaInventario, 
                modeloInventario, 
                SUM(cantidadInventario) AS cantidadInventario 
        FROM inventario_equipos 
        WHERE categoriaInventario = ?
        GROUP BY modeloInventario";

$stmt = $conexion->prepare($query);
$stmt->bind_param('i', $idCategoria);
$stmt->execute();
$res = $stmt->get_result();

if (!$res || $res->num_rows === 0) {
    echo "<p>No se encontraron equipos en inventario.</p>";
    exit;
}

ob_start();
?>

    <style>
        #tablaInventarioCarpeta th,
        #tablaInventarioCarpeta td {
            text-align: center !important;
            vertical-align: middle !important;
        }
    </style>

    <table id="tablaInventarioCarpeta" class="table table-sm table-bordered table-striped">
        <thead class="bg-secondary text-white">
        <tr>
            <th>N°</th>
            <th>Nombre</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Cantidad</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $contador = 1;

        while ($row = $res->fetch_assoc()) {
            $equipoInventario    = $row['equipoInventario'];
            $marcaInventario     = $row['marcaInventario'];
            $modeloInventario    = $row['modeloInventario'];
            $cantidadInventario  = $row['cantidadInventario'];

            $btnVerEquipo = '<em class="text-red">Sin Equipo Asociado</em>';

            ?>
            <tr>
                <td><?= $contador++; ?></td>
                <td><?= my($equipoInventario); ?></td>
                <td><?= my($marcaInventario); ?></td>
                <td><?= my($modeloInventario); ?></td>
                <td><?= my($cantidadInventario); ?></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
        <tfoot class="bg-secondary text-white">
        <tr>
            <th>N°</th>
            <th>Nombre</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Cantidad</th>
        </tr>
        </tfoot>
    </table>

    <script>
        if ($.fn.DataTable.isDataTable('#tablaInventarioCarpeta')) {
            $('#tablaInventarioCarpeta').DataTable().destroy();
        }

        $('#tablaInventarioCarpeta').DataTable({
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

    </script>

<?php
echo ob_get_clean();
?>