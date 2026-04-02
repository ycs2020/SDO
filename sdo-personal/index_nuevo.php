
<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('America/Santiago');
$horaActual = date('H');
$nomUsuario = $_SESSION['nomUsuario'];
$idUsuario = $_SESSION['idUsuario'];

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
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

    <!-- Theme style -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">


    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>


    <style>
        .rotar {
            transform: rotate(180deg);
            transition: transform 0.7s ease;
        }

        .animacion_agrandar:hover {
            animation-name: bounce;
            animation-duration: 1s;
        }

        .cantidad1 {
            background-color: blue;
            color: white;
        }

        .cantidad3 {
            background-color: green;
            color: white;
        }

    </style>
    <link rel="stylesheet" href="../dist/css/cuadro-cantidad.css">
    <style>
        #countdown {
            font-size: 20px;
            text-align: center;
            border: 3px solid #fff; /* Borde blanco */
            padding: 20px;
            border-radius: 10px;
            background-color: #0f3057; /* Color de fondo azul medio */
            box-shadow: 0 0 10px rgba(2, 213, 35, 0.5); /* Sombra con opacidad */
            color: white;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php

    include '../sdo-templates/user-sidebar.php';

    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">

            </div>
            <!-- /.container-fluid -->
        </section>
        <?php

        $fecha_actual = new DateTime();

        $fecha_inicio_navidad = new DateTime('2024-12-25 00:00:00');
        $fecha_fin_navidad = new DateTime('2024-12-25 23:59:59');

        // Compara si la fecha actual está dentro del rango
        if ($fecha_actual >= $fecha_inicio_navidad && $fecha_actual <= $fecha_fin_navidad) {
            if ($userUsuario == 'jsilva') {
                ?>
                <script>
                    Swal.fire(
                        '<img style="width: 100%" src="../dist/img/Navidad/pesebre.png" alt="Pesebre">'
                    )
                </script>
            <?php
            }else{

            ?>
                <script>
                    Swal.fire(
                        '<img style="width: 50%" src="../dist/img/Navidad/Arbol%20de%20Navidad.png" alt="Arbol de Navidad">'
                    )
                </script>
                <?php
            }
        }
        ?>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <!-- /.card-header -->
                            <?php
                            $ipCliente2 = $_SERVER['REMOTE_ADDR'];

                            if ($departamentoPersonal === "Instrumentación") {
                                // Para Instrumentación, según IP
                                if ($ipCliente2 === '10.69.250.1521') {
                                    // Por ejemplo, vista especial
                                    include_once 'sdo-includes/index_control_3.php';
                                } else {
                                    include_once 'sdo-includes/index_control.php';
                                }
                            } else {
                                // Otros departamentos
                                include_once 'sdo-includes/index_otros.php';
                            }

                            ?>
                            <!-- /.card -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success">
                                            <h5 class="modal-title" id="exampleModalLabel">Habilitar Informe EDAN</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="../sdo-personal/sdo-includes/habilitar_edac.php" method="POST">
                                            <input type="hidden" id="nomUsuario" name="nomUsuario" value="<?php echo $nomUsuario ?>">
                                            <div class="modal-body">
                                                <label>Fecha de Inicio</label>
                                                <br>
                                                <input type="datetime-local" required style="width: 100%" class="form-control-border form-control" id="fechaInicio" name="fechaInicio" value="<?php echo date('Y-m-d H:i') ?>">
                                                <br>
                                                <label>Fecha de Termino</label>
                                                <br>
                                                <input type="datetime-local" required style="width: 100%" class="form-control-border form-control" id="fechaTermino" name="fechaTermino">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                <button type="submit" class="btn btn-primary">Habilitar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

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

<script src="../dist/sweetalert-master/src/sweetalert.js"></script>
<link rel="stylesheet" type="text/css" href="../dist/sweetalert-master/src/sweetalert.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    toastr.options = {
        "positionClass": "toast-top-right",
        "timeOut": "4000",
        "closeButton": true,
        "progressBar": true
    };
</script>

<?php

$obtenerSolicitudesSegunNombre = obtenerSolicitudesSegunNombre($nomUsuario);
$obtenerHistorialDocu = obtenerHistorialDocu($nomUsuario);
$obtenerOTSegunNombre = obtenerOTSegunNombre($nomUsuario);

// Aquí tu PHP con los toastr según corresponda
if (isset($_GET['loginExito'])) {
    echo "<script>toastr.success('Ingreso Exitoso a la Plataforma.');</script>";
}
if (isset($_GET['exitoCambioPassword'])) {
    echo "<script>toastr.success('Contrase&ntilde;a Modificada con Exito');</script>";
}
if ($obtenerSolicitudesSegunNombre > 0) {
    echo "<script>toastr.info('Existen nuevas Tickets asignados.');</script>";
}
if ($obtenerOTSegunNombre > 0) {
    echo "<script>toastr.info('Existen nuevas Ordenes de Trabajo.');</script>";
}
if($obtenerHistorialDocu > 0){
    echo "<script>toastr.info('Existen nuevos documentos a ingresar.');</script>";
}
if (isset($_GET['exitoMarcajeAsistencia'])) {
    echo "<script>toastr.success('Marcaje registrado correctamente.');</script>";
}
if (isset($_GET['errorMarcajeAsistencia'])) {
    echo "<script>toastr.error('Error al registrar la asistencia.');</script>";
}
if (isset($_GET['existeMarcajeAsistencia'])) {
    echo "<script>toastr.info('Existen Marcaje de Asistencia.');</script>";
}
?>

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
<script>
    $(document).on('click', '.habilitar', function (e) {
        e.preventDefault();
        Swal.fire({
            icon: 'question',
            title: '¿Habilitar creación de informe EDAN?',
            text: 'Esta acción no se puede revertir',
            showConfirmButton: true,
            confirmButtonText: 'Sí, habilitar',
            confirmButtonColor: '#3085d6',
            showCancelButton: true,
            cancelButtonText: 'No, lo pensaré',
            cancelButtonColor: '#d33',
            buttonsStyling: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Realizar la solicitud AJAX tipo GET
                $.ajax({
                    url: '../sdo-personal/sdo-includes/habilitar_edac.php',
                    type: 'GET',
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Informe EDAN habilitado',
                            text: response, // Mostrar el mensaje de éxito del servidor
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#3085d6'
                        });
                        setTimeout(function () {
                            window.location.href = '../sdo-personal/listar_informe_edan.php';
                        }, 2100);
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error al habilitar el informe EDAN',
                            text: 'Hubo un problema al procesar su solicitud',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#d33'
                        });
                    }
                });
            }
        });
    });
</script>

</body>

</html>