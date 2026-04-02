<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

// Obtener la categoría seleccionada desde la petición POST
if (isset($_POST['idUCAMostrar'])) {

    $idUCAMostrar = $_POST['idUCAMostrar'];

    if ($idUCAMostrar > 0) {

        // Consulta SQL
        $sql = "SELECT * FROM ACS_DOOR_GROUP_DOOR WHERE deviceid = ? ORDER BY doorid ASC";


// Preparar la consulta
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $idUCAMostrar);
        $stmt->execute();




// Obtener los resultados
        $result = $stmt->get_result();
        $datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
        while ($row = $result->fetch_assoc()) {

            if ($row['dooruniqueid'] > -1) {
                $nomPuerta = obtenerNomPuerta2($row['dooruniqueid']);
                $grupoPuerta = $row['doorgroupid'];

                if (empty($nuevoNom)) {
                    if ($row['deviceid'] > 0 && $row['doorid'] > -1) {
                        $nomPuerta = obtenerNomPuerta($row['deviceid'], $row['doorid']);

                    } else {
                        $nomPuerta = 'Sin Información';
                    }
                } else {

                    $nomPuerta = $nomPuerta;
                }

            } else {
                $nomPuerta = 'Sin Información';
            }

            if ($row['doorgroupid'] > 0) {
                $nomGrupo = obtenerNameGrupoPuerta($row['doorgroupid']);

            } else {
                $nomGrupo = 'Sin Información';
            }


            $nomPuerta = mb_convert_case($nomPuerta, MB_CASE_UPPER, "UTF-8");
            $nomGrupo = mb_convert_case($nomGrupo, MB_CASE_UPPER, "UTF-8");

            $row["nomPuerta"] = $nomPuerta;
            $row["nomGrupo"] = $nomGrupo;
            $row["idGrupo"] = $grupoPuerta;

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