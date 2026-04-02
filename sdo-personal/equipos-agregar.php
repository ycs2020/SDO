<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$nomCategoria = '';

if (isset($_POST['idCategoria'])) {
    $idCategoria = trim($_POST['idCategoria']);

    if (!empty($idCategoria) && $idCategoria > 0) {
        $nomCategoria = getNomCategoria($idCategoria);

        $_SESSION['idCategoria'] = $idCategoria;
    } else {
        $nomCategoria = '';
    }
} else if (isset($_SESSION['idCategoria'])) {
    $idCategoria = trim($_SESSION['idCategoria']);

    if (!empty($idCategoria) && $idCategoria > 0) {
        $nomCategoria = getNomCategoria($idCategoria);
    } else {
        $nomCategoria = '';
    }
} else {
    header('Location: index.php?errorCategoria=true');
}

date_default_timezone_set('America/Santiago');

$contarForzarEquipo = contarEquiposForzados();

// $fecha = date('d-m-Y h:i a');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agregar Equipos a Sistema "<?php echo $nomCategoria; ?>"</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="../plugins/summernote/summernote-bs4.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <?php
        include '../sdo-templates/user-sidebar.php';
        ?>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Agregar Equipos a Sistema "<?php echo $nomCategoria; ?>"</h1>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                            </ol>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <!-- Small boxes (Stat box) -->

                                    <div id="accordion">
                                        <div class="card card-primary">
                                            <div class="card-header">
                                                <h4 class="card-title w-75">
                                                    <a class="d-block w-100" data-toggle="collapse" href="#collapseSistema">
                                                        Agregar Equipos a Sistema "<?php echo $nomCategoria; ?>"
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseSistema" class="collapse show" data-parent="#accordion">
                                                <div class="card-body">
                                                    <br>
                                                    <div class="row">

                                                        <div class="col-lg-6 col-12">
                                                            <!-- small box -->
                                                            <div class="small-box bg-primary">
                                                                <div class="inner">
                                                                    <center>
                                                                        <h4>Agregar Equipos</h4>
                                                                        <i class="fa-solid fa-plus fa-3x"></i>
                                                                    </center>
                                                                </div>

                                                                <form id="formSistemaAgregar" action="sistemas-menu.php" method="post">
                                                                    <input type="hidden" name="idCategoria" value="<?php echo $idCategoria; ?>" />
                                                                </form>
                                                                <a class="small-box-footer" href="javascript:;" onclick="document.getElementById('formSistemaAgregar').submit();">Ingresar <i class="fas fa-arrow-circle-right"></i></a>

                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 col-12">
                                                            <!-- small box -->
                                                            <div class="small-box bg-secondary">
                                                                <div class="inner">
                                                                    <center>
                                                                        <h4>Listado Equipos</h4>
                                                                        <i class="fa-solid fa-list fa-3x"></i>
                                                                    </center>
                                                                </div>

                                                                <form id="formSistemaListar" action="sistemas-visualizar.php" method="post">
                                                                    <input type="hidden" name="idCategoria" value="<?php echo $idCategoria; ?>" />
                                                                </form>
                                                                <a class="small-box-footer" href="javascript:;" onclick="document.getElementById('formSistemaListar').submit();">Ingresar <i class="fas fa-arrow-circle-right"></i></a>

                                                            </div>
                                                        </div>

                                                    </div> <!-- /.row -->

                                                </div>
                                            </div>
                                        </div>
                                        <div class="card card-secondary">
                                            <div class="card-header">
                                                <h4 class="card-title w-75">
                                                    <a class="d-block w-100" data-toggle="collapse" href="#collapseAgregarEquipos">
                                                        Agregar Equipos a Sistema "<?php echo $nomCategoria; ?>"
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseAgregarEquipos" class="collapse" data-parent="#accordion">
                                                <div class="card-body">
                                                    <br>
                                                    <div class="row">

                                                        <div class="col-lg-6 col-12">
                                                            <!-- small box -->
                                                            <div class="small-box bg-primary">
                                                                <div class="inner">
                                                                    <center>
                                                                        <h4>Agregar Equipos</h4>
                                                                        <i class="fa-solid fa-plus fa-3x"></i>
                                                                    </center>
                                                                </div>

                                                                <form id="formSistemaAgregar" action="sistemas-menu.php" method="post">
                                                                    <input type="hidden" name="idCategoria" value="<?php echo $idCategoria; ?>" />
                                                                </form>
                                                                <a class="small-box-footer" href="javascript:;" onclick="document.getElementById('formSistemaAgregar').submit();">Ingresar <i class="fas fa-arrow-circle-right"></i></a>

                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 col-12">
                                                            <!-- small box -->
                                                            <div class="small-box bg-secondary">
                                                                <div class="inner">
                                                                    <center>
                                                                        <h4>Listado Equipos</h4>
                                                                        <i class="fa-solid fa-list fa-3x"></i>
                                                                    </center>
                                                                </div>

                                                                <form id="formSistemaListar" action="sistemas-visualizar.php" method="post">
                                                                    <input type="hidden" name="idCategoria" value="<?php echo $idCategoria; ?>" />
                                                                </form>
                                                                <a class="small-box-footer" href="javascript:;" onclick="document.getElementById('formSistemaListar').submit();">Ingresar <i class="fas fa-arrow-circle-right"></i></a>

                                                            </div>
                                                        </div>

                                                    </div> <!-- /.row -->

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

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

    <script src="js/dark-mode.js"></script>
    <!-- jQuery -->
    <script src="../plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- ChartJS -->
    <script src="../plugins/chart.js/Chart.min.js"></script>
    <!-- Sparkline -->
    <script src="../plugins/sparklines/sparkline.js"></script>
    <!-- JQVMap -->
    <script src="../plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="../plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="../plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="../plugins/moment/moment.min.js"></script>
    <script src="../plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Summernote -->
    <script src="../plugins/summernote/summernote-bs4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
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
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "order": [
                    [1, "desc"]
                ],
                "info": false,
                "autoWidth": false,
                "responsive": true,
                "language": {
                    "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                    "zeroRecords": "No se han encontrado Registros.",
                    "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                    "infoEmpty": "No existen Registros.",
                    "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                    "paginate": {
                        "previous": "Anterior",
                        "next": "Siguiente"
                    }
                }
            });
        });
    </script>


</body>

</html>