<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');

date_default_timezone_set('America/Santiago');
setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');

if (isset($_POST['folioSolicitud'])) {

    $folioSolicitud = trim($_POST['folioSolicitud']);

    if (empty($folioSolicitud) || $folioSolicitud == '') {
        header('Location: ../sdo-flujo/soporte_consultaEstado.php?folioSolicitudVacio=true');
    } else {
        $estadoSolicitud = consultarEstado($folioSolicitud);

        if (empty($estadoSolicitud) || $estadoSolicitud == '') {
            header('Location: ../sdo-flujo/soporte_consultaEstado.php?noExisteFolio=true');
        } else {
            if ($estadoSolicitud == "Solicitud Finalizada") {

                $getNomPersonalSolicitud = getNomPersonalSolicitud($folioSolicitud);
                $getTerminoSolicitud = getTerminoSolicitud($folioSolicitud);
                $getRazonesTerminoSolicitud = getRazonesTerminoSolicitud($folioSolicitud);
                $getNomDeptoSolicitud = getNomDeptoSolicitud($folioSolicitud);

                if (empty($getNomPersonalSolicitud) || $getNomPersonalSolicitud == '') {
                    header('Location: ../sdo-flujo/soporte_consultaEstado.php?noExisteFolio=true');
                }

                if (empty($getTerminoSolicitud) || $getTerminoSolicitud == '') {
                    header('Location: ../sdo-flujo/soporte_consultaEstado.php?noExisteFolio=true');
                } else {
                    $getTerminoSolicitud2 = strtotime($getTerminoSolicitud);
                    $getTerminoSolicitud2 = fechaEspañol($getTerminoSolicitud2);
                }

                if (empty($getRazonesTerminoSolicitud) || $getRazonesTerminoSolicitud == '') {
                    header('Location: ../sdo-flujo/soporte_consultaEstado.php?noExisteFolio=true');
                }

                if (empty($getNomDeptoSolicitud) || $getNomDeptoSolicitud == '') {
                    header('Location: ../sdo-flujo/soporte_consultaEstado.php?noExisteFolio=true');
                }
            } else if ($estadoSolicitud == "Solicitud Cancelada") {
                $getTerminoSolicitud = getTerminoSolicitud($folioSolicitud);

                $idSolicitud = getIDxFolio($folioSolicitud);

                if (!empty($idSolicitud) && $idSolicitud > 0) {
                    $getDetalleObservaSolicitudes = getDetalleObservaSolicitudes("Solicitud Cancelada", $idSolicitud);

                    if (empty($getDetalleObservaSolicitudes) || $getDetalleObservaSolicitudes == '') {
                        header('Location: ../sdo-flujo/soporte_consultaEstado.php?noExisteFolio=true');
                    }

                    if (empty($getTerminoSolicitud) || $getTerminoSolicitud == '') {
                        header('Location: ../sdo-flujo/soporte_consultaEstado.php?noExisteFolio=true');
                    } else {
                        $getTerminoSolicitud2 = strtotime($getTerminoSolicitud);
                        $getTerminoSolicitud2 = fechaEspañol($getTerminoSolicitud2);
                    }
                } else {
                    header('Location: ../sdo-flujo/soporte_consultaEstado.php?noExisteFolio=true');
                }
            } else if ($estadoSolicitud == "Ticket de Proyecto") {
                $getTerminoSolicitud = getDetalleTicketProyecto($folioSolicitud);

                $idSolicitud = getIDxFolio($folioSolicitud);

                if (!empty($idSolicitud) && $idSolicitud > 0) {


                } else {
                    header('Location: ../sdo-flujo/soporte_consultaEstado.php?noExisteFolio=true');
                }
            }else if($estadoSolicitud == 'Ticket con Programación de Trabajos'){

            }
        }
    }
} else {
    header('Location: ../sdo-flujo/soporte_consultaEstado.php?errorConsultaEstado=true');
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Situaci&oacute;n de Solicitud de Soporte</title>

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


<body style="background-image:url(../dist/img/Hospital.png); background-size: cover;" class="hold-transition login-page ">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="../dist/img/controlCent-HBQP-120.png" alt="AdminLTELogo">
    </div>


    <div class="container">
        <div class="row align-items-center vh-100">
            <div class="col-md-8 mx-auto">
                <div class="card shadow border">
                    <div class="card-header text-center">
                        <a href="#" class="h3"><b>Estado de Solicitud de Soporte - SDO</b></a>
                        <br>
                        <br>
                        <input type=image src="../dist/img/controlCent-HBQP-80.png">
                    </div>

                    <div class="card-body align-items-center">

                        <?php

                        if ($estadoSolicitud == "Solicitud Finalizada") {
                        ?>
                            <div class="form-group">
                                <h5 style="text-align:justify;"><b>Estimado(a): </b></h5>
                                <br>
                                <h5 style="text-align:justify;">El d&iacute;a <?php echo $getTerminoSolicitud2; ?>, su Solicitud de Soporte N° <b><?php echo $folioSolicitud; ?></b> fue Finalizada por <?php echo $getNomPersonalSolicitud; ?> de <?php echo $getNomDeptoSolicitud ?> .</h5>
                                <br>
                                <h5 style="text-align:justify;">Las razones de finalizaci&oacute;n son las siguientes: <br><br></h5>

                                <textarea id="conclusionesSolicitud" readonly="readonly" style="resize:none; background-color:white; text-align:justify;" name="conclusionesSolicitud" class="form-control" rows="12" placeholder="Conclusiones del Problema..."><?php echo $getRazonesTerminoSolicitud; ?></textarea>

                                <br>

                            </div>
                        <?php
                        } else if ($estadoSolicitud == "Solicitud Cancelada") {
                            ?>
                            <div class="form-group">
                                <h5 style="text-align:justify;"><b>Estimado(a): </b></h5>
                                <br>
                                <h5 style="text-align:justify;">El d&iacute;a <?php echo $getTerminoSolicitud2; ?>, su Solicitud de Soporte N° <b><?php echo $folioSolicitud; ?></b> fue Cancelada.</h5>
                                <br>
                                <h5 style="text-align:justify;">Las razones de cancelaci&oacute;n son las siguientes: <br><br></h5>

                                <textarea id="conclusionesSolicitud" readonly="readonly" style="resize:none; background-color:white; text-align:justify;" name="conclusionesSolicitud" class="form-control" rows="12" placeholder="Conclusiones del Problema..."><?php echo $getDetalleObservaSolicitudes; ?></textarea>

                                <br>

                            </div>
                            <?php
                        } else if ($estadoSolicitud == "Ticket de Proyecto") {
                            ?>
                            <div class="form-group">
                                <h5 style="text-align:justify;"><b>Estimado(a): </b></h5>
                                <br>
                                <h5 style="text-align:justify;">Su Solicitud de Soporte N° <b><?php echo $folioSolicitud; ?></b> ha sido catalogada como un Proyecto, por lo cual ha sido derivada a C&oacute;mite.</h5>
                                <br>
                                <h5 style="text-align:justify;">Las razones de esta decisi&oacute;n son las siguientes: <br><br></h5>

                                <textarea id="conclusionesSolicitud" readonly="readonly" style="resize:none; background-color:white; text-align:justify;" name="conclusionesSolicitud" class="form-control" rows="12" placeholder="Razones..."><?php echo $getTerminoSolicitud; ?></textarea>

                                <br>

                            </div>
                            <?php
                        } else if ($estadoSolicitud == "Solicitud Creada") {
                        ?>
                            <div class="form-group">
                                <h5><b>Estimado(a): </b></h5>
                                <br>
                                <h5>Su Solicitud de Soporte N° <b><?php echo $folioSolicitud; ?></b> se encuentra en el siguiente estado: </h5>
                                <br>

                                <center>
                                    <h4><b>"Ingresada en Sistema"</b></h4>
                                </center>
                                <br>
                            </div>
                        <?php
                        } else {
                        ?>
                            <div class="form-group">
                                <h5><b>Estimado(a): </b></h5>
                                <br>
                                <h5>Su Solicitud de Soporte N° <b><?php echo $folioSolicitud; ?></b> se encuentra en el siguiente estado: </h5>
                                <br>

                                <center>
                                    <h3><b>"<?php echo $estadoSolicitud; ?></b>"</h3>
                                </center>
                                <br>

                            </div>
                        <?php
                        }

                        ?>


                        <div class="form-group">
                            <button class="btn btn-block btn-warning" onclick="cerrarPestana()">Finalizar</button>
                        </div>

                        <script>
                            function cerrarPestana() {
                                window.close();  // Cierra la pestaña actual
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="../plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>
</body>

</html>