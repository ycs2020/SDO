<?php

include_once '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set("America/Santiago");
setlocale(LC_TIME, 'es_CL.UTF-8', 'esp');
$fecha_actual = date("Y-m-d");

//VARIABLES DE SESSION

$nomUsuario = $_SESSION['nomUsuario'];
$userUsuario = trim($_SESSION['userUsuario']);

$isJefe = isJefe($userUsuario);

if($isJefe < 1){
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if (isset($_POST['idViaje'])) {
    $idViaje = $_POST['idViaje'];

    if ($idViaje > 0) {
        if (empty($idViaje) || $idViaje == '') {
        } else {

            ini_set("memory_limit", "1256M");
            $zip = new ZipArchive();
            $dir = "../sdo-archivos-viajesMovilizacion/registro-" . $idViaje;
            $dir2 = $dir . "/";
            $rutaFinal = "../sdo-jefestecnicos/";
            $archivoZip = "Archivos-viaje.zip";
            if ($zip->open($archivoZip, ZIPARCHIVE::CREATE) === true) {
                agregar_zip($dir2, $zip);
                $zip->close();
                // echo '<script type="text/javascript">alert("'.$dir.'");</script>';
                if (file_exists($rutaFinal . $archivoZip)) {
                    $absoluteFilePath = $rutaFinal . "/" . $archivoZip;
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename=' . basename($archivoZip));
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($archivoZip));
                    ob_clean();
                    flush();
                    readfile($archivoZip);
                    unlink($archivoZip); //Esta línea destruye el elemento zip que se genera
                    exit;
                } else {
                    $descargaConfirma .= "N";
                }
            }
        }
    }
} {
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulario de viajes</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">

    <script src="../sdo-canvasdraw1/jquery.min.js"></script>
    <script src="../sdo-canvasdraw1/signature_pad.js"></script>
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />



    <script type="text/javascript">
        $(document).ready(function() {
            habilitarOtroMotivo();
            habilitarOtroConductor();
            habilitarOtroPatente();
            habilitarOtraUnidad();
            habilitarPersonalizaUnidad();
            habilitarExtensionHoraria();

            $('#motivoViaje').change(function() {
                habilitarOtroMotivo();
            });

            $('#conductorViaje').change(function() {
                habilitarOtroConductor();
            });

            $('#patenteVehiculo').change(function() {
                habilitarOtroPatente();
            });

            $('#unidadMovilizacion').change(function() {
                habilitarOtraUnidad();
            });

            $('#unidadSolicitante').change(function() {
                habilitarPersonalizaUnidad();
            });

            $('#unidadMovilizacion').change(function() {
                habilitarExtensionHoraria();
            });

            $('#horaTermino').change(function() {
                habilitarExtensionHoraria();
            });


            function habilitarOtraUnidad(){
                $.ajax({
                    type: "POST",
                    url: "sdo-datos-motivos/habilitarOtraUnidad.php",
                    data: "nomUnidad=" + $('#unidadMovilizacion').val(),
                    success: function(r) {
                        $('#visualizarOtraUnidad').html(r);


                    }
                });
            }

            function habilitarPersonalizaUnidad(){
                $.ajax({
                    type: "POST",
                    url: "sdo-datos-motivos/habilitarPersonalizaUnidad.php",
                    data: "nomUnidad=" + $('#unidadSolicitante').val(),
                    success: function(r) {
                        $('#unidadPersonalizada').html(r);
                    }
                });
            }

            function habilitarOtroMotivo() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-motivos/habilitarOtroMotivo.php",
                    data: "nomMotivo=" + $('#motivoViaje').val(),
                    success: function(r) {
                        $('#visualizarOtroMotivo').html(r);


                    }
                });

            };

            function habilitarOtroConductor() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-motivos/habilitarOtroConductor2.php",
                    data: "nomConductor=" + $('#conductorViaje').val(),
                    success: function(r) {
                        $('#visualizarOtroConductor').html(r);


                    }
                });

            };

            function habilitarOtroPatente() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-motivos/habilitarOtroPatente2.php",
                    data: "numPatente=" + $('#patenteVehiculo').val(),
                    success: function(r) {
                        $('#visualizarOtroPatente').html(r);


                    }
                });

            };

            function habilitarExtensionHoraria(){

                /*var horaTermino = $('#horaTermino').val();*/
                var unidadMovilizacion = $('#unidadMovilizacion').val();

                if (unidadMovilizacion === "" || unidadMovilizacion === null) {
                    document.getElementById("chkExtension").checked = false;
                    document.getElementById('chkExtension').readOnly = false;
                    document.getElementById("chkExtension").disabled = true;
                    document.getElementById("divChkExtension").style.display = "none";
                }else{
                    if (unidadMovilizacion !== "HOSPITALIZACION DOMICILIARIA") {
                        document.getElementById("chkExtension").checked = false;
                        document.getElementById('chkExtension').readOnly = false;
                        document.getElementById("chkExtension").disabled = true;
                        document.getElementById("divChkExtension").style.display = "none";
                    }else{

                        document.getElementById("chkExtension").checked = false;
                        document.getElementById("chkExtension").disabled = false;
                        document.getElementById("divChkExtension").style.display = "";
                    }
                }

            };
        });
    </script>
    <style>
        #mesInforme {
            display: none;
        }

        #rangoFecha1 {
            display: none;
        }
    </style>
    <script>
        function selectNit(e) {
            var nit =  e.target.selectedOptions[0].getAttribute("data-nit")
            document.getElementById("nit").value = nit;
        }
    </script>
    <script>
        $(function(){
            $('.fantasma').change(function(){
                if(!$(this).prop('checked')){
                    $('#fieldset1').hide();
                    $('#fieldset2').hide();
                    $('#parrafo').hide();

                }else{
                    $('#fieldset1').show();
                    $('#fieldset2').show();
                    $('#parrafo').show();

                }

            })

        })
    </script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="preloader  flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../dist/img/ssvq1.png" alt="logo">
    <br>
    <div class="spinner-border text-danger" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>
<div class="wrapper">
    <?php
    include '../sdo-templates/jefes-sidebar.php';
    ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">

                    </div>

                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <?php

        if (isset($_GET['noNumero'])) {
            ?>
            <script>
                Swal.fire(
                    '¡ Ha ocurrido un problema !',
                    'No ingreso Numero de documento!',
                    'error'
                )
            </script>
            <?php
        }

        if (isset($_GET['existenVacios'])) {
            ?>
            <script>
                Swal.fire(
                    '¡ Ha ocurrido un problema !',
                    'Todos los inputs son obligatorios!',
                    'error'
                )
            </script>
            <?php
        }

        if (isset($_GET['exitoCrearViaje'])) {
            ?>
            <script>
                Swal.fire(
                    'Se ha creado el registro del viaje correctamente!',
                    '',
                    'success'
                )
            </script>
            <?php
        }

        if (isset($_GET['noValorViaje'])) {
            ?>
            <script>
                Swal.fire(
                    '¡ Ha ocurrido un problema !',
                    'No fue posible generar el valor del viaje!',
                    'error'
                )
            </script>
            <?php
        }

        if (isset($_GET['errorCrearViaje'])) {
            ?>
            <script>
                Swal.fire(
                    '¡ Ha ocurrido un problema !',
                    'Error al crear el viaje!',
                    'error'
                )
            </script>
            <?php
        }

        if (isset($_GET['exitoModificar'])) {
            ?>
            <script>
                Swal.fire(
                    'Exito!',
                    'Registro modificado correctamente!',
                    'success'
                )
            </script>
            <?php
        }

        if (isset($_GET['errorModificar'])) {
            ?>
            <script>
                Swal.fire(
                    '¡ Ha ocurrido un problema !',
                    'Error al modificar registro!',
                    'error'
                )
            </script>
            <?php
        }

        if (isset($_GET['noDatos'])) {
            ?>
            <script>
                Swal.fire(
                    '¡ Ha ocurrido un problema !',
                    'No existen registros, segun fecha seleccionada',
                    'info'
                )
            </script>
            <?php
        }


        ?>
        <section class="content">
            <div class="container-fluid">

                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">

                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h4 class="card-title"> <b> Listado de viajes realizados</b></h4>
                            </div>
                            <!-- /.card-header -->

                            <div class="card-body">

                                <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;<b>Volver Atras</b> </a>
                                <br>
                                <br>
                                <div class="row justify-content-center">
                                    <button class="btn btn-success" data-toggle="modal" data-target=".modalViaje"><b><i class="fas fa-plus"></i> Añadir Viaje</b> </button>
                                    <button type="button" style="margin-left: 10px;" class="btn btn-warning" data-toggle="modal" data-target=".generarInforme"> <b><i class="far fa-file-pdf"></i> Generar Requerimiento</b> </button>
                                    <button type="button" style="margin-left: 10px;" class="btn btn-info" data-toggle="modal" data-target=".generarInformeResumen"> <b><i class="far fa-file-pdf"></i> Generar Reporte Resumen</b> </button>
                                    <form method="post" action="conductores_administracion.php">
                                        <button class="btn bg-purple" style="margin-left: 10px;"><b><i class="fas fa-users"></i>&nbsp;&nbsp;Administrar Conductores</b> </button>
                                    </form>
                                </div>

                                <div class="modal fade generarInforme" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-warning">
                                                <h5 class="modal-title"> <b>Generar Requerimiento</b> </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="../sdo-pdf/SDO-Reporte-Viajes-Prueba.php" method="POST">

                                                    <!-- <input type="hidden" name="idHoja" id="hojaSubir" value="<?php echo $idHoja ?>"> -->
                                                    <input type="hidden" name="periodo" value="rangoFechas">
                                                    <p class="text-bold text-danger" style="font-size: 12px">*Campos Obligatorios</p>

                                                    <div class="row" id="rangoFecha">
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Unidad Solicitante:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                                </div>
                                                                <select class="form-control form-control-sm" onchange="cambiaTexto()" required id="unidadSolicitante" name="unidadSolicitante">

                                                                    <option value="" selected disabled>-- Seleccione --</option>

                                                                    <?php

                                                                    $sqlMotivos = "SELECT * FROM unidad_movilizacion ";
                                                                    $resMotivos = mysqli_query($conexion, $sqlMotivos);

                                                                    while ($rowMotivos = $resMotivos->fetch_assoc()) {
                                                                        $datos = [];
                                                                        $datos['idUnidad'] = $rowMotivos['idUnidad'];
                                                                        $datos['nomUnidad'] = $rowMotivos['nomUnidad'];
                                                                        $nomUnidad = $datos['nomUnidad'];
                                                                        $idUnidad = $datos['idUnidad'];

                                                                        ?>

                                                                        <option value="<?php echo  $nomUnidad ?>"><?php echo  $nomUnidad ?></option>

                                                                        <?php

                                                                    }
                                                                    ?>
                                                                    <option value="Otro"  >OTRO</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div id="unidadPersonalizada" name="unidadPersonalizada" class="form-group col-md-12">

                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Solicitante:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-envelope-open-text"></i></span>
                                                                </div>
                                                                <input type="text" class="form-control form-control-sm" required id="solicitante" name="solicitante"></input>

                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Motivo Requerimiento:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-envelope-open-text"></i></span>
                                                                </div>
                                                                <textarea type="text" class="form-control form-control-sm" rows="5" required id="motivo" name="motivo">UNIDAD REQUIERE SERVICIOS DE VIAJES, PARA EL TRASLADO DE FUNCIONARIOS A VISITAS DE PACIENTES EN SUS DOMICILIOS, DE LUNES A VIERNES EN LOS SIGUIENTES HORARIOS: 3 VEHICULOS PARA SERVICIO DE VIAJE DE 9:00 HASTA EL TERMINO DE LA RUTA, 1 VEHICULO PARA SERVICIO DE VIAJES DE 14:00 HASTA EL TERMINO DE RUTA.</textarea>

                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Observaciones:</label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-envelope-open-text"></i></span>
                                                                </div>
                                                                <textarea type="text" class="form-control form-control-sm" rows="3" name="observaciones"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class=" form-group col-md-12">
                                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Boletas a seleccionar :</label>
                                                            <div class="input-group mb-3">
                                                                <select class="js-example-basic-multiple form-control form-control-sm"  style="width: 100%" name="boletas[]" multiple="multiple">
                                                                    <?php
                                                                    $sql = "SELECT * FROM viajes_movilizacion_prueba";
                                                                    $res = mysqli_query($conexion, $sql);
                                                                    while($row = $res->fetch_assoc()){
                                                                        $datos = [];
                                                                        $datos['numeroViaje'] = $row['numeroViaje'];
                                                                        $numeroViaje = $datos['numeroViaje'];
                                                                        ?>
                                                                        <option value="<?php  echo  $numeroViaje ?>"><?php  echo  $numeroViaje ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <p class="text-bold text-danger">*Dejar el campo en blanco, si quiere filtar por todos las boletas</p>
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Desde :</label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                </div>
                                                                <input type="date" class="form-control form-control-sm"  id="fechaInicio" name="fechaInicio">

                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Hasta :</label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                </div>
                                                                <input type="date" class="form-control form-control-sm"  id="fechaFin" name="fechaFin">

                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal"> <b>Cerrar</b> </button>
                                                <button type="submit" id="enviar" class="btn btn-primary"> <b>Generar Reporte</b> </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade generarInformeResumen" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info">
                                                <h5 class="modal-title"> <b>Generar Informe Resumen</b> </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="../sdo-pdf/SDO-Reporte-Viajes-Resumen.php" method="POST" id="formulario">
                                                    <div class="form-group col-md-12">
                                                        <label for="exampleInputCodigoTarjeta" style="font-size:12px">Tipo de archivo a generar:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="far fa-folder"></i></span>
                                                            </div>
                                                            <select class="form-control form-control-sm" onchange="tipoArchivoElegido()" required id="tipoArchivo" name="tipoArchivo" aria-describedby="emailHelp">
                                                                <option value="" selected>-- Seleccione --</option>
                                                                <option value="excel">Excel</option>
                                                                <option value="pdf">PDF</option>
                                                            </select>

                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="form-group col-md-12">
                                                        <label for="exampleInputCodigoTarjeta" style="font-size:12px">Unidad Solicitante:</label>
                                                        <small id="emailHelp" class="form-text text-muted">Dejar en blanco si quiere filtrar por todos los registros.</small>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                            </div>
                                                            <select class="form-control form-control-sm" id="unidadSolicitanteResumen" name="unidadSolicitanteResumen" aria-describedby="emailHelp">

                                                                <option value="" selected>-- Seleccione --</option>

                                                                <?php

                                                                $sqlMotivos = "SELECT * FROM unidad_movilizacion";
                                                                $resMotivos = mysqli_query($conexion, $sqlMotivos);

                                                                while ($rowMotivos = $resMotivos->fetch_assoc()) {
                                                                    $datos = [];
                                                                    $datos['nomUnidad'] = $rowMotivos['nomUnidad'];
                                                                    $nomUnidad = $datos['nomUnidad'];
                                                                    ?>
                                                                    <option value="<?php echo  $nomUnidad ?>"><?php echo  $nomUnidad ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                                <option value="OTROS">OTROS</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <!-- <input type="hidden" name="idHoja" id="hojaSubir" value="<?php echo $idHoja ?>"> -->
                                                    <div class="form-group">
                                                        <h6 class="tex-center">Escoja el periodo</h6>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="periodo" onchange="mostrarInputs(this.value)" id="exampleRadios1" value="mensual">
                                                            <label class="form-check-label" for="exampleRadios1">
                                                                Mensual
                                                            </label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="periodo" onchange="mostrarInputs(this.value)" id="exampleRadios2" value="rangoFechas">
                                                            <label class="form-check-label" for="exampleRadios2">
                                                                Rango de Fechas
                                                            </label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="periodo" onchange="mostrarInputs(this.value)" id="exampleRadios3" value="todos">
                                                            <label class="form-check-label" for="exampleRadios3">
                                                                Todos
                                                            </label>
                                                        </div>
                                                    </div>


                                                    <div class="row" id="mesInforme">
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Escoja el mes:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                </div>
                                                                <select type="text" class="form-control form-control-sm" required id="mensual" name="mesInforme">
                                                                    <option value="" disabled selected>-- Seleccione --</option>
                                                                    <option value="Enero">Enero</option>
                                                                    <option value="Febrero">Febrero</option>
                                                                    <option value="Marzo">Marzo</option>
                                                                    <option value="Abril">Abril</option>
                                                                    <option value="Mayo">Mayo</option>
                                                                    <option value="Junio">Junio</option>
                                                                    <option value="Julio">Julio</option>
                                                                    <option value="Agosto">Agosto</option>
                                                                    <option value="Septiembre">Septiembre</option>
                                                                    <option value="Octubre">Octubre</option>
                                                                    <option value="Noviembre">Noviembre</option>
                                                                    <option value="Diciembre">Diciembre</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row" id="rangoFecha1">
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Desde :<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                </div>
                                                                <input type="date" class="form-control form-control-sm" required id="fechaInicio1" name="fechaInicio1">

                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Hasta :<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                </div>
                                                                <input type="date" class="form-control form-control-sm" onchange="ValidarFechas()" required id="fechaFin1" name="fechaFin1">

                                                            </div>
                                                        </div>
                                                    </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal"> <b>Cerrar</b> </button>
                                                <button type="submit" id="enviar"  class="btn btn-primary"> <b>Generar Reporte</b> </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class="modal fade modalViaje" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success">
                                                <h5 class="modal-title" id="exampleModalLabel"> <b>Formulario - Información del viaje</b> </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="../sdo-funciones/guardar-viaje-movilizacion-prueba.php" method="POST" enctype="multipart/form-data">
                                                <div class="modal-body">

                                                    <input type="hidden" value="<?php echo $nomUsuario ?>" name="responsable">
                                                    <p class="text-bold text-danger" style="font-size: 12px">*Campos Obligatorios</p>

                                                    <div class="row">
                                                        <div class="form-group col-lg-4 col-12">
                                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Fecha Registro Vale:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                </div>
                                                                <input type="date" required class="form-control form-control-sm" value="<?php echo $fecha_actual ?>" id="fechaRegistroVale" name="fechaRegistroVale">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group col-lg-4 col-12">
                                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Numero Boleta:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                                                </div>
                                                                <input type="text" class="form-control form-control-sm" required id="numeroViaje" name="numeroViaje">
                                                                <!--<p id="mensaje"></p> -->
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-lg-4 col-12">
                                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Fecha:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                </div>
                                                                <input type="date" required class="form-control form-control-sm" id="fechaViaje" name="fechaViaje">
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-lg-4 col-12">
                                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Patente:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-car"></i></span>
                                                                </div>
                                                                <select class="form-control form-control-sm" required name="patenteVehiculo" id="patenteVehiculo">
                                                                    <option value="" selected disabled>Seleccione Patente...</option>
                                                                    <?php

                                                                    $sqlPatentes = "SELECT numPatente FROM patentes_movilizacion ORDER BY numPatente ASC";

                                                                    $resPatentes = mysqli_query($conexion, $sqlPatentes);

                                                                    while ($rowPatentes = $resPatentes->fetch_assoc()) {

                                                                        $numPatente = mb_convert_case($rowPatentes['numPatente'], MB_CASE_UPPER, 'UTF-8');

                                                                        echo '<option value="' . $numPatente . '">' . $numPatente . '</option>';
                                                                    }

                                                                    ?>
                                                                    <option value="Otro">OTRO</option>

                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div id="visualizarOtroPatente" name="visualizarOtroPatente" class="form-group col-lg-4 col-12">

                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-lg-4 col-12">
                                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Tipo Vehiculo:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-car"></i></span>
                                                                </div>
                                                                <select type="text" name="tipoVehiculo" class="form-control form-control-sm" required id="tipoVehiculo">
                                                                    <option value="" selected disabled>-- Seleccione --</option>
                                                                    <option value="1">Sedan/Sub o Camioneta</option>
                                                                    <option value="2">Van</option>
                                                                    <option value="3">3/4 Pick Up o camioneta de carga</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-lg-4 col-12">
                                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Conductor:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                                </div>
                                                                <select class="form-control form-control-sm" required name="conductorViaje" id="conductorViaje" onchange="selectNit(event)">
                                                                    <option value="" selected disabled>Seleccione Conductor...</option>
                                                                    <?php

                                                                    $sqlConductores = "SELECT * FROM conductores_movilizacion ORDER BY nomConductor ASC";

                                                                    $resConductores = mysqli_query($conexion, $sqlConductores);

                                                                    while ($rowConductores = $resConductores->fetch_assoc()) {

                                                                        $nomConductor = mb_convert_case($rowConductores['nomConductor'], MB_CASE_UPPER, 'UTF-8');
                                                                        //$nomConductor = $rowConductores['nomConductor'];
                                                                        $rutConductor = $rowConductores['rutConductor'];

                                                                        ?>
                                                                            <option data-nit="<?php echo $rutConductor?>" value="<?php echo $nomConductor?>"><?php echo $nomConductor?></option>
                                                                        <?php

                                                                    }


                                                                    ?>

                                                                </select>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="form-group col-lg-4 col-12">
                                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Rut del Conductor:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                                                </div>
                                                                <input id="nit" type="text" class="form-control form-control-sm" required readonly name="rutConductor">
                                                            </div>
                                                        </div>


                                                        <div class="destino"></div>
                                                        <div class="form-group col-lg-4 col-12">
                                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Unidad:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="far fa-building"></i></span>
                                                                </div>
                                                                <select class="form-control form-control-sm" required name="unidadMovilizacion" id="unidadMovilizacion" >
                                                                    <option value="" disabled selected>-- Seleccione --</option>
                                                                    <?php
                                                                    $sql = "SELECT * FROM unidad_movilizacion";
                                                                    $res = mysqli_query($conexion, $sql);

                                                                    while($row = $res->fetch_assoc()){
                                                                        $datos = [];
                                                                        $datos['idUnidad'] = $row['idUnidad'];
                                                                        $datos['nomUnidad'] = $row['nomUnidad'];
                                                                        $idUnidad = $datos['idUnidad'];
                                                                        $nomUnidad = $datos['nomUnidad'];
                                                                        ?>
                                                                        <option value="<?php echo $nomUnidad ?>"><?php echo $nomUnidad ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                    <option value="Otro">OTRO</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div id="visualizarOtraUnidad" name="visualizarOtraUnidad" class="form-group col-lg-4 col-12">

                                                        </div>
                                                        <div class="form-group col-lg-4 col-12">
                                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Pasajero:</label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                                </div>
                                                                <input type="text" class="form-control form-control-sm" id="pasajeroViaje" name="pasajeroViaje">
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-lg-4 col-12">
                                                            <!-- <label for="customFile">Custom File</label> -->
                                                            <label for="exampleInputTareasControlCent" style="font-size:12px;">Pto. Partida:</label><br>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-street-view"></i></span>
                                                                </div>
                                                                <input type="text" readonly class="form-control form-control-sm" id="partidaViaje" name="partidaViaje" value="HBQP">

                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Motivo:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                                </div>
                                                                <textarea name="motivoViaje1" id="motivoViaje1" class="form-control form-control-sm" rows="5"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">

                                                        <div class="form-group col-lg-4 col-12">
                                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Destino:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                                                </div>
                                                                <select name="destinoViaje" required id="destinoViaje" class="form-control form-control-sm">
                                                                    <option value="" disabled selected>-- Seleccione --</option>
                                                                    <option value="Comuna de Quillota">Comuna de Quillota</option>
                                                                    <option value="Provincia de Quillota">Provincia de Quillota</option>
                                                                    <option value="Vina del mar">Viña del mar</option>
                                                                    <option value="Valparaiso">Valparaiso</option>
                                                                    <option value="Region Metropolitana">Región Metropolitana</option>
                                                                    <option value="Provincia Marga Marga">Provincia Marga-Marga</option>
                                                                    <option value="Provincia Petorca">Provincia Petorca</option>
                                                                    <option value="San Felipe">San Felipe</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-lg-4 col-12">
                                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Hora Inicio:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-hourglass-half"></i></span>
                                                                </div>
                                                                <input type="time" required class="form-control form-control-sm" id="horaInicio" name="horaInicio">
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-lg-4 col-12">
                                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Hora Termino:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-hourglass-half"></i></span>
                                                                </div>
                                                                <input type="time" required class="form-control form-control-sm" id="horaTermino" name="horaTermino">
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-lg-4 col-12" id="divChkExtension" value="divChkExtension">
                                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Extensi&oacute;n:<code>*</code></label>
                                                            <br>
                                                            <!--<div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-hourglass-half"></i></span>
                                                                </div>
                                                            </div>-->
                                                            <div class="custom-control custom-checkbox">
                                                                <input class="custom-control-input fantasma" type="checkbox" id="chkExtension" name="chkExtension" value="extensionHora">
                                                                <label for="chkExtension" class="custom-control-label">Extensi&oacute;n Horaria</label>
                                                            </div>
                                                            <br/>
                                                        </div>
                                                        <div class="form-group col-lg-4 col-12" id="fieldset1" name="fieldset1" style="display:none;">
                                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Horario Inicio Extención:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-hourglass-half"></i></span>
                                                                </div>
                                                                <input type="time" class="form-control form-control-sm" id="horaInicioExtencion" name="horaInicioExtencion">
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-lg-4 col-12" id="fieldset2" name="fieldset2" style="display: none">
                                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Horario Termino Extención:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-hourglass-half"></i></span>
                                                                </div>
                                                                <input type="time"  class="form-control form-control-sm" id="horaTerminoExtencion" name="horaTerminoExtencion">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-12">
                                                            <!-- <label for="customFile">Custom File</label> -->
                                                            <label for="exampleInputTareasControlCent" style="font-size:12px;">Subir Archivos:</label><br>
                                                            <div class="custom-file">
                                                                <input type="file" multiple class="custom-file-input" id="archivosViajes" name="archivosViajes">
                                                                <label class="custom-file-label" for="customFile" style="font-size:12px;"><b>Seleccionar Archivos...</b> </label>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <input type="hidden" name="registroAntViaje" id="registroAntViaje" value="1" />
                                                    <button type="button" class="btn btn-warning" data-dismiss="modal"> <b>Cerrar</b> </button>
                                                    <button type="submit" disabled id="AnadirViaje" class="btn btn-success"> <b>Añadir Viaje</b> </button>

                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div> <!--Hasta Aqui-->
                                <br>
                                <hr>
                                <div class="row" style="display: flex; justify-content:center;">
                                    <div class="col-md-3">

                                        <div class="info-box">
                                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-car"></i></span>

                                            <div class="info-box-content">
                                                <?php



                                                $valorInicial = 157614000;

                                                $valorTotal = valorTotalViaje();

                                                $valorActual = $valorInicial - $valorTotal;

                                                $valorActual2 = '$' . number_format($valorActual, 0, ",", ".");

                                                ?>
                                                <span class="info-box-text text-success"><b>Saldo Inicial: $157.614.000</b></span>



                                                <span class="info-box-text text-info"><b>Saldo Actual: <?php echo $valorActual2; ?></b></span>
                                            </div>
                                            <!-- /.info-box-content -->
                                        </div>
                                        <!-- /.info-box -->
                                    </div>
                                    <!-- /.col -->
                                    <!-- /.col -->


                                </div>
                                <hr>

                                <table id="example2" class="table table-bordered table-hover text-center ">
                                    <thead class="bg-secondary">
                                    <tr>
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Destino</th>
                                        <th scope="col">Patente</th>
                                        <th scope="col">Tipo de Vehiculo</th>
                                        <th scope="col">Unidad</th>
                                        <th scope="col">Conductor</th>
                                        <th scope="col">Cantidad</th>
                                        <th scope="col">N° Boleta</th>
                                        <th scope="col">Inicio</th>
                                        <th scope="col">Fin</th>
                                        <th scope="col">Valor</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php

                                    $sqlViaje = "SELECT * FROM viajes_movilizacion_prueba  ORDER BY idViaje DESC";
                                    $resViaje = mysqli_query($conexion, $sqlViaje);

                                    while ($row = $resViaje->fetch_assoc()) {

                                        $datos = [];
                                        $datos['idViaje'] = $row['idViaje'];
                                        $datos['valorViaje'] = $row['valorViaje'];
                                        $datos['numeroViaje'] = $row['numeroViaje'];
                                        $datos['fechaViaje'] = $row['fechaViaje'];
                                        $datos['tipoVehiculo'] = $row['tipoVehiculo'];
                                        $datos['patenteVehiculo'] = $row['patenteVehiculo'];
                                        $datos['conductorViaje'] = $row['conductorViaje'];
                                        $datos['pasajeroViaje'] = $row['pasajeroViaje'];
                                        $datos['partidaViaje'] = $row['partidaViaje'];
                                        $datos['destinoViaje'] = $row['destinoViaje'];
                                        $datos['tipoJornada'] = $row['tipoJornada'];
                                        $datos['motivoViaje'] = $row['motivoViaje'];
                                        $datos['horaInicia'] = $row['horaInicia'];
                                        $datos['horaTermino'] = $row['horaTermino'];
                                        $datos['unidadViaje'] = $row['unidadViaje'];

                                        $unidadViaje = $datos['unidadViaje'];
                                        $idViaje = $datos['idViaje'];
                                        $valorViaje = $datos['valorViaje'];
                                        $numeroViaje = $datos['numeroViaje'];
                                        $tipoVehiculo = $datos['tipoVehiculo'];
                                        $fechaViaje = $datos['fechaViaje'];
                                        $patenteVehiculo = $datos['patenteVehiculo'];
                                        $conductorViaje = $datos['conductorViaje'];
                                        $pasajeroViaje = $datos['pasajeroViaje'];
                                        $partidaViaje = $datos['partidaViaje'];
                                        $destinoViaje = $datos['destinoViaje'];
                                        $tipoJornada = $datos['tipoJornada'];
                                        $motivoViaje = $datos['motivoViaje'];
                                        $horaInicia = $datos['horaInicia'];
                                        $horaTermino = $datos['horaTermino'];

                                        $valorViaje = moneda_chilena($valorViaje);

                                        ?>
                                        <tr>
                                            <td style="font-size: 12px">
                                                <?php

                                                $fechaViaje = date('d-m-Y', (strtotime(str_replace('/', '-', $row['fechaViaje']))));

                                                echo $fechaViaje
                                                ?>
                                            </td>
                                            <td style="font-size: 12px"><?php echo strtoupper($destinoViaje) ?> </td>
                                            <td style="font-size: 12px"><?php echo strtoupper($patenteVehiculo) ?> </td>
                                            <td style="font-size: 12px">
                                                <?php
                                                if ($tipoVehiculo == 1) {
                                                    $tipoVehiculo = 'AUTOMOVIL';
                                                } else if ($tipoVehiculo == 2) {
                                                    $tipoVehiculo = 'VAN';
                                                } else if ($tipoVehiculo == 3) {
                                                    $tipoVehiculo = 'CAMIONETA DE CARGA';
                                                }
                                                echo $tipoVehiculo

                                                ?>
                                            </td>
                                            <td style="font-size: 12px"><?php
                                                if($unidadViaje == ''){
                                                    echo '-';
                                                }else{
                                                    echo strtoupper($unidadViaje);
                                                }
                                                ?> </td>
                                            <td style="font-size: 12px"><?php echo strtoupper($conductorViaje) ?> </td>
                                            <td style="font-size: 12px"><?php echo strtoupper($tipoJornada)  ?> </td>
                                            <td style="font-size: 12px" class="text-danger text-bold"> <?php echo $numeroViaje ?> </td>
                                            <td style="font-size: 12px" class=" text-bold"> <?php echo $horaInicia ?> </td>
                                            <td style="font-size: 12px" class=" text-bold"> <?php echo $horaTermino  ?> </td>
                                            <td style="font-size: 12px" class="text-success text-bold"> <?php echo $valorViaje ?> </td>
                                            <td style="font-size: 12px" class="text-success text-bold">
                                                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">

                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">

                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <form action="editar-viaje-movilizacion.php" method="post" enctype="multipart/form-data">
                                                                <input type="hidden" name="idViaje" value="<?php echo $idViaje ?>">
                                                                <button class="dropdown-item" tyoe="submit"><i class="fas fa-edit"></i> Editar </button>
                                                            </form>

                                                            <?php

                                                            $sqlArchivos = "SELECT * FROM archivos_viajes_movilizacion WHERE idViaje = $idViaje";
                                                            $resArchivos = mysqli_query($conexion, $sqlArchivos);

                                                            if ($resArchivos->num_rows > 0) {
                                                                ?>
                                                                <form action="listado-viajes.php" method="POST">
                                                                    <input type="hidden" name="idViaje" value="<?php echo $idViaje ?>">
                                                                    <button type="submit" class="dropdown-item" href="#"><i class="fas fa-download"></i> Descargar </button>
                                                                </form>
                                                                <?php
                                                            }

                                                            ?>
                                                            <a class="dropdown-item eliminar" id="<?php echo $idViaje ?>" href="#"><i class="fas fa-trash-alt"></i> Eliminar</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <?php
                                    }

                                    ?>

                                    </tbody>
                                    <tfoot class="bg-secondary">
                                    <th scope="col">Fecha</th>
                                    <th scope="col">Destino</th>
                                    <th scope="col">Patente</th>
                                    <th scope="col">Tipo de Vehiculo</th>
                                    <th scope="col">Unidad</th>
                                    <th scope="col">Conductor</th>
                                    <th scope="col">Cantidad</th>
                                    <th scope="col">N° Boleta</th>
                                    <th scope="col">Inicio</th>
                                    <th scope="col">Fin</th>
                                    <th scope="col">Valor</th>
                                    <th scope="col">Acciones</th>
                                    </tfoot>
                                </table>



                            </div>

                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->

                    </div>
                    <!--/.col  -->

                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <strong>Copyright &copy; 2022 - ∞. Derechos Reservados</strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- ./wrapper -->
<script src="js/dark-mode.js"></script>
<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../plugins/jszip/jszip.min.js"></script>
<script src="../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Page specific script -->
<script>
    $(function() {
        bsCustomFileInput.init();
    });
</script>
<!-- <script>
    $(document).ready(function() {
        $('#table_equipos').DataTable({
            "buttons": [{
                extend: 'excelHtml5',
                text: 'Exportar a Excel',
                title: 'Resumen Solicitudes de Compra',
                exportOptions: {
                    columns: [0,1,2,3],
                    format: {
                        body: function(data, column, row) {
                            if (typeof data === 'string' || data instanceof String) {
                                data = data.replace(/<br\s*\/?>/ig, "\r\n");
                            }
                            return data;
                        }
                    }
                }
            }],
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": false,
            "autoWidth": true,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "search": "Buscar:",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            }
        })
    });
</script> -->
<script>
    $(function() {

        $('#example2').DataTable({
            "buttons": [{
                extend: 'excelHtml5',
                text: 'Exportar a Excel',
                title: 'Resumen Viajes Movilización',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                    format: {
                        body: function(data, column, row) {
                            if (typeof data === 'string' || data instanceof String) {
                                data = data.replace(/<br\s*\/?>/ig, "\r\n");
                            }
                            return data;
                        }
                    }
                }
            }],
            "paging": true,
            "pageLength": 10,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": false,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "search": "Buscar :",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
            initComplete: function() {
                this.api().columns([0]).every(function() {
                    var column = this;
                    var select = $('<select style="width: 100%;"><option value="">Seleccione</option></select>')
                        .appendTo($(column.header()).empty())
                        .on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                    column.data().unique().sort().each(function(d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });
                this.api().columns([1]).every(function() {
                    var column = this;
                    var select = $('<select style="width: 100%;"><option value="">Seleccione</option></select>')
                        .appendTo($(column.header()).empty())
                        .on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                    column.data().unique().sort().each(function(d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });
                this.api().columns([4]).every(function() {
                    var column = this;
                    var select = $('<select style="width: 100%;"><option value="">Seleccione</option></select>')
                        .appendTo($(column.header()).empty())
                        .on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                    column.data().unique().sort().each(function(d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });
                this.api().columns([2]).every(function() {
                    var column = this;
                    var select = $('<select style="width: 100%;"><option value="">Seleccione</option></select>')
                        .appendTo($(column.header()).empty())
                        .on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                    column.data().unique().sort().each(function(d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });

            }
        }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
    });
</script>
<script>
    $(document).ready(

        $('.eliminar').click(function(e) {

            e.preventDefault();
            let id = $(this).attr('id');
            var dataString = 'id=' + id;
            Swal.fire({
                icon: 'question',
                title: '¿Eliminar el registro de este viaje?',
                text: 'Esta acción no se puede revertir',
                showConfirmButton: true,
                confirmButtonText: 'Si , eliminar',
                confirmButtonColor: '#3085d6',
                showCancelButton: true,
                cancelButtonText: 'No, lo pensare',
                cancelButtonColor: '#d33',
                buttonsStyling: true
            }).then((result) => {

                if (result.isConfirmed) {
                    $.ajax({
                        url: '../sdo-funciones/eliminar-viaje-movilizacion.php',
                        type: 'POST',
                        data: dataString,
                        success: function(response) {
                            console.log('eliminado.. ' + dataString);
                            Swal.fire({
                                icon: 'success',
                                title: 'Registro del viaje eliminado',
                                showConfirmButton: false,
                                timer: 2000,
                            })
                            setTimeout(function() {
                                location.reload();
                            }, 2100)
                        }
                    })
                }
            })
        })
    )
</script>
<script>
    $("#numeroViaje").blur(function() {
        AnadirViaje = document.getElementById('AnadirViaje');
        campo = document.getElementById('numeroViaje');
        mensaje = document.getElementById('mensaje');
        valor = $(this).val();
        var dataString = 'valor=' + valor;
        console.log(valor);
        $.ajax({
            url: 'consulta-numero-viaje.php',
            type: 'POST',
            data: dataString,
            success: function(response) {
                console.log(response)
                if (response == '1') {
                    AnadirViaje.disabled = true;
                    campo.style.borderColor = "red";
                    mensaje.textContent = 'Ya existe el codigo ingresado';
                    mensaje.style.color = "red";
                    mensaje.style.fontWeight = "bold";

                } else if (response == '0') {

                    AnadirViaje.disabled = false;
                    campo.style.borderColor = "green";
                    mensaje.textContent = 'Todo ok, llene el formulario';
                    mensaje.style.color = "green";
                    mensaje.style.fontWeight = "bold";

                }
            }
        })

    });
</script>
<script>
    function cambiaTexto() {

        unidadSolicitante = document.getElementById('unidadSolicitante');
        solicitante = document.getElementById('solicitante');

        seleccionado = unidadSolicitante.options[unidadSolicitante.selectedIndex].text;

        motivo = document.getElementById('motivo');

        console.log(seleccionado);

        if (seleccionado == 'PALIATIVOS' || seleccionado == 'paliativos') {

            texto = `Unidad requiere servicios de transporte, para el traslado de personal para realizar visitas domiciliarias de la Provincia de Quillota , de lunes a viernes en horarios de 09:00 a 13:30 y 14:00 a 17:30 de forma Permanente. Se debe considerar de manera las urgencias o traslados extras según lo requiera la unidad`;
            solicitante.value = "SUPERVISORA DE CRD HBQP";
            motivo.innerHTML = texto;

        } else if (seleccionado == 'HOSPITALIZACION DOMICILIARIA' || seleccionado == 'hospitalizacion domiciliaria') {

            texto = `Unidad de Hospitalización Domiciliaria requiere vehículos de transporte para su personal el cual realiza visitas a domicilio dentro de la comuna de Quillota en los siguientes horarios:
                3 vehículos de lunes a domingo de 08:00 a 20:00
                1 vehículo de lunes a viernes de 8:00 a 17:00 horas`;
            solicitante.value = "CLAUDIO BARRIGA";
            motivo.innerHTML = texto;
        }
    }
</script>
<script>
    function mostrarInputs(valor) {

        var enviar = document.getElementById("enviar");

        //Mensual
        mesInforme = document.getElementById('mesInforme');
        mensual = document.getElementById('mensual');

        //Rango de fechas
        rangoFecha1 = document.getElementById('rangoFecha1');
        fechaInicio = document.getElementById('fechaInicio1');
        fechaFin = document.getElementById('fechaFin1');

        if (valor == 'mensual') {
            enviar.disabled = false;

            mensual.required = true;
            mesInforme.style.display = 'block';

            //Configura rango fechas
            rangoFecha1.style.display = 'none';
            fechaInicio.required = false;
            fechaFin.required = false;
        } else if (valor == 'rangoFechas') {
            enviar.disabled = false;

            fechaInicio.required = true;
            fechaFin.required = true;
            rangoFecha1.style.display = 'block';


            //Configura radio mensual
            mensual.required = false;
            mesInforme.style.display = 'none';
        } else if (valor == 'todos') {
            enviar.disabled = false;

            //Configura rango fechas
            rangoFecha1.style.display = 'none';
            fechaInicio.required = false;
            fechaFin.required = false;

            //Configura radio mensual
            mensual.required = false;
            mesInforme.style.display = 'none';
        }

        console.log(valor);

    }
</script>
<script>
    function ValidarFechas() {
        var fechainicial = document.getElementById("fechaInicio1").value;
        var fechafinal = document.getElementById("fechaFin1").value;
        var enviar = document.getElementById("enviar");

        console.log(fechainicial)
        console.log(fechafinal)

        fechainicial = Date.parse(fechainicial);
        fechafinal = Date.parse(fechafinal);

        console.log(fechainicial)
        console.log(fechafinal)

        if (fechainicial > fechafinal) {
            alert("La fecha final debe ser mayor a la fecha inicial");
            enviar.disabled = true;

        } else {
            enviar.disabled = false;
        }

    }
</script>
<script>
    function tipoArchivoElegido() {

        tipoArchivo = document.getElementById("tipoArchivo").value;
        formulario = document.getElementById("formulario");

        console.log(tipoArchivo);

        if (tipoArchivo == 'excel') {
            formulario.setAttribute('action', '../sdo-pdf/SDO-Reporte-Viajes-Resumen2.php')
        } else if (tipoArchivo == 'pdf') {
            formulario.setAttribute('action', '../sdo-pdf/SDO-Reporte-Viajes-Resumen.php')
        }

    }
</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();
    });
</script>
</body>

</html>