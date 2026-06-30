<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Observaciones Generales</title>

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

    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="../dist/img/controlCent-HBQP-120.png" alt="Logo Control Centralizado">
        <br>
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only"></span>
        </div>
    </div>
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        include '../sdo-templates/user-sidebar.php';
        ?>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h4>Observaciones Generales</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">

                            
                                    <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <br>
                                    <br>

                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-12">
                                            <h5>&nbsp;&nbsp; Colores para Identificar:</h5>
                                            <ul>
                                                <li><i style="color: #28A745; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Forzado - Encendido</li>

                                                <li><i style="color: #E83E8C; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Forzado - Apagado</li>

                                                <li><i style="color: #FFC107; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Forzado - Variables</li>

                                                <li><i style="color: #6C757D; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Forzado</li>

                                                <li><i style="color: #000080; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Modo Automatico</li>

                                                <li><i style="color: #001F3F; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Observaci&oacute;n Normal</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="table-responsive">
                                        <table id="example2" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="vertical-align:middle;">
                                                        <center>Equipo</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Departamento:</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Fecha Observaciones:</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Nombre del Tecnico:<center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Tipo de Observaci&oacute;n:<center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Observaciones:<center>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th style="vertical-align:middle;">
                                                        <center>Equipo</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Departamento:</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Fecha Observaciones:</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Nombre del Tecnico:<center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Tipo de Observaci&oacute;n:<center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Observaciones:<center>
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->


                        </div>
                        <!-- /.col -->
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
    <!-- Page specific script -->
    <script>
        $(document).ready(function() {

            $('#example2').DataTable({

                processing: true,   // 👈 MUESTRA "Procesando..."
                ajax: {
                    url: '../sdo-includes/sistemas_observaciones.php',
                    type: 'POST',
                    dataSrc: 'data',
                    error: function(xhr, error, thrown) {
                        console.log('Status:', xhr.status);
                        console.log('Response:', xhr.responseText);
                    }
                },

                columns: [
                    { data: 'subcategoria' },
                    { data: 'categoria' },
                    { data: 'fecha' },
                    { data: 'personal' },
                    { data: 'forzado' },
                    { data: 'detalle' }
                ],

                columnDefs: [
                    {
                        targets: [2, 5],
                        render: function(data) {
                            return data;
                        }
                    }
                ],

                createdRow: function(row, data) {
                    $(row).addClass(data.clase);
                },

                lengthChange: false,
                searching: true,
                autoWidth: false,
                responsive: true,
                ordering: false,

                processing: true,

                language: {
                    processing: '<span class="spinner-border spinner-border-sm"></span> Procesando...',
                    lengthMenu: "Visualizando _MENU_ Registros por Página",
                    zeroRecords: "No se han encontrado Registros.",
                    info: "Mostrando página _PAGE_ de _PAGES_",
                    infoEmpty: "No existen Registros.",
                    infoFiltered: "(Filtrado desde _MAX_ Registros Totales)",
                    search: "Buscar:",
                    paginate: {
                        previous: "Anterior",
                        next: "Siguiente"
                    }
                }

            });

        });
    </script>    <script src="js/dark-mode.js"></script>
</body>

</html>