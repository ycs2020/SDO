<?php

include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
} else {
    session_destroy();
    session_start();
}
//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

$rutaDireccion = "";

if ($rolUsuario2 == "personal") {
    $tipoUsuario = 'personal';
    $rutaDireccion = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $tipoUsuario = 'administrativo';
    $rutaDireccion = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $tipoUsuario = 'bodega pañol';
    $rutaDireccion = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $tipoUsuario = 'jefe tecnico';
    $rutaDireccion = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $tipoUsuario = 'administrador';
    $rutaDireccion = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $tipoUsuario = 'directiva';
    $rutaDireccion = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

date_default_timezone_set("America/Santiago");
setlocale(LC_TIME, 'es_CL.UTF-8', 'esp');

$now = date('Y-m-d H:i:s', time());

$solicitanteFormRegAcceso = $_POST['solicitanteFormRegAcceso'];
$unidadSolicitaFormRegAcceso = $_POST['unidadSolicitaFormRegAcceso'];
$emailSolicitaFormRegAcceso = $_POST['emailSolicitaFormRegAcceso'];


$conteo = 0;
$unidad = 0;

$cantidadFilas = $_POST['cantidadFilas'];

$nombreUnidades = ( unserialize(base64_decode($_POST['nombreUnidades'])));
$queryRegistrarSolicitud = 'INSERT INTO formularios_registro (idFormRegAcceso,folioFormRegAcceso,unidadFormRegAcceso,fechaFormRegAcceso,fechaCreaFormRegAcceso,fechaRecepcionFormRegAcceso,categoriaFormRegAcceso,solicitanteFormRegAcceso,unidadSolicitaFormRegAcceso,emailSolicitaFormRegAcceso,conteo) 
        VALUES (NULL,"",' . $unidad . ',"' . $now . '","' . $now . '",NULL,"Nuevo","' . $solicitanteFormRegAcceso . '","'.$unidadSolicitaFormRegAcceso.'","' . $emailSolicitaFormRegAcceso . '","'.$conteo.'")';
$resRegistrarSolicitud = mysqli_query($conexion, $queryRegistrarSolicitud);
if($resRegistrarSolicitud){

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


    $folioRegistrarSolicitud = "FCA-" . $idRegistrarSolicitud2."-N";
    $queryFolioSolicitud = 'UPDATE formularios_registro SET folioFormRegAcceso = "' . $folioRegistrarSolicitud . '" WHERE idFormRegAcceso = ' . $idRegistrarSolicitud;
    $resFolioSolicitud = mysqli_query($conexion, $queryFolioSolicitud);

    $conteoFuncionariosExito = 0;

    $conteoFuncionariosError = 0;

    if ($resFolioSolicitud) {
        foreach ($nombreUnidades  as $nomUn) {
            $cantidadPuertas = $_POST['cantidadPuertas'.$nomUn];
            $unidadFormCCAA = $_POST['unidadFormCCAA'.$nomUn];
            $cantidadFilas = $_POST['cantidadFilas'.$nomUn];

            if ($cantidadFilas > 0 && $cantidadPuertas > 0 && $unidadFormCCAA > 0) {
                for($i = 1; $i <= $cantidadFilas; $i++){

                    $nomFuncionario = $_POST['nomFuncionario' . $nomUn. $i];
                    $rutFuncionario = $_POST['rutFuncionario' . $nomUn.$i];
                    $calidadFuncionario = $_POST['calidadJuridica' . $nomUn.$i];
                    $unidadFuncionario = $_POST['unidadFuncionario' .$nomUn.$i];
                    $correlativotarjeta = $_POST['correlativotarjeta'.$nomUn.$i];

                    $nomFuncionario = trim($nomFuncionario);
                    if (!empty($nomFuncionario) && !empty($rutFuncionario)) {
                        $cadenaPuertas = '';
                        for ($j = 1; $j <= 18; $j++) {
                            $chkPuerta = $_POST['chkPuerta' . $j . '-' . $i.$nomUn];
                            if (!empty($chkPuerta)) {
                                $cadenaPuertas .= '1,';
                            } else {
                                $cadenaPuertas .= '0,';
                            }

                        }
                        $now2 = date('Y-m-d H:i:s', time());

                        $verificarRUT = verificarYFormatearRUT($rutFuncionario);
                        if ($verificarRUT) {
                            $sqlInsertFuncionarios = 'INSERT INTO formularios_funcionarios(idFormFuncCCAA,nomFormFuncCCAA,rutFormFuncCCAA,calidadFormFuncCCAA,unidadFormFuncCCAA,correlativoTarjeta,fechaFormCCAA,door1FormCCAA,door2FormCCAA,door3FormCCAA,door4FormCCAA,door5FormCCAA,door6FormCCAA,door7FormCCAA,door8FormCCAA,door9FormCCAA,door10FormCCAA,door11FormCCAA,door12FormCCAA,door13FormCCAA,door14FormCCAA,door15FormCCAA,door16FormCCAA,door17FormCCAA,door18FormCCAA,idFormRegAcceso,idUnidad,conteo) ';
                            $sqlInsertFuncionarios .= ' VALUES(NULL,"' . $nomFuncionario . '","' . $rutFuncionario . '","' . $calidadFuncionario . '","' . $unidadFuncionario . '","'.$correlativotarjeta.'","' . $now2 . '",' . $cadenaPuertas . $idRegistrarSolicitud . ',"'.$unidadFormCCAA.'","'.$conteo.'")';
                            $resInsertFuncionarios = mysqli_query($conexion, $sqlInsertFuncionarios);

                            if ($resInsertFuncionarios) {
                                $conteoFuncionariosExito++;
                            }else{
                                $conteoFuncionariosError++;
                            }

                        }else{
                            $conteoFuncionariosError++;
                        }

                    }else{
                        $conteoFuncionariosError = 0;
                    }

                }

                $now3= date('Y-m-d H:i:s', time());

                $observaEliminar = 'Debido a errores presentados, se elimina Folio: '.$folioRegistrarSolicitud;

                if($cantidadFilas == $conteoFuncionariosError){

                    $sqlBorraRegistro = "DELETE FROM formularios_registro WHERE idFormRegAcceso = ".$idRegistrarSolicitud;
                    $resBorraRegistro = mysqli_query($conexion, $sqlBorraRegistro);


                    $sqlObservaBorrar = 'INSERT INTO formularios_observaciones(idObservacion,detalleObs,fechaObs,creador,idFormRegAcceso,idFormFuncCCAA,folioFormRegAcceso) VALUES (NULL,"'.$observaEliminar.'","' . $now3 . '","Administrador de Sistema",0,0,"' . $folioRegistrarSolicitud . '")';

                    $resObservaBorrar = mysqli_query($conexion, $sqlObservaBorrar);

                    header('Location: ../'.$rutaDireccion.'/formulariosCCAA_listado.php?errorGenerarFormulario1=true');
                }else if($conteoFuncionariosExito == 0){

                    $sqlBorraRegistro = "DELETE FROM formularios_registro WHERE idFormRegAcceso = ".$idRegistrarSolicitud;
                    $resBorraRegistro = mysqli_query($conexion, $sqlBorraRegistro);

                    $sqlObservaBorrar = 'INSERT INTO formularios_observaciones(idObservacion,detalleObs,fechaObs,creador,idFormRegAcceso,idFormFuncCCAA,folioFormRegAcceso) VALUES (NULL,"'.$observaEliminar.'","' . $now3 . '","Administrador de Sistema",0,0,"' . $folioRegistrarSolicitud . '")';

//                    echo $sqlObservaBorrar;
//                    exit;
                    $resObservaBorrar = mysqli_query($conexion, $sqlObservaBorrar);

                    header('Location: ../'.$rutaDireccion.'/formulariosCCAA_listado.php?errorGenerarFormulario2=true');
                }else{
                    $_SESSION['idFormRegAcceso'] = $idRegistrarSolicitud;
                    header('Location: ../'.$rutaDireccion.'/formulariosCCAA_administracion_2.php?exitoGenerarFormulario=true');
                }
            } else {
                header('Location: ../'.$rutaDireccion.'/formulariosCCAA_menuSeleccion.php?errorGenerarFormulario6=true');
            }
        }

    }else{
        header('Location: ../'.$rutaDireccion.'/formulariosCCAA_menuSeleccion.php?errorGenerarFormulario3=true');
    }
}





