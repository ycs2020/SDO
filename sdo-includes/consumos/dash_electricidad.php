<?php
include_once '../../sdo-funciones/conexion.php';
include_once '../../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
$nowAnio = date('Y');

$aniosDisponibles = [];
$resAnios = mysqli_query($conexion, "SELECT DISTINCT YEAR(fechaDocumento) AS anio FROM electricidad_administracion ORDER BY anio DESC");
while ($rowAnio = mysqli_fetch_assoc($resAnios)) { $aniosDisponibles[] = $rowAnio['anio']; }
?>

    <div class="card card-outline card-primary shadow">
        <div class="card-header d-flex p-0">
            <h3 class="card-title p-3 text-bold"><i class="fas fa-bolt mr-2 text-warning"></i> Dashboard Eléctrico</h3>
            <ul class="nav nav-pills ml-auto p-2" id="tab-anios-dash">
                <?php foreach ($aniosDisponibles as $index => $anio): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($index === 0) ? 'active' : ''; ?>" data-toggle="tab" href="#tabElect<?php echo $anio; ?>"><?php echo $anio; ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="card-body bg-light">
            <div class="tab-content">
                <?php foreach ($aniosDisponibles as $index => $anio): ?>
                    <div class="tab-pane fade <?php echo ($index === 0) ? 'show active' : ''; ?>" id="tabElect<?php echo $anio; ?>">
                        <div class="row">
                            <?php
                            // Columna HBQP - Azul
                            echo generarColumnaHospital($conexion, $anio, 'HBQP', 'primary', 'rgba(0, 123, 255, 1)', 'rgba(0, 123, 255, 0.2)');
                            // Columna HSMQ - Cyan/Teal
                            echo generarColumnaHospital($conexion, $anio, 'HSMQ', 'info', 'rgba(23, 162, 184, 1)', 'rgba(23, 162, 184, 0.2)');
                            // Columna TOTAL - Verde
                            echo generarColumnaHospital($conexion, $anio, 'TOTAL', 'success', 'rgba(40, 167, 69, 1)', 'rgba(40, 167, 69, 0.2)');
                            ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

<?php
function generarColumnaHospital($conexion, $anio, $tipo, $clase, $colorBorde, $colorFondo) {
    $titulo = ($tipo == 'TOTAL') ? "TOTAL AMBOS HOSPITALES" : "RESUMEN $tipo - $anio";
    $idGrafico = "chart_" . $tipo . "_" . $anio;

    $labels = [];
    $dataConsumo = [];
    $rowsHtml = '';
    $sumKwh = 0; $sumMonto = 0;

    for ($i = 1; $i <= 12; $i++) {
        $mesNombre = obtenerMes($i);
        $labels[] = $mesNombre;
        $f = "$anio-$i";

        // Lógica de datos según tipo
        if ($tipo == 'HBQP') { $k = obtenerConsumoMesEnergia($f); $m = obtenerMontodelMes($f); }
        elseif ($tipo == 'HSMQ') { $k = obtenerSumaTotalHSMQ($f); $m = obtenerSumaTotalMontoHSMQ($f); }
        else { $k = obtenerSumaCosumodeTodo($f); $m = obtenerSumaTotaldeTodo($f); }

        $dataConsumo[] = $k;
        $sumKwh += $k; $sumMonto += $m;

        $rowsHtml .= "<tr>
            <td class='p-1 pl-2'>$mesNombre</td>
            <td class='text-right p-1'>" . number_format($k, 0, ",", ".") . "</td>
            <td class='text-right p-1 pr-2'>$" . number_format($m, 0, ",", ".") . "</td>
        </tr>";
    }

    ob_start(); ?>
    <div class="col-12 col-xl-4 mb-3">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-<?php echo $clase; ?> py-2">
                <h3 class="card-title text-sm" style="width:100%; text-align:center;"><b><?php echo $titulo; ?></b></h3>
            </div>
            <div class="card-body p-2">
                <div style="position: relative; height:220px; width:100%;">
                    <canvas id="<?php echo $idGrafico; ?>"></canvas>
                </div>

                <div class="table-responsive mt-3">
                    <table class="table table-sm table-valign-middle table-hover border">
                        <thead class="bg-dark text-xs text-center">
                        <tr>
                            <th style="width: 35%">MES</th>
                            <th style="width: 30%">KWH</th>
                            <th style="width: 35%">MONTO (IVA)</th>
                        </tr>
                        </thead>
                        <tbody class="text-xs">
                        <?php echo $rowsHtml; ?>
                        </tbody>
                        <tfoot class="bg-light text-bold text-xs text-right">
                        <tr>
                            <td class="text-center">TOTAL</td>
                            <td><?php echo number_format($sumKwh, 0, ",", "."); ?></td>
                            <td>$<?php echo number_format($sumMonto, 0, ",", "."); ?></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Usamos un timeout para asegurar que el DOM esté listo tras el AJAX
        setTimeout(function() {
            var canvas = document.getElementById('<?php echo $idGrafico; ?>');
            if (canvas) {
                var ctx = canvas.getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: <?php echo json_encode($labels); ?>,
                        datasets: [{
                            label: 'Consumo kWh',
                            data: <?php echo json_encode($dataConsumo); ?>,
                            borderColor: '<?php echo $colorBorde; ?>',
                            backgroundColor: '<?php echo $colorFondo; ?>',
                            borderWidth: 2,
                            pointRadius: 3,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        legend: { display: false },
                        scales: {
                            yAxes: [{ ticks: { beginAtZero: true, fontSize: 10 } }],
                            xAxes: [{ ticks: { fontSize: 9 } }]
                        }
                    }
                });
            }
        }, 300); // 300ms de espera son suficientes
    </script>

    <?php
    return ob_get_clean();
}
?>