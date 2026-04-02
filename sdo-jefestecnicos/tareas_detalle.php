<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');



if (isset($_POST['conocerUsuario'])) {

    $conocerUsuario = trim($_POST['conocerUsuario']);

    if (empty($conocerUsuario)) {
        header('Location: tareas_administrar.php?errorEjecutor=true');
    } else {

        $conocerUsuario = mb_convert_case($conocerUsuario, MB_CASE_LOWER, "UTF-8");

        $_SESSION['conocerUsuario'] = $conocerUsuario;

        $separador1 = "tecnico";
        $separador2 = "profesional";


        if (strpos($conocerUsuario, $separador1) !== false) {

            $tecnico = explode($separador1, $conocerUsuario);

            $datosTecnico = array();
            $datosTecnico = getDatosPersonal($tecnico[1]);

            $nomEjecutor =  $datosTecnico['nomPersonal'];
            $cargoEjecutor = $datosTecnico['cargoPersonal'];
        } else if (strpos($conocerUsuario, $separador2) !== false) {

            $profesional = explode($separador2, $conocerUsuario);

            $datosProfesional = array();
            $datosProfesional = getDatosJefe($profesional[1]);

            $nomEjecutor =  $datosProfesional['nomJefe'];
            $cargoEjecutor = $datosProfesional['cargoJefe'];
        } else {
            header('Location: tareas_administrar.php?errorEjecutor=true');
        }
    }
} else if (isset($_SESSION['conocerUsuario'])) {
    $conocerUsuario = trim($_SESSION['conocerUsuario']);

    if (empty($conocerUsuario)) {
        header('Location: tareas_administrar.php?errorEjecutor=true');
    } else {

        $conocerUsuario = mb_convert_case($conocerUsuario, MB_CASE_LOWER, "UTF-8");

        $_SESSION['conocerUsuario'] = $conocerUsuario;

        $separador1 = "tecnico";
        $separador2 = "profesional";


        if (strpos($conocerUsuario, $separador1) !== false) {

            $tecnico = explode($separador1, $conocerUsuario);

            $datosTecnico = array();
            $datosTecnico = getDatosPersonal($tecnico[1]);

            $nomEjecutor =  $datosTecnico['nomPersonal'];
            $cargoEjecutor = $datosTecnico['cargoPersonal'];
        } else if (strpos($conocerUsuario, $separador2) !== false) {

            $profesional = explode($separador2, $conocerUsuario);

            $datosProfesional = array();
            $datosProfesional = getDatosJefe($profesional[1]);

            $nomEjecutor =  $datosProfesional['nomJefe'];
            $cargoEjecutor = $datosProfesional['cargoJefe'];
        } else {
            header('Location: tareas_administrar.php?errorEjecutor=true');
        }
    }
} else {
    header('Location: tareas_administrar.php?errorEjecutor=true');
}

if (isset($_POST['idTarea'])) {

    $idTarea = trim($_POST['idTarea']);

    if ($idTarea > 0) {

        $dataTarea = getDataTarea($idTarea);

        // echo is_array($dataTarea) ? 'Array' : 'No es un array';

        $idTarea2 = $dataTarea['idTarea'];
        $tituloTarea = $dataTarea['tituloTarea'];
        $detalleTarea = $dataTarea['detalleTarea'];
        $nomSolicitante = $dataTarea['nomSolicitante'];
        $nomEjecutor2 = $dataTarea['nomEjecutor'];
        $estadoTarea = $dataTarea['estadoTarea'];

        //FECHA INICIO
        $inicioTarea = $dataTarea['inicioTarea'];
        $inicioTarea2 = strtotime($inicioTarea);
        $inicioTarea3 = fechaEspañol($inicioTarea2);
        //FIN FECHA INICIO

        //HORA INICIO
        $inicioHora = date("H:i", $inicioTarea2);
        //FIN HORA INICIO



        //FECHA TERMINO
        $terminoTarea = $dataTarea['terminoTarea'];
        $terminoTarea2 = strtotime($terminoTarea);
        $terminoTarea3 = fechaEspañol($terminoTarea2);
        //FIN FECHA TERMINO

        //HORA TERMINO
        $terminoHora = date("H:i", $terminoTarea2);
        //FIN HORA TERMINO



        //FECHA LIMITE
        $limiteFecha = $dataTarea['limiteFecha'];
        $limiteFecha2 = strtotime($limiteFecha);
        $limiteFecha3 = fechaEspañol($limiteFecha2);
        //FIN FECHA LIMITE

        //HORA LIMITE
        $limiteHora = date("H:i", $limiteFecha2);
        //FIN HORA LIMITE



        $prioridadTarea = $dataTarea['prioridadTarea'];
    } else {
        header('Location: tareas_administrar.php?errorIdTarea=true');
    }
} else if (isset($_SESSION['idTarea'])) {

    $idTarea = trim($_SESSION['idTarea']);

    if ($idTarea > 0) {

        $dataTarea = getDataTarea($idTarea);

        echo is_array($dataTarea);
    } else {
        header('Location: tareas_administrar.php?errorIdTarea=true');
    }
} else {
    header('Location: tareas_administrar.php?errorIdTarea=true');
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalle de Tarea</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- AdminLTE css -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

</head>

<body class="hold-transition sidebar-mini dark-mode">
    <!-- Site wrapper -->
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
                            <h4>Detalle de Tarea a Realizar</h4>
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
                                    <a href="tareas_realizar.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <br>
                                    <br>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card h-100">
                                        <div class="card-body p-0">
                                            <div class="mailbox-read-info" style="font-size:85%">
                                                <h5 style="font-size:150%"><b>Titulo de la Tarea: </b><?php echo $tituloTarea; ?></h5>
                                                <h5 style="font-size:150%"><b>Funcionario: </b><?php echo $nomEjecutor; ?></h5>
                                                <h5 style="font-size:150%"><b>Fecha de Creación: </b><?php echo $inicioTarea3 . ' a las ' . $inicioHora; ?></h5>
                                                <h5 style="font-size:150%"><b>Fecha Limite: </b><?php echo $limiteFecha3 . ' a las ' . $limiteHora; ?></h5>
                                            </div>
                                            <div class="mailbox-read-message">
                                                <p style="text-align:justify;"><b>DETALLE DEL TRABAJO A REALIZAR: <br><br></b></p>
                                                <p style="text-align:justify;"><?php echo nl2br($detalleTarea); ?></p>
                                            </div>
                                            <!-- /.mailbox-read-message -->
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
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": false,
                "autoWidth": true,
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
            });
        });
    </script>
</body>

</html>