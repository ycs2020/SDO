<?php

include '../../sdo-funciones/conexion.php';

if (isset($_POST['idAviso'])) {
    $id = (int)$_POST['idAviso'];
    $sql = "UPDATE avisos_relevantes SET estadoAviso = 'Finalizado' WHERE idAviso = $id";
    $res = mysqli_query($conexion, $sql);
    echo $res ? 'ok' : 'error';
} else {
    echo 'error';
}