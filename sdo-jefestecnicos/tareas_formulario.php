<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (isset($_POST['nomEjecutor'], $_POST['nomCreador'])) {

    $nomEjecutor = trim($_POST['nomEjecutor']);
    $nomCreador = trim($_POST['nomCreador']);

    if (empty($nomEjecutor) || empty($nomCreador)) {
        header('Location: tareas_administrar.php?errorEjecutor=true');
    }
} else {
    header('Location: tareas_administrar.php?errorEjecutor=true');
}

date_default_timezone_set('America/Santiago');
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Designar Tareas</title>

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

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
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
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h4>Designar Tarea</h4>
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
                    <form action="../sdo-funciones/tareas_agregarTarea.php" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <!-- left column -->
                            <div class="col-md-12">

                                <!-- general form elements -->
                                <div class="card card-primary">
                                    <!-- /.card-header -->
                                    <div class="card-body">

                                        <div class="form-group">
                                            <div style="float: right;" class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                <input type="checkbox" class="custom-control-input" id="customSwitch3" onclick="toggleDark()">
                                                <label class="custom-control-label" for="customSwitch3">Modo Claro</label>
                                            </div>
                                        </div>

                                        <script>
                                            function toggleDark() {

                                                document.body.classList.toggle("dark-mode");

                                            }
                                        </script>
                                        <br>
                                        <br>
                                        <a href="tareas_realizar.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                        <br>
                                        <br>
                                        <fieldset class="border p-3">
                                            <legend class="w-auto">T&eacute;cnico Asignado:</legend>

                                            <div class="form-row">


                                                <div class="form-group col-md-6">
                                                    <label for="exampleInput">T&eacute;cnico Asignado:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                        </div>

                                                        <input type="text" readonly="readonly" title="* Nombre de Tecnico no puede ser modificado." style="text-transform:capitalize" class="form-control" id="nomEjecutor2" name="nomEjecutor2" placeholder="Nombre Tecnico Asignado" value="<?php echo $nomEjecutor; ?>">



                                                    </div>
                                                </div>

                                            </div>

                                        </fieldset>

                                        <br>

                                        <fieldset class="border p-3">
                                            <legend class="w-auto">Informaci&oacute;n del Trabajo:</legend>



                                            <div class="form-row">
                                                <div class="form-group col-md-6">

                                                    <label for="exampleInput">Prioridad del Trabajo:<code>*</code></label>
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="checkbox" id="chkPrioridad" name="chkPrioridad" value="Prioritario">
                                                        <label id="" for="chkPrioridad" class="custom-control-label">Trabajo Prioritario</label>
                                                        <input type="hidden" name="valorPrioridad" id="valorPrioridad" value="0">
                                                    </div>
                                                    <script type="text/javascript">
                                                        document.getElementById('chkPrioridad').onchange = function() {
                                                            var esPrioritario = document.getElementById('valorPrioridad').value;

                                                            if (esPrioritario == 'SI') {
                                                                document.getElementById('valorPrioridad').value = '0';
                                                            } else if (esPrioritario == 'NO') {
                                                                document.getElementById('valorPrioridad').value = '1';
                                                            } else {
                                                                document.getElementById('valorPrioridad').value = '0';
                                                            }
                                                        };
                                                    </script>
                                                </div>
                                            </div>

                                            <br>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInput">Titulo de Trabajo:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                        </div>
                                                        <input type="text" required class="form-control" id="tituloTarea" name="tituloTarea" placeholder="Titulo del Trabajo a Realizar">
                                                    </div>
                                                </div>

                                                
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInput">Fecha Limite de Trabajo:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                        </div>
                                                        <input type="date" required style="text-transform:capitalize" min="<?= date('Y-m-d'); ?>" class="form-control" id="limiteTarea" name="limiteTarea">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-row">

                                                <div class="form-group col-md-12">
                                                    <label for="exampleInput">Detalle del Trabajo:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                        </div>
                                                        <textarea class="form-control" style="resize:none;" name="detalleTarea" id="detalleTarea" rows="8" placeholder="Detalle del Trabajo a Realizar"></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                        </fieldset>

                                        <br>
                                        <br>


                                        <div class="form-group">

                                            <input type="hidden" name="nomEjecutor" id="nomEjecutor" value="<?php echo $nomEjecutor; ?>">
                                            <input type="hidden" name="nomCreador" id="nomCreador" value="<?php echo $nomUsuario; ?>">
                                            <input type="submit" class="btn btn-block btn-primary" value="Guardar" />
                                        </div>
                                        <div class="form-group">
                                            <a href="tareas_administrar.php" class="btn btn-block btn-warning"> Volver </a>
                                        </div>

                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->

                            </div>
                            <!--/.col  -->

                        </div>
                    </form>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
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
        $('#selectTecnicoTurno').change(function() {
            var id = $(this).find(':selected')[0].value;
            $('#nomTecnicoTurno').val(id);
        })
    </script>
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
</body>

</html>