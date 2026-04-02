<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


$datos = array();


// Iterar sobre los resultados y aplicar la lógica necesaria
$datos['argumento'] = $_POST['argumento'];
$datos['anio'] = $_POST['anio'];
// Agregar la fila modificada al array de resultados

// Enviar los resultados modificados como respuesta JSON
echo json_encode($datos);

?>