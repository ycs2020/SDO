<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (isset($_POST['idCliente'])) {

    $idCliente = trim($_POST['idCliente']);

    $cadena = '';

    if ($idCliente == "Otro" || $idCliente == "otro" || $idCliente == "OTRO") {

        $cadena .= '<label for="exampleNomServicio">Agregar Medidor:</label>';
        $cadena .= '<div class="input-group mb-3">';
        $cadena .= '<div class="input-group-prepend">';
        $cadena .= '<span class="input-group-text"><i class="fas fa-server"></i></span>';
        $cadena .= '</div>';
        $cadena .= '<input type="text" style="text-transform:uppercase" class="form-control" id="otroMedidor" name="otroMedidor" placeholder="Número del Medidor">';
        $cadena .= '</div>';


    } else {

    }

    echo $cadena;
}
