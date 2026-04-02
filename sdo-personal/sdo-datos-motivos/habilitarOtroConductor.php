<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (isset($_POST['nomConductor'])) {

    $nomConductor = trim($_POST['nomConductor']);

    $cadena = '';

    if ($nomConductor == "Otro" || $nomConductor == "otro" || $nomConductor == "OTRO") {

        $cadena .= '<label for="exampleNomMotivo" style="font-size:12px">Agregar Conductor:</label>';
        $cadena .= '<div class="input-group mb-3">';
        $cadena .= '<div class="input-group-prepend">';
        $cadena .= '<span class="input-group-text"><i class="fas fa-file"></i></span>';
        $cadena .= '</div>';
        $cadena .= '<input type="text" style="text-transform:uppercase" required class="form-control form-control-sm" id="otroConductor" name="otroConductor" placeholder="Nombre Conductor">';
        $cadena .= '</div>';


    } else {
    }

    echo $cadena;
}
