<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

// Consulta SQL
$sql = "SELECT nomAlarma, mensajeAlarma, deptoAlarma, categoriaAlarma, COUNT(*) AS total_repeticiones, MAX(fechaAlarma) AS ultima_fechaAlarma FROM bms_reporteAlarmas GROUP BY mensajeAlarma ORDER BY total_repeticiones DESC, ultima_fechaAlarma DESC;
;";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
while ($row = $result->fetch_assoc()) {

    $fechaAlarma = $row["ultima_fechaAlarma"];
    $nomAlarma = $row["nomAlarma"];
    $deptoAlarma = $row["deptoAlarma"];
    $categoriaAlarma = $row["categoriaAlarma"];
    $mensajeAlarma = $row["mensajeAlarma"];
//    $estadoAlarma = $row["estadoAlarma"];

    if (empty($fechaAlarma)) {
        $fechaCreacion = 'Sin Informacion';
    } else {
        $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $fechaAlarma)));
        $inicioHora = date('H:i', strtotime(str_replace('/', '-', $fechaAlarma)));

        $fechaCreacion = $inicioFecha . '<br>' . $inicioHora . ' hrs.';
    }


    $row['fechaAlarma'] = $fechaAlarma;
    $row['fechaAlarma2'] = $fechaCreacion;
    $row['nomAlarma'] = $nomAlarma;
    $row['deptoAlarma'] = $deptoAlarma;
    $row['categoriaAlarma'] = $categoriaAlarma;
    $row['mensajeAlarma'] = $mensajeAlarma;
//    $row['estadoAlarma'] = $estadoAlarma;


    // Agregar la fila modificada al array de resultados
    $datos[] = $row;
}

// Cerrar la conexión y liberar recursos
$stmt->close();
$conexion->close();

// Enviar los resultados modificados como respuesta JSON
echo json_encode($datos);

?>