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
    <title>Administrar Equipos | Vamoos SpA</title>

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
        .form-group label { font-size: 12px; margin-bottom: 2px; }
        .bg-purple { background-color: #6f42c1 !important; color: white; }

        /* Centrado vertical y horizontal para todas las celdas de la tabla */
        #tablaVehiculos th,
        #tablaVehiculos td {
            vertical-align: middle !important;
            text-align: center !important;
        }

        /* Ajuste para los botones de acción para que no se vean gigantes */
        .btn-xs {
            display: inline-block;
            width: 100%;
            min-width: 40px;
            margin: 0 auto;
        }

        /* Opcional: Estilo para los badges de estado para que se vean uniformes */
        .badge {
            display: inline-block;
            padding: 0.5em 0.8em;
            font-size: 85%;
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
                                <h5 class="mb-0"><i class="fas fa-industry mr-2"></i> Gestión de Vehículos - Movilización</h5>
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
                <div class="row justify-content-center">
                    <div class="col-md-4 mb-4">
                        <button class="btn btn-block bg-purple shadow-lg py-2" data-toggle="modal" data-target="#modalVehiculo">
                            <i class="fas fa-plus-circle mr-2"></i><b>AGREGAR NUEVO VEHÍCULO</b>
                        </button>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-gradient-primary">
                        <h3 class="card-title font-weight-bold">Lista de Equipos Registrados</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm text-center" id="tablaVehiculos">
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
                                    <th>VISUALIZAR</th>
                                    <th>ELIMINAR</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot class="bg-gradient-secondary">
                                <tr>
                                    <th>ID</th>
                                    <th>NOMBRE</th>
                                    <th>PATENTE</th>
                                    <th>MARCA</th>
                                    <th>MODELO</th>
                                    <th>SERVICIO</th>
                                    <th>VALOR C/IVA</th>
                                    <th>ESTADO</th>
                                    <th>VISUALIZAR</th>
                                    <th>ELIMINAR</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="modal fade" id="modalVehiculo" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-header bg-purple text-white">
                        <h5 class="modal-title font-weight-bold"><i class="fas fa-car-side mr-2"></i> Registro Detallado de Vehículo</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="formVehiculo" method="POST" action="../sdo-includes/vehiculos/vehiculos_listado_agregar.php">
                        <div class="modal-body p-4">

                            <div class="form-section-title"><i class="fas fa-id-card mr-2"></i> Identificación del Vehículo</div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>ID Único <code>*</code></label>
                                    <input type="text" class="form-control form-control-sm" name="idVehiculo" required placeholder="ID de sistema">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Nombre del Vehículo <code>*</code></label>
                                    <input type="text" class="form-control form-control-sm" name="nomVehiculo" required placeholder="Nombre descriptivo">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Patente <code>*</code></label>
                                    <input type="text" class="form-control form-control-sm" name="patenteVehiculo" required placeholder="ABCD-12">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Marca <code>*</code></label>
                                    <input type="text" class="form-control form-control-sm" name="marcaVehiculo" required placeholder="Marca">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Modelo</label>
                                    <input type="text" class="form-control form-control-sm" name="modeloVehiculo" placeholder="Modelo">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Servicio Asignado <code>*</code></label>
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
                                    <input type="text" class="form-control form-control-sm" name="numInventario" placeholder="N° de serie interna">
                                </div>
                            </div>

                            <div class="form-section-title mt-3"><i class="fas fa-tools mr-2"></i> Especificaciones Técnicas</div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>N° de Chasis</label>
                                    <input type="text" class="form-control form-control-sm" name="numChasis" placeholder="Chasis">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>N° de Motor</label>
                                    <input type="text" class="form-control form-control-sm" name="numMotor" placeholder="Motor">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Fabricante</label>
                                    <input type="text" class="form-control form-control-sm" name="frabricante" placeholder="Nombre del fabricante">
                                </div>
                            </div>

                            <div class="form-section-title mt-3"><i class="fas fa-file-invoice-dollar mr-2"></i> Compra y Vida Útil</div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Fecha de Compra</label>
                                    <input type="date" class="form-control form-control-sm" name="fechaCompra">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Orden de Compra</label>
                                    <input type="text" class="form-control form-control-sm" name="ordenCompra" placeholder="Ej: OC-1234">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Vida Útil (Años/KM)</label>
                                    <input type="text" class="form-control form-control-sm" name="vidaUtil" placeholder="Indique vida útil">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Valor con IVA ($)</label>
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light text-success font-weight-bold">$</span>
                                        </div>
                                        <input type="number" class="form-control" name="valorConIva" placeholder="0">
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
        <strong>Copyright &copy; 2026. Vamoos SpA.</strong>
    </footer>
</div>

<script src="../plugins/jquery/jquery.min.js"></script>
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../dist/js/adminlte.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function() {
        // 1. Inicializar DataTable
        var tablaVehiculos = $('#tablaVehiculos').DataTable({
            "ajax": {
                "url": "../sdo-includes/vehiculos/vehiculos_listado_obtener.php",
                "dataSrc": ""
            },
            "columns": [
                { "data": "idUnico" },
                { "data": "nomVehiculo" },
                { "data": "patenteVehiculo" },
                { "data": "marcaVehiculo" },
                { "data": "modeloVehiculo" },
                { "data": "servicioVehiculo" },
                { "data": "valorConIva" },
                { "data": "estadoVehiculo" },
                { "data": "acciones" },
                { "data": "eliminar" }
            ],
            "paging": true,
            "iDisplayLength": 25,
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
                "infoEmpty": "Sin registros para mostrar.",
                "search": "Buscar:",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
            // ESTO ASEGURA EL CENTRADO EN EL CUERPO DE LA TABLA
            "columnDefs": [
                { "className": "dt-center", "targets": "_all" }
            ],
        });

        // 2. PROCESAMIENTO AJAX (SOLO ESTE BLOQUE)
        $('#formVehiculo').on('submit', function(e) {
            e.preventDefault();

            let btnGuardar = $(this).find('button[type="submit"]');
            btnGuardar.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if(response && response.status === 'success') {
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
                        // Aquí capturamos si el PHP mandó un error
                        Swal.fire('Error', 'No se pudo guardar: ' + (response.message || 'Error desconocido'), 'error');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Si el JSON viene mal formado o el PHP tiró un error 500
                    console.log("Error detallado:", jqXHR.responseText);
                    Swal.fire('Error', 'Error crítico en el servidor o respuesta inválida.', 'error');
                },
                complete: function() {
                    btnGuardar.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> GUARDAR VEHÍCULO');
                }
            });
        });

        // 3. Lógica para Eliminar
        $(document).on('click', '.btn-eliminar-movil', function() {
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

                    $.post('../sdo-includes/vehiculos/vehiculos-listado-eliminar.php', { id: id }, function(response) {
                        // Parseamos la respuesta JSON del PHP
                        let res = JSON.parse(response);

                        if(res.status === 'success') {
                            Swal.fire('¡Eliminado!', res.message, 'success');
                            tablaVehiculos.ajax.reload();
                        } else {
                            // Aquí mostrará: "No se puede eliminar: Existen registros en..."
                            Swal.fire('Atención', res.message, 'error');
                        }
                    });
                }
            });
        });

        // 4. Lógica para Visualizar vía POST dinámico
        $(document).on('click', '.btn-ver-movil', function() {
            let idMovil = $(this).attr('id');

            let ruta = 'visualizarMovil.php';
            let ipCliente = '<?php echo obtenerIPCliente(); ?>';

            if(ipCliente === '10.69.250.151') {
                ruta = 'visualizarMovilNuevo.php';
            }

            // Creamos un formulario temporal
            let form = $('<form>', {
                'action': ruta,
                'method': 'POST'
            }).append($('<input>', {
                'type': 'hidden',
                'name': 'idMovil',
                'value': idMovil
            }));

            // Lo añadimos al cuerpo, enviamos y eliminamos
            $('body').append(form);
            form.submit();
        });
    });
</script>
</body>
</html>