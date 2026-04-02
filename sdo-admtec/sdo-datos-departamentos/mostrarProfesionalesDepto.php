<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (isset($_POST['idDepartamento'])) {

    $idDepartamento = trim($_POST['idDepartamento']);

    $cadena = '';
    if ($idDepartamento == 0 || empty($idDepartamento)) {
    } else {
        $nomDepartamento = getNomDepartamento($idDepartamento);

        $cadena .= '<label for="profesionalSC">Personal del Departamento: <code>*</code></label>';
        $cadena .= '<select id="profesionalSC" class="custom-select" name="profesionalSC">';
        $cadena .= '<option value="0">Seleccione...</option>';

        $queryJefe = "SELECT idJefe, nomJefe, cargoJefe, emailJefe FROM jefes_tecnicos WHERE idDepartamento = '" . $idDepartamento . "' ORDER BY cargoJefe ASC";

        $resJefe = mysqli_query($conexion, $queryJefe);

        while ($rowJefe = mysqli_fetch_array($resJefe)) {
            $cadena = $cadena . '<option value="' . $rowJefe[0] . '">' . $rowJefe[1] . ' ( ' . $rowJefe[2] . ' )</option>';
        }


        $cadena .= '</select>';
    }

    echo $cadena;
}
