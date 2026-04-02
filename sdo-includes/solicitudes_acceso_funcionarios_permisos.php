<?php
include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';

$idUnidad = (int)$_POST['idUnidad'];
$nomUnidad = $_POST['nomUnidad'];
$idForm = (int)$_POST['idFormRegAcceso'];

$sql = "SELECT nomFormFuncCCAA, rutFormFuncCCAA, calidadFormFuncCCAA, unidadFormFuncCCAA, correlativoTarjeta, idFormFuncCCAA
        FROM formularios_funcionarios
        WHERE idUnidad = $idUnidad AND idFormRegAcceso = $idForm";

$res = mysqli_query($conexion, $sql);

?>

&nbsp;
<br>

<table id="tablaFuncionariosUnidad" class="table table-bordered table-hover table-sm">
    <thead>
    <tr class="bg-secondary" style="font-weight:bold;">
        <th style="text-align:center;">NOMBRE</th>
        <th style="text-align:center;">RUT</th>
        <th style="text-align:center;">CALIDAD JURIDICA</th>
        <th style="text-align:center;">UNIDAD</th>
        <th style="text-align:center;">CORRELATIVO</th>
        <th style="text-align:center;">PERMISOS AUTORIZADOS</th>
    </tr>
    </thead>
    <tbody>
    <?php

    if ($res && mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {

            $nomFormFuncCCAA = $row['nomFormFuncCCAA'];
            $nomFormFuncCCAA2 = mb_convert_case($nomFormFuncCCAA, MB_CASE_UPPER, 'UTF-8');

            $rutFormFuncCCAA = $row['rutFormFuncCCAA'];
            $rutFormFuncCCAA2 = mb_convert_case($rutFormFuncCCAA, MB_CASE_UPPER, 'UTF-8');

            $calidadFormFuncCCAA = $row['calidadFormFuncCCAA'];
            $calidadFormFuncCCAA2 = mb_convert_case($calidadFormFuncCCAA, MB_CASE_UPPER, 'UTF-8');

            $unidadFormFuncCCAA = $row['unidadFormFuncCCAA'];
            $unidadFormFuncCCAA2 = mb_convert_case($unidadFormFuncCCAA, MB_CASE_UPPER, 'UTF-8');

            $correlativoTarjeta = $row['correlativoTarjeta'];

            $idFormFuncCCAA = $row['idFormFuncCCAA'];

            ?>
            <tr>
                <td style="text-align:center; vertical-align: middle;"><?php echo $nomFormFuncCCAA2; ?></td>
                <td style="text-align:center; vertical-align: middle;"><?php echo $rutFormFuncCCAA2; ?></td>
                <td style="text-align:center; vertical-align: middle;"><?php echo $calidadFormFuncCCAA2; ?></td>
                <td style="text-align:center; vertical-align: middle;"><?php echo $unidadFormFuncCCAA2; ?></td>
                <td style="text-align:center; vertical-align: middle;"><?php echo $correlativoTarjeta; ?></td>
                <td style="text-align:center; vertical-align: middle;">
                    <button class="btn btn-block btn-warning" id="btnVerAutorizaciones" data-idfuncccaa="<?php echo $idFormFuncCCAA; ?>" data-unidadfunccaa="<?php echo $idUnidad; ?>" data-nombrefunc="<?php echo $nomFormFuncCCAA2 ?>" data-rutfunc="<?php echo $rutFormFuncCCAA2 ?>">
                        <i class="fas fa-eye"></i>
                    </button>
                </td>
            </tr>


            <?php
        }
    } else {
        ?>
        <tr>
            <td colspan="6" style="text-align:center; vertical-align: middle;">No se encontraron funcionarios en esta unidad.</td>
        </tr>
        <?php
    }
    ?>
    </tbody>
    <tfoot>
    <tr class="bg-secondary" style="font-weight:bold;">
        <th style="text-align:center;">NOMBRE</th>
        <th style="text-align:center;">RUT</th>
        <th style="text-align:center;">CALIDAD JURIDICA</th>
        <th style="text-align:center;">UNIDAD</th>
        <th style="text-align:center;">CORRELATIVO</th>
        <th style="text-align:center;">PERMISOS AUTORIZADOS</th>
    </tr>
    </tfoot>
</table>
<!-- Modal para ver autorizaciones -->
<div class="modal fade" id="modalAutorizaciones" tabindex="-1" role="dialog" aria-labelledby="modalAutorizacionesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalAutorizacionesLabel">Permisos Autorizados</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="contenidoAutorizaciones">
                <p class="text-muted">Cargando información...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;&nbsp;Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        // Inicializar DataTable
        $('#tablaFuncionariosUnidad').DataTable({
            "paging": true,
            "iDisplayLength": 10,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por Pág.",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "search": "Buscar:",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            }
        });

        // Botón para ver autorizaciones
        $(document).on('click', '#btnVerAutorizaciones', function () {
            let idFormFuncCCAA = $(this).data('idfuncccaa');
            let unidadFormFuncCCAA = $(this).data('unidadfunccaa');
            let nomFormFuncCCAA = $(this).data('nombrefunc');
            let rutFormFuncCCAA = $(this).data('rutfunc');

            $('#contenidoAutorizaciones').html('<p class="text-info">Cargando autorizaciones...</p>');
            $('#modalAutorizaciones').modal('show');

            $.ajax({
                url: '/sdo-includes/solicitudes_acceso_funcionarios_autorizaciones.php',
                method: 'POST',
                data: {
                    idFormFuncCCAA: idFormFuncCCAA,
                    unidadFormFuncCCAA: unidadFormFuncCCAA,
                    nomFormFuncCCAA: nomFormFuncCCAA,
                    rutFormFuncCCAA: rutFormFuncCCAA
                },
                success: function (data) {
                    $('#contenidoAutorizaciones').html(data);

                    // Verificar y destruir si ya existe
                    if ($.fn.DataTable.isDataTable('#tablaAutorizaciones')) {
                        $('#tablaAutorizaciones').DataTable().clear().destroy();
                    }

                    // Inicializar nuevamente
                    $('#tablaAutorizaciones').DataTable({
                        "paging": true,
                        "iDisplayLength": 10,
                        "lengthChange": false,
                        "searching": true,
                        "ordering": false,
                        "info": true,
                        "autoWidth": false,
                        "responsive": true,
                        "language": {
                            "lengthMenu": "Visualizando _MENU_ Registros por Pág.",
                            "zeroRecords": "No se han encontrado Registros.",
                            "info": "Mostrando página _PAGE_ de _PAGES_",
                            "infoEmpty": "No existen Registros.",
                            "search": "Buscar:",
                            "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                            "paginate": {
                                "previous": "Anterior",
                                "next": "Siguiente"
                            }
                        }
                    });
                },
                error: function () {
                    $('#contenidoAutorizaciones').html('<p class="text-danger">Error al cargar la información.</p>');
                }
            });
        });
    });
</script>