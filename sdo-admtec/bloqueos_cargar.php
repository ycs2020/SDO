<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (isset($_POST['idActaBloqueo'])) {

    $idActa = $_POST['idActaBloqueo'];

    if ($idActa > 0) {

        $sql = "SELECT * FROM actas_bloqueos WHERE actaBloqueo = $idActa ORDER BY fechaBloqueo DESC";

// Preparar la consulta
        $stmt = $conexion->prepare($sql);
        $stmt->execute();


// Obtener los resultados
        $result = $stmt->get_result();
        $datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
        while ($row = $result->fetch_assoc()) {

            $fecha = date('d/m/Y', strtotime(str_replace('/', '-', $row['fechaBloqueo'])));
            $hora = date('H:i', strtotime(str_replace('/', '-', $row['fechaBloqueo'])));

            $funcionarioBloqueo = $row['funcionarioBloqueo'];
            $rutBloqueo = $row['rutBloqueo'];
            $solicitanteBloqueo = $row['solicitanteBloqueo'];
            $detalleBloqueo = $row['detalleBloqueo'];

            $funcionarioCompleto = $funcionarioBloqueo;

            $fechaBloqueo = $fecha . '<br>' . $hora . ' hrs.';

            $detalleBloqueo = 'Según lo solicitado por ' . $solicitanteBloqueo . ' se realiza: <br><br>' . $detalleBloqueo;


            $row['fechaBloqueo'] = $fechaBloqueo;
            $row['funcionarioCompleto'] = $funcionarioCompleto;
            $row['detalleBloqueoSolic'] = $detalleBloqueo;

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