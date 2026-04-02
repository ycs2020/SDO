<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// echo '';

if (isset($_POST['idGasto'])) {
    $idGasto = $_POST['idGasto'];
    $idEquipo = $_POST['idEquipo'];

    if ($idGasto > 0) {
        if (empty($idGasto) || $idGasto == '') {
        } else {

            ini_set("memory_limit", "1256M");
            $zip = new ZipArchive();
            $dir = "../sdo-archivos-gastoEquipos/EQUIPO-" . $idEquipo . "/Gasto-" . $idGasto;
            $dir2 = $dir . "/";
            $rutaFinal = "../sdo-administrador/";
            $archivoZip = "OrdenCompra-Equipo-" . $idEquipo . "-Gasto-" . $idGasto . ".zip";
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
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

$id = $_GET['id'];

if ($_GET['id']) {
    $sql = "SELECT * FROM equipamiento_hbqp WHERE id = $id";
    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['nombreEquipo'] = $row['nombreEquipo'];
        $datos['carpeta'] = $row['carpeta'];
        $nomEquipo = $datos['nombreEquipo'];
        $carpeta = $datos['carpeta'];
    }
    $_SESSION['id'] = $id;
} else if ($_SESSION['id']) {

    $id = $_SESSION['id'];

    $sql = "SELECT * FROM equipamiento_hbqp WHERE id = $id";
    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['nombreEquipo'] = $row['nombreEquipo'];
        $datos['carpeta'] = $row['carpeta'];
        $nomEquipo = $datos['nombreEquipo'];
        $carpeta = $datos['carpeta'];
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
    <style>
        #mesInforme {
            display: none;
        }

        #rangoFecha {
            display: none;
        }
    </style>
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
        include '../sdo-templates/admin-sidebar.php';
        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <br>
            <!-- AVISOS -->
            <?php

            if (isset($_GET['noIdEnGasto'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No id en la interfaz de gasto',
                    })
                </script>

            <?php
            }

            if (isset($_GET['vaciosEnGasto'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Campos vacios en el formulario de gasto',
                    })
                </script>

            <?php
            }

            if (isset($_GET['noInsertGasto'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No insert en la tabla gasto',
                    })
                </script>

            <?php
            }

            if (isset($_GET['exitoCrearGasto'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Exito',
                        text: 'Exito al añadir el registro del gasto al equipo',
                    })
                </script>

            <?php
            }

            if (isset($_GET['exitoModificar'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Exito',
                        text: 'Exito al modificar el gasto seleccionado',
                    })
                </script>

            <?php
            }

            if (isset($_GET['noDatos'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'info',
                        title: 'Aviso',
                        text: 'No existen registros para mostrar en la fecha seleccionada',
                    })
                </script>

            <?php
            }




            ?>
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h1 class="card-title"> <b> Gastos del equipo - <?php echo $nomEquipo ?></b></h1>
                        </div>
                        <div class="row">
                            <!-- left column -->
                            <div class="col-md-12">
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <a href="visualizarEquipo-cc.php?id=<?php echo $id ?>" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp; <b>Volver Atras</b> </a>
                                    <br>
                                    <br>
                                    <input type="hidden" id="idHojaSeleccionada" value="<?php echo $idHoja ?>">
                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target=".modalGasto"> <b><i class="fas fa-plus"></i> Añadir Gasto</b> </button>

                                    <button type="button" class="btn btn-warning" onclick="agregarHojaVida();" data-toggle="modal" data-target=".generarInforme"> <b><i class="far fa-file-pdf"></i> Generar Informes</b> </button>

                                    <div class="modal fade generarInforme" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-warning">
                                                    <h5 class="modal-title"> <b>Generar Informe</b> </h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="../sdo-pdf/SDO-Informe-Gasto.php" method="POST">

                                                        <input type="hidden" name="idHoja" id="hojaSubir" value="<?php echo $idHoja ?>">

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

                                                        <div class="row" id="rangoFecha">
                                                            <div class="form-group col-md-12">
                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px">Desde :<code>*</code></label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                    </div>
                                                                    <input type="date" class="form-control form-control-sm" required id="fechaInicio" name="fechaInicio">

                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-12">
                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px">Hasta :<code>*</code></label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                    </div>
                                                                    <input type="date" class="form-control form-control-sm" onchange="ValidarFechas()" required id="fechaFin" name="fechaFin">

                                                                </div>
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal"> <b>Cerrar</b> </button>
                                                    <button type="submit" id="enviar" class="btn btn-primary"> <b>Generar Informe</b> </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade modalGasto" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info">
                                                    <h5 class="modal-title" id="exampleModalLabel"> <b>Formulario - Información del gasto</b> </h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="../sdo-funciones/guardarGasto-cc.php" method="POST" enctype="multipart/form-data">

                                                        <div class="row">
                                                            <div class="form-group col-md-4">
                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px">Nombre Mantenimiento:<code>*</code></label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control form-control-sm" required id="nomMantenimiento" name="nomMantenimiento">
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px">Hoja Técnica Asociada:</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="far fa-file-alt"></i></span>
                                                                    </div>
                                                                    <select name="idHojaTecnica" id="idHojaTecnica" class="form-control form-control-sm">
                                                                        <option value="" selected disabled>-- Seleccione --</option>
                                                                        <?php

                                                                        $sql = "SELECT * FROM ordenes_trabajos_general ORDER BY idOrden  DESC";
                                                                        $res = mysqli_query($conexion, $sql);

                                                                        while ($rowOrden = $res->fetch_assoc()) {
                                                                            $datos = [];
                                                                            $datos['idOrden'] = $rowOrden['idOrden'];
                                                                            $datos['nomOrden'] = $rowOrden['nomOrden'];

                                                                            $idOrden  = $datos['idOrden'];
                                                                            $nomOrden = $datos['nomOrden'];

                                                                        ?>

                                                                            <option value="<?php echo $idOrden; ?>"><?php echo  $nomOrden ?></option>


                                                                        <?php
                                                                        }


                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px">Tipo Mantención:<code>*</code></label>
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
                                                                        <option value="OTROS">OTROS - Tipos de trabajos</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-md-4">
                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px">Solicitud de Compra Asociada:</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-th-list"></i></span>
                                                                    </div>
                                                                    <select name="ordenCompra" id="ordenCompra" class="form-control form-control-sm">
                                                                        <option value="" selected disabled>-- Seleccione --</option>
                                                                        <?php
                                                                        $sql = "SELECT * FROM solicitudes_compra_sdo ORDER BY idSoliCompra  DESC";
                                                                        $res = mysqli_query($conexion, $sql);

                                                                        while ($rowSoli = $res->fetch_assoc()) {
                                                                            $datos = [];
                                                                            $datos['idSoliCompra'] = $rowSoli['idSoliCompra'];
                                                                            $datos['personalSoliCompra'] = $rowSoli['personalSoliCompra'];

                                                                            $idSoliCompra = $datos['idSoliCompra'];
                                                                            $personalSoliCompra = $datos['personalSoliCompra'];

                                                                        ?>

                                                                            <option value="<?php echo $idSoliCompra; ?>"><?php echo 'N° Interno SDO: ' . $idSoliCompra . ' - ' .  $personalSoliCompra ?></option>

                                                                        <?php
                                                                        }

                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px">Código Orden Compra:</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-code"></i></span>
                                                                    </div>
                                                                    <input type="text" min="1" class="form-control form-control-sm" id="codOrdenCompra" name="codOrdenCompra">
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <!-- <label for="customFile">Custom File</label> -->
                                                                <label for="exampleInputTareasControlCent" style="font-size:12px;">Subir Orden de Compra:</label><br>
                                                                <div class="custom-file">
                                                                    <input type="file" class="custom-file-input" id="archivoOC" name="archivoOC">
                                                                    <label class="custom-file-label" for="customFile" style="font-size:12px;"><b>Seleccionar Archivo...</b> </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-md-8">
                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px">Costo Total:<code>*</code></label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                                                    </div>
                                                                    <input type="number" required min="1" class="form-control form-control-sm" id="costoTotal" name="costoTotal">
                                                                </div>
                                                            </div>

                                                            <div class="form-group col-md-4">
                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px">Fecha Gasto:<code>*</code></label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="far fa-clock"></i></span>
                                                                    </div>
                                                                    <input type="date" name="fechaGasto" id="fechaGasto" required class="form-control form-control-sm">
                                                                    <!-- <select name="mesGasto" required id="mesGasto" class="form-control form-control-sm">
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
                                                                    </select> -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-md-8">
                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px">Encargado Mantenimiento:</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control form-control-sm" id="encargadoMant" name="encargadoMant">
                                                                </div>
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <input type="hidden" name="idHoja" id="idHoja" value="<?php echo $id ?>">
                                                    <input type="hidden" name="isEquipo" value="isEquipo">
                                                    <input type="hidden" name="carpeta" value="<?php echo $carpeta ?>">
                                                    <button type="button" class="btn btn-warning" data-dismiss="modal"> <b>Cerrar</b> </button>
                                                    <button type="submit" class="btn btn-success"> <b>Añadir Gasto</b> </button>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <br>
                                    <br>
                                    <div class="row">
                                        <div class="col-12">

                                            <table id="gasto_equipo" class="table">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th scope="col" class="text-center">N°</th>
                                                        <th scope="col" class="text-center">Fecha</th>
                                                        <th scope="col" class="text-center">Tipo Mantenimiento</th>
                                                        <th scope="col" class="text-center">Trabajo Realizado</th>
                                                        <th scope="col" class="text-center">Orden Compra</th>
                                                        <th scope="col" class="text-center">Monto</th>
                                                        <th scope="col" class="text-center">Encargado de Mantenimiento</th>
                                                        <th scope="col" class="text-center">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sql = "SELECT * FROM gastoEquipos2 WHERE idEquipo = $id ORDER BY fechaGasto ASC";
                                                    $res = mysqli_query($conexion, $sql);
                                                    $num = 1;
                                                    while ($row = $res->fetch_assoc()) {
                                                        $datos = [];
                                                        $datos['idGasto'] = $row['idGasto'];
                                                        $datos['fechaRegistro'] = $row['fechaRegistro'];
                                                        $datos['fechaGasto'] = $row['fechaGasto'];
                                                        $datos['mesGasto'] = $row['mesGasto'];
                                                        $datos['nomMantenimiento'] = $row['nomMantenimiento'];
                                                        $datos['idHojaTecnica'] = $row['idHojaTecnica'];
                                                        $datos['tipoMantencion'] = $row['tipoMantencion'];
                                                        $datos['solicitudCompra'] = $row['solicitudCompra'];
                                                        $datos['codOrdenCompra'] = $row['codOrdenCompra'];
                                                        $datos['costoTotal'] = $row['costoTotal'];
                                                        $datos['encargadoMant'] = $row['encargadoMant'];

                                                        $idGasto =  $datos['idGasto'];
                                                        $mesGasto = $datos['mesGasto'];
                                                        $fechaRegistro =  $datos['fechaRegistro'];
                                                        $fechaGasto = $datos['fechaGasto'];
                                                        $nomMantenimiento =  $datos['nomMantenimiento'];
                                                        $idHojaTecnica =  $datos['idHojaTecnica'];
                                                        $tipoMantencion =  $datos['tipoMantencion'];
                                                        $solicitudCompra =  $datos['solicitudCompra'];
                                                        $codOrdenCompra =  $datos['codOrdenCompra'];
                                                        $costoTotal =  $datos['costoTotal'];
                                                        $encargadoMant = $datos['encargadoMant'];

                                                        $mesArray = explode("-", $fechaRegistro);

                                                        $fechaGasto =  date('d-m-Y', (strtotime($fechaGasto)));

                                                    ?>
                                                        <tr>
                                                            <td style=" font-weight:bold; font-size: 12px" class="text-primary text-center"><?php echo $num; ?></td>
                                                            <td style=" font-weight:bold; font-size: 12px" class="text-primary text-center"><?php echo $fechaGasto ?></td>
                                                            <td style=" font-weight:bold; font-size: 12px" class="text-primary text-center"> <?php echo $tipoMantencion; ?></td>
                                                            <td style=" font-weight:bold; font-size: 12px" class="text-primary text-center"><?php echo strtoupper($nomMantenimiento); ?></td>
                                                            <td style=" font-weight:bold; font-size: 12px" class="text-primary text-center">
                                                                <?php echo strtoupper($codOrdenCompra); ?>
                                                            </td>
                                                            <td class="text-success text-center" style=" font-size: 12px"><b><?php
                                                                                                                    $costoTotal = moneda_chilena($costoTotal);

                                                                                                                    echo $costoTotal;
                                                                                                                    ?></b> </td>
                                                            <td style=" font-size: 12px; font-weight:bold; text-align:center" class="text-primary text-center">
                                                                <?php if ($encargadoMant == '') {
                                                                    echo '-';
                                                                } else {
                                                                    echo strtoupper($encargadoMant);
                                                                }; ?>
                                                            </td>
                                                            <td class="text-center">

                                                                <!-- <form action="../sdo-pdf/SDO-Resumen-Gasto.php" method="POST" style="display: inline;">
                                                                    <input type="hidden" value="<?php echo $idGasto ?>" name="idGasto">
                                                                    <input type="hidden" value="<?php echo $idHoja ?>" name="idHoja">
                                                                    <button type="submit" class="btn btn-sm btn-primary "><i class="far fa-eye"></i></button>
                                                                </form> -->

                                                                <form action="editarGasto.php" method="post" style="display:inline">
                                                                    <input type="hidden" value="<?php echo $idGasto ?>" name="idGasto">
                                                                    <input type="hidden" value="<?php echo $idHoja ?>" name="idHoja">
                                                                    <button class="btn btn-sm btn-warning"><i class="far fa-edit"></i></button>
                                                                </form>

                                                                <button class="btn btn-sm bg-purple" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-cloud-download-alt"></i></button>

                                                                <?php

                                                                $sqlArchivos = "SELECT * FROM archivos_gasto_equipos WHERE idGasto = $idGasto";
                                                                $resArchivos = mysqli_query($conexion, $sqlArchivos);

                                                                while ($rowArchivos = $resArchivos->fetch_assoc()) {
                                                                    $datos = [];
                                                                    $datos['idArchivoGE'] = $rowArchivos['idArchivoGE'];
                                                                    $idArchivoGE = $datos['idArchivoGE'];
                                                                }

                                                                ?>

                                                                <input type="hidden" id="hojaTecnicaModal" value="<?php echo $idGasto ?>">
                                                                <input type="hidden" id="idHojaModal" value="<?php echo $idHoja ?>">


                                                                <!-- Modal -->
                                                                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-purple">
                                                                                <h5 class="modal-title" id="exampleModalLabel">Descargar Archivos</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">


                                                                                <?php
                                                                                if (!empty($idHojaTecnica)) {
                                                                                ?>
                                                                                    <div class="col-12">
                                                                                        <form action="../sdo-pdf/SDO-OrdenTrabajoGeneral.php" target="_blank" method="POST">
                                                                                            <div class="info-box">
                                                                                                <span class="info-box-icon bg-primary elevation-1">
                                                                                                    <i class="fas fa-file-download"></i>
                                                                                                </span>
                                                                                                <div class="info-box-content">
                                                                                                    <button type="submit" class="btn btn-xs text-primary"><b>Hoja Técnica</b></button>
                                                                                                    <input type="hidden" name="idOrden" value="<?php echo $idHojaTecnica ?>">
                                                                                                </div>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                <?php
                                                                                }
                                                                                ?>
                                                                                <?php

                                                                                if (!empty($idArchivoGE)) {
                                                                                ?>
                                                                                    <div class="col-12">
                                                                                        <form action="gastoEquipo.php" method="POST">
                                                                                            <div class="info-box">
                                                                                                <span class="info-box-icon bg-success elevation-1">
                                                                                                    <i class="fas fa-file-download"></i>
                                                                                                </span>
                                                                                                <div class="info-box-content">
                                                                                                    <button type="submit" class="btn btn-xs text-success"><b>Orden de Compra</b></button>
                                                                                                    <input type="hidden" id="idGasto" name="idGasto" value="">
                                                                                                    <input type="hidden" id="idHojaArchivo" name="idEquipo" value="">

                                                                                                </div>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                <?php
                                                                                }

                                                                                ?>

                                                                                <!-- <div class="col-12 ">
                                                                                    <div class="info-box">
                                                                                        <span class="info-box-icon bg-danger elevation-1">
                                                                                            <i class="fas fa-file-download"></i>
                                                                                        </span>
                                                                                        <div class="info-box-content">
                                                                                            <button type="submit" class="btn btn-xs text-danger"><b>Resumen del gasto</b></button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div> -->
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <a class="btn btn-sm btn-danger eliminar" id="<?php echo $idGasto ?>"><i class="fas fa-trash-alt"></i></a>
                                                            </td>
                                                        </tr>

                                                    <?php
                                                        $num++;
                                                    }
                                                    ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr class="bg-info">
                                                        <th scope="col" class="text-center">N°</th>
                                                        <th scope="col" class="text-center">Fecha</th>
                                                        <th scope="col" class="text-center">Tipo Mantenimiento</th>
                                                        <th scope="col" class="text-center">Trabajo Realizado</th>
                                                        <th scope="col" class="text-center">Orden Compra</th>
                                                        <th scope="col" class="text-center">Monto</th>
                                                        <th scope="col" class="text-center">Encargado de Mantenimiento</th>
                                                        <th scope="col" class="text-center">Acciones</th>
                                                    </tr>
                                                </tfoot>
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
    <script>
        function mostrarInputs(valor) {

            var enviar = document.getElementById("enviar");

            //Mensual
            mesInforme = document.getElementById('mesInforme');
            mensual = document.getElementById('mensual');

            //Rango de fechas
            rangoFecha = document.getElementById('rangoFecha');
            fechaInicio = document.getElementById('fechaInicio');
            fechaFin = document.getElementById('fechaFin');

            if (valor == 'mensual') {
                enviar.disabled = false;

                mensual.required = true;
                mesInforme.style.display = 'block';

                //Configura rango fechas 
                rangoFecha.style.display = 'none';
                fechaInicio.required = false;
                fechaFin.required = false;
            } else if (valor == 'rangoFechas') {
                enviar.disabled = false;

                fechaInicio.required = true;
                fechaFin.required = true;
                rangoFecha.style.display = 'block';


                //Configura radio mensual
                mensual.required = false;
                mesInforme.style.display = 'none';
            } else if (valor == 'todos') {
                enviar.disabled = false;

                //Configura rango fechas 
                rangoFecha.style.display = 'none';
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
        function agregarHojaVida() {

            idHojaSeleccionada = document.getElementById('idHojaSeleccionada').value;
            hojaSubir = document.getElementById('hojaSubir').value;

            hojaSubir = idHojaSeleccionada;
        }
    </script>
    <script>
        function ValidarFechas() {
            var fechainicial = document.getElementById("fechaInicio").value;
            var fechafinal = document.getElementById("fechaFin").value;
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
        hojaTecnicaModal = document.getElementById('hojaTecnicaModal');
        idGasto = document.getElementById('idGasto');

        idHojaArchivo = document.getElementById('idHojaArchivo');
        idHojaModal = document.getElementById('idHojaModal');


        idGasto.value = hojaTecnicaModal.value;
        idHojaArchivo.value = idHojaModal.value;
    </script>
</body>

</html>