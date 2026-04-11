<?php
include_once '../../sdo-funciones/conexion.php';
include_once '../../sdo-funciones/funciones.php';

// Función para centralizar los colores de los estados
function obtenerBadgeEstado($estado, $idMovil = 0) {
    $estado = trim($estado);
    $idMovil = (int)$idMovil;

    switch (mb_strtoupper($estado)) {
        case 'OPERATIVA': case 'OPERATIVO':
        $clase = 'success'; $icono = 'fa-check'; $texto = 'OPERATIVA'; break;
        case 'MANTENCION PREVENTIVA':
            $clase = 'info'; $icono = 'fa-exclamation-triangle'; $texto = 'MANTENCION PREVENTIVA'; break;
        case 'MANTENCION CORRECTIVA':
            $clase = 'warning'; $icono = 'fa-exclamation-triangle'; $texto = 'MANTENCION CORRECTIVA'; break;
        case 'DEBAJA':
            $clase = 'danger'; $icono = 'fa-skull-crossbones'; $texto = 'DE BAJA'; break;
        case 'OTRO':
            $clase = 'dark'; $icono = 'fa-cog'; $texto = 'OTRO'; break;
        default:
            $clase = 'secondary'; $icono = 'fa-question'; $texto = 'Sin Información'; break;
    }

    $badge = '<span class="badge badge-' . $clase . '"><i class="fas ' . $icono . '"></i>&nbsp;&nbsp;' . $texto . '</span>';

    if ($idMovil > 0) {
        return '
        <form action="visualizarMovil.php" method="POST" style="display:inline;">
            <input type="hidden" name="idMovil" value="' . $idMovil . '">
            <button type="submit" class="btn btn-link p-0 border-0" style="vertical-align: baseline;" title="Click para ver detalle">
                ' . $badge . '
            </button>
        </form>';
    }
    return $badge;
}

// Recibir variables
$mes  = isset($_POST['mes']) ? (int)$_POST['mes'] : 0;
$anio = isset($_POST['anio']) ? (int)$_POST['anio'] : date('Y');

// Definir condiciones y parámetros manualmente para máxima compatibilidad
if ($mes > 0) {
    $condicionHistorial = "MONTH(h.fechaRegistro) = ? AND YEAR(h.fechaRegistro) = ?";
    $condicionEstado    = "AND MONTH(h3.fechaRegistro) = ? AND YEAR(h3.fechaRegistro) = ?";
    $fechaCorte         = "$anio-" . str_pad($mes, 2, "0", STR_PAD_LEFT) . "-01";
} else {
    $condicionHistorial = "YEAR(h.fechaRegistro) = ?";
    $condicionEstado    = "AND YEAR(h3.fechaRegistro) = ?";
    $fechaCorte         = "$anio-01-01";
}

$sql = "SELECT 
            m.idMovil,
            m.nomVehiculo, 
            m.patenteVehiculo,
            m.estadoVehiculo as estado_actual_real,
            COALESCE((
                SELECT h2.cantKilometraje 
                FROM historial_kilometraje_moviles h2 
                WHERE h2.idMovil = m.idMovil 
                AND h2.fechaRegistro < ? 
                ORDER BY h2.fechaRegistro DESC, h2.idKilometraje DESC 
                LIMIT 1
            ), (SELECT MIN(hmin.cantKilometraje) FROM historial_kilometraje_moviles hmin WHERE hmin.idMovil = m.idMovil AND $condicionHistorial)) as km_inicial,
            MAX(h.cantKilometraje) as km_final,
            (SELECT h3.estadoVehiculo 
             FROM historial_kilometraje_moviles h3 
             WHERE h3.idMovil = m.idMovil 
             $condicionEstado 
             ORDER BY h3.fechaRegistro DESC, h3.idKilometraje DESC 
             LIMIT 1) as estado_periodo
        FROM moviles_movilizacion m
        INNER JOIN historial_kilometraje_moviles h ON m.idMovil = h.idMovil
        WHERE $condicionHistorial
        GROUP BY m.idMovil";

$stmt = $conexion->prepare($sql);

// Bindeo manual según el caso para evitar errores de versión de PHP
if ($mes > 0) {
    // 6 parámetros: fechaCorte(s), mes(i), anio(i), mes(i), anio(i), mes(i), anio(i)
    // Pero espera, la consulta tiene: 1 en km_inicial, 2 en subconsulta MIN, 2 en subconsulta estado, 2 en el WHERE final.
    // Ajustemos el bindeo exacto a la posición de los '?'
    $stmt->bind_param("siiiiii", $fechaCorte, $mes, $anio, $mes, $anio, $mes, $anio);
} else {
    $stmt->bind_param("siii", $fechaCorte, $anio, $anio, $anio);
}

$stmt->execute();
$result = $stmt->get_result();

$data = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $row['total_periodo'] = (float)$row['km_final'] - (float)$row['km_inicial'];
        $row['badge_periodo'] = obtenerBadgeEstado($row['estado_periodo'] ?? 'Sin Registro');
        $row['badge_actual']  = obtenerBadgeEstado($row['estado_actual_real'] ?? 'Desconocido', $row['idMovil']);
        $data[] = $row;
    }
}

echo json_encode($data);