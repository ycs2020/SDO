<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';

if (isset($_POST['idCategoria']) && is_numeric($_POST['idCategoria'])) {
    $idCategoria = (int)$_POST['idCategoria'];

    if ($idCategoria <= 0) {
        echo "<div class='alert alert-danger'>ID de categoría inválido.</div>";
        exit;
    }

    $sql = "SELECT DISTINCT modelo, marca 
            FROM equipamiento_hbqp 
            WHERE idCategoria = $idCategoria 
              AND modelo IS NOT NULL 
              AND modelo <> ''";

    $result = mysqli_query($conexion, $sql);

    if (!$result || mysqli_num_rows($result) === 0) {
        echo "<div class='alert alert-warning'>No se encontraron datos para esta categoría.</div>";
        exit;
    }

    $totalGeneral = 0;

    ?>
    <div class="table-responsive">
        <table id="tablaValorEquipos" class="table table-striped table-bordered table-hover" style="width:100%">
            <thead class="bg-purple text-white">
            <tr>
                <th>Modelo</th>
                <th>Marca</th>
                <th>Cantidad</th>
                <th>Precio Unitario (Con IVA)</th>
                <th>Precio Total (Con IVA)</th>
            </tr>
            </thead>
            <tbody>

            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                $modelo = $row['modelo'] ?: 'No Aplica';
                $modelo = mb_convert_case($modelo, MB_CASE_UPPER, 'UTF-8');

                $marca = $row['marca'] ?: 'No Aplica';
                $marca = mb_convert_case($marca, MB_CASE_UPPER, 'UTF-8');

                $precioUnitario = obtenerMayorValorEquipamiento($modelo, $idCategoria);
                $precioUnitario = is_numeric($precioUnitario) ? floatval($precioUnitario) : 0;
                $precioUnitarioTexto = $precioUnitario > 0 ? moneda_chilena($precioUnitario) : '$ 0';

                $cantidad = contarModelosDistintos($modelo, $idCategoria);
                $cantidad = is_numeric($cantidad) ? intval($cantidad) : 0;
                $cantidadTexto = $cantidad > 0 ? $cantidad : 0;

                $precioTotal = $precioUnitario * $cantidad;
                $precioTotalTexto = $precioTotal > 0 ? moneda_chilena($precioTotal) : '$ 0';

                $totalGeneral += $precioTotal;


                ?>
                <tr>
                    <td class="text-center"><?php echo $modelo; ?></td>
                    <td class="text-center"><?php echo $marca; ?></td>
                    <td class="text-center"><?php echo $cantidadTexto; ?></td>
                    <td class="text-center"><?php echo $precioUnitarioTexto; ?></td>
                    <td class="text-center"><?php echo $precioTotalTexto; ?></td>
                </tr>
                <?php
            }

            ?>
            </tbody>
            <tfoot class="bg-purple text-white">
            <tr>
                <th>Modelo</th>
                <th>Marca</th>
                <th>Cantidad</th>
                <th>Precio Unitario (Con IVA)</th>
                <th>Precio Total (Con IVA)</th>
            </tr>
            </tfoot>
        </table>
    </div>
    <script>
        $('#tablaValorEquipos').DataTable({
            "paging": true,
            "iDisplayLength": 10,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "search": "Buscar:",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
        });
    </script>
    <?php

} else {
    echo "<div class='alert alert-danger'>ID de categoría inválido o no enviado.</div>";
}
?>
