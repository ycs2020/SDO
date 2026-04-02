<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (isset($_POST['idDepartamento'])) {

    $idDepartamento = trim($_POST['idDepartamento']);

    $cadena = '';

    if ($idDepartamento > 0) {

        $cadena .= '<label for="exampleSubDepartamento">Tipo de Problema: <code>*</code></label>';
        $cadena .= '<div class="input-group mb-3">';
        $cadena .= '<div class="input-group-prepend">';
        $cadena .= '<span class="input-group-text"><i class="fas fa-server"></i></span>';
        $cadena .= '</div>';
        $cadena .= '<select id="tipoObservacion" class="form-control" name="tipoObservacion">';

        $cadena = $cadena . '<option id="idOptionTipoObservacion" value="0" selected disabled>Seleccione...</option>';

        $queryTipoObservacion = "SELECT idTipoSolicitud, nomTipoSolicitud FROM solicitudes_tipos WHERE idDepartamento = '" . $idDepartamento . "'";

        $resTipoObservacion = mysqli_query($conexion, $queryTipoObservacion);

        while ($rowTipoObservacion = mysqli_fetch_array($resTipoObservacion)) {
            $cadena = $cadena . '<option id="idOptionTipoObservacion" value="' . $rowTipoObservacion[0] . '">' . $rowTipoObservacion[1] . '</option>';
        }


        $cadena .= '</select>';
        $cadena .= '</div>';
    } else {
    }

    echo $cadena;
}
