<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (isset($_POST['id'])) {

    $tipoArchivo = trim($_POST['id']);

    $cadena = '';

    if ($tipoArchivo == "HOJA TECNICA EXTERNA" || $tipoArchivo == "hoja tecnica externa") {
        $cadena .= '<label for="exampleNomServicio" style="font-size:12px">Tipo mantenimiento:</label>';
        $cadena .= '<div class="input-group mb-3">';
        $cadena .= '<div class="input-group-prepend">';
        $cadena .= '<span class="input-group-text"><i class="fas fa-server"></i></span>';
        $cadena .= '</div>';
        $cadena .= '<select type="text" style="text-transform:capitalize" class="form-control form-control-sm" required id="tipoMantenimiento" name="tipoMantenimiento" >';
        $cadena .= '<option value="MPE">MPE - Mantenimiento Preventivo Externo</option>';
        $cadena .= '<option value="MRE">MRE - Mantenimiento Reparativo Externo</option>';
        $cadena .= '</select>';
        $cadena .= '</div>';
    } else {
    }

    echo $cadena;
}
