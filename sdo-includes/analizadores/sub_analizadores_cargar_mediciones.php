<?php
// sub_analizadores_cargar_mediciones.php
include '../../sdo-funciones/conexion.php';

if (isset($_POST['subId'])) {
    $subId = (int)$_POST['subId'];
    $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : 'voltaje'; // Capturamos el tipo

    // Definimos qué líneas buscar según el tipo
    if ($tipo === 'fase') {
        $ordenDeseado = ['Fase 1', 'Fase 2', 'Fase 3'];
    } else {
        $ordenDeseado = ['L1N', 'L2N', 'L3N', 'L1L2', 'L2L3', 'L3L1'];
    }

    $query = "SELECT idMedicion, fechaMedicion, valorMedicion, lineaMedicion 
              FROM analizadores_red_mediciones 
              WHERE idSubAnalizador = ? 
              ORDER BY fechaMedicion DESC";

    if ($stmt = $conexion->prepare($query)) {
        $stmt->bind_param("i", $subId);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $tablasPorLinea = [];

        while ($fila = $resultado->fetch_assoc()) {
            $linea = $fila['lineaMedicion'];
            if (!isset($tablasPorLinea[$linea])) {
                $tablasPorLinea[$linea] = [];
            }
            $tablasPorLinea[$linea][] = [
                'fecha' => $fila['fechaMedicion'],
                'valor' => $fila['valorMedicion']
            ];
        }
        $stmt->close();
        ?>

        <div class="row">
            <?php
            $hayDatos = false;
            if (count($tablasPorLinea) > 0) {
                foreach ($ordenDeseado as $nombreLinea) {
                    if (!isset($tablasPorLinea[$nombreLinea])) {
                        continue;
                    }
                    $hayDatos = true;
                    $registros = $tablasPorLinea[$nombreLinea];

                    $esFase = (strpos($nombreLinea, 'Fase') !== false);
                    $unidadMedida = $esFase ? 'A' : 'V';
                    $iconoHeader = $esFase ? 'fa-bolt text-warning' : 'fa-chart-bar text-primary';
                    ?>
                    <div class="col-xl-4 col-md-6 col-12 mb-4">
                        <div class="card card-outline card-secondary shadow-sm">
                            <div class="card-header bg-light py-2 text-center">
                                <h6 class="card-title font-weight-bold text-navy m-0 w-100">
                                    <i class="fas <?= $iconoHeader ?> mr-1"></i> <?= htmlspecialchars($nombreLinea) ?>
                                </h6>
                            </div>
                            <div class="card-body p-2" style="max-height: 400px; overflow-y: auto;">
                                <table class="table table-sm table-bordered table-striped table-valign-middle text-center table-hover m-0 tabla-sub-linea w-100 text-xs tabla-data-dinamica">
                                    <thead class="bg-gradient-gray text-center">
                                    <tr>
                                        <th>Fecha y Hora</th>
                                        <th>Valor (<?= $unidadMedida ?>)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($registros as $reg) {
                                        $fechaFormateada = date("d-m-Y H:i:s", strtotime($reg['fecha']));
                                        $valor = $reg['valor'];

                                        // Lógica de alerta por rangos de voltaje
                                        $fueraDeRango = false;
                                        if ($nombreLinea === 'L1N' || $nombreLinea === 'L2N' || $nombreLinea === 'L3N') {
                                            if ($valor < 205.0 || $valor > 235.0) {
                                                $fueraDeRango = true;
                                            }
                                        } elseif ($nombreLinea === 'L1L2' || $nombreLinea === 'L2L3' || $nombreLinea === 'L3L1') {
                                            if ($valor < 354.0 || $valor > 406.0) {
                                                $fueraDeRango = true;
                                            }
                                        }

                                        // Si está fuera de rango usa 'text-danger', de lo contrario mantiene el 'text-primary' (o 'text-warning' si son fases)
                                        $claseColor = $fueraDeRango ? 'text-danger font-weight-bold' : ($esFase ? 'text-primary' : 'text-primary');
                                        ?>
                                        <tr>
                                            <td class="text-center text-muted font-weight-bold"><?= $fechaFormateada ?></td>
                                            <td class="text-center font-weight-bold <?= $claseColor ?>">
                                                <?= number_format($valor, 1) ?> <?= $unidadMedida ?>
                                                <?php if ($fueraDeRango): ?>
                                                    <i class="fas fa-exclamation-triangle ml-1" title="Valor fuera de rango normativo"></i>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }

            if (!$hayDatos) {
                echo "<div class='col-12'><div class='alert alert-info m-2'>No se encontraron registros de " . ($tipo === 'fase' ? 'fases' : 'voltajes') . " para este medidor.</div></div>";
            }
            ?>
        </div>
        <?php
    } else {
        echo "<div class='alert alert-danger'><i class='fas fa-exclamation-circle mr-2'></i>Error en el motor de base de datos.</div>";
    }
} else {
    echo "<div class='alert alert-warning'><i class='fas fa-exclamation-triangle mr-2'></i>ID de sub-analizador inválido.</div>";
}
?>