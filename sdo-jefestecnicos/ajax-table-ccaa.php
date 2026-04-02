<?php

// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

// Consulta SQL
$sql = "SELECT * FROM ACM_owner ORDER BY OWNER_Name, DEPT_ID ASC";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
while ($row = $result->fetch_assoc()) {
//
//    $idCCAA = $row["ID"];
//    $rutCCAA = $row["OWNER_CODE"];
//    $nombreCCAA = $row["OWNER_Name"];
//    $tarjetaCCAA = $row["CARD_NUMBER"];
//    $idDeptoCCAA = $row["DEPT_ID"];
//
//    $row1['ID'] = $idCCAA;
//    $row1['OWNER_CODE'] = $rutCCAA;
//    $row1['nombreCCAA'] = $nombreCCAA;
//    $row1['tarjetaCCAA'] = $tarjetaCCAA;
//    $row1['idDeptoCCAA'] = $idDeptoCCAA;


    // Agregar la fila modificada al array de resultados
    $datos[] = $row1;
}

// Cerrar la conexión y liberar recursos
$stmt->close();
$conexion->close();

// Enviar los resultados modificados como respuesta JSON
echo json_encode($datos);

