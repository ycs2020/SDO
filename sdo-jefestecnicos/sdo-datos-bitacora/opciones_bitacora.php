<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';
// Obtener el ID del POST

// Recupera las opciones desde la base de datos

$obtenerIDBitacora = obtenerIDBitacora();

$query = "SELECT * FROM subcategorias WHERE idCategoria = ".$obtenerIDBitacora." ORDER BY nomSubcategoria ASC";
$result = mysqli_query($conexion,$query);

// Verifica si hay resultados
if ($result->num_rows > 0) {
    $options = array();
    while ($row = $result->fetch_assoc()) {
        $options[] = array('value' => $row['idSubcategoria'], 'text' => $row['nomSubcategoria']);
    }
}

// Cierra la conexión a la base de datos

mysqli_close($conexion);

// Devuelve las opciones como JSON
echo json_encode($options);