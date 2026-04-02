<?php
include '../sdo-funciones/conexion.php';

$anioActual = date('Y');
$meses = ["", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

// 1. Consulta única optimizada
$query = "SELECT idDepartamento, MONTH(fechaOrden) as mes, COUNT(*) as total 
          FROM ordenes_trabajos_general 
          WHERE YEAR(fechaOrden) = '$anioActual'
          GROUP BY idDepartamento, MONTH(fechaOrden)";
$res = mysqli_query($conexion, $query);

$dataReporte = [];
while ($row = mysqli_fetch_assoc($res)) {
    $dataReporte[$row['idDepartamento']][$row['mes']] = (int)$row['total'];
}

// 2. Departamentos
$sqlDepto = "SELECT idDepartamento, nomDepartamento FROM departamentos 
             WHERE nomDepartamento NOT IN ('Control Centralizado', 'Informática', 'Ingeniería Operacional') 
             ORDER BY nomDepartamento ASC";
$resDepto = mysqli_query($conexion, $sqlDepto);

$totalesMensuales = array_fill(1, 12, 0);
$granTotal = 0;
?>

<div class="row animate__animated animate__fadeIn">
    <div class="col-xl-8 col-lg-12">
        <div class="card shadow-none border">
            <div class="card-header bg-light">
                <h3 class="card-title font-weight-bold">Detalle Mensual por Departamento</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-valign-middle mb-0">
                        <thead class="bg-dark text-white text-center">
                        <tr>
                            <th>Depto.</th>
                            <?php for($m=1; $m<=12; $m++) echo "<th class='small'>".substr($meses[$m], 0, 3)."</th>"; ?>
                            <th class="bg-primary">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        mysqli_data_seek($resDepto, 0); // Reset pointer
                        while ($d = mysqli_fetch_assoc($resDepto)):
                            $idD = $d['idDepartamento'];
                            $totalFila = 0;
                            ?>
                            <tr>
                                <td class="small font-weight-bold text-uppercase"><?= $d['nomDepartamento'] ?></td>
                                <?php for ($m = 1; $m <= 12; $m++):
                                    $v = $dataReporte[$idD][$m] ?? 0;
                                    $totalFila += $v;
                                    $totalesMensuales[$m] += $v;
                                    ?>
                                    <td class="text-center <?= $v > 0 ? 'font-weight-bold' : 'text-muted' ?>" style="font-size: 0.8rem;">
                                        <?= $v > 0 ? $v : '-' ?>
                                    </td>
                                <?php endfor; ?>
                                <td class="text-center font-weight-bold bg-light text-primary"><?= $totalFila ?></td>
                            </tr>
                            <?php $granTotal += $totalFila; endwhile; ?>
                        </tbody>
                        <tfoot class="bg-light font-weight-bold text-center">
                        <tr>
                            <td>TOTALES</td>
                            <?php foreach($totalesMensuales as $tm) echo "<td>$tm</td>"; ?>
                            <td class="bg-primary text-white"><?= $granTotal ?></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-12">
        <div class="card shadow-none border">
            <div class="card-header bg-light">
                <h3 class="card-title font-weight-bold">Tendencia Anual</h3>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height:300px;">
                    <canvas id="graficoLineasHT"></canvas>
                </div>
                <hr>
                <div class="text-center">
                    <span class="h4 font-weight-bold text-primary"><?= number_format($granTotal) ?></span><br>
                    <small class="text-muted text-uppercase">Total Hojas Técnicas <?= $anioActual ?></small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    if (window.miGraficoHT) { window.miGraficoHT.destroy(); } // Evita duplicados al recargar AJAX

    var ctx = document.getElementById('graficoLineasHT').getContext('2d');
    window.miGraficoHT = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode(array_values(array_slice($meses, 1))) ?>,
            datasets: [{
                label: 'Hojas Técnicas',
                data: <?= json_encode(array_values($totalesMensuales)) ?>,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                borderWidth: 3,
                pointRadius: 4,
                pointBackgroundColor: '#007bff',
                fill: true,
                tension: 0.3 // Curva suave
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { drawBorder: false }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
</script>