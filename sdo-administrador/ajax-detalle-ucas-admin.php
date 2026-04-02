<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

// Consulta SQL
$sql = "SELECT * FROM ucas_listado WHERE name NOT LIKE '%VTO%' ORDER BY CAST(SUBSTRING(name, LOCATE('P', name) + 1) AS SIGNED), name ASC;";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
while ($row = $result->fetch_assoc()) {

    $name = $row["name"];
    $idSalaUCA = $row["idSalaUCA"];
    $idUnique = $row["idUnique"];
    $moduloIncendioUCA = $row["moduloIncendioUCA"];

    if($idSalaUCA > 0){
        $obtenerNomSalaUCA = obtenerNomSalaUCA($idSalaUCA);
        $obtenerUbicacionSalaUCA = obtenerUbicacionSalaUCA($idSalaUCA);
        $obtenerPisoSalaUCA = obtenerPisoSalaUCA($idSalaUCA);
    }else{

        $obtenerNomSalaUCA = 'No Aplica';
        $obtenerUbicacionSalaUCA = 'No Aplica';
        $obtenerPisoSalaUCA = 'No Aplica';
    }

    if($moduloIncendioUCA > 0){
        $dataModuloIncendio = $moduloIncendioUCA;
    }else if($moduloIncendioUCA == 0){
        $dataModuloIncendio = 'Sin Conexión a Módulo';
    }else if($moduloIncendioUCA == -1){
        $dataModuloIncendio = 'No Existe Módulo';
    }else{
        $dataModuloIncendio = 'No Existe Información';
    }

    $dataModuloIncendio = mb_convert_case($dataModuloIncendio,MB_CASE_UPPER,'UTF-8');

    $row['name'] = $name;
    $row['idSalaUCA'] = $idSalaUCA;
    $row['obtenerNomSalaUCA'] = $obtenerNomSalaUCA;
    $row['obtenerUbicacionSalaUCA'] = $obtenerUbicacionSalaUCA;
    $row['obtenerPisoSalaUCA'] = $obtenerPisoSalaUCA;
    $row['moduloIncendioUCA'] = $dataModuloIncendio;
    $row['idUnique'] = $idUnique;


    // Agregar la fila modificada al array de resultados
    $datos[] = $row;
}

// Cerrar la conexión y liberar recursos
$stmt->close();
$conexion->close();

// Enviar los resultados modificados como respuesta JSON
echo json_encode($datos);

?>