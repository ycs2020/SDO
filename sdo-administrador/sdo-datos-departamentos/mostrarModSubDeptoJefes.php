<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (isset($_POST['idDepartamento'], $_POST['idSubDepartamento2'])) {

    $idDepartamento = trim($_POST['idDepartamento']);

    $idSubDepartamento2 = trim($_POST['idSubDepartamento2']);

    $nomDepartamento = getNomDepartamento($idDepartamento);

    $cadena = '';

    if ($nomDepartamento == "Equipos Industriales") {

        $cadena .= '<label for="exampleSubDepartamento">SubDepartamento: <code>*</code></label>';
        $cadena .= '<select id="idSubDepartamento" class="custom-select" name="idSubDepartamento">';

        $querySubDepartamentos = "SELECT idSubDepartamento, nomSubDepartamento FROM subdepartamentos WHERE idDepartamento = '" . $idDepartamento . "' ORDER BY nomSubDepartamento ASC";

        $resSubDepartamentos = mysqli_query($conexion, $querySubDepartamentos);

        while ($rowSubDepartamentos = mysqli_fetch_array($resSubDepartamentos)) {

            if($idSubDepartamento2 == $rowSubDepartamentos[0]){
                $cadena = $cadena . '<option id="idOptionSubDepartamento" value="' . $rowSubDepartamentos[0] . '">'. $rowSubDepartamentos[1] . '</option>';
            }else{
                
            $cadena = $cadena . '<option id="idOptionSubDepartamento" value="' . $rowSubDepartamentos[0] . '">'. $rowSubDepartamentos[1] . '</option>';
            }
        }


        $cadena .= '</select>';


    } else {
        $cadena .= $idDepartamento . " - ".$idSubDepartamento2 . " - ".$nomDepartamento;
    }

    echo $cadena;
}
