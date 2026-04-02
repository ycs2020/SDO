<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (isset($_POST['nomConductor'])) {

    $nomConductor = trim($_POST['nomConductor']);

    $cadena = '';

    if ($nomConductor == "Otro" || $nomConductor == "otro" || $nomConductor == "OTRO") {

        $cadena .= '<label for="exampleNomMotivo" style="font-size:12px">Agregar Rut:</label>';
        $cadena .= '<div class="input-group mb-12">';
        $cadena .= '<div class="input-group-prepend">';
        $cadena .= '<span class="input-group-text"><i class="fas fa-file"></i></span>';
        $cadena .= '</div>';
        $cadena .= '<input type="text" style="text-transform:uppercase" required class="form-control form-control-sm" id="otroRut" name="otroRut" placeholder="Rut Conductor">';
        $cadena .= '</div>';


    } else {
    }

    echo $cadena;
}
