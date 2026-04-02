<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (isset($_POST['idServicio'])) {

    $idServicio = trim($_POST['idServicio']);

    $cadena = '';

    if ($idServicio == "Otro" || $idServicio == "otro") {

        $cadena .= '<label for="exampleNomServicio">Agregar Servicio:<code>*</code></label>';
        $cadena .= '<div class="input-group mb-3">';
        $cadena .= '<div class="input-group-prepend">';
        $cadena .= '<span class="input-group-text"><i class="fas fa-server"></i></span>';
        $cadena .= '</div>';
        $cadena .= '<input type="text" style="text-transform:capitalize" required class="form-control" id="otroServicio" name="otroServicio" placeholder="Nombre Servicio">';
        $cadena .= '</div>';


    } else {
    }

    echo $cadena;
}
