<?php

include '../sdo-funciones/conexion.php';
session_start();

$usuario = $_SESSION['usuario'];
$sql = "UPDATE avisos_notificaciones SET leida = 1 WHERE usuario = '$usuario'";
mysqli_query($conexion, $sql);
echo "ok";
