<?php


include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (isset($_POST['idConductor'])) {

    $idConductor = trim($_POST['idConductor']);

    $cadena = '';

    if ($idConductor > 0) {

        $getRUTConductor = getRUTConductor($idConductor);
        if($getRUTConductor == '' || $getRUTConductor == 0){
            $cadena .= '';
        }else{
            $cadena .= '<label for="exampleRUTConductor" style="font-size:12px">Rut del Conductor:<code>*</code></label>';
            $cadena .= '<div class="input-group mb-12">';
            $cadena .= '<div class="input-group-prepend">';
            $cadena .= '<span class="input-group-text"><i class="fas fa-user"></i></span>';
            $cadena .= '</div>';
            $cadena .= '<input type="text" style="text-transform:uppercase" readonly="readonly" required class="form-control form-control-sm" id="rutConductor" name="rutConductor" value="'.$getRUTConductor.'" placeholder="RUT Conductor">';
            $cadena .= '</div>';
        }

    }

    echo $cadena;
}
