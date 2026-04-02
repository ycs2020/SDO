<?php
include '../../sdo-funciones/conexion.php';

date_default_timezone_set('America/Santiago');

if (isset($_POST['idAviso'], $_POST['observacion'], $_POST['creador'], $_POST['visibilidad'])) {
    $idAviso = intval($_POST['idAviso']);
    $observacion = mysqli_real_escape_string($conexion, $_POST['observacion']);
    $creador = mysqli_real_escape_string($conexion, $_POST['creador']);
    $visibilidad = $_POST['visibilidad'];
    $fecha = date('Y-m-d H:i:s');

    if (empty($observacion) || empty($creador)) {
        echo 'error';
        exit;
    }

    $intVisibilidad = ($visibilidad == 'Privada') ? 1 : 0;

    $todoOk = true;

    $sql = "INSERT INTO avisos_observaciones (idAviso, detalleObservacion, fechaObservacion, creadorObservacion, esPrivada)
            VALUES ($idAviso, '$observacion', '$fecha', '$creador', '$intVisibilidad')";

    if (mysqli_query($conexion, $sql)) {
        $idObservacion = mysqli_insert_id($conexion);

        // Obtener creador del aviso
        $sqlCreador = "SELECT creadorAviso FROM avisos_relevantes WHERE idAviso = $idAviso";
        $resCreador = mysqli_query($conexion, $sqlCreador);
        $rowCreador = mysqli_fetch_assoc($resCreador);
        $creadorAviso = $rowCreador['creadorAviso'];

        // Obtener destinatarios
        $sqlDest = "SELECT nomDestinatario FROM avisos_destinatarios WHERE idAviso = $idAviso";
        $resDest = mysqli_query($conexion, $sqlDest);

        $usuariosANotificar = [];
        while ($rowDest = mysqli_fetch_assoc($resDest)) {
            $usuariosANotificar[] = $rowDest['nomDestinatario'];
        }
        $usuariosANotificar[] = $creadorAviso;
        $usuariosANotificar = array_unique($usuariosANotificar);

        foreach ($usuariosANotificar as $usuario) {
            if ($usuario !== $creador) {
                $sqlNotif = "INSERT INTO avisos_notificaciones (idAviso, idObservacion, usuario)
                             VALUES ($idAviso, $idObservacion, '$usuario')";
                if (!mysqli_query($conexion, $sqlNotif)) {
                    $todoOk = false;
                }
            }
        }

        echo $todoOk ? 'ok' : 'error';
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}
