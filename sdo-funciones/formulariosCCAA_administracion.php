<?php

include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
} else {
    session_destroy();
    session_start();
}


date_default_timezone_set("America/Santiago");
setlocale(LC_TIME, 'es_CL.UTF-8', 'esp');

$now = date('Y-m-d H:i:s', time());

if (isset($_POST['cantidadFilas'], $_POST['cantidadPuertas'], $_POST['unidadFormCCAA'])) {

    $cantidadFilas = $_POST['cantidadFilas'];
    $cantidadPuertas = $_POST['cantidadPuertas'];
    $unidadFormCCAA = $_POST['unidadFormCCAA'];

    if ($cantidadFilas > 0 && $cantidadPuertas > 0 && $unidadFormCCAA > 0) {

//        for ($i = 1; $i <= $cantidadFilas; $i++) {
//
//            echo 'Nombre: '.$_POST['nomFuncionario'.$i].' -- RUT: '.$_POST['rutFuncionario'.$i].' -- Calidad: '.$_POST['calidadJuridica'.$i].' -- Unidad: '.$_POST['unidadFuncionario'.$i].'<br><br>';
//
//        }

        $getUnidadFormatosCCAA = getUnidadFormatosCCAA($unidadFormCCAA);

        $queryRegistrarSolicitud = 'INSERT INTO formularios_registro(idFormRegAcceso,folioFormRegAcceso,unidadFormRegAcceso,fechaFormRegAcceso,fechaCreaFormRegAcceso) VALUES (NULL,"",' . $unidadFormCCAA . ',"' . $now . '","'.$now.'")';

        $resRegistrarSolicitud = mysqli_query($conexion, $queryRegistrarSolicitud);

        if ($resRegistrarSolicitud) {

            $idRegistrarSolicitud = mysqli_insert_id($conexion);

            if ($idRegistrarSolicitud < 10) {
                $idRegistrarSolicitud2 = "0000" . $idRegistrarSolicitud;
            } else if ($idRegistrarSolicitud > 9 && $idRegistrarSolicitud < 100) {
                $idRegistrarSolicitud2 = "000" . $idRegistrarSolicitud;
            } else if ($idRegistrarSolicitud > 99 && $idRegistrarSolicitud < 1000) {
                $idRegistrarSolicitud2 = "00" . $idRegistrarSolicitud;
            } else if ($idRegistrarSolicitud > 999) {
                $idRegistrarSolicitud2 = "0" . $idRegistrarSolicitud;
            } else {
                $idRegistrarSolicitud2 = $idRegistrarSolicitud;
            }

            $folioRegistrarSolicitud = "FCA-A-" . $idRegistrarSolicitud2;

            $queryFolioSolicitud = 'UPDATE formularios_registro SET folioFormRegAcceso = "' . $folioRegistrarSolicitud . '" WHERE idFormRegAcceso = ' . $idRegistrarSolicitud;

//            echo $queryFolioSolicitud;
            $resFolioSolicitud = mysqli_query($conexion, $queryFolioSolicitud);

            $conteoFuncionariosExito = 0;

            $conteoFuncionariosError = 0;

            if ($resFolioSolicitud) {

                for ($i = 1; $i <= $cantidadFilas; $i++) {

//                    echo 'Funcionario '.$i.'<br><br>';

                    $nomFuncionario = $_POST['nomFuncionario' . $i];
                    $rutFuncionario = $_POST['rutFuncionario' . $i];
                    $calidadFuncionario = $_POST['calidadJuridica' . $i];
                    $unidadFuncionario = $_POST['unidadFuncionario' . $i];

//                    echo 'Nombre: '.$nomFuncionario.'<br>';
//                    echo 'RUT: '.$rutFuncionario.'<br>';
//                    echo 'Calidad: '.$calidadFuncionario.'<br>';
//                    echo 'Unidad: '.$unidadFuncionario.'<br><br>';

                    $nomFuncionario = trim($nomFuncionario);

                    if (!empty($nomFuncionario)) {
                        $cadenaPuertas = '';
                        for ($j = 1; $j <= 18; $j++) {
                            $chkPuerta = $_POST['chkPuerta' . $j . '-' . $i];

                            if (!empty($chkPuerta)) {
                                $cadenaPuertas .= '1,';
                            } else {
                                $cadenaPuertas .= '0,';
                            }

                        }

                        $now2 = date('Y-m-d H:i:s', time());

                        $sqlInsertFuncionarios = 'INSERT INTO formularios_funcionarios(idFormFuncCCAA,nomFormFuncCCAA,rutFormFuncCCAA,calidadFormFuncCCAA,unidadFormFuncCCAA,fechaFormCCAA,door1FormCCAA,door2FormCCAA,door3FormCCAA,door4FormCCAA,door5FormCCAA,door6FormCCAA,door7FormCCAA,door8FormCCAA,door9FormCCAA,door10FormCCAA,door11FormCCAA,door12FormCCAA,door13FormCCAA,door14FormCCAA,door15FormCCAA,door16FormCCAA,door17FormCCAA,door18FormCCAA,idFormRegAcceso) ';
                        $sqlInsertFuncionarios .= ' VALUES(NULL,"' . $nomFuncionario . '","' . $rutFuncionario . '","' . $calidadFuncionario . '","' . $unidadFuncionario . '","' . $now2 . '",' . $cadenaPuertas . $idRegistrarSolicitud . ')';

                        $resInsertFuncionarios = mysqli_query($conexion, $sqlInsertFuncionarios);

                        if ($resInsertFuncionarios) {
                            $conteoFuncionariosExito++;
                        }else{
                            $conteoFuncionariosError++;
                        }

                    } else {

                        $conteoFuncionariosError = 0;
                    }
//

                }

                if($cantidadFilas == $conteoFuncionariosError){
                    header('Location: ../sdo-flujo/accesos_seleccion.php?errorGenerarFormulario1=true');
                }else if($conteoFuncionariosExito == 0){
                    header('Location: ../sdo-flujo/accesos_seleccion.php?errorGenerarFormulario2=true');
                }

//                echo '<script language="javascript">alert("'.$conteoFuncionariosError.'");</script>';


            } else {
                header('Location: ../sdo-flujo/accesos_seleccion.php?errorGenerarFormulario3=true');
            }


        } else {
            header('Location: ../sdo-flujo/accesos_seleccion.php?errorGenerarFormulario4=true');
        }

    } else {
        header('Location: ../sdo-flujo/accesos_seleccion.php?errorGenerarFormulario5=true');
    }

} else {
    header('Location: ../sdo-flujo/accesos_seleccion.php?errorGenerarFormulario6=true');
}


?>