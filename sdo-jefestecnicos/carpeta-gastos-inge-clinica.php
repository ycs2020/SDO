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

$idDepartamento1 = $_SESSION['idDepartamento'];

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

function crearYDescargarZip($idGasto, $tipo, $idCarpeta, $idEquipo) {
    ini_set("memory_limit", "1256M");
    $zip = new ZipArchive();
    $dir = "../archivos-equipamiento-{$tipo}-inge-clinica/carpeta-{$idCarpeta}/gasto-{$idGasto}";
    $dir2 = $dir . "/";
    $archivoZip = "OrdenCompra-Equipo-{$idEquipo}-Gasto-{$idGasto}.zip";

    // Comprobar si el directorio existe
    if (!is_dir($dir)) {
        echo "Error: El directorio no existe: " . $dir;
        return;
    }

    if ($zip->open($archivoZip, ZipArchive::CREATE) === true) {
        agregar_zip($dir2, $zip);
        $zip->close();

        // Verificar si el archivo ZIP se creó
        if (file_exists($archivoZip)) {
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
            unlink($archivoZip); // Eliminar el archivo ZIP después de la descarga
            exit;
        } else {
            echo "Error: El archivo ZIP no se creó.";
        }
    } else {
        echo "Error: No se pudo abrir el archivo ZIP.";
    }
}

if (isset($_POST['idGasto'])) {
    $idGasto = $_POST['idGasto'];
    if ($idGasto > 0) {
        crearYDescargarZip($idGasto, 'oc', $idCarpeta, $idEquipo);
    }
}

if (isset($_POST['idGastoSC'])) {
    $idGastoSC = $_POST['idGastoSC'];
    if ($idGastoSC > 0) {
        crearYDescargarZip($idGastoSC, 'sc', $idCarpeta, $idEquipo);
    }
}

if (isset($_POST['idGastoHT'])) {
    $idGastoHT = $_POST['idGastoHT'];
    if ($idGastoHT > 0) {
        crearYDescargarZip($idGastoHT, 'ht', $idCarpeta, $idEquipo);
    }
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
    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <!-- summernote -->
    <!-- DataTables -->
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
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
    <?php
    include '../sdo-templates/jefes-sidebar.php';
    ?>
    <div class="content-wrapper">
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
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-secondary">
                                <?php
                                if($idDepartamento1 == 6){
                                    ?>
                                    <a href="carpeta-equipo-inge-clinica.php" style="float: right;" class="btn btn-danger btn-xs"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <?php
                                }else{
                                    ?>
                                    <a href="carpeta-equipo.php" style="float: right;" class="btn btn-danger btn-xs"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <h1 style="font-size: 32px;" class="text-center text-bold text-muted">Gastos - <?php echo $nomCategoria ?> </h1>
                                        <hr>
                                        <h5>&nbsp;&nbsp;Colores para Identificar</h5>
                                        <ul>
                                            <li><i style="color: #F7D44A; text-align: 0 0 3px #000" class="fa fa-circle nav-icon">&nbsp;&nbsp;</i> Gastos de la carpeta</li>
                                        </ul>
                                        <hr>
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
                                                        <form action="../sdo-pdf/SDO-Informe-Gasto-Carpeta-Inge-Clinica.php" target="_blank" method="POST">

                                                            <input type="hidden" name="idCategoria"  value="<?php echo $idCarpeta ?>">
                                                            <?php
                                                            $year = date('Y');
                                                            ?>
                                                            <input type="hidden" name="year" value="<?php echo $year?>">
                                                            <div class="row" >
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
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal"> <b>Cerrar</b> </button>
                                                        <button type="submit" id="enviar" class="btn btn-primary"> <b>Generar Informe</b> </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <button class="btn btn-info ml-2 text-center" data-toggle="modal" data-target=".modalGasto"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp; <b>Añadir Un Gasto A La Carpeta</b> </button>
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
                                                        <form action="../sdo-funciones/guardar-gasto-carpeta-inge-clinica.php" method="POST" enctype="multipart/form-data">
                                                            <input type="hidden" name="idCategoria" id="idCategoria" value="<?php echo $idCarpeta ?>">
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
                                                                            <input type="file" class="custom-file-input" id="registrosAc" name="registrosAc">
                                                                            <label class="custom-file-label" for="customFileInge" style="font-size:12px;"><b>Seleccionar Archivo...</b> </label>                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Tipo de Intervención:<code>*</code></label>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-th-list"></i></span>
                                                                        </div>
                                                                        <select class="form-control" id="tipoIntervencion1" name="tipoIntervencion1">
                                                                            <option value="" selected disabled>Seleccion...</option>
                                                                            <option value="MPPC">MPPC</option>
                                                                            <option value="MPPA">MPPA</option>
                                                                            <option value="PREDEC">PREDEC.</option>
                                                                            <option value="REPARAT">REPARAT.</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-md-4">
                                                                    <label for="solicitudCompraAc" style="font-size:12px">Subir solicitud de Compra Asociada:</label>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-th-list"></i></span>
                                                                        </div>
                                                                        <input type="file" class="custom-file-input" id="solicitudCompraAc" name="solicitudCompraAc">
                                                                        <label class="custom-file-label" for="solicitudCompraAc" style="font-size:12px;"><b>Seleccionar Archivo...</b></label>
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
                                                                        <input type="number" required  class="form-control form-control-sm" id="costoTotal" name="costoTotal">
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
                                                        <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipo ?>">
                                                        <button type="button" class="btn btn-warning" data-dismiss="modal"> <b>Cerrar</b> </button>
                                                        <button type="submit" class="btn btn-success"> <b>Añadir Gasto</b> </button>
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <table id="tabla-hojas-tecnicas" class="table table-hover  table-bordered table-sm">
                                            <thead class="bg-secondary">
                                            <tr>
                                                <th scope="col" class="text-center">N°</th>
                                                <th scope="col" class="text-center">Fecha</th>
                                                <th scope="col" class="text-center">Tipo</th>
                                                <th scope="col" class="text-center">Trabajo Realizado</th>
                                                <th scope="col" class="text-center">Hojas Técnicas</th>
                                                <th scope="col" class="text-center">Solicitud Compra</th>
                                                <th scope="col" class="text-center">Orden de Compra</th>
                                                <th scope="col" class="text-center">Monto</th>
                                                <th scope="col" class="text-center">Encargado de Mantenimiento</th>
                                                <th scope="col" class="text-center">Acciones</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $sqlEquipo = "SELECT * FROM gasto_equipo_carpeta_inge_clinica WHERE idCategoria = $idCarpeta ORDER BY fechaGasto DESC";
                                            $resEquipo = mysqli_query($conexion, $sqlEquipo);
                                            $num = 1;
                                            while ($rowEquipo = $resEquipo->fetch_assoc()) {
                                                $datos = [];
                                                $datos['id'] = $rowEquipo['id'];
                                                $datos['fechaRegistro'] = $rowEquipo['fechaRegistro'];
                                                $datos['fechaGasto'] = $rowEquipo['fechaGasto'];
                                                $datos['mesGasto'] = $rowEquipo['mesGasto'];
                                                $datos['nombreMantenimiento'] = $rowEquipo['nombreMantenimiento'];
                                                $datos['idHojaTecnica'] = $rowEquipo['idHojaTecnica'];
                                                $datos['tipoIntervencion'] = $rowEquipo['tipoIntervencion'];
                                                //$datos['solicitudCompra'] = $rowEquipo['solicitudCompra'];
                                                $datos['codOrdenCompra'] = $rowEquipo['codOrdenCompra'];
                                                $datos['costoTotal'] = $rowEquipo['costoTotal'];
                                                $datos['encargadoMant'] = $rowEquipo['encargadoMant'];
                                                $datos['idEquipo'] = $rowEquipo['idEquipo'];

                                                $idGasto = $datos['id'];
                                                $fechaRegistro = $datos['fechaRegistro'];
                                                $mesGasto = $datos['mesGasto'];
                                                $fechaGasto = $datos['fechaGasto'];
                                                $nomMantenimiento = $datos['nombreMantenimiento'];
                                                $tipoMantencion = $datos['tipoIntervencion'];
                                                //$solicitudCompra = $datos['solicitudCompra'];
                                                $codOrdenCompra = $datos['codOrdenCompra'];
                                                $costoTotal = $datos['costoTotal'];
                                                $encargadoMant = $datos['encargadoMant'];
                                                $idEquipo = $datos['idEquipo'];

                                                $formatoFecha = date('d/m/Y', strtotime($fechaGasto));


                                                $color = "";

                                                if($idEquipo == '' || $idEquipo == 0 || empty($idEquipo)){
                                                    $color = "bg-warning";
                                                }else{
                                                    $color = "";
                                                }



                                                $costoTotal = moneda_chilena($costoTotal);
                                                ?>
                                                <tr class="<?php echo $color?>">
                                                    <td class="text-center"><?php echo $num ?></td>
                                                    <td class="text-center"><?php echo $formatoFecha ?></td>
                                                    <td class="text-center"><?php echo $tipoMantencion ?></td>
                                                    <td class="text-center"><?php echo $nomMantenimiento ?></td>
                                                    <td class="text-center">
                                                        <?php
                                                        $sqlHT = "SELECT COUNT(*) as totalHT FROM gastos_ht_archivo_inge_clinica WHERE idGasto = $idGasto";
                                                        $resHT = mysqli_query($conexion,$sqlHT);
                                                        while($rowHT = $resHT->fetch_assoc()){
                                                            $totalHT = $rowHT['totalHT'];
                                                        }
                                                        if($totalHT > 0){
                                                            ?>
                                                            <form action="carpeta-gastos-inge-clinica.php" method="POST" style="display: inline;">
                                                                <input type="hidden" name="idGastoHT" value="<?php echo $idGasto ?>">
                                                                <button type="submit" class="btn btn-block btn-info "><i class="fas fa-download"></i> Descargar Hoja Técnica</button>
                                                            </form>
                                                            <?php
                                                        }else{
                                                            ?>
                                                            Sin Información
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php
                                                        $sqlSoliCompra = "SELECT COUNT(*) as totalSoli FROM gastos_sc_archivo_inge_clinica WHERE idGasto = $idGasto";
                                                        $resSoliCompra = mysqli_query($conexion,$sqlSoliCompra);
                                                        while($rowSoliCompra = $resSoliCompra->fetch_assoc()){
                                                            $totalSC = $rowSoliCompra['totalSoli'];
                                                        }
                                                        if($totalSC > 0){
                                                            ?>
                                                            <form action="carpeta-gastos-inge-clinica.php" method="POST" style="display: inline;">
                                                                <input type="hidden" name="idGastoSC" value="<?php echo $idGasto ?>">
                                                                <button type="submit" class="btn btn-block btn-info "><i class="fas fa-download"></i> Descargar Solicitud de Compra</button>
                                                            </form>
                                                            <?php
                                                        }else{
                                                            ?>
                                                            Sin Información
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $sqlOrdenCompra = "SELECT COUNT(*) AS total FROM `gastos_oc_archivo_inge_clinica` WHERE idGasto = $idGasto";
                                                        $resOrdenCompra = mysqli_query($conexion,$sqlOrdenCompra);
                                                        while($rowOrdenCompra = $resOrdenCompra->fetch_assoc()){
                                                            $total = $rowOrdenCompra['total'];
                                                        }
                                                        if($total > 0){
                                                            ?>
                                                            <form action="carpeta-gastos-inge-clinica.php" method="POST" style="display: inline;">
                                                                <input type="hidden" name="idGasto" value="<?php echo $idGasto ?>">
                                                                <button type="submit" class="btn btn-block btn-info "><i class="fas fa-download"></i> Descargar Orden de Compra</button>
                                                            </form>
                                                            <?php
                                                        }else{
                                                            ?>
                                                            Sin Información
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-center text-success text-bold"><?php echo $costoTotal ?></td>
                                                    <td class="text-center">
                                                        <?php echo $encargadoMant?>
                                                    </td>
                                                    <td class="text-center text-success text-bold">
                                                        <form action="editar-gasto-carpeta-inge-clinica.php" style="display: inline;" method="POST">
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
                                                <th scope="col" class="text-center">Hojas Técnicas</th>
                                                <th scope="col" class="text-center">Solicitud Compra</th>
                                                <th scope="col" class="text-center">Orden de Compra</th>
                                                <th scope="col" class="text-center">Monto</th>
                                                <th scope="col" class="text-center">Encargado de Mantenimiento</th>
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
            </div>
        </section>
    </div>
    <footer class="main-footer">
        <strong>Copyright &copy; 2022 - ∞. Derechos Reservados</strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
</div>
<script>
    $(document).ready(
        $('.eliminar').click(function (e) {
            e.preventDefault();
            let id = $(this).attr('id');
            var dataString = 'id=' + id;
            console.log(dataString);
            Swal.fire({
                icon: 'question',
                title: '¿Eliminar este gasto?',
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
                        url: '../sdo-funciones/eliminar-gasto-equipo-inge.php',
                        type: 'POST',
                        data: dataString,
                        success: function (response) {
                            console.log('eliminado.. ' + dataString);
                            Swal.fire({
                                icon: 'success',
                                title: 'Gasto eliminado con exito',
                                showConfirmButton: false,
                                timer: 2000,
                            })
                            setTimeout(function () {
                                location.reload();
                            }, 2100)
                        }
                    })
                }
            })
        })
    )
</script>
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
    $(document).ready(function() {
        $('#tabla-hojas-tecnicas').DataTable({
            "paging": true,
            "iDisplayLength": 50,
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