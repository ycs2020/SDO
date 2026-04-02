<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

// Consulta SQL
$sql = "SELECT * FROM asignacion_turnos ORDER BY fecha ASC, turno ASC;";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
//$stmt->bind_param("s", $idDepartamento);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
while ($row = $result->fetch_assoc()) {
    $fecha = $row["fecha"];
    $turno = $row["turno"];
    $funcionario = $row["funcionario"];
    $equipo1 = $row["equipo1"];
    $equipo2 = $row["equipo2"];
    $realizado = $row["realizado"];

    $buscarSalasMantenimientoEquipo1 = buscarSalasMantenimiento($equipo1);
    $buscarSalasMantenimientoEquipo2 = buscarSalasMantenimiento($equipo2);

    $fechaTime = strtotime($fecha);
    $fechaFinal = fechaEspañol2($fechaTime);

    $turno = mb_convert_case($turno, MB_CASE_UPPER, 'UTF-8');
    $funcionario = mb_convert_case($funcionario, MB_CASE_UPPER, 'UTF-8');
    $equipo1 = mb_convert_case($equipo1, MB_CASE_UPPER, 'UTF-8');
    $equipo2 = mb_convert_case($equipo2, MB_CASE_UPPER, 'UTF-8');

    $row['fechaFinal'] = $fechaFinal;
    $row['turno'] = $turno;
    $row['funcionario'] = $funcionario;
    $row['equipo1'] = $equipo1;
    $row['equipo2'] = $equipo2;
    $row['totalEquipo1'] = $buscarSalasMantenimientoEquipo1;
    $row['totalEquipo2'] = $buscarSalasMantenimientoEquipo2;


    // Agregar la fila modificada al array de resultados
    $datos[] = $row;
}

// Cerrar la conexión y liberar recursos
$stmt->close();
$conexion->close();

// Enviar los resultados modificados como respuesta JSON
echo json_encode($datos);

?>