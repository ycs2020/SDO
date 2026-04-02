<?php
// Archivo: bms_registroAlarmas.php

// Conexión a la base de datos
include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if(isset($_POST['source'], $_POST['mensaje'])) {
    $source = $_POST['source'];
    $mensaje = $_POST['mensaje'];

    if(!empty($source) && !empty($mensaje)) {
        // Consulta SQL
        $sql = "SELECT fechaCreacionAlarma, fechaNormalAlarma FROM bms_alarmas WHERE sourceAlarma = '$source' AND mensajeAlarma = '$mensaje' ORDER BY fechaCreacionAlarma DESC";

        // Preparar la consulta
        $stmt = $conexion->prepare($sql);
        $stmt->execute();

        // Obtener los resultados
        $result = $stmt->get_result();
        $datos = array();

        // Iterar sobre los resultados y aplicar la lógica necesaria
        while ($row = $result->fetch_assoc()) {
            $fechaCreacionAlarma = $row["fechaCreacionAlarma"];
            $fechaNormalAlarma = $row["fechaNormalAlarma"];

            if (empty($fechaCreacionAlarma)) {
                $fechaCreacionAlarma2 = 'Sin Informacion';
            } else {
                $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $fechaCreacionAlarma)));
                $inicioHora = date('H:i', strtotime(str_replace('/', '-', $fechaCreacionAlarma)));
                $fechaCreacionAlarma2 = $inicioFecha . ' a las ' . $inicioHora . ' hrs.';
            }

            if (empty($fechaNormalAlarma)) {
                $fechaNormalAlarma2 = 'ALARMA ACTIVA';
            } else {
                $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $fechaNormalAlarma)));
                $inicioHora = date('H:i', strtotime(str_replace('/', '-', $fechaNormalAlarma)));
                $fechaNormalAlarma2 = $inicioFecha . ' a las ' . $inicioHora . ' hrs.';
            }

            $row['fechaCreacionAlarma2'] = $fechaCreacionAlarma2;
            $row['fechaNormalAlarma2'] = $fechaNormalAlarma2;

            // Agregar la fila modificada al array de resultados
            $datos[] = $row;
        }

        // Cerrar la conexión y liberar recursos
        $stmt->close();
        $conexion->close();

        // Enviar los resultados modificados como respuesta JSON
        echo json_encode($datos);
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}
?>
