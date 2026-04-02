<?php
include_once '../../sdo-funciones/conexion.php';
include_once '../../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
$nowAnio = date('Y');
$nowMes = date('m');

// 1. Obtener años disponibles
$aniosDisponibles = [];
$resAnios = mysqli_query($conexion, "SELECT DISTINCT YEAR(fechaAC) AS anio FROM combustible_administracion ORDER BY anio DESC");
while ($rowAnio = mysqli_fetch_assoc($resAnios)) {
    $aniosDisponibles[] = $rowAnio['anio'];
}
?>

    <div class="card card-outline card-success shadow">
        <div class="card-header d-flex p-0">
            <h3 class="card-title p-3 text-bold text-success"><i class="fas fa-gas-pump mr-2"></i> Dashboard Combustible Diesel</h3>
            <ul class="nav nav-pills ml-auto p-2">
                <?php foreach ($aniosDisponibles as $index => $anio): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($index === 0) ? 'active' : ''; ?>" data-toggle="tab" href="#tabComb<?php echo $anio; ?>"><?php echo $anio; ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="card-body bg-light">
            <div class="tab-content">
                <?php foreach ($aniosDisponibles as $index => $anio):
                    $esAnioActual = ($anio == $nowAnio);
                    ?>
                    <div class="tab-pane fade <?php echo ($index === 0) ? 'show active' : ''; ?>" id="tabComb<?php echo $anio; ?>">

                        <div class="row">
                            <div class="col-12 mb-4">
                                <div class="card shadow-sm border-0">
                                    <div class="card-header bg-success py-2 text-center">
                                        <h3 class="card-title text-sm" style="float:none;"><b>EVOLUCIÓN CONSUMO TOTAL DIESEL (LTS) - <?php echo $anio; ?></b></h3>
                                    </div>
                                    <div class="card-body">
                                        <div style="position: relative; height:250px; width:100%;">
                                            <canvas id="chart_comb_<?php echo $anio; ?>"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-xl-4 mb-3">
                                <?php echo generarCardCombustible($conexion, $anio, 'consumo', 'success', $nowMes, $esAnioActual); ?>
                            </div>

                            <div class="col-12 col-xl-4 mb-3">
                                <?php echo generarCardCombustible($conexion, $anio, 'valor', 'info', $nowMes, $esAnioActual); ?>
                            </div>

                            <div class="col-12 col-xl-4 mb-3">
                                <?php echo generarCardCombustible($conexion, $anio, 'variacion', 'warning', $nowMes, $esAnioActual); ?>
                            </div>
                        </div>
                    </div>

                    <script>
                        $(function () {
                            <?php
                            $labels = []; $dataLine = [];
                            for ($i = 1; $i <= 12; $i++) {
                                $labels[] = obtenerMes($i);
                                $f = "$anio-$i";
                                $cons = -1 * (obtenerConsumoMesT1($f) + obtenerConsumoMesT2($f) + obtenerConsumoMesT3($f) + obtenerConsumoMesT4($f));
                                $dataLine[] = $cons;
                            }
                            ?>
                            var ctx = document.getElementById('chart_comb_<?php echo $anio; ?>').getContext('2d');
                            new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: <?php echo json_encode($labels); ?>,
                                    datasets: [{
                                        label: 'Litros Consumidos',
                                        data: <?php echo json_encode($dataLine); ?>,
                                        borderColor: '#28a745',
                                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                                        fill: true,
                                        tension: 0.4
                                    }]
                                },
                                options: { maintainAspectRatio: false, responsive: true, legend: {display: false} }
                            });
                        });
                    </script>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

<?php
/**
 * Función para generar las tablas de combustible
 */
function generarCardCombustible($conexion, $anio, $modo, $clase, $nowMes, $esAnioActual) {
    $titulos = [
        'consumo' => 'CONSUMO (LTS)',
        'valor' => 'GASTO TOTAL ($)',
        'variacion' => 'VARIACIÓN VS AÑO ANTERIOR (' . ($anio - 1) . ')'
    ];
    $titulo = $titulos[$modo];

    ob_start(); ?>
    <div class="card h-100 shadow-sm border-0">
        <div class="card-header bg-<?php echo $clase; ?> py-2 text-center text-xs"><b><?php echo $titulo; ?></b></div>
        <div class="card-body p-0">
            <table class="table table-sm table-striped mb-0" style="font-size: 11px;">
                <thead class="bg-dark">
                <?php if ($modo == 'variacion'): ?>
                    <tr>
                        <th style="width: 25%">MES</th>
                        <th class="text-right" style="width: 75%">COMPARATIVA (LTS) Y %</th>
                    </tr>
                <?php else: ?>
                    <tr>
                        <th>MES</th>
                        <th class="text-right">VALOR</th>
                    </tr>
                <?php endif; ?>
                </thead>
                <tbody>
                <?php
                $totalAnual = 0;
                $totalAnualAnt = 0;
                for ($i = 1; $i <= 12; $i++) {
                    if ($esAnioActual && $i > $nowMes) break;

                    $f = "$anio-$i";
                    $fAnt = ($anio - 1) . "-" . $i;

                    // Consumo Año Actual
                    $c = -1 * (obtenerConsumoMesT1($f) + obtenerConsumoMesT2($f) + obtenerConsumoMesT3($f) + obtenerConsumoMesT4($f));

                    if ($modo == 'consumo') {
                        $valorOut = number_format($c, 0, ",", ".") . " Lts";
                        $totalAnual += $c;
                    } elseif ($modo == 'valor') {
                        $vProm = obtenerPromedioACMes2($f);
                        $vTotal = round($vProm * $c);
                        $valorOut = "$ " . number_format($vTotal, 0, ",", ".");
                        $totalAnual += $vTotal;
                    } else {
                        // Consumo Año Anterior
                        $cAnt = -1 * (obtenerConsumoMesT1($fAnt) + obtenerConsumoMesT2($fAnt) + obtenerConsumoMesT3($fAnt) + obtenerConsumoMesT4($fAnt));

                        $totalAnual += $c;
                        $totalAnualAnt += $cAnt;

                        $porcent = ($cAnt > 0) ? (($c - $cAnt) / $cAnt) * 100 : 0;

                        // Estética del porcentaje
                        $colorBadge = ($porcent > 0) ? 'badge-danger' : (($porcent < 0) ? 'badge-primary' : 'badge-secondary');
                        $signo = ($porcent > 0) ? '+' : '';

                        $valorOut = "<span class='text-muted'>" . number_format($cAnt, 0, ",", ".") . " <i class='fas fa-arrow-right mx-1'></i> </span>";
                        $valorOut .= "<b>" . number_format($c, 0, ",", ".") . "</b> ";
                        $valorOut .= "<span class='badge $colorBadge ml-1'>" . $signo . round($porcent, 1) . "%</span>";
                    }
                    echo "<tr><td class='pl-2'>" . obtenerMes($i) . "</td><td class='text-right pr-2'>$valorOut</td></tr>";
                } ?>
                </tbody>
                <tfoot class="bg-secondary">
                <?php if ($modo == 'variacion'):
                    $porcentTotal = ($totalAnualAnt > 0) ? (($totalAnual - $totalAnualAnt) / $totalAnualAnt) * 100 : 0;
                    $signoTotal = ($porcentTotal > 0) ? '+' : '';
                    ?>
                    <tr>
                        <th class="pl-2">TOTAL</th>
                        <th class="text-right pr-2">
                            <small>Ant: <?php echo number_format($totalAnualAnt, 0, ",", "."); ?></small> |
                            <?php echo number_format($totalAnual, 0, ",", "."); ?>
                            <span class="ml-1 text-warning"><?php echo $signoTotal . round($porcentTotal, 1); ?>%</span>
                        </th>
                    </tr>
                <?php else: ?>
                    <tr>
                        <th class="pl-2">TOTAL</th>
                        <th class="text-right pr-2"><?php echo ($modo == 'consumo' ? '' : '$ ') . number_format($totalAnual, 0, ",", "."); ?></th>
                    </tr>
                <?php endif; ?>
                </tfoot>
            </table>
        </div>
    </div>
    <?php return ob_get_clean();
}
?>