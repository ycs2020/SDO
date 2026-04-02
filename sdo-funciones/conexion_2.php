<?php
    $hostname = 'localhost';
    $username = 'adminSDO';
    $password = 'RmR2vnBnYouHh4Y';
    $database = 'sistema_sdo_2';

    $conexion = new mysqli($hostname, $username, $password, $database);
    $conexion -> set_charset("utf8");

?>