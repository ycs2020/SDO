<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (isset($_POST['idDepartamento'])) {

    $idDepartamento = trim($_POST['idDepartamento']);

    $cadena = '';

    if (!empty($_POST['idDepartamento'])) {

        $contarPersonal = contarPersonal($idDepartamento);

        if ($contarPersonal > 0) {
            $queryPersonal = "SELECT idPersonal, nomPersonal FROM personal WHERE departamentoPersonal = '" . $idDepartamento . "' AND activoPersonal = 1 ORDER BY nomPersonal ASC";

            $resPersonal = mysqli_query($conexion, $queryPersonal);


            $cadena = $cadena . '<div class="input-group-prepend">';

            $cadena = $cadena . '<span class="input-group-text"><i class="fas fa-user"></i></span>';

            $cadena = $cadena . '<select id="nomTecnicoRecepcionador" onchange="leerDatos()" class="form-control" name="nomTecnicoRecepcionador">';

            while ($rowPersonal = mysqli_fetch_array($resPersonal)) {
                $cadena = $cadena . '<option id="idOptionTecnicoAdmin" value="' . $rowPersonal[0] . '">' . $rowPersonal[1] . '</option>';
            }

            $cadena = $cadena . '</select>';

            $cadena = $cadena . '</div>';
        } else {
            $cadena = $cadena . '0';
        }
    } else {
        $cadena = $cadena . 'Error';
    }



    echo $cadena;
}
