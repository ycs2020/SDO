<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

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
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['numeroViaje'])) {

        $idViaje = $_POST['idViaje'];
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
        $motivoViaje = $_POST['motivosCambio'];
        $listadoUnidad = $_POST['listadoUnidad'];
        $unidadMovilizacion = $_POST['unidadMovilizacion'];
        $horaInicioExtencion = $_POST['horaInicioExtencion'];
        $horaTerminoExtencion = $_POST['horaTerminoExtencion'];
        $nit = $_POST['rutConductor'];
        $chkExtension = $_POST['chkExtension'];
        $respaldoMotivo = $_POST['respaldoCambio'];
        $fechaIngresoPlata = $_POST['fechaIngresoPlata'];

        if($chkExtension !== 'extensionHora'){
            $horaInicioExtencion = '00:00';
            $horaTerminoExtencion = '00:00';
        }
        if($horaInicioExtencion == 0 || empty($horaInicioExtencion) || $horaInicioExtencion == '00:00'){
            $horaInicioExtencion = '00:00';
        }
        if($horaTerminoExtencion == 0 || empty($horaTerminoExtencion) || $horaTerminoExtencion == '00:00'){
            $horaTerminoExtencion = '00:00';
        }



        if (!empty($numeroViaje)  || !empty($fechaViaje) || !empty($patenteVehiculo)  || !empty($conductorViaje)  || !empty($destinoViaje)  || !empty($horaInicio)  || !empty($horaTermino)) {


            if ($conductorViaje == 'Seleccione...' || $patenteVehiculo == 'Seleccione...' || $unidadMovilizacion == 'Seleccione...') {
                header('Location: ../' . $rutaDireccion . '/listado-viajes-vamoos-spa.php?errorModificar1=true');

            }

            $motivoViaje = mb_convert_case($motivoViaje, MB_CASE_LOWER, 'UTF-8');
            $listadoUnidad = mb_convert_case($listadoUnidad,MB_CASE_UPPER,'UTF-8');

            if ($motivoViaje == 'otro') {
                if (isset($_POST['otroMotivo'])) {
                    $otroMotivo = trim($_POST['otroMotivo']);

                    $otroMotivo = mb_convert_case($otroMotivo, MB_CASE_UPPER, 'UTF-8');

                    $otroMotivo2 = $otroMotivo;

                    $totalMotivo = buscarMotivo($otroMotivo2);
                    if (empty($otroMotivo2)) {
                        header('Location: ../' . $rutaDireccion . '/listado-viajes-vamoos-spa.php?errorModificar2=true');

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
                                header('Location: ../' . $rutaDireccion . '/listado-viajes-vamoos-spa.php?errorModificar3=true');
                            }
                        }
                    }
                } else {
                    header('Location: ../' . $rutaDireccion . '/listado-viajes-vamoos-spa.php?errorModificar4=true');

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
                            echo $totalUnidad  . '<br><br>';

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

            // if ($diferenciaHora >= 4 && $diferenciaMinutos >= 30) {
            //     $tipoJornada = 'Dia Completo';
            //     $numTipoJornada = '1';
            // } else if ($diferenciaHora >= 5 && $diferenciaMinutos >= 1) {
            //     $tipoJornada = 'Dia Completo';
            //     $numTipoJornada = '1';
            // } else {
            //     $tipoJornada = 'Medio Dia';
            //     $numTipoJornada = '1/2';
            // }

            if ($diferenciaHora <= 4 && $diferenciaMinutos <= 30) {
                $tipoJornada = 'Medio Dia';
                $numTipoJornada = '1/2';
            }else if($diferenciaHora <= 3 && $diferenciaMinutos <= 60){
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
                            if($tipoVehiculo == '1' && $tipoDia == 'Habil' && $tipoJornada = 'Dia Completo'){
                                $valorExtension1 = 50000;
                            }else if($tipoVehiculo == '1' && $tipoDia == 'Inhabil' && $tipoJornada = 'Dia Completo'){
                                $valorExtension1 = 50000;
                            }
                            if($tipoVehiculo == '2' && $tipoDia == 'Habil' && $tipoJornada = 'Dia Completo'){
                                $valorExtension1 = 70000;
                            }else if($tipoVehiculo == '2' && $tipoDia == 'Inhabil' && $tipoJornada = 'Dia Completo'){
                                $valorExtension1 = 70000;
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

                        }else if($destinoViaje == 'Provincia de Valparaiso'){
                            if($tipoVehiculo == '1' && $tipoDia == 'Habil' && $tipoJornada = 'Dia Completo'){
                                $valorExtension1 = 70000;
                            }else if($tipoVehiculo == '1' && $tipoDia == 'Inhabil' && $tipoJornada = 'Dia Completo'){
                                $valorExtension1 = 70000;
                            }
                            if($tipoVehiculo == '2' && $tipoDia == 'Habil' && $tipoJornada = 'Dia Completo'){
                                $valorExtension1 = 94500;
                            }else if($tipoVehiculo == '2' && $tipoDia == 'Inhabil' && $tipoJornada = 'Dia Completo'){
                                $valorExtension1 = 94500;
                            }

                        }else if($destinoViaje == 'Provincia Marga Marga'){
                            if($tipoVehiculo == '1' && $tipoDia == 'Habil' && $tipoJornada = 'Dia Completo'){
                                $valorExtension1 = 65000;
                            }else if($tipoVehiculo == '1' && $tipoDia == 'Inhabil' && $tipoJornada = 'Dia Completo'){
                                $valorExtension1 = 65000;
                            }
                            if($tipoVehiculo == '2' && $tipoDia == 'Habil' && $tipoJornada = 'Dia Completo'){
                                $valorExtension1 = 87750;
                            }else if($tipoVehiculo == '2' && $tipoDia == 'Inhabil' && $tipoJornada = 'Dia Completo'){
                                $valorExtension1 = 87750;
                            }

                        }else if($destinoViaje == 'Provincia Petorca'){
                            if($tipoVehiculo == '1' && $tipoDia == 'Habil' && $tipoJornada = 'Dia Completo'){
                                $valorExtension1 = 100000;
                            }else if($tipoVehiculo == '1' && $tipoDia == 'Inhabil' && $tipoJornada = 'Dia Completo'){
                                $valorExtension1 = 100000;
                            }
                            if($tipoVehiculo == '2' && $tipoDia == 'Habil' && $tipoJornada = 'Dia Completo'){
                                $valorExtension1 = 135000;
                            }else if($tipoVehiculo == '2' && $tipoDia == 'Inhabil' && $tipoJornada = 'Dia Completo'){
                                $valorExtension1 = 140000;
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
                        }else if($destinoViaje == 'Region Metropolitana'){
                            if($tipoVehiculo == '1' && $tipoDia == 'Habil' && $tipoJornada = 'Dia Completo'){
                                $valorExtension1 = 130000;
                            }else if($tipoVehiculo == '1' && $tipoDia == 'Inhabil' && $tipoJornada = 'Dia Completo'){
                                $valorExtension1 = 130000;
                            }
                            if($tipoVehiculo == '2' && $tipoDia == 'Habil' && $tipoJornada = 'Dia Completo'){
                                $valorExtension1 = 169000;
                            }else if($tipoVehiculo == '2' && $tipoDia == 'Inhabil' && $tipoJornada = 'Dia Completo'){
                                $valorExtension1 = 169000;
                            }
                        }else if($destinoViaje == 'Provincia Aconcagua'){
                            if($tipoVehiculo == '1' && $tipoDia == 'Habil' && $tipoJornada = 'Dia Completo'){
                                $valorExtension1 = 90000;
                            }else if($tipoVehiculo == '1' && $tipoDia == 'Inhabil' && $tipoJornada = 'Dia Completo'){
                                $valorExtension1 = 85000;
                            }
                            if($tipoVehiculo == '2' && $tipoDia == 'Habil' && $tipoJornada = 'Dia Completo'){
                                $valorExtension1 = 126000;
                            }else if($tipoVehiculo == '2' && $tipoDia == 'Inhabil' && $tipoJornada = 'Dia Completo'){
                                $valorExtension1 = 114750;
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



            if ($destinoViaje == 'Provincia de Quillota') {

                //TIPO VEHICULO SEDA/SUB O CAMIONETA
                if ($tipoJornada == 'Medio Dia'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Habil') {
                    $valorViaje = 50000;
                } else if ($tipoJornada  ==  'Dia Completo' && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Habil') {
                    $valorViaje = 80000;
                } else if ($tipoJornada  ==  'Medio Dia' && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 50000;
                } else if ($tipoJornada  == 'Dia Completo'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 80000;
                }

                //VAN
                if ($tipoJornada == 'Medio Dia'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Habil') {
                    $valorViaje = 70000;
                } else if ($tipoJornada  ==  'Dia Completo'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Habil') {
                    $valorViaje = 104000;
                } else if ($tipoJornada  ==  'Medio Dia' && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 70000;
                } else if ($tipoJornada  == 'Dia Completo' && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 104000;
                }

                //3/4 PICK UP O CAMIONETA DE CARGA
                /*if ($tipoJornada == 'Medio Dia'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Habil') {
                    $valorViaje = 75000;
                } else if ($tipoJornada  ==  'Dia Completo'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Habil') {
                    $valorViaje = 80000;
                } else if ($tipoJornada  ==  'Medio Dia'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 75000;
                } else if ($tipoJornada  == 'Dia Completo'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 90000;
                }*/
            } else if ($destinoViaje == 'Provincia de Valparaiso') {

                //TIPO VEHICULO SEDA/SUB O CAMIONETA
                if ($tipoJornada == 'Medio Dia'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Habil') {
                    $valorViaje = 70000;
                } else if ($tipoJornada  ==  'Dia Completo'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Habil') {
                    $valorViaje = 100000;
                } else if ($tipoJornada  ==  'Medio Dia' && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 70000;
                } else if ($tipoJornada  == 'Dia Completo'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 100000;
                }

                //VAN
                if ($tipoJornada == 'Medio Dia'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Habil') {
                    $valorViaje = 94500;
                } else if ($tipoJornada  ==  'Dia Completo'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Habil') {
                    $valorViaje = 130000;
                } else if ($tipoJornada  ==  'Medio Dia'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 94500;
                } else if ($tipoJornada  == 'Dia Completo'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 130000;
                }

                //3/4 PICK UP O CAMIONETA DE CARGA
                /*if ($tipoJornada == 'Medio Dia'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Habil') {
                    $valorViaje = 80000;
                } else if ($tipoJornada  ==  'Dia Completo'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Habil') {
                    $valorViaje = 100000;
                } else if ($tipoJornada  ==  'Medio Dia'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 80000;
                } else if ($tipoJornada  == 'Dia Completo'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 100000;
                }*/
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
                    $valorViaje = 130000;
                } else if ($tipoVehiculo  ==  '2') {
                    $valorViaje = 169000;
                }

            } else if ($destinoViaje == 'Provincia Marga Marga') {

                //TIPO VEHICULO SEDA/SUB O CAMIONETA
                if ($tipoJornada == 'Medio Dia'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Habil') {
                    $valorViaje = 65000;
                } else if ($tipoJornada  ==  'Dia Completo'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Habil') {
                    $valorViaje = 95000;
                } else if ($tipoJornada  ==  'Medio Dia'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 65000;
                } else if ($tipoJornada  == 'Dia Completo'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 95000;
                }

                //VAN
                if ($tipoJornada == 'Medio Dia'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Habil') {
                    $valorViaje = 87750;
                } else if ($tipoJornada  ==  'Dia Completo'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Habil') {
                    $valorViaje = 123500;
                } else if ($tipoJornada  ==  'Medio Dia'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 87750;
                } else if ($tipoJornada  == 'Dia Completo'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 123500;
                }

                //3/4 PICK UP O CAMIONETA DE CARGA
                /*if ($tipoJornada == 'Medio Dia'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Habil') {
                    $valorViaje = 240000;
                } else if ($tipoJornada  ==  'Dia Completo'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Habil') {
                    $valorViaje = 240000;
                } else if ($tipoJornada  ==  'Medio Dia'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 240000;
                } else if ($tipoJornada  == 'Dia Completo'  && $tipoVehiculo  ==  '3' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 240000;
                }*/
            } else if ($destinoViaje == 'Provincia Petorca') {

                //TIPO VEHICULO SEDA/SUB O CAMIONETA
                if ($tipoJornada == 'Medio Dia'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Habil') {
                    $valorViaje = 100000;
                } else if ($tipoJornada  ==  'Dia Completo'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Habil') {
                    $valorViaje = 130000;
                } else if ($tipoJornada  ==  'Medio Dia'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 100000;
                } else if ($tipoJornada  == 'Dia Completo'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 130000;
                }

                //VAN
                if ($tipoJornada == 'Medio Dia'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Habil') {
                    $valorViaje = 135000;
                } else if ($tipoJornada  ==  'Dia Completo'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Habil') {
                    $valorViaje = 169000;
                } else if ($tipoJornada  ==  'Medio Dia'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 140000;
                } else if ($tipoJornada  == 'Dia Completo'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 169000;
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
            }else if($destinoViaje == 'Provincia Aconcagua'){
                if ($tipoJornada == 'Medio Dia'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Habil') {
                    $valorViaje = 90000;
                } else if ($tipoJornada  ==  'Dia Completo'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Habil') {
                    $valorViaje = 120000;
                } else if ($tipoJornada  ==  'Medio Dia'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 85000;
                } else if ($tipoJornada  == 'Dia Completo'  && $tipoVehiculo  ==  '1' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 115000;
                }
                //VAN
                if ($tipoJornada == 'Medio Dia'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Habil') {
                    $valorViaje = 126000;
                } else if ($tipoJornada  ==  'Dia Completo'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Habil') {
                    $valorViaje = 156000;
                } else if ($tipoJornada  ==  'Medio Dia'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 114750;
                } else if ($tipoJornada  == 'Dia Completo'  && $tipoVehiculo  ==  '2' && $tipoDia  ==  'Inhabil') {
                    $valorViaje = 149500;
                }
            }


            //INSERT EN LA TABLA viajes_movilizacion
            if (isset($valorViaje)) {
                $sqlUpdate = "UPDATE viajes_movilizacion_vamoos_spa SET fechaIngresoPlata = '$fechaIngresoPlata',respaldoMotivo = '$respaldoMotivo ', valorExtension = $valorExtension1, horaTerminoExtencion = '$horaTerminoExtencion',horaInicioExtencion = '$horaInicioExtencion' ,rutConductor = '$nit',motivoViaje = '$motivoViaje' ,valorViaje = $valorViaje ,numeroViaje = '$numeroViaje', fechaViaje = '$fechaViaje' , tipoVehiculo = '$tipoVehiculo' , patenteVehiculo = '$patenteVehiculo' ,conductorViaje = '$conductorViaje',pasajeroViaje = '$pasajeroViaje',partidaViaje = '$partidaViaje',destinoViaje = '$destinoViaje', tipoJornada = '$numTipoJornada', unidadViaje = '$unidadMovilizacion', horaInicia = '$horaInicio', horaTermino = '$horaTermino', listadoUnidad = '$listadoUnidad' WHERE idViaje = $idViaje";
                $res = mysqli_query($conexion, $sqlUpdate);

                $idViaje = mysqli_insert_id($conexion);

                if (isset($_FILES['archivosViajes'])) {

                    $directorio = "../sdo-archivos-viajesMovilizacion-contrato-vamoos-spa/registro-" . $idViaje . "/";
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

                            $directorio = "../sdo-archivos-viajesMovilizacion-contrato-vamoos-spa/registro-" . $idViaje . "/";

                            if (!file_exists($directorio)) {
                                mkdir($directorio, 0777, true);
                            }

                            $dir = opendir($directorio);

                            $ruta = $directorio . $name;

                            $separarNombre = explode(".", $name);

                            $extImagen = end($separarNombre);

                            $extImagen = mb_strtolower($extImagen);

                            if ($extImagen == "docx" || $extImagen == "xlsx" || $extImagen == "pdf" || $extImagen == "jpg" || $extImagen == "png" || $extImagen == "jpeg" || $extImagen == "bmp" || $extImagen == "tif" || $extImagen == "webp" || $extImagen == "svg" || $extImagen == "jfif") {
                                if(file_exists($ruta)){
                                    header('Location: ../' . $rutaDireccion . '/listado-viajes-vamoos-spa.php?errorModificar5=true');
                                }
                                if (move_uploaded_file($tmp_name, $ruta)) {

                                    $query = "INSERT INTO archivos_viajes_movilizacion_vamoos_spa( nomArchivoVM,rutaArchivoVM,extArchivo, idViaje)";
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
                header('Location: ../' . $rutaDireccion . '/listado-viajes-vamoos-spa.php?noValorViaje=true');
            }

            if ($res) {
                header('Location: ../' . $rutaDireccion . '/listado-viajes-vamoos-spa.php?exitoModificar=true');
            } else {
                header('Location: ../' . $rutaDireccion . '/listado-viajes-vamoos-spa.php?errorModificar6=true');
            }
        } else {
            header('Location: ../' . $rutaDireccion . '/listado-viajes-vamoos-spa.php?existenVacios=true');
        }
    } else {
        header('Location: ../' . $rutaDireccion . '/listado-viajes-vamoos-spa.php?noNumero=true');
    }
}
