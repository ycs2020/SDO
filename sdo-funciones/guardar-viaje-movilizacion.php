<?php

include 'conexion.php';
include 'funciones.php';

date_default_timezone_set('America/Santiago');
setlocale(LC_ALL, "es_ES");
if (session_status() == PHP_SESSION_NONE) {
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
    $rutaDireccion = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $rutaDireccion = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $rutaDireccion = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $rutaDireccion = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $rutaDireccion = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $rutaDireccion = "sdo-directiva";
} else if ($rolUsuario2 == 'oficina tecnica'){
    $rutaDireccion = "sdo-oficinaTecnica";
} else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['numeroViaje'])) {

        $numeroViaje = $_POST['numeroViaje'];
        $fechaViaje = $_POST['fechaViaje'];
        $patenteVehiculo = $_POST['patenteVehiculo'];
        $conductorViaje = $_POST['conductorViaje'];
        $pasajeroViaje = $_POST['pasajeroViaje'];
        $partidaViaje = $_POST['partidaViaje'];
        $destinoViaje = $_POST['destinoViaje'];
        $horaInicio = $_POST['horaInicio'];
        $horaTermino = $_POST['horaTermino'];
        $archivosViajes = $_FILES['archivosViajes'];
        $tipoVehiculo = $_POST['tipoVehiculo'];
        $motivoViaje = $_POST['motivoViaje'];
        $horaInicio = $_POST['horaInicio'];
        $horaTermino = $_POST['horaTermino'];
        $fechaRegistroVale = $_POST['fechaRegistroVale'];
        $listadoUnidad = $_POST['listadoUnidad'];
        $unidadMovilizacion = $_POST['unidadMovilizacion'];
        $motivoViaje1 = $_POST['motivoViaje1'];
        $nit = $_POST['rutConductor'];
        $horaInicioExtencion = $_POST['horaInicioExtencion'];
        $horaTerminoExtencion = $_POST['horaTerminoExtencion'];
        $chkExtension = $_POST['chkExtension'];
        $respaldoViaje1 = $_POST['respaldoViaje1'];

        if($horaInicioExtencion == 0 || empty($horaInicioExtencion)){
            $horaInicioExtencion = '00:00';
        }
        if($horaTerminoExtencion == 0 || empty($horaTerminoExtencion)){
            $horaTerminoExtencion = '00:00';
        }

        if(!empty($chkExtension)){
            $chkExtension1 = 1;
        }else{
            $chkExtension1 = 0;

        }

        $responsable = $_POST['responsable'];


        if (!empty($numeroViaje)  || !empty($fechaViaje) || !empty($patenteVehiculo)  || !empty($conductorViaje)  || !empty($destinoViaje)  || !empty($horaInicio)  || !empty($horaTermino)) {

            if ($conductorViaje == 'Seleccione...' || $patenteVehiculo == 'Seleccione...' || $motivoViaje == 'Seleccione...') {
                header('Location: ../' . $rutaDireccion . '/listado-viajes.php?errorCrearViaje3=true');
                exit;
            }

            $motivoViaje = mb_convert_case($motivoViaje, MB_CASE_LOWER, 'UTF-8');
            $listadoUnidad = mb_convert_case($listadoUnidad, MB_CASE_LOWER, 'UTF-8');
            $conductorViaje = mb_convert_case($conductorViaje, MB_CASE_LOWER, 'UTF-8');
            $patenteVehiculo = mb_convert_case($patenteVehiculo, MB_CASE_LOWER, 'UTF-8');


            if ($motivoViaje == 'otro') {
                if (isset($_POST['otroMotivo'])) {
                    $otroMotivo = trim($_POST['otroMotivo']);

                    $otroMotivo = mb_convert_case($otroMotivo, MB_CASE_UPPER, 'UTF-8');

                    $otroMotivo2 = $otroMotivo;

                    $totalMotivo = buscarMotivo($otroMotivo2);
                    if (empty($otroMotivo2)) {
                        header('Location: ../' . $rutaDireccion . '/listado-viajes.php?errorCrearViaje=true');
                        exit;
                        // echo 'hay vacio<br><br>';

                    } else {

                        if ($totalMotivo > 0) {
                            echo 'EXISTE<br><br>';
                            echo $totalMotivo . '<br><br>';

                            $motivoViaje = $otroMotivo2;
                        } else {
                            $sqlInsert = "INSERT INTO motivos_movilizacion(idMotivo,nomMotivo) VALUES (NULL,'$otroMotivo2')";
                            $res = mysqli_query($conexion, $sqlInsert);

                            if ($res) {
                                $motivoViaje = $otroMotivo2;
                            } else {
                                header('Location: ../' . $rutaDireccion . '/listado-viajes.php?errorCrearViaje=true');
                            }
                        }
                    }
                } else {
                    header('Location: ../' . $rutaDireccion . '/listado-viajes.php?errorCrearViaje=true');
                    exit;
                    echo 'no hay otro<br><br>';
                }
            }

            if ($listadoUnidad == 'otro') {
                if (isset($_POST['otraUnidad'])) {
                    $otraUnidad = trim($_POST['otraUnidad']);

                    $otraUnidad = mb_convert_case($otraUnidad, MB_CASE_UPPER, 'UTF-8');

                    $otraUnidad2 = $otraUnidad;

                    $totalUnidad = buscarMotivo($otraUnidad2);
                    if (empty($otraUnidad2)) {
                        header('Location: ../' . $rutaDireccion . '/listado-viajes.php?errorCrearViaje=true');
                        exit;
                        // echo 'hay vacio<br><br>';

                    } else {

                        if ($totalUnidad > 0) {
                            echo 'EXISTE<br><br>';
                            echo $totalUnidad . '<br><br>';

                            $listadoUnidad = $otraUnidad2;


                        } else {
                            $sqlInsert = "INSERT INTO motivos_movilizacion(idMotivo,nomMotivo,motivoUnidad) VALUES (NULL,'$otraUnidad2',1)";
                            $res = mysqli_query($conexion, $sqlInsert);

                            if ($res) {
                                $listadoUnidad = $otraUnidad2;
                            } else {
                                header('Location: ../' . $rutaDireccion . '/listado-viajes.php?errorCrearViaje=true');
                            }
                        }
                    }
                } else {
                    header('Location: ../' . $rutaDireccion . '/listado-viajes.php?errorCrearViaje=true');
                    exit;
                    echo 'no hay otro<br><br>';
                }
            }

            if ($conductorViaje == 'otro' || $conductorViaje == 'Otro') {
                if (isset($_POST['otroConductor'])) {
                    $otroConductor = trim($_POST['otroConductor']);

                    $otroConductor = mb_convert_case($otroConductor, MB_CASE_UPPER, 'UTF-8');

                    $otroConductor2 = $otroConductor;

                    $totalConductor = buscarConductor($otroConductor2);

                    if (empty($otroConductor2)) {
                        header('Location: ../' . $rutaDireccion . '/listado-viajes.php?errorCrearViaje=true');
                        exit;
                        // echo 'hay vacio<br><br>';

                    } else {

                        if ($totalConductor > 0) {
                            echo 'EXISTE<br><br>';
                            echo $totalConductor . '<br><br>';

                            $conductorViaje = $otroConductor2;
                        } else {
                            $sqlInsert = "INSERT INTO conductores_movilizacion(idConductor ,nomConductor) VALUES (NULL,'$otroConductor2')";


                            $res = mysqli_query($conexion, $sqlInsert);

                            if ($res) {
                                $conductorViaje = $otroConductor2;
                            } else {
                                header('Location: ../' . $rutaDireccion . '/listado-viajes.php?errorCrearViaje=true');
                            }
                        }
                    }
                } else {
                    header('Location: ../' . $rutaDireccion . '/listado-viajes.php?errorCrearViaje=true');
                    exit;
                    echo 'no hay otro<br><br>';
                }
            }

            if ($unidadMovilizacion == 'otro' || $unidadMovilizacion == 'Otro') {
                if (isset($_POST['otraUnidad'])) {
                    $otraUnidad = trim($_POST['otraUnidad']);

                    $otraUnidad = mb_convert_case($otraUnidad, MB_CASE_UPPER, 'UTF-8');

                    $otraUnidad2 = $otraUnidad;

                    $totalUnidad = buscarUnidad($otraUnidad2);

                    if (empty($otraUnidad2)) {
                        header('Location: ../' . $rutaDireccion . '/listado-viajes.php?errorCrearViaje=true');
                        exit;
                        // echo 'hay vacio<br><br>';

                    } else {

                        if ($totalUnidad > 0) {
                            echo 'EXISTE<br><br>';
                            echo $totalUnidad  . '<br><br>';

                            $unidadMovilizacion = $otraUnidad2;

                        } else {
                            $sqlInsert = "INSERT INTO unidad_movilizacion (idUnidad ,nomUnidad) VALUES (NULL,'$otraUnidad2')";
                            $res = mysqli_query($conexion, $sqlInsert);

                            if ($res) {
                                $unidadMovilizacion = $otraUnidad2;
                            } else {
                                header('Location: ../' . $rutaDireccion . '/listado-viajes.php?errorCrearViaje=true');
                            }
                        }
                    }
                } else {
                    header('Location: ../' . $rutaDireccion . '/listado-viajes.php?errorCrearViaje=true');
                    exit;
                    echo 'no hay otro<br><br>';
                }
            }


            if ($patenteVehiculo == 'otro' || $patenteVehiculo == 'Otro') {
                if (isset($_POST['otroPatente'])) {
                    $otroPatente = trim($_POST['otroPatente']);

                    $otroPatente = mb_convert_case($otroPatente, MB_CASE_UPPER, 'UTF-8');

                    $otroPatente2 = $otroPatente;

                    $totalPatente = buscarPatenteMov($otroPatente2);
                    if (empty($otroPatente2)) {
                        header('Location: ../' . $rutaDireccion . '/listado-viajes.php?errorCrearViaje=true');
                        exit;
                        // echo 'hay vacio<br><br>';

                    } else {

                        if ($totalPatente > 0) {
                            echo 'EXISTE<br><br>';
                            echo $totalPatente . '<br><br>';

                            $patenteVehiculo = $otroPatente2;
                        } else {
                            $sqlInsert = "INSERT INTO patentes_movilizacion(idPatente  ,numPatente) VALUES (NULL,'$otroPatente2')";
                            $res = mysqli_query($conexion, $sqlInsert);

                            if ($res) {
                                $patenteVehiculo = $otroPatente2;
                            } else {
                                header('Location: ../' . $rutaDireccion . '/listado-viajes.php?errorCrearViaje=true');
                            }
                        }
                    }
                } else {
                    header('Location: ../' . $rutaDireccion . '/listado-viajes.php?errorCrearViaje=true');
                    exit;
                    echo 'no hay otro<br><br>';
                }
            }

            /* ===== REGLAS DE NEGOCIOS =====*/
            $inicio = new DateTime($horaInicio);
            $termino = new DateTime($horaTermino);

            $diferencia = $inicio->diff($termino);

            $diferenciaHora =  $diferencia->format('%H');
            $diferenciaMinutos =  $diferencia->format('%i');

            $diferenciaHora = intval($diferenciaHora);
            $diferenciaMinutos = intval($diferenciaMinutos);

            echo $diferenciaHora . "<br>";
            echo $diferenciaMinutos . "<br>";

            if ($diferenciaHora <= 4 && $diferenciaMinutos <= 30) {
                $tipoJornada = 'Medio Dia';
                $numTipoJornada = '1/2';
            } else if ($diferenciaHora <= 3 && $diferenciaMinutos <= 60) {
                $tipoJornada = 'Medio Dia';
                $numTipoJornada = '1/2';
            } else {
                $tipoJornada = 'Dia Completo';
                $numTipoJornada = '1';
            }

            //CALCULA SI ES DÍA HABIL O INHABIL
            $diaSemana  = get_nombre_dia($fechaViaje);

            if ($diaSemana == 'Domingo') {
                $tipoDia = 'Inhabil';
            } else {
                $tipoDia = 'Habil';
            }

            if($unidadMovilizacion == 'HOSPITALIZACION DOMICILIARIA'){
                if(!empty($chkExtension)){
                    if(!empty($horaInicioExtencion) && !empty($horaTerminoExtencion)){
                        if($destinoViaje == 'Comuna de Quillota' || $destinoViaje == 'Provincia de Quillota'){
                            if($tipoVehiculo == '1' && $tipoDia == 'Habil'){
                                $valorExtension1 = 65000;
                            }else if($tipoVehiculo == '1' && $tipoDia == 'Inhabil'){
                                $valorExtension1 = 80000;
                            }
                            if($tipoVehiculo == '2' && $tipoDia == 'Habil'){
                                $valorExtension1 = 85000;
                            }else if($tipoVehiculo == '2' && $tipoDia == 'Inhabil'){
                                $valorExtension1 = 85000;
                            }
                            if($tipoVehiculo == '3' && $tipoDia == 'Habil'){
                                $valorExtension1 = 75000;
                            }else if($tipoVehiculo == '3' && $tipoDia == 'Inhabil'){
                                $valorExtension1 = 75000;
                            }
                        }else if($destinoViaje == 'Vina del mar'){
                            if($tipoVehiculo == '1' && $tipoDia == 'Habil'){
                                $valorExtension1 = 75000;
                            }else if($tipoVehiculo == '1' && $tipoDia == 'Inhabil'){
                                $valorExtension1 = 85000;
                            }
                            if($tipoVehiculo == '2' && $tipoDia == 'Habil'){
                                $valorExtension1 = 95000;
                            }else if($tipoVehiculo == '2' && $tipoDia == 'Inhabil'){
                                $valorExtension1 = 95000;
                            }
                            if($tipoVehiculo == '3' && $tipoDia == 'Habil'){
                                $valorExtension1 = 80000;
                            }else if($tipoVehiculo == '3' && $tipoDia == 'Inhabil'){
                                $valorExtension1 = 80000;
                            }
                        }else if($destinoViaje == 'Valparaiso'){
                            if($tipoVehiculo == '1' && $tipoDia == 'Habil'){
                                $valorExtension1 = 90000;
                            }else if($tipoVehiculo == '1' && $tipoDia == 'Inhabil'){
                                $valorExtension1 = 90000;
                            }
                            if($tipoVehiculo == '2' && $tipoDia == 'Habil'){
                                $valorExtension1 = 95000;
                            }else if($tipoVehiculo == '2' && $tipoDia == 'Inhabil'){
                                $valorExtension1 = 95000;
                            }
                            if($tipoVehiculo == '3' && $tipoDia == 'Habil'){
                                $valorExtension1 = 80000;
                            }else if($tipoVehiculo == 3 && $tipoDia == 'Inhabil'){
                                $valorExtension1 = 80000;
                            }
                        }else if($destinoViaje == 'Provincia Marga Marga'){
                            if($tipoVehiculo == '1' && $tipoDia == 'Habil'){
                                $valorExtension1 = 75000;
                            }else if($tipoVehiculo == '1' && $tipoDia == 'Inhabil'){
                                $valorExtension1 = 85000;
                            }
                            if($tipoVehiculo == '2' && $tipoDia == 'Habil'){
                                $valorExtension1 = 85000;
                            }else if($tipoVehiculo == '2' && $tipoDia == 'Inhabil'){
                                $valorExtension1 = 85000;
                            }
                            if($tipoVehiculo == '3' && $tipoDia == 'Habil'){
                                $valorExtension1 = 240000;
                            }else if($tipoVehiculo == '3' && $tipoDia == 'Inhabil'){
                                $valorExtension1 = 240000;
                            }
                        }else if($destinoViaje == 'Provincia Petorca'){
                            if($tipoVehiculo == '1' && $tipoDia == 'Habil'){
                                $valorExtension1 = 115000;
                            }else if($tipoVehiculo == '1' && $tipoDia == 'Inhabil'){
                                $valorExtension1 = 130000;
                            }
                            if($tipoVehiculo == '2' && $tipoDia == 'Habil'){
                                $valorExtension1 = 150000;
                            }else if($tipoVehiculo == '2' && $tipoDia == 'Inhabil'){
                                $valorExtension1 = 150000;
                            }
                            if($tipoVehiculo == '3' && $tipoDia = 'Habil'){
                                $valorExtension1 = 360000;
                            }else if($tipoVehiculo == '3' && $tipoDia = 'Inhabil'){
                                $valorExtension1 = 360000;
                            }
                        }else if($destinoViaje == 'San Felipe'){
                            if($tipoVehiculo == '1' && $tipoDia == 'Habil'){
                                $valorExtension1 = 150000;
                            }else if($tipoVehiculo == '1' && $tipoDia == 'Inhabil'){
                                $valorExtension1 = 180000;
                            }
                            if($tipoVehiculo == '2' && $tipoDia == 'Habil'){
                                $valorExtension1 = 180000;
                            }else if($tipoVehiculo == '2' && $tipoDia == 'Inhabil'){
                                $valorExtension1 = 190000;
                            }
                            if($tipoVehiculo == '3' && $tipoDia == 'Habil'){
                                $valorExtension1 = 0;
                            }else if($tipoVehiculo == '3' && $tipoDia == 'Inhabil'){
                                $valorExtension1 = 0;
                            }

                        }
                    }else{
                        $valorExtension1 = 0;
                    }
                }else{
                    $valorExtension1 = 0;
                }
            }else{
                $valorExtension1 = 0;
            }

            if ($destinoViaje == 'Comuna de Quillota') {
                //TIPO VEHICULO SEDA/SUB O CAMIONETA
                if ($tipoJornada == 'Medio Dia'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Habil') {
                    $valorViaje = 65000;
                } else if ($tipoJornada  ==  'Dia Completo'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Habil') {
                    $valorViaje = 80000;
                } else if ($tipoJornada  ==  'Medio Dia'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 80000;
                } else if ($tipoJornada  == 'Dia Completo'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 90000;
                }

                //VAN
                if ($tipoJornada == 'Medio Dia'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Habil') {
                    $valorViaje = 85000;
                } else if ($tipoJornada  ==  'Dia Completo' && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Habil') {
                    $valorViaje = 95000;
                } else if ($tipoJornada  ==  'Medio Dia'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 85000;
                } else if ($tipoJornada  == 'Dia Completo' && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 95000;
                }

                //3/4 PICK UP O CAMIONETA DE CARGA
                if ($tipoJornada == 'Medio Dia' && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Habil') {
                    $valorViaje = 75000;
                } else if ($tipoJornada  ==  'Dia Completo'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Habil') {
                    $valorViaje = 80000;
                } else if ($tipoJornada  ==  'Medio Dia' && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 75000;
                } else if ($tipoJornada  == 'Dia Completo'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 90000;
                }
            } else if ($destinoViaje == 'Provincia de Quillota') {

                //TIPO VEHICULO SEDA/SUB O CAMIONETA
                if ($tipoJornada == 'Medio Dia'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Habil') {
                    $valorViaje = 65000;
                } else if ($tipoJornada  ==  'Dia Completo' && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Habil') {
                    $valorViaje = 80000;
                } else if ($tipoJornada  ==  'Medio Dia' && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 80000;
                } else if ($tipoJornada  == 'Dia Completo'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 90000;
                }

                //VAN
                if ($tipoJornada == 'Medio Dia'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Habil') {
                    $valorViaje = 85000;
                } else if ($tipoJornada  ==  'Dia Completo'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Habil') {
                    $valorViaje = 95000;
                } else if ($tipoJornada  ==  'Medio Dia' && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 85000;
                } else if ($tipoJornada  == 'Dia Completo' && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 95000;
                }

                //3/4 PICK UP O CAMIONETA DE CARGA
                if ($tipoJornada == 'Medio Dia'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Habil') {
                    $valorViaje = 75000;
                } else if ($tipoJornada  ==  'Dia Completo'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Habil') {
                    $valorViaje = 80000;
                } else if ($tipoJornada  ==  'Medio Dia'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 75000;
                } else if ($tipoJornada  == 'Dia Completo'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 90000;
                }
            } else if ($destinoViaje == 'Vina del mar') {

                //TIPO VEHICULO SEDA/SUB O CAMIONETA
                if ($tipoJornada == 'Medio Dia'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Habil') {
                    $valorViaje = 75000;
                } else if ($tipoJornada  ==  'Dia Completo'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Habil') {
                    $valorViaje = 85000;
                } else if ($tipoJornada  ==  'Medio Dia' && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 85000;
                } else if ($tipoJornada  == 'Dia Completo'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 95000;
                }

                //VAN
                if ($tipoJornada == 'Medio Dia'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Habil') {
                    $valorViaje = 95000;
                } else if ($tipoJornada  ==  'Dia Completo'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Habil') {
                    $valorViaje = 110000;
                } else if ($tipoJornada  ==  'Medio Dia'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 95000;
                } else if ($tipoJornada  == 'Dia Completo'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 110000;
                }

                //3/4 PICK UP O CAMIONETA DE CARGA
                if ($tipoJornada == 'Medio Dia'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Habil') {
                    $valorViaje = 80000;
                } else if ($tipoJornada  ==  'Dia Completo'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Habil') {
                    $valorViaje = 100000;
                } else if ($tipoJornada  ==  'Medio Dia'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 80000;
                } else if ($tipoJornada  == 'Dia Completo'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 100000;
                }
            } else if ($destinoViaje == 'Valparaiso') {

                //TIPO VEHICULO SEDA/SUB O CAMIONETA
                if ($tipoJornada == 'Medio Dia'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Habil') {
                    $valorViaje = 90000;
                } else if ($tipoJornada  ==  'Dia Completo'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Habil') {
                    $valorViaje = 95000;
                } else if ($tipoJornada  ==  'Medio Dia'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 90000;
                } else if ($tipoJornada  == 'Dia Completo'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 100000;
                }

                //VAN
                if ($tipoJornada == 'Medio Dia'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Habil') {
                    $valorViaje = 95000;
                } else if ($tipoJornada  ==  'Dia Completo'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Habil') {
                    $valorViaje = 110000;
                } else if ($tipoJornada  ==  'Medio Dia'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 95000;
                } else if ($tipoJornada  == 'Dia Completo'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 110000;
                }

                //3/4 PICK UP O CAMIONETA DE CARGA
                if ($tipoJornada == 'Medio Dia'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Habil') {
                    $valorViaje = 80000;
                } else if ($tipoJornada  ==  'Dia Completo'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Habil') {
                    $valorViaje = 100000;
                } else if ($tipoJornada  ==  'Medio Dia'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 80000;
                } else if ($tipoJornada  == 'Dia Completo'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 100000;
                }
            } else if ($destinoViaje == 'Region Metropolitana') {
                //TIPO VEHICULO SEDA/SUB O CAMIONETA
                if ($tipoVehiculo  ==  '1') {
                    $valorViaje = 160000;
                } else if ($tipoVehiculo  ==  '2') {
                    $valorViaje = 200000;
                } else if ($tipoVehiculo  ==  '3') {
                    $valorViaje = 135000;
                }
            } else if ($destinoViaje == 'Provincia Marga Marga') {

                //TIPO VEHICULO SEDA/SUB O CAMIONETA
                if ($tipoJornada == 'Medio Dia'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Habil') {
                    $valorViaje = 75000;
                } else if ($tipoJornada  ==  'Dia Completo'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Habil') {
                    $valorViaje = 85000;
                } else if ($tipoJornada  ==  'Medio Dia'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 85000;
                } else if ($tipoJornada  == 'Dia Completo'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 95000;
                }

                //VAN
                if ($tipoJornada == 'Medio Dia'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Habil') {
                    $valorViaje = 85000;
                } else if ($tipoJornada  ==  'Dia Completo'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Habil') {
                    $valorViaje = 95000;
                } else if ($tipoJornada  ==  'Medio Dia'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 85000;
                } else if ($tipoJornada  == 'Dia Completo'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 100000;
                }

                //3/4 PICK UP O CAMIONETA DE CARGA
                if ($tipoJornada == 'Medio Dia'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Habil') {
                    $valorViaje = 240000;
                } else if ($tipoJornada  ==  'Dia Completo'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Habil') {
                    $valorViaje = 240000;
                } else if ($tipoJornada  ==  'Medio Dia'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 240000;
                } else if ($tipoJornada  == 'Dia Completo'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 240000;
                }
            } else if ($destinoViaje == 'Provincia Petorca') {

                //TIPO VEHICULO SEDA/SUB O CAMIONETA
                if ($tipoJornada == 'Medio Dia'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Habil') {
                    $valorViaje = 115000;
                } else if ($tipoJornada  ==  'Dia Completo'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Habil') {
                    $valorViaje = 130000;
                } else if ($tipoJornada  ==  'Medio Dia'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 130000;
                } else if ($tipoJornada  == 'Dia Completo'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 150000;
                }

                //VAN
                if ($tipoJornada == 'Medio Dia'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Habil') {
                    $valorViaje = 150000;
                } else if ($tipoJornada  ==  'Dia Completo'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Habil') {
                    $valorViaje = 150000;
                } else if ($tipoJornada  ==  'Medio Dia'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 150000;
                } else if ($tipoJornada  == 'Dia Completo'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 180000;
                }

                //3/4 PICK UP O CAMIONETA DE CARGA
                if ($tipoJornada == 'Medio Dia'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Habil') {
                    $valorViaje = 360000;
                } else if ($tipoJornada  ==  'Dia Completo'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Habil') {
                    $valorViaje = 360000;
                } else if ($tipoJornada  ==  'Medio Dia'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 360000;
                } else if ($tipoJornada  == 'Dia Completo'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 360000;
                }
            }else if($destinoViaje == 'San Felipe'){
                //TIPO VEHICULO SEDA/SUB O CAMIONETA
                if ($tipoJornada == 'Medio Dia'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Habil') {
                    $valorViaje = 150000;
                } else if ($tipoJornada  ==  'Dia Completo'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Habil') {
                    $valorViaje = 150000;
                } else if ($tipoJornada  ==  'Medio Dia'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 180000;
                } else if ($tipoJornada  == 'Dia Completo'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 180000;
                }

                //VAN
                if ($tipoJornada == 'Medio Dia'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Habil') {
                    $valorViaje = 180000;
                } else if ($tipoJornada  ==  'Dia Completo'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Habil') {
                    $valorViaje = 180000;
                } else if ($tipoJornada  ==  'Medio Dia'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 190000;
                } else if ($tipoJornada  == 'Dia Completo'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 190000;
                }

                //3/4 PICK UP O CAMIONETA DE CARGA
                if ($tipoJornada == 'Medio Dia'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Habil') {
                    $valorViaje = 0;
                } else if ($tipoJornada  ==  'Dia Completo'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Habil') {
                    $valorViaje = 0;
                } else if ($tipoJornada  ==  'Medio Dia'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 0;
                } else if ($tipoJornada  == 'Dia Completo'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 0;
                }
            }

            $motivoViaje = mb_convert_case($motivoViaje, MB_CASE_UPPER, 'UTF-8');

            //INSERT EN LA TABLA viajes_movilizacion
            if (isset($valorViaje)) {
                $sqlInsert = "INSERT INTO viajes_movilizacion(idViaje,valorViaje,numeroViaje,fechaViaje,fechaRegistro,tipoVehiculo,patenteVehiculo,conductorViaje,pasajeroViaje,partidaViaje,destinoViaje, tipoJornada, motivoViaje, horaInicia, horaTermino, nomResponsable, listadoUnidad, unidadViaje, rutConductor, valorExtension,horaInicioExtencion,horaTerminoExtencion,chkExtension,respaldoMotivo) 
                VALUES (NULL, $valorViaje , '$numeroViaje', '$fechaViaje','$fechaRegistroVale','$tipoVehiculo' ,'$patenteVehiculo', '$conductorViaje', '$pasajeroViaje', '$partidaViaje', '$destinoViaje', '$numTipoJornada', '$motivoViaje1' , '$horaInicio' , '$horaTermino', '$responsable', '$listadoUnidad', '$unidadMovilizacion','$nit ','$valorExtension1','$horaInicioExtencion','$horaTerminoExtencion',$chkExtension1,'$respaldoViaje1')";
                $res = mysqli_query($conexion, $sqlInsert);

                $idViaje = mysqli_insert_id($conexion);

                if (isset($_FILES['archivosViajes'])) {

                    $directorio = "../sdo-archivos-viajesMovilizacion/registro-" . $idViaje . "/";
                    if (!file_exists($directorio)) {
                        mkdir($directorio, 0777, true);
                    }
                }

                if (isset($_FILES['archivosViajes'])) {

                    $tot = count($_FILES["archivosViajes"]["name"]);

                    for ($i = 0; $i < $tot; $i++) {
                        $tmp_name = $_FILES["archivosViajes"]["tmp_name"];
                        $name = $_FILES["archivosViajes"]["name"];

                        if ($tmp_name == '' || $tmp_name == ' ' || empty($tmp_name)) {
                            echo 'sin nombre';
                        } else {

                            $directorio = "../sdo-archivos-viajesMovilizacion/registro-" . $idViaje . "/";

                            if (!file_exists($directorio)) {
                                mkdir($directorio, 0777, true);
                            }

                            $dir = opendir($directorio);

                            $ruta = $directorio . $name;

                            $separarNombre = explode(".", $name);

                            $extImagen = end($separarNombre);

                            $extImagen = mb_strtolower($extImagen);

                            if ($extImagen == "docx" || $extImagen == "xlsx" || $extImagen == "pdf" || $extImagen == "jpg" || $extImagen == "png" || $extImagen == "jpeg" || $extImagen == "bmp" || $extImagen == "tif" || $extImagen == "webp" || $extImagen == "svg" || $extImagen == "jfif") {

                                if (move_uploaded_file($tmp_name, $ruta)) {

                                    $query = "INSERT INTO archivos_viajes_movilizacion( nomArchivoVM,rutaArchivoVM,extArchivo, idViaje)";
                                    $query .=  "VALUES('$name', '$ruta','$extImagen', $idViaje )";


                                    $resArchivo = mysqli_query($conexion, $query);

                                    if ($resArchivo) {

                                        echo "GOOD SUBIDA";
                                    }
                                } else {
                                    echo 'No subi';
                                }
                            }
                        }
                    }
                }
            } else {
                header('Location: ../' . $rutaDireccion . '/listado-viajes.php?noValorViaje=true');
            }

            if ($res) {
                header('Location: ../' . $rutaDireccion . '/listado-viajes.php?exitoCrearViaje=true');
            } else {
                header('Location: ../' . $rutaDireccion . '/listado-viajes.php?errorCrearViaje=true');
            }

            echo '"' . $motivoViaje . '"' . '<br><br>';
            echo '"' . $otroMotivo . '"' . '<br><br>';
            echo '"' . $totalMotivo . '"' . '<br><br>';
        } else {
            header('Location: ../' . $rutaDireccion . '/listado-viajes.php?existenVacios=true');
        }
    } else {
        header('Location: ../' . $rutaDireccion . '/listado-viajes.php?noNumero=true');
    }
}
