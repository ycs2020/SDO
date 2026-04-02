<?php
header('Content-Type: application/json');

// Configuración de conexión a la base de datos
include '../sdo-funciones/conexion.php';

if ($conexion->connect_error) {
    die(json_encode(['error' => 'Error en la conexión a la base de datos']));
}


// Construcción de la consulta
$query = "
    SELECT 
        id, 
        fecha AS start, 
        nombre_turno AS title, 
        turno,
        funcionario_1, 
        funcionario_2 
    FROM 
        calendario_turnos
";

$result = $conexion->query($query);

// Verificar si hay resultados
if ($result && $result->num_rows > 0) {
    $eventos = [];
    while ($row = $result->fetch_assoc()) {
        // Puedes personalizar el formato de cada evento
        $eventos[] = [
            'id' => $row['id'],
            'title' => $row['title'] . ' (' . $row['turno'] . ')', // Ejemplo: "Turno A (Mañana)"
            'start' => $row['start'], // Fecha del evento
            'description' => 'Funcionarios: ' . $row['funcionario_1'] . ', ' . $row['funcionario_2'] // Descripción opcional
        ];
    }

    // Devolver los datos como JSON
    echo json_encode($eventos);
} else {
    // Si no hay resultados, devolver un JSON vacío
    echo json_encode([]);
}

// Cerrar la conexión
$conexion->close();
?>
