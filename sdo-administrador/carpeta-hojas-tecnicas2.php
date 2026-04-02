<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');
// echo '';
$descargaConfirma = "";
if (isset($_POST['idOrden'])) {

    $idOrden = $_POST['idOrden'];
    $folio = $_POST['folio'];

    if ($idOrden > 0) {

        ini_set("memory_limit", "1256M");
        $zip = new ZipArchive();
        $dir = '../sdo-archivos-orden/' . $folio;
        $dir2 = $dir . "/";
        $rutaFinal = "../sdo-administrador/";
        $archivoZip = "Archivos-" . $folio . ".zip";

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
            } else {
                $descargaConfirma .= "N";
            }
        }
    }
} {
}

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

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Carpeta - Sistema</title>
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
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Show div by removing inline display none style rule
            $(".show-realizar").click(function() {
                $("#rowMenu2").show();
                $("#rowMenu1").hide();
                $("#listarSC").show();
                $("#realizarSC").hide();
            });

            // Show div by removing inline display none style rule
            $(".show-listar").click(function() {
                $("#rowMenu2").hide();
                $("#rowMenu1").show();
                $("#listarSC").hide();
                $("#realizarSC").show();
            });
        });
    </script>
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
            if (isset($_GET['exitoAgregarHoja'])) {
                ?>
                <script>
                    Swal.fire(
                        'Exito!',
                        'Se agrego correctamente la hoja técnica a la carpeta!',
                        'success'
                    )
                </script>
                <?php
            }
            if (isset($_GET['errorAgregarHoja'])) {
                ?>
                <script>
                    Swal.fire(
                        'Error!',
                        'No fue posible agregar la hoja técnica a la carpeta!',
                        'error'
                    )
                </script>
                <?php
            }
            if (isset($_GET['noExisteHojaTecnica'])) {
                ?>
                <script>
                    Swal.fire(
                        'Error!',
                        'Esta hoja técnica no existe, intente con otra!',
                        'error'
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
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <a href="carpeta-equipo.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body" style="text-align:center;">
                                <button class="btn bg-pink show-realizar"  id="realizarSC" name="realizarSC"><i class="fas fa-eye"></i> Visualizar Hojas Técnicas Externas</button>
                                <button class="btn bg-purple show-listar" id="listarSC" style="display:none;" name="listarSC"><i class="fas fa-eye"></i> Visualizar Hojas Técnicas Internas</button>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>

                <div class="row" name="rowMenu1" id="rowMenu1">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <h1 style="font-size: 32px;" class="text-center text-bold text-muted">Hojas Técnicas Internas - <?php echo $nomCategoria ?> </h1>
                                        <h5>&nbsp;&nbsp; Colores para Identificar:</h5>
                                        <ul>
                                            <li><i style="color: #FFC107; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Hoja Técnica De La Carpeta</li>
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
                                                        <form action="../sdo-pdf/SDO-Informe-Gasto-Carpeta.php" target="_blank" method="POST">

                                                            <input type="hidden" name="idCategoria"  value="<?php echo $idCarpeta ?>">

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
                                        <button class="btn btn-primary text-center ml-2" data-toggle="modal" data-target="#modalHojas"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp; <b>Añadir Hoja Técnica Interna A La Carpeta</b> </button>
                                        <br>
                                        <div class="modal fade" id="modalHojas" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalHojasLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary">
                                                        <h5 class="modal-title" id="modalHojasLabel">Escoja la hoja técnica a añadir</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table id="hoja_tecnicas" class="table table-sm table-hover  table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th scope="col" class="text-center">N°</th>
                                                                <th scope="col" class="text-center">Nombre</th>
                                                                <th scope="col" class="text-center">Tecnico Recepcionador</th>
                                                                <th scope="col" class="text-center">Ticket Asociado</th>
                                                                <th scope="col" class="text-center">Seleccionar</th>
                                                                <th scope="col" class="text-center">Ver Hojas Técnicas</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php

                                                            $sqlHojaTec1 = "SELECT * FROM ordenes_trabajos_general WHERE idEquipo is NULL AND idCategoria is NULL OR idEquipo = 0 OR idCategoria = 0 ORDER BY idOrden DESC";
                                                            $resHojaTec1 = mysqli_query($conexion, $sqlHojaTec1);

                                                            $num = 1;

                                                            while ($rowHojaTec1 = $resHojaTec1->fetch_assoc()) {
                                                                $datos = [];
                                                                $datos['idOrden'] = $rowHojaTec1['idOrden'];
                                                                $datos['nomOrden'] = $rowHojaTec1['nomOrden'];
                                                                $datos['nomTecnicoRecepcionador'] = $rowHojaTec1['nomTecnicoRecepcionador'];
                                                                $datos['ticketAsociado'] = $rowHojaTec1['ticketAsociado'];

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

                                                                        <form action="../sdo-funciones/agregar-hoja-tecnica-carpeta.php" method="POST">
                                                                            <input type="hidden" name="idCategoria" value="<?php echo $idCarpeta ?>">
                                                                            <input type="hidden" name="idHojaTecnica" value="<?php echo $idOrden ?>">
                                                                            <input type="submit" value="Añadir Esta Hoja" class="btn btn-danger">
                                                                        </form>

                                                                    </td>
                                                                    <td>
                                                                        <form action="../sdo-pdf/SDO-OrdenTrabajoGeneral.php" target="_blank" method="POST" style="display: inline;">
                                                                            <input type="hidden" name="idOrden" value="<?php echo $idOrden ?>">
                                                                            <center>
                                                                                <button type="submit" class="btn btn-sm btn-info"><i class="far fa-eye"></i></button>
                                                                            </center>
                                                                        </form>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                                $num++;
                                                            }
                                                            ?>
                                                            </tbody>
                                                            <tfoot>
                                                            <tr>
                                                                <th scope="col" class="text-center">N°</th>
                                                                <th scope="col" class="text-center">Nombre</th>
                                                                <th scope="col" class="text-center">Tecnico Recepcionador</th>
                                                                <th scope="col" class="text-center">Ticket Asociado</th>
                                                                <th scope="col" class="text-center">Seleccionar</th>
                                                                <th scope="col" class="text-center">Ver Hojas Técnicas</th>

                                                            </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div> <!--Hasta aqui-->
                                        </div>
                                        <br>
                                        <table id="tabla-hojas-tecnicas" class="table table-hover  table-bordered table-sm">
                                            <thead class="bg-secondary">
                                            <tr>
                                                <th scope="col" class="text-center">N°</th>
                                                <th scope="col" class="text-center">Nombre</th>
                                                <th scope="col" class="text-center">Tipo</th>
                                                <th scope="col" class="text-center">Tecnico Recepcionador</th>
                                                <th scope="col" class="text-center">Ticket Asociado</th>
                                                <th scope="col" class="text-center">Equipo</th>
                                                <th scope="col" class="text-center">Acciones</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $sqlEquipo = "SELECT * FROM ordenes_trabajos_general WHERE idCategoria = $idCarpeta ORDER BY fechaOrden DESC";
                                            $resEquipo = mysqli_query($conexion, $sqlEquipo);
                                            $num = 1;
                                            while ($rowEquipo = $resEquipo->fetch_assoc()) {
                                                $datos = [];
                                                $datos['idOrden'] = $rowEquipo['idOrden'];
                                                $datos['nomOrden'] = $rowEquipo['nomOrden'];
                                                $datos['nomTecnicoRecepcionador'] = $rowEquipo['nomTecnicoRecepcionador'];
                                                $datos['ticketAsociado'] = $rowEquipo['ticketAsociado'];
                                                $datos['idEquipo'] = $rowEquipo['idEquipo'];
                                                $datos['folioOrden'] = $rowEquipo['folioOrden'];
                                                $datos['tipoMantencion'] = $rowEquipo['tipoMantencion'];

                                                $idOrden = $datos['idOrden'];
                                                $nomOrden = $datos['nomOrden'];
                                                $nomTecnicoRecepcionador = $datos['nomTecnicoRecepcionador'];
                                                $ticketAsociado = $datos['ticketAsociado'];
                                                $idEquipo = $datos['idEquipo'];
                                                $folioOrden = $datos['folioOrden'];
                                                $tipoMantencion = $datos['tipoMantencion'];

                                                if ($idEquipo == '') {
                                                    $color = '#FFC107';
                                                    $colorText = '#000';
                                                }else{
                                                    $color = '';
                                                    $colorText = '';
                                                }

                                                ?>
                                                <tr style="background-color:<?php echo $color ?>;color:<?php echo $colorText ?>;">
                                                    <td class="text-center"><?php echo $num ?></td>
                                                    <td class="text-center"><?php echo $nomOrden ?></td>
                                                    <td class="text-center"><?php echo $tipoMantencion ?></td>
                                                    <td class="text-center"><?php echo $nomTecnicoRecepcionador ?></td>
                                                    <td class="text-center">
                                                        <?php
                                                        if ($ticketAsociado == '') {
                                                            echo '-';
                                                        } else {
                                                            echo $ticketAsociado;
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php
                                                        if ($idEquipo == '') {
                                                            echo '-';
                                                        } else {


                                                            $tipoMenu1 = obtenerTipoMenu($idEquipo);

                                                            if ($tipoMenu1 == 'nuevo') {
                                                                $rutaMenu1 = 'equipo_menuNuevo.php';
                                                            } else {
                                                                $rutaMenu1 = 'equipo_menuAntiguo.php';

                                                            }

                                                            ?>
                                                            <form action="<?php echo $rutaMenu1 ?>" method="post">
                                                                <input type="hidden" value="<?php echo $idEquipo ?>" name="idEquipo">
                                                                <button class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></button>
                                                            </form>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-center">

                                                        <form action="../sdo-pdf/SDO-OrdenTrabajoGeneral.php" target="_blank" method="POST" style="display: inline;">
                                                            <input type="hidden" name="idOrden" value="<?php echo $idOrden ?>">
                                                            <button type="submit" class="btn btn-sm btn-info "><i class="far fa-eye"></i></button>
                                                        </form>
                                                        <?php
                                                        $archivos_orden_general = "SELECT * FROM archivos_orden_general WHERE idOrden = $idOrden";
                                                        $res = mysqli_query($conexion, $archivos_orden_general);

                                                        if ($res && mysqli_affected_rows($conexion) > 0) {
                                                            ?>
                                                            <form action="hojastecnicas.php" method="POST" style="display: inline;">
                                                                <input type="hidden" name="idOrden" value="<?php echo $idOrden ?>">
                                                                <input type="hidden" name="folio" value="<?php echo $folioOrden ?>">
                                                                <button type="submit" class="btn btn-sm btn-warning "><i class="fas fa-download"></i></button>
                                                            </form>
                                                            <?php
                                                        }
                                                        ?>
                                                        <a class="btn btn-sm btn-danger eliminar" id="<?php echo $idOrden ?>"><i class="fas fa-trash-alt"></i></a>
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
                                                <th scope="col" class="text-center">Nombre</th>
                                                <th scope="col" class="text-center">Tipo</th>
                                                <th scope="col" class="text-center">Tecnico Recepcionador</th>
                                                <th scope="col" class="text-center">Ticket Asociado</th>
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
                <div class="row" name="rowMenu2" id="rowMenu2" style="display: none">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <h2 style="font-size: 32px;" class="text-center text-bold text-muted">Hojas Técnicas Externas - <?php echo $nomCategoria ?> </h2>
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
    $(document).ready(function() {
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
        $('.eliminar').click(function(e) {
            e.preventDefault();
            let id = $(this).attr('id');
            var dataString = 'id=' + id;
            Swal.fire({
                icon: 'question',
                title: '¿Eliminar la hoja ténica de la carpeta?',
                text: 'Si esta hoja técnica le pertenece a un equipo, tambien se eliminara del registro',
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