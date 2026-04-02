
<?php


date_default_timezone_set('America/Santiago');

$fecha_actual = new DateTime();
$anio = date('Y');
$anioDespues = $anio + 1;

// Define las fechas de inicio y fin del rango
$fecha_inicio_navidad = new DateTime($anio.'-12-01 00:00:00');
$fecha_fin_navidad = new DateTime($anio.'-12-25 23:59:59');

$fecha_inicio_preanionuevo = new DateTime($anio.'-12-26 00:00:00');
$fecha_fin_preanionuevo = new DateTime($anio.'-12-31 23:59:59');

$fecha_inicio_anionuevo = new DateTime($anioDespues.'-01-01 00:00:00');
$fecha_fin_anionuevo = new DateTime($anioDespues.'-01-01 23:59:59');

$fecha_inicio_chile = new DateTime($anio.'-09-01 00:00:00');
$fecha_fin_chile  = new DateTime($anio.'-09-21 23:59:59');

$fecha_inicio_hallow = new DateTime($anio.'-10-31 00:00:00');
$fecha_fin_hallow  = new DateTime($anio.'-11-01 08:00:00');

$ipPermitida = '10.69.250.151'; // Reemplaza con la IP permitida
$ipPermitida2 = '10.69.250.19'; // Reemplaza con la IP permitida
$ipPermitida3 = '10.69.250.205'; // Reemplaza con la IP permitida
//
// Obtén la IP del cliente
$ipCliente = $_SERVER['REMOTE_ADDR'];
//
/// // Verifica si la IP del cliente es la permitida


//if ($ipCliente === $ipPermitida || $ipCliente === $ipPermitida2) {
//    include_once('login_index_lluvia.php');
//
//}else{

// Compara si la fecha actual está dentro del rango
    if ($fecha_actual >= $fecha_inicio_navidad && $fecha_actual <= $fecha_fin_navidad) {
        include_once('login_index_navidad.php');
    }
    else if ($fecha_actual >= $fecha_inicio_preanionuevo && $fecha_actual <= $fecha_fin_preanionuevo) {
        include_once('login_index_preanio.php');
    }
    else if ($fecha_actual >= $fecha_inicio_anionuevo && $fecha_actual <= $fecha_fin_anionuevo) {
        include_once('login_index_anionuevo.php');
    }
    else if ($fecha_actual >= $fecha_inicio_chile && $fecha_actual <= $fecha_fin_chile) {
        include_once('login_index_respaldo2.php');
    }
    else if ($fecha_actual >= $fecha_inicio_hallow && $fecha_actual <= $fecha_fin_hallow) {
        include_once('login_index_halloween.php');
    }
    else if ($ipCliente == $ipPermitida) {
        include_once('login_index_2026.php');
//        include_once('login_index_respaldo.php');

    }
    else {
        include_once('login_index_respaldo.php');
//        include_once('login_index_lluvia.php');
    }
//}
