<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$nomUsuario = $_SESSION['nomUsuario'] ?? null;

// Validar existencia de sesión de finalizador
if (empty($_SESSION['dataFinalizador'])) {
    header('Location: solicitudes_recibidas.php?errorCargarSolicitud=true');
    exit;
}

$dataFinalizador = trim($_SESSION['dataFinalizador']);
$soloNombre = trim(explode("(", $dataFinalizador)[0]);


// Obtener ID de solicitud desde POST o SESSION
$idSolicitud = $_POST['idSolicitud'] ?? $_SESSION['idSolicitud'] ?? null;
$idSolicitud = trim($idSolicitud);

// Validación de ID
if (empty($idSolicitud) || $idSolicitud == 0) {
    header('Location: solicitudes_recibidas.php?errorCargarSolicitud=true');
    exit;
}

// Obtener datos de solicitud
$datosSolicitud = getSolicitudSoporte($idSolicitud);
if (!$datosSolicitud) {
    header('Location: solicitudes_recibidas.php?errorCargarSolicitud=true');
    exit;
}

// Guardar ID en sesión para continuidad
$_SESSION['idSolicitud'] = $idSolicitud;

// Extraer y procesar datos
extract($datosSolicitud);

// Departamento final (último del string separado por '/')
$deptoSolicitanteParts = explode('/', $deptoSolicitante);
$ultima_deptoSolicitante = mb_strtoupper(end($deptoSolicitanteParts));

// Formato de fechas y horas
$fechaInicio = fechaEspañol2(strtotime($inicioSolicitud));
$horaInicio = date('H:i', strtotime($inicioSolicitud));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrar Solicitud de Soporte</title>

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
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            recargarSubDepartamento();
            recargarNomDepartamento();

            $('#departamentoSolicitante').change(function () {
                recargarSubDepartamento();
            });

            $('#servicios').change(function () {
                recargarNomDepartamento();
            });

            function recargarSubDepartamento() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-departamentos/mostrarSubDepartamentos.php",
                    data: "idDepartamento=" + $('#departamentoSolicitante').val(),
                    success: function (r) {
                        $('#visualizarSubDepartamento').html(r);
                    }
                });

            };

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
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            cargarTipoObservacion();

            $('#departamentoNuevoResponsable').change(function () {
                cargarTipoObservacion();
            });


            function cargarTipoObservacion() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-solicitud/cargarTipoSolicitud.php",
                    data: "idDepartamento=" + $('#departamentoNuevoResponsable').val(),
                    success: function (r) {
                        $('#dataTipoSolicitud').html(r);


                    }
                });

            };

        });
    </script>
</head>

<body class="hold-transition sidebar-mini ">
<!-- Site wrapper -->
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/user-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
            </div><!-- /.container-fluid -->
        </section>

        <?php
        $mensajesSwal = [
            'exitoAsignarPersonal' => [
                'titulo' => 'Asignación de Personal con Éxito.',
                'texto' => '',
                'icono' => 'success'
            ],
            'errorAsignarPersonal' => [
                'titulo' => '¡ Ha ocurrido un Problema !',
                'texto' => 'Error al Asignar Personal a la Solicitud.',
                'icono' => 'error'
            ],
            'exitoCambiarDepartamento' => [
                'titulo' => 'Cambio de Departamento Responsable con Éxito.',
                'texto' => '',
                'icono' => 'success'
            ],
            'errorCambiarDepartamento' => [
                'titulo' => '¡ Ha ocurrido un Problema !',
                'texto' => 'Error al Cambiar Departamento Responsable.',
                'icono' => 'error'
            ],
            'exitoCancelarSolicitud' => [
                'titulo' => 'Solicitud Cancelada con Éxito.',
                'texto' => '',
                'icono' => 'success'
            ],
            'errorCancelarSolicitud' => [
                'titulo' => '¡ Ha ocurrido un Problema !',
                'texto' => 'Error al Cancelar Solicitud. Reintente.',
                'icono' => 'error'
            ],
            'exitoAgregarObservacion' => [
                'titulo' => 'Observación Agregada con Éxito.',
                'texto' => '',
                'icono' => 'success'
            ],
            'errorAgregarObservacion' => [
                'titulo' => '¡ Ha ocurrido un Problema !',
                'texto' => 'Error al Agregar Observación. Reintente.',
                'icono' => 'error'
            ]
        ];

        // Buscar cuál mensaje mostrar según los parámetros GET
        foreach ($mensajesSwal as $clave => $alerta) {
            if (isset($_GET[$clave])) {
                ?>
                <script>
                    Swal.fire(
                        <?= json_encode($alerta['titulo']) ?>,
                        <?= json_encode($alerta['texto']) ?>,
                        <?= json_encode($alerta['icono']) ?>
                    );
                </script>
                <?php
                break; // Solo mostrar una alerta
            }
        }
        ?>


        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <div class="row">
                    <!-- left column -->
                    <div class="col-xl-10 col-lg-9 col-md-8 col-12">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <a style="pointer-events: none; font-size: 13pt; text-transform: uppercase" class="btn font-weight-bold">Finalizando Solicitud N° : </b><?php echo $folioSolicitud; ?></b></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                        <div class="card">
                            <div class="card-header bg-danger">
                                <a href="solicitudes_detalle.php" class="btn btn-block btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- AQUI VA FORM -->
                <form action="../sdo-funciones/solicitudes_finalizar_nuevo_formato.php" method="post" enctype="multipart/form-data" id="terminarFinalizar" name="terminarFinalizar">


                    <div class="row">
                        <div class="col-md-12">
                            <!-- Asegúrate de tener cargado Bootstrap + AdminLTE CSS/JS -->


                            <div id="accordion">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h4 class="card-title w-100">
                                            <a class="d-block w-100 font-weight-bold" data-toggle="collapse" href="#collapseOne">
                                                1.- INFORMACI&Oacute;N DE LA SOLICITUD
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="form-row">

                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputFecha">Fecha Actual:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                        </div>
                                                        <?php
                                                        date_default_timezone_set('America/Santiago');
                                                        ?>
                                                        <input type="datetime" readonly="readonly" id="fechaActualOrden" name="fechaActualOrden" class="form-control" value="<?php echo date('d-m-Y H:i'); ?>"/>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputQuienSolicita">Persona Solicitante:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                        </div>
                                                        <input type="text" readonly="readonly" style="text-transform:capitalize" class="form-control" id="nomSolicitante" name="nomSolicitante" value="<?php echo $nomSolicitante ?>" placeholder="Persona Solicitante">
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputQuienSolicita">Departamento Solicitante:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                        </div>
                                                        <input type="text" readonly="readonly" style="text-transform:capitalize" class="form-control" id="deptoSolicitante" name="deptoSolicitante" value="<?php echo $ultima_deptoSolicitante ?>" placeholder="Departamento Solicitante">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-lg-4 col-md-6">
                                                    <label for="exampleInputRounded0">Departamento Responsable:
                                                        <code>*</code></label>
                                                    <div class="input-group mb-2">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text">
                                                                <span class="fas fa-building"></span>
                                                            </div>
                                                        </div>
                                                        <input type="text" id="departamentoResponsable" readonly="readonly" required name="departamentoResponsable" class="form-control" placeholder="Departamento Responsable" value="<?php echo $deptoResponsable; ?>">

                                                    </div>
                                                </div>
                                                <div class="form-group col-lg-4 col-md-6">
                                                    <label for="exampleInputRounded0">Tipo de Problema:
                                                        <code>*</code></label>
                                                    <div class="input-group mb-2">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text">
                                                                <span class="fas fa-building"></span>
                                                            </div>
                                                        </div>
                                                        <input type="text" id="tipoObservacion" readonly="readonly" required name="tipoObservacion" class="form-control" placeholder="Tipo de Problema" value="<?php echo $tipoProblemaSolicitud; ?>">

                                                    </div>
                                                </div>

                                                <div class="form-group col-lg-4 col-md-6">
                                                    <label for="exampleInputRounded0">Ubicaci&oacute;n donde se encuentra el Problema (PMA):
                                                        <code>*</code></label>
                                                    <div class="input-group mb-2">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text">
                                                                <span class="fas fa-map"></span>
                                                            </div>
                                                        </div>
                                                        <input type="text" id="pmaSolicitud" readonly="readonly" required name="pmaSolicitud" class="form-control" placeholder="Ubicaci&oacute;n donde se encuentra el Problema (PMA) (Ejemplo: A-1-1)" value="<?php echo $ubicacionSolicitud; ?>">
                                                    </div>
                                                </div>

                                            </div>


                                            <div class="form-group">
                                                <label for="exampleInputRounded0">Detalle de Problema:
                                                    <code>*</code></label>
                                                <div class="input-group mb-2">
                                                    <textarea id="detalleSolicitud" readonly="readonly" style="resize:none;" name="detalleSolicitud" class="form-control" rows="5" placeholder="Detalle del Problema que Presenta..."><?php echo $problemaSolicitud; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-danger">
                                    <div class="card-header">
                                        <h4 class="card-title w-100">
                                            <a class="d-block w-100 font-weight-bold" data-toggle="collapse" href="#collapseTwo">
                                                2.- CONCLUSI&Oacute;N DE LA SOLICITUD
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo" class="collapse show" data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label>Inicio Fecha/Hora:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                        </div>
                                                        <input type="datetime-local" required style="text-transform:capitalize" class="form-control" id="horarioInicio" name="horarioInicio" value="">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Termino Fecha/Hora:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                        </div>
                                                        <input type="datetime-local" required style="text-transform:capitalize" class="form-control" id="horarioTermino" name="horarioTermino" value="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="noOrdenTrabajo">N° Orden de Trabajo:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-file"></i></span>
                                                        </div>
                                                        <input type="text" list="noOrdenTrabajos" style="text-transform:capitalize"
                                                                class="form-control" id="noOrdenTrabajo"
                                                                name="noOrdenTrabajo" value=""
                                                                placeholder="Escriba el N° de Orden de Trabajo">
                                                        <datalist id="noOrdenTrabajos">
                                                            <?php
                                                            $sqlOrden = "SELECT * FROM trabajos_general WHERE tecnico1 = '$nomUsuario' AND estadoTG != 'Trabajo Terminado'";
                                                            $resOrden = mysqli_query($conexion, $sqlOrden);
                                                            while ($rowOrden = $resOrden->fetch_assoc()) {
                                                                $folioTG = $rowOrden['folioTG'];
                                                                $turnoTG = $rowOrden['turnoTG'];
                                                                $fechaProgramacion = $rowOrden['fechaProgramacion'];
                                                                ?>
                                                                <option value="<?php echo $folioTG ?>"><?php echo $turnoTG ?> / <?php echo $fechaProgramacion ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </datalist>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputRounded0">Tipo de Matención Realizada:
                                                        <code>*</code></label>
                                                    <div class="input-group mb-2">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text">
                                                                <span class="fas fa-map"></span>
                                                            </div>
                                                        </div>
                                                        <select name="tipoMantencion" class="form-control" required id="tipoMantencion">
                                                            <option value="" disabled selected>-- Seleccione --</option>
                                                            <option value="MPE">MPE - Mantenimiento Preventivo Externo</option>
                                                            <option value="MRE">MRE - Mantenimiento Reparativo Externo</option>
                                                            <option value="MPI">MPI - Mantenimiento Preventivo Interno</option>
                                                            <option value="MRI">MRI - Mantenimiento Reparativo Interno</option>
                                                            <option value="OM">OM - Obras Menores</option>
                                                            <option value="IS">IS - Inspección/Supervisión</option>
                                                            <?php
                                                            if ($idDepartamento == 1) {
                                                                ?>
                                                                <option value="MS">MS - Mejoras del Sistema</option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputTurno">Unidad o Servicio:
                                                        <code>*</code></label>
                                                    <div class="input-group mb-2">
                                                        <select name="servicios" id="servicios" class="form-control select2" required>
                                                            <option value="" disabled selected>Seleccione un servicio</option>
                                                            <?php
                                                            $sqlServicio = "SELECT idServicio, nomServicio FROM servicios ORDER BY nomServicio ASC";
                                                            $resServicio = mysqli_query($conexion, $sqlServicio);
                                                            while ($rowServicio = $resServicio->fetch_assoc()) {
                                                                $idServicio = $rowServicio['idServicio'];
                                                                $nomServicio = $rowServicio['nomServicio'];
                                                                ?>
                                                                <option value="<?php echo $idServicio ?>"><?php echo $nomServicio ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                            <option value="Otro">Otro</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div name="visualizarNomServicio" id="visualizarNomServicio" class="form-group col-md-6">

                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputTurno">Recinto:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-server"></i></span>
                                                        </div>
                                                        <input type="text" id="recintoOrden" class="form-control" name="recintoOrden">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputRounded0">Conclusi&oacute;n del Problema: </label>
                                                <div class="input-group mb-2">
                                                    <textarea id="conclusionProblema" style="resize:none;" name="conclusionProblema" class="form-control" rows="8" placeholder="Conclusión del Problema."></textarea>
                                                </div>
                                            </div>

                                            <!-- AQUI VAN MATERIALES -->
                                            <br><br>

                                            <div class="form-group text-right">

                                                <button type="button" class="bg-primary btn" id="agregarFila"><i class="fas fa-plus"></i>&nbsp;&nbsp;Agregar Material</button>
                                            </div>

                                            <br><br>

                                            <div class="table-responsive">
                                                <table id="miTabla" class="table table-bordered" style="font-size: 12px;">
                                                    <thead class="bg-secondary">
                                                    <tr>
                                                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">NOMBRE MATERIAL:</td>
                                                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">CANTIDAD:</td>
                                                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">MEDIDA:</td>
                                                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">ACCIONES:</td>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="miTbody">
                                                    <tr>
                                                        <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                            <input type="text" class="form-control" name="articuloSC1" list="opcionesArticulo1" oninput="limpiarMedida(this)" onchange="cambiarUnidad(this)" placeholder="Articulo">
                                                            <datalist id="opcionesArticulo1">
                                                                <?php echo listadoDataListInventarioHT(); ?>
                                                            </datalist>
                                                            <input type="hidden" class="form-control" name="idInventarioAgregar1" id="idInventarioAgregar1" value="0">

                                                        </td>
                                                        <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                            <input type="number" min="1" class="form-control" id="cantidadSC1" name="cantidadSC1" placeholder="Cantidad">
                                                            <input type="hidden" class="form-control" name="valorUnit1" id="valorUnit1" value="0">
                                                        </td>

                                                        <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                            <input type="text" min="1" class="form-control" id="medidaSC1" name="medidaSC1" placeholder="Medida">
                                                        </td>
                                                        <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                            No se puede eliminar
                                                        </td>
                                                    </tr>

                                                    </tbody>
                                                </table>

                                                <input type="hidden" name="cantidadFilas" id="cantidadFilas" value="1"/>
                                            </div>

                                            <br>
                                            <br>

                                            <!-- FIN MATERIALES -->


                                            <script>
                                                const tabla = document.getElementById('miTabla');
                                                const btnAgregarFila = document.getElementById('agregarFila');
                                                let correlativo = 2;

                                                var tbody = document.getElementById('miTbody');

                                                btnAgregarFila.addEventListener('click', () => {
                                                    contenido = '';
                                                    const nuevaFila = document.createElement('tr');
                                                    contenido += '<td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">' +
                                                        '<input type="text" class="form-control" oninput="limpiarMedida(this)" onchange="cambiarUnidad(this)" name="articuloSC' + correlativo + '" list="opcionesArticulo' + correlativo + '" placeholder="Articulo">'+
                                                        '<datalist id="opcionesArticulo' + correlativo + '"><?php echo listadoDataListInventarioHT(); ?></datalist>'+
                                                        '<input type="hidden" class="form-control" name="idInventarioAgregar' + correlativo + '" id="idInventarioAgregar' + correlativo + '" value="0">' +
                                                        '</td>';

                                                    contenido += '<td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="number" min="1" class="form-control" id="cantidadSC' + correlativo + '" name="cantidadSC' + correlativo + '" placeholder="Cantidad"><input type="hidden" class="form-control" name="valorUnit' + correlativo + '" id="valorUnit' + correlativo + '" value="0"></td>';

                                                    contenido += '<td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="text" min="1" class="form-control" id="medidaSC' + correlativo + '" name="medidaSC' + correlativo + '" placeholder="Medida"></td>';

                                                    contenido += '<td style="font-size: 12px; white-space: nowrap; vertical-align: middle; text-align: center;"><button onclick="eliminarFila(this)" class="btn btn-block btn-danger"><span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>Eliminar</button></td>';

                                                    nuevaFila.innerHTML = contenido;

                                                    tabla.querySelector('tbody').appendChild(nuevaFila);
                                                    correlativo++; // Incrementar el contador

                                                    document.getElementById('cantidadFilas').value = tbody.getElementsByTagName('tr').length; // Actualizar la cantidad de filas en un campo oculto
                                                });
                                                function limpiarMedida(input) {
                                                    const selectedOption = input.value;
                                                    const dataList = input.list;
                                                    const options = dataList.options;

                                                    // Obtener el número de correlativo del id del input
                                                    const correlativo = input.name.replace('articuloSC', '');

                                                    const idInvAgregar = document.getElementById('idInventarioAgregar' + correlativo);

                                                    const medidaInput = document.getElementById('medidaSC' + correlativo);
                                                    const valorUnitInput = document.getElementById('valorUnit' + correlativo);

                                                    const cantidadInput = document.getElementById('cantidadSC' + correlativo);

                                                    // Verificar si el valor ingresado coincide con algún valor del datalist
                                                    let coincide = false;
                                                    for (let i = 0; i < options.length; i++) {
                                                        if (options[i].text === selectedOption) {
                                                            coincide = true;
                                                            break;
                                                        }
                                                    }

                                                    // Si no coincide, limpiar el valor del input medidaSC
                                                    if (!coincide) {
                                                        medidaInput.value = '';
                                                        valorUnitInput.value = '';
                                                        cantidadInput.value = '';
                                                        idInvAgregar.value = '0';
                                                    }
                                                }

                                                function cambiarUnidad(input) {
                                                    const selectedOption = input.value;
                                                    const dataList = input.list;
                                                    const options = dataList.options;

                                                    // Obtener el número de correlativo del id del input
                                                    const correlativo = input.name.replace('articuloSC', '');

                                                    const medidaInput = document.getElementById('medidaSC' + correlativo);

                                                    const valorUnitInput = document.getElementById('valorUnit' + correlativo);

                                                    const cantidadInput = document.getElementById('cantidadSC' + correlativo);

                                                    const idInvAgregar = document.getElementById('idInventarioAgregar' + correlativo);

                                                    // Buscar la opción seleccionada y obtener su unidad
                                                    for (let i = 0; i < options.length; i++) {
                                                        if (options[i].text === selectedOption) {
                                                            const unidad = options[i].getAttribute('data-unidad');
                                                            const valorSin = options[i].getAttribute('data-valorSin');
                                                            const cantidad = parseInt(options[i].getAttribute('data-cantidad'), 10); // Convertir a número
                                                            const invAgregar = options[i].getAttribute('data-idInvAgregar');
                                                            medidaInput.value = unidad; // Asignar la unidad al input de medida correspondiente
                                                            valorUnitInput.value = valorSin;
                                                            idInvAgregar.value = invAgregar;
                                                            if(cantidad === 0){
                                                                Swal.fire('Producto Sin Stock.', '', 'warning');
                                                            }else if(cantidad < 0){
                                                                Swal.fire('Producto con Cupo Negativo.', '', 'error');
                                                            }
                                                            break;
                                                        }
                                                    }


                                                    // Si no coincide, limpiar el valor del input medidaSC
                                                    if (!coincide) {
                                                        medidaInput.value = '';
                                                        valorUnitInput.value = '';
                                                    }
                                                }

                                                function calcularSuma(input) {
                                                    const selectedOption = input.value;

                                                    // Obtener el número de correlativo del id del input
                                                    const correlativo = input.name.replace('cantidadSC', '');

                                                    const valorSC = document.getElementById('valorSC' + correlativo);

                                                    const valorUnitInput = document.getElementById('valorUnit' + correlativo).value;

                                                    const valorTotal = selectedOption * valorUnitInput;

                                                    var valorFinal = Math.floor(valorTotal * 1.19);

                                                    valorSC.value = valorFinal;
                                                    actualizarValorTotal();
                                                }

                                                function actualizarValorTotal() {
                                                    let valorTotal = 0;

                                                    // Obtener todas las filas de la tabla
                                                    const filas = document.querySelectorAll('#miTabla tbody tr');

                                                    // Iterar sobre cada fila para calcular el valor total
                                                    filas.forEach((fila, index) => {
                                                        const cantidadInput = fila.querySelector('input[name^="cantidadSC"]');
                                                        const valorUnitarioInput = fila.querySelector('input[name^="valorUnit"]');
                                                        const cantidad = parseInt(cantidadInput.value);
                                                        const valorUnitario = parseFloat(valorUnitarioInput.value);

                                                        if (cantidad > 0) {

                                                            valorTotal += cantidad * valorUnitario;
                                                        } else {

                                                            valorTotal += 0;
                                                        }

                                                    });

                                                    // Calcular el valor total final con impuestos
                                                    const valorTotalConImpuestos = Math.floor(valorTotal * 1.19);

                                                    const valorTotalConImpuestos2 = valorTotalConImpuestos.toLocaleString('es-CL', {
                                                        style: 'currency',
                                                        currency: 'CLP'
                                                    });

                                                    // Actualizar el contenido del th con el valor total
                                                    document.getElementById('thValorTotal').textContent = valorTotalConImpuestos2;
                                                }

                                                function eliminarFila(boton) {
                                                    const fila = boton.closest('tr');
                                                    fila.remove();
                                                    // Actualiza los correlativos después de eliminar una fila
                                                    correlativo--;
                                                    // Obtén una referencia al elemento <tbody> por su id

                                                    // Cuenta la cantidad de elementos <tr> dentro del <tbody>
                                                    var cantidadDeFilas = tbody.getElementsByTagName('tr').length;

                                                    if (cantidadDeFilas < 1) {
                                                        document.getElementById("enviarFormulario").disabled = true;
                                                    }
                                                    var cantidadDeFilas2 = tbody.getElementsByTagName('tr').length;

                                                    document.getElementById('cantidadFilas').value = cantidadDeFilas2;
                                                }

                                                function actualizarCorrelativos() {
                                                    const filas = tabla.querySelectorAll('tbody tr');
                                                    filas.forEach((fila, index) => {
                                                        const correlativoCell = fila.querySelector('td:first-child');
                                                        correlativoCell.textContent = index + 1;
                                                    });
                                                }


                                            </script>

                                            <br>


                                            <div class="form-group">
                                                <!-- <label for="customFile">Custom File</label> -->
                                                <label for="exampleInputTareasControlCent">Subir Imagenes: </label><br>
                                                <label for="exampleInputTareasControlCent">Formatos Permitidos: .jpg, .png, .jpeg, .bmp, .tif, .webp, .svg, .jfif</label>
                                                <div class="custom-file">
                                                    <input type="file" max-uploads="40" class="custom-file-input" multiple="" id="archivoImagenOrden[]" name="archivoImagenOrden[]" accept=".jpg, .png, .jpeg, .bmp, .tif, .webp, .svg, .jfif">
                                                    <label class="custom-file-label" for="customFile">Seleccionar Archivo...</label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <!-- <label for="customFile">Custom File</label> -->
                                                <label for="exampleInputTareasControlCent">Subir Archivos: (Maximo 10 Archivos)</label><br>
                                                <label for="exampleInputTareasControlCent">Formatos Permitidos: .pdf, .xlsx(Excel), .docx(Word) </label>
                                                <div class="custom-file">
                                                    <input type="file" max-uploads="10" class="custom-file-input" multiple="" id="archivosOrden[]" name="archivosOrden[]" accept=".pdf, .xlsx, .docx">
                                                    <label class="custom-file-label" for="customFile"><b>Seleccionar Archivo...</b>
                                                    </label>
                                                </div>
                                            </div>

                                            <br>
                                            <br>
                                            <hr>

                                            <!-- BOTÓN QUE ABRE EL MODAL -->
                                            <div class="row col-md-12 col-12 text-center justify-content-center">
                                                <button type="button" class="btn btn-primary text-center mb-5 ml-2" id="botonAddHT" data-toggle="modal" data-target="#modalHojas">
                                                    <i class="fa-solid fas fa-plus"></i>&nbsp;&nbsp;<b>Añadir Equipo a la Hoja Técnica</b>
                                                </button>
                                            </div>

                                            <!-- MODAL CON TABLA -->
                                            <div class="modal fade" id="modalHojas" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalHojasLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-xl">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-primary">
                                                            <h5 class="modal-title" id="modalHojasLabel">Escoja el equipamiento</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table id="hoja_tecnicas" class="table table-sm table-hover table-bordered w-100 text-center">
                                                                <thead class="thead-dark">
                                                                <tr>
                                                                    <th class="text-center">N°</th>
                                                                    <th class="text-center">Fecha Registro</th>
                                                                    <th class="text-center">Nombre del Equipamiento</th>
                                                                    <th class="text-center">Marca</th>
                                                                    <th class="text-center">Recinto</th>
                                                                    <th class="text-center">Servicio</th>
                                                                    <th class="text-center">PMA</th>
                                                                    <th class="text-center">Seleccionar</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody></tbody>
                                                            </table>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;&nbsp;Cerrar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                $(document).ready(function () {
                                                    let tablaEquiposInicializada = false;

                                                    $('#modalHojas').on('shown.bs.modal', function () {
                                                        if (!tablaEquiposInicializada) {
                                                            $('#hoja_tecnicas').DataTable({
                                                                paging: true,
                                                                iDisplayLength: 10,
                                                                lengthChange: true,
                                                                searching: true,
                                                                ordering: false,
                                                                info: true,
                                                                autoWidth: false,
                                                                responsive: true,
                                                                ajax: {
                                                                    url: '/sdo-includes/hojas_tecnicas_cargar_equipos.php',
                                                                    type: 'POST',
                                                                    data: {
                                                                        idDepartamento: <?php echo $idDepartamento; ?>
                                                                    },
                                                                    dataSrc: 'data'
                                                                },
                                                                columns: [
                                                                    { data: 'num', className: 'text-center align-middle' },
                                                                    { data: 'fechaRegistro', className: 'text-center align-middle' },
                                                                    { data: 'nombreEquipo', className: 'align-middle' },
                                                                    { data: 'marca', className: 'align-middle' },
                                                                    { data: 'recinto', className: 'align-middle' },
                                                                    { data: 'nomServicio', className: 'align-middle' },
                                                                    { data: 'pma', className: 'text-center align-middle' },
                                                                    { data: 'accion', className: 'text-center align-middle' }
                                                                ],
                                                                language: {
                                                                    lengthMenu: "Visualizando _MENU_ Registros por P&aacute;gina",
                                                                    zeroRecords: "No se han encontrado Registros.",
                                                                    info: "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                                                                    infoEmpty: "No existen Registros.",
                                                                    infoFiltered: "(Filtrado desde _MAX_ Registros Totales)",
                                                                    search: "Buscar:",
                                                                    paginate: {
                                                                        previous: "Anterior",
                                                                        next: "Siguiente"
                                                                    }
                                                                }
                                                            });

                                                            tablaEquiposInicializada = true;
                                                        } else {
                                                            $('#hoja_tecnicas').DataTable().ajax.reload();
                                                        }
                                                    });

                                                    $('#modalHojas').on('hidden.bs.modal', function () {
                                                        $('#hoja_tecnicas').DataTable().clear().destroy();
                                                        tablaEquiposInicializada = false;
                                                    });
                                                });
                                            </script>



                                            <fieldset class="border p-3" id="fieldset2" name="fieldset2">

                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label for="exampleInputQuienSolicita">Equipo o Instrumento:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-box"></i></span>
                                                            </div>
                                                            <input type="text" style="text-transform:capitalize" readonly
                                                                    class="form-control" id="nomEquipo" name="nomEquipo"
                                                                    value="" placeholder="Nombre del equipo">
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label for="exampleInputQuienSolicita">Numero Inventario:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-barcode"></i></span>
                                                            </div>
                                                            <input type="text" style="text-transform:capitalize" readonly
                                                                    class="form-control" id="numInventario"
                                                                    name="numInventario" value=""
                                                                    placeholder="Número de inventario del equipo">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label for="exampleInputQuienSolicita">Marca:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-copyright"></i></span>
                                                            </div>
                                                            <input type="text" style="text-transform:capitalize" readonly
                                                                    class="form-control" id="marcaEquipo" name="marcaEquipo"
                                                                    value="" placeholder="Marca del equipo">
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label for="exampleInputQuienSolicita">Modelo:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-copyright"></i></span>
                                                            </div>
                                                            <input type="text" style="text-transform:capitalize" readonly
                                                                    class="form-control" id="modeloEquipo"
                                                                    name="modeloEquipo" value=""
                                                                    placeholder="Modelo del equipo">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label for="exampleInputQuienSolicita">N° de Serie:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-code"></i></span>
                                                            </div>
                                                            <input type="text" style="text-transform:capitalize" readonly
                                                                    class="form-control" id="numSerie" name="numSerie"
                                                                    value="" placeholder="N° de serie del equipo">
                                                        </div>
                                                    </div>
                                            </fieldset>
                                            <hr>

                                            <br>
                                            <br>

                                            <div class="form-group">
                                                <input type="hidden" name="datosFinalizador" id="datosFinalizador" value="<?php echo $soloNombre; ?>">
                                                <input type="hidden" name="idSolicitud" id="idSolicitud" value="<?php echo $idSolicitud; ?>">
                                                <input type="hidden" name="idCategoria" id="idCategoria" value="">
                                                <input type="hidden" name="idEquipo" id="idEquipo" value="">
                                                <input type="hidden" id="idHoja" name="idHoja" value="">
                                                <input type="hidden" id="piso" name="piso" value="">
                                                <input type="hidden" id="idServicio" name="idServicio" value="">
                                                <input type="hidden" id="idDepartamento" name="idDepartamento" value="<?php echo $idDepartamento ?>">
                                                <input type="submit" class="btn btn-block btn-primary" id="finalizarGuardar" name="finalizarGuardar" onclick="cambiarTipo()" value="Guardar"/>
                                            </div>
                                            <div class="form-group">
                                                <a href="solicitudes_detalle.php" class="btn btn-block btn-warning"> Volver </a>
                                            </div>



                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>



                </form>
                <!-- AQUI VA FIN FORM -->
            </div>
            <!-- /.timeline -->

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
<script src="js/dark-mode.js"></script>
<!-- Page specific script -->
<!-- Page specific script -->
<script>
    $(document).ready(function () {
        // Inicializar el select2

        $('.select2').select2({
            theme: 'bootstrap4',
            allowClear: true
        });

    });
</script>

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
                "search": "Buscar :",
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
    function cambiarTipo() {
        const boton = document.querySelector('button[id="finalizarGuardar"]');
        boton.type = 'button';  // Cambia el tipo del botón a "button"
        boton.textContent = 'Enviando...';  // Cambia el texto del botón
        boton.disabled = true;  // Deshabilita el botón para evitar envíos repetidos

        // // Simula un tiempo de carga antes de enviar el formulario
        // setTimeout(function() {
        //     document.getElementById('soporteAgregar').submit();  // Envía el formulario
        // }, 3000); // Simula 3 segundos de tiempo de carga


        document.getElementById('terminarFinalizar').submit();  // Envía el formulario
    }
</script>
<script>
    function obtenerid(id) {
        console.log(id);
        idHoja = document.getElementById('idHoja');
        idHoja.value = id;
        $("#modalHojas").modal("hide");
        Swal.fire(
            'Exito!',
            'Equipo seleccionado correctamente!',
            'success'
        )
        botonAddHT = document.getElementById('botonAddHT');

        botonAddHT.classList.remove('btn-primary');
        botonAddHT.classList.add('btn-danger');
        botonAddHT.innerText = 'Equipo Seleccionado';

        var dataId = id;
        console.log(dataId);
        var elementos = {};
        //AJAX
        $.ajax({
            type: 'POST',
            url: '../sdo-funciones/obtener-datos-equipo.php',
            data: {
                'id': dataId
            },
            success: function (response) {
                console.log(response);
                var datos = JSON.parse(response);

                elementos.nombreEquipo = datos.nombreEquipo;
                elementos.marca = datos.marca;
                elementos.modelo = datos.modelo;
                elementos.numeroInventario = datos.numeroInventario;
                elementos.numeroSerie = datos.numeroSerie;
                elementos.idCategoria = datos.idCategoria;
                elementos.idServicio = datos.idServicio;
                elementos.idDepaPertenece = datos.idDepaPertenece;
                elementos.piso = datos.piso;
                elementos.id = datos.id;

                $('#nomEquipo').val(elementos.nombreEquipo);
                $('#marcaEquipo').val(elementos.marca);
                $('#modeloEquipo').val(elementos.modelo);
                $('#numInventario').val(elementos.numeroInventario);
                $('#numSerie').val(elementos.numeroSerie);
                $('#idCategoria').val(elementos.idCategoria);
                $('#idEquipo').val(elementos.id);
                $('#idServicio').val(elementos.idServicio);
                $('#idDepaPertenece').val(elementos.idDepaPertenece);
                $('#piso').val(elementos.piso);

            }
        })
    }
</script>
</body>

</html>