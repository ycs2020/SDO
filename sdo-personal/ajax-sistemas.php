<?php
// Archivo: tu_servidor.php

// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

// Obtener la categoría seleccionada desde la petición POST
if (isset($_POST['categoria'])) {

    $categoria = $_POST['categoria'];

    if (!empty($categoria)) {

        // Consulta SQL
        $sql = "SELECT * FROM equipos WHERE idSubCategoria = ? ORDER BY nomEquipo ASC";

// Preparar la consulta
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $categoria);
        $stmt->execute();

// Obtener los resultados
        $result = $stmt->get_result();
        $datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
        while ($row = $result->fetch_assoc()) {
            $nomEquipo = $row["nomEquipo"];
            $idEquipo = $row["idEquipo"];

            if ($row['equipoPabellon'] > 0) {
                $nomEquipo = $nomEquipo . ' ( Pabellon ' . $row['equipoPabellon'] . ' )';
            }else{
                $nomEquipo = $nomEquipo;
            }

            $nomEquipo = mb_convert_case($nomEquipo, MB_CASE_UPPER, 'UTF-8');

            $row['nomEquipo'] = $nomEquipo;

            // Agregar la fila modificada al array de resultados
            $datos[] = $row;
        }

// Cerrar la conexión y liberar recursos
        $stmt->close();
        $conexion->close();

// Enviar los resultados modificados como respuesta JSON
        echo json_encode($datos);
    }

}

?>