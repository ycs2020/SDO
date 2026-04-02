<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $name = isset($_POST['name']) ? $_POST['name'] : null;
    $datapuertas = isset($_POST['datapuertas']) ? $_POST['datapuertas'] : null;
    $imagen = isset($_POST['dataimagen']) ? $_POST['dataimagen'] : null;

    if ($id !== null && $name !== null && $datapuertas !== null && $imagen !== null) {
        if($datapuertas == 0){
        }else{
            $conteo = 0;
            $conteo = $datapuertas - 1;

            $sqlUpdate = "UPDATE datos_checklist_ucas SET cantidadPuertas = $conteo WHERE id = $name";
            $resUpdate = mysqli_query($conexion,$sqlUpdate);

            $EliminarBotonera = "DELETE FROM `botonera_checklist` WHERE puerta = '$id' AND idCheck = $name";
            $resBotonera = mysqli_query($conexion, $EliminarBotonera);

            $EliminarMagenetico = "DELETE FROM magnetico_checklist WHERE puerta = '$id' AND idCheck = $name";
            $resMagenetico = mysqli_query($conexion, $EliminarMagenetico);

            $EliminarPMA = "DELETE FROM puertas_pma_checklist WHERE puerta = '$id' AND idCheck = $name";
            $resPMA = mysqli_query($conexion,$EliminarPMA);

            $EliminarBiometrico = "DELETE FROM biometrico_checklist WHERE puerta = '$id' AND idCheck = $name";
            $resBiometrico = mysqli_query($conexion, $EliminarBiometrico);

            $EliminarFoto = "DELETE FROM `archivos_uca_check_list` WHERE $id = $imagen";
            $resFoto = mysqli_query($conexion, $EliminarFoto);

        }
    }
}