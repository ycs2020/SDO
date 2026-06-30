<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

$nomUsuario = $_SESSION['nomUsuario'];

?>

    <!DOCTYPE html>
    <html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formularios CCAA - Recibidos</title>

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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <script>
        $(document).ready(function() {
            $(".mostrarDashboard").click(function() {
                $("#rowDatos").hide();
                $("#mostrarShai").hide();
                $("#rowDashboard").show();
                $("#listarDatosBoton").show();
                $("#mostrarDashboardBoton").hide();

            });
            $(".listarDatos").click(function() {
                $("#rowDatos").show();
                $("#mostrarShai").show();
                $("#rowDashboard").hide();
                $("#listarDatosBoton").hide();
                $("#mostrarDashboardBoton").show();
            });
        });
    </script>

    <style>
        .anio-link{
            transition: all 0.2s ease;
            border-radius: 8px;
            padding: 6px 12px;
        }

        .anio-link:hover{
            background: rgba(255,255,255,0.15);
        }

        .anio-link.active{
            background: #ffffff !important;
            color: #000 !important;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(0,0,0,0.25);
            transform: scale(1.05);
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

            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-xl-10 col-lg-9 col-md-8 col-12">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <a style="pointer-events: none;" class="btn font-weight-bold">Solicitudes de Acceso Recibidas</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                        <div class="card">
                            <div class="card-header bg-danger">
                                <a href="index.php" style="float: right;" class="btn btn-block btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            </div>
                        </div>
                    </div>


                    <div class="col-12">
                        <!-- Custom Tabs -->
                        <div class="card">
                            <div class="card-header" style="text-align:center;">
                                <button class="btn btn-md bg-success" id="mostrarShai" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-eye"></i> Buscar Funcionario</button>
                                <button class="btn btn-md bg-blue mostrarDashboard" id="mostrarDashboardBoton"><i class="fas fa-chart-line"></i> DashBoard</button>
                                <button class="btn btn-md bg-blue listarDatos" id="listarDatosBoton" style="display: none"> Volver a los datos</button>
                            </div>

                            <div class="row" id="rowDashboard" style="display:none;">
                                <div class="card-body" style="text-align:center;">
                                    <ul class="nav bg-dark p-2 rounded">

                                        <?php
                                        $sqlBuscarAño = "SELECT DISTINCT YEAR(fechaFormRegAcceso) AS anio
                                             FROM formularios_registro
                                             ORDER BY anio DESC";

                                        $resBuscarAño = mysqli_query($conexion,$sqlBuscarAño);

                                        while($rowBuscarAño = mysqli_fetch_assoc($resBuscarAño)){

                                            $anio = $rowBuscarAño['anio'];

                                            echo "
                                            <li class='nav-item'>
                                                <a href='#'
                                                   class='nav-link text-white fw-bold anio-link'
                                                   data-anio='$anio'>
                                                    $anio
                                                </a>
                                            </li>";
                                        }
                                        ?>
                                    </ul>
                                    <br>
                                    <h5><span id="anioActual"></span></h5>
                                    <hr>

                                    <div class="row g-3 mt-3">

                                        <!-- KPIs -->
                                        <div class="col-md-3">
                                            <div class="card border-0 shadow-sm bg-primary text-white">
                                                <div class="card-body">
                                                    <small class="text-uppercase">Total Formularios</small>
                                                    <h1 id="totalCasos" class="fw-bold mb-0">0</h1>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="card border-0 shadow-sm bg-success text-white">
                                                <div class="card-body">
                                                    <small class="text-uppercase">Promedio Mensual</small>
                                                    <h1 id="promedioMensual" class="fw-bold mb-0">0</h1>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="card border-0 shadow-sm bg-info text-white">
                                                <div class="card-body">
                                                    <small class="text-uppercase">Promedio Diario</small>
                                                    <h1 id="promedioDiario" class="fw-bold mb-0">0</h1>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="card border-0 shadow-sm bg-warning">
                                                <div class="card-body">
                                                    <small class="text-uppercase">Último Mes</small>
                                                    <h5 id="nombreUltimoMes" class="mb-0"></h5>
                                                    <small id="ultimoMes"></small>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <!-- ?? GRAFICOS -->
                                    <div class="row mt-4">
                                        <div class="col-md-4">

                                            <div class="card border-0 shadow">
                                                <div class="card-header bg-black">
                                                    <strong>Firmados vs No Firmados</strong>
                                                    <span id="anioActual"></span>
                                                </div>

                                                <div class="card-body d-flex justify-content-center align-items-center">

                                                    <div style="width: 220px; height: 350px;">
                                                        <canvas id="donutFirmados"></canvas>
                                                    </div>

                                                </div>

                                            </div>

                                        </div>
                                        <div class="col-md-4">
                                            <div class="card border-0 shadow">
                                                <div class="card-header bg-black">
                                                    <strong>Top 10 Unidades por Año</strong>
                                                </div>

                                                <div class="card-body">
                                                    <div style="height:350px;">
                                                        <canvas id="barTopUnidades"></canvas>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card border-0 shadow">
                                                <div class="card-header bg-black">
                                                    <strong>Cantidad de Formatos por Mes</strong>
                                                </div>
                                                <div class="card-body">
                                                    <canvas id="lineFormatoMes" style="width: 100%; height: 350px;"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="row" id="rowDatos">
                                <div class="card-body" style="text-align:center;">
                                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-xl" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success">
                                                    <h5 class="modal-title" id="exampleModalLabel">Buscar Funcionarios en los Formatos de Accesos</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <table id="tablaFuncionarios" class="table table-striped table-bordered" style="width:100%">                                                    <thead class="bg-secondary">
                                                        <tr>
                                                            <th>Folio</th>
                                                            <th>Funcionario</th>
                                                            <th>Documento Recibido</th>
                                                            <th>Visualizar</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                        <tfoot class="bg-secondary">
                                                        <tr>
                                                            <th>Folio</th>
                                                            <th>Funcionario</th>
                                                            <th>Documento Recibido</th>
                                                            <th>Visualizar</th>
                                                        </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <table id="tablaSolicitudesRecibidas" class="table table-bordered table-striped table-sm text-center" style="font-size: 11pt;">
                                        <thead class="bg-secondary">
                                        <tr>
                                            <th>
                                                Folio
                                            </th>
                                            <th>
                                                Solicitante
                                            </th>
                                            <th>
                                                Unidad
                                            </th>
                                            <th>
                                                Fecha
                                            </th>
                                            <th>
                                                Categoria
                                            </th>
                                            <th>
                                                Visualizar
                                            </th>
                                        </tr>
                                        </thead>
                                        <tfoot class="bg-secondary">
                                        <tr>
                                            <th>
                                                Folio
                                            </th>
                                            <th>
                                                Solicitante
                                            </th>
                                            <th>
                                                Unidad
                                            </th>
                                            <th>
                                                Fecha
                                            </th>
                                            <th>
                                                Categoria
                                            </th>
                                            <th>
                                                Visualizar
                                            </th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                    <style>
                                        #tablaSolicitudesRecibidas td,
                                        #tablaSolicitudesRecibidas th {
                                            vertical-align: middle !important;
                                        }
                                    </style>
                                    <script>
                                        $(document).ready(function () {
                                            $('#tablaSolicitudesRecibidas').DataTable({
                                                "processing": true,
                                                "serverSide": true,
                                                "ajax": {
                                                    "url": "/sdo-includes/formularios_ccaa_recibidos_cargar.php",
                                                    "type": "POST"
                                                },
                                                "columns": [
                                                    {"data": "folioFormRegAcceso"},
                                                    {"data": "solicitanteFormRegAcceso"},
                                                    {
                                                        "data": "unidades",
                                                        "render": function (data) {
                                                            return data.replace(/\n/g, "<br>");
                                                        }
                                                    },
                                                    {"data": "fechaFormRegAcceso2"},
                                                    {"data": "categoriaFormRegAcceso"},
                                                    {
                                                        "data": "botonVisualizar"
                                                    }
                                                ],
                                                "responsive": true,
                                                "lengthChange": false,
                                                "ordering": false,
                                                "searching": true,
                                                "language": {
                                                    "lengthMenu": "Visualizando _MENU_ Registros por Página",
                                                    "zeroRecords": "No se han encontrado registros.",
                                                    "info": "Mostrando página _PAGE_ de _PAGES_",
                                                    "infoEmpty": "No existen registros.",
                                                    "infoFiltered": "(filtrado desde _MAX_ registros totales)",
                                                    "search": "Buscar:",
                                                    "processing": "Procesando...",
                                                    "paginate": {
                                                        "previous": "Anterior",
                                                        "next": "Siguiente"
                                                    }
                                                }
                                            });
                                        });
                                    </script>
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
    $(document).ready(function () {
        $('#tablaFuncionarios').DataTable({
            "ajax": {
                "url": "/sdo-includes/formularios_ccaa_recibidos_cargar_funcionarios.php",
                "type": "POST"
            },
            "columns": [
                {"data": "folioFormRegAcceso"},
                {"data": "funcionarios"},
                {"data": "estadoDocumento"},
                {"data": "botonVisualizar"},
            ],
            "responsive": true,
            "lengthChange": false,
            "ordering": false,
            "searching": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por Página",
                "zeroRecords": "No se han encontrado registros.",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "No existen registros.",
                "infoFiltered": "(filtrado desde _MAX_ registros totales)",
                "search": "Buscar:",
                "processing": "Procesando...",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            }
        });
    });
</script>

<script>
    $(document).on('click', '.anio-link', function(e){

        e.preventDefault();

        let anio = $(this).data('anio');
        $('#anioActual').text(anio);

        cargarDashboard(anio);

    });
</script>
<script>
    Chart.register(ChartDataLabels);
    let donutChart = null;
    let barChart = null;
    let lineChart = null;

    function cargarDashboard(anio){

        $.ajax({
            url:'../sdo-includes/dashboard_solicitudes_acceso.php',
            type:'POST',
            dataType:'json',
            data:{
                anio:anio
            },
            success:function(data){

                $('#totalCasos').text(data.totalCasos);
                $('#promedioMensual').text(data.promedioMensual);
                $('#promedioDiario').text(data.promedioDiario);

                $('#nombreUltimoMes').text(data.ultimoMesNombre);
                $('#ultimoMes').text(data.ultimoMesTotal + ' formularios');

                $('#nombreMesActivo').text(data.mesMasActivoNombre);
                $('#mesActivo').text(data.mesMasActivoTotal + ' formularios');

                // ?? destruir gráfico anterior si existe
                if (donutChart !== null) {
                    donutChart.destroy();
                }

                // ?? crear nuevo gráfico
                donutChart = new Chart(document.getElementById('donutFirmados'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Firmados', 'No firmados'],
                        datasets: [{
                            data: [data.firmados, data.noFirmados],
                            backgroundColor: ['#28a745', '#dc3545']
                        }]
                    },
                    options: {
                        plugins: {
                            datalabels: {
                                color: '#fff',
                                font: {
                                    weight: 'bold',
                                    size: 14
                                },
                                formatter: (value, context) => {
                                    return value; // muestra el número dentro del segmento
                                }
                            },
                            legend: {
                                position: 'bottom'
                            }
                        }
                    },
                    plugins: [ChartDataLabels]
                });

                // destruir anterior si existe
                if (barChart !== null) {
                    barChart.destroy();
                }

                if(lineChart !== null){
                    lineChart.destroy();
                }

// preparar datos
                const labels = data.top5_unidades.map(item => item.unidadFormCCAA);
                const valores = data.top5_unidades.map(item => item.total_2025);

                const meses = data.formatosMes.map(item => item.mes);
                const totalesPorMes = data.formatosMes.map(item => item.total);

// crear gráfico de barras
                barChart = new Chart(document.getElementById('barTopUnidades'), {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Top unidades',
                            data: valores,
                            backgroundColor: '#007bff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            datalabels: {
                                anchor: 'end',
                                align: 'top',
                                color: '#000',
                                font: {
                                    weight: 'bold'
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 200
                            }
                        }
                    },
                    plugins: [ChartDataLabels]
                });

                lineChart = new Chart(document.getElementById('lineFormatoMes'), {
                    type: 'line',
                    data: {
                        labels: meses,
                        datasets: [{
                            label: 'Formularios por mes',
                            data: totalesPorMes,
                            borderColor: '#007bff',
                            backgroundColor: 'rgba(0, 123, 255, 0.2)',
                            fill: true,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true
                            },
                            datalabels: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    },
                    plugins: [ChartDataLabels]
                });

            }
        });

    }
</script>
<!-- Page specific script -->

</body>
</html>

