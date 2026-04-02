<?php
include 'conexion.php';
include 'funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_POST['id'])) {
    $id = trim($_POST['id']);
    $sql = "SELECT * FROM equipamiento_hbqp WHERE id = $id";
    echo $sql;



}