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

    if (isset($_POST['idFormRegAcceso'])) {
        $idFormRegAcceso = $_POST['idFormRegAcceso'];

    } else if (isset($_SESSION['idFormRegAcceso'])) {
        $idFormRegAcceso = $_SESSION['idFormRegAcceso'];
    }

    if ($idFormRegAcceso > 0) {

        $sql = "SELECT * FROM formularios_registro WHERE idFormRegAcceso = $idFormRegAcceso";
        $res = mysqli_query($conexion,$sql);
        while($row = $res->fetch_assoc()){
            $folioFormRegAcceso = $row['folioFormRegAcceso'];
            $unidadFormRegAcceso = $row['unidadFormRegAcceso'];
            $fechaFormRegAcceso = $row['fechaFormRegAcceso'];
            $fechaRecepcionFormRegAcceso = $row['fechaRecepcionFormRegAcceso'];
            $categoriaFormRegAcceso = $row['categoriaFormRegAcceso'];
            $solicitanteFormRegAcceso = $row['solicitanteFormRegAcceso'];
            $unidadSolicitaFormRegAcceso = $row['unidadSolicitaFormRegAcceso'];
            $emailSolicitaFormRegAcceso = $row['emailSolicitaFormRegAcceso'];
            $tipoDocumento = $row['tipoDocumento'];
            $numeroDocumento = $row['numeroDocumento'];
            $fechaFormRegAcceso2 = date('d-m-Y H:i', strtotime($fechaFormRegAcceso));
            $fechaFormRegAcceso3 = strtotime($fechaFormRegAcceso);
            $fechaFormRegAcceso3 = fechaEspañol2($fechaFormRegAcceso3);

            $horaFormRegAcceso = date('H:i', strtotime($fechaFormRegAcceso));

            $fechaRecepcionFormRegAcceso2 = date('d-m-Y H:i', strtotime($fechaRecepcionFormRegAcceso));
            $fechaRecepcionFormRegAcceso3 = strtotime($fechaRecepcionFormRegAcceso);
            $fechaRecepcionFormRegAcceso3 = fechaEspañol2($fechaRecepcionFormRegAcceso3);
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
    include '../sdo-templates/user-sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Visualizando Formulario</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <a href="javascript:history.back()" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br><br>
                                <h4><b>Solicitud de Accesos - <?php echo $folioFormRegAcceso; ?></b></h4>
                                <br>
                                <h5><b>Jefatura Solicitante: </b><?php echo $solicitanteFormRegAcceso; ?></h5>
                                <h5><b>Unidad Solicitante: </b><?php echo $unidadSolicitaFormRegAcceso; ?></h5>
                                <h5><b>Correo Solicitante: </b><?php echo $emailSolicitaFormRegAcceso; ?></h5>
                                <h5><b>Fecha Solicitud: </b><?php echo $fechaFormRegAcceso3 . ' a las ' . $horaFormRegAcceso . ' hrs.'; ?></h5>
                                <?php
                                if (!empty($fechaRecepcionFormRegAcceso)) {
                                    ?>
                                    <br>
                                    <h5><b>Fecha Recepci&oacute;n: </b><?php echo $fechaRecepcionFormRegAcceso3 . ' a las ' . $horaRecepcionFormRegAcceso . ' hrs.'; ?></h5>
                                    <?php
                                }
                                ?>
                                <br>
                                <?php

                                if (isset($_GET['exitoObservacion'])) {
                                    ?>
                                    <script>
                                        Swal.fire({
                                            title: 'Observación Agregada con &Eacute;xito.',
                                            confirmButtonText: 'Cerrar',
                                            icon: 'success',
                                        });
                                    </script>
                                    <?php
                                }

                                if (isset($_GET['errorObservacion'])) {
                                    ?>
                                    <script>
                                        Swal.fire({
                                            title: 'Error al Agregar una Observaci&oacute;n.',
                                            confirmButtonText: 'Cerrar',
                                            icon: 'error',
                                        });
                                    </script>
                                    <?php
                                }

                                if (isset($_GET['exitoFirmaDocumento'])) {
                                    ?>
                                    <script>
                                        Swal.fire({
                                            title: 'Documento Subido con &Eacute;xito.',
                                            confirmButtonText: 'Cerrar',
                                            icon: 'success',
                                        });
                                    </script>
                                    <?php
                                }

                                if (isset($_GET['errorFirmaDocumento'])) {
                                    ?>
                                    <script>
                                        Swal.fire({
                                            title: 'Error al Subir Formato Firmado. Reintente.',
                                            confirmButtonText: 'Cerrar',
                                            icon: 'error',
                                        });
                                    </script>
                                    <?php
                                }
                                if (isset($_GET['errorGenerarFormulario'])) {
                                    ?>
                                    <script>
                                        Swal.fire({
                                            title: 'Error al Visualizar Formato. Reintente.',
                                            confirmButtonText: 'Cerrar',
                                            icon: 'error',
                                        });
                                    </script>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="card-body">
                                <?php
                                $getCategoriaFormRegAcceso = getCategoriaFormRegAcceso($idFormRegAcceso);
                                if ($getCategoriaFormRegAcceso == 'Nuevo') {
                                    ?>
                                    <div class="row text-center" style="display: flex; justify-content:center;">
                                        <div class="col-lg-3 col-12">
                                            <form style=" display: inline" target="_blank" class="" action="../sdo-pdf/SDO-Formulario-SolicitudAcceso-Nuevo-Con-Listado.php" method="post">

                                                <input type="hidden" name="idFormRegAcceso" value="<?php echo $idFormRegAcceso; ?>">
                                                <button type="submit" id="inputMozi" class="btn btn-block bg-gradient-primary btn-md"><i class="fa fa-eye"></i>&nbsp;&nbsp;Visualizar Formato de Acceso</button>

                                            </form>

                                        </div>
                                    </div>
                                    <hr>
                                    <?php
                                }else if ($getCategoriaFormRegAcceso == 'Antiguo'){
                                    ?>
                                    <div class="row text-center" style="display: flex; justify-content:center;">
                                        <div class="col-lg-3 col-12">
                                            <form style=" display: inline" target="_blank" class="" action="../sdo-pdf/SDO-Formulario-SolicitudAcceso-Antiguo.php" method="post">

                                                <input type="hidden" name="idFormRegAcceso" value="<?php echo $idFormRegAcceso; ?>">
                                                <button type="submit" id="inputMozi" class="btn btn-block bg-gradient-primary btn-md"><i class="fa fa-eye"></i>&nbsp;&nbsp;Visualizar Formato de Acceso</button>

                                            </form>

                                        </div>
                                        &nbsp;&nbsp;
                                        <?php
                                        $contarFormulariosGuardados = contarFormulariosGuardados($idFormRegAcceso);
                                        if ($contarFormulariosGuardados > 0) {
                                            ?>

                                            <div class="col-lg-3 col-12">

                                                <?php
                                                $obtenerRutaFormFirma = obtenerRutaFormFirma($idFormRegAcceso);

                                                if (!empty($obtenerRutaFormFirma)) {
                                                    ?>
                                                    <a href="<?php echo $obtenerRutaFormFirma; ?>" target="_blank" class="btn btn-block bg-gradient-purple"><i class="fa fa-download"></i>&nbsp;&nbsp;Descargar Documento Firmado</a>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <a href="#" style="opacity: 0.5; pointer-events: none; text-decoration: none;" target="_blank" class="btn btn-block bg-gradient-purple">Error al Cargar Archivo. Contacte a Soporte.</a>
                                                    <?php
                                                }
                                                ?>


                                            </div>
                                            <?php
                                        } else {
                                            ?>

                                            <div class="col-lg-3 col-12">
                                                <form style=" display: inline" class="" action="../sdo-funciones/formulariosCCAA_subirFirma.php" method="post" enctype="multipart/form-data">

                                                    <input type="hidden" name="idFormRegAcceso" value="<?php echo $idFormRegAcceso; ?>">
                                                    <button type="button" title="Subir Formato Firmado" data-toggle="modal" data-target="#subirFormatoFirmado" class="btn btn-block bg-gradient-warning btn-md"><i class="fa fa-upload"></i>&nbsp;&nbsp;Subir Documento Firmado</button>
                                                    <div class="modal fade" id="subirFormatoFirmado" name="subirFormatoFirmado" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-success">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Subir Documento Firmado</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>

                                                                <div class="modal-body" style="text-align: justify">
                                                                    <div style="text-align:left;" class="form-group">

                                                                        <label for="exampleInputBorder" style="font-size: 15px">Usuario Creador: <code>*</code></label>
                                                                        <input type="text" class="form-control form-control-border" readonly id="nomCreador" name="nomCreador" value="<?php echo $nomUsuario ?>" required>

                                                                    </div>
                                                                    <br>
                                                                    <div class="form-group">
                                                                        <!-- <label for="customFile">Custom File</label> -->
                                                                        <label for="exampleInputTareasControlCent">Subir Archivos Adjuntos: </label><br>
                                                                        <label for="exampleInputTareasControlCent">Formato Permitido: .pdf</label>
                                                                        <div class="custom-file">
                                                                            <input type="file" max-uploads="1" class="custom-file-input" id="documentoFirmado" name="documentoFirmado" accept=".pdf">
                                                                            <label class="custom-file-label" for="customFile">Seleccionar Archivo...</label>
                                                                        </div>
                                                                    </div>
                                                                    <script type="text/javascript">
                                                                        $('.custom-file input').change(function (e) {
                                                                            var files = [];
                                                                            for (var i = 0; i < $(this)[0].files.length; i++) {
                                                                                files.push($(this)[0].files[i].name);
                                                                            }
                                                                            $(this).next('.custom-file-label').html(files.join(', '));
                                                                        });
                                                                    </script>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="idFormRegAcceso" id="idFormRegAcceso" value="<?php echo $idFormRegAcceso ?>">
                                                                    <input type="hidden" name="folioFormRegAcceso" id="folioFormRegAcceso" value="<?php echo $folioFormRegAcceso ?>">
                                                                    <button type="submit" class="btn btn-primary">Subir</button>
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </form>

                                            </div>
                                            <?php
                                        }

                                        if (empty($fechaRecepcionFormRegAcceso) || $fechaRecepcionFormRegAcceso == '') {
                                            ?>

                                            &nbsp;&nbsp;
                                            <div class="col-lg-3 col-12">
                                                <form style=" display: inline" class="" action="../sdo-funciones/formulariosCCAA_recepcionPermiso.php" method="post">

                                                    <input type="hidden" name="idFormRegAcceso" value="<?php echo $idFormRegAcceso; ?>">
                                                    <input type="hidden" name="nomCreador" value="<?php echo $nomUsuario; ?>">
                                                    <button type="submit" id="inputMozi" class="btn btn-block bg-gradient-success btn-md"><i class="fa fa-check"></i>&nbsp;&nbsp;Recepci&oacute;n de Formato</button>

                                                </form>

                                            </div>
                                            <div class="col-lg-3 col-12">
                                                <br>
                                                <button type="button" class="btn btn-block bg-gradient-secondary btn-md" data-toggle="modal" data-target="#revisarObservacionTotal"><i class="fas fa-eye"></i>&nbsp;Visualizar Observaciones</button>
                                            </div>
                                            <?php
                                        }else{
                                            ?>
                                            <div class="col-lg-3 col-12">
                                                <button type="button" class="btn btn-block bg-gradient-secondary btn-md" data-toggle="modal" data-target="#revisarObservacionTotal"><i class="fas fa-eye"></i>&nbsp;Visualizar Observaciones</button>
                                            </div>
                                            <?php
                                        }

                                        ?>

                                    </div>
                                    <?php
                                }else{
                                    header('Location: ../sdo-administrador/formulariosCCAA_listado.php?errorGenerarFormulario=true');
                                }
                                $contarFormulariosGuardados = contarFormulariosGuardados($idFormRegAcceso);
                                if ($contarFormulariosGuardados > 0) {
                                    ?>

                                    <div class="col-lg-2 col-12">

                                        <?php
                                        $obtenerRutaFormFirma = obtenerRutaFormFirma($idFormRegAcceso);

                                        if (!empty($obtenerRutaFormFirma)) {
                                            ?>
                                            <a href="<?php echo $obtenerRutaFormFirma; ?>" target="_blank" class="btn btn-block bg-gradient-purple"><i class="fa fa-download"></i>&nbsp;&nbsp;Descargar Documento Firmado</a>
                                            <?php
                                        } else {
                                            ?>
                                            <a href="#" style="opacity: 0.5; pointer-events: none; text-decoration: none;" target="_blank" class="btn btn-block bg-gradient-purple">Error al Cargar Archivo. Contacte a Soporte.</a>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <?php
                                } else {
                                    ?>

                                    <div class="col-lg-2 col-12">
                                        <form style=" display: inline" class="" action="../sdo-funciones/formulariosCCAA_subirFirma.php" method="post" enctype="multipart/form-data">

                                            <input type="hidden" name="idFormRegAcceso" value="<?php echo $idFormRegAcceso; ?>">
                                            <button type="button" title="Subir Formato Firmado" data-toggle="modal" data-target="#subirFormatoFirmado" class="btn btn-block bg-gradient-warning btn-md"><i class="fa fa-upload"></i>&nbsp;&nbsp;Subir Documento Firmado</button>
                                            <div class="modal fade" id="subirFormatoFirmado" name="subirFormatoFirmado" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-success">
                                                            <h5 class="modal-title" id="exampleModalLabel">Subir Documento Firmado</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body" style="text-align: justify">
                                                            <div style="text-align:left;" class="form-group">

                                                                <label for="exampleInputBorder" style="font-size: 15px">Usuario Creador: <code>*</code></label>
                                                                <input type="text" class="form-control form-control-border" readonly id="nomCreador" name="nomCreador" value="<?php echo $nomUsuario ?>" required>

                                                            </div>
                                                            <br>
                                                            <div class="form-group">
                                                                <!-- <label for="customFile">Custom File</label> -->
                                                                <label for="exampleInputTareasControlCent">Subir Archivos Adjuntos: </label><br>
                                                                <label for="exampleInputTareasControlCent">Formato Permitido: .pdf</label>
                                                                <div class="custom-file">
                                                                    <input type="file" max-uploads="1" class="custom-file-input" id="documentoFirmado" name="documentoFirmado" accept=".pdf">
                                                                    <label class="custom-file-label" for="customFile">Seleccionar Archivo...</label>
                                                                </div>
                                                            </div>
                                                            <script type="text/javascript">
                                                                $('.custom-file input').change(function (e) {
                                                                    var files = [];
                                                                    for (var i = 0; i < $(this)[0].files.length; i++) {
                                                                        files.push($(this)[0].files[i].name);
                                                                    }
                                                                    $(this).next('.custom-file-label').html(files.join(', '));
                                                                });
                                                            </script>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="hidden" name="idFormRegAcceso" id="idFormRegAcceso" value="<?php echo $idFormRegAcceso ?>">
                                                            <input type="hidden" name="folioFormRegAcceso" id="folioFormRegAcceso" value="<?php echo $folioFormRegAcceso ?>">
                                                            <button type="submit" class="btn btn-primary">Subir</button>
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                        </form>

                                    </div>
                                    <?php
                                }
                                if (empty($fechaRecepcionFormRegAcceso) || $fechaRecepcionFormRegAcceso == '') {
                                    ?>
                                    &nbsp;&nbsp;
                                    <div class="col-lg-2">
                                        <form style=" display: inline" class="" action="../sdo-funciones/formulariosCCAA_recepcionPermiso.php" method="post">

                                            <input type="hidden" name="idFormRegAcceso" value="<?php echo $idFormRegAcceso; ?>">
                                            <input type="hidden" name="nomCreador" value="<?php echo $nomUsuario; ?>">
                                            <button type="submit" id="inputMozi" class="btn btn-block bg-gradient-success btn-md"><i class="fa fa-check"></i>&nbsp;&nbsp;Recepci&oacute;n de Formato</button>

                                        </form>
                                    </div>
                                    <div class="col-lg-2">
                                        <button type="button" class="btn btn-block bg-gradient-secondary btn-md" data-toggle="modal" data-target="#revisarObservacionTotal"><i class="fas fa-eye"></i>&nbsp;Visualizar Observaciones</button>
                                    </div>
                                    <?php
                                } else {
                                    ?>
                                    <div class="col-lg-2">
                                        <button type="button" class="btn btn-block bg-gradient-secondary btn-md" data-toggle="modal" data-target="#revisarObservacionTotal"><i class="fas fa-eye"></i>&nbsp;Visualizar Observaciones</button>
                                    </div>
                                    <?php
                                }

                                ?>
                                <div class="card col-12">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <?php
                                            $sqlFuncionario = "SELECT idUnidad FROM formularios_funcionarios WHERE idFormRegAcceso = $idFormRegAcceso GROUP BY idUnidad";
                                            $resFuncionario = mysqli_query($conexion, $sqlFuncionario);

                                            while ($rowFuncionario = $resFuncionario->fetch_assoc()) {
                                                $idUnidad = $rowFuncionario['idUnidad'];
                                                $buscarNomUnidad = buscarnombreunidad($idUnidad);
                                                $sqlAcceso = "SELECT * FROM formularios_acceso WHERE unidadFormCCAA = '$buscarNomUnidad'";
                                                $resAcceso = mysqli_query($conexion, $sqlAcceso);
                                                while ($rowAcceso = $resAcceso->fetch_assoc()) {
                                                    $cantidadFormCCAA = $rowAcceso['cantidadFormCCAA'];
                                                    // Definir el array de llaves
                                                    $keys = [
                                                        'door1FormCCAA', 'door2FormCCAA', 'door3FormCCAA', 'door4FormCCAA',
                                                        'door5FormCCAA', 'door6FormCCAA', 'door7FormCCAA', 'door8FormCCAA',
                                                        'door9FormCCAA', 'door10FormCCAA', 'door11FormCCAA', 'door12FormCCAA',
                                                        'door13FormCCAA', 'door14FormCCAA', 'door15FormCCAA', 'door16FormCCAA',
                                                        'door17FormCCAA', 'door18FormCCAA'
                                                    ];

                                                    // Convertir todas las puertas a mayúsculas
                                                    foreach ($keys as $key) {
                                                        if (isset($rowAcceso[$key])) {
                                                            $rowAcceso[$key] = mb_convert_case($rowAcceso[$key], MB_CASE_UPPER, 'UTF-8');
                                                        }
                                                    }

                                                    // Generar el HTML para la tabla
                                                    ?>
                                                    <legend><?php echo htmlspecialchars($buscarNomUnidad); ?></legend>
                                                    <table id="miTabla<?php echo htmlspecialchars($idUnidad); ?>" class="table table-bordered" style="font-size: 12px;">
                                                        <thead class="bg-secondary">
                                                        <tr>
                                                            <td style="vertical-align:middle; text-align:center;">N°:</td>
                                                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">NOMBRE DEL<br>FUNCIONARIO:</td>
                                                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">RUT DEL<br>FUNCIONARIO:</td>
                                                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">CALIDAD JURIDICA<br>DEL FUNCIONARIO:</td>
                                                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">UNIDAD DEL<br>FUNCIONARIO:</td>
                                                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">CORRELATIVO DE LA<br>TARJETA:</td>
                                                            <?php
                                                            // Imprimir las columnas de puertas
                                                            foreach ($keys as $key) {
                                                                if (!empty($rowAcceso[$key])) {
                                                                    echo '<td style="white-space: nowrap; vertical-align:middle; text-align:center;">' . htmlspecialchars($rowAcceso[$key]) . '</td>';
                                                                }
                                                            }
                                                            ?>
                                                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">INGRESADO EN ACTAS:</td>
                                                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">INGRESADO EN SISTEMA CCAA:</td>
                                                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">PERMISOS ENTREGADOS:</td>
                                                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">OBSERVACIONES:</td>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        $sqlBuscarFuncionarios = "SELECT * FROM formularios_funcionarios WHERE idFormRegAcceso = " . $idFormRegAcceso . " AND idUnidad = $idUnidad ORDER BY permisosFormFuncCCAA ASC";
                                                        $resBuscarFuncionarios = mysqli_query($conexion,$sqlBuscarFuncionarios);
                                                        $cantidadFuncionarios = 1;
                                                        while($rowBuscarFuncionarios = $resBuscarFuncionarios->fetch_assoc()){
                                                            $idFormFuncCCAA = $rowBuscarFuncionarios['idFormFuncCCAA'];
                                                            ?>
                                                            <tr>
                                                                <td style="vertical-align: middle; text-align: center;">
                                                                    <?php echo $cantidadFuncionarios; ?>
                                                                </td>
                                                                <td style="vertical-align: middle; text-align: center;">
                                                                    <?php echo $rowBuscarFuncionarios['nomFormFuncCCAA'] ?>
                                                                </td>
                                                                <td style="vertical-align: middle; text-align: center;">
                                                                    <?php echo $rowBuscarFuncionarios['rutFormFuncCCAA'] ?>
                                                                </td>
                                                                <td style="vertical-align: middle; text-align: center;">
                                                                    <?php echo $rowBuscarFuncionarios['calidadFormFuncCCAA'] ?>
                                                                </td>
                                                                <td style="vertical-align: middle; text-align: center;">
                                                                    <?php echo $rowBuscarFuncionarios['unidadFormFuncCCAA'] ?>
                                                                </td>
                                                                <td style="vertical-align: middle; text-align: center;">
                                                                    <?php echo $rowBuscarFuncionarios['correlativoTarjeta'] ?>
                                                                </td>
                                                                <?php

                                                                for ($i = 1; $i <= $cantidadFormCCAA; $i++) {
                                                                    ?>

                                                                    <td style="vertical-align: middle; text-align: center;">

                                                                        <b>
                                                                            <?php

                                                                            $opcion = $rowBuscarFuncionarios['door' . $i . 'FormCCAA'];

                                                                            if ($opcion == 1) {
                                                                                ?>
                                                                                <i class="fas fa-check"></i>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </b>

                                                                    </td>
                                                                    <?php
                                                                }
                                                                ?>
                                                                <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                                                    <?php
                                                                    $rutSinCaracteres = quitarCaracteresRUT($rowBuscarFuncionarios['rutFormFuncCCAA']);

                                                                    $buscarRUTenActa = buscarRUTenActa($rutSinCaracteres);


                                                                    $obtenerStatusCredencialCCAA = obtenerStatusCredencialCCAA($rutSinCaracteres);


                                                                    if ($buscarRUTenActa > 0) {
                                                                        ?>
                                                                        <form style=" display: inline" target="_blank" class="" action="../sdo-pdf/SDO-Acta.php" method="post">

                                                                            <input type="hidden" name="idActa" value="<?php echo $buscarRUTenActa; ?>">
                                                                            <button type="submit" id="inputMozi" class="btn btn-block bg-gradient-success btn-md"><i class="fa fa-eye"></i>&nbsp;&nbsp;Persona Ingresada</button>
                                                                        </form>

                                                                        <?php
                                                                    }else {
                                                                        ?>
                                                                        <b style="color: red;">Persona No Ingresada</b>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <?php
                                                                $valorIngresoCCAA = 0;
                                                                $rutSinCaracteres2 = quitarCaracteresRUT($rowBuscarFuncionarios['rutFormFuncCCAA']);

                                                                $buscarRUTenControlAcceso = buscarRUTenControlAcceso($rutSinCaracteres2);


                                                                $textoSinUltimoCaracter = substr($rutSinCaracteres2, 0, -1);
                                                                $buscarRUTenControlAcceso2 = buscarRUTenControlAcceso($textoSinUltimoCaracter);

                                                                if ($buscarRUTenControlAcceso > 0) {

                                                                    $obtenerRutaControlAcceso = obtenerRutaControlAcceso($rutSinCaracteres2);
                                                                    $obtenerRutaControlAcceso2 = str_replace("\\", " / ", $obtenerRutaControlAcceso);
                                                                    ?>
                                                                    <td style="white-space: nowrap; vertical-align:middle; text-align:center; color: green">

                                                                        <button onclick="mostrarRutaPersona(<?php echo "'" . $obtenerRutaControlAcceso2 . "','" . $rowBuscarFuncionarios['nomFormFuncCCAA'] . "'"; ?>)" id="inputMozi" class="btn btn-block bg-gradient-info btn-md"><i class="fa fa-eye"></i>&nbsp;&nbsp;Visualizar Ruta</button>

                                                                    </td>
                                                                    <?php
                                                                    $valorIngresoCCAA = 1;
                                                                } else if ($buscarRUTenControlAcceso2 > 0) {

                                                                    $obtenerRutaControlAcceso = obtenerRutaControlAcceso($textoSinUltimoCaracter);
                                                                    $obtenerRutaControlAcceso2 = str_replace("\\", " / ", $obtenerRutaControlAcceso);
                                                                    ?>
                                                                    <td style="white-space: nowrap; vertical-align:middle; text-align:center; color: green">

                                                                        <button onclick="mostrarRutaPersona(<?php echo "'" . $obtenerRutaControlAcceso2 . "','" . $rowBuscarFuncionarios['nomFormFuncCCAA'] . "'"; ?>)" id="inputMozi" class="btn btn-block bg-gradient-info btn-md"><i class="fa fa-eye"></i>&nbsp;&nbsp;Visualizar Ruta</button>

                                                                    </td>
                                                                    <?php
                                                                    $valorIngresoCCAA = 1;
                                                                } else {
                                                                    ?>
                                                                    <td style="white-space: nowrap; vertical-align:middle; text-align:center; color: red">
                                                                        <b>Persona No Ingresada</b>
                                                                    </td>
                                                                    <?php
                                                                    $valorIngresoCCAA = 0;
                                                                }
                                                                ?>
                                                                <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                                                    <?php
                                                                    $permisosFormFuncCCAA = $rowBuscarFuncionarios['permisosFormFuncCCAA'];
                                                                    $fechaPermisoFormFuncCCAA = $rowBuscarFuncionarios['fechaPermisoFormFuncCCAA'];

                                                                    $fechaPermisoFormFuncCCAA2 = date('d-m-Y H:i', strtotime($fechaPermisoFormFuncCCAA));

                                                                    if ($permisosFormFuncCCAA > 0) {
                                                                        echo '<b style="color: green">Permisos entregados con fecha: ' . $fechaPermisoFormFuncCCAA2 . '</b>';
                                                                    } else {
                                                                        if ($valorIngresoCCAA == 0) {
                                                                            ?>
                                                                            <button type="button" title="Permisos Entregados" disabled class="btn btn-success btn-block">Deshabilitado</button>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <form method="post" action="../sdo-funciones/formulariosCCAA_entregaPermisos.php">

                                                                                <button type="button" title="Permisos Entregados" data-toggle="modal" data-target="#finalizarPermisos<?php echo $idFormFuncCCAA ?>" class="btn btn-success btn-block"><i class="fas fa-check"></i>&nbsp;Finalizar</button>
                                                                                <div class="modal fade" id="finalizarPermisos<?php echo $idFormFuncCCAA ?>" name="finalizarPermisos<?php echo $idFormFuncCCAA ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

                                                                                    <div class="modal-dialog" role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header bg-success">
                                                                                                <h5 class="modal-title" id="exampleModalLabel">Finalizar Entrega de Permisos</h5>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>

                                                                                            <div class="modal-body">
                                                                                                <div style="text-align:left;" class="form-group">
                                                                                                    <label for="exampleInputBorder" style="font-size: 15px">Usuario Creador: <code>*</code></label>
                                                                                                    <input type="text" class="form-control form-control-border" readonly id="nomCreador" name="nomCreador" value="<?php echo $nomUsuario ?>" required>
                                                                                                    <br>

                                                                                                    <label for="exampleInputBorder" style="font-size: 15px">Detalle de la Observaci&oacute;n: <code>*</code></label>
                                                                                                    <textarea id="detalleObs" style="resize:none;" name="detalleObs" class="form-control" rows="15" placeholder="Observaciones..." required></textarea>

                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="modal-footer">
                                                                                                <input type="hidden" name="idFormRegAcceso" id="idFormRegAcceso" value="<?php echo $idFormRegAcceso ?>">
                                                                                                <input type="hidden" name="idFormFuncCCAA" id="idFormFuncCCAA" value="<?php echo $idFormFuncCCAA ?>">
                                                                                                <input type="hidden" name="folioFormRegAcceso" id="folioFormRegAcceso" value="<?php echo $folioFormRegAcceso; ?>">
                                                                                                <input type="hidden" name="fechaObservacion" id="fechaObservacion" value="<?php echo $now ?>">
                                                                                                <button type="submit" class="btn btn-primary">Agregar</button>
                                                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </form>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>

                                                                </td>
                                                                <td style="vertical-align:middle; text-align:center;">
                                                                    <?php
                                                                    if ($idFormRegAcceso == '' || $idFormRegAcceso == 0 || empty($idFormRegAcceso)){
                                                                        echo 'ERROR, DATOS NO EXISTEN';
                                                                    }else{
                                                                        // echo $idFormRegAcceso;
                                                                        $now = date('Y-m-d H:i:s')
                                                                        ?>
                                                                        <button type="button" title="Agregar Observacion" data-toggle="modal" data-target="#agregarObservacion<?php echo $idFormFuncCCAA ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i></button>
                                                                        <button type="button" title="Revisar Observacion" data-toggle="modal" data-target="#revisarObservacion<?php echo $idFormFuncCCAA ?>" class="btn btn-primary btn-sm"><i class="far fa-eye"></i></button>
                                                                        <form method="post" action="../sdo-funciones/guardar-observacion-formatos.php">
                                                                            <input type="hidden" name="idFormRegAcceso" id="idFormRegAcceso" value="<?php echo $idFormRegAcceso ?>">
                                                                            <input type="hidden" name="idFormFuncCCAA" id="idFormFuncCCAA" value="<?php echo $idFormFuncCCAA ?>">
                                                                            <input type="hidden" name="folioFormRegAcceso" id="folioFormRegAcceso" value="<?php echo $folioFormRegAcceso; ?>">
                                                                            <input type="hidden" name="fechaObservacion" id="fechaObservacion" value="<?php echo $now ?>">
                                                                            <div class="modal fade" id="agregarObservacion<?php echo $idFormFuncCCAA ?>" name="agregarObservacion<?php echo $idFormFuncCCAA ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header bg-primary">
                                                                                            <h5 class="modal-title" id="exampleModalLabel">Agregar Observaci&oacute;n</h5>
                                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                <span aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>

                                                                                        <div class="modal-body">
                                                                                            <div style="text-align:left;" class="form-group">

                                                                                                <label for="exampleInputBorder" style="font-size: 15px">Usuario Creador: <code>*</code></label>
                                                                                                <input type="text" class="form-control form-control-border" readonly id="nomCreador" name="nomCreador" value="<?php echo $nomUsuario ?>" required>
                                                                                                <br>

                                                                                                <label for="exampleInputBorder" style="font-size: 15px">Detalle de la Observaci&oacute;n: <code>*</code></label>
                                                                                                <textarea id="detalleObs" style="resize:none;" name="detalleObs" class="form-control" rows="15" placeholder="Observaciones..." required></textarea>

                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="submit" class="btn btn-primary">Agregar</button>
                                                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                        <div class="modal fade" id="revisarObservacion<?php echo $idFormFuncCCAA ?>" name="revisarObservacion<?php echo $idFormFuncCCAA ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                            <div class="modal-dialog modal-xl" role="document">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header bg-primary">
                                                                                        <h5 class="modal-title" id="exampleModalLabel">Revisar Observacion</h5>
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                            <span aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <table id="observaciones" class="table table-bordered table-sm">
                                                                                            <thead style="background-color: indianred; color: white">
                                                                                            <tr>
                                                                                                <th style="font-size: 15px">Fecha de la Observacion</th>
                                                                                                <th style="font-size: 15px">Creador de la Observacion</th>
                                                                                                <th style="font-size: 15px">Detalle de la Observacion</th>
                                                                                            </tr>
                                                                                            </thead>
                                                                                            <tbody style="font-size: 15px">
                                                                                            <?php
                                                                                            $sqlObservaciones = "SELECT * FROM formularios_observaciones WHERE idFormFuncCCAA = $idFormFuncCCAA";
                                                                                            $resObservaciones = mysqli_query($conexion, $sqlObservaciones);
                                                                                            while ($rowObservaciones = $resObservaciones->fetch_assoc()) {
                                                                                                $fechaObs = $rowObservaciones['fechaObs'];
                                                                                                $creador = $rowObservaciones['creador'];
                                                                                                $detalleObs = $rowObservaciones['detalleObs'];
                                                                                                ?>
                                                                                                <tr>
                                                                                                    <td>
                                                                                                        <?php echo $fechaObs; ?>
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <?php echo $creador; ?>
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <?php echo $detalleObs; ?>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <?php
                                                                                            }
                                                                                            ?>
                                                                                            </tbody>
                                                                                            <tfoot style="background-color: indianred; color: white">
                                                                                            <tr>
                                                                                                <th style="font-size: 15px">Fecha de la Observacion</th>
                                                                                                <th style="font-size: 15px">Creador de la Observacion</th>
                                                                                                <th style="font-size: 15px">Detalle de la Observacion</th>
                                                                                            </tr>
                                                                                            </tfoot>
                                                                                        </table>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </td>

                                                            </tr>
                                                            <?php
                                                            $cantidadFuncionarios++;
                                                        }

                                                        ?>
                                                        <script>
                                                            function mostrarRutaPersona(variable, nombre) {

                                                                Swal.fire({
                                                                    title: 'Informaci&oacute;n: ',
                                                                    text: 'En el Sistema de Control de Accesos, ' + nombre + ' se encuentra en la siguiente Ruta: ' + variable,
                                                                    icon: 'info',
                                                                    confirmButtonText: 'Aceptar'
                                                                });
                                                            }
                                                        </script>
                                                        </tbody>
                                                        <tfoot class="bg-secondary">
                                                        <tr>
                                                            <td style="vertical-align:middle; text-align:center;">N°:</td>
                                                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">NOMBRE DEL<br>FUNCIONARIO:</td>
                                                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">RUT DEL<br>FUNCIONARIO:</td>
                                                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">CALIDAD JURIDICA<br>DEL FUNCIONARIO:</td>
                                                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">UNIDAD DEL<br>FUNCIONARIO:</td>
                                                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">CORRELATIVO DE LA<br>TARJETA:</td>
                                                            <?php
                                                            // Imprimir las columnas de puertas
                                                            foreach ($keys as $key) {
                                                                if (!empty($rowAcceso[$key])) {
                                                                    echo '<td style="white-space: nowrap; vertical-align:middle; text-align:center;">' . htmlspecialchars($rowAcceso[$key]) . '</td>';
                                                                }
                                                            }
                                                            ?>
                                                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">INGRESADO EN ACTAS:</td>
                                                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">INGRESADO EN SISTEMA CCAA:</td>
                                                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">PERMISOS ENTREGADOS:</td>
                                                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">OBSERVACIONES:</td>
                                                        </tr>
                                                        </tfoot>
                                                    </table>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                        <div class="modal fade" id="revisarObservacionTotal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-secondary">
                                                        <h5 class="modal-title" id="exampleModalLabel">Revisar todas las observaciones de este formato</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table id="observacionTotal" class="table table-bordered table-sm">
                                                            <thead style="background-color: indianred; color: white">
                                                            <tr>
                                                                <th style="font-size: 15px">Fecha de la Observacion</th>
                                                                <th style="font-size: 15px">Creador de la Observacion</th>
                                                                <th style="font-size: 15px">Nombre del Funcionario</th>
                                                                <th style="font-size: 15px">Rut del Funcionario</th>
                                                                <th style="font-size: 15px">Detalle de la Observacion</th>
                                                                <th style="font-size: 15px">Unidad Perteneciente</th>
                                                            </tr>

                                                            </thead>
                                                            <tbody style="font-size: 15px">
                                                            <?php
                                                            $sqlObservaciones = "SELECT * FROM formularios_observaciones WHERE idFormFuncCCAA NOT IN (0) AND idFormRegAcceso = $idFormRegAcceso";
                                                            $resObservaciones = mysqli_query($conexion, $sqlObservaciones);
                                                            while ($rowObservaciones = $resObservaciones->fetch_assoc()) {
                                                                $fechaObs = $rowObservaciones['fechaObs'];
                                                                $creador = $rowObservaciones['creador'];
                                                                $detalleObs = $rowObservaciones['detalleObs'];
                                                                $idFormFuncCCAAform = $rowObservaciones['idFormFuncCCAA'];

                                                                $sqlFuncionario = "SELECT * FROM formularios_funcionarios WHERE idFormFuncCCAA = $idFormFuncCCAAform";
                                                                $resFuncionario = mysqli_query($conexion, $sqlFuncionario);
                                                                while ($rowFuncionario = $resFuncionario->fetch_assoc()) {
                                                                    $nomFormFuncCCAA = $rowFuncionario['nomFormFuncCCAA'];
                                                                    $rutFormFuncCCAA = $rowFuncionario['rutFormFuncCCAA'];
                                                                    $idUnidadFuncionario = $rowFuncionario['idUnidad'];
                                                                }

                                                                $sqlUnidad = "SELECT * FROM formularios_acceso WHERE idFormCCAA = $idUnidadFuncionario";
                                                                $resUnidad = mysqli_query($conexion,$sqlUnidad);
                                                                while($rowUnidad = $resUnidad->fetch_assoc()){
                                                                    $unidadFormCCAA = $rowUnidad['unidadFormCCAA'];
                                                                }

                                                                ?>
                                                                <tr>
                                                                    <td>
                                                                        <?php echo $fechaObs; ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php echo $creador; ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php echo $nomFormFuncCCAA ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php echo $rutFormFuncCCAA ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php echo $detalleObs; ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php echo $unidadFormCCAA?>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            }
                                                            ?>
                                                            </tbody>
                                                            <tfoot style="background-color: indianred; color: white">
                                                            <tr>
                                                                <th style="font-size: 15px">Fecha de la Observacion</th>
                                                                <th style="font-size: 15px">Creador de la Observacion</th>
                                                                <th style="font-size: 15px">Nombre del Funcionario</th>
                                                                <th style="font-size: 15px">Rut del Funcionario</th>
                                                                <th style="font-size: 15px">Detalle de la Observacion</th>
                                                                <th style="font-size: 15px">Unidad Perteneciente</th>
                                                            </tr>
                                                            </tfoot>
                                                        </table>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
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


