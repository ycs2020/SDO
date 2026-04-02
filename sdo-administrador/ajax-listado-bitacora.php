<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

// Obtener la categoría seleccionada desde la petición POST
if (isset($_POST['idBitacoraAjax'])) {

    $idBitacoraAjax = $_POST['idBitacoraAjax'];

    if ($idBitacoraAjax > 0) {

        // Consulta SQL
        $sql = "SELECT * FROM bitacora ORDER BY fechaBitacora DESC";

// Preparar la consulta
        $stmt = $conexion->prepare($sql);
        $stmt->execute();

// Obtener los resultados
        $result = $stmt->get_result();
        $datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
        while ($row = $result->fetch_assoc()) {
            $observacionBitacora = $row["observacionBitacora"];
            $fechaBitacora = $row["fechaBitacora"];
            $tecnicoBitacora = $row["tecnicoBitacora"];
            $idSubcategoria = $row["idSubcategoria"];


            $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $fechaBitacora)));
            $inicioHora = date('H:i', strtotime(str_replace('/', '-', $fechaBitacora)));

            $fechaBitacora2 = $inicioFecha . ' a las ' . $inicioHora . ' hrs.';



            if (!empty($observacionBitacora)) {

                $observacionBitacora = str_replace("'", "\'", $observacionBitacora);
            }else{
                $observacionBitacora = 'No Aplica';
            }

            $tecnicoBitacora = mb_convert_case($tecnicoBitacora, MB_CASE_TITLE, 'UTF-8');

            if($idSubcategoria > 0){
                $nomSubCategoria = getNomSubCategoria($idSubcategoria);
            }else{
                $nomSubCategoria = 'No Aplica';
            }

            $row['observacionBitacora'] = $observacionBitacora;
            $row['tecnicoBitacora'] = $tecnicoBitacora;
            $row['fechaBitacora'] = $fechaBitacora2;
            $row['nomSubCategoria'] = $nomSubCategoria;


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