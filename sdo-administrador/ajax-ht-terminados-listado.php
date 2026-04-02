<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

// Obtener la categoría seleccionada desde la petición POST
if (isset($_POST['idTG'])) {

    $idTG = $_POST['idTG'];

    if (!empty($idTG)) {

        // Consulta SQL
        $sql = "SELECT * FROM ordenes_trabajos_general WHERE noOrdenTrabajo = ? ORDER BY fechaOrden DESC";

// Preparar la consulta
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $idTG);
        $stmt->execute();

// Obtener los resultados
        $result = $stmt->get_result();
        $datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
        while ($row = $result->fetch_assoc()) {
            $folioOrden = $row["folioOrden"];
            $ticketAsociado = $row["ticketAsociado"];
            $nomTecnicoRecepcionador = $row["nomTecnicoRecepcionador"];
            $nomSolicitante = $row["nomSolicitante"];
            $idOrden = $row["idOrden"];

            $obtenerCantidadArchivosHT = obtenerCantidadArchivosHT($idOrden);

            if($obtenerCantidadArchivosHT < 1){
                $obtenerCantidadArchivosHT = 0;
            }

            $folioOrden = mb_convert_case($folioOrden, MB_CASE_UPPER, 'UTF-8');
            $nomTecnicoRecepcionador = mb_convert_case($nomTecnicoRecepcionador, MB_CASE_UPPER, 'UTF-8');
            $nomSolicitante = mb_convert_case($nomSolicitante, MB_CASE_UPPER, 'UTF-8');

            if($ticketAsociado == 0 || empty($ticketAsociado)){
                $ticketAsociado = 'No Aplica';
            }
            $ticketAsociado = mb_convert_case($ticketAsociado, MB_CASE_UPPER, 'UTF-8');

            $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $row['fechaOrden'])));
            $inicioHora = date('H:i', strtotime(str_replace('/', '-', $row['fechaOrden'])));

            $fechaOrden = $inicioFecha . ' a las ' . $inicioHora . ' hrs.';

            $row['folioOrden'] = $folioOrden;
            $row['nomTecnicoRecepcionador'] = $nomTecnicoRecepcionador;
            $row['nomSolicitante'] = $nomSolicitante;
            $row['ticketAsociado'] = $ticketAsociado;
            $row['fechaOrden'] = $fechaOrden;
            $row['cantidadArchivosHT'] = $obtenerCantidadArchivosHT;


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