<?php

include('funciones.php');
include_once('conexion.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$rolUsuario = '';
$rutaRol = '';
$rutaError = '';

if (isset($_POST['rolUsuario'])) {

    $rolUsuario = trim($_POST['rolUsuario']);

    if (!empty($rolUsuario)) {
        if ($rolUsuario == "Administrador") {

            $rutaRol = "sdo-administrador/actas-funcionario.php";

            $rutaError = "sdo-administrador/actas-listado.php";
        } else if ($rolUsuario == "Personal") {

            $rutaRol = "sdo-personal/actas-funcionario.php";

            $rutaError = "sdo-personal/actas-listado.php";
        } else if ($rolUsuario == "Directiva") {

            $rutaRol = "sdo-directiva/actas-funcionario.php";

            $rutaError = "sdo-directiva/actas-listado.php";
        } else if ($rolUsuario == "Jefe Tecnico") {

            $rutaRol = "sdo-jefestecnicos/actas-funcionario.php";

            $rutaError = "sdo-jefestecnicos/actas-listado.php";
        } else if ($rolUsuario == "Administrativo") {

            $rutaRol = "sdo-admtec/actas-listado.php";

            $rutaError = "sdo-admtec/actas-listado.php";
        } else {

            header('Location: ../sdo-flujo/login_index.php?errorLogin=true');
        }



        if (isset($_POST['nomTarjeta'], $_POST['rutTarjeta'], $_POST['calidadContractual'], $_POST['codigoTarjeta'], $_POST['unidadTarjeta'], $_POST['nomSolicitante'], $_POST['nomTecnicoTurno'], $_POST['numTelefono'], $_POST['valorDiscapacidad'], $_POST['valorBeca'], $_POST['datosUnidad'])) {

            //OBTENER nomTarjeta Y CAPITALIZARLO 
            $nomTarjeta = trim($_POST['nomTarjeta']);
            $nomTarjeta = mb_convert_case($nomTarjeta, MB_CASE_LOWER, "UTF-8");
            $nomTarjeta = mb_convert_case($nomTarjeta, MB_CASE_UPPER, "UTF-8");
            $otrocontrol = trim($_POST['otrocontrol']);
            $otrocontrol = mb_convert_case($otrocontrol, MB_CASE_UPPER, "UTF-8");
            //FIN OBTENER nomTarjeta Y CAPITALIZARLO

            //OBTENER codigoTarjeta Y CAPITALIZARLO
            $codigoTarjeta = trim($_POST['codigoTarjeta']);
            $codigoTarjeta = mb_convert_case($codigoTarjeta, MB_CASE_UPPER, "UTF-8");
            //FIN OBTENER codigoTarjeta Y CAPITALIZARLO


            $rutTarjeta = trim($_POST['rutTarjeta']);
            $calidadContractual = trim($_POST['calidadContractual']);
            $numTelefono = trim($_POST['numTelefono']);

            while (strlen($rutTarjeta) < 9) {
                $rutTarjeta = "0" . $rutTarjeta;
            }

            //OBTENER unidadTarjeta Y CAPITALIZARLO
            $unidadTarjeta = trim($_POST['unidadTarjeta']);
            $unidadTarjeta = mb_convert_case($unidadTarjeta, MB_CASE_UPPER, "UTF-8");

        
            //FIN OBTENER unidadTarjeta Y CAPITALIZARLO

            //OBTENER nomSolicitante Y CAPITALIZARLO
            $nomSolicitante = trim($_POST['nomSolicitante']);
            $nomSolicitante = mb_convert_case($nomSolicitante, MB_CASE_LOWER, "UTF-8");
            $nomSolicitante = mb_convert_case($nomSolicitante, MB_CASE_UPPER, "UTF-8");
            //FIN OBTENER nomSolicitante Y CAPITALIZARLO


            if ($unidadTarjeta == 'OTRO') {
                if (isset($_POST['unidadTarjeta2'], $_POST['nomSolicitante2'])) {
                    $unidadTarjeta2 = trim($_POST['unidadTarjeta2']);
                    $nomSolicitante2 = trim($_POST['nomSolicitante2']);

                    if (!empty($unidadTarjeta2) && !empty($nomSolicitante2)) {
                        $unidadTarjeta2 = mb_convert_case($unidadTarjeta2, MB_CASE_UPPER, "UTF-8");
                        $nomSolicitante2 = mb_convert_case($nomSolicitante2, MB_CASE_UPPER, "UTF-8");

                        $unidadTarjeta = $unidadTarjeta2;
                        $nomSolicitante = $nomSolicitante2;
                    }
                }
            }


            //OBTENER nomTecnicoTurno Y CAPITALIZARLO
            $nomTecnicoTurno = trim($_POST['nomTecnicoTurno']);
            $nomTecnicoTurno = mb_convert_case($nomTecnicoTurno, MB_CASE_LOWER, "UTF-8");
            $nomTecnicoTurno = mb_convert_case($nomTecnicoTurno, MB_CASE_UPPER, "UTF-8");
            //FIN OBTENER nomTecnicoTurno Y CAPITALIZARLO

            
            if ($nomSolicitante == 'OTRONOMBRE') {
                if (isset($_POST['nomSolicitante3'])) {
                    $nomSolicitante3 = trim($_POST['nomSolicitante3']);

                    if (!empty($nomSolicitante3)) {
                        $nomSolicitante3 = mb_convert_case($nomSolicitante3, MB_CASE_UPPER, "UTF-8");

                        $nomSolicitante = $nomSolicitante3;
                    }
                }
            }


            $valorDiscapacidad = trim($_POST['valorDiscapacidad']);
            $valorBeca = trim($_POST['valorBeca']);

            $datosUnidad = trim($_POST['datosUnidad']);
            $datosUnidad = mb_convert_case($datosUnidad, MB_CASE_UPPER, "UTF-8");

            $esDiscapacitado = 0;

            $esBecado = 0;

//            date_default_timezone_set('America/Santiago');
//
//             echo $nomTarjeta.'<br><br>';
//
//             echo $codigoTarjeta.'<br><br>';
//
//             echo $unidadTarjeta.'<br><br>';
//
//             echo $nomSolicitante.'<br><br>';
//
//             echo $nomTecnicoTurno.'<br><br>';
//
//             echo $numTelefono.'<br><br>';
//
//             echo $valorDiscapacidad.'<br><br>';
//
//             echo $valorBeca.'<br><br>';
//
//             exit();

            if (!empty($nomTarjeta) && !empty($codigoTarjeta) && !empty($unidadTarjeta) && !empty($nomSolicitante) && !empty($nomTecnicoTurno) && !empty($numTelefono) && !empty($valorDiscapacidad) && !empty($valorBeca)) {

                if ($valorDiscapacidad == 'SI') {
                    $esDiscapacitado = 1;
                } else if ($valorDiscapacidad == 'NO') {
                    $esDiscapacitado = 0;
                } else {
                    $esDiscapacitado = 0;
                }

                if ($valorBeca == 'SI') {
                    $esBecado = 1;
                } else if ($valorBeca == 'NO') {
                    $esBecado = 0;
                } else {
                    $esBecado = 0;
                }
                $nomUsuario = $_POST['nomUsuario'];

                $detalleObs = $nomUsuario.' ha dado ingreso en el sistema de actas a '.$nomTarjeta;
                date_default_timezone_set('America/Santiago');
                $now = date('Y-m-d H:i:s', time());


                if($otrocontrol == '' || empty($otrocontrol)){
                    $query = "INSERT INTO actas(idActa,fechaActa,folioActa,codigoTarjeta,nomTarjeta,rutTarjeta,unidadTarjeta,nomSolicitante,nomControlCentralizado,calidadContractual,numTelefono,tipoActa,personaDiscapacitada,personaBecada)";
                    $query .=  "VALUES (NULL,'" . $now . "','','" . $codigoTarjeta . "','" . $nomTarjeta . "','" . $rutTarjeta . "','" . $unidadTarjeta . "','" . $nomSolicitante . "','" . $nomTecnicoTurno . "','" . $calidadContractual . "','" . $numTelefono . "','Activación','" . $esDiscapacitado . "','" . $esBecado . "')";
                }else{
                    $query = "INSERT INTO actas(idActa,fechaActa,folioActa,codigoTarjeta,nomTarjeta,rutTarjeta,unidadTarjeta,nomSolicitante,nomControlCentralizado,calidadContractual,numTelefono,tipoActa,personaDiscapacitada,personaBecada)";
                    $query .=  "VALUES (NULL,'" . $now . "','','" . $codigoTarjeta . "','" . $nomTarjeta . "','" . $rutTarjeta . "','" . $unidadTarjeta . "','" . $nomSolicitante . "','" . $otrocontrol . "','" . $calidadContractual . "','" . $numTelefono . "','Activación','" . $esDiscapacitado . "','" . $esBecado . "')";
                }

                $res = mysqli_query($conexion, $query);

                if ($res) {

                    $_SESSION['rutActa2'] = $rutTarjeta;

                    $idActa = mysqli_insert_id($conexion);

                    $_SESSION['idActa'] = $idActa;

                    $anio = date("Y");

                    $mes = date("m");

                    $folioActa = "A-" . $idActa . "-" . $codigoTarjeta;

                    $queryFolio = "UPDATE actas SET folioActa = '" . $folioActa . "' WHERE idActa =" . $idActa;

                    $resFolio = mysqli_query($conexion, $queryFolio);

                    if ($resFolio) {

                        $sqlObservacion = "INSERT INTO actas_observacion (idActasObs,fechaIngreso,nomFuncionario,rutFuncionario,detalleObservacion,calidadContractual,unidadFuncionario,creadorObs,idActa)
                         VALUES (NULL,'".$now."','".$nomTarjeta."','".$rutTarjeta."','".$detalleObs."','".$calidadContractual."','".$unidadTarjeta."','".$nomUsuario."',$idActa)";
                        $resObservacion = mysqli_query($conexion,$sqlObservacion);

                        if (isset($_POST['patente1'], $_POST['patente2'], $_POST['modelo1'], $_POST['modelo2'])) {

                            $patente1 = trim($_POST['patente1']);
                            $patente2 = trim($_POST['patente2']);
                            $modelo1 = trim($_POST['modelo1']);
                            $modelo2 = trim($_POST['modelo2']);

                            if (empty($patente1) || empty($modelo1)) {
                                header('Location: ../' . $rutaRol . '?exitoCrearActa=true&vehiculo1Vacio=true');
                            } else {
                                if (buscarPatente($patente1) == 0 || empty(buscarPatente($patente1))) {
                                    $queryParking1 = "INSERT INTO parking(idParking,patenteParking,modeloParking,rutParking)";
                                    $queryParking1 .=  "VALUES (NULL,'" . $patente1 . "','" . $modelo1 . "','" . $rutTarjeta . "')";
                                    $resParking1 = mysqli_query($conexion, $queryParking1);

                                    if ($resParking1) {
                                        if (empty($patente2) || empty($modelo2)) {
                                            header('Location: ../' . $rutaRol . '?exitoCrearActa=true&soloVehiculo1=true');
                                        } else {
                                            if (buscarPatente($patente2) == 0 || empty(buscarPatente($patente2))) {
                                                $queryParking2 = "INSERT INTO parking(idParking,patenteParking,modeloParking,rutParking)";
                                                $queryParking2 .=  "VALUES (NULL,'" . $patente2 . "','" . $modelo2 . "','" . $rutTarjeta . "')";
                                                $resParking2 = mysqli_query($conexion, $queryParking2);

                                                if ($resParking2) {
                                                    header('Location: ../' . $rutaRol . '?exitoCrearActaVehiculo1y2=true');
                                                } else {
                                                    header('Location: ../' . $rutaRol . '?exitoCrearActa=true&soloVehiculo1=true&errorVehiculo2=true');
                                                }
                                            } else {
                                                header('Location: ../' . $rutaRol . '?exitoCrearActa=true&soloVehiculo1=true&existePatente2=true');
                                            }
                                        }
                                    } else {
                                        header('Location: ../' . $rutaRol . '?exitoCrearActa=true&errorVehiculo1=true');
                                    }
                                } else {
                                    header('Location: ../' . $rutaRol . '?exitoCrearActa=true&existePatente1=true');
                                }
                            }
                        } else {
                            header('Location: ../' . $rutaRol . '?exitoCrearActa=true&sinEstacionamiento=true');
                        }
                    } else {
                        header('Location: ../' . $rutaError . '?errorCrearFolio=true');
                    }
                } else {
                    header('Location: ../' . $rutaError . '?errorCrearActa1=true');
                }
            } else {
                header('Location: ../' . $rutaError . '?errorCrearActa2=true');
            }

            
        } else {
            header('Location: ../' . $rutaError . '?errorCrearActa3=true');
        }
    } else {
        header('Location: ../sdo-flujo/login_index.php?errorLogin=true');
    }
} else {
    header('Location: ../sdo-flujo/login_index.php?errorLogin=true');
}
