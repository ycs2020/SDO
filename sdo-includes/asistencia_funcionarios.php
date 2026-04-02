<?php
// Archivo: tu_servidor.php

// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (isset($_POST['fechaAsistencia'], $_POST['turnoAsistencia'])) {
    $fechaAsistencia = $_POST['fechaAsistencia'];
    $turnoAsistencia = $_POST['turnoAsistencia'];

    $date = DateTime::createFromFormat('Y-m-d', $fechaAsistencia);

    if ($date && $date->format('Y-m-d') === $fechaAsistencia) {

// Consulta SQL
        $sql = "SELECT idAsistencia, personalAsistencia, deptoAsistencia FROM asistencia_sdo WHERE fechaAsistencia LIKE '$fechaAsistencia' AND turnoAsistencia LIKE '$turnoAsistencia' ORDER BY deptoAsistencia ASC, personalAsistencia ASC;";

        $stmt = $conexion->prepare($sql);
        $stmt->execute();

// Obtener los resultados
        $result = $stmt->get_result();
        $datos = array();


// Procesar los resultados
        while ($row = $result->fetch_assoc()) {
            $idAsistencia = $row["idAsistencia"];
            $personalAsistencia = $row["personalAsistencia"];
            $deptoAsistencia = $row["deptoAsistencia"];


            $row['idAsistencia'] = $idAsistencia;
            $row['personalAsistencia'] = $personalAsistencia;
            $row['deptoAsistencia'] = $deptoAsistencia;

            $datos[] = $row;

        }

// Cerrar el statement y la conexión
        $stmt->close();
        $conexion->close();

// Enviar los resultados modificados como respuesta JSON
        header('Content-Type: application/json');
        echo json_encode(array('data' => $datos));

    }
}
?>
