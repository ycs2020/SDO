<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

// Consulta SQL
$sql = "SELECT * FROM medicion_tanque_indura WHERE tanqueMedicion ORDER BY fechaMedicion DESC;";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
while ($row = $result->fetch_assoc()) {

    $lecturaMedicion = $row["lecturaMedicion"];
    $unidadMedicion = $row["unidadMedicion"];
    $fechaMedicion = $row["fechaMedicion"];
    $fuenteMedicion = $row["fuenteMedicion"];
    $tanqueMedicion = $row["tanqueMedicion"];
    $fechaSubidaMedicion = $row["fechaSubidaMedicion"];

    if (empty($fechaMedicion)) {
        $fechaMedicion2 = 'Sin Informacion';
    } else {
        $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $fechaMedicion)));
        $inicioHora = date('H:i', strtotime(str_replace('/', '-', $fechaMedicion)));

        $fechaMedicion2 = $inicioFecha . ' a las ' . $inicioHora . ' hrs.';
    }

    if (empty($fechaSubidaMedicion)) {
        $fechaSubidaMedicion2 = 'Sin Informacion';
    } else {
        $terminoFecha = date('d/m/Y', strtotime(str_replace('/', '-', $fechaSubidaMedicion)));
        $terminoHora = date('H:i', strtotime(str_replace('/', '-', $fechaSubidaMedicion)));

        $fechaSubidaMedicion2 = $terminoFecha . ' a las ' . $terminoHora . ' hrs.';
    }

    if($fuenteMedicion == 'Telemetry'){
        $fuenteMedicion2 = 'Telemetría';
    }else if($fuenteMedicion == 'Telemetría'){
        $fuenteMedicion2 = 'Telemetría';
    }else if($fuenteMedicion == 'Driver before delivery'){
        $fuenteMedicion2 = 'Conductor Antes de la Entrega';
    }else if($fuenteMedicion == 'Driver after delivery'){
        $fuenteMedicion2 = 'Conductor Después de la Entrega';
    }else if($fuenteMedicion == 'Conductor antes de la entrega'){
        $fuenteMedicion2 = 'Conductor Antes de la Entrega';
    }else if($fuenteMedicion == 'Conductor después de la entrega'){
        $fuenteMedicion2 = 'Conductor Después de la Entrega';
    }else{
        $fuenteMedicion2 = 'Sin Información';
    }


    $row['lecturaMedicion'] = $lecturaMedicion;
    $row['unidadMedicion'] = $unidadMedicion;
    $row['fechaMedicion'] = $fechaMedicion;
    $row['fechaMedicion2'] = $fechaMedicion2;
    $row['fuenteMedicion'] = $fuenteMedicion;
    $row['fuenteMedicion2'] = $fuenteMedicion2;
    $row['tanqueMedicion'] = $tanqueMedicion;
    $row['fechaSubidaMedicion'] = $fechaSubidaMedicion;
    $row['fechaSubidaMedicion2'] = $fechaSubidaMedicion2;


    // Agregar la fila modificada al array de resultados
    $datos[] = $row;
}

// Cerrar la conexión y liberar recursos
$stmt->close();
$conexion->close();

// Enviar los resultados modificados como respuesta JSON
echo json_encode($datos);

?>