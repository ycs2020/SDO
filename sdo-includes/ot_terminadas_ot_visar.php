<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

if (isset($_POST['idCarga'])) {


    $idCarga = $_POST['idCarga'];    //
    $usuarioCarga = $_POST['usuarioCarga'];


    if (!empty($idCarga) && $idCarga > 0) {

// Consulta SQL
        $sql = "SELECT * FROM trabajos_general WHERE estadoTG = 'Trabajo Terminado' AND idTG = ? ORDER BY fechaTG DESC";

// Preparar la consulta
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idCarga);
        $stmt->execute();

// Obtener los resultados
        $result = $stmt->get_result();
        $datos = array();

        $numOrden = 1;
// Iterar sobre los resultados y aplicar la lógica necesaria
        while ($row = $result->fetch_assoc()) {
            $idTG = $row['idTG'];
            $tecnico = $row['tecnico1'];
            $fechaTG = $row['fechaTG'];
            $turnoTG = $row['turnoTG'];
            $solicitanteTG = $row['solicitanteTG'];
            $estadoTG = $row['estadoTG'];
            $folioTG = $row['folioTG'];

            $tecnico2 = mb_convert_case(trim($tecnico), MB_CASE_UPPER, "UTF-8");
            $turnoTG2 = mb_convert_case(trim($turnoTG), MB_CASE_UPPER, "UTF-8");
            $solicitanteTG2 = mb_convert_case(trim($solicitanteTG), MB_CASE_UPPER, "UTF-8");
            $estadoTG2 = mb_convert_case(trim($estadoTG), MB_CASE_UPPER, "UTF-8");
            $folioTG2 = mb_convert_case(trim($folioTG), MB_CASE_UPPER, "UTF-8");

            $fechaCompleta = 'Fecha inválida';

            $fechaRaw = $row['fechaTG'] ?? '';

            if (!empty($fechaRaw)) {
                $timestamp = strtotime(str_replace('/', '-', $fechaRaw));

                if ($timestamp !== false && $timestamp > 0) {
                    $inicioFecha = date('d/m/Y', $timestamp);
                    $inicioHora  = date('H:i', $timestamp);
                    $fechaCompleta = $inicioFecha . '<br>' . $inicioHora;
                }
            }



            $buttonDescarga = '';
            $solicitanteTGEsc = htmlspecialchars($solicitanteTG, ENT_QUOTES, 'UTF-8');
            $usuarioCargaEsc   = htmlspecialchars($usuarioCarga, ENT_QUOTES, 'UTF-8');
            $idTGEsc   = htmlspecialchars($idTG, ENT_QUOTES, 'UTF-8');

            if (strcasecmp($solicitanteTG, $usuarioCarga) === 0) {
                $buttonDescarga = "<button class='btn btn-success btn-sm btn-block btn-visualizar-ot' data-idot='$idTGEsc'><i class='fas fa-eye'></i></button>";
            } else if ($idTGEsc > 0) {
                $buttonDescarga = "<button class='btn btn-success btn-sm btn-block btn-visualizar-ot' data-idot='$idTGEsc'><i class='fas fa-eye'></i></button>";
            } else {
                $buttonDescarga = "<span class='btn btn-danger btn-sm'>ID OT No Válido</span>";
            }

            $countIsVisadoOT = countIsVisadoOT($idTG);

            $buttonVisado = '';

            if (strcasecmp($solicitanteTG, $usuarioCarga) === 0) {
                if ($countIsVisadoOT == 1) {
                    $buttonVisado = 'OT Visada';
                } else {
                    $buttonVisado = "<button class='btn btn-warning btn-sm btn-block btn-visarot' data-idot='$idTG' data-solicitante='$solicitanteTG' ><i class='fas fa-file-signature'></i></button>";
                }
            } else {
                $buttonVisado = "No Autorizado";
            }



            $row['numOrden'] = $numOrden;
            $row['idTG'] = $idTG;
            $row['tecnico'] = $tecnico;
            $row['tecnico2'] = $tecnico2;
            $row['fechaTG'] = $fechaTG;
            $row['turnoTG'] = $turnoTG;
            $row['solicitanteTG'] = $solicitanteTG;
            $row['estadoTG'] = $estadoTG;
            $row['folioTG'] = $folioTG;
            $row['fechaCompleta'] = $fechaCompleta;
            $row['turnoTG2'] = $turnoTG2;
            $row['solicitanteTG2'] = $solicitanteTG2;
            $row['estadoTG2'] = $estadoTG2;
            $row['folioTG2'] = $folioTG2;
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