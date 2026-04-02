<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (isset($_POST['idDepartamento'])) {

    $idDepartamento = trim($_POST['idDepartamento']);

    $nomDepartamento = getNomDepartamento($idDepartamento);

    $cadena = '';

    if ($nomDepartamento == "Equipos Industriales") {

        $cadena .= '<label for="exampleSubDepartamento">SubDepartamento:</label>';
        $cadena .= '<div class="input-group mb-3">';
        $cadena .= '<div class="input-group-prepend">';
        $cadena .= '<span class="input-group-text"><i class="fas fa-server"></i></span>';
        $cadena .= '</div>';
        $cadena .= '<select id="idSubDepartamento" class="form-control" name="idSubDepartamento">';

        $querySubDepartamentos = "SELECT idSubDepartamento, nomSubDepartamento FROM subdepartamentos WHERE idDepartamento = '" . $idDepartamento . "' ORDER BY nomSubDepartamento ASC";

        $resSubDepartamentos = mysqli_query($conexion, $querySubDepartamentos);

        while ($rowSubDepartamentos = mysqli_fetch_array($resSubDepartamentos)) {
            if($rowSubDepartamentos[0] == 2){

            } else{
                $cadena = $cadena . '<option id="idOptionSubDepartamento" value="' . $rowSubDepartamentos[0] . '">'. $rowSubDepartamentos[1] . '</option>';

            }




        }


        $cadena .= '</select>';
        $cadena .= '</div>';


    } else {
    }

    echo $cadena;
}
