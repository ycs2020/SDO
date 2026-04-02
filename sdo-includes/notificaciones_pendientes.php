<?php
include '../sdo-funciones/conexion.php';
session_start();

$usuario = $_SESSION['usuario'];

$sql = "SELECT an.idNotificacion, ar.tituloAviso, ao.detalleObservacion, ao.fechaObservacion
        FROM avisos_notificaciones an
        JOIN avisos_observaciones ao ON an.idObservacion = ao.idObservacion
        JOIN avisos_relevantes ar ON an.idAviso = ar.idAviso
        WHERE an.usuario = '$usuario' AND an.leida = 0
        ORDER BY ao.fechaObservacion DESC";

$res = mysqli_query($conexion, $sql);

$notificaciones = [];
while ($row = mysqli_fetch_assoc($res)) {
    $notificaciones[] = $row;
}

echo json_encode($notificaciones);
