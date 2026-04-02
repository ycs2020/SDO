<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


date_default_timezone_set('America/Santiago');

// $fecha = date('d-m-Y h:i a');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administraci&oacute;n de Tareas</title>

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

    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>
</head>

<body class="hold-transition sidebar-mini dark-mode">
    <div class="wrapper">

        <!-- Navbar -->
        <?php
        include '../sdo-templates/jefes-sidebar.php';
        ?>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Administraci&oacute;n de Tareas</h1>
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
                                <div class="card-header">

                                    <div class="form-group">
                                        <div style="float: right;" class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" id="customSwitch3" onclick="toggleDark()">
                                            <label class="custom-control-label" for="customSwitch3">Modo Claro</label>
                                        </div>
                                    </div>

                                    <script>
                                        function toggleDark() {

                                            document.body.classList.toggle("dark-mode");
                                            var element2 = document.getElementById("hrefInicio");

                                            const color = element2.style.color;

                                            if(color == 'white'){
                                                element2.style.color = 'blue';
                                            }else{
                                                element2.style.color = 'white';
                                            }

                                        }
                                    </script>
                                    <br>
                                    <br>
                                    <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <br>
                                    <br>
                                </div>
                                <div class="card-body">

                                    <?php

                                    if (strpos($cargoJefe, 'Encargad') !== false) {
                                    ?>


                                        <fieldset class="border p-3">
                                            <legend class="w-auto">Listado de Profesionales</legend>

                                            <div class="row">
                                                <?php

                                                $sqlProfesionales = "SELECT * FROM jefes_tecnicos WHERE idDepartamento = '" . $idDepartamento . "' AND cargoJefe NOT LIKE '%Encargad%' ORDER BY nomJefe ASC";

                                                $resProfesionales = mysqli_query($conexion, $sqlProfesionales);

                                                $numProfesional = 1;

                                                while ($rowProfesionales = $resProfesionales->fetch_assoc()) {

                                                    if ($numProfesional == 1) {
                                                        $color = "bg-primary";
                                                    } else if ($numProfesional == 2) {
                                                        $color = "bg-secondary";
                                                    } else if ($numProfesional == 3) {
                                                        $color = "bg-success";
                                                    } else {
                                                        if ($numProfesional == 4) {
                                                            $numProfesional = 1;
                                                            if ($numProfesional == 1) {
                                                                $color = "bg-primary";
                                                            } else if ($numProfesional == 2) {
                                                                $color = "bg-secondary";
                                                            } else if ($numProfesional == 3) {
                                                                $color = "bg-success";
                                                            } else {
                                                                if ($numProfesional == 4) {
                                                                    $numProfesional = 1;
                                                                }
                                                            }
                                                        }
                                                    }


                                                    $nomJefe = trim($rowProfesionales['nomJefe']);

                                                ?>

                                                    <div class="col-lg-4 col-12">
                                                        <!-- small box -->
                                                        <div class="small-box <?php echo $color; ?>">
                                                            <div class="inner">
                                                                <center>
                                                                    <h4><?php echo $rowProfesionales['nomJefe'] ?></h4>
                                                                    <i class="fa-solid fa-user-circle fa-3x"></i>
                                                                </center>
                                                            </div>

                                                            <form id="formSistemaProfesional<?php echo $rowProfesionales['idJefe']; ?>" action="tareas_realizar.php" method="post">
                                                                <input type="hidden" name="conocerUsuario" id="conocerUsuario" value="Profesional<?php echo $rowProfesionales['idJefe']; ?>">
                                                                <input type="hidden" name="nomEjecutor" value="<?php echo $rowProfesionales['nomJefe']; ?>" />
                                                                <input type="hidden" name="cargoEjecutor" value="<?php echo $rowProfesionales['cargoJefe']; ?>" />
                                                            </form>
                                                            <a class="small-box-footer" href="javascript:;" onclick="document.getElementById('formSistemaProfesional<?php echo $rowProfesionales['idJefe']; ?>').submit();">Ingresar <i class="fas fa-arrow-circle-right"></i></a>
                                                            <!-- <a href="#" class="small-box-footer">Deshabilitada</a> -->

                                                        </div>
                                                    </div>
                                                    <!-- ./col -->
                                                <?php
                                                    $numProfesional = $numProfesional + 1;
                                                }
                                                ?>
                                            </div>
                                        </fieldset>

                                        <br>
                                    <?php
                                    }

                                    ?>


                                    <fieldset class="border p-3">
                                        <legend class="w-auto">Listado de T&eacute;cnicos</legend>

                                        <div class="row">
                                            <?php

                                            $sqlTecnicos = "SELECT * FROM personal WHERE departamentoPersonal = '" . $idDepartamento . "' ORDER BY nomPersonal ASC";

                                            $resTecnicos = mysqli_query($conexion, $sqlTecnicos);

                                            $numCategorias = 1;

                                            while ($rowTecnicos = $resTecnicos->fetch_assoc()) {

                                                if ($numCategorias == 1) {
                                                    $color = "bg-primary";
                                                } else if ($numCategorias == 2) {
                                                    $color = "bg-secondary";
                                                } else if ($numCategorias == 3) {
                                                    $color = "bg-success";
                                                } else {
                                                    if ($numCategorias == 4) {
                                                        $numCategorias = 1;
                                                        if ($numCategorias == 1) {
                                                            $color = "bg-primary";
                                                        } else if ($numCategorias == 2) {
                                                            $color = "bg-secondary";
                                                        } else if ($numCategorias == 3) {
                                                            $color = "bg-success";
                                                        } else {
                                                            if ($numCategorias == 4) {
                                                                $numCategorias = 1;
                                                            }
                                                        }
                                                    }
                                                }


                                                $nomPersonal = trim($rowTecnicos['nomPersonal']);

                                            ?>

                                                <div class="col-lg-4 col-12">
                                                    <!-- small box -->
                                                    <div class="small-box <?php echo $color; ?>">
                                                        <div class="inner">
                                                            <center>
                                                                <h4><?php echo $rowTecnicos['nomPersonal'] ?></h4>
                                                                <i class="fa-solid fa-user-circle fa-3x"></i>
                                                            </center>
                                                        </div>

                                                        <form id="formSistema<?php echo $rowTecnicos['idPersonal']; ?>" action="tareas_realizar.php" method="post">
                                                            <input type="hidden" name="conocerUsuario" id="conocerUsuario" value="Tecnico<?php echo $rowTecnicos['idPersonal']; ?>">
                                                            <input type="hidden" name="nomEjecutor" value="<?php echo $rowTecnicos['nomPersonal']; ?>" />
                                                            <input type="hidden" name="cargoEjecutor" value="<?php echo $rowTecnicos['cargoPersonal']; ?>" />
                                                        </form>
                                                        <a class="small-box-footer" href="javascript:;" onclick="document.getElementById('formSistema<?php echo $rowTecnicos['idPersonal']; ?>').submit();">Ingresar <i class="fas fa-arrow-circle-right"></i></a>
                                                        <!-- <a href="#" class="small-box-footer">Deshabilitada</a> -->

                                                    </div>
                                                </div>
                                                <!-- ./col -->
                                            <?php
                                                $numCategorias = $numCategorias + 1;
                                            }
                                            ?>
                                        </div>
                                    </fieldset>

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


</body>

</html>