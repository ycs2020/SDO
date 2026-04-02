<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);


date_default_timezone_set('America/Santiago');

$fecha = date('d-m-Y H:i');

$rutaDireccion = "";

if ($rolUsuario2 == "personal") {
    $tipoUsuario = 'personal';
    $rutaDireccion = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $tipoUsuario = 'administrativo';
    $rutaDireccion = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $tipoUsuario = 'bodega pañol';
    $rutaDireccion = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $tipoUsuario = 'jefe tecnico';
    $rutaDireccion = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $tipoUsuario = 'administrador';
    $rutaDireccion = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $tipoUsuario = 'directiva';
    $rutaDireccion = "sdo-directiva";
} else if ($rolUsuario2 == "oficina tecnica") {
    $tipoUsuario = 'oficina tecnica';
    $rutaDireccion = "sdo-oficinaTecnica";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

$datosTanque = array();
$datosTanque = obtenerDatosTanque();

if ($datosTanque !== 'null') {

    $idTanque = $datosTanque['idTanque'];
    $codigoClienteTanque = $datosTanque['codigoClienteTanque'];
    $nomClienteTanque = $datosTanque['nomClienteTanque'];
    $direccionTanque = $datosTanque['direccionTanque'];
    $tipoProductoTanque = $datosTanque['tipoProductoTanque'];
    $numSerieTanque = $datosTanque['numSerieTanque'];
    $capacidadTanqueIN = $datosTanque['capacidadTanqueIN'];
    $capacidadTanqueM3 = $datosTanque['capacidadTanqueM3'];
    $capacidadTanqueGAL = $datosTanque['capacidadTanqueGAL'];
    $fechaActivacion = $datosTanque['fechaActivacion'];
} else {
    header('Location: sistemas-listado.php?errorTanqueIndura=true');
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tanque Indura</title>
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap4.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <!-- summernote -->
    <!-- DataTables -->
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <style>
        .input-output-heading {
            font-size: 18px;
            color: #333; /* Cambiar color según tu preferencia */
            margin-bottom: 10px;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/admin-sidebar.php';

    $messages = [
        'exitoEditarEquipo' => ['Exito!', 'Se guardo los cambios correctamente', 'success'],
        'errorEditarEquipo' => ['Error!', 'Se produjo un error al guardar los cambios', 'error'],
    ];

    foreach ($messages as $param => list($title, $text, $icon)) {
        if (isset($_GET[$param])) {
            ?>
            <script>
                Swal.fire(
                    '<?php echo $title; ?>',
                    '<?php echo $text; ?>',
                    '<?php echo $icon; ?>'
                )
            </script>
            <?php
        }
    }
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
                            <div class="card-body">
                                <form method="post" action="sistemas-listado.php" class="text-right">
                                    <button type="submit" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atrás</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">

                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile w">
                                <div class="text-center">
                                    <img class=" img-fluid" style="width: 200px; " src="../sdo-administrador/img/controlCent-HBQP.png" alt="Control Centralizado">


                                </div>

                                <br>

                                <ul class="list-group list-group-unbordered mb-3">
                                    <li class="list-group-item">
                                        <b>Nombre: </b><?php echo $numSerieTanque; ?>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Tipo: </b><?php echo $tipoProductoTanque; ?>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Capacidad en IN: </b><?php echo $capacidadTanqueIN . ' IN'; ?>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Capacidad en M³: </b><?php echo $capacidadTanqueM3 . ' M³'; ?>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Capacidad en Gal: </b><?php echo $capacidadTanqueGAL . ' Gal'; ?>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Cliente: </b><?php echo $nomClienteTanque; ?>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Codigo Cliente: </b><?php echo $codigoClienteTanque; ?>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Direcci&oacute;n: </b><?php echo $direccionTanque; ?>
                                    </li>
                                </ul>

                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>

                    <div class="col-xl-9 col-lg-8 col-md-6 col-12 text-center">

                        <div class="card card-primary card-outline text-center">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <?php

                                        if (isset($_GET['mensajeVar']) && isset($_SESSION['varRespuesta'])) {
                                            $varRespuesta = $_SESSION['varRespuesta'];

                                            if (!empty($varRespuesta)) {

                                                ?>
                                                <script>
                                                    Swal.fire(
                                                        '<?php echo addslashes($varRespuesta); ?>', // Asegúrate de escapar las comillas y caracteres especiales
                                                        '',
                                                        'info'
                                                    );
                                                </script>
                                                <?php
                                            }

                                            $_SESSION['varRespuesta'] = '';
                                        }
                                        ?>

                                        <form style="margin: 0; text-align: center;" class="" action="../sdo-funciones/indura_subirMediciones.php" method="post" enctype="multipart/form-data">

                                            <button type="button" data-toggle="modal" data-target="#modalSubirExcel" id="subirExcel" class="btn bg-gradient-success"><i class="fa fa-upload"></i>&nbsp;&nbsp;Subir Datos</button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="modalSubirExcel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-success">
                                                            <h5 class="modal-title" id="exampleModalLabel">Subir Mediciones Tanque Indura</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-12 ml-auto">
                                                                    <div style="text-align:left;" class="form-group">
                                                                        <div class="custom-file">
                                                                            <input type="file" class="custom-file-input" id="archivo_excel" name="archivo_excel" accept=".xlsx">
                                                                            <label class="custom-file-label" for="customFile">Seleccionar Archivo...</label>
                                                                        </div>
                                                                        <script>
                                                                            document.getElementById('archivo_excel').addEventListener('change', function () {
                                                                                var fileName = this.value.split('\\').pop(); // Obtener solo el nombre del archivo
                                                                                var label = document.querySelector('.custom-file-label');
                                                                                label.textContent = fileName;
                                                                            });
                                                                        </script>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i>&nbsp;&nbsp;Agregar</button>
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <hr>
                                    </div>
                                    <?php
                                    $obtenerUltimoRegistroTanqueIndura = obtenerUltimoRegistroTanqueIndura();

                                    if ($obtenerUltimoRegistroTanqueIndura !== 'null') {
                                        $dataUltRegTanque = array();
                                        $dataUltRegTanque = $obtenerUltimoRegistroTanqueIndura;

                                        $fechaMedicionReg = $dataUltRegTanque['fechaMedicion'];

                                        $fechaMedicionReg2 = strtotime($fechaMedicionReg);
                                        $fechaMedicionReg2 = "Ultimo Registro: ".date('d/m/Y',$fechaMedicionReg2)." a las ".date('H:i',$fechaMedicionReg2)." hrs.";

                                        $unidadMedicionReg = $dataUltRegTanque['unidadMedicion'];
                                        $unidadMedicionReg2 = mb_convert_case($unidadMedicionReg,MB_CASE_UPPER,'UTF-8');


                                        $lecturaMedicionReg = $dataUltRegTanque['lecturaMedicion'];

                                        if($lecturaMedicionReg > 0) {
                                            if($unidadMedicionReg2 == 'IN'){
                                                $lecturaIN = $lecturaMedicionReg;
                                                $lecturaM3 = ($lecturaMedicionReg * $capacidadTanqueM3) / $capacidadTanqueIN;
                                                $lecturaGAL = ($lecturaMedicionReg * $capacidadTanqueGAL) / $capacidadTanqueIN;

                                                $porcentajeEstanque = (intOdecimal($lecturaIN)*100)/$capacidadTanqueIN;

                                                $porcentajeEstanque = number_format($porcentajeEstanque, 0, ',', '.');

                                                $porcentajeEstanque = $porcentajeEstanque.'%';

                                                $nivelEstanqueLitros = $lecturaGAL * 3.78;

                                                $nivelEstanqueOxGas = $nivelEstanqueLitros * 0.86;

                                                $nivelEstanqueOxGas2 = intOdecimal($nivelEstanqueOxGas).' M³';

                                                $lecturaIN = intOdecimal($lecturaIN).' IN';
                                                $lecturaM3 = intOdecimal($lecturaM3).' M³';
                                                $lecturaGAL = intOdecimal($lecturaGAL).' GAL';


                                            }else if($unidadMedicionReg2 == 'M³'){

                                                $lecturaIN = ($lecturaMedicionReg * $capacidadTanqueIN) / $capacidadTanqueM3;
                                                $lecturaM3 = $lecturaMedicionReg;
                                                $lecturaGAL = ($lecturaMedicionReg * $capacidadTanqueGAL) / $capacidadTanqueM3;

                                                $porcentajeEstanque = (intOdecimal($lecturaIN)*100)/$capacidadTanqueIN;

                                                $porcentajeEstanque = intOdecimal($porcentajeEstanque).'%';

                                                $nivelEstanqueLitros = $lecturaGAL * 3.78;

                                                $nivelEstanqueOxGas = $nivelEstanqueLitros * 0.86;

                                                $nivelEstanqueOxGas2 = intOdecimal($nivelEstanqueOxGas).' M³';

                                                $lecturaIN = intOdecimal($lecturaIN).' IN';
                                                $lecturaM3 = intOdecimal($lecturaM3).' M³';
                                                $lecturaGAL = intOdecimal($lecturaGAL).' GAL';

                                            }else if($unidadMedicionReg2 == 'GAL'){

                                                $lecturaIN = ($lecturaMedicionReg * $capacidadTanqueIN) / $capacidadTanqueGAL;
                                                $lecturaM3 = ($lecturaMedicionReg * $capacidadTanqueM3) / $capacidadTanqueGAL;
                                                $lecturaGAL = $lecturaMedicionReg;

                                                $porcentajeEstanque = (intOdecimal($lecturaIN)*100)/$capacidadTanqueIN;

                                                $porcentajeEstanque = intOdecimal($porcentajeEstanque).'%';

                                                $nivelEstanqueLitros = $lecturaGAL * 3.78;

                                                $nivelEstanqueOxGas = $nivelEstanqueLitros * 0.86;

                                                $nivelEstanqueOxGas2 = intOdecimal($nivelEstanqueOxGas).' M³';

                                                $lecturaIN = intOdecimal($lecturaIN).' IN';
                                                $lecturaM3 = intOdecimal($lecturaM3).' M³';
                                                $lecturaGAL = intOdecimal($lecturaGAL).' GAL';

                                            }else{
                                                $lecturaIN = 'Sin Información';
                                                $lecturaM3 = 'Sin Información';
                                                $lecturaGAL = 'Sin Información';
                                                $porcentajeEstanque = 'Sin Información';
                                                $nivelEstanqueOxGas2 = 'Sin Información';

                                            }
                                        }else{
                                            $lecturaIN = 'Sin Información';
                                            $lecturaM3 = 'Sin Información';
                                            $lecturaGAL = 'Sin Información';
                                            $porcentajeEstanque = 'Sin Información';
                                            $nivelEstanqueOxGas2 = 'Sin Información';
                                        }

                                            $sqlFecha = "SELECT fechaMedicion FROM medicion_tanque_indura WHERE fuenteMedicion LIKE '%Driver after delivery%' OR fuenteMedicion LIKE '%Conductor Después de la Entrega%' ORDER BY medicion_tanque_indura.fechaMedicion DESC limit 1;";
                                            $resFecha = mysqli_query($conexion,$sqlFecha);
                                            while($rowFecha = $resFecha->fetch_assoc()){
                                                $fechaMedicion = $rowFecha['fechaMedicion'];
                                            }
                                            $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $fechaMedicion)));
                                            $inicioHora = date('H:i', strtotime(str_replace('/', '-', $fechaMedicion)));
                                            $fechaMedicion24 = $inicioFecha . ' a las ' . $inicioHora . ' hrs.';
                                            ?>
                                        <div class="col-12">
                                            <b><?php echo $fechaMedicionReg2; ?></b>
                                            <br>
                                            <br>
                                        </div>
                                        <div class="col-12">
                                            <div class="row d-flex justify-content-center">
                                                <div class="col-sm-3 col-6">
                                                    <div class="description-block border-left border-right">
                                                        <h5 class="description-header"><?php echo htmlspecialchars($lecturaIN); ?></h5>
                                                        <span class="description-text">NIVEL EN PULGADA</span>
                                                    </div>
                                                    <!-- /.description-block -->
                                                </div>
                                                <div class="col-sm-3 col-6">
                                                    <div class="description-block border-left border-right">
                                                        <h5 class="description-header"><?php echo htmlspecialchars($lecturaM3); ?></h5>
                                                        <span class="description-text">NIVEL DE OXIGENO LIQUIDO M³</span>
                                                    </div>
                                                    <!-- /.description-block -->
                                                </div>
                                                <div class="col-sm-3 col-6">
                                                    <div class="description-block border-left border-right">
                                                        <h5 class="description-header"><?php echo htmlspecialchars($nivelEstanqueOxGas2); ?></h5>
                                                        <span class="description-text">NIVEL DE OXIGENO GASEOSO M³</span>
                                                    </div>
                                                    <!-- /.description-block -->
                                                </div>
                                                <div class="col-sm-3 col-6">
                                                    <div class="description-block border-left border-right">
                                                        <h5 class="description-header"><?php echo htmlspecialchars($lecturaGAL); ?></h5>
                                                        <span class="description-text">NIVEL EN GALONES</span>
                                                    </div>
                                                    <!-- /.description-block -->
                                                </div>
                                                <div class="col-sm-3 col-6">
                                                    <div class="description-block border-left border-right">
                                                        <h5 class="description-header"><?php echo htmlspecialchars($fechaMedicion24); ?></h5>
                                                        <span class="description-text">FECHA ULTIMA RECARGA</span>
                                                    </div>
                                                    <!-- /.description-block -->
                                                </div>
                                                <div class="col-sm-3 col-6">
                                                    <div class="description-block border-left border-right">
                                                        <h5 class="description-header"><?php echo htmlspecialchars($porcentajeEstanque); ?></h5>
                                                        <span class="description-text">PORCENTAJE DEL ESTANQUE</span>
                                                    </div>
                                                    <!-- /.description-block -->
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <hr>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <div class="col-12">
                                        <input type="hidden" name="idTanqueDatos" id="idTanqueDatos" value="<?php echo $idTanque; ?>">

                                        <table id="tableIndura" class="table table-bordered table-striped text-center">
                                            <thead class="text-center bg-secondary">
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Lectura</th>
                                                <th>Medida</th>
                                                <th>Fuente</th>
                                            </tr>
                                            </thead>
                                            <tfoot class="text-center bg-secondary">
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Lectura</th>
                                                <th>Medida</th>
                                                <th>Fuente</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>


            </div>
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
<!-- Select2 -->
<script src="../plugins/select2/js/select2.full.min.js"></script>
<!-- ChartJS -->
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
<!-- overlayScrollbars -->
<script src="../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Page specific script -->
<script src="js/dark-mode.js"></script>
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
<script>
    $(document).ready(function () {
        var tableIndura = $('#tableIndura').DataTable({
            "lengthChange": false,
            "searching": true,
            "autoWidth": false,
            "responsive": true,
            "ordering": false,
            "processing": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "search": "Buscar:",
                "processing": "Procesando...",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
            initComplete: function() {
                this.api().columns([3]).every(function() {
                    var column = this;
                    var select = $('<select style="width: 100%;"><option value="">Fuente</option></select>')
                        .appendTo($(column.header()).empty())
                        .on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                    column.data().unique().sort().each(function(d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });

            },
            "ajax": {
                "url": "sdo-includes/indura_cargarDatos.php", // Archivo PHP que contiene la consulta SQL
                "type": "POST",
                "data": function (d) {
                    d.idTanque = $('#idTanqueDatos').val(); // Pasa el valor seleccionado del select como parámetro
                    console.log(d);

                },
                "dataSrc": function (json) {
                    console.log("Response from server:", json);
                    return json;
                },
                "error": function (xhr, error, thrown) {
                    console.error("Error occurred during AJAX request:", error, thrown);
                    console.log("XHR object:", xhr);
                }
            },
            "columns": [
                {"data": "fechaMedicion2"},
                {"data": "lecturaMedicion"},
                {"data": "unidadMedicion"},
                {"data": "fuenteMedicion2"}


                // Agrega más columnas según sea necesario
            ],
            "createdRow": function (row, data, dataIndex) {
                $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                $('td', row).css('text-align', 'center'); // Aplica el estilo de centrado vertical a todas las celdas

            }
        });
    });
</script>
</body>

</html>