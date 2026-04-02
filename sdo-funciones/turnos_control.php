<?php
include_once 'conexion.php';

// Año a generar (puede venir de $_GET o $_POST)
$anio = 2026;

// Listas de funcionarios a rotar (deben ser múltiplos de 2 para formar pares)
$funcionariosDia = [
    'Gonzalo Cáceres Carreño',
    'Luis Pérez Bernal',
    'Felipe Retamal Fuentes',
    'Jesús Silva Pellet',
    'Abel Torres Arancibia',
    'Manuel Torres Sagredo',
    'Joaquín Jorquera Gonzalez',
    'Rene Condell Vargas'
];

$funcionariosNoche = [
    'Joaquín Jorquera Gonzalez',
    'Rene Condell Vargas',
    'Gonzalo Cáceres Carreño',
    'Luis Pérez Bernal',
    'Felipe Retamal Fuentes',
    'Jesús Silva Pellet',
    'Abel Torres Arancibia',
    'Manuel Torres Sagredo'
];

// Inicializamos fecha desde el 1 de enero al 31 de diciembre del año dado
$inicio = new DateTime("$anio-01-01");
$fin = new DateTime("$anio-12-31");
$intervalo = new DateInterval('P1D');
$fechas = new DatePeriod($inicio, $intervalo, $fin->modify('+1 day'));

// Preparar la consulta
$stmt = $conexion->prepare("INSERT INTO turno_control_calendario (funcionarioTurno, tipoTurno, fechaTurno) VALUES (?, ?, ?)");

// Contadores para rotar los funcionarios
$indexDia = 0;
$indexNoche = 0;

foreach ($fechas as $fecha) {
    $fechaTurno = $fecha->format('Y-m-d');

    // Obtener pareja de día
    $parDia1 = $funcionariosDia[$indexDia % count($funcionariosDia)];
    $parDia2 = $funcionariosDia[($indexDia + 1) % count($funcionariosDia)];

    // Insertar día
    $stmt->bind_param("sss", $parDia1, $tipoDia, $fechaTurno);
    $tipoDia = 'Día';
    $stmt->execute();

    $stmt->bind_param("sss", $parDia2, $tipoDia, $fechaTurno);
    $stmt->execute();

    // Obtener pareja de noche
    $parNoche1 = $funcionariosNoche[$indexNoche % count($funcionariosNoche)];
    $parNoche2 = $funcionariosNoche[($indexNoche + 1) % count($funcionariosNoche)];

    // Insertar noche
    $stmt->bind_param("sss", $parNoche1, $tipoNoche, $fechaTurno);
    $tipoNoche = 'Noche';
    $stmt->execute();

    $stmt->bind_param("sss", $parNoche2, $tipoNoche, $fechaTurno);
    $stmt->execute();

    // Avanzar índices de rotación (por pares)
    $indexDia = ($indexDia + 2) % count($funcionariosDia);
    $indexNoche = ($indexNoche + 2) % count($funcionariosNoche);
}

$stmt->close();
$conexion->close();

echo "Turnos generados correctamente para el año $anio.";

