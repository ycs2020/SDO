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

if (isset($_POST['cantidadFilas'], $_POST['idFuncionarios'], $_POST['solicitanteFormRegAcceso'], $_POST['numDocumentoSolicitaFormRegAcceso'], $_POST['rutListFuncionarios'], $_POST['tipoDocumentoSolicitaFormRegAcceso'], $_POST['unidadSolicitaFormRegAcceso'], $_POST['emailSolicitaFormRegAcceso'], $_POST['fechaDocumentoBloqueo'], $_POST['categoriaFormulario'])) {

    $cantidadFilas = $_POST['cantidadFilas'];
    $idFuncionarios = $_POST['idFuncionarios'];
    $conteo = count($idFuncionarios);
    $unidadFormCCAA = $_POST['unidadFormCCAA'];

    $categoriaFormulario = $_POST['categoriaFormulario'];
    $tipoDocumentoSolicitaFormRegAcceso = $_POST['tipoDocumentoSolicitaFormRegAcceso'];
    $numDocumentoSolicitaFormRegAcceso = $_POST['numDocumentoSolicitaFormRegAcceso'];
    $rutListFuncionarios = $_POST['rutListFuncionarios'];

    $solicitanteFormRegAcceso = $_POST['solicitanteFormRegAcceso'];
    $unidadSolicitaFormRegAcceso = $_POST['unidadSolicitaFormRegAcceso'];
    $emailSolicitaFormRegAcceso = $_POST['emailSolicitaFormRegAcceso'];
    $fechaDocumentoBloqueo = $_POST['fechaDocumentoBloqueo'];
    $fechaDocumentoBloqueo2 = date('Y-m-d',strtotime($fechaDocumentoBloqueo));
    $fechaDocumentoBloqueo3 = $fechaDocumentoBloqueo2.' 08:00:00';
    $fechaDocumentoBloqueo4 = date('Y-m-d H:i:s',strtotime($fechaDocumentoBloqueo3));


    if ($cantidadFilas > 0 && $conteo > 0 && !empty($solicitanteFormRegAcceso) && !empty($tipoDocumentoSolicitaFormRegAcceso) &&!empty($rutListFuncionarios) && !empty($numDocumentoSolicitaFormRegAcceso) && !empty($unidadSolicitaFormRegAcceso) && !empty($emailSolicitaFormRegAcceso) && !empty($fechaDocumentoBloqueo4) && !empty($categoriaFormulario)) {


        $queryRegistrarSolicitud = 'INSERT INTO formularios_registro(idFormRegAcceso,folioFormRegAcceso,unidadFormRegAcceso,fechaFormRegAcceso,fechaCreaFormRegAcceso,fechaRecepcionFormRegAcceso,categoriaFormRegAcceso,solicitanteFormRegAcceso,unidadSolicitaFormRegAcceso,emailSolicitaFormRegAcceso,tipoDocumento,numeroDocumento) VALUES (NULL,"",0,"' . $fechaDocumentoBloqueo4 . '","'.$now.'",NULL,"'.$categoriaFormulario.'","' . $solicitanteFormRegAcceso . '","' . $unidadSolicitaFormRegAcceso . '","' . $emailSolicitaFormRegAcceso . '","' . $tipoDocumentoSolicitaFormRegAcceso . '","' . $numDocumentoSolicitaFormRegAcceso . '")';

//        echo $queryRegistrarSolicitud;

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

            $primerCaracter = $categoriaFormulario[0];
            $primerCaracter2 = mb_convert_case($primerCaracter,MB_CASE_UPPER,'UTF-8');
            $sinAcentos = iconv('UTF-8', 'ASCII//TRANSLIT', $primerCaracter2);

            $folioRegistrarSolicitud = "FCA-" . $idRegistrarSolicitud2 . "-".$sinAcentos;

            $queryFolioSolicitud = 'UPDATE formularios_registro SET folioFormRegAcceso = "' . $folioRegistrarSolicitud . '" WHERE idFormRegAcceso = ' . $idRegistrarSolicitud;

//            echo $queryFolioSolicitud;
            $resFolioSolicitud = mysqli_query($conexion, $queryFolioSolicitud);

            $conteoFuncionariosExito = 0;

            $conteoFuncionariosError = 0;

            if ($resFolioSolicitud) {

                for ($i = 0; $i < $cantidadFilas; $i++) {

//                    echo 'Funcionario '.$i.'<br><br>';

                    $idBuscaFunc = $idFuncionarios[$i];

                    $getFuncionarioACMxID = array();
                    $getFuncionarioACMxID = getFuncionarioACMxID($idBuscaFunc);


                    if($getFuncionarioACMxID == 'No Existe' || empty($getFuncionarioACMxID)){

                        $conteoFuncionariosError = 0;
                    }else{
                        $nomFuncionario = $getFuncionarioACMxID['OWNER_Name'];
                        $rutFuncionario = $rutListFuncionarios[$i];
                        $rutControl = $getFuncionarioACMxID['OWNER_CODE'];
                        $obtenerCalidadJuridica = obtenerCalidadJuridica($rutControl);
                        $nomDeptoACM = $getFuncionarioACMxID['nomDepartament'];
                        $posicion = strrpos($nomDeptoACM, '\\');

                        // Obtiene el texto después del último backslash
                        $texto_despues_backslash = substr($nomDeptoACM, $posicion + 1);

                        echo 'Nombre: '.$nomFuncionario.'<br>';
                        echo 'RUT: '.$rutFuncionario.'<br>';
                        echo 'Calidad: '.$obtenerCalidadJuridica.'<br>';
                        echo 'Unidad: '.$texto_despues_backslash.'<br><br>';

                        $nomFuncionario = trim($nomFuncionario);

                        if (!empty($nomFuncionario) && !empty($rutFuncionario)) {

                            $now2 = date('Y-m-d H:i:s', time());


                            $verificarRUT = verificarYFormatearRUT($rutFuncionario);

                            if ($verificarRUT) {

                                $sqlInsertFuncionarios = 'INSERT INTO formularios_funcionarios_bloqueo(idFormFuncCCAA,nomFormFuncCCAA,rutFormFuncCCAA,calidadFormFuncCCAA,unidadFormFuncCCAA,idFormRegBloqueo,fechaFormRegBloqueo) ';
                                $sqlInsertFuncionarios .= ' VALUES(NULL,"' . $nomFuncionario . '","' . $verificarRUT . '","' . $obtenerCalidadJuridica . '","' . $texto_despues_backslash . '","' . $idRegistrarSolicitud . '","' . $now2 . '")';

                                echo $sqlInsertFuncionarios;
                                $resInsertFuncionarios = mysqli_query($conexion, $sqlInsertFuncionarios);

                                if ($resInsertFuncionarios) {
                                    $conteoFuncionariosExito++;
                                } else {
                                    $conteoFuncionariosError++;
                                }
                            } else {
                                $conteoFuncionariosError++;
                            }

                        } else {

                            $conteoFuncionariosError = 0;
                        }
                    }


                }


                $now3 = date('Y-m-d H:i:s', time());

                $observaEliminar = 'Debido a errores presentados, se elimina Folio: ' . $folioRegistrarSolicitud;

                if ($cantidadFilas == $conteoFuncionariosError) {

                    $sqlBorraRegistro = "DELETE FROM formularios_registro WHERE idFormRegAcceso = " . $idRegistrarSolicitud;
                    $resBorraRegistro = mysqli_query($conexion, $sqlBorraRegistro);


                    $sqlObservaBorrar = 'INSERT INTO formularios_observaciones(idObservacion,detalleObs,fechaObs,creador,idFormRegAcceso,idFormFuncCCAA,folioFormRegAcceso) VALUES (NULL,"' . $observaEliminar . '","' . $now3 . '","Administrador de Sistema",0,0,"' . $folioRegistrarSolicitud . '")';

                    $resObservaBorrar = mysqli_query($conexion, $sqlObservaBorrar);
//                    echo $sqlObservaBorrar;
//                    exit;

                    header('Location: ../' . $rutaDireccion . '/formulariosCCAA_listado.php?errorGenerarFormulario1=true');
                } else if ($conteoFuncionariosExito == 0) {

                    $sqlBorraRegistro = "DELETE FROM formularios_registro WHERE idFormRegAcceso = " . $idRegistrarSolicitud;
                    $resBorraRegistro = mysqli_query($conexion, $sqlBorraRegistro);


                    $sqlObservaBorrar = 'INSERT INTO formularios_observaciones(idObservacion,detalleObs,fechaObs,creador,idFormRegAcceso,idFormFuncCCAA,folioFormRegAcceso) VALUES (NULL,"' . $observaEliminar . '","' . $now3 . '","Administrador de Sistema",0,0,"' . $folioRegistrarSolicitud . '")';

//                    echo $sqlObservaBorrar;
//                    exit;
                    $resObservaBorrar = mysqli_query($conexion, $sqlObservaBorrar);

                    header('Location: ../' . $rutaDireccion . '/formulariosCCAA_listado.php?errorGenerarFormulario2=true');
                } else {
                    $_SESSION['idFormRegAcceso'] = $idRegistrarSolicitud;
                    header('Location: ../' . $rutaDireccion . '/formulariosCCAA_administracion_2.php?exitoGenerarFormulario=true');

                }




            } else {
                header('Location: ../' . $rutaDireccion . '/formulariosCCAA_menuSeleccion.php?errorGenerarFormulario3=true');
            }


        } else {
            header('Location: ../' . $rutaDireccion . '/formulariosCCAA_menuSeleccion.php?errorGenerarFormulario4=true');
        }

    } else {
        header('Location: ../' . $rutaDireccion . '/formulariosCCAA_menuSeleccion.php?errorGenerarFormulario5=true');
    }

} else {
    header('Location: ../' . $rutaDireccion . '/formulariosCCAA_menuSeleccion.php?errorGenerarFormulario6=true');
}


?>