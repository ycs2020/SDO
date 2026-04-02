<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (isset($_POST['idDepartamento'])) {

    $idDepartamento = trim($_POST['idDepartamento']);

  
    $cadena = '';

    if (!empty($_POST['idDepartamento'])) {

        $contarPersonal = contarPersonal($idDepartamento);

        if ($contarPersonal > 0) {
            $queryPersonal = "SELECT idPersonal, nomPersonal, cargoPersonal FROM personal WHERE departamentoPersonal = '" . $idDepartamento . "'";

            $resPersonal = mysqli_query($conexion, $queryPersonal);


            $cadena = $cadena . '<div class="input-group-prepend">';

            $cadena = $cadena . '<span class="input-group-text"><i class="fas fa-user"></i></span>';

            $cadena = $cadena . '<select id="personalTecnico" class="form-control" onchange="cambiar()" name="personalTecnico">';

            while ($rowPersonal = mysqli_fetch_array($resPersonal)) {
                $cadena = $cadena . '<option id="idOptionTecnicoAdmin" value="' . $rowPersonal[0] . '">' . $rowPersonal[1] .  '' . "  -  " . '' . $rowPersonal[2] . '</option>';
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
