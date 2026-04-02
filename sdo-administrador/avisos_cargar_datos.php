<?php
include '../sdo-funciones/conexion.php'; // o tu conexión
include '../sdo-funciones/funciones.php'; // o tu conexión

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
        $sqlDestinatario = "SELECT nomDestinatario, fechaDestinatario FROM avisos_destinatarios WHERE idAviso = $idAviso ORDER BY fechaDestinatario ASC, nomDestinatario ASC";
        $resDestinatario = mysqli_query($conexion, $sqlDestinatario);

        $destinatarios = [];

        while ($destinatario = mysqli_fetch_assoc($resDestinatario)) {
            $fechaOriginal = strtotime($destinatario['fechaDestinatario']);
            $fechaFormateada = fechaEspañol2($fechaOriginal) . ' a las ' . date('H:i', $fechaOriginal) . ' hrs.';

            $destinatarios[] = [
                'nomDestinatario' => $destinatario['nomDestinatario'],
                'fechaDestinatario' => $fechaFormateada
            ];
        }


        echo json_encode([
            'titulo' => $row['tituloAviso'],
            'descripcion' => nl2br($row['descripcionAviso']),
            'fecha' => $fechaCompleta,
            'departamento' => $row['sistemaAviso'],
            'creador' => $row['creadorAviso'],
            'destinatarios' => $destinatarios,
        ]);
    } else {
        echo json_encode(['error' => 'Aviso no encontrado']);
    }
} else {
    echo json_encode(['error' => 'ID no recibido']);
}
