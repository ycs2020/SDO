<?php


include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (isset($_POST['numPatente'])) {

    $numPatente = trim($_POST['numPatente']);

    $cadena = '';

    if ($numPatente !== '' || !empty($numPatente)) {

        //$getTipoMovil = getRUTConductor($idConductor);
        $getTipoMovil = getTipoMovil($numPatente);

        $cadena .= '<label for="exampleRUTConductor" style="font-size:12px">Tipo Movil: (Al elegir patente, este valor cambia automaticamente)<code>*</code></label>';
        $cadena .= '<div class="input-group mb-12">';
        $cadena .= '<div class="input-group-prepend">';
        $cadena .= '<span class="input-group-text"><i class="fas fa-car"></i></span>';
        $cadena .= '</div>';
        $cadena .= '<input type="text" style="text-transform:uppercase" readonly="readonly" required class="form-control form-control-sm" id="tipoMovil" name="tipoMovil" value="'.$getTipoMovil.'" placeholder="Tipo Movil">';
        $cadena .= '</div>';


    }

    echo $cadena;
}
