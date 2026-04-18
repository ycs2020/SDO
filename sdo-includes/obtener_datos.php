<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

$anio = $_POST['anio'];

$sql = "SELECT SUM(metrosConsumidos) as total FROM criogenico_datos WHERE YEAR(fecha) = '$anio'";
$res = mysqli_query($conexion, $sql);
$row = mysqli_fetch_assoc($res);

$total = $row['total'] ?? 0;

$sqlGastoIVA = "SELECT SUM(valorTotalConIVA) as totalGastoIva FROM criogenico_datos WHERE YEAR(fecha) = '$anio'";
$resGastoIVA = mysqli_query($conexion, $sqlGastoIVA);
$rowGastoIVA = mysqli_fetch_assoc($resGastoIVA);

$totalGastoIva = $rowGastoIVA['totalGastoIva'] ?? 0;


$sqlPromedio = "SELECT AVG(metrosConsumidos) as promedio FROM criogenico_datos WHERE YEAR(fecha) = '$anio'";
$resPromedio = mysqli_query($conexion, $sqlPromedio);
$rowPromedio = mysqli_fetch_assoc($resPromedio);

$promedio = $rowPromedio['promedio'] ?? 0;


$sqlMeses = "SELECT 
            MONTH(fecha) as mes,
            SUM(metrosConsumidos) as total,
            SUM(valorTotalConIVA) as totalGastoIva,
            AVG(metrosConsumidos) as promedio
        FROM criogenico_datos 
        WHERE YEAR(fecha) = '$anio'
        GROUP BY MONTH(fecha)
        ORDER BY MONTH(fecha)";

$resMeses = mysqli_query($conexion, $sqlMeses);

$meses = [];
$totales = array_fill(1, 12, 0);
$gastos = array_fill(1, 12, 0);
$promedios = [];


while ($rowMeses = mysqli_fetch_assoc($resMeses)) {
    $mes = (int)$rowMeses['mes'];
    $totales[$mes] = $rowMeses['total'];
    $gastos[$mes] = $rowMeses['totalGastoIva'];
}


$nombresMeses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];

$labels = $nombresMeses;
$totales = array_values($totales);
$gastos = array_values($gastos);



?>
<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
        <span class="info-box-icon bg-info elevation-1">
            <i class="fas fa-flask"></i>
        </span>
            <div class="info-box-content">
            <span class="info-box-text">
                Total de M<sup>3</sup> Consumidos en <?php echo $anio; ?>
            </span>
                <span class="info-box-number">
                <?php echo number_format($total); ?>
            </span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
        <span class="info-box-icon bg-info elevation-1">
            <i class="fas fa-money-bill"></i>
        </span>
            <div class="info-box-content">
            <span class="info-box-text">
                Gasto Total Con IVA en <?php echo $anio; ?>
            </span>
                <span class="info-box-number">
                <?php echo number_format($totalGastoIva); ?>
            </span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4">
        <div class="info-box">
        <span class="info-box-icon bg-info elevation-1">
            <i class="fas fa-flask"></i>
        </span>
            <div class="info-box-content">
            <span class="info-box-text">
                Promedio de M<sup>3</sup> Consumidos en <?php echo $anio; ?>
            </span>
                <span class="info-box-number">
                <?php echo number_format($promedio); ?>
            </span>
            </div>
        </div>
    </div>
</div>

<hr>
<script>
    var labels = <?php echo json_encode($labels); ?>;
    var dataTotales = <?php echo json_encode($totales); ?>;
    var dataGastos = <?php echo json_encode($gastos); ?>;
</script>
<canvas id="barChart"></canvas>

<script>
    if (window.barChartInstance) {
        window.barChartInstance.destroy();
    }

    var ctx = document.getElementById('barChart').getContext('2d');

    window.barChartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Metros Consumidos',
                    data: dataTotales,
                    backgroundColor: 'rgba(60,141,188,0.8)'
                },
                {
                    label: 'Gasto con IVA',
                    data: dataGastos,
                    backgroundColor: 'rgba(210, 214, 222, 1)'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>



