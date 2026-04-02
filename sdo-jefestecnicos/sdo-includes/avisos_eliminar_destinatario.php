<?php

include '../../sdo-funciones/conexion.php';

if (isset($_POST['idDestinatario'])) {
    $id = (int)$_POST['idDestinatario'];
    $sql = "DELETE FROM avisos_destinatarios WHERE idDestinatario = $id";
    $res = mysqli_query($conexion, $sql);
    echo $res ? 'ok' : 'error';
} else {
    echo 'error';
}