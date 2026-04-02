<?php
include_once '../../sdo-funciones/conexion.php';
include_once '../../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
$nowAnio = date('Y');

// 1. Obtener años disponibles de Gases Clínicos
$aniosDisponibles = [];
$resAnios = mysqli_query($conexion, "SELECT DISTINCT YEAR(fechaIngreso) AS anio FROM gases_clinicos ORDER BY anio DESC");
while ($rowAnio = mysqli_fetch_assoc($resAnios)) {
    $aniosDisponibles[] = $rowAnio['anio'];
}
?>

<div class="card card-outline shadow" style="border-top: 3px solid #ff653f;">
    <div class="card-header d-flex p-0">
        <h3 class="card-title p-3 text-bold" style="color: #ff653f;">
            <i class="fas fa-cloud mr-2"></i> Dashboard Gases Clínicos
        </h3>
        <ul class="nav nav-pills ml-auto p-2" id="tab-anios-gases">
            <?php foreach ($aniosDisponibles as $index => $anio): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($index === 0) ? 'active' : ''; ?>"
                        style="<?php echo ($index === 0) ? 'background-color: #ff653f;' : ''; ?>"
                        data-toggle="tab" href="#tabGases<?php echo $anio; ?>">
                        <?php echo $anio; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="card-body bg-light">
        <div class="tab-content">
            <?php foreach ($aniosDisponibles as $index => $anio): ?>
                <div class="tab-pane fade <?php echo ($index === 0) ? 'show active' : ''; ?>" id="tabGases<?php echo $anio; ?>">
                    <div class="row justify-content-center">

                        <div class="col-12 col-xl-8 mb-3">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-header text-white py-2" style="background-color: #ff653f;">
                                    <h3 class="card-title text-sm" style="float:none; text-align:center;">
                                        <b>CONSUMO Y MONTOS GASES CLÍNICOS - <?php echo $anio; ?></b>
                                    </h3>
                                </div>
                                <div class="card-body p-3">
                                    <div style="position: relative; height:250px; width:100%;">
                                        <canvas id="chart_gases_<?php echo $anio; ?>"></canvas>
                                    </div>

                                    <div class="table-responsive mt-4">
                                        <table class="table table-sm table-hover border">
                                            <thead class="text-white text-xs text-center" style="background-color: #ff653f;">
                                            <tr>
                                                <th style="width: 30%">MESES</th>
                                                <th style="width: 35%">VALOR NETO (CILINDROS)</th>
                                                <th style="width: 35%">MONTO TOTAL (IVA INC.)</th>
                                            </tr>
                                            </thead>
                                            <tbody class="text-xs text-center">
                                            <?php
                                            $labels = []; $dataNeto = [];
                                            $sumNeto = 0; $sumIva = 0;

                                            for ($i = 1; $i <= 12; $i++) {
                                                $mesLabel = obtenerMes($i);
                                                $labels[] = $mesLabel;
                                                $f = "$anio-$i";

                                                $neto = obtenerConsumoGasesClinicos($f);
                                                $iva = obtenerMontoIvaGasesClinicos($f);

                                                $dataNeto[] = $neto;
                                                $sumNeto += $neto; $sumIva += $iva;
                                                ?>
                                                <tr>
                                                    <td class="text-bold"><?php echo $mesLabel; ?></td>
                                                    <td class="text-right">$<?php echo number_format($neto, 0, ",", "."); ?></td>
                                                    <td class="text-right">$<?php echo number_format($iva, 0, ",", "."); ?></td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                            <tfoot class="text-bold text-xs text-right" style="background-color: #fcece8;">
                                            <tr>
                                                <td class="text-center">TOTAL ANUAL</td>
                                                <td>$<?php echo number_format($sumNeto, 0, ",", "."); ?></td>
                                                <td>$<?php echo number_format($sumIva, 0, ",", "."); ?></td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <script>
                    $(function () {
                        var ctx = document.getElementById('chart_gases_<?php echo $anio; ?>').getContext('2d');
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: <?php echo json_encode($labels); ?>,
                                datasets: [{
                                    label: 'Valor Neto Cilindros',
                                    data: <?php echo json_encode($dataNeto); ?>,
                                    borderColor: '#ff653f',
                                    backgroundColor: 'rgba(255, 101, 63, 0.1)',
                                    borderWidth: 3,
                                    pointBackgroundColor: '#ff653f',
                                    fill: true,
                                    tension: 0.4
                                }]
                            },
                            options: {
                                maintainAspectRatio: false,
                                responsive: true,
                                scales: {
                                    yAxes: [{ ticks: { beginAtZero: true, fontSize: 10, callback: function(value){ return '$' + value.toLocaleString(); } } }],
                                    xAxes: [{ ticks: { fontSize: 10 } }]
                                },
                                tooltips: {
                                    callbacks: {
                                        label: function(item) { return 'Neto: $' + item.yLabel.toLocaleString(); }
                                    }
                                }
                            }
                        });
                    });
                </script>
            <?php endforeach; ?>
        </div>
    </div>
</div>