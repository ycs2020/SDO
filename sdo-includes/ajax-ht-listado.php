<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

// Obtener la categoría seleccionada desde la petición POST
if (isset($_POST['idDepartamento'])) {

    $idDepartamento = $_POST['idDepartamento'];

    if ($idDepartamento > 0) {

        // Consulta SQL
        $sql = "SELECT * FROM ordenes_trabajos_general WHERE idDepartamento = ? AND anulada < 1 ORDER BY fechaOrden DESC";

// Preparar la consulta
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $idDepartamento);
        $stmt->execute();

// Obtener los resultados
        $result = $stmt->get_result();
        $datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
        while ($row = $result->fetch_assoc()) {
            $folioOrden = $row["folioOrden"];
            $nomTecnicoRecepcionador = $row["nomTecnicoRecepcionador"];
            $nomSolicitante = $row["nomSolicitante"];

            $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $row['fechaOrden'])));
            $inicioHora = date('H:i', strtotime(str_replace('/', '-', $row['fechaOrden'])));

            $fechaOrden = $inicioFecha . ' a las ' . $inicioHora . ' hrs.';

            $folioOrden = mb_convert_case($folioOrden, MB_CASE_UPPER, 'UTF-8');
            $nomTecnicoRecepcionador = mb_convert_case($nomTecnicoRecepcionador, MB_CASE_UPPER, 'UTF-8');
            $nomSolicitante = mb_convert_case($nomSolicitante, MB_CASE_UPPER, 'UTF-8');

            $row['folioOrden'] = $folioOrden;
            $row['nomTecnicoRecepcionador'] = $nomTecnicoRecepcionador;
            $row['nomSolicitante'] = $nomSolicitante;
            $row['fechaOrden'] = $fechaOrden;


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