<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (isset($_POST['nomJefeUni'])) {

    $nomJefeUni = trim($_POST['nomJefeUni']);

    $cadena = '';

    if ($nomJefeUni == "OtroNombre" || $nomJefeUni == "OtroNombre") {

        $cadena .= 'Habilitar';


    } else {
    }

    echo $cadena;
}
