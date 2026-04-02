<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (isset($_POST['id'])) {

    $tipoArchivo = trim($_POST['id']);

    $cadena = '';

    if ($tipoArchivo == "Otro" || $tipoArchivo == "otro") {
        $cadena .= '<label for="exampleNomServicio" style="font-size:12px">Agregar Nuevo Tipo Archivo:</label>';
        $cadena .= '<div class="input-group mb-3">';
        $cadena .= '<div class="input-group-prepend">';
        $cadena .= '<span class="input-group-text"><i class="fas fa-server"></i></span>';
        $cadena .= '</div>';
        $cadena .= '<input type="text" style="text-transform:capitalize" class="form-control form-control-sm" required id="otroTipoArchivo" name="otroTipoArchivo" placeholder="Nombre Tipo Archivo">';
        $cadena .= '</div>';
    } else {
    }

    echo $cadena;
}
