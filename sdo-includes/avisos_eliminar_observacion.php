<?php

include '../../sdo-funciones/conexion.php';

if (isset($_POST['idObservacion'])) {
    $id = (int)$_POST['idObservacion'];
    $sql = "DELETE FROM avisos_observaciones WHERE idObservacion = $id";
    $res = mysqli_query($conexion, $sql);
    echo $res ? 'ok' : 'error';
} else {
    echo 'error';
}