<?php
header('Content-Type: application/json');

// Configuración de conexión a la base de datos
include '../sdo-funciones/conexion.php';

date_default_timezone_set('America/Santiago');

if ($conexion->connect_error) {
    die(json_encode(['error' => 'Error en la conexión a la base de datos']));
}

// Construcción de la consulta
$query = "
    SELECT 
        id, 
        fecha AS start, 
        turno,
        funcionario_1,
        funcionario_2,
        horasFuncionario,
        CASE
            WHEN turno = 'Día' THEN '#FF6347'
            WHEN turno = 'Noche' THEN '#4682B4'
            WHEN turno = 'Extra Día' THEN '#4682B4'
            WHEN turno = 'Extra Noche' THEN '#4682B4'
            ELSE '#32CD32'                        -- Verde para otros turnos
        END AS color
    FROM 
        calendario_turnos
    WHERE 
        turno IN ('Día', 'Noche', 'Extra Día', 'Extra Noche')
";

$result = $conexion->query($query);

// Verificar si hay resultados
if ($result && $result->num_rows > 0) {
    $eventos = [];
    $extrasPorFecha = [];

    while ($row = $result->fetch_assoc()) {
        if ($row['turno'] === 'Extra Día' || $row['turno'] === 'Extra Noche') {
            $fecha = $row['start'];
            $horasFuncionario = $row['horasFuncionario'];
            if (empty($horasFuncionario)) {
                // Está vacío, nulo o cadena vacía
                // Puedes asignar un valor por defecto o no mostrar nada
                $horaTexto = '';
            } else {
                $horaTexto = date("H:i", strtotime($horasFuncionario));
                $horaTexto = " (" . $horaTexto . " hrs.)";
            }


            $linea = $row['funcionario_1'] . $horaTexto;
            $funcionario_1 = $row['funcionario_1'];
            $funcionario_2 = $row['funcionario_2'];
            $turno = $row['turno'];
            $idTurno = $row['id'];

            if (!isset($extrasPorFecha[$fecha])) {
                $extrasPorFecha[$fecha] = [];
            }

            $extrasPorFecha[$fecha][] = $linea;
        } else {
            $funcionario_1 = trim($row['funcionario_1']);
            $funcionario_2 = trim($row['funcionario_2']);

            $textoParaTitulo = '';

            if(!empty($funcionario_1)){
                $textoParaTitulo .= $funcionario_1.'<br>';
            }
            if(!empty($funcionario_2)){
                $textoParaTitulo .= $funcionario_2;
            }


            $titulo = $row['turno'] . "<br>" . $textoParaTitulo;
            $eventos[] = [
                'id' => $row['id'],
                'start' => $row['start'],
                'title' => $titulo,
                'color' => $row['color'],
                'funcionario_1' => $row['funcionario_1'],
                'funcionario_2' => $row['funcionario_2'],
                'turno' => $row['turno'],
                'idTurno' => $row['id'],
            ];
        }
    }

    // Crear un solo evento "Extra" por fecha
    foreach ($extrasPorFecha as $fecha => $lineas) {
        $titulo = "Extra<br>" . implode("<br>", $lineas);
        $eventos[] = [
            'id' => 'extra_' . $fecha,
            'start' => $fecha,
            'title' => $titulo,
            'color' => '#32CD32',    // verde
            'textColor' => '#000000', // negro
            'funcionario_1' => $row['funcionario_1'],
            'funcionario_2' => $row['funcionario_2'],
            'turno' => $row['turno'],
            'idTurno' => $row['id'],
        ];

    }

    echo json_encode($eventos);
} else {
    echo json_encode([]);
}


$conexion->close();
?>
