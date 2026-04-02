<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

// Consulta SQL
$sql = "SELECT * FROM documentos_generados ORDER BY fechaDoc DESC;";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
//$stmt->bind_param("s", $idDepartamento);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
while ($row = $result->fetch_assoc()) {
    $idDoc = $row["idDoc"];
    $tituloDoc = $row["tituloDoc"];
    $fechaDoc = $row["fechaDoc"];
    $creadorDoc = $row["creadorDoc"];

    $tituloDoc = mb_convert_case($tituloDoc, MB_CASE_UPPER, 'UTF-8');

    if(empty($fechaDoc)){
        $fechaCompleta = 'No Aplica';
    }else{
        $fechaDoc2 = strtotime($fechaDoc);

        $fecha = fechaEspañol2($fechaDoc2);
        $hora = date('H:i',$fechaDoc2);

        $fechaCompleta = $fecha.'<br>'.$hora.' hrs.';
    }

    $fechaDoc = mb_convert_case($fechaDoc, MB_CASE_UPPER, 'UTF-8');
    $creadorDoc = mb_convert_case($creadorDoc, MB_CASE_UPPER, 'UTF-8');

    $row['idDoc'] = $idDoc;
    $row['tituloDoc'] = $tituloDoc;
    $row['fechaCompleta'] = $fechaCompleta;
    $row['creadorDoc'] = $creadorDoc;


    // Agregar la fila modificada al array de resultados
    $datos[] = $row;
}

// Cerrar la conexión y liberar recursos
$stmt->close();
$conexion->close();

// Enviar los resultados modificados como respuesta JSON
echo json_encode($datos);

?>