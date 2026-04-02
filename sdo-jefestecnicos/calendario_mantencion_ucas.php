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
    <title>Calendario Mantenimiento UCAS</title>

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
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <!-- Custom Tabs -->
                        <div class="card">
                            <div class="card-header d-flex p-0" style="background-color: #03cea4;">
                                <h3 class="card-title p-3"><b>Men&uacute; de Actas</b></h3>
                                <ul class="nav nav-pills ml-auto p-2">
                                    <li class="nav-item"><a class="nav-link active " href="#tabMantenimiento" data-toggle="tab"> <b>Calendario de Mantenimiento</b> </a></li>
                                </ul>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="tab-content">

                                    <div class="tab-pane active" id="tabMantenimiento">

                                        <h5 class="text-center font-weight-bold">Calendario de Mantenimiento</h5>

                                        <br>

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="table-responsive">

                                                    <table id="miTabla" class="table table-bordered text-center table-sm">
                                                        <thead class="bg-secondary">
                                                        <tr>
                                                            <th style="vertical-align:middle;">
                                                                <center>FECHA</center>
                                                            </th>
                                                            <th style="vertical-align:middle;">
                                                                <center>TURNO</center>
                                                            </th>
                                                            <th style="vertical-align:middle;">
                                                                <center>FUNCIONARIO</center>
                                                            </th>
                                                            <th style="vertical-align:middle;">
                                                                <center>EQUIPO 1</center>
                                                            </th>
                                                            <th style="vertical-align:middle;">
                                                                <center>EQUIPO 2</center>
                                                            </th>
                                                            <th>
                                                                <center>REALIZADO</center>
                                                            </th>
                                                            <th>
                                                                <center>MODIFICAR</center>
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                        <tfoot class="bg-secondary">
                                                        <tr>
                                                            <th style="vertical-align:middle;">
                                                                <center>FECHA</center>
                                                            </th>
                                                            <th style="vertical-align:middle;">
                                                                <center>TURNO</center>
                                                            </th>
                                                            <th style="vertical-align:middle;">
                                                                <center>FUNCIONARIO</center>
                                                            </th>
                                                            <th style="vertical-align:middle;">
                                                                <center>EQUIPO 1</center>
                                                            </th>
                                                            <th style="vertical-align:middle;">
                                                                <center>EQUIPO 2</center>
                                                            </th>
                                                            <th>
                                                                <center>REALIZADO</center>
                                                            </th>
                                                            <th>
                                                                <center>MODIFICAR</center>
                                                            </th>
                                                        </tr>
                                                        </tfoot>
                                                    </table>
                                                    <script>
                                                        $(document).ready(function () {
                                                            var miTabla = $('#miTabla').DataTable({
                                                                "lengthChange": true,
                                                                "searching": true,
                                                                "autoWidth": false,
                                                                "responsive": true,
                                                                "ordering": false,
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
                                                                    "url": "/sdo-funciones/ajax_calendario_mantenimiento_ucas.php", // Archivo PHP que contiene la consulta SQL
                                                                    "type": "POST",
                                                                    "data": function (d) {
                                                                        // Puedes añadir parámetros adicionales aquí
                                                                    },
                                                                    "dataSrc": ""
                                                                },
                                                                "pageLength": -1, // Muestra todos los registros sin paginación
                                                                "lengthMenu": [[-1], ["Todos"]],
                                                                "columns": [
                                                                    {"data": "fechaFinal"},
                                                                    {"data": "turno"},
                                                                    {"data": "funcionario"},
                                                                    {
                                                                        "data": null,
                                                                        "render": function (data, type, row) {
                                                                            if(row.totalEquipo1 > 0){
                                                                                return row.equipo1 + '<br>(Cantidad: <b>' + row.totalEquipo1 + '</b>)';
                                                                            }else{
                                                                                return '';
                                                                            }

                                                                        }
                                                                    },
                                                                    {
                                                                        "data": null,
                                                                        "render": function (data, type, row) {
                                                                            if(row.totalEquipo2 > 0){
                                                                                return row.equipo2 + '<br>(Cantidad: <b>' + row.totalEquipo2 + '</b>)';
                                                                            }else{
                                                                                return '';
                                                                            }
                                                                        }
                                                                    },
                                                                    {
                                                                        "data": null,
                                                                        "render": function (data, type, row) {
                                                                            if(row.realizado === 1){
                                                                                return 'Realizado';
                                                                            }else{
                                                                                return `
                                                                            <button class="btn btn-primary btn-block btn-realizado" data-id="${row.id}">
                                                                                <i class="fas fa-check"></i>
                                                                            </button>`;
                                                                            }
                                                                        }
                                                                    },
                                                                    {
                                                                        "data": null,
                                                                        "render": function (data, type, row) {
                                                                            if(row.realizado === 1){
                                                                                return 'Deshabilitado';
                                                                            }else{
                                                                                return `
                                                                            <button class="btn btn-warning btn-block btn-edit" data-id="${row.id}" data-turno="${row.turno}" data-funcionario="${row.funcionario}" data-equipo1="${row.equipo1}" data-equipo2="${row.equipo2}">
                                                                                <i class="fas fa-pen"></i>
                                                                            </button>`;
                                                                            }
                                                                        }
                                                                    }
                                                                ],
                                                                "createdRow": function (row, data, dataIndex) {
                                                                    $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                                                                }
                                                            });

                                                            // Manejo del evento click en el botón Editar
                                                            $('#miTabla tbody').on('click', '.btn-edit', function () {
                                                                var id = $(this).data('id');
                                                                var turno = $(this).data('turno');
                                                                var funcionario = $(this).data('funcionario');
                                                                var equipo1 = $(this).data('equipo1');
                                                                var equipo2 = $(this).data('equipo2');

                                                                // Lógica para abrir un modal o realizar acciones de edición
                                                                Swal.fire({
                                                                    title: 'Editar Registro',
                                                                    html: `

                                                                        <input id="editTurno" class="swal2-input" placeholder="Ingrese el turno" value="${turno}">
                                                                        <input id="editFuncionario" class="swal2-input" placeholder="Ingrese el nombre del funcionario" value="${funcionario}">
                                                                        <input id="editEquipo1" class="swal2-input" placeholder="Ingrese el equipo 1" value="${equipo1}">
                                                                        <input id="editEquipo2" class="swal2-input" placeholder="Ingrese el equipo 2" value="${equipo2}">
                                                                    `,
                                                                    confirmButtonText: 'Guardar',
                                                                    showCancelButton: true,
                                                                    preConfirm: () => {
                                                                        return {
                                                                            turno: $('#editTurno').val(),
                                                                            funcionario: $('#editFuncionario').val(),
                                                                            equipo1: $('#editEquipo1').val(),
                                                                            equipo2: $('#editEquipo2').val()
                                                                        };
                                                                    }
                                                                }).then((result) => {
                                                                    if (result.isConfirmed) {
                                                                        // Aquí puedes realizar la solicitud AJAX para actualizar el registro en el servidor
                                                                        $.ajax({
                                                                            url: '/sdo-funciones/ajax_actualizar_mantenimiento_ucas.php',
                                                                            type: 'POST',
                                                                            data: {
                                                                                id: id,
                                                                                turno: result.value.turno,
                                                                                funcionario: result.value.funcionario,
                                                                                equipo1: result.value.equipo1,
                                                                                equipo2: result.value.equipo2
                                                                            },
                                                                            success: function (response) {
                                                                                Swal.fire('Actualizado', 'El registro se ha actualizado correctamente.', 'success');
                                                                                miTabla.ajax.reload(); // Recarga la tabla
                                                                            },
                                                                            error: function () {
                                                                                Swal.fire('Error', 'No se pudo actualizar el registro.', 'error');
                                                                            }
                                                                        });
                                                                    }
                                                                });
                                                            });

                                                            $('#miTabla tbody').on('click', '.btn-realizado', function () {
                                                                var id = $(this).data('id'); // Obtener el ID del registro

                                                                Swal.fire({
                                                                    title: '¿Estás seguro?',
                                                                    text: "¡Marcarás este mantenimiento como realizado!",
                                                                    icon: 'warning',
                                                                    showCancelButton: true,
                                                                    confirmButtonText: 'Sí, marcar',
                                                                    cancelButtonText: 'Cancelar'
                                                                }).then((result) => {
                                                                    if (result.isConfirmed) {
                                                                        $.ajax({
                                                                            url: '/sdo-funciones/ajax_realizado_mantenimiento_ucas.php', // Ruta del archivo PHP
                                                                            type: 'POST',
                                                                            data: { id: id },
                                                                            success: function (response) {
                                                                                var res = JSON.parse(response);
                                                                                if (res.status === "success") {
                                                                                    Swal.fire('Marcado', res.message, 'success');
                                                                                    miTabla.ajax.reload(); // Recargar la tabla
                                                                                } else {
                                                                                    Swal.fire('Error', res.message, 'error');
                                                                                }
                                                                            },
                                                                            error: function () {
                                                                                Swal.fire('Error', 'No se pudo marcar el registro.', 'error');
                                                                            }
                                                                        });
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
</body>

</html>