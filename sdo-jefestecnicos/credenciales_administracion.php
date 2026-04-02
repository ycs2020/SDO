<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$idDepartamento = $_SESSION['idDepartamento'];
$nomUsuario = $_SESSION['nomUsuario'];
$horaActual = date('H');
$anioActual = date('Y');

$ipCliente = $_SERVER['REMOTE_ADDR'];


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administraci&oacute;n de Credenciales</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">


    <!-- Theme style -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">
    <link rel="stylesheet" href="../dist/css/cuadro-cantidad.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Alinear Select2 dentro de input-group */
        .input-group .select2-container {
            flex: 1 1 auto !important;
            width: 1% !important;
        }

        .input-group .select2-selection {
            height: 38px !important;
            display: flex !important;
            align-items: center !important;
        }

        .select2-container .select2-selection__arrow {
            height: 36px !important;
        }

        /* Ajuste botón verde */
        .input-group .btn {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            padding-left: 12px;
            padding-right: 12px;
        }

        /* Cambiar texto de warning a negro */
        .toast-warning {
            color: #000 !important;
        }

        /* Opcional: también el título si usas titulo en toastr.warning(title,message) */
        .toast-warning strong {
            color: #000 !important;
        }

        .toast-warning .toast-message::before {
            color: #000 !important;
        }

        .toast-warning::before {
            color: #000 !important;
        }

    </style>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">


<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/jefes-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-xl-10 col-lg-9 col-md-8 col-12">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <a style="pointer-events: none;" class="btn font-weight-bold text-black ">Administraci&oacute;n de Funcionarios</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                        <div class="card">
                            <div class="card-header bg-danger ">
                                <a href="index.php" class="btn btn-block btn-danger">
                                    <i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atrás
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

            </div><!-- /.container-fluid -->
        </section>


        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row" id="botonesMenu">
                    <div class="col-md-12">
                        <div class="card">

                            <div class="card-header d-flex p-0 text-center bg-gradient-gray text-white">
                                <ul style="font-size: 10pt;" class="nav nav-pills mr-auto ml-auto p-2 text-center text-white">
                                    <li class="nav-item text-white">
                                        <a class="nav-link active text-white" href="#listadoFuncionarios" data-toggle="tab">
                                            Listado de Funcionarios
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="card-body">
                                <div class="tab-content p-3">

                                    <div class="tab-pane fade show active" id="listadoFuncionarios">

                                        <h5 style="text-align: center; font-weight: bold;">LISTADO DE FUNCIONARIOS</h5>


                                        <!--<div class="text-center mb-3">
                                            <button class="btn btn-success" data-toggle="modal" data-target="#modalAgregarCredencial">
                                                <i class="fas fa-plus"></i> Agregar Credencial
                                            </button>
                                        </div>
                                        -->
                                        <div class="modal fade" id="modalAgregarCredencial" tabindex="-1" role="dialog" aria-labelledby="tituloModalAgregar" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
                                                <div class="modal-content">
                                                    <form id="formAgregarCredenciales" method="POST" autocomplete="off">
                                                        <div class="modal-header bg-success text-white">
                                                            <h5 class="modal-title" id="tituloModalAgregar"><i class="fas fa-id-card-alt"></i> Agregar Nueva Credencial</h5>
                                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div class="form-row justify-content-center text-center">
                                                                <!-- Nombre -->
                                                                <div class="col-md-6 col-12 mb-3">
                                                                    <label for="nombreCredencial" class="font-weight-bold">Nombre del(la) Funcionario(a)&nbsp;<code>*</code></label>
                                                                    <input type="text"
                                                                            class="form-control"
                                                                            id="nombreCredencial"
                                                                            name="nombreCredencial"
                                                                            placeholder="Ej: Jean Pierre o Jean-Pierre"
                                                                            pattern="^[A-Za-zÁÉÍÓÚÑáéíóúñ\s\-]+$"
                                                                            title="Solo letras, espacios y guiones"
                                                                            required>
                                                                </div>

                                                                <!-- Apellido -->
                                                                <div class="col-md-6 col-12 mb-3">
                                                                    <label for="apellidoCredencial" class="font-weight-bold">Apellido del(la) Funcionario(a)&nbsp;<code>*</code></label>
                                                                    <input type="text"
                                                                            class="form-control"
                                                                            id="apellidoCredencial"
                                                                            name="apellidoCredencial"
                                                                            placeholder="Ej: Pérez García o Pérez-García"
                                                                            pattern="^[A-Za-zÁÉÍÓÚÑáéíóúñ\s\-]+$"
                                                                            title="Solo letras, espacios y guiones"
                                                                            required>
                                                                </div>
                                                            </div>

                                                            <hr>

                                                            <div class="form-row justify-content-center text-center">
                                                                <!-- RUT -->
                                                                <div class="col-md-6 col-12 mb-3">
                                                                    <label for="rutCredencial" class="font-weight-bold">RUT del(la) Funcionario(a)&nbsp;<code>*</code></label>
                                                                    <input type="text"
                                                                            class="form-control"
                                                                            id="rutCredencial"
                                                                            name="rutCredencial"
                                                                            placeholder="Ej: 12.345.678-9"
                                                                            pattern="^\d{1,2}\.\d{3}\.\d{3}-[\dkK]$"
                                                                            title="Formato válido: 12.345.678-9"
                                                                            required>
                                                                </div>

                                                                <!-- Teléfono -->
                                                                <div class="col-md-6 col-12 mb-3">
                                                                    <label for="telefonoCredencial" class="font-weight-bold">Teléfono del(la) Funcionario(a)&nbsp;<code>*</code></label>
                                                                    <input type="text"
                                                                            class="form-control"
                                                                            id="telefonoCredencial"
                                                                            name="telefonoCredencial"
                                                                            placeholder="Ej: +56987654321"
                                                                            title="Ej: +56987654321"
                                                                            required>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-success">
                                                                <i class="fas fa-save"></i> Guardar Credencial
                                                            </button>
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                                <i class="fas fa-times"></i> Cancelar
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>


                                        <hr>

                                        <div class="table-responsive">

                                            <table id="tablaListadoCredencial" class="table table-bordered table-hover table-sm">

                                                <thead class="bg-gradient-success">
                                                <tr style="text-transform: uppercase;">
                                                    <th style="vertical-align: middle; text-align: center;">
                                                        N°
                                                    </th>
                                                    <th style="vertical-align: middle; text-align: center;">
                                                        Nombre
                                                    </th>
                                                    <th style="vertical-align: middle; text-align: center;">
                                                        Apellido
                                                    </th>
                                                    <th style="vertical-align: middle; text-align: center;">
                                                        RUT
                                                    </th>
                                                    <th style="vertical-align: middle; text-align: center;">
                                                        Telefono
                                                    </th>
                                                    <th style="vertical-align: middle; text-align: center;">
                                                        Detalle
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot class="bg-gradient-success">
                                                <tr style="text-transform: uppercase;">
                                                    <th style="vertical-align: middle; text-align: center;">
                                                        N°
                                                    </th>
                                                    <th style="vertical-align: middle; text-align: center;">
                                                        Nombre
                                                    </th>
                                                    <th style="vertical-align: middle; text-align: center;">
                                                        Apellido
                                                    </th>
                                                    <th style="vertical-align: middle; text-align: center;">
                                                        RUT
                                                    </th>
                                                    <th style="vertical-align: middle; text-align: center;">
                                                        Telefono
                                                    </th>
                                                    <th style="vertical-align: middle; text-align: center;">
                                                        Detalle
                                                    </th>
                                                </tr>
                                                </tfoot>

                                            </table>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <strong>Copyright &copy; 2022. Derechos Reservados.
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->

<!-- SCRIPT DARK MODE -->
<script src="js/dark-mode.js"></script>

<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../plugins/jszip/jszip.min.js"></script>
<script src="../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!-- Select2 -->
<script src="../plugins/select2/js/select2.full.min.js"></script>

<script>
    toastr.options = {
        "positionClass": "toast-top-right",
        "timeOut": "5000",
        "closeButton": true,
        "progressBar": true
    };
</script>

<script>

    $(document).ready(function () {
        var tablaListadoCredencial = $('#tablaListadoCredencial').DataTable({
            "lengthChange": false,
            "searching": true,
            "autoWidth": false,
            "responsive": true,
            "ordering": false,
            "processing": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "search": "Buscar:",
                "processing": "Procesando...",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
            "ajax": {
                "url": "/sdo-includes/credenciales_cargarDatos.php", // Archivo PHP que contiene la consulta SQL
                "type": "POST",
                "data": function (d) {
                    // d.idDepartamento = $('#idDeptoHT').val(); // Pasa el valor seleccionado del select como parámetro

                },
                "dataSrc": "data",
                "error": function (xhr, error, thrown) {
                    console.error("Error occurred during AJAX request:", error, thrown);
                    console.log("XHR object:", xhr);
                }
            },
            "columns": [
                {
                    "data": null,
                    "render": function (data, type, row, meta) {
                        return meta.row + 1; // Devuelve el número de fila (empezando desde 1)
                    }
                },
                {"data": "nombreCredencial"},
                {"data": "apellidoCredencial"},
                {"data": "rutCredencial"},
                {"data": "telefonoCredencial"},
                {"data": "buttonVisualizar"},


                // Agrega más columnas según sea necesario
            ],
            "createdRow": function (row, data, dataIndex) {
                $('td', row).css('vertical-align', 'middle');
                $('td', row).css('text-align', 'center');
                $('td', row).css('text-transform', 'uppercase');
                $('td:eq(0)', row).html(dataIndex + 1); // Actualiza el contenido de la primera celda (columna "N°")
                // $('td', row).css('font-size', '10pt');

            }
        });
    });
</script>
<script>
    function limpiarFormatoRut(rut) {
        return rut.replace(/\./g, '').replace('-', '').toUpperCase();
    }

    function calcularDV(rut) {
        let suma = 0,
            multiplicador = 2;
        for (let i = rut.length - 1; i >= 0; i--) {
            suma += parseInt(rut[i]) * multiplicador;
            multiplicador = multiplicador === 7 ? 2 : multiplicador + 1;
        }
        const resto = 11 - (suma % 11);
        if (resto === 11) return '0';
        if (resto === 10) return 'K';
        return resto.toString();
    }

    function validarRutCompleto(rutCompleto) {
        rutCompleto = rutCompleto.trim().toUpperCase();
        if (!/^\d{1,2}\.\d{3}\.\d{3}-[\dK]$/.test(rutCompleto)) return false;

        const rutSinFormato = limpiarFormatoRut(rutCompleto);
        const rut = rutSinFormato.slice(0, -1);
        const dv = rutSinFormato.slice(-1);

        return calcularDV(rut) === dv;
    }

    // Capitaliza cada palabra y parte con guiones: ej. "maría-josé pÉrez-gómez" -> "María-José Pérez-Gómez"
    function capitalizar(valor) {
        return valor
            .toLowerCase()
            .split(' ')
            .map(palabra =>
                palabra
                    .split('-')
                    .map(parte => parte.charAt(0).toUpperCase() + parte.slice(1))
                    .join('-')
            )
            .join(' ');
    }

    // Capitalización en tiempo real
    document.getElementById('nombreCredencial').addEventListener('input', e => {
        e.target.value = capitalizar(e.target.value);
    });

    document.getElementById('apellidoCredencial').addEventListener('input', e => {
        e.target.value = capitalizar(e.target.value);
    });

    // Validación con Toastr
    document.getElementById('formAgregarCredenciales').addEventListener('submit', function (e) {
        const rutInput = document.getElementById('rutCredencial');
        const nombreInput = document.getElementById('nombreCredencial');
        const apellidoInput = document.getElementById('apellidoCredencial');

        const soloLetrasEspaciosGuiones = /^[A-Za-zÁÉÍÓÚÑáéíóúñ\s\-]+$/;

        // Validar RUT
        if (!validarRutCompleto(rutInput.value)) {
            e.preventDefault();
            toastr.error('RUT inválido. Verifica número y dígito verificador.', 'Error en RUT');
            rutInput.focus();
            return;
        }

        // Validar nombre
        if (!soloLetrasEspaciosGuiones.test(nombreInput.value.trim())) {
            e.preventDefault();
            toastr.error('El nombre solo puede incluir letras, espacios y guiones.', 'Error en Nombre');
            nombreInput.focus();
            return;
        }

        // Validar apellido
        if (!soloLetrasEspaciosGuiones.test(apellidoInput.value.trim())) {
            e.preventDefault();
            toastr.error('El apellido solo puede incluir letras, espacios y guiones.', 'Error en Apellido');
            apellidoInput.focus();
            return;
        }
    });
</script>


<!-- FIN MODAL UNIDAD Y JEFATURA -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Forzar visualmente mayúsculas al escribir
        document.querySelectorAll("#formAgregarCredenciales input[type='text'], textarea").forEach(input => {
            input.style.textTransform = "uppercase";

            // Convertir automáticamente mientras se escribe
            input.addEventListener("input", () => {
                input.value = input.value.toUpperCase();
            });

            // Convertir al cargar valores precargados
            input.value = input.value.toUpperCase();
        });

        // Opcional: Si quieres que también los SELECTs aparezcan visualmente en mayúsculas
        document.querySelectorAll("#formAgregarCredenciales select").forEach(select => {
            select.style.textTransform = "uppercase";
        });
    });
</script>
<script>
    $(document).ready(function () {
        // Enviar formulario por AJAX
        $('#formAgregarCredenciales').on('submit', function (e) {
            e.preventDefault();

            const form = $(this)[0];
            const formData = new FormData(form);

            $.ajax({
                url: '/sdo-funciones/credenciales_rrhh_agregar.php',
                type: 'POST',
                data: formData,
                dataType: 'json', // IMPORTANTE para que jQuery ya haga JSON.parse()
                processData: false,
                contentType: false,
                success: function (res) {
                    Swal.close(); // Cerrar el loading

                    if (res.status === 'ok') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Credencial guardada',
                            text: res.mensaje || 'La credencial fue registrada correctamente',
                            timer: 1500,
                            showConfirmButton: false,
                            willClose: () => {
                                if (res.idGenerado) {
                                    // Crear formulario dinámico para redireccionar por POST
                                    const form = document.createElement('form');
                                    form.method = 'POST';
                                    form.action = 'credenciales_funcionario.php';
                                    form.target = '_blank';

                                    const input = document.createElement('input');
                                    input.type = 'hidden';
                                    input.name = 'idCredencialVer';
                                    input.value = res.idGenerado;
                                    form.appendChild(input);

                                    document.body.appendChild(form);
                                    form.submit();
                                }
                            }
                        });

                        // Cambiar de pestaña
                        $('a[href="#listadoFuncionarios"]').tab('show');

                        // Recargar DataTable después de un pequeño delay
                        setTimeout(() => {
                            $('#tablaListadoCredencial').DataTable().ajax.reload(null, false);
                        }, 500);

                        // Limpiar formulario
                        form.reset();

                        // Deseleccionar selects manualmente
                        $('#unidadTarjeta').val(null).trigger('change');
                        $('#jefaturaCredencial').val(null).trigger('change');
                        $('#cargoCredencial').val('Seleccione');
                        $('#calidadCredencial').val('Seleccione');
                        $('#tipoCredencial').val('Seleccione');

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: res.mensaje || 'No se pudo guardar la credencial'
                        });
                    }
                },
                error: function (xhr, status, error) {
                    Swal.close(); // <- CERRAR el loading
                    Swal.fire({
                        icon: 'error',
                        title: 'Error inesperado',
                        text: 'No se pudo procesar la solicitud'
                    });
                    console.error('Error AJAX:', xhr.responseText);
                }
            });

        });


        $('#tablaListadoCredencial').on('click', '.btn-vercred', function () {
            var idcred = $(this).data('idcred');

            // Crear un formulario dinámico
            var form = $('<form>', {
                'action': 'credenciales_funcionario.php', // Reemplaza 'tu_url_destino' con la URL de destino
                'method': 'POST',
                'target': '_blank',
            });

            // Agregar un campo oculto para el ID
            form.append($('<input>', {
                'type': 'hidden',
                'name': 'idCredencialVer',
                'value': idcred
            }));

            // Agregar el formulario al documento y enviarlo
            form.appendTo('body').submit();
        });
    });
</script>


</body>

</html>


