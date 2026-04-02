<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('America/Santiago');


if(isset($_POST['idMC'])){
    $idMC = $_POST['idMC'];
    $sql = "SELECT * FROM combustible_medicion WHERE idMC = $idMC";
    $res = mysqli_query($conexion,$sql);
    while($row = $res->fetch_assoc()){
        $datos = [''];
        $datos['fechaMC'] = $row['fechaMC'];
        $datos['turnoMC'] = $row['turnoMC'];
        $datos['terreno1MC'] = $row['terreno1MC'];
        $datos['terreno2MC'] = $row['terreno2MC'];
        $datos['terreno3MC'] = $row['terreno3MC'];
        $datos['terreno4MC'] = $row['terreno4MC'];
        $datos['control1MC'] = $row['control1MC'];
        $datos['control2MC'] = $row['control2MC'];
        $datos['control3MC'] = $row['control3MC'];
        $datos['control4MC'] = $row['control4MC'];
        $fechaMC = $datos['fechaMC'];
        $turnoMC = $datos['turnoMC'];
        $terreno1MC = $datos['terreno1MC'];
        $terreno2MC = $datos['terreno2MC'];
        $terreno3MC = $datos['terreno3MC'];
        $terreno4MC = $datos['terreno4MC'];
        $control1MC = $datos['control1MC'];
        $control2MC = $datos['control2MC'];
        $control3MC = $datos['control3MC'];
        $control4MC = $datos['control4MC'];

    }

}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modificacion de la medicion del combustible</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap4.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dhtmlx-gantt@8.0.1/codebase/dhtmlxgantt.min.css">
    <script src="https://cdn.jsdelivr.net/npm/dhtmlx-gantt@8.0.1/codebase/dhtmlxgantt.min.js"></script>
    <script src="https://cdn.anychart.com/releases/8.11.0/js/anychart-base.min.js" type="text/javascript"></script>
    <script src="https://cdn.anychart.com/releases/8.11.0/js/anychart-core.min.js" type="text/javascript"></script>
    <script src="https://cdn.anychart.com/releases/8.11.0/js/anychart-gantt.min.js" type="text/javascript"></script>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="preloader  flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../dist/img/ssvq1.png" alt="logo">
    <br>
    <div class="spinner-border text-danger" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<!-- Site wrapper -->
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
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Modificacion de la medicion del combustible</h4>

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

                <!-- Timelime example  -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">

                                <a href="combustible_mediciones.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body" style="text-align:center;">

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <?php

                if (isset($_GET['exitoAgregarMC'])) {
                    ?>
                    <script>
                        Swal.fire({
                            title: 'Medici&oacute;n de Combustible Agregada con &Eacute;xito.',
                            confirmButtonText: 'Cerrar',
                            icon: 'success',
                        });
                    </script>
                    <?php
                }

                if (isset($_GET['errorAgregarMC'])) {
                    ?>
                    <script>
                        Swal.fire({
                            title: 'Error al Ingresar Medici&oacute;n de Combustible.',
                            confirmButtonText: 'Cerrar',
                            icon: 'error',
                        });
                    </script>
                    <?php
                }
                ?>
                <div class="row" id="rowMenu1" name="rowMenu1">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>Modificar medicion del combustible</h5>
                                <br>
                                <form action="../sdo-funciones/editar-mediciones.php" method="POST" enctype="multipart/form-data">
                                    <fieldset class="border p-3">
                                        <legend class="w-auto">&nbsp;Informaci&oacute;n del Turno:&nbsp;&nbsp;</legend>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Turno realizado: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                    </div>
                                                    <select id="turnoMC" required class="form-control" name="turnoMC">
                                                        <?php
                                                        if($turnoMC == 'Día'){
                                                            ?>
                                                            <option value="Día" selected="selected">Dia (08:00 - 20:00)</option>
                                                            <option value="Noche">Noche (20:00 - 08:00)</option>
                                                            <?php
                                                        } else{
                                                            ?>
                                                            <option value="Noche" selected="selected">Noche (20:00 - 08:00)</option>
                                                            <option value="Día">Dia (08:00 - 20:00)</option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Fecha: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                    </div>
                                                    <input type="datetime-local" name="fechaMC" id="fechaMC" class="form-control" value="<?php echo $fechaMC?>" required>
                                                </div>
                                            </div>
                                        </div>

                                    </fieldset>
                                    <hr>
                                    <fieldset class="border p-3">
                                        <legend class="w-auto">&nbsp;Informaci&oacute;n del Tanque 1:&nbsp;&nbsp;</legend>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTerreno1">Medici&oacute;n Tanque 1 en Terreno: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-sort-numeric-up"></i></span>
                                                    </div>
                                                    <input class="form-control" required id="terreno1MC" type="number" name="terreno1MC" value="<?php echo $terreno1MC ?>" min="0" />
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputControl1">Medici&oacute;n en Porcentaje de Tanque 1 en Control Centralizado: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-sort-numeric-up"></i></span>
                                                    </div>
                                                    <input class="form-control" required id="control1MC" type="number" name="control1MC" value="<?php echo $control1MC?>" min="0" />
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <hr>
                                    <fieldset class="border p-3">
                                        <legend class="w-auto">&nbsp;Informaci&oacute;n del Tanque 2:&nbsp;&nbsp;</legend>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTerreno1">Medici&oacute;n Tanque 2 en Terreno: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-sort-numeric-up"></i></span>
                                                    </div>
                                                    <input class="form-control" required id="terreno2MC" type="number" value="<?php echo $terreno2MC ?>" name="terreno2MC" min="0" />
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputControl1">Medici&oacute;n en Porcentaje de Tanque 2 en Control Centralizado: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-sort-numeric-up"></i></span>
                                                    </div>
                                                    <input class="form-control" required id="control2MC" type="number" name="control2MC" value="<?php echo $control2MC?>" min="0" />
                                                </div>
                                            </div>
                                        </div>

                                    </fieldset>
                                    <hr>
                                    <fieldset class="border p-3">
                                        <legend class="w-auto">&nbsp;Informaci&oacute;n del Tanque 3:&nbsp;&nbsp;</legend>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTerreno1">Medici&oacute;n Tanque 3 en Terreno: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-sort-numeric-up"></i></span>
                                                    </div>
                                                    <input class="form-control" required id="terreno3MC" type="number" value="<?php echo $terreno3MC ?>" name="terreno3MC" min="0" />
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputControl1">Medici&oacute;n en Porcentaje de Tanque 3 en Control Centralizado: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-sort-numeric-up"></i></span>
                                                    </div>
                                                    <input class="form-control" required id="control3MC" type="number" name="control3MC" value="<?php echo $control3MC?>" min="0" />
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <fieldset class="border p-3">
                                            <legend class="w-auto">&nbsp;Informaci&oacute;n del Tanque 4:&nbsp;&nbsp;</legend>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputTerreno1">Medici&oacute;n Tanque 4 en Terreno: <code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-sort-numeric-up"></i></span>
                                                        </div>
                                                        <input class="form-control" required id="terreno4MC"  value="<?php echo $terreno4MC?>" type="number" name="terreno4MC" min="0" />
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputControl1">Medici&oacute;n en Porcentaje de Tanque 4 en Control Centralizado: <code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-sort-numeric-up"></i></span>
                                                        </div>
                                                        <input class="form-control" required id="control4MC" type="number" value="<?php echo $control4MC?>" name="control4MC" min="0" />
                                                    </div>
                                                </div>
                                            </div>

                                        </fieldset>
                                    </fieldset>
                                    <div class="form-group">
                                        <input type="hidden" name="personalMC" id="personalMC" value="<?php echo $nomUsuario; ?>">
                                        <input type="hidden" name="idMC" id="idMC" value="<?php echo $idMC?>">
                                        <input type="submit" class="btn btn-block btn-success" value="Modificar" />
                                        <a href="combustible_mediciones.php" style="float: right;" class="btn btn-warning btn-block">Volver Atras</a>
                                    </div>
                                </form>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>

            </div>
            <!-- /.timeline -->

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
<script src="js/dark-mode.js"></script>
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
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            "buttons": [{
                extend: 'excelHtml5',
                text: 'Exportar a Excel',
                title: 'ADMINISTRACIÓN DE COMBUSTIBLE DE CALDERAS - SDO',
                className: 'btn btn-success',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
                    format: {
                        body: function(data, row, column, node) {
                            data = $('<p>' + data + '</p>').text();
                            return $.isNumeric(data.replace(',', '.')) ? data.replace('.', ',') : data;
                        }
                    }
                }
            }],
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "search": "Buscar :",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            }

        }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
    });
</script>
<!-- <script>
    function myFunction(name) {
        alert(name)
    }
</script> -->
</body>
</html>