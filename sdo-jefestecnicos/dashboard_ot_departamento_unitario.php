<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Hojas Tecnicas</title>

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
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="js/dark-mode.js"></script>
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

    <style>
        .grafico-ht {
            height: 350px !important;
            width: 100% !important;
            min-width: 300px;
        }
    </style>

</head>
<body class="hold-transition sidebar-mini ">
<div class="wrapper">
    <?php
    include '../sdo-templates/jefes-sidebar.php';
    ?>
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
                                <h4 class="text-justify font-weight-bold ">Dashboard Hojas Tecnicas</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="post" action="index.php" class="text-right">
                                    <button type="submit" class="btn btn-danger btn-block">
                                        <i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atrás
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-12 col-md-12">
                        <div class="card">
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-12 col-lg-6">
                                        <?php
                                        // Definir agrupaciones visibles (nombre => array de idDepartamento)
                                        function obtenerIdsRelacionados($idDepartamento, $agrupaciones) {
                                            $ids = [];

                                            foreach ($agrupaciones as $idsGrupo) {
                                                if (in_array($idDepartamento, $idsGrupo)) {
                                                    $ids = array_merge($ids, $idsGrupo);
                                                }
                                            }

                                            return array_unique($ids);
                                        }

                                        // 1. Definir agrupaciones
                                        $agrupaciones = [
                                            "Control Centralizado"     => [1, 13],
                                            "Equipos Industriales"     => [2],
                                            "Incendio y Seguridad"     => [2, 12],
                                            "Climatización"            => [3],
                                            "Electricidad"             => [4],
                                            "Infraestructura"          => [5],
                                            "Equipos de Apoyo Clínico" => [10]
                                        ];

                                        // 2. Obtener todos los IDs asociados a las agrupaciones del id actual
                                        $idsRelacionados = obtenerIdsRelacionados($idDepartamento, $agrupaciones);

                                        // 3. Preparar string para SQL
                                        $listaDepartamentos = implode(',', $idsRelacionados);

                                        // 4. Consulta SQL final
                                        $sqlDepas = "
    SELECT idDepartamento, YEAR(fechaOrden) AS anio, COUNT(*) AS total_ordenes
    FROM ordenes_trabajos_general
    WHERE anulada = 0 
      AND fechaOrden IS NOT NULL 
      AND idDepartamento IN ($listaDepartamentos)
    GROUP BY idDepartamento, anio
    ORDER BY anio, idDepartamento
";
                                        $resultDepas = mysqli_query($conexion, $sqlDepas);

                                        // Función para obtener los nombres visibles de agrupaciones que contiene un idDepartamento
                                        function obtenerDepartamentosVisibles($id, $agrupaciones)
                                        {
                                            $resultados = [];
                                            foreach ($agrupaciones as $nombre => $ids) {
                                                if (in_array($id, $ids)) {
                                                    $resultados[] = $nombre;
                                                }
                                            }
                                            return $resultados;
                                        }

                                        // Agrupar resultados
                                        $datosAgrupados = [];
                                        while ($rowDepas = mysqli_fetch_assoc($resultDepas)) {
                                            $anio = $rowDepas['anio'];
                                            $idDep = $rowDepas['idDepartamento'];
                                            $total = $rowDepas['total_ordenes'];

                                            $departamentosVisibles = obtenerDepartamentosVisibles($idDep, $agrupaciones);

                                            foreach ($departamentosVisibles as $deptoVisible) {
                                                if (!isset($datosAgrupados[$deptoVisible][$anio])) {
                                                    $datosAgrupados[$deptoVisible][$anio] = 0;
                                                }
                                                $datosAgrupados[$deptoVisible][$anio] += $total;
                                            }
                                        }

                                        // Contar cuántos IDs tiene cada agrupación (para saber si es múltiple)
                                        $conteoIDsPorGrupo = [];
                                        foreach ($agrupaciones as $nombre => $ids) {
                                            $conteoIDsPorGrupo[$nombre] = count($ids);
                                        }

                                        // Preparar labels (años)
                                        $labels = [];
                                        foreach ($datosAgrupados as $anios) {
                                            foreach ($anios as $anio => $count) {
                                                if (!in_array($anio, $labels)) {
                                                    $labels[] = $anio;
                                                }
                                            }
                                        }
                                        sort($labels);

                                        // Preparar datasets para Chart.js
                                        $datasets = [];
                                        foreach ($datosAgrupados as $depto => $anios) {
                                            $data = [];
                                            foreach ($labels as $anio) {
                                                $data[] = isset($anios[$anio]) ? $anios[$anio] : 0;
                                            }

                                            $color = ($conteoIDsPorGrupo[$depto] > 1)
                                                ? 'rgba(255, 99, 132, 0.7)'   // Rojo para agrupaciones múltiples
                                                : 'rgba(54, 162, 235, 0.7)';  // Azul por defecto

                                            $border = str_replace('0.7', '1', $color);

                                            $datasets[] = [
                                                'label' => $depto,
                                                'data' => $data,
                                                'backgroundColor' => $color,
                                                'borderColor' => $border,
                                                'borderWidth' => 1
                                            ];
                                        }

                                        // Convertir a JS
                                        $labelsJS = json_encode($labels);
                                        $datasetsJS = json_encode($datasets);
                                        ?>

                                        <!-- Canvas -->
                                        <div style="overflow-x: auto;">
                                            <canvas id="graficoDepartamentos" class="grafico-ht"></canvas>
                                        </div>

                                        <!-- Librerías Chart.js y DataLabels -->
                                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                                        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

                                        <!-- Script del gráfico -->
                                        <script>
                                            const ctx = document.getElementById('graficoDepartamentos').getContext('2d');

                                            const data = {
                                                labels: <?= $labelsJS ?>,
                                                datasets: <?= $datasetsJS ?>.map(dataset => ({
                                                    ...dataset
                                                }))
                                            };

                                            new Chart(ctx, {
                                                type: 'bar',
                                                data: data,
                                                options: {
                                                    responsive: true,
                                                    maintainAspectRatio: false,
                                                    scales: {
                                                        y: {
                                                            beginAtZero: true,
                                                            title: {
                                                                display: true,
                                                                text: 'Total HT'
                                                            }
                                                        },
                                                        x: {
                                                            title: {
                                                                display: true,
                                                                text: 'Año'
                                                            }
                                                        }
                                                    },
                                                    plugins: {
                                                        legend: {
                                                            position: 'top'
                                                        },
                                                        title: {
                                                            display: true,
                                                            text: 'Hojas Técnicas por Año'
                                                        },
                                                        datalabels: {
                                                            anchor: 'center',
                                                            align: 'center',
                                                            color: '#fff',
                                                            font: {
                                                                weight: 'bold',
                                                                size: 12
                                                            },
                                                            formatter: function(value) {
                                                                return value + ' HT';
                                                            }
                                                        }
                                                    }
                                                },
                                                plugins: [ChartDataLabels]
                                            });
                                        </script>
                                    </div>
                                </div>
                                <hr>

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

    <footer class="main-footer">
        <strong>Copyright &copy; 2022 - ∞. Derechos Reservados</strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
</div>

<!-- /.control-sidebar -->

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


</body>
</html>