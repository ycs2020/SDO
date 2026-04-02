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
        $sql = "SELECT * FROM ordenes_trabajos_general WHERE idDepartamento = ? ORDER BY fechaOrden DESC";

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
            $ticketAsociado = $row["ticketAsociado"];
            $idOrden1 = $row['idOrden'];

            $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $row['fechaOrden'])));
            $inicioHora = date('H:i', strtotime(str_replace('/', '-', $row['fechaOrden'])));

            $fechaOrden = $inicioFecha . ' a las ' . $inicioHora . ' hrs.';

            if ($row['horaInicio'] == 0 || empty($row['horaInicio'])) {
                $diferenciaFinal = 'Sin Información';
            }else{
                if ($row['horaTermino'] == 0 || empty($row['horaTermino'])) {
                    $diferenciaFinal = 'Sin Información';
                }else{

                    $horaInicio = new DateTime($row['horaInicio']);
                    $horaTermino = new DateTime($row['horaTermino']);
                    $diferencia = $horaTermino->diff($horaInicio);
                    $diferenciaFinal = "Diferencia: " . $diferencia->days . " días, " . $diferencia->h . " horas, " . $diferencia->i . " minutos";
                }
            }

            if($ticketAsociado < 1){
                $ticketAsociado = 'No Aplica.';
            }

            $sqlArchivo = "SELECT count(*) as cantidad FROM archivos_orden_general WHERE idOrden = $idOrden1 ";
            $resArchivo = mysqli_query($conexion, $sqlArchivo);

            while ($rowArchivo = $resArchivo->fetch_assoc()) {
                $datos = [];
                $datos['cantidad'] = $rowArchivo['cantidad'];
                $cantidad = $datos['cantidad'];
            }

            $folioOrden = mb_convert_case($folioOrden, MB_CASE_UPPER, 'UTF-8');
            $nomTecnicoRecepcionador = mb_convert_case($nomTecnicoRecepcionador, MB_CASE_UPPER, 'UTF-8');
            $nomSolicitante = mb_convert_case($nomSolicitante, MB_CASE_UPPER, 'UTF-8');

            $row['folioOrden'] = $folioOrden;
            $row['nomTecnicoRecepcionador'] = $nomTecnicoRecepcionador;
            $row['nomSolicitante'] = $nomSolicitante;
            $row['fechaOrden'] = $fechaOrden;
            $row['ticketAsociado'] = $ticketAsociado;
            $row['diferencia'] = $diferenciaFinal;

            // Agregar la fila modificada al array de resultados
            $datos[] = $row;
            $datos1[] = $rowArchivo;
        }

// Cerrar la conexión y liberar recursos
        $stmt->close();
        $conexion->close();

// Enviar los resultados modificados como respuesta JSON
        //echo json_encode($datos);
         $response = array(
             'datos' => $datos,
             'datos1' => $datos1
         );

         echo json_encode($response);
    }

}

?>