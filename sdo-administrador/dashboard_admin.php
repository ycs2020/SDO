<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

include '../vendor/quickchart/QuickChart.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$nomUsuario = $_SESSION['nomUsuario'];

$horaActual = date('H');
$anio = date('Y');



//$departamentosVisibles = [
//                                                                    "Electricidad" => "Electricidad",
//                                                                    "Equipos de Apoyo Clínico" => "Equipos de Apoyo Clínico",
//                                                                    "Equipos Industriales" => "Equipos Industriales",
//                                                                    "Equipos Médicos" => "Equipos Médicos",
//                                                                    "Incendio y Seguridad" => "Incendio y Seguridad",
//                                                                    "Infraestructura" => "Infraestructura",
//                                                                    "Inst. Térmicas, Climatización y Refrigeración" => "Climatización",
//                                                                    "Instrumentación" => "Control Centralizado",
//                                                                    "Movilización" => "Movilización"
//                                                                ];

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
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js"></script>
    <link rel="stylesheet" href="../dist/css/cuadro-cantidad.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
        .btn-no-background {
            background: none;
            border: none;
            padding: 0;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/admin-sidebar.php';
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

                <div class="row mb-2">
                    <?php

                    $mostrarOcultar = deshabilitarOpciones();

                    if ($mostrarOcultar == 'Mostrar') {
                        ?>

                        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                            <!-- small box -->
                            <?php
                            $cantHojasTec = "SELECT COUNT(*) as TOTAL FROM ordenes_trabajos_general WHERE YEAR(fechaOrden) = " . $anio;
                            $resCantHojasTec = mysqli_query($conexion, $cantHojasTec);
                            while ($rowCantHojasTec = $resCantHojasTec->fetch_assoc()) {
                                $datos = [];
                                $datos['TOTAL'] = $rowCantHojasTec['TOTAL'];
                                $TOTAL = $datos['TOTAL'];
                            }

                            ?>
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="far fa-file"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Hojas Tecnicas Año <?php echo $anio; ?></span>
                                    <span class="info-box-number"><?php echo $TOTAL; ?></span>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>

                    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                        <!-- small box -->
                        <?php
                        $cantHojasTec = "SELECT COUNT(*) as TOTAL FROM solicitudes_compra_sdo WHERE YEAR(fechaSoliCompra) = " . $anio;
                        $resCantHojasTec = mysqli_query($conexion, $cantHojasTec);
                        while ($rowCantHojasTec = $resCantHojasTec->fetch_assoc()) {
                            $datos = [];
                            $datos['TOTAL'] = $rowCantHojasTec['TOTAL'];
                            $TOTAL = $datos['TOTAL'];
                        }

                        ?>
                        <div class="info-box bg-warning">
                            <span class="info-box-icon"><i class="fas fa-shopping-cart"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Solicitudes de Compra Año <?php echo $anio; ?></span>
                                <span class="info-box-number"><?php echo $TOTAL; ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                        <!-- small box -->
                        <?php
                        $cantHojasTec = "SELECT COUNT(*) as TOTAL FROM equipamiento_hbqp";
                        $resCantHojasTec = mysqli_query($conexion, $cantHojasTec);
                        while ($rowCantHojasTec = $resCantHojasTec->fetch_assoc()) {
                            $datos = [];
                            $datos['TOTAL'] = $rowCantHojasTec['TOTAL'];
                            $TOTAL = $datos['TOTAL'];
                        }

                        ?>
                        <div class="info-box bg-purple">
                            <span class="info-box-icon"><i class="fas fa-boxes"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Equipamiento</span>
                                <span class="info-box-number"><?php echo $TOTAL; ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                        <!-- small box -->
                        <?php
                        $cantHojasTec = "SELECT COUNT(*) as TOTAL FROM reportes_generales WHERE YEAR(fechaReporte) = " . $anio;
                        $resCantHojasTec = mysqli_query($conexion, $cantHojasTec);
                        while ($rowCantHojasTec = $resCantHojasTec->fetch_assoc()) {
                            $datos = [];
                            $datos['TOTAL'] = $rowCantHojasTec['TOTAL'];
                            $TOTAL = $datos['TOTAL'];
                        }

                        ?>
                        <div class="info-box bg-success">
                            <span class="info-box-icon"><i class="fas fa-book"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Reportes Turno Año <?php echo $anio; ?></span>
                                <span class="info-box-number"><?php echo $TOTAL; ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-12">
                                <!-- Custom Tabs -->
                                <div class="card">
                                    <div class="card-header bg-gradient-warning d-flex p-0">
                                        <h3 class="card-title p-3"><b>Accesos Rápidos</b></h3>
                                        <ul class="nav nav-pills ml-auto p-2">
                                            <?php

                                            $mostrarOcultar = deshabilitarOpciones();

                                            if ($mostrarOcultar == 'Mostrar') {
                                                ?>
                                                <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab"> <b>Hojas Técnicas</b> </a></li>
                                                <?php
                                            }
                                            ?>

                                            <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab"><b>Solicitudes de Compra</b></a></li>
                                            <li class="nav-item"><a class="nav-link" href="#tab_5" data-toggle="tab"><b>Ordenes de Trabajo</b></a></li>
                                            <li class="nav-item"><a class="nav-link" href="#tab_6" data-toggle="tab"><b>Pulsador de Cama</b></a></li>
                                        </ul>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <?php

                                            $mostrarOcultar = deshabilitarOpciones();

                                            if ($mostrarOcultar == 'Mostrar') {
                                                ?>
                                                <div class="tab-pane active" id="tab_1">

                                                    <?php

                                                    $tipoUsuarioVer = 'todos';
//                                                    $userUsuarioMinusc = mb_convert_case($userUsuario,MB_CASE_LOWER,'UTF-8');
//                                                    if ($userUsuarioMinusc == 'adminsdo') {
//                                                        echo 'Deshabilitado';
//                                                    } else {
//                                                        include_once '../sdo-includes/hojastecnicas_dashboard.php';
//                                                    }
                                                    include_once '../sdo-includes/hojastecnicas_dashboard.php';
//                                                    echo 'Deshabilitado';
                                                    ?>

                                                </div>
                                                <?php
                                            }
                                            ?>
                                            <div class="tab-pane" id="tab_2">
                                                <?php
                                                $userUsuarioMinusc = mb_convert_case($userUsuario,MB_CASE_LOWER,'UTF-8');
//                                                if ($userUsuarioMinusc == 'adminsdo') {
//                                                    include_once '../sdo-includes/solicitudes_compra_dashboard.php';
//                                                } else {
//                                                    echo 'Deshabilitado';
//                                                }
                                                include_once '../sdo-includes/solicitudes_compra_dashboard.php';

                                                ?>
                                            </div>

                                            <div class="tab-pane" id="tab_5">
                                                <?php include_once('../sdo-includes/ordenes_trabajo_dashboard.php') ?>
                                            </div>
                                            <div class="tab-pane" id="tab_6">
                                                <?php
                                                include_once('../sdo-includes/mostrar-peras.php')
                                                ?>
                                            </div>
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
<script scr="js/fullscreen.js"></script>

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
<script src="js/fullScreen.js"></script>

</body>

</html>