<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (isset($_POST['idEquipoAjax'])) {

    $idEquipoAjax = $_POST['idEquipoAjax'];
    $idAnadir = $_POST['idAnadir'];

    if ($idEquipoAjax > 0) {

        if($idAnadir == 'Si'){
            // Consulta SQL
            $sql = "SELECT * FROM ordenes_trabajos_general WHERE (idEquipo is NULL AND idCategoria is NULL) OR (idEquipo = 0 OR idCategoria = 0) ORDER BY idOrden DESC";
        }else{
            // Consulta SQL
            $sql = "SELECT * FROM ordenes_trabajos_general WHERE idEquipo = ? ORDER BY fechaOrden DESC";
        }

// Preparar la consulta
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $idEquipoAjax);
        $stmt->execute();


// Obtener los resultados
        $result = $stmt->get_result();
        $datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
        while ($row = $result->fetch_assoc()) {
            $folioOrden = $row["folioOrden"];
            $nomTecnicoRecepcionador = $row["nomTecnicoRecepcionador"];
            $tipoMantencion = $row["tipoMantencion"];
            $ticketAsociado = $row["ticketAsociado"];
            $idOrden1 = $row['idOrden'];

            $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $row['fechaOrden'])));
            $inicioHora = date('H:i', strtotime(str_replace('/', '-', $row['fechaOrden'])));

            $fechaOrden = $inicioFecha . ' a las ' . $inicioHora . ' hrs.';

            if ($row['horaInicio'] == 0 || empty($row['horaInicio'])) {
                $diferenciaFinal = 'Sin Información';
            } else {
                if ($row['horaTermino'] == 0 || empty($row['horaTermino'])) {
                    $diferenciaFinal = 'Sin Información';
                } else {

                    $horaInicio = new DateTime($row['horaInicio']);
                    $horaTermino = new DateTime($row['horaTermino']);
                    $diferencia = $horaTermino->diff($horaInicio);
                    $diferenciaFinal = "Diferencia: " . $diferencia->days . " días, " . $diferencia->h . " horas, " . $diferencia->i . " minutos";
                }
            }

            if ($ticketAsociado < 1) {
                $ticketAsociado = 'No Aplica.';
            }

            $sqlArchivo = "SELECT count(*) as cantidad FROM archivos_orden_general WHERE idOrden = $idOrden1";
            $resArchivo = mysqli_query($conexion, $sqlArchivo);

            while ($rowArchivo = $resArchivo->fetch_assoc()) {
                $cantidad = $rowArchivo['cantidad'];
                $row['cantidad'] = $cantidad;
            }

            $folioOrden = mb_convert_case($folioOrden, MB_CASE_UPPER, 'UTF-8');
            $nomTecnicoRecepcionador = mb_convert_case($nomTecnicoRecepcionador, MB_CASE_UPPER, 'UTF-8');
            $tipoMantencion = mb_convert_case($tipoMantencion, MB_CASE_UPPER, 'UTF-8');

            $row['folioOrden'] = $folioOrden;
            $row['nomTecnicoRecepcionador'] = $nomTecnicoRecepcionador;
            $row['tipoMantencion'] = $tipoMantencion;
            $row['fechaOrden'] = $fechaOrden;
            $row['ticketAsociado'] = $ticketAsociado;
            $row['diferencia'] = $diferenciaFinal;
            $row['cantidad'] = $cantidad;

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