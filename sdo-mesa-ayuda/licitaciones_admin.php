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


if (isset($_POST['folioEnviar'])) {

    $folioEnviar = $_POST['folioEnviar'];

    if (!empty($folioEnviar)) {

        ini_set("memory_limit", "1256M");
        $zip = new ZipArchive();
        $dir = '../sdo-licitacion-adjuntos/'.$folioEnviar;
        $dir2 = $dir . "/";
        $rutaFinal = "../sdo-mesa-ayuda/";
        $archivoZip = "Archivos-" . $folioEnviar . ".zip";

        if ($zip->open($archivoZip, ZIPARCHIVE::CREATE) === true) {
            agregar_zipSC($dir2, $zip);
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
            } else {
                $descargaConfirma .= "N";
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
    <title>Licitaciones</title>

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
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"/>
    <!-- include summernote css-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- include summernote js-->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <style>
        .reset {
            all: revert;
        }

        #cargaCombustible,
        #medicionNormal {
            display: none;
        }

        .select2-container .select2-selection--single {
            height: calc(2.25rem + 2px);
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
        }

    </style>
    <script type="text/javascript">
        $(document).ready(function () {
            recargarListTecnicos();
            recargarListTecnicosPersonal();
            recargarNomDepartamento();

            $('#servicios').change(function () {
                recargarNomDepartamento();
            });

            $('#departamentoSolicitante').change(function () {
                recargarListTecnicos();


            });

            $('#depaPersonal').change(function () {
                recargarListTecnicosPersonal();

            });

            function recargarNomDepartamento() {
                $.ajax({
                    type: "POST",
                    url: "sdo-datos-servicios/habilitarIngresoServicio.php",
                    data: "idServicio=" + $('#servicios').val(),
                    success: function (r) {
                        $('#visualizarNomServicio').html(r);
                    }
                });
            };

            function recargarListTecnicos() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-tecnicos/cargarTecnicosYJefes.php",
                    data: "idDepartamento=" + $('#departamentoSolicitante').val(),
                    success: function (r) {

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
                    success: function (r) {

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
        $(document).ready(function () {


            // Show div by removing inline display none style rule
            $(".show-realizar").click(function () {
                $("#rowMenu2").show();
                $("#rowMenu1").hide();
                $("#listarAC").show();
                $("#listarAC2").show();
                $("#registrarAC").hide();
                $("#registrarAC2").hide();
            });

            // Show div by removing inline display none style rule
            $(".show-listar").click(function () {
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
        $(document).ready(function () {
            $('textarea').on('keyup keypress', function () {
                $(this).height(0);
                $(this).height(this.scrollHeight);
            });
        });
    </script>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/mesaayuda-sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Licitaciones</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <?php
        if (isset($_GET['exitoModiLicitacion'])) {
            ?>
            <script>
                Swal.fire(
                    'Exito!',
                    'Se ha modificado correctamente!',
                    'success'
                )
            </script>
            <?php
        }
        if (isset($_GET['errorModiLicitacion'])) {
            ?>
            <script>
                Swal.fire(
                    'Error!',
                    'Se ha producido un error al intentar modificar. Reintente',
                    'error'
                )
            </script>
            <?php
        }
        if (isset($_GET['exitoLicitacion'])) {
            ?>
            <script>
                Swal.fire(
                    'Exito!',
                    'Se ha registrado correctamente!',
                    'success'
                )
            </script>
            <?php
        }
        ?>
        <?php
        if (isset($_GET['errorLicitacion'])) {
            ?>
            <script>
                Swal.fire(
                    'Error!',
                    'Se ha producido un error al intentar guardar. Reintente!',
                    'error'
                )
            </script>
            <?php
        }
        ?>
        <section class="content">
            <div class="container-fluid">
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
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body" style="text-align:center;">
                                <div class="row text-center" style="display: flex; justify-content:center;">
                                    <div id="registrarAC2" name="registrarAC2" class="col-lg-3 col-md-6 col-12 text-center">
                                        <!-- small box -->
                                        <button id="registrarAC" style="color: white" name="registrarAC" class="btn btn-block bg-gradient-info btn-md show-realizar text-black text-center"><i class="nav-icon fas fa-book"></i><br>Registrar Licitacion</button>
                                    </div>
                                    &nbsp;
                                    <div class="col-lg-3 col-md-6 col-12 text-center" id="listarAC2" style="display:none;" name="listarAC2">
                                        <!-- small box -->
                                        <button id="listarAC" style="display:none; color: white" name="listarAC" class="btn btn-block bg-gradient-info btn-md text-black text-center show-listar"><i class="nav-icon fas fa-book"></i><br>Ver Listado</button>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="rowMenu1" name="rowMenu1">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>1.- Listado de Licitaciones</h5>
                                <br>
                                <div class="table-responsive">
                                    <table style="font-size:80%" id="example2" class="table table-bordered cell-border">
                                        <thead class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle;">
                                                <center>N° de Memo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Nombre del Proceso</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Unidad</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Tipo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Encargado</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Cargo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Estado</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Presupuesto</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>ID Licitacion</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha Inicio</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha Termino</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Descargar Archivos</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Acciones</center>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $sql = "SELECT * FROM licitaciones ORDER BY id";
                                        $res = mysqli_query($conexion,$sql);
                                        while($row = $res->fetch_assoc()){
                                            $idLicitacion = $row['id'];
                                            $numeroMemo = $row['numeroMemo'];
                                            $nombreProceso = $row['nombreProceso'];
                                            $cargo = $row['cargo'];
                                            $tipo = $row['tipo'];
                                            $presupuesto = $row['presupuesto'];
                                            $moneda = $row['moneda'];
                                            $encargado = $row['encargado'];
                                            $estado = $row['estado'];
                                            $fechaIngreso = $row['fechaIngreso'];
                                            $idLicitacionMostar = $row['idLicitacion'];
                                            $fechaInicio = $row['fechaInicio'];
                                            $fechaTermino = $row['fechaTermino'];
                                            $unidad = $row['unidad'];
                                            $buscarArchivos = buscarArchivosLicitacion($idLicitacion);
                                            $folio = $row['folio'];
                                            $presupuestoFinal = '';

                                            $presupuestoNumerico = floatval($presupuesto); // Convertir a número
                                            $presupuestoFormateado = number_format($presupuestoNumerico, 0, ',', '.');

                                            if($presupuestoFormateado == '0'){
                                                $presupuestoFinal = 'Sin Presupuesto';
                                            }else{
                                                if($moneda == '$'){
                                                    $presupuestoFinal = $moneda.$presupuestoFormateado;
                                                }else if($moneda == 'UF'){
                                                    $presupuestoFinal = $presupuestoFormateado.' '.$moneda;
                                                }else{
                                                    $presupuestoFinal = $presupuestoFormateado;
                                                }
                                            }

                                            if($fechaTermino == '0000-00-00 00:00:00' || empty($fechaTermino)){
                                                $fechaTermino = 'No posee fecha de termino';
                                            }
                                            if($fechaInicio == '0000-00-00 00:00:00' || empty($fechaInicio)){
                                                $fechaInicio = 'No posee fecha de inicio';
                                            }

                                            if(empty($idLicitacionMostar)){
                                                $idLicitacionMostar = '0';
                                            }

                                            if(empty($unidad)){
                                                $unidad = 'Sin Unidad';
                                            }

                                            if(empty($cargo)){
                                                $cargo = 'Sin información';
                                            }

                                            ?>
                                            <tr style="text-align: center">
                                                <td style="vertical-align: middle; justify-content: center;"><?php echo $numeroMemo;?></td>
                                                <td style="vertical-align: middle; justify-content: center;"><?php echo $nombreProceso;?></td>
                                                <td style="vertical-align: middle; justify-content: center;"><?php echo $unidad?></td>
                                                <td style="vertical-align: middle; justify-content: center;"><?php echo $tipo;?></td>
                                                <td style="vertical-align: middle; justify-content: center;"><?php echo $encargado;?></td>
                                                <td style="vertical-align: middle; justify-content: center;"><?php echo $cargo;?></td>
                                                <td style="vertical-align: middle; justify-content: center;"><?php echo $estado;?></td>
                                                <td style="vertical-align: middle; justify-content: center;"><?php echo $presupuestoFinal;?></td>
                                                <td style="vertical-align: middle; justify-content: center;"><?php echo $idLicitacionMostar;?></td>
                                                <td style="vertical-align: middle; justify-content: center;"><?php echo $fechaInicio;?></td>
                                                <td style="vertical-align: middle; justify-content: center;"><?php echo $fechaTermino;?></td>
                                                <td style="vertical-align: middle; justify-content: center;">
                                                    <?php
                                                    if($buscarArchivos > 0){
                                                        ?>
                                                        <button type="button" class="btn btn-block btn-info" data-toggle="modal" data-target="#modalListDesc">
                                                            <i class="fas fa-download"></i>&nbsp;&nbsp;Descargar Archivos Adjuntos
                                                        </button>
                                                        <div class="modal fade" id="modalListDesc" style="font-size: 12px;">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-primary">
                                                                        <h4 class="modal-title">
                                                                            Listado de Archivos Adjuntos
                                                                        </h4>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form style="margin: 0; text-align: center;" class="" action="licitaciones_admin.php" method="post">
                                                                            <input type="hidden" name="folioEnviar" value="<?php echo $folio?>">
                                                                            <button type="submit" style="float: right;" id="inputMozi" class="btn btn-success"><i class="fa fa-download"></i>&nbsp;&nbsp;Descargar Todo</button>
                                                                        </form>
                                                                        <br>
                                                                        &nbsp;
                                                                        <br>
                                                                        &nbsp;
                                                                        <div class="table table-responsive">
                                                                            <table class="table table-bordered text-center">
                                                                                <thead class="bg-secondary">
                                                                                <tr>
                                                                                    <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                                                        N°
                                                                                    </th>
                                                                                    <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                                                        Nombre Archivo
                                                                                    </th>
                                                                                    <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                                                        Descarga
                                                                                    </th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                <?php
                                                                                    $sqlArchivos = "SELECT * FROM `archivos_licitacion_sdo` WHERE idLicitacion = $idLicitacion";
                                                                                    $resArchivos = mysqli_query($conexion,$sqlArchivos);
                                                                                    $numListDesc = 1;

                                                                                    while($rowArchivos = $resArchivos->fetch_assoc()){
                                                                                        $nomArchivo = $rowArchivos['nomArchivo'];
                                                                                        $rutaArchivo = $rowArchivos['rutaArchivo'];
                                                                                        $rutaArchivo = trim($rutaArchivo);
                                                                                        ?>
                                                                                        <tr>
                                                                                            <td style="vertical-align:middle;" nowrap="nowrap"><?php echo $numListDesc?></td>
                                                                                            <td style="vertical-align:middle;" nowrap="nowrap"><?php echo $nomArchivo?></td>
                                                                                            <td style="vertical-align:middle;" nowrap="nowrap">
                                                                                                <?php
                                                                                                if (empty($rutaArchivo) || $rutaArchivo == '') {
                                                                                                    echo 'Error al Obtener Datos';
                                                                                                } else {
                                                                                                    ?>
                                                                                                    <a href="<?php echo $rutaArchivo; ?>" class="btn btn-success" download><i class="fas fa-download"></i>&nbsp;&nbsp;Descargar Archivo</a>
                                                                                                    <?php
                                                                                                }
                                                                                                ?>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <?php
                                                                                    }
                                                                                ?>

                                                                                </tbody>
                                                                                <tfoot class="bg-secondary">
                                                                                <tr>
                                                                                    <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                                                        N°
                                                                                    </th>
                                                                                    <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                                                        Nombre Archivo
                                                                                    </th>
                                                                                    <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                                                        Descarga
                                                                                    </th>
                                                                                </tr>
                                                                                </tfoot>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    }else{
                                                        echo 'Sin Archivos Subidos';
                                                    }
                                                    ?>


                                                </td>
                                                <td style="vertical-align: middle; justify-content: center;">
                                                    <?php
                                                    if($idLicitacion == 0 || empty($idLicitacion)){
                                                        echo 'Error con los datos';
                                                    } else {
                                                        ?>
                                                        <div class="d-flex align-items-center">
                                                            <!-- Formulario con el botón de editar -->
                                                            <form action="modificar_licitacion.php" method="POST" class="mr-3">
                                                                <input type="hidden" name="idLicitacion" value="<?php echo $idLicitacion?>">
                                                                <button class="btn btn-md bg-warning" title="Editar Licitación">
                                                                    <i class="fas fa-pen"></i>
                                                                </button>
                                                            </form>

                                                            <!-- Botón para abrir el modal de vista -->
                                                            <button type="button" class="btn btn-primary mr-3" data-toggle="modal" data-target="#exampleModal<?php echo $idLicitacion?>" title="Ver Observación">
                                                                <i class="fas fa-eye"></i>
                                                            </button>

                                                            <!-- Botón para abrir el modal de agregar -->
                                                            <button type="button" class="btn btn-success mr-3" data-toggle="modal" data-target="#exampleModalAgregar<?php echo $idLicitacion?>" title="Agregar Observación">
                                                                <i class="fas fa-plus"></i>
                                                            </button>

                                                            <!-- Botón para abrir el modal de subir archivo -->
                                                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModalSubirArchivo<?php echo $idLicitacion?>" title="Subir Archivo">
                                                                <i class="fas fa-upload"></i>
                                                            </button>
                                                        </div>

                                                        <div class="modal fade" id="exampleModalSubirArchivo<?php echo $idLicitacion?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-primary">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Subir Archivos</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <form action="../sdo-funciones/subir_archivo_licitacion.php" method="POST" enctype="multipart/form-data">
                                                                        <div class="modal-body">
                                                                            <label>Subir Archivos</label>
                                                                            <input type="file" class="form-control" name="archivosNuevos[]" id="archivosNuevos[]" multiple>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <input type="hidden" name="idLicitacion" value="<?php echo $idLicitacion?>">
                                                                            <input type="hidden" name="folio" id="folio" value="<?php echo $folio?>">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="exampleModal<?php echo $idLicitacion?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-xl" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-primary">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Listado de Observaciones</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <table class="table table-bordered">
                                                                            <thead class="bg-secondary">
                                                                                <th style="width: 30%">Nombre del Creador</th>
                                                                                <th style="width: 30%">Fecha</th>
                                                                                <th>Observación</th>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php
                                                                                $sqlObs = "SELECT * FROM `licitaciones_observaciones` WHERE idLicitacion = $idLicitacion";
                                                                                $resObs = mysqli_query($conexion,$sqlObs);
                                                                                while($rowObs = $resObs->fetch_assoc()){
                                                                                    $nomCreador = $rowObs['nomCreador'];
                                                                                    $fechaObs = $rowObs['fecha'];
                                                                                    $obs = $rowObs['obs'];
                                                                                    ?>
                                                                                    <td style="vertical-align: middle; justify-content: center;"><?php echo $nomCreador?></td>
                                                                                    <td style="vertical-align: middle; justify-content: center;"><?php echo $fechaObs?></td>
                                                                                    <td style="vertical-align: middle; justify-content: center;"><?php echo $obs?></td>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                            </tbody>
                                                                            <tfoot class="bg-secondary">
                                                                                <th>Nombre del Creador</th>
                                                                                <th>Fecha</th>
                                                                                <th>Observación</th>
                                                                            </tfoot>
                                                                        </table>

                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal fade" id="exampleModalAgregar<?php echo $idLicitacion?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-primary">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Agregar una Observacion</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <form action="../sdo-funciones/ingresar_obs_licitacion.php" method="POST">
                                                                        <div class="modal-body">
                                                                            <label>Nombre del Creador:</label>
                                                                            <input type="text" name="nomCreador" readonly class="form-control" value="<?php echo $nomUsuario?>">
                                                                            <br>
                                                                            <?php $now = date('Y-m-d H:i:s')?>
                                                                            <label>Fecha:</label>
                                                                            <input type="text" name="fecha" class="form-control" readonly value="<?php echo $now?>">
                                                                            <br>
                                                                            <label>Observación</label>
                                                                            <textarea class="form-control" name="obs" rows="15" style="resize: block"></textarea>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <input type="hidden" name="idLicitacion" value="<?php echo $idLicitacion?>">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>

                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                        <tfoot class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle;">
                                                <center>N° de Memo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Nombre del Proceso</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Unidad</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Tipo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Encargado</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Cargo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Estado</center>
                                            </th>



                                            <th style="vertical-align:middle;">
                                                <center>Presupuesto</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>ID Licitacion</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha Inicio</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha Termino</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Descargar Archivos</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Acciones</center>
                                            </th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" id="rowMenu2" name="rowMenu2" style="display:none;">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>2.- Registrar Licitacion</h5>
                                <br>
                                <form action="../sdo-funciones/guardar-licitacion.php" method="POST" enctype="multipart/form-data">
                                    <fieldset class="border p-3">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">N° de Memo: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="text" required class="form-control" id="numeroMemo" name="numeroMemo" placeholder="Numero del Memo">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Nombre del Proceso: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="text" required class="form-control" id="nombreProceso" name="nombreProceso" placeholder="Nombre del proceso">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">

                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Unidad: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <select class="form-control" name="departamentos" id="departamentos">
                                                        <option value="" selected disabled>Seleccione....</option>
                                                        <?php
                                                            $sqlDepa = "SELECT * FROM departamentos WHERE idDepartamento != 13 AND idDepartamento != 11  ORDER BY nomDepartamento";
                                                            $resDepa = mysqli_query($conexion,$sqlDepa);
                                                            while($rowDepa = $resDepa->fetch_assoc()){
                                                                $nomDepartamento = $rowDepa['nomDepartamento'];
                                                                if($nomDepartamento == "Instrumentación"){
                                                                    $nomDepartamento = "Control Centralizado";
                                                                }
                                                                ?>
                                                                <option value="<?php echo $nomDepartamento?>"><?php echo $nomDepartamento?></option>
                                                                <?php
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Tipo (Trato Directo / Licitacion): <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <select name="tipo" required class="form-control">
                                                        <option value="" selected disabled>Seleccione....</option>
                                                        <option value="Licitacion">Licitacion</option>
                                                        <option value="Trato Directo">Trato Directo</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Encargado Licitacion: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="text" required class="form-control" id="encargado" name="encargado" placeholder="Encargado" oninput="this.value = this.value.toUpperCase();">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Cargo encargado: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="text" required class="form-control" id="cargo" name="cargo" placeholder="Cargo" oninput="this.value = this.value.toUpperCase();">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Estado: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="text" required class="form-control" id="estado" name="estado" placeholder="Estado">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Presupuesto:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" id="presupuesto" name="presupuesto" placeholder="Presupuesto">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">ID Licitacion:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="text"  class="form-control" id="idlicitacion" name="idlicitacion" placeholder="ID Licitacion">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Fecha Inicio:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                    </div>
                                                    <input type="datetime-local"  class="form-control" id="fechaInicio" name="fechaInicio">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Fecha Termino:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                    </div>
                                                    <input type="datetime-local"  class="form-control" id="fechaTermino" name="fechaTermino">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="exampleInputTurno">Subir Bases y Contrato:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                    </div>
                                                    <input type="file" name="archivos[]" id="archivos[]" class="form-control" multiple>
                                                </div>
                                            </div>
                                        </div>

                                    </fieldset>
                                    <br>
                                    <button type="submit" class="btn btn-block bg-success">Guardar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
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
    <script src="../plugins/select2/js/select2.full.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>
    <script>
        $(function () {
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
                "responsive": true,
                "language": {
                    "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                    "zeroRecords": "No se han encontrado Registros.",
                    "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                    "infoEmpty": "No existen Registros.",
                    "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                    "paginate": {
                        "previous": "Anterior",
                        "next": "Siguiente"
                    }
                }
            });
        });
    </script>
</div>
</body>
</html>
