<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];
$idDepartamento = $_SESSION['idDepartamento'];


$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);


date_default_timezone_set('America/Santiago');

$fecha = date('d-m-Y H:i');

$rutaDireccion = "";

if ($rolUsuario2 == "personal") {
    $tipoUsuario = 'personal';
    $rutaDireccion = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $tipoUsuario = 'administrativo';
    $rutaDireccion = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $tipoUsuario = 'bodega pañol';
    $rutaDireccion = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $tipoUsuario = 'jefe tecnico';
    $rutaDireccion = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $tipoUsuario = 'administrador';
    $rutaDireccion = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $tipoUsuario = 'directiva';
    $rutaDireccion = "sdo-directiva";
} else if ($rolUsuario2 == "oficina tecnica") {
    $tipoUsuario = 'oficina tecnica';
    $rutaDireccion = "sdo-oficinaTecnica";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if(isset($_GET['exitoGenerarPlanificador']) && isset($_GET['idEquipo'])) {
    $idEquipo = $_GET['idEquipo'];

}

if (isset($_POST['idEquipo']) || isset($_SESSION['idEquipo'])) {

    if (isset($_POST['idEquipo'])) {
        $idEquipo = $_POST['idEquipo'];
        $_SESSION['idEquipo'] = $idEquipo;
    } else if (isset($_SESSION['idEquipo'])) {
        $idEquipo = $_SESSION['idEquipo'];
    } else {
        header('Location: ../' . $rutaDireccion . '/carpeta-equipo.php?errorCargarEquipo=true');
    }

    //$_SESSION['idEquipo'] = $idEquipo;

    $sql = "SELECT * FROM equipamiento_hbqp WHERE id = " . $idEquipo;

    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {

        $fechaRegistro = $row['fechaRegistro'];
        $identificadorUnico = $row['identificadorUnico'];
        $numeroInventario = $row['numeroInventario'];
        $fechaRecepcion = $row['fechaRecepcion'];
        $nombreEquipo = $row['nombreEquipo'];
        $marca = $row['marca'];
        $modelo = $row['modelo'];

        $modeloCadena = $modelo;

        $servicioDB = $row['idServicio'];
        $recinto = $row['recinto'];
        $pma = $row['pma'];
        $coordenadas = $row['coordenadas'];
        $fechaCompra = $row['fechaCompra'];
        $valorConIva = $row['valorConIva'];
        $idLicitacion = $row['idLicitacion'];
        $ordenCompra = $row['ordenCompra'];
        $fechaMarcha = $row['fechaMarcha'];
        $clase = $row['clase'];
        $subclase = $row['subclase'];
        $complejidadTec = $row['complejidadTec'];
        $nivelRiesgo = $row['nivelRiesgo'];
        $AhnoFrabricacion = $row['AhnoFrabricacion'];
        $vidaUtil = $row['vidaUtil'];
        $versionSoftware = $row['versionSoftware'];
        $paisOrigen = $row['paisOrigen'];
        $terminoGarantia = $row['terminoGarantia'];
        $costoMantAnual = $row['costoMantAnual'];
        $frecuenciaMantenimiento = $row['frecuenciaMantenimiento'];
        $numeroSerie = $row['numeroSerie'];
        $piso = $row['piso'];
        $idCategoria = $row['idCategoria'];
        $estadoEquipo = $row['estadoEquipo'];
        $tipoControlador = $row['tipoControlador'];
        $device = $row['device_id'];
        $network = $row['network'];
        $mac = $row['mac_address'];
        $tcc = $row['tcc'];
        $ejesTCC = $row['ejesTCC'];
        $alimentacionTCC = $row['alimentacionTCC'];
        $ejesECBoss = $row['ejesECBoss'];
        $ejesRack = $row['ejesRack'];
        $rack = $row['rack'];
        $puerto = $row['puerto'];
        $direccionIP = $row['direccionIP'];
        $idEquipoPadre = $row['idEquipoPadre'];
        $rutaImagen = $row['rutaImagen'];
        $equipo_controla = $row['equipo_controla'];
        $equipoSource = $row['sourceAlarma'];
        $ahnoAdquisicion = $row['AhnoAdquisicion'];
        $propiedades = $row['propiedades'];
        $evaluaciones = $row['evaluaciones'];
        $nombreProovedor = $row['nombreProovedor'];
        $mantenimientos = $row['mantenimientos'];
        $enGarantia = $row['enGarantia'];
        $vidaUtilResidual = $row['vidaUtilResidual'];
        $bajoPlanMantenimiento = $row['bajoPlanMantenimiento'];
        $idConvenioMantenimiento = $row['idConvenioMantenimiento'];
        $costoAnualMantenimiento = $row['costoAnualMantenimiento'];
        $frecuenciaAnualMantenimiento = $row['frecuenciaAnualMantenimiento'];
        $ahnoInstalacion = $row['AhnoInstalacion'];


        $sql = "SELECT * FROM categorias_equipos WHERE idCategoria = $idCategoria";
        $res = mysqli_query($conexion, $sql);

        while ($row = $res->fetch_assoc()) {
            $datos = [];
            $datos['nomCategoria'] = $row['nomCategoria'];
            $nomCategoria = $datos['nomCategoria'];
        }

        if($idDepartamento == 6){

            //$sql = "SELECT COUNT(*) AS cantidadHojasTec FROM hojas_tecnicas_inge_clinica WHERE idEquipo = $idEquipo";
            $sql = "SELECT COUNT(*) AS cantidadHojasTec FROM ordenes_trabajos_general WHERE idEquipo = $idEquipo";

        }else{
            $sql = "SELECT COUNT(*) AS cantidadHojasTec FROM ordenes_trabajos_general WHERE idEquipo = $idEquipo";
        }


        $res = mysqli_query($conexion, $sql);

        while ($row = $res->fetch_assoc()) {
            $datos = [];
            $datos['cantidadHojasTec'] = $row['cantidadHojasTec'];
            $cantidadHojasTec = $datos['cantidadHojasTec'];
        }

        if($idDepartamento == 6){
            $sqlSolicitud = "SELECT COUNT(*) AS total FROM solicitudes_compra_inge_clinica WHERE idEquipo = $idEquipo";
        }else{
            $sqlSolicitud = "SELECT COUNT(*) AS total FROM solicitudes_compra_sdo WHERE idEquipo = $idEquipo";
        }

        $resSolicitud = mysqli_query($conexion, $sqlSolicitud);

        while ($rowSolicitud = $resSolicitud->fetch_assoc()) {
            $datosSolicitud = [];
            $datosSolicitud['total'] = $rowSolicitud['total'];
            $total = $datosSolicitud['total'];
        }

        $sql = "SELECT COUNT(*) AS cantidadSubEquipos FROM equipamiento_hbqp WHERE idEquipoPadre = $idEquipo";
        $res = mysqli_query($conexion, $sql);

        while ($row = $res->fetch_assoc()) {
            $datos = [];
            $datos['cantidadSubEquipos'] = $row['cantidadSubEquipos'];
            $cantidadSubEquipos = $datos['cantidadSubEquipos'];
        }

        if($idDepartamento == 6){
            $sql = "SELECT SUM(costoTotal) AS gastoTotal FROM  gasto_equipo_carpeta_inge_clinica WHERE idEquipo = $idEquipo";
            $res = mysqli_query($conexion, $sql);
        }else{
            $sql = "SELECT SUM(costoTotal) AS gastoTotal FROM  gasto_equipo_carpeta WHERE idEquipo = $idEquipo";
            $res = mysqli_query($conexion, $sql);
        }



        while ($row = $res->fetch_assoc()) {
            $datos = [];
            $datos['gastoTotal'] = $row['gastoTotal'];
            $gastoTotal = $datos['gastoTotal'];
        }

        $sql = "SELECT COUNT(*) AS cantidadArchivos FROM archivos_equipamiento_hbqp WHERE idEquipo = $idEquipo";
        $res = mysqli_query($conexion, $sql);

        while ($row = $res->fetch_assoc()) {
            $datos = [];
            $datos['cantidadArchivos'] = $row['cantidadArchivos'];
            $cantidadArchivos = $datos['cantidadArchivos'];
        }

        $sqlx = "SELECT MAX(id) AS ultimo_id FROM equipamiento_hbqp";
        $resx = mysqli_query($conexion,$sqlx);
        while($rowx = $resx->fetch_assoc()){
            $ultimo_id = $rowx['ultimo_id'];
            $ultimo_id = $ultimo_id + 1;
        }

        $idGenerado = 'EQUIP-HBQP-' . $ultimo_id;

    }
}


$porcionesAntes = explode("ECB", $nombreEquipo);

$variableParaTablaAntes = 'ECB ' . $porcionesAntes[1];

$porcionesAntes2 = explode("_", $variableParaTablaAntes);

$variableParaTablaFinalAntes = $porcionesAntes2[0] . '-' . $porcionesAntes2[1];


//CONTAR DIGITAL INPUT Y ANALOG INPUT
$contardigitalinput = contardigitalinput($variableParaTablaFinalAntes);
$contaranaloginput = contaranaloginput($variableParaTablaFinalAntes);
//FIN DEL CONTEO INPUT

//CONTAR DIGITAL Y ANALOG OUTPUT
$contardigitaloutput = contardigitaloutput($variableParaTablaFinalAntes);

$contaranalogoutput = contaranalogoutput($variableParaTablaFinalAntes);
//FIN CONTEO OUTPUT
$contarambosinput = $contardigitalinput + $contaranaloginput;
$contarambosoutput = $contardigitaloutput + $contaranalogoutput;

$buscarequipoPadre = buscarequipoPadre($idEquipoPadre);

$contarsiguientes = 1;



?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Equipo</title>
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
    <script type="text/javascript">
        $(document).ready(function() {
            recargarNomDepartamento();

            $('#tipoArchivo').change(function() {
                recargarNomDepartamento();
            });

            function recargarNomDepartamento() {
                $.ajax({
                    type: "POST",
                    url: "sdo-datos-archivos/habilitarIngresoArchivo.php",
                    data: "id=" + $('#tipoArchivo').val(),
                    success: function(r) {
                        $('#visualizarNomTipo').html(r);
                    }
                });
            };
        });
    </script>
    <script>
        $(document).ready(function () {
            $(".show-agregar").click(function () {
                $("#agregarnuevodiv").show();
                $("#tablaequipos").hide();
                $("#divshowlistar").show();
                $("#divshowagregar").hide();
            });
            $(".show-listar").click(function (){
                $("#agregarnuevodiv").hide();
                $("#tablaequipos").show();
                $("#divshowlistar").hide();
                $("#divshowagregar").show();
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $(".agregarAccesorios").click(function () {
                $("#tabladelosaccesorios").hide();
                $("#listarAccesoriosbutton").show();
                $("#ingresodeaccesorios").show();
                $("#agregarAccesorios1").hide();
            });
            $(".listadoAccesorios").click(function () {
                $("#tabladelosaccesorios").show();
                $("#listarAccesoriosbutton").hide();
                $("#ingresodeaccesorios").hide();
                $("#agregarAccesorios1").show();
            });
            $(".agregarInsumos").click(function(){
               $("#agregarInsumos1").hide();
               $("#listarInsumosbutton").show();
               $("#ingresodeinsumos").show();
               $("#tabladelosinsumos").hide();
            });
            $(".listadoInsumos").click(function () {
                $("#tabladelosinsumos").show();
                $("#listarInsumosbutton").hide();
                $("#ingresodeinsumos").hide();
                $("#agregarInsumos1").show();
            });
            $(".agregarRegistros").click(function(){
                $("#agregarRegistros1").hide();
                $("#listarRegistrosbutton").show();
                $("#ingresoderegistros").show();
                $("#tabladelosregistros").hide();
            });
            $(".listadoRegistros").click(function () {
                $("#tabladelosregistros").show();
                $("#listarRegistrosbutton").hide();
                $("#ingresoderegistros").hide();
                $("#agregarRegistros1").show();
            });
        });
    </script>
    <style>
        .input-output-heading {
            font-size: 18px;
            color: #333; /* Cambiar color según tu preferencia */
            margin-bottom: 10px;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/jefes-sidebar.php';

    $messages = [
        'exitoEditarEquipo' => ['Exito!', 'Se guardo los cambios correctamente', 'success'],
        'errorEditarEquipo' => ['Error!', 'Se produjo un error al guardar los cambios', 'error'],
        'exitoHijos' => ['Exito!', 'Se agrego el sub-equipo exitosamente', 'success'],
        'errorHijos' => ['Error!', 'No fue posible agregar el sub equipo seleccionado', 'error'],
        'exitoCambioEstado' => ['Exito!', 'El cambio de estado del equipo resulto exitoso', 'success'],
        'errorCambioEstado' => ['Error!', 'Se genero un problema al cambiar el estado del equipo', 'error'],
        'exitoCambiarImagen' => ['Exito!', 'Cambio de Imagen Realizado', 'success'],
        'errorCambiarImagen' => ['Error!', 'Error al Cambiar Imagen.', 'error'],
        'exitoModificarSP' => ['Exito!', 'Cambio de Set-Point Realizado', 'success'],
        'errorModificarSP' => ['Error!', 'Error al Cambiar Set-Point.', 'error'],
        'errorModificarSPSinObservacion' => ['Error!', 'Error al Agregar Observacion de Cambio de Set-Point.', 'error'],
        'exitoForzarEncendidoEquipo' => ['Exito!', 'Forzado Encendido Realizado', 'success'],
        'errorForzarEncendidoEquipo' => ['Error!', 'Error al Forzar Encendido.', 'error'],
        'errorForzarEquipoSinObservacion' => ['Error!', 'Error al Agregar Observacion de Forzado.', 'error'],
        'exitoForzarApagadoEquipo' => ['Exito!', 'Forzado Apagado Realizado', 'success'],
        'errorForzarApagadoEquipo' => ['Error!', 'Error al Forzar Apagado.', 'error'],
        'exitoForzarAutomaticoEquipo' => ['Exito!', 'Modo Automatico Activado', 'success'],
        'errorForzarAutomaticoEquipo' => ['Error!', 'Error al Activar Modo Automatico.', 'error'],
        'exitoForzarVariablesEquipo' => ['Exito!', 'Variables Forzadas', 'success'],
        'errorForzarVariablesEquipo' => ['Error!', 'Error al Forzar Variables.', 'error'],
        'exitoAgregarObservacion' => ['Exito!', 'Observación Agregada.', 'success'],
        'errorAgregarObservacion' => ['Error!', 'Error al Agregar Observación.', 'error'],
    ];

    foreach ($messages as $param => list($title, $text, $icon)) {
        if (isset($_GET[$param])) {
            ?>
            <script>
                Swal.fire(
                    '<?php echo $title; ?>',
                    '<?php echo $text; ?>',
                    '<?php echo $icon; ?>'
                )
            </script>
            <?php
        }
    }
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
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <?php
                if($idDepartamento == 6){
                    include_once 'sdo-includes/vista-equipo-inge-clinico.php';
                }else{
                    include_once 'sdo-includes/vista-equipo.php';

                }
                ?>
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
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="../plugins/select2/js/select2.full.min.js"></script>
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
        $('.equipoQueControlaMod').select2()
        $('.equipoQueControlaAgregar').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })


    });
</script>
<script>
    $(document).ready(function () {
        $('#tablaSubequipos').DataTable({
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
        $('#tabla-equipos').DataTable({
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
            initComplete: function () {
                var idCategoria = document.getElementById('idCategoriaBuscar').value;
                console.log(idCategoria);
                if(idCategoria === '2'){
                    this.api().columns([2]).every(function () {
                        var column = this;
                        var select = $('<select style="width: 100%;"><option value="">Nombre Equipo</option></select>')
                            .appendTo($(column.header()).empty())
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });

                        column.data().unique().sort().each(function (d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>')
                        });
                    });
                    this.api().columns([5]).every(function () {
                        var column = this;
                        var select = $('<select style="width: 100%;"><option value="">Piso</option></select>')
                            .appendTo($(column.header()).empty())
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });

                        column.data().unique().sort().each(function (d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>')
                        });
                    });
                }else{
                    this.api().columns([1]).every(function () {
                        var column = this;
                        var select = $('<select style="width: 100%;"><option value="">Nombre Equipo</option></select>')
                            .appendTo($(column.header()).empty())
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });

                        column.data().unique().sort().each(function (d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>')
                        });
                    });
                    this.api().columns([4]).every(function () {
                        var column = this;
                        var select = $('<select style="width: 100%;"><option value="">Piso</option></select>')
                            .appendTo($(column.header()).empty())
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });

                        column.data().unique().sort().each(function (d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>')
                        });
                    });
                }


            }
        });

        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": false,
            "autoWidth": false,
            "responsive": true,
            "pageLength": 20,
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

        $('#tableCartaPlanificacion').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": false,
            "autoWidth": false,
            "responsive": true,
            "pageLength": 30,
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
<script>
    $(document).ready(function () {
        $('#tabla-equipos-inge').DataTable({
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
            }
        });

        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": false,
            "autoWidth": false,
            "responsive": true,
            "pageLength": 20,
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
                        url: '../sdo-funciones/eliminar-hoja-hijo.php',
                        type: 'POST',
                        data: dataString,
                        success: function (response) {
                            console.log('eliminado.. ' + dataString);
                            Swal.fire({
                                icon: 'success',
                                title: 'Registro del sub-equipo eliminado',
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
    $(document).ready(
        $('.eliminarManual').click(function (e) {
            e.preventDefault();
            let id = $(this).attr('id');
            var dataString = 'id=' + id;
            console.log(dataString);
            Swal.fire({
                icon: 'question',
                title: '¿Eliminar este documento?',
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
                        url: '../sdo-funciones/eliminar-manual.php',
                        type: 'POST',
                        data: dataString,
                        success: function (response) {
                            console.log('eliminado.. ' + dataString);
                            Swal.fire({
                                icon: 'success',
                                title: 'Documento fue eliminado con exito',
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
    $(document).ready(function (){
        $('.eliminarhallazgo').click(function (e) {
            e.preventDefault();
            let id = $(this).attr('id');
            var dataString = 'id=' + id;
            console.log(dataString);
            Swal.fire({
                icon: 'question',
                title: '¿Eliminar este hallazgo?',
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
                        url: '../sdo-funciones/eliminar-hallazgo.php',
                        type: 'POST',
                        data: dataString,
                        success: function (response) {
                            console.log('eliminado.. ' + dataString);
                            Swal.fire({
                                icon: 'success',
                                title: 'Hallazgo fue eliminado con exito',
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
        $('#tabla-puntos').DataTable({
            "paging": true,
            "iDisplayLength": 25,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": false,
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
        $('#tableAnotacionesSistema').DataTable({
            "paging": true,
            "iDisplayLength": 10,
            "searching": true,
            "ordering": false,
            "info": true,
            "processing": true,
            "autoWidth": false,
            "responsive": false,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "search": "Buscar:",
                "processing": "Procesando...",
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
        $('#tableDetalleControl').DataTable({
            "paging": true,
            "iDisplayLength": 25,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": false,
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
<!-- tabla-puntos -->
<script>
    $(function() {
        $('[data-toggle="popover"]').popover()
    })
</script>
<script>
    function mostrarOcultarFilas(id) {
        var filas = document.getElementsByClassName(id);
        console.log(filas);


        var idd = id;

        var subcadenas = idd.split("info");



        var btn = "button" + subcadenas[1];

        for (var i = 0; i < filas.length; i++) {
            if (filas[i].style.display === "none" || filas[i].style.display === "") {
                filas[i].style.display = "table-row";
                document.getElementById(btn).innerHTML = '<i class="fa fa-minus"></i>&nbsp;&nbsp;Minimizar';
            } else {
                filas[i].style.display = "none";
                document.getElementById(btn).innerHTML = '<i class="fa fa-plus"></i>&nbsp;&nbsp;Expandir';
            }
        }

    }
</script>
<script>
    // Agregar evento de clic a las filas principales para mostrar/ocultar las filas adicionales
    document.querySelectorAll('[data-widget="expandable-table"]').forEach(row => {
        row.addEventListener('click', () => {
            row.classList.toggle('active');
            const hiddenRows = row.nextElementSibling;
            if (hiddenRows.classList.contains('hidden')) {
                hiddenRows.classList.remove('hidden');
            } else {
                hiddenRows.classList.add('hidden');
            }
        });
    });
</script>
<script>
    $(document).ready(function () {
        var tableAlarmasBMS = $('#tableAlarmasBMS').DataTable({
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
                "url": "sdo-includes/bms_cargarAlarmas.php", // Archivo PHP que contiene la consulta SQL
                "type": "POST",
                "data": function (d) {
                    d.varSourceAlarma = $('#varSourceAlarma').val(); // Pasa el valor seleccionado del select como parámetro
                    console.log(d);

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
                {"data": "sourceAlarma"},
                {"data": "classAlarma"},
                {"data": "mensajeAlarma"},
                {"data": "fechaCreacionAlarma2"},
                {"data": "fechaNormalAlarma2"},
                {
                    "data": null,
                    "render": function (data, type, row) {
                        // Asegúrate de que data-id esté correctamente escapado
                        var mensajeAlarma2 = (row.mensajeAlarma || '').replace(/"/g, '&quot;');
                        var sourceAlarma2 = (row.sourceAlarma || '').replace(/"/g, '&quot;');
                        if(mensajeAlarma2 !== '' && sourceAlarma2 !== ''){
                            return '<button class="btn btn-primary btn-visualizar" data-source="' + sourceAlarma2 + '" data-mensaje="' + mensajeAlarma2 + '"><i class="fas fa-eye"></i></button>';
                        }else{
                            DESHABILITADO
                        }
                    }
                }



                // Agrega más columnas según sea necesario
            ],
            "createdRow": function (row, data, dataIndex) {
                $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                $('td', row).css('text-align', 'center'); // Aplica el estilo de centrado vertical a todas las celdas
                $('td:eq(0)', row).html(dataIndex + 1); // Actualiza el contenido de la primera celda (columna "N°")

                $('td', row).each(function(index) {
                    var cellText = $(this).text();
                    if (index !== 4 && index !== 5 && index !== 6) {
                        $(this).text(cellText.toUpperCase());
                    }else{
                        if(cellText === 'ALARMA ACTIVA'){
                            $(this).css({
                                'color': 'red',
                                'font-weight': 'bold'
                            });
                        }
                    }


                });
            }
        });

        // Evento para manejar la apertura del modal con los detalles de la credencial
        $('#tableAlarmasBMS tbody').on('click', 'button.btn-visualizar', function () {
            var source = $(this).attr('data-source');
            var mensaje = $(this).attr('data-mensaje');


            // Llenar los datos del formulario con los valores de la fila seleccionada
            $('#visualizarAlarmas #sourceMostrar').val(source);
            $('#visualizarAlarmas #mensajeMostrar').val(mensaje);


            // Realizar una solicitud AJAX para obtener los datos y llenar la tabla
            $.ajax({
                url: 'sdo-includes/bms_registroAlarmas.php', // Cambia esto a la ruta de tu API
                method: 'POST',
                data: { source: source, mensaje: mensaje },
                dataType: 'json', // Asegúrate de que la respuesta sea tratada como JSON
                success: function (response) {
                    console.log('Datos recibidos del servidor:', response); // Añadir este console.log para ver los datos recibidos

                    // Verificar que response sea un array
                    if (Array.isArray(response)) {
                        var table = $('#tableRegistroAlarmas').DataTable();
                        table.clear().draw();
                        response.forEach(function (item,index) {
                            table.row.add([
                                index + 1,
                                item.fechaCreacionAlarma2,
                                item.fechaNormalAlarma2
                            ]).draw(false);
                        });
                    } else {
                        console.error('La respuesta no es un array:', response);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error en la solicitud AJAX:', status, error);
                }
            });


            // Mostrar el modal
            $('#editarModal').modal('show');
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#tableRegistroAlarmas').DataTable({
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
        });
    });
</script>
</body>

</html>
