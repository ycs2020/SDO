<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Turnos - Control Centralizado</title>

    <link rel="icon" href="../dist/img/controlCent-HBQP-120.png" type="image/icon type">
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

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <!-- FullCalendar CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css">
    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

    <style>
        .fc-event {
            border: none !important;
        }

        .fc-event .fc-event-title {
            white-space: normal !important;
        }
    </style>
    <!-- html2canvas para capturar como imagen -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <!-- jsPDF para generar el PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

</head>


<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">


<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/admin-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-xl-10 col-lg-9 col-md-8 col-12">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <a style="pointer-events: none;" class="btn font-weight-bold">Turnos - Control Centralizado</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                        <div class="card">
                            <div class="card-header bg-danger">
                                <a href="index.php" style="float: right;" class="btn btn-block btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row justify-content-md-center">
                                    <div class="col-xl-3 col-lg-4 col-md-6">
                                        <button class="btn btn-block bg-gradient-success btn-md" data-toggle="modal" data-target="#modalAnadirTurno">
                                            <i class="fas fa-plus"></i>&nbsp;&nbsp;
                                            <b>Añadir Turno</b>
                                        </button>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <!-- Custom Tabs -->
                        <div class="card">
                            <div class="card-header d-flex p-0" style="background-color: #03cea4;">
                                <h3 class="card-title p-3">
                                    <b>Turnos - Control Centralizado</b>
                                </h3>
                                <ul class="nav nav-pills ml-auto p-2">
                                    <li class="nav-item">
                                        <a class="nav-link active " href="#tab2025" data-toggle="tab">
                                            <b>2025</b>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="tab-content">

                                    <div class="tab-pane active" id="tab2025">

                                        <div class="row">

                                            <div class="col-12">

                                                <div id="calendar"></div>

                                                <!-- Modal para editar cualquier turno -->
                                                <div class="modal fade" id="modalEditarTurno" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-md">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-warning">
                                                                <h5 class="modal-title" id="modalEditarLabel">Editar Turno</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form id="formEditarTurno">
                                                                <div class="modal-body">
                                                                    <input type="hidden" id="editIdTurno" name="editIdTurno">
                                                                    <div class="form-group">
                                                                        <label for="editFecha">Fecha de Turno:</label>
                                                                        <input type="date" class="form-control" id="editFecha" name="editFecha" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="editFuncionario1">Funcionario 1:</label>
                                                                        <input type="text" class="form-control" id="editFuncionario1" name="editFuncionario1">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="editFuncionario2">Funcionario 2:</label>
                                                                        <input type="text" class="form-control" id="editFuncionario2" name="editFuncionario2">
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-warning">Guardar Cambios</button>
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Modal Añadir Turno Extra -->
                                                <div class="modal fade" id="modalAnadirTurno" tabindex="-1" aria-labelledby="modalAnadirTurnoLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-md">
                                                        <div class="modal-content">
                                                            <form id="formAnadirTurno">
                                                                <div class="modal-header bg-success">
                                                                    <h5 class="modal-title" id="modalAnadirTurnoLabel">Añadir Turno Extra</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label for="addFechaTurno">Fecha de Turno:</label>
                                                                        <input type="date" class="form-control" id="addFechaTurno" name="addFechaTurno" required>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label for="addFuncionario">Funcionario:</label>
                                                                        <select class="form-control" id="addFuncionario" name="addFuncionario" required style="width: 100%;">
                                                                            <option value="">Seleccione un funcionario</option>
                                                                            <!-- Se rellenará dinámicamente -->
                                                                        </select>
                                                                    </div>

                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-success">Guardar</button>
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                <script>
                                                    $(document).ready(function () {
                                                        $('#formAnadirTurno').submit(function (e) {
                                                            e.preventDefault();

                                                            $.ajax({
                                                                url: '/sdo-includes/turnos_insertar_extra.php',
                                                                method: 'POST',
                                                                data: $(this).serialize(),
                                                                success: function (res) {
                                                                    const json = (typeof res === 'string') ? JSON.parse(res) : res;

                                                                    if (json.status === 'ok') {
                                                                        Swal.fire({
                                                                            icon: 'success',
                                                                            title: 'Guardado',
                                                                            text: 'Turno Extra añadido correctamente.',
                                                                            confirmButtonText: 'Aceptar'
                                                                        }).then(() => {
                                                                            $('#modalAnadirTurno').modal('hide');
                                                                            location.reload(); // o calendar.refetchEvents();
                                                                        });
                                                                    } else {
                                                                        Swal.fire('Error', json.message || 'No se pudo añadir.', 'error');
                                                                    }
                                                                }
                                                            });
                                                        });
                                                    });
                                                </script>


                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function () {
                                                        const calendarEl = document.getElementById('calendar');
                                                        const calendar = new FullCalendar.Calendar(calendarEl, {
                                                            locale: 'es',
                                                            firstDay: 1, // Empezar la semana el lunes
                                                            headerToolbar: {
                                                                left: 'prev,next',
                                                                center: 'title',
                                                                right: 'today,month'
                                                            },
                                                            events: '/sdo-includes/turnos_cargar_datos.php', // Asegúrate de que esta ruta sea correcta
                                                            showNonCurrentDates: false, // ⬅️ esta línea oculta los días de otros meses
                                                            eventContent: function (arg) {
                                                                // Convertir el título de los eventos a mayúsculas
                                                                return {html: arg.event.title};
                                                            },
                                                            eventColor: function (info) {
                                                                // Asignar color según la propiedad 'color' del evento
                                                                return info.event.extendedProps.color || '#32CD32'; // Usar verde si no tiene color
                                                            },
                                                            buttonText: {
                                                                today: 'HOY',
                                                                month: 'MES',
                                                                week: 'SEMANA',
                                                                day: 'DÍA'
                                                            },
                                                            titleFormat: {
                                                                month: 'long',
                                                                year: 'numeric'
                                                            },
                                                            eventsSet: function () {
                                                                const titleEl = document.querySelector('.fc-header-title');
                                                                if (titleEl) {
                                                                    titleEl.innerHTML = titleEl.innerHTML.toUpperCase(); // Convertir el título a mayúsculas
                                                                }
                                                            },
                                                            eventClick: function (info) {
                                                                const evento = info.event;
                                                                const titulo = evento.title || '';
                                                                const partes = titulo.split('<br>');

                                                                // Llenar formulario
                                                                $('#editIdTurno').val(evento.id);
                                                                $('#editFecha').val(evento.startStr);
                                                                $('#editFuncionario1').val(partes[1] || '');
                                                                $('#editFuncionario2').val(partes[2] || '');

                                                                $('#modalEditarTurno').modal('show');
                                                            }

                                                        });

                                                        calendar.render();
                                                    });
                                                </script>
                                                <script>
                                                    $(document).ready(function () {
                                                        $('#formEditarTurno').submit(function (e) {
                                                            e.preventDefault(); // <- muy importante

                                                            $.ajax({
                                                                url: '/sdo-includes/turnos_editar.php',
                                                                method: 'POST',
                                                                data: $(this).serialize(),
                                                                success: function (res) {
                                                                    const json = (typeof res === 'string') ? JSON.parse(res) : res;

                                                                    if (json.status === 'ok') {
                                                                        Swal.fire({
                                                                            icon: 'success',
                                                                            title: 'Éxito',
                                                                            text: 'Turno actualizado correctamente.',
                                                                            confirmButtonText: 'Aceptar'
                                                                        }).then(() => {
                                                                            $('#modalEditarTurno').modal('hide');
                                                                            location.reload();
                                                                        });
                                                                    } else {
                                                                        Swal.fire('Error', json.message || 'No se pudo actualizar.', 'error');
                                                                    }
                                                                }
                                                            });
                                                        });
                                                    });
                                                </script>

                                            </div>
                                        </div>

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
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<script src="js/dark-mode.js"></script>

<!-- Page specific script -->
<script src="../plugins/select2/js/select2.full.min.js"></script>
<script>
    $(document).ready(function () {
        // Inicializar Select2 para autocompletar nombres
        $('#addFuncionario').select2({
            placeholder: 'Buscar funcionario',
            ajax: {
                url: '/sdo-includes/turnos_funcionarios.php',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data.map(function (item) {
                            return {
                                id: item.nombre,
                                text: item.nombre
                            };
                        })
                    };
                }
            },
            width: 'resolve',
            theme: 'bootstrap4'
        });
    });
</script>
</body>

</html>