<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (isset($_POST['numPatente'])) {

    $numPatente = trim($_POST['numPatente']);

    $cadena = '';

    if ($numPatente == "Otro" || $numPatente == "otro" || $numPatente == "OTRO") {

        $cadena .= '<label for="exampleNomMotivo" style="font-size:12px">Agregar Patente:</label>';
        $cadena .= '<div class="input-group mb-3">';
        $cadena .= '<div class="input-group-prepend">';
        $cadena .= '<span class="input-group-text"><i class="fas fa-file"></i></span>';
        $cadena .= '</div>';
        $cadena .= '<input type="text" style="text-transform:uppercase" required class="form-control form-control-sm" id="otroPatente" name="otroPatente" placeholder="Nueva Patente">';
        $cadena .= '</div>';


    } else {
    }

    echo $cadena;
}
