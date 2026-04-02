<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('America/Santiago');

if (isset($_POST['idEquipo'])) {
    $idEquipo = $_POST['idEquipo'];
    ECHO $idEquipo;
    $idCategoria = $_POST['idCategoria'];
    $sqlGeneral = "SELECT * FROM equipamiento_hbqp WHERE id = $idEquipo";
    $res = mysqli_query($conexion, $sqlGeneral);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['nombreEquipo'] = $row['nombreEquipo'];
        $nombreEquipo = $datos['nombreEquipo'];
    }

    $_SESSION['idEquipo'] = $idEquipo;
} else if ($_SESSION['idEquipo']) {
    $idEquipo = $_SESSION['idEquipo'];
    $idCategoria = $_POST['idCategoria'];
    $sqlGeneral = "SELECT * FROM equipamiento_hbqp WHERE id = $idEquipo";
    $res = mysqli_query($conexion, $sqlGeneral);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['nombreEquipo'] = $row['nombreEquipo'];
        $nombreEquipo = $datos['nombreEquipo'];
    }
}

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
{
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
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <!-- summernote -->
    <!-- DataTables -->
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
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
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <?php


                                $tipoMenu = obtenerTipoMenu($idEquipo);

                                if ($tipoMenu == 'nuevo') {
                                    $rutaMenu = 'equipo_menuNuevo.php';
                                } else {
                                    $rutaMenu = 'equipo_menuAntiguo.php';

                                }
                                ?>
                                <a href="<?php echo $rutaMenu; ?>" style="float: right;" class="btn btn-danger btn-xs"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <h1 style="font-size: 32px;" class="text-center text-bold text-muted">Equipo <?php echo $nombreEquipo ?> - Hojas Técnicas </h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row justify-content-md-center">
                                    <div class="col-xl-3 col-lg-4 col-md-6">
                                        <button class="btn btn-block bg-gradient-primary btn-md" data-toggle="modal" data-target="#modalHojas"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp; <b>A&ntilde;adir Hoja T&eacute;cnica al Equipo</b></button>
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
                                                        <input type="hidden" name="idCategoriaAjaxHT" id="idCategoriaAjaxHT" value="<?php echo $idCategoria; ?>">
                                                        <input type="hidden" name="idEquipoAjaxHT" id="idEquipoAjaxHT" value="<?php echo $idEquipo; ?>">
                                                        <div class="table-responsive">

                                                            <table id="hoja_tecnicas" class="table table-sm table-hover  table-bordered">
                                                                <thead class="bg-gradient-secondary">
                                                                <tr>
                                                                    <th class="text-center">N°</th>
                                                                    <th class="text-center">Nombre</th>
                                                                    <th class="text-center">Tecnico Recepcionador</th>
                                                                    <th class="text-center">Tipo de Mantenimiento</th>
                                                                    <th class="text-center">Ticket Asociado</th>
                                                                    <th class="text-center">Seleccionar</th>
                                                                    <th class="text-center">Ver Hoja Técnica</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>

                                                                </tbody>
                                                                <tfoot class="bg-gradient-secondary">
                                                                <tr>
                                                                    <th class="text-center">N°</th>
                                                                    <th class="text-center">Nombre</th>
                                                                    <th class="text-center">Tecnico Recepcionador</th>
                                                                    <th class="text-center">Tipo de Mantenimiento</th>
                                                                    <th class="text-center">Ticket Asociado</th>
                                                                    <th class="text-center">Seleccionar</th>
                                                                    <th class="text-center">Ver Hoja Técnica</th>
                                                                </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-md-6">
                                        <button type="button" class="btn btn-block bg-gradient-warning btn-md" onclick="agregarHojaVida();" data-toggle="modal" data-target=".generarInforme"><b><i class="far fa-file-pdf"></i> Generar Informes</b></button>
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
                                                        <form action="../sdo-pdf/SDO-Informe-Hojas-Tecnicas.php" target="_blank" method="POST">

                                                            <input type="hidden" name="idEquipo" id="hojaSubir" value="<?php echo $idEquipo ?>">

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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <input type="hidden" name="idEquipoAjax" id="idEquipoAjax" value="<?php echo $idEquipo; ?>">
                                        <div class="table-responsive">
                                            <table id="miTabla" class="table table-bordered text-center">
                                                <thead class="bg-secondary">
                                                <tr>
                                                    <th class="text-center">N°</th>
                                                    <th class="text-center">Nombre</th>
                                                    <th class="text-center">Fecha</th>
                                                    <th class="text-center">Tecnico Recepcionador</th>
                                                    <th class="text-center">Tipo de Mantencion</th>
                                                    <th class="text-center">Ticket Asociado</th>
                                                    <th class="text-center">Visualizar</th>
                                                    <th class="text-center">Adjuntos</th>
                                                    <th class="text-center">Eliminar</th>
                                                </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot class="bg-secondary">
                                                <tr>
                                                    <th class="text-center">N°</th>
                                                    <th class="text-center">Nombre</th>
                                                    <th class="text-center">Fecha</th>
                                                    <th class="text-center">Tecnico Recepcionador</th>
                                                    <th class="text-center">Tipo de Mantencion</th>
                                                    <th class="text-center">Ticket Asociado</th>
                                                    <th class="text-center">Visualizar</th>
                                                    <th class="text-center">Adjuntos</th>
                                                    <th class="text-center">Eliminar</th>
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
        var miTabla = $('#miTabla').DataTable({
            "lengthChange": false,
            "searching": true,
            "autoWidth": false,
            "responsive": true,
            "ordering": false,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "search": "Buscar:",
                "processing": "Procesando...",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
            "ajax": {
                "url": "ajax-ht-equipamiento.php", // Archivo PHP que contiene la consulta SQL
                "type": "POST",
                "data": function (d) {
                    d.idEquipoAjax = $('#idEquipoAjax').val(); // Pasa el valor seleccionado del select como parámetro
                },
                "dataSrc": "",

            },
            "columns": [
                {
                    "data": null,
                    "render": function (data, type, row, meta) {
                        return meta.row + 1; // Devuelve el número de fila (empezando desde 1)
                    }
                },
                {"data": "folioOrden"},
                {"data": "fechaOrden"},
                {"data": "nomTecnicoRecepcionador"},
                {"data": "tipoMantencion"},
                {"data": "ticketAsociado"},
                {
                    "data": null,
                    "render": function (data, type, row) {
                        // Agrega el formulario y el botón de visualización para cada fila
                        if (row.idOrden > 0) {
                            if (row.esFirmadaHT > 0) {
                                return '' +
                                    '<form style="margin: 0; text-align: center;" target="_blank" action="../sdo-funciones/ht_descargar_firmada.php" method="post">' +
                                    '<input type="hidden" name="idOrden" value="' + row.idVisualizarHT + '">' +
                                    '<input type="hidden" name="archivo" value="' + row.rutaVisualizarHT + '">' +
                                    '<input type="hidden" name="folio" value="' + row.folioOrden + '">' +
                                    '<center>' +
                                    '<button type="submit" class="btn bg-purple" title="Descargar HT Firmada"><i class="fas fa-download"></i></button>' +
                                    '</center>' +
                                    '</form>';
                            } else {
                                return '' +
                                    '<form style="margin: 0; text-align: center;" target="_blank" action="' + row.rutaVisualizarHT + '" method="post">' +
                                    '<input type="hidden" name="idOrden" value="' + row.idVisualizarHT + '">' +
                                    '<center>' +
                                    '<button type="submit" class="btn btn-primary" title="Ver HT"><i class="fas fa-eye"></i></button>' +
                                    '</center>' +
                                    '</form>';
                            }
                        } else {
                            return '<button type="button" class="btn btn-primary" disabled title="Sin HT">Deshabilitado</button>';
                        }
                    }

                },
                {
                    "data": null,
                    "render": function (data, type, row) {
                        // Agrega el formulario y el botón de visualización para cada fila
                        return '' +
                            '<form style="margin: 0; text-align: center;" class="" action="equipo-hojas-tecnicas.php" method="post">' +
                            '<input type="hidden" name="idOrden" value="' + row.idOrden + '">' +
                            '<input type="hidden" name="folio" value="' + row.folioOrden + '">' +
                            '<center>' +
                            (row.cantidad < 1 ?
                                    '<button type="button" class="btn btn-warning" disabled>Deshabilitado</button>' :
                                    '<button type="submit" class="btn btn-warning"><i class="fas fa-download"></i></button>'
                            ) +
                            '</center>' +
                            '</form>';
                    }

                },
                {
                    "data": null,
                    "render": function (data, type, row) {
                        // Agrega el formulario y el botón de visualización para cada fila
                        return '' +
                            '<a class="btn btn-danger eliminar" id="' + row.idOrden + '"><i class="fas fa-trash-alt"></i></a>';
                    }

                }




                // Agrega más columnas según sea necesario
            ],
            "createdRow": function (row, data, dataIndex) {
                $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                $('td:eq(0)', row).html(dataIndex + 1); // Actualiza el contenido de la primera celda (columna "N°")
            }
        });
    });
</script>
<script>
    $(document).ready(function () {

        const idCategoriaAjaxHT = document.getElementById('idCategoriaAjaxHT').value;
        const idEquipoAjaxHT = document.getElementById('idEquipoAjaxHT').value;


        $('#hoja_tecnicas').DataTable({
            "lengthChange": false,
            "searching": true,
            "autoWidth": false,
            "responsive": true,
            "ordering": false,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "search": "Buscar:",
                "processing": "Procesando...",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
            "ajax": {
                "url": "ajax-ht-equipamiento.php", // Archivo PHP que contiene la consulta SQL
                "type": "POST",
                "data": function (d) {
                    d.idEquipoAjax = $('#idEquipoAjax').val(); // Pasa el valor seleccionado del select como parámetro
                    d.idAnadir = 'Si';
                },
                "dataSrc": "",

            },
            "columns": [
                {
                    "data": null,
                    "render": function (data, type, row, meta) {
                        return meta.row + 1; // Devuelve el número de fila (empezando desde 1)
                    }
                },
                {"data": "folioOrden"},
                {"data": "nomTecnicoRecepcionador"},
                {"data": "tipoMantencion"},
                {"data": "ticketAsociado"},
                {
                    "data": null,
                    "render": function (data, type, row) {
                        // Agrega el formulario y el botón de visualización para cada fila
                        return '' +
                            '<form style="margin: 0; text-align: center;" class="" action="../sdo-funciones/agregar-hoja-tecnica-equipo.php" method="post">' +
                            '<input type="hidden" name="idCategoria" value="' + idCategoriaAjaxHT + '">' +
                            '<input type="hidden" name="idEquipo" value="' + idEquipoAjaxHT + '">' +
                            '<input type="hidden" name="idHojaTecnica" value="' + row.idOrden + '">' +
                            '<center>' +
                            (row.idOrden < 1 ?
                                    '<button type="button" class="btn btn-success" disabled>Deshabilitado</button>' :
                                    '<button type="submit" class="btn btn-success"><i class="fas fa-check"></i>&nbsp;&nbsp;A&ntilde;adir</button>'
                            ) +
                            '</center>' +
                            '</form>';
                    }

                },
                {
                    "data": null,
                    "render": function (data, type, row) {
                        // Agrega el formulario y el botón de visualización para cada fila
                        return '' +
                            '<form style="margin: 0; text-align: center;" class="" target="_blank" action="../sdo-pdf/SDO-OrdenTrabajoGeneral.php" method="post">' +
                            '<input type="hidden" name="idOrden" value="' + row.idOrden + '">' +
                            '<center>' +
                            (row.idOrden < 1 ?
                                    '<button type="button" class="btn btn-primary" disabled>Deshabilitado</button>' :
                                    '<button type="submit" class="btn btn-primary"><i class="fas fa-eye"></i></button>'
                            ) +
                            '</center>' +
                            '</form>';
                    }

                }


                // Agrega más columnas según sea necesario
            ],
            "createdRow": function (row, data, dataIndex) {
                $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                $('td', row).css('text-align', 'center'); // Aplica el estilo de centrado vertical a todas las celdas
                $('td:eq(0)', row).html(dataIndex + 1); // Actualiza el contenido de la primera celda (columna "N°")
            }
        });
    });
</script>
<script>
    $(document).ready(function () {
        // Maneja el evento `click` en los botones de eliminación con delegación de eventos
        $(document).on('click', '.eliminar', function (e) {
            e.preventDefault();

            // Obtén el ID del botón
            let id = $(this).attr('id');

            // Prepara la cadena de datos para enviar por AJAX
            let dataString = 'id=' + id;

            // Muestra el diálogo de confirmación con SweetAlert2
            Swal.fire({
                icon: 'question',
                title: '¿Eliminar la hoja técnica de la carpeta?',
                text: 'Si esta hoja técnica le pertenece a un equipo, también se eliminará del registro.',
                showConfirmButton: true,
                confirmButtonText: 'Sí, eliminar',
                confirmButtonColor: '#3085d6',
                showCancelButton: true,
                cancelButtonText: 'No, lo pensaré',
                cancelButtonColor: '#d33',
                buttonsStyling: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirmó la eliminación, realiza la solicitud AJAX
                    $.ajax({
                        url: '../sdo-funciones/eliminar-hojatecnica-equipo.php',
                        type: 'POST',
                        data: dataString,
                        success: function (response) {
                            console.log('Eliminado: ' + dataString);
                            // Muestra la respuesta del servidor en la consola
                            console.log('Respuesta del servidor:', response);

                            // Puedes también mostrar la respuesta en un mensaje para el usuario
                            Swal.fire({
                                icon: 'success',
                                title: 'Éxito',
                                text: 'El registro fue eliminado correctamente.',
                                showConfirmButton: true,
                            })

                            setTimeout(function () {
                                location.reload();
                            }, 1500);
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            // Manejar error de la solicitud AJAX
                            console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Hubo un problema al eliminar el registro.',
                                showConfirmButton: true,
                            });
                        }
                    });
                }
            });
        });
    });
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
</body>

</html>