<?php
include '../../sdo-funciones/conexion.php';

if (isset($_POST['idAviso']) && isset($_POST['destinatarios']) && is_array($_POST['destinatarios'])) {
    $idAviso = intval($_POST['idAviso']);
    $destinatarios = $_POST['destinatarios'];
    $fecha = date('Y-m-d H:i:s');

    $todoOk = true;

    foreach ($destinatarios as $nombre) {
        $nombreLimpio = mysqli_real_escape_string($conexion, $nombre);

        $sql = "INSERT INTO avisos_destinatarios (idAviso, nomDestinatario, fechaDestinatario) 
                VALUES ($idAviso, '$nombreLimpio', '$fecha')";
        if (!mysqli_query($conexion, $sql)) {
            $todoOk = false;
            break;
        }
    }

    echo $todoOk ? 'ok' : 'error';
} else {
    echo 'error';
}
