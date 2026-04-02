<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

// Obtener la categoría seleccionada desde la petición POST
if (isset($_POST['idTicketSIGH'])) {

    $idTicketSIGH = $_POST['idTicketSIGH'];

    if ($idTicketSIGH > 0) {

        // Consulta SQL
        $sql = "SELECT * FROM observaciones_ticket_sigh WHERE idSolicitudTicket = ? ORDER BY fecha DESC";

// Preparar la consulta
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $idTicketSIGH);
        $stmt->execute();

// Obtener los resultados
        $result = $stmt->get_result();

        // Array para almacenar las observaciones
        $observaciones = array();

        if ($result->num_rows > 0) {
            // Recorrer los resultados y almacenarlos en el array
            while($row = $result->fetch_assoc()) {
                $observaciones[] = $row;
            }
        }

        // Devolver los datos en formato JSON
        header('Content-Type: application/json');
        echo json_encode($observaciones);
    }

} else {
    // Si no se recibió el ID del ticket, devolver un mensaje de error
    echo "Error: ID del ticket no recibido";
}

?>