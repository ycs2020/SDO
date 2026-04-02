<?php


include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (isset($_POST['numPatente'])) {

    $numPatente = trim($_POST['numPatente']);

    $cadena = '';

    if ($numPatente !== '' || !empty($numPatente)) {

        //$getTipoMovil = getRUTConductor($idConductor);
        $getAnoMovil = getAnoMovil($numPatente);

        $cadena .= '<label style="font-size:12px">Año del Vehiculo: (Al elegir patente, este valor cambia automaticamente)<code>*</code></label>';
        $cadena .= '<div class="input-group mb-12">';
        $cadena .= '<div class="input-group-prepend">';
        $cadena .= '<span class="input-group-text"><i class="fas fa-car"></i></span>';
        $cadena .= '</div>';
        $cadena .= '<input type="text" readonly="readonly" required class="form-control form-control-sm" id="anoMovil" name="anoMovil" value="'.$getAnoMovil.'" placeholder="Año del Movil">';
        $cadena .= '</div>';


    }

    echo $cadena;
}
