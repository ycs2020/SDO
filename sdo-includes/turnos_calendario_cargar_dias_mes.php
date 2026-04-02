<?php
include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';

$anio = $_POST['anio'];
$mes  = $_POST['mes'];

$fechaInicio      = $anio . '-' . str_pad($mes, 2, '0', STR_PAD_LEFT) . "-01";
$fechaTimestamp   = strtotime($fechaInicio);
$primerDiaSemana  = date('N', $fechaTimestamp); // 1=lun .. 7=dom
$diasEnMes        = date('t', $fechaTimestamp);

$feriados = obtenerFeriadosChile(); // debe retornar array de 'Y-m-d'

// tabla
echo '<table class="table table-bordered table-sm" style="table-layout:fixed;width:100%;">';
echo '<thead class="thead-dark"><tr><th>Lun</th><th>Mar</th><th>Mié</th><th>Jue</th><th>Vie</th><th>Sáb</th><th>Dom</th></tr></thead><tbody><tr>';

// celdas vacías antes del primer día
$columna = 1;
for ($i = 1; $i < $primerDiaSemana; $i++, $columna++) {
    echo "<td></td>";
}

// Colores por tipo (fondo sólido para dark-mode)
$bgColores = array(
    'Día'         => '#007bff',
    'Extra Día'   => '#20c997',
    'Noche'       => '#6f42c1',
    'Extra Noche' => '#fd7e14'
);

// Orden deseado
$arrayTurnos = array('Día', 'Extra Día', 'Noche', 'Extra Noche');

for ($dia = 1; $dia <= $diasEnMes; $dia++, $columna++) {
    $fechaCompleta = $anio . '-' . str_pad($mes, 2, '0', STR_PAD_LEFT) . '-' . str_pad($dia, 2, '0', STR_PAD_LEFT);
    $diaSemana     = date('N', strtotime($fechaCompleta));
    $isFeriado     = in_array($fechaCompleta, $feriados);

    $colorDia = ($diaSemana >= 6 || $isFeriado) ? 'color:#ff3333;' : 'color:#000000;';
    echo '<td style="vertical-align:top; min-height:100px; ' . $colorDia . '">';
    echo '<b>' . $dia . '</b><br><br>';

    // Traer turnos del día
    $placeholders = implode(',', array_fill(0, count($arrayTurnos), '?'));
    $sql = "SELECT idTurno, funcionarioTurno, tipoTurno
          FROM turno_control_calendario
          WHERE tipoTurno IN ($placeholders)
            AND fechaTurno = ?
          ORDER BY FIELD(tipoTurno, 'Día','Extra Día','Noche','Extra Noche'), funcionarioTurno ASC";

    $stmt = $conexion->prepare($sql);
    $tiposYFecha = array_merge($arrayTurnos, array($fechaCompleta));
    $stmt->bind_param(str_repeat('s', count($arrayTurnos)) . 's', ...$tiposYFecha);
    $stmt->execute();
    $res = $stmt->get_result();

    // agrupar por tipoTurno
    $grupos = array();
    while ($row = $res->fetch_assoc()) {
        $grupos[$row['tipoTurno']][] = array(
            'id'     => $row['idTurno'],
            'nombre' => $row['funcionarioTurno'],
            'tipo'   => $row['tipoTurno'],
            'fecha'  => $fechaCompleta
        );
    }
    $stmt->close();

    foreach ($arrayTurnos as $tipoKey) {
        if (!isset($grupos[$tipoKey])) continue;

        $bg = isset($bgColores[$tipoKey]) ? $bgColores[$tipoKey] : '#555';
        echo '<div class="bloque-tipo" style="background-color:' . $bg . ';">';
        echo '<b>' . $tipoKey . '</b><br>';

        foreach ($grupos[$tipoKey] as $t) {
            echo '<div class="item-turno"
                 data-idturno="' . (int)$t['id'] . '"
                 data-funcionario="' . htmlspecialchars($t['nombre'], ENT_QUOTES, 'UTF-8') . '"
                 data-tipo="' . htmlspecialchars($t['tipo'], ENT_QUOTES, 'UTF-8') . '"
                 data-fecha="' . htmlspecialchars($t['fecha'], ENT_QUOTES, 'UTF-8') . '"
                 data-detalle="' . htmlspecialchars($t['detalleReemplazo'], ENT_QUOTES, 'UTF-8') . '"
                 data-funcionario-original="' . htmlspecialchars($t['nombre'], ENT_QUOTES, 'UTF-8') . '">
                 
              <span>' . htmlspecialchars($t['nombre'], ENT_QUOTES, 'UTF-8') . '</span>
              
             
            </div>';
        }

        echo '</div>';
    }

    echo '</td>';

    if ($columna % 7 === 0) echo '</tr><tr>';
}

// celdas vacías finales
while (($columna - 1) % 7 !== 0) {
    echo "<td></td>";
    $columna++;
}

// forzar 6 filas (estético)
$totalFilas = ceil(($columna - 1) / 7);
while ($totalFilas < 6) {
    echo "<tr>";
    for ($i = 0; $i < 7; $i++) echo "<td></td>";
    echo "</tr>";
    $totalFilas++;
}

echo '</tr></tbody></table>';
