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
            height: 150px !important; /* puedes ajustar */
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
                                <div class="card-header d-flex p-0">
                                    <h3 class="card-title p-3">Dashboard de Informaci&oacute;n</h3>
                                    <ul class="nav nav-pills ml-auto p-2">
                                        <?php
                                        date_default_timezone_set('America/Santiago');
                                        $nowAnioHT = date('Y', time());
                                        $sqlAniosHT = "SELECT DISTINCT YEAR(fechaOrden) AS anio FROM ordenes_trabajos_general ORDER BY fechaOrden ASC;";
                                        $resAniosHT = mysqli_query($conexion, $sqlAniosHT);
                                        while ($rowHT = $resAniosHT->fetch_assoc()) {
                                            $activeClass = ($rowHT['anio'] == $nowAnioHT) ? 'active' : '';
                                            echo '<li class="nav-item"><a class="nav-link ' . $activeClass . '" href="#tabHT' . $rowHT['anio'] . '" data-toggle="tab">' . $rowHT['anio'] . '</a></li>';
                                        }
                                        ?>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <?php
                                        date_default_timezone_set('America/Santiago');
                                        $nowAnioHT = date('Y', time());

                                        $sqlAniosHT = "SELECT DISTINCT YEAR(fechaOrden) AS anio FROM ordenes_trabajos_general ORDER BY fechaOrden ASC;";
                                        $resAniosHT = mysqli_query($conexion, $sqlAniosHT);
                                        while ($rowHT = $resAniosHT->fetch_assoc()) {
                                            $anioHT = $rowHT['anio'];

                                            $anioAnteriorHT = $anioHT - 1;
                                            $nowMesHT = date('m', time());
                                            if ($anioHT == $nowAnioHT) {
                                                $tabPane = 'active';
                                            } else {
                                                $tabPane = '';
                                            }
                                            ?>

                                            <div class="tab-pane <?php echo $tabPane; ?>" id="tabHT<?php echo $rowHT['anio']; ?>">
<!--                                                Cantidad de Hojas Tecnicas --><?php //echo $rowHT['anio']; ?>
                                                <div class="row justify-content-center">
                                                    <div class="col-12 col-lg-8 order-lg-1 order-1">
                                                        <div class="table-responsive">

                                                            <table class="table table-hover table-sm" style="font-size: 11pt;">
                                                                <thead class="bg-success">
                                                                <tr>
                                                                    <th scope="col">Departamento</th>
                                                                    <?php
                                                                    for ($mes = 1; $mes < 13; $mes++) {
                                                                        ?>
                                                                        <th scope="col" class="text-center"><?php echo mesEspañol3($mes); ?></th>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                    <th scope="col" class="text-center">Total <?php echo $anioHT; ?></th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php
//                                                                $departamentosVisibles = [
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

                                                                $departamentosVisibles = [];

                                                                switch ($idDepartamento) {
                                                                    case 1:
                                                                        $departamentosVisibles["Instrumentación"] = "Control Centralizado";
                                                                        break;

                                                                    case 2:
                                                                        $departamentosVisibles["Equipos Industriales"] = "Equipos Industriales";
                                                                        $departamentosVisibles["Incendio y Seguridad"] = "Incendio y Seguridad";
                                                                        break;

                                                                    case 3:
                                                                        $departamentosVisibles["Inst. Térmicas, Climatización y Refrigeración"] = "Climatización";
                                                                        break;

                                                                    case 4:
                                                                        $departamentosVisibles["Electricidad"] = "Electricidad";
                                                                        break;

                                                                    case 5:
                                                                        $departamentosVisibles["Infraestructura"] = "Infraestructura";
                                                                        break;

                                                                    case 10:
                                                                        $departamentosVisibles["Equipos de Apoyo Clínico"] = "Equipos de Apoyo Clínico";
                                                                        break;

                                                                    case 12:
                                                                        $departamentosVisibles["Incendio y Seguridad"] = "Incendio y Seguridad";
                                                                        break;

                                                                    default:
                                                                        // No hacer nada
                                                                        break;
                                                                }

                                                                asort($departamentosVisibles);

                                                                $departamentosEscapados = array_map(function($depto) use ($conexion) {
                                                                    return "'" . mysqli_real_escape_string($conexion, $depto) . "'";
                                                                }, array_keys($departamentosVisibles));

                                                                $listaDeptos = implode(",", $departamentosEscapados);

                                                                $sqlDeptoT = "SELECT * FROM departamentos WHERE nomDepartamento IN ($listaDeptos) AND nomDepartamento NOT LIKE '%Control Centralizado%'";
                                                                $resDeptoT = mysqli_query($conexion, $sqlDeptoT);

                                                                $datosDeptos = [];
                                                                while ($row = $resDeptoT->fetch_assoc()) {
                                                                    $datosDeptos[$row['nomDepartamento']] = $row;
                                                                }

                                                                // Inicializar acumulador por mes
                                                                $totalesMes = array_fill(1, 12, 0);
                                                                $granTotal = 0;

                                                                $totalesPorDepartamento = [];

                                                                foreach ($departamentosVisibles as $nomReal => $nomMostrar) {
                                                                    if (!isset($datosDeptos[$nomReal])) continue;

                                                                    $rowDeptoT = $datosDeptos[$nomReal];
                                                                    $idDepartamentoT = $rowDeptoT['idDepartamento'];

                                                                    echo "<tr>";
                                                                    echo "<th scope='row'>" . $nomMostrar . "</th>";

                                                                    $totalAnual = 0;

                                                                    for ($mesOT = 1; $mesOT <= 12; $mesOT++) {
                                                                        $mesHT = str_pad($mesOT, 2, "0", STR_PAD_LEFT);
                                                                        $fechaT = $anioHT . '-' . $mesHT;

                                                                        $contarHojasTecnicasMesAnio = contarHojasTecnicasMesAnio($idDepartamentoT, $fechaT);
                                                                        $valorMostrar = ($contarHojasTecnicasMesAnio < 1) ? '-' : $contarHojasTecnicasMesAnio;

                                                                        if (is_numeric($contarHojasTecnicasMesAnio) && $contarHojasTecnicasMesAnio > 0) {
                                                                            $totalesMes[$mesOT] += $contarHojasTecnicasMesAnio;
                                                                            $totalAnual += $contarHojasTecnicasMesAnio;
                                                                        }

                                                                        echo "<td class='text-center'>{$valorMostrar}</td>";
                                                                    }

                                                                    $granTotal += $totalAnual;



                                                                    echo "<td class='text-center text-bold'>" . $totalAnual . "</td>";
                                                                    echo "</tr>";

                                                                    $totalesPorDepartamento[$nomMostrar] = $totalAnual;
                                                                }


                                                                ?>
                                                                </tbody>

                                                                <tfoot class="bg-info">
                                                                <tr>
                                                                    <th class="text-end text-bold">Total por mes</th>
                                                                    <?php
                                                                    for ($i = 1; $i <= 12; $i++) {
                                                                        echo "<th class='text-center text-bold'>" . ($totalesMes[$i] > 0 ? $totalesMes[$i] : '-') . "</th>";
                                                                    }
                                                                    echo "<th class='text-center text-bold'>" . $granTotal . "</th>";
                                                                    ?>
                                                                </tr>
                                                                </tfoot>

                                                            </table>
                                                        </div>
                                                    </div>


                                                    <div class="col-12 col-lg-4 order-lg-2 order-2 d-flex justify-content-center align-items-center mt-4 mt-lg-0">
                                                        <canvas class="grafico-ht" id="graficoDepartamentos<?php echo $anioHT; ?>"></canvas>

                                                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                                                        <script>
                                                            document.addEventListener('DOMContentLoaded', function () {
                                                                const anio = <?php echo json_encode($rowHT['anio']); ?>;

                                                                const ctx = document.getElementById('graficoDepartamentos' + anio);
                                                                if (!ctx) return; // si no existe el canvas, no hace nada

                                                                new Chart(ctx.getContext('2d'), {
                                                                    type: 'bar',
                                                                    data: {
                                                                        labels: <?php echo json_encode(array_keys($totalesPorDepartamento)); ?>,
                                                                        datasets: [{
                                                                            label: 'Hojas Técnicas por Departamento - Año ' + anio,
                                                                            data: <?php echo json_encode(array_values($totalesPorDepartamento)); ?>,
                                                                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                                                                            borderColor: 'rgba(54, 162, 235, 1)',
                                                                            borderWidth: 1
                                                                        }]
                                                                    },
                                                                    options: {
                                                                        responsive: true,
                                                                        maintainAspectRatio: false, // <-- esto permite que el height CSS sea respetado
                                                                        plugins: {
                                                                            legend: { display: false }
                                                                        },
                                                                        scales: {
                                                                            y: {
                                                                                beginAtZero: true,
                                                                                title: { display: true, text: 'Cantidad HT' }
                                                                            },
                                                                            x: {
                                                                                title: { display: true, text: 'Departamento' }
                                                                            }
                                                                        }
                                                                    }
                                                                });
                                                            });
                                                        </script>

                                                    </div>

                                                    <hr>


                                                </div>

                                                <hr>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
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