<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

$buscador = '';

if(isset($_POST['varSourceAlarma'])){
    $varSourceAlarma = $_POST['varSourceAlarma'];

    if(empty($varSourceAlarma)){
        $buscador = '';
    }else{
        $buscador = 'WHERE a.sourceAlarma = "'.$varSourceAlarma.'"';
    }
}

// Consulta SQL
$sql = "SELECT a.sourceAlarma, a.mensajeAlarma, a.classAlarma, a.fechaCreacionAlarma, a.fechaNormalAlarma, b.cantidad 
        FROM bms_alarmas a 
        JOIN (SELECT sourceAlarma, mensajeAlarma, MAX(fechaCreacionAlarma) AS ultimaFechaCreacion, COUNT(*) AS cantidad 
              FROM bms_alarmas 
              GROUP BY sourceAlarma, mensajeAlarma) b 
        ON a.sourceAlarma = b.sourceAlarma 
        AND a.mensajeAlarma = b.mensajeAlarma 
        AND a.fechaCreacionAlarma = b.ultimaFechaCreacion 
        $buscador
        ORDER BY b.ultimaFechaCreacion DESC;";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
while ($row = $result->fetch_assoc()) {

    $sourceAlarma = $row["sourceAlarma"];
    $mensajeAlarma = $row["mensajeAlarma"];
    $classAlarma = $row["classAlarma"];
    $fechaCreacionAlarma = $row['fechaCreacionAlarma'];
    $fechaNormalAlarma = $row['fechaNormalAlarma'];
    $conteoAlarma = $row['conteoAlarma'];

    if (empty($fechaCreacionAlarma)) {
        $fechaCreacionAlarma2 = 'Sin Informacion';
    } else {
        $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $fechaCreacionAlarma)));
        $inicioHora = date('H:i', strtotime(str_replace('/', '-', $fechaCreacionAlarma)));

        $fechaCreacionAlarma2 = $inicioFecha . '<br>' . $inicioHora . ' hrs.';
    }

    if (empty($fechaNormalAlarma)) {
        $fechaNormalAlarma2 = 'ALARMA ACTIVA';
    } else {
        $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $fechaNormalAlarma)));
        $inicioHora = date('H:i', strtotime(str_replace('/', '-', $fechaNormalAlarma)));

        $fechaNormalAlarma2 = $inicioFecha . '<br>' . $inicioHora . ' hrs.';
    }

    $row['fechaCreacionAlarma'] = $fechaCreacionAlarma;
    $row['fechaCreacionAlarma2'] = $fechaCreacionAlarma2;
    $row['sourceAlarma'] = $sourceAlarma;
    $row['classAlarma'] = $classAlarma;
    $row['fechaNormalAlarma'] = $fechaNormalAlarma;
    $row['fechaNormalAlarma2'] = $fechaNormalAlarma2;
    $row['mensajeAlarma'] = $mensajeAlarma;
    $row['conteoAlarma'] = $conteoAlarma;


    // Agregar la fila modificada al array de resultados
    $datos[] = $row;
}

// Cerrar la conexión y liberar recursos
$stmt->close();
$conexion->close();

// Enviar los resultados modificados como respuesta JSON
echo json_encode($datos);

?>