<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8');

include_once '../../sdo-funciones/conexion.php';
include_once '../../sdo-funciones/funciones.php';

function formatearVacioFilas($valor)
{
    $valorLimpio = trim($valor ?? '');
    return $valorLimpio !== '' ? $valorLimpio : '';
}

function h($string)
{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

$idCarpeta = isset($_GET['idCarpeta']) ? (int)$_GET['idCarpeta'] : 0;

if ($idCarpeta <= 0) {
    echo '<div class="alert alert-warning">Categoría no válida.</div>';
    exit;
}

$sqlUMAS = "SELECT * FROM equipamiento_hbqp WHERE idEquipoPadre = 11142 ORDER BY piso ASC";

$resUMAS = mysqli_query($conexion, $sqlUMAS);

if ($resUMAS && mysqli_num_rows($resUMAS) > 0) {
    ?>
    <div class="container-fluid">
        <h5 class="text-center">Unidades Manejadoras de Aire (UMAS)</h5>
        <hr>

        <div class="form-inline mb-2">
            <label class="mr-2"><strong>Filtrar por Piso:</strong></label>
            <select id="filtroPiso" class="form-control" style="width:200px;">
                <option value="">Todos los pisos</option>
            </select>
        </div>

        <hr>

        <div class="table-responsive">
            <table id="tablaUmasListado" class="table table-hover table-striped table-sm mb-0">
                <thead class="bg-secondary text-center">
                <tr>
                    <th>N°</th>
                    <th>Nombre</th>
                    <th>Imagen</th>
                    <th>Piso</th>
                    <th>Visualizar</th>
                </tr>
                </thead>
                <tbody>
                <?php

                $numList = 1;

                while ($rowUMAS = mysqli_fetch_assoc($resUMAS)) {

                    $nombreEquipo = formatearVacioFilas($rowUMAS['nombreEquipo']);
                    $nombreEquipo2 = mb_convert_case($nombreEquipo, MB_CASE_UPPER, 'UTF-8');

                    $piso = formatearVacioFilas($rowUMAS['piso']);
                    $piso2 = mb_convert_case($piso, MB_CASE_UPPER, 'UTF-8');

                    $idCategoria = formatearVacioFilas($rowUMAS['idCategoria']);

                    $tipoMenu = formatearVacioFilas($rowUMAS['tipoMenu']);

                    $idEquipo = $rowUMAS['id'] ?? 0;

                    if($idEquipo > 0){
                        ?>
                        <tr>
                            <td><?= h($numList ?? '') ?></td>
                            <td class="font-weight-bold"><?= h($nombreEquipo) ?></td>
                            <td class="text-center">
                                <img src="../archivos-equipamiento/Equipo-11142/UMA.JPG" alt="Imagen de <?= h($nombreEquipo) ?>" class="img-thumbnail" style="max-width: 100px;">
                            </td>
                            <td><?= h($piso) ?></td>
                            <td class="text-center">
                                <button class="btn btn-primary btn-veruma" data-id="<?= $idEquipo ?>" data-menu="<?php echo h($tipoMenu); ?>" data-idcategoria="<?= $idCategoria ?>">
                                    <i class="fas fa-eye"></i> Visualizar
                                </button>
                            </td>
                        </tr>
                        <?php
                        $numList++;
                    }


                }
                ?>
                </tbody>
                <tfoot class="bg-secondary text-center">
                <tr>
                    <th>N°</th>
                    <th>Nombre</th>
                    <th>Imagen</th>
                    <th>Piso</th>
                    <th>Visualizar</th>
                </tr>
                </tfoot>
            </table>
        </div>

        <script>
            // 1. Validamos que el DOM haya procesado este nuevo HTML
            $(document).ready(function () {

                // 2. IMPORTANTE: Destruir la tabla si ya existía para evitar errores de recarga
                if ($.fn.DataTable.isDataTable('#tablaUmasListado')) {
                    $('#tablaUmasListado').DataTable().destroy();
                }

                // 3. Inicializamos CON SU CONFIGURACIÓN ÚNICA
                var tabla = $('#tablaUmasListado').DataTable({
                    lengthChange: false,
                    searching: true,
                    autoWidth: false,
                    responsive: true,
                    ordering: false,
                    info: true,
                    language: {
                        lengthMenu: "Visualizando _MENU_ registros por página",
                        zeroRecords: "No se han encontrado registros.",
                        info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
                        infoEmpty: "Mostrando 0 a 0 de 0 registros",
                        infoFiltered: "(filtrado desde _MAX_ registros totales)",
                        search: "Buscar:",
                        processing: "Procesando...",
                        paginate: {
                            previous: "Anterior",
                            next: "Siguiente"
                        }
                    },
                    createdRow: function (row, data, dataIndex) {
                        $('td', row).css('vertical-align', 'middle');
                        $('td', row).css('text-align', 'center');
                        $('td:eq(0)', row).html(dataIndex + 1);
                    },

                    initComplete: function () {

                        var api = this.api();
                        var columna = api.column(3);
                        var select = $('#filtroPiso');

                        // limpiar select
                        select.empty();
                        select.append('<option value="">Todos los pisos</option>');

                        columna.data().unique().sort().each(function (d) {

                            if (d !== null && d !== '') {
                                select.append('<option value="' + d + '">' + d + '</option>');
                            }

                        });

                    }

                });


                // FILTRO
                $('#filtroPiso').on('change', function () {

                    var valor = $(this).val();

                    if (valor === '') {
                        tabla.column(3).search('').draw();
                    } else {
                        tabla.column(3).search('^' + valor + '$', true, false).draw();
                    }

                });

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

                // HOJA DE VIDA
                $('#tablaUmasListado').on('click', '.btn-veruma', function () {

                    const id = $(this).data('id');
                    const idCategoria = $(this).data('idcategoria');
                    const tipomenu = $(this).data('menu');

                    const url = (tipomenu === 'nuevo')
                        ? 'equipo_menuNuevo.php'
                        : 'equipo_menuAntiguo.php';

                    const target = (tipomenu === 'nuevo')
                        ? '_self'
                        : '_blank';

                    enviarFormulario(url, {
                        idEquipo: id,
                        idCategoria: idCategoria
                    }, target);

                });


            });
        </script>

    </div>

    <?php
} else {
    echo '<div class="alert alert-info">No se encontraron equipos para esta categoría.</div>';
}
?>