<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

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

date_default_timezone_set('America/Santiago');
// echo '';

if (isset($_POST['idCarpeta'])) {
    $idCarpeta = $_POST['idCarpeta'];
    $sqlGeneral = "SELECT * FROM categorias_equipos WHERE idCategoria = $idCarpeta";
    $res = mysqli_query($conexion, $sqlGeneral);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['nomCategoria'] = $row['nomCategoria'];
        $datos['imagenCategoria'] = $row['imagenCategoria'];
        $datos['idDepartamento'] = $row['idDepartamento'];

        $nomCategoria = $datos['nomCategoria'];
        $imagenCategoria = $datos['imagenCategoria'];
        $idDepartamento = $datos['idDepartamento'];
    }

    $_SESSION['idCarpeta'] = $idCarpeta;
} else if ($_SESSION['idCarpeta']) {
    $idCarpeta = $_SESSION['idCarpeta'];
    $sqlGeneral = "SELECT * FROM categorias_equipos WHERE idCategoria = $idCarpeta";
    $res = mysqli_query($conexion, $sqlGeneral);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['nomCategoria'] = $row['nomCategoria'];
        $datos['imagenCategoria'] = $row['imagenCategoria'];
        $datos['idDepartamento'] = $row['idDepartamento'];

        $nomCategoria = $datos['nomCategoria'];
        $imagenCategoria = $datos['imagenCategoria'];
        $idDepartamento = $datos['idDepartamento'];
    }
}
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

if (isset($_POST['idGasto'])) {
    $idGasto = $_POST['idGasto'];

    if ($idGasto > 0) {
        if (empty($idGasto) || $idGasto == '') {
        } else {

            ini_set("memory_limit", "1256M");
            $zip = new ZipArchive();
            $dir = "archivos-equipamiento/carpeta-" . $idCarpeta . "/gasto-" . $idGasto;
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
}
{
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Carpeta - Gastos</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap4.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>

    <!-- summernote -->
    <!-- DataTables -->
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/admin-sidebar.php';
    ?>
    <!-- /.navbar -->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
            <?php
            if (isset($_GET['exitoCrearGasto'])) {
                ?>
                <script>
                    Swal.fire(
                        'Exito!',
                        'Se agrego correctamente el gasto a la carpeta!',
                        'success'
                    )
                </script>
                <?php
            }
            if (isset($_GET['errorCrearGasto'])) {
                ?>
                <script>
                    Swal.fire(
                        'Error!',
                        'No fue posible agregar el gasto a la carpeta!',
                        'error'
                    )
                </script>
                <?php
            }
            if (isset($_GET['exitoEditarGasto'])) {
                ?>
                <script>
                    Swal.fire(
                        'Exito!',
                        'Se edito el gasto correctamente!',
                        'info'
                    )
                </script>
                <?php
            }
            if (isset($_GET['errorEditarGasto'])) {
                ?>
                <script>
                    Swal.fire(
                        'Error!',
                        'No fue posible editar el registro!',
                        'error'
                    )
                </script>
                <?php
            }

            if (isset($_GET['noDatos'])) {
                ?>
                <script>
                    Swal.fire(
                        'No datos!',
                        'No se encontraron datos para mostrar según la fecha solicitada!',
                        'info'
                    )
                </script>
                <?php
            }

            ?>
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-secondary">
                                <a href="carpeta-equipo.php" style="float: right;" class="btn btn-danger btn-xs"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <h1 style="font-size: 32px;" class="text-center text-bold text-muted">Gastos - <?php echo $nomCategoria ?> </h1>
                                        <hr>
                                        <br>
                                        <button type="button" class="btn btn-warning" onclick="agregarHojaVida();" data-toggle="modal" data-target=".generarInforme"><b><i class="far fa-file-pdf"></i> Generar Informes</b></button>

                                        <div class="modal fade generarInforme" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-warning">
                                                        <h5 class="modal-title"><b>Generar Informe</b></h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="../sdo-pdf/SDO-Informe-Gasto-Carpeta2.php" target="_blank" method="POST">

                                                            <input type="hidden" name="idCategoria" value="<?php echo $idCarpeta ?>">

                                                            <div class="row">
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Tipo informe:<code>*</code></label>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-file-archive"></i></span>
                                                                        </div>
                                                                        <select type="text" class="form-control form-control-sm" required id="tipoInforme" name="tipoInforme">
                                                                            <option value="" disabled selected>-- Seleccione --</option>
                                                                            <option value="Completo">Completo - Carpeta y equipos</option>
                                                                            <option value="Carpeta">Solo Carpeta</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

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
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><b>Cerrar</b></button>
                                                        <button type="submit" id="enviar" class="btn btn-primary"><b>Generar Informe</b></button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <button class="btn btn-info ml-2 text-center" data-toggle="modal" data-target=".modalGasto"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp; <b>Añadir Un Gasto A La Carpeta</b></button>
                                        <br>
                                        <div class="modal fade modalGasto" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-info">
                                                        <h5 class="modal-title" id="exampleModalLabel"><b>Formulario - Información del gasto</b></h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="../sdo-funciones/guardar-gasto-carpeta.php" method="POST" enctype="multipart/form-data">
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
                                                                            $sql = "SELECT * FROM ordenes_trabajos_general WHERE idCategoria is null OR idCategoria = 0 ORDER BY idOrden  DESC";
                                                                            $res = mysqli_query($conexion, $sql);
                                                                            while ($rowOrden = $res->fetch_assoc()) {
                                                                                $datos = [];
                                                                                $datos['idOrden'] = $rowOrden['idOrden'];
                                                                                $datos['nomOrden'] = $rowOrden['nomOrden'];
                                                                                $idOrden = $datos['idOrden'];
                                                                                $nomOrden = $datos['nomOrden'];
                                                                                ?>
                                                                                <option value="<?php echo $idOrden; ?>"><?php echo $nomOrden ?></option>
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
                                                                            <option value="OM">OM - Obras Menores</option>
                                                                            <option value="IS">IS - Inspección/Supervisión</option>
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
                                                                                <option value="<?php echo $idSoliCompra; ?>"><?php echo 'N° Interno SDO: ' . $idSoliCompra . ' - ' . $personalSoliCompra ?></option>
                                                                                <?php
                                                                            }

                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Archivo Externo:</label>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-th-list"></i></span>
                                                                        </div>
                                                                        <select name="ordenCompra" id="ordenCompra" class="form-control form-control-sm">
                                                                            <option value="" selected disabled>-- Seleccione --</option>
                                                                            <?php
                                                                            $sql = "SELECT * FROM archivos_equipamiento_hbqp ";
                                                                            $res = mysqli_query($conexion, $sql);
                                                                            while ($rowSoli = $res->fetch_assoc()) {
                                                                                $datos = [];
                                                                                $datos['id'] = $rowSoli['id'];
                                                                                $datos['nomArchivo'] = $rowSoli['nomArchivo'];

                                                                                $id = $datos['id'];
                                                                                $nomArchivo = $datos['nomArchivo'];
                                                                                ?>
                                                                                <option value="<?php echo $id; ?>"><?php echo $nomArchivo ?></option>
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
                                                                        <input type="number" required min="0" class="form-control form-control-sm" id="costoTotal" name="costoTotal">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group col-md-4">
                                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Fecha Gasto:<code>*</code></label>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="far fa-clock"></i></span>
                                                                        </div>
                                                                        <input type="date" name="fechaGasto" id="fechaGasto" required class="form-control form-control-sm">
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
                                                        <input type="hidden" name="idCategoria" id="idCategoria" value="<?php echo $idCarpeta ?>">
                                                        <button type="button" class="btn btn-warning" data-dismiss="modal"><b>Cerrar</b></button>
                                                        <button type="submit" class="btn btn-success"><b>Añadir Gasto</b></button>
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <br>
                                        <table id="tabla-hojas-tecnicas" class="table table-hover  table-bordered table-sm">
                                            <thead class="bg-secondary">
                                            <tr>
                                                <th scope="col" class="text-center">N°</th>
                                                <th scope="col" class="text-center">Fecha</th>
                                                <th scope="col" class="text-center">Tipo</th>
                                                <th scope="col" class="text-center">Trabajo Realizado</th>
                                                <th scope="col" class="text-center">Solicitud Compra</th>
                                                <th scope="col" class="text-center">Hoja Técnica</th>
                                                <th scope="col" class="text-center">Monto</th>
                                                <th scope="col" class="text-center">Encargado de Mantenimiento</th>
                                                <th scope="col" class="text-center">Equipo</th>
                                                <th scope="col" class="text-center">Acciones</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $sqlEquipo = "SELECT * FROM gasto_equipo_carpeta WHERE idCategoria = $idCarpeta ORDER BY fechaGasto DESC";
                                            $resEquipo = mysqli_query($conexion, $sqlEquipo);
                                            $num = 1;
                                            while ($rowEquipo = $resEquipo->fetch_assoc()) {
                                                $datos = [];
                                                $datos['idGasto'] = $rowEquipo['idGasto'];
                                                $datos['fechaRegistro'] = $rowEquipo['fechaRegistro'];
                                                $datos['fechaGasto'] = $rowEquipo['fechaGasto'];
                                                $datos['mesGasto'] = $rowEquipo['mesGasto'];
                                                $datos['nomMantenimiento'] = $rowEquipo['nomMantenimiento'];
                                                $datos['idHojaTecnica'] = $rowEquipo['idHojaTecnica'];
                                                $datos['tipoMantencion'] = $rowEquipo['tipoMantencion'];
                                                $datos['solicitudCompra'] = $rowEquipo['solicitudCompra'];
                                                $datos['codOrdenCompra'] = $rowEquipo['codOrdenCompra'];
                                                $datos['costoTotal'] = $rowEquipo['costoTotal'];
                                                $datos['encargadoMant'] = $rowEquipo['encargadoMant'];
                                                $datos['idEquipo'] = $rowEquipo['idEquipo'];

                                                $idGasto = $datos['idGasto'];
                                                $fechaRegistro = $datos['fechaRegistro'];
                                                $mesGasto = $datos['mesGasto'];
                                                $fechaGasto = $datos['fechaGasto'];
                                                $nomMantenimiento = $datos['nomMantenimiento'];
                                                $idHojaTecnica = $datos['idHojaTecnica'];
                                                $tipoMantencion = $datos['tipoMantencion'];
                                                $solicitudCompra = $datos['solicitudCompra'];
                                                $codOrdenCompra = $datos['codOrdenCompra'];
                                                $costoTotal = $datos['costoTotal'];
                                                $encargadoMant = $datos['encargadoMant'];
                                                $idEquipo = $datos['idEquipo'];

                                                $formatoFecha = date('d/m/Y', strtotime($fechaGasto));


                                                $costoTotal = moneda_chilena($costoTotal);

                                                ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $num ?></td>
                                                    <td class="text-center"><?php echo $formatoFecha ?></td>
                                                    <td class="text-center"><?php echo $tipoMantencion ?></td>
                                                    <td class="text-center"><?php echo $nomMantenimiento ?></td>
                                                    <td class="text-center">
                                                        <?php

                                                        if ($solicitudCompra == '') {
                                                            echo '-';
                                                        } else {
                                                            ?>
                                                            <form action='../<?php echo $ruta ?>/soliCompra_visualizarSC_sdo.php' target="_blank" method="POST">
                                                                <input type="hidden" name="idSoliCompra" value="<?php echo $solicitudCompra ?>">
                                                                <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i></button>
                                                            </form>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php

                                                        if ($idHojaTecnica == '') {
                                                            echo '-';
                                                        } else {
                                                            ?>
                                                            <form action="../sdo-pdf/SDO-OrdenTrabajoGeneral.php" target="_blank" method="POST">
                                                                <input type="hidden" name="idOrden" value="<?php echo $idHojaTecnica ?>">
                                                                <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i></button>
                                                            </form>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-center text-success text-bold"><?php echo $costoTotal ?></td>
                                                    <td class="text-center">
                                                        <?php
                                                        if ($encargadoMant == '') {
                                                            echo '-';
                                                        } else {
                                                            echo $encargadoMant;
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php
                                                        if ($idEquipo == '') {
                                                            echo '-';
                                                        } else {

                                                            $tipoMenu = obtenerTipoMenu($idEquipo);

                                                            if ($tipoMenu == 'nuevo') {
                                                                $rutaMenu = 'equipo_menuNuevo.php';
                                                            } else {
                                                                $rutaMenu = 'equipo_menuAntiguo.php';

                                                            }


                                                            ?>
                                                            <form action="<?php echo $rutaMenu; ?>" method="POST">
                                                                <input type="hidden" name="idEquipo" value="<?php echo $idEquipo ?>">
                                                                <input type="hidden" name="idCategoria" value="<?php echo $idCarpeta ?>">
                                                                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></button>
                                                            </form>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php
                                                        $archivos_orden_general = "SELECT * FROM archivos_gasto_carpeta WHERE idGasto = $idGasto";
                                                        $res = mysqli_query($conexion, $archivos_orden_general);

                                                        while ($row = $res->fetch_assoc()) {
                                                            $datos = [];
                                                            $datos['rutaArchivoGE'] = $row['rutaArchivoGE'];
                                                            $rutaArchivoGE = $datos['rutaArchivoGE'];
                                                        }

                                                        if ($res && mysqli_affected_rows($conexion) > 0) {
                                                            ?>
                                                            <form action="carpeta-gastos.php" method="POST" style="display: inline;">
                                                                <input type="hidden" name="idGasto" value="<?php echo $idGasto ?>">
                                                                <input type="hidden" name="rutaArchivoGE" value="<?php echo $rutaArchivoGE ?>">
                                                                <button type="submit" class="btn btn-sm btn-info "><i class="fas fa-download"></i></button>
                                                            </form>
                                                            <?php
                                                        }
                                                        ?>

                                                        <form action="editar-gasto-carpeta.php" style="display: inline;" method="POST">
                                                            <input type="hidden" name="idCarpeta" value="<?php echo $idCarpeta ?>">
                                                            <input type="hidden" name="idGasto" value="<?php echo $idGasto ?>">
                                                            <button type="submit" class="btn btn-sm bg-pink "><i class="fas fa-pen"></i></button>
                                                        </form>
                                                        <a class="btn btn-sm btn-danger eliminar" id="<?php echo $idGasto ?>"><i class="fas fa-trash-alt"></i></a>
                                                    </td>
                                                </tr>
                                                <?php
                                                $num++;
                                            }
                                            ?>
                                            </tbody>
                                            <tfoot class="bg-secondary">
                                            <tr>
                                                <th scope="col" class="text-center">N°</th>
                                                <th scope="col" class="text-center">Fecha</th>
                                                <th scope="col" class="text-center">Tipo</th>
                                                <th scope="col" class="text-center">Trabajo Realizado</th>
                                                <th scope="col" class="text-center">Solicitud Compra</th>
                                                <th scope="col" class="text-center">Hoja Técnica</th>
                                                <th scope="col" class="text-center">Monto</th>
                                                <th scope="col" class="text-center">Encargado de Mantenimiento</th>
                                                <th scope="col" class="text-center">Equipo</th>
                                                <th scope="col" class="text-center">Acciones</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
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
<!-- jQuery UI 1.11.4 -->
<script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="../plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="../plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="../plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="../plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="../plugins/moment/moment.min.js"></script>
<script src="../plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<!-- overlayScrollbars -->
<script src="../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Page specific script -->
<script src="js/dark-mode.js"></script>
<script src="../dist/js/adminlte.min.js"></script>
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
<script>
    $(document).ready(function () {
        $('#tabla-hojas-tecnicas').DataTable({
            "paging": true,
            "iDisplayLength": 10,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
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
            },
        });
    });
</script>
<script>
    $(document).ready(function () {
        $('#hoja_tecnicas').DataTable({
            "paging": true,
            "iDisplayLength": 10,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
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
            },
        });
    });
</script>
<script>
    $(document).ready(
        $('.eliminar').click(function (e) {
            e.preventDefault();
            let id = $(this).attr('id');
            var dataString = 'id=' + id;
            Swal.fire({
                icon: 'question',
                title: '¿Eliminar el registro de este gasto?',
                text: 'Si el registro del gasto  le pertenece a un equipo, tambien se eliminara del registro',
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
                        url: '../sdo-funciones/eliminar-gasto-equipo.php',
                        type: 'POST',
                        data: dataString,
                        success: function (response) {
                            console.log('eliminado.. ' + dataString);
                            Swal.fire({
                                icon: 'success',
                                title: 'Registro eliminado',
                                showConfirmButton: false,
                                timer: 2000,
                            })
                            setTimeout(function () {
                                location.reload();
                            }, 1800)
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
</body>

</html>