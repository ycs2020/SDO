<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$idDepartamento = $_SESSION['idDepartamento'];
$nomUsuario = $_SESSION['nomUsuario'];
$horaActual = date('H');
$anioActual = date('Y');


$ipPermitida = '10.69.250.151';

$ipCliente = $_SERVER['REMOTE_ADDR'];


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Men&uacute; del Sistema</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

    <!-- Theme style -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">
    <link rel="stylesheet" href="../dist/css/cuadro-cantidad.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        .cantidad4 {
            background-color: green;
            color: white;
        }

        .cantidad5 {
            background-color: green;
            color: white;
        }

    </style>
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
                <div class="row mb-2 animate__animated animate__fadeInDown">
                    <div class="col-12">
                        <div class="card card-widget widget-user">
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div class="widget-user-header bg-primary">
                                <h3 class="widget-user-username"><?php echo $nomUsuario; ?></h3>
                                <h3 class="widget-user-username"><?php echo $cargoUsuario; ?></h3>
                            </div>
                            <div class="widget-user-image">
                                <img class="img-circle elevation-2" src="../dist/img/simboloAdmin.jpg" alt="User Avatar"/>
                            </div>
                            <div class="card-footer">
                                <div class="row justify-content-center">
                                    <?php
                                    function obtenerConteo($conexion, $sql)
                                    {
                                        $res = mysqli_query($conexion, $sql);
                                        if ($res && $row = mysqli_fetch_assoc($res)) {
                                            return (int)$row['TOTAL'];
                                        }
                                        return 0;
                                    }

                                    // 1️⃣ Contador de Hojas Técnicas
                                    $sql = "SELECT COUNT(*) AS TOTAL 
        FROM ordenes_trabajos_general 
        WHERE YEAR(fechaOrden) = $anioActual AND idDepartamento = $idDepartamento";
                                    $totalHojasTecnicas = obtenerConteo($conexion, $sql);
                                    ?>
                                    <div class="col-sm-3 mb-3">
                                        <a href="ordenes-listado-general.php" class="btn btn-block bg-gradient-info">
                                            <div class="description-block">
                                                <h5 class="description-header"><?= $totalHojasTecnicas ?></h5>
                                                <span class="description-text">Hojas Técnicas</span>
                                            </div>
                                        </a>
                                    </div>
                                    <?php
                                    // 2️⃣ Contador de Solicitudes de Compra
                                    $nomDepartamentoEsc = mysqli_real_escape_string($conexion, $nomDepartamento);
                                    $sql = "SELECT COUNT(*) AS TOTAL 
        FROM solicitudes_compra_sdo 
        WHERE YEAR(fechaSoliCompra) = $anioActual 
        AND unidadSoliCompra = '$nomDepartamentoEsc'";
                                    $totalSolicitudesCompra = obtenerConteo($conexion, $sql);
                                    ?>
                                    <div class="col-sm-3 mb-3">
                                        <a href="soliCompra_sdo_administracion.php" class="btn btn-block bg-gradient-yellow">
                                            <div class="description-block">
                                                <h5 class="description-header"><?= $totalSolicitudesCompra ?></h5>
                                                <span class="description-text">Solicitudes de Compra</span>
                                            </div>
                                        </a>
                                    </div>
                                    <?php
                                    // 3️⃣ Contador de Equipamiento
                                    $sql = "SELECT COUNT(*) AS TOTAL 
        FROM equipamiento_hbqp 
        WHERE idCategoria IN (
            SELECT idCategoria FROM categorias_equipos WHERE idDepartamento = $idDepartamento
        )";
                                    $totalEquipos = obtenerConteo($conexion, $sql);
                                    ?>
                                    <div class="col-sm-3 mb-3">
                                        <a href="equipamiento_hbqp.php" class="btn btn-block bg-gradient-purple">
                                            <div class="description-block">
                                                <h5 class="description-header"><?= $totalEquipos ?></h5>
                                                <span class="description-text">Equipamiento</span>
                                            </div>
                                        </a>
                                    </div>
                                    <?php
                                    // 4️⃣ Contador de Avisos Relevantes
                                    $nomUsuarioEsc = mysqli_real_escape_string($conexion, $nomUsuario);
                                    $sql = "SELECT COUNT(*) AS TOTAL 
        FROM avisos_destinatarios 
        WHERE nomDestinatario LIKE '$nomUsuarioEsc'";
                                    $totalAvisos = obtenerConteo($conexion, $sql);
                                    ?>
                                    <div class="col-sm-3 mb-3">
                                        <a href="avisos_relevantes_administracion.php" class="btn btn-block bg-gradient-danger">
                                            <div class="description-block">
                                                <h5 class="description-header"><?= $totalAvisos ?></h5>
                                                <span class="description-text">Avisos Relevantes</span>
                                            </div>
                                        </a>
                                    </div>
                                    <?php if ($nomDepartamento === 'Instrumentación'):
                                        $sql = "SELECT COUNT(*) AS TOTAL FROM reportes_generales";
                                        $totalReportes = obtenerConteo($conexion, $sql);
                                        ?>
                                        <div class="col-sm-3 mb-3">
                                            <a href="reportes-listado.php" class="btn btn-block bg-gradient-success">
                                                <div class="description-block">
                                                    <h5 class="description-header"><?= $totalReportes ?></h5>
                                                    <span class="description-text">Reportes Turno</span>
                                                </div>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <!-- /.row -->
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <div class="row mb-2">
                    <div class="col-sm-12">

                        <div class="row" style="font-size: 15px;">
                            <div class="col-12">
                                <!-- Custom Tabs -->
                                <div class="card">
                                    <div class="card-header d-flex p-0" style="background-color: #03cea4;">
                                        <h3 class="card-title p-3">
                                            <b>Accesos Rápidos</b>
                                        </h3>
                                        <ul class="nav nav-pills ml-auto p-2">
                                            <li class="nav-item">
                                                <a class="nav-link active " href="#tab_4" data-toggle="tab">
                                                    <b>Menu</b>
                                                </a>
                                            </li>

                                        </ul>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="tab_4">
                                                <?php

                                                $datos = [
                                                    ["id" => 1,"nombre" => "Documentos - Agregar","ruta" => "documentos_agregar.php","icono" => '<i class="fas fa-file-alt"></i>',"idDepartamento" => 1]
                                                ];

                                                ?>
                                            </div>

                                            <!-- /.tab-pane -->
                                        </div>
                                        <!-- /.tab-content -->
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- ./card -->
                            </div>
                            <!-- /.col -->
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
        <strong>Copyright &copy; 2022. Derechos Reservados.
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->

<!-- SCRIPT DARK MODE -->
<script src="js/dark-mode.js"></script>

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    toastr.options = {
        "positionClass": "toast-top-right",
        "timeOut": "7000",
        "closeButton": true,
        "progressBar": true
    };
</script>

<?php
// Aquí tu PHP con los toastr según corresponda
//if ($obtenerOTSegunNombre > 0) {
//    echo "<script>toastr.info('Existen nuevas Ordenes de Trabajo.');</script>";
//}
if (isset($_GET['exitoMarcajeAsistencia'])) {
    echo "<script>toastr.success('Marcaje registrado correctamente.');</script>";
}
if (isset($_GET['errorMarcajeAsistencia'])) {
    echo "<script>toastr.error('Error al registrar la asistencia.');</script>";
}
if (isset($_GET['existeMarcajeAsistencia'])) {
    echo "<script>toastr.info('Existen Marcaje de Asistencia.');</script>";
}


$obtenerHistorialDocu = obtenerHistorialDocu($nomUsuario);
$obtenerOTSegunNombre = obtenerOTSegunNombre($nomUsuario);
$contarfaltavisado = contarfaltavisado($nomUsuario);
$obtenerSolicitudesSegunNombre = obtenerSolicitudesSegunNombre($nomUsuario);

if (isset($_GET['loginExito'])) {
    echo "<script>toastr.success('Ingreso Exitoso a la Plataforma.');</script>";
}

if ($obtenerSolicitudesSegunNombre > 0) {
    echo "<script>toastr.info('Tiene Solicitudes de Soporte Asignadas.');</script>";
}

if ($obtenerOTSegunNombre > 0) {
    echo "<script>toastr.info('Existen nuevas Ordenes de Trabajo.');</script>";
}


if ($idDepartamento == 1) {
    if ($contarfaltavisado > 0) {
        $mensajeSinVisar = "Existen $contarfaltavisado Ordenes de Trabajo sin visar.";

        echo "<script>toastr.info($mensajeSinVisar);</script>";
    }
}

?>
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
    const toggle = document.getElementById('toggle-sidebar');
    const icon = document.getElementById('sidebar-icon');


    toggle.addEventListener('click', function () {
        console.log('hola');
        icon.classList.toggle('rotar');
    })
</script>
<script>
    const Anuladas = document.getElementById('Anuladas').textContent;
    const Rechazadas = document.getElementById('Rechazadas').textContent;
    const Aprobadas = document.getElementById('Aprobadas').textContent;
    const ctx = document.getElementById('myChart');

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Aprobadas', 'Rechazadas', 'Anuladas'],
            datasets: [{
                label: 'Cantidad',
                data: [Aprobadas, Rechazadas, Anuladas],
                borderWidth: 1,
                backgroundColor: ['green', 'red', 'orange'],
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

</body>

</html>


