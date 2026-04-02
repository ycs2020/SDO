<?php
include '../../sdo-funciones/conexion.php'; // o tu conexión
include '../../sdo-funciones/funciones.php'; // o tu conexión

header('Content-Type: application/json');

if (isset($_POST['idAviso'])) {
    $idAviso = intval($_POST['idAviso']);

    $sql = "SELECT * FROM avisos_relevantes WHERE idAviso = $idAviso LIMIT 1";
    $res = mysqli_query($conexion, $sql);

    if ($row = mysqli_fetch_assoc($res)) {

        $fechaTime = strtotime($row['fechaAviso']);

        $fechaFormato = fechaEspañol($fechaTime);
        $horaFormato = date('H:i', $fechaTime);

        $fechaCompleta = $fechaFormato . ' a las ' . $horaFormato.' hrs.';

        // Consulta para obtener los detalles relacionados al aviso
        $destinatarios = [];
        $sqlDestinatario = "SELECT idDestinatario, nomDestinatario, fechaDestinatario FROM avisos_destinatarios WHERE idAviso = $idAviso ORDER BY fechaDestinatario ASC, nomDestinatario ASC";
        $resDestinatario = mysqli_query($conexion, $sqlDestinatario);

        while ($destinatario = mysqli_fetch_assoc($resDestinatario)) {
            $fechaOriginal = strtotime($destinatario['fechaDestinatario']);
            $fechaFormateada = fechaEspañol2($fechaOriginal) . ' a las ' . date('H:i', $fechaOriginal) . ' hrs.';

            $destinatarios[] = [
                'idDestinatario' => $destinatario['idDestinatario'],
                'nomDestinatario' => $destinatario['nomDestinatario'],
                'fechaDestinatario' => $fechaFormateada
            ];
        }

        // Consulta para obtener los detalles relacionados al aviso
        $observaciones = [];


        if($_POST['nomDepartamento'] == 'Instrumentación'){
            $sqlObservaciones = "SELECT idObservacion, detalleObservacion, fechaObservacion, creadorObservacion, esPrivada FROM avisos_observaciones WHERE idAviso = $idAviso ORDER BY fechaObservacion DESC";

        }else{
            $sqlObservaciones = "SELECT idObservacion, detalleObservacion, fechaObservacion, creadorObservacion, esPrivada FROM avisos_observaciones WHERE idAviso = $idAviso AND esPrivada = 0 ORDER BY fechaObservacion DESC";
        }

        $resObservaciones  = mysqli_query($conexion, $sqlObservaciones);

        while ($observacion = mysqli_fetch_assoc($resObservaciones )) {
            $fechaOriginal = strtotime($observacion['fechaObservacion']);
            $fechaFormateada = fechaEspañol2($fechaOriginal) . '<br>' . date('H:i', $fechaOriginal) . ' hrs.';

            if($observacion['esPrivada'] == 1){
                $esPrivada = 'Privada';
            }else{
                $esPrivada = 'Pública';
            }

            $observaciones[] = [
                'idObservacion' => $observacion['idObservacion'],
                'detalleObservacion' => $observacion['detalleObservacion'],
                'fechaObservacion' => $fechaFormateada,
                'creadorObservacion' => $observacion['creadorObservacion'],
                'esPrivada' => $esPrivada
            ];
        }


        echo json_encode([
            'titulo' => $row['tituloAviso'],
            'descripcion' => nl2br($row['descripcionAviso']),
            'fecha' => $fechaCompleta,
            'departamento' => $row['sistemaAviso'],
            'creador' => $row['creadorAviso'],
            'destinatarios' => $destinatarios,
            'observaciones' => $observaciones,
            'estado' => $row['estadoAviso'],
        ]);
    } else {
        echo json_encode(['error' => 'Aviso no encontrado']);
    }
} else {
    echo json_encode(['error' => 'ID no recibido']);
}
