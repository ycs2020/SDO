<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (isset($_POST['idCliente'])) {

    $idCliente = trim($_POST['idCliente']);

    $cadena = '';

    if ($idCliente == "Otro" || $idCliente == "otro" || $idCliente == "OTRO") {

        $cadena .= '<label for="exampleNomServicio">Agregar Cliente:</label>';
        $cadena .= '<div class="input-group mb-3">';
        $cadena .= '<div class="input-group-prepend">';
        $cadena .= '<span class="input-group-text"><i class="fas fa-server"></i></span>';
        $cadena .= '</div>';
        $cadena .= '<input type="text" style="text-transform:uppercase" class="form-control" id="otroCliente" name="otroCliente" placeholder="Número del Cliente">';
        $cadena .= '</div>';


    } else {

    }

    echo $cadena;
}
