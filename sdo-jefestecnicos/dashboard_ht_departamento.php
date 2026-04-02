<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');

?>


<!DOCTYPE html>
<html lang="es">

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

    <!-- Librerías Chart.js y DataLabels -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
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

                <!-- CANTIDAD HOJAS TECNICAS AÑO -->
                <div class="row justify-content-center">
                    <?php
                    // Definir agrupaciones visibles (nombre => array de idDepartamento)
                    function obtenerIdsRelacionados($idDepartamento, $agrupaciones)
                    {
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
                        "Control Centralizado" => [1, 13],
                        "Equipos Industriales" => [2],
                        "Incendio y Seguridad" => [2, 12],
                        "Climatización" => [3],
                        "Electricidad" => [4],
                        "Infraestructura" => [5],
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
                    $i = 0;
                    foreach ($datosAgrupados as $depto => $anios) {
                        $data = [];
                        foreach ($labels as $anio) {
                            $data[] = isset($anios[$anio]) ? $anios[$anio] : 0;
                        }

                        $color = ($conteoIDsPorGrupo[$depto] > 1)
                            ? 'rgba(255, 99, 132, 0.7)'   // rojo
                            : 'rgba(54, 162, 235, 0.7)';  // azul

                        $border = str_replace('0.7', '1', $color);
                        $idCanvas = 'grafico_' . $i;
                        ?>
                        <!-- Cada gráfico en su propia columna -->
                        <div class="col-12 col-md-6 mb-4">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title text-center"><?= $depto ?></h5>
                                    <div style="height:300px;">
                                        <canvas id="<?= $idCanvas ?>"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Script individual para cada gráfico -->
                        <script>
                            document.addEventListener("DOMContentLoaded", function () {
                                const ctx<?= $i ?> = document.getElementById("<?= $idCanvas ?>").getContext("2d");
                                new Chart(ctx<?= $i ?>, {
                                    type: 'bar',
                                    data: {
                                        labels: <?= json_encode($labels) ?>,
                                        datasets: [{
                                            label: "<?= $depto ?>",
                                            data: <?= json_encode($data) ?>,
                                            backgroundColor: "<?= $color ?>",
                                            borderColor: "<?= $border ?>",
                                            borderWidth: 1,
                                            minBarLength: 5 // fuerza altura mínima para barras pequeñas
                                        }]
                                    },
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
                                            legend: {display: false},
                                            title: {
                                                display: true,
                                                text: 'Hojas Técnicas por Año',
                                                padding: {
                                                    bottom: 40 // o el valor que necesites
                                                },
                                            },
                                            datalabels: {
                                                anchor: 'end',
                                                align: 'top',
                                                color: '#000',
                                                font: {
                                                    weight: 'bold',
                                                    size: 12
                                                },
                                                formatter: (value) => value + ' HT'
                                            }
                                        }
                                    },
                                    plugins: [ChartDataLabels]
                                });
                            });
                        </script>
                        <?php
                        $i++;
                    }
                    ?>

                </div>
                <!-- FIN CANTIDAD HOJAS TECNICAS AÑO -->
                <hr>
                <!-- CANTIDAD HOJAS TECNICAS MANTENIMIENTO -->
                <div class="row justify-content-center">
                    <?php
                    $agrupaciones = [
                        "Control Centralizado" => [1, 13],
                        "Equipos Industriales" => [2],
                        "Incendio y Seguridad" => [2, 12],
                        "Climatización" => [3],
                        "Electricidad" => [4],
                        "Infraestructura" => [5],
                        "Equipos de Apoyo Clínico" => [10]
                    ];

                    function obtenerIdsRelacionadosMTTO($idDepartamento, $agrupaciones) {
                        $ids = [];
                        foreach ($agrupaciones as $idsGrupo) {
                            if (in_array($idDepartamento, $idsGrupo)) {
                                $ids = array_merge($ids, $idsGrupo);
                            }
                        }
                        return array_unique($ids);
                    }

                    function obtenerDepartamentosVisiblesMTTO($id, $agrupaciones) {
                        $resultados = [];
                        foreach ($agrupaciones as $nombre => $ids) {
                            if (in_array($id, $ids)) {
                                $resultados[] = $nombre;
                            }
                        }
                        return $resultados;
                    }

                    $idsRelacionados = obtenerIdsRelacionadosMTTO($idDepartamento, $agrupaciones);
                    $listaDepartamentos = implode(',', $idsRelacionados);

                    $sqlDepas = "
    SELECT idDepartamento, YEAR(fechaOrden) AS anio, tipoMantencion, COUNT(*) AS total_ordenes
    FROM ordenes_trabajos_general
    WHERE anulada = 0 
      AND fechaOrden IS NOT NULL 
      AND idDepartamento IN ($listaDepartamentos)
    GROUP BY idDepartamento, anio, tipoMantencion
    ORDER BY anio, idDepartamento, tipoMantencion
";

                    $resultDepas = mysqli_query($conexion, $sqlDepas);

                    // Organizar por tipoMantencion > agrupación visible > año
                    $datosPorTipo = [];
                    $agrupacionesVisibles = [];

                    $labelsAnios = [];

                    while ($row = mysqli_fetch_assoc($resultDepas)) {
                        $anio = $row['anio'];
                        $idDep = $row['idDepartamento'];
                        $tipo = trim($row['tipoMantencion']) ?: 'SIN_TIPO';
                        $total = $row['total_ordenes'];

                        $labelsAnios[] = $anio;

                        $depasVisibles = obtenerDepartamentosVisiblesMTTO($idDep, $agrupaciones);
                        foreach ($depasVisibles as $nombreGrupo) {
                            $agrupacionesVisibles[$nombreGrupo] = true;
                            $datosPorTipo[$tipo][$nombreGrupo][$anio] = ($datosPorTipo[$tipo][$nombreGrupo][$anio] ?? 0) + $total;
                        }
                    }

                    $labelsAnios = array_unique($labelsAnios);
                    sort($labelsAnios);
                    $agrupacionesVisibles = array_keys($agrupacionesVisibles);
                    sort($agrupacionesVisibles);

                    // Colores por agrupación visible (puedes personalizar más)
                    $coloresAgrupaciones = [
                        "Control Centralizado" => "rgba(54, 162, 235, 0.7)",
                        "Equipos Industriales" => "rgba(54, 162, 235, 0.7)",
                        "Incendio y Seguridad" => "rgba(255, 99, 132, 0.7)",
                        "Climatización" => "rgba(75, 192, 192, 0.7)",
                        "Electricidad" => "rgba(153, 102, 255, 0.7)",
                        "Infraestructura" => "rgba(255, 159, 64, 0.7)",
                        "Equipos de Apoyo Clínico" => "rgba(199, 199, 199, 0.7)",
                        "SIN_TIPO" => "rgba(100,100,100,0.7)"
                    ];

                    $i = 0;
                    foreach ($datosPorTipo as $tipoMantencion => $grupos) {
                        $datasetsJS = [];

                        foreach ($agrupacionesVisibles as $grupo) {
                            $data = [];
                            foreach ($labelsAnios as $anio) {
                                $data[] = $grupos[$grupo][$anio] ?? 0;
                            }

                            $color = $coloresAgrupaciones[$grupo] ?? 'rgba(100,100,100,0.7)';
                            $border = str_replace('0.7', '1', $color);

                            $datasetsJS[] = [
                                'label' => $grupo,
                                'data' => $data,
                                'backgroundColor' => $color,
                                'borderColor' => $border,
                                'borderWidth' => 1
                            ];
                        }

                        $idCanvas = 'graficoMtto_' . $i;
                        ?>
                        <div class="col-12 col-lg-3 col-md-4 mb-4">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <?php

                                    $tipoMantencion2 = '';

                                    switch ($tipoMantencion) {
                                        case 'SIN_TIPO':
                                            $tipoMantencion2 = 'Sin Tipo de Mantención';
                                            break;
                                        case 'IS':
                                            $tipoMantencion2 = 'IS - Inspección y Supervisión';
                                            break;
                                        case 'MPE':
                                            $tipoMantencion2 = 'MPE - Mantenimiento Preventivo Externo';
                                            break;
                                        case 'MPI':
                                            $tipoMantencion2 = 'MPI - Mantenimiento Preventivo Interno';
                                            break;
                                        case 'MRE':
                                            $tipoMantencion2 = 'MRE - Mantenimiento Reparativo Externo';
                                            break;
                                        case 'MRI':
                                            $tipoMantencion2 = 'MRI - Mantenimiento Reparativo Interno';
                                            break;
                                        case 'OM':
                                            $tipoMantencion2 = 'OM - Obras Menores';
                                            break;
                                        case 'MS':
                                            $tipoMantencion2 = 'MS - Mejoras del Sistema';
                                            break;
                                    }

                                    ?>
                                    <h5 class="card-title text-center"><?= $tipoMantencion2 ?></h5>
                                    <div style="height:300px;">
                                        <canvas id="<?= $idCanvas ?>"></canvas>
                                    </div>

                                    &nbsp;<br>
                                </div>
                            </div>
                        </div>

                        <script>
                            document.addEventListener("DOMContentLoaded", function () {
                                const ctx<?= $i ?> = document.getElementById("<?= $idCanvas ?>").getContext("2d");
                                new Chart(ctx<?= $i ?>, {
                                    type: 'bar',
                                    data: {
                                        labels: <?= json_encode($labelsAnios) ?>,
                                        datasets: <?= json_encode($datasetsJS) ?>
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        layout: {
                                            padding: {
                                                top: 30 // espacio extra superior
                                            }
                                        },
                                        scales: {
                                            x: {
                                                stacked: true,
                                                title: {
                                                    display: true,
                                                    text: 'Año'
                                                }
                                            },
                                            y: {
                                                stacked: true,
                                                beginAtZero: true,
                                                title: {
                                                    display: true,
                                                    text: 'Total HT'
                                                }
                                            }
                                        },
                                        plugins: {
                                            title: {
                                                display: true,
                                                text: 'Hojas Técnicas por Tipo de Mantención',
                                                padding: {
                                                    bottom: 40 // espacio entre el título y las barras
                                                },
                                                font: {
                                                    size: 16
                                                }
                                            },
                                            legend: {
                                                position: 'bottom'
                                            },
                                            datalabels: {
                                                anchor: 'end',
                                                align: 'top',
                                                color: '#000',
                                                font: {
                                                    weight: 'bold',
                                                    size: 11
                                                },
                                                formatter: (value) => value > 0 ? value.toLocaleString('es-CL') : ''
                                            }
                                        }
                                    },
                                    plugins: [ChartDataLabels]
                                });
                            });
                        </script>

                        <?php
                        $i++;
                    }
                    ?>
                </div>

                <!-- FIN CANTIDAD HOJAS TECNICAS MANTENIMIENTO -->

            </div>
            <!-- /.row -->

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