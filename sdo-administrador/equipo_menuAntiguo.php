<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);


date_default_timezone_set('America/Santiago');

$fecha = date('d-m-Y H:i');

$ipPermitida = '10.69.250.151'; // Reemplaza con la IP permitida

$ipCliente = $_SERVER['REMOTE_ADDR'];

//if ($ipCliente === $ipPermitida) {
//    $valorDesarrollo = 'Pasaste';
//} else {
//    header('Location: plataforma_mantenimiento.php');
//}

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

if (isset($_GET['exitoGenerarPlanificador']) && isset($_GET['idEquipo'])) {
    $idEquipo = $_GET['idEquipo'];

}

if (isset($_GET['idEquipo']) && is_numeric($_GET['idEquipo'])) {
    $_SESSION['idEquipo'] = $_GET['idEquipo'];  // Guardamos el valor de 'idEquipo' en la sesión
    $idEquipo = $_SESSION['idEquipo'];  // Ahora puedes usarlo en el resto de tu código
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
        $umaControl = $row['umaControl'];
        $umaControl2 = mb_convert_case($umaControl, MB_CASE_UPPER, 'UTF-8');
        $idEquipoPadre = $row['idEquipoPadre'];
        $rutaImagen = $row['rutaImagen'];
        $equipo_controla = $row['equipo_controla'];

        $equipoSource = $row['sourceAlarma'];
        $tipoMenu = $row['tipoMenu'];

        $sql = "SELECT * FROM categorias_equipos WHERE idCategoria = $idCategoria";
        $res = mysqli_query($conexion, $sql);

        while ($row = $res->fetch_assoc()) {
            $datos = [];
            $datos['nomCategoria'] = $row['nomCategoria'];
            $nomCategoria = $datos['nomCategoria'];
        }

        $sql = "SELECT COUNT(*) AS cantidadHojasTec FROM ordenes_trabajos_general WHERE idEquipo = $idEquipo";
        $res = mysqli_query($conexion, $sql);

        while ($row = $res->fetch_assoc()) {
            $datos = [];
            $datos['cantidadHojasTec'] = $row['cantidadHojasTec'];
            $cantidadHojasTec = $datos['cantidadHojasTec'];
        }

        $sqlSolicitud = "SELECT COUNT(*) AS total FROM solicitudes_compra_sdo WHERE idEquipo = $idEquipo";
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

        $sql = "SELECT SUM(costoTotal) AS gastoTotal FROM  gasto_equipo_carpeta WHERE idEquipo = $idEquipo";
        $res = mysqli_query($conexion, $sql);

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
        $resx = mysqli_query($conexion, $sqlx);
        while ($rowx = $resx->fetch_assoc()) {
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            recargarNomDepartamento();

            $('#tipoArchivo').change(function () {
                recargarNomDepartamento();
            });

            function recargarNomDepartamento() {
                $.ajax({
                    type: "POST",
                    url: "sdo-datos-archivos/habilitarIngresoArchivo.php",
                    data: "id=" + $('#tipoArchivo').val(),
                    success: function (r) {
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
            $(".show-listar").click(function () {
                $("#agregarnuevodiv").hide();
                $("#tablaequipos").show();
                $("#divshowlistar").hide();
                $("#divshowagregar").show();
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
        'exitoCrearPlanConOT' => ['Exito!', 'Tarea Planificada con Exito.', 'success'],
        'errorCrearPlanConOT' => ['Error!', 'Error al Planificar Tarea.', 'error'],
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


    if (isset($_GET['mensajeVar']) && isset($_SESSION['varRespuesta'])) {
        $varRespuesta = $_SESSION['varRespuesta'];

        if (!empty($varRespuesta)) {

            ?>
            <script>
                Swal.fire(
                    '<?php echo addslashes($varRespuesta); ?>', // Asegúrate de escapar las comillas y caracteres especiales
                    '',
                    'info'
                );
            </script>
            <?php
        }

        $_SESSION['varRespuesta'] = '';
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
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-info">
                                <?php
                                    if($idCategoria == 1){
                                        ?>
                                        <h1 class="card-title">Visualizando Equipo - <?php echo $nombreEquipo ?> - <?php echo $nomCategoria ?> - <?php echo $recinto?></h1>
                                        <?php
                                    }else{
                                        ?>
                                        <h1 class="card-title">Visualizando Equipo - <?php echo $nombreEquipo ?> - <?php echo $nomCategoria ?></h1>
                                        <?php
                                    }
                                ?>
                                <?php
                                if ($idEquipoPadre > 0) {

                                    $tipoMenuEquipoPadre = obtenerTipoMenu($idEquipoPadre);

                                    if ($tipoMenuEquipoPadre == 'nuevo') {

                                        ?>
                                        <form method="post" action="equipo_menuNuevo.php" class="text-right">
                                            <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipoPadre; ?>"/>
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atrás
                                            </button>
                                        </form>
                                        <?php
                                    } else {

                                        ?>
                                        <form method="post" action="equipo_menuAntiguo.php" class="text-right">
                                            <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipoPadre; ?>"/>
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atrás
                                            </button>
                                        </form>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <form method="post" action="carpeta-equipo.php" class="text-right">
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atrás
                                        </button>
                                    </form>
                                    <?php
                                }

                                $nomDetalleEquipo = $nombreEquipo;

                                $nomDetalleCategoria = $nomCategoria;

                                $nomDetalleCompleto = $nomDetalleEquipo . ' - ' . $nomDetalleCategoria;

                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">

                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile w">
                                <div class="text-center">
                                    <?php

                                    $obtenerRutaImagenSegunModelo = obtenerRutaImagenSegunModelo($modelo);

                                    if (!empty($rutaImagen)) {
                                        ?>
                                        <img class=" img-fluid" src="<?php echo $rutaImagen; ?>" width="200px" alt="ECBOS">
                                        <?php
                                    } else if (!empty($obtenerRutaImagenSegunModelo)) {
                                        ?>
                                        <img class=" img-fluid" src="<?php echo $obtenerRutaImagenSegunModelo; ?>" width="200px" alt="ECBOS">
                                        <?php
                                    } else {
                                        ?>
                                        <img class=" img-fluid" src="../sdo-administrador/img/controlCent-HBQP.png" width="200px" alt="Control Centralizado">
                                        <?php
                                    }

                                    ?>


                                </div>

                                <br>

                                <div class=" text-center">

                                    <button type="button" data-toggle="modal" data-target="#modal-modImagen" id="inputMozi" class="btn bg-blue btn-sm">
                                        <i class="fas fa-edit"></i>&nbsp;&nbsp;Modificar Imagen
                                    </button>

                                    <form method="post" action="../sdo-funciones/equipamiento_cambiarImagen.php" enctype="multipart/form-data">
                                        <!-- Modal -->
                                        <div class="modal fade" id="modal-modImagen" name="modal-modImagen" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary">
                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                            <b>Modificar Imagen de </b><?php echo $nombreEquipo; ?>
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div style="text-align:left;" class="form-group">
                                                            <label for="exampleInputBorder">Nombre de Equipo:
                                                                <code>*</code></label>
                                                            <input type="text" readonly="readonly" class="form-control " id="addNombre" name="addNombre" placeholder="Nombre de Equipo" value="<?php echo $nombreEquipo; ?>" required>
                                                        </div>

                                                        <div class="input-group mb-3">
                                                            <div class="custom-file">
                                                                <input type="file" required class="custom-file-input" id="imagenCategoriaEdita" name="imagenCategoriaEdita" aria-describedby="inputGroupFileAddon01" accept="image/*" onchange="mostrarNombreArchivo()">
                                                                <label class="custom-file-label" for="imagenCategoriaEdita" id="labelImagen">Subir Imagen</label>
                                                            </div>
                                                        </div>

                                                        <script>
                                                            function mostrarNombreArchivo() {
                                                                // Obtener el nombre del archivo seleccionado
                                                                var nombreArchivo = document.getElementById("imagenCategoriaEdita").files[0].name;
                                                                // Mostrar el nombre del archivo en el label
                                                                document.getElementById("labelImagen").innerHTML = nombreArchivo;
                                                            }
                                                        </script>
                                                        <div class="mt-4">
                                                            <hr>
                                                            <h4>Imagen Actual:</h4>
                                                            <img class="img-fluid" id="img" src="<?php echo $rutaImagen; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="idEquipoCambiar" id="idEquipoCambiar" value="<?php echo $idEquipo ?>">
                                                        <button type="submit" class="btn btn-primary">Cambiar Imagen</button>
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </form>

                                </div>

                                <br>

                                <?php
                                if($idCategoria == 1){
                                    ?>
                                    <h2 class="profile-username text-center text-bold">Equipo - <?php echo $nombreEquipo ?> - <?php echo $recinto?></h2>
                                    <?php
                                }else{
                                    ?>
                                    <h2 class="profile-username text-center text-bold">Equipo - <?php echo $nombreEquipo ?> </h2>
                                    <?php
                                }
                                ?>


                                <?php
                                if ($buscarequipoPadre !== 'null') {
                                    ?>
                                    <h5 class="text-center text-bold text-muted">Equipo Padre - <?php echo $buscarequipoPadre ?></h5>
                                    <?php
                                }
                                ?>
                                <!--                                <canvas id="myChart" width="300" height="300"></canvas>-->

                                <!-- AQUIIIIII -->

                                <?php

                                if ($contarambosinput > 0 && $contarambosoutput > 0) {
                                    ?>
                                    <br>
                                    <h6 class="text-left text-bold">Entradas (digitales y analógicas) ocupadas: <?php echo $contarambosinput ?></h6>
                                    <h6 class="text-left text-bold">Salidas (digitales y analógicas) ocupadas: <?php echo $contarambosoutput ?></h6>

                                    <?php
                                }
                                ?>

                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>

                    <div class="col-xl-9 col-lg-8 col-md-6 col-12">

                        <div class="card card-primary card-outline">
                            <div class="card-body">
                                <div class="row">
                                    <?php
                                    if ($estadoEquipo == 'Operativo' || $estadoEquipo == 'operativo' || $estadoEquipo == 'OPERATIVO') {
                                        $color = 'bg-gradient-success';
                                    } else if ($estadoEquipo == 'Mantenimiento') {
                                        $color = 'bg-gradient-warning';
                                    } else if ($estadoEquipo == 'OperativoObs') {
                                        $estadoEquipo = 'Operativo, Observacion';
                                        $color = 'bg-gradient-orange';
                                    } else {
                                        $color = 'bg-gradient-danger';
                                    }

                                    ?>

                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                                        <button class="btn btn-block <?php echo $color ?> btn-lg" style="font-size: medium" data-toggle="modal" data-target="#exampleModal">
                                            <i class="fas fa-edit"></i>
                                            <br> Estado Actual:
                                            <b><?php echo $estadoEquipo ?></b></i>
                                        </button>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                                        <button class="btn btn-block btn-lg btn-primary" style="font-size: medium" data-toggle="modal" data-target="#exampleModal1">
                                            <i class="fas fa-list"></i>
                                            <br>Observaciones del Equipo<br> </i>
                                        </button>
                                    </div>

                                    <?php
                                    if ($ipCliente == $ipPermitida) {
                                        ?>
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                                            <button class="btn btn-block btn-lg bg-purple" style="font-size: medium" data-toggle="modal" data-target="#modalTiempoFuncion">
                                                <i class="fas fa-clock"></i>
                                                <br> Tiempo de Funcionamiento<br> </i>
                                            </button>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header <?php echo $color ?>">
                                                    <h5 class="modal-title" id="exampleModalLabel">
                                                        <b>Cambiar el estado del equipo</b>
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="formularioCambiaEstado" method="POST" action="../sdo-funciones/cambiaEstadoEquipo.php">
                                                        <input type="hidden" value="<?php echo $idEquipo ?>" name="idEquipo">
                                                        <input type="hidden" value="<?php echo $idCategoria ?>" name="idCategoria">
                                                        <input type="hidden" value="<?php echo $nombreEquipo ?>" name="nombreEquipo">
                                                        <div class="form-row">
                                                            <div class="form-group col-md-12">
                                                                <label for="codigoBarra">Nuevo Estado:<code>*</code></label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-info"></i></span>
                                                                    </div>
                                                                    <select name="opcionEstado" id="opcionEstado" required class="form-control">
                                                                        <?php
                                                                        $fuera = 'Fuera de Servicio';
                                                                        $ope = 'Operativo';
                                                                        $man = 'Mantenimiento';
                                                                        $extra = 'Extraviado';
                                                                        if ($estadoEquipo == 'Fuera de Servicio') {
                                                                            ?>
                                                                            <option value="<?php echo $fuera ?>" selected="selected">Fuera de Servicio</option>
                                                                            <option value="Operativo">Operativo</option>
                                                                            <option value="Mantenimiento">Mantenimiento</option>
                                                                            <option value="OperativoObs">Operativo con Observacion</option>
                                                                            <option value="Extraviado">Extraviado</option>
                                                                            <?php
                                                                        } else if ($estadoEquipo == 'Mantenimiento') {
                                                                            ?>
                                                                            <option value="<?php echo $man ?>" selected="selected">Mantenimiento</option>
                                                                            <option value="Fuera de Servicio">Fuera de Servicio</option>
                                                                            <option value="Operativo">Operativo</option>
                                                                            <option value="OperativoObs">Operativo con Observacion</option>
                                                                            <option value="Extraviado">Extraviado</option>

                                                                            <?php
                                                                        } else if($estadoEquipo == 'Extraviado'){
                                                                            ?>
                                                                            <option value="<?php echo $ope ?>">Operativo</option>
                                                                            <option value="Mantenimiento">Mantenimiento</option>
                                                                            <option value="Fuera de Servicio">Fuera de Servicio</option>
                                                                            <option value="OperativoObs">Operativo con Observacion</option>
                                                                            <option value="Extraviado" selected="selected">Extraviado</option>

                                                                            <?php
                                                                        }else{
                                                                            ?>
                                                                            <option value="<?php echo $ope ?>" selected>Operativo</option>
                                                                            <option value="Mantenimiento">Mantenimiento</option>
                                                                            <option value="Fuera de Servicio">Fuera de Servicio</option>
                                                                            <option value="OperativoObs">Operativo con Observacion</option>
                                                                            <option value="Extraviado">Extraviado</option>
                                                                            <?php
                                                                        }
                                                                        ?>


                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-12">
                                                                <label for="codigoBarra">Observaciones:<code>*</code></label>
                                                                <div class="input-group mb-3">
                                                                    <textarea name="observaciones" id="observaciones" rows="3" class="form-control" required></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                    <button type="submit" class="btn btn-primary">Guardar Estado</button>

                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info">
                                                    <h5 class="modal-title" id="exampleModalLabel">
                                                        <b> Estas visualizando todas las observaciones de este equipo</b>
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">

                                                    <table class="table table-bordered table-sm" id="tabla-equipo-observacion">
                                                        <thead class="bg-info">
                                                        <tr>
                                                            <th scope="col" class="text-center">Usuario Observador</th>
                                                            <th scope="col" class="text-center">Fecha</th>
                                                            <th scope="col" class="text-center">Tipo</th>
                                                            <th scope="col" class="text-center">Detalle</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        $sqlObservaciones = "SELECT * FROM historial_cambios_equipos_respaldo WHERE idEquipo = $idEquipo";
                                                        $resObservaciones = mysqli_query($conexion, $sqlObservaciones);
                                                        while ($rowObservaciones = $resObservaciones->fetch_assoc()) {
                                                            $datos = [];
                                                            $datos['idCambio'] = $rowObservaciones['idCambio'];
                                                            $idCambio = $datos['idCambio'];
                                                            $datos['fecha'] = $rowObservaciones['fecha'];
                                                            $fecha = $datos['fecha'];
                                                            $datos['estado'] = $rowObservaciones['estado'];
                                                            $estado = $datos['estado'];
                                                            $datos['observaciones'] = $rowObservaciones['observaciones'];
                                                            $observaciones = $datos['observaciones'];
                                                            $datos['idEquipo'] = $rowObservaciones['idEquipo'];
                                                            $idEquipo = $datos['idEquipo'];
                                                            $datos['idUsuarioCambia'] = $rowObservaciones['idUsuarioCambia'];
                                                            $idUsuarioCambia = $datos['idUsuarioCambia'];
                                                            $datos['tipoUsuario'] = $rowObservaciones['tipoUsuario'];
                                                            $tipoUsuario = $datos['tipoUsuario'];
                                                            $fecha = strtotime($fecha);
                                                            $fechaFormateada = date('d/m/Y', $fecha);
                                                            ?>
                                                            <tr>
                                                                <?php
                                                                if ($tipoUsuario == 'administrativo') {
                                                                    $sql = "SELECT * FROM adm_tecnicos WHERE idAdmTecnicos = $idUsuarioCambia";
                                                                    $res = mysqli_query($conexion, $sql);
                                                                    while ($row2 = $res->fetch_assoc()) {
                                                                        $datos2 = [];
                                                                        $datos2['nomAdmTecnicos'] = $row2['nomAdmTecnicos'];
                                                                        $nombre = $datos2['nomAdmTecnicos'];
                                                                    }

                                                                } else if ($tipoUsuario == 'jefe tecnico') {
                                                                    $sql = "SELECT * FROM jefes_tecnicos WHERE idJefe = $idUsuarioCambia";
                                                                    $res = mysqli_query($conexion, $sql);
                                                                    while ($row2 = $res->fetch_assoc()) {
                                                                        $datos2 = [];
                                                                        $datos2['nomJefe'] = $row2['nomJefe'];
                                                                        $nombre = $datos2['nomJefe'];
                                                                    }

                                                                } else if ($tipoUsuario == 'personal') {
                                                                    $sql = "SELECT * FROM personal WHERE idPersonal = $idUsuarioCambia";
                                                                    $res = mysqli_query($conexion, $sql);
                                                                    while ($row2 = $res->fetch_assoc()) {
                                                                        $datos2 = [];
                                                                        $datos2['nomPersonal'] = $row2['nomPersonal'];
                                                                        $nombre = $datos2['nomPersonal'];
                                                                    }
                                                                } else if ($tipoUsuario == 'administrador') {
                                                                    $sql = "SELECT * FROM administracion WHERE idAdministrador = $idUsuarioCambia";
                                                                    $res = mysqli_query($conexion, $sql);
                                                                    while ($row2 = $res->fetch_assoc()) {
                                                                        $datos2 = [];
                                                                        $datos2['nomAdministrador'] = $row2['nomAdministrador'];
                                                                        $nombre = $datos2['nomAdministrador'];

                                                                    }

                                                                } else if ($tipoUsuario == 'mesa ayuda') {
                                                                    $sql = "SELECT * FROM mesa_ayuda WHERE idMesaAyuda = $idUsuarioCambia";
                                                                    $res = mysqli_query($conexion, $sql);
                                                                    while ($row2 = $res->fetch_assoc()) {
                                                                        $datos2 = [];
                                                                        $datos2['nomMesaAyuda'] = $row2['nomMesaAyuda'];
                                                                        $nombre = $datos2['nomMesaAyuda'];

                                                                    }
                                                                } else if ($tipoUsuario == 'directiva') {
                                                                    $sql = "SELECT * FROM directivos WHERE idDirectivo = $idUsuarioCambia ";
                                                                    $res = mysqli_query($conexion, $sql);
                                                                    while ($row2 = $res->fetch_assoc()) {
                                                                        $datos2 = [];
                                                                        $datos2['nomDirectivo'] = $row2['nomDirectivo'];
                                                                        $nombre = $datos2['nomDirectivo'];
                                                                    }
                                                                }
                                                                ?>
                                                                <td class="text-center" style="font-size: 14px">
                                                                    <?php
                                                                    echo $nombre;
                                                                    ?>

                                                                </td>

                                                                <td class="text-center" style="font-size: 14px"><?php echo $fechaFormateada ?></td>
                                                                <td class="text-center" style="font-size: 14px"><?php echo mb_convert_case($estado, MB_CASE_UPPER, 'UTF-8') ?></td>
                                                                <td class="text-center" style="font-size: 14px"><?php echo $observaciones ?></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                        ?>
                                                        </tbody>
                                                        <tfoot class="bg-info">
                                                        <th scope="col" class="text-center">Usuario Observador</th>
                                                        <th scope="col" class="text-center">Fecha</th>
                                                        <th scope="col" class="text-center">Tipo</th>
                                                        <th scope="col" class="text-center">Detalle</th>
                                                        </tfoot>
                                                    </table>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">Cerrar Modal</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="modalTiempoFuncion" tabindex="-1" role="dialog" aria-labelledby="modalTiempoFuncion" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header bg-purple">
                                                    <h5 class="modal-title" id="exampleModalLabel">
                                                        <b>Tiempo de Funcionamiento</b>
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <?php

                                                    $aniosFuera = 0;
                                                    $mesesFuera = 0;
                                                    $diasFuera = 0;
                                                    $horasFuera = 0;
                                                    $minutosFuera = 0;

                                                    $aniosOperativo = 0;
                                                    $mesesOperativo = 0;
                                                    $diasOperativo = 0;
                                                    $horasOperativo = 0;
                                                    $minutosOperativo = 0;

                                                    $aniosMantenimiento = 0;
                                                    $mesesMantenimiento = 0;
                                                    $diasMantenimiento = 0;
                                                    $horasMantenimiento = 0;
                                                    $minutosMantenimiento = 0;

                                                    $aniosOperativoObs = 0;
                                                    $mesesOperativoObs = 0;
                                                    $diasOperativoObs = 0;
                                                    $horasOperativoObs = 0;
                                                    $minutosOperativoObs = 0;

                                                    // Fecha actual
                                                    $fechaActual = new DateTime();

                                                    $sqlEquipamiento = "SELECT fechaRegistro FROM equipamiento_hbqp WHERE id = $idEquipo";
                                                    $resEquipamiento = mysqli_query($conexion, $sqlEquipamiento);
                                                    $rowEquipamiento = $resEquipamiento->fetch_assoc();
                                                    $fechaRegistro = new DateTime($rowEquipamiento['fechaRegistro']);

                                                    $diferenciaParaTotal = $fechaActual->diff($fechaRegistro);

                                                    $aniosTotal = $diferenciaParaTotal->y;
                                                    $mesesTotal = $diferenciaParaTotal->m;
                                                    $diasTotal = $diferenciaParaTotal->d;
                                                    $horasTotal = $diferenciaParaTotal->h;
                                                    $minutosTotal = $diferenciaParaTotal->i;

                                                    $cantidadHistorialEquipo = obtenerCantidadHistorialEquipo($idEquipo);

                                                    $fraseTotal = "$aniosTotal Año(s), $mesesTotal Mes(es), $diasTotal Día(s), $horasTotal Hora(s), $minutosTotal Minuto(s)";

                                                    if ($cantidadHistorialEquipo == 0) {

                                                        if ($estado == 'Fuera de Servicio') {

                                                            $fraseOperativo = "0 Año(s), 0 Mes(es), 0 Día(s), 0 Hora(s), 0 Minuto(s)";
                                                            $fraseFuera = "$aniosTotal Año(s), $mesesTotal Mes(es), $diasTotal Día(s), $horasTotal Hora(s), $minutosTotal Minuto(s)";
                                                            $fraseMantenimiento = "0 Año(s), 0 Mes(es), 0 Día(s), 0 Hora(s), 0 Minuto(s)";
                                                            $fraseObserva = "0 Año(s), 0 Mes(es), 0 Día(s), 0 Hora(s), 0 Minuto(s)";

                                                        } else if ($estado == 'Mantenimiento') {

                                                            $fraseOperativo = "0 Año(s), 0 Mes(es), 0 Día(s), 0 Hora(s), 0 Minuto(s)";
                                                            $fraseFuera = "0 Año(s), 0 Mes(es), 0 Día(s), 0 Hora(s), 0 Minuto(s)";
                                                            $fraseMantenimiento = "$aniosTotal Año(s), $mesesTotal Mes(es), $diasTotal Día(s), $horasTotal Hora(s), $minutosTotal Minuto(s)";
                                                            $fraseObserva = "0 Año(s), 0 Mes(es), 0 Día(s), 0 Hora(s), 0 Minuto(s)";

                                                        } else if ($estado == 'OperativoObs') {

                                                            $fraseOperativo = "0 Año(s), 0 Mes(es), 0 Día(s), 0 Hora(s), 0 Minuto(s)";
                                                            $fraseFuera = "0 Año(s), 0 Mes(es), 0 Día(s), 0 Hora(s), 0 Minuto(s)";
                                                            $fraseMantenimiento = "0 Año(s), 0 Mes(es), 0 Día(s), 0 Hora(s), 0 Minuto(s)";
                                                            $fraseObserva = "$aniosTotal Año(s), $mesesTotal Mes(es), $diasTotal Día(s), $horasTotal Hora(s), $minutosTotal Minuto(s)";

                                                        } else {

                                                            $fraseOperativo = "$aniosTotal Año(s), $mesesTotal Mes(es), $diasTotal Día(s), $horasTotal Hora(s), $minutosTotal Minuto(s)";
                                                            $fraseFuera = "0 Año(s), 0 Mes(es), 0 Día(s), 0 Hora(s), 0 Minuto(s)";
                                                            $fraseMantenimiento = "0 Año(s), 0 Mes(es), 0 Día(s), 0 Hora(s), 0 Minuto(s)";
                                                            $fraseObserva = "0 Año(s), 0 Mes(es), 0 Día(s), 0 Hora(s), 0 Minuto(s)";

                                                        }
                                                    } else {

                                                        $sqlBuscarEquipo = "SELECT * FROM historial_cambios_equipos WHERE idEquipo = $idEquipo ORDER BY fecha ASC";

                                                        $resBuscarEquipo = mysqli_query($conexion, $sqlBuscarEquipo);
                                                        $fechaAnterior = clone $fechaRegistro; // Clonamos la fecha de registro

                                                        $totalAnioFuera = 0;
                                                        $totalMesFuera = 0;
                                                        $totalDiaFuera = 0;
                                                        $totalHoraFuera = 0;
                                                        $totalMinutoFuera = 0;

                                                        $totalAnioMantenimiento = 0;
                                                        $totalMesMantenimiento = 0;
                                                        $totalDiaMantenimiento = 0;
                                                        $totalHoraMantenimiento = 0;
                                                        $totalMinutoMantenimiento = 0;

                                                        $totalAnioOperativoObs = 0;
                                                        $totalMesOperativoObs = 0;
                                                        $totalDiaOperativoObs = 0;
                                                        $totalHoraOperativoObs = 0;
                                                        $totalMinutoOperativoObs = 0;

                                                        while ($rowBuscarEquipo = $resBuscarEquipo->fetch_assoc()) {
                                                            $estadoBuscarEquipo = $rowBuscarEquipo['estado'];
                                                            $fechaEstado = new DateTime($rowBuscarEquipo['fecha']);

                                                            if ($estadoBuscarEquipo == 'Fuera de Servicio') {
                                                                $diferenciaFuera = $fechaEstado->diff($fechaAnterior);

                                                                $aniosFuera = $diferenciaFuera->format('%y');
                                                                $mesesFuera = $diferenciaFuera->format('%m');
                                                                $diasFuera = $diferenciaFuera->format('%d');
                                                                $horasFuera = $diferenciaFuera->format('%h');
                                                                $minutosFuera = $diferenciaFuera->format('%i');

                                                                $totalAnioFuera += $aniosFuera;
                                                                $totalMesFuera += $mesesFuera;
                                                                $totalDiaFuera += $diasFuera;
                                                                $totalHoraFuera += $horasFuera;
                                                                $totalMinutoFuera += $minutosFuera;

                                                            }

                                                            if ($estadoBuscarEquipo == 'Mantenimiento') {
                                                                $diferenciaMantenimiento = $fechaEstado->diff($fechaAnterior);

                                                                $aniosMantenimiento = $diferenciaMantenimiento->format('%y');
                                                                $mesesMantenimiento = $diferenciaMantenimiento->format('%m');
                                                                $diasMantenimiento = $diferenciaMantenimiento->format('%d');
                                                                $horasMantenimiento = $diferenciaMantenimiento->format('%h');
                                                                $minutosMantenimiento = $diferenciaMantenimiento->format('%i');

                                                                $totalAnioMantenimiento += $aniosMantenimiento;
                                                                $totalMesMantenimiento += $mesesMantenimiento;
                                                                $totalDiaMantenimiento += $diasMantenimiento;
                                                                $totalHoraMantenimiento += $horasMantenimiento;
                                                                $totalMinutoMantenimiento += $minutosMantenimiento;
                                                            }

                                                            if ($estadoBuscarEquipo == 'OperativoObs') {
                                                                $diferenciaOperativoObs = $fechaEstado->diff($fechaAnterior);

                                                                $aniosOperativoObs = $diferenciaOperativoObs->format('%y');
                                                                $mesesOperativoObs = $diferenciaOperativoObs->format('%m');
                                                                $diasOperativoObs = $diferenciaOperativoObs->format('%d');
                                                                $horasOperativoObs = $diferenciaOperativoObs->format('%h');
                                                                $minutosOperativoObs = $diferenciaOperativoObs->format('%i');

                                                                $totalAnioOperativoObs += $aniosOperativoObs;
                                                                $totalMesOperativoObs += $mesesOperativoObs;
                                                                $totalDiaOperativoObs += $diasOperativoObs;
                                                                $totalHoraOperativoObs += $horasOperativoObs;
                                                                $totalMinutoOperativoObs += $minutosOperativoObs;
                                                            }

                                                            $fechaAnterior = clone $fechaEstado;

                                                        }


                                                        $fraseOperativo = "0 Año(s), 0 Mes(es), 0 Día(s), 0 Hora(s), 0 Minuto(s)";
                                                        $fraseFuera = "$totalAnioFuera Año(s), $totalMesFuera Mes(es), $totalDiaFuera Día(s), $totalHoraFuera Hora(s), $totalMinutoFuera Minuto(s)";
                                                        $fraseMantenimiento = "$totalAnioMantenimiento Año(s), $totalMesFuera Mes(es), $totalDiaMantenimiento Día(s), $totalHoraMantenimiento Hora(s), $totalMinutoMantenimiento Minuto(s)";
                                                        $fraseObserva = "$totalAnioOperativoObs Año(s), $totalMesOperativoObs Mes(es), $totalDiaOperativoObs Día(s), $totalHoraOperativoObs Hora(s), $totalMinutoOperativoObs Minuto(s)";


                                                    }

                                                    ?>

                                                    <table class="table-hover table-bordered" id="tabla-equipo-observacion" width="100%" style="text-align: center">
                                                        <thead>
                                                        <tr>
                                                            <th colspan="2">
                                                                <h6>Historial de Cambio de Estado: <?php echo $idEquipo ?></h6>
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td>Tiempo Total:</td>
                                                            <td><?php echo $fraseTotal; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Tiempo Operativo:</td>
                                                            <td><?php echo $fraseOperativo; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Tiempo Operativo pero con Observaciones:</td>
                                                            <td><?php echo $fraseObserva; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Tiempo En Mantenimiento:</td>
                                                            <td><?php echo $fraseMantenimiento; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Tiempo Fuera de Servicio:</td>
                                                            <td><?php echo $fraseFuera; ?></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>

                                                    <?php


                                                    ?>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">Cerrar Modal</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-lg-3 col-12">
                                        <!-- small box -->
                                        <form action="equipo-hojas-tecnicas.php" method="post" style="display: inline">
                                            <input type="hidden" name="idCategoria" value="<?php echo $idCategoria ?>">
                                            <input type="hidden" name="idEquipo" value="<?php echo $idEquipo ?>">
                                            <button type="submit" style="font-size: medium" class="btn btn-block bg-gradient-primary btn-lg">
                                                <i class="fas fa-clipboard-list"></i><br><?php echo $cantidadHojasTec ?>
                                                <br>Hojas Técnicas
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-lg-3 col-12">
                                        <!-- small box -->
                                        <form action="equipo-gastos.php" method="post" style="display: inline">
                                            <input type="hidden" name="idEquipo" value="<?php echo $idEquipo ?>">
                                            <input type="hidden" name="idCategoria" value="<?php echo $idCategoria ?>">
                                            <button type="submit" style="font-size: medium" class="btn btn-block bg-gradient-secondary btn-lg">
                                                <i class="fas fa-money-bill"></i><br>
                                                <?php
                                                if ($gastoTotal == 0) {
                                                    echo '$ 0';
                                                } else {
                                                    echo moneda_chilena($gastoTotal);
                                                }
                                                ?>
                                                <br>Gastos
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-lg-3 col-12">
                                        <!-- small box -->
                                        <form action="equipo-solicitudes-compra.php" method="post" style="display: inline">
                                            <input type="hidden" name="idEquipo" value="<?php echo $idEquipo ?>">
                                            <input type="hidden" name="idCategoria" value="<?php echo $idCategoria ?>">
                                            <button type="submit" style="font-size: medium" class="btn btn-block bg-gradient-danger btn-lg">
                                                <i class="fas fa-cart-plus"></i><br>
                                                <?php
                                                $numero = 0;
                                                if ($total == 0) {
                                                    echo $numero;
                                                } else {
                                                    echo $total;
                                                }
                                                ?>
                                                <br>Solicitudes de Compra
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-lg-3 col-12">
                                        <!-- small box -->
                                        <form action="equipo-archivos.php" method="post" style="display: inline">
                                            <input type="hidden" name="idEquipo" value="<?php echo $idEquipo ?>">
                                            <input type="hidden" name="idCategoria" value="<?php echo $idCategoria ?>">
                                            <button type="submit" style="font-size: medium" class="btn btn-block bg-gradient-pink btn-lg">
                                                <i class="fas fa-archive"></i><br><?php echo $cantidadArchivos ?>
                                                <br>Archivos Adjuntos
                                            </button>
                                        </form>
                                    </div>

                                    <?php

                                    if ($nombreEquipo == 'BACNET MSTP') {
                                        ?>

                                        <div class="col-lg-3 col-12 m-3">
                                            <!-- small box -->
                                            <form action="../sdo-pdf/SDO-BOS-Detalle.php" method="post" style="display: inline">
                                                <button type="submit" style="font-size: medium" class="btn btn-block bg-gradient-yellow btn-lg">
                                                    <i class="fas fa-archive"></i><br>&nbsp;<br>RESUMEN BOS
                                                </button>
                                            </form>
                                        </div>
                                        <?php

                                    }

                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-secondary d-flex p-0">
                                <ul class="nav nav-pills mr-auto ml-auto p-2">
                                    <!--                    <li class="nav-item"><a class="nav-link" href="#tabInfo" data-toggle="tab">Informaci&oacute;n Equipo</a></li>-->
                                    <li class="nav-item">
                                        <a class="nav-link active text-white" href="#tabSubEquipo" data-toggle="tab">Sub-Equipos &nbsp;
                                            <span class="badge badge-danger"><?php echo $cantidadSubEquipos ?></span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white" href="#tabMod" data-toggle="tab">Modificar Equipo</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white" href="#tabDocs" data-toggle="tab">Documentaci&oacute;n</a>
                                    </li>
                                    <?php

                                    $modeloBuscar1 = 'ECB_300';
                                    $modeloBuscar2 = 'ECB_400';
                                    $modeloBuscar3 = 'ECB_600';

                                    $pos1 = strpos($modeloCadena, $modeloBuscar1);
                                    $pos2 = strpos($modeloCadena, $modeloBuscar2);
                                    $pos3 = strpos($modeloCadena, $modeloBuscar3);
                                    if ($pos1 === false) {
                                        if ($pos2 === false) {
                                            if ($pos3 === false) {
                                            } else {
                                                ?>
                                                <li class="nav-item">
                                                    <a class="nav-link text-white" href="#tabDetalle" data-toggle="tab">Detalle Controladores</a>
                                                </li>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <li class="nav-item">
                                                <a class="nav-link text-white" href="#tabDetalle" data-toggle="tab">Detalle Controladores</a>
                                            </li>
                                            <?php
                                        }
                                    } else {
                                        ?>

                                        <li class="nav-item">
                                            <a class="nav-link text-white" href="#tabDetalle" data-toggle="tab">Detalle Controladores</a>
                                        </li>
                                        <?php
                                    }

                                    ?>
                                    <li class="nav-item">
                                        <a class="nav-link text-white" href="#tabPlan" data-toggle="tab">Planificaci&oacute;n</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white" href="#tabHallazgos" data-toggle="tab">Hallazgos</a>
                                    </li>

                                    <?php
                                    if ($equipo_controla > 0) {
                                        ?>
                                        <li class="nav-item">
                                            <a class="nav-link text-white" href="#tabSistemas" data-toggle="tab">Observaciones Sistemas</a>
                                        </li>
                                        <?php
                                    }


                                    if (!empty($equipoSource)) {
                                        ?>
                                        <li class="nav-item">
                                            <a class="nav-link text-white" href="#tabAlarmasBMS" data-toggle="tab">Alarmas BMS</a>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                    <li class="nav-item">
                                        <a class="nav-link text-white" href="#tabEquipamientoBMS" data-toggle="tab">BMS</a>
                                    </li>
                                    <?php
                                    if (strpos($nombreEquipo, 'UMA-') === 0) {
                                           ?>
                                            <li class="nav-item">
                                                <a class="nav-link text-white" href="#tabRelaciones" data-toggle="tab">Relaciones</a>
                                            </li>
                                            <?php
                                        }
                                    ?>
                                    <?php
                                        if($idCategoria == 2){
                                            ?>
                                            <li class="nav-item">
                                                <a class="nav-link text-white" href="#tabCheckList" data-toggle="tab">Check List</a>
                                            </li>
                                            <?php
                                        }
                                    ?>
                                    <!--                                    <li class="nav-item"><a class="nav-link text-white" href="#tabEquipamientoMtto" data-toggle="tab">Mantenimiento</a></li>-->

                                </ul>
                            </div>

                            <div class="card-body">
                                <div class="tab-content">

                                    <div class="tab-pane" id="tabInfo">
                                        <div class="col-12">
                                            <h5>Informacion</h5>
                                        </div>
                                    </div>

                                    <div class="tab-pane " id="tabMod">
                                        <div class="col-12">
                                            <form action="../sdo-funciones/guardar-edicion-equipo-nuevo.php" method="post">
                                                <input type="hidden" name="idEquipo" value="<?php echo $idEquipo ?>">
                                                <br>
                                                <fieldset>
                                                    <legend>Información Técnica</legend>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="inputIDUnico" style="font-size: 12px;">ID Único:
                                                                <code>*</code></label>
                                                            <input type="text" class="form-control " required readonly name="inputIDUnico" id="inputIDUnico" value="<?php echo $identificadorUnico ?>" placeholder="Escriba el Identificador unico del equipo">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="inputInventario" style="font-size: 12px;">N° de Inventario</label>
                                                            <input type="text" class="form-control " id="inputInventario" name="inputInventario" value="<?php echo $numeroInventario ?>" placeholder="Escriba el numero de inventario del equipo">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="IDLicitacion" style="font-size: 12px;">ID - Licitación: </label>
                                                            <input type="text" class="form-control " id="IDLicitacion" name="IDLicitacion" value="<?php echo $idLicitacion ?>" placeholder="Escriba el id de litación del equipo">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="ordenCompra" style="font-size: 12px;">Orden de compra:</label>
                                                            <input type="text" class="form-control " id="ordenCompra" name="ordenCompra" value="<?php echo $ordenCompra ?>">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="inputMantenimiento" style="font-size: 12px;">F. Mantenimiento</label>
                                                            <input type="text" class="form-control " name="inputMantenimiento" value="<?php echo $frecuenciaMantenimiento ?>" id="inputMantenimiento">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="fechaRecepcion" style="font-size: 12px;">Fecha de recepción</label>
                                                            <input type="date" class="form-control " name="fechaRecepcion" value="<?php echo $fechaRecepcion ?>" id="fechaRecepcion">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="fechaCompra" style="font-size: 12px;">Fecha de Compra:</label>
                                                            <input type="date" class="form-control " name="fechaCompra" value="<?php echo $fechaCompra ?>" id="fechaCompra" name="fechaCompra">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="fechaMarcha" style="font-size: 12px;">Fecha en Marcha:</label>
                                                            <input type="date" class="form-control " name="fechaMarcha" value="<?php echo $fechaMarcha ?>" id="fechaMarcha" name="fechaMarcha">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="clase" style="font-size: 12px;">Clase:</label>
                                                            <input type="text" class="form-control " id="clase" value="<?php echo $clase ?>" name="clase">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="subClase" style="font-size: 12px;">Sub-Clase:</label>
                                                            <input type="text" class="form-control " id="subClase" value="<?php echo $subclase ?>" name="subClase">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="complejidad" style="font-size: 12px;">Complejidad Tecnológica:</label>
                                                            <input type="text" class="form-control " id="complejidad" value="<?php echo $complejidadTec ?>" name="complejidad">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="riesgo" style="font-size: 12px;">Nivel de riesgo:</label>
                                                            <input type="text" class="form-control " id="riesgo" value="<?php echo $nivelRiesgo ?>" name="riesgo">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="costoMantencion" style="font-size: 12px;">Costo Mantención Anual: $</label>
                                                            <input type="text" class="form-control " id="costoMantencion" value="<?php echo $costoMantAnual ?>" name="costoMantencion">
                                                        </div>
                                                        <?php
                                                        if ($idCategoria == 2) {
                                                            ?>
                                                            <div class="form-group col-md-3 col-12">
                                                                <label for="costoMantencion" style="font-size: 12px;">Equipo que Controla en Sistema:</label>
                                                                <select class="form-control  equipoQueControlaMod" id="equipoQueControlaMod" name="equipoQueControlaMod" style="width: 100%">
                                                                    <option value="Seleccione...">Seleccione...</option>
                                                                    <?php
                                                                    $sqlCategoriasEquipos = "SELECT DISTINCT idSubCategoria FROM equipos";
                                                                    $resCategoriasEquipos = mysqli_query($conexion, $sqlCategoriasEquipos);
                                                                    if ($resCategoriasEquipos->num_rows > 0) {
                                                                        while ($rowCategoriasEquipos = $resCategoriasEquipos->fetch_assoc()) {
                                                                            $idSubCatEquipo = $rowCategoriasEquipos['idSubCategoria'];
                                                                            $nomSubCatEquipo = getNomSubCategoria($idSubCatEquipo);
                                                                            $getCategoriaSegunSubcategoria = getCategoriaSegunSubcategoria($idSubCatEquipo);
                                                                            $nomCatEquipo = getNomCategoria($getCategoriaSegunSubcategoria);

                                                                            // Agregar el ID y el nombre de la subcategoría al array
                                                                            $categoriasEquipos[] = array(
                                                                                'id' => $idSubCatEquipo,
                                                                                'nombre' => $nomSubCatEquipo,
                                                                                'idPadre' => $getCategoriaSegunSubcategoria,
                                                                                'nomPadre' => $nomCatEquipo
                                                                            );
                                                                        }
                                                                    }

                                                                    usort($categoriasEquipos, function ($a, $b) {
                                                                        // Primero ordenar por nomPadre y luego por nombre
                                                                        $cmp = strcmp($a['nomPadre'], $b['nomPadre']);
                                                                        if ($cmp == 0) {
                                                                            return strcmp($a['nombre'], $b['nombre']);
                                                                        }
                                                                        return $cmp;
                                                                    });


                                                                    // Recorrer $categoriasEquipos y agregar las opciones al select
                                                                    foreach ($categoriasEquipos as $categoria) {
                                                                        if (!empty($categoria['nombre']) && !empty($categoria['nomPadre'])) {
                                                                            echo '<optgroup label="' . $categoria['nomPadre'] . ' / ' . $categoria['nombre'] . '">';

                                                                            $idCategoriaEquipo = $categoria['id'];
                                                                            $sqlEquipos = "SELECT * FROM equipos WHERE idSubCategoria = " . $idCategoriaEquipo;
                                                                            $resEquipoSistema = mysqli_query($conexion, $sqlEquipos);
                                                                            $idSubCategoria = 0;
                                                                            if ($resEquipoSistema->num_rows > 0) {
                                                                                while ($rowEquipoSistema = $resEquipoSistema->fetch_assoc()) {
                                                                                    if ($rowEquipoSistema['idEquipo'] == $equipo_controla) {
                                                                                        echo '<option value="' . $rowEquipoSistema['idEquipo'] . '" selected>' . $rowEquipoSistema['nomEquipo'] . '</option>';
                                                                                    } else {
                                                                                        echo '<option value="' . $rowEquipoSistema['idEquipo'] . '">' . $rowEquipoSistema['nomEquipo'] . '</option>';
                                                                                    }
                                                                                }
                                                                            }
                                                                            echo '</optgroup>';
                                                                        }
                                                                    }

                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-3 col-12">
                                                                <label for="sourceAlarma" style="font-size: 12px;">Source de Alarmas:</label>
                                                                <select class="form-control  sourceAlarmaMod" id="sourceAlarmaMod" name="sourceAlarmaMod" style="width: 100%">
                                                                    <option value="Seleccione...">Seleccione...</option>
                                                                    <?php
                                                                    $sqlSourceAlarmas = "SELECT DISTINCT nameSource FROM bms_sourceAlarmas";
                                                                    $resSourceAlarmas = mysqli_query($conexion, $sqlSourceAlarmas);
                                                                    if ($resSourceAlarmas->num_rows > 0) {
                                                                        while ($rowSourceAlarmas = $resSourceAlarmas->fetch_assoc()) {
                                                                            $nomSourceAlarmas = $rowSourceAlarmas['nameSource'];

                                                                            // Agregar el ID y el nombre de la subcategoría al array
                                                                            $dataSource[] = array(
                                                                                'nombre' => $nomSourceAlarmas
                                                                            );
                                                                        }
                                                                    }

                                                                    usort($dataSource, function ($a, $b) {
                                                                        // Primero ordenar por nomPadre y luego por nombre
                                                                        $cmp = strcmp($a['nombre'], $b['nombre']);
                                                                        return $cmp;
                                                                    });


                                                                    // Recorrer $categoriasEquipos y agregar las opciones al select
                                                                    foreach ($dataSource as $data) {
                                                                        if (!empty($data['nombre'])) {
                                                                            $nombre = $data['nombre'];
                                                                            if ($nombre == $equipoSource) {
                                                                                echo '<option value="' . $nombre . '" selected>' . $nombre . '</option>';
                                                                            } else {
                                                                                echo '<option value="' . $nombre . '">' . $nombre . '</option>';
                                                                            }
                                                                        }
                                                                    }

                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <?php
                                                        }
                                                        ?>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="costoMantencion" style="font-size: 12px;">Tipo de Interfaz:</label>
                                                            <select class="form-control " name="tipoInterfaz" style="width: 100%">
                                                                <?php
                                                                if ($tipoMenu == 'nuevo') {
                                                                    ?>
                                                                    <option value="Seleccione...">Seleccione...</option>
                                                                    <option value="nuevo" selected>Interfaz Compacta</option>
                                                                    <option value="antiguo">Interfaz Detallada</option>
                                                                    <?php
                                                                } else if ($tipoMenu == 'antiguo') {
                                                                    ?>
                                                                    <option value="Seleccione...">Seleccione...</option>
                                                                    <option value="nuevo">Interfaz Compacta</option>
                                                                    <option value="antiguo" selected>Interfaz Detallada</option>
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <option value="Seleccione..." selected>Seleccione...</option>
                                                                    <option value="nuevo">Interfaz Compacta</option>
                                                                    <option value="antiguo">Interfaz Detallada</option>
                                                                    <?php
                                                                }
                                                                ?>

                                                            </select>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                                <hr>
                                                <fieldset>
                                                    <legend>Información Básica</legend>

                                                    <div class="form-row">
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="nombreEquipo" style="font-size: 12px;">Nombre del equipo:
                                                                <code>*</code></label>
                                                            <input type="text" class="form-control " required id="nombreEquipo" name="nombreEquipo" value="<?php echo $nombreEquipo ?>" placeholder="Escriba el nombre del equipo">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="valorConIva" style="font-size: 12px;">Valor con IVA:</label>
                                                            <input type="number" class="form-control " id="valorConIva" value="<?php echo $valorConIva ?>" name="valorConIva">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="marcaEquipo" style="font-size: 12px;">Marca</label>
                                                            <input type="text" class="form-control " id="marcaEquipo" name="marcaEquipo" value="<?php echo $marca ?>" placeholder="Escriba la marca del equipo">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="modeloEquipo" style="font-size: 12px;">Modelo</label>
                                                            <input type="text" class="form-control " id="modeloEquipo" name="modeloEquipo" value="<?php echo $modelo ?>" placeholder="Escriba el modelo del equipo">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="fechaFabricación" style="font-size: 12px;">Año de fabricación</label>
                                                            <input type="text" class="form-control " id="fechaFabricación" value="<?php echo $AhnoFrabricacion ?>" name="fechaFabricación">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="vidaUtil" style="font-size: 12px;">Vida Útil (años):</label>
                                                            <input type="text" class="form-control " id="vidaUtil" value="<?php echo $vidaUtil ?>" name="vidaUtil">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="versionSoftware" style="font-size: 12px;">Versión de Software:</label>
                                                            <input type="text" class="form-control " id="versionSoftware" value="<?php echo $versionSoftware ?>" name="versionSoftware">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="paisOrigen" style="font-size: 12px;">Pais de origen:</label>
                                                            <input type="text" class="form-control " id="paisOrigen" value="<?php echo $paisOrigen ?>" name="paisOrigen">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="terminoGarantia" style="font-size: 12px;">Termino de garantía:</label>
                                                            <input type="text" class="form-control " id="terminoGarantia" value="<?php echo $terminoGarantia ?>" name="terminoGarantia">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="numSerie" style="font-size: 12px;">Numero de serie:</label>
                                                            <input type="text" class="form-control " id="numSerie" value="<?php echo $numeroSerie ?>" name="numSerie">
                                                        </div>
                                                        <!--                                        --><?php
                                                        //                                        if ($tipoControlador == 'esclavo') {
                                                        //                                            ?>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="numSerie" style="font-size: 12px;">Device_id:</label>
                                                            <input type="text" class="form-control " id="device" value="<?php echo $device ?>" name="device">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="numSerie" style="font-size: 12px;">Network:</label>
                                                            <input type="text" class="form-control " id="network" value="<?php echo $network ?>" name="network">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="numSerie" style="font-size: 12px;">Mac Address:</label>
                                                            <input type="text" class="form-control " id="mac" value="<?php echo $mac ?>" name="mac">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="numSerie" style="font-size: 12px;">TCC:</label>
                                                            <input type="text" class="form-control " id="tcc" value="<?php echo $tcc ?>" name="tcc">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="numSerie" style="font-size: 12px;">Ejes TCC:</label>
                                                            <input type="text" class="form-control " id="ejestcc" value="<?php echo $ejesTCC ?>" name="ejestcc">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="numSerie" style="font-size: 12px;">Alimentacion TCC:</label>
                                                            <input type="text" class="form-control " id="alimentaciontcc" value="<?php echo $alimentacionTCC ?>" name="alimentaciontcc">
                                                        </div>
                                                        <!---->
                                                        <!--                                            --><?php
                                                        //                                        }
                                                        //                                        if ($tipoControlador == 'maestro') {
                                                        //                                            ?>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="ejesECBOSS" style="font-size: 12px;">Ejes ECBOSS:</label>
                                                            <input type="text" class="form-control " id="ejesECBOSS" value="<?php echo $ejesECBoss ?>" name="ejesECBOSS">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="ejesRack" style="font-size: 12px;">Ejes Rack:</label>
                                                            <input type="text" class="form-control " id="ejesRack" value="<?php echo $ejesRack ?>" name="ejesRack">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="rack" style="font-size: 12px;">Rack:</label>
                                                            <input type="text" class="form-control " id="rack" value="<?php echo $rack ?>" name="rack">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="puerto" style="font-size: 12px;">Puerto:</label>
                                                            <input type="text" class="form-control " id="puerto" value="<?php echo $puerto ?>" name="puerto">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="direccionIP" style="font-size: 12px;">Direccion IP:</label>
                                                            <input type="text" class="form-control " id="direccionIP" value="<?php echo $direccionIP ?>" name="direccionIP">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="umaControl" style="font-size: 12px;">UMA que maneja equipo:</label>
                                                            <select class="form-control listUmaControl" id="umaControl" name="umaControl" style="width: 100%">
                                                                <option value="Seleccione...">Seleccione...</option>
                                                                <?php
                                                                $sqlUMA = "SELECT * FROM equipos WHERE nomEquipo LIKE '%UMA%' ORDER BY nomEquipo ASC";
                                                                $resUMA = mysqli_query($conexion, $sqlUMA);
                                                                if ($resUMA->num_rows > 0) {
                                                                    while ($rowUMA = $resUMA->fetch_assoc()) {
                                                                        $umaControlSQL = $rowUMA['nomEquipo'];
                                                                        $umaControlSQL2 = mb_convert_case($umaControlSQL, MB_CASE_UPPER, 'UTF-8');
                                                                        if ($umaControlSQL2 == $umaControl2) {
                                                                            echo '<option value="' . $umaControlSQL2 . '" selected>' . $umaControlSQL2 . '</option>';
                                                                        } else {
                                                                            echo '<option value="' . $umaControlSQL2 . '">' . $umaControlSQL2 . '</option>';
                                                                        }
                                                                    }
                                                                }

                                                                ?>
                                                            </select>
                                                        </div>
                                                        <!--                                            --><?php
                                                        //                                        }
                                                        //
                                                        //                                        ?>

                                                    </div>
                                                </fieldset>
                                                <hr>
                                                <fieldset>
                                                    <legend>Ubicación</legend>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="IDLicitacion" style="font-size: 12px;">Servicio:
                                                                <code>*</code></label>
                                                            <select class="form-control " id="servicio" required name="servicio">
                                                                <option value="" disabled selected>-- Seleccione --</option>

                                                                <?php

                                                                $sqlServicios = "SELECT * FROM servicios ORDER BY nomServicio ASC";
                                                                $resServicios = mysqli_query($conexion, $sqlServicios);

                                                                while ($rowServicios = $resServicios->fetch_assoc()) {
                                                                    $datos = [];

                                                                    $datos['idServicio'] = $rowServicios['idServicio'];
                                                                    $datos['nomServicio'] = $rowServicios['nomServicio'];
                                                                    $idServicio = $datos['idServicio'];
                                                                    $nomServicio = $datos['nomServicio'];

                                                                    if ($idServicio == $servicioDB) {
                                                                        ?>
                                                                        <option value="<?php echo $idServicio ?>" selected><?php echo mb_strtoupper($nomServicio, 'UTF-8') ?></option>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <option value="<?php echo $idServicio ?>"><?php echo mb_strtoupper($nomServicio, 'UTF-8') ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="recinto" style="font-size: 12px;">Recinto:</label>
                                                            <input type="text" class="form-control " id="recinto" name="recinto" value="<?php echo $recinto ?>">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="pma" style="font-size: 12px;">PMA:</label>
                                                            <input type="text" class="form-control " id="pma" name="pma" value="<?php echo $pma ?>">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="coordenadas" style="font-size: 12px;">Coordenadas:</label>
                                                            <input type="text" class="form-control " id="coordenadas" name="coordenadas" value="<?php echo $coordenadas ?>">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="coordenadas" style="font-size: 12px;">Piso:</label>
                                                            <input type="text" class="form-control " id="piso" name="piso" value="<?php echo $piso ?>">
                                                        </div>
                                                    </div>
                                                </fieldset>
                                                <hr>
                                                <input type="hidden" name="tipoControlador" id="tipoControlador" value="<?php echo $tipoControlador ?>">
                                                <button type="submit" class="btn btn-warning btn-block text-center">
                                                    <b> Guardar Cambios</b>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="tab-pane active" id="tabSubEquipo">
                                        <div class="col-12">
                                            <br>
                                            <div class="row">
                                                <button class="btn bg-info text-center" data-toggle="modal" data-target="#staticBackdrop" aria-describedby="tooltip" id="agregar">
                                                    <i class="fas fa-plus"></i><b> Asociar un nuevo sub-equipo</b>
                                                </button>&nbsp;
                                                <div id="divshowagregar">
                                                    <button class="btn bg-gradient-success text-center show-agregar" id="agregarnuevo">
                                                        <i class="fas fa-plus"></i><b> Añadir un nuevo equipo y asociarlo como sub-equipo</b>
                                                    </button>
                                                </div>
                                                <div id="divshowlistar" style="display: none">
                                                    <button class="btn bg-gradient-success text-center show-listar" id="listarsub-equipos">
                                                        <i class="fas fa-clipboard-list"></i><b> Listar sub-equipos</b>
                                                    </button>
                                                </div>
                                            </div>
                                            <hr>
                                            <!-- Modal -->
                                            <div class="modal fade" id="staticBackdrop" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-info">
                                                            <h5 class="modal-title" id="staticBackdropLabel">Seleccione el equipo a añadir</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table class="table table-sm table-bordered table-hover" id="tablaSubequipos">
                                                                <thead>
                                                                <tr>
                                                                    <th scope="col" class="text-center">N°</th>
                                                                    <th scope="col" class="text-center">ID Unico</th>
                                                                    <th scope="col" class="text-center">Nombre Equipo</th>
                                                                    <th scope="col" class="text-center">Estado</th>
                                                                    <th scope="col" class="text-center">Seleccionar</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php
                                                                $sqlhijo = "SELECT * FROM equipamiento_hbqp WHERE (idEquipoPadre is null OR idEquipoPadre = 0) AND idCategoria = $idCategoria";
                                                                $resHijo = mysqli_query($conexion, $sqlhijo);
                                                                $num = 1;
                                                                while ($rowHijo = $resHijo->fetch_assoc()) {
                                                                    $datos = [];
                                                                    $datos['id'] = $rowHijo['id'];
                                                                    $datos['identificadorUnico'] = $rowHijo['identificadorUnico'];
                                                                    $datos['nombreEquipo'] = $rowHijo['nombreEquipo'];
                                                                    $datos['estadoEquipo'] = $rowHijo['estadoEquipo'];
                                                                    $idAsociar = $datos['id'];
                                                                    $identificadorUnicoAsociar = $datos['identificadorUnico'];
                                                                    $nombreEquipoAsociar = $datos['nombreEquipo'];
                                                                    $estadoEquipoAsociar = $datos['estadoEquipo'];
                                                                    ?>
                                                                    <tr>
                                                                        <td class="text-center"><?php echo $num ?></td>
                                                                        <td class="text-center"><?php echo $identificadorUnico ?></td>
                                                                        <td class="text-center"><?php echo $nombreEquipoAsociar ?></td>
                                                                        <td class="text-center"><?php echo $estadoEquipoAsociar ?></td>
                                                                        <td>
                                                                            <form action="../sdo-funciones/agregarEquipoHijo.php" method="POST">
                                                                                <input type="hidden" name="idEquipoPadre" value="<?php echo $idEquipo ?>">
                                                                                <input type="hidden" name="idEquipoHijo" value="<?php echo $idAsociar ?>">
                                                                                <input type="submit" value="Añadir Equipo" class="btn bg-danger">
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
                                                                    <th scope="col" class="text-center">ID Unico</th>
                                                                    <th scope="col" class="text-center">Nombre Equipo</th>
                                                                    <th scope="col" class="text-center">Estado</th>
                                                                    <th scope="col" class="text-center">Seleccionar</th>
                                                                </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                            <button type="button" class="btn btn-primary">Guardar Equipo</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="agregarnuevodiv" style="display:none;">
                                                <p class="text-bold text-danger">(*) Campos Obligatorios</p>
                                                <form action="../sdo-funciones/guardar-equipo.php" method="post">
                                                    <input type="hidden" name="idCategoria" value="<?php echo $idCategoria ?>">
                                                    <input type="hidden" name="idEquipoPadre" value="<?php echo $idEquipo ?>">
                                                    <fieldset>
                                                        <legend>Información Técnica</legend>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-2 col-12">
                                                                <label for="inputIDUnico" style="font-size: 12px;">ID Único:
                                                                    <code>*</code></label>
                                                                <input type="text" class="form-control " required readonly name="inputIDUnico" id="inputIDUnico" value="<?php echo $idGenerado ?>" placeholder="Escriba el Identificador unico del equipo">
                                                            </div>
                                                            <div class="col-md-1">
                                                                <div style="display: inline ; margin-left: 20px; font-size: 20px">
                                                                    <a class="pop-me-over" data-toggle="popover" data-placement="right" data-content="El ID Único es generado aleatoriamente y es la identificación del equipo dentro del sistema, guarde este código." data-title="Información"><i class="fas fa-question-circle"></i></a>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-3 col-12">
                                                                <label for="inputInventario" style="font-size: 12px;">N° de Inventario</label>
                                                                <input type="text" class="form-control " id="inputInventario" name="inputInventario" placeholder="Escriba el numero de inventario del equipo">
                                                            </div>
                                                            <div class="form-group col-md-3 col-12">
                                                                <label for="IDLicitacion" style="font-size: 12px;">ID - Licitación: </label>
                                                                <input type="text" class="form-control " id="IDLicitacion" name="IDLicitacion" placeholder="Escriba el id de litación del equipo">
                                                            </div>
                                                            <div class="form-group col-md-3 col-12">
                                                                <label for="ordenCompra" style="font-size: 12px;">Orden de compra:</label>
                                                                <input type="text" class="form-control " id="ordenCompra" name="ordenCompra">
                                                            </div>
                                                            <div class="form-group col-md-3 col-12">
                                                                <label for="inputMantenimiento" style="font-size: 12px;">F. Mantenimiento</label>
                                                                <input type="text" class="form-control " name="inputMantenimiento" id="inputMantenimiento">
                                                            </div>
                                                            <div class="form-group col-md-3 col-12">
                                                                <label for="fechaRecepcion" style="font-size: 12px;">Fecha de recepción</label>
                                                                <input type="date" class="form-control " name="fechaRecepcion" id="fechaRecepcion">
                                                            </div>
                                                            <div class="form-group col-md-3 col-12">
                                                                <label for="fechaCompra" style="font-size: 12px;">Fecha de Compra:</label>
                                                                <input type="date" class="form-control " id="fechaCompra" name="fechaCompra">
                                                            </div>
                                                            <div class="form-group col-md-3 col-12">
                                                                <label for="fechaMarcha" style="font-size: 12px;">Fecha en Marcha:</label>
                                                                <input type="date" class="form-control " id="fechaMarcha" name="fechaMarcha">
                                                            </div>
                                                            <div class="form-group col-md-3 col-12">
                                                                <label for="clase" style="font-size: 12px;">Clase:</label>
                                                                <input type="text" class="form-control " id="clase" name="clase">
                                                            </div>
                                                            <div class="form-group col-md-3 col-12">
                                                                <label for="subClase" style="font-size: 12px;">Sub-Clase:</label>
                                                                <input type="text" class="form-control " id="subClase" name="subClase">
                                                            </div>
                                                            <div class="form-group col-md-3 col-12">
                                                                <label for="complejidad" style="font-size: 12px;">Complejidad Tecnológica:</label>
                                                                <input type="text" class="form-control " id="complejidad" name="complejidad">
                                                            </div>
                                                            <div class="form-group col-md-3 col-12">
                                                                <label for="riesgo" style="font-size: 12px;">Nivel de riesgo:</label>
                                                                <input type="text" class="form-control " id="riesgo" name="riesgo">
                                                            </div>
                                                            <div class="form-group col-md-3 col-12">
                                                                <label for="costoMantencion" style="font-size: 12px;">Costo Mantención Anual: $</label>
                                                                <input type="text" class="form-control " id="costoMantencion" name="costoMantencion">
                                                            </div>
                                                            <div class="form-group col-md-3 col-12">
                                                                <label for="costoMantencion" style="font-size: 12px;">Equipo que Controla en Sistema:</label>
                                                                <select class="form-control  equipoQueControlaAgregar" id="equipoQueControlaAgregar" name="equipoQueControlaAgregar" style="width: 100%">
                                                                    <option value="Seleccione...">Seleccione...</option>
                                                                    <?php
                                                                    $sqlCategoriasEquipos = "SELECT DISTINCT idSubCategoria FROM equipos";
                                                                    $resCategoriasEquipos = mysqli_query($conexion, $sqlCategoriasEquipos);
                                                                    if ($resCategoriasEquipos->num_rows > 0) {
                                                                        while ($rowCategoriasEquipos = $resCategoriasEquipos->fetch_assoc()) {
                                                                            $idSubCatEquipo = $rowCategoriasEquipos['idSubCategoria'];
                                                                            $nomSubCatEquipo = getNomSubCategoria($idSubCatEquipo);
                                                                            $getCategoriaSegunSubcategoria = getCategoriaSegunSubcategoria($idSubCatEquipo);
                                                                            $nomCatEquipo = getNomCategoria($getCategoriaSegunSubcategoria);

                                                                            // Agregar el ID y el nombre de la subcategoría al array
                                                                            $categoriasEquiposParaSubEquipo[] = array(
                                                                                'id' => $idSubCatEquipo,
                                                                                'nombre' => $nomSubCatEquipo,
                                                                                'idPadre' => $getCategoriaSegunSubcategoria,
                                                                                'nomPadre' => $nomCatEquipo
                                                                            );
                                                                        }
                                                                    }

                                                                    usort($categoriasEquiposParaSubEquipo, function ($a, $b) {
                                                                        // Primero ordenar por nomPadre y luego por nombre
                                                                        $cmp = strcmp($a['nomPadre'], $b['nomPadre']);
                                                                        if ($cmp == 0) {
                                                                            return strcmp($a['nombre'], $b['nombre']);
                                                                        }
                                                                        return $cmp;
                                                                    });


                                                                    // Recorrer $categoriasEquipos y agregar las opciones al select
                                                                    foreach ($categoriasEquiposParaSubEquipo as $categoria) {
                                                                        if (!empty($categoria['nombre']) && !empty($categoria['nomPadre'])) {
                                                                            echo '<optgroup label="' . $categoria['nomPadre'] . ' / ' . $categoria['nombre'] . '">';

                                                                            $idCategoriaEquipo = $categoria['id'];
                                                                            $sqlEquipos = "SELECT * FROM equipos WHERE idSubCategoria = " . $idCategoriaEquipo;
                                                                            $resEquipoSistema = mysqli_query($conexion, $sqlEquipos);
                                                                            $idSubCategoria = 0;
                                                                            if ($resEquipoSistema->num_rows > 0) {
                                                                                while ($rowEquipoSistema = $resEquipoSistema->fetch_assoc()) {
                                                                                    if ($rowEquipoSistema['idEquipo'] == $equipo_controla) {
                                                                                        echo '<option value="' . $rowEquipoSistema['idEquipo'] . '" selected>' . $rowEquipoSistema['nomEquipo'] . '</option>';
                                                                                    } else {
                                                                                        echo '<option value="' . $rowEquipoSistema['idEquipo'] . '">' . $rowEquipoSistema['nomEquipo'] . '</option>';
                                                                                    }
                                                                                }
                                                                            }
                                                                            echo '</optgroup>';
                                                                        }
                                                                    }

                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-3 col-12">
                                                                <label for="costoMantencion" style="font-size: 12px;">Tipo de Interfaz:</label>
                                                                <select class="form-control " name="tipoInterfaz" style="width: 100%">

                                                                    <option value="Seleccione..." selected>Seleccione...</option>
                                                                    <option value="antiguo">Interfaz Detallada (Interfaz Antigua)</option>
                                                                    <option value="nuevo">Interfaz Compacta (Interfaz Nueva)</option>

                                                                </select>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                    <hr>
                                                    <fieldset>
                                                        <legend>Información Básica</legend>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-3 col-12">
                                                                <label for="nombreEquipo" style="font-size: 12px;">Nombre del equipo:
                                                                    <code>*</code></label>
                                                                <input type="text" class="form-control " required id="nombreEquipo" name="nombreEquipo" placeholder="Escriba el nombre del equipo">
                                                            </div>
                                                            <div class="form-group col-md-3 col-12">
                                                                <label for="valorConIva" style="font-size: 12px;">Valor con IVA:</label>
                                                                <input type="number" class="form-control " id="valorConIva" name="valorConIva">
                                                            </div>
                                                            <div class="form-group col-md-3 col-12">
                                                                <label for="marcaEquipo" style="font-size: 12px;">Marca</label>
                                                                <input type="text" class="form-control " id="marcaEquipo" name="marcaEquipo" placeholder="Escriba la marca del equipo">
                                                            </div>
                                                            <div class="form-group col-md-3 col-12">
                                                                <label for="modeloEquipo" style="font-size: 12px;">Modelo</label>
                                                                <input type="text" class="form-control " id="modeloEquipo" name="modeloEquipo" placeholder="Escriba el modelo del equipo">
                                                            </div>
                                                            <div class="form-group col-md-3 col-12">
                                                                <label for="fechaFabricación" style="font-size: 12px;">Año de fabricación</label>
                                                                <input type="text" class="form-control " id="fechaFabricación" name="fechaFabricación">
                                                            </div>
                                                            <div class="form-group col-md-3 col-12">
                                                                <label for="vidaUtil" style="font-size: 12px;">Vida Útil (años):</label>
                                                                <input type="text" class="form-control " id="vidaUtil" name="vidaUtil">
                                                            </div>
                                                            <div class="form-group col-md-3 col-12">
                                                                <label for="versionSoftware" style="font-size: 12px;">Versión de Software:</label>
                                                                <input type="text" class="form-control " id="versionSoftware" name="versionSoftware">
                                                            </div>
                                                            <div class="form-group col-md-3 col-12">
                                                                <label for="paisOrigen" style="font-size: 12px;">Pais de origen:</label>
                                                                <input type="text" class="form-control " id="paisOrigen" name="paisOrigen">
                                                            </div>
                                                            <div class="form-group col-md-3 col-12">
                                                                <label for="terminoGarantia" style="font-size: 12px;">Termino de garantía:</label>
                                                                <input type="text" class="form-control " id="terminoGarantia" name="terminoGarantia">
                                                            </div>
                                                            <div class="form-group col-md-3 col-12">
                                                                <label for="numSerie" style="font-size: 12px;">Numero de serie:</label>
                                                                <input type="text" class="form-control " id="numSerie" name="numSerie">
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                    <hr>
                                                    <fieldset>
                                                        <legend>Ubicación</legend>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-3 col-12">
                                                                <label for="IDLicitacion" style="font-size: 12px;">Servicio:
                                                                    <code>*</code></label>
                                                                <select class="form-control " id="servicio" required name="servicio">
                                                                    <option value="" disabled selected>-- Seleccione --</option>
                                                                    <?php
                                                                    $sqlServicios = "SELECT * FROM servicios ORDER BY nomServicio ASC";
                                                                    $resServicios = mysqli_query($conexion, $sqlServicios);

                                                                    while ($rowServicios = $resServicios->fetch_assoc()) {
                                                                        $datos = [];

                                                                        $datos['idServicio'] = $rowServicios['idServicio'];
                                                                        $datos['nomServicio'] = $rowServicios['nomServicio'];
                                                                        $idServicio = $datos['idServicio'];
                                                                        $nomServicio = $datos['nomServicio'];

                                                                        ?>

                                                                        <option value="<?php echo $idServicio ?>"><?php echo mb_strtoupper($nomServicio, 'UTF-8') ?></option>

                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-3 col-12">
                                                                <label for="recinto" style="font-size: 12px;">Recinto:</label>
                                                                <input type="text" class="form-control " id="recinto" name="recinto">
                                                            </div>
                                                            <div class="form-group col-md-3 col-12">
                                                                <label for="pma" style="font-size: 12px;">PMA:</label>
                                                                <input type="text" class="form-control " id="pma" name="pma">
                                                            </div>
                                                            <div class="form-group col-md-3 col-12">
                                                                <label for="coordenadas" style="font-size: 12px;">Coordenadas:</label>
                                                                <input type="text" class="form-control " id="coordenadas" name="coordenadas">
                                                            </div>
                                                            <div class="form-group col-md-3 col-12">
                                                                <label for="coordenadas" style="font-size: 12px;">Piso:
                                                                    <code>*</code>
                                                                    <code>Coloque un 0 si no conoce el piso</code></label>
                                                                <input type="text" required class="form-control " id="piso" name="piso">
                                                            </div>
                                                            <div class="form-group col-md-3 col-12">
                                                                <label for="coordenadas" style="font-size: 12px;">Departamento al que pertenece:
                                                                    <code>*</code></label>
                                                                <select name="idDepaCategoria" id="idDepaCategoria" class="form-control " required>
                                                                    <option value="selected">
                                                                        Seleccione un departamento
                                                                    </option>
                                                                    <?php
                                                                    $sqlDepa = "SELECT * FROM departamentos WHERE nomDepartamento NOT LIKE ('%Control Centralizado%') and nomDepartamento NOT LIKE ('%Informatica%') AND nomDepartamento NOT LIKE ('%Operacional%')";
                                                                    $resDepa = mysqli_query($conexion, $sqlDepa);
                                                                    while ($rowDepa = $resDepa->fetch_assoc()) {
                                                                        $datos1 = [];
                                                                        $datos1['nomDepartamento'] = $rowDepa['nomDepartamento'];
                                                                        $datos1['idDepartamento'] = $rowDepa['idDepartamento'];
                                                                        $idDepartamento1 = $datos1['idDepartamento'];
                                                                        $nomDepartamento = $datos1['nomDepartamento'];

                                                                        ?>
                                                                        <option value="<?php echo $idDepartamento1 ?>">
                                                                            <?php echo $nomDepartamento ?>
                                                                        </option>
                                                                        <?php
                                                                    }

                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                    <hr>
                                                    <input type="hidden" name="nomUsuarioCreador" id="nomUsuarioCreador" value="<?php echo $nomUsuario ?>">
                                                    <button type="submit" class="btn btn-success btn-block text-center">
                                                        <b> Guardar Equipo</b>
                                                    </button>
                                                </form>

                                                <br>
                                                <br>
                                            </div>

                                            <div id="tablaequipos" class="table-responsive">
                                                <table id="tabla-equipos" class="table table-hover  table-bordered table-sm">
                                                    <thead class="bg-secondary">
                                                    <tr>
                                                        <th scope="col" class="text-center">ID</th>
                                                        <th scope="col" class="text-center">Ver Puntos</th>
                                                        <th scope="col" class="text-center">Nombre Equipo</th>
                                                        <!-- <th scope="col" class="text-center">Servicio</th> -->
                                                        <th scope="col" class="text-center">Recinto</th>
                                                        <th scope="col" class="text-center">PMA</th>
                                                        <th scope="col" class="text-center">Piso</th>
                                                        <th scope="col" class="text-center">Estado</th>
                                                        <th scope="col" class="text-center">Acciones</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    $sqlEquipo = "SELECT * FROM equipamiento_hbqp WHERE idEquipoPadre = $idEquipo ORDER BY piso ASC, pma ASC, nombreEquipo ASC";
                                                    $resEquipo = mysqli_query($conexion, $sqlEquipo);

                                                    $numExpandir = 1;

                                                    while ($rowEquipo = $resEquipo->fetch_assoc()) {
                                                        $datos = [];
                                                        $datos['id'] = $rowEquipo['id'];
                                                        $datos['identificadorUnico'] = $rowEquipo['identificadorUnico'];
                                                        $datos['nombreEquipo'] = $rowEquipo['nombreEquipo'];
                                                        $datos['idServicio'] = $rowEquipo['idServicio'];
                                                        $datos['recinto'] = $rowEquipo['recinto'];
                                                        $datos['pma'] = $rowEquipo['pma'];
                                                        $datos['piso'] = $rowEquipo['piso'];
                                                        $datos['estadoEquipo'] = $rowEquipo['estadoEquipo'];
                                                        $datos['modelo'] = $rowEquipo['modelo'];
                                                        $datos['tipoMenu'] = $rowEquipo['tipoMenu'];

                                                        $idEquipoHijos = $datos['id'];
                                                        $identificadorUnico = $datos['identificadorUnico'];
                                                        $nombreEquipoEqui = $datos['nombreEquipo'];
                                                        $idServicio = $datos['idServicio'];
                                                        $recinto = $datos['recinto'];
                                                        $pma = $datos['pma'];
                                                        $piso = $datos['piso'];
                                                        $estadoEquipo = $datos['estadoEquipo'];
                                                        $modelo = $datos['modelo'];
                                                        $tipoMenuSubEquipo = $datos['tipoMenu'];

                                                        if ($recinto == '') {
                                                            $recinto = '-';
                                                        }

                                                        if ($pma == '') {
                                                            $pma = '-';
                                                        }

                                                        if ($piso == '') {
                                                            $piso = '-';
                                                        }


                                                        ?>
                                                        <tr>
                                                            <td class="text-center"><?php echo $numExpandir; ?></td>
                                                            <!-- <td class="text-center"><?php echo $identificadorUnico ?></td> -->
                                                            <?php
                                                            $posicion_inicioMostrar = strpos($nombreEquipoEqui, 'ECB');
                                                            // Si se encuentra 'ECB600_', obtener la subcadena desde esa posición hasta el siguiente espacio
                                                            if ($posicion_inicioMostrar !== false && $idCategoria == 2) {

                                                                ?>
                                                                <td class="text-center">
                                                                    <button class="bg-primary btn btn-block" data-toggle="modal" data-target="#mostrarPuntos<?php echo $idEquipoHijos ?>">Ver Puntos</button>
                                                                    <div class="modal fade" id="mostrarPuntos<?php echo $idEquipoHijos ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog modal-xl" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header bg-primary">
                                                                                    <h5 class="modal-title" id="exampleModalLabel">CONTROLADOR: <?php echo $nombreEquipoEqui ?></h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <?php


                                                                                    // Encontrar la posición del texto 'ECB600_'
                                                                                    $posicion_inicio = strpos($nombreEquipoEqui, 'ECB');
                                                                                    // Si se encuentra 'ECB600_', obtener la subcadena desde esa posición hasta el siguiente espacio
                                                                                    if ($posicion_inicio !== false) {
                                                                                        $posicion_inicio += strlen('ECB'); // Mover la posición de inicio después de 'ECB600_'
                                                                                        $posicion_fin = strpos($nombreEquipoEqui, ' ', $posicion_inicio); // Encontrar la posición del siguiente espacio
                                                                                        if ($posicion_fin !== false) {
                                                                                            $texto_deseado = substr($nombreEquipoEqui, $posicion_inicio, $posicion_fin - $posicion_inicio);
                                                                                            $nombreEquipoEqui = 'ECB' . $texto_deseado; // Esto mostrará '1042'
                                                                                        }
                                                                                    }


                                                                                    if (strpos($nombreEquipoEqui, 'ECB') === 0) {
                                                                                        // Reemplazar "_" con "-"
                                                                                        $nombreEquipoEqui = str_replace('_', '-', $nombreEquipoEqui);

                                                                                        // Agregar un espacio después de "ECB"
                                                                                        $nombreEquipoEqui = str_replace('ECB', 'ECB ', $nombreEquipoEqui);

                                                                                        $nombreEquipoEqui = trim($nombreEquipoEqui);
                                                                                    }


                                                                                    ?>
                                                                                    <table id="tabla-puntos" class="table-hover table-bordered">
                                                                                        <thead class="bg-secondary">
                                                                                        <tr>
                                                                                            <th>
                                                                                                <center></center>
                                                                                            </th>
                                                                                            <th>
                                                                                                <center>Piso
                                                                                                    <center>
                                                                                            </th>
                                                                                            <th>
                                                                                                <center>TCC
                                                                                                    <center>
                                                                                            </th>
                                                                                            <th>
                                                                                                <center>Controlador
                                                                                                    <center>
                                                                                            </th>
                                                                                            <th>
                                                                                                <center>N&uacute;mero de Entrada
                                                                                                    <center>
                                                                                            </th>
                                                                                            <th>
                                                                                                <center>Digital Input (UI)
                                                                                                    <center>
                                                                                            </th>
                                                                                            <th>
                                                                                                <center>Analog Input (UI)
                                                                                                    <center>
                                                                                            </th>
                                                                                            <th>
                                                                                                <center>N&uacute;mero de Salida
                                                                                                    <center>
                                                                                            </th>
                                                                                            <th>
                                                                                                <center>Digital Output (UO)
                                                                                                    <center>
                                                                                            </th>
                                                                                            <th>
                                                                                                <center>Analog Output (UO)
                                                                                                    <center>
                                                                                            </th>
                                                                                        </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                        <?php
                                                                                        $querySelectEquiposs = 'SELECT * FROM detalle_control_bms WHERE controlador_ControlBMS LIKE "' . $nombreEquipoEqui . '" ORDER BY idControlBMS ASC';
                                                                                        $resSelectEquipos = mysqli_query($conexion, $querySelectEquiposs);
                                                                                        $numsad = 1;
                                                                                        while ($rowSelectEquipos = $resSelectEquipos->fetch_assoc()) {
                                                                                            ?>
                                                                                            <tr>
                                                                                                <td><?php echo $numsad ?></td>
                                                                                                <td nowrap="nowrap" style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                                                                    <?php
                                                                                                    $piso_ControlBMSExpandir = mb_strtoupper($rowSelectEquipos['piso_ControlBMS']);
                                                                                                    echo $piso_ControlBMSExpandir;
                                                                                                    ?>
                                                                                                </td>
                                                                                                <td nowrap="nowrap" style="vertical-align:middle;" align="center">
                                                                                                    <?php
                                                                                                    $tcc_ControlBMSExpandir = mb_strtoupper($rowSelectEquipos['tcc_ControlBMS']);
                                                                                                    echo $tcc_ControlBMSExpandir;
                                                                                                    ?>
                                                                                                </td>
                                                                                                <td nowrap="nowrap" style="vertical-align:middle;" align="center">
                                                                                                    <?php
                                                                                                    $controlador_ControlBMSExpandir = mb_strtoupper($rowSelectEquipos['controlador_ControlBMS']);
                                                                                                    echo $controlador_ControlBMSExpandir;

                                                                                                    ?>
                                                                                                </td>
                                                                                                <td nowrap="nowrap" style="vertical-align:middle;" align="center">
                                                                                                    <?php
                                                                                                    $numEntrada_ControlBMSExpandir = $rowSelectEquipos['numEntrada_ControlBMS'];
                                                                                                    echo $numEntrada_ControlBMSExpandir;

                                                                                                    ?>
                                                                                                </td>
                                                                                                <td style="vertical-align:middle;" align="center">
                                                                                                    <?php
                                                                                                    $digitalInput_ControlBMSExpandir = $rowSelectEquipos['digitalInput_ControlBMS'];
                                                                                                    echo $digitalInput_ControlBMSExpandir;

                                                                                                    ?>
                                                                                                </td>
                                                                                                <td style="vertical-align:middle;" align="center">
                                                                                                    <?php
                                                                                                    $analogInput_ControlBMSExpandir = $rowSelectEquipos['analogInput_ControlBMS'];
                                                                                                    echo $analogInput_ControlBMSExpandir;

                                                                                                    ?>
                                                                                                </td>
                                                                                                <td style="vertical-align:middle;" align="center">
                                                                                                    <?php
                                                                                                    $numSalida_ControlBMSExpandir = $rowSelectEquipos['numSalida_ControlBMS'];
                                                                                                    echo $numSalida_ControlBMSExpandir;

                                                                                                    ?>
                                                                                                </td>
                                                                                                <td style="vertical-align:middle;" align="center">
                                                                                                    <?php
                                                                                                    $digitalOutput_ControlBMSExpandir = $rowSelectEquipos['digitalOutput_ControlBMS'];
                                                                                                    echo $digitalOutput_ControlBMSExpandir;

                                                                                                    ?>
                                                                                                </td>
                                                                                                <td style="vertical-align:middle;" align="center">
                                                                                                    <?php
                                                                                                    $analogOutput_ControlBMSExpandir = $rowSelectEquipos['analogOutput_ControlBMS'];
                                                                                                    echo $analogOutput_ControlBMSExpandir;

                                                                                                    ?>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <?php
                                                                                            $numsad++;
                                                                                        }
                                                                                        ?>
                                                                                        </tbody>
                                                                                        <tfoot class="bg-secondary">
                                                                                        <tr>
                                                                                            <th>
                                                                                                <center>N°</center>
                                                                                            </th>
                                                                                            <th>
                                                                                                <center>Piso
                                                                                                    <center>
                                                                                            </th>
                                                                                            <th>
                                                                                                <center>TCC
                                                                                                    <center>
                                                                                            </th>
                                                                                            <th>
                                                                                                <center>Controlador
                                                                                                    <center>
                                                                                            </th>
                                                                                            <th>
                                                                                                <center>N&uacute;mero de Entrada
                                                                                                    <center>
                                                                                            </th>
                                                                                            <th>
                                                                                                <center>Digital Input (UI)
                                                                                                    <center>
                                                                                            </th>
                                                                                            <th>
                                                                                                <center>Analog Input (UI)
                                                                                                    <center>
                                                                                            </th>
                                                                                            <th>
                                                                                                <center>N&uacute;mero de Salida
                                                                                                    <center>
                                                                                            </th>
                                                                                            <th>
                                                                                                <center>Digital Output (UO)
                                                                                                    <center>
                                                                                            </th>
                                                                                            <th>
                                                                                                <center>Analog Output (UO)
                                                                                                    <center>
                                                                                            </th>
                                                                                        </tr>
                                                                                        </tfoot>
                                                                                    </table>
                                                                                    <?php
                                                                                    ?>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <td class="text-center align-content-center">
                                                                    Deshabilitado
                                                                </td>
                                                                <?php
                                                            }
                                                            ?>

                                                            <td class="text-center"><?php echo $nombreEquipoEqui ?></td>
                                                            <!-- <td class="text-center"><?php echo $nombreServicio ?></td> -->
                                                            <td class="text-center"><?php echo $recinto ?></td>
                                                            <td class="text-center"><?php echo $pma ?></td>
                                                            <td class="text-center">
                                                                <?php

                                                                if ($piso == 0) {
                                                                    $piso = -1;
                                                                }

                                                                echo $piso;
                                                                ?>
                                                            </td>

                                                            <?php

                                                            if ($estadoEquipo == 'Operativo') {
                                                                ?>
                                                                <td class="font-weight-bold text-success  text-center " style="display: inline-block"><?php echo '<i class="fas fa-check"></i> ' . strtoupper($estadoEquipo) ?></td>
                                                                <?php
                                                            } else if ($estadoEquipo == 'Mantenimiento') {
                                                                ?>
                                                                <td class="font-weight-bold   text-warning text-center " style="display: inline-block"><?php echo '<i class="fas fa-exclamation-triangle"></i> ' . strtoupper($estadoEquipo) ?></td>
                                                                <?php
                                                            } else if ($estadoEquipo == 'OperativoObs') {
                                                                $estadoEquipo = 'Operativo con Observaciones'
                                                                ?>
                                                                <td class="font-weight-bold   text-secondary text-center " style="display: inline-block"><?php echo '<i class="fas fa-exclamation-circle"></i> ' . strtoupper($estadoEquipo) ?></td>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <td class="font-weight-bold   text-danger text-center " style="display: inline-block"><?php echo '<i class="fas fa-skull-crossbones"></i> ' . strtoupper($estadoEquipo) ?></td>
                                                                <?php
                                                            }

                                                            ?>
                                                            <td class="text-center">
                                                                <form action="../sdo-pdf/SDO-Hoja-Vida-Equipamiento.php" target="_blank" method="POST" style="display: inline;">
                                                                    <input type="hidden" id="idCarpeta" name="idCarpeta" value="<?php echo $idEquipoHijos ?>">
                                                                    <button type="submit" class="btn btn-primary btn-sm">
                                                                        <i class="far fa-file"></i>
                                                                    </button>
                                                                </form>
                                                                <?php
                                                                if ($tipoMenuSubEquipo == 'nuevo') {
                                                                    $idaMenu = 'equipo_menuNuevo.php';
                                                                } else {
                                                                    $idaMenu = 'equipo_menuAntiguo.php';
                                                                }
                                                                ?>
                                                                <form action="<?php echo $idaMenu; ?>" target="_blank" method="POST" style="display: inline">
                                                                    <input type="hidden" id="idEquipo" name="idEquipo" value="<?php echo $idEquipoHijos ?>">
                                                                    <button type="submit" class="btn btn-info btn-sm">
                                                                        <i class="fa-solid fa-door-open"></i>
                                                                    </button>
                                                                </form>

                                                                <button class="btn btn-danger btn-sm eliminar" id="<?php echo $idEquipoHijos ?>">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        $numExpandir++;
                                                    }


                                                    ?>
                                                    </tbody>
                                                    <tfoot class="bg-secondary">
                                                    <tr>
                                                        <th scope="col" class="text-center">ID</th>
                                                        <th scope="col" class="text-center">Ver Puntos</th>
                                                        <th scope="col" class="text-center">Nombre Equipo</th>
                                                        <!-- <th scope="col" class="text-center">Servicio</th> -->
                                                        <th scope="col" class="text-center">Recinto</th>
                                                        <th scope="col" class="text-center">PMA</th>
                                                        <th scope="col" class="text-center">Piso</th>
                                                        <th scope="col" class="text-center">Estado</th>
                                                        <th scope="col" class="text-center">Acciones</th>
                                                    </tr>
                                                    </tfoot>

                                                </table>


                                                <?php


                                                ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="tabDocs">
                                        <div class="col-12">
                                            <div class="row">
                                                <fieldset class="border p-3 text-center" style="width: 20%">
                                                    <p>
                                                        <b>Listado de Documentacion:</b>
                                                    </p>
                                                    <hr>
                                                    <button type="button" title="Agregar un manual" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#agregarmanual">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                    <hr>
                                                    <?php
                                                    $sqlArchivos = "SELECT * FROM archivos_equipamiento_hbqp WHERE idEquipo = $idEquipo";
                                                    $resArchivos = mysqli_query($conexion, $sqlArchivos);
                                                    $count = 1;
                                                    while ($rowArchivos = $resArchivos->fetch_assoc()) {
                                                        $nomArchivo = $rowArchivos['nomArchivo'];
                                                        $rutaArchivo = $rowArchivos['rutaArchivo'];
                                                        $idArchivo = $rowArchivos['id'];
                                                        $extension = pathinfo($rutaArchivo, PATHINFO_EXTENSION);

                                                        ?>
                                                        <ul class="nav flex-column">
                                                            <li class="nav-item">
                                                                <?php
                                                                if ($extension == 'pdf') {
                                                                    ?>
                                                                    <button class="btn bg-gradient-info" onclick="cambiarPDF('<?php echo $rutaArchivo; ?>')">
                                                                        <i class="fas fa-file-pdf"></i>&nbsp;<?php echo $nomArchivo ?>
                                                                    </button>
                                                                    <?php
                                                                } else if ($extension == 'jpg' || $extension == 'png' || $extension == 'jpeg') {
                                                                    ?>
                                                                    <button class="btn bg-gradient-info" onclick="cambiarPDF('<?php echo $rutaArchivo; ?>')">
                                                                        <i class="fas fa-image"></i>&nbsp;<?php echo $nomArchivo ?>
                                                                    </button>
                                                                    <?php
                                                                }
                                                                ?>
                                                                <!--<button type="button" title="Modificar el manual" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modificarmanual<?php echo $idArchivo ?>"><i class="fas fa-pen"></i></button>-->
                                                                <div class="modal fade" id="modificarmanual<?php echo $idArchivo ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-gradient-warning">
                                                                                <h5 class="modal-title" id="exampleModalLabel">Modificar Nombre del Manual</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <form action="../sdo-funciones/modificar-nomManueales.php" method="POST" enctype="multipart/form-data">
                                                                                <div class="modal-body">
                                                                                    <div class="row">
                                                                                        <div class="form-group col-md-12 col-12">
                                                                                            <label style="font-size:12px">Nombre Archivo:<code>*</code></label>
                                                                                            <div class="input-group mb-3">
                                                                                                <div class="input-group-prepend">
                                                                                                    <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                                                                </div>
                                                                                                <input type="text" class="form-control " required id="nomArchivoModi" name="nomArchivoModi" value="<?php echo $nomArchivo ?>">
                                                                                                <input type="hidden" name="id" id="id" value="<?php echo $idArchivo ?>">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                                    <button type="submit" class="btn btn-success">Modificar</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <button class="btn btn-danger btn-sm eliminarManual" id="<?php echo $idArchivo ?>">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                                <hr>
                                                            </li>
                                                        </ul>
                                                        <br>
                                                        <?php
                                                    }
                                                    ?>
                                                </fieldset>
                                                <fieldset style="width: 80%" class="border p-3 text-center">
                                                    <?php
                                                    if ($extension == 'pdf') {
                                                        ?>
                                                        <embed id="pdfViewer" src="" type="application/pdf" width="100%" height="900px"/>
                                                        <?php
                                                    } else if ($extension == 'jpg' || $extension == 'png' || $extension == 'jpeg') {
                                                        ?>
                                                        <img id="pdfViewer" src=""/>
                                                        <?php
                                                    }
                                                    ?>
                                                </fieldset>
                                            </div>
                                            <script>
                                                function cambiarPDF(ruta) {
                                                    document.getElementById('pdfViewer').setAttribute('src', ruta);
                                                }
                                            </script>
                                            <div class="modal fade" id="agregarmanual" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-info">
                                                            <h5 class="modal-title" id="exampleModalLabel">Formulario - Información del archivo</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="../sdo-funciones/guardar-archivos-carpeta.php" method="POST" enctype="multipart/form-data">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="idCarpeta" id="idCarpeta" value="<?php echo $idCategoria ?>">
                                                                <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipo ?>">
                                                                <input type="hidden" name="nomCarpeta" id="nomCarpeta" value="<?php echo $nomDetalleEquipo ?>">
                                                                <input type="hidden" name="idEquipamiento" id="idEquipamiento" value="<?php echo $idEquipo ?>">
                                                                <div class="row">
                                                                    <div class="form-group col-md-12 col-12">
                                                                        <label style="font-size:12px">Nombre Archivo:<code>*</code></label>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                                            </div>
                                                                            <input type="text" class="form-control " required id="nomArchivo" name="nomArchivo">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-md-12 col-12">
                                                                        <label for="exampleInputCodigoTarjeta" style="font-size:12px">Tipo Archivo:<code>*</code></label>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                                                            </div>
                                                                            <select class="form-control " required id="tipoArchivo" name="tipoArchivo">
                                                                                <option value="" disabled selected>-- Seleccione --</option>
                                                                                <?php
                                                                                $sql = "SELECT * FROM tiposarchivos";
                                                                                $res = mysqli_query($conexion, $sql);
                                                                                while ($row = $res->fetch_assoc()) {
                                                                                    $datos = [];
                                                                                    $datos['id '] = $row['id'];
                                                                                    $datos['nomTipo'] = $row['nomTipo'];
                                                                                    $id = $datos['id'];
                                                                                    $nomTipo = $datos['nomTipo'];
                                                                                    ?>
                                                                                    <option value="<?php echo $nomTipo ?>"><?php echo mb_convert_case($nomTipo, MB_CASE_UPPER, 'UTF-8') ?></option>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                                <option value="Otros">Otro</option>
                                                                                <?php
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div name="visualizarNomTipo" id="visualizarNomTipo" class="form-group col-md-12 col-12">

                                                                    </div>
                                                                    <div class="form-group col-md-12 col-12">
                                                                        <label for="exampleInputCodigoTarjeta" style="font-size:12px">Adjunto el archivo:<code>*</code></label>
                                                                        <div class="input-group mb-3">
                                                                            <input type="file" class="form-control " required id="archivoAdjunto" name="archivoAdjunto">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-warning" data-dismiss="modal">Cerrar</button>
                                                                <button type="submit" class="btn btn-success">Añadir Archivo</button>
                                                            </div>
                                                        </form>
                                                        <input type="hidden" id="idCategoriaBuscar" name="idCategoriaBuscar" value="<?php echo $idCategoria ?>">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="tabDetalle">

                                        <?php

                                        // Encontrar la posición del texto 'ECB600_'
                                        $posicion_inicio = strpos($nomDetalleEquipo, 'ECB');
                                        // Si se encuentra 'ECB600_', obtener la subcadena desde esa posición hasta el siguiente espacio
                                        if ($posicion_inicio !== false) {
                                            $posicion_inicio += strlen('ECB'); // Mover la posición de inicio después de 'ECB600_'
                                            $posicion_fin = strpos($nomDetalleEquipo, ' ', $posicion_inicio); // Encontrar la posición del siguiente espacio
                                            if ($posicion_fin !== false) {
                                                $texto_deseado = substr($nomDetalleEquipo, $posicion_inicio, $posicion_fin - $posicion_inicio);
                                                $nomDetalleEquipo = 'ECB' . $texto_deseado; // Esto mostrará '1042'
                                            }
                                        }

                                        $porciones1 = explode("ECB", $nomDetalleEquipo);

                                        $variableParaTabla = 'ECB ' . $porciones1[1];

                                        $porciones2 = explode("_", $variableParaTabla);

                                        $variableParaTablaFinal = $porciones2[0] . '-' . $porciones2[1];

                                        $variableParaTablaFinal = trim($variableParaTablaFinal);
                                        ?>

                                        <table id="tableDetalleControl" class="table table-bordered table-hover">
                                            <thead class="bg-secondary">
                                            <tr>
                                                <th>
                                                    <center>N°</center>
                                                </th>
                                                <th>
                                                    <center>Piso
                                                        <center>
                                                </th>
                                                <th>
                                                    <center>TCC
                                                        <center>
                                                </th>
                                                <th>
                                                    <center>Controlador
                                                        <center>
                                                </th>
                                                <th>
                                                    <center>N&uacute;mero de Entrada
                                                        <center>
                                                </th>
                                                <th>
                                                    <center>Digital Input (UI)
                                                        <center>
                                                </th>
                                                <th>
                                                    <center>Analog Input (UI)
                                                        <center>
                                                </th>
                                                <th>
                                                    <center>N&uacute;mero de Salida
                                                        <center>
                                                </th>
                                                <th>
                                                    <center>Digital Output (UO)
                                                        <center>
                                                </th>
                                                <th>
                                                    <center>Analog Output (UO)
                                                        <center>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <?php
                                            $query = 'SELECT * FROM detalle_control_bms WHERE controlador_ControlBMS LIKE "' . $variableParaTablaFinal . '" ORDER BY idControlBMS ASC';

                                            $res = mysqli_query($conexion, $query);

                                            $numContBMS = 1;

                                            while ($row = $res->fetch_assoc()) {
                                                ?>
                                                <tr>
                                                    <td nowrap="nowrap" style="vertical-align:middle;">
                                                        <center><?php echo $numContBMS; ?></center>
                                                    </td>
                                                    <td nowrap="nowrap" style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php
                                                        $piso_ControlBMS = mb_strtoupper($row['piso_ControlBMS']);
                                                        echo $piso_ControlBMS;
                                                        ?>
                                                    </td>
                                                    <td nowrap="nowrap" style="vertical-align:middle;" align="center">
                                                        <?php
                                                        $tcc_ControlBMS = mb_strtoupper($row['tcc_ControlBMS']);
                                                        echo $tcc_ControlBMS;
                                                        ?>
                                                    </td>
                                                    <td nowrap="nowrap" style="vertical-align:middle;" align="center">
                                                        <?php
                                                        $controlador_ControlBMS = mb_strtoupper($row['controlador_ControlBMS']);
                                                        echo $controlador_ControlBMS;

                                                        ?>
                                                    </td>
                                                    <td nowrap="nowrap" style="vertical-align:middle;" align="center">
                                                        <?php
                                                        $numEntrada_ControlBMS = $row['numEntrada_ControlBMS'];
                                                        echo $numEntrada_ControlBMS;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" align="center">
                                                        <?php
                                                        $digitalInput_ControlBMS = $row['digitalInput_ControlBMS'];
                                                        echo $digitalInput_ControlBMS;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" align="center">
                                                        <?php
                                                        $analogInput_ControlBMS = $row['analogInput_ControlBMS'];
                                                        echo $analogInput_ControlBMS;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" align="center">
                                                        <?php
                                                        $numSalida_ControlBMS = $row['numSalida_ControlBMS'];
                                                        echo $numSalida_ControlBMS;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" align="center">
                                                        <?php
                                                        $digitalOutput_ControlBMS = $row['digitalOutput_ControlBMS'];
                                                        echo $digitalOutput_ControlBMS;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" align="center">
                                                        <?php
                                                        $analogOutput_ControlBMS = $row['analogOutput_ControlBMS'];
                                                        echo $analogOutput_ControlBMS;

                                                        ?>
                                                    </td>

                                                </tr>
                                                <?php

                                                $numContBMS = $numContBMS + 1;
                                            }
                                            ?>


                                            </tbody>
                                            <tfoot class="bg-secondary">
                                            <th>
                                                <center>N°</center>
                                            </th>
                                            <th>
                                                <center>Piso
                                                    <center>
                                            </th>
                                            <th>
                                                <center>TCC
                                                    <center>
                                            </th>
                                            <th>
                                                <center>Controlador
                                                    <center>
                                            </th>
                                            <th>
                                                <center>N&uacute;mero de Entrada
                                                    <center>
                                            </th>
                                            <th>
                                                <center>Digital Input (UI)
                                                    <center>
                                            </th>
                                            <th>
                                                <center>Analog Input (UI)
                                                    <center>
                                            </th>
                                            <th>
                                                <center>N&uacute;mero de Salida
                                                    <center>
                                            </th>
                                            <th>
                                                <center>Digital Output (UO)
                                                    <center>
                                            </th>
                                            <th>
                                                <center>Analog Output (UO)
                                                    <center>
                                            </th>
                                            </tfoot>
                                        </table>

                                    </div>

                                    <div class="tab-pane" id="tabPlan">


                                        <div class="row justify-content-center">
                                            <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">


                                                <button class="btn bg-primary btn-block" type="button" data-toggle="modal" data-target="#modalAddSubTarea">
                                                    <i class="fas fa-plus"></i> Agregar Tarea
                                                </button>
                                                <form method="post" style="vertical-align: middle" action="../sdo-funciones/planificacion_agregarTarea.php">
                                                    <div class="modal fade" id="modalAddSubTarea" name="modalAddSubTarea" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-primary">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Agregar Tarea</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-md-12 ml-auto">
                                                                            <div style="text-align:left;" class="form-group">
                                                                                <label for="exampleInputBorder">Actividad:
                                                                                    <code>*</code></label>
                                                                                <textarea id="actividadTareaP" style="resize:none;" name="actividadTareaP" required class="form-control" rows="3" placeholder="Actividad..."></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4 ml-auto">
                                                                            <div style="text-align:left;" class="form-group">
                                                                                <label for="inicioPlanTareaP">Fecha Inicio - Planificaci&oacute;n:
                                                                                    <code>*</code></label>
                                                                                <input type="date" name="inicioPlanTareaP" id="inicioPlanTareaP" placeholder="Fecha Inicio - Planificaci&oacute;n" class="form-control" required/>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4 ml-auto">
                                                                            <div style="text-align:left;" class="form-group">
                                                                                <label for="finPlanTareaP">Fecha Termino - Planificaci&oacute;n:
                                                                                    <code>*</code></label>
                                                                                <input type="date" name="finPlanTareaP" id="finPlanTareaP" placeholder="Fecha Termino - Planificaci&oacute;n" class="form-control" required/>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4 ml-auto">
                                                                            <div style="text-align:left;" class="form-group">
                                                                                <label for="finPlanTareaP">Tipo de Días a Trabajar:
                                                                                    <code>*</code></label>
                                                                                <select name="tipoDiasHabil" id="tipoDiasHabil" class="form-control">
                                                                                    <option value="Seleccione...">Seleccione...</option>
                                                                                    <option value="Habiles">Habiles</option>
                                                                                    <option value="Inhabiles">Inhabiles</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipo ?>">
                                                                    <input type="hidden" name="esEquipamiento" id="esEquipamiento" value="1">
                                                                    <input type="hidden" name="idTareaP" id="idTareaP" value="0">
                                                                    <input type="hidden" name="nomCreadorTareaP" id="nomCreadorTareaP" value="<?php echo $nomUsuario; ?>">
                                                                    <input type="hidden" name="deptoCreadorTareaP" id="deptoCreadorTareaP" value="<?php echo getIDControlCentra(); ?>">
                                                                    <button type="submit" class="btn btn-primary">Agregar</button>
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                            <?php
                                            if ($mostrarEstaOpcion == 1) {
                                                ?>
                                                <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
                                                    <button class="btn bg-warning btn-block" type="button" data-toggle="modal" data-target="#modalAddSubTareaOT">
                                                        <i class="fas fa-plus"></i> Agregar Tarea para OT
                                                    </button>
                                                    <form id="formAgregarTareaOT" method="post" style="vertical-align: middle" action="../sdo-funciones/planificacion_agregarTarea_ot.php">
                                                        <div class="modal fade" id="modalAddSubTareaOT" name="modalAddSubTareaOT" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-primary">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Agregar Tarea con Orden de Trabajo</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-md-12 ml-auto">
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Titulo de Tarea:
                                                                                        <code>*</code></label>
                                                                                    <input type="text" name="tituloTareaP" id="tituloTareaP" class="form-control" required placeholder="Titulo de Tarea...">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-12 ml-auto">

                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Turno Asignado
                                                                                        <code>*</code></label>
                                                                                    <select class="custom-select" name="turnoAsignado" id="turnoAsignado">
                                                                                        <option value="0">Seleccione...</option>
                                                                                        <option value="1">Personal Diurno</option>
                                                                                        <option value="2">Personal Nocturno</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-12 ml-auto">
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleFormControlSelect1">Seleccione Funcionarios
                                                                                        <code>*</code></label>
                                                                                    <select class="listFuncionariosTareaOT" name="funcionarioTareaOT[]" required multiple="multiple">
                                                                                        <?php


                                                                                        $sqlListadoFuncionarios = "SELECT jt.nomJefe AS nomPersonal 
                                                                                                             FROM jefes_tecnicos jt 
                                                                                                             WHERE (nomJefe NOT LIKE('%Jaime Amador Estay Jorquera%') AND nomJefe NOT LIKE('%Alejandro Cantillano%')) 
                                                                                                             AND (idDepartamento = 1 AND jt.userActivado = 1)
                                                                                                             AND nomJefe NOT LIKE('%Encargado Pruebas%')   
                                                                                                             UNION 
                                                                                                             SELECT p.nomPersonal AS nomPersonal
                                                                                                             FROM personal p 
                                                                                                             WHERE departamentoPersonal = 1
                                                                                                             AND activoPersonal = 1 
                                                                                                             AND (nomPersonal NOT LIKE('%Técnico Pruebas%') AND nomPersonal NOT LIKE('%Cynthia Latorre%'))
                                                                                                             ORDER BY nomPersonal;";
                                                                                        $resListadoFuncionarios = mysqli_query($conexion, $sqlListadoFuncionarios);

                                                                                        if ($resListadoFuncionarios && mysqli_num_rows($resListadoFuncionarios) > 0) {
                                                                                            while ($rowListadoFuncionarios = $resListadoFuncionarios->fetch_assoc()) {
                                                                                                $nomPersonal = $rowListadoFuncionarios['nomPersonal'];
                                                                                                ?>
                                                                                                <option value="<?php echo $nomPersonal; ?>"><?php echo $nomPersonal; ?></option>
                                                                                                <?php
                                                                                            }
                                                                                        }
                                                                                        ?>
                                                                                    </select>

                                                                                    <script>
                                                                                        $(document).ready(function () {
                                                                                            function aplicarTemaSelect2() {
                                                                                                const darkMode = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                                                                                                if (darkMode) {
                                                                                                    $('body').addClass('dark-mode');
                                                                                                } else {
                                                                                                    $('body').removeClass('dark-mode');
                                                                                                }
                                                                                            }

                                                                                            // Aplicar tema al cargar
                                                                                            aplicarTemaSelect2();

                                                                                            // Escuchar cambios en el esquema de color del sistema
                                                                                            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', aplicarTemaSelect2);

                                                                                            $('.listFuncionariosTareaOT').select2({
                                                                                                placeholder: 'Seleccione',
                                                                                                theme: 'bootstrap4',
                                                                                                allowClear: true,
                                                                                                width: '100%'
                                                                                            });


                                                                                            $("#formAgregarTareaOT").submit(function (event) {
                                                                                                let checklistSeleccionado = $("#checklist\\[\\]").val(); // Captura el valor del checklist
                                                                                                let funcionariosSeleccionados = $(".listFuncionariosTareaOT").val(); // Captura los funcionarios seleccionados

                                                                                                if (checklistSeleccionado !== "" && funcionariosSeleccionados.length > 1) {
                                                                                                    Swal.fire({
                                                                                                        icon: 'warning',
                                                                                                        title: 'Validación',
                                                                                                        text: 'Al seleccionar un Checklist, solo puede asignar la Tarea a un Funcionario.',
                                                                                                        confirmButtonColor: '#d33'
                                                                                                    });
                                                                                                    event.preventDefault(); // Detiene el envío del formulario
                                                                                                }
                                                                                            });
                                                                                        });
                                                                                    </script>
                                                                                </div>
                                                                            </div>
                                                                        </div>


                                                                        <div class="row">
                                                                            <div class="col-md-12 ml-auto">

                                                                                <div class="form-group">
                                                                                    <label for="exampleInputCodigoTarjeta">Tipo de Trabajo:</label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-th-list"></i></span>
                                                                                        </div>
                                                                                        <select name="tipoTrabajo" class="form-control" id="tipoTrabajo">
                                                                                            <option value="0" disabled selected>-- Seleccione --</option>
                                                                                            <option value="MPE">MPE - Mantenimiento Preventivo Externo</option>
                                                                                            <option value="MRE">MRE - Mantenimiento Reparativo Externo</option>
                                                                                            <option value="MPI">MPI - Mantenimiento Preventivo Interno</option>
                                                                                            <option value="MRI">MRI - Mantenimiento Reparativo Interno</option>
                                                                                            <option value="OM">OM - Obras Menores</option>
                                                                                            <option value="IS">IS - Inspección/Supervisión</option>
                                                                                            <option value="MS">MS - Mejoras del Sistema</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-12 ml-auto">
                                                                                <div class="form-group">
                                                                                    <label for="exampleInputCoordenadas">Check List:</label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                                                                        </div>
                                                                                        <select class="form-control" id="checklist" name="checklist">
                                                                                            <option value="" selected>Seleccione...</option>
                                                                                            <option value="Pantallas Graficas">Pantallas Gráficas</option>
                                                                                            <option value="Logica de Programacion">Logica de Programacion</option>
                                                                                            <option value="Unidades Control de Accesos">Unidades Control de Accesos (UCAS)</option>
                                                                                            <option value="Controlador Modelo ECB">Controlador Modelo ECB</option>
                                                                                            <option value="TCC de UMA">TCC de UMA</option>
                                                                                            <option value="VAV">VAV</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">

                                                                            <div class="col-md-12 ml-auto">
                                                                                <div class="form-group">
                                                                                    <label for="exampleInputCoordenadas">¿Cuantas veces se hara este checklist?:</label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                                                                        </div>
                                                                                        <input type="number" min="0" name="cantidad[]" id="cantidad[]" class="form-control">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-12 ml-auto">
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Actividad:
                                                                                        <code>*</code></label>
                                                                                    <textarea id="actividadTareaP" style="resize:none;" name="actividadTareaP" required class="form-control" rows="6" placeholder="Actividad..."></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-4 ml-auto">
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="inicioPlanTareaP">Fecha Inicio - Planificaci&oacute;n:
                                                                                        <code>*</code></label>
                                                                                    <input type="date" name="inicioPlanTareaP" id="inicioPlanTareaP" placeholder="Fecha Inicio - Planificaci&oacute;n" class="form-control" required/>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4 ml-auto">
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="finPlanTareaP">Fecha Termino - Planificaci&oacute;n:
                                                                                        <code>*</code></label>
                                                                                    <input type="date" name="finPlanTareaP" id="finPlanTareaP" placeholder="Fecha Termino - Planificaci&oacute;n" class="form-control" required/>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4 ml-auto">
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="finPlanTareaP">Tipo de Días a Trabajar:
                                                                                        <code>*</code></label>
                                                                                    <select name="tipoDiasHabil" id="tipoDiasHabil" class="form-control">
                                                                                        <option value="Seleccione...">Seleccione...</option>
                                                                                        <option value="Habiles">Habiles</option>
                                                                                        <option value="Inhabiles">Inhabiles</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipo ?>">
                                                                        <input type="hidden" name="esEquipamiento" id="esEquipamiento" value="1">
                                                                        <input type="hidden" name="idTareaP" id="idTareaP" value="0">
                                                                        <input type="hidden" name="nomCreadorTareaP" id="nomCreadorTareaP" value="<?php echo $nomUsuario; ?>">
                                                                        <input type="hidden" name="deptoCreadorTareaP" id="deptoCreadorTareaP" value="1">
                                                                        <button type="submit" class="btn btn-primary">
                                                                            <i class="fas fa-upload"></i>&nbsp;&nbsp;Agregar
                                                                        </button>
                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                                            <i class="fas fa-times"></i>&nbsp;&nbsp;Cancelar
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <?php
                                            }
                                            ?>

                                            <?php
                                            if (strpos($modelo, "ECB_VAV") !== false) {
                                                // El modelo contiene "ECB_VAV"
                                                ?>
                                                <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
                                                    <button class="btn bg-purple btn-block" type="button" data-toggle="modal" data-target="#modalVAVOT">
                                                        <i class="fas fa-plus"></i> Agregar MPI VAV
                                                    </button>
                                                    <form method="post" style="vertical-align: middle" action="../sdo-funciones/planificacion_mpi_vav.php">
                                                        <div class="modal fade" id="modalVAVOT" name="modalVAVOT" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-purple">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Agregar MPI a VAV</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-md-12 ml-auto">
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Titulo de Tarea:
                                                                                        <code>*</code></label>
                                                                                    <input type="text" name="tituloTareaP" id="tituloTareaP" class="form-control" required readonly="readonly" placeholder="Titulo de Tarea..." value="MANTENIMIENTO PREVENTIVO INTERNO UTILIZANDO PAUTA CHECK LIST DE <?php echo $nombreEquipo ?>">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-12 ml-auto">

                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Turno Asignado
                                                                                        <code>*</code></label>
                                                                                    <select class="custom-select" name="turnoAsignado" id="turnoAsignado">
                                                                                        <option value="0">Seleccione...</option>
                                                                                        <option value="1">Personal Diurno</option>
                                                                                        <option value="2">Personal Nocturno</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-12 ml-auto">
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleFormControlSelect1">Seleccione Funcionario
                                                                                        <code>*</code></label>
                                                                                    <select class="listFuncionarioMPIVAV" name="funcionarioMPIVAV" required>
                                                                                        <option value="nulo">Seleccione...</option>

                                                                                        <?php


                                                                                        $sqlListadoFuncionarios = "SELECT jt.nomJefe AS nomPersonal 
                                                                                                             FROM jefes_tecnicos jt 
                                                                                                             WHERE (nomJefe NOT LIKE('%Jaime Amador Estay Jorquera%') AND nomJefe NOT LIKE('%Alejandro Cantillano%')) 
                                                                                                             AND (idDepartamento = 1 AND jt.userActivado = 1)
                                                                                                             AND nomJefe NOT LIKE('%Encargado Pruebas%')   
                                                                                                             UNION 
                                                                                                             SELECT p.nomPersonal AS nomPersonal
                                                                                                             FROM personal p 
                                                                                                             WHERE departamentoPersonal = 1
                                                                                                             AND activoPersonal = 1 
                                                                                                             AND (nomPersonal NOT LIKE('%Técnico Pruebas%') AND nomPersonal NOT LIKE('%Cynthia Latorre%'))
                                                                                                             ORDER BY nomPersonal;";
                                                                                        $resListadoFuncionarios = mysqli_query($conexion, $sqlListadoFuncionarios);

                                                                                        if ($resListadoFuncionarios && mysqli_num_rows($resListadoFuncionarios) > 0) {
                                                                                            while ($rowListadoFuncionarios = $resListadoFuncionarios->fetch_assoc()) {
                                                                                                $nomPersonal = $rowListadoFuncionarios['nomPersonal'];
                                                                                                ?>
                                                                                                <option value="<?php echo $nomPersonal; ?>"><?php echo $nomPersonal; ?></option>
                                                                                                <?php
                                                                                            }
                                                                                        }
                                                                                        ?>
                                                                                    </select>

                                                                                    <script>
                                                                                        $(document).ready(function () {

                                                                                            $('.listFuncionarioMPIVAV').select2({
                                                                                                placeholder: 'Seleccione',
                                                                                                theme: 'bootstrap4',
                                                                                                allowClear: true,
                                                                                                width: '100%',

                                                                                            });


                                                                                            // $("#formAgregarTareaOT").submit(function (event) {
                                                                                            //     let checklistSeleccionado = $("#checklist\\[\\]").val(); // Captura el valor del checklist
                                                                                            //     let funcionariosSeleccionados = $(".listFuncionariosTareaOT").val(); // Captura los funcionarios seleccionados
                                                                                            //
                                                                                            //     if (checklistSeleccionado !== "" && funcionariosSeleccionados.length > 1) {
                                                                                            //         Swal.fire({
                                                                                            //             icon: 'warning',
                                                                                            //             title: 'Validación',
                                                                                            //             text: 'Al seleccionar un Checklist, solo puede asignar la Tarea a un Funcionario.',
                                                                                            //             confirmButtonColor: '#d33'
                                                                                            //         });
                                                                                            //         event.preventDefault(); // Detiene el envío del formulario
                                                                                            //     }
                                                                                            // });
                                                                                        });
                                                                                    </script>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="col-md-12 ml-auto">
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="inicioPlanTareaP">Fecha Inicio - Planificaci&oacute;n:
                                                                                        <code>*</code></label>
                                                                                    <input type="date" name="inicioPlanTareaP" id="inicioPlanTareaP" placeholder="Fecha Inicio - Planificaci&oacute;n" class="form-control" required/>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipo ?>">
                                                                        <input type="hidden" name="esEquipamiento" id="esEquipamiento" value="1">
                                                                        <input type="hidden" name="idTareaP" id="idTareaP" value="0">
                                                                        <input type="hidden" name="nomCreadorTareaP" id="nomCreadorTareaP" value="<?php echo $nomUsuario; ?>">
                                                                        <input type="hidden" name="deptoCreadorTareaP" id="deptoCreadorTareaP" value="1">
                                                                        <button type="submit" class="btn btn-primary">
                                                                            <i class="fas fa-upload"></i>&nbsp;&nbsp;Agregar
                                                                        </button>
                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                                            <i class="fas fa-times"></i>&nbsp;&nbsp;Cancelar
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>

                                                </div>
                                                <?php
                                            }
                                            ?>

                                            <?php
                                                if(strpos($nombreEquipo, "VF_") !== false){
                                                    ?>
                                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
                                                        <button class="btn bg-purple btn-block" type="button" data-toggle="modal" data-target="#modalVFOT">
                                                            <i class="fas fa-plus"></i> Agregar MPI VF
                                                        </button>
                                                        <form method="post" style="vertical-align: middle" action="../sdo-funciones/planificacion_mpi_vf.php">
                                                            <div class="modal fade" id="modalVFOT" name="modalVFOT" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header bg-purple">
                                                                            <h5 class="modal-title" id="exampleModalLabel">Agregar MPI a VF</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="row">
                                                                                <div class="col-md-12 ml-auto">
                                                                                    <div style="text-align:left;" class="form-group">
                                                                                        <label for="exampleInputBorder">Titulo de Tarea:
                                                                                            <code>*</code></label>
                                                                                        <input type="text" name="tituloTareaP" id="tituloTareaP" class="form-control" required readonly="readonly" placeholder="Titulo de Tarea..." value="MANTENIMIENTO PREVENTIVO INTERNO UTILIZANDO PAUTA CHECK LIST DE <?php echo $nombreEquipo ?>">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-md-12 ml-auto">

                                                                                    <div style="text-align:left;" class="form-group">
                                                                                        <label for="exampleInputBorder">Turno Asignado
                                                                                            <code>*</code></label>
                                                                                        <select class="custom-select" name="turnoAsignado" id="turnoAsignado">
                                                                                            <option value="0">Seleccione...</option>
                                                                                            <option value="1">Personal Diurno</option>
                                                                                            <option value="2">Personal Nocturno</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-md-12 ml-auto">
                                                                                    <div style="text-align:left;" class="form-group">
                                                                                        <label for="exampleFormControlSelect1">Seleccione Funcionario
                                                                                            <code>*</code></label>
                                                                                        <select class="listFuncionarioMPIVAV" name="funcionarioMPIVAV" required>
                                                                                            <option value="nulo">Seleccione...</option>

                                                                                            <?php


                                                                                            $sqlListadoFuncionarios = "SELECT jt.nomJefe AS nomPersonal 
                                                                                                             FROM jefes_tecnicos jt 
                                                                                                             WHERE (nomJefe NOT LIKE('%Jaime Amador Estay Jorquera%') AND nomJefe NOT LIKE('%Alejandro Cantillano%')) 
                                                                                                             AND (idDepartamento = 1 AND jt.userActivado = 1)
                                                                                                             AND nomJefe NOT LIKE('%Encargado Pruebas%')   
                                                                                                             UNION 
                                                                                                             SELECT p.nomPersonal AS nomPersonal
                                                                                                             FROM personal p 
                                                                                                             WHERE departamentoPersonal = 1
                                                                                                             AND activoPersonal = 1 
                                                                                                             AND (nomPersonal NOT LIKE('%Técnico Pruebas%') AND nomPersonal NOT LIKE('%Cynthia Latorre%'))
                                                                                                             ORDER BY nomPersonal;";
                                                                                            $resListadoFuncionarios = mysqli_query($conexion, $sqlListadoFuncionarios);

                                                                                            if ($resListadoFuncionarios && mysqli_num_rows($resListadoFuncionarios) > 0) {
                                                                                                while ($rowListadoFuncionarios = $resListadoFuncionarios->fetch_assoc()) {
                                                                                                    $nomPersonal = $rowListadoFuncionarios['nomPersonal'];
                                                                                                    ?>
                                                                                                    <option value="<?php echo $nomPersonal; ?>"><?php echo $nomPersonal; ?></option>
                                                                                                    <?php
                                                                                                }
                                                                                            }
                                                                                            ?>
                                                                                        </select>

                                                                                        <script>
                                                                                            $(document).ready(function () {

                                                                                                $('.listFuncionarioMPIVAV').select2({
                                                                                                    placeholder: 'Seleccione',
                                                                                                    theme: 'bootstrap4',
                                                                                                    allowClear: true,
                                                                                                    width: '100%',

                                                                                                });


                                                                                                // $("#formAgregarTareaOT").submit(function (event) {
                                                                                                //     let checklistSeleccionado = $("#checklist\\[\\]").val(); // Captura el valor del checklist
                                                                                                //     let funcionariosSeleccionados = $(".listFuncionariosTareaOT").val(); // Captura los funcionarios seleccionados
                                                                                                //
                                                                                                //     if (checklistSeleccionado !== "" && funcionariosSeleccionados.length > 1) {
                                                                                                //         Swal.fire({
                                                                                                //             icon: 'warning',
                                                                                                //             title: 'Validación',
                                                                                                //             text: 'Al seleccionar un Checklist, solo puede asignar la Tarea a un Funcionario.',
                                                                                                //             confirmButtonColor: '#d33'
                                                                                                //         });
                                                                                                //         event.preventDefault(); // Detiene el envío del formulario
                                                                                                //     }
                                                                                                // });
                                                                                            });
                                                                                        </script>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-md-12 ml-auto">
                                                                                    <div style="text-align:left;" class="form-group">
                                                                                        <label for="inicioPlanTareaP">Fecha Inicio - Planificaci&oacute;n:
                                                                                            <code>*</code></label>
                                                                                        <input type="date" name="inicioPlanTareaP" id="inicioPlanTareaP" placeholder="Fecha Inicio - Planificaci&oacute;n" class="form-control" required/>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipo ?>">
                                                                            <input type="hidden" name="esEquipamiento" id="esEquipamiento" value="1">
                                                                            <input type="hidden" name="idTareaP" id="idTareaP" value="0">
                                                                            <input type="hidden" name="nomCreadorTareaP" id="nomCreadorTareaP" value="<?php echo $nomUsuario; ?>">
                                                                            <input type="hidden" name="deptoCreadorTareaP" id="deptoCreadorTareaP" value="1">
                                                                            <button type="submit" class="btn btn-primary">
                                                                                <i class="fas fa-upload"></i>&nbsp;&nbsp;Agregar
                                                                            </button>
                                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                                                <i class="fas fa-times"></i>&nbsp;&nbsp;Cancelar
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <?php
                                                }
                                            ?>

                                            <?php
                                                if(strpos($nombreEquipo, "ECB") !== false){
                                                    ?>
                                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
                                                        <button class="btn bg-purple btn-block" type="button" data-toggle="modal" data-target="#modalECBOT">
                                                            <i class="fas fa-plus"></i> Agregar MPI ECB
                                                        </button>
                                                        <form method="post" style="vertical-align: middle" action="../sdo-funciones/planificacion_mpi_ECB.php">
                                                            <div class="modal fade" id="modalECBOT" name="modalECBOT" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header bg-purple">
                                                                            <h5 class="modal-title" id="exampleModalLabel">Agregar MPI a ECB</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="row">
                                                                                <div class="col-md-12 ml-auto">
                                                                                    <div style="text-align:left;" class="form-group">
                                                                                        <label for="exampleInputBorder">Titulo de Tarea:
                                                                                            <code>*</code></label>
                                                                                        <input type="text" name="tituloTareaP" id="tituloTareaP" class="form-control" required readonly="readonly" placeholder="Titulo de Tarea..." value="MANTENIMIENTO PREVENTIVO INTERNO UTILIZANDO PAUTA CHECK LIST DE <?php echo $nombreEquipo ?>">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-md-12 ml-auto">

                                                                                    <div style="text-align:left;" class="form-group">
                                                                                        <label for="exampleInputBorder">Turno Asignado
                                                                                            <code>*</code></label>
                                                                                        <select class="custom-select" name="turnoAsignado" id="turnoAsignado">
                                                                                            <option value="0">Seleccione...</option>
                                                                                            <option value="1">Personal Diurno</option>
                                                                                            <option value="2">Personal Nocturno</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-md-12 ml-auto">
                                                                                    <div style="text-align:left;" class="form-group">
                                                                                        <label for="exampleFormControlSelect1">Seleccione Funcionario
                                                                                            <code>*</code></label>
                                                                                        <select class="listFuncionarioMPIVAV" name="funcionarioMPIVAV" required>
                                                                                            <option value="nulo">Seleccione...</option>

                                                                                            <?php


                                                                                            $sqlListadoFuncionarios = "SELECT jt.nomJefe AS nomPersonal 
                                                                                                             FROM jefes_tecnicos jt 
                                                                                                             WHERE (nomJefe NOT LIKE('%Jaime Amador Estay Jorquera%') AND nomJefe NOT LIKE('%Alejandro Cantillano%')) 
                                                                                                             AND (idDepartamento = 1 AND jt.userActivado = 1)
                                                                                                             AND nomJefe NOT LIKE('%Encargado Pruebas%')   
                                                                                                             UNION 
                                                                                                             SELECT p.nomPersonal AS nomPersonal
                                                                                                             FROM personal p 
                                                                                                             WHERE departamentoPersonal = 1
                                                                                                             AND activoPersonal = 1 
                                                                                                             AND (nomPersonal NOT LIKE('%Técnico Pruebas%') AND nomPersonal NOT LIKE('%Cynthia Latorre%'))
                                                                                                             ORDER BY nomPersonal;";
                                                                                            $resListadoFuncionarios = mysqli_query($conexion, $sqlListadoFuncionarios);

                                                                                            if ($resListadoFuncionarios && mysqli_num_rows($resListadoFuncionarios) > 0) {
                                                                                                while ($rowListadoFuncionarios = $resListadoFuncionarios->fetch_assoc()) {
                                                                                                    $nomPersonal = $rowListadoFuncionarios['nomPersonal'];
                                                                                                    ?>
                                                                                                    <option value="<?php echo $nomPersonal; ?>"><?php echo $nomPersonal; ?></option>
                                                                                                    <?php
                                                                                                }
                                                                                            }
                                                                                            ?>
                                                                                        </select>

                                                                                        <script>
                                                                                            $(document).ready(function () {

                                                                                                $('.listFuncionarioMPIVAV').select2({
                                                                                                    placeholder: 'Seleccione',
                                                                                                    theme: 'bootstrap4',
                                                                                                    allowClear: true,
                                                                                                    width: '100%',

                                                                                                });


                                                                                                // $("#formAgregarTareaOT").submit(function (event) {
                                                                                                //     let checklistSeleccionado = $("#checklist\\[\\]").val(); // Captura el valor del checklist
                                                                                                //     let funcionariosSeleccionados = $(".listFuncionariosTareaOT").val(); // Captura los funcionarios seleccionados
                                                                                                //
                                                                                                //     if (checklistSeleccionado !== "" && funcionariosSeleccionados.length > 1) {
                                                                                                //         Swal.fire({
                                                                                                //             icon: 'warning',
                                                                                                //             title: 'Validación',
                                                                                                //             text: 'Al seleccionar un Checklist, solo puede asignar la Tarea a un Funcionario.',
                                                                                                //             confirmButtonColor: '#d33'
                                                                                                //         });
                                                                                                //         event.preventDefault(); // Detiene el envío del formulario
                                                                                                //     }
                                                                                                // });
                                                                                            });
                                                                                        </script>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-md-12 ml-auto">
                                                                                    <div style="text-align:left;" class="form-group">
                                                                                        <label for="inicioPlanTareaP">Fecha Inicio - Planificaci&oacute;n:
                                                                                            <code>*</code></label>
                                                                                        <input type="date" name="inicioPlanTareaP" id="inicioPlanTareaP" placeholder="Fecha Inicio - Planificaci&oacute;n" class="form-control" required/>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipo ?>">
                                                                            <input type="hidden" name="esEquipamiento" id="esEquipamiento" value="1">
                                                                            <input type="hidden" name="idTareaP" id="idTareaP" value="0">
                                                                            <input type="hidden" name="nomCreadorTareaP" id="nomCreadorTareaP" value="<?php echo $nomUsuario; ?>">
                                                                            <input type="hidden" name="deptoCreadorTareaP" id="deptoCreadorTareaP" value="1">
                                                                            <button type="submit" class="btn btn-primary">
                                                                                <i class="fas fa-upload"></i>&nbsp;&nbsp;Agregar
                                                                            </button>
                                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                                                <i class="fas fa-times"></i>&nbsp;&nbsp;Cancelar
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <?php
                                                }
                                            ?>

                                            <?php
                                            if (strpos($nombreEquipo, 'UCA-') !== false) {
                                                ?>
                                                <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
                                                    <button class="btn bg-purple btn-block" type="button" data-toggle="modal" data-target="#modalUCAOT">
                                                        <i class="fas fa-plus"></i> Agregar MPI UCA
                                                    </button>
                                                    <form method="post" style="vertical-align: middle" action="../sdo-funciones/planificacion_mpi_uca.php">
                                                        <div class="modal fade" id="modalUCAOT" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-purple">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Agregar MPI a UCA</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-md-12 ml-auto">
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Titulo de Tarea:
                                                                                        <code>*</code></label>
                                                                                    <input type="text" name="tituloTareaP" id="tituloTareaP" class="form-control" required readonly="readonly" placeholder="Titulo de Tarea..." value="MANTENIMIENTO PREVENTIVO INTERNO UTILIZANDO PAUTA CHECK LIST DE <?php echo $nombreEquipo ?>">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-12 ml-auto">

                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Turno Asignado
                                                                                        <code>*</code></label>
                                                                                    <select class="custom-select" name="turnoAsignado" id="turnoAsignado">
                                                                                        <option value="0">Seleccione...</option>
                                                                                        <option value="1">Personal Diurno</option>
                                                                                        <option value="2">Personal Nocturno</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-12 ml-auto">
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleFormControlSelect1">Seleccione Funcionario
                                                                                        <code>*</code></label>
                                                                                    <select class="listFuncionarioMPIUCA" name="funcionarioMPIUCA" required>
                                                                                        <option value="nulo">Seleccione...</option>

                                                                                        <?php


                                                                                        $sqlListadoFuncionarios = "SELECT jt.nomJefe AS nomPersonal 
                                                                                                             FROM jefes_tecnicos jt 
                                                                                                             WHERE (nomJefe NOT LIKE('%Jaime Amador Estay Jorquera%') AND nomJefe NOT LIKE('%Alejandro Cantillano%')) 
                                                                                                             AND (idDepartamento = 1 AND jt.userActivado = 1)
                                                                                                             AND nomJefe NOT LIKE('%Encargado Pruebas%')   
                                                                                                             UNION 
                                                                                                             SELECT p.nomPersonal AS nomPersonal
                                                                                                             FROM personal p 
                                                                                                             WHERE departamentoPersonal = 1
                                                                                                             AND activoPersonal = 1 
                                                                                                             AND (nomPersonal NOT LIKE('%Técnico Pruebas%') AND nomPersonal NOT LIKE('%Cynthia Latorre%'))
                                                                                                             ORDER BY nomPersonal;";
                                                                                        $resListadoFuncionarios = mysqli_query($conexion, $sqlListadoFuncionarios);

                                                                                        if ($resListadoFuncionarios && mysqli_num_rows($resListadoFuncionarios) > 0) {
                                                                                            while ($rowListadoFuncionarios = $resListadoFuncionarios->fetch_assoc()) {
                                                                                                $nomPersonal = $rowListadoFuncionarios['nomPersonal'];
                                                                                                ?>
                                                                                                <option value="<?php echo $nomPersonal; ?>"><?php echo $nomPersonal; ?></option>
                                                                                                <?php
                                                                                            }
                                                                                        }
                                                                                        ?>
                                                                                    </select>

                                                                                    <script>
                                                                                        $(document).ready(function () {

                                                                                            $('.listFuncionarioMPIUCA').select2({
                                                                                                placeholder: 'Seleccione',
                                                                                                theme: 'bootstrap4',
                                                                                                allowClear: true,
                                                                                                width: '100%',

                                                                                            });


                                                                                            // $("#formAgregarTareaOT").submit(function (event) {
                                                                                            //     let checklistSeleccionado = $("#checklist\\[\\]").val(); // Captura el valor del checklist
                                                                                            //     let funcionariosSeleccionados = $(".listFuncionariosTareaOT").val(); // Captura los funcionarios seleccionados
                                                                                            //
                                                                                            //     if (checklistSeleccionado !== "" && funcionariosSeleccionados.length > 1) {
                                                                                            //         Swal.fire({
                                                                                            //             icon: 'warning',
                                                                                            //             title: 'Validación',
                                                                                            //             text: 'Al seleccionar un Checklist, solo puede asignar la Tarea a un Funcionario.',
                                                                                            //             confirmButtonColor: '#d33'
                                                                                            //         });
                                                                                            //         event.preventDefault(); // Detiene el envío del formulario
                                                                                            //     }
                                                                                            // });
                                                                                        });
                                                                                    </script>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-12 ml-auto">
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="inicioPlanTareaP">Fecha Inicio - Planificaci&oacute;n:
                                                                                        <code>*</code></label>
                                                                                    <input type="date" name="inicioPlanTareaP" id="inicioPlanTareaP" placeholder="Fecha Inicio - Planificaci&oacute;n" class="form-control" required/>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipo ?>">
                                                                        <input type="hidden" name="esEquipamiento" id="esEquipamiento" value="1">
                                                                        <input type="hidden" name="idTareaP" id="idTareaP" value="0">
                                                                        <input type="hidden" name="nomCreadorTareaP" id="nomCreadorTareaP" value="<?php echo $nomUsuario; ?>">
                                                                        <input type="hidden" name="deptoCreadorTareaP" id="deptoCreadorTareaP" value="1">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                        <button type="submit" class="btn btn-primary">Agregar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <?php
                                            }
                                            ?>

                                            <?php
                                            if (strpos($nombreEquipo, 'UMA-') === 0) {
                                                ?>
                                                <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
                                                    <button class="btn bg-purple btn-block" type="button" data-toggle="modal" data-target="#modalUMAOT">
                                                        <i class="fas fa-plus"></i> Agregar MPI UMA
                                                    </button>
                                                    <form method="post" style="vertical-align: middle" action="../sdo-funciones/planificacion_mpi_uma.php">
                                                        <div class="modal fade" id="modalUMAOT" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-purple">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Agregar MPI a UMA</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-md-12 ml-auto">
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Titulo de Tarea:
                                                                                        <code>*</code></label>
                                                                                    <input type="text" name="tituloTareaP" id="tituloTareaP" class="form-control" required readonly="readonly" placeholder="Titulo de Tarea..." value="MANTENIMIENTO PREVENTIVO INTERNO UTILIZANDO PAUTA CHECK LIST DE <?php echo $nombreEquipo ?>">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-12 ml-auto">

                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Turno Asignado
                                                                                        <code>*</code></label>
                                                                                    <select class="custom-select" name="turnoAsignado" id="turnoAsignado">
                                                                                        <option value="0">Seleccione...</option>
                                                                                        <option value="1">Personal Diurno</option>
                                                                                        <option value="2">Personal Nocturno</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-12 ml-auto">
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleFormControlSelect1">Seleccione Funcionario
                                                                                        <code>*</code></label>
                                                                                    <select class="listFuncionarioMPIUMA" name="funcionarioMPIUMA" required>
                                                                                        <option value="nulo">Seleccione...</option>

                                                                                        <?php


                                                                                        $sqlListadoFuncionarios = "SELECT jt.nomJefe AS nomPersonal 
                                                                                                             FROM jefes_tecnicos jt 
                                                                                                             WHERE (nomJefe NOT LIKE('%Jaime Amador Estay Jorquera%') AND nomJefe NOT LIKE('%Alejandro Cantillano%')) 
                                                                                                             AND (idDepartamento = 1 AND jt.userActivado = 1)
                                                                                                             AND nomJefe NOT LIKE('%Encargado Pruebas%')   
                                                                                                             UNION 
                                                                                                             SELECT p.nomPersonal AS nomPersonal
                                                                                                             FROM personal p 
                                                                                                             WHERE departamentoPersonal = 1
                                                                                                             AND activoPersonal = 1 
                                                                                                             AND (nomPersonal NOT LIKE('%Técnico Pruebas%') AND nomPersonal NOT LIKE('%Cynthia Latorre%'))
                                                                                                             ORDER BY nomPersonal;";
                                                                                        $resListadoFuncionarios = mysqli_query($conexion, $sqlListadoFuncionarios);

                                                                                        if ($resListadoFuncionarios && mysqli_num_rows($resListadoFuncionarios) > 0) {
                                                                                            while ($rowListadoFuncionarios = $resListadoFuncionarios->fetch_assoc()) {
                                                                                                $nomPersonal = $rowListadoFuncionarios['nomPersonal'];
                                                                                                ?>
                                                                                                <option value="<?php echo $nomPersonal; ?>"><?php echo $nomPersonal; ?></option>
                                                                                                <?php
                                                                                            }
                                                                                        }
                                                                                        ?>
                                                                                    </select>

                                                                                    <script>
                                                                                        $(document).ready(function () {

                                                                                            $('.listFuncionarioMPIUMA').select2({
                                                                                                placeholder: 'Seleccione',
                                                                                                theme: 'bootstrap4',
                                                                                                allowClear: true,
                                                                                                width: '100%',

                                                                                            });


                                                                                            // $("#formAgregarTareaOT").submit(function (event) {
                                                                                            //     let checklistSeleccionado = $("#checklist\\[\\]").val(); // Captura el valor del checklist
                                                                                            //     let funcionariosSeleccionados = $(".listFuncionariosTareaOT").val(); // Captura los funcionarios seleccionados
                                                                                            //
                                                                                            //     if (checklistSeleccionado !== "" && funcionariosSeleccionados.length > 1) {
                                                                                            //         Swal.fire({
                                                                                            //             icon: 'warning',
                                                                                            //             title: 'Validación',
                                                                                            //             text: 'Al seleccionar un Checklist, solo puede asignar la Tarea a un Funcionario.',
                                                                                            //             confirmButtonColor: '#d33'
                                                                                            //         });
                                                                                            //         event.preventDefault(); // Detiene el envío del formulario
                                                                                            //     }
                                                                                            // });
                                                                                        });
                                                                                    </script>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-12 ml-auto">
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="inicioPlanTareaP">Fecha Inicio - Planificaci&oacute;n:
                                                                                        <code>*</code></label>
                                                                                    <input type="date" name="inicioPlanTareaP" id="inicioPlanTareaP" placeholder="Fecha Inicio - Planificaci&oacute;n" class="form-control" required/>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipo ?>">
                                                                        <input type="hidden" name="esEquipamiento" id="esEquipamiento" value="1">
                                                                        <input type="hidden" name="idTareaP" id="idTareaP" value="0">
                                                                        <input type="hidden" name="nomCreadorTareaP" id="nomCreadorTareaP" value="<?php echo $nomUsuario; ?>">
                                                                        <input type="hidden" name="deptoCreadorTareaP" id="deptoCreadorTareaP" value="1">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                        <button type="submit" class="btn btn-primary">Agregar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <?php
                                            }
                                            ?>

                                        </div>
                                        <hr>


                                        <div class="row justify-content-center">
                                            <div class="col-12 mb-3">
                                                <?php
                                                $query = 'SELECT * FROM tareas_carta_gantt WHERE idEquipamiento = ' . $idEquipo . ' ORDER BY inicioPlanTareaP ASC';

                                                //                                        echo '<h4>' . $query . '</h4>';
                                                ?>

                                                <div class="table-responsive">

                                                    <table id="tableCartaPlanificacion" class="table table-bordered table-hover">
                                                        <thead>
                                                        <tr class="bg-gradient-dark">
                                                            <th>N°</th>
                                                            <th>Detalle</th>
                                                            <th>Actividad</th>
                                                            <th>Personal</th>
                                                            <th>Inicio Planificado</th>
                                                            <th>Termino Planificado</th>
                                                            <th>Inicio Real</th>
                                                            <th>Termino Real</th>
                                                            <th>Porcentaje</th>
                                                            <th>Detalle</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php


                                                        $res = mysqli_query($conexion, $query);

                                                        $numTareaP = 1;
                                                        $numFila = 0;

                                                        while ($row = $res->fetch_assoc()) {

                                                            $idTareaP = $row['idTareaP'];

                                                            $contarSubTareaP = contarSubTareaP($idTareaP);

                                                            $porcentajeTareaP = obtenerPorcentajeTarea($idTareaP);

                                                            $porcentajeTareaP = intval($porcentajeTareaP);


                                                            if ($contarSubTareaP > 0) {
                                                                $contarTotalPorcentaje = contarTotalPorcentaje($idTareaP);

                                                                $contarCantidadPadre = contarCantidadPadre($idTareaP);

                                                                if ($contarTotalPorcentaje > 0) {
                                                                    $porcentajePadre = $contarTotalPorcentaje / $contarCantidadPadre;
                                                                } else {
                                                                    $porcentajePadre = 0;
                                                                }

                                                                $argumentoTR = 'data-widget="expandable-table" aria-expanded="false"';
                                                            } else {
                                                                $contarTotalPorcentaje = $porcentajeTareaP;

                                                                if ($porcentajeTareaP > 0) {
                                                                    $porcentajePadre = $porcentajeTareaP;
                                                                } else {
                                                                    $porcentajePadre = 0;
                                                                }

                                                                $argumentoTR = '';
                                                            }
                                                            ?>
                                                            <tr class="fila-clickeable">
                                                                <td style="width: 5%; vertical-align: middle; text-align: center;">
                                                                    <?php
                                                                    $numFila++;
                                                                    echo $numFila;
                                                                    ?>
                                                                </td>
                                                                <td style="width: 5%; vertical-align: middle; text-align: center;">
                                                                    <?php
                                                                    if ($contarSubTareaP > 0) {
                                                                        ?>
                                                                        <button type="submit" style="text-wrap: nowrap;" id="button<?php echo $numTareaP; ?>" class="btn btn-primary" onclick="mostrarOcultarFilas('info<?php echo $numTareaP; ?>')">
                                                                            <i class="fa fa-plus"></i>&nbsp;&nbsp;Expandir
                                                                        </button>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <button type="submit" class="btn btn-primary" disabled="disabled">Deshabilitado</button>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td style="width: 25%; vertical-align: middle; text-align: center;">

                                                                    <?php

                                                                    if (!empty($row['tituloTarea'])) {
                                                                        echo mb_convert_case($row['tituloTarea'], MB_CASE_UPPER, 'UTF-8') . '<br><br>';
                                                                    }

                                                                    ?>

                                                                    <button type="button" title="Visualizar Actividad" class="btn btn-primary btn-block" data-toggle="modal" data-target="#visualizarActividad<?php echo $idTareaP; ?>">
                                                                        <i class="fas fa-eye"></i>&nbsp;&nbsp;Actividad
                                                                    </button>


                                                                </td>
                                                                <td style="width: 10%; vertical-align: middle;">
                                                                    <?php
                                                                    $datosOTCartaGantt = obtenerOTCartaGantt($idTareaP);

                                                                    if (is_array($datosOTCartaGantt)) {

                                                                        $idTGOT = $datosOTCartaGantt['idTG'];
                                                                        $tecnico1OT = $datosOTCartaGantt['tecnico1'];
                                                                        $estadoTGOT = $datosOTCartaGantt['estadoTG'];
                                                                        $destinoTGOT = '#';

                                                                        $tecnico1OT = trim($tecnico1OT);
                                                                        if($idTGOT > 0 && !empty($tecnico1OT) && in_array($estadoTGOT, ['Trabajo Ingresado', 'Trabajo Terminado'])){

                                                                            if($estadoTGOT == 'Trabajo Ingresado'){
                                                                                $destinoTGOT = 'detalle_ordenes_trabajo.php';
                                                                            }else if($estadoTGOT == 'Trabajo Terminado' ){
                                                                                $destinoTGOT = 'visualizardetallesterminadas.php';
                                                                            }else{
                                                                                $destinoTGOT = '#';
                                                                            }


                                                                            ?>
                                                                            <form style="margin: 0; text-align: center;" target="_blank" class="" action="<?php echo $destinoTGOT; ?>" method="post">

                                                                                <input type="hidden" name="idTG" value="<?php echo $idTGOT; ?>">
                                                                                <button type="submit" id="inputMozi" class="btn btn-block btn-info"><?php echo $tecnico1OT; ?></button>

                                                                            </form>
                                                                            <?php

                                                                        }else{
                                                                            echo 'No Aplica';
                                                                        }



                                                                    } else {
                                                                        echo 'No Aplica';
                                                                    }


                                                                    ?>
                                                                </td>
                                                                <td style="width: 10%; vertical-align: middle;">
                                                                    <?php
                                                                    $inicioPlanTareaP = $row['inicioPlanTareaP'];
                                                                    $inicioPlanTareaP2 = date("d-m-Y", strtotime($inicioPlanTareaP));
                                                                    echo $inicioPlanTareaP2;
                                                                    ?>
                                                                </td>
                                                                <td style="width: 10%; vertical-align: middle;">
                                                                    <?php
                                                                    $finPlanTareaP = $row['finPlanTareaP'];
                                                                    $finPlanTareaP2 = date("d-m-Y", strtotime($finPlanTareaP));
                                                                    echo $finPlanTareaP2;
                                                                    ?>
                                                                </td>
                                                                <td style="width: 10%; vertical-align: middle;">
                                                                    <?php
                                                                    $inicioRealTareaP = $row['inicioRealTareaP'];

                                                                    if (empty($inicioRealTareaP)) {
                                                                        echo 'No Iniciado';
                                                                    } else {
                                                                        $inicioRealTareaP2 = date("d-m-Y", strtotime($inicioRealTareaP));
                                                                        echo $inicioRealTareaP2;
                                                                    }

                                                                    ?>
                                                                </td>
                                                                <td style="width: 10%; vertical-align: middle;">
                                                                    <?php
                                                                    $finRealTareaP = $row['finRealTareaP'];

                                                                    if (empty($inicioRealTareaP)) {
                                                                        echo 'No Iniciado';
                                                                    } else {
                                                                        if (empty($finRealTareaP)) {
                                                                            echo 'No Finalizado';
                                                                        } else {
                                                                            $finRealTareaP2 = date("d-m-Y", strtotime($finRealTareaP));
                                                                            echo $finRealTareaP2;
                                                                        }
                                                                    }

                                                                    ?>
                                                                </td>
                                                                <td style="width: 20%; vertical-align: middle; text-align: center;">
                                                                    <?php
                                                                    if ($contarTotalPorcentaje == 0) {
                                                                        echo $contarTotalPorcentaje;
                                                                    } else {
                                                                        ?>
                                                                        <div class="form-group">

                                                                            <?php
                                                                            $porcentajeTareaP2 = round($porcentajePadre, 1);

                                                                            ?>
                                                                            <label><?php echo $porcentajeTareaP2 . '%'; ?></label>
                                                                            <div class="progress">
                                                                                <div class="progress-bar progress-bar-striped" role="progressbar" style="width: <?php echo $porcentajePadre; ?>%" aria-valuenow="<?php echo $porcentajePadre; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                                            </div>
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td style="width: 10%; vertical-align: middle; text-align: center;">
                                                                    <?php
                                                                    if ($row['idTareaP'] > 0) {
                                                                        ?>

                                                                        <form style="margin: 0; text-align: center;" class="" action="planificador_detalle.php" method="post">

                                                                            <input type="hidden" name="idTareaP" value="<?php echo $row['idTareaP']; ?>">
                                                                            <button type="submit" id="inputMozi" class="btn btn-block btn-info">
                                                                                <i class="fas fa-eye"></i>
                                                                            </button>

                                                                        </form>
                                                                        <?php
                                                                    } else {
                                                                        echo 'Deshabilitado';
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <div class="modal fade" id="visualizarActividad<?php echo $idTareaP; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header bg-info">
                                                                            <h5 class="modal-title" id="exampleModalLabel">Actividad</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <form action="../sdo-funciones/subir-hallazgos.php" method="POST" enctype="multipart/form-data">
                                                                            <div class="modal-body">
                                                                                <p>
                                                                                    <?php
                                                                                    echo nl2br($row['actividadTareaP']);
                                                                                    ?>
                                                                                </p>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                                                    <i class="fas fa-times"></i>&nbsp;&nbsp;Cerrar
                                                                                </button>
                                                                            </div>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                            <?php

                                                            if ($contarSubTareaP > 0) {
                                                                $queryPadre = 'SELECT * FROM tareas_carta_gantt WHERE idPadreTareaP = ' . $row['idTareaP'] . ' ORDER BY inicioPlanTareaP ASC';

                                                                $resPadre = mysqli_query($conexion, $queryPadre);

                                                                $numTareaPadre = 1;

                                                                while ($rowPadre = $resPadre->fetch_assoc()) {
                                                                    ?>
                                                                    <tr class="info<?php echo $numTareaP; ?> fila-oculta" style="display: none; background-color: lightgrey;">
                                                                        <td style="width: 5%; vertical-align: middle; text-align: center;">
                                                                            <?php
                                                                            echo '';
                                                                            ?>
                                                                        </td>
                                                                        <td style="width: 5%; vertical-align: middle; text-align: center;">
                                                                            <?php echo $numFila . '.' . $numTareaPadre; ?>
                                                                        </td>
                                                                        <td style="width: 25%; vertical-align: middle;">
                                                                            <?php
                                                                            echo mb_convert_case($rowPadre['actividadTareaP'], MB_CASE_UPPER, 'UTF-8');
                                                                            ?>
                                                                        </td>
                                                                        <td style="width: 10%; vertical-align: middle;">
                                                                            <?php
                                                                            $inicioPlanTareaPadre = $rowPadre['inicioPlanTareaP'];
                                                                            $inicioPlanTareaPadre2 = date("d-m-Y", strtotime($inicioPlanTareaPadre));
                                                                            echo $inicioPlanTareaPadre2;
                                                                            ?>
                                                                        </td>
                                                                        <td style="width: 10%; vertical-align: middle;">
                                                                            <?php
                                                                            $finPlanTareaPadre = $rowPadre['finPlanTareaP'];
                                                                            $finPlanTareaPadre2 = date("d-m-Y", strtotime($finPlanTareaPadre));
                                                                            echo $finPlanTareaPadre2;
                                                                            ?>
                                                                        </td>
                                                                        <td style="width: 10%; vertical-align: middle;">
                                                                            <?php
                                                                            $inicioRealTareaPadre = $row['inicioRealTareaP'];

                                                                            if (empty($inicioRealTareaPadre)) {
                                                                                echo 'No Iniciado';
                                                                            } else {
                                                                                $inicioRealTareaPadre2 = date("d-m-Y", strtotime($inicioRealTareaPadre));
                                                                                echo $inicioRealTareaPadre2;
                                                                            }

                                                                            ?>
                                                                        </td>
                                                                        <td style="width: 10%; vertical-align: middle;">
                                                                            <?php
                                                                            $finRealTareaPadre = $row['finRealTareaP'];

                                                                            if (empty($inicioRealTareaPadre)) {
                                                                                echo 'No Iniciado';
                                                                            } else {
                                                                                if (empty($finRealTareaPadre)) {
                                                                                    echo 'No Finalizado';
                                                                                } else {
                                                                                    $finRealTareaPadre2 = date("d-m-Y", strtotime($finRealTareaPadre));
                                                                                    echo $finRealTareaPadre2;
                                                                                }
                                                                            }

                                                                            ?>
                                                                        </td>
                                                                        <td style="width: 20%; vertical-align: middle; text-align: center;">
                                                                            <div class="form-group">
                                                                                <?php
                                                                                $porcentajeTareaP2 = round($rowPadre['porcentajeTareaP'], 1);

                                                                                ?>
                                                                                <label><?php echo $porcentajeTareaP2 . '%'; ?></label>
                                                                                <div class="progress">
                                                                                    <div class="progress-bar progress-bar-striped" role="progressbar" style="width: <?php echo $porcentajeTareaP2; ?>%" aria-valuenow="<?php echo $porcentajeTareaP2; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td style="width: 10%; vertical-align: middle; text-align: center;">
                                                                            <?php
                                                                            echo '';
                                                                            ?>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                    $numTareaPadre++;
                                                                }
                                                            }
                                                            $numTareaP++;
                                                        }


                                                        ?>


                                                        </tbody>
                                                        <tfoot>

                                                        <tr class="bg-gradient-dark">
                                                            <th>N°</th>
                                                            <th>Detalle</th>
                                                            <th>Actividad</th>
                                                            <th>Personal</th>
                                                            <th>Inicio Planificado</th>
                                                            <th>Termino Planificado</th>
                                                            <th>Inicio Real</th>
                                                            <th>Termino Real</th>
                                                            <th>Porcentaje</th>
                                                            <th>Detalle</th>
                                                        </tr>
                                                        </tfoot>
                                                    </table>

                                                    <!--                            <h5>--><?php //echo $query; ?><!--</h5>-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="tabHallazgos">
                                        <div class="col-12">
                                            <div class="row">
                                                <fieldset class="border p-3 text-center" style="width: 20%">
                                                    <p>
                                                        <b>Listado de Hallazgo:</b>
                                                    </p>
                                                    <button type="button" title="Agregar un hallazgo" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#agregarhallazgo">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                    <hr>
                                                    <?php
                                                    $sqlHallazgos = "SELECT * FROM archivos_equipamiento_hbqp_hallazgos WHERE idEquipo = $idEquipo";
                                                    $resHallazgos = mysqli_query($conexion, $sqlHallazgos);
                                                    while ($rowHallazgos = $resHallazgos->fetch_assoc()) {
                                                        $nomArchivoHallazgo = $rowHallazgos['nomArchivo'];
                                                        $rutaArchivoHallazgo = $rowHallazgos['rutaArchivo'];
                                                        $idArchivoHallazgo = $rowHallazgos['id'];
                                                        $extensionHallazgo = pathinfo($rutaArchivoHallazgo, PATHINFO_EXTENSION);
                                                        ?>
                                                        <ul class="nav flex-column">
                                                            <li class="nav-item">
                                                                <?php
                                                                if ($extensionHallazgo == 'pdf') {
                                                                    ?>
                                                                    <button class="btn bg-gradient-info" onclick="cambiarPDFHallazgo('<?php echo $rutaArchivoHallazgo; ?>')">
                                                                        <i class="fas fa-file-pdf"></i>&nbsp;<?php echo $nomArchivoHallazgo ?>
                                                                    </button>
                                                                    <?php
                                                                } else if ($extensionHallazgo == 'jpg' || $extensionHallazgo == 'png') {
                                                                    ?>
                                                                    <button class="btn bg-gradient-info" onclick="cambiarPDFHallazgo('<?php echo $rutaArchivoHallazgo; ?>')">
                                                                        <i class="fas fa-image"></i>&nbsp;<?php echo $nomArchivoHallazgo ?>
                                                                    </button>
                                                                    <?php
                                                                }
                                                                ?>
                                                                <button class="btn btn-danger btn-sm eliminarhallazgo" id="<?php echo $idArchivoHallazgo ?>">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                                <hr>
                                                            </li>
                                                        </ul>
                                                        <?php
                                                    }
                                                    ?>
                                                </fieldset>
                                                <fieldset style="width: 80%" class="border p-3 text-center">
                                                    <?php
                                                    if ($extensionHallazgo == 'pdf') {
                                                        ?>
                                                        <embed id="pdfViewerHallazgo" src="" type="application/pdf" width="100%" height="900px"/>
                                                        <?php
                                                    } else if ($extensionHallazgo == 'jpg' || $extensionHallazgo == 'png') {
                                                        ?>
                                                        <img id="pdfViewerHallazgo" src=""/>
                                                        <?php
                                                    }
                                                    ?>
                                                </fieldset>
                                            </div>
                                            <script>
                                                function cambiarPDFHallazgo(ruta) {
                                                    document.getElementById('pdfViewerHallazgo').setAttribute('src', ruta);
                                                }
                                            </script>
                                            <div class="modal fade" id="agregarhallazgo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-info">
                                                            <h5 class="modal-title" id="exampleModalLabel">Formulario Ingreso Hallazgos</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="../sdo-funciones/subir-hallazgos.php" method="POST" enctype="multipart/form-data">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="idCarpeta" id="idCarpeta" value="<?php echo $idCategoria ?>">
                                                                <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipo ?>">
                                                                <input type="hidden" name="nomCarpeta" id="nomCarpeta" value="<?php echo $nomDetalleEquipo ?>">
                                                                <input type="hidden" name="idEquipamiento" id="idEquipamiento" value="<?php echo $idEquipo ?>">
                                                                <p>
                                                                    <u><code>Solo se permiten fotos o archivos pdf</code></u>
                                                                </p>
                                                                <div class="row">
                                                                    <div class="form-group col-md-12 col-12">
                                                                        <label for="exampleInputCodigoTarjeta" style="font-size:15px">Adjunto el archivo:<code>*</code></label>
                                                                        <div class="input-group mb-3">
                                                                            <input type="file" class="form-control " required id="archivoAdjuntoHallazgo" name="archivoAdjuntoHallazgo">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                <button type="submit" class="btn btn-primary">
                                                                    <i class="fas fa-upload"></i> Subir
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="tab-pane" id="tabSistemas">

                                        <h4 class="text-center font-weight-bold">Observaciones de Equipos en Sistema</h4>

                                        <br>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="text-center bg-primary">
                                                <tr>
                                                    <th style="width: 50%;">EQUIPO</th>
                                                    <th style="width: 50%;">ESTADO ACTUAL</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                if (!empty($equipo_controla) && $equipo_controla > 0) {
                                                    $nomEquipoControla = getNomEquipo($equipo_controla);
                                                    $forzadoEquipo = getStatusForzado($equipo_controla);
                                                    $forzadoEncendido = getStatusForzadoEncendido($equipo_controla);
                                                    $forzadoApagado = getStatusForzadoApagado($equipo_controla);
                                                    $forzadoVariable = getStatusForzadoVariables($equipo_controla);
                                                    $modoAutomatico = getStatusAutomatico($equipo_controla);

                                                    $getSubCategoriaEquipo = getSubCategoriaEquipo($equipo_controla);

                                                    $_SESSION['idSubCategoria'] = $getSubCategoriaEquipo;
                                                } else {
                                                    $nomEquipoControla = 'NO APLICA';
                                                    $forzadoEquipo = 0;
                                                    $forzadoEncendido = 0;
                                                    $forzadoApagado = 0;
                                                    $forzadoVariable = 0;
                                                    $modoAutomatico = 0;
                                                }


                                                if ($forzadoEncendido == 1) {
                                                    $estadoEquipoControla = 'Forzado Encendido';
                                                } else if ($forzadoApagado == 1) {
                                                    $estadoEquipoControla = 'Forzado Apagado';
                                                } else if ($modoAutomatico == 1) {
                                                    $estadoEquipoControla = 'Modo Automatico';
                                                } else if ($forzadoVariable == 1) {
                                                    $estadoEquipoControla = 'Variables Forzadas';
                                                } else {
                                                    $estadoEquipoControla = 'Modo Automatico';
                                                }

                                                $nomEquipoControla2 = mb_convert_case($nomEquipoControla, MB_CASE_UPPER, 'UTF-8');
                                                $estadoEquipoControla2 = mb_convert_case($estadoEquipoControla, MB_CASE_UPPER, 'UTF-8');
                                                ?>

                                                <tr>
                                                    <td style="width: 50%; font-size: 15pt;" class="text-center font-weight-bold">
                                                        <?php echo $nomEquipoControla; ?>
                                                    </td>
                                                    <td style="width: 50%; font-size: 15pt;" class="text-center font-weight-bold">
                                                        <?php echo $estadoEquipoControla2; ?>
                                                    </td>
                                                </tr>

                                                </tbody>

                                            </table>
                                        </div>

                                        <div class="col-12">

                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row text-center" style="display: flex; justify-content:center;">


                                                        <!-- FORZADO ENCENDIDO -->
                                                        <div class="col-xl-2 col-lg-4 col-md-6 col-12">

                                                            <form method="post" action="../sdo-funciones/equipos-forzadoEncendido.php">
                                                                <button type="button" data-toggle="modal" data-target="#modalForzarEncendido" id="inputMozi" class="btn btn-success btn-block">
                                                                    <i class="fa fa-toggle-off"></i>&nbsp;&nbsp;Forzar Encendido
                                                                </button>

                                                                <div class="modal fade" id="modalForzarEncendido" name="modalForzarEncendido" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-success">
                                                                                <h5 class="modal-title" id="exampleModalLabel">
                                                                                    <b>Forzar Encendido de Equipo: </b><?php echo $nomEquipo; ?>
                                                                                </h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>

                                                                            <div class="modal-body">
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Nombre del Tecnico:
                                                                                        <code>*</code></label>
                                                                                    <input type="text" readonly="readonly" class="form-control " id="addNombre" name="addNombre" placeholder="Nombre de Personal" value="<?php echo $nomUsuario; ?>" required>
                                                                                </div>
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Fecha de Forzar Encendido:
                                                                                        <code>*</code></label>
                                                                                    <input style="text-align:left;" readonly="readonly" type="text" style="text-align:center;" class="form-control " id="addFecha" name="addFecha" value="<?php echo $fecha; ?>" required>
                                                                                </div>
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Razones de Forzar Encendido de Equipo:
                                                                                        <code>*</code></label>
                                                                                    <textarea id="razonesForzado" style="resize:none; word-break: break-all;" name="razonesForzado" class="form-control" rows="12" placeholder="Razones de Forzar Encendido de Equipo..."></textarea>

                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <input type="hidden" name="encendidoEquipo" id="encendidoEquipo" value="Encender">
                                                                                <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $equipo_controla; ?>">
                                                                                <input type="hidden" name="personalObservacion" id="personalObservacion" value="<?php echo $nomUsuario . ' (' . $cargoUsuario . ')'; ?>">
                                                                                <input type="hidden" name="idEquipamiento" id="idEquipamiento" value="<?php echo $idEquipo; ?>">
                                                                                <button type="submit" class="btn btn-primary">Forzar Encendido</button>
                                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </form>
                                                        </div>
                                                        <!-- FIN FORZADO ENCENDIDO -->

                                                        <!-- FORZADO APAGADO -->
                                                        <div class="col-xl-2 col-lg-4 col-md-6 col-12">

                                                            <form method="post" action="../sdo-funciones/equipos-forzadoApagado.php">
                                                                <button type="button" data-toggle="modal" data-target="#modalForzarApagado" id="inputMozi" class="btn bg-pink btn-block">
                                                                    <i class="fa fa-toggle-off"></i>&nbsp;&nbsp;Forzar Apagado
                                                                </button>

                                                                <div class="modal fade" id="modalForzarApagado" name="modalForzarApagado" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-pink">
                                                                                <h5 class="modal-title" id="exampleModalLabel">
                                                                                    <b>Forzar Apagado de Equipo: </b><?php echo $nomEquipo; ?>
                                                                                </h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>

                                                                            <div class="modal-body">
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Nombre del Tecnico:
                                                                                        <code>*</code></label>
                                                                                    <input type="text" readonly="readonly" class="form-control " id="addNombre" name="addNombre" placeholder="Nombre de Personal" value="<?php echo $nomUsuario; ?>" required>
                                                                                </div>
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Fecha de Forzar Apagado:
                                                                                        <code>*</code></label>
                                                                                    <input style="text-align:left;" readonly="readonly" type="text" style="text-align:center;" class="form-control " id="addFecha" name="addFecha" value="<?php echo $fecha; ?>" required>
                                                                                </div>
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Razones de Forzar Apagado de Equipo:
                                                                                        <code>*</code></label>
                                                                                    <textarea id="razonesForzado" style="resize:none; word-break: break-all;" name="razonesForzado" class="form-control" rows="12" placeholder="Razones de Forzar Apagado de Equipo..."></textarea>

                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">

                                                                                <input type="hidden" name="apagadoEquipo" id="apagadoEquipo" value="Apagar">
                                                                                <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $equipo_controla; ?>">
                                                                                <input type="hidden" name="personalObservacion" id="personalObservacion" value="<?php echo $nomUsuario . ' (' . $cargoUsuario . ')'; ?>">
                                                                                <input type="hidden" name="idEquipamiento" id="idEquipamiento" value="<?php echo $idEquipo; ?>">
                                                                                <button type="submit" class="btn btn-primary">Forzar Apagado Equipo</button>
                                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </form>
                                                        </div>
                                                        <!-- FIN FORZADO APAGADO -->

                                                        <!-- MODO AUTOMATICO -->
                                                        <div class="col-xl-2 col-lg-4 col-md-6 col-12">

                                                            <form method="post" action="../sdo-funciones/equipos-forzarAutomatico.php">
                                                                <button type="button" data-toggle="modal" data-target="#modalAutomatico" id="inputMozi" class="btn btn-info btn-block" style="float:right;">
                                                                    <i class="fa fa-toggle-off"></i>&nbsp;&nbsp;Modo Automatico
                                                                </button>

                                                                <div class="modal fade" id="modalAutomatico" name="modalAutomatico" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-info">
                                                                                <h5 class="modal-title" id="exampleModalLabel">
                                                                                    <b>Modo Automatico de Equipo: </b><?php echo $nomEquipo; ?>
                                                                                </h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>

                                                                            <div class="modal-body">
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Nombre del Tecnico:
                                                                                        <code>*</code></label>
                                                                                    <input type="text" readonly="readonly" class="form-control " id="addNombre" name="addNombre" placeholder="Nombre de Personal" value="<?php echo $nomUsuario; ?>" required>
                                                                                </div>
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Fecha de Modo Automatico:
                                                                                        <code>*</code></label>
                                                                                    <input style="text-align:left;" readonly="readonly" type="text" style="text-align:center;" class="form-control " id="addFecha" name="addFecha" value="<?php echo $fecha; ?>" required>
                                                                                </div>
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Razones de Modo Automatico:
                                                                                        <code>*</code></label>
                                                                                    <textarea id="razonesForzado" style="resize:none; word-break: break-all;" name="razonesForzado" class="form-control" rows="12" placeholder="Razones de Modo Automatico..."></textarea>

                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">

                                                                                <input type="hidden" name="modoAutomatico" id="modoAutomatico" value="Automatico">
                                                                                <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $equipo_controla; ?>">
                                                                                <input type="hidden" name="personalObservacion" id="personalObservacion" value="<?php echo $nomUsuario . ' (' . $cargoUsuario . ')'; ?>">
                                                                                <input type="hidden" name="idEquipamiento" id="idEquipamiento" value="<?php echo $idEquipo; ?>">
                                                                                <button type="submit" class="btn btn-primary">Activar Modo Automatico</button>
                                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </form>
                                                        </div>
                                                        <!-- FIN MODO AUTOMATICO -->

                                                        <!-- FORZAR VARIABLES -->
                                                        <div class="col-xl-2 col-lg-4 col-md-6 col-12">

                                                            <form method="post" action="../sdo-funciones/equipos-forzadoVariable.php">
                                                                <button type="button" data-toggle="modal" data-target="#modalVariables" id="inputMozi" class="btn bg-yellow btn-block">
                                                                    <i class="fa fa-toggle-off"></i>&nbsp;&nbsp;Forzar Variables
                                                                </button>

                                                                <div class="modal fade" id="modalVariables" name="modalVariables" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-yellow">
                                                                                <h5 class="modal-title" id="exampleModalLabel">
                                                                                    <b>Forzar Variables de Equipo: </b><?php echo $nomEquipo; ?>
                                                                                </h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>

                                                                            <div class="modal-body">
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Nombre del Tecnico:
                                                                                        <code>*</code></label>
                                                                                    <input type="text" readonly="readonly" class="form-control " id="addNombre" name="addNombre" placeholder="Nombre de Personal" value="<?php echo $nomUsuario; ?>" required>
                                                                                </div>
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Fecha de Forzado de Variables:
                                                                                        <code>*</code></label>
                                                                                    <input style="text-align:left;" readonly="readonly" type="text" style="text-align:center;" class="form-control " id="addFecha" name="addFecha" value="<?php echo $fecha; ?>" required>
                                                                                </div>
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Razones de Forzado de Variables:
                                                                                        <code>*</code></label>
                                                                                    <textarea id="razonesForzado" style="resize:none; word-break: break-all;" name="razonesForzado" class="form-control" rows="12" placeholder="Razones de Forzado de Variables..."></textarea>

                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <input type="hidden" name="forzadoVariable" id="forzadoVariable" value="Variables">
                                                                                <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $equipo_controla; ?>">
                                                                                <input type="hidden" name="personalObservacion" id="personalObservacion" value="<?php echo $nomUsuario . ' (' . $cargoUsuario . ')'; ?>">
                                                                                <input type="hidden" name="idEquipamiento" id="idEquipamiento" value="<?php echo $idEquipo; ?>">
                                                                                <button type="submit" class="btn btn-primary">Activar Modo Automatico</button>
                                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- FIN FORZAR VARIABLES -->

                                                        <!-- AGREGAR OBSERVACION -->
                                                        <div class="col-xl-2 col-lg-4 col-md-6 col-12">
                                                            <form method="post" action="../sdo-funciones/observaciones-agregar.php">
                                                                <button type="button" data-toggle="modal" data-target="#modalAddObservacion" id="inputMozi" class="btn bg-navy btn-block">
                                                                    <i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Observacion
                                                                </button>

                                                                <!-- Modal -->
                                                                <div class="modal fade" id="modalAddObservacion" name="modalAddObservacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-navy">
                                                                                <h5 class="modal-title" id="exampleModalLabel">Agregar Observacion</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>

                                                                            <div class="modal-body">
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Nombre del Tecnico:
                                                                                        <code>*</code></label>
                                                                                    <input type="text" readonly="readonly" class="form-control " id="addNombre" name="addNombre" placeholder="Nombre de Personal" value="<?php echo $nomUsuario; ?>" required>
                                                                                </div>
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Fecha de Observacion:
                                                                                        <code>*</code></label>
                                                                                    <input style="text-align:left;" readonly="readonly" type="text" style="text-align:center;" class="form-control " id="addFecha" name="addFecha" value="<?php echo $fecha; ?>" required>
                                                                                </div>
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Observacion:
                                                                                        <code>*</code></label>
                                                                                    <textarea id="observacionEquipo" style="resize:none;" name="observacionEquipo" class="form-control" rows="12" placeholder="Observacion..."></textarea>

                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipo; ?>">
                                                                                <input type="hidden" name="personalObservacion" id="personalObservacion" value="<?php echo $nomUsuario . ' (Técnico de ' . getNomDepartamento($idDepartamento) . ')'; ?>">
                                                                                <button type="submit" class="btn btn-primary">Agregar</button>
                                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- FIN AGREGAR OBSERVACION -->
                                                        <?php

                                                        $verificarEsPabellon = verificarEsPabellon($equipo_controla);
                                                        if ($verificarEsPabellon == 1) {
                                                            ?>
                                                            <!-- CAMBIO SET POINT -->
                                                            <div class="col-xl-2 col-lg-4 col-md-6 col-12">
                                                                <form method="post" action="../sdo-funciones/pabellones_cambiarSetPoint.php">
                                                                    <button type="button" data-toggle="modal" data-target="#modalCambiarSP" id="inputMozi" class="btn btn-block bg-indigo">
                                                                        <i class="fa fa-pen"></i>&nbsp;&nbsp; Set-Point T°
                                                                    </button>

                                                                    <!-- Modal -->
                                                                    <div class="modal fade" id="modalCambiarSP" name="modalCambiarSP" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header bg-primary">
                                                                                    <h5 class="modal-title" id="exampleModalLabel">Cambiar Set-Point de Temperatura</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>

                                                                                <div class="modal-body">
                                                                                    <div style="text-align:left;" class="form-group">
                                                                                        <label for="exampleInputBorder">Nombre del Tecnico:
                                                                                            <code>*</code></label>
                                                                                        <input type="text" readonly="readonly" class="form-control " id="addNombre" name="addNombre" placeholder="Nombre de Personal" value="<?php echo $nomUsuario; ?>" required>
                                                                                    </div>
                                                                                    <div style="text-align:left;" class="form-group">
                                                                                        <label for="exampleInputBorder">Fecha de Observacion:
                                                                                            <code>*</code></label>
                                                                                        <input style="text-align:left;" readonly="readonly" type="text" style="text-align:center;" class="form-control " id="addFecha" name="addFecha" value="<?php echo $fecha; ?>" required>
                                                                                    </div>
                                                                                    <div style="text-align:left;" class="form-group">
                                                                                        <label for="exampleInputBorder">Nuevo Set-Point de Temperatura:
                                                                                            <code>*</code></label>
                                                                                        <label for="exampleInputBorder" style="color: red">Para Decimales debe agregar una coma ' , '</label>
                                                                                        <input style="text-align:left;" type="number" style="text-align:center;" class="form-control" min="0" step=".1" id="nuevoValorSP" name="nuevoValorSP" required>
                                                                                    </div>
                                                                                    <div style="text-align:left;" class="form-group">
                                                                                        <label for="exampleInputBorder">Observacion:
                                                                                            <code>*</code></label>
                                                                                        <textarea id="observacionEquipo" style="resize:none;" name="observacionEquipo" class="form-control" rows="8" placeholder="Observacion..."></textarea>

                                                                                    </div>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $equipo_controla; ?>">
                                                                                    <input type="hidden" name="personalObservacion" id="personalObservacion" value="<?php echo $nomUsuario . ' (' . $cargoUsuario . ')'; ?>">
                                                                                    <input type="hidden" name="idEquipamiento" id="idEquipamiento" value="<?php echo $idEquipo; ?>">
                                                                                    <button type="submit" class="btn btn-primary">Agregar</button>
                                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <!-- FIN CAMBIO SET POINT -->
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="table-responsive">

                                            <table id="tableAnotacionesSistema" class="table table-bordered table-hover">
                                                <thead class="bg-info">
                                                <tr>
                                                    <th style="vertical-align:middle;">
                                                        <center>N°</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Fecha Observaciones:</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Nombre de Quien Registra:</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Observaciones:</center>
                                                    </th>
                                                    <?php
                                                    $verificarEsPabellon = verificarEsPabellon($equipo_controla);

                                                    if ($verificarEsPabellon == 1) {
                                                        ?>
                                                        <th nowrap="nowrap" style="vertical-align:middle;">
                                                            <center>Set-Point de T°:</center>
                                                        </th>
                                                        <?php
                                                    }
                                                    ?>
                                                    <th style="vertical-align:middle;">
                                                        <center>Tipo de Observaci&oacute;n:</center>
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $query = "SELECT * FROM equipos_observaciones WHERE idEquipo = '" . $equipo_controla . "' ORDER BY fechaObservaciones DESC";

                                                $res = mysqli_query($conexion, $query);

                                                $numObservacion = 1;

                                                while ($row = $res->fetch_assoc()) {
                                                    ?>

                                                    <?php

                                                    $classObservacion = "";

                                                    if ($row['esForzado'] == "Forzado - Encendido") {

                                                        $classObservacion = "bg-success";
                                                    } else if ($row['esForzado'] == "Forzado - Apagado") {

                                                        $classObservacion = "bg-pink ";
                                                    } else if ($row['esForzado'] == "Forzado - Variables") {

                                                        $classObservacion = "bg-yellow";
                                                    } else if ($row['esForzado'] == "Modo Automatico") {

                                                        $classObservacion = "bg-info";
                                                    } else if ($row['esForzado'] == "Observación Normal") {

                                                        $classObservacion = "bg-navy";
                                                    } else if ($row['esForzado'] == "Forzado") {

                                                        $classObservacion = "bg-gray";
                                                    } else if ($row['esForzado'] == "Cambio de Set Point") {

                                                        $classObservacion = "bg-indigo";
                                                    } else {

                                                        $classObservacion = "bg-light";
                                                    }
                                                    ?>

                                                    <tr class="<?php echo $classObservacion; ?>">
                                                        <td style="vertical-align:middle;">
                                                            <center>
                                                                <?php echo $numObservacion; ?>
                                                            </center>
                                                        </td>
                                                        <td style="vertical-align:middle;">
                                                            <center>
                                                                <?php if ($row['fechaObservaciones'] == 0) {
                                                                    echo "No registra fecha";
                                                                } else {
                                                                    $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $row['fechaObservaciones'])));
                                                                    $inicioHora = date('H:i', strtotime($row['fechaObservaciones']));
                                                                    echo $inicioFecha . '&nbsp;&nbsp;' . $inicioHora;
                                                                } ?>
                                                            </center>
                                                        </td>
                                                        <td nowrap="nowrap" style="vertical-align:middle;">
                                                            <center>
                                                                <?php
                                                                $personalObservacion = $row['personalObservacion'];

                                                                $partes = explode(' (', $personalObservacion, 2);
                                                                $nombre = $partes[0];
                                                                $profesion = rtrim($partes[1], ')');

                                                                echo $nombre . '<br>(' . $profesion . ')';
                                                                ?>
                                                            </center>
                                                        </td>
                                                        <td style="vertical-align:middle; text-align:justify;">
                                                            <?php echo nl2br($row['detalleObservaciones']); ?>
                                                        </td>
                                                        <?php
                                                        $verificarEsPabellon = verificarEsPabellon($equipo_controla);

                                                        if ($verificarEsPabellon == 1) {
                                                            ?>
                                                            <td nowrap="nowrap" style="vertical-align:middle;">
                                                                <center>
                                                                    <?php
                                                                    $porcentajeObservacion = $row['porcentajeObservacion'];

                                                                    if ($porcentajeObservacion > 0) {
                                                                        echo $porcentajeObservacion;
                                                                    } else {
                                                                        echo 'No Aplica';
                                                                    }

                                                                    ?>
                                                                </center>
                                                            </td>
                                                            <?php
                                                        }
                                                        ?>
                                                        <td style="vertical-align:middle; text-align:center;">
                                                            <?php echo $row['esForzado']; ?>
                                                        </td>
                                                    </tr>

                                                    <?php

                                                    $numObservacion++;
                                                }
                                                ?>
                                                </tbody>
                                                <tfoot class="bg-info">
                                                <tr>
                                                    <th style="vertical-align:middle;">
                                                        <center>N°</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Fecha Observaciones:</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Nombre de Quien Registra:</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Observaciones:</center>
                                                    </th>
                                                    <?php
                                                    $verificarEsPabellon = verificarEsPabellon($equipo_controla);

                                                    if ($verificarEsPabellon == 1) {
                                                        ?>
                                                        <th nowrap="nowrap" style="vertical-align:middle;">
                                                            <center>Set-Point de T°:</center>
                                                        </th>
                                                        <?php
                                                    }
                                                    ?>
                                                    <th style="vertical-align:middle;">
                                                        <center>Tipo de Observaci&oacute;n:</center>
                                                    </th>
                                                </tr>
                                                </tfoot>
                                            </table>

                                            <!--                            <h5>--><?php //echo $query; ?><!--</h5>-->
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="tabAlarmasBMS">

                                        <h4 class="text-center font-weight-bold">Alarmas BMS</h4>

                                        <input type="hidden" name="varSourceAlarma" id="varSourceAlarma" value="<?php echo $equipoSource; ?>">
                                        <br>
                                        <div class="table-responsive">
                                            <table style="font-size:80%" id="tableAlarmasBMS" class="table table-bordered cell-border">
                                                <thead class="bg-secondary">
                                                <tr>
                                                    <th style="vertical-align:middle;">
                                                        <center>N°</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Source</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Clase de Alarma</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Mensaje Alarma</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Ultima Activaci&oacute;n Alarma</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Ultimo Normalizado Alarma</center>
                                                    </th>
                                                    <th style="vertical-align:middle">
                                                        <center>Historial</center>
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                                <tfoot class="bg-secondary">
                                                <tr>
                                                    <th style="vertical-align:middle;">
                                                        <center>N°</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Source</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Clase de Alarma</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Mensaje Alarma</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Ultima Activaci&oacute;n Alarma</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Ultimo Normalizado Alarma</center>
                                                    </th>
                                                    <th style="vertical-align:middle">
                                                        <center>Historial</center>
                                                    </th>
                                                </tr>
                                                </tfoot>
                                            </table>

                                            <div class="modal fade" id="editarModal" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-xl" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-gradient-warning">
                                                            <h5 class="modal-title" id="editarModalLabel">Detalles de Alarma</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form id="visualizarAlarmas">
                                                                <div class="form-row">
                                                                    <div class="form-group col-md-6">
                                                                        <label for="sourceMostrar">Source:</label>
                                                                        <input type="text" readonly class="form-control" id="sourceMostrar" name="sourceMostrar">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label for="mensajeMostrar">Mensaje</label>
                                                                        <input type="text" readonly class="form-control" id="mensajeMostrar" name="mensajeMostrar">
                                                                    </div>
                                                                </div>
                                                                <br><br>
                                                                <div class="table-responsive">
                                                                    <table id="tableRegistroAlarmas" class="table table-bordered table-striped text-center" style="width: 100%">
                                                                        <thead class="bg-secondary">
                                                                        <tr>
                                                                            <th style="width: 10%">N°</th>
                                                                            <th style="width: 45%">Fecha Alarma</th>
                                                                            <th style="width: 45%">Fecha Normalizado</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        </tbody>
                                                                        <tfoot class="bg-secondary">
                                                                        <tr>
                                                                            <th style="width: 10%">N°</th>
                                                                            <th style="width: 45%">Fecha Alarma</th>
                                                                            <th style="width: 45%">Fecha Normalizado</th>
                                                                        </tr>
                                                                        </tfoot>
                                                                    </table>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                                <i class="fas fa-times"></i>&nbsp;&nbsp;Cerrar
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="tab-pane" id="tabEquipamientoBMS">

                                        <h4 class="text-center font-weight-bold"><?php echo 'BMS: ' . $nombreEquipo; ?></h4>
                                        <br><br>


                                        <div class="row">
                                            <?php
                                            $sqlSourceBtn = "WITH RECURSIVE EquiposHijos AS ( SELECT id FROM equipamiento_hbqp WHERE idEquipoPadre = $idEquipo UNION ALL SELECT eh.id FROM equipamiento_hbqp eh INNER JOIN EquiposHijos ehh ON eh.idEquipoPadre = ehh.id ) SELECT sourceAsocia FROM bms_asocia_source WHERE idEquipoAsocia = $idEquipo OR idEquipoAsocia IN (SELECT id FROM EquiposHijos) ORDER BY sourceAsocia ASC;";

                                            $resSourceBtn = mysqli_query($conexion, $sqlSourceBtn);

                                            $sourceBtn = array();

                                            $numInt = 1;

                                            // Definir una lista de clases de colores
                                            $colores = ['bg-primary', 'bg-success', 'bg-danger', 'bg-warning', 'bg-info'];

                                            if (mysqli_num_rows($resSourceBtn) > 0) {
                                                while ($rowSourceBtn = $resSourceBtn->fetch_assoc()) {
                                                    $nomSourceBtn = $rowSourceBtn['sourceAsocia'];

                                                    // Seleccionar un color aleatorio
                                                    $colorAleatorio = $colores[array_rand($colores)];
                                                    ?>

                                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 p-2">
                                                        <!-- small box -->
                                                        <button type="button" style="font-size: medium" class="btn btn-block btn-lg <?php echo $colorAleatorio; ?>" data-toggle="modal" data-target="#revisar<?php echo $numInt; ?>">
                                                            <i class="fas fa-cog"></i>
                                                            <br>
                                                            <?php echo $nomSourceBtn; ?>
                                                        </button>
                                                    </div>

                                                    <div class="modal fade" id="revisar<?php echo $numInt; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header <?php echo $colorAleatorio; ?>">
                                                                    <h5 class="modal-title" id="exampleModalLabel"><?php echo $nomSourceBtn; ?></h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <br>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <br>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    $numInt++;
                                                }
                                            }
                                            ?>
                                        </div>
                                        <br>

                                        <div class="row">
                                            <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                                                <div class="card card-primary card-outline">
                                                    <div class="card-body">


                                                        <h3 class="profile-username text-center">Sources Asociados</h3>
                                                        <br>


                                                        <ul class="list-group list-group-unbordered mb-3">
                                                            <li class="list-group-item">
                                                                <button type="button" title="Asociar Sources" class="btn btn-primary btn-block" data-toggle="modal" data-target="#asociarSource">
                                                                    <i class="fas fa-plus"></i>&nbsp;&nbsp;Asociar Sources
                                                                </button>
                                                            </li>
                                                            <li class="list-group-item">
                                                                <button type="button" title="Subir Datos" class="btn btn-success btn-block" data-toggle="modal" data-target="#subirAlarmasSources">
                                                                    <i class="fas fa-file-upload"></i>&nbsp;&nbsp;Subir Alarmas
                                                                </button>
                                                            </li>

                                                            <?php
                                                            $sqlObtenerSource = "WITH RECURSIVE EquiposHijos AS ( SELECT id FROM equipamiento_hbqp WHERE idEquipoPadre = $idEquipo UNION ALL SELECT eh.id FROM equipamiento_hbqp eh INNER JOIN EquiposHijos ehh ON eh.idEquipoPadre = ehh.id ) SELECT sourceAsocia, idAsocia FROM bms_asocia_source WHERE idEquipoAsocia = $idEquipo OR idEquipoAsocia IN (SELECT id FROM EquiposHijos) ORDER BY sourceAsocia ASC;";

                                                            //                                                                                                                echo $sqlObtenerSource;
                                                            $resObtenerSource = mysqli_query($conexion, $sqlObtenerSource);

                                                            $sourcesArray = array();

                                                            if (mysqli_num_rows($resObtenerSource) > 0) {
                                                                while ($rowObtenerSource = $resObtenerSource->fetch_assoc()) {
                                                                    $sourceAsocia = $rowObtenerSource['sourceAsocia'];
                                                                    $idAsocia = $rowObtenerSource['idAsocia'];
                                                                    $sourcesArray[] = $rowObtenerSource['sourceAsocia'];
                                                                    ?>

                                                                    <li style="vertical-align: middle" class="list-group-item">
                                                                        <?php echo $sourceAsocia; ?>
                                                                        <button class="btn btn-danger btn-desasociarsource float-right" data-id="<?php echo $idAsocia; ?>">
                                                                            <i class="fas fa-times"></i>
                                                                        </button>
                                                                    </li>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </ul>


                                                        <script>
                                                            $(document).ready(function () {
                                                                $(document).on('click', '.btn-desasociarsource', function () {
                                                                    var button = $(this); // Referencia al botón clicado
                                                                    var icon = button.find('i'); // Icono dentro del botón
                                                                    var idAsocia = button.data('id'); // Obtener el valor del atributo data-id

                                                                    // console.log(sourceAsocia+equipo);

                                                                    // Mostrar confirmación con SweetAlert
                                                                    Swal.fire({
                                                                        title: "¿Estás seguro?",
                                                                        text: "No podrás revertir esta acción.",
                                                                        icon: "warning",
                                                                        showCancelButton: true,
                                                                        confirmButtonColor: "#d33",
                                                                        cancelButtonColor: "#3085d6",
                                                                        confirmButtonText: "Sí, eliminar",
                                                                        cancelButtonText: "Cancelar"
                                                                    }).then((result) => {
                                                                        if (result.isConfirmed) {
                                                                            // Cambiar el icono y deshabilitar el botón
                                                                            icon.removeClass('fa-times').addClass('fa-spinner fa-spin');
                                                                            button.prop('disabled', true);

                                                                            // Realizar la solicitud AJAX
                                                                            $.ajax({
                                                                                url: '/sdo-funciones/sources_desasociarEquipo.php',
                                                                                type: 'POST',
                                                                                data: {idAsocia: idAsocia},
                                                                                success: function (response) {
                                                                                    console.log(response.status);
                                                                                    if (response.status === 'success') {
                                                                                        Swal.fire({
                                                                                            icon: 'success',
                                                                                            title: 'Éxito',
                                                                                            text: response.message,
                                                                                            confirmButtonText: 'Aceptar'
                                                                                        }).then(() => {
                                                                                            button.closest('li').remove();
                                                                                            $('#tableListProtocolos').DataTable().ajax.reload();

                                                                                        });
                                                                                    } else {
                                                                                        Swal.fire({
                                                                                            icon: 'error',
                                                                                            title: 'Error',
                                                                                            text: response.message,
                                                                                            confirmButtonText: 'Aceptar'
                                                                                        });
                                                                                    }
                                                                                },
                                                                                error: function (xhr, status, error) {
                                                                                    Swal.fire({
                                                                                        icon: 'error',
                                                                                        title: 'Error',
                                                                                        text: 'Error al procesar la solicitud. Intente nuevamente.',
                                                                                        confirmButtonText: 'Aceptar'
                                                                                    });
                                                                                }
                                                                            });
                                                                        }
                                                                    });
                                                                });

                                                            });
                                                        </script>

                                                        <div class="modal fade" id="asociarSource" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-info">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Asociar Sources</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <form action="../sdo-funciones/bms_asociarSourcesEquipos.php" method="POST" enctype="multipart/form-data">
                                                                        <div class="modal-body">
                                                                            <?php

                                                                            $SourceAlarmasEquiposArray = array();

                                                                            $nombreEquipoSinEspacios = preg_replace('/\s+/', '', $nombreEquipo); // Quitar todos los espacios y tabulaciones

                                                                            // Construye la consulta SQL
                                                                            $sqlSourceAlarmasEquipos = "SELECT sourceAlarma FROM bms_alarmas_equipamiento WHERE REPLACE(sourceAlarma, ' ', '') LIKE '%$nombreEquipoSinEspacios%' GROUP BY sourceAlarma;";

                                                                            // Ejecuta la consulta
                                                                            $resSourceAlarmasEquipos = mysqli_query($conexion, $sqlSourceAlarmasEquipos);

                                                                            // Verifica si la consulta se ejecutó correctamente
                                                                            if ($resSourceAlarmasEquipos && mysqli_num_rows($resSourceAlarmasEquipos) > 0) {
                                                                                while ($rowSourceAlarmasEquipos = $resSourceAlarmasEquipos->fetch_assoc()) {
                                                                                    // Agrega cada fila al array
                                                                                    $SourceAlarmasEquiposArray[] = $rowSourceAlarmasEquipos['sourceAlarma'];
                                                                                }
                                                                            }
                                                                            //
                                                                            //                                                                                                                    echo $sqlObtenerSource.'<br><br>';
                                                                            //                                                                                                                    var_dump($SourceAlarmasEquiposArray);
                                                                            ?>
                                                                            <input type="hidden" name="idEquipamiento" id="idEquipamiento" value="<?php echo $idEquipo; ?>">
                                                                            <input type="hidden" name="idEquipamientoPadre" id="idEquipamientoPadre" value="<?php echo $idEquipoPadre ?>">
                                                                            <div class="form-group">
                                                                                <label for="exampleFormControlSelect1">Seleccione Sources</label>
                                                                                <select class="listDataSource" name="sourcesParaAsociar[]" multiple="multiple">
                                                                                    <?php
                                                                                    // Supongamos que $arrayFuentesExistentes contiene las fuentes que no quieres mostrar

                                                                                    $sqlListadoSource = "SELECT sourceAlarma FROM bms_alarmas_equipamiento GROUP BY sourceAlarma";
                                                                                    $resListadoSource = mysqli_query($conexion, $sqlListadoSource);

                                                                                    // Verifica si la consulta se ejecutó correctamente
                                                                                    if ($resListadoSource && mysqli_num_rows($resListadoSource) > 0) {
                                                                                        while ($rowListadoSource = $resListadoSource->fetch_assoc()) {
                                                                                            $sourceAlarma = $rowListadoSource['sourceAlarma'];

                                                                                            // Mostrar solo si el valor no está en el array $arrayFuentesExistentes
                                                                                            if (!in_array($sourceAlarma, $sourcesArray)) {
                                                                                                ?>
                                                                                                <option value="<?php echo $sourceAlarma; ?>"><?php echo $sourceAlarma; ?></option>
                                                                                                <?php
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="submit" class="btn btn-success">
                                                                                <i class="fas fa-arrow-circle-up"></i>&nbsp;&nbsp;Asociar
                                                                            </button>
                                                                            <button type="button" class="btn btn-warning" data-dismiss="modal">
                                                                                <i class="fas fa-times"></i>&nbsp;&nbsp;Cerrar
                                                                            </button>
                                                                        </div>
                                                                    </form>

                                                                </div>
                                                            </div>
                                                        </div>

                                                        <form action="../sdo-funciones/bms_subirAlarmasEquipamiento.php" method="POST" enctype="multipart/form-data">

                                                            <div class="modal fade" id="subirAlarmasSources" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header bg-info">
                                                                            <h5 class="modal-title" id="exampleModalLabel">Subir Alarmas</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="row">
                                                                                <div class="col-md-12 ml-auto">
                                                                                    <div style="text-align:left;" class="form-group">
                                                                                        <div class="custom-file">
                                                                                            <input type="file" class="custom-file-input" id="archivo_excel_alarmas" name="archivo_excel_alarmas" accept=".xlsx">
                                                                                            <label class="custom-file-label" for="customFile">Seleccionar Archivo...</label>
                                                                                        </div>
                                                                                        <script>
                                                                                            document.getElementById('archivo_excel_alarmas').addEventListener('change', function () {
                                                                                                var fileName = this.value.split('\\').pop(); // Obtener solo el nombre del archivo
                                                                                                var label = document.querySelector('.custom-file-label');
                                                                                                label.textContent = fileName;
                                                                                            });
                                                                                        </script>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <input type="hidden" name="idEquipamiento" id="idEquipamiento" value="<?php echo $idEquipo; ?>">
                                                                            <input type="hidden" name="idEquipamientoPadre" id="idEquipamientoPadre" value="<?php echo $idEquipoPadre; ?>">
                                                                            <input type="hidden" name="tipoMenuVuelta" id="tipoMenuVuelta" value="menuAntiguo">
                                                                            <button type="submit" class="btn btn-primary">
                                                                                <i class="fas fa-upload"></i>&nbsp;&nbsp;Agregar
                                                                            </button>
                                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                                                <i class="fas fa-times"></i>&nbsp;&nbsp;Cancelar
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <!-- /.card-body -->
                                                </div>
                                            </div>

                                            <div class="col-xl-9 col-lg-9 col-12">

                                                <div class="card card-primary card-outline">
                                                    <div class="card-body">
                                                        <h3 class="profile-username text-center">Tabla de Alarmas</h3>
                                                        <br>
                                                        <br>
                                                        <div class="table-responsive">
                                                            <input type="hidden" name="idSubEquipoCargaAlarmas" id="idSubEquipoCargaAlarmas" value="<?php echo $idSubEquipo; ?>">
                                                            <table id="bms_alarmas_equipos<?php echo $idSubEquipo; ?>" class="table table-bordered">
                                                                <thead class="bg-secondary text-center">
                                                                <tr>
                                                                    <th>Fecha</th>
                                                                    <th>Source</th>
                                                                    <th>Estado</th>
                                                                    <th>Clase</th>
                                                                    <th>Mensaje</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php
                                                                // Escapa y formatea los valores del array para la consulta SQL
                                                                $escapedSources = array_map(function ($value) use ($conexion) {
                                                                    return "'" . mysqli_real_escape_string($conexion, $value) . "'";
                                                                }, $sourcesArray);

                                                                // Convierte el array de valores en una cadena separada por comas
                                                                $sourceList = implode(', ', $escapedSources);

                                                                // Construye la consulta SQL
                                                                $sqlTableAlarmasEquipos = "SELECT * FROM bms_alarmas_equipamiento WHERE sourceAlarma IN ($sourceList)";

                                                                // Ejecuta la consulta
                                                                $resTableAlarmas = mysqli_query($conexion, $sqlTableAlarmasEquipos);

                                                                // Verifica si la consulta se ejecutó correctamente
                                                                if ($resTableAlarmas && mysqli_num_rows($resTableAlarmas) > 0) {
                                                                    while ($rowTableAlarmas = $resTableAlarmas->fetch_assoc()) {
                                                                        $fechaAlarmas = $rowTableAlarmas['fechaAlarma'];

                                                                        $fechaAlarmas2 = strtotime($fechaAlarmas);

                                                                        $fechaAlarmas3 = fechaEspañol2($fechaAlarmas2);

                                                                        $horaAlarmas = date('H:i:s', $fechaAlarmas2);

                                                                        ?>
                                                                        <tr>
                                                                            <td><?php echo $fechaAlarmas3 . '<br>' . $horaAlarmas ?></td>
                                                                            <td><?php echo $rowTableAlarmas['sourceAlarma'] ?></td>
                                                                            <td><?php echo $rowTableAlarmas['stateAlarma'] ?></td>
                                                                            <td><?php echo $rowTableAlarmas['classAlarma'] ?></td>
                                                                            <td><?php echo $rowTableAlarmas['mensajeAlarma'] ?></td>
                                                                        </tr>
                                                                        <?php
                                                                    }
                                                                }

                                                                ?>
                                                                </tbody>
                                                                <tfoot class="bg-secondary text-center">
                                                                <tr>
                                                                    <th>Fecha</th>
                                                                    <th>Source</th>
                                                                    <th>Estado</th>
                                                                    <th>Clase</th>
                                                                    <th>Mensaje</th>
                                                                </tr>
                                                                </tfoot>
                                                            </table>
                                                            <script>
                                                                $(document).ready(function () {
                                                                    $('#bms_alarmas_equipos<?php echo $idSubEquipo; ?>').DataTable({
                                                                        "paging": true,
                                                                        "iDisplayLength": 10,
                                                                        "lengthChange": false,
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
                                                                        "createdRow": function (row, data, dataIndex) {
                                                                            $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                                                                            $('td', row).css('text-align', 'center'); // Aplica el estilo de centrado vertical a todas las celdas
                                                                        }
                                                                    });
                                                                });
                                                            </script>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="tab-pane" id="tabEquipamientoMtto">

                                        <div class="col-12">
                                            <div class="row">
                                                <fieldset class="border p-3 text-center" style="width: 20%">
                                                    <p>
                                                        <b>Listado de Mantenimiento:</b>
                                                    </p>
                                                    <hr>
                                                    <button type="button" title="Agregar un manual" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#agregarmanual">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                    <hr>
                                                    <?php
                                                    $sqlUcas = "SELECT * FROM datos_checklist_ucas WHERE idEquipamiento = $idEquipo ORDER BY uca ASC";
                                                    $resUcas = mysqli_query($conexion, $sqlUcas);
                                                    while ($rowUcas = $resUcas->fetch_assoc()) {
                                                        $fechaUca = $rowUcas['fecha'];
                                                        $uca = $rowUcas['uca'];
                                                        $idCheckUCA = $rowUcas['id'];
                                                        $isVisadoUCA = $rowUcas['isVisado'];

                                                        ?>
                                                        <ul class="nav flex-column">
                                                            <li class="nav-item">
                                                                <?php
                                                                if ($extension == 'pdf') {
                                                                    ?>
                                                                    <button class="btn bg-gradient-info" onclick="cambiarPDF('<?php echo $rutaArchivo; ?>//')">
                                                                        <i class="fas fa-file-pdf"></i>&nbsp;<?php echo $nomArchivo ?>
                                                                    </button>
                                                                    <?php
                                                                } else if ($extension == 'jpg' || $extension == 'png') {
                                                                    ?>
                                                                    <button class="btn bg-gradient-info" onclick="cambiarPDF('<?php echo $rutaArchivo; ?>//')">
                                                                        <i class="fas fa-image"></i>&nbsp;<?php echo $nomArchivo ?>
                                                                    </button>
                                                                    <?php
                                                                }
                                                                ?>
                                                                <!--<button type="button" title="Modificar el manual" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modificarmanual<?php echo $idArchivo ?>"><i class="fas fa-pen"></i></button>-->
                                                                <div class="modal fade" id="modificarmanual<?php echo $idArchivo ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-gradient-warning">
                                                                                <h5 class="modal-title" id="exampleModalLabel">Modificar Nombre del Manual</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <form action="../sdo-funciones/modificar-nomManueales.php" method="POST" enctype="multipart/form-data">
                                                                                <div class="modal-body">
                                                                                    <div class="row">
                                                                                        <div class="form-group col-md-12 col-12">
                                                                                            <label style="font-size:12px">Nombre Archivo:<code>*</code></label>
                                                                                            <div class="input-group mb-3">
                                                                                                <div class="input-group-prepend">
                                                                                                    <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                                                                </div>
                                                                                                <input type="text" class="form-control " required id="nomArchivoModi" name="nomArchivoModi" value="<?php echo $nomArchivo ?>">
                                                                                                <input type="hidden" name="id" id="id" value="<?php echo $idArchivo ?>">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                                    <button type="submit" class="btn btn-success">Modificar</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <button class="btn btn-danger btn-sm eliminarManual" id="<?php echo $idArchivo ?>">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                                <hr>
                                                            </li>
                                                        </ul>
                                                        <br>
                                                        <?php
                                                    }
                                                    ?>
                                                </fieldset>
                                                <fieldset style="width: 80%" class="border p-3 text-center">
                                                    <?php
                                                    if ($extension == 'pdf') {
                                                        ?>
                                                        <embed id="pdfViewer" src="" type="application/pdf" width="100%" height="900px"/>
                                                        <?php
                                                    } else if ($extension == 'jpg' || $extension == 'png') {
                                                        ?>
                                                        <img id="pdfViewer" src=""/>
                                                        <?php
                                                    }
                                                    ?>
                                                </fieldset>
                                            </div>
                                            <script>
                                                function cambiarPDF(ruta) {
                                                    document.getElementById('pdfViewer').setAttribute('src', ruta);
                                                }
                                            </script>
                                            <div class="modal fade" id="agregarmanual" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-info">
                                                            <h5 class="modal-title" id="exampleModalLabel">Formulario - Información del archivo</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="../sdo-funciones/guardar-archivos-carpeta.php" method="POST" enctype="multipart/form-data">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="idCarpeta" id="idCarpeta" value="<?php echo $idCategoria ?>">
                                                                <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipo ?>">
                                                                <input type="hidden" name="nomCarpeta" id="nomCarpeta" value="<?php echo $nomDetalleEquipo ?>">
                                                                <div class="row">
                                                                    <div class="form-group col-md-12 col-12">
                                                                        <label style="font-size:12px">Nombre Archivo:<code>*</code></label>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                                            </div>
                                                                            <input type="text" class="form-control " required id="nomArchivo" name="nomArchivo">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-md-12 col-12">
                                                                        <label for="exampleInputCodigoTarjeta" style="font-size:12px">Tipo Archivo:<code>*</code></label>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                                                            </div>
                                                                            <select class="form-control " required id="tipoArchivo" name="tipoArchivo">
                                                                                <option value="" disabled selected>-- Seleccione --</option>
                                                                                <?php
                                                                                $sql = "SELECT * FROM tiposarchivos";
                                                                                $res = mysqli_query($conexion, $sql);
                                                                                while ($row = $res->fetch_assoc()) {
                                                                                    $datos = [];
                                                                                    $datos['id '] = $row['id'];
                                                                                    $datos['nomTipo'] = $row['nomTipo'];
                                                                                    $id = $datos['id'];
                                                                                    $nomTipo = $datos['nomTipo'];
                                                                                    ?>
                                                                                    <option value="<?php echo $nomTipo ?>"><?php echo mb_convert_case($nomTipo, MB_CASE_UPPER, 'UTF-8') ?></option>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                                <option value="Otros">Otro</option>
                                                                                <?php
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div name="visualizarNomTipo" id="visualizarNomTipo" class="form-group col-md-12 col-12">

                                                                    </div>
                                                                    <div class="form-group col-md-12 col-12">
                                                                        <label for="exampleInputCodigoTarjeta" style="font-size:12px">Adjunto el archivo:<code>*</code></label>
                                                                        <div class="input-group mb-3">
                                                                            <input type="file" class="form-control " required id="archivoAdjunto" name="archivoAdjunto">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-warning" data-dismiss="modal">Cerrar</button>
                                                                <button type="submit" class="btn btn-success">Añadir Archivo</button>
                                                            </div>
                                                        </form>
                                                        <input type="hidden" id="idCategoriaBuscar" name="idCategoriaBuscar" value="<?php echo $idCategoria ?>">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="tabRelaciones">
                                        <div class="col-12">
                                            <h5 class="text-center">Relaciones</h5>
                                            <hr>
                                                <?php
                                                    if (strpos($nombreEquipo, 'UMA-') === 0) {
                                                        ?>
                                                        <div class="row">
                                                            <table id="tableRelaciones" class="table table-bordered table-striped text-center" style="width: 100%">
                                                                <thead class="bg-secondary">
                                                                <tr>
                                                                    <th>Nombre del Equipo</th>
                                                                    <th>Número de Entrada</th>
                                                                    <th>Entradas Digitales</th>
                                                                    <th>Entradas Analogicas</th>
                                                                    <th>Número de Salida</th>
                                                                    <th>Salidas Digitales</th>
                                                                    <th>Salidas Analogicas</th>
                                                                    <th>Visualizar Equipo</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>

                                                                <?php
                                                                if (strpos($nombreEquipo, 'UMA-') === 0) {
                                                                    $nombreEquipoRelacion = str_replace('UMA-', 'UMA ', $nombreEquipo);
                                                                }

                                                                if($nombreEquipoRelacion == 'UMA S1-4'){
                                                                    $nombreEquipoRelacion = 'UMA 1-4';
                                                                }
                                                                $sqlRelaciones = "SELECT * FROM detalle_control_bms WHERE digitalInput_ControlBMS REGEXP '" . $nombreEquipoRelacion . "($|[^A-Za-z0-9])' OR analogInput_ControlBMS REGEXP ' " . $nombreEquipoRelacion . "($|[^A-Za-z0-9])' OR analogOutput_ControlBMS REGEXP ' " . $nombreEquipoRelacion . "($|[^A-Za-z0-9])' OR digitalOutput_ControlBMS REGEXP '".$nombreEquipoRelacion."($|[^A-Za-z0-9])'";

                                                                $resRelaciones = mysqli_query($conexion,$sqlRelaciones);
                                                                while($rowRelaciones = $resRelaciones->fetch_assoc()){
                                                                    $controlador_ControlBMSRelacion = $rowRelaciones['controlador_ControlBMS'];
                                                                    $digitalInput_ControlBMSRelacion = $rowRelaciones['digitalInput_ControlBMS'];
                                                                    $analogInput_ControlBMSRelacion = $rowRelaciones['analogInput_ControlBMS'];
                                                                    $digitalOutput_ControlBMSRelacion = $rowRelaciones['digitalOutput_ControlBMS'];
                                                                    $analogOutput_ControlBMSRelacion = $rowRelaciones['analogOutput_ControlBMS'];
                                                                    $numEntrada_ControlBMSRelacion = $rowRelaciones['numEntrada_ControlBMS'];
                                                                    $numSalida_ControlBMSRelacion = $rowRelaciones['numSalida_ControlBMS'];
                                                                    $controlador_ControlBMSRelacionEquipamiento = str_replace([' ', '-'], ['', '_'], $controlador_ControlBMSRelacion);

                                                                    ?>
                                                                    <tr>
                                                                        <td>
                                                                            <?php echo $controlador_ControlBMSRelacion?>
                                                                        </td>
                                                                        <td>
                                                                            <?php echo $numEntrada_ControlBMSRelacion?>
                                                                        </td>
                                                                        <td>
                                                                            <?php echo $digitalInput_ControlBMSRelacion?>
                                                                        </td>
                                                                        <td>
                                                                            <?php echo $analogInput_ControlBMSRelacion?>
                                                                        </td>
                                                                        <td>
                                                                            <?php echo $numSalida_ControlBMSRelacion?>
                                                                        </td>
                                                                        <td>
                                                                            <?php echo $digitalOutput_ControlBMSRelacion?>
                                                                        </td>
                                                                        <td>
                                                                            <?php
                                                                            if (strpos($analogOutput_ControlBMSRelacion, $nombreEquipoRelacion) !== false) {
                                                                                echo $analogOutput_ControlBMSRelacion;
                                                                            } else {
                                                                                echo '-';
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php
                                                                            $sqlEquipamientoControlador = "SELECT *  FROM `equipamiento_hbqp` WHERE `nombreEquipo` LIKE '%$controlador_ControlBMSRelacionEquipamiento%'";
                                                                            $resEquipamientoControlador = mysqli_query($conexion,$sqlEquipamientoControlador);
                                                                            while($rowEquipamientoControlador = $resEquipamientoControlador->fetch_assoc()){
                                                                                $idRelacion = $rowEquipamientoControlador['id'];
                                                                            }
                                                                            ?>
                                                                            <form action="equipo_menuAntiguo.php" method="POST" target="_blank">
                                                                                <input type="hidden" name="idEquipo" value="<?php echo $idRelacion ?>">
                                                                                <button type="submit" class="btn bg-secondary"><i class="fas fa-eye"></i></button>
                                                                            </form>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                                ?>
                                                                </tbody>
                                                                <tfoot class="bg-secondary">
                                                                <tr>
                                                                    <th>Nombre del Equipo</th>
                                                                    <th>Número de Entrada</th>
                                                                    <th>Entradas Digitales</th>
                                                                    <th>Entradas Analogicas</th>
                                                                    <th>Número de Salida</th>
                                                                    <th>Salidas Digitales</th>
                                                                    <th>Salidas Analogicas</th>
                                                                    <th>Visualizar Equipo</th>
                                                                </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                        <?php
                                                    }
                                                ?>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tabCheckList">
                                        <div class="col-12">
                                            <h5 class="text-center">Check List</h5>
                                            <hr>
                                            <table class="table table-bordered table-striped text-center" id="tableCheckList" style="width: 100%">
                                                <thead class="bg-secondary">
                                                <tr>
                                                    <th>Fecha Realizacion de Check List</th>
                                                    <th>Personal Designado</th>
                                                    <th>Visualizar Check List</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $tabla = '';
                                                $campoId = '';

                                                if (strpos($nombreEquipo, 'VAV_') === 0) {
                                                    $tabla = 'datos_vav';
                                                    $campoId = 'idVav';
                                                    $campoIdNUEVO = 'idVAV';
                                                    $pdf = '../sdo-pdf/SDO-CheckList-VAV.php';
                                                } elseif (strpos($nombreEquipo, 'VF-') === 0) {
                                                    $tabla = 'datos_vf';
                                                    $campoId = 'id';
                                                    $campoIdNUEVO = 'idVF';
                                                    $pdf = '../sdo-pdf/SDO-CheckList-VF.php';
                                                }

                                                if ($tabla != '') {
                                                    $sqlCheckList = "SELECT * FROM $tabla WHERE idEquipamiento = $idEquipo";
                                                    $resCheckList = mysqli_query($conexion, $sqlCheckList);

                                                    while ($rowCheckList = $resCheckList->fetch_assoc()) {
                                                        $id = $rowCheckList[$campoId];
                                                        $idEquipamiento = $rowCheckList['idEquipamiento'];
                                                        $fechaCheckList = $rowCheckList['fecha'];
                                                        $idTGChecKList = $rowCheckList['idTG'];
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $fechaCheckList?></td>
                                                            <td>
                                                                <?php
                                                                    $sqlBuscarOT = "SELECT * FROM `trabajos_general` WHERE idTG = $idTGChecKList ";
                                                                    $resBuscarOT = mysqli_query($conexion, $sqlBuscarOT);
                                                                    while($rowBuscarOT = $resBuscarOT->fetch_assoc()){
                                                                        echo $rowBuscarOT['tecnico1'];
                                                                    }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <form action="<?php echo $pdf?>" method="POST" target="_blank">
                                                                    <input type="hidden" name="<?php echo $campoIdNUEVO?>" value="<?php echo $id?>">
                                                                    <input type="hidden" name="idTG" value="<?php echo $idTGChecKList?>">
                                                                    <button type="submit" class="btn bg-success"><i class="fas fa-eye"></i></button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                </tbody>
                                                <tfoot class="bg-secondary">
                                                <tr>
                                                    <th>Fecha Realizacion de Check List</th>
                                                    <th>Personal Designado</th>
                                                    <th>Visualizar Check List</th>
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

                <script>

                    $(document).ready(function () {
                        //Initialize Select2 Elements
                        $('.equipoQueControlaMod').select2({
                            theme: 'bootstrap4'
                        })
                        $('.equipoQueControlaAgregar').select2({
                            theme: 'bootstrap4'
                        })

                        //Initialize Select2 Elements
                        $('.select2bs4').select2({
                            theme: 'bootstrap4'
                        })

                        $('.listDataSource').select2({
                            placeholder: 'Seleccione',
                            theme: 'bootstrap4',
                            allowClear: true,
                            width: '100%' // Asegura que ocupe el 100% del ancho
                        });


                        $('.listUmaControl').select2({
                            placeholder: 'Seleccione',
                            theme: 'bootstrap4',
                            allowClear: true,
                            width: '100%' // Asegura que ocupe el 100% del ancho
                        });

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
                                if (idCategoria === '2') {
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
                                } else {
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
                    $(document).ready(function () {
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
                    $(function () {
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
                                        if (mensajeAlarma2 !== '' && sourceAlarma2 !== '') {
                                            return '<button class="btn btn-primary btn-visualizar" data-source="' + sourceAlarma2 + '" data-mensaje="' + mensajeAlarma2 + '"><i class="fas fa-eye"></i></button>';
                                        } else {
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

                                $('td', row).each(function (index) {
                                    var cellText = $(this).text();
                                    if (index !== 4 && index !== 5 && index !== 6) {
                                        $(this).text(cellText.toUpperCase());
                                    } else {
                                        if (cellText === 'ALARMA ACTIVA') {
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
                                data: {
                                    source: source,
                                    mensaje: mensaje
                                },
                                dataType: 'json', // Asegúrate de que la respuesta sea tratada como JSON
                                success: function (response) {
                                    console.log('Datos recibidos del servidor:', response); // Añadir este console.log para ver los datos recibidos

                                    // Verificar que response sea un array
                                    if (Array.isArray(response)) {
                                        var table = $('#tableRegistroAlarmas').DataTable();
                                        table.clear().draw();
                                        response.forEach(function (item, index) {
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
                    $(document).ready(function () {
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
                <!-- /.container-fluid -->
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