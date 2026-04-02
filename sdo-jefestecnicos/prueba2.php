<?php

include '../sdo-funciones/conexion.php';


// Consulta para obtener las filas que deben actualizarse
$sqlSelect = "SELECT idOrden FROM ordenes_trabajos_general 
              WHERE (fechaOrden LIKE '%2024-11-06%' AND nomTecnicoRecepcionador LIKE '%JOAQUÍN J%') 
              AND isVisado = 0 ORDER BY idOrden DESC";

$result = mysqli_query($conexion,$sqlSelect);

// Comprobar si hay resultados
if ($result->num_rows > 0) {
    // Iniciar la fecha base para la primera actualización
    $fechaBase = new DateTime('2024-11-06 08:40:00');

    // Procesar cada fila
    while ($row = $result->fetch_assoc()) {
        // Obtener el idOrden
        $idOrden = $row['idOrden'];

        // Convertir la fecha actual a un formato compatible con SQL
        $fechaVisado = $fechaBase->format('Y-m-d H:i:s');

        // Actualizar la fila con la fechaVisado correspondiente
        $sqlUpdate = "UPDATE ordenes_trabajos_general 
                      SET isVisado = 1, 
                          nombreVisado = 'Yorgo Casimis Subiabre', 
                          fechaVisado = '$fechaVisado' 
                      WHERE idOrden = $idOrden";

        if ($conexion->query($sqlUpdate) === TRUE) {
            echo "Registro actualizado correctamente: idOrden = $idOrden, fechaVisado = $fechaVisado<br>";
        } else {
            echo "Error actualizando el registro: " . $conexion->error . "<br>";
        }

        // Incrementar la fecha base en 2 minutos
        $fechaBase->modify('+2 minutes');
    }
} else {
    echo "No se encontraron registros que coincidan con los criterios.";
}

// Cerrar la conexión
$conexion->close();

