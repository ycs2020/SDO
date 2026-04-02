<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

$ruta = "";

if ($rolUsuario2 == "personal") {

    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {

    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {

    $ruta = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {

    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {

    $ruta = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $ruta = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

$idViaje = $_POST['idViaje'];

if ($_POST['idViaje']) {

    $sqlViaje = "SELECT * FROM viajes_movilizacion_vamoos_spa WHERE idViaje = $idViaje";
    $resViaje = mysqli_query($conexion, $sqlViaje);

    while ($row = $resViaje->fetch_assoc()) {
        $datos = [];

        $datos['valorViaje'] = $row['valorViaje'];
        $datos['numeroViaje'] = $row['numeroViaje'];
        $datos['fechaViaje'] = $row['fechaViaje'];
        $datos['patenteVehiculo'] = $row['patenteVehiculo'];
        $datos['tipoVehiculo'] = $row['tipoVehiculo'];
        $datos['conductorViaje'] = $row['conductorViaje'];
        $datos['pasajeroViaje'] = $row['pasajeroViaje'];
        $datos['partidaViaje'] = $row['partidaViaje'];
        $datos['destinoViaje'] = $row['destinoViaje'];
        $datos['tipoJornada'] = $row['tipoJornada'];
        $datos['motivoViaje'] = $row['motivoViaje'];
        $datos['horaInicia'] = $row['horaInicia'];
        $datos['horaTermino'] = $row['horaTermino'];
        $datos['unidadViaje'] = $row['unidadViaje'];
        $datos['horaInicioExtencion'] = $row['horaInicioExtencion'];
        $datos['horaTerminoExtencion'] = $row['horaTerminoExtencion'];
        $datos['rutConductor'] = $row['rutConductor'];
        $datos['chkExtension'] = $row['chkExtension'];
        $datos['respaldoMotivo'] = $row['respaldoMotivo'];
        $fechaIngresoPlata = $row['fechaIngresoPlata'];

        if($fechaIngresoPlata == '' || empty($fechaIngresoPlata)){
            $fechaIngresoPlata = '0000-00-00 00:00:00';
        }

        $valorViaje = $datos['valorViaje'];
        $numeroViaje = $datos['numeroViaje'];
        $fechaViaje = $datos['fechaViaje'];
        $patenteVehiculo = $datos['patenteVehiculo'];
        $patenteVehiculo = strtoupper(trim($datos['patenteVehiculo']));
        $tipoVehiculo = $datos['tipoVehiculo'];
        $conductorViaje = $datos['conductorViaje'];
        $pasajeroViaje = $datos['pasajeroViaje'];
        $partidaViaje = $datos['partidaViaje'];
        $destinoViaje = $datos['destinoViaje'];
        $tipoJornada = $datos['tipoJornada'];
        $motivoViaje = $datos['motivoViaje'];
        $horaInicia = $datos['horaInicia'];
        $horaTermino = $datos['horaTermino'];
        $horaIniciaExtencion = $datos['horaInicioExtencion'];
        $horaTerminoExtencion = $datos['horaTerminoExtencion'];
        $unidadViaje = $datos['unidadViaje'];
        $rutConductor = $datos['rutConductor'];
        $chkExtension1 = $datos['chkExtension'];
        $respaldoMotivo = $datos['respaldoMotivo'];




        /*echo $conductorViaje;
        exit;*/
        $conductorViaje = mb_convert_case($conductorViaje, MB_CASE_UPPER, 'UTF-8');



        $unidadViaje = strtoupper($unidadViaje);

    }

    if ($tipoVehiculo == '1') {
        $marca1 = 'selected';
    } else if ($tipoVehiculo == '2') {
        $marca2 = 'selected';
    } else if ($tipoVehiculo == '3') {
        $marca3 = 'selected';
    }

    if ($destinoViaje == 'Comuna de Quillota') {
        $ComunadeQuillota = 'selected';
    } else if ($destinoViaje == 'Provincia de Quillota') {
        $ProvinciadeQuillota = 'selected';
    } else if ($destinoViaje == 'Vina del mar') {
        $Vinadelmar = 'selected';
    } else if ($destinoViaje == 'Valparaiso') {
        $Valparaiso = 'selected';
    } else if ($destinoViaje == 'Region Metropolitana') {
        $RegionMetropolitana = 'selected';
    }else if ($destinoViaje == 'Provincia Marga Marga') {
        $ProvinciaMargaMarga = 'selected';
    }else if ($destinoViaje == 'Provincia Petorca') {
        $ProvinciaPetorca = 'selected';
    }else if($destinoViaje == 'San Felipe'){
        $SanFelipe = 'selected';
    }






} else {
    header('Location: listado-viajes.php?noViaje=true');
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Viaje</title>
    <link rel="icon" href="../dist/img/ss-vina-quillota.png" type="image/icon type">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

    <script src="../sdo-canvasdraw1/jquery.min.js"></script>
    <script src="../sdo-canvasdraw1/signature_pad.js"></script>
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                var chkExtension = $('#chkExtensionNuevo').val();

                console.log(chkExtension)

                if (unidadMovilizacion === "" || unidadMovilizacion === null) {
                    document.getElementById("chkExtension").checked = false;
                    document.getElementById('chkExtension').readOnly = false;
                    document.getElementById("chkExtension").disabled = true;
                    document.getElementById('horaInicioExtencion').readOnly = false;
                    document.getElementById("horaInicioExtencion").disabled = true;
                    document.getElementById('horaTerminoExtencion').readOnly = false;
                    document.getElementById("horaTerminoExtencion").disabled = true;
                    document.getElementById("divChkExtension").style.display = "none";
                    document.getElementById("divInicioExtension").style.display = "none";
                    document.getElementById("divTerminoExtension").style.display = "none";


                }else{
                    if (unidadMovilizacion !== "HOSPITALIZACION DOMICILIARIA") {

                        document.getElementById("chkExtension").checked = false;
                        document.getElementById('chkExtension').readOnly = false;
                        document.getElementById("chkExtension").disabled = true;
                        document.getElementById('horaInicioExtencion').readOnly = false;
                        document.getElementById("horaInicioExtencion").disabled = true;
                        document.getElementById('horaTerminoExtencion').readOnly = false;
                        document.getElementById("horaTerminoExtencion").disabled = true;
                        document.getElementById("divChkExtension").style.display = "none";
                        document.getElementById("divInicioExtension").style.display = "none";
                        document.getElementById("divTerminoExtension").style.display = "none";
                    }else{
                        if(chkExtension > 0){
                            document.getElementById("chkExtension").checked = true;
                            document.getElementById("chkExtension").disabled = false;
                            document.getElementById("horaInicioExtencion").disabled = false;
                            document.getElementById("horaTerminoExtencion").disabled = false;
                            document.getElementById("divChkExtension").style.display = "";
                            document.getElementById("divInicioExtension").style.display = "";
                            document.getElementById("divInicioExtension").style.display = "";
                            document.getElementById("divTerminoExtension").style.display = "";
                        }else{
                            document.getElementById("divChkExtension").style.display = "block";  // o "" si quieres restaurar el valor original
                            document.getElementById("chkExtension").disabled = false;
                            document.getElementById("chkExtension").checked = false;
                            document.getElementById("divInicioExtension").style.display = "none";
                            document.getElementById("divTerminoExtension").style.display = "none";
                            document.getElementById("horaInicioExtencion").disabled = false;
                            document.getElementById("horaTerminoExtencion").disabled = false;

                        }
                    }
                }

            };
        });

    </script>

    <script>
        function selectNit(e) {
            var nit =  e.target.selectedOptions[0].getAttribute("data-nit")
            document.getElementById("nit").value = nit;
        }
    </script>
    <script>
        $(function () {
            $('.fantasma').change(function () {
                if (!$(this).prop('checked')) {
                    $('#divInicioExtension').hide();
                    $('#divTerminoExtension').hide();
                    $('#parrafo').hide();

                } else {
                    $('#divInicioExtension').show();
                    $('#divTerminoExtension').show();
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
        <br>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- general form elements -->
                <div class="card card-warning">
                    <div class="card-header">
                        <h1 class="card-title"> <b> Editar Viaje - N°: <?php echo $numeroViaje ?></b></h1>
                    </div>
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-12">
                            <!-- /.card-header -->
                            <div class="card-body">
                                <a href="listado-viajes-vamoos-spa.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp; <b>Volver Atras</b> </a>
                                <br>
                                <br>

                                <div class="row">
                                    <div class="col-12">
                                        <input id="chkExtensionNuevo" type="hidden" value="<?php echo $chkExtension1?>">

                                        <form action="../sdo-funciones/guardar-modificacion-viaje-movilizacion-vamoos-spa.php" method="POST" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label for="exampleInputCodigoTarjeta" id="mensajeCorro" style="font-size:12px">Fecha de Ingreso a la Plataforma:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                                        </div>
                                                        <input type="datetime-local"  class="form-control form-control-sm" required id="fechaIngresoPlata" value="<?php echo $fechaIngresoPlata?>" name="fechaIngresoPlata">

                                                    </div>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Numero Boleta:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm" readonly required id="numeroViaje" name="numeroViaje" value="<?php echo $numeroViaje ?>">
                                                        <p id="mensaje"></p>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Fecha:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                        </div>
                                                        <input type="date" required class="form-control form-control-sm" id="fechaViaje" name="fechaViaje" value="<?php echo $fechaViaje ?>">
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Patente:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-car"></i></span>
                                                        </div>
                                                        <select class="form-control form-control-sm" required name="patenteVehiculo" id="patenteVehiculo">
                                                            <option value="" disabled>Seleccione Patente...</option>
                                                            <?php

                                                            $sqlPatentes = "SELECT numPatente FROM patentes_movilizacion ORDER BY numPatente ASC";

                                                            $resPatentes = mysqli_query($conexion, $sqlPatentes);

                                                            while ($rowPatentes = $resPatentes->fetch_assoc()) {

                                                                $numPatente = mb_convert_case($rowPatentes['numPatente'], MB_CASE_UPPER, 'UTF-8');

                                                                if ($numPatente == $patenteVehiculo) {
                                                                    echo '<option value="' . $numPatente . '" selected>' . $numPatente . '</option>';
                                                                } else {
                                                                    echo '<option value="' . $numPatente . '">' . $numPatente . '</option>';
                                                                }
                                                            }

                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Tipo Vehiculo:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-car"></i></span>
                                                        </div>
                                                        <select type="text" name="tipoVehiculo" class="form-control form-control-sm" required id="tipoVehiculo">
                                                            <option value="" disabled>-- Seleccione --</option>
                                                            <option value="1" <?php echo $marca1 ?>>Sedan/Sub o Camioneta</option>
                                                            <option value="2" <?php echo $marca2 ?>>Van</option>
                                                            <option value="3" <?php echo $marca3 ?>>3/4 Pick Up o camioneta de carga</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Conductor:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                        </div>
                                                        <select class="form-control form-control-sm" required name="conductorViaje" id="conductorViaje" onchange="selectNit(event)">
                                                            <option value="" selected disabled>Seleccione Conductor...</option>
                                                            <?php

                                                            $sqlConductores = "SELECT * FROM conductores_movilizacion WHERE antesConductor = 0 ORDER BY nomConductor ASC";

                                                            $resConductores = mysqli_query($conexion, $sqlConductores);

                                                            while ($rowConductores = $resConductores->fetch_assoc()) {


                                                                $nomConductor = mb_convert_case($rowConductores['nomConductor'], MB_CASE_UPPER, 'UTF-8');
                                                                $rutConductor1 = $rowConductores['rutConductor'];
                                                                $idConductor = $rowConductores['idConductor'];

                                                                if($idConductor == $conductorViaje){
                                                                    ?>
                                                                    <option data-nit="<?php echo $rutConductor1?>" value="<?php echo $idConductor?>" selected><?php echo $nomConductor?></option>
                                                                    <?php
                                                                }else{
                                                                    ?>
                                                                    <option data-nit="<?php echo $rutConductor1?>" value="<?php echo $idConductor?>" ><?php echo $nomConductor?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="destino"></div>
                                                <div class="form-group col-md-4">
                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Rut:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm" readonly id="nit" name="rutConductor" value="<?php echo $rutConductor?>">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Pasajero:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm" id="pasajeroViaje" name="pasajeroViaje" value="<?php echo $pasajeroViaje ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <!-- <label for="customFile">Custom File</label> -->
                                                    <label for="exampleInputTareasControlCent" style="font-size:12px;">Pto. Partida:</label><br>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-street-view"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm" id="partidaViaje" name="partidaViaje" value="<?php echo $partidaViaje ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Unidad:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                        </div>
                                                        <select class="form-control form-control-sm" required name="unidadMovilizacion" id="unidadMovilizacion">
                                                            <option value="" selected disabled>Seleccione Motivo...</option>
                                                            <?php

                                                            $sqlMotivo = "SELECT nomUnidad FROM unidad_movilizacion_nuevo ORDER BY nomUnidad ASC";

                                                            $resMotivo = mysqli_query($conexion, $sqlMotivo);

                                                            while ($rowMotivo = $resMotivo->fetch_assoc()) {

                                                                $nomUnidad = mb_convert_case($rowMotivo['nomUnidad'], MB_CASE_UPPER, 'UTF-8');

                                                                if ($nomUnidad == $unidadViaje) {
                                                                    echo '<option value="' . $nomUnidad . '" selected>' . $nomUnidad . '</option>';
                                                                } else {
                                                                    echo '<option value="' . $nomUnidad . '">' . $nomUnidad . '</option>';
                                                                }
                                                            }

                                                            ?>
                                                            <option value="Otro">OTRO</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div id="visualizarOtraUnidad" name="visualizarOtraUnidad" class="form-group col-md-4">

                                                </div>
                                            </div>
                                            <div class="row">

                                                <div class="form-group col-md-4">
                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Destino:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                                        </div>
                                                        <select name="destinoViaje" required id="destinoViaje" class="form-control form-control-sm">
                                                            <option value="" disabled>-- Seleccione --</option>
                                                            <option value="Comuna de Quillota" <?php echo $ComunadeQuillota ?>>Comuna de Quillota</option>
                                                            <option value="Provincia de Quillota" <?php echo $ProvinciadeQuillota ?>>Provincia de Quillota</option>
                                                            <option value="Vina del mar" <?php echo $Vinadelmar ?>>Viña del mar</option>
                                                            <option value="Valparaiso" <?php echo $Valparaiso ?>>Valparaiso</option>
                                                            <option value="Region Metropolitana" <?php echo $RegionMetropolitana ?>>Región Metropolitana</option>
                                                            <option value="Provincia Marga Marga" <?php echo $ProvinciaMargaMarga ?>>Provincia Marga-Marga</option>
                                                            <option value="Provincia Petorca" <?php echo $ProvinciaPetorca ?>>Provincia Petorca</option>
                                                            <option value="San Felipe" <?php echo $SanFelipe ?>>San Felipe</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Hora Inicio:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-hourglass-half"></i></span>
                                                        </div>
                                                        <input type="time" required class="form-control form-control-sm" id="horaInicio" name="horaInicio" value="<?php echo $horaInicia; ?>" placeholder="Hora Inicio">
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Hora Termino:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-hourglass-half"></i></span>
                                                        </div>
                                                        <input type="time" required class="form-control form-control-sm" id="horaTermino" name="horaTermino" value="<?php echo $horaTermino; ?>">
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">

                                                <div class="form-group col-md-4">
                                                    <!-- <label for="customFile">Custom File</label> -->
                                                    <label for="exampleInputTareasControlCent" style="font-size:12px;">Subir Archivos:</label><br>
                                                    <div class="custom-file">
                                                        <input type="file" multiple class="custom-file-input" id="archivosViajes" name="archivosViajes">
                                                        <label class="custom-file-label" for="customFile" style="font-size:12px;"><b>Seleccionar Archivos...</b> </label>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
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

                                                <div class="form-group col-md-4" id="divInicioExtension" value="divInicioExtension">
                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Hora Inicio Extencion:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-hourglass-half"></i></span>
                                                        </div>
                                                        <input type="time" required class="form-control form-control-sm" id="horaInicioExtencion" name="horaInicioExtencion" value="<?php echo $horaIniciaExtencion?>">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-4" id="divTerminoExtension" value="divTerminoExtension">
                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Hora Termino Extencion:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-hourglass-half"></i></span>
                                                        </div>
                                                        <input type="time" required class="form-control form-control-sm" id="horaTerminoExtencion" name="horaTerminoExtencion" value="<?php echo $horaTerminoExtencion?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="exampleInputTareasControlCent" style="font-size:12px;">Motivos:</label><br>
                                                    <textarea class="form-control" name="motivosCambio" id="motivosCambio"><?php echo $motivoViaje?></textarea>
                                                    <br>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="exampleInputTareasControlCent" style="font-size:12px;">Respaldo:</label><br>
                                                    <textarea class="form-control" name="respaldoCambio" id="respaldoCambio"><?php echo $respaldoMotivo?></textarea>
                                                    <br>
                                                </div>

                                            </div>
                                            <div class="row justify-content-center ">
                                                <input type="hidden" value="<?php echo $idViaje ?>" name="idViaje">
                                                <button class="btn btn-success " type="submit"> <b> Guardar Cambios</b></button>
                                            </div>

                                        </form>

                                    </div>
                                </div>
                            </div><!-- .tabla-responsive -->
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!--/.col  -->
    </div>
    <!-- /.row -->
    </section>
    <!-- /.content -->
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

<script src="js/dark-mode.js"></script>
<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- bs-custom-file-input -->
<script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.2/js/dataTables.bootstrap4.min.js"></script>
<!-- Page specific script -->
<script>
    $(function() {
        bsCustomFileInput.init();
    });
</script>
<script>
    $(document).ready(function() {
        $('#gasto_equipo').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "scrollX": true,
            "autoWidth": false,
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
        });
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
                title: '¿Eliminar el registro de este gasto?',
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
                        url: '../sdo-funciones/eliminar-gasto-hoja-vida.php',
                        type: 'POST',
                        data: dataString,
                        success: function(response) {
                            console.log('eliminado.. ' + dataString);
                            Swal.fire({
                                icon: 'success',
                                title: 'Registro del gasto eliminado correctamente',
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
</body>

</html>