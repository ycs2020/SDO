<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (isset($_POST['idDepartamento'])) {

    $idDepartamento = trim($_POST['idDepartamento']);

    $cadena = '';

    if (!empty($_POST['idDepartamento'])) {

        $queryTipos = "SELECT * FROM solicitudes_tipos WHERE idDepartamento = '" . $idDepartamento . "'";

        $resTipos = mysqli_query($conexion, $queryTipos);


        $cadena = $cadena . '<label for="exampleInputBorder">Tipo de Observarci&oacute;n: <code>*</code></label>';

        $cadena = $cadena . '<select id="tipoObservacion" class="form-control" name="tipoObservacion">';

        while ($rowTipos = mysqli_fetch_array($resTipos)) {
            $cadena = $cadena . '<option id="idOptionTecnicoAdmin" value="' . $rowTipos[0] . '">' . $rowTipos[1] . '</option>';
        }

        $cadena = $cadena . '</select>';

    } else {

    }



    echo $cadena;
}
