<?php
// Archivo: ajax-viajes-listado-vamoos.php

// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

/**
 * OPTIMIZACIÓN:
 * 1. Usamos LEFT JOIN para traer el nombre del conductor de una vez.
 * 2. Usamos una subconsulta para contar los archivos sin hacer un SELECT por cada fila.
 */
$sql = "SELECT 
            v.*, 
            c.nomConductor,
            (SELECT COUNT(*) FROM archivos_viajes_movilizacion_vamoos_spa a WHERE a.idViaje = v.idViaje) as totalArchivos
        FROM viajes_movilizacion_vamoos_spa v
        LEFT JOIN conductores_movilizacion c ON v.conductorViaje = c.idConductor
        ORDER BY v.fechaViaje DESC, v.idViaje DESC";

$stmt = $conexion->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$datos = array();

while ($row = $result->fetch_assoc()) {

    // --- Procesamiento de Fechas ---
    $fechaViaje = $row['fechaViaje'];
    $fechaIngresoPlata = $row['fechaIngresoPlata'];

    $row['fechaViaje'] = empty($fechaViaje) ? 'NO APLICA' : date('d-m-Y', strtotime(str_replace('/', '-', $fechaViaje)));
    $row['fechaIngresoPlata'] = empty($fechaIngresoPlata) ? 'SIN REGISTRO' : date('d-m-Y', strtotime(str_replace('/', '-', $fechaIngresoPlata)));

    // --- Procesamiento de Textos (Uppercase) ---
    $row['destinoViaje'] = empty($row['destinoViaje']) ? 'NO APLICA' : mb_convert_case($row['destinoViaje'], MB_CASE_UPPER, 'UTF-8');
    $row['patenteVehiculo'] = empty($row['patenteVehiculo']) ? 'NO APLICA' : mb_convert_case($row['patenteVehiculo'], MB_CASE_UPPER, 'UTF-8');
    $row['unidadViaje'] = empty($row['unidadViaje']) ? 'NO APLICA' : mb_convert_case($row['unidadViaje'], MB_CASE_UPPER, 'UTF-8');
    $row['tipoJornada'] = empty($row['tipoJornada']) ? 'NO APLICA' : mb_convert_case($row['tipoJornada'], MB_CASE_UPPER, 'UTF-8');
    $row['numeroViaje'] = empty($row['numeroViaje']) ? 'NO APLICA' : mb_convert_case($row['numeroViaje'], MB_CASE_UPPER, 'UTF-8');
    $row['horaInicia'] = empty($row['horaInicia']) ? 'NO APLICA' : $row['horaInicia'];
    $row['horaTermino'] = empty($row['horaTermino']) ? 'NO APLICA' : $row['horaTermino'];

    // --- Lógica de Tipo de Vehículo ---
    switch ($row['tipoVehiculo']) {
        case 1: $row['tipoVehiculo'] = 'AUTOMOVIL'; break;
        case 2: $row['tipoVehiculo'] = 'VAN'; break;
        case 3: $row['tipoVehiculo'] = 'CAMIONETA DE CARGA'; break;
        default: $row['tipoVehiculo'] = 'NO APLICA'; break;
    }

    // --- Conductor (Ya viene del JOIN) ---
    $row['conductorViaje'] = !empty($row['nomConductor']) ? mb_convert_case($row['nomConductor'], MB_CASE_UPPER, 'UTF-8') : 'NO APLICA';

    // --- Lógica de Valores Monetarios ---
    $valorViajeNum = $row['valorViaje'];
    $valorExtNum = $row['valorExtension'];
    $totalSumaNum = $valorViajeNum + $valorExtNum;

    $row['valorViaje'] = '$' . number_format($valorViajeNum, 0, ",", ".");
    $row['valorExtension'] = (empty($valorExtNum) || $valorExtNum == 0) ? 'NO APLICA' : '$' . number_format($valorExtNum, 0, ",", ".");
    $row['valorTotal'] = ($totalSumaNum == 0) ? 'NO APLICA' : '$' . number_format($totalSumaNum, 0, ",", ".");

    // --- Cálculo de Duración ---
    if (!empty($row['horaInicia']) && !empty($row['horaTermino']) && $row['horaInicia'] != '00:00:00') {
        $inicio = new DateTime($row['horaInicia']);
        $fin = new DateTime($row['horaTermino']);

        if ($fin < $inicio) {
            $fin->modify('+1 day');
        }

        $intervalo = $inicio->diff($fin);
        $duracion = $intervalo->format('%H:%I');
        $row['duracionViaje2'] = $duracion . " hrs.";
    } else {
        $row['duracionViaje2'] = '00:00 hrs.';
    }

    // --- Archivos (Ya viene de la subconsulta) ---
    $row['hasArchivos'] = (int)$row['totalArchivos'];

    $datos[] = $row;
}

// Cerrar recursos
$stmt->close();
$conexion->close();

// Respuesta JSON
echo json_encode($datos);
?>