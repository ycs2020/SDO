<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (isset($_POST['idDepartamento'], $_POST['idSubdepartamento'])) {

    $idDepartamento = trim($_POST['idDepartamento']);

    $idSubDepartamento = trim($_POST['idSubdepartamento']);

    $nomDepartamento = getNomDepartamento($idDepartamento);

    if ($idSubDepartamento == 0 || empty($idSubDepartamento)) {
        
    } else {
        $nomSubDepartamento = getNomSubDepartamento($row['idSubdepartamento']);
    }

    
    $cadena = '';

    if ($nomDepartamento == "Equipos Industriales") {

        $cadena .= '<label for="exampleSubDepartamento">SubDepartamento:</label>';
        $cadena .= '<div class="input-group mb-3">';
        $cadena .= '<div class="input-group-prepend">';
        $cadena .= '<span class="input-group-text"><i class="fas fa-server"></i></span>';
        $cadena .= '</div>';
        $cadena .= '<select readonly="readonly" style="pointer-events: none;"  id="idSubDepartamento" class="form-control" name="idSubDepartamento">';

        $querySubDepartamentos = "SELECT idSubDepartamento, nomSubDepartamento FROM subdepartamentos WHERE idDepartamento = '" . $idDepartamento . "' ORDER BY nomSubDepartamento ASC";

        $resSubDepartamentos = mysqli_query($conexion, $querySubDepartamentos);

        while ($rowSubDepartamentos = mysqli_fetch_array($resSubDepartamentos)) {
            if ($idSubDepartamento == $rowSubDepartamentos['idSubDepartamento']) {
                $cadena = $cadena . '<option id="idOptionSubDepartamento" value="' . $rowSubDepartamentos[0] . '" selected="selected">' . $rowSubDepartamentos[1] . '</option>';
            } else {
                $cadena = $cadena . '<option id="idOptionSubDepartamento" value="' . $rowSubDepartamentos[0] . '">' . $rowSubDepartamentos[1] . '</option>';
            }
        }


        $cadena .= '</select>';
        $cadena .= '</div>';
    } else {
    }

    echo $cadena;
}
