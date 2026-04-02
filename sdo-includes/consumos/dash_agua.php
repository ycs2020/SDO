<?php
include_once '../../sdo-funciones/conexion.php';
include_once '../../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
$nowAnio = date('Y');

// 1. Obtener años disponibles de la tabla de Agua
$aniosDisponibles = [];
$resAnios = mysqli_query($conexion, "SELECT DISTINCT YEAR(fechaDocumento) AS anio FROM agua_administracion ORDER BY anio DESC");
while ($rowAnio = mysqli_fetch_assoc($resAnios)) {
    $aniosDisponibles[] = $rowAnio['anio'];
}
?>

    <div class="card card-outline card-info shadow">
        <div class="card-header d-flex p-0">
            <h3 class="card-title p-3 text-bold"><i class="fas fa-tint mr-2 text-info"></i> Dashboard Agua Potable</h3>
            <ul class="nav nav-pills ml-auto p-2" id="tab-anios-agua">
                <?php foreach ($aniosDisponibles as $index => $anio): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($index === 0) ? 'active' : ''; ?>" data-toggle="tab" href="#tabAgua<?php echo $anio; ?>"><?php echo $anio; ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="card-body bg-light">
            <div class="tab-content">
                <?php foreach ($aniosDisponibles as $index => $anio): ?>
                    <div class="tab-pane fade <?php echo ($index === 0) ? 'show active' : ''; ?>" id="tabAgua<?php echo $anio; ?>">
                        <div class="row">
                            <?php
                            // HBQP - Azul Info
                            echo generarColumnaAgua($conexion, $anio, 'HBQP', 'primary', 'rgba(0, 123, 255, 1)', 'rgba(0, 123, 255, 0.2)');
                            // HSMQ - Celeste/Teal
                            echo generarColumnaAgua($conexion, $anio, 'HSMQ', 'info', 'rgba(23, 162, 184, 1)', 'rgba(23, 162, 184, 0.2)');
                            // TOTAL - Verde
                            echo generarColumnaAgua($conexion, $anio, 'TOTAL', 'success', 'rgba(40, 167, 69, 1)', 'rgba(40, 167, 69, 0.2)');
                            ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

<?php
/**
 * Función Maestra para generar el bloque de Agua (Gráfico + Tabla)
 */
function generarColumnaAgua($conexion, $anio, $tipo, $clase, $colorBorde, $colorFondo) {
    $titulo = ($tipo == 'TOTAL') ? "TOTAL AMBOS HOSPITALES" : "RESUMEN $tipo - $anio";
    $idGrafico = "chart_agua_" . $tipo . "_" . $anio;

    $labels = [];
    $dataConsumo = [];
    $rowsHtml = '';
    $sumMt3 = 0; $sumMonto = 0;

    for ($i = 1; $i <= 12; $i++) {
        $mesNombre = obtenerMes($i);
        $labels[] = $mesNombre;
        $f = "$anio-$i";

        // Llamada a tus funciones específicas de AGUA
        if ($tipo == 'HBQP') {
            $k = obtenerConsumoMesAgua($f);
            $m = obtenerMontodelMesAgua($f);
        } elseif ($tipo == 'HSMQ') {
            $k = obtenerSumaTotalAguaHSMQ($f);
            $m = obtenerSumaTotalMontoAguaHSMQ($f);
        } else {
            $k = obtenerSumaConsumodeTodoAgua($f);
            $m = obtenerSumaTotaldeTodoAgua($f);
        }

        $dataConsumo[] = $k;
        $sumMt3 += $k; $sumMonto += $m;

        $rowsHtml .= "<tr>
            <td class='p-1 pl-2'>$mesNombre</td>
            <td class='text-right p-1'>" . number_format($k, 0, ",", ".") . "</td>
            <td class='text-right p-1 pr-2'>$" . number_format($m, 0, ",", ".") . "</td>
        </tr>";
    }

    ob_start(); ?>
    <div class="col-12 col-xl-4 mb-3">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-<?php echo $clase; ?> py-2 text-center">
                <h3 class="card-title text-sm" style="float:none;"><b><?php echo $titulo; ?></b></h3>
            </div>
            <div class="card-body p-2">
                <div style="position: relative; height:200px; width:100%;">
                    <canvas id="<?php echo $idGrafico; ?>"></canvas>
                </div>

                <div class="table-responsive mt-3">
                    <table class="table table-sm table-valign-middle table-hover border">
                        <thead class="bg-dark text-xs text-center">
                        <tr>
                            <th style="width: 35%">MES</th>
                            <th style="width: 30%">MT³</th>
                            <th style="width: 35%">MONTO (IVA)</th>
                        </tr>
                        </thead>
                        <tbody class="text-xs">
                        <?php echo $rowsHtml; ?>
                        </tbody>
                        <tfoot class="bg-light text-bold text-xs text-right">
                        <tr>
                            <td class="text-center">TOTAL</td>
                            <td><?php echo number_format($sumMt3, 0, ",", "."); ?></td>
                            <td>$<?php echo number_format($sumMonto, 0, ",", "."); ?></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function () {
            var ctx = document.getElementById('<?php echo $idGrafico; ?>').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($labels); ?>,
                    datasets: [{
                        label: 'Consumo Mt3',
                        data: <?php echo json_encode($dataConsumo); ?>,
                        borderColor: '<?php echo $colorBorde; ?>',
                        backgroundColor: '<?php echo $colorFondo; ?>',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    legend: { display: false },
                    scales: {
                        xAxes: [{ gridLines: { display: false }, ticks: { fontSize: 10 } }],
                        yAxes: [{ ticks: { beginAtZero: true, fontSize: 10 } }]
                    }
                }
            });
        });
    </script>
    <?php
    return ob_get_clean();
}
?>