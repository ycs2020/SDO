<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema de Actas</title>

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

</head>

<body class="hold-transition sidebar-mini dark-mode">

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
        include '../sdo-templates/jefes-sidebar.php';
        ?>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h4>Sistema de Actas</h4>
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

                                    <form method="post" action="actas-formulario.php">

                                        <button type="submit" style="float: right;" id="inputMozi" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Actas</button>

                                    </form>

                                    <br>
                                    <br>

                                    <?php
                                    if (isset($_GET['exitoCrearActa'])) {
                                        echo '<div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <h5><i class="fa fa-check"></i>&nbsp;Acta Creada con Exito.</h5></div>';
                                    }

                                    if (isset($_GET['exitoModActa'])) {
                                        echo '<div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <h5><i class="fa fa-check"></i>&nbsp;Acta Modificada con Exito.</h5></div>';
                                    }

                                    if (isset($_GET['errorCrearActa'])) {
                                        echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Crear Acta. Reintente.</h5></div>';
                                    }

                                    if (isset($_GET['errorCrearFolio'])) {
                                        echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Crear Folio de Acta. Reintente.</h5></div>';
                                    }

                                    if (isset($_GET['errorVisualizarActa'])) {
                                        echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Visualizar Acta. Reintente.</h5></div>';
                                    }

                                    if (isset($_GET['vehiculo1Vacio'])) {
                                        echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Datos de Vehiculo N° 1 estan Vacios. Reintente.</h5></div>';
                                    }

                                    if (isset($_GET['soloVehiculo1'])) {
                                        echo '<div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <h5><i class="fa fa-check"></i>&nbsp;Se ha inscrito Vehiculo N° 1.</h5></div>';
                                    }

                                    if (isset($_GET['sinEstacionamiento'])) {
                                        echo '<div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <h5><i class="fa fa-check"></i>&nbsp;Persona No Requiere Estacionamiento.</h5></div>';
                                    }

                                    if (isset($_GET['exitoCrearActaVehiculo1y2'])) {
                                        echo '<div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <h5><i class="fa fa-check"></i>&nbsp;Se ha inscrito Vehiculo N° 1 y Vehiculo N° 2.</h5></div>';
                                    }

                                    if (isset($_GET['errorVehiculo1'])) {
                                        echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Inscribir Vehiculo N° 1. Reintente.</h5></div>';
                                    }

                                    if (isset($_GET['errorVehiculo2'])) {
                                        echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Inscribir Vehiculo N° 2. Reintente.</h5></div>';
                                    }

                                    if (isset($_GET['existePatente1'])) {
                                        echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Patente de Vehiculo N° 1 ya se encuentra registrada.</h5></div>';
                                    }

                                    if (isset($_GET['existePatente2'])) {
                                        echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Patente de Vehiculo N° 2 ya se encuentra registrada.</h5></div>';
                                    }
                                    ?>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">

                                    <div class="table-responsive">
                                        <br />
                                        <div id="alert_message"></div>
                                        <table id="example" class="table table-bordered table-striped">
                                            <thead class="bg-secondary">
                                                <tr>
                                                    <th style="vertical-align:middle;">
                                                        <center>Folio Acta<center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Usuario de la Tarjeta<center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>RUT de la Tarjeta<center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>N° Tarjeta<center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Fecha de Acta<center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Unidad de la Tarjeta<center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Nombre de Solicitante<center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Nombre de Tecnico<center>
                                                    </th>
                                                </tr>
                                            </thead>
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

</body>

<script>
    $(document).ready(function() {
        $('#example').DataTable({
            ajax: 'actas-datos.php',
        });
    });
</script>

</html>