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
        $fechaRegistroVale = $_POST['fechaRegistroVale'];
        $listadoUnidad = $_POST['listadoUnidad'];
        $unidadMovilizacion = $_POST['unidadMovilizacion'];
        $motivoViaje1 = $_POST['motivoViaje1'];
        $nit = $_POST['rutConductor'];
        $horaInicioExtencion = $_POST['horaInicioExtencion'];
        $horaTerminoExtencion = $_POST['horaTerminoExtencion'];
        $chkExtension = $_POST['chkExtension'];
        $respaldoViaje1 = $_POST['respaldoViaje1'];
        $tipoMovil = $_POST['tipoMovil'];
        $anoMovil = $_POST['anoMovil'];


        if ($fechaViaje <= '1969-01-01' || $fechaRegistroVale <= '1969-01-01') {

            header('Location: ../' . $rutaDireccion . '/listado-viajes-vamoos-spa.php?errorCrearViajeFecha=true');
            exit;
        }


        if ($horaInicioExtencion == 0 || empty($horaInicioExtencion)) {
            $horaInicioExtencion = '00:00';
        }
        if ($horaTerminoExtencion == 0 || empty($horaTerminoExtencion)) {
            $horaTerminoExtencion = '00:00';
        }

        if (!empty($chkExtension)) {
            $chkExtension1 = 1;
        } else {
            $chkExtension1 = 0;

        }

        if (!empty($motivoViaje)) {
        } else {
            $motivoViaje = '';
        }

        $responsable = $_POST['responsable'];

        if (!empty($numeroViaje) || !empty($fechaViaje) || !empty($patenteVehiculo) || !empty($conductorViaje) || !empty($destinoViaje) || !empty($horaInicio) || !empty($horaTermino)) {

            if ($conductorViaje == 'Seleccione...' || $patenteVehiculo == 'Seleccione...' || $motivoViaje == 'Seleccione...') {
                header('Location: ../' . $rutaDireccion . '/listado-viajes-vamoos-spa.php?errorCrearViaje=true');
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
                        header('Location: ../' . $rutaDireccion . '/listado-viajes-vamoos-spa.php?errorCrearViaje=true');
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
                                header('Location: ../' . $rutaDireccion . '/listado-viajes-vamoos-spa.php?errorCrearViaje=true');
                            }
                        }
                    }
                } else {
                    header('Location: ../' . $rutaDireccion . '/listado-viajes-vamoos-spa.php?errorCrearViaje=true');
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
                        header('Location: ../' . $rutaDireccion . '/listado-viajes-vamoos-spa.php?errorCrearViaje=true');
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
                                header('Location: ../' . $rutaDireccion . '/listado-viajes-vamoos-spa.php?errorCrearViaje=true');
                            }
                        }
                    }
                } else {
                    header('Location: ../' . $rutaDireccion . '/listado-viajes-vamoos-spa.php?errorCrearViaje=true');
                    exit;
                    echo 'no hay otro<br><br>';
                }
            }

            if ($conductorViaje == 'otro' || $conductorViaje == 'Otro') {
                if (isset($_POST['otroConductor'], $_POST['otroRut'])) {
                    $conductorViaje = '';
                    $otroConductor = trim($_POST['otroConductor']);
                    $otroRut = trim($_POST['otroRut']);

                    $otroConductor = mb_convert_case($otroConductor, MB_CASE_UPPER, 'UTF-8');
                    $otroRut = mb_convert_case($otroRut, MB_CASE_UPPER, 'UTF-8');

                    $otroConductor2 = $otroConductor;
                    $otroRut2 = $otroRut;

                    $totalConductor = buscarConductor2($otroConductor2);
                    $totalRut = buscarRUTConductor2($otroRut2);

                    echo $totalConductor;
                    echo '<br>';
                    echo $totalRut;


                    if (empty($otroConductor2) || empty($otroRut2)) {
                        header('Location: ../' . $rutaDireccion . '/listado-viajes-vamoos-spa.php?errorCrearViaje=true');
                        exit;
                    } else {
                        if ($totalConductor > 0 || $totalRut > 0) {
                            header('Location: ../' . $rutaDireccion . '/listado-viajes-vamoos-spa.php?errorCrearViaje=true');
                        } else {
                            $sqlInsert = "INSERT INTO conductores_movilizacion(idConductor ,nomConductor,rutConductor,antesConductor) 
                            VALUES (NULL,'$otroConductor2','$otroRut2',2)";

                            $res = mysqli_query($conexion, $sqlInsert);

                            if ($res) {
                                $idConductor = mysqli_insert_id($conexion);
                                $conductorViaje = $idConductor;
                                if ($conductorViaje == '' || empty($conductorViaje)) {
                                    header('Location: ../' . $rutaDireccion . '/listado-viajes-vamoos-spa.php?errorCrearViaje=true');
                                    exit;
                                }
                                //$conductorViaje = $otroConductor2;
                                $nit = $otroRut2;
                            } else {
                                header('Location: ../' . $rutaDireccion . '/listado-viajes-vamoos-spa.php?errorCrearViaje=true');
                                exit;
                            }
                        }
                    }
                } else {
                    header('Location: ../' . $rutaDireccion . '/listado-viajes-vamoos-spa.php?errorCrearViaje=true');
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
                        header('Location: ../' . $rutaDireccion . '/listado-viajes-vamoos-spa.php?errorCrearViaje=true');
                        exit;
                        // echo 'hay vacio<br><br>';

                    } else {

                        if ($totalUnidad > 0) {
                            echo 'EXISTE<br><br>';
                            echo $totalUnidad . '<br><br>';

                            $unidadMovilizacion = $otraUnidad2;

                        } else {
                            $sqlInsert = "INSERT INTO unidad_movilizacion_nuevo(idUnidad ,nomUnidad) VALUES (NULL,'$otraUnidad2')";
                            $res = mysqli_query($conexion, $sqlInsert);


                            if ($res) {
                                $unidadMovilizacion = $otraUnidad2;
                            } else {
                                header('Location: ../' . $rutaDireccion . '/listado-viajes-vamoos-spa.php?errorCrearViaje=true');
                            }
                        }
                    }
                } else {
                    header('Location: ../' . $rutaDireccion . '/listado-viajes-vamoos-spa.php?errorCrearViaje=true');
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
                        header('Location: ../' . $rutaDireccion . '/listado-viajes-vamoos-spa.php?errorCrearViaje=true');
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
                                header('Location: ../' . $rutaDireccion . '/listado-viajes-vamoos-spa.php?errorCrearViaje=true');
                            }
                        }
                    }
                } else {
                    header('Location: ../' . $rutaDireccion . '/listado-viajes-vamoos-spa.php?errorCrearViaje=true');
                    exit;
                    echo 'no hay otro<br><br>';
                }
            }

            /* ===== REGLAS DE NEGOCIOS =====*/
            $inicio = new DateTime($horaInicio);
            $termino = new DateTime($horaTermino);

            $diferencia = $inicio->diff($termino);

            $diferenciaHora = $diferencia->format('%H');
            $diferenciaMinutos = $diferencia->format('%i');

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
            $diaSemana = get_nombre_dia($fechaViaje);

            if ($diaSemana == 'Domingo') {
                $tipoDia = 'Inhabil';
            } else {
                $tipoDia = 'Habil';
            }

            if ($unidadMovilizacion == 'HOSPITALIZACION DOMICILIARIA') {
                if (!empty($chkExtension)) {
                    if (!empty($horaInicioExtencion) && !empty($horaTerminoExtencion)) {
                        $valoresViajeExt = [
                            'Provincia de Quillota' => [
                                '1' => ['Dia Completo' => ['Habil' => 50000, 'Inhabil' => 50000]],
                                '2' => ['Dia Completo' => ['Habil' => 70000, 'Inhabil' => 70000]],
                                '3' => ['Dia Completo' => ['Habil' => 50000, 'Inhabil' => 50000]],
                            ],
                            'Vina del mar' => [
                                '1' => ['Medio Dia' => ['Habil' => 75000, 'Inhabil' => 85000], 'Dia Completo' => ['Habil' => 75000, 'Inhabil' => 85000]],
                                '2' => ['Medio Dia' => ['Habil' => 95000, 'Inhabil' => 95000], 'Dia Completo' => ['Habil' => 95000, 'Inhabil' => 95000]],
                                '3' => ['Medio Dia' => ['Habil' => 75000, 'Inhabil' => 85000], 'Dia Completo' => ['Habil' => 75000, 'Inhabil' => 85000]],

                            ],
                            'Provincia de Valparaiso' => [
                                '1' => ['Dia Completo' => ['Habil' => 70000, 'Inhabil' => 70000]],
                                '2' => ['Dia Completo' => ['Habil' => 94500, 'Inhabil' => 94500]],
                                '3' => ['Dia Completo' => ['Habil' => 70000, 'Inhabil' => 70000]],
                            ],
                            'Provincia Marga Marga' => [
                                '1' => ['Dia Completo' => ['Habil' => 65000, 'Inhabil' => 65000]],
                                '2' => ['Dia Completo' => ['Habil' => 87750, 'Inhabil' => 87750]],
                                '3' => ['Dia Completo' => ['Habil' => 65000, 'Inhabil' => 65000]],
                            ],
                            'Provincia Petorca' => [
                                '1' => ['Dia Completo' => ['Habil' => 100000, 'Inhabil' => 100000]],
                                '2' => ['Dia Completo' => ['Habil' => 135000, 'Inhabil' => 140000]],
                                '3' => ['Dia Completo' => ['Habil' => 100000, 'Inhabil' => 100000]],
                            ],
                            'San Felipe' => [
                                '1' => ['Medio Dia' => ['Habil' => 150000, 'Inhabil' => 180000], 'Dia Completo' => ['Habil' => 150000, 'Inhabil' => 180000]],
                                '2' => ['Medio Dia' => ['Habil' => 180000, 'Inhabil' => 180000], 'Dia Completo' => ['Habil' => 190000, 'Inhabil' => 190000]],
                                '3' => ['Medio Dia' => ['Habil' => 150000, 'Inhabil' => 180000], 'Dia Completo' => ['Habil' => 150000, 'Inhabil' => 180000]],
                            ],
                            'Region Metropolitana' => [
                                '1' => ['Dia Completo' => ['Habil' => 130000, 'Inhabil' => 130000]],
                                '2' => ['Dia Completo' => ['Habil' => 169000, 'Inhabil' => 169000]],
                                '3' => ['Dia Completo' => ['Habil' => 130000, 'Inhabil' => 130000]],
                            ],
                            'Provincia Aconcagua' => [
                                '1' => ['Dia Completo' => ['Habil' => 90000, 'Inhabil' => 85000]],
                                '2' => ['Dia Completo' => ['Habil' => 126000, 'Inhabil' => 114750]],
                                '3' => ['Dia Completo' => ['Habil' => 90000, 'Inhabil' => 85000]],
                            ]
                        ];


                        if (isset($valoresViajeExt[$destinoViaje][$tipoVehiculo][$tipoJornada][$tipoDia])) {
                            $valorExtension1 = $valoresViajeExt[$destinoViaje][$tipoVehiculo][$tipoJornada][$tipoDia];
                        } else {
                            $valorExtension1 = 0; // Valor por defecto si no se encuentra la combinación
                        }


                    } else {
                        $valorExtension1 = 0;
                    }
                } else {
                    $valorExtension1 = 0;
                }
            } else {
                $valorExtension1 = 0;
            }

            $valoresViaje = [
                'Provincia de Quillota' => [
                    '1' => ['Medio Dia' => ['Habil' => 50000, 'Inhabil' => 50000], 'Dia Completo' => ['Habil' => 80000, 'Inhabil' => 80000]],
                    '2' => ['Medio Dia' => ['Habil' => 70000, 'Inhabil' => 70000], 'Dia Completo' => ['Habil' => 104000, 'Inhabil' => 104000]],
                    '3' => ['Medio Dia' => ['Habil' => 50000, 'Inhabil' => 50000], 'Dia Completo' => ['Habil' => 80000, 'Inhabil' => 80000]],
                ],
                'Provincia de Valparaiso' => [
                    '1' => ['Medio Dia' => ['Habil' => 70000, 'Inhabil' => 70000], 'Dia Completo' => ['Habil' => 100000, 'Inhabil' => 100000]],
                    '2' => ['Medio Dia' => ['Habil' => 94500, 'Inhabil' => 94500], 'Dia Completo' => ['Habil' => 130000, 'Inhabil' => 130000]],
                    '3' => ['Medio Dia' => ['Habil' => 70000, 'Inhabil' => 70000], 'Dia Completo' => ['Habil' => 100000, 'Inhabil' => 100000]],
                ],
                'Valparaiso' => [
                    '1' => ['Medio Dia' => ['Habil' => 90000, 'Inhabil' => 90000], 'Dia Completo' => ['Habil' => 95000, 'Inhabil' => 100000]],
                    '2' => ['Medio Dia' => ['Habil' => 95000, 'Inhabil' => 95000], 'Dia Completo' => ['Habil' => 110000, 'Inhabil' => 110000]],
                    '3' => ['Medio Dia' => ['Habil' => 90000, 'Inhabil' => 90000], 'Dia Completo' => ['Habil' => 95000, 'Inhabil' => 100000]],
                ],
                'Region Metropolitana' => [
                    '1' => ['Medio Dia' => ['Habil' => 130000, 'Inhabil' => 130000], 'Dia Completo' => ['Habil' => 130000, 'Inhabil' => 130000]],
                    '2' => ['Medio Dia' => ['Habil' => 95000, 'Inhabil' => 95000], 'Dia Completo' => ['Habil' => 110000, 'Inhabil' => 110000]],
                    '3' => ['Medio Dia' => ['Habil' => 90000, 'Inhabil' => 90000], 'Dia Completo' => ['Habil' => 95000, 'Inhabil' => 100000]],
                ],
                'Provincia Marga Marga' => [
                    '1' => ['Medio Dia' => ['Habil' => 65000, 'Inhabil' => 65000], 'Dia Completo' => ['Habil' => 95000, 'Inhabil' => 95000]],
                    '2' => ['Medio Dia' => ['Habil' => 87750, 'Inhabil' => 87750], 'Dia Completo' => ['Habil' => 123500, 'Inhabil' => 123500]],
                    '3' => ['Medio Dia' => ['Habil' => 65000, 'Inhabil' => 65000], 'Dia Completo' => ['Habil' => 95000, 'Inhabil' => 95000]],
                ],
                'Provincia Petorca' => [
                    '1' => ['Medio Dia' => ['Habil' => 100000, 'Inhabil' => 100000], 'Dia Completo' => ['Habil' => 130000, 'Inhabil' => 130000]],
                    '2' => ['Medio Dia' => ['Habil' => 135000, 'Inhabil' => 140000], 'Dia Completo' => ['Habil' => 169000, 'Inhabil' => 169000]],
                    '3' => ['Medio Dia' => ['Habil' => 100000, 'Inhabil' => 100000], 'Dia Completo' => ['Habil' => 130000, 'Inhabil' => 130000]],
                ],
                'San Felipe' => [
                    '1' => ['Medio Dia' => ['Habil' => 150000, 'Inhabil' => 180000], 'Dia Completo' => ['Habil' => 150000, 'Inhabil' => 180000]],
                    '2' => ['Medio Dia' => ['Habil' => 180000, 'Inhabil' => 180000], 'Dia Completo' => ['Habil' => 190000, 'Inhabil' => 190000]],
                    '3' => ['Medio Dia' => ['Habil' => 150000, 'Inhabil' => 180000], 'Dia Completo' => ['Habil' => 150000, 'Inhabil' => 180000]],
                ],
                'Provincia Aconcagua' => [
                    '1' => ['Medio Dia' => ['Habil' => 90000, 'Inhabil' => 85000], 'Dia Completo' => ['Habil' => 120000, 'Inhabil' => 115000]],
                    '2' => ['Medio Dia' => ['Habil' => 126000, 'Inhabil' => 114750], 'Dia Completo' => ['Habil' => 156000, 'Inhabil' => 149500]],
                    '3' => ['Medio Dia' => ['Habil' => 90000, 'Inhabil' => 85000], 'Dia Completo' => ['Habil' => 120000, 'Inhabil' => 115000]],
                ]

            ];


            if (isset($valoresViaje[$destinoViaje][$tipoVehiculo][$tipoJornada][$tipoDia])) {
                $valorViaje = $valoresViaje[$destinoViaje][$tipoVehiculo][$tipoJornada][$tipoDia];
                //$suma = array_sum($valoresViaje[$destinoViaje][$tipoVehiculo][$tipoJornada]);

            }

            $motivoViaje = mb_convert_case($motivoViaje, MB_CASE_UPPER, 'UTF-8');

            ECHO $valorExtension1.'<br>';
            ECHO $suma;
            $sqlInsert = "INSERT INTO viajes_movilizacion_vamoos_spa(idViaje,valorViaje,numeroViaje,fechaViaje,fechaRegistro,tipoVehiculo,patenteVehiculo,conductorViaje,pasajeroViaje,partidaViaje,destinoViaje, tipoJornada, motivoViaje, horaInicia, horaTermino, nomResponsable, listadoUnidad, unidadViaje, rutConductor, valorExtension,horaInicioExtencion,horaTerminoExtencion,chkExtension,respaldoMotivo,tipoMovil,anoVehiculo) 
                VALUES (NULL, $valorViaje , '$numeroViaje', '$fechaViaje','$fechaRegistroVale','$tipoVehiculo' ,'$patenteVehiculo', '$conductorViaje', '$pasajeroViaje', '$partidaViaje', '$destinoViaje', '$numTipoJornada', '$motivoViaje1' , '$horaInicio' , '$horaTermino', '$responsable', '$listadoUnidad', '$unidadMovilizacion','$nit ','$valorExtension1','$horaInicioExtencion','$horaTerminoExtencion',$chkExtension1,'$respaldoViaje1','$tipoMovil',$anoMovil)";
            ECHO $sqlInsert;
        }
    }
}