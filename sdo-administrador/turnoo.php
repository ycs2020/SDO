<?php

include '../sdo-funciones/turnoFuncionarios_generar.php';
include 'conexion.php'; // Asegúrate de que este archivo tenga la conexión correcta

// Función para generar un calendario para un mes específico
// Definir los turnos y sus patrones
$turnos = [

    'Turno 1' => ['Noche', 'Libre', 'Libre', 'Día'],
    'Turno 2' => ['Libre', 'Libre', 'Día', 'Noche'],
    'Turno 3' => ['Libre', 'Día', 'Noche', 'Libre'],
    'Turno 4' => ['Día', 'Noche', 'Libre', 'Libre'],
];

// Inicializar el índice de turno
$turnoIndex = 0;

// Generar calendario desde noviembre de 2024 hasta diciembre de 2025 e insertar en la base de datos
$startYear = 2026;
$startMonth = 1;
$endYear = 2026;
$endMonth = 12;

for ($year = $startYear; $year <= $endYear; $year++) {
    $monthStart = ($year == $startYear) ? $startMonth : 1;
    $monthEnd = ($year == $endYear) ? $endMonth : 12;

    for ($month = $monthStart; $month <= $monthEnd; $month++) {
        generarCalendarioMensual($year, $month, $turnos, $turnoIndex, $conexion); // Pasa la conexión
    }
}

?>
