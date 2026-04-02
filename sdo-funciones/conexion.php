<?php
$hostname = 'localhost';
$username = 'adminSDO';
$password = 'RmR2vnBnYouHh4Y';

$ipPermitida = '10.69.250.151'; // Reemplaza con la IP permitida

$ipCliente = $_SERVER['REMOTE_ADDR'];

if ($ipCliente === $ipPermitida) {
//    $database = 'sistema_sdo_2';
    $database = 'sistema_sdo';
} else {
    $database = 'sistema_sdo';
}


$conexion = new mysqli($hostname, $username, $password, $database);
$conexion->set_charset("utf8");

?>