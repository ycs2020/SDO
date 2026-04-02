<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Men&uacute; - Pabellones</title>

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

    <script>
        function handleRadioChange(radio) {
            var panolDiv = document.getElementById('divPanol');
            console.log(radio.id);

            if (radio.id === 'opcion1') {
                panolDiv.style.display = 'block';
            }else if (radio.id === 'opcion2'){
                panolDiv.style.display = 'none';
            }
        }
    </script>
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
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Men&uacute; - Pabellones</h4>
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
                <div class="row justify-content-center align-items-center">
                    <div class="col-12 ">
                        <div class="card">
                            <div class="card-header">

                                <h3>Administraci&oacute;n de Variables de Pabellones</h3>

                                <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body text-center">

                                <div class="row justify-content-center">
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-body" style="text-align:center;">
                                                <button class="btn btn-block bg-gradient-success btn-md" data-toggle="modal" data-target="#exampleModal"><i class="nav-icon fas fa-file"></i><br><b>Generar Informe</b></button>
                                                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-gradient-info">
                                                                <h5 class="modal-title" id="exampleModalLabel">Generar Informe de Pabellones</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <label>Seleccione una Opción</label>
                                                                <br>
                                                                <input type="radio" id="opcion1" onchange="handleRadioChange(this)" name="opciones" value="opcion1">
                                                                <label for="opcion1">RANGO DE FECHA</label> &nbsp;
                                                                <input type="radio" id="opcion2" onchange="handleRadioChange(this)" name="opciones" value="opcion2">
                                                                <label for="opcion2">TODO</label>
                                                                <div id="divPanol" style="display:none;">
                                                                    <label style="float: left" for="fechaInicio">Desde:</label>
                                                                    <input type="date" class="form-control" id="fechaInicio" name="fechaInicio">
                                                                    <br>
                                                                    <label style="float: left" for="fechaTermino">Hasta:</label>
                                                                    <input type="date" class="form-control" id="fechaTermino" name="fechaTermino">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                <button type="button" class="btn btn-primary">Generar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                        <!-- /.card -->
                                    </div>
                                    <?php
                                    include_once 'sdo-correo-pabellon/pabellon-ultima-temperatura.php';
                                    ?>

                                    <!-- /.col -->
                                </div>

                                <hr>
                                <br>

                                <div class="row justify-content-center">

                                    <div class="col-lg-3 col-md-4">

                                        <!-- Profile Image -->
                                        <div class="card card-primary card-outline">
                                            <div class="card-body box-profile">
                                                <div class="text-center">
                                                    <img class="profile-user-img img-fluid img-circle" src="../dist/img/icon-pabellon/Humedad.png" alt="Humedad">
                                                </div>
                                                <br>

                                                <h3 class="profile-username text-center">Humedad</h3>

                                                <br>

                                                <a href="pabellones_registroHumedad.php" class="btn btn-primary btn-block"><b>Ingresar</b>&nbsp;&nbsp;<i class="fas fa-arrow-right"></i></a>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                        <!-- /.card -->
                                    </div>

                                    <div class="col-lg-3 col-md-4">

                                        <!-- Profile Image -->
                                        <div class="card card-primary card-outline">
                                            <div class="card-body box-profile">
                                                <div class="text-center">
                                                    <img class="profile-user-img img-fluid img-circle" src="../dist/img/icon-pabellon/Temperatura.png" alt="Temperatura">
                                                </div>
                                                <br>

                                                <h3 class="profile-username text-center">Temperatura</h3>

                                                <br>

                                                <a href="pabellones_registroTemperatura.php" class="btn btn-primary btn-block"><b>Ingresar</b>&nbsp;&nbsp;<i class="fas fa-arrow-right"></i></a>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                        <!-- /.card -->
                                    </div>

                                    <div class="col-lg-3 col-md-4">

                                        <!-- Profile Image -->
                                        <div class="card card-primary card-outline">
                                            <div class="card-body box-profile">
                                                <div class="text-center">
                                                    <img class="profile-user-img img-fluid img-circle" src="../dist/img/icon-pabellon/Presion.png" alt="Presion">
                                                </div>
                                                <br>

                                                <h3 class="profile-username text-center">Presion</h3>

                                                <br>

                                                <a href="pabellones_registroPresion.php" class="btn btn-primary btn-block"><b>Ingresar</b>&nbsp;&nbsp;<i class="fas fa-arrow-right"></i></a>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                        <!-- /.card -->
                                    </div>

                            </div>

                                <hr>
                                <br>
                                <div class="col-12 col-md-12" id="rowMenu3" name="rowMenu3">
                                    <?php include_once('../sdo-includes/pabellones_informativo.php')?>
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
<script src="js/dark-mode.js"></script>
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

</html>