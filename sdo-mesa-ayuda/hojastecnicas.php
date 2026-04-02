<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['idOrden'])) {
    $idOrden = $_POST['idOrden'];
    $folio = $_POST['folio'];
    if ($idOrden > 0) {
        if (empty($idOrden) || $idOrden == '') {
        } else {
            ini_set("memory_limit", "1256M");
            $zip = new ZipArchive();
            $dir = "../sdo-archivos-orden/" . $folio;
            $dir2 = $dir . "/";
            $rutaFinal = "../sdo-administrador/";
            $archivoZip = "HojaTecnica-" . $folio . ".zip";
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
}else if ($rolUsuario2 == "mesa ayuda") {
    $ruta = "sdo-mesa-ayuda";
} else {
    header('Location: ../sdo-flujo/login.php?errorSesion=true');
}

$idHoja = $_GET['idHoja'];

if ($_GET['idHoja']) {
    $sql = "SELECT * FROM hoja_de_vida WHERE idHoja = $idHoja";
    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {
        $datos = [];

        $datos['nomEquipo'] = $row['nomEquipo'];

        $nomEquipo = $datos['nomEquipo'];
    }
    $_SESSION['idHoja'] = $idHoja;
} else if ($_SESSION['idHoja']) {

    $idHoja = $_SESSION['idHoja'];

    $sql = "SELECT * FROM hoja_de_vida WHERE idHoja = $idHoja";
    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {
        $datos = [];

        $datos['nomEquipo'] = $row['nomEquipo'];

        $nomEquipo = $datos['nomEquipo'];
    }
} else {
    header('Location: administrar-equipos.php?noGasto=true');
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrar Equipos</title>
    <link rel="icon" href="../dist/img/ss-vina-quillota.png" type="image/icon type">
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
    <link rel="stylesheet" href="css/dark-mode.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
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
        include '../sdo-templates/mesaayuda-sidebar.php';
        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <br>
            <!-- AVISOS -->
            <?php


            if (isset($_GET['exitoHoja'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Exito',
                        text: 'Exito al añadir hoja técnica',
                    })
                </script>

            <?php
            }

            ?>
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- general form elements -->
                    <div class="card card-danger">
                        <div class="card-header">
                            <h1 class="card-title"> <b> Hojas Técnicas - <?php echo $nomEquipo ?></b></h1>
                        </div>
                        <div class="row">
                            <!-- left column -->
                            <div class="col-md-12">
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <a href="visualizarEquipo.php?idHoja=<?php echo $idHoja ?>" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp; <b>Volver Atras</b> </a>
                                    <br>
                                    <br>

                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#staticBackdrop"> <b><i class="fas fa-plus"></i> Añadir Hoja Técnica</b> </button>

                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#crear"> <b><i class="fas fa-plus"></i> Crear Hoja Técnica</b> </button>

                                    <div class="modal fade" id="crear" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary">
                                                    <h5 class="modal-title" id="staticBackdropLabel">Información Hoja Técnica</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="../sdo-funciones/admin-ordenes-generar-general.php" method="post" enctype="multipart/form-data">

                                                        <p><code>*</code> <b style="color:red">Campo obligatorio</b> </p>
                                                        <br>
                                                        <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
                                                            <li class="nav-item">
                                                                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true"><b>Solicitante</b> </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false"><b>Detalles Solicitud</b> </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link " id="pills-equipo-tab" data-toggle="pill" href="#pills-equipo" role="tab" aria-controls="pills-equipo" aria-selected="false"> <b>Equipo</b> </a>
                                                            </li>

                                                            <!-- <li class="nav-item">
                                                <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false"> <b>Resumen</b> </a>
                                            </li> -->
                                                        </ul>
                                                        <div class="tab-content" id="pills-tabContent">
                                                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">

                                                                <div class="form-row">
                                                                    <div class="form-group col-md-6">
                                                                        <label for="exampleInputQuienSolicita" style="font-size: 12px;">Ticket Asociado:</label>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i class="fas fa-ticket-alt"></i></span>
                                                                            </div>
                                                                            <input type="text" style="text-transform:capitalize" class="form-control form-control-sm" id="ticketAsociado" name="ticketAsociado" value="" placeholder="Escriba el ticket asociado">
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                                <div class="form-row">

                                                                    <div class="form-group col-md-6">
                                                                        <label for="exampleInputFecha" style="font-size: 12px;">Fecha Actual:</label>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                                            </div>
                                                                            <?php
                                                                            date_default_timezone_set('America/Santiago');
                                                                            ?>
                                                                            <input type="datetime" readonly="readonly" id="fechaActualOrden" name="fechaActualOrden" class="form-control form-control-sm" value="<?php echo date('d-m-Y'); ?>" />

                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-row">
                                                                    <div class="form-group col-md-6">
                                                                        <label for="exampleInputDepartamento" style="font-size: 12px;">Departamento:</label>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i class="fas fa-server"></i></span>
                                                                            </div>
                                                                            <select id="departamentoSolicitante" class="form-control form-control-sm" name="departamentoSolicitante">
                                                                                <?php

                                                                                $queryDepartamentos = "SELECT * FROM departamentos ORDER BY nomDepartamento ASC";

                                                                                $resDepartamentos = mysqli_query($conexion, $queryDepartamentos);

                                                                                while ($rowDepartamentos = $resDepartamentos->fetch_assoc()) {

                                                                                    if ($rowDepartamentos['idDepartamento'] == $idDepartamento) {
                                                                                ?>
                                                                                        <option value="<?php echo $rowDepartamentos['idDepartamento']; ?>" selected="selected">
                                                                                            <?php echo $rowDepartamentos['nomDepartamento']; ?>
                                                                                        </option>
                                                                                    <?php
                                                                                    } else {
                                                                                    ?>
                                                                                        <option value="<?php echo $rowDepartamentos['idDepartamento']; ?>">
                                                                                            <?php echo $rowDepartamentos['nomDepartamento']; ?>
                                                                                        </option>
                                                                                <?php
                                                                                    }
                                                                                }

                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div name="visualizarSubDepartamento" id="visualizarSubDepartamento" class="form-group  form-control-sm col-md-6">

                                                                    </div>
                                                                </div>

                                                                <div class="form-row">
                                                                    <div class="form-group col-md-6">
                                                                        <label for="exampleInputNomSolicitante" style="font-size: 12px;">Tecnico Recepcionador:<code>*</code></label>
                                                                        <div class="input-group mb-3">

                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                                            </div>
                                                                            <input type="text" style="text-transform:capitalize" class="form-control form-control-sm" id="nomTecnicoRecepcionador" name="nomTecnicoRecepcionador" value="" placeholder=" Escriba El Nombre Funcionario Recepcionador">

                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-row">
                                                                    <div class="form-group col-md-6">
                                                                        <label for="exampleInputQuienSolicita" style="font-size: 12px;">Persona que Solicita:<code>*</code></label>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                                            </div>
                                                                            <input type="text" style="text-transform:capitalize" class="form-control form-control-sm" id="nomSolicitante" name="nomSolicitante" value="" placeholder="Persona que Solicita">
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                                <div class="form-row">
                                                                    <div class="form-group col-md-6">
                                                                        <label for="exampleInputTurno" style="font-size: 12px;">Servicio:</label>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i class="fas fa-server"></i></span>
                                                                            </div>
                                                                            <select id="servicioOrden" class="form-control" name="servicioOrden">
                                                                                <?php

                                                                                $queryServicios = "SELECT * FROM servicios ORDER BY nomServicio ASC";

                                                                                $resServicios = mysqli_query($conexion, $queryServicios);

                                                                                while ($rowServicios = $resServicios->fetch_assoc()) {

                                                                                ?>
                                                                                    <option value="<?php echo $rowServicios['idServicio']; ?>">
                                                                                        <?php echo $rowServicios['nomServicio']; ?>
                                                                                    </option>
                                                                                <?php

                                                                                }

                                                                                ?>
                                                                                <option value="Otro">
                                                                                    Otro
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div name="visualizarNomServicio" id="visualizarNomServicio" class="form-group form-control-sm col-md-6">

                                                                    </div>
                                                                </div>

                                                                <div class="form-row">
                                                                    <div class="form-group col-md-6">
                                                                        <label for="exampleInputRecinto" style="font-size: 12px;">Recinto:<code>*</code></label>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i class="fas fa-map-marker"></i></span>
                                                                            </div>
                                                                            <input type="text" required class="form-control form-control-sm" id="recintoOrden" name="recintoOrden" placeholder="Recinto">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group col-md-3">
                                                                        <label for="exampleInputPMA" style="font-size: 12px;">PMA:</label>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i class="fas fa-map-marker"></i></span>
                                                                            </div>
                                                                            <input type="text" class="form-control form-control-sm" id="pmaOrden" name="pmaOrden" placeholder="PMA">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group col-md-3">
                                                                        <label for="exampleInputCoordenadas" style="font-size: 12px;">Coordenadas:</label>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i class="fas fa-map-marker"></i></span>
                                                                            </div>
                                                                            <input type="text" class="form-control form-control-sm" id="coordenadasOrden" name="coordenadasOrden" placeholder="Coordenadas">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div><!--Aqui termina la pestaña del solicitante -->

                                                            <div class="tab-pane fade " id="pills-equipo" role="tabpanel" aria-labelledby="pills-equipo-tab">

                                                                <div class="row">
                                                                    <div class="form-group col-md-6">
                                                                        <fieldset class="border p-3">
                                                                            <legend class="w-auto" style="font-size:15px;"><b>Filtrar por ID del equipo</b></legend>
                                                                            <input list="ids" disabled name="idFiltra" class="form-control form-control-sm" placeholder="Escriba el id para filtrar" />
                                                                            <datalist id="ids">

                                                                                <?php

                                                                                $sqlIdUnico = "SELECT * FROM hoja_de_vida";
                                                                                $res = mysqli_query($conexion, $sqlIdUnico);

                                                                                while ($row = $res->fetch_assoc()) {
                                                                                    $datos[''];
                                                                                    $datos['idUnico'] = $row['idUnico'];
                                                                                    $idUnico = $datos['idUnico'];
                                                                                ?>
                                                                                    <option><?php echo $idUnico; ?></option>
                                                                                <?php
                                                                                }

                                                                                ?>

                                                                            </datalist>
                                                                        </fieldset>
                                                                    </div>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="form-group col-md-6">
                                                                        <label for="exampleInputQuienSolicita" style="font-size: 12px;">Equipo o Instrumento:</label>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i class="fas fa-box"></i></span>
                                                                            </div>
                                                                            <input type="text" style="text-transform:capitalize" class="form-control form-control-sm" id="nomEquipo" name="nomEquipo" value="" placeholder="Nombre del equipo">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group col-md-6">
                                                                        <label for="exampleInputQuienSolicita" style="font-size: 12px;">Numero Inventario:</label>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                                                            </div>
                                                                            <input type="text" style="text-transform:capitalize" class="form-control form-control-sm" id="numInventario" name="numInventario" value="" placeholder="Número de inventario del equipo">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-row">
                                                                    <div class="form-group col-md-6">
                                                                        <label for="exampleInputQuienSolicita" style="font-size: 12px;">Marca:</label>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i class="fas fa-copyright"></i></span>
                                                                            </div>
                                                                            <input type="text" style="text-transform:capitalize" class="form-control form-control-sm" id="marcaEquipo" name="marcaEquipo" value="" placeholder="Marca del equipo">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group col-md-6">
                                                                        <label for="exampleInputQuienSolicita" style="font-size: 12px;">Modelo:</label>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i class="fas fa-copyright"></i></span>
                                                                            </div>
                                                                            <input type="text" style="text-transform:capitalize" class="form-control form-control-sm" id="modeloEquipo" name="modeloEquipo" value="" placeholder="Modelo del equipo">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-row">
                                                                    <div class="form-group col-md-6">
                                                                        <label for="exampleInputQuienSolicita" style="font-size: 12px;">N° de Serie:</label>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i class="fas fa-code"></i></span>
                                                                            </div>
                                                                            <input type="text" style="text-transform:capitalize" class="form-control form-control-sm" id="numSerie" name="numSerie" value="" placeholder="N° de serie del equipo">
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                                <div class="form-group">
                                                                    <input type="hidden" name="idSubDep" id="idSubDep" value="<?php echo $idSubDepartamento; ?>">
                                                                    <input type="hidden" name="idUsuario" id="idUsuario" value="<?php echo $idUsuario; ?>">
                                                                    <button type="submit" id="guardarRecepcion" name="guardarRecepcion" class="btn btn-block btn-primary"> <b>Guardar</b> </button>

                                                                </div>


                                                            </div><!--Aqui termina la pestaña del equipo -->

                                                            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">

                                                                <fieldset class="border p-3">
                                                                    <legend class="w-auto">Tipo de trabajo</legend>
                                                                    <div class="form-row">
                                                                        <div class="form-group col-md-12">
                                                                            <label for="exampleInputCodigoTarjeta" style="font-size: 12px;">Tipo Mantención:<code>*</code></label>
                                                                            <div class="input-group mb-3">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text"><i class="fas fa-th-list"></i></span>
                                                                                </div>
                                                                                <select name="tipoMantencion" class="form-control form-control-sm" required id="tipoMantencion">
                                                                                    <option value="" disabled selected>-- Seleccione --</option>
                                                                                    <option value="MPE">MPE - Mantenimiento Preventivo Externo</option>
                                                                                    <option value="MRE">MRE - Mantenimiento Reparativo Externo</option>
                                                                                    <option value="MPI">MPI - Mantenimiento Preventivo Interno</option>
                                                                                    <option value="MRI">MRI - Mantenimiento Reparativo Interno</option>
                                                                                    <option value="OM">OM - Obras Menores</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </fieldset>
                                                                <br>

                                                                <div class="form-group">
                                                                    <label for="exampleInputDetalleTrabajo" style="font-size: 12px;">Problema que presenta:<code>*</code></label>
                                                                    <textarea id="detalleTrabajo" style="resize:none;" required name="problemaPresenta" class="form-control form-control-sm" rows="3" placeholder="Detalle el problema que presenta.."></textarea>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="exampleInputDetalleTrabajo" style="font-size: 12px;">Trabajo Realizado:<code>*</code></label>
                                                                    <textarea id="detalleTrabajo" style="resize:none;" required name="trabajoRealizado" class="form-control form-control-sm" rows="3" placeholder="Escriba el trabajo que realizo.."></textarea>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="exampleInputDetalleTrabajo" style="font-size: 12px;">Acciones Realizadas:</label>
                                                                    <textarea id="detalleTrabajo" style="resize:none;" name="accionesRealiza" class="form-control form-control-sm" rows="3" placeholder="Acciones realizadas en el trabajo..."></textarea>
                                                                </div>

                                                                <div class="form-row fieldGroup">
                                                                    <div class="form-group col-md-4">
                                                                        <label for="exampleInputRecinto" style="font-size: 12px;">Materiales Usados:</label>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i class="fas fa-list"></i></span>
                                                                            </div>
                                                                            <input type="text" class="form-control form-control-sm" id="materialUsado[]" name="materialUsado[]" placeholder="Materiales Usados">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group col-md-3">
                                                                        <label for="exampleInputPMA" style="font-size: 12px;">Unidad:</label>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                                                            </div>
                                                                            <input type="text" class="form-control form-control-sm" id="unidad[]" name="unidad[]" placeholder="Unidad">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group col-md-3">
                                                                        <label for="exampleInputCoordenadas" style="font-size: 12px;">Cantidad:</label>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                                                            </div>
                                                                            <input type="number" min="1" class="form-control form-control-sm" id="cantidad[]" name="cantidad[]" placeholder="Cantidad">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-md-2">
                                                                        <label for="exampleInputCoordenadas">&nbsp;</label>
                                                                        <div class="input-group mb-3">
                                                                            <a href="javascript:void(0)" class="btn btn-block btn-success addMore"><span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span><b>Agregar Material Usado</b> </a>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <hr>

                                                                <div class="form-group">
                                                                    <label for="exampleInputComentarios" style="font-size: 12px;">Comentarios:</label>
                                                                    <textarea id="comentariosTrabajos" style="resize:none;" name="comentariosTrabajos" class="form-control form-control-sm" rows="3" placeholder="Comentarios"></textarea>
                                                                </div>

                                                                <div class="form-group">
                                                                    <!-- <label for="customFile">Custom File</label> -->
                                                                    <label for="exampleInputTareasControlCent" style="font-size: 12px;">Subir Imagenes: (Maximo 10 Imagenes)<code>*</code></label><br>
                                                                    <label for="exampleInputTareasControlCent" style="font-size: 12px;">Formatos Permitidos: .jpg, .png, .jpeg, .bmp, .tif, .webp, .svg, .jfif</label>
                                                                    <div class="custom-file">
                                                                        <input type="file" max-uploads="10" class="custom-file-input" multiple="" id="archivoImagenOrden[]" name="archivoImagenOrden[]" accept=".jpg, .png, .jpeg, .bmp, .tif, .webp, .svg, .jfif">
                                                                        <label class="custom-file-label" for="customFile" style="font-size: 12px;"><b>Seleccionar Archivo...</b> </label>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <!-- <label for="customFile">Custom File</label> -->
                                                                    <label for="exampleInputTareasControlCent" style="font-size: 12px;">Subir Archivos: (Maximo 10 Archivos)</label><br>
                                                                    <label for="exampleInputTareasControlCent" style="font-size: 12px;">Formatos Permitidos: .pdf, .xlsx(Excel), .docx(Word) </label>
                                                                    <div class="custom-file">
                                                                        <input type="file" max-uploads="10" class="custom-file-input" multiple="" id="archivosOrden[]" name="archivosOrden[]" accept=".pdf, .xlsx, .docx">
                                                                        <label class="custom-file-label" for="customFile" style="font-size: 12px;"><b>Seleccionar Archivo...</b> </label>
                                                                    </div>
                                                                </div>
                                                                <br>
                                                                <br>


                                                            </div><!--Aqui termina la pestaña detalles solicitud -->
                                                            <!-- <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">

                                                <fieldset class="border p-3">
                                                    <legend class="w-auto">Solicitante</legend>

                                                    <h6><b>Persona que Solicita:
                                                        </b> <span id="personaSolicita"></span> </h6>
                                                    <h6><b>Departamento:
                                                        </b> <span id="depa"></span></h6>
                                                    <h6><b>Tecnico Recepcionador:
                                                        </b> <span id="tecnico"></span> </h6>
                                                    <h6><b>Servicio:
                                                        </b><span id="servicio"></span></h6>
                                                    <h6><b>Recinto:
                                                        </b><span id="recinto"></span></h6>
                                                    <h6><b>PMA:
                                                        </b><span id="pma"></span></h6>
                                                    <h6><b>Coordenadas:
                                                        </b><span id="coordenadas"></span></h6>
                                                </fieldset>
                                                <br>
                                                <fieldset class="border p-3">
                                                    <legend class="w-auto">Equipo</legend>

                                                    <h6><b>Equipo o Instrumento:
                                                        </b> <span id="equipo"></span> </h6>
                                                    <h6><b>N° Inventario:
                                                        </b> <span id="inventario"></span></h6>
                                                    <h6><b>Marca:
                                                        </b> <span id="marca"></span> </h6>
                                                    <h6><b>Modelo:
                                                        </b><span id="modelo"></span></h6>
                                                    <h6><b>N° de Serie:
                                                        </b><span id="serie"></span></h6>

                                                </fieldset>
                                                <br>
                                                <fieldset class="border p-3">
                                                    <legend class="w-auto">Detalle Solicitud</legend>

                                                    <div class="row">
                                                        <div class="form-group col-md-4">
                                                            <label for="exampleInputCoordenadas">Trabajo Realizado:</label>
                                                            <textarea id="comentariosTrabajos" disabled style="resize:none;" name="comentariosTrabajos" class="form-control" rows="3"></textarea>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="exampleInputCoordenadas">Problema que presenta:</label>
                                                            <textarea id="comentariosTrabajos" disabled style="resize:none;" name="comentariosTrabajos" class="form-control" rows="3"></textarea>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="exampleInputCoordenadas">Acciones realizadas:</label>
                                                            <textarea id="comentariosTrabajos" disabled style="resize:none;" name="comentariosTrabajos" class="form-control" rows="3"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputCoordenadas">Comentarios:</label>
                                                            <textarea id="comentariosTrabajos" disabled style="resize:none;" name="comentariosTrabajos" class="form-control" rows="3"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        
                                                    </div>

                                                </fieldset>
                                                <hr>

                                             

                                            </div>Aqui termina la pestaña detalles solicitud -->
                                                        </div>


                                                        <br>
                                                        <br>

                                                        <input type="hidden" value="crearYAgregar" name="crearYAgregar">

                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success">
                                                    <h5 class="modal-title" id="staticBackdropLabel">Escoja la hoja técnica a añadir</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <table id="hoja_tecnicas" class="table">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">N°</th>
                                                                <th scope="col">Nombre</th>
                                                                <th scope="col">Tecnico Recepcionador</th>
                                                                <th scope="col">Ticket Asociado</th>
                                                                <th scope="col">Seleccionar</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php

                                                            $sqlHojaTec = "SELECT * FROM ordenes_trabajos_general WHERE idEquipo is null";
                                                            $resHojaTec = mysqli_query($conexion, $sqlHojaTec);

                                                            $num = 1;

                                                            while ($rowHojaTec = $resHojaTec->fetch_assoc()) {
                                                                $datos = [];
                                                                $datos['idOrden'] = $rowHojaTec['idOrden'];
                                                                $datos['nomOrden'] = $rowHojaTec['nomOrden'];
                                                                $datos['nomTecnicoRecepcionador'] = $rowHojaTec['nomTecnicoRecepcionador'];
                                                                $datos['ticketAsociado'] = $rowHojaTec['ticketAsociado'];

                                                                $idOrden  = $datos['idOrden'];
                                                                $nomOrden = $datos['nomOrden'];
                                                                $nomTecnicoRecepcionador = $datos['nomTecnicoRecepcionador'];
                                                                $ticketAsociado = $datos['ticketAsociado'];

                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $num ?> </td>
                                                                    <td><?php echo mb_convert_case($nomOrden, MB_CASE_UPPER, 'UTF-8');  ?></td>
                                                                    <td><?php echo mb_convert_case($nomTecnicoRecepcionador, MB_CASE_UPPER, 'UTF-8'); ?></td>
                                                                    <td>
                                                                        <?php
                                                                        if ($ticketAsociado == '') {
                                                                            echo "SIN INFORMACIÓN";
                                                                        } else {
                                                                            echo strtoupper($ticketAsociado);
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td>

                                                                        <form action="../sdo-funciones/agregarHojaTecnica.php" method="POST">
                                                                            <input type="hidden" name="idHojaPadre" value="<?php echo $idHoja ?>">
                                                                            <input type="hidden" name="idHojaTecnica" value="<?php echo $idOrden ?>">
                                                                            <input type="submit" value="Añadir Esta Hoja" class="btn btn-danger">
                                                                        </form>

                                                                    </td>
                                                                </tr>
                                                            <?php
                                                                $num++;
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <br>
                                    <br>
                                    <div class="row">
                                        <div class="col-12">

                                            <table id="gasto_equipo" class="table table-striped table-bordered">
                                                <thead>
                                                    <tr class="bg-info ">
                                                        <th scope="col">N°</th>
                                                        <th scope="col">Nombre</th>
                                                        <th scope="col">Tecnico Recepcionador</th>
                                                        <th scope="col">Ticket Asociado</th>
                                                        <th scope="col">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sql = "SELECT * FROM ordenes_trabajos_general  WHERE idEquipo = $idHoja ORDER BY idOrden DESC";

                                                    $res = mysqli_query($conexion, $sql);
                                                    $num = 1;
                                                    while ($row = $res->fetch_assoc()) {

                                                        $datos['idOrden'] = $row['idOrden'];
                                                        $datos['nomOrden'] = $row['nomOrden'];
                                                        $datos['nomTecnicoRecepcionador'] = $row['nomTecnicoRecepcionador'];
                                                        $datos['ticketAsociado'] = $row['ticketAsociado'];
                                                        $datos['folioOrden'] = $row['folioOrden'];

                                                        $idOrden  = $datos['idOrden'];
                                                        $nomOrden = $datos['nomOrden'];
                                                        $nomTecnicoRecepcionador = $datos['nomTecnicoRecepcionador'];
                                                        $ticketAsociado = $datos['ticketAsociado'];
                                                        $folioOrden = $datos['folioOrden'];

                                                    ?>
                                                        <tr>
                                                            <td style=" font-weight:bold" class="text-primary"><?php echo $num; ?></td>
                                                            <td style=" font-weight:bold" class="text-primary"><?php echo $nomOrden ?></td>
                                                            <td style=" font-weight:bold" class="text-primary"><?php echo $nomTecnicoRecepcionador; ?></td>
                                                            <td style=" font-weight:bold" class="text-primary"> <?php
                                                                                                                if ($ticketAsociado == '') {
                                                                                                                    echo "SIN INFORMACIÓN";
                                                                                                                } else {
                                                                                                                    echo strtoupper($ticketAsociado);
                                                                                                                }
                                                                                                                ?></td>
                                                            <td>

                                                                <form action="../sdo-pdf/SDO-OrdenTrabajoGeneral.php" target="_blank" method="POST" style="display: inline;">
                                                                    <input type="hidden" name="idOrden" value="<?php echo $idOrden ?>">
                                                                    <button type="submit" class="btn btn-sm btn-info "><i class="far fa-eye"></i></button>
                                                                </form>

                                                                <?php

                                                                $sqlArchivos = "SELECT * FROM ";
                                                                $resArchivos = mysqli_query($conexion, $sqlArchivos);

                                                                ?>

                                                                <form action="hojastecnicas.php" method="POST" style="display: inline;">
                                                                    <input type="hidden" name="idOrden" value="<?php echo $idOrden ?>">
                                                                    <input type="hidden" name="folio" value="<?php echo $folioOrden ?>">
                                                                    <button type="submit" class="btn btn-sm btn-warning "><i class="fas fa-download"></i></button>
                                                                </form>

                                                                <a class="btn btn-sm btn-danger eliminar" id="<?php echo $idOrden ?>"><i class="fas fa-trash-alt"></i></a>

                                                            </td>

                                                        <?php
                                                        $num++;
                                                    }
                                                        ?>
                                                </tbody>
                                            </table>

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
    <script src="js/dark-mode.js"></script>
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
        $(document).ready(function() {
            $('#hoja_tecnicas').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "language": {
                    "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                    "zeroRecords": "No se han encontrado hojas técnicas, añada uno.",
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
                    title: '¿Eliminar el registro seleccionado?',
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
                            url: '../sdo-funciones/eliminar-hojatecnica-equipo.php',
                            type: 'POST',
                            data: dataString,
                            success: function(response) {
                                console.log('eliminado.. ' + dataString);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Registro eliminado',
                                    showConfirmButton: false,
                                    timer: 2000,
                                })
                                setTimeout(function() {
                                    location.reload();
                                }, 1800)
                            }
                        })
                    }
                })
            })
        )
    </script>
</body>

</html>