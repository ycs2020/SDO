<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (isset($_POST['valorServicio'])) {

    $valorServicio = trim($_POST['valorServicio']);

    $cadena = '';

    if ($valorServicio == "otro" || $valorServicio == "Otro") {

        $cadena .= '<label for="exampleOtroServicio">Agregar Servicio:<code>*</code></label>';
        $cadena .= '<div class="input-group mb-3">';
        $cadena .= '<div class="input-group-prepend">';
        $cadena .= '<span class="input-group-text"><i class="fas fa-server"></i></span>';
        $cadena .= '</div>';
        $cadena .= '<input type="text" style="text-transform:capitalize" class="form-control" id="otroServicio" name="otroServicio" placeholder="Nombre Servicio">';
        $cadena .= '</div>';


    } else {
    }

    echo $cadena;
}
