<?php
// Aseguramos que las variables existan
if (!isset($anio) || !isset($mes) || !isset($conteo)) {
    echo "<div class='alert alert-danger'>Error: datos del calendario no disponibles.</div>";
    exit;
}

// Datos del mes
$primerDiaMes = mktime(0, 0, 0, $mes, 1, $anio);
$numeroDiasMes = date('t', $primerDiaMes);
$diaDeSemana = (date('w', $primerDiaMes) + 6) % 7; // Lunes = 0
$diasSemana = ["Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom"];

$cantidadTotalMes = array_sum($conteo);

// Render HTML
?>
<div class="card border-info">
    <div class="card-header bg-info text-white p-2 text-center">
        <strong><?php echo date("F", $primerDiaMes) . " " . $anio; ?></strong>
    </div>

    <div class="card-body p-2">
        <table style="border: 2px solid black; border-collapse: collapse;" class="table table-bordered text-center">
            <thead>
            <tr>
                <?php foreach ($diasSemana as $dia): ?>
                    <th style="border: 1px solid black; background-color: blueviolet; color:white;"><?php echo $dia; ?></th>
                <?php endforeach; ?>
            </tr>
            </thead>
            <tbody>
            <tr>
                <?php
                // Celdas vacías antes del primer día
                for ($j = 0; $j < $diaDeSemana; $j++) {
                    echo "<td></td>";
                }

                for ($dia = 1; $dia <= $numeroDiasMes; $dia++) {
                    if (($diaDeSemana + $dia - 1) % 7 == 0 && $dia != 1) {
                        echo "</tr><tr>";
                    }

                    $fecha = sprintf('%04d-%02d-%02d', $anio, $mes, $dia);
                    $cantidadTickets = isset($conteo[$fecha]) ? $conteo[$fecha] : 0;

                    echo "<td style='border: 1px solid black;'>";
                    echo "<b>$dia</b><br><small>Tickets:</small> <b>$cantidadTickets</b>";
                    echo "</td>";
                }

                // Celdas vacías al final
                while (($diaDeSemana + $dia - 1) % 7 != 0) {
                    echo "<td></td>";
                    $dia++;
                }
                ?>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="card-footer text-center" style="background-color: indigo; color: white;">
        <h5>Total de Tickets: <?php echo $cantidadTotalMes; ?></h5>
    </div>
</div>
