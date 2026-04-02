<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];
$idDepartamento = $_SESSION['idDepartamento'];
$userUsuario = $_SESSION['userUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);
$nomUsuario = $_SESSION['nomUsuario'];


date_default_timezone_set('America/Santiago');

if (isset($_REQUEST['idCategoria'])) {
    $idCategoria = intval($_REQUEST['idCategoria']);

    $sql = "SELECT * FROM `categorias_recepcion_conforme` WHERE id = $idCategoria";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $nombre = $row['nombre'];
    }

    $sqlContratoRecepcionConforme = "SELECT * FROM `datos_contrato_recepcion_conforme` WHERE idCategoria = $idCategoria";
    $resContratoRecepcionConforme = mysqli_query($conexion, $sqlContratoRecepcionConforme);
    while ($rowContratoRecepcionConforme = mysqli_fetch_array($resContratoRecepcionConforme)) {
        $idContrato = $rowContratoRecepcionConforme['id'];
        $Licitacion = $rowContratoRecepcionConforme['licitacion'];
        $servicioContratado = $rowContratoRecepcionConforme['servicioContratado'];
        $centroCosto = $rowContratoRecepcionConforme['centroCosto'];
        $nContrato = $rowContratoRecepcionConforme['nContrato'];
        $prorroga = $rowContratoRecepcionConforme['prorroga'];
        $idOrdenCompra = $rowContratoRecepcionConforme['idOrdenCompra'];
        $fechaPrestacionServicio = $rowContratoRecepcionConforme['fechaPrestacionServicio'];
        $unidadMedida = $rowContratoRecepcionConforme['unidadMedida'];
        $vigenciaConvenio = $rowContratoRecepcionConforme['vigenciaConvenio'];
        $vigenciaConvenioDetalle = $rowContratoRecepcionConforme['vigenciaConvenioDetalle'];
        $unidad = $rowContratoRecepcionConforme['unidad'];
        $titulo = $rowContratoRecepcionConforme['titulo'];

        $sqlMontos = "SELECT * FROM `datos_montos_recepcion_conforme` WHERE idContrato = $idContrato";
        $resMontos = mysqli_query($conexion, $sqlMontos);
        while ($rowMontos = $resMontos->fetch_assoc()) {
            $saldoDisponible = $rowMontos['saldoDisponible'];
            $aplicaMulta = $rowMontos['aplicaMulta'];
        }

        $sqlProveedor = "SELECT * FROM `datos_proveedor_recepcion_conforme` WHERE idContrato = $idContrato";
        $resProveedor = mysqli_query($conexion, $sqlProveedor);
        while ($rowProveedor = $resProveedor->fetch_assoc()) {
            $rutProveedor = $rowProveedor['rut'];
            $nombreProveedor = $rowProveedor['nombreProveedor'];
            $nombreAdministrador = $rowProveedor['nombreAdministrador'];

        }
    }


}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recepcion Conforme</title>

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
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"/>
    <!-- include summernote css-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- include summernote js-->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <style>
        .reset {
            all: revert;
        }

        #cargaCombustible,
        #medicionNormal {
            display: none;
        }

        .select2-container .select2-selection--single {
            height: calc(2.25rem + 2px);
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
        }

    </style>
    <script type="text/javascript">
        $(document).ready(function () {
            recargarListTecnicos();
            recargarListTecnicosPersonal();
            recargarNomDepartamento();

            $('#servicios').change(function () {
                recargarNomDepartamento();
            });

            $('#departamentoSolicitante').change(function () {
                recargarListTecnicos();


            });

            $('#depaPersonal').change(function () {
                recargarListTecnicosPersonal();

            });

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

            function recargarListTecnicos() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-tecnicos/cargarTecnicosYJefes.php",
                    data: "idDepartamento=" + $('#departamentoSolicitante').val(),
                    success: function (r) {

                        if (r == 0) {

                            var cadena = "";

                            cadena = cadena + '<div class="input-group-prepend">';

                            cadena = cadena + '<span class="input-group-text"><i class="fas fa-user"></i></span>';

                            cadena = cadena + '<span class="input-group-text w-100">No Existen Tecnicos en este Departamento.</span>';

                            cadena = cadena + '</div>';

                            $('#htmlSelect').html(cadena);

                            var btnDelCentral = document.getElementById("guardarRecepcion");

                            btnDelCentral.removeAttribute("enabled", "");

                            btnDelCentral.setAttribute("disabled", "");

                        } else if (r == "error" || r == "ERROR" || r == "Error") {

                            var cadena = "";

                            cadena = cadena + '<div class="input-group-prepend">';

                            cadena = cadena + '<span class="input-group-text"><i class="fas fa-user"></i></span>';

                            cadena = cadena + '<span class="input-group-text w-100">Error al Obtener Datos.</span>';

                            cadena = cadena + '</div>';

                            $('#htmlSelect').html(cadena);

                            var btnDelCentral = document.getElementById("guardarRecepcion");

                            btnDelCentral.removeAttribute("enabled", "");

                            btnDelCentral.setAttribute("disabled", "");

                        } else {
                            $('#htmlSelect').html(r);

                            var btnDelCentral = document.getElementById("guardarRecepcion");

                            btnDelCentral.removeAttribute("disabled", "");

                            btnDelCentral.setAttribute("enabled", "");

                        }

                    }
                });

            };

            function recargarListTecnicosPersonal() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-tecnicos/cargarTecnicosPersonal.php",
                    data: "idDepartamento=" + $('#depaPersonal').val(),
                    success: function (r) {

                        if (r == 0) {

                            var cadena = "";

                            cadena = cadena + '<div class="input-group-prepend">';

                            cadena = cadena + '<span class="input-group-text"><i class="fas fa-user"></i></span>';

                            cadena = cadena + '<span class="input-group-text w-100">No Existen Tecnicos en este Departamento.</span>';

                            cadena = cadena + '</div>';

                            $('#htmlPersonal').html(cadena);

                            var btnDelCentral = document.getElementById("guardarRecepcion");

                            btnDelCentral.removeAttribute("enabled", "");

                            btnDelCentral.setAttribute("disabled", "");

                        } else if (r == "error" || r == "ERROR" || r == "Error") {

                            var cadena = "";

                            cadena = cadena + '<div class="input-group-prepend">';

                            cadena = cadena + '<span class="input-group-text"><i class="fas fa-user"></i></span>';

                            cadena = cadena + '<span class="input-group-text w-100">Error al Obtener Datos.</span>';

                            cadena = cadena + '</div>';

                            $('#htmlPersonal').html(cadena);

                            var btnDelCentral = document.getElementById("guardarRecepcion");

                            btnDelCentral.removeAttribute("enabled", "");

                            btnDelCentral.setAttribute("disabled", "");

                        } else {
                            $('#htmlPersonal').html(r);

                            var btnDelCentral = document.getElementById("guardarRecepcion");

                            btnDelCentral.removeAttribute("disabled", "");

                            btnDelCentral.setAttribute("enabled", "");

                        }

                    }
                });

            };


        });
    </script>

    <script>
        $(document).ready(function () {


            // Show div by removing inline display none style rule
            $(".show-realizar").click(function () {
                $("#rowMenu2").show();
                $("#rowMenu1").hide();
                $("#listarAC").show();
                $("#listarAC2").show();
                $("#registrarAC").hide();
                $("#registrarAC2").hide();
            });

            // Show div by removing inline display none style rule
            $(".show-listar").click(function () {
                $("#rowMenu2").hide();
                $("#rowMenu1").show();
                $("#listarAC").hide();
                $("#listarAC2").hide();
                $("#registrarAC").show();
                $("#registrarAC2").show();
            });
        });
    </script>
    <script>
        function DoCheckUncheckDisplay(d, dchecked, dunchecked) {
            if (d.checked == true) {
                document.getElementById(dchecked).style.display = "block";
                document.getElementById(dunchecked).style.display = "none";
            } else {
                document.getElementById(dchecked).style.display = "none";
                document.getElementById(dunchecked).style.display = "block";
            }
        }
    </script>
    <script>
        $(document).ready(function () {
            $('textarea').on('keyup keypress', function () {
                $(this).height(0);
                $(this).height(this.scrollHeight);
            });
        });
    </script>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/mesaayuda-sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Recepcion Conforme - <?php echo $nombre ?></h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="index.php" id="hrefInicio" class="text-dark">Inicio</a>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">

                                <div class="col-lg-3 col-md-6 col-12 float-right" id="volverAtras2" name="volverAtras2">
                                    <!-- small box -->
                                    <a href="recepcion_conforme.php" style="float: right;" class="btn bg-gradient-red float-right btn-md text-white"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                </div>
                                <br>
                                <br>

                            </div>

                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body" style="text-align:center;">
                                <div class="row text-center" style="display: flex; justify-content:center;">
                                    <div id="registrarAC2" name="registrarAC2" class="col-lg-3 col-md-6 col-12 text-center">
                                        <!-- small box -->
                                        <button id="registrarAC" style="color: white" name="registrarAC" class="btn btn-block bg-gradient-info btn-md show-realizar text-black text-center">
                                            <i class="nav-icon fas fa-book"></i><br>Registrar Recepcion Conforme
                                        </button>
                                    </div>
                                    &nbsp;
                                    <div class="col-lg-3 col-md-6 col-12 text-center" id="listarAC2" style="display:none;" name="listarAC2">
                                        <!-- small box -->
                                        <button id="listarAC" style="display:none; color: white" name="listarAC" class="btn btn-block bg-gradient-info btn-md text-black text-center show-listar">
                                            <i class="nav-icon fas fa-book"></i><br>Ver Listado
                                        </button>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-12 text-center">
                                        <button class="btn btn-block bg-gradient-info btn-md text-black text-center">
                                            <label style="color: white">Saldo Disponible: </label><br>
                                            <?php
                                            $sqlSaldoDisponible = "
                                                        SELECT *
                                                        FROM datos_montos_recepcion_conforme
                                                        WHERE idCategoria = $idCategoria
                                                        ORDER BY id DESC
                                                        LIMIT 1
                                                    ";
                                            $resSaldoDisponible = mysqli_query($conexion, $sqlSaldoDisponible);
                                            if ($rowSaldoDisponible = $resSaldoDisponible->fetch_assoc()) {
                                                echo '$ ' . number_format($rowSaldoDisponible['saldoDisponible'], 0, ',', '.');
                                            } else {
                                                echo '$ 0';
                                            }
                                            ?>
                                        </button>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="rowMenu1" name="rowMenu1">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>1.- Listado</h5>
                                <br>
                                <div class="table-responsive">
                                    <table style="font-size:80%" id="example2" class="table table-bordered cell-border">
                                        <thead class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle;">
                                                <center>FECHA</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>LICITACIÓN</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>N° CONTRATO</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>FECHA PRESTACIÓN SERVICIO</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>VIGENCIA DEL CONVENIO</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Acciones:</center>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody class="text-center">
                                        <?php
                                        $sql2 = "SELECT * FROM `datos_contrato_recepcion_conforme` WHERE idCategoria = $idCategoria";
                                        $res2 = mysqli_query($conexion, $sql2);
                                        while ($row2 = mysqli_fetch_array($res2)) {
                                            $idContratoVar = $row2['id'];
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php
                                                    echo date('m-Y', strtotime($row2['fechaGeneral']));
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php echo $row2['licitacion']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row2['nContrato']; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    echo date('m-Y', strtotime($row2['fechaPrestacionServicio']));
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    echo $row2['vigenciaConvenio'] . '<br>' . $row2['vigenciaConvenioDetalle'];
                                                    ?>
                                                </td>

                                                <td class="text-center align-middle">
                                                    <div class="row g-2 justify-content-center text-center">                                                        <!-- Formulario Editar -->
                                                        <div class="col-auto">
                                                            <form action="editar_recepcion_conforme.php" method="post">
                                                                <input type="hidden" name="idContrato" value="<?php echo $idContratoVar ?>"> <!-- ID del registro -->
                                                                <input type="hidden" name="idCategoria" value="<?php echo $idCategoria ?>"> <!-- ID del registro -->
                                                                <button type="submit" class="btn btn-warning">
                                                                    <i class="fas fa-pen"></i>
                                                                </button>
                                                            </form>
                                                        </div>

                                                        <!-- Formulario Ver -->
                                                        <div class="col-auto">
                                                            <?php
                                                            $ipCliente = $_SERVER['REMOTE_ADDR'];
                                                            $url = '../sdo-pdf/ver_recepcion_conforme.php';
//                                                            if ($ipCliente == '10.69.250.151') {
//                                                                $url = '../sdo-pdf/ver_recepcion_conforme2.php';
//                                                            }
                                                            ?>
                                                            <form action="<?= $url; ?>" target="_blank" method="post">
                                                                <input type="hidden" name="idContrato" value="<?php echo $idContratoVar ?>"> <!-- ID del registro -->
                                                                <input type="hidden" name="idCategoria" value="<?php echo $idCategoria ?>"> <!-- ID del registro -->
                                                                <input type="hidden" name="idCategoria" value="<?php echo $idCategoria ?>"> <!-- ID del registro -->
                                                                <button type="submit" class="btn btn-info">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                        <div class="col-auto">
                                                            <!-- Button trigger modal -->
                                                            <button title="Agregar Observacion" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal<?php echo $idContratoVar ?>">
                                                                <i class="fas fa-plus"></i>
                                                            </button>

                                                            <!-- Modal -->
                                                            <div class="modal fade" id="exampleModal<?php echo $idContratoVar ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header bg-primary">
                                                                            <h5 class="modal-title" id="exampleModalLabel"> Agregar Observaciones</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <form action="../sdo-funciones/guardar_observacion_recepcion_conforme.php" method="post">
                                                                            <input type="hidden" name="idContrato" value="<?php echo $idContratoVar ?>"> <!-- ID del registro -->
                                                                            <input type="hidden" name="idCategoria" value="<?php echo $idCategoria ?>"> <!-- ID del registro -->
                                                                            <input type="hidden" name="idCategoria" value="<?php echo $idCategoria ?>"> <!-- ID del registro -->
                                                                            <div class="modal-body">
                                                                                <label>Fecha</label>
                                                                                <?php $now = date('Y-m-d H:i:s') ?>
                                                                                <input name="fecha" readonly type="text" class="form-control" value="<?php echo $now ?>">
                                                                                <br>
                                                                                <label>Observaciones</label>
                                                                                <textarea name="obs" class="form-control" rows="15" style="resize: none"></textarea>

                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                                <button type="submit" class="btn btn-primary">Guardar</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="col-auto">
                                                            <button title="Listar Observacion" type="button" class="btn btn-primary" data-toggle="modal" data-target="#listadoObservacion<?php echo $idContratoVar ?>">
                                                                <i class="fas fa-file-alt"></i>
                                                            </button>
                                                            <div class="modal fade" id="listadoObservacion<?php echo $idContratoVar ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-xl" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header bg-primary">
                                                                            <h5 class="modal-title" id="exampleModalLabel">Listado de Observaciones</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <table id="listadoObservacion" class="table table-bordered cell-border" style="width:100%">
                                                                                <thead class="bg-secondary">
                                                                                <tr>
                                                                                    <td>Fecha</td>
                                                                                    <td>Observación</td>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                <?php
                                                                                $sqlListado = "SELECT * FROM `observaciones_recepcion_conforme` WHERE idCategoria = $idCategoria AND idContrato = $idContratoVar";
                                                                                $resListado = mysqli_query($conexion, $sqlListado);
                                                                                while ($rowListado = $resListado->fetch_assoc()) {
                                                                                    ?>
                                                                                    <tr>
                                                                                        <td><?php echo date("d-m-Y H:i:s", strtotime($rowListado['fecha'])) ?></td>
                                                                                        <td><?php echo nl2br($rowListado['obs']) ?></td>
                                                                                    </tr>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                                </tbody>
                                                                                <tfoot class="bg-secondary">
                                                                                <tr>
                                                                                    <td>Fecha</td>
                                                                                    <td>Observación</td>
                                                                                </tr>
                                                                                </tfoot>
                                                                            </table>

                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                        <tfoot class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle;">
                                                <center>FECHA</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>LICITACION</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>N° CONTRATO</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>FECHA PRESTACION SERVICIO</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>VIGENCIA DEL CONVENIO</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Acciones:</center>
                                            </th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="rowMenu2" name="rowMenu2" style="display:none;">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>2.- Registrar</h5>
                                <br>
                                <form action="../sdo-funciones/guardar_recepcion_conforme.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="idCategoria" value="<?php echo $idCategoria ?>">
                                    <fieldset class="border p-3">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Unidad:
                                                    <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="text" required class="form-control" id="unidad" value="<?php echo $unidad ?>" name="unidad">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Establecimiento:
                                                    <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="text" required class="form-control" id="Establecimiento" name="Establecimiento" value="HOSPITAL SAN MARTIN DE QUILLOTA - HOSPITAL BIPROVINCIAL QUILLOTA PETORCA" READONLY placeholder="Nombre del proceso">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Fecha:
                                                    <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="month" required class="form-control" id="Fecha" name="Fecha" placeholder="Numero del Memo">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Titulo Recepcion Conforme:
                                                    <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="text" value="<?php echo $titulo ?>" required class="form-control" id="titulo" name="titulo" placeholder="Titulo">
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <br>
                                    <fieldset class="border p-3">
                                        <h5>Datos del Contrato:</h5>
                                        <br>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Licitacion:
                                                    <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" id="Licitacion" name="Licitacion" value="<?php echo $Licitacion ?>" placeholder="Licitacion">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Descripción del Servicio Contratado:
                                                    <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" id="servicioContratado" name="servicioContratado" value="<?php echo $servicioContratado ?>" placeholder="Servicio Contratado">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Centro de costo que recibe el servicio:
                                                    <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" id="centroCosto" name="centroCosto" value="<?php echo $centroCosto ?>" placeholder="Centro de Costo">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">N° Contrato:
                                                    <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" id="nContrato" name="nContrato" value="<?php echo $nContrato ?>" placeholder="Numero de Contrato">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Prórroga:
                                                    <code>*</code></label>

                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-pen-alt"></i>
                                                        </span>
                                                    </div>
                                                    <div class="input-group-text">
                                                        <?php
                                                        if ($prorroga == 'on') {
                                                            ?>
                                                            <input type="checkbox" checked id="prorroga" name="prorroga">
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <input type="checkbox" id="prorroga" name="prorroga">
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">ID Orden de Compra:
                                                    <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" id="idOrdenCompra" name="idOrdenCompra" value="<?php echo $idOrdenCompra ?>" placeholder="ID Orden de Compra">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Fecha de Prestación del Servicio:
                                                    <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <?php
                                                    if ($fechaPrestacionServicio == '') {
                                                        ?>
                                                        <input type="month" class="form-control" id="FechaPrestacion"
                                                                name="FechaPrestacion">
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <input type="month" class="form-control" id="FechaPrestacion"
                                                                name="FechaPrestacion"
                                                                value="<?php echo date('Y-m', strtotime($fechaPrestacionServicio)); ?>">
                                                        <?php
                                                    }
                                                    ?>


                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Unidad de Medida General (Pesos Chilenos):
                                                    <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="number" class="form-control" id="unidadMedida" value="<?php echo $unidadMedida ?>" name="unidadMedida">

                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Saldo Disponible Anterior (Pesos Chilenos):
                                                    <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="number" class="form-control" id="saldoDisponibleAnterior" value="<?php echo $saldoDisponible ?>" name="saldoDisponibleAnterior">


                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Vigencia del Convenio:
                                                    <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" id="vigenciaConvenio" value="<?php echo $vigenciaConvenio ?>" name="vigenciaConvenio">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Vigencia del Convenio Detallado:
                                                    <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" id="vigenciaConvenio2 " value="<?php echo $vigenciaConvenioDetalle ?>" name="vigenciaConvenio2">
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <br>
                                    <fieldset class="border p-3">
                                        <h5>Datos del Proveedor:</h5>
                                        <br>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">RUT (Escriba el rut sin puntos ni guion):
                                                    <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="text" required class="form-control" id="rutProveedor" value="<?php echo $rutProveedor ?>" name="rutProveedor">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Nombre del Proveedor:
                                                    <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="text" required class="form-control" id="nombreProveedor" value="<?php echo $nombreProveedor ?>" name="nombreProveedor">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Nombre del administrador de contrato o representante legal:
                                                    <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="text" required class="form-control" id="nombreAdministrador" value="<?php echo $nombreAdministrador ?>" name="nombreAdministrador">
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <br>
                                    <fieldset class="border p-3">
                                        <h5>Datos de la contraparte técnica:</h5>
                                        <br>
                                        <button type="button" id="btnAgregarFuncionario" style="float: right" class="bg-info btn">
                                            + Agregar Funcionario
                                        </button>
                                        <br>
                                        <br>
                                        <div class="row" id="contenedorFuncionarios">

                                            <!-- Funcionario inicial -->
                                            <div class="funcionario border rounded p-3 mb-3">
                                                <?php
                                                $sqlTecnica = "SELECT nombreFuncionario, cargo 
               FROM datos_tecnica_recepcion_contrato 
               WHERE idContrato = $idContrato";

                                                $resTecnica = mysqli_query($conexion, $sqlTecnica);

                                                /* SI HAY REGISTROS */
                                                if ($resTecnica && $resTecnica->num_rows > 0) {

                                                    while ($fila = $resTecnica->fetch_assoc()) {
                                                        $nombreFuncionario = $fila['nombreFuncionario'];
                                                        $cargo = $fila['cargo'];
                                                        ?>

                                                        <div class="funcionario border rounded p-3 mb-3">
                                                            <div class="row">
                                                                <div class="form-group col-md-6">
                                                                    <label>Nombre del Funcionario
                                                                        <code>*</code></label>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                                        </div>
                                                                        <input type="text"
                                                                                class="form-control"
                                                                                name="nombreContraparteTecnica[]"
                                                                                value="<?php echo htmlspecialchars($nombreFuncionario); ?>"
                                                                                required>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group col-md-6">
                                                                    <label>Cargo del Funcionario
                                                                        <code>*</code></label>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                                        </div>
                                                                        <input type="text"
                                                                                class="form-control"
                                                                                name="cargoContraparteTecnica[]"
                                                                                value="<?php echo htmlspecialchars($cargo); ?>"
                                                                                required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <?php
                                                    }

                                                    /* SI NO HAY REGISTROS */
                                                } else {
                                                    ?>
                                                    <div class="funcionario border rounded p-3 mb-3">
                                                        <div class="row">
                                                            <div class="form-group col-md-6">
                                                                <label>Nombre del Funcionario
                                                                    <code>*</code></label>
                                                                <input type="text" class="form-control" name="nombreContraparteTecnica[]" required>
                                                            </div>

                                                            <div class="form-group col-md-6">
                                                                <label>Cargo del Funcionario
                                                                    <code>*</code></label>
                                                                <input type="text" class="form-control" name="cargoContraparteTecnica[]" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                                ?>


                                            </div>
                                            <input type="hidden" name="contadorFuncionarios" id="contadorFuncionarios" value="1">
                                        </div>
                                    </fieldset>
                                    <br>
                                    <fieldset class="border p-3">
                                        <h5>Ejecución del servicio en caso de contrato:</h5>
                                        <br>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Valor Mensual:
                                                    <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="number" required class="form-control" id="valorMensual" name="valorMensual">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Saldo Disponible:
                                                    <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="number" required class="form-control" readonly id="saldoDisponible" name="saldoDisponible">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Aplica Multas:
                                                    <code>*</code></label>

                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-pen-alt"></i>
                                                        </span>
                                                    </div>
                                                    <?php
                                                    if ($aplicaMulta == 0) {
                                                        ?>
                                                        <div class="input-group-text">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="aplicaMultas" id="siMultas" value="1" required>
                                                                <label class="form-check-label" for="siMultas">Sí</label>
                                                            </div>

                                                            <div class="form-check form-check-inline ml-3">
                                                                <input class="form-check-input" checked type="radio" name="aplicaMultas" id="noMultas" value="0">
                                                                <label class="form-check-label" for="noMultas">No</label>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    } else if ($aplicaMulta == 1) {
                                                        ?>
                                                        <div class="input-group-text">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" checked type="radio" name="aplicaMultas" id="siMultas" value="1" required>
                                                                <label class="form-check-label" for="siMultas">Sí</label>
                                                            </div>

                                                            <div class="form-check form-check-inline ml-3">
                                                                <input class="form-check-input" type="radio" name="aplicaMultas" id="noMultas" value="0">
                                                                <label class="form-check-label" for="noMultas">No</label>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <div class="input-group-text">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="aplicaMultas" id="siMultas" value="1" required>
                                                                <label class="form-check-label" for="siMultas">Sí</label>
                                                            </div>

                                                            <div class="form-check form-check-inline ml-3">
                                                                <input class="form-check-input" type="radio" name="aplicaMultas" id="noMultas" value="0">
                                                                <label class="form-check-label" for="noMultas">No</label>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>

                                                </div>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Observaciones:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <textarea class="form-control" name="detalleMultas" style="resize: none" rows="5"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <br>
                                    <button type="submit" id="btnRegistrar" class="btn btn-block bg-gradient-success btn-md text-black text-center">Registrar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
</body>
<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<script src="js/dark-mode.js"></script>
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
<script>
    let contador = 1;

    const inputContador = document.getElementById('contadorFuncionarios');
    const contenedor = document.getElementById('contenedorFuncionarios');

    document.getElementById('btnAgregarFuncionario').addEventListener('click', function () {
        contador++;
        inputContador.value = contador;

        const html = `
        <div class="funcionario border rounded p-3 mb-3">
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Nombre del Funcionario <code>*</code></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                        </div>
                        <input type="text" class="form-control" name="nombreContraparteTecnica[]" required>
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label>Cargo del Funcionario <code>*</code></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                        </div>
                        <input type="text" class="form-control" name="cargoContraparteTecnica[]" required>
                    </div>
                </div>

                <div class="col-12 text-right">
                    <button type="button" class="btn btn-danger btn-sm btnEliminarFuncionario">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </div>
            </div>
        </div>
        `;

        contenedor.insertAdjacentHTML('beforeend', html);
    });

    // Eliminar funcionario (delegación de eventos)
    contenedor.addEventListener('click', function (e) {
        if (e.target.closest('.btnEliminarFuncionario')) {
            e.target.closest('.funcionario').remove();

            if (contador > 1) {
                contador--;
                inputContador.value = contador;
            }
        }
    });
</script>

<input type="hidden" name="contadorFuncionarios" id="contadorFuncionarios" value="1">
<script>
    function calcularSaldo() {
        const unidad = parseFloat(document.getElementById('saldoDisponibleAnterior').value) || 0;
        const valor = parseFloat(document.getElementById('valorMensual').value) || 0;

        let saldo = unidad - valor;

        // Evitar valores negativos
        saldo = Math.max(0, saldo);

        // Mostrar con 2 decimales
        document.getElementById('saldoDisponible').value = saldo
    }

    document.getElementById('saldoDisponibleAnterior').addEventListener('input', calcularSaldo);
    document.getElementById('valorMensual').addEventListener('input', calcularSaldo);
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
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            }
        });
    });
</script>

</html>
