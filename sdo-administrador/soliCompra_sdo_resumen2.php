<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);
$ruta = "";

date_default_timezone_set('America/Santiago');
$anioSC = date('Y', time());

if ($rolUsuario2 == "personal") {
    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $ruta = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $ruta = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $ruta = "sdo-directiva";
} else if ($rolUsuario2 == "mesa ayuda") {
    $ruta = "sdo-mesa-ayuda";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Resumen SC Control</title>

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
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

    <style>
        .bg-brown {
            background-color: #6F4C3E; /* Color café */
            color: white;
        }
    </style>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/admin-sidebar.php';
    ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-xl-10 col-lg-9 col-md-8 col-12">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <a style="pointer-events: none;" class="btn font-weight-bold bg-primary">Resumen de Solicitudes de Compra</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                        <div class="card">
                            <div class="card-header bg-danger">
                                <a href="index.php" style="float: right;" class="btn btn-block bg-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <!-- Profile Image -->
                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile">
                                <?php

                                $sqlContador = "SELECT COUNT(*) AS total FROM solicitudes_compra_sdo WHERE YEAR(fechaSoliCompra) = " . $anioSC;
                                $totalSolicitudes = obtenerDashboardSC($sqlContador);

                                $sqlContador1 = "SELECT COUNT(*) AS total FROM solicitudes_compra_sdo WHERE estadoSoliCompra LIKE 'Autorizado por la S.D.A' AND YEAR(fechaSoliCompra) = " . $anioSC;
                                $totalAutoSDA = obtenerDashboardSC($sqlContador1);

                                $sqlContador2 = "SELECT COUNT(*) AS total FROM solicitudes_compra_sdo WHERE estadoSoliCompra LIKE 'Rechazado por la S.D.A' AND YEAR(fechaSoliCompra) = " . $anioSC;
                                $totalRechazoSDA = obtenerDashboardSC($sqlContador2);

                                $sqlContador3 = "SELECT COUNT(*) AS total FROM solicitudes_compra_sdo WHERE estadoSoliCompra LIKE 'Solicitud Anulada' AND YEAR(fechaSoliCompra) = " . $anioSC;
                                $totalAnula = obtenerDashboardSC($sqlContador3);

                                $sqlContador4 = "SELECT COUNT(*) AS total FROM solicitudes_compra_sdo WHERE estadoSoliCompra LIKE 'SOLICITADA A SECRETARIA SDO' AND YEAR(fechaSoliCompra) = " . $anioSC;
                                $totalSolicitaSDO = obtenerDashboardSC($sqlContador4);

                                $sqlContador5 = "SELECT COUNT(*) AS total FROM solicitudes_compra_sdo WHERE estadoSoliCompra LIKE 'ENTREGADO A LA S.D.A' AND YEAR(fechaSoliCompra) = " . $anioSC;
                                $totalEntregadoSDA = obtenerDashboardSC($sqlContador5);

                                $sqlContador6 = "SELECT COUNT(*) AS total FROM solicitudes_compra_sdo WHERE estadoSoliCompra LIKE 'DERIVADO A ABASTECIMIENTO' AND YEAR(fechaSoliCompra) = " . $anioSC;
                                $totalAbastecimiento = obtenerDashboardSC($sqlContador6);

                                $sqlContador7 = "SELECT COUNT(*) AS total FROM solicitudes_compra_sdo WHERE estadoSoliCompra LIKE 'Recibido por pañol' AND YEAR(fechaSoliCompra) = " . $anioSC;
                                $totalPanol = obtenerDashboardSC($sqlContador7);

                                $sqlContador8 = "SELECT COUNT(*) AS total FROM solicitudes_compra_sdo WHERE estadoSoliCompra LIKE 'Solicitud Completada' AND YEAR(fechaSoliCompra) = " . $anioSC;
                                $totalCompletado = obtenerDashboardSC($sqlContador8);

                                $sqlContador9 = "SELECT COUNT(*) AS total FROM solicitudes_compra_sdo WHERE estadoSoliCompra LIKE 'Revisada por Secretaría SDO' AND YEAR(fechaSoliCompra) = " . $anioSC;
                                $totalRevisadaSDO = obtenerDashboardSC($sqlContador9);

                                $totalGeneradas = $totalSolicitudes - $totalAnula;
                                if ($totalGeneradas > -1) {
                                    $totalGeneradas2 = $totalGeneradas;
                                } else {
                                    $totalGeneradas2 = 'Sin Información';
                                }

                                $recibidasSDA = $totalGeneradas2 - $totalSolicitaSDO - $totalRevisadaSDO;

                                ?>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header bg-gradient-primary">
                                                <h5 class="card-title">Total de Solicitudes de Compra SDO Año <?php echo $anioSC . ': <b>' . $totalSolicitudes . '</b>'; ?></h5>

                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body">
                                                <p class="font-weight-bold">SC = Solicitud de Compra</p>
                                                <div class="row">

                                                    <br>

                                                    <!-- /.col -->
                                                    <div class="col-xl-3 col-lg-3 col-md-4 col-12">
                                                        <p class="text-center">
                                                            <strong>Proceso SDO</strong>
                                                        </p>

                                                        <div class="progress-group">
                                                    <span class="progress-text">
                                                        SC Generadas</span>
                                                            <span class="float-right"><b><?php echo $totalSolicitudes; ?></b><br></span>

                                                            <div class="progress progress-sm">
                                                                <div class="progress-bar bg-primary" style="width: <?php echo calcularPorcentajesSC($totalSolicitudes, 100, $totalSolicitudes) ?>%"></div>
                                                            </div>
                                                        </div>
                                                        <!-- /.progress-group -->

                                                        <div class="progress-group">
                                                            <button class="btn btn-white btn-sm btn-no-background text-danger" onclick="cargarDatos('anuladas','<?php echo $anioSC; ?>','Solicitudes Anuladas')">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                            SC Anuladas
                                                            <span class="float-right"><b><?php echo $totalAnula; ?></b>/<?php echo $totalSolicitudes; ?></span>
                                                            <div class="progress progress-sm">
                                                                <div class="progress-bar bg-danger" style="width: <?php echo calcularPorcentajesSC($totalAnula, 100, $totalSolicitudes) ?>%"></div>
                                                            </div>
                                                        </div>
                                                        <div class="progress-group">
                                                    <span class="progress-text">
                                                        <button class="btn btn-white btn-sm btn-no-background text-success" onclick="cargarDatos('enCurso','<?php echo $anioSC; ?>','Solicitudes en Curso')">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                        SC En Curso
                                                    </span>
                                                            <span class="float-right"><b><?php echo $totalGeneradas2; ?></b>/<?php echo $totalSolicitudes; ?></span>
                                                            <div class="progress progress-sm">
                                                                <div class="progress-bar bg-success" style="width: <?php echo calcularPorcentajesSC($totalGeneradas2, 100, $totalSolicitudes) ?>%"></div>
                                                            </div>
                                                        </div>
                                                        <!-- /.progress-group -->
                                                        <div class="progress-group">
                                                            <button class="btn btn-white btn-sm btn-no-background text-warning" onclick="cargarDatos('porRevisarSDO','<?php echo $anioSC; ?>','Solicitudes Faltantes por Revisar')">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                            SC Faltantes por Revisar
                                                            <span class="float-right"><b><?php echo $totalSolicitaSDO; ?></b>/<?php echo $totalGeneradas2; ?></span>
                                                            <div class="progress progress-sm">
                                                                <div class="progress-bar bg-warning" style="width: <?php echo calcularPorcentajesSC($totalSolicitaSDO, 100, $totalGeneradas2) ?>%"></div>
                                                            </div>
                                                        </div>

                                                        <div class="progress-group">
                                                            <button class="btn btn-white btn-sm btn-no-background text-purple" onclick="cargarDatos('porTramitarSDO','<?php echo $anioSC; ?>','Solicitudes Faltantes por Tramitar')">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                            SC Faltantes por Tramitar
                                                            <span class="float-right"><b><?php echo $totalRevisadaSDO; ?></b>/<?php echo $totalGeneradas2; ?></span>
                                                            <div class="progress progress-sm">
                                                                <div class="progress-bar bg-purple" style="width: <?php echo calcularPorcentajesSC($totalRevisadaSDO, 100, $totalGeneradas2) ?>%"></div>
                                                            </div>
                                                        </div>
                                                        <!-- /.progress-group -->

                                                        <div class="progress-group">
                                                            <button class="btn btn-white btn-sm btn-no-background text-primary" onclick="cargarDatos('enviadasSDA','<?php echo $anioSC; ?>','Solicitudes Enviadas a SDA')">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                            SC Enviadas a SDA
                                                            <span class="float-right"><b><?php echo $recibidasSDA; ?></b>/<?php echo $totalGeneradas2; ?></span>
                                                            <div class="progress progress-sm">
                                                                <div class="progress-bar bg-info" style="width: <?php echo calcularPorcentajesSC($recibidasSDA, 100, $totalGeneradas2) ?>%"></div>
                                                            </div>
                                                        </div>
                                                        <!-- /.progress-group -->


                                                        <!-- /.progress-group -->
                                                    </div>
                                                    <div class="col-xl-3 col-lg-3 col-md-4 col-12">
                                                        <p class="text-center">
                                                            <strong>Proceso SDA</strong>
                                                        </p>


                                                        <div class="progress-group">
                                                            <button class="btn btn-white btn-sm btn-no-background text-primary" onclick="cargarDatos('recibidaSDA','<?php echo $anioSC; ?>','Solicitudes Pendientes por SDA')">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                            SC Pendientes por SDA
                                                            <span class="float-right"><b><?php echo $totalEntregadoSDA; ?></b>/<?php echo $recibidasSDA; ?></span>
                                                            <div class="progress progress-sm">
                                                                <div class="progress-bar bg-primary" style="width: <?php echo calcularPorcentajesSC($totalEntregadoSDA, 100, $recibidasSDA) ?>%"></div>
                                                            </div>
                                                        </div>
                                                        <!-- /.progress-group -->

                                                        <div class="progress-group">
                                                            <button class="btn btn-white btn-sm btn-no-background text-success" onclick="cargarDatos('aprobadaSDA','<?php echo $anioSC; ?>','Solicitudes Por Derivar desde SDA')">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                            SC Por Derivar desde SDA
                                                            <span class="float-right"><b><?php echo $totalAutoSDA; ?></b>/<?php echo $recibidasSDA; ?></span>
                                                            <div class="progress progress-sm">
                                                                <div class="progress-bar bg-success" style="width: <?php echo calcularPorcentajesSC($totalAutoSDA, 100, $recibidasSDA) ?>%"></div>
                                                            </div>
                                                        </div>
                                                        <!-- /.progress-group -->

                                                        <div class="progress-group">
                                                            <button class="btn btn-white btn-sm btn-no-background text-danger" onclick="cargarDatos('rechazadaSDA','<?php echo $anioSC; ?>','Solicitudes Rechazadas por SDA')">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                            SC Rechazadas por SDA
                                                            <span class="float-right"><b><?php echo $totalRechazoSDA; ?></b>/<?php echo $recibidasSDA; ?></span>
                                                            <div class="progress progress-sm">
                                                                <div class="progress-bar bg-danger" style="width: <?php echo calcularPorcentajesSC($totalRechazoSDA, 100, $recibidasSDA) ?>%"></div>
                                                            </div>
                                                        </div>
                                                        <!-- /.progress-group -->
                                                    </div>
                                                    <div class="col-xl-3 col-lg-3 col-md-4 col-12">
                                                        <p class="text-center">
                                                            <strong>Proceso Abastecimiento</strong>
                                                        </p>

                                                        <div class="progress-group">
                                                            <button class="btn btn-white btn-sm btn-no-background text-warning" onclick="cargarDatos('abastecimiento','<?php echo $anioSC; ?>','Solicitudes Pendientes por Abastecimiento')">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                            SC Pendientes por Abastecimiento
                                                            <span class="float-right"><b><?php echo $totalAbastecimiento; ?></b>/<?php echo $recibidasSDA; ?></span>
                                                            <div class="progress progress-sm">
                                                                <div class="progress-bar bg-warning" style="width: <?php echo calcularPorcentajesSC($totalAbastecimiento, 100, $recibidasSDA) ?>%"></div>
                                                            </div>
                                                        </div>
                                                        <!-- /.progress-group -->
                                                    </div>
                                                    <div class="col-xl-3 col-lg-3 col-md-4 col-12">
                                                        <p class="text-center">
                                                            <strong>Proceso Pa&ntilde;ol</strong>
                                                        </p>

                                                        <div class="progress-group">
                                                            <button class="btn btn-white btn-sm btn-no-background text-warning" onclick="cargarDatos('panol','<?php echo $anioSC; ?>','SC Pendientes por Entregar desde Pañol')">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                            SC Pendientes por Entregar desde Pa&ntilde;ol
                                                            <span class="float-right"><b><?php echo $totalPanol; ?></b>/<?php echo $recibidasSDA; ?></span>
                                                            <div class="progress progress-sm">
                                                                <div class="progress-bar bg-warning" style="width: <?php echo calcularPorcentajesSC($totalPanol, 100, $recibidasSDA) ?>%"></div>
                                                            </div>
                                                        </div>
                                                        <!-- /.progress-group -->

                                                        <div class="progress-group">
                                                            <button class="btn btn-white btn-sm btn-no-background text-success" onclick="cargarDatos('completada','<?php echo $anioSC; ?>','Solicitudes Completadas')">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                            SC Completadas
                                                            <span class="float-right"><b><?php echo $totalCompletado; ?></b>/<?php echo $recibidasSDA; ?></span>
                                                            <div class="progress progress-sm">
                                                                <div class="progress-bar bg-success" style="width: <?php echo calcularPorcentajesSC($totalCompletado, 100, $recibidasSDA) ?>%"></div>
                                                            </div>
                                                        </div>
                                                        <!-- /.progress-group -->
                                                    </div>
                                                    <!-- /.col -->

                                                </div>
                                                <!-- /.row -->
                                            </div>
                                            <!-- ./card-body -->
                                        </div>
                                        <!-- /.card -->
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->

                            </div>
                        </div>
                    </div>


                    <div class="col-12">
                        <!-- Profile Image -->
                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile">
                                <div class="row justify-content-center">

                                    <?php
                                    $unidades = [
                                        1 => ['nombre' => 'Control Centralizado', 'color' => 'bg-primary', 'icono' => 'fas fa-laptop'],
                                        2 => ['nombre' => 'Electricidad', 'color' => 'bg-success', 'icono' => 'fas fa-bolt'],
                                        3 => ['nombre' => 'Equipos de Apoyo Clínico', 'color' => 'bg-info', 'icono' => 'fas fa-bed'],
                                        4 => ['nombre' => 'Equipos Industriales', 'color' => 'bg-warning', 'icono' => 'fas fa-gas-pump'],
                                        5 => ['nombre' => 'Incendio y Seguridad', 'color' => 'bg-danger', 'icono' => 'fas fa-fire-extinguisher'],
                                        6 => ['nombre' => 'Infraestructura', 'color' => 'bg-secondary', 'icono' => 'fas fa-building'],
                                        7 => ['nombre' => 'Inst. Térmicas, Climatización y Refrigeración', 'color' => 'bg-dark', 'icono' => 'fas fa-cloud'],
                                        8 => ['nombre' => 'Logística', 'color' => 'bg-brown', 'icono' => 'fas fa-file'],
                                        9 => ['nombre' => 'Movilización', 'color' => 'bg-orange', 'icono' => 'fas fa-ambulance'],
                                        10 => ['nombre' => 'Subdirección de Operaciones', 'color' => 'bg-indigo', 'icono' => 'fas fa-cogs']
                                    ];

                                    $gastoTotal = 0;

                                    foreach ($unidades as $numero => $unidad) {
                                        // Definir el nombre de la unidad
                                        $nombreUnidad = $unidad['nombre'];

                                        // Si la unidad es Control Centralizado, sumar Instrumentación y Jefe de Control Centralizado
                                        if ($numero == 1) {
                                            $sqlSuma = "SELECT SUM(valor) as totalUnidad FROM solicitudes_sc_sdo_resumen WHERE (unidadSoliCompra LIKE '%Instrumentación%' OR unidadSoliCompra LIKE '%Jefe de Control Centralizado%') AND YEAR(fechaSoliCompra) = " . intval($anioSC);
                                        } else {
                                            // Consulta para las demás unidades
                                            $sqlSuma = "SELECT SUM(valor) as totalUnidad FROM solicitudes_sc_sdo_resumen WHERE unidadSoliCompra LIKE '%" . mysqli_real_escape_string($conexion, $nombreUnidad) . "%' AND YEAR(fechaSoliCompra) = " . intval($anioSC);
                                        }

                                        // Ejecutar el query
                                        $resSuma = mysqli_query($conexion, $sqlSuma);
                                        $totalUnidad = 0; // Inicializar totalUnidad por defecto

                                        if ($resSuma) {
                                            $row = mysqli_fetch_assoc($resSuma);
                                            // Verificar si el valor de totalUnidad es nulo o mayor que 0
                                            if (isset($row['totalUnidad']) && $row['totalUnidad'] !== null) {
                                                $totalUnidad = $row['totalUnidad'];
                                                $gastoTotal += $totalUnidad;
                                            }
                                        } else {
                                            // En caso de error en la consulta
                                            $totalUnidad = 'Error al Obtener Información.';
                                        }

                                        // Formatear el total de la unidad
                                        $totalUnidad2 = ($totalUnidad > -1) ? "$" . number_format($totalUnidad, 0, ',', '.') : "Sin datos";

                                        // Mostrar el botón
                                        ?>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-12 p-2">
                                            <button class="btn btn-block <?php echo $unidad['color']; ?> btn-lg" style="font-size: medium">
                                                <b><?php echo $nombreUnidad; ?></b>
                                                <br>
                                                <i class="<?php echo $unidad['icono']; ?>"></i>
                                                <br>
                                                <b><?php echo $totalUnidad2; ?></b>
                                            </button>
                                        </div>
                                        <?php
                                    }


                                    ?>
                                </div>

                                <hr>

                                <div class="row justify-content-center">

                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 p-2">
                                        <button class="btn btn-block bg-success btn-lg" style="font-size: medium">
                                            <b>Gasto Total SC</b>
                                            <br>
                                            <i class="fas fa-money-bill"></i>
                                            <br>
                                            <?php
                                            $gastoTotal2 = ($gastoTotal > -1) ? "$" . number_format($gastoTotal, 0, ',', '.') : "Sin datos";

                                            ?>
                                            <b><?php echo $gastoTotal2; ?></b>
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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

<script>
    $(function () {
        bsCustomFileInput.init();
    });
</script>


</body>

</html>
