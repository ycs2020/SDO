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
            $ticketAsociado = $row["ticketAsociado"];
            $idOrden1 = $row['idOrden'];
            $recinto = $row['recintoOrden'];
            $idEquipo = $row['idEquipo'];

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

            // Verificar archivos asociados a la orden
            $sqlArchivo = "SELECT count(*) as cantidad FROM archivos_orden_general WHERE idOrden = $idOrden1";
            $resArchivo = mysqli_query($conexion, $sqlArchivo);
            $cantidad = 0;
            if ($rowArchivo = mysqli_fetch_assoc($resArchivo)) {
                $cantidad = $rowArchivo['cantidad'];
            }

            // Obtener el numeroSerie del equipo
            $numeroSerie = 'Sin información'; // Inicializar la variable por si no se encuentra
            $nombreEquipo = 'Sin información';
            $numeroInventario = 'Sin información';
            if ($idEquipo > 0) {
                $sqlEquipo = "SELECT numeroSerie,nombreEquipo,numeroInventario FROM equipamiento_hbqp WHERE id = $idEquipo";
                $resEquipo = mysqli_query($conexion, $sqlEquipo);
                if ($rowEquipo = mysqli_fetch_assoc($resEquipo)) {
                    $numeroSerie = $rowEquipo['numeroSerie'];
                    $nombreEquipo = $rowEquipo['nombreEquipo'];
                    $numeroInventario = $rowEquipo['numeroInventario'];
                }
            }



            // Convertir los textos a mayúsculas
            $folioOrden = mb_convert_case($folioOrden, MB_CASE_UPPER, 'UTF-8');
            $nomTecnicoRecepcionador = mb_convert_case($nomTecnicoRecepcionador, MB_CASE_UPPER, 'UTF-8');
            $nomSolicitante = mb_convert_case($nomSolicitante, MB_CASE_UPPER, 'UTF-8');

            // Asignar los valores al array de resultados
            $row['folioOrden'] = $folioOrden;
            $row['nomTecnicoRecepcionador'] = $nomTecnicoRecepcionador;
            $row['nomSolicitante'] = $nomSolicitante;
            $row['fechaOrden'] = $fechaOrden;
            $row['ticketAsociado'] = $ticketAsociado;
            $row['diferencia'] = $diferenciaFinal;
            $row['cantidad'] = $cantidad;
            $row['recintoOrden'] = $recinto;
            $row['numeroSerie'] = $numeroSerie;
            $row['nombreEquipo'] = $nombreEquipo;
            $row['numeroInventario'] = $numeroInventario;
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
