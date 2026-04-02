<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];
$userUsuario = $_SESSION['userUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);
$idDepartamento = $_SESSION['idDepartamento'];


date_default_timezone_set('America/Santiago');

if (isset($_POST['idTG'])) {
    $idTG = $_POST['idTG'];
    $sql = "SELECT * FROM trabajos_general WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $solicitanteTG = $row['solicitanteTG'];
        $detalleTG = $row['detalleTG'];
        $fechaTG = $row['fechaTG'];
        $estadoTG = $row['estadoTG'];
        $turnoTG = $row['turnoTG'];
        $tecnico1 = $row['tecnico1'];
        $folioTG = $row['folioTG'];
        $fechaProgra = $row['fechaProgramacion'];
        $ticketAsociado = $row['ticketAsociado'];
        $detalleTurnoCamara = $row['detalleTurnoCamara'];
        $detalleTurnoLLE = $row['detalleTurnoLLE'];
        $detalleTurnoCA = $row['detalleTurnoCA'];
        $detalleTurnoRonda = $row['detalleTurnoRondas'];
        $detalleOtros = $row['detalleOtros'];
        $tipodeTrabajo = $row['tipoTrabajo'];
        $departamentos = $row['departamento'];


    }


}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modificar Trabajo General - <?php echo $folioTG ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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

    <style>
        .reset {
            all: revert;
        }

        #cargaCombustible,
        #medicionNormal {
            display: none;
        }
    </style>

    <script type="text/javascript">
        $(document).ready(function () {
            recargarListTecnicos();
            recargarListTecnicosPersonal();

            $('#departamentoSolicitante').change(function () {
                recargarListTecnicos();

            });

            $('#depaPersonal').change(function () {
                recargarListTecnicosPersonal();

            });


            function recargarListTecnicos() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-tecnicos/cargarJefesNombres.php",
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
        function selectNit(e) {
            var nit = e.target.selectedOptions[0].getAttribute("data-nit")
            document.getElementById("nit").value = nit;
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


    <script>


        function visible(valor) {
            console.log(valor);
            document.getElementById('departamentosecreto').value = valor;


            var otcontrolDiv = document.getElementById("otcontrol");

            var otespecialDiv = document.getElementById("otespecial");


            // alert(valor);
            verificarDIVs(valor);

            if (valor == 1) {

                otcontrolDiv.style.display = "block";
                otespecialDiv.style.display = "none";

            } else {

                otcontrolDiv.style.display = "none";
                otespecialDiv.style.display = "block";
            }
        }


    </script>
</head>


<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed" onload="verificarDIVs(<?php echo $departamentos; ?>)" >
<!-- Site wrapper -->
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/admtec-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Modificar Trabajo General <?php echo $folioTG ?></h4>
                    </div>
                    <?php


                    if (isset($_GET['exitoIngresoTG'])) {
                        ?>
                        <script>
                            Swal.fire(
                                'Exito!',
                                'Orden de Trabajo ingresada correctamente!',
                                'success'
                            )
                        </script>
                        <?php
                    }

                    if (isset($_GET['error1TG'])) {
                        ?>
                        <script>
                            Swal.fire(
                                'Error!',
                                'La subida del Archivo fallo.Porfavor reintente!',
                                'danger'
                            )
                        </script>
                        <?php
                    }

                    if (isset($_GET['error2TG'])) {
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'El archivo no se encuentra permitido.!'
                            })
                        </script>
                        <?php

                    }

                    if (isset($_GET['error3TG'])) {
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'No se a subido un archivo. Porfavor suba un archivo adjunto'
                            })
                        </script>
                        <?php

                    }

                    if (isset($_GET['errorTecnicoTG'])) {
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: '¡No se puede colocar el técnico dos veces.!'
                            })
                        </script>
                        <?php

                    }

                    if (isset($_GET['modificacionok'])) {
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Exito',
                                text: 'Se ha modificado correctamente'
                            })
                        </script>
                        <?php

                    }

                    ?>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio"
                                                           class="text-dark">Inicio</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>


        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <!-- Timelime example  -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">

                                <div class="col-lg-3 col-md-6 col-12 float-right" id="volverAtras2"
                                     name="volverAtras2">
                                    <!-- small box -->
                                    <a href="detalle_ordenes_trabajo.php" style="float: right;"
                                       class="btn bg-gradient-red float-right btn-md text-white"><i
                                                class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
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

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>


                <?php

                if (isset($_GET['exitoAgregarAC'])) {
                    ?>
                    <script>
                        Swal.fire({
                            title: 'Medici&oacute;n de Combustible Agregada con &Eacute;xito.',
                            confirmButtonText: 'Cerrar',
                            icon: 'success',
                        });
                    </script>
                    <?php
                }

                if (isset($_GET['errorAgregarAC'])) {
                    ?>
                    <script>
                        Swal.fire({
                            title: 'Error al Ingresar Medici&oacute;n de Combustible.',
                            confirmButtonText: 'Cerrar',
                            icon: 'error',
                        });
                    </script>
                    <?php
                }
                ?>

                <div class="row" id="rowMenu2" name="rowMenu2">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="../sdo-funciones/modificar-trabajo-general-admin.php" method="POST"
                                      enctype="multipart/form-data">
                                    <legend class="w-auto">&nbsp;Informaci&oacute;n del Trabajo General:&nbsp;&nbsp;
                                    </legend>
                                    <fieldset class="border p-3">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <br>
                                                <label for="exampleInputTurno">Fecha de Creacion:
                                                    <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-calendar"></i></span>
                                                    </div>
                                                    <?php
                                                    date_default_timezone_set('America/Santiago');
                                                    ?>
                                                    <input type="datetime" class="form-control" readonly="readonly"
                                                           name="fechaOrden" id="fechaOrden"
                                                           value="<?php echo $fechaTG ?>">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <br>
                                                <label for="exampleInputTurno">Fecha de Programacion del Trabajo:
                                                    <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-calendar"></i></span>
                                                    </div>
                                                    <?php
                                                    date_default_timezone_set('America/Santiago');
                                                    ?>
                                                    <input type="date" class="form-control" name="fechaProgramado"
                                                           id="fechaProgramado" value="<?php echo $fechaProgra ?>">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Ticket Asociado: </label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-ticket-alt"></i></span>
                                                    </div>
                                                    <input type="number" class="form-control"
                                                           name="ticketAsociado" id="ticketAsociado"
                                                           value="<?php echo $ticketAsociado ?>">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputCodigoTarjeta">Tipo de Trabajo:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-th-list"></i></span>
                                                    </div>
                                                    <select name="tipoTrabajo" class="form-control"
                                                            id="tipoTrabajo">
                                                        <?php
                                                        if ($tipodeTrabajo == 'MPE') {
                                                            ?>
                                                            <option value="" disabled>-- Seleccione --</option>
                                                            <option value="MPE" selected>MPE - Mantenimiento Preventivo
                                                                Externo
                                                            </option>
                                                            <option value="MRE">MRE - Mantenimiento Reparativo Externo
                                                            </option>
                                                            <option value="MPI">MPI - Mantenimiento Preventivo Interno
                                                            </option>
                                                            <option value="MRI">MRI - Mantenimiento Reparativo Interno
                                                            </option>
                                                            <option value="OM">OM - Obras Menores</option>
                                                            <option value="IS">IS - Inspección/Supervisión</option>
                                                            <?php
                                                        } else if ($tipodeTrabajo == 'MRE') {
                                                            ?>
                                                            <option value="" disabled>-- Seleccione --</option>
                                                            <option value="MPE">MPE - Mantenimiento Preventivo Externo
                                                            </option>
                                                            <option value="MRE" selected>MRE - Mantenimiento Reparativo
                                                                Externo
                                                            </option>
                                                            <option value="MPI">MPI - Mantenimiento Preventivo Interno
                                                            </option>
                                                            <option value="MRI">MRI - Mantenimiento Reparativo Interno
                                                            </option>
                                                            <option value="OM">OM - Obras Menores</option>
                                                            <option value="IS">IS - Inspección/Supervisión</option>
                                                            <?php
                                                        } else if ($tipodeTrabajo == 'MPI') {
                                                            ?>
                                                            <option value="" disabled>-- Seleccione --</option>
                                                            <option value="MPE">MPE - Mantenimiento Preventivo Externo
                                                            </option>
                                                            <option value="MRE">MRE - Mantenimiento Reparativo Externo
                                                            </option>
                                                            <option value="MPI" selected>MPI - Mantenimiento Preventivo
                                                                Interno
                                                            </option>
                                                            <option value="MRI">MRI - Mantenimiento Reparativo Interno
                                                            </option>
                                                            <option value="OM">OM - Obras Menores</option>
                                                            <option value="IS">IS - Inspección/Supervisión</option>
                                                            <?php
                                                        } else if ($tipodeTrabajo == 'MRI') {
                                                            ?>
                                                            <option value="" disabled>-- Seleccione --</option>
                                                            <option value="MPE">MPE - Mantenimiento Preventivo Externo
                                                            </option>
                                                            <option value="MRE">MRE - Mantenimiento Reparativo Externo
                                                            </option>
                                                            <option value="MPI">MPI - Mantenimiento Preventivo Interno
                                                            </option>
                                                            <option value="MRI" selected>MRI - Mantenimiento Reparativo
                                                                Interno
                                                            </option>
                                                            <option value="OM">OM - Obras Menores</option>
                                                            <option value="IS">IS - Inspección/Supervisión</option>
                                                            <?php
                                                        } else if ($tipodeTrabajo == 'OM') {
                                                            ?>
                                                            <option value="" disabled>-- Seleccione --</option>
                                                            <option value="MPE">MPE - Mantenimiento Preventivo Externo
                                                            </option>
                                                            <option value="MRE">MRE - Mantenimiento Reparativo Externo
                                                            </option>
                                                            <option value="MPI">MPI - Mantenimiento Preventivo Interno
                                                            </option>
                                                            <option value="MRI">MRI - Mantenimiento Reparativo Interno
                                                            </option>
                                                            <option value="OM" selected>OM - Obras Menores</option>
                                                            <option value="IS">IS - Inspección/Supervisión</option>
                                                            <?php
                                                        } else if($tipodeTrabajo == 'IS') {
                                                            ?>
                                                            <option value="" disabled>-- Seleccione --</option>
                                                            <option value="MPE">MPE - Mantenimiento Preventivo Externo
                                                            </option>
                                                            <option value="MRE">MRE - Mantenimiento Reparativo Externo
                                                            </option>
                                                            <option value="MPI">MPI - Mantenimiento Preventivo Interno
                                                            </option>
                                                            <option value="MRI">MRI - Mantenimiento Reparativo Interno
                                                            </option>
                                                            <option value="OM">OM - Obras Menores</option>
                                                            <option value="IS" selected>IS - Inspección/Supervisión
                                                            </option>
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <option value="" disabled selected>-- Seleccione --</option>
                                                            <option value="MPE">MPE - Mantenimiento Preventivo Externo
                                                            </option>
                                                            <option value="MRE">MRE - Mantenimiento Reparativo Externo
                                                            </option>
                                                            <option value="MPI">MPI - Mantenimiento Preventivo Interno
                                                            </option>
                                                            <option value="MRI">MRI - Mantenimiento Reparativo Interno
                                                            </option>
                                                            <option value="OM">OM - Obras Menores</option>
                                                            <option value="IS" >IS - Inspección/Supervisión
                                                            </option>
                                                            <?php
                                                        }
                                                        ?>

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Persona Solicitante:
                                                    <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-user"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" required
                                                           name="personaSolicitante" id="personaSolicitante"
                                                           value="<?php echo $solicitanteTG ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Turno: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-users"></i></span>
                                                    </div>
                                                    <select name="turno" id="turno" class="form-control" required>
                                                        <?php
                                                        if ($turnoTG == 'Personal Diurno') {
                                                            ?>
                                                            <option value="Personal Diurno" selected>Personal Diurno
                                                            </option>
                                                            <option value="Personal Nocturno">Personal Nocturno</option>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <option value="Personal Nocturno" selected>Personal Nocturno
                                                            </option>
                                                            <option value="Personal Diurno">Personal Diurno</option>

                                                            <?php
                                                        }
                                                        ?>

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Funcionario Asignado:
                                                    <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-users"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" readonly
                                                           name="tecnicoRecepcionador" id="tecnicoRecepcionador"
                                                           value="<?php echo $tecnico1 ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Departamentos: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                    </div>
                                                    <select name="departamentoSolicitante" id="departamentoSolicitante" onchange="visible(this.value)" class="form-control">
                                                        <option value="" selected disabled>Seleccione</option>
                                                        <?php
                                                        $sqlDepa = "SELECT * FROM departamentos WHERE idDepartamento NOT IN (9) AND idDepartamento NOT IN (13) AND idDepartamento NOT IN (11) ORDER BY nomDepartamento ASC";
                                                        $resDepa = mysqli_query($conexion, $sqlDepa);
                                                        while ($rowDepa = $resDepa->fetch_assoc()) {
                                                            $idDepa = $rowDepa['idDepartamento'];
                                                            $nomDepa = $rowDepa['nomDepartamento'];
                                                            ?>
                                                            <option value="<?php echo $idDepa ?>"><?php echo $nomDepa ?></option>
                                                            <?php
                                                        }

                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Personal:<code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <!-- <span class="input-group-text"><i class="fas fa-user"></i></span> -->
                                                    </div>
                                                    <div style="width: 100%;" id="htmlSelect" name="htmlSelect"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <label for="exampleInputTurno">Adjuntar Archivo: </label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-file"></i></span>
                                            </div>
                                            <input type="file" class="form-control" id="archivoAdjunto"
                                                   name="archivoAdjunto">
                                        </div>


                                        <input type="hidden" id="departamentosecreto" name="departamentosecreto" value="<?php echo $departamentos ?>">
                                        <br>
                                        <?php
                                        if ($departamentos < 1) {
                                            $otcontrol = 'display:none';
                                            $otespecial = 'display:none';
                                        } else if ($departamentos == 1) {
                                            $otcontrol = 'display:block';
                                            $otespecial = 'display:none';
                                        } else {
                                            $otcontrol = 'display:none';
                                            $otespecial = 'display:block';
                                        }
                                        ?>
                                        <div id="otcontrol" style="<?php echo $otcontrol; ?>">

                                            <div id="bms1">
                                                <label for="exampleInputTurno">Detalle de los Trabajos a realizar en BMS:
                                                    <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                    </div>
                                                    <textarea  class="form-control" name="detalleTurno"
                                                               id="detalleTurno" rows="5"><?php echo $detalleTG?></textarea>
                                                </div>
                                            </div>
                                            <div id="cctv1">
                                                <label for="exampleInputTurno">Detalle de los Trabajos a realizar en CCTV:
                                                    <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                    </div>
                                                    <textarea  class="form-control" name="detalleTurnoCCTV"
                                                               id="detalleTurnoCCTV"
                                                               rows="5"><?php echo $detalleTurnoCamara?></textarea>
                                                </div>
                                            </div>
                                            <div id="lle1">
                                                <label for="exampleInputTurno">Detalle de los Trabajos a realizar en LLE:
                                                    <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                    </div>
                                                    <textarea  class="form-control" name="detalleTurnoLLE"
                                                               id="detalleTurnoLLE"
                                                               rows="5"><?php echo $detalleTurnoLLE?></textarea>
                                                </div>
                                            </div>
                                            <div id="ca1">
                                                <label for="exampleInputTurno">Detalle de los Trabajos a realizar en Control
                                                    de Acceso: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                    </div>
                                                    <textarea  class="form-control" name="detalleTurnoCA"
                                                               id="detalleTurnoCA" rows="5"><?php echo $detalleTurnoCA?></textarea>
                                                </div>
                                            </div>
                                            <div id="rondas1">
                                                <label for="exampleInputTurno">Detalle de los Trabajos a realizar en Rondas: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                    </div>
                                                    <textarea  class="form-control" name="detalleTurnoRondas"
                                                               id="detalleTurnoRondas" rows="5"><?php echo $detalleTurnoRonda?></textarea>
                                                </div>
                                            </div>
                                            <div id="otros1">
                                                <label for="exampleInputTurno">Detalle de Otros tipos de trabajos:
                                                    <code>(Limpieza,Ordenar,etc )</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                    </div>
                                                    <textarea  class="form-control" name="detalleOtros"
                                                               id="detalleOtros"><?php echo $detalleOtros?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="otespecial" style="<?php echo $otespecial; ?>">

                                            <label for="exampleInputTurno">Detalle de los Trabajos a realizar:
                                                <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                </div>
                                                <textarea  class="form-control" name="detalleTurno"
                                                           id="detalleTurno" rows="5"><?php echo $detalleTG?></textarea>
                                            </div>
                                        </div>
                                        <input type="hidden" id="textooculto" name="textooculto" value="<?php echo $detalleTG?>">

                                        <script>

                                            function deshabilitarDiv(divId) {
                                                // Obtén el div por su id
                                                var miDiv = document.getElementById(divId);

                                                // Itera sobre los elementos hijos del div y deshabilita los que pueden ser deshabilitados
                                                for (var i = 0; i < miDiv.children.length; i++) {
                                                    var elemento = miDiv.children[i];

                                                    if (elemento.tagName === "INPUT" || elemento.tagName === "BUTTON" || elemento.tagName === "SELECT" || elemento.tagName === "TEXTAREA") {
                                                        elemento.disabled = true;
                                                    }
                                                }

                                                miDiv.disabled = true;
                                            }

                                            function habilitarDiv(divId) {
                                                var miDiv = document.getElementById(divId);

                                                for (var i = 0; i < miDiv.children.length; i++) {
                                                    var elemento = miDiv.children[i];

                                                    if (elemento.tagName === "INPUT" || elemento.tagName === "BUTTON" || elemento.tagName === "SELECT" || elemento.tagName === "TEXTAREA") {
                                                        elemento.disabled = false;
                                                    }
                                                }
                                                miDiv.disabled = false;
                                            }

                                            function verificarDIVs(id) {

                                                if (id === 1) {

                                                    habilitarDiv('otcontrol');
                                                    deshabilitarDiv('otespecial');

                                                } else {

                                                    habilitarDiv('otespecial');
                                                    deshabilitarDiv('otcontrol');
                                                }
                                            }
                                        </script>

                                    </fieldset>
                                    <br>

                                    <br>
                                    <div class="form-group">
                                        <?php
                                        $now = date('Y-m-d H:i:s');

                                        $nomCarpeta = 'ORDEN DE TRABAJO - ' . $now;

                                        if ($userUsuario == 'ycasimis') {
                                            $letra = 'YC';
                                        } else if ($userUsuario == 'iperez') {
                                            $letra = 'IP';
                                        } else if ($userUsuario == 'acantillano') {
                                            $letra = 'AC';
                                        }

                                        $trabajoTerminado = 0;

                                        ?>
                                        <input type="hidden" name="fechaModi" id="fechaModi"
                                               value="<?php echo $now ?>">
                                        <input type="hidden" name="letraSolicitante" id="letraSolicitante"
                                               value="<?php echo $letra ?>">
                                        <input type="hidden" name="nomCarpeta" id="nomCarpeta"
                                               value="<?php echo $nomCarpeta ?>">
                                        <input type="hidden" name="trabajoTerminado" id="trabajoTerminado"
                                               value="<?php echo $trabajoTerminado ?>">
                                        <input type="hidden" name="idTG" id="idTG" value="<?php echo $idTG ?>">
                                        <input type="hidden" name="idTecnico1" id="idTecnico1"
                                               value="<?php echo $tecnico1 ?>">
                                        <input type="hidden" name="folioTG" id="folioTG"
                                               value="<?php echo $folioTG ?>">
                                        <button type="submit" name="Guardar" id="Guardar"
                                                class="btn btn-block btn-success">Guardar
                                        </button>
                                    </div>
                                </form>
                                <div class="modal fade" id="eliminar" tabindex="-1"
                                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger">
                                                <h5 class="modal-title" id="exampleModalLabel"><b> Escoja el
                                                        archivo que quiere eliminar </b></h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">

                                                <table class="table table-bordered table-sm"
                                                       id="tabla-equipo-falla">
                                                    <thead class="bg-info">
                                                    <tr>
                                                        <th scope="col" class="text-center">Nombre del Archivo
                                                        </th>
                                                        <th scope="col" class="text-center">Tipo de Archivo</th>
                                                        <th scope="col" class="text-center">¿Es avance del
                                                            técnico?
                                                        </th>
                                                        <th scope="col" class="text-center">Fecha de Subida del
                                                            Archivo
                                                        </th>
                                                        <th scope="col" class="text-center">Eliminar</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    $sqlArchivo = "SELECT * FROM Archivos_Trabajo_General WHERE idTG = $idTG";
                                                    $resArchivo = mysqli_query($conexion, $sqlArchivo);
                                                    while ($rowArchivo = $resArchivo->fetch_assoc()) {
                                                        $idArchivo = $rowArchivo['idArchivo'];
                                                        $nomArchivo = $rowArchivo['nomArchivo'];
                                                        $tipoArchivo = $rowArchivo['extArchivo'];
                                                        $trabajoTerminado1 = $rowArchivo['trabajoTerminado'];
                                                        $fechaSubida = $rowArchivo['fechaSubida'];
                                                        ?>
                                                        <tr>
                                                            <td class="text-center" style="font-size: 14px">
                                                                <?php
                                                                echo $nomArchivo;
                                                                ?>
                                                            </td>
                                                            <td class="text-center" style="font-size: 14px">
                                                                <?php
                                                                echo $tipoArchivo;
                                                                ?>
                                                            </td>
                                                            <td class="text-center" style="font-size: 14px">
                                                                <?php
                                                                if ($trabajoTerminado1 == 0) {
                                                                    echo 'NO';
                                                                } else {
                                                                    echo 'SI';
                                                                }
                                                                ?>
                                                            </td>
                                                            <td class="text-center" style="font-size: 14px">
                                                                <?php
                                                                echo $fechaSubida;
                                                                ?>
                                                            </td>
                                                            <td class="text-center" style="font-size: 14px">
                                                                <form
                                                                        action="../sdo-funciones/eliminar-archivo-trabajo-general.php"
                                                                        method="POST">
                                                                    <input type="hidden" name="idArchivo"
                                                                           id="idArchivo"
                                                                           value="<?php echo $idArchivo ?>">
                                                                    <button type="submit"
                                                                            class="btn btn-danger btn-sm"><i
                                                                                class="fas fa-trash"></i></button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>

                                                    </tbody>
                                                    <tfoot class="bg-info">
                                                    <th scope="col" class="text-center">Nombre del Archivo</th>
                                                    <th scope="col" class="text-center">Tipo de Archivo</th>
                                                    <th scope="col" class="text-center">¿Es avance del técnico?
                                                    </th>
                                                    <th scope="col" class="text-center">Fecha de Subida del
                                                        Archivo
                                                    </th>
                                                    <th scope="col" class="text-center">Eliminar</th>
                                                    </tfoot>
                                                </table>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-block"
                                                        data-dismiss="modal">Cerrar Modal
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            </div>

                        </div>

                    </div>

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
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Page specific script -->
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
    function visible(valor) {
        console.log(valor);
        document.getElementById('departamentosecreto').value = valor;


        var otcontrolDiv = document.getElementById("otcontrol");

        var otespecialDiv = document.getElementById("otespecial");


        // alert(valor);

        if (valor == 1) {

            otcontrolDiv.style.display = "block";
            otespecialDiv.style.display = "none";

        } else {

            otcontrolDiv.style.display = "none";
            otespecialDiv.style.display = "block";
        }

        //
        // if(valor == 1){
        //     if(hola1 == 1){
        //         document.getElementById('bms1').style.display = 'block';
        //         document.getElementById('lle1').style.display = 'block';
        //         document.getElementById('cctv1').style.display = 'block';
        //         document.getElementById('rondas1').style.display = 'block';
        //         document.getElementById('ca1').style.display = 'block';
        //         document.getElementById('otros1').style.display = 'block';
        //     }else{
        //         document.getElementById('bms').style.display = 'block';
        //         document.getElementById('lle').style.display = 'block';
        //         document.getElementById('cctv').style.display = 'block';
        //         document.getElementById('rondas').style.display = 'block';
        //         document.getElementById('ca').style.display = 'block';
        //         document.getElementById('otros').style.display = 'block';
        //         document.getElementById('nocontrol').style.display = 'none';
        //         document.getElementById('nocontrol').value = '';
        //     }
        // }else{
        //     if(hola1 == 1){
        //         document.getElementById('bms1').style.display = 'none';
        //         document.getElementById('lle1').style.display = 'none';
        //         document.getElementById('cctv1').style.display = 'none';
        //         document.getElementById('rondas1').style.display = 'none';
        //         document.getElementById('ca1').style.display = 'none';
        //         document.getElementById('otros1').style.display = 'none';
        //         document.getElementById('nocontrol').style.display = 'block';
        //     }else{
        //         document.getElementById('bms').style.display = 'none';
        //         document.getElementById('lle').style.display = 'none';
        //         document.getElementById('cctv').style.display = 'none';
        //         document.getElementById('rondas').style.display = 'none';
        //         document.getElementById('ca').style.display = 'none';
        //         document.getElementById('otros').style.display = 'none';
        //         document.getElementById('nocontrol').style.display = 'block';
        //
        //     }
        //     document.getElementById('departamentosecreto').value = valor;
        //
        //     //document.getElementById('nocontrol1').style.display = 'block';
        //     hola = document.getElementById('textooculto').value;
        //     document.getElementById('nocontrol').value = hola;
        // }
    }
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
            "responsive": false,
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

        }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
    });
</script>


</body>

</html>