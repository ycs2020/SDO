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

$nombreUnidades = ( unserialize(base64_decode($_POST['nombreUnidades'])));

$solicitanteFormRegAcceso = $_POST['solicitanteFormRegAcceso'];
$unidadSolicitaFormRegAcceso = $_POST['unidadSolicitaFormRegAcceso'];
$emailSolicitaFormRegAcceso = $_POST['emailSolicitaFormRegAcceso'];


$conteo = 0;
$unidad = 0;

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

                    if($cantidadFilas == $conteoFuncionariosError){

                        $sqlBorraRegistro = "DELETE FROM formularios_registro WHERE idFormRegAcceso = ".$idRegistrarSolicitud;
                        $resBorraRegistro = mysqli_query($conexion, $sqlBorraRegistro);

                        header('Location: ../sdo-flujo/accesos_seleccion.php?errorGenerarFormulario1=true');
                    }else if($conteoFuncionariosExito == 0){

                        $sqlBorraRegistro = "DELETE FROM formularios_registro WHERE idFormRegAcceso = ".$idRegistrarSolicitud;
                        $resBorraRegistro = mysqli_query($conexion, $sqlBorraRegistro);

                        header('Location: ../sdo-flujo/accesos_seleccion.php?errorGenerarFormulario2=true');
                    }
                }else{
                    header('Location: ../sdo-flujo/accesos_seleccion.php?errorGenerarFormulario5=true');
                }
            }
        }else{
            header('Location: ../sdo-flujo/accesos_seleccion.php?errorGenerarFormulario3=true');
        }
}else{
    header('Location: ../sdo-flujo/accesos_seleccion.php?errorGenerarFormulario4=true');
}

/*
foreach ($nombreUnidades  as $nomUn) {


        $conteo = $_POST['ultimo'];
        $unidad = 0;

        if($resRegistrarSolicitud){




            if ($resFolioSolicitud) {


            }else{
                header('Location: ../sdo-flujo/accesos_seleccion_2.php?errorGenerarFormulario3=true');
            }

        }else{
            header('Location: ../sdo-flujo/accesos_seleccion_2.php?errorGenerarFormulario4=true');
        }

    }else{
        header('Location: ../sdo-flujo/accesos_seleccion_2.php?errorGenerarFormulario5=true');
    }
}
*/
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Solicitud de Formato de Acceso</title>

    <link rel="icon" href="../dist/img/controlCent-HBQP-120.png" type="image/icon type">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>

    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
</head>
<body style="background-image:url(../dist/img/Hospital.png); background-position: center; background-size: cover;" class="hold-transition login-page ">
<div class="container">
    <div class="row align-items-center vh-100">
        <div class="col-md-6 mx-auto">
            <div class="card shadow border">
                <div class="card-header text-center">
                    <a href="#" class="h3"><b>Solicitud de Formato de Acceso</b></a>
                    <br>
                    <br>
                    <input type="image" src="../dist/img/controlCent-HBQP-80.png">
                </div>

                <div class="card-body  flex-column align-items-center">
                    <h5 style="text-indent:40px; text-align: justify;">Se ha generado de manera correcta la Solicitud de Acceso a la Unidad solicitada. Ahora debe descargar el archivo mediante el bot&oacute;n a continuaci&oacute;n y solicitar la Firma a la Jefatura de dicha Unidad.</h5>
                    <h5 style="text-indent:40px; text-align: justify;">Una vez firmado, debe ser enviado mediante correo electronico a la Sala de Control Centralizado, al e-mail: saladecontrolhbqp@gmail.com o entregado de manera f&iacute;sica.</h5>

                    <br><br><br>

                    <form action="../sdo-pdf/SDO-Formulario-SolicitudAcceso-Nuevo-2.php" id="miFormulario" target="_blank" method="post" enctype="multipart/form-data">

                        <input type="hidden" name="idFormRegAcceso" id="idFormRegAcceso" value="<?php echo $idRegistrarSolicitud?>"/>
                        <button type="submit" style="text-align: center;" id="enviarFormulario" class="btn btn-block bg-success">Descargar Solicitud de Acceso</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
</body>
</html>



