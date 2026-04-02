
<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

include '../vendor/quickchart/QuickChart.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$nomUsuario = $_SESSION['nomUsuario'];

date_default_timezone_set('America/Santiago');
$ipPermitida = '10.69.250.151'; // Reemplaza con la IP permitida
$ipPermitida2 = '10.69.250.19'; // Reemplaza con la IP permitida
$ipPermitida3 = '10.69.250.205'; // Reemplaza con la IP permitida
//
// Obtén la IP del cliente
$ipCliente = $_SERVER['REMOTE_ADDR'];
//
/// // Verifica si la IP del cliente es la permitida

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Men&uacute; del Sistema</title>

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

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/directiva-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <!--                --><?php
                //                include_once "../sdo-includes/div_weather.php";
                //                ?>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex p-0" style="background-color: #03cea4;">
                                <h3 class="card-title p-3"><b>Accesos Rápidos</b></h3>
                                <ul class="nav nav-pills ml-auto p-2">
                                    <li class="nav-item"><a class="nav-link active" href="#tab_4" data-toggle="tab"> <b>Menu</b> </a></li>
                                </ul>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_4">

                                        <?php
                                        if ($valorDesarrollo == 'Mostrar') {
                                            ?>
                                            <h5><b>Informe EDAN</b></h5>
                                            <br>
                                            <div class="row">
                                                <div class="col-lg-3 col-12">
                                                    <a href="listar_informe_edan.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-list"></i><br>Listar Informe EDAN</a>
                                                </div>
                                            </div>
                                            <hr>
                                            <h5><b>Informe</b></h5>
                                            <br>
                                            <div class="row">
                                                <div class="col-lg-3 col-12">
                                                    <a href="informe_crear.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-list"></i><br>Crear Informe</a>
                                                </div>
                                                <div class="col-lg-3 col-12">
                                                    <a href="informe_listado.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-list"></i><br>Historial Informe</a>
                                                </div>
                                            </div>
                                            <hr>
                                            <h5><b>Memorandum</b></h5>
                                            <br>
                                            <div class="row">
                                                <div class="col-lg-3 col-12">

                                                    <a href="memorandum_crear.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-list"></i><br>Crear Memorandum</a>
                                                </div>
                                                <div class="col-lg-3 col-12">

                                                    <a href="memorandum_listado.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-list"></i><br>Historial Memorandum</a>
                                                </div>
                                            </div>
                                            <hr>
                                            <?php
                                        }
                                        ?>
                                        <h5><b>Orden de Trabajo</b></h5>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-3 col-12">

                                                <a href="trabajos_general_listado.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-list"></i><br>Trabajos</a>
                                            </div>
                                            <div class="col-lg-3 col-12">

                                                <a href="trabajos_general_listado_terminado.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-list"></i><br>Trabajos Terminados</a>
                                            </div>
                                        </div>
                                        <hr>

                                        <h5><b>Listado de Equipos</b></h5>
                                        <div class="row">
                                            <div class="col-lg-3 col-12">
                                                <a href="equipamiento_hbqp.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-list"></i><br>Listado de Equipos</a>

                                            </div>
                                        </div>
                                        <hr>
                                        <h5><b>Informes de los Departamentos</b></h5>
                                        <br>
                                        <div class="row">

                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <a href="reportes-visualizar.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-book"></i><br>Historial Reportes Generales</a>

                                            </div>
                                            &nbsp;
                                            <!--
                                            <div class="col-lg-3 col-12">
                                                <a href="ordenes-visualizar.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-book"></i><br>Historial Recepciones de Trabajo</a>
                                            </div>
                                            -->
                                        </div>

                                        <hr>
                                        <?php

                                        $mostrarOcultar = deshabilitarOpciones();

                                        if ($mostrarOcultar == 'Mostrar') {
                                            ?>
                                            <h5><b>Hojas T&eacute;cnicas</b></h5>
                                            <br>
                                            <div class="row">
                                                <div class="col-lg-3 col-12">
                                                    <a href="ordenes-listado-general.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-book"></i><br>Historial Hojas T&eacute;cnicas</a>
                                                </div>
                                            </div>

                                            <hr>
                                            <?php
                                        }
                                        ?>

                                        <h5><b>Informaci&oacute;n de Funcionarios de SDO</b></h5>
                                        <br>
                                        <div class="row">

                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <a href="jefes-listado.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-users"></i><br>Encargados de Departamento</a>

                                            </div>
                                            &nbsp;
                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <a href="profesionales-listado.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-users"></i><br>Profesionales del Departamento</a>

                                            </div>
                                            &nbsp;
                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <a href="tecnicos-listado.php" class="btn btn-block bg-gradient-warning btn-lg"><i class="nav-icon fas fa-users"></i><br>T&eacute;cnicos del Departamento</a>

                                            </div>
                                        </div>

                                        <hr>

                                        <h5><b>Sistema de Tickets</b></h5>
                                        <br>
                                        <div class="row">

                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <a href="solicitudes_recibidas.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-ticket-alt"></i><br>Tickets Recibidos</a>

                                            </div>
                                            &nbsp;
                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <a href="solicitudes_finalizadas.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-check"></i><br>Tickets Finalizados</a>

                                            </div>
                                            &nbsp;
                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <a href="solicitudes_canceladas.php" class="btn btn-block bg-gradient-warning btn-lg"><i class="nav-icon fas fa-times"></i><br>Tickets Cancelados</a>

                                            </div>
                                        </div>

                                        <hr>

                                        <h5><b>Solicitudes de Compra</b></h5>
                                        <br>
                                        <div class="row">

                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <a href="soliCompra_pm_administracion.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-shopping-bag"></i><br>Administrar SC - Puesta en Marcha</a>

                                            </div>
                                            &nbsp;
                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <a href="soliCompra_sdo_administracion.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-shopping-bag"></i><br>Administrar SC - SDO</a>

                                            </div>
                                        </div>
                                        <hr>
                                        <h5><b>Viajes y Moviles</b></h5>
                                        <br>
                                        <div class="row">

                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <a href="viajes-1.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-car"></i><br>Viajes</a>

                                            </div>
                                            &nbsp;
                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <a href="administrar-moviles.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-ambulance"></i><br>Equipamiento-Moviles</a>

                                            </div>
                                        </div>
                                        <hr>

                                        <h5><b>Consumos Básicos </b></h5>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <a href="consumos_basicos.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-gas-pump"></i>&nbsp;&nbsp;<i class="nav-icon fas fa-tint"></i>&nbsp;&nbsp;<i class="nav-icon fas fa-bolt"></i><br>Consumos Basicos</a>

                                            </div>
                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <a href="combustible_mediciones.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-gas-pump"></i><br>Combustible Mediciones</a>

                                            </div>
                                        </div>

                                        <hr>
                                    </div>

                                </div>
                            </div>
                            <!--
                            <h5><b>Combustible </b></h5>
                            <br>
                            <div class="row">
                                <div class="col-lg-3 col-12">

                                    <a href="combustible_administracion.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-gas-pump"></i><br>Informe de Combustible</a>

                                </div>
                            </div>
                            -->


                            <br>


                            <!-- /.card-body -->
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
    $(function () {
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
            "ordering": false,
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
<script src="js/dark-mode.js"></script>
</body>

</html>