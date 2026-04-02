<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (isset($_POST['idDepartamento'])) {

    $idDepartamento = trim($_POST['idDepartamento']);

    $cadena = '';

    if (!empty($_POST['idDepartamento'])) {

        $contarPersonal = contarJefe($idDepartamento);

        if ($contarPersonal > 0) {

            $queryPersonal = "SELECT jt.idJefe, jt.nomJefe AS nomEmpleado, jt.cargoJefe FROM jefes_tecnicos jt WHERE idDepartamento = '".$idDepartamento."' UNION SELECT p.idPersonal AS idJefe, p.nomPersonal AS nomEmpleado, p.cargoPersonal AS cargoJefe  FROM personal p WHERE departamentoPersonal = '".$idDepartamento."';";

            $resPersonal = mysqli_query($conexion, $queryPersonal);

            //SELECT jt.idJefe, jt.nomJefe AS nomEmpleado, jt.cargoJefe FROM jefes_tecnicos jt INNER JOIN personal p ON jt.idJefe = p.idPersonal WHERE p.departamentoPersonal = '4';

            $cadena = $cadena . '<div class="input-group-prepend">';

            $cadena = $cadena . '<span class="input-group-text"><i class="fas fa-user"></i></span>';

            $cadena = $cadena . '<select id="tecnico1" class="form-control form-control-sm" name="tecnico1">';

            $conteoReg = 1;

            while ($rowPersonal = mysqli_fetch_array($resPersonal)) {


                $cadena = $cadena . '<option id="idOptionTecnicoAdmin" value="' . $rowPersonal['nomEmpleado'] . '">' . $rowPersonal['nomEmpleado'] . '</option>';

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
