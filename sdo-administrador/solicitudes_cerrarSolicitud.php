<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');

if (isset($_SESSION['dataFinalizador'])) {

    //$dataFinalizador = trim($_SESSION['dataFinalizador']);
    $dataFinalizador = $_SESSION['nomUsuario'];

    if (empty($dataFinalizador)) {
        header('Location: solicitudes_recibidas.php?errorCargarSolicitud=true');
    }
} else {
    header('Location: solicitudes_recibidas.php?errorCargarSolicitud=true');
}

if (isset($_POST['idSolicitud'])) {
    $idSolicitud = trim($_POST['idSolicitud']);

    if ($idSolicitud == 0 || empty($idSolicitud)) {
        header('Location: solicitudes_recibidas.php?errorCargarSolicitud=true');
    } else {
        $datosSolicitud = array();
        $datosSolicitud = getSolicitudSoporte($idSolicitud);

        $idSolicitud2 = $datosSolicitud['idSolicitud'];
        $folioSolicitud = $datosSolicitud['folioSolicitud'];
        $aleatorioSolicitud = $datosSolicitud['aleatorioSolicitud'];
        $nomSolicitante = $datosSolicitud['nomSolicitante'];
        $rutSolicitante = $datosSolicitud['rutSolicitante'];
        $deptoSolicitante = $datosSolicitud['deptoSolicitante'];

        $deptoSolicitante = explode('/', $deptoSolicitante);

        $longitud_deptoSolicitante = count($deptoSolicitante);

        $ultima_deptoSolicitante = $deptoSolicitante[$longitud_deptoSolicitante - 1];

        $ultima_deptoSolicitante = mb_strtoupper($ultima_deptoSolicitante);

        $emailSolicitante = $datosSolicitud['emailSolicitante'];
        $telefonoSolicitante = $datosSolicitud['telefonoSolicitante'];
        $ubicacionSolicitud = $datosSolicitud['ubicacionSolicitud'];
        $deptoResponsable = $datosSolicitud['deptoResponsable'];
        $tipoProblemaSolicitud = $datosSolicitud['tipoProblemaSolicitud'];
        $problemaSolicitud = $datosSolicitud['problemaSolicitud'];
        $inicioSolicitud = $datosSolicitud['inicioSolicitud'];
        $terminoSolicitud = $datosSolicitud['terminoSolicitud'];
        $situacionSolicitud = $datosSolicitud['situacionSolicitud'];
        $tecnicoSolicitud = $datosSolicitud['tecnicoSolicitud'];
        $canceladaSolicitud = $datosSolicitud['canceladaSolicitud'];

        $fechaInicio = strtotime($inicioSolicitud);
        $fechaInicio = fechaEspañol2($fechaInicio);

        $horaInicio = date('H:i', strtotime($inicioSolicitud));

        $_SESSION['idSolicitud'] = $idSolicitud;
    }
} else if (isset($_SESSION['idSolicitud'])) {
    $idSolicitud = trim($_SESSION['idSolicitud']);

    if ($idSolicitud == 0 || empty($idSolicitud)) {
        header('Location: solicitudes_recibidas.php?errorCargarSolicitud=true');
    } else {
        $datosSolicitud = array();
        $datosSolicitud = getSolicitudSoporte($idSolicitud);

        $idSolicitud2 = $datosSolicitud['idSolicitud'];
        $folioSolicitud = $datosSolicitud['folioSolicitud'];
        $aleatorioSolicitud = $datosSolicitud['aleatorioSolicitud'];
        $nomSolicitante = $datosSolicitud['nomSolicitante'];
        $rutSolicitante = $datosSolicitud['rutSolicitante'];
        $deptoSolicitante = $datosSolicitud['deptoSolicitante'];

        $deptoSolicitante = explode('/', $deptoSolicitante);

        $longitud_deptoSolicitante = count($deptoSolicitante);

        $ultima_deptoSolicitante = $deptoSolicitante[$longitud_deptoSolicitante - 1];

        $ultima_deptoSolicitante = mb_strtoupper($ultima_deptoSolicitante);

        $emailSolicitante = $datosSolicitud['emailSolicitante'];
        $telefonoSolicitante = $datosSolicitud['telefonoSolicitante'];
        $ubicacionSolicitud = $datosSolicitud['ubicacionSolicitud'];
        $deptoResponsable = $datosSolicitud['deptoResponsable'];
        $tipoProblemaSolicitud = $datosSolicitud['tipoProblemaSolicitud'];
        $problemaSolicitud = $datosSolicitud['problemaSolicitud'];
        $inicioSolicitud = $datosSolicitud['inicioSolicitud'];
        $terminoSolicitud = $datosSolicitud['terminoSolicitud'];
        $situacionSolicitud = $datosSolicitud['situacionSolicitud'];
        $tecnicoSolicitud = $datosSolicitud['tecnicoSolicitud'];
        $canceladaSolicitud = $datosSolicitud['canceladaSolicitud'];

        $fechaInicio = strtotime($inicioSolicitud);
        $fechaInicio = fechaEspañol2($fechaInicio);

        $horaInicio = date('H:i', strtotime($inicioSolicitud));
    }
} else {
    header('Location: solicitudes_recibidas.php?errorCargarSolicitud=true');
}
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
    <script src="js/dark-mode.js"></script>
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            cargarTipoObservacion();

            $('#departamentoNuevoResponsable').change(function() {
                cargarTipoObservacion();
            });


            function cargarTipoObservacion() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-solicitud/cargarTipoSolicitud.php",
                    data: "idDepartamento=" + $('#departamentoNuevoResponsable').val(),
                    success: function(r) {
                        $('#dataTipoSolicitud').html(r);


                    }
                });

            };

        });
    </script>
</head>

<body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        include '../sdo-templates/admin-sidebar.php';
        ?>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h4>Administrar Solicitud de Soporte</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <?php
            if (isset($_GET['exitoAsignarPersonal'])) {
            ?>
                <script>
                    Swal.fire(
                        'Asignaci&oacute;n de Personal con &Eacute;xito.',
                        '',
                        'success'
                    )
                </script>
            <?php
            }

            if (isset($_GET['errorAsignarPersonal'])) {
            ?>
                <script>
                    Swal.fire(
                        '¡ Ha ocurrido un Problema !',
                        'Error al Asignar Personal a la Solicitud.',
                        'error'
                    )
                </script>
            <?php
            }

            if (isset($_GET['exitoCambiarDepartamento'])) {
            ?>
                <script>
                    Swal.fire(
                        'Cambio de Departamento Responsable con &Eacute;xito.',
                        '',
                        'success'
                    )
                </script>
            <?php
            }

            if (isset($_GET['errorCambiarDepartamento'])) {
            ?>
                <script>
                    Swal.fire(
                        '¡ Ha ocurrido un Problema !',
                        'Error al Cambiar Departamento Responsable.',
                        'error'
                    )
                </script>
            <?php
            }

            if (isset($_GET['exitoCancelarSolicitud'])) {
            ?>
                <script>
                    Swal.fire(
                        'Solicitud Cancelada con &Eacute;xito.',
                        '',
                        'success'
                    )
                </script>
            <?php
            }

            if (isset($_GET['errorCancelarSolicitud'])) {
            ?>
                <script>
                    Swal.fire(
                        '¡ Ha ocurrido un Problema !',
                        'Error al Cancelar Solicitud. Reintente.',
                        'error'
                    )
                </script>
            <?php
            }

            if (isset($_GET['exitoAgregarObservacion'])) {
            ?>
                <script>
                    Swal.fire(
                        'Observaci&oacute;n Agregada con &Eacute;xito.',
                        '',
                        'success'
                    )
                </script>
            <?php
            }

            if (isset($_GET['errorAgregarObservacion'])) {
            ?>
                <script>
                    Swal.fire(
                        '¡ Ha ocurrido un Problema !',
                        'Error al Agregar Observaci&oacute;n. Reintente.',
                        'error'
                    )
                </script>
            <?php
            }
            ?>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <!-- Timelime example  -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <a href="solicitudes_detalle.php" style="float: right;" class="btn btn-info"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>

                                    <br>
                                    <br>
                                    <br>
                                    <h4 align="justify"><b>Finalizando Solicitud N° : </b><?php echo $folioSolicitud; ?></h4>
                                </div>
                                <div class="card-body">

                                    <form action="../sdo-funciones/solicitudes_finalizar.php" method="post" enctype="multipart/form-data" id="terminarFinalizar"  name="terminarFinalizar">

                                        <h4 class="card-title">
                                            <b>
                                                <u>Informaci&oacute;n de la Solicitud</u>
                                            </b>
                                        </h4>

                                        <br>

                                        <br>

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
                                                    <input type="datetime" readonly="readonly" id="fechaActualOrden" name="fechaActualOrden" class="form-control" value="<?php echo date('d-m-Y H:i'); ?>" />

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
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputRounded0">Tipo de Ayuda que necesita: <code>*</code></label>
                                                <div class="input-group mb-2">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <span class="fas fa-building"></span>
                                                        </div>
                                                    </div>
                                                    <input type="text" id="departamentoResponsable" readonly="readonly" required name="departamentoResponsable" class="form-control" placeholder="Departamento Responsable" value="<?php echo $deptoResponsable; ?>">

                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputRounded0">Tipo de Problema: <code>*</code></label>
                                                <div class="input-group mb-2">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <span class="fas fa-building"></span>
                                                        </div>
                                                    </div>
                                                    <input type="text" id="tipoObservacion" readonly="readonly" required name="tipoObservacion" class="form-control" placeholder="Tipo de Problema" value="<?php echo $tipoProblemaSolicitud; ?>">

                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputRounded0">Ubicaci&oacute;n donde se encuentra el Problema (PMA): <code>*</code></label>
                                            <div class="input-group mb-2">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-map"></span>
                                                    </div>
                                                </div>
                                                <input type="text" id="pmaSolicitud" readonly="readonly" required name="pmaSolicitud" class="form-control" placeholder="Ubicaci&oacute;n donde se encuentra el Problema (PMA) (Ejemplo: A-1-1)" value="<?php echo $ubicacionSolicitud; ?>">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputRounded0">Detalle de Problema: <code>*</code></label>
                                            <div class="input-group mb-2">
                                                <textarea id="detalleSolicitud" readonly="readonly" style="resize:none;" name="detalleSolicitud" class="form-control" rows="8" placeholder="Detalle del Problema que Presenta..."><?php echo $problemaSolicitud; ?></textarea>
                                            </div>
                                        </div>

                                        <hr>

                                        <br>

                                        <h5 class="card-title">
                                            <b>
                                                <u>Conclusi&oacute;n de la Solicitud</u>
                                            </b>
                                        </h5>

                                        <br>

                                        <br>

                                        <div class="form-group">
                                            <label for="exampleInputRounded0">Tipo de Matención Realizada: <code>*</code></label>
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
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputRounded0">Conclusi&oacute;n del Problema: </label>
                                            <div class="input-group mb-2">
                                                <textarea id="conclusionProblema" style="resize:none;" name="conclusionProblema" class="form-control" rows="8" placeholder="Conclusión del Problema."></textarea>
                                            </div>
                                        </div>

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

                                        <br>
                                        <br>


                                        <hr>

                                        <br>
                                        <br>

                                        <div class="form-group">

                                            <input type="hidden" name="datosFinalizador" id="datosFinalizador" value="<?php echo $dataFinalizador; ?>">
                                            <input type="hidden" name="idSolicitud" id="idSolicitud" value="<?php echo $idSolicitud; ?>">
                                            <input type="submit" class="btn btn-block btn-primary" id="finalizarGuardar" name="finalizarGuardar" onclick="cambiarTipo()" value="Guardar" />

                                        </div>
                                        <div class="form-group">
                                            <a href="index.php" class="btn btn-block btn-warning"> Volver </a>
                                        </div>


                                    </form>

                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
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
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>
    <!-- Page specific script -->
    <script>
        $(function() {
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
</body>

</html>