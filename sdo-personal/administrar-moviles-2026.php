<?php
include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrar Equipos</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">

    <style>
        .form-section-title {
            font-size: 14px;
            font-weight: bold;
            color: #6f42c1;
            border-bottom: 1px solid #eee;
            margin-bottom: 15px;
            padding-bottom: 5px;
            text-transform: uppercase;
        }

        .form-group label {
            font-size: 12px;
            margin-bottom: 2px;
        }

        .bg-purple {
            background-color: #6f42c1 !important;
            color: white;
        }

        #tablaVehiculos th,
        #tablaVehiculos td {
            vertical-align: middle !important;
            text-align: center !important;
        }

        .btn-xs {
            display: inline-block;
            width: 100%;
            min-width: 40px;
            margin: 0 auto;
        }

        .badge {
            display: inline-block;
            padding: 0.5em 0.8em;
            font-size: 85%;
        }

        /* Ajuste para que las tabs luzcan bien en AdminLTE */
        .nav-tabs .nav-link.active {
            border-top: 3px solid #6f42c1 !important;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <?php include '../sdo-templates/user-sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-md-10 col-9">
                        <div class="card bg-primary text-white shadow-sm mb-0">
                            <div class="card-body py-2 text-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-industry mr-2"></i> Gestión de Movilización
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-3">
                        <a href="index.php" class="btn btn-danger btn-block shadow-sm font-weight-bold">
                            <i class="fa fa-chevron-left"></i> VOLVER
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">

                <div class="card card-purple card-outline card-tabs shadow-sm">
                    <div class="card-header p-0 pt-1 border-bottom-0">
                        <ul class="nav nav-tabs" id="tabSistemas" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="tab-listado-vehiculos-tab" data-toggle="pill" href="#tab-listado-vehiculos" role="tab" aria-controls="tab-listado-vehiculos" aria-selected="true">
                                    <i class="fas fa-car mr-1"></i> Listado de Vehículos
                                </a>
                            </li>
                            <?php
                            $ipCliente = $_SERVER['REMOTE_ADDR'];

                            if ($ipCliente === '10.69.250.151') {
                                ?>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-dashboard-km-tab" data-toggle="pill" href="#tab-dashboard-km" role="tab" aria-controls="tab-dashboard-km" aria-selected="false">
                                        <i class="fas fa-tools mr-1"></i> Dashboard de Kilometraje
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" id="tab-dashboard-comb-tab" data-toggle="pill" href="#tab-dashboard-comb" role="tab" aria-controls="tab-dashboard-comb" aria-selected="false">
                                        <i class="fas fa-tools mr-1"></i> Dashboard de Combustible
                                    </a>
                                </li>
                                <?php
                            }

                            ?>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content" id="tabSistemasContent">

                            <div class="tab-pane fade show active" id="tab-listado-vehiculos" role="tabpanel" aria-labelledby="tab-listado-vehiculos-tab">
                                <div class="row justify-content-center">
                                    <div class="col-md-4 mb-4">
                                        <button class="btn btn-block bg-purple shadow-sm py-2" data-toggle="modal" data-target="#modalVehiculo">
                                            <i class="fas fa-plus-circle mr-2"></i><b>AGREGAR NUEVO VEHÍCULO</b>
                                        </button>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-sm text-center table-valign-middle" id="tablaVehiculos" style="width:100%">
                                        <thead class="bg-gradient-secondary">
                                        <tr>
                                            <th>ID</th>
                                            <th>NOMBRE</th>
                                            <th>PATENTE</th>
                                            <th>MARCA</th>
                                            <th>MODELO</th>
                                            <th>SERVICIO</th>
                                            <th>VALOR C/IVA</th>
                                            <th>ESTADO</th>
                                            <th>FECHA ULT. ESTADO</th>
                                            <th>EST. MANTENIMIENTO</th>
                                            <th>VISUALIZAR</th>
                                            <th>ELIMINAR</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tab-dashboard-km" role="tabpanel" aria-labelledby="tab-dashboard-km-tab">
                                <?php
                                include_once '../sdo-includes/vehiculos/vehiculos_dashboard_kilometraje.php';
                                ?>
                            </div>

                            <div class="tab-pane fade" id="tab-dashboard-comb" role="tabpanel" aria-labelledby="tab-dashboard-comb-tab">
                                <?php
                                include_once '../sdo-includes/vehiculos/vehiculos_dashboard_combustible.php';
                                ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="modal fade" id="modalVehiculo" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-header bg-purple text-white">
                        <h5 class="modal-title font-weight-bold">
                            <i class="fas fa-car-side mr-2"></i> Registro Detallado de Vehículo
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="formVehiculo" method="POST" action="../sdo-includes/vehiculos/vehiculos_listado_agregar.php">
                        <div class="modal-body p-4">
                            <div class="form-section-title">
                                <i class="fas fa-id-card mr-2"></i> Identificación
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>ID Único
                                        <code>*</code></label>
                                    <input type="text" class="form-control form-control-sm" name="idVehiculo" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Nombre del Vehículo
                                        <code>*</code></label>
                                    <input type="text" class="form-control form-control-sm" name="nomVehiculo" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Patente
                                        <code>*</code></label>
                                    <input type="text" class="form-control form-control-sm" name="patenteVehiculo" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Marca
                                        <code>*</code></label>
                                    <input type="text" class="form-control form-control-sm" name="marcaVehiculo" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Modelo</label>
                                    <input type="text" class="form-control form-control-sm" name="modeloVehiculo">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Servicio Asignado
                                        <code>*</code></label>
                                    <select name="servicioVehiculo" class="form-control form-control-sm" required>
                                        <option value="">Seleccione Servicio...</option>
                                        <?php
                                        $resServicios = mysqli_query($conexion, "SELECT nomServicio FROM servicios ORDER BY nomServicio ASC");
                                        while ($rowS = $resServicios->fetch_assoc()) {
                                            echo "<option value='{$rowS['nomServicio']}'>{$rowS['nomServicio']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>N° de Inventario</label>
                                    <input type="text" class="form-control form-control-sm" name="numInventario">
                                </div>
                            </div>
                            <div class="form-section-title mt-3">
                                <i class="fas fa-tools mr-2"></i> Especificaciones
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>N° de Chasis</label>
                                    <input type="text" class="form-control form-control-sm" name="numChasis">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>N° de Motor</label>
                                    <input type="text" class="form-control form-control-sm" name="numMotor">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Fabricante</label>
                                    <input type="text" class="form-control form-control-sm" name="frabricante">
                                </div>
                            </div>
                            <div class="form-section-title mt-3">
                                <i class="fas fa-file-invoice-dollar mr-2"></i> Finanzas
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Fecha de Compra</label>
                                    <input type="date" class="form-control form-control-sm" name="fechaCompra">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Orden de Compra</label>
                                    <input type="text" class="form-control form-control-sm" name="ordenCompra">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Vida Útil</label>
                                    <input type="text" class="form-control form-control-sm" name="vidaUtil">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Valor con IVA ($)</label>
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" class="form-control" name="valorConIva">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-light justify-content-between">
                            <button type="button" class="btn btn-outline-danger font-weight-bold" data-dismiss="modal">CERRAR</button>
                            <button type="submit" class="btn btn-success px-4 font-weight-bold">
                                <i class="fas fa-save mr-1"></i> GUARDAR VEHÍCULO
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer class="main-footer">
        <strong>Copyright &copy;.</strong>
    </footer>
</div>

<script src="../plugins/jquery/jquery.min.js"></script>
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../dist/js/adminlte.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="js/dark-mode.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<script>
    $(document).ready(function () {

        // CORRECCIÓN PARA DATATABLES EN TABS:
        // Ajusta las columnas cuando se cambia de pestaña para evitar que se vean encogidas
        $('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });

        // 1. Inicializar DataTable
        var tablaVehiculos = $('#tablaVehiculos').DataTable({
            "ajax": {
                "url": "../sdo-includes/vehiculos/vehiculos_listado_obtener.php",
                "dataSrc": ""
            },
            "columns": [
                {"data": "idUnico"},
                {"data": "nomVehiculo"},
                {"data": "patenteVehiculo"},
                {"data": "marcaVehiculo"},
                {"data": "modeloVehiculo"},
                {"data": "servicioVehiculo"},
                {"data": "valorConIva"},
                {"data": "estadoVehiculo"},
                {"data": "fechaReporte"},
                {"data": "badgeMantenimiento"},
                {"data": "acciones"},
                {"data": "eliminar"}
            ],
            "paging": true,
            "iDisplayLength": 25,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "Sin registros para mostrar.",
                "search": "Buscar:",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
            "columnDefs": [
                {
                    "className": "dt-center",
                    "targets": "_all"
                }
            ],
        });

        // 2. PROCESAMIENTO AJAX AGREGAR
        $('#formVehiculo').on('submit', function (e) {
            e.preventDefault();
            let btnGuardar = $(this).find('button[type="submit"]');
            btnGuardar.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response && response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Registro Exitoso!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            $('#modalVehiculo').modal('hide');
                            $('#formVehiculo')[0].reset();
                            tablaVehiculos.ajax.reload();
                        });
                    } else {
                        Swal.fire('Error', 'No se pudo guardar: ' + (response.message || 'Error desconocido'), 'error');
                    }
                },
                error: function (jqXHR) {
                    Swal.fire('Error', 'Error crítico en el servidor.', 'error');
                },
                complete: function () {
                    btnGuardar.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> GUARDAR VEHÍCULO');
                }
            });
        });

        // 3. Eliminar
        $(document).on('click', '.btn-eliminar-movil', function () {
            let id = $(this).attr('id');
            Swal.fire({
                title: '¿Eliminar vehículo?',
                text: "Esta acción solo se realizará si no hay historiales asociados",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('../sdo-includes/vehiculos/vehiculos_listado_eliminar.php', {id: id}, function (response) {
                        let res = JSON.parse(response);
                        if (res.status === 'success') {
                            Swal.fire('¡Eliminado!', res.message, 'success');
                            tablaVehiculos.ajax.reload();
                        } else {
                            Swal.fire('Atención', res.message, 'error');
                        }
                    });
                }
            });
        });

        // 4. Visualizar
        $(document).on('click', '.btn-ver-movil', function () {
            let idMovil = $(this).attr('id');
            let form = $('<form>', {
                'action': 'visualizarMovil.php',
                'method': 'POST'
            }).append($('<input>', {
                'type': 'hidden',
                'name': 'idMovil',
                'value': idMovil
            }));
            $('body').append(form);
            form.submit();
        });
    });
</script>
<script src="../sdo-includes/vehiculos_js/vehiculos_datos_kilometraje.js"></script>
<script src="../sdo-includes/vehiculos_js/vehiculos_dashboard_combustible.js"></script>
</body>
</html>

