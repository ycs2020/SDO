<?php

include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';

$idTG = 5066;
$nombreColumna = 'piso3';


// Control 1: Ver qué columna y qué ID estás intentando buscar
echo "DEBUG - Columna: " . $nombreColumna . " | ID: " . $idTG . "<br>";

$sql = "SELECT `$nombreColumna` FROM parametros_uma_bms WHERE idTG = ?";

if ($stmt = mysqli_prepare($conexion, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $idTG);

    // Control 2: Ver si la ejecución falla
    if (!mysqli_stmt_execute($stmt)) {
        echo "Error al ejecutar la consulta: " . mysqli_stmt_error($stmt) . "<br>";
    }

    $res = mysqli_stmt_get_result($stmt);

    // Control 3: Ver cuántas filas devolvió la base de datos
    echo "DEBUG - Filas encontradas: " . mysqli_num_rows($res) . "<br>";

    while ($row = mysqli_fetch_assoc($res)) {
        // Control 4: Ver qué datos están llegando realmente en cada fila
        echo "DEBUG - Fila encontrada: ";
        var_dump($row);
        echo "<br>";

        if (trim((string)$row[$nombreColumna]) !== '') {
            $datosConcatenados .= $row[$nombreColumna] . "\n";
        }
    }
    mysqli_stmt_close($stmt);
} else {
    // Control 5: Si mysqli_prepare devuelve false, aquí verás por qué
    echo "Error en la preparación de la consulta: " . mysqli_error($conexion) . "<br>";
}