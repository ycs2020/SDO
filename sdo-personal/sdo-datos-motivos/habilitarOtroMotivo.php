<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (isset($_POST['nomMotivo'])) {

    $nomMotivo = trim($_POST['nomMotivo']);

    $cadena = '';

    if ($nomMotivo == "Otro" || $nomMotivo == "otro" || $nomMotivo == "OTRO") {

        $cadena .= '<label for="exampleNomMotivo" style="font-size:12px">Agregar Motivo:<code>*</code></label>';
        $cadena .= '<div class="input-group mb-3">';
        $cadena .= '<div class="input-group-prepend">';
        $cadena .= '<span class="input-group-text"><i class="fas fa-file"></i></span>';
        $cadena .= '</div>';
        $cadena .= '<input type="text" style="text-transform:uppercase" required class="form-control form-control-sm" id="otroMotivo" name="otroMotivo" placeholder="Nombre Motivo">';
        $cadena .= '</div>';


    } else {
    }

    echo $cadena;
}
