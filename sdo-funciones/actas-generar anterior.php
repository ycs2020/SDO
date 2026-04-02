<?php
include_once('conexion.php');
include_once('funciones.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if (isset($_POST['nomTarjeta'], $_POST['rutTarjeta'], $_POST['calidadContractual'], $_POST['codigoTarjeta'], $_POST['unidadTarjeta'], $_POST['nomSolicitante'], $_POST['nomTecnicoTurno'], $_POST['numTelefono'], $_POST['valorDiscapacidad'], $_POST['valorBeca'])) {

    //OBTENER nomTarjeta Y CAPITALIZARLO 
    $nomTarjeta = trim($_POST['nomTarjeta']);
    $nomTarjeta = mb_convert_case($nomTarjeta, MB_CASE_LOWER, "UTF-8");
    $nomTarjeta = mb_convert_case($nomTarjeta, MB_CASE_TITLE, "UTF-8");
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

    //OBTENER nomTecnicoTurno Y CAPITALIZARLO
    $nomTecnicoTurno = trim($_POST['nomTecnicoTurno']);
    $nomTecnicoTurno = mb_convert_case($nomTecnicoTurno, MB_CASE_LOWER, "UTF-8");
    $nomTecnicoTurno = mb_convert_case($nomTecnicoTurno, MB_CASE_TITLE, "UTF-8");
    //FIN OBTENER nomTecnicoTurno Y CAPITALIZARLO

    //OBTENER nomSolicitante Y CAPITALIZARLO
    $nomSolicitante = trim($_POST['nomSolicitante']);
    $nomSolicitante = mb_convert_case($nomSolicitante, MB_CASE_LOWER, "UTF-8");
    $nomSolicitante = mb_convert_case($nomSolicitante, MB_CASE_TITLE, "UTF-8");
    //FIN OBTENER nomSolicitante Y CAPITALIZARLO


    $valorDiscapacidad = trim($_POST['valorDiscapacidad']);
    $valorBeca = trim($_POST['valorBeca']);


    $esDiscapacitado = 0;

    $esBecado = 0;

    date_default_timezone_set('America/Santiago');

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

        date_default_timezone_set('America/Santiago');
        $now = date('Y-m-d H:i:s', time());
        $query = "INSERT INTO actas(idActa,fechaActa,folioActa,codigoTarjeta,nomTarjeta,rutTarjeta,unidadTarjeta,nomSolicitante,nomControlCentralizado,calidadContractual,numTelefono,tipoActa,personaDiscapacitada,personaBecada)";
        $query .=  "VALUES (NULL,'".$now."','','" . $codigoTarjeta . "','" . $nomTarjeta . "','" . $rutTarjeta . "','" . $unidadTarjeta . "','" . $nomSolicitante . "','" . $nomTecnicoTurno . "','" . $calidadContractual . "','" . $numTelefono . "','Activación','" . $esDiscapacitado . "','" . $esBecado . "')";
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

                if (isset($_POST['patente1'], $_POST['patente2'], $_POST['modelo1'], $_POST['modelo2'])) {

                    $patente1 = trim($_POST['patente1']);
                    $patente2 = trim($_POST['patente2']);
                    $modelo1 = trim($_POST['modelo1']);
                    $modelo2 = trim($_POST['modelo2']);

                    if (empty($patente1) || empty($modelo1)) {
                        header('Location: ../sdo-personal/actas-funcionario.php?exitoCrearActa=true&vehiculo1Vacio=true');
                    } else {
                        if (buscarPatente($patente1) == 0 || empty(buscarPatente($patente1))) {
                            $queryParking1 = "INSERT INTO parking(idParking,patenteParking,modeloParking,rutParking)";
                            $queryParking1 .=  "VALUES (NULL,'" . $patente1 . "','" . $modelo1 . "','" . $rutTarjeta . "')";
                            $resParking1 = mysqli_query($conexion, $queryParking1);

                            if ($resParking1) {
                                if (empty($patente2) || empty($modelo2)) {
                                    header('Location: ../sdo-personal/actas-funcionario.php?exitoCrearActa=true&soloVehiculo1=true');
                                } else {
                                    if (buscarPatente($patente2) == 0 || empty(buscarPatente($patente2))) {
                                        $queryParking2 = "INSERT INTO parking(idParking,patenteParking,modeloParking,rutParking)";
                                        $queryParking2 .=  "VALUES (NULL,'" . $patente2 . "','" . $modelo2 . "','" . $rutTarjeta . "')";
                                        $resParking2 = mysqli_query($conexion, $queryParking2);

                                        if ($resParking2) {
                                            header('Location: ../sdo-personal/actas-funcionario.php?exitoCrearActaVehiculo1y2=true');
                                        } else {
                                            header('Location: ../sdo-personal/actas-funcionario.php?exitoCrearActa=true&soloVehiculo1=true&errorVehiculo2=true');
                                        }
                                    } else {
                                        header('Location: ../sdo-personal/actas-funcionario.php?exitoCrearActa=true&soloVehiculo1=true&existePatente2=true');
                                    }
                                }
                            } else {
                                header('Location: ../sdo-personal/actas-funcionario.php?exitoCrearActa=true&errorVehiculo1=true');
                            }
                        } else {
                            header('Location: ../sdo-personal/actas-funcionario.php?exitoCrearActa=true&existePatente1=true');
                        }
                    }
                } else {
                    header('Location: ../sdo-personal/actas-funcionario.php?exitoCrearActa=true&sinEstacionamiento=true');
                }
            } else {
                header('Location: ../sdo-personal/actas-listado.php?errorCrearFolio=true');
            }
        } else {
            header('Location: ../sdo-personal/actas-listado.php?errorCrearActa=true');
        }
    } else {
    }
} else {
    header('Location: ../sdo-personal/actas-listado.php?errorCrearActa=true');
}

mysqli_close($conexion);
