<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (isset($_POST['idProfesional'])) {

    $idProfesional = trim($_POST['idProfesional']);

    $cadena = $idProfesional;

    $cadena = '';
    if ($idProfesional == 0 || empty($idProfesional)) {
    } else {
        $datosProfesionales = array();
        $datosProfesionales = getDatosJefe($idProfesional);

        $nomJefe = $datosProfesionales['nomJefe'];
        $cargoJefe = $datosProfesionales['cargoJefe'];
        $deptoJefe = getNomDepartamento($datosProfesionales['idDepartamento']);
        $emailJefe = $datosProfesionales['emailJefe'];



        $cadena .= '
        
        <input type="hidden" name="personalSC" id="personalSC" value="'.$nomJefe.'">
        <input type="hidden" name="cargoSC" id="cargoSC" value="'.$cargoJefe.'">
        <input type="hidden" name="deptoSC" id="deptoSC" value="'.$deptoJefe.'">
        <input type="hidden" name="emailSC" id="emailSC" value="'.$emailJefe.'">
        
        ';
    }

    echo $cadena;
}
