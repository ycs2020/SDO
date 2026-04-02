<?php

include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');
$nomUsuario = $_SESSION['nomUsuario'];

$now = date('Y-m-d H:i:s');

if (isset($_POST['idFormRegAcceso']) || isset($_SESSION['idFormRegAcceso'])) {

    // Obtener el ID desde POST o SESSION, priorizando POST
    $idFormRegAcceso = isset($_POST['idFormRegAcceso'])
        ? (int)$_POST['idFormRegAcceso']
        : (int)$_SESSION['idFormRegAcceso'];

    if ($idFormRegAcceso > 0) {

        $_SESSION['idFormRegAcceso'] = $idFormRegAcceso;

        $sql = "SELECT * FROM formularios_registro WHERE idFormRegAcceso = $idFormRegAcceso";
        $res = mysqli_query($conexion, $sql);

        if ($res && mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);

            // Asignar valores
            $folioFormRegAcceso = $row['folioFormRegAcceso'];
            $unidadFormRegAcceso = $row['unidadFormRegAcceso'];
            $fechaFormRegAcceso = $row['fechaFormRegAcceso'];
            $fechaRecepcionFormRegAcceso = $row['fechaRecepcionFormRegAcceso'];
            $categoriaFormRegAcceso = $row['categoriaFormRegAcceso'];
            $solicitanteFormRegAcceso = $row['solicitanteFormRegAcceso'];
            $solicitanteFormRegAcceso2 = mb_convert_case($solicitanteFormRegAcceso, MB_CASE_TITLE, "UTF-8");
            $unidadSolicitaFormRegAcceso = $row['unidadSolicitaFormRegAcceso'];
            $unidadSolicitaFormRegAcceso2 = mb_convert_case($unidadSolicitaFormRegAcceso, MB_CASE_TITLE, "UTF-8");
            $emailSolicitaFormRegAcceso = $row['emailSolicitaFormRegAcceso'];
            $emailSolicitaFormRegAcceso2 = mb_convert_case($emailSolicitaFormRegAcceso, MB_CASE_LOWER, "UTF-8");
            $tipoDocumento = $row['tipoDocumento'];
            $numeroDocumento = $row['numeroDocumento'];

            // Fechas en formatos múltiples
            $fechaFormRegAcceso2 = date('d-m-Y H:i', strtotime($fechaFormRegAcceso));
            $fechaFormRegAcceso3 = fechaEspañol2(strtotime($fechaFormRegAcceso));
            $horaFormRegAcceso = date('H:i', strtotime($fechaFormRegAcceso));

            $fechaRecepcionFormRegAcceso2 = date('d-m-Y H:i', strtotime($fechaRecepcionFormRegAcceso));
            $fechaRecepcionFormRegAcceso3 = fechaEspañol2(strtotime($fechaRecepcionFormRegAcceso));

        } else {
            // Opcional: manejar caso de ID inválido
            echo "<p class='text-danger'>No se encontró el formulario con ID $idFormRegAcceso.</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Visualizando Formulario</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/jefes-sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">

            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-xl-10 col-lg-9 col-md-8 col-12">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <a style="pointer-events: none; font-size: 13pt; text-transform: uppercase" class="btn font-weight-bold">Solicitud de Accesos - <?php echo $folioFormRegAcceso; ?></b></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                        <div class="card">
                            <div class="card-header bg-danger">
                                <a href="formulariosCCAA_listado.php" class="btn btn-block btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            </div>
                        </div>
                    </div>

                    <?php
                    $alertas = [
                        'exitoObservacion' => ['Observación Agregada con Éxito.', 'success'],
                        'errorObservacion' => ['Error al Agregar una Observación.', 'error'],
                        'exitoFirmaDocumento' => ['Documento Subido con Éxito.', 'success'],
                        'errorFirmaDocumento' => ['Error al Subir Formato Firmado. Reintente.', 'error'],
                        'errorGenerarFormulario' => ['Error al Visualizar Formato. Reintente.', 'error'],
                    ];

                    foreach ($alertas as $key => [$mensaje, $icono]) {
                        if (isset($_GET[$key])) {
                            echo "<script>
        const isDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        Swal.fire({
            title: `" . $mensaje . "`,
            icon: '{$icono}',
            confirmButtonText: 'Cerrar',
            background: isDark ? '#1e1e1e' : '#ffffff',
            color: isDark ? '#f0f0f0' : '#000000',
            iconColor: (isDark 
                        ? ( '{$icono}' === 'success' ? '#00ff99' : '#ff6666' )
                        : ( '{$icono}' === 'success' ? '#28a745' : '#dc3545' ))
        });
        </script>";
                        }
                    }
                    ?>


                    <div class="col-md-8 col-12">
                        <div class="card">
                            <div class="card-body">

                                <?php
                                // Definir array de etiquetas y valores
                                $datosFormulario = [
                                    'FOLIO SOLICITUD:' => $folioFormRegAcceso,
                                    'FECHA SOLICITUD:' => $fechaFormRegAcceso3 . ' a las ' . $horaFormRegAcceso . ' hrs.',
                                    'JEFATURA SOLICITANTE:' => $solicitanteFormRegAcceso2,
                                    'UNIDAD SOLICITANTE:' => $unidadSolicitaFormRegAcceso2,
                                    'CORREO SOLICITANTE:' => $emailSolicitaFormRegAcceso2
                                ];

                                // Agregar solo si está definido y no está vacío
                                if (!empty($fechaRecepcionFormRegAcceso)) {
                                    $datosFormulario['FECHA RECEPCIÓN:'] = $fechaRecepcionFormRegAcceso3;
                                } else {
                                    $datosFormulario['FECHA RECEPCIÓN:'] = '<b style="color: red;">Solicitud No Recepcionada</b>';
                                }
                                ?>

                                <div class="table-responsive">
                                    <table style="font-size: 15px;" class="table table-bordered table-sm">
                                        <tbody>
                                        <?php foreach ($datosFormulario as $label => $valor): ?>
                                            <tr>
                                                <td style="width: 25%;">
                                                    <b><?php echo $label; ?></b>
                                                </td>
                                                <td style="width: 75%;"><?php echo $valor; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <?php
                                        $getCategoriaFormRegAcceso = getCategoriaFormRegAcceso($idFormRegAcceso);
                                        $urlCategoria = "";
                                        $textoCategoria = "Visualizar Solicitud de Acceso";
                                        $disabled = false;

                                        switch ($getCategoriaFormRegAcceso) {
                                            case 'Nuevo':
                                                $urlCategoria = "../sdo-pdf/SDO-Formulario-SolicitudAcceso-Nuevo-Con-Listado.php";
                                                break;
                                            case 'Antiguo':
                                                $urlCategoria = "../sdo-pdf/SDO-Formulario-SolicitudAcceso-Antiguo.php";
                                                break;
                                            default:
                                                $urlCategoria = "";
                                                $textoCategoria = "Visualizar Solicitud de Acceso (Deshabilitado)";
                                                $disabled = true;
                                                break;
                                        }
                                        ?>

                                        <form target="_blank" class="text-center m-0"
                                                action="<?php echo $urlCategoria; ?>" method="post">
                                            <input type="hidden" name="idFormRegAcceso" value="<?php echo $idFormRegAcceso; ?>">
                                            <button type="submit" id="inputMozi"
                                                    class="btn btn-block btn-info"
                                                <?php echo $disabled ? 'disabled' : ''; ?>>
                                                <i class="fas fa-eye"></i>&nbsp;&nbsp;<?php echo $textoCategoria; ?>
                                            </button>
                                        </form>
                                    </div>

                                    <?php if (empty($fechaRecepcionFormRegAcceso)) {

                                        ?>
                                        <div class="col-12 mb-3">
                                            <form action="../sdo-funciones/formulariosCCAA_recepcionPermiso.php"
                                                    method="post" class="d-inline">
                                                <input type="hidden" name="idFormRegAcceso" value="<?php echo $idFormRegAcceso; ?>">
                                                <input type="hidden" name="nomCreador" value="<?php echo $nomUsuario; ?>">
                                                <button type="submit" id="inputMozi"
                                                        class="btn btn-block bg-gradient-success btn-md">
                                                    <i class="fa fa-check"></i>&nbsp;&nbsp;Recepci&oacute;n de Formato
                                                </button>
                                            </form>
                                        </div>
                                    <?php
                                    } else {
                                    if (contarExisteDocumento($idFormRegAcceso) > 0) {
                                    ?>
                                        <div class="col-12 mb-3">
                                            <?php
                                            $documentos = obtenerRutasFormFirma($idFormRegAcceso);

                                            if (count($documentos) === 1) {
                                                $doc = $documentos[0];
                                                ?>
                                                <a href="<?= $doc['ruta'] ?>" target="_blank" class="btn btn-block bg-gradient-purple">
                                                    <i class="fa fa-download"></i>&nbsp;&nbsp;Documento Firmado (<?= $doc['nombre'] ?>)
                                                </a>
                                                <?php
                                            } elseif (count($documentos) > 1) {
                                                ?>
                                                <button type="button" class="btn btn-block bg-gradient-purple" data-toggle="modal" data-target="#modalDescargasMultiples">
                                                    <i class="fa fa-download"></i>&nbsp;&nbsp;Ver Documentos Firmados (<?= count($documentos) ?>)
                                                </button>

                                                <!-- Modal -->
                                                <div class="modal fade" id="modalDescargasMultiples" tabindex="-1" role="dialog" aria-labelledby="tituloModalDescargas" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content border-primary">
                                                            <div class="modal-header bg-purple text-white">
                                                                <h5 class="modal-title" id="tituloModalDescargas">Documentos Firmados Asociados</h5>
                                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <ul class="list-group">
                                                                    <?php foreach ($documentos as $index => $doc) {
                                                                        $fechaDesc = $doc['fecha'];
                                                                        $fechaTime = strtotime($fechaDesc);
                                                                        $fechaDescFin = date('d-m-Y', $fechaTime);
                                                                        ?>
                                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                            <span><i class="fa fa-file-pdf text-danger"></i> <?= $doc['nombre'] . " ($fechaDescFin)" ?></span>
                                                                            <a href="<?= $doc['ruta'] ?>" target="_blank" class="btn btn-sm btn-primary">
                                                                                <i class="fas fa-download"></i>&nbsp;&nbsp;Descargar
                                                                            </a>
                                                                        </li>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </ul>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    <?php

                                    } else {
                                    ?>
                                        <div class="col-12 mb-3">
                                            <!-- Botón para abrir modal -->
                                            <button type="button"
                                                    class="btn btn-block bg-gradient-warning btn-md"
                                                    data-toggle="modal"
                                                    data-target="#modalSubirDocumentoFirmado"
                                                    title="Subir Formato Firmado">
                                                <i class="fa fa-upload"></i>&nbsp;&nbsp;Subir Documento Firmado
                                            </button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="modalSubirDocumentoFirmado" tabindex="-1" role="dialog" aria-labelledby="tituloSubirDocumento" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <form action="../sdo-funciones/formulariosCCAA_subirFirma.php" method="post" enctype="multipart/form-data" class="modal-content">

                                                        <div class="modal-header bg-success text-white">
                                                            <h5 class="modal-title" id="tituloSubirDocumento">Subir Documento Firmado</h5>
                                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <!-- Usuario -->
                                                            <div class="form-group">
                                                                <label for="nomCreador">Usuario Creador:
                                                                    <code>*</code></label>
                                                                <input type="text" class="form-control" id="nomCreador" name="nomCreador" value="<?= $nomUsuario ?>" readonly required>
                                                            </div>

                                                            <!-- Archivo PDF -->
                                                            <div class="form-group">
                                                                <label for="documentoFirmado">Subir Archivo Adjunto (.pdf):</label>
                                                                <div class="custom-file">
                                                                    <input type="file" class="custom-file-input" id="documentoFirmado" name="documentoFirmado" accept=".pdf" required>
                                                                    <label class="custom-file-label" for="documentoFirmado">Seleccionar Archivo...</label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <input type="hidden" name="idFormRegAcceso" value="<?= $idFormRegAcceso ?>">
                                                            <input type="hidden" name="folioFormRegAcceso" value="<?= $folioFormRegAcceso ?>">
                                                            <button type="submit" class="btn btn-primary">Subir</button>
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Script para actualizar nombre del archivo -->
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function () {
                                                const fileInput = document.getElementById('documentoFirmado');
                                                const fileLabel = fileInput.nextElementSibling;

                                                fileInput.addEventListener('change', function () {
                                                    const fileName = this.files.length > 0 ? this.files[0].name : 'Seleccionar Archivo...';
                                                    fileLabel.textContent = fileName;
                                                });
                                            });
                                        </script>

                                        <?php
                                    }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="nav-unidades-acceso col-12">
                        <div class="card">
                            <div class="card-body">

                                <?php
                                $sqlFuncionario = "SELECT 
                                    ff.idUnidad,
                                    fa.unidadFormCCAA
                                FROM formularios_funcionarios ff
                                INNER JOIN formularios_acceso fa 
                                    ON ff.idUnidad = fa.idFormCCAA
                                WHERE ff.idFormRegAcceso = $idFormRegAcceso
                                GROUP BY ff.idUnidad, fa.unidadFormCCAA
                                ORDER BY fa.unidadFormCCAA";
                                $resFuncionario = mysqli_query($conexion, $sqlFuncionario);
                                $firstIdUnidad = null;

                                if ($resFuncionario && mysqli_num_rows($resFuncionario)) {
                                    echo '<ul class="nav nav-tabs bg-primary text-white rounded-top" id="unidadTabs" role="tablist">';
                                    $first = true;
                                    while ($rowFuncionario = mysqli_fetch_assoc($resFuncionario)) {
                                        $idUnidad = $rowFuncionario['idUnidad'];
                                        $unidadFormCCAA = $rowFuncionario['unidadFormCCAA'];

                                        $activeClass = $first ? 'active text-white font-weight-bold' : 'text-white';
                                        echo "
                        <li class='nav-item'>
                            <a class='nav-link-unidad $activeClass' 
                               id='tab-$idUnidad' 
                               data-toggle='tab' 
                               href='#' 
                               data-idunidad='$idUnidad'
                               data-nomunidad='$unidadFormCCAA'>
                               $unidadFormCCAA
                            </a>
                        </li>";

                                        if ($first) {
                                            $firstIdUnidad = $idUnidad;
                                            $firstNombUnidad = $unidadFormCCAA;
                                            $first = false;
                                        }
                                    }
                                    echo '</ul>';

                                } else {
                                    echo '<p class="text-muted">No se encontraron unidades asociadas.</p>';
                                }
                                ?>

                                <div class="mt-3" id="contenidoUnidadSeleccionada">
                                    <p class="text-muted">Seleccione una unidad para ver su contenido.</p>
                                </div>

                            </div>
                        </div>
                    </div>

                    <style>
                        .nav-unidades-acceso .nav-tabs .nav-link-unidad {
                            display: inline-block;
                            min-width: 160px;
                            padding: 8px 15px;
                            font-size: 1rem;
                            font-weight: bold;
                            text-align: center;
                            white-space: nowrap;
                            background-color: #0056b3;
                            border: none;
                            border-radius: 0.25rem 0.25rem 0 0;
                            transition: background-color 0.3s ease;
                        }

                        .nav-unidades-acceso .nav-tabs .nav-link-unidad.active {
                            background-color: #004085 !important;
                            border-color: #004085 #004085 #fff !important;
                        }
                    </style>

                    <script>
                        $(document).ready(function () {
                            // Carga automática del primer tab si existe
                            <?php if ($firstIdUnidad !== null && $firstNombUnidad !== null): ?>
                            cargarContenidoUnidad(<?= $firstIdUnidad ?>, '<?= $firstNombUnidad ?>');
                            <?php endif; ?>

                            // Al hacer clic en un tab
                            $('.nav-link-unidad').on('click', function (e) {
                                e.preventDefault();
                                $('.nav-link-unidad').removeClass('active font-weight-bold');
                                $(this).addClass('active font-weight-bold');

                                const idUnidad = $(this).data('idunidad');
                                const nomUnidad = $(this).data('nomunidad');
                                cargarContenidoUnidad(idUnidad, nomUnidad);
                            });

                            function cargarContenidoUnidad(idUnidad,nomUnidad) {
                                $('#contenidoUnidadSeleccionada').html('<p class="text-info">Cargando contenido...</p>');

                                $.ajax({
                                    url: '/sdo-includes/solicitudes_acceso_funcionarios_permisos.php',
                                    method: 'POST',
                                    data: {
                                        idUnidad: idUnidad,
                                        nomUnidad: nomUnidad,
                                        idFormRegAcceso: <?= $idFormRegAcceso ?>
                                    },
                                    success: function (data) {
                                        $('#contenidoUnidadSeleccionada').html(data);
                                    },
                                    error: function () {
                                        $('#contenidoUnidadSeleccionada').html('<p class="text-danger">Error al cargar la información.</p>');
                                    }
                                });
                            }
                        });
                    </script>



                </div>
            </div>
        </section>
    </div>
</div>
</body>
<script src="js/dark-mode.js"></script>
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
</html>



