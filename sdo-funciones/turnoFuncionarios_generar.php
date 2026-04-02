<?php

include 'conexion.php'; // Asegúrate de que este archivo tenga la conexión correcta

// Función para generar un calendario para un mes específico y guardar en base de datos
function generarCalendarioMensual($year, $month, $turnos, &$turnoIndex, $conexion) {
    // Crear objeto de fecha para el primer día del mes
    $fecha = new DateTime("$year-$month-01");

    // Obtener el número de días del mes y el día de la semana en que comienza el mes
    $diasEnMes = $fecha->format('t'); // Cantidad de días en el mes
    $primerDiaSemana = $fecha->format('N'); // Día de la semana del primer día (1 = Lunes, 7 = Domingo)

    // Asignar funcionarios a los turnos
    $funcionarios = [
        'Turno 1' => ['Joaquín Jorquera Gonzalez', 'Rene Condell Vargas'],
        'Turno 2' => ['Abel Torres Arancibia', 'Manuel Torres Sagredo'],
        'Turno 3' => ['Felipe Retamal Fuentes', 'Jesús Silva Pellet'],
        'Turno 4' => ['Luis Pérez Bernal', 'Gonzalo Cáceres Carreño']
    ];

    // Imprimir el mes y el año
    echo "<h3>" . $fecha->format('F Y') . "</h3>";

    // Crear la tabla del calendario
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>Lun</th><th>Mar</th><th>Mié</th><th>Jue</th><th>Vie</th><th>Sáb</th><th>Dom</th></tr>";

    // Imprimir la primera fila vacía si el mes no empieza en lunes
    echo "<tr>";
    for ($i = 1; $i < $primerDiaSemana; $i++) {
        echo "<td></td>";
    }

    // Imprimir los días del mes con los turnos
    for ($dia = 1; $dia <= $diasEnMes; $dia++) {
        // Obtener el turno para el día actual
        echo "<td>";
        echo "<strong>$dia</strong><br>";
        foreach ($turnos as $nombreTurno => $patronTurno) {
            echo $nombreTurno . ": " . $patronTurno[$turnoIndex] . "<br>";

            // Insertar en la base de datos
            $fechaDia = "$year-$month-$dia";
            $turnoFuncionario1 = mysqli_real_escape_string($conexion, $funcionarios[$nombreTurno][0]);
            $turnoFuncionario2 = mysqli_real_escape_string($conexion, $funcionarios[$nombreTurno][1]);
            $turnoAsignado = $patronTurno[$turnoIndex];

            $sql = "INSERT INTO calendario_turnos (fecha, nombre_turno, turno, funcionario_1, funcionario_2) 
                    VALUES ('$fechaDia', '$nombreTurno', '$turnoAsignado', '$turnoFuncionario1', '$turnoFuncionario2')";

            $res = mysqli_query($conexion, $sql);

            // Verificar si la consulta fue exitosa
            if ($res) {
                echo "Registro guardado exitosamente para $fechaDia<br><br>";
            } else {
                // Mostrar el error de MySQL para depuración
                echo "Error al guardar registro: " . mysqli_error($conexion) . "<br>";
                echo "Consulta SQL: " . $sql . "<br><br>";
            }
        }
        echo "</td>";

        // Avanzar al siguiente día de la semana
        if (($dia + $primerDiaSemana - 1) % 7 == 0) {
            echo "</tr><tr>"; // Nueva fila al finalizar la semana
        }

        // Incrementar el índice del ciclo de turnos
        $turnoIndex = ($turnoIndex + 1) % count($turnos['Turno 1']);
    }

    // Completar la última fila del mes si es necesario
    while (($dia + $primerDiaSemana - 2) % 7 != 6) {
        echo "<td></td>";
        $dia++;
    }

    echo "</tr>";
    echo "</table>";
}

?>
