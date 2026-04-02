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

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Trabajos</title>

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
<!-- Site wrapper -->
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/jefes-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Listado de Trabajos</h4>
                    </div>
                    <?php
                    if (isset($_GET['exitoSinArchivo'])) {
                        ?>
                        <script>
                            Swal.fire(
                                'Exito!',
                                'Orden de Trabajo ingresada correctamente sin archivo adjunto!',
                                'success'
                            )
                        </script>
                        <?php
                    }

                    if (isset($_GET['exitoConArchivo'])) {
                        ?>
                        <script>
                            Swal.fire(
                                'Exito!',
                                'Orden de Trabajo ingresada correctamente junto con archivo adjunto!',
                                'success'
                            )
                        </script>
                        <?php
                    }

                    if (isset($_GET['errorEnvioArchivo'])) {
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Algo Salio Mal',
                                text: 'El Archivo Adjunto No Se Subio De Manera Correcta!',
                            })
                        </script>
                        <?php
                    }

                    if (isset($_GET['errorArchivo'])) {
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Algo Salio Mal',
                                text: 'Algo fallo con la subida del Archivo Adjunto.Reintente!',
                            })
                        </script>
                        <?php

                    }

                    if (isset($_GET['archivoNoHabilitado'])) {
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

                    if (isset($_GET['error'])) {
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'A ocurrido un error inesperado.Porfavor reintente'
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

                    if (isset($_GET['error4tg'])) {
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Algunos datos no se han enviado correctamente.Reintente'
                            })
                        </script>
                        <?php

                    }

                    if (isset($_GET['errorEnlosDatos'])) {
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Algunos Datos No Se Encuentran Llenados.Reintente'
                            })
                        </script>
                        <?php
                    }

                    if (isset($_GET['exito'])) {
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'sucess',
                                title: 'Exito',
                                text: 'Se ha ingresado la respuesta con exito'
                            })
                        </script>
                        <?php
                    }

                    if (isset($_GET['exitoGenerar'])) {
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Exito',
                                text: 'Se ha ingresado el avance con exito'
                            })
                        </script>
                        <?php
                    }
                    if (isset($_GET['exitoGenerarBMS'])) {
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Exito',
                                text: 'Se ha la HT de BMS con exito'
                            })
                        </script>
                        <?php
                    }
                    if (isset($_GET['exitoGenerarRONDAS'])) {
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Exito',
                                text: 'Se ha la HT de Rondas con exito'
                            })
                        </script>
                        <?php
                    }
                    if (isset($_GET['errorFolioBMS'])) {
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error en el folio de HT del bms'
                            })
                        </script>
                        <?php
                    }
                    if (isset($_GET['errorGenerarBMS'])) {
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al crear la HT de BMS'
                            })
                        </script>
                        <?php
                    }
                    if (isset($_GET['errorOrdenRONDAS'])) {
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al generar la HT de las rondas.'
                            })
                        </script>
                        <?php
                    }

                    if (isset($_GET['errorFolioRONDAS'])) {
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error en el folio de HT de las rondas'
                            })
                        </script>
                        <?php
                    }
                    if (isset($_GET['errorGenerarCA'])) {
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al crear la HT de Control de Acceso'
                            })
                        </script>
                        <?php
                    }
                    if (isset($_GET['exitoGenerarCA'])) {
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Exito',
                                text: 'Se ha creado la HT de Control de Acceso'
                            })
                        </script>
                        <?php
                    }
                    if (isset($_GET['exitoGenerarCCTV'])) {
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Exito',
                                text: 'Se ha creado la HT de CCTV'
                            })
                        </script>
                        <?php
                    }
                    if (isset($_GET['errorGenerarCCTV'])) {
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al crear la HT de CCTV'
                            })
                        </script>
                        <?php
                    }
                    if (isset($_GET['errorFolioCCTV'])) {
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al crear la HT de CCTV'
                            })
                        </script>
                        <?php
                    }
                    if (isset($_GET['exitoGenerarLLE'])) {
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Exito',
                                text: 'Se ha generado la HT de LLE con exito'
                            })
                        </script>
                        <?php
                    }
                    if (isset($_GET['errorFolioLLE'])) {
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al crear la HT de LLE'
                            })
                        </script>
                        <?php
                    }

                    if (isset($_GET['errorGenerarLLE'])) {
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al crear la HT de LLE'
                            })
                        </script>
                        <?php
                    }
                    if (isset($_GET['exitoGenerarOTROS'])) {
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Exito',
                                text: 'Exito al crear la HT de Otros'
                            })
                        </script>
                        <?php
                    }
                    if (isset($_GET['errorFolioOTROS'])) {
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al crear la HT de otros'
                            })
                        </script>
                        <?php
                    }
                    if (isset($_GET['errorGenerarOTROS'])) {
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al crear la HT de otros'
                            })
                        </script>
                        <?php
                    }
                    ?>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
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

                                <div class="col-lg-3 col-md-6 col-12 float-right" id="volverAtras2" name="volverAtras2">
                                    <!-- small box -->
                                    <a href="index.php" style="float: right;" class="btn bg-gradient-red float-right btn-md text-white"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
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
                                        <button id="registrarAC" style="color: white" name="registrarAC" class="btn btn-block bg-gradient-info btn-md show-realizar text-black text-center"><i class="nav-icon fas fa-book"></i><br>Registrar Orden de Trabajo</button>

                                    </div>
                                    &nbsp;
                                    <div class="col-lg-3 col-md-6 col-12 text-center" id="listarAC2" style="display:none;" name="listarAC2">
                                        <!-- small box -->
                                        <button id="listarAC" style="display:none; color: white" name="listarAC" class="btn btn-block bg-gradient-info btn-md text-black text-center show-listar"><i class="nav-icon fas fa-book"></i><br>Ver Listado</button>

                                    </div>

                                    <?php
                                    if ($idDepartamento == 1) {
                                        ?>
                                        <div id="crearFacilOT2" name="crearFacilOT2" class="col-lg-3 col-md-6 col-12 text-center">
                                            <!-- small box -->


                                            <button id="crearFacilOT" style="color: white" data-toggle="modal" data-target="#modalCrearOTMonitoreo" name="crearFacilOT" class="btn btn-block bg-gradient-success btn-md text-black text-center"><i class="nav-icon fas fa-book"></i><br>Crear OT Monitoreo</button>

                                            <div class="modal fade" id="modalCrearOTMonitoreo" name="modalCrearOTMonitoreo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-primary">
                                                            <h5 class="modal-title" id="exampleModalLabel">Generar OT Monitoreo</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <form method="post" action="../sdo-funciones/agregar_trabajo_monitoreo_control.php" enctype="multipart/form-data">
                                                            <div class="modal-body">

                                                                <div style="text-align:left;" class="form-group">
                                                                    <label for="exampleInputBorder">Fecha de Programacion del Trabajo: <code>*</code></label>
                                                                    <input type="date" name="fechaMonitoreo" id="fechaMonitoreo" class="form-control" min="2022-01-01">
                                                                </div>
                                                                <div style="text-align:left;" class="form-group">
                                                                    <label for="exampleInputBorder">Formularios de Rondas</label>
                                                                    <select class="custom-select" name="tipoFormulario" id="tipoFormulario">
                                                                        <option value="0">Sin Formulario</option>
                                                                        <option value="1">Formato Rondas con Consola de Alarmas</option>
                                                                        <option value="2">Formato Rondas con Unidades manejadoras de aire BMS</option>
                                                                    </select>
                                                                </div>
                                                                <div style="text-align:left;" class="form-group">
                                                                    <label for="exampleInputBorder">Persona que realizara el Trabajo <code>*</code></label>
                                                                    <select class="custom-select" name="personaAsignada" id="personaAsignada">
                                                                        <?php
                                                                        $sqlTecnico1 = "SELECT * FROM personal WHERE (departamentoPersonal = $idDepartamento AND activoPersonal = 1) AND (nomPersonal NOT LIKE('%Cristian Jimenez%') AND nomPersonal NOT LIKE ('%Cynthia Latorre Ravanales%')) ORDER BY nomPersonal ASC";
                                                                        $resTecnico1 = mysqli_query($conexion, $sqlTecnico1);
                                                                        ?>
                                                                        <option value="" disabled selected>-- Seleccione --</option>
                                                                        <?php
                                                                        while ($rowTecnico1 = $resTecnico1->fetch_assoc()) {
                                                                            $datos = [''];
                                                                            $datos['nomPersonal'] = $rowTecnico1['nomPersonal'];
                                                                            $datos['idPersonal'] = $rowTecnico1['idPersonal'];
                                                                            $datos['cargoPersonal'] = $rowTecnico1['cargoPersonal'];
                                                                            $nomPersonal = $datos['nomPersonal'];
                                                                            $idPersonal = $datos['idPersonal'];
                                                                            $cargoPersonal = $datos['cargoPersonal'];
                                                                            ?>
                                                                            <option value="<?php echo $nomPersonal ?>"><?php echo $nomPersonal ?></option>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                                <div style="text-align:left;" class="form-group">
                                                                    <label for="exampleInputBorder">Turno Asignado <code>*</code></label>
                                                                    <select class="custom-select" name="turnoAsignado" id="turnoAsignado">
                                                                        <option value="0">Seleccione...</option>
                                                                        <option value="1">Personal Diurno</option>
                                                                        <option value="2">Personal Nocturno</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="hidden" name="creadorOT" id="creadorOT" value="<?php echo $nomUsuario; ?>">
                                                                <button type="submit" class="btn btn-primary">Agregar</button>
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                            </div>

                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <?php
                                    }
                                    ?>


                                </div>

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

                if (isset($_GET['otyaexiste'])) {
                    ?>
                    <script>
                        Swal.fire({
                            title: 'El archivo adjunto ya existe. Por favor cambie el nombre del archivo',
                            confirmButtonText: 'Cerrar',
                            icon: 'info',
                        });
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

                <div class="row" id="rowMenu1" name="rowMenu1">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>1.- Listado de Trabajos</h5>
                                <br>
                                <h5>&nbsp;&nbsp; Colores para Identificar:</h5>
                                <ul>
                                    <!--  <li><i style="color: #28A745; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Trabajo Ingresado</li> -->
                                    <li><i style="color: #FFC107; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Ud.Funcionario Asignado</li>
                                </ul>
                                <hr>
                                <div class="table-responsive">
                                    <table style="font-size:80%" id="example2" class="table table-bordered cell-border">
                                        <thead class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha de Trabajo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Folio de Trabajo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Turno de Trabajo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Solicitante</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Funcionario Responsable</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Estado</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Descripción</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Información</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Detalles</center>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php
                                        $queryTG = "SELECT * FROM trabajos_general WHERE estadoTG != 'Trabajo Terminado' AND departamento = $idDepartamento ORDER BY fechaProgramacion DESC, folioTG DESC";

                                        $resTG = mysqli_query($conexion, $queryTG);

                                        $numTG = 1;


                                        while ($rowTG = $resTG->fetch_assoc()) {


                                            $fechaTG = $rowTG['fechaTG'];
                                            $fechaProgramacion = $rowTG['fechaProgramacion'];

                                            $turnoTG = $rowTG['turnoTG'];
                                            $turnoTG = trim($turnoTG);

                                            $solicitanteTG = $rowTG['solicitanteTG'];
                                            $solicitanteTG = trim($solicitanteTG);

                                            $idTG = $rowTG['idTG'];
                                            $idTG = trim($idTG);

                                            $estadoTG = trim($rowTG['estadoTG']);

                                            $tecnico1 = $rowTG['tecnico1'];

                                            $folioTG = $rowTG['folioTG'];

                                            $descripcion = $rowTG['descripcion'];

                                            // $contarObs = contarObs($idTG);
                                            $contarMaterial = contarMaterial($idTG);
                                            $contarObs = contarObs($idTG);
                                            $contarPanol = contarPanol($idTG);
                                            $contarRechazadoPanol = contarRechazadoPanol($idTG);
                                            $contarAceptadoPanol = contarAceptadoPanol($idTG);


                                            if ($tecnico1 == $nomUsuario) {
                                                $colorTR = "background-color: #FFC107; color:black;";
                                            } else if ($contarMaterial > 0) {
                                                $colorTR = "background-color: indianred; color:white";
                                            } else if ($contarPanol > 0) {
                                                $colorTR = "background-color: blue; color:white";
                                            } else if ($contarAceptadoPanol > 0) {
                                                $colorTR = "background-color: green; color:white";
                                            } else if ($contarRechazadoPanol > 0) {
                                                $colorTR = "background-color: red; color:white";
                                            } else {
                                                $colorTR = "";
                                            }

                                            ?>

                                            <tr style="<?php echo $colorTR ?>">
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <?php if ($fechaProgramacion == 0) {
                                                        echo "No registra fecha";
                                                    } else {

                                                        $registrofechaTG = date('d/m/Y', strtotime(str_replace('/', '-', $fechaProgramacion)));
                                                        $registroHoraTG = date('H:i', strtotime($fechaProgramacion));

                                                        // echo $registrofechaAC . ' a las ' . $registroHoraMC.' hrs.';

                                                        echo $registrofechaTG;
                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <?php
                                                    echo $folioTG;
                                                    ?>

                                                </td>
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <?php if (empty($turnoTG)) {
                                                        echo "No registra Turno";
                                                    } else {

                                                        echo $turnoTG;
                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <?php if (empty($solicitanteTG)) {
                                                        echo "No registra Solicitante";
                                                    } else {

                                                        echo $solicitanteTG;
                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php
                                                    echo $tecnico1;
                                                    ?>
                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php if (empty($estadoTG)) {
                                                        echo "No registra Estado";
                                                    } else {
                                                        if ($estadoTG == 'trabajoTerminado') {
                                                            $estado = "Trabajo Terminado";
                                                            echo $estado;
                                                        } else {
                                                            echo $estadoTG;
                                                        }

                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php
                                                    if (!empty($descripcion)) {
                                                        echo $descripcion;
                                                    } else {
                                                        echo 'Sin informacion';
                                                    }
                                                    ?>
                                                </td>
                                                <td style="vertical-align:middle;">
                                                    <?php
                                                    if ($contarPanol > 0) {
                                                        ?>
                                                        <center>
                                                            <?php echo 'Funcionario asignado ha solicitado materiales y/o herramientas' ?>
                                                        </center>
                                                        <?php
                                                    } else if ($contarMaterial > 0) {
                                                        ?>
                                                        <center>
                                                            <?php echo 'EL FUNCIONARIO ASIGNADO HA SOLICITADO MATERIALES POR SOLICITUD DE COMPRA'; ?>
                                                        </center>
                                                        <?php

                                                    } else if ($contarAceptadoPanol > 0) {
                                                        ?>
                                                        <center>
                                                            <?php echo 'La solicitud hacia pañol se ha entregado con exito' ?>
                                                        </center>
                                                        <?php
                                                    } else if ($contarRechazadoPanol > 0) {
                                                        ?>
                                                        <center>
                                                            <?php echo 'La solicitud se ha rechazado por personal de pañol' ?>
                                                        </center>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <center>
                                                            <?php echo 'Sin Informacion'; ?>
                                                        </center>
                                                        <?php
                                                    }

                                                    ?>
                                                </td>
                                                <td style="vertical-align:middle;">
                                                    <center>
                                                        <?php
                                                        if ($idTG == 0 || $idTG == "" || $idTG == " " || $idTG == "  ") {
                                                            ?>
                                                            <center><?php echo "Error al Cargar Datos."; ?></center>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <form style="margin: 0; text-align: center;" class="" target="_blank" action="detalle_ordenes_trabajo.php" method="post">

                                                                <input type="hidden" name="idTG" value="<?php echo $idTG; ?>">
                                                                <button type="submit" id="inputMozi" class="btn btn-block btn-info"><i class="fa fa-eye"></i></button>

                                                            </form>
                                                            <?php
                                                        }
                                                        ?>
                                                    </center>
                                                </td>


                                            </tr>

                                            <?php
                                        }
                                        ?>

                                        </tbody>
                                        <tfoot class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha de Trabajo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Folio de Trabajo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Turno de Trabajo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Solicitante</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Funcionario Responsable</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Estado</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Descripción</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Información</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Detalles</center>
                                            </th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <div class="row" id="rowMenu2" name="rowMenu2" style="display:none;">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>2.- Registrar Trabajo General</h5>
                                <br>
                                <form action="../sdo-funciones/agregar-trabajo-general.php" method="POST" enctype="multipart/form-data">
                                    <fieldset class="border p-3">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Descripción: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="text" required class="form-control" id="descripcion" name="descripcion" placeholder="Descripción del Trabajo">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Fecha de Creacion: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                    </div>
                                                    <?php
                                                    date_default_timezone_set('America/Santiago');
                                                    ?>
                                                    <input type="datetime" class="form-control" readonly="readonly" name="fechaOrden" id="fechaOrden" value="<?php echo date('Y-m-d H:i'); ?>">

                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Fecha de Programacion del Trabajo: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                    </div>
                                                    <?php
                                                    date_default_timezone_set('America/Santiago');
                                                    ?>
                                                    <input type="date" required class="form-control" name="fechaProgramado" id="fechaProgramado">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Ticket Asociado: </label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-ticket-alt"></i></span>
                                                    </div>
                                                    <input type="number" class="form-control" name="ticketAsociado" id="ticketAsociado" placeholder="Ingrese número del ticket">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputCodigoTarjeta">Tipo de Trabajo:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-th-list"></i></span>
                                                    </div>
                                                    <select name="tipoTrabajo" class="form-control" id="tipoTrabajo">
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
                                                <label for="exampleInputTurno">Persona Solicitante: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                    </div>
                                                    <input type="text" value="<?php echo $nomUsuario ?>" class="form-control" required name="personaSolicitante" id="personaSolicitante" placeholder="Ingrese a quien creará la orden de trabajo">
                                                </div>
                                            </div>

                                            <?php
                                            if ($idDepartamento == 1) {
                                                ?>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputTurno">Formularios de Rondas:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-clipboard"></i></span>
                                                        </div>
                                                        <select class="form-control" id="formularioRondas" name="formularioRondas">
                                                            <option value="" selected disabled>Selecione..</option>
                                                            <option value="1">Formato Rondas con Consola de Alarmas</option>
                                                            <option value="2">Formato Rondas con Unidades manejadoras de aire BMS</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <div class="form-row">
                                            <?php
                                            if ($idDepartamento == 5) {
                                                if ($nomUsuario !== 'Lukas Zamora') {
                                                    ?>
                                                    <div class="form-group col-md-6">
                                                        <label for="exampleInputTurno">Persona quien hara el trabajo: <code>*</code></label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-users"></i></span>
                                                            </div>
                                                            <select name="tecnico1" id="tecnico1" class="form-control" required>
                                                                <?php
                                                                $sqlTecnico1 = "SELECT * FROM personal WHERE departamentoPersonal = $idDepartamento AND activoPersonal = 1 AND nomPersonal NOT LIKE('%Cristian Jimenez%') AND nomPersonal NOT LIKE ('%Cynthia Latorre Ravanales%')";
                                                                $resTecnico1 = mysqli_query($conexion, $sqlTecnico1);
                                                                ?>
                                                                <option value="" disabled selected>-- Seleccione --</option>
                                                                <option value="Lukas Zamora">Lukas Zamora</option>
                                                                <?php
                                                                while ($rowTecnico1 = $resTecnico1->fetch_assoc()) {
                                                                    $datos = [''];
                                                                    $datos['nomPersonal'] = $rowTecnico1['nomPersonal'];
                                                                    $datos['idPersonal'] = $rowTecnico1['idPersonal'];
                                                                    $datos['cargoPersonal'] = $rowTecnico1['cargoPersonal'];
                                                                    $nomPersonal = $datos['nomPersonal'];
                                                                    $idPersonal = $datos['idPersonal'];
                                                                    $cargoPersonal = $datos['cargoPersonal'];
                                                                    ?>
                                                                    <option value="<?php echo $nomPersonal ?>"><?php echo $nomPersonal ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <div class="form-group col-md-6">
                                                        <label for="exampleInputTurno">Persona quien hara el trabajo: <code>*</code></label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-users"></i></span>
                                                            </div>
                                                            <select name="tecnico1" id="tecnico1" class="form-control" required>
                                                                <?php
                                                                $sqlTecnico1 = "SELECT * FROM personal WHERE departamentoPersonal = $idDepartamento AND activoPersonal = 1 AND nomPersonal NOT LIKE('%Cristian Jimenez%') AND nomPersonal NOT LIKE ('%Cynthia Latorre Ravanales%')";
                                                                $resTecnico1 = mysqli_query($conexion, $sqlTecnico1);
                                                                ?>

                                                                <option value="" disabled selected>-- Seleccione --</option>
                                                                <?php
                                                                while ($rowTecnico1 = $resTecnico1->fetch_assoc()) {
                                                                    $datos = [''];
                                                                    $datos['nomPersonal'] = $rowTecnico1['nomPersonal'];
                                                                    $datos['idPersonal'] = $rowTecnico1['idPersonal'];
                                                                    $datos['cargoPersonal'] = $rowTecnico1['cargoPersonal'];
                                                                    $nomPersonal = $datos['nomPersonal'];
                                                                    $idPersonal = $datos['idPersonal'];
                                                                    $cargoPersonal = $datos['cargoPersonal'];
                                                                    ?>
                                                                    <option value="<?php echo $nomPersonal ?>"><?php echo $nomPersonal ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                            } else if ($idDepartamento == 2) {
                                                ?>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputTurno">Persona quien hara el trabajo: <code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-users"></i></span>
                                                        </div>
                                                        <select name="tecnico1" id="tecnico1" class="form-control" required>
                                                            <?php
                                                            $sqlTecnico1 = "SELECT * FROM personal WHERE departamentoPersonal = $idDepartamento AND activoPersonal = 1 AND nomPersonal NOT LIKE('%Cristian Jimenez%') AND nomPersonal NOT LIKE ('%Cynthia Latorre Ravanales%') AND nomPersonal NOT LIKE ('%Tecnico Prueba%') ORDER BY nomPersonal ASC";
                                                            $resTecnico1 = mysqli_query($conexion, $sqlTecnico1);
                                                            ?>
                                                            <option value="" disabled selected>-- Seleccione --</option>
                                                            <option value="Haroll Álvarez Barrera">Haroll Álvarez Barrera</option>
                                                            <?php
                                                            while ($rowTecnico1 = $resTecnico1->fetch_assoc()) {
                                                                $datos = [''];
                                                                $datos['nomPersonal'] = $rowTecnico1['nomPersonal'];
                                                                $datos['idPersonal'] = $rowTecnico1['idPersonal'];
                                                                $datos['cargoPersonal'] = $rowTecnico1['cargoPersonal'];
                                                                $nomPersonal = $datos['nomPersonal'];
                                                                $idPersonal = $datos['idPersonal'];
                                                                $cargoPersonal = $datos['cargoPersonal'];
                                                                ?>
                                                                <option value="<?php echo $nomPersonal ?>"><?php echo $nomPersonal ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputTurno">Persona quien hara el trabajo: <code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-users"></i></span>
                                                        </div>
                                                        <select name="tecnico1" id="tecnico1" class="form-control" required>
                                                            <?php
                                                            $sqlTecnico1 = "SELECT * FROM personal WHERE departamentoPersonal = $idDepartamento AND activoPersonal = 1 AND nomPersonal NOT LIKE('%Cristian Jimenez%') AND nomPersonal NOT LIKE ('%Cynthia Latorre Ravanales%') AND nomPersonal NOT LIKE ('%Tecnico Prueba%') ORDER BY nomPersonal ASC";
                                                            $resTecnico1 = mysqli_query($conexion, $sqlTecnico1);
                                                            ?>
                                                            <option value="" disabled selected>-- Seleccione --</option>
                                                            <?php
                                                            if ($idDepartamento == 3) {
                                                                ?>
                                                                <option value="Javier Beltrán Saldías">Javier Beltrán Saldías</option>
                                                                <option value="Victor Fierro">Victor Fierro</option>
                                                                <?php
                                                            }
                                                            ?>
                                                            <?php
                                                            while ($rowTecnico1 = $resTecnico1->fetch_assoc()) {
                                                                $datos = [''];
                                                                $datos['nomPersonal'] = $rowTecnico1['nomPersonal'];
                                                                $datos['idPersonal'] = $rowTecnico1['idPersonal'];
                                                                $datos['cargoPersonal'] = $rowTecnico1['cargoPersonal'];
                                                                $nomPersonal = $datos['nomPersonal'];
                                                                $idPersonal = $datos['idPersonal'];
                                                                $cargoPersonal = $datos['cargoPersonal'];
                                                                ?>
                                                                <option value="<?php echo $nomPersonal ?>"><?php echo $nomPersonal ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Turno: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-users"></i></span>
                                                    </div>
                                                    <select name="turno" id="turno" class="form-control" required>
                                                        <option value="" disabled selected>Seleccione...</option>
                                                        <option value="Personal Diurno">Personal Diurno</option>
                                                        <option value="Personal Nocturno">Personal Nocturno</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        if ($idDepartamento == 1) {
                                            ?>
                                            <div class="form-row fieldGroup">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputCoordenadas">Check List:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                                        </div>
                                                        <select class="form-control" id="checklist[]" name="checklist[]">
                                                            <?php
                                                            if ($idDepartamento == 1) {
                                                                ?>
                                                                <option value="" selected>Seleccione...</option>
                                                                <option value="Pantallas Graficas">Pantallas Gráficas</option>
                                                                <option value="Logica de Programacion">Logica de Programacion</option>
                                                                <option value="Unidades Control de Accesos">Unidades Control de Accesos (UCAS)</option>
                                                                <option value="Controlador Modelo ECB">Controlador Modelo ECB</option>
                                                                <option value="TCC de UMA">TCC de UMA</option>
                                                                <option value="VAV">VAV</option>
                                                                <option value="VF">Vigas Frias</option>
                                                                <option value="UMAS">UMAS</option>
                                                                <?php
                                                            } else if ($idDepartamento == 3) {
                                                                ?>
                                                                <option value="" selected>Seleccione</option>
                                                                <option value="aguaCaliente">Agua Caliente</option>
                                                                <option value="salacomunicacion">Sala de Comunicacion</option>s
                                                                <option value="chillerResonador">Chiller Resonador</option>
                                                                <option value="calderas">Calderas</option>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <option value="Pantallas Graficas">SIN NADA</option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label for="exampleInputCoordenadas">¿Cuantas veces se hara este checklist?:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                                        </div>
                                                        <input type="number" min="0" name="cantidad[]" id="cantidad[]" class="form-control">
                                                    </div>
                                                </div>
                                                <?php
                                                if ($idDepartamento == 3) {
                                                    ?>
                                                    <div class="form-group col-md-3">
                                                        <label for="exampleInputCoordenadas">&nbsp;</label>
                                                        <div class="input-group mb-3">
                                                            <a href="javascript:void(0)" class="btn btn-block btn-success addMore"><span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>Agregar Otro CheckList</a>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <div class="form-row fieldGroupCopy" style="display: none;">
                                                <div class="form-group col-md-6">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                                        </div>
                                                        <select class="form-control" id="checklist[]" name="checklist[]">
                                                            <?php
                                                            if ($idDepartamento == 1) {
                                                                ?>
                                                                <option value="" selected>Seleccione...</option>
                                                                <option value="Pantallas Graficas">Pantallas Gráficas</option>
                                                                <?php
                                                            } else if ($idDepartamento == 3) {
                                                                ?>
                                                                <option value="" selected>Seleccione</option>
                                                                <option value="aguaCaliente">Agua Caliente</option>
                                                                <option value="salacomunicacion">Sala de Comunicacion</option>
                                                                <option value="chillerResonador">Chiller Resonador</option>
                                                                <option value="calderas">Calderas</option>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <option value="Pantallas Graficas">SIN NADA</option>

                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                                        </div>
                                                        <input type="number" min="0" name="cantidad[]" id="cantidad[]" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <div class="input-group mb-3">
                                                        <a href="javascript:void(0)" class="btn btn-block btn-danger remove"><span class="glyphicon glyphicon glyphicon-remove" aria-hidden="true"></span> Eliminar</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <script type="text/javascript">
                                                $(document).ready(function () {
                                                    //group add limit
                                                    var maxGroup = "100";

                                                    //add more fields group
                                                    $(".addMore").click(function () {
                                                        if ($('body').find('.fieldGroup').length < maxGroup) {
                                                            var fieldHTML = '<div class="form-row fieldGroup">' + $(".fieldGroupCopy").html() + '</div>';
                                                            $('body').find('.fieldGroup:last').after(fieldHTML);

                                                        } else {
                                                            alert('Limite de Materiales Completados. Solo se permiten ' + maxGroup + ' Materiales.');
                                                        }
                                                    });

                                                    //remove fields group
                                                    $("body").on("click", ".remove", function () {
                                                        $(this).parents(".fieldGroup").remove();
                                                    });
                                                });
                                            </script>
                                            <?php
                                        }
                                        ?>
                                        <?php
                                        if ($idDepartamento == 1) {
                                            ?>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputTurno">Unidad o Servicio: <code>*</code></label>
                                                    <div class="input-group mb-2">
                                                        <select name="servicios" id="servicios" class="form-control select2" style="width: 100%" required>
                                                            <option value="" disabled selected>Seleccione un servicio</option>
                                                            <?php
                                                            $sqlServicio = "SELECT * FROM servicios ORDER BY nomServicio ASC";
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
                                            <?php
                                        }
                                        ?>

                                        <div class="form-row">

                                            <label for="exampleInputTurno">Adjuntar Trabajo General: </label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                </div>
                                                <input type="file" class="form-control" id="archivoAdjunto" name="archivoAdjunto">
                                            </div>

                                        </div>
                                        <?php
                                        if ($idDepartamento == 1) {
                                            ?>
                                            <label for="exampleInputTurno">Detalle los Trabajos a realizar en BMS: <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                </div>
                                                <textarea class="form-control" name="detalleTurno" id="detalleTurno"></textarea>
                                            </div>
                                            <label for="exampleInputTurno">Detalle los Trabajos a realizar en Cámara/Parking: <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                </div>
                                                <textarea class="form-control" name="detalleTurnoCamara" id="detalleTurnoCamara"></textarea>
                                            </div>
                                            <label for="exampleInputTurno">Detalle los Trabajos a realizar en LLamado a Enfermeria: <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                </div>
                                                <textarea class="form-control" name="detalleTurnoLLE" id="detalleTurnoLLE"></textarea>
                                            </div>
                                            <label for="exampleInputTurno">Detalle los Trabajos a realizar en Control de Acceso: <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                </div>
                                                <textarea class="form-control" name="detalleTurnoCA" id="detalleTurnoCA"></textarea>
                                            </div>
                                            <label for="exampleInputTurno">Detalle los Trabajos a realizar en Rondas: <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                </div>
                                                <textarea class="form-control" name="detalleTurnoRondas" id="detalleTurnoRondas"></textarea>
                                            </div>
                                            <label for="exampleInputTurno">Detalle de Otros tipos de trabajos: <code>(Limpieza,Ordenar,etc )</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                </div>
                                                <textarea class="form-control" name="detalleOtros" id="detalleOtros"></textarea>
                                            </div>
                                            <?php
                                        } else {
                                            ?>
                                            <label for="exampleInputTurno">Detalle los Trabajos a realizar: <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                </div>
                                                <textarea class="form-control" name="detalleTurno" id="detalleTurno"></textarea>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </fieldset>
                                    <br>
                                    <div class="form-group">
                                        <input type="hidden" name="nomCarpeta" id="nomCarpeta" value="<?php echo $nomCarpeta ?>">
                                        <button type="submit" name="Guardar" id="Guardar" class="btn btn-block btn-success">Guardar</button>
                                        <a href="index.php" class="btn btn-block btn-warning">Volver Atras</a>
                                    </div>
                                </form>
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
<script src="../plugins/select2/js/select2.full.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Page specific script -->
<script>
    $(document).ready(function () {
        // Inicializar el select2
        $('.select2').select2();

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


<script type="text/javascript">
    $("input[name='opciones']").change(function () {
        if ($(this).val() == 'medicionN') {
            $('#medicionNormal').show();
            $('#cargaCombustible').hide();
        } else if ($(this).val() == 'cargaC') {
            $('#medicionNormal').hide();
            $('#cargaCombustible').show();
        } else {
            $('#medicionNormal').hide();
            $('#cargaCombustible').hide();
        }
    })
</script>

</body>

</html>