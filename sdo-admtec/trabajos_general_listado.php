<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];
$idDepartamento = $_SESSION['idDepartamento'];
$userUsuario = $_SESSION['userUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);
$nomUsuario = $_SESSION['nomUsuario'];



date_default_timezone_set('America/Santiago');

if(isset($_POST['selectOT'])){
    $selectOT1 = $_POST['selectOT'];
    if ($selectOT1 > 0) {
        ini_set("memory_limit", "1256M");
        $zip = new ZipArchive();
        $dir = "../sdo-administrador/archivos-OT-firmadas/OT-". $selectOT1 ."/";
        $dir2 = $dir . "/";
        $rutaFinal = "../sdo-admtec/";
        $archivoZip = "Archivos-OT-" . $selectOT1 . ".zip";

        if ($zip->open($archivoZip, ZIPARCHIVE::CREATE) === true) {
            agregar_zip($dir2, $zip);
            $zip->close();
            if (file_exists($rutaFinal . "/" . $archivoZip)) {
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
            }
        }
    }
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Trabajos</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- AdminLTE css -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" />
    <!-- include summernote css-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- include summernote js-->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <style>
        .reset {
            all: revert;
        }

        #cargaCombustible,
        #medicionNormal {
            display: none;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function() {
            recargarListTecnicos();
            recargarListTecnicosPersonal();

            $('#departamentoSolicitante').change(function() {
                recargarListTecnicos();


            });

            $('#depaPersonal').change(function() {
                recargarListTecnicosPersonal();

            });

            function recargarListTecnicos() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-tecnicos/cargarTecnicosYJefes.php",
                    data: "idDepartamento=" + $('#departamentoSolicitante').val(),
                    success: function(r) {

                        if (r == 0) {

                            var cadena = "";

                            cadena = cadena + '<div class="input-group-prepend">';

                            cadena = cadena + '<span class="input-group-text"><i class="fas fa-user"></i></span>';

                            cadena = cadena + '<span class="input-group-text w-100">No Existen Tecnicos en este Departamento.</span>';

                            cadena = cadena + '</div>';

                            $('#htmlSelect').html(cadena);

                            var btnDelCentral = document.getElementById("guardarRecepcion");

                            btnDelCentral.removeAttribute("enabled", "");

                            btnDelCentral.setAttribute("disabled", "");

                        } else if (r == "error" || r == "ERROR" || r == "Error") {

                            var cadena = "";

                            cadena = cadena + '<div class="input-group-prepend">';

                            cadena = cadena + '<span class="input-group-text"><i class="fas fa-user"></i></span>';

                            cadena = cadena + '<span class="input-group-text w-100">Error al Obtener Datos.</span>';

                            cadena = cadena + '</div>';

                            $('#htmlSelect').html(cadena);

                            var btnDelCentral = document.getElementById("guardarRecepcion");

                            btnDelCentral.removeAttribute("enabled", "");

                            btnDelCentral.setAttribute("disabled", "");

                        } else {
                            $('#htmlSelect').html(r);

                            var btnDelCentral = document.getElementById("guardarRecepcion");

                            btnDelCentral.removeAttribute("disabled", "");

                            btnDelCentral.setAttribute("enabled", "");

                        }

                    }
                });

            };

            function recargarListTecnicosPersonal() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-tecnicos/cargarTecnicosPersonal.php",
                    data: "idDepartamento=" + $('#depaPersonal').val(),
                    success: function(r) {

                        if (r == 0) {

                            var cadena = "";

                            cadena = cadena + '<div class="input-group-prepend">';

                            cadena = cadena + '<span class="input-group-text"><i class="fas fa-user"></i></span>';

                            cadena = cadena + '<span class="input-group-text w-100">No Existen Tecnicos en este Departamento.</span>';

                            cadena = cadena + '</div>';

                            $('#htmlPersonal').html(cadena);

                            var btnDelCentral = document.getElementById("guardarRecepcion");

                            btnDelCentral.removeAttribute("enabled", "");

                            btnDelCentral.setAttribute("disabled", "");

                        } else if (r == "error" || r == "ERROR" || r == "Error") {

                            var cadena = "";

                            cadena = cadena + '<div class="input-group-prepend">';

                            cadena = cadena + '<span class="input-group-text"><i class="fas fa-user"></i></span>';

                            cadena = cadena + '<span class="input-group-text w-100">Error al Obtener Datos.</span>';

                            cadena = cadena + '</div>';

                            $('#htmlPersonal').html(cadena);

                            var btnDelCentral = document.getElementById("guardarRecepcion");

                            btnDelCentral.removeAttribute("enabled", "");

                            btnDelCentral.setAttribute("disabled", "");

                        } else {
                            $('#htmlPersonal').html(r);

                            var btnDelCentral = document.getElementById("guardarRecepcion");

                            btnDelCentral.removeAttribute("disabled", "");

                            btnDelCentral.setAttribute("enabled", "");

                        }

                    }
                });

            };


        });
    </script>

    <script>
        $(document).ready(function() {


            // Show div by removing inline display none style rule
            $(".show-realizar").click(function() {
                $("#rowMenu2").show();
                $("#rowMenu1").hide();
                $("#listarAC").show();
                $("#listarAC2").show();
                $("#registrarAC").hide();
                $("#registrarAC2").hide();
            });

            // Show div by removing inline display none style rule
            $(".show-listar").click(function() {
                $("#rowMenu2").hide();
                $("#rowMenu1").show();
                $("#listarAC").hide();
                $("#listarAC2").hide();
                $("#registrarAC").show();
                $("#registrarAC2").show();
            });
        });
    </script>
    <script>
        function DoCheckUncheckDisplay(d, dchecked, dunchecked) {
            if (d.checked == true) {
                document.getElementById(dchecked).style.display = "block";
                document.getElementById(dunchecked).style.display = "none";
            } else {
                document.getElementById(dchecked).style.display = "none";
                document.getElementById(dunchecked).style.display = "block";
            }
        }
    </script>
    <script>
        $(document).ready(function() {
            $('textarea').on('keyup keypress', function() {
                $(this).height(0);
                $(this).height(this.scrollHeight);
            });
        });
    </script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<!-- Site wrapper -->
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/admtec-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Listado de Trabajos</h4>
                    </div>
                    <?php
                    if(isset($_GET['exitoSinArchivo'])){
                        ?>
                        <script>
                            Swal.fire(
                                'Exito!',
                                'Orden de Trabajo ingresada correctamente sin archivo adjunto!',
                                'success'
                            )
                        </script>
                        <?php
                    }

                    if (isset($_GET['exitoConArchivo'])) {
                        ?>
                        <script>
                            Swal.fire(
                                'Exito!',
                                'Orden de Trabajo ingresada correctamente junto con archivo adjunto!',
                                'success'
                            )
                        </script>
                        <?php
                    }

                    if(isset($_GET['errorEnvioArchivo'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Algo Salio Mal',
                                text: 'El Archivo Adjunto No Se Subio De Manera Correcta!',
                            })
                        </script>
                        <?php
                    }

                    if(isset($_GET['errorArchivo'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Algo Salio Mal',
                                text: 'Algo fallo con la subida del Archivo Adjunto.Reintente!',
                            })
                        </script>
                        <?php

                    }

                    if(isset($_GET['archivoNoHabilitado'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'El archivo no se encuentra permitido.!'
                            })
                        </script>
                        <?php

                    }

                    if(isset($_GET['error'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'A ocurrido un error inesperado.Porfavor reintente'
                            })
                        </script>
                        <?php

                    }

                    if(isset($_GET['errorTecnicoTG'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: '¡No se puede colocar el técnico dos veces.!'
                            })
                        </script>
                        <?php

                    }

                    if(isset($_GET['error4tg'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Algunos datos no se han enviado correctamente.Reintente'
                            })
                        </script>
                        <?php

                    }

                    if(isset($_GET['errorEnlosDatos'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Algunos Datos No Se Encuentran Llenados.Reintente'
                            })
                        </script>
                        <?php
                    }

                    if(isset($_GET['exito'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'sucess',
                                title: 'Exito',
                                text: 'Se ha ingresado la respuesta con exito'
                            })
                        </script>
                        <?php
                    }

                    if(isset($_GET['exitoGenerar'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Exito',
                                text: 'Se ha ingresado el avance con exito'
                            })
                        </script>
                        <?php
                    }
                    if(isset($_GET['exitoGenerarBMS'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Exito',
                                text: 'Se ha la HT de BMS con exito'
                            })
                        </script>
                        <?php
                    }
                    if(isset($_GET['exitoGenerarRONDAS'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Exito',
                                text: 'Se ha la HT de Rondas con exito'
                            })
                        </script>
                        <?php
                    }
                    if(isset($_GET['errorFolioBMS'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error en el folio de HT del bms'
                            })
                        </script>
                        <?php
                    }
                    if(isset($_GET['errorGenerarBMS'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al crear la HT de BMS'
                            })
                        </script>
                        <?php
                    }
                    if(isset($_GET['errorOrdenRONDAS'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al generar la HT de las rondas.'
                            })
                        </script>
                        <?php
                    }

                    if(isset($_GET['errorFolioRONDAS'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error en el folio de HT de las rondas'
                            })
                        </script>
                        <?php
                    }
                    if(isset($_GET['errorGenerarCA'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al crear la HT de Control de Acceso'
                            })
                        </script>
                        <?php
                    }
                    if(isset($_GET['exitoGenerarCA'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Exito',
                                text: 'Se ha creado la HT de Control de Acceso'
                            })
                        </script>
                        <?php
                    }
                    if(isset($_GET['exitoGenerarCCTV'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Exito',
                                text: 'Se ha creado la HT de CCTV'
                            })
                        </script>
                        <?php
                    }
                    if(isset($_GET['errorGenerarCCTV'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al crear la HT de CCTV'
                            })
                        </script>
                        <?php
                    }
                    if(isset($_GET['errorFolioCCTV'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al crear la HT de CCTV'
                            })
                        </script>
                        <?php
                    }
                    if(isset($_GET['exitoGenerarLLE'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Exito',
                                text: 'Se ha generado la HT de LLE con exito'
                            })
                        </script>
                        <?php
                    }
                    if(isset($_GET['errorFolioLLE'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al crear la HT de LLE'
                            })
                        </script>
                        <?php
                    }

                    if(isset($_GET['errorGenerarLLE'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al crear la HT de LLE'
                            })
                        </script>
                        <?php
                    }
                    if(isset($_GET['exitoGenerarOTROS'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Exito',
                                text: 'Exito al crear la HT de Otros'
                            })
                        </script>
                        <?php
                    }
                    if(isset($_GET['errorFolioOTROS'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al crear la HT de otros'
                            })
                        </script>
                        <?php
                    }
                    if(isset($_GET['errorGenerarOTROS'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al crear la HT de otros'
                            })
                        </script>
                        <?php
                    }
                    if(isset($_GET['modificacionok'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Exito',
                                text: 'Se ha modificado correctamente'
                            })
                        </script>
                        <?php

                    }
                    ?>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>


        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <!-- Timelime example  -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">

                                <div class="col-lg-3 col-md-6 col-12 float-right" id="volverAtras2" name="volverAtras2">
                                    <!-- small box -->
                                    <a href="index.php" style="float: right;" class="btn bg-gradient-red float-right btn-md text-white"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                </div>
                                <br>
                                <br>

                            </div>

                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!--
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body" style="text-align:center;">

                                <div class="row text-center" style="display: flex; justify-content:center;">

                                                                        <div id="registrarAC2" name="registrarAC2" class="col-lg-3 col-md-6 col-12 text-center">
                                                                            <button id="registrarAC" style="color: white" name="registrarAC" class="btn btn-block bg-gradient-info btn-md show-realizar text-black text-center"><i class="nav-icon fas fa-book"></i><br>Registrar Orden de Trabajo</button>
                                    </div>

                                    &nbsp;
                                    <div class="col-lg-3 col-md-6 col-12 text-center" id="listarAC2" style="display:none;" name="listarAC2">

                                        <button id="listarAC" style="display:none; color: white" name="listarAC" class="btn btn-block bg-gradient-info btn-md text-black text-center show-listar"><i class="nav-icon fas fa-book"></i><br>Ver Listado</button>
                                    </div>

                                    <div class="col-lg-3 col-md-6 col-12 text-center">
                                        <button type="button" style="color: whitesmoke" class="btn bg-gradient-success btn-block btn-md " data-toggle="modal" data-target="#exampleModalSubir"><i class="fas fa-arrow-up"></i><br> Subir OT y AST Firmada</button>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-12 text-center">
                                        <button type="button" style="color: whitesmoke" class="btn bg-gradient-danger btn-block btn-md" data-toggle="modal" data-target="#exampleModalDescargar" ><i class="fas fa-download"></i><br> Descargar OT Firmadas</button>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>
                </div>
                -->


                <?php

                if (isset($_GET['exitoAgregarAC'])) {
                    ?>
                    <script>
                        Swal.fire({
                            title: 'Medici&oacute;n de Combustible Agregada con &Eacute;xito.',
                            confirmButtonText: 'Cerrar',
                            icon: 'success',
                        });
                    </script>
                    <?php
                }

                if (isset($_GET['errorAgregarAC'])) {
                    ?>
                    <script>
                        Swal.fire({
                            title: 'Error al Ingresar Medici&oacute;n de Combustible.',
                            confirmButtonText: 'Cerrar',
                            icon: 'error',
                        });
                    </script>
                    <?php
                }

                if(isset($_GET['otyaexiste'])){
                    ?>
                    <script>
                        Swal.fire({
                            title: 'El archivo adjunto ya existe. Por favor cambie el nombre del archivo',
                            confirmButtonText: 'Cerrar',
                            icon: 'info',
                        });
                    </script>
                    <?php
                }
                ?>

                <div class="row" id="rowMenu1" name="rowMenu1">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>1.- Listado de Trabajos</h5>
                                <br>
                                <hr>
                                <div class="table-responsive">
                                    <table style="font-size:80%" id="example2" class="table table-bordered cell-border">
                                        <thead class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha de Trabajo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Folio de Trabajo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Turno de Trabajo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Solicitante</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Técnico Responsable</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Estado</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Informacion</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Detalles</center>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php
                                        $queryTG = "SELECT * FROM trabajos_general WHERE estadoTG != 'Trabajo Terminado' AND departamento = $idDepartamento ORDER BY fechaTG DESC";

                                        $resTG = mysqli_query($conexion, $queryTG);

                                        $numTG = 1;

                                        $colorTG = "";

                                        while ($rowTG = $resTG->fetch_assoc()) {

                                            $fechaTG = $rowTG['fechaTG'];

                                            $turnoTG = $rowTG['turnoTG'];
                                            $turnoTG = trim($turnoTG);

                                            $solicitanteTG = $rowTG['solicitanteTG'];
                                            $solicitanteTG = trim($solicitanteTG);

                                            $idTG = $rowTG['idTG'];
                                            $idTG = trim($idTG);

                                            $estadoTG = trim($rowTG['estadoTG']);

                                            $tecnico1 = $rowTG['tecnico1'];

                                            $folioTG = $rowTG['folioTG'];

                                            $contarObs = contarObs($idTG);
                                            $contarPanol = contarPanol($idTG);
                                            $contarRechazadoPanol = contarRechazadoPanol($idTG);
                                            $contarAceptadoPanol = contarAceptadoPanol($idTG);
                                            //$contarNoPanol = contarNoPanol($idTG);
                                            //$contarMaterial = contarMaterial($idTG);


                                            if($contarPanol > 0){
                                                $colorTR = "background-color: blue; color:white";
                                            }else if($contarAceptadoPanol > 0){
                                                $colorTR = "background-color: green; color:white";
                                            }else if($contarRechazadoPanol > 0) {
                                                $colorTR = "background-color: red; color:white";
                                            }else{
                                                $colorTR = "";
                                            }

                                            ?>

                                            <tr style="<?php echo $colorTR?>">
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <?php if ($fechaTG == 0) {
                                                        echo "No registra fecha";
                                                    } else {

                                                        $registrofechaTG = date('d/m/Y', strtotime(str_replace('/', '-', $fechaTG)));
                                                        $registroHoraTG = date('H:i', strtotime($fechaTG));

                                                        // echo $registrofechaAC . ' a las ' . $registroHoraMC.' hrs.';

                                                        echo $registrofechaTG . ' a las ' . $registroHoraTG . ' hrs';
                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <?php
                                                    echo $folioTG;
                                                    ?>

                                                </td>
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <?php if (empty($turnoTG)) {
                                                        echo "No registra Turno";
                                                    } else {

                                                        echo $turnoTG;
                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <?php if (empty($solicitanteTG)) {
                                                        echo "No registra Solicitante";
                                                    } else {

                                                        echo $solicitanteTG;
                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php
                                                    echo $tecnico1;
                                                    ?>
                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php if (empty($estadoTG)) {
                                                        echo "No registra Estado";
                                                    } else {
                                                        if($estadoTG == 'trabajoTerminado'){
                                                            $estado = "Trabajo Terminado";
                                                            echo $estado;
                                                        }else{
                                                            echo $estadoTG;
                                                        }

                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php
                                                    if($contarPanol > 0){
                                                        ?>
                                                        <center>
                                                            <?php echo 'Funcionario asignado ha solicitado materiales y/o herramientas'?>
                                                        </center>
                                                        <?php
                                                    }else if($contarAceptadoPanol > 0){
                                                        ?>
                                                        <center>
                                                            <?php echo 'La solicitud hacia pañol se ha entregado con exito'?>
                                                        </center>
                                                        <?php
                                                    }else if($contarRechazadoPanol > 0){
                                                        ?>
                                                        <center>
                                                            <?php echo 'La solicitud se ha rechazado por personal de pañol'?>
                                                        </center>
                                                        <?php
                                                    }else{
                                                        ?>
                                                        <center>
                                                            <?php echo 'Sin Informacion';?>
                                                        </center>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td style="vertical-align:middle;">
                                                    <center>
                                                        <?php
                                                        if ($idTG == 0 || $idTG == "" || $idTG == " " || $idTG == "  ") {
                                                            ?>
                                                            <center><?php echo "Error al Cargar Datos."; ?></center>
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <form style="margin: 0; text-align: center;" class="" action="detalle_ordenes_trabajo.php" method="post">

                                                                <input type="hidden" name="idTG" value="<?php echo $idTG; ?>">
                                                                <button type="submit" id="inputMozi" class="btn btn-block btn-info"><i class="fa fa-eye"></i></button>

                                                            </form>
                                                            <?php
                                                        }
                                                        ?>
                                                    </center>
                                                </td>


                                            </tr>

                                            <?php
                                        }
                                        ?>

                                        </tbody>
                                        <tfoot class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha de Trabajo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Folio de Trabajo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Turno de Trabajo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Solicitante</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Técnico Responsable</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Estado</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Informacion</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Detalles</center>
                                            </th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>

                <div class="row" id="rowMenu2" name="rowMenu2" style="display:none;">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>2.- Registrar Trabajo General</h5>
                                <br>
                                <form action="../sdo-funciones/agregar-trabajo-general-admin.php" method="POST" enctype="multipart/form-data">
                                    <fieldset class="border p-3">
                                        <legend class="w-auto">&nbsp;Informaci&oacute;n del Trabajo General:&nbsp;&nbsp;</legend>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <br>
                                                <label for="exampleInputTurno">Fecha de Creacion: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                    </div>
                                                    <?php
                                                    date_default_timezone_set('America/Santiago');
                                                    ?>
                                                    <input type="datetime" class="form-control" readonly="readonly" name="fechaOrden" id="fechaOrden" value="<?php echo date('Y-m-d H:i'); ?>" >

                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <br>
                                                <label for="exampleInputTurno">Fecha de Programacion del Trabajo: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                    </div>
                                                    <?php
                                                    date_default_timezone_set('America/Santiago');
                                                    ?>
                                                    <input type="date" required class="form-control" name="fechaProgramado" id="fechaProgramado">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Ticket Asociado: </label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-ticket-alt"></i></span>
                                                    </div>
                                                    <input type="number" class="form-control"  name="ticketAsociado" id="ticketAsociado" placeholder="Ingrese número del ticket">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputCodigoTarjeta">Tipo de Trabajo:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-th-list"></i></span>
                                                    </div>
                                                    <select name="tipoTrabajo" class="form-control"  id="tipoTrabajo">
                                                        <option value="" disabled selected>-- Seleccione --</option>
                                                        <option value="MPE">MPE - Mantenimiento Preventivo Externo</option>
                                                        <option value="MRE">MRE - Mantenimiento Reparativo Externo</option>
                                                        <option value="MPI">MPI - Mantenimiento Preventivo Interno</option>
                                                        <option value="MRI">MRI - Mantenimiento Reparativo Interno</option>
                                                        <option value="OM">OM - Obras Menores</option>
                                                        <option value="IS">IS - Inspección/Supervisión</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Persona Solicitante: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" required name="personaSolicitante" id="personaSolicitante" placeholder="Ingrese a quien creará la orden de trabajo" value="<?php echo $nomUsuario?>">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Turno: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-users"></i></span>
                                                    </div>
                                                    <select  name="turno" id="turno" class="form-control" required>
                                                        <option value="" disabled selected>Seleccione...</option>
                                                        <option value="Personal Diurno">Personal Diurno</option>
                                                        <option value="Personal Nocturno">Personal Nocturno</option>
                                                    </select>
                                                    <!--<input type="hidden" id="nit2" name="nit2">-->

                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Departamentos: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                    </div>
                                                    <select name="departamentoSolicitante" id="departamentoSolicitante" onchange="visible(this.value)" class="form-control" required>
                                                        <option value="" selected disabled>Seleccione</option>
                                                        <?php
                                                        $sqlDepa = "SELECT * FROM departamentos WHERE idDepartamento NOT IN (9) AND idDepartamento NOT IN (13) AND idDepartamento NOT IN (11) ORDER BY nomDepartamento ASC";
                                                        $resDepa = mysqli_query($conexion,$sqlDepa);
                                                        while($rowDepa = $resDepa->fetch_assoc()){
                                                            $idDepa = $rowDepa['idDepartamento'];
                                                            $nomDepa = $rowDepa['nomDepartamento'];
                                                            ?>
                                                            <option value="<?php echo $idDepa?>"><?php echo $nomDepa?></option>
                                                            <?php
                                                        }

                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Personal:<code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <!-- <span class="input-group-text"><i class="fas fa-user"></i></span> -->
                                                    </div>
                                                    <div style="width: 100%;" id="htmlSelect" name="htmlSelect"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">

                                            <label for="exampleInputTurno">Adjuntar Trabajo General: </label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                </div>
                                                <input type="file" class="form-control" id="archivoAdjunto" name="archivoAdjunto">
                                            </div>

                                        </div>
                                        <div id="bms" style="display: none">
                                            <label id="tb"  for="exampleInputTurno">Detalle de los Trabajos a realizar en BMS:
                                                <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div id="divtbpen"  class="input-group-prepend">
                                                    <span id="tbpen"  class="input-group-text"><i class="fas fa-pen"></i></span>
                                                </div>
                                                <textarea  class="form-control" name="detallebms"
                                                           id="detallebms" rows="5"></textarea>
                                            </div>
                                        </div>
                                        <div id="cctv" style="display: none">
                                            <label id="tcctv" for="exampleInputTurno">Detalle de los Trabajos a realizar en CCTV:
                                                <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div id="tbcctv" class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                </div>
                                                <textarea  class="form-control" name="detalleTurnoCCTV"
                                                           id="detalleTurnoCCTV"
                                                           rows="5"></textarea>
                                            </div>
                                        </div>
                                        <div id="lle" style="display: none">
                                            <label id="tlle"  for="exampleInputTurno">Detalle de los Trabajos a realizar en LLE:
                                                <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div id="tllepen" class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                </div>
                                                <textarea  class="form-control" name="detalleTurnoLLE"
                                                           id="detalleTurnoLLE"
                                                           rows="5"></textarea>
                                            </div>
                                        </div>
                                        <div id="ca" style="display: none">
                                            <label id="tca"  for="exampleInputTurno">Detalle de los Trabajos a realizar en Control
                                                de Acceso: <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div id="tcapen" class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                </div>
                                                <textarea class="form-control" name="detalleTurnoCA"
                                                          id="detalleTurnoCA" rows="5"></textarea>
                                            </div>
                                        </div>
                                        <div id="rondas" style="display: none">
                                            <label id="trondas"  for="exampleInputTurno">Detalle de los Trabajos a realizar en Rondas:
                                                <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div id="trondaspen" class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                </div>
                                                <textarea class="form-control" name="detalleTurnoRondas"
                                                          id="detalleTurnoRondas"
                                                          rows="5"></textarea>
                                            </div>
                                        </div>
                                        <div id="otros" style="display: none">
                                            <label id="totros"  for="exampleInputTurno">Detalle de Otros tipos de trabajos:
                                                <code>(Limpieza,Ordenar,etc )</code></label>
                                            <div class="input-group mb-3">
                                                <div id="totrospen" class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                </div>
                                                <textarea  class="form-control" name="detalleOtros"
                                                           id="detalleOtros"></textarea>
                                            </div>
                                        </div>
                                        <div id="nocontrol">
                                            <label id="tralizar" for="exampleInputTurno">Detalle los Trabajos a realizar: <code>*</code></label>
                                            <div id="tralizarpen" class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                </div>
                                                <textarea class="form-control" name="detalleTurno" id="detalleTurno"></textarea>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <br>
                                    <div class="form-group">
                                        <input type="hidden" name="nomCarpeta" id="nomCarpeta" value="<?php echo $nomCarpeta?>">
                                        <button type="submit" name="Guardar" id="Guardar" class="btn btn-block btn-success">Guardar</button>
                                        <a href="index.php" class="btn btn-block btn-warning">Volver Atras</a>
                                    </div>
                                </form>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="exampleModalSubir" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-info">
                            <h5 class="modal-title" id="exampleModalLabel">Subir OT Firmada</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="../sdo-funciones/subirOT.php" method="POST" enctype="multipart/form-data">
                            <div class="modal-body text-center">
                                <label>Escoja la OT</label>
                                <select class="form-control form-control-border" id="selectOT" name="selectOT">
                                    <?php

                                        $sql = "SELECT * FROM trabajos_general WHERE estadoTG = 'Trabajo Terminado'";
                                        $res = mysqli_query($conexion,$sql);
                                        ?>
                                        <option value="" selected disabled>Seleccione...</option>
                                        <?php
                                        while($row = $res->fetch_assoc()){
                                            $idTG1 = $row['idTG'];
                                           ?>
                                            <option value="<?php echo $idTG1?>"><?php echo $row['folioTG'].' / Asignado: '. $row['tecnico1']?></option>
                                            <?php
                                        }
                                    ?>
                                </select>
                                <br>
                                <label style="color: indianred">**Adjunte la Orden de Trabajo para Respaldarla**</label>
                                <input type="file" onchange="showSuccessAlert()" required accept="application/pdf" name="archivoAdjunto" id="upload" class="ocultar ">
                                <label for="upload" class="btn btn-block btn-warning"> <i class="fas fa-file-contract"></i> Adjuntar Imagen <i class="fas fa-file-contract"></i></label>

                                <!--<label for="archivoAdjunto" class="btn btn-block btn-warning"> <i class="fas fa-file-contract"></i> Adjuntar OT <i class="fas fa-file-contract"></i></label>-->
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" value="Upload" class="btn btn-primary">Subir la OT</button>
                            </div>
                        </form>



                        <style>
                            .ocultar {
                                display: none;
                            }
                        </style>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="exampleModalDescargar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-gradient-danger">
                            <h5 class="modal-title" id="exampleModalLabel">Descargar OT y AST Firmadas</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="trabajos_general_listado.php" method="POST">
                            <div class="modal-body">
                                <label>Escoja la OT</label>
                                <select class="form-control form-control-border" id="selectOT" name="selectOT">
                                    <?php

                                    $sql = "SELECT * FROM trabajos_general WHERE estadoTG = 'Trabajo Terminado'";
                                    $res = mysqli_query($conexion,$sql);
                                    ?>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <?php
                                    while($row = $res->fetch_assoc()){
                                        $idTG1 = $row['idTG'];
                                        ?>
                                        <option value="<?php echo $idTG1?>"><?php echo $row['folioTG'].' / Asignado: '. $row['tecnico1']?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                <br>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Descargar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /.timeline -->
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

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<script src="js/dark-mode.js"></script>
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
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": false,
            "autoWidth": false,
            "responsive": false,
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
            }

        }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
    });
</script>
<script>
    function visible(valor)
    {
        if(valor == 1){
            document.getElementById('bms').style.display = 'block';
            document.getElementById('lle').style.display = 'block';
            document.getElementById('cctv').style.display = 'block';
            document.getElementById('rondas').style.display = 'block';
            document.getElementById('ca').style.display = 'block';
            document.getElementById('otros').style.display = 'block';
            document.getElementById('nocontrol').style.display = 'none';
        }else{
            document.getElementById('bms').style.display = 'none';
            document.getElementById('lle').style.display = 'none';
            document.getElementById('cctv').style.display = 'none';
            document.getElementById('rondas').style.display = 'none';
            document.getElementById('ca').style.display = 'none';
            document.getElementById('otros').style.display = 'none';
            document.getElementById('nocontrol').style.display = 'block';

        }
    }
</script>

<script type="text/javascript">
    $("input[name='opciones']").change(function() {
        if ($(this).val() == 'medicionN') {
            $('#medicionNormal').show();
            $('#cargaCombustible').hide();
        } else if ($(this).val() == 'cargaC') {
            $('#medicionNormal').hide();
            $('#cargaCombustible').show();
        } else {
            $('#medicionNormal').hide();
            $('#cargaCombustible').hide();
        }
    })
</script>

<script>
    // Función para mostrar la alerta de éxito
    function showSuccessAlert() {
        var fileInput = document.getElementById('upload');
        if (fileInput.files.length > 0) {
            document.getElementById('successAlert').classList.add('show');
        }
    }

    $("#exampleModal").on("hidden.bs.modal", function() {
        // Aquí va el código a disparar en el evento
        document.getElementById('successAlert').classList.remove('show');
        document.getElementById('upload').value = '';
    });

</script>
</body>

</html>