<?php
// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

// Verificar si se ha enviado la solicitud POST con los parámetros requeridos
if (isset($_POST['idCarpeta']) && isset($_POST['variableBuscar'])) {

    // Obtener los parámetros de la solicitud POST
    $idCarpeta = $_POST['idCarpeta'];
    $variableBuscar = $_POST['variableBuscar'];

    // Verificar si el valor de idCarpeta es mayor que 0
    if ($idCarpeta > 0) {

        // Inicializar un array para almacenar los datos de los equipos
        $datos = array();

        // Preparar la consulta SQL utilizando una consulta preparada para evitar la inyección de SQL
        $sql = "SELECT * FROM equipamiento_hbqp WHERE (nombreEquipo LIKE ? OR numeroInventario LIKE ?) AND idCategoria = ? ORDER BY nombreEquipo ASC";

        // Preparar la consulta SQL
        $stmt = $conexion->prepare($sql);

        // Verificar si la preparación de la consulta fue exitosa
        if ($stmt) {
            // Vincular los parámetros a la consulta preparada
            $variableBuscar = '%' . $variableBuscar . '%';
            $stmt->bind_param("ssi", $variableBuscar, $variableBuscar, $idCarpeta);

            // Ejecutar la consulta preparada
            $stmt->execute();

            // Obtener el resultado de la consulta
            $result = $stmt->get_result();

            // Verificar si se encontraron resultados
            if ($result->num_rows > 0) {
                // Iterar sobre los resultados y agregarlos al array $datos
                while ($row = $result->fetch_assoc()) {
                    $nombreEquipo = $row['nombreEquipo'];
                    $idEquipoPadre = $row['idEquipoPadre'];
                    //$numeroInventario = $row['numeroInventario'];
                    $rutaEquipo = '';

                    if(!empty($nombreEquipo)){
                        $nombreEquipo2 = mb_convert_case($nombreEquipo,MB_CASE_UPPER,'UTF-8');
                    }else{
                        $nombreEquipo2 = 'NO APLICA.';
                    }

                    $rutaEquipo = $nombreEquipo2;

                    if($idEquipoPadre > 0){

                        while($idEquipoPadre > 0){

                            $nomEquipamiento = buscarequipo($idEquipoPadre);

                            $nomEquipamiento = trim($nomEquipamiento);

                            $rutaEquipo = $nomEquipamiento.' // '.$rutaEquipo;

                            $idEquipoPadre = obtenerIDEquipamientoPadre($idEquipoPadre);
                        }

                    }

                    $row['rutaEquipo'] = $rutaEquipo;

                    $datos[] = $row;
                }
            }
            // Liberar el resultado de la consulta
            $result->free();

            // Cerrar la consulta preparada
            $stmt->close();
        } else {
            // Si la preparación de la consulta falló, agregar un mensaje de error al array $datos
            $datos['error'] = 'Error en la preparación de la consulta';
        }

        // Devolver los datos en formato JSON
        echo json_encode($datos);
    } else {
        // Si idCarpeta es 0 o menor, devolver un mensaje de error
        echo json_encode(array('error' => 'El valor de idCarpeta no es válido'));
    }
} else {
    // Si no se han proporcionado todos los parámetros requeridos, devolver un mensaje de error
    echo json_encode(array('error' => 'No se han proporcionado todos los parámetros requeridos'));
}

// Cerrar la conexión a la base de datos
$conexion->close();
?>
