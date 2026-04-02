<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(isset($_POST['nomDeptoEnviar'])){

    $nomDeptoEnviar = $_POST['nomDeptoEnviar'];

    if(!empty($nomDeptoEnviar)){

        if($nomDeptoEnviar == 'Equipos Industriales'){
            $sql = "SELECT * FROM solicitudes_compra_sdo WHERE unidadSoliCompra LIKE 'Equipos Industriales' OR unidadSoliCompra LIKE 'Equipos de Apoyo Clínico' OR unidadSoliCompra LIKE 'Incendio y Seguridad' ORDER BY idSoliCompra DESC";
        }else if($nomDeptoEnviar == 'Instrumentación' || $nomDeptoEnviar == 'Control Centralizado'){
            $sql = "SELECT * FROM solicitudes_compra_sdo WHERE unidadSoliCompra LIKE 'Control Centralizado' OR unidadSoliCompra LIKE 'Instrumentación' ORDER BY idSoliCompra DESC";
        }else if($nomDeptoEnviar == 'Incendio y Seguridad'){
            $sql = "SELECT * FROM solicitudes_compra_sdo WHERE unidadSoliCompra LIKE 'Equipos Industriales' OR unidadSoliCompra LIKE 'Incendio y Seguridad' ORDER BY idSoliCompra DESC";
        }else{
            $sql = "SELECT * FROM solicitudes_compra_sdo WHERE unidadSoliCompra LIKE '" . $nomDeptoEnviar . "' ORDER BY idSoliCompra DESC";
        }

// Preparar la consulta
        $stmt = $conexion->prepare($sql);
        $stmt->execute();

// Obtener los resultados
        $result = $stmt->get_result();
        $datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
        while ($row = $result->fetch_assoc()) {
// Asignación de variables para cada columna
            $idSoliCompra = $row["idSoliCompra"];
            $numSoliCompra = $row["numSoliCompra"];
            $folioSoliCompra = $row["folioSoliCompra"];
            $descripcionSoliCompra = $row["descripcionSoliCompra"];
            $fechaSoliCompra = $row["fechaSoliCompra"];
            $personalSoliCompra = $row["personalSoliCompra"];
            $unidadSoliCompra = $row["unidadSoliCompra"];
            $emailSoliCompra = $row["emailSoliCompra"];
            $argumentosSoliCompra = $row["argumentosSoliCompra"];
            $adjuntaCotizacion = $row["adjuntaCotizacion"];
            $estadoSoliCompra = $row["estadoSoliCompra"];
            $notificacion = $row["notificacion"];
            $isCotizacion = $row["isCotizacion"];
            $isRevisado = $row["isRevisado"];
            $idEquipo = $row["idEquipo"];
            $idCategoria = $row["idCategoria"];
            $idSolicitudSoporte = $row["idSolicitudSoporte"];
            $numTicketSIGH = $row["numTicketSIGH"];


            if(empty($_SESSION['nomUsuario'])){
                $nomUsuario2 = 'No Aplica';
            }else{
                $nomUsuario2 = $_SESSION['nomUsuario'];
            }


            $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $fechaSoliCompra)));
            $inicioHora = date('H:i', strtotime(str_replace('/', '-', $fechaSoliCompra)));

            $fechaSoliCompra2 = $inicioFecha . '<br>' . $inicioHora . ' hrs.';


            $personalSoliCompra = mb_convert_case($personalSoliCompra,MB_CASE_UPPER,'UTF-8');
            $unidadSoliCompra = mb_convert_case($unidadSoliCompra,MB_CASE_UPPER,'UTF-8');

            $personalSeparar = explode('(', $personalSoliCompra);


            $personalSoliCompra = explode(' (', $personalSoliCompra);

            if (empty($personalSoliCompra[1])) {
                $personalSoliCompra2 = $personalSoliCompra[0] . '&nbsp;<br>(' . $unidadSoliCompra . ')';
            } else {
                $personalSoliCompra2 = $personalSoliCompra[0] . '&nbsp;<br>(' . $personalSoliCompra[1] . '&nbsp;<br>(' . $unidadSoliCompra . ')';
            }

            if(empty($descripcionSoliCompra)){
                $descripcionSoliCompra = 'No Aplica';
            }

            $descripcionSoliCompra2 = mb_convert_case($descripcionSoliCompra,MB_CASE_UPPER,'UTF-8');
            $estadoSoliCompra2 = mb_convert_case($estadoSoliCompra,MB_CASE_UPPER,'UTF-8');

            if($nomUsuario2 == 'Carola Avila' && $estadoSoliCompra2 == 'SOLICITADA A SECRETARIA SDO'){
                if($isRevisado > 0){
                    $opcion2 = '1';
                }else{
                    $opcion2 = 'revisadoSecre';
                }
            }else{

                $opcion2 = '1';
            }

            $textoTicket = '';

            if($numTicketSIGH > 0){
                $textoTicket .= 'Ticket SIGH: N° '.$numTicketSIGH;
            }else{
                $textoTicket .= 'Ticket SIGH: No Aplica.';
            }

            $textoTicket2 = mb_convert_case($textoTicket,MB_CASE_UPPER,'UTF-8');

            $contarCotizacionesSoliCompraSDO = contarCotizacionesSoliCompraSDO($idSoliCompra);

// Asignación de variables nuevamente al arreglo $row
            $row["idSoliCompra"] = $idSoliCompra;
            $row["numSoliCompra"] = $numSoliCompra;
            $row["folioSoliCompra"] = $folioSoliCompra;
            $row["descripcionSoliCompra"] = $descripcionSoliCompra2;
            $row["fechaSoliCompra"] = $fechaSoliCompra2;
            $row["personalSoliCompra"] = $personalSoliCompra2;
            $row["unidadSoliCompra"] = $unidadSoliCompra;
            $row["emailSoliCompra"] = $emailSoliCompra;
            $row["argumentosSoliCompra"] = $argumentosSoliCompra;
            $row["adjuntaCotizacion"] = $adjuntaCotizacion;
            $row["estadoSoliCompra"] = $estadoSoliCompra2;
            $row["notificacion"] = $notificacion;
            $row["isCotizacion"] = $isCotizacion;
            $row["isRevisado"] = $isRevisado;
            $row["idEquipo"] = $idEquipo;
            $row["idCategoria"] = $idCategoria;
            $row["idSolicitudSoporte"] = $idSolicitudSoporte;
            $row['numTicketSIGH'] = $numTicketSIGH;
            $row["nomUsuarioVisual"] = $nomUsuario2;
            $row["opcion2"] = $opcion2;
            $row['contarCotizacion'] = $contarCotizacionesSoliCompraSDO;
            $row['sql'] = $sql;
            $row['textoTicket'] = $textoTicket2;




            // Agregar la fila modificada al array de resultados
            $datos[] = $row;
        }

// Cerrar la conexión y liberar recursos
        $stmt->close();
        $conexion->close();

// Enviar los resultados modificados como respuesta JSON
        echo json_encode($datos);
    }else{
        echo '';
    }

}else{
    echo '';
}

?>