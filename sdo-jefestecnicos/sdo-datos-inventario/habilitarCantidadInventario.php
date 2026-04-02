<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (isset($_POST['idInventario'])) {

    $idInventario = trim($_POST['idInventario']);

    $cadena = '';

    $obtenerCantMaxInvent = obtenerCantMaxInvent($idInventario);

    if (!empty($idInventario) && $idInventario > 0 && $obtenerCantMaxInvent > 0) {

        $cadena .= '<label for="exampleNomServicio">Ingrese Cantidad a Ocupar:</label>';
        $cadena .= '<div class="input-group mb-3">';
        $cadena .= '<div class="input-group-prepend">';
        $cadena .= '<span class="input-group-text"><i class="fas fa-sort-numeric-up"></i></span>';
        $cadena .= '</div>';
        $cadena .= '<input type="number" class="form-control" id="cantMaxInventario" name="cantMaxInventario" min="0" max="'.$obtenerCantMaxInvent.'" placeholder="Cantidad Maxima: '.$obtenerCantMaxInvent.'">';
        $cadena .= '</div>';


    }else{

    }

    echo $cadena;
}
