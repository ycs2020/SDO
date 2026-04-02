<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

if (isset($_POST['folioCarga'])) {


    $folioCarga = $_POST['folioCarga'];    //
    $usuarioCarga = $_POST['usuarioCarga'];


    if (!empty($folioCarga)) {

// Consulta SQL
        $sql = "SELECT * FROM ordenes_trabajos_general WHERE noOrdenTrabajo LIKE ? ORDER BY fechaOrden DESC;";

// Preparar la consulta
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $folioCarga);
        $stmt->execute();

// Obtener los resultados
        $result = $stmt->get_result();
        $datos = array();

        $numOrden = 1;
// Iterar sobre los resultados y aplicar la lógica necesaria
        while ($row = $result->fetch_assoc()) {
            $idOrden = $row['idOrden'];
            $nombreOrden = $row['nomOrden'];
            $ticketAsociado = $row['ticketAsociado'];
            $folioOrden  = $row['folioOrden'];
            $nomTecnicoRecepcionador = $row['nomTecnicoRecepcionador'];
            $nomSolicitante = $row['nomSolicitante'];
            $fechaOrden = $row['fechaOrden'];

            if(empty($ticketAsociado)){
                $ticketAsociado = 'Sin Información';
            }

            $fechaCompleta = 'Fecha inválida';

            $fechaRaw = $row['fechaOrden'] ?? '';

            if (!empty($fechaRaw)) {
                $timestamp = strtotime(str_replace('/', '-', $fechaRaw));

                if ($timestamp !== false && $timestamp > 0) {
                    $inicioFecha = date('d/m/Y', $timestamp);
                    $inicioHora  = date('H:i', $timestamp);
                    $fechaCompleta = $inicioFecha . '<br>' . $inicioHora;
                }
            }

            $nombreOrden = mb_convert_case($nombreOrden, MB_CASE_UPPER, "UTF-8");
            $ticketAsociado = mb_convert_case($ticketAsociado, MB_CASE_UPPER, "UTF-8");
//            $folioOrden = mb_convert_case($folioOrden, MB_CASE_UPPER, "UTF-8");
            $nomTecnicoRecepcionador = mb_convert_case($nomTecnicoRecepcionador, MB_CASE_UPPER, "UTF-8");
            $nomSolicitante2 = mb_convert_case($nomSolicitante, MB_CASE_UPPER, "UTF-8");
            $usuarioCarga = mb_convert_case($usuarioCarga, MB_CASE_UPPER, "UTF-8");

            $buttonDescarga = '';
            $nomSolicitanteEsc = htmlspecialchars($nomSolicitante, ENT_QUOTES, 'UTF-8');
            $usuarioCargaEsc   = htmlspecialchars($usuarioCarga, ENT_QUOTES, 'UTF-8');
            $folioOrdenEsc   = htmlspecialchars($folioOrden, ENT_QUOTES, 'UTF-8');

            if (strcasecmp($nomSolicitante, $usuarioCarga) === 0) {
                $buttonDescarga = "<button class='btn btn-success btn-sm btn-block btn-visualizar-ht' data-idordenht='$idOrden'><i class='fas fa-eye'></i></button>";
            } else if ($idOrden > 0) {
                $buttonDescarga = "<button class='btn btn-success btn-sm btn-block btn-visualizar-ht' data-idordenht='$idOrden'><i class='fas fa-eye'></i></button>";
            } else {
                $buttonDescarga = "<span class='btn btn-danger btn-sm'>ID HT No Válido</span>";
            }

            $countIsVisadoHT = countIsVisadoHT($idOrden);

            $buttonVisado = '';

            if (strcasecmp($nomSolicitante, $usuarioCarga) === 0) {
                if ($countIsVisadoHT == 1) {
                    $buttonVisado = 'HT Visada';
                } else {
                    $buttonVisado = "<button class='btn btn-warning btn-sm btn-block btn-visarht' data-idordenht='$idOrden' data-solicitante='$nomSolicitanteEsc' data-folio='$folioOrdenEsc'><i class='fas fa-file-signature'></i></button>";
                }
            } else {
                $buttonVisado = "No Autorizado";
            }



            $row['numOrden'] = $numOrden;
            $row['nomOrden'] = $nombreOrden;
            $row['ticketAsociado'] = $ticketAsociado;
            $row['folioOrden'] = $folioOrden;
            $row['nomTecnicoRecepcionador'] = $nomTecnicoRecepcionador;
            $row['nomSolicitante'] = $nomSolicitante;
            $row['nomSolicitante2'] = $nomSolicitante2;
            $row['fechaOrden'] = $fechaOrden;
            $row['fechaCompleta'] = $fechaCompleta;
            $row['buttonDescarga'] = $buttonDescarga;
            $row['buttonVisado'] = $buttonVisado;


            $numOrden++;


            // Agregar la fila modificada al array de resultados
            $datos[] = $row;
        }

// Cerrar la conexión y liberar recursos
        $stmt->close();
        $conexion->close();

// Enviar los resultados modificados como respuesta JSON
        echo json_encode($datos);
    } else {
        return '';
    }

} else {
    return '';
}

?>