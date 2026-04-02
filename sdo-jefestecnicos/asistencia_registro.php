<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$nomUsuario = $_SESSION['nomUsuario'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modulo de Registro de Turno</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <link rel="stylesheet" href="../sdo-funciones/jquery-3.2.1.min.js">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Theme style -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>

    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">


    <!-- Bootstrap Switch CSS (si lo necesitas, AdminLTE puede ya tenerlo integrado) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css">

    <style>
        /* ============================
           MODO CLARO (por defecto)
        ============================ */

        /* Texto dentro del campo seleccionado */
        .select2-container--bootstrap4 .select2-selection__rendered {
            color: #000000; /* Texto negro */
        }

        /* Opciones en el listado desplegable */
        .select2-container--bootstrap4 .select2-results__option {
            color: #000000; /* Texto negro */
        }

        /* Fondo del dropdown */
        .select2-container--bootstrap4 .select2-dropdown {
            background-color: #ffffff; /* Fondo blanco */
            color: #000000;
        }

        /* Opciones seleccionadas múltiples (chips) */
        .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
            background-color: #007bff !important; /* Azul */
            color: #ffffff !important; /* Texto blanco */
            border: none;
        }

        /* ============================
           MODO OSCURO (con clase .dark-mode en <body>)
        ============================ */

        /* Fondo del campo de selección */
        .dark-mode .select2-container--bootstrap4 .select2-selection {
            background-color: #343a40 !important; /* Fondo oscuro */
            color: #ffffff !important; /* Texto blanco */
            border-color: #495057 !important;
        }

        /* Texto dentro del campo seleccionado */
        .dark-mode .select2-container--bootstrap4 .select2-selection__rendered {
            color: #ffffff !important;
        }

        /* Opciones seleccionadas múltiples (chips) */
        .dark-mode .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
            background-color: #007bff !important;
            color: #ffffff !important;
            border: none;
        }

        /* Dropdown fondo y texto */
        .dark-mode .select2-container--bootstrap4 .select2-dropdown {
            background-color: #343a40 !important;
            color: #ffffff !important;
            border-color: #495057 !important;
        }

        /* Opciones en la lista desplegable */
        .dark-mode .select2-container--bootstrap4 .select2-results__option {
            color: #ffffff !important;
        }

        /* Opción seleccionada en dropdown */
        .dark-mode .select2-container--bootstrap4 .select2-results__option[aria-selected="true"] {
            background-color: #007bff !important;
            color: #ffffff !important;
        }

        /* Opción resaltada con el mouse */
        .dark-mode .select2-container--bootstrap4 .select2-results__option--highlighted {
            background-color: #007bff !important;
            color: #ffffff !important;
        }

        .bg-rojo-personalizado {
            background-color: #b81414;
            color: white;
        }

        .bg-amarillo-personalizado {
            background-color: #fff475;
            color: black;
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

            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-9 col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="text-justify font-weight-bold ">Modulo de Registro de Turno</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="post" action="index.php" class="text-right">
                                    <button type="submit" class="btn btn-danger btn-block">
                                        <i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atrás
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>




                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                <table class="table table-bordered table-hover" id="tablaAsistencia">
                                    <thead class="bg-secondary text-center">
                                    <tr>
                                        <th>Fecha Turno</th>
                                        <th>Tipo Turno</th>
                                        <th>Detalle Turno</th>
                                        <th>Enviar Turno</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot class="bg-secondary text-center">
                                    <tr>
                                        <th>Fecha Turno</th>
                                        <th>Tipo Turno</th>
                                        <th>Detalle Turno</th>
                                        <th>Enviar Turno</th>
                                    </tr>
                                    </tfoot>
                                </table>

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
        <strong>Copyright &copy; 2022 - ∞. Derechos Reservados</strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
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

<!-- Bootstrap Switch JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-switch@3.3.4/dist/js/bootstrap-switch.min.js"></script>
<!-- Select2 -->
<script src="../plugins/select2/js/select2.full.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>

<script src="js/dark-mode.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    toastr.options = {
        "positionClass": "toast-top-right",
        "timeOut": "4000",
        "closeButton": true,
        "progressBar": true
    };
</script>

<script>
    $(document).ready(function() {
        $('#tablaAsistencia').DataTable({
            "lengthChange": false,
            "searching": true,
            "autoWidth": false,
            "responsive": true,
            "ordering": false,
            "processing": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por Página",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando página _PAGE_ de _PAGES_",
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
                "url": "/sdo-includes/asistencia_datos.php", // Archivo PHP que contiene la consulta SQL
                "type": "POST",
                "data": function (d) {
                },
                "dataSrc": "data", // Coincide con la clave 'data' en la respuesta JSON
                "error": function (xhr, error, thrown) {
                    console.error("Error occurred during AJAX request:", error, thrown);
                    console.log("XHR object:", xhr);
                }
            },
            "columns": [
                {"data": "fechaPalabra"},
                {"data": "turnoAsistencia"},
                {
                    "data": null,
                    "render": function (data, type, row) {
                        if (row.id < 1) {
                            return 'Deshabilitado';
                        } else {
                            return '<button class="btn btn-primary btn-block btn-visualizar" data-fecha="' + row.fechaAsistencia + '" data-turno="' + row.turnoAsistencia + '"><i class="fas fa-eye"></i></button>';
                        }
                    }
                },
                {
                    "data": null,
                    "render": function (data, type, row) {
                        if (row.id < 1) {
                            return 'Deshabilitado';
                        } else {
                            return '<button class="btn btn-warning btn-block btn-enviar-correo" data-fecha="' + row.fechaAsistencia + '" data-turno="' + row.turnoAsistencia + '"><i class="fas fa-envelope"></i></button>';
                        }
                    }
                }

            ],
            "createdRow": function (row, data, dataIndex) {
                $('td', row).css({
                    'vertical-align': 'middle',  // Centra verticalmente el texto en las celdas
                    'text-align': 'center',      // Centra horizontalmente el texto en las celdas
                    'font-size': '15px'          // Establece el tamaño de la fuente a 15px
                });
            }
        });

        $('#tablaAsistencia').on('click', '.btn-visualizar', function () {
            var fecha = $(this).data('fecha');
            var turno = $(this).data('turno');

            // Crear un formulario dinámico
            var form = $('<form>', {
                'action': 'asistencia_detalle.php', // Reemplaza 'tu_url_destino' con la URL de destino
                'method': 'POST',
            });

            // Agregar un campo oculto para el ID
            form.append($('<input>', {
                'type': 'hidden',
                'name': 'fechaAsistencia',
                'value': fecha
            }));

            // Agregar un campo oculto para el ID
            form.append($('<input>', {
                'type': 'hidden',
                'name': 'turnoAsistencia',
                'value': turno
            }));

            // Agregar el formulario al documento y enviarlo
            form.appendTo('body').submit();
        });

        $('#tablaAsistencia').on('click', '.btn-enviar-correo', function () {
            const turno = $(this).data('turno');
            const fecha = $(this).data('fecha');

            const fechaObj = new Date(fecha);
            const dia = String(fechaObj.getDate()).padStart(2, '0');
            const mes = String(fechaObj.getMonth() + 1).padStart(2, '0');
            const anio = fechaObj.getFullYear();
            const fecha2 = `${dia}-${mes}-${anio}`;

            Swal.fire({
                title: '¿Deseas enviar el correo?',
                html: `Turno: ${turno} <br><br> Fecha: ${fecha2}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, enviar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Enviando...',
                        html: 'Por favor espera',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Simulación de retardo de PHPMailer
                    setTimeout(() => {
                        $.ajax({
                            url: '/sdo-funciones/asistencia_enviar_turno.php',
                            method: 'POST',
                            data: { turno: turno, fecha: fecha },
                            success: function(response) {
                                Swal.close();
                                if (response.status === 'success') {
                                    Swal.fire('Éxito', response.message, 'success');
                                } else {
                                    Swal.fire('Error', response.message, 'error');
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.close();
                                Swal.fire('Error', 'Error en la solicitud AJAX.', 'error');
                            }
                        });
                    }, 1000); // retrasa intencionalmente para mostrar el spinner
                }
            });
        });


    });
</script>
</body>


</html>