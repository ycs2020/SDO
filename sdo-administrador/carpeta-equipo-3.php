<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';
include '../vendor/quickchart/QuickChart.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('America/Santiago');
// echo '';


$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = mb_convert_case($rolUsuario, MB_CASE_LOWER, 'UTF-8');

//
//$ipPermitida = '10.69.250.151'; // Reemplaza con la IP permitida
//
//$ipCliente = $_SERVER['REMOTE_ADDR'];
//
//if ($ipCliente === $ipPermitida) {
//    $valorDesarrollo = 'Pasaste';
//} else {
//    header('Location: plataforma_mantenimiento.php');
//}



if (isset($_POST['idCarpeta'])) {
    $idCarpeta = $_POST['idCarpeta'];
} else if (isset($_SESSION['idCarpeta'])) {
    $idCarpeta = $_SESSION['idCarpeta'];
} else {
    header('Location: equipamiento_hbqp.php?noIdCarpeta=true');
}

if ($idCarpeta > 0) {

    $_SESSION['idCarpeta'] = $idCarpeta;

    $idCarpeta = mysqli_real_escape_string($conexion, $idCarpeta);

// Obtener datos generales de la categoría
    $sqlGeneral = "SELECT * FROM categorias_equipos WHERE idCategoria = $idCarpeta";
    $res = mysqli_query($conexion, $sqlGeneral);
    $row = $res->fetch_assoc();
    $nomCategoria = $row['nomCategoria'];
    $imagenCategoria = $row['imagenCategoria'];
    $idDepartamento = $row['idDepartamento'];

// Consultas para contar y sumar diferentes datos
    $queries = [
        "cantidadEquiposRegistrados" => "SELECT COUNT(*) AS cantidad FROM equipamiento_hbqp WHERE idCategoria = $idCarpeta",
        "cuentaEquiposOperativos" => "SELECT COUNT(*) AS cantidad FROM equipamiento_hbqp WHERE idCategoria = $idCarpeta AND estadoEquipo LIKE 'Operativo'",
        "cuentaEquiposConObservaciones" => "SELECT COUNT(*) AS cantidad FROM equipamiento_hbqp WHERE idCategoria = $idCarpeta AND estadoEquipo LIKE 'OperativoObs'",
        "cantEquiposMantenimiento" => "SELECT COUNT(*) AS cantidad FROM equipamiento_hbqp WHERE idCategoria = $idCarpeta AND estadoEquipo LIKE 'En Mantenimiento'",
        "cantEquiposFalla" => "SELECT COUNT(*) AS cantidad FROM equipamiento_hbqp WHERE idCategoria = $idCarpeta AND estadoEquipo LIKE 'Fuera de Servicio'",
        "cantEquiposExtraviados" => "SELECT COUNT(*) AS cantidad FROM equipamiento_hbqp WHERE idCategoria = $idCarpeta AND estadoEquipo LIKE 'Extraviado'",
        "cantidadHojasTecnicas" => "SELECT COUNT(*) AS cantidad FROM ordenes_trabajos_general WHERE idCategoria = $idCarpeta",
        "gastoTotal" => "SELECT SUM(costoTotal) AS cantidad FROM gasto_equipo_carpeta WHERE idCategoria = $idCarpeta",
        "cuentaSoliCompra" => "SELECT COUNT(*) AS cantidad FROM solicitudes_compra_sdo WHERE idCategoria = $idCarpeta",
        "cuentaArchivos" => "SELECT COUNT(*) AS cantidad FROM archivos_equipamiento_hbqp WHERE idCategoria = $idCarpeta"
    ];

    $resultados = [];

    foreach ($queries as $key => $query) {
        $res = mysqli_query($conexion, $query);
        $row = $res->fetch_assoc();
        $resultados[$key] = $row['cantidad'];
    }

// Acceder a los resultados
    $cantidadEquiposRegistrados = $resultados['cantidadEquiposRegistrados'];
    $cuentaEquiposOperativos = $resultados['cuentaEquiposOperativos'];
    $cuentaEquiposConObservaciones = $resultados['cuentaEquiposConObservaciones'];
    $cantEquiposMantenimiento = $resultados['cantEquiposMantenimiento'];
    $cantEquiposFalla = $resultados['cantEquiposFalla'];
    $cantEquiposExtraviados = $resultados['cantEquiposExtraviados'];
    $cantidadHojasTecnicas = $resultados['cantidadHojasTecnicas'];
    $gastoTotal = $resultados['gastoTotal'];
    $cuentaSoliCompra = $resultados['cuentaSoliCompra'];
    $cuentaArchivos = $resultados['cuentaArchivos'];

    if ($rolUsuario2 == "personal") {
        $tipoUsuario = 'personal';
        $ruta = "sdo-personal";
    } else if ($rolUsuario2 == "administrativo") {
        $tipoUsuario = 'administrativo';
        $ruta = "sdo-admtec";
    } else if ($rolUsuario2 == "bodega pañol") {
        $tipoUsuario = 'bodega pañol';
        $ruta = "sdo-bodega-panol";
    } else if ($rolUsuario2 == "jefe tecnico") {
        $tipoUsuario = 'jefe tecnico';
        $ruta = "sdo-jefestecnicos";
    } else if ($rolUsuario2 == "administrador") {
        $tipoUsuario = 'administrador';
        $ruta = "sdo-administrador";
    } else if ($rolUsuario2 == "directiva") {
        $tipoUsuario = 'directiva';
        $ruta = "sdo-directiva";
    } else if ($rolUsuario2 == "oficina tecnica") {
        $tipoUsuario = 'oficina tecnica';
        $ruta = "sdo-oficinaTecnica";
    } else {
        header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    }


} else {
    header('Location: equipamiento_hbqp.php?noIdCarpeta=true');
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Carpeta - Sistema</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap4.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dhtmlx-gantt@8.0.1/codebase/dhtmlxgantt.min.css">
    <script src="https://cdn.jsdelivr.net/npm/dhtmlx-gantt@8.0.1/codebase/dhtmlxgantt.min.js"></script>
    <script src="https://cdn.anychart.com/releases/8.11.0/js/anychart-base.min.js" type="text/javascript"></script>
    <script src="https://cdn.anychart.com/releases/8.11.0/js/anychart-core.min.js" type="text/javascript"></script>
    <script src="https://cdn.anychart.com/releases/8.11.0/js/anychart-gantt.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap4.min.css">

    <style>
        /* ============================
           MODO CLARO (por defecto)
        ============================ */

        /* Texto dentro del campo seleccionado */
        .select2-container--bootstrap4 .select2-selection__rendered {
            color: #000000; /* Texto negro */
        }

        /* Opciones en el listado desplegable */
        .select2-container--bootstrap4 .select2-results__option {
            color: #000000; /* Texto negro */
        }

        /* Fondo del dropdown */
        .select2-container--bootstrap4 .select2-dropdown {
            background-color: #ffffff; /* Fondo blanco */
            color: #000000;
        }

        /* Opciones seleccionadas múltiples (chips) */
        .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
            background-color: #007bff !important; /* Azul */
            color: #ffffff !important; /* Texto blanco */
            border: none;
        }

        /* ============================
           MODO OSCURO (con clase .dark-mode en <body>)
        ============================ */

        /* Fondo del campo de selección */
        .dark-mode .select2-container--bootstrap4 .select2-selection {
            background-color: #343a40 !important; /* Fondo oscuro */
            color: #ffffff !important; /* Texto blanco */
            border-color: #495057 !important;
        }

        /* Texto dentro del campo seleccionado */
        .dark-mode .select2-container--bootstrap4 .select2-selection__rendered {
            color: #ffffff !important;
        }

        /* Opciones seleccionadas múltiples (chips) */
        .dark-mode .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
            background-color: #007bff !important;
            color: #ffffff !important;
            border: none;
        }

        /* Dropdown fondo y texto */
        .dark-mode .select2-container--bootstrap4 .select2-dropdown {
            background-color: #343a40 !important;
            color: #ffffff !important;
            border-color: #495057 !important;
        }

        /* Opciones en la lista desplegable */
        .dark-mode .select2-container--bootstrap4 .select2-results__option {
            color: #ffffff !important;
        }

        /* Opción seleccionada en dropdown */
        .dark-mode .select2-container--bootstrap4 .select2-results__option[aria-selected="true"] {
            background-color: #007bff !important;
            color: #ffffff !important;
        }

        /* Opción resaltada con el mouse */
        .dark-mode .select2-container--bootstrap4 .select2-results__option--highlighted {
            background-color: #007bff !important;
            color: #ffffff !important;
        }
    </style>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/admin-sidebar.php';
    ?>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
            </div>
        </div>
        <?php
        if (isset($_GET['exitoGuardarEquipo'])) {
            ?>
            <script>
                Swal.fire(
                    'Exito!',
                    'Se ha guardado el nuevo equipo correctamente!',
                    'success'
                )
            </script>
            <?php
        }
        if (isset($_GET['errorGuardarEquipo'])) {
            ?>
            <script>
                Swal.fire(
                    'Error!',
                    'Se ha producido un error al intentar guardar el equipo!',
                    'error'
                )
            </script>
            <?php
        }
        ?>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <div class="row">

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-info">


                                <form method="post" action="equipamiento_hbqp.php" class="text-right">
                                    <button style="float: right" type="submit" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atrás</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">

                        <div class="card card-primary card-outline">
                            <div class="card-body">
                                <div class="row">

                                    <?php



                                    // Array asociativo con códigos y colores de fondo
                                    $botones = array(
                                        "MPI" => array("bg-info", "MANTENIMIENTO PREVENTIVO INTERNO"),
                                        "MRI" => array("bg-danger", "MANTENIMIENTO REPARATIVO INTERNO"),
                                        "MPE" => array("bg-secondary", "MANTENIMIENTO PREVENTIVO EXTERNO"),
                                        "MRE" => array("bg-purple", "MANTENIMIENTO REPARATIVO EXTERNO"),
                                        "OM" => array("bg-primary", "OBRAS MENORES"),
                                        "IS" => array("bg-orange", "INSPECCIÓN Y SUPERVISIÓN")
                                    );

                                    // Recorrer el array y generar HTML
                                    foreach ($botones as $codigo => $info) {

                                        $codigo_corto = substr($codigo, 0, 3);
                                        $color = $info[0];
                                        $descripcion = $info[1];

                                        $totalMantenimiento = obtieneCantidadTiposMantencion($idCarpeta, $codigo_corto);
                                        $costoMantenimiento = obtieneGastoTiposMantencion($idCarpeta, $codigo_corto);

                                        if ($totalMantenimiento > 0) {

                                            // SOLO materiales OT (no duplicar gastos)
                                            $sqlMateriales = "
            SELECT COALESCE(SUM(ie.valorUnitario), 0) AS totalValor
            FROM ordenes_trabajos_general otg
            INNER JOIN material_orden_general mog 
                ON mog.idOrden = otg.idOrden
            INNER JOIN inventario_equipos ie 
                ON ie.equipoInventario = mog.nomMaterial
            WHERE otg.idCategoria = ?
              AND otg.tipoMantencion = ?
        ";

                                            $stmt = $conexion->prepare($sqlMateriales);
                                            $stmt->bind_param("is", $idCarpeta, $codigo_corto);
                                            $stmt->execute();
                                            $res = $stmt->get_result();
                                            $row = $res->fetch_assoc();

                                            $totalValorMateriales = (float)$row['totalValor'];

                                            $totalFinal = $costoMantenimiento + $totalValorMateriales;
                                            ?>
                                            <br>
                                            <input type="hidden" id="costoMant<?php echo $codigo_corto; ?>" value="<?php echo $costoMantenimiento ?>">
                                            <div class="col-xl-3 col-lg-4 col-md-4 col-12">
                                                <button class="btn btn-block btn-md small-box <?php echo $color; ?>" data-toggle="modal" data-target="#<?php echo $codigo_corto; ?>">
                                                    <h4>
                                                        <i class="fas fa-file"></i>
                                                        <?php echo $codigo_corto; ?>
                                                    </h4>

                                                    Cantidad: <b><?php echo $totalMantenimiento ?></b>
                                                    <br>

                                                    <?php
                                                    echo ($totalFinal > 0)
                                                        ? moneda_chilena($totalFinal)
                                                        : '$ 0';
                                                    ?>
                                                </button>
                                            </div>
                                            <?php
                                        }
                                    }



                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


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
<script src="../plugins/select2/js/select2.full.min.js"></script>

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

        //Initialize Select2 Elements
        $('.listadoEncInstru').select2({
            placeholder: 'Técnico Encargado de Instrumentación en Turno',
            theme: 'bootstrap4',
            allowClear: true
        });



    });
</script>
<script>
    $(document).ready(function () {
        let idCarpetaCargar = $('#idCarpetaAjax').val();
        var tablaEquipos = $('#tabla-equipos').DataTable({
            "lengthChange": false,
            "searching": true,
            "autoWidth": false,
            "responsive": true,
            "ordering": false,
            "processing": true,
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
            "search": {
                "regex": true,
                "smart": false
            },
            initComplete: function() {
                this.api().columns([1]).every(function() {
                    var column = this;
                    var select = $('<select style="width: 100%;"><option value="">Seleccione Equipo...</option></select>')
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
                    var select = $('<select style="width: 100%;"><option value="">Seleccione Recinto...</option></select>')
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
                this.api().columns([5]).every(function() {
                    var column = this;
                    var select = $('<select style="width: 100%;"><option value="">Seleccione Estado...</option></select>')
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
            },
            "ajax": {
                "url": "sdo-includes/equipos_registrados.php", // Archivo PHP que contiene la consulta SQL
                "type": "POST",
                "data": function (d) {
                    d.idCarpetaAjax = idCarpetaCargar; // Pasa el valor seleccionado del select como parámetro
                    console.log("Revisar: "+d.idCarpetaAjax);

                },
                "dataSrc": function (json) {
                    console.log("Response from server:", json);
                    return json;
                },
                "error": function (xhr, error, thrown) {
                    console.error("Error occurred during AJAX request:", error, thrown);
                    console.log("XHR object:", xhr);
                }
            },
            "columns": [
                {
                    "data": null,
                    "render": function (data, type, row, meta) {
                        return meta.row + 1; // Devuelve el número de fila (empezando desde 1)
                    }
                },
                {"data": "nombreEquipo"},
                {"data": "recinto"},
                {"data": "pma"},
                {"data": "piso"},
                {
                    "data": "estadoEquipo",
                    "render": function (data, type, row) {

                        // Texto legible para mostrar
                        const estadosTexto = {
                            "Operativo": "Operativo",
                            "Mantenimiento": "Mantenimiento",
                            "Fuera de Servicio": "Fuera de Servicio",
                            "OperativoObs": "Operativo con Observaciones",
                            "Extraviado": "Extraviado"
                        };

                        var modalId = 'modalEquipo_' + row.id;
                        var textoEstado = estadosTexto[data] || data;

                        return `
            <span>${textoEstado}</span><br>
            <button class="btn btn-sm btn-secondary btn-estadoEquipo mt-1" data-toggle="modal" data-target="#${modalId}">
                <i class="fas fa-edit"></i>
            </button>

            <!-- Modal -->
            <div class="modal fade" id="${modalId}" tabindex="-1" role="dialog" aria-labelledby="${modalId}Label" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title" id="${modalId}Label"><b>Cambiar el estado del equipo</b></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="../sdo-funciones/cambiaEstadoEquipo_equipo_2.php">
                                <input type="hidden" name="idEquipo" value="${row.id}">
                                <input type="hidden" name="idCategoria" value="${row.idCategoria}">
                                <input type="hidden" name="nombreEquipo" value="${row.nombreEquipo}">

                                <div class="form-group">
                                    <label>Nuevo Estado:<code>*</code></label>
                                    <select name="opcionEstado" required class="form-control">
                                        <option value="Operativo" ${data === 'Operativo' ? 'selected' : ''}>Operativo</option>
                                        <option value="Mantenimiento" ${data === 'Mantenimiento' ? 'selected' : ''}>Mantenimiento</option>
                                        <option value="Fuera de Servicio" ${data === 'Fuera de Servicio' ? 'selected' : ''}>Fuera de Servicio</option>
                                        <option value="OperativoObs" ${data === 'OperativoObs' ? 'selected' : ''}>Operativo con Observaciones</option>
                                        <option value="Extraviado" ${data === 'Extraviado' ? 'selected' : ''}>Extraviado</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Observaciones:<code>*</code></label>
                                    <textarea name="observaciones" rows="3" class="form-control" required></textarea>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary">Guardar Estado</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        `;
                    }
                },



                {
                    "data": null,
                    "render": function (data, type, row) {
                        if (row.id > 0) {
                            return '<button class="btn btn-primary btn-hojaVida" data-idequipover="' + row.id + '"><i class="fas fa-file"></i></button>';
                        } else {
                            return 'Deshabilitado';
                        }
                    }
                },
                {
                    "data": null,
                    "render": function (data, type, row) {
                        if (row.id > 0) {
                            return '<button class="btn btn-info btn-infoEquipo" data-idequipoinfo="' + row.id + '" data-idcategoriainfo="' + row.idCategoria + '" data-tipomenu="' + row.tipoMenu + '"><i class="fas fa-search"></i></button>';
                        } else {
                            return 'Deshabilitado';
                        }
                    }
                },
                {
                    "data": null,
                    "render": function (data, type, row) {
                        if (row.id > 0) {
                            return '<button class="btn btn-danger btn-eliminarEquipo" data-ideliminar="' + row.id + '"><i class="fas fa-trash"></i></button>';
                        } else {
                            return 'Deshabilitado';
                        }
                    }
                }




                // Agrega más columnas según sea necesario
            ],
            "createdRow": function (row, data, dataIndex) {
                // Aplica el estilo de centrado vertical y horizontal a todas las celdas
                $('td', row).css({
                    'vertical-align': 'middle',
                    'text-align': 'center'
                });

                // Actualiza el contenido de la primera celda (columna "N°")
                $('td:eq(0)', row).html(dataIndex + 1);

                // Aplica el estilo a la columna 5 (estadoEquipo)
                var column5 = $('td:eq(5)', row);
                var estado = data.estadoEquipo; // viene limpio

                column5.css({
                    'font-weight': 'bold',
                    'text-transform': 'uppercase'
                });

                if (estado === 'Operativo') {
                    column5.addClass('text-success');
                    column5.prepend('<i class="fas fa-check"></i> ');
                }
                else if (estado === 'Mantenimiento') {
                    column5.addClass('text-warning');
                    column5.prepend('<i class="fas fa-exclamation-triangle"></i> ');
                }
                else if (estado === 'OperativoObs') {
                    column5.addClass('text-secondary');
                    column5.prepend('<i class="fas fa-exclamation-circle"></i> ');
                }
                else if (estado === 'Extraviado') {
                    column5.css('color', 'purple'); // color morado
                    column5.prepend('<i class="fas fa-question"></i> '); // icono de interrogación
                }
                else { // cualquier otro estado
                    column5.addClass('text-danger');
                    column5.prepend('<i class="fas fa-skull-crossbones"></i> ');
                }
            }

        });

        $('#tabla-equipos').on('click', '.btn-hojaVida', function () {
            var id = $(this).data('idequipover');

            var form = $('<form>', {
                'action': '../sdo-pdf/SDO-Hoja-Vida-Equipamiento.php', // Reemplaza 'tu_url_destino' con la URL de destino
                'method': 'POST',
                'target': '_blank',
            });

            // Agregar un campo oculto para el ID
            form.append($('<input>', {
                'type': 'hidden',
                'name': 'idCarpeta',
                'value': id
            }));

            // Agregar el formulario al documento y enviarlo
            form.appendTo('body').submit();
        });

        $('#tabla-equipos').on('click', '.btn-infoEquipo', function () {
            var idEquipo = $(this).data('idequipoinfo');
            var idCategoria = $(this).data('idcategoriainfo');
            var tipomenu = $(this).data('tipomenu');
            console.log('idEquipo Capturado:', idEquipo); // Verifica si el ID está siendo capturado correctamente
            console.log('idCategoria Capturado:', idCategoria); // Verifica si el ID está siendo capturado correctamente
            console.log('tipoMenu Capturado:', tipomenu); // Verifica si el ID está siendo capturado correctamente

            if(tipomenu === 'nuevo'){

                var form = $('<form>', {
                    'action': 'equipo_menuNuevo.php', // Reemplaza 'tu_url_destino' con la URL de destino
                    'method': 'POST',
                });
            }else{

                var form = $('<form>', {
                    'action': 'equipo_menuAntiguo.php', // Reemplaza 'tu_url_destino' con la URL de destino
                    'method': 'POST',
                    'target': '_blank',
                });
            }


            // Agregar un campo oculto para el ID
            form.append($('<input>', {
                'type': 'hidden',
                'name': 'idEquipo',
                'value': idEquipo
            }));

            // Agregar un campo oculto para el ID
            form.append($('<input>', {
                'type': 'hidden',
                'name': 'idCategoria',
                'value': idCategoria
            }));

            // Agregar el formulario al documento y enviarlo
            form.appendTo('body').submit();
        });


        $('#tabla-equipos').on('click', '.btn-eliminarEquipo', function (e) {
            e.preventDefault(); // Previene la acción por defecto del botón

            var id = $(this).data('ideliminar'); // Obtiene el ID del botón

            var dataString = 'id=' + id; // Prepara los datos para enviar

            Swal.fire({
                icon: 'question',
                title: '¿Eliminar este equipo?',
                text: 'Esta acción no se puede revertir',
                showConfirmButton: true,
                confirmButtonText: 'Sí, eliminar',
                confirmButtonColor: '#3085d6',
                showCancelButton: true,
                cancelButtonText: 'No, lo pensaré',
                cancelButtonColor: '#d33',
                buttonsStyling: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../sdo-funciones/eliminar-equipo-carpeta.php',
                        type: 'POST',
                        data: dataString,
                        success: function (response) {
                            console.log('Eliminado: ' + dataString);
                            Swal.fire({
                                icon: 'success',
                                title: 'Registro del equipo eliminado',
                                showConfirmButton: false,
                                timer: 2000,
                            });

                            // Recargar la página después de mostrar el mensaje de éxito
                            setTimeout(function () {
                                location.reload();
                            }, 2100);
                        },
                        error: function (xhr, status, error) {
                            console.error('Error al eliminar el equipo:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'No se pudo eliminar el equipo. Inténtelo de nuevo.',
                                confirmButtonColor: '#3085d6'
                            });
                        }
                    });
                }
            });
        });

    });
</script>

<script>
    $(document).ready(function () {
        $('#tabla-tickets').DataTable({
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

<?php

// Array asociativo con códigos y colores de fondo
$botones = array(
    "MPI" => "bg-info",
    "MRI" => "bg-danger",
    "MPE" => "bg-secondary",
    "MRE" => "bg-purple",
    "OM" => "bg-primary",
    "IS" => "bg-orange"
);

// Recorrer el array y generar HTML
foreach ($botones as $codigo => $color) {
    // Obtener las dos primeras letras del código
    $codigo_corto = substr($codigo, 0, 3);

    ?>
    <script>
        $(document).ready(function () {
            $('#tabla-hojas-<?php echo $codigo_corto; ?>').DataTable({
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
    <?php

}

?>

<script>

    $(document).ready(function () {
        $('#tablaInfoEquipos').DataTable({
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
        $('#tabla-equipo-observacion').DataTable({
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
        $('#tabla-equipo-falla').DataTable({
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
        $('#tabla-gastos-mre').DataTable({
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
        $('#tabla-gastos-mpe').DataTable({
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
        $('#tabla_ht_mpe').DataTable({
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
        $('#tabla_ht_mre').DataTable({
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
        $('#tabla_ht_om').DataTable({
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
        $('#tableCheckListNoTerminados').DataTable({
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
                title: '¿Eliminar este equipo?',
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
                        url: '../sdo-funciones/eliminar-equipo-carpeta.php',
                        type: 'POST',
                        data: dataString,
                        success: function (response) {
                            console.log('eliminado.. ' + dataString);
                            Swal.fire({
                                icon: 'success',
                                title: 'Registro del equipo eliminado',
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

<script>

    let id = document.getElementById('id').value;

    fetch('../sdo-funciones/obtener_tarea_programacion.php?id=' + id)
        .then(response => response.json())
        .then(data => {
            let jsonData = JSON.stringify(data);
            console.log(jsonData);
            anychart.onDocumentReady(function () {
                anychart.format.inputLocale("en");

                // create a data tree
                var treeData = anychart.data.tree(jsonData, "as-tree");
                // create a chart
                var chart = anychart.ganttProject();
                // set the data
                chart.data(treeData);
                // set the container id
                chart.container("container");
                // initiate drawing the chart
                chart.draw();
                // fit elements to the width of the timeline
                chart.fitAll();
            });

        })
        .catch(error => {
            console.log('Error en la consulta Fetch API: ', error)
        })
</script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

    let gastoTotal = document.getElementById('gastoTotal').value;

    let costoMRE = document.getElementById('costoMRE').value;
    let costoMPI = document.getElementById('costoMPI').value;
    let costoMRI = document.getElementById('costoMRI').value;
    let costoMPE = document.getElementById('costoMPE').value;
    let costoOM = document.getElementById('costoOM').value;
    let costoIS = document.getElementById('costoIS').value;

    if (costoMRE === "") {
        costoMRE = "0";
    }

    costoMRE = parseInt(costoMRE);
    costoMPI = parseInt(costoMPI);
    costoMRI = parseInt(costoMRI);
    costoMPE = parseInt(costoMPE);
    costoOM = parseInt(costoOM);
    costoIS = parseInt(costoIS);

    console.log(costoMRE)

    if (costoMRE == 0) {
        console.log('entre')
        // Load the Visualization API and the piechart package.
        google.charts.load('current', {'packages': ['corechart']});

        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            // Create the data table.
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Topping');
            data.addColumn('number', 'Slices');

            data.addRows([
                ['Sin Información', 1],
            ]);

            // Set chart options
            var options = {
                'title': ' Estas visualizando un gráfico estándar, que no contiene información.',
                'width': 400,
                'height': 300,
                'backgroundColor': 'transparent',
                colors: ['#566573']
            };

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    } else {
        // Load the Visualization API and the piechart package.
        google.charts.load('current', {'packages': ['corechart']});

        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            // Create the data table.
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Topping');
            data.addColumn('number', 'Slices');

            data.addRows([
                ['MPI', costoMPI],
                ['MRI', costoMRI],
                ['MPE', costoMPE],
                ['MRE', costoMRE],
                ['OM', costoOM],
                ['IS', costoIS],
            ]);

            // Set chart options
            var options = {
                'title': ' Total: ' + gastoTotal,
                'width': 400,
                'height': 300,
                'backgroundColor': 'transparent',
                colors: ['#5bc0de', '#d9534f', '#7c8184', '#59287a', '#2972fa', '#fab633']
            };

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    }


    // Callback that creates and populates a data table,
    // instantiates the pie chart, passes in the data and
    // draws it.


    let cantidadMRE = document.getElementById('cantidadMRE').value;
    let cantidadMPI = document.getElementById('cantidadMPI').value;
    let cantidadMRI = document.getElementById('cantidadMRI').value;
    let cantidadMPE = document.getElementById('cantidadMPE').value;
    let cantidadOM = document.getElementById('cantidadOM').value;
    let cantidadIS = document.getElementById('cantidadIS').value;

    cantidadMRE = parseInt(cantidadMRE);
    cantidadMPI = parseInt(cantidadMPI);
    cantidadMRI = parseInt(cantidadMRI);
    cantidadMPE = parseInt(cantidadMPE);
    cantidadOM = parseInt(cantidadOM);
    cantidadIS = parseInt(cantidadIS);

    if (cantidadMRE == 0 && cantidadMPI == 0 && cantidadMRI == 0 && cantidadMPE == 0 && cantidadOM == 0 && cantidadIS == 0) {

        console.log("Hola");

        google.charts.setOnLoadCallback(drawChartHojas);

        function drawChartHojas() {
            // Create the data table.
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Tipos');
            data.addColumn('number', 'Cantidad');
            data.addRows([
                ['Sin Información', 1],
            ]);

            // Instantiate and draw our chart, passing in some options.
            var barchart_options = {
                title: 'Estas visualizando un gráfico estándar, que no contiene información.',
                width: 400,
                height: 300,
                legend: 'none',
                'backgroundColor': 'transparent',
                colors: ['#566573']
            };
            var barchart = new google.visualization.PieChart(document.getElementById('grafico_barras'));
            barchart.draw(data, barchart_options);
        }


    } else {

        google.charts.setOnLoadCallback(drawChartHojas);

        function drawChartHojas() {
            // Create the data table.
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Tipos');
            data.addColumn('number', 'Cantidad');
            data.addRows([
                ['MPI', cantidadMPI],
                ['MRI', cantidadMRI],
                ['MPE', cantidadMPE],
                ['MRE', cantidadMRE],
                ['OM', cantidadOM],
                ['IS', cantidadIS],
            ]);

            // Instantiate and draw our chart, passing in some options.
            var barchart_options = {
                title: 'Cantidad de tipos de mantenciones',
                width: 400,
                height: 300,
                legend: 'none',
                'backgroundColor': 'transparent',
                colors: ['#5bc0de', '#d9534f', '#7c8184', '#59287a', '#2972fa', '#fab633']
            };
            var barchart = new google.visualization.PieChart(document.getElementById('grafico_barras'));
            barchart.draw(data, barchart_options);
        }
    }
</script>
<script>
    document.getElementById('filtroPiso').addEventListener('change', function () {
        const valorSeleccionado = this.value;
        const tarjetas = document.querySelectorAll('.equipo-card');
        let contador = 0;

        tarjetas.forEach(function (tarjeta) {
            if (valorSeleccionado === 'todos') {
                tarjeta.style.display = 'block';
                contador++;
            } else {
                if (tarjeta.classList.contains(valorSeleccionado)) {
                    tarjeta.style.display = 'block';
                    contador++;
                } else {
                    tarjeta.style.display = 'none';
                }
            }
        });

        document.getElementById('contadorEquipos').textContent = contador;
    });
</script>
<script>
    function busquedaDinamica() {

        let variableBuscar = document.getElementById('variableBuscar').value;
        let idCarpetaBuscar2 = document.getElementById('idCarpetaBuscar').value;

        var columns = [
            {"data": "nombreEquipo"},
            {"data": "marca"},
            {"data": "modelo"},
            {"data": "rutaEquipo"},
            {
                "data": null,
                "render": function (data, type, row) {
                    // Define la URL de acción del formulario basado en el valor de row.tipoMenu
                    var formAction = (row.tipoMenu === 'nuevo') ? 'equipo_menuNuevo.php' : 'equipo_menuAntiguo.php';

                    // Agrega el formulario y el botón de visualización para cada fila
                    return '' +
                        '<form style="margin: 0; text-align: center;" action="' + formAction + '" method="post">' +
                        '<input type="hidden" id="idCategoria" name="idCategoria" value="' + row.idCategoria + '">' +
                        '<input type="hidden" id="idEquipo" name="idEquipo" value="' + row.id + '">' +
                        '<center>' +
                        (row.id < 1 ?
                                '<button type="button" class="btn btn-primary" disabled>Deshabilitado</button>' :
                                '<button type="submit" title="Información del equipo" class="btn btn-primary"><i class="fa-solid fa-door-open"></i></button>'
                        ) +
                        '</center>' +
                        '</form>';
                }
            }
            // Agregar columnas dinámicamente
        ];

        $('#tablaBuscadorDinamico').DataTable({
            "lengthChange": false,
            "searching": true,
            "autoWidth": false,
            "responsive": true,
            "ordering": false,
            "processing": true,
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
                "url": "ajax-equipo-dinamico.php", // Archivo PHP que contiene la consulta SQL
                "type": "POST",
                "data": function (d) {
                    d.variableBuscar = variableBuscar; // Pasa el valor seleccionado del select como parámetro
                    d.idCarpeta = idCarpetaBuscar2; // Pasa el valor seleccionado del select como parámetro
                    console.log(d);
                },
                "dataSrc": ""
            },
            "columns": columns,
            "createdRow": function (row, data, dataIndex) {
                $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                $('td', row).css('text-align', 'center'); // Aplica el estilo de centrado vertical a todas las celdas

            },
            "bDestroy": true
        });
    }
</script>
<script>

    $(document).ready(function () {

        //Initialize Select2 Elements


        $('.equipoQueControlaMod1').select2({
            placeholder: 'Seleccione',
            theme: 'bootstrap4',
            allowClear: true
        });
        $('.sourceAlarmaMod').select2({
            placeholder: 'Seleccione',
            theme: 'bootstrap4',
            allowClear: true
        });
        $('.sourceAlarmaAgregar').select2({
            placeholder: 'Seleccione',
            theme: 'bootstrap4',
            allowClear: true
        });
        $('.tipoInterfazAgregar').select2({
            placeholder: 'Seleccione',
            theme: 'bootstrap4',
            allowClear: true
        });

    });
</script>


</body>

</html>

