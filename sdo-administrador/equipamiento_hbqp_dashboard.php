<?php
include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';
include_once '../vendor/quickchart/QuickChart.php';

if (session_status() === PHP_SESSION_NONE) session_start();
date_default_timezone_set('America/Santiago');

// Validar sesión
if (!isset($_SESSION['userUsuario'], $_SESSION['idUsuario'], $_SESSION['nomUsuario'], $_SESSION['rolUsuario'])) {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    exit;
}

$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];
$rolUsuario = mb_strtolower(trim($_SESSION['rolUsuario']), 'UTF-8');

// Validar idCarpeta
$idCarpeta = $_POST['idCarpeta'] ?? $_SESSION['idCarpeta'] ?? null;
if (!is_numeric($idCarpeta)) {
    header('Location: equipamiento_hbqp.php?noIdCarpeta=true');
    exit;
}
$idCarpeta = (int)$idCarpeta;
$_SESSION['idCarpeta'] = $idCarpeta;

//echo "<script>console.log('ID Carpeta: " . $idCarpeta . "');</script>";

// === Obtener datos generales de la categoría ===
$stmt = $conexion->prepare("SELECT * FROM categorias_equipos WHERE idCategoria = ?");
$stmt->bind_param("i", $idCarpeta);
$stmt->execute();
$categoria = $stmt->get_result()->fetch_assoc();

//echo "<script>console.log('Carpeta: " . $categoria . "');</script>";

if (!$categoria) {
    header('Location: equipamiento_hbqp.php?categoriaNoExiste=true');
    exit;
}

$nomCategoria = $categoria['nomCategoria'];
$imagenCategoria = $categoria['imagenCategoria'];
$idDepartamento = $categoria['idDepartamento'];


// === Roles y rutas ===
$roles = [
    'personal' => 'sdo-personal',
    'administrativo' => 'sdo-admtec',
    'bodega pañol' => 'sdo-bodega-panol',
    'jefe tecnico' => 'sdo-jefestecnicos',
    'administrador' => 'sdo-administrador',
    'directiva' => 'sdo-directiva',
    'oficina tecnica' => 'sdo-oficinaTecnica',
];

$ruta = $roles[$rolUsuario] ?? null;
if (!$ruta) {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    exit;
}

$botones = [
    "MPI" => ["bg-info",      "MANTENIMIENTO PREVENTIVO INTERNO"],
    "MRI" => ["bg-danger",    "MANTENIMIENTO REPARATIVO INTERNO"],
    "MPE" => ["bg-secondary", "MANTENIMIENTO PREVENTIVO EXTERNO"],
    "MRE" => ["bg-purple",    "MANTENIMIENTO REPARATIVO EXTERNO"],
    "OM"  => ["bg-primary",   "OBRAS MENORES"],
    "IS"  => ["bg-orange",    "INSPECCIÓN Y SUPERVISIÓN"]
];

$labelsMantenimiento = [];
$valoresMantenimiento = [];
$coloresMantenimiento = [];
$costosMantenimiento = [];

// Mapa de colores hexadecimales equivalentes a las clases de AdminLTE
$mapaHex = [
    "bg-info"      => "#17a2b8",
    "bg-danger"    => "#dc3545",
    "bg-secondary" => "#6c757d",
    "bg-purple"    => "#6f42c1",
    "bg-primary"   => "#007bff",
    "bg-orange"    => "#fd7e14"
];


foreach ($botones as $codigo => $info) {
    $totalMantenimiento = (int) obtieneCantidadTiposMantencion($idCarpeta, $codigo);

    // Solo incluimos en el gráfico si hay registros
    if ($totalMantenimiento > 0) {
        $labelsMantenimiento[] = $codigo;
        $valoresMantenimiento[] = $totalMantenimiento;
        $coloresMantenimiento[] = $mapaHex[$info[0]] ?? "#000000";

        // Calculamos el costo final para el tooltip
        $costoBase = (float) obtieneGastoTiposMantencion($idCarpeta, $codigo);
        $materiales = $materialesPorTipo[$codigo] ?? 0;
        $costosMantenimiento[] = '$ ' . number_format($costoBase + $materiales, 0, ',', '.');
    }
}

// Convertir a JSON para JS
$labelsMantJS = json_encode($labelsMantenimiento);
$valoresMantJS = json_encode($valoresMantenimiento);
$coloresMantJS = json_encode($coloresMantenimiento);
$costosMantJS  = json_encode($costosMantenimiento);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Equipamiento HBQP</title>

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
    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">



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
                <div class="row">
                    <!-- left column -->
                    <div class="col-xl-10 col-lg-9 col-md-8 col-12">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <a style="pointer-events: none;" class="btn font-weight-bold text-black ">Equipamiento HBQP</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                        <div class="card">
                            <div class="card-header bg-danger ">
                                <a href="equipamiento_hbqp.php" class="btn btn-block btn-danger">
                                    <i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atrás
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

            </div><!-- /.container-fluid -->
        </section>


        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <div class="row mb-3">
                    <div class="col-12">
                        <h3 class="text-dark font-weight-bold">
                            <i class="fas fa-folder-open mr-2"></i><?php echo $nomCategoria; ?>
                        </h3>
                        <hr>
                    </div>
                </div>

                <?php
                // 1. LLAMADA A LAS FUNCIONES (Importante: deben estar aquí o antes)
                $registrados = contarequiposcategoria($idCarpeta);
                $operativos = contareuiposoperativos($idCarpeta);
                $observaciones = contarequiposobs($idCarpeta);
                $mantenimiento = contarequiposmanteni($idCarpeta);
                $fuera = contarequiposfallas($idCarpeta);
                $extraviados = contarequiposextraviados($idCarpeta);

                // 2. Definir array de datos brutos
                $datosBrutos = [
                    'Operativos' => (int)$operativos,
                    'Con Observaciones' => (int)$observaciones,
                    'Mantenimiento' => (int)$mantenimiento,
                    'Fuera de Servicio' => (int)$fuera,
                    'Extraviados' => (int)$extraviados
                ];

                // 3. Filtrar solo los mayores a 0
                $datosFiltrados = array_filter($datosBrutos, function($v) { return $v > 0; });

                // 4. Preparar datos para JS
                $labelsJS = json_encode(array_keys($datosFiltrados));
                $valoresJS = json_encode(array_values($datosFiltrados));

                $mapaColores = [
                    'Operativos' => '#28a745',
                    'Con Observaciones' => '#ffc107',
                    'Mantenimiento' => '#17a2b8',
                    'Fuera de Servicio' => '#dc3545',
                    'Extraviados' => '#6c757d'
                ];

                $coloresFiltrados = [];
                foreach(array_keys($datosFiltrados) as $label) {
                    $coloresFiltrados[] = $mapaColores[$label];
                }
                $coloresJS = json_encode($coloresFiltrados);

                $totalEquipos = array_sum($datosFiltrados);
                $totalJS = json_encode($totalEquipos);
                ?>

                <div class="row d-flex align-items-stretch" id="interfazPrincipal">

                    <div class="col-xl-3 col-lg-4 col-md-6 d-flex">
                        <div class="card card-secondary card-outline w-100">
                            <div class="card-header">
                                <h3 class="card-title">Resumen Numérico</h3>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-unbordered mb-3">
                                    <li class="list-group-item">
                                        <b>Total Registrados</b> <a class="float-right badge badge-primary" style="font-size: 1rem;"><?php echo $registrados; ?></a>
                                    </li>
                                    <?php foreach($datosFiltrados as $label => $valor): ?>
                                        <li class="list-group-item">
                                            <b><?php echo $label; ?></b>
                                            <span class="float-right badge" style="background-color: <?php echo $mapaColores[$label]; ?>; color:white; font-size: 0.9rem;">
                                        <?php echo $valor; ?>
                                    </span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-md-6 d-flex">
                        <div class="card card-primary card-outline w-100">
                            <div class="card-header">
                                <h3 class="card-title">Estado de Equipos</h3>
                            </div>
                            <div class="card-body d-flex align-items-center">
                                <?php if (count($datosFiltrados) > 0): ?>
                                    <div class="chart w-100">
                                        <canvas id="pieChartEquipos" style="min-height: 230px; height: 230px; max-height: 230px; max-width: 100%;"></canvas>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center w-100">
                                        <p class="text-muted">No hay datos suficientes para mostrar el gráfico.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 d-flex">
                        <div class="card card-info card-outline w-100">
                            <div class="card-header">
                                <h3 class="card-title">Resumen de Mantenimientos (Cantidades)</h3>
                            </div>
                            <div class="card-body">
                                <div class="chart">
                                    <canvas id="lineChartMantenimiento" style="min-height: 230px; height: 230px; max-height: 230px; max-width: 100%;"></canvas>
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
<!-- Select2 -->
<script src="../plugins/select2/js/select2.full.min.js"></script>

<script src="../dist/js/adminlte.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    toastr.options = {
        "positionClass": "toast-top-right",
        "timeOut": "5000",
        "closeButton": true,
        "progressBar": true
    };
</script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<script>
    $(function () {
        // 1. REGISTRAR EL PLUGIN (Importante para Chart.js 3+)
        Chart.register(ChartDataLabels);

        var lineChartCanvas = $('#pieChartEquipos').get(0).getContext('2d');
        var total = <?php echo $totalJS; ?>;
        var valoresAbsolutos = <?php echo $valoresJS; ?>;

        var porcentajes = valoresAbsolutos.map(function(valor) {
            return ((valor / total) * 100).toFixed(2);
        });

        var lineData = {
            labels: <?php echo $labelsJS; ?>,
            datasets: [{
                label: 'Distribución %',
                data: porcentajes,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.2)',
                fill: true,
                tension: 0.3,
                pointRadius: 6,
                pointHoverRadius: 8,
                pointBackgroundColor: <?php echo $coloresJS; ?>,
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        };

        var lineOptions = {
            maintainAspectRatio : false,
            responsive : true,
            layout: {
                padding: {
                    top: 40,    // Aumentamos un poco el margen superior
                    right: 20,
                    left: 20,
                    bottom: 10
                }
            },
            plugins: {
                legend: { display: false },
                datalabels: {
                    display: true,
                    align: 'top',
                    anchor: 'end',
                    offset: 5,        // Desplazamiento extra desde el punto
                    clip: false,      // CRÍTICO: Evita que la etiqueta se oculte si toca el borde del canvas
                    backgroundColor: '#f8f9fa',
                    borderRadius: 4,
                    color: '#333',
                    font: {
                        weight: 'bold',
                        size: 11
                    },
                    formatter: function(value) {
                        return value + '%';
                    },
                    padding: 4
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            var index = context.dataIndex;
                            return " " + context.raw + "% (" + valoresAbsolutos[index] + " equipos)";
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    // Quitamos el 'max: 115' fijo para que sea dinámico
                    grace: '15%', // <--- Esto añade automáticamente un 15% de espacio sobre el valor más alto
                    ticks: {
                        callback: function(value) { return value + "%"; }
                    }
                }
            }
        };

        new Chart(lineChartCanvas, {
            type: 'line',
            data: lineData,
            options: lineOptions
        });
    });

    $(function () {
        // Si no has registrado el plugin de datalabels arriba, hazlo aquí:
        // Chart.register(ChartDataLabels);

        var ctxMant = $('#lineChartMantenimiento').get(0).getContext('2d');
        var costosReales = <?php echo $costosMantJS; ?>;

        var mantData = {
            labels: <?php echo $labelsMantJS; ?>,
            datasets: [{
                label: 'Cantidad',
                data: <?php echo $valoresMantJS; ?>,
                borderColor: '#17a2b8', // Color de línea Info
                backgroundColor: 'rgba(23, 162, 184, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 6,
                pointBackgroundColor: <?php echo $coloresMantJS; ?>, // Color del punto según el tipo
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        };

        var mantOptions = {
            maintainAspectRatio: false,
            responsive: true,
            layout: { padding: { top: 40, left: 10, right: 10 } },
            plugins: {
                legend: { display: false },
                datalabels: {
                    display: true,
                    align: 'top',
                    anchor: 'end',
                    backgroundColor: '#343a40', // Fondo oscuro para resaltar
                    color: '#fff',
                    borderRadius: 3,
                    font: { weight: 'bold' },
                    formatter: function(value) { return value; } // Muestra el número entero
                },
                tooltip: {
                    callbacks: {
                        afterLabel: function(context) {
                            // Agregamos el costo total al tooltip
                            return "Gasto Total: " + costosReales[context.dataIndex];
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grace: '20%',
                    ticks: { stepSize: 1 } // Al ser cantidades, forzamos números enteros
                }
            }
        };

        new Chart(ctxMant, {
            type: 'line',
            data: mantData,
            options: mantOptions
        });
    });
</script>
</body>

</html>


