<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$nomUsuario = $_SESSION['nomUsuario'];

if (isset($_POST['fechaAsistencia'], $_POST['turnoAsistencia'])) {

    $fechaAsistencia = $_POST['fechaAsistencia'];
    $turnoAsistencia = $_POST['turnoAsistencia'];


    $date = DateTime::createFromFormat('Y-m-d', $fechaAsistencia);

    if ($date && $date->format('Y-m-d') === $fechaAsistencia) {

        $fechaTime = strtotime($fechaAsistencia);

        $fechaPalabra = fechaEspañol2($fechaTime);

        $tituloPalabra = $turnoAsistencia.' - '.$fechaPalabra;

    }else{
        header('Location: asistencia_registro.php?errorCargaDatos=true');
    }

} else {
    header('Location: asistencia_registro.php?errorCargaDatos=true');
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalle de Asistencia</title>

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
    <link rel="stylesheet" href="../sdo-funciones/jquery-3.2.1.min.js">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Theme style -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>

    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">


    <!-- Bootstrap Switch CSS (si lo necesitas, AdminLTE puede ya tenerlo integrado) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css">


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

            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-9 col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="text-justify font-weight-bold "><?php echo $tituloPalabra; ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="post" action="asistencia_registro.php" class="text-right">
                                    <button type="submit" class="btn btn-danger btn-block">
                                        <i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atrás
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                <table class="table table-bordered table-hover" id="tablaAsistencia">
                                    <thead class="bg-secondary text-center">
                                    <tr>
                                        <th>N°</th>
                                        <th>Personal</th>
                                        <th>Departamento donde realiza Turno</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot class="bg-secondary text-center">
                                    <tr>
                                        <th>N°</th>
                                        <th>Personal</th>
                                        <th>Departamento donde realiza Turno</th>
                                    </tr>
                                    </tfoot>
                                </table>

                            </div>
                        </div>
                    </div>
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

<!-- Bootstrap Switch JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-switch@3.3.4/dist/js/bootstrap-switch.min.js"></script>
<!-- Select2 -->
<script src="../plugins/select2/js/select2.full.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>

<script src="js/dark-mode.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    toastr.options = {
        "positionClass": "toast-top-right",
        "timeOut": "4000",
        "closeButton": true,
        "progressBar": true
    };
</script>

<script>
    $(document).ready(function () {
        $('#tablaAsistencia').DataTable({
            "lengthChange": false,
            "searching": true,
            "autoWidth": false,
            "responsive": true,
            "ordering": false,
            "processing": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por Página",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "search": "Buscar:",
                "processing": "Procesando...",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
            "ajax": {
                "url": "/sdo-includes/asistencia_funcionarios.php", // Archivo PHP que contiene la consulta SQL
                "type": "POST",
                "data": function (d) {
                    d.fechaAsistencia = '<?php echo $fechaAsistencia; ?>';
                    d.turnoAsistencia = '<?php echo $turnoAsistencia; ?>';
                },
                "dataSrc": "data", // Coincide con la clave 'data' en la respuesta JSON
                "error": function (xhr, error, thrown) {
                    console.error("Error occurred during AJAX request:", error, thrown);
                    console.log("XHR object:", xhr);
                }
            },
            "columns": [
                {
                    "data": null,
                    "render": function (data, type, row, meta) {
                        return meta.row + 1; // Devuelve el número de fila (empezando desde 1)
                    }
                },
                {"data": "personalAsistencia"},
                {"data": "deptoAsistencia"},

            ],
            "createdRow": function (row, data, dataIndex) {
                $('td', row).css({
                    'vertical-align': 'middle',  // Centra verticalmente el texto en las celdas
                    'text-align': 'center',      // Centra horizontalmente el texto en las celdas
                    'font-size': '15px'          // Establece el tamaño de la fuente a 15px
                });
                $('td:eq(0)', row).html(dataIndex + 1); // Actualiza el contenido de la primera celda (columna "N°")
            }
        });
    });
</script>

</body>


</html>