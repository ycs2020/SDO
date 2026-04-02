<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (isset($_POST['idInventario'],$_POST['cantidadInvAnterior'],$_POST['idInvAnterior'])) {

    $idInventario = trim($_POST['idInventario']);
    $cantidadInvAnterior = $_POST['cantidadInvAnterior'];
    $idInvAnterior = trim($_POST['idInvAnterior']);
    $cant2InvAnterior = $cantidadInvAnterior;

    $cadena = '';

    $obtenerCantMaxInvent = obtenerCantMaxInvent($idInventario);



    if (!empty($idInventario) && $idInventario > 0 && $cantidadInvAnterior > 0 && $idInvAnterior > 0) {

        if($idInventario == $idInvAnterior){
            $cant2InvAnterior = $cantidadInvAnterior;

            $obtenerCantMaxInvent = $obtenerCantMaxInvent + $cantidadInvAnterior;
        }else{
            $cant2InvAnterior = '';
        }

        $cadena .= '<label for="exampleNomServicio">Ingrese Cantidad a Ocupar:</label>';
        $cadena .= '<div class="input-group mb-3">';
        $cadena .= '<div class="input-group-prepend">';
        $cadena .= '<span class="input-group-text"><i class="fas fa-sort-numeric-up"></i></span>';
        $cadena .= '</div>';
        $cadena .= '<input type="number" class="form-control" id="cantMaxInventario" name="cantMaxInventario" min="0" max="'.$obtenerCantMaxInvent.'" oninput="cambiarCeroAUno()" placeholder="Cantidad Maxima: '.$obtenerCantMaxInvent.'" value="'.$cant2InvAnterior.'">';
        $cadena .= '</div>';



    }else{

    }

    echo $cadena;
}
