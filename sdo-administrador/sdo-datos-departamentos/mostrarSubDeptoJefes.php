<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (isset($_POST['idDepartamento'])) {

    $idDepartamento = trim($_POST['idDepartamento']);

    $nomDepartamento = getNomDepartamento($idDepartamento);

    $cadena = '';

    if ($nomDepartamento == "Equipos Industriales") {

        $cadena .= '<label for="exampleSubDepartamento">SubDepartamento:</label>';
        $cadena .= '<select id="idSubDepartamento" class="custom-select" name="idSubDepartamento">';

        $querySubDepartamentos = "SELECT idSubDepartamento, nomSubDepartamento FROM subdepartamentos WHERE idDepartamento = '" . $idDepartamento . "' ORDER BY nomSubDepartamento ASC";

        $resSubDepartamentos = mysqli_query($conexion, $querySubDepartamentos);

        while ($rowSubDepartamentos = mysqli_fetch_array($resSubDepartamentos)) {
            $cadena = $cadena . '<option id="idOptionSubDepartamento" value="' . $rowSubDepartamentos[0] . '">' . $rowSubDepartamentos[1] . '</option>';
        }


        $cadena .= '</select>';
    } else {
    }

    echo $cadena;
}
