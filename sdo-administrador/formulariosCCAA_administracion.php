<?php

include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');
$nomUsuario = $_SESSION['nomUsuario'];
$now = date('Y-m-d H:i:s');

//VARIABLES DE SESSION

if (isset($_POST['idFormRegAcceso']) || isset($_SESSION['idFormRegAcceso'])) {

    if (isset($_POST['idFormRegAcceso'])) {
        $idFormRegAcceso = $_POST['idFormRegAcceso'];

    } else if (isset($_SESSION['idFormRegAcceso'])) {
        $idFormRegAcceso = $_SESSION['idFormRegAcceso'];
    }

    if ($idFormRegAcceso > 0) {

        $getDataRegistroForm = array();
        $getDataRegistroForm = getDataRegistroForm($idFormRegAcceso);

        $idFormRegAcceso = $getDataRegistroForm['idFormRegAcceso'];
        $folioFormRegAcceso = $getDataRegistroForm['folioFormRegAcceso'];
        $unidadFormRegAcceso = $getDataRegistroForm['unidadFormRegAcceso'];

        $fechaFormRegAcceso = $getDataRegistroForm['fechaFormRegAcceso'];
        $fechaFormRegAcceso2 = date('d-m-Y H:i', strtotime($fechaFormRegAcceso));
        $fechaFormRegAcceso3 = strtotime($fechaFormRegAcceso);
        $fechaFormRegAcceso3 = fechaEspañol2($fechaFormRegAcceso3);

        $horaFormRegAcceso = date('H:i', strtotime($fechaFormRegAcceso));

        $fechaRecepcionFormRegAcceso = $getDataRegistroForm['fechaRecepcionFormRegAcceso'];
        $fechaRecepcionFormRegAcceso2 = date('d-m-Y H:i', strtotime($fechaRecepcionFormRegAcceso));
        $fechaRecepcionFormRegAcceso3 = strtotime($fechaRecepcionFormRegAcceso);
        $fechaRecepcionFormRegAcceso3 = fechaEspañol2($fechaRecepcionFormRegAcceso3);

        $horaRecepcionFormRegAcceso = date('H:i', strtotime($fechaRecepcionFormRegAcceso));

        $categoriaFormRegAcceso = $getDataRegistroForm['categoriaFormRegAcceso'];
        $solicitanteFormRegAcceso = $getDataRegistroForm['solicitanteFormRegAcceso'];
        $unidadSolicitaFormRegAcceso = $getDataRegistroForm['unidadSolicitaFormRegAcceso'];;
        $emailSolicitaFormRegAcceso = $getDataRegistroForm['emailSolicitaFormRegAcceso'];


        if ($unidadFormRegAcceso > 0) {

            $getPuertasFormulario = array();
            $getPuertasFormulario = getPuertasFormulario($unidadFormRegAcceso);

            $idFormCCAA = $getPuertasFormulario['idFormCCAA'];
            $unidadFormCCAA = $getPuertasFormulario['unidadFormCCAA'];
            $fechaFormCCAA = $getPuertasFormulario['fechaFormCCAA'];
            $subdireccionFormCCAA = $getPuertasFormulario['subdireccionFormCCAA'];
            $cantidadFormCCAA = $getPuertasFormulario['cantidadFormCCAA'];
            $door1FormCCAA = $getPuertasFormulario['door1FormCCAA'];
            $door1FormCCAA = mb_convert_case($door1FormCCAA, MB_CASE_UPPER, 'UTF-8');
            $door2FormCCAA = $getPuertasFormulario['door2FormCCAA'];
            $door2FormCCAA = mb_convert_case($door2FormCCAA, MB_CASE_UPPER, 'UTF-8');
            $door3FormCCAA = $getPuertasFormulario['door3FormCCAA'];
            $door3FormCCAA = mb_convert_case($door3FormCCAA, MB_CASE_UPPER, 'UTF-8');
            $door4FormCCAA = $getPuertasFormulario['door4FormCCAA'];
            $door4FormCCAA = mb_convert_case($door4FormCCAA, MB_CASE_UPPER, 'UTF-8');
            $door5FormCCAA = $getPuertasFormulario['door5FormCCAA'];
            $door5FormCCAA = mb_convert_case($door5FormCCAA, MB_CASE_UPPER, 'UTF-8');
            $door6FormCCAA = $getPuertasFormulario['door6FormCCAA'];
            $door6FormCCAA = mb_convert_case($door6FormCCAA, MB_CASE_UPPER, 'UTF-8');
            $door7FormCCAA = $getPuertasFormulario['door7FormCCAA'];
            $door7FormCCAA = mb_convert_case($door7FormCCAA, MB_CASE_UPPER, 'UTF-8');
            $door8FormCCAA = $getPuertasFormulario['door8FormCCAA'];
            $door8FormCCAA = mb_convert_case($door8FormCCAA, MB_CASE_UPPER, 'UTF-8');
            $door9FormCCAA = $getPuertasFormulario['door9FormCCAA'];
            $door9FormCCAA = mb_convert_case($door9FormCCAA, MB_CASE_UPPER, 'UTF-8');
            $door10FormCCAA = $getPuertasFormulario['door10FormCCAA'];
            $door10FormCCAA = mb_convert_case($door10FormCCAA, MB_CASE_UPPER, 'UTF-8');
            $door11FormCCAA = $getPuertasFormulario['door11FormCCAA'];
            $door11FormCCAA = mb_convert_case($door11FormCCAA, MB_CASE_UPPER, 'UTF-8');
            $door12FormCCAA = $getPuertasFormulario['door12FormCCAA'];
            $door12FormCCAA = mb_convert_case($door12FormCCAA, MB_CASE_UPPER, 'UTF-8');
            $door13FormCCAA = $getPuertasFormulario['door13FormCCAA'];
            $door13FormCCAA = mb_convert_case($door13FormCCAA, MB_CASE_UPPER, 'UTF-8');
            $door14FormCCAA = $getPuertasFormulario['door14FormCCAA'];
            $door14FormCCAA = mb_convert_case($door14FormCCAA, MB_CASE_UPPER, 'UTF-8');
            $door15FormCCAA = $getPuertasFormulario['door15FormCCAA'];
            $door15FormCCAA = mb_convert_case($door15FormCCAA, MB_CASE_UPPER, 'UTF-8');
            $door16FormCCAA = $getPuertasFormulario['door16FormCCAA'];
            $door16FormCCAA = mb_convert_case($door16FormCCAA, MB_CASE_UPPER, 'UTF-8');
            $door17FormCCAA = $getPuertasFormulario['door17FormCCAA'];
            $door17FormCCAA = mb_convert_case($door17FormCCAA, MB_CASE_UPPER, 'UTF-8');
            $door18FormCCAA = $getPuertasFormulario['door18FormCCAA'];
            $door18FormCCAA = mb_convert_case($door18FormCCAA, MB_CASE_UPPER, 'UTF-8');

            $_SESSION['idFormRegAcceso'] = $idFormRegAcceso;

        }

    } else {
        header('Location: ../sdo-administrador/formulariosCCAA_listado.php?errorGenerarFormulario=true');
    }


} else {
    header('Location: ../sdo-administrador/formulariosCCAA_listado.php?errorGenerarFormulario=true');
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Visualizando Formulario</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Visualizando Formulario</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a>
                            </li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">


                                <a href="formulariosCCAA_listado.php" style="float: right;" class="btn btn-danger"><i
                                            class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br><br>

                                <h4><b>Solicitud de Accesos - <?php echo $folioFormRegAcceso; ?></b></h4>
                                <br>
                                <h5><b>Jefatura Solicitante: </b><?php echo $solicitanteFormRegAcceso; ?></h5>
                                <h5><b>Unidad Solicitante: </b><?php echo $unidadSolicitaFormRegAcceso; ?></h5>
                                <h5><b>Correo Solicitante: </b><?php echo $emailSolicitaFormRegAcceso; ?></h5>
                                <h5><b>Fecha
                                        Solicitud: </b><?php echo $fechaFormRegAcceso3 . ' a las ' . $horaFormRegAcceso . ' hrs.'; ?>
                                </h5>
                                <?php
                                if (!empty($fechaRecepcionFormRegAcceso)) {
                                    ?>
                                    <br>
                                    <h5><b>Fecha
                                            Recepci&oacute;n: </b><?php echo $fechaRecepcionFormRegAcceso3 . ' a las ' . $horaRecepcionFormRegAcceso . ' hrs.'; ?>
                                    </h5>
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
                                if (isset($_GET['exitoGenerarFormulario'])) {
                                    ?>
                                    <script>
                                        Swal.fire({
                                            title: 'Formulario Generado con &Eacute;xito.',
                                            confirmButtonText: 'Cerrar',
                                            icon: 'success',
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

                                <br>
                                <br>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body text-center">


                                <!-- small box -->
                                <?php
                                $getCategoriaFormRegAcceso = getCategoriaFormRegAcceso($idFormRegAcceso);

                                if ($getCategoriaFormRegAcceso == 'Nuevo') {
                                    ?>
                                    <div class="row text-center" style="display: flex; justify-content:center;">
                                        <div class="col-lg-3 col-12">
                                            <form style=" display: inline" target="_blank" class=""
                                                  action="../sdo-pdf/SDO-Formulario-SolicitudAcceso-Nuevo.php"
                                                  method="post">

                                                <input type="hidden" name="idFormRegAcceso"
                                                       value="<?php echo $idFormRegAcceso; ?>">
                                                <button type="submit" id="inputMozi"
                                                        class="btn btn-block bg-gradient-primary btn-md"><i
                                                            class="fa fa-eye"></i>&nbsp;&nbsp;Visualizar Formato de
                                                    Acceso
                                                </button>

                                            </form>

                                        </div>
                                    </div>
                                    <hr>
                                    <?php
                                } else if ($getCategoriaFormRegAcceso == 'Antiguo') {
                                    ?>
                                    <div class="row text-center" style="display: flex; justify-content:center;">
                                        <div class="col-lg-3 col-12">
                                            <form style=" display: inline" target="_blank" class=""
                                                  action="../sdo-pdf/SDO-Formulario-SolicitudAcceso-Antiguo.php"
                                                  method="post">

                                                <input type="hidden" name="idFormRegAcceso"
                                                       value="<?php echo $idFormRegAcceso; ?>">
                                                <button type="submit" id="inputMozi"
                                                        class="btn btn-block bg-gradient-primary btn-md"><i
                                                            class="fa fa-eye"></i>&nbsp;&nbsp;Visualizar Formato de
                                                    Acceso
                                                </button>

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
                                                    <a href="<?php echo $obtenerRutaFormFirma; ?>" target="_blank"
                                                       class="btn btn-block bg-gradient-purple"><i
                                                                class="fa fa-download"></i>&nbsp;&nbsp;Descargar
                                                        Documento Firmado</a>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <a href="#"
                                                       style="opacity: 0.5; pointer-events: none; text-decoration: none;"
                                                       target="_blank" class="btn btn-block bg-gradient-purple">Error al
                                                        Cargar Archivo. Contacte a Soporte.</a>
                                                    <?php
                                                }
                                                ?>


                                            </div>
                                            <?php
                                        } else {
                                            ?>

                                            <div class="col-lg-3 col-12">
                                                <form style=" display: inline" class=""
                                                      action="../sdo-funciones/formulariosCCAA_subirFirma.php"
                                                      method="post" enctype="multipart/form-data">

                                                    <input type="hidden" name="idFormRegAcceso"
                                                           value="<?php echo $idFormRegAcceso; ?>">
                                                    <button type="button" title="Subir Formato Firmado"
                                                            data-toggle="modal" data-target="#subirFormatoFirmado"
                                                            class="btn btn-block bg-gradient-warning btn-md"><i
                                                                class="fa fa-upload"></i>&nbsp;&nbsp;Subir Documento
                                                        Firmado
                                                    </button>
                                                    <div class="modal fade" id="subirFormatoFirmado"
                                                         name="subirFormatoFirmado" tabindex="-1" role="dialog"
                                                         aria-labelledby="exampleModalLabel" aria-hidden="true">

                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-success">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Subir
                                                                        Documento Firmado</h5>
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>

                                                                <div class="modal-body" style="text-align: justify">
                                                                    <div style="text-align:left;" class="form-group">

                                                                        <label for="exampleInputBorder"
                                                                               style="font-size: 15px">Usuario Creador:
                                                                            <code>*</code></label>
                                                                        <input type="text"
                                                                               class="form-control form-control-border"
                                                                               readonly id="nomCreador"
                                                                               name="nomCreador"
                                                                               value="<?php echo $nomUsuario ?>"
                                                                               required>

                                                                    </div>
                                                                    <br>
                                                                    <div class="form-group">
                                                                        <!-- <label for="customFile">Custom File</label> -->
                                                                        <label for="exampleInputTareasControlCent">Subir
                                                                            Archivos Adjuntos: </label><br>
                                                                        <label for="exampleInputTareasControlCent">Formato
                                                                            Permitido: .pdf</label>
                                                                        <div class="custom-file">
                                                                            <input type="file" max-uploads="1"
                                                                                   class="custom-file-input"
                                                                                   id="documentoFirmado"
                                                                                   name="documentoFirmado"
                                                                                   accept=".pdf">
                                                                            <label class="custom-file-label"
                                                                                   for="customFile">Seleccionar
                                                                                Archivo...</label>
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
                                                                    <input type="hidden" name="idFormRegAcceso"
                                                                           id="idFormRegAcceso"
                                                                           value="<?php echo $idFormRegAcceso ?>">
                                                                    <input type="hidden" name="folioFormRegAcceso"
                                                                           id="folioFormRegAcceso"
                                                                           value="<?php echo $folioFormRegAcceso ?>">
                                                                    <button type="submit" class="btn btn-primary">
                                                                        Subir
                                                                    </button>
                                                                    <button type="button" class="btn btn-danger"
                                                                            data-dismiss="modal">Cancelar
                                                                    </button>
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
                                                <form style=" display: inline" class=""
                                                      action="../sdo-funciones/formulariosCCAA_recepcionPermiso.php"
                                                      method="post">

                                                    <input type="hidden" name="idFormRegAcceso"
                                                           value="<?php echo $idFormRegAcceso; ?>">
                                                    <input type="hidden" name="nomCreador"
                                                           value="<?php echo $nomUsuario; ?>">
                                                    <button type="submit" id="inputMozi"
                                                            class="btn btn-block bg-gradient-success btn-md"><i
                                                                class="fa fa-check"></i>&nbsp;&nbsp;Recepci&oacute;n de
                                                        Formato
                                                    </button>

                                                </form>

                                            </div>
                                            <div class="col-lg-3 col-12">
                                                <br>
                                                <button type="button" class="btn btn-block bg-gradient-secondary btn-md"
                                                        data-toggle="modal" data-target="#revisarObservacionTotal"><i
                                                            class="fas fa-eye"></i>&nbsp;Visualizar Observaciones
                                                </button>
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
                                    <hr>
                                    <?php
                                } else {
                                    header('Location: ../sdo-administrador/formulariosCCAA_listado.php?errorGenerarFormulario3=true');
                                }


                                ?>


                                <div class="card col-12">

                                    <div class="card-body">
                                        <div class="table-responsive">

                                            <table id="miTabla" class="table table-bordered" style="font-size: 12px;">
                                                <thead class="bg-secondary">
                                                <tr>
                                                    <th style="vertical-align:middle; text-align:center;">N°:</th>
                                                    <th style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                                        NOMBRE DEL<br>FUNCIONARIO:
                                                    </th>
                                                    <th style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                                        RUT DEL<br>FUNCIONARIO:
                                                    </th>
                                                    <th style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                                        CALIDAD JURIDICA<br>DEL FUNCIONARIO:
                                                    </th>
                                                    <th style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                                        UNIDAD DEL<br>FUNCIONARIO:
                                                    </th>
                                                    <?php

                                                    for ($j = 1; $j <= $cantidadFormCCAA; $j++) {

                                                        $nomPuertaJ = $getPuertasFormulario['door' . $j . 'FormCCAA'];
                                                        $nomPuertaJ2 = mb_convert_case($nomPuertaJ, MB_CASE_UPPER, 'UTF-8');
                                                        ?>
                                                        <th style="white-space: nowrap; vertical-align:middle; text-align:center;"><?php echo $nomPuertaJ2 ?></th>
                                                        <?php
                                                    }
                                                    ?>

                                                    <th style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                                        INGRESADO EN ACTAS:
                                                    </th>
                                                    <th style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                                        INGRESADO EN SISTEMA CCAA:
                                                    </th>
                                                    <th style="vertical-align:middle; text-align:center;">PERMISOS
                                                        ENTREGADOS:
                                                    </th>
                                                    <th style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                                        OBSERVACIONES:
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $sqlBuscarFuncionarios = "SELECT * FROM formularios_funcionarios WHERE idFormRegAcceso = " . $idFormRegAcceso." ORDER BY permisosFormFuncCCAA ASC";

                                                $resBuscarFuncionarios = mysqli_query($conexion, $sqlBuscarFuncionarios);

                                                $cantidadFuncionarios = 1;
                                                while ($rowBuscarFuncionarios = $resBuscarFuncionarios->fetch_assoc()) {

                                                    $idFormFuncCCAA = $rowBuscarFuncionarios['idFormFuncCCAA'];
                                                    ?>
                                                    <tr>
                                                        <td style="vertical-align: middle; text-align: center;">
                                                            <?php echo $cantidadFuncionarios; ?>
                                                        </td>
                                                        <td style="vertical-align: middle; text-align: center;">
                                                            <?php
                                                            $nomFormFuncCCAA = $rowBuscarFuncionarios['nomFormFuncCCAA'];
                                                            $nomFormFuncCCAA2 = mb_convert_case($nomFormFuncCCAA, MB_CASE_TITLE, 'UTF-8');

                                                            echo $nomFormFuncCCAA2;

                                                            ?>
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

                                                            if ($buscarRUTenActa > 0) {
                                                                ?>
                                                                <form style=" display: inline" target="_blank" class=""
                                                                      action="../sdo-pdf/SDO-Acta.php" method="post">

                                                                    <input type="hidden" name="idActa"
                                                                           value="<?php echo $buscarRUTenActa; ?>">
                                                                    <button type="submit" id="inputMozi"
                                                                            class="btn btn-block bg-gradient-success btn-md">
                                                                        <i class="fa fa-eye"></i>&nbsp;&nbsp;Persona
                                                                        Ingresada
                                                                    </button>

                                                                </form>
                                                                <?php
                                                            } else {
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


                                                        <td style="vertical-align:middle; text-align:center;">
                                                            <?php
                                                            $permisosFormFuncCCAA = $rowBuscarFuncionarios['permisosFormFuncCCAA'];
                                                            $fechaPermisoFormFuncCCAA = $rowBuscarFuncionarios['fechaPermisoFormFuncCCAA'];

                                                            $fechaPermisoFormFuncCCAA2 = date('d-m-Y H:i', strtotime($fechaPermisoFormFuncCCAA));

                                                            if ($permisosFormFuncCCAA > 0) {
                                                                echo '<b style="color: green">Permisos entregados con fecha: ' . $fechaPermisoFormFuncCCAA2 . '</b>';
                                                            } else {
                                                                if ($valorIngresoCCAA == 0) {
                                                                    ?>
                                                                    <button type="button" title="Permisos Entregados"
                                                                            disabled class="btn btn-success btn-block">
                                                                        Deshabilitado
                                                                    </button>
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <form method="post"
                                                                          action="../sdo-funciones/formulariosCCAA_entregaPermisos.php">

                                                                        <button type="button"
                                                                                title="Permisos Entregados"
                                                                                data-toggle="modal"
                                                                                data-target="#finalizarPermisos<?php echo $idFormFuncCCAA ?>"
                                                                                class="btn btn-success btn-block"><i
                                                                                    class="fas fa-check"></i>&nbsp;Finalizar
                                                                        </button>
                                                                        <div class="modal fade"
                                                                             id="finalizarPermisos<?php echo $idFormFuncCCAA ?>"
                                                                             name="finalizarPermisos<?php echo $idFormFuncCCAA ?>"
                                                                             tabindex="-1" role="dialog"
                                                                             aria-labelledby="exampleModalLabel"
                                                                             aria-hidden="true">

                                                                            <div class="modal-dialog" role="document">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header bg-success">
                                                                                        <h5 class="modal-title"
                                                                                            id="exampleModalLabel">
                                                                                            Finalizar Entrega de
                                                                                            Permisos</h5>
                                                                                        <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                            <span aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                    </div>

                                                                                    <div class="modal-body">
                                                                                        <div style="text-align:left;"
                                                                                             class="form-group">

                                                                                            <label for="exampleInputBorder"
                                                                                                   style="font-size: 15px">Usuario
                                                                                                Creador: <code>*</code></label>
                                                                                            <input type="text"
                                                                                                   class="form-control form-control-border"
                                                                                                   readonly
                                                                                                   id="nomCreador"
                                                                                                   name="nomCreador"
                                                                                                   value="<?php echo $nomUsuario ?>"
                                                                                                   required>
                                                                                            <br>

                                                                                            <label for="exampleInputBorder"
                                                                                                   style="font-size: 15px">Detalle
                                                                                                de la Observaci&oacute;n:
                                                                                                <code>*</code></label>
                                                                                            <textarea id="detalleObs"
                                                                                                      style="resize:none;"
                                                                                                      name="detalleObs"
                                                                                                      class="form-control"
                                                                                                      rows="15"
                                                                                                      placeholder="Observaciones..."
                                                                                                      required></textarea>

                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <input type="hidden"
                                                                                               name="idFormRegAcceso"
                                                                                               id="idFormRegAcceso"
                                                                                               value="<?php echo $idFormRegAcceso ?>">
                                                                                        <input type="hidden"
                                                                                               name="idFormFuncCCAA"
                                                                                               id="idFormFuncCCAA"
                                                                                               value="<?php echo $idFormFuncCCAA ?>">
                                                                                        <input type="hidden"
                                                                                               name="folioFormRegAcceso"
                                                                                               id="folioFormRegAcceso"
                                                                                               value="<?php echo $folioFormRegAcceso; ?>">
                                                                                        <input type="hidden"
                                                                                               name="fechaObservacion"
                                                                                               id="fechaObservacion"
                                                                                               value="<?php echo $now ?>">
                                                                                        <button type="submit"
                                                                                                class="btn btn-primary">
                                                                                            Agregar
                                                                                        </button>
                                                                                        <button type="button"
                                                                                                class="btn btn-danger"
                                                                                                data-dismiss="modal">
                                                                                            Cancelar
                                                                                        </button>
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
                                                            ?>
                                                            <button type="button" title="Agregar Observacion"
                                                                    data-toggle="modal"
                                                                    data-target="#agregarObservacion<?php echo $idFormFuncCCAA ?>"
                                                                    class="btn btn-primary btn-sm"><i
                                                                        class="fas fa-plus"></i></button>

                                                            <?php
                                                            $cantidadRecibidos = contarObservacionesIngresadas();
                                                            ?>
                                                            <button type="button" title="Revisar Observacion"
                                                                    data-toggle="modal"
                                                                    data-target="#revisarObservacion<?php echo $idFormFuncCCAA ?>"
                                                                    class="btn btn-primary btn-sm"><i
                                                                        class="far fa-eye"></i></button>


                                                            <!-- Modal -->
                                                            <form method="post"
                                                                  action="../sdo-funciones/guardar-observacion-formatos.php">
                                                                <input type="hidden" name="idFormRegAcceso"
                                                                       id="idFormRegAcceso"
                                                                       value="<?php echo $idFormRegAcceso ?>">
                                                                <input type="hidden" name="idFormFuncCCAA"
                                                                       id="idFormFuncCCAA"
                                                                       value="<?php echo $idFormFuncCCAA ?>">
                                                                <input type="hidden" name="folioFormRegAcceso"
                                                                       id="folioFormRegAcceso"
                                                                       value="<?php echo $folioFormRegAcceso; ?>">
                                                                <input type="hidden" name="fechaObservacion"
                                                                       id="fechaObservacion" value="<?php echo $now ?>">
                                                                <div class="modal fade"
                                                                     id="agregarObservacion<?php echo $idFormFuncCCAA ?>"
                                                                     name="agregarObservacion<?php echo $idFormFuncCCAA ?>"
                                                                     tabindex="-1" role="dialog"
                                                                     aria-labelledby="exampleModalLabel"
                                                                     aria-hidden="true">

                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-primary">
                                                                                <h5 class="modal-title"
                                                                                    id="exampleModalLabel">Agregar
                                                                                    Observaci&oacute;n</h5>
                                                                                <button type="button" class="close"
                                                                                        data-dismiss="modal"
                                                                                        aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>

                                                                            <div class="modal-body">
                                                                                <div style="text-align:left;"
                                                                                     class="form-group">

                                                                                    <label for="exampleInputBorder"
                                                                                           style="font-size: 15px">Usuario
                                                                                        Creador: <code>*</code></label>
                                                                                    <input type="text"
                                                                                           class="form-control form-control-border"
                                                                                           readonly id="nomCreador"
                                                                                           name="nomCreador"
                                                                                           value="<?php echo $nomUsuario ?>"
                                                                                           required>
                                                                                    <br>

                                                                                    <label for="exampleInputBorder"
                                                                                           style="font-size: 15px">Detalle
                                                                                        de la Observaci&oacute;n: <code>*</code></label>
                                                                                    <textarea id="detalleObs"
                                                                                              style="resize:none;"
                                                                                              name="detalleObs"
                                                                                              class="form-control"
                                                                                              rows="15"
                                                                                              placeholder="Observaciones..."
                                                                                              required></textarea>

                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="submit"
                                                                                        class="btn btn-primary">Agregar
                                                                                </button>
                                                                                <button type="button"
                                                                                        class="btn btn-danger"
                                                                                        data-dismiss="modal">Cancelar
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>

                                                            <div class="modal fade" id="revisarObservacionTotal"
                                                                 tabindex="-1" role="dialog"
                                                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-xl" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header bg-secondary">
                                                                            <h5 class="modal-title"
                                                                                id="exampleModalLabel">Revisar todas las
                                                                                observaciones de este formato</h5>
                                                                            <button type="button" class="close"
                                                                                    data-dismiss="modal"
                                                                                    aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <table id="observacionTotal1"
                                                                                   class="table table-bordered table-sm">
                                                                                <thead style="background-color: indianred; color: white">
                                                                                <tr>
                                                                                    <th style="font-size: 15px">Fecha de
                                                                                        la Observacion
                                                                                    </th>
                                                                                    <th style="font-size: 15px">Creador
                                                                                        de la Observacion
                                                                                    </th>
                                                                                    <th style="font-size: 15px">Nombre
                                                                                        del Funcionario
                                                                                    </th>
                                                                                    <th style="font-size: 15px">Rut del
                                                                                        Funcionario
                                                                                    </th>
                                                                                    <th style="font-size: 15px">Detalle
                                                                                        de la Observacion
                                                                                    </th>
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
                                                                                    </tr>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                                </tbody>
                                                                                <tfoot style="background-color: indianred; color: white">
                                                                                <tr>
                                                                                    <th style="font-size: 15px">Fecha de
                                                                                        la Observacion
                                                                                    </th>
                                                                                    <th style="font-size: 15px">Creador
                                                                                        de la Observacion
                                                                                    </th>
                                                                                    <th style="font-size: 15px">Nombre
                                                                                        del Funcionario
                                                                                    </th>
                                                                                    <th style="font-size: 15px">Rut del
                                                                                        Funcionario
                                                                                    </th>
                                                                                    <th style="font-size: 15px">Detalle
                                                                                        de la Observacion
                                                                                    </th>
                                                                                </tr>
                                                                                </tfoot>
                                                                            </table>
                                                                            <div class="modal-footer">
                                                                                <button type="button"
                                                                                        class="btn btn-danger"
                                                                                        data-dismiss="modal">Cancelar
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="modal fade"
                                                                 id="revisarObservacion<?php echo $idFormFuncCCAA ?>"
                                                                 name="revisarObservacion<?php echo $idFormFuncCCAA ?>"
                                                                 tabindex="-1" role="dialog"
                                                                 aria-labelledby="exampleModalLabel" aria-hidden="true">

                                                                <div class="modal-dialog modal-xl" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header bg-primary">
                                                                            <h5 class="modal-title"
                                                                                id="exampleModalLabel">Revisar
                                                                                Observacion</h5>
                                                                            <button type="button" class="close"
                                                                                    data-dismiss="modal"
                                                                                    aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <table id="observaciones"
                                                                                   class="table table-bordered table-sm">
                                                                                <thead style="background-color: indianred; color: white">
                                                                                <tr>
                                                                                    <th style="font-size: 15px">Fecha de
                                                                                        la Observacion
                                                                                    </th>
                                                                                    <th style="font-size: 15px">Creador
                                                                                        de la Observacion
                                                                                    </th>
                                                                                    <th style="font-size: 15px">Detalle
                                                                                        de la Observacion
                                                                                    </th>
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
                                                                                    <th style="font-size: 15px">Fecha de
                                                                                        la Observacion
                                                                                    </th>
                                                                                    <th style="font-size: 15px">Creador
                                                                                        de la Observacion
                                                                                    </th>
                                                                                    <th style="font-size: 15px">Detalle
                                                                                        de la Observacion
                                                                                    </th>
                                                                                </tr>
                                                                                </tfoot>
                                                                            </table>
                                                                            <div class="modal-footer">
                                                                                <button type="button"
                                                                                        class="btn btn-danger"
                                                                                        data-dismiss="modal">Cancelar
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                <?php
                                                                }
                                                                ?>
                                                        </td>
                                                        <?php

                                                        ?>
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

                                                <thead class="bg-secondary">
                                                <tr>
                                                    <th style="vertical-align:middle; text-align:center;">N°:</th>
                                                    <th style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                                        NOMBRE DEL<br>FUNCIONARIO:
                                                    </th>
                                                    <th style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                                        RUT DEL<br>FUNCIONARIO:
                                                    </th>
                                                    <th style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                                        CALIDAD JURIDICA<br>DEL FUNCIONARIO:
                                                    </th>
                                                    <th style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                                        UNIDAD DEL<br>FUNCIONARIO:
                                                    </th>
                                                    <?php

                                                    for ($j = 1; $j <= $cantidadFormCCAA; $j++) {

                                                        $nomPuertaJ = $getPuertasFormulario['door' . $j . 'FormCCAA'];
                                                        $nomPuertaJ2 = mb_convert_case($nomPuertaJ, MB_CASE_UPPER, 'UTF-8');
                                                        ?>
                                                        <th style="white-space: nowrap; vertical-align:middle; text-align:center;"><?php echo $nomPuertaJ2 ?></th>
                                                        <?php
                                                    }
                                                    ?>

                                                    <th style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                                        INGRESADO EN ACTAS:
                                                    </th>
                                                    <th style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                                        INGRESADO EN SISTEMA CCAA:
                                                    </th>
                                                    <th style="vertical-align:middle; text-align:center;">PERMISOS
                                                        ENTREGADOS:
                                                    </th>
                                                    <th style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                                        OBSERVACIONES:
                                                    </th>
                                                </tr>
                                                </thead>
                                            </table>


                                        </div>
                                    </div>

                                </div>

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->


                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
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
<!-- Page specific script -->
<script>
    $(document).ready(function () {
        $('#observacionTotal1').DataTable({
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
    $(function () {

        $('#miTabla thead tr')
            .clone(true)
            .addClass('filters')
            .appendTo('#example2 thead');

        $('#miTabla').DataTable({
            "paging": true,
            "lengthChange": false,
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
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "search": "Buscar:",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            }
        });
    });
</script>

<!--
<script>
    $(function () {
        $('#miTabla1').DataTable({
            "paging": true,
            "lengthChange": false,
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
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "search": "Buscar:",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            }
        });
    });
</script>
-->

</body>

</html>