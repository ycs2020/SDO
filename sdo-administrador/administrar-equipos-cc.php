<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
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
    <title>Sistema BMS - Control Centralizado</title>
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
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>
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
            <div class="content-header">
                <div class="container-fluid">

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
                                <div class="card-header">
                                    <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <br>
                                </div>
                                <div class="card-body">
                                    <div class="card card-primary card-tabs">
                                        <div class="card-header p-0 pt-1">
                                            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Equipamiento Control Centralizado</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            
                                            <hr>
                                            <div class="tab-content" id="custom-tabs-one-tabContent">
                                                <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                                                    <div class="row">
                                                        <?php

                                                        $sqlCategorias = "SELECT * FROM categorias_equipos ORDER BY ordenCategoria ASC";

                                                        $resCategorias = mysqli_query($conexion, $sqlCategorias);

                                                        $numCategorias = 1;

                                                        while ($rowCategorias = $resCategorias->fetch_assoc()) {

                                                            if ($numCategorias == 1) {
                                                                $color = "bg-primary";
                                                            } else if ($numCategorias == 2) {
                                                                $color = "bg-secondary";
                                                            } else if ($numCategorias == 3) {
                                                                $color = "bg-success";
                                                            } else if ($numCategorias == 4) {
                                                                $color = "bg-danger";
                                                            } else if ($numCategorias == 5) {
                                                                $color = "bg-warning";
                                                            } else if ($numCategorias == 6) {
                                                                $color = "bg-info";
                                                            } else {
                                                                if ($numCategorias == 7) {
                                                                    $numCategorias = 1;
                                                                    if ($numCategorias == 1) {
                                                                        $color = "bg-primary";
                                                                    } else if ($numCategorias == 2) {
                                                                        $color = "bg-secondary";
                                                                    } else if ($numCategorias == 3) {
                                                                        $color = "bg-success";
                                                                    } else if ($numCategorias == 4) {
                                                                        $color = "bg-danger";
                                                                    } else if ($numCategorias == 5) {
                                                                        $color = "bg-warning";
                                                                    } else if ($numCategorias == 6) {
                                                                        $color = "bg-info";
                                                                    } else {
                                                                        if ($numCategorias == 7) {
                                                                            $numCategorias = 1;
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            $idCategoria  = trim($rowCategorias['idCategoria']);

                                                            if ($idCategoria == 1) {
                                                                $direccion = 'enfermeria';
                                                            }

                                                            $nomCategoria = trim($rowCategorias['nomCategoria']);

                                                            $nomCategoria = mb_strtolower($nomCategoria);

                                                            if ($nomCategoria == 'bitacora' || $nomCategoria == 'bitácora' || $nomCategoria == 'Bitácora') {
                                                            } else {
                                                        ?>

                                                                <div class="col-lg-2 col-12">
                                                                    <!-- small box -->
                                                                    <div class="small-box <?php echo $color; ?>">
                                                                        <div class="inner">
                                                                            <center>
                                                                                <h4><?php echo $rowCategorias['nomCategoria'] ?></h4>
                                                                                <?php echo $rowCategorias['imagenCategoria'] ?>
                                                                            </center>
                                                                        </div>

                                                                        <form id="formSistema<?php echo $rowCategorias['idCategoria']; ?>" action="sistemas-visualizar.php" method="post">
                                                                            <input type="hidden" name="idCategoria" value="<?php echo $rowCategorias['idCategoria']; ?>" />
                                                                        </form>
                                                                        <a class="small-box-footer" href="administrar-equipos-seleccion.php?carpeta=<?php echo $direccion ?>">Ingresar <i class="fas fa-arrow-circle-right"></i></a>
                                                                        <!-- <a href="#" class="small-box-footer">Deshabilitada</a> -->

                                                                    </div>
                                                                </div>
                                                                <!-- ./col -->
                                                        <?php
                                                                $numCategorias = $numCategorias + 1;
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card -->
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

    <!-- jQuery -->
    <script src="../plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
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
            $('#example2').DataTable({
                "autoWidth": false,
                "responsive": true,
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "info": false,
                "ordering": false,
                "language": {
                    "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                    "zeroRecords": "No se han encontrado Registros.",
                    "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                    "infoEmpty": "No existen Registros.",
                    "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                    "paginate": {
                        "previous": "",
                        "next": ""
                    }
                }
            });
        });
    </script>
    <script src="js/dark-mode.js"></script>


</body>

</html>