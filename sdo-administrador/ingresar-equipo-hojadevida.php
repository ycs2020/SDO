<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ingreso Equipo</title>

    <link rel="icon" href="../dist/img/ss-vina-quillota.png" type="image/icon type">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <script src="../sdo-canvasdraw1/jquery.min.js"></script>
    <script src="../sdo-canvasdraw1/signature_pad.js"></script>
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            recargarSubDepartamento();
            recargarNomDepartamento();
            recargarListTecnicos();

            $('#departamentoSolicitante').change(function() {
                recargarSubDepartamento();
                recargarListTecnicos();
            });

            $('#servicioOrden').change(function() {
                recargarNomDepartamento();
            });


            function recargarSubDepartamento() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-departamentos/mostrarSubDepartamentos.php",
                    data: "idDepartamento=" + $('#departamentoSolicitante').val(),
                    success: function(r) {
                        $('#visualizarSubDepartamento').html(r);


                    }
                });

            };

            function recargarNomDepartamento() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-servicios/habilitarIngresoServicio.php",
                    data: "idServicio=" + $('#servicioOrden').val(),
                    success: function(r) {
                        $('#visualizarNomServicio').html(r);


                    }
                });

            };


            function recargarListTecnicos() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-tecnicos/cargarTecnicos.php",
                    data: "idDepartamento=" + $('#departamentoSolicitante').val(),
                    success: function(r) {

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
        });
    </script>
    <style>
        .ocultar-manual {
            display: none;
        }

        .ocultar-accesorio {
            display: none;
        }

        .ocultar-pauta {
            display: none;
        }

        .ocultar-certificados {
            display: none;
        }

        .ocultar-otros {
            display: none;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini " id="body">
    <div class="preloader  flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="../dist/img/ssvq1.png" alt="logo">
        <br>
        <div class="spinner-border text-danger" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <div class="wrapper">
        <?php
        include '../sdo-templates/admin-sidebar.php';
        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">


                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- MUESTRA MENSAJES DE ALERTAS-->
            <?php

            if (isset($_GET['errorNoPost'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Método no reconocido',
                        text: 'El formulario no fue enviado por método POST',
                    })
                </script>
            <?php
            }

            if (isset($_GET['errorInputVacios'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Input Vacios',
                        text: 'Faltan datos que ingresar',
                    })
                </script>
            <?php
            }

            if (isset($_GET['errorInsert'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error SQL',
                        text: 'Error al guardar los datos',
                    })
                </script>
            <?php
            }

            if (isset($_GET['noServicio'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error SQL',
                        text: 'Error al obtener los servicios',
                    })
                </script>
            <?php
            }

            if (isset($_GET['noUpdateEquipo'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error SQL',
                        text: 'Error al actualizar folio',
                    })
                </script>
            <?php
            }

            if (isset($_GET['exito'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Exito',
                        text: 'Exito al guardar el equipo',
                    })
                </script>
            <?php
            }

            if (isset($_GET['noSubido'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'No subido',
                        text: 'Error al guardar los archivos adjuntos',
                    })
                </script>
            <?php
            }

            if (isset($_GET['formatoNoValido'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Formato',
                        text: 'No se soportan el formato de los archivos adjuntos',
                    })
                </script>
            <?php
            }

            if (isset($_GET['noAdjunto'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'No adjuntos',
                        text: 'No se ha adjuntado Archivos',
                    })
                </script>
            <?php
            }
            ?>
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- general form elements -->
                    <div class="card card-purple">
                        <div class="card-header">
                            <h3 class="card-title"> <b> Información Ingreso Equipo</b></h3>
                        </div>
                        <div class="row">
                            <!-- left column -->
                            <div class="col-md-12">
                                <!-- /.card-header -->
                                <div class="card-body">

                                    <a href="administrar-equipos.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp; <b>Volver Atras</b> </a>
                                    <br>
                                    <br>

                                    <p style="color:red;font-weight:bold;font-size:15px">*Campos Obligatorios</p>
                                    <form action="../sdo-funciones/guardar-equipo-hojadevida.php" id="formulario" method="POST" enctype="multipart/form-data">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="codigoBarra" style="font-size:12px">Identificador Único(ID):<code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-id-badge"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control form-control-sm" id="id" name="id" placeholder="Escriba el identificador único">
                                                    <div style="display: inline ; margin-left: 20px; font-size: 20px">
                                                        <a class="pop-me-over" data-toggle="popover" data-placement="right" data-content="El ID creado sirve para poder indentificar este equipo con las hojas técnicas creadas de una manera rápida y fácil, antes de crear revise si ya existe un formato para el equipo es cuestión, ingresando a la lista de equipos ingresados. Por ejemplo si ingresa un generador, el ID podria ser el siguiente GEN01, siendo el número las veces que se ha ingresado un generador" data-title="Información"><i class="fas fa-question-circle"></i></a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <!-- <label for="customFile">Custom File</label> -->
                                                <label for="exampleInputTareasControlCent" style="font-size:12px">Subir Imagenes: (Maximo 10 Imagenes)<code>*</code></label><br>
                                                <label for="exampleInputTareasControlCent" style="font-size:12px">Formatos Permitidos: .jpg, .png, .jpeg, .bmp, .tif, .webp, .svg, .jfif</label>
                                                <div class="custom-file">
                                                    <input type="file" max-uploads="10" class="custom-file-input" multiple="" id="archivoImagen[]" name="archivoImagen[]" accept=".jpg, .png, .jpeg, .bmp, .tif, .webp, .svg, .jfif">
                                                    <label class="custom-file-label" for="customFile" style="font-size:12px"><b>Seleccionar Archivo...</b> </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="codigoBarra" style="font-size:12px">N° de Inventario:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pallet"></i></span>
                                                    </div>
                                                    <input type="text" style="text-transform:uppercase;" class="form-control form-control-sm" id="numeroInventario" name="numeroInventario" placeholder="Escriba el número de inventario">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="codigoBarra" style="font-size:12px">Fecha de recepción:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                    </div>
                                                    <select name="diaFechaRecepcion" id="diaFechaRecepcion" max="5" class="form-control form-control-sm col-md-2">
                                                        <option value="" disabled selected>-- Dia --</option>
                                                        <option value="01">1</option>
                                                        <option value="02">2</option>
                                                        <option value="03">3</option>
                                                        <option value="04">4</option>
                                                        <option value="05">5</option>
                                                        <option value="06">6</option>
                                                        <option value="07">7</option>
                                                        <option value="08">8</option>
                                                        <option value="09">9</option>
                                                        <option value="10">10</option>
                                                        <option value="11">11</option>
                                                        <option value="12">12</option>
                                                        <option value="13">13</option>
                                                        <option value="14">14</option>
                                                        <option value="15">15</option>
                                                        <option value="16">16</option>
                                                        <option value="17">17</option>
                                                        <option value="18">18</option>
                                                        <option value="19">19</option>
                                                        <option value="20">20</option>
                                                        <option value="21">21</option>
                                                        <option value="22">22</option>
                                                        <option value="23">23</option>
                                                        <option value="24">24</option>
                                                        <option value="25">25</option>
                                                        <option value="26">26</option>
                                                        <option value="27">27</option>
                                                        <option value="28">28</option>
                                                        <option value="29">29</option>
                                                        <option value="30">30</option>
                                                        <option value="31">31</option>
                                                    </select>
                                                    <select name="mesFechaRecepcion" id="mesFechaRecepcion" class="form-control form-control-sm col-md-2">
                                                        <option value="" disabled selected>-- Mes --</option>
                                                        <option value="01">Enero</option>
                                                        <option value="02">Febrero</option>
                                                        <option value="03">Marzo</option>
                                                        <option value="04">Abril</option>
                                                        <option value="05">Mayo</option>
                                                        <option value="06">Junio</option>
                                                        <option value="07">Julio</option>
                                                        <option value="08">Agosto</option>
                                                        <option value="09">Septiembre</option>
                                                        <option value="10">Octubre</option>
                                                        <option value="11">Noviembre</option>
                                                        <option value="12">Diciembre</option>
                                                    </select>
                                                    <select name="anhoFechaRecepcion" required id="anhoFechaRecepcion" class="form-control form-control-sm col-md-2">
                                                        <option value="0">Año</option>

                                                        <?php

                                                        $anho = date('Y');

                                                        for ($i = 2000; $i <= $anho; $i++) {
                                                            echo "<option value='" . $i . "'>" . $i . "</option>";
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="accordion">
                                            <div class="card">
                                                <div class="card-header  bg-purple" id="headingOne" style="padding: 6px 12px;">
                                                    <h5 class="mb-0">
                                                        <button type="button" class="btn btn-link text-white" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                            <b> Datos Técnicos del equipo <i class="fas fa-arrow-alt-circle-down"></i></b>
                                                        </button>
                                                    </h5>
                                                </div>

                                                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                                    <div class="card-body">
                                                        <div class="form-row">
                                                            <div class="form-group col-md-3">
                                                                <label for="codigoBarra" style="font-size:12px">Nombre del equipo:<code>*</code></label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                                                    </div>
                                                                    <input type="text" style="text-transform:uppercase;" class="form-control form-control-sm " id="nombreEquipo" name="nombreEquipo" value="<?php isset($_POST['nombreEquipo']) ? $_POST['nombreEquipo'] : '' ?>" placeholder="Escriba el nombre del equipo">
                                                                </div>
                                                            </div>

                                                            <div class="form-group col-md-3">
                                                                <label for="codInventario" style="font-size:12px">Valor con IVA:</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-money-bill-wave-alt"></i></span>
                                                                    </div>
                                                                    <input type="number" style="text-transform:uppercase;" class="form-control form-control-sm " id="valorIva" name="valorIva" placeholder="Precio con IVA ">
                                                                </div>
                                                                <label for="" id="label" style="color: green; font-weight:bold"></label>
                                                                <script>
                                                                    const input = document.querySelector("#valorIva");
                                                                    const label = document.querySelector("#label");
                                                                    const formatter = new Intl.NumberFormat("es-CL", {
                                                                        style: "currency",
                                                                        currency: "CLP"
                                                                    });

                                                                    input.addEventListener("input", e => {
                                                                        label.textContent = formatter.format(input.value)
                                                                    })
                                                                </script>

                                                            </div>

                                                            <div class="form-group col-md-3">

                                                                <label for="codInventario" style="font-size:12px">Marca:<code>*</code></label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-copyright"></i></span>
                                                                    </div>
                                                                    <input type="text" style="text-transform:uppercase;" class="form-control form-control-sm " id="marcaEquipo" name="marcaEquipo" placeholder="Escriba la marca del equipo">
                                                                </div>

                                                            </div>

                                                            <div class="form-group col-md-3">
                                                                <label for="exampleInputNomTarjeta" style="font-size:12px">ID - Licitación:</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-fingerprint"></i></span>
                                                                    </div>
                                                                    <input type="text" style="text-transform:uppercase" class="form-control form-control-sm " id="idLicitacion" name="idLicitacion" placeholder="ID de Licitación">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-row">
                                                            <div class="form-group col-md-3">
                                                                <label for="departamento" style="font-size:12px">Modelo:<code>*</code></label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                                                    </div>
                                                                    <input type="text" style="text-transform:uppercase" name="modeloEquipo" id="modeloEquipo" class="form-control form-control-sm " placeholder="Escriba el modelo del equipo">
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="departamento" style="font-size:12px">Orden de compra: (1 Orden de compra por linea)</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-folder-open"></i></span>
                                                                    </div>
                                                                    <textarea cols="30" rows="4" style="text-transform: uppercase;" name="ordenCompra" id="ordenCompra" class="form-control form-control-sm "></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px">N° de Serie:</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control form-control-sm " style="text-transform:uppercase" id="numeroSerie" name="numeroSerie" placeholder="Escriba el número de serie">
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px">Fecha de Compra:</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                    </div>
                                                                    <select name="diaFechaCompra" id="" class="form-control form-control-sm  ">
                                                                        <option value="" disabled selected>-- Dia --</option>
                                                                        <option value="01">1</option>
                                                                        <option value="02">2</option>
                                                                        <option value="03">3</option>
                                                                        <option value="04">4</option>
                                                                        <option value="05">5</option>
                                                                        <option value="06">6</option>
                                                                        <option value="07">7</option>
                                                                        <option value="08">8</option>
                                                                        <option value="09">9</option>
                                                                        <option value="10">10</option>
                                                                        <option value="11">11</option>
                                                                        <option value="12">12</option>
                                                                        <option value="13">13</option>
                                                                        <option value="14">14</option>
                                                                        <option value="15">15</option>
                                                                        <option value="16">16</option>
                                                                        <option value="17">17</option>
                                                                        <option value="18">18</option>
                                                                        <option value="19">19</option>
                                                                        <option value="20">20</option>
                                                                        <option value="21">21</option>
                                                                        <option value="22">22</option>
                                                                        <option value="23">23</option>
                                                                        <option value="24">24</option>
                                                                        <option value="25">25</option>
                                                                        <option value="26">26</option>
                                                                        <option value="27">27</option>
                                                                        <option value="28">28</option>
                                                                        <option value="29">29</option>
                                                                        <option value="30">30</option>
                                                                        <option value="31">31</option>
                                                                    </select>
                                                                    <select name="mesFechaCompra" id="" class="form-control form-control-sm  ">
                                                                        <option value="" disabled selected>-- Mes --</option>
                                                                        <option value="01">Enero</option>
                                                                        <option value="02">Febrero</option>
                                                                        <option value="03">Marzo</option>
                                                                        <option value="04">Abril</option>
                                                                        <option value="05">Mayo</option>
                                                                        <option value="06">Junio</option>
                                                                        <option value="07">Julio</option>
                                                                        <option value="08">Agosto</option>
                                                                        <option value="09">Septiembre</option>
                                                                        <option value="10">Octubre</option>
                                                                        <option value="11">Noviembre</option>
                                                                        <option value="12">Diciembre</option>
                                                                    </select>
                                                                    <select name="anhoFechaCompra" class="form-control form-control-sm  ">
                                                                        <option value="0">Año</option>

                                                                        <?php

                                                                        $anho = date('Y');

                                                                        for ($i = 2000; $i <= $anho; $i++) {
                                                                            echo "<option value='" . $i . "'>" . $i . "</option>";
                                                                        } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>



                                                        <div class="form-row">


                                                            <div class="form-group col-md-3">
                                                                <label for="exampleInputNomTarjeta" style="font-size:12px">Fecha Puesta en Marcha:</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                    </div>

                                                                    <select name="diaFechaMarcha" id="" class="form-control form-control-sm  ">
                                                                        <option value="" disabled selected>-- Dia --</option>
                                                                        <option value="01">1</option>
                                                                        <option value="02">2</option>
                                                                        <option value="03">3</option>
                                                                        <option value="04">4</option>
                                                                        <option value="05">5</option>
                                                                        <option value="06">6</option>
                                                                        <option value="07">7</option>
                                                                        <option value="08">8</option>
                                                                        <option value="09">9</option>
                                                                        <option value="10">10</option>
                                                                        <option value="11">11</option>
                                                                        <option value="12">12</option>
                                                                        <option value="13">13</option>
                                                                        <option value="14">14</option>
                                                                        <option value="15">15</option>
                                                                        <option value="16">16</option>
                                                                        <option value="17">17</option>
                                                                        <option value="18">18</option>
                                                                        <option value="19">19</option>
                                                                        <option value="20">20</option>
                                                                        <option value="21">21</option>
                                                                        <option value="22">22</option>
                                                                        <option value="23">23</option>
                                                                        <option value="24">24</option>
                                                                        <option value="25">25</option>
                                                                        <option value="26">26</option>
                                                                        <option value="27">27</option>
                                                                        <option value="28">28</option>
                                                                        <option value="29">29</option>
                                                                        <option value="30">30</option>
                                                                        <option value="31">31</option>
                                                                    </select>
                                                                    <select name="mesFechaMarcha" id="" class="form-control form-control-sm  ">
                                                                        <option value="" disabled selected>-- Mes --</option>
                                                                        <option value="01">Enero</option>
                                                                        <option value="02">Febrero</option>
                                                                        <option value="03">Marzo</option>
                                                                        <option value="04">Abril</option>
                                                                        <option value="05">Mayo</option>
                                                                        <option value="06">Junio</option>
                                                                        <option value="07">Julio</option>
                                                                        <option value="08">Agosto</option>
                                                                        <option value="09">Septiembre</option>
                                                                        <option value="10">Octubre</option>
                                                                        <option value="11">Noviembre</option>
                                                                        <option value="12">Diciembre</option>
                                                                    </select>
                                                                    <select name="anhoFechaMarcha" class="form-control form-control-sm  ">
                                                                        <option value="0">Año</option>

                                                                        <?php

                                                                        $anho = date('Y');

                                                                        for ($i = 2000; $i <= $anho; $i++) {
                                                                            echo "<option value='" . $i . "'>" . $i . "</option>";
                                                                        } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="exampleInputTurno" style="font-size:12px">Servicio:</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-server"></i></span>
                                                                    </div>
                                                                    <select id="servicioOrden" class="form-control form-control-sm " name="servicioOrden">
                                                                        <?php

                                                                        $queryServicios = "SELECT * FROM servicios ORDER BY nomServicio ASC";

                                                                        $resServicios = mysqli_query($conexion, $queryServicios);

                                                                        while ($rowServicios = $resServicios->fetch_assoc()) {

                                                                        ?>
                                                                            <option value="<?php echo $rowServicios['idServicio']; ?>">
                                                                                <?php echo $rowServicios['nomServicio']; ?>
                                                                            </option>
                                                                        <?php

                                                                        }

                                                                        ?>
                                                                        <option value="Otro">
                                                                            Otro
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div name="visualizarNomServicio" id="visualizarNomServicio" class="form-group form-control-sm col-md-3">

                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="exampleInputNomTarjeta" style="font-size:12px">Recinto:</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control form-control-sm " style="text-transform: uppercase;" id="nombreRecinto" name="nombreRecinto" placeholder="¿DÓNDE SE ENCUENTRA EL EQUIPO?">
                                                                </div>
                                                            </div>
                                                        </div>



                                                        <script>
                                                            let idServicio = document.getElementById("idServicio");

                                                            idServicio.addEventListener('change', function() {
                                                                if (idServicio.value == 24) {
                                                                    let otroServicio = document.getElementById("otroServicio");
                                                                    otroServicio.removeAttribute("disabled");
                                                                    otroServicio.style.removeProperty("cursor")

                                                                } else {
                                                                    otroServicio.setAttribute("disabled", "disabled");
                                                                    otroServicio.style.setProperty("cursor", "not-allowed");
                                                                }
                                                            })
                                                        </script>


                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header bg-success" id="headingTwo" style="padding: 6px 12px;">
                                                    <h5 class="mb-0">
                                                        <button type="button" class="btn btn-link collapsed text-white" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                            <b>Información Extra <i class="fas fa-arrow-alt-circle-down"></i></b>
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                                    <div class="card-body">
                                                        <div class="form-row">
                                                            <div class="form-group col-md-3">
                                                                <label for="codigoBarra" style="font-size:12px">Clase:</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control form-control-sm " id="claseEquipo" name="claseEquipo" placeholder="Tipo de clase del equipo">
                                                                </div>
                                                            </div>

                                                            <div class="form-group col-md-3">
                                                                <label for="codInventario" style="font-size:12px">Sub-clase:</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control form-control-sm " id="subClaseEquipo" name="subClaseEquipo" placeholder="Tipo de sub-clase del equipo">
                                                                </div>
                                                            </div>

                                                            <div class="form-group col-md-3">

                                                                <label for="codInventario" style="font-size:12px">Complejidad Tecnológica:</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control form-control-sm " id="compleEquipo" name="compleEquipo" placeholder="Escriba la complejidad técnologica del equipo">
                                                                </div>

                                                            </div>

                                                            <div class="form-group col-md-3">
                                                                <label for="exampleInputNomTarjeta" style="font-size:12px">Nivel de riesgo:</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control form-control-sm " id="nivelRiesgo" name="nivelRiesgo" placeholder="Escriba el nivel de riesgo del equipo">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-row">
                                                            <div class="form-group col-md-3">
                                                                <label for="departamento" style="font-size:12px">Año Fabricación:</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                                    </div>
                                                                    <input type="text" name="ahnoFabricacion" id="ahnoFabricacion" class="form-control form-control-sm " placeholder="ESCRIBA EL AÑO DE FABRICACIÓN DEL EQUIPO">
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="departamento" style="font-size:12px">Vida Util:</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-heart"></i></span>
                                                                    </div>
                                                                    <input type="text" name="vidaUtil" id="vidaUtil" class="form-control form-control-sm " placeholder="Escriba los años de vida util del equipo"></input>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px">Versión del software:</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-code-branch"></i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control form-control-sm " id="versionSoftware" name="versionSoftware" placeholder="Escriba la versión del software">
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px">Pais Origen:</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-globe-americas"></i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control form-control-sm " id="paisOrigen" name="paisOrigen" placeholder="ESCRIBA EL PAIS DE ORIGEN DEL EQUIPO">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-row">
                                                            <div class="form-group col-md-3">
                                                                <label for="exampleInputNomTarjeta" style="font-size:12px">Termino de garantía:</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-dizzy"></i></span>
                                                                    </div>

                                                                    <select name="diaFechaGarantia" id="" class="form-control form-control-sm  ">
                                                                        <option value="" disabled selected>-- Dia --</option>
                                                                        <option value="01">1</option>
                                                                        <option value="02">2</option>
                                                                        <option value="03">3</option>
                                                                        <option value="04">4</option>
                                                                        <option value="05">5</option>
                                                                        <option value="06">6</option>
                                                                        <option value="07">7</option>
                                                                        <option value="08">8</option>
                                                                        <option value="09">9</option>
                                                                        <option value="10">10</option>
                                                                        <option value="11">11</option>
                                                                        <option value="12">12</option>
                                                                        <option value="13">13</option>
                                                                        <option value="14">14</option>
                                                                        <option value="15">15</option>
                                                                        <option value="16">16</option>
                                                                        <option value="17">17</option>
                                                                        <option value="18">18</option>
                                                                        <option value="19">19</option>
                                                                        <option value="20">20</option>
                                                                        <option value="21">21</option>
                                                                        <option value="22">22</option>
                                                                        <option value="23">23</option>
                                                                        <option value="24">24</option>
                                                                        <option value="25">25</option>
                                                                        <option value="26">26</option>
                                                                        <option value="27">27</option>
                                                                        <option value="28">28</option>
                                                                        <option value="29">29</option>
                                                                        <option value="30">30</option>
                                                                        <option value="31">31</option>
                                                                    </select>
                                                                    <select name="mesFechaGarantia" id="" class="form-control form-control-sm  ">
                                                                        <option value="" disabled selected>-- Mes --</option>
                                                                        <option value="01">Enero</option>
                                                                        <option value="02">Febrero</option>
                                                                        <option value="03">Marzo</option>
                                                                        <option value="04">Abril</option>
                                                                        <option value="05">Mayo</option>
                                                                        <option value="06">Junio</option>
                                                                        <option value="07">Julio</option>
                                                                        <option value="08">Agosto</option>
                                                                        <option value="09">Septiembre</option>
                                                                        <option value="10">Octubre</option>
                                                                        <option value="11">Noviembre</option>
                                                                        <option value="12">Diciembre</option>
                                                                    </select>
                                                                    <select name="anhoFechaGarantia" class="form-control form-control-sm  ">
                                                                        <option value="0">Año</option>

                                                                        <?php

                                                                        $anho = date('Y');

                                                                        for ($i = 2000; $i <= $anho; $i++) {
                                                                            echo "<option value='" . $i . "'>" . $i . "</option>";
                                                                        } ?>
                                                                    </select>

                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px">Costo Mantenimiento Anual:</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                                                    </div>
                                                                    <input type="number" class="form-control form-control-sm " name="costoAnualMantenimiento" id="costoAnualMantenimiento" placeholder="COSTO ANUAL DEL MANTENIMIENTO DEL EQUIPO">
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="exampleInputNomTarjeta" style="font-size:12px">Frecuencia Del Mantenimiento:</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-wave-square"></i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control form-control-sm " id="frecuenciaMantenimiento" name="frecuenciaMantenimiento" placeholder="ESCRIBA LA FRECUENCIA DEL MANTENIMIENTO">
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card ">
                                                <div class="card-header bg-danger" id="headingThree" style="padding: 6px 12px;">
                                                    <h5 class="mb-0">
                                                        <button type="button" class="btn btn-link collapsed text-white" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                            <b>Información Técnica y Mantenimiento Existente <i class="fas fa-arrow-alt-circle-down"></i></b>
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                                                    <div class="card-body">
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <div class="form-group">
                                                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                                        <input type="checkbox" class="custom-control-input" id="pauta" onclick="togglePauta()">
                                                                        <label class="custom-control-label" for="pauta" style="font-size: 12px;">Adjuntar Pautas</label>
                                                                        <input type="hidden" name="pauta" value="NO" id="tienePauta">
                                                                        <script type="text/javascript">
                                                                            document.getElementById('pauta').onchange = function() {
                                                                                var tienePauta = document.getElementById('tienePauta').value;
                                                                                var textoPautas = document.getElementById('textoPautas');
                                                                                var adjuntosPauta = document.getElementById('adjuntosPauta[]');

                                                                                if (tienePauta == 'SI') {
                                                                                    document.getElementById('tienePauta').value = 'NO';
                                                                                    textoPautas.removeAttribute("required");
                                                                                    adjuntosPauta.removeAttribute("required");

                                                                                } else if (tienePauta == 'NO') {
                                                                                    document.getElementById('tienePauta').value = 'SI';
                                                                                    textoPautas.setAttribute("required", "");
                                                                                    adjuntosPauta.setAttribute("required", "");

                                                                                } else {
                                                                                    document.getElementById('tienePauta').value = 'NO';
                                                                                    textoPautas.removeAttribute("required");
                                                                                    adjuntosPauta.removeAttribute("required");
                                                                                }
                                                                            };
                                                                        </script>
                                                                    </div>
                                                                    <script>
                                                                        function togglePauta() {
                                                                            // var element = document.getElementById("navBarPrincipal")
                                                                            // element.classList.toggle("dark-mode")
                                                                            var pautaInput = document.getElementById("pautaInput");
                                                                            pautaInput.classList.toggle("ocultar-pauta");
                                                                        }
                                                                    </script>
                                                                </div>
                                                                <br>
                                                                <div class="form-group ocultar-pauta" id="pautaInput">
                                                                    <!-- <label for="customFile">Custom File</label> -->
                                                                    <label for="exampleInputTareasControlCent" style="font-size: 12px;">Subir Archivos Adjuntos: </label><br>
                                                                    <label for="exampleInputTareasControlCent" style="font-size: 12px;">Formatos Permitidos: .pdf , .doc , .docx , .xls , .xlsx, .eml</label>
                                                                    <div class="custom-file">
                                                                        <input type="file" max-uploads="40" class="custom-file-input" multiple="" id="adjuntosPauta[]" name="adjuntosPauta[]" accept=".pdf, .doc, .docx, .xls, .xlsx, .eml">
                                                                        <label class="custom-file-label" for="customFile" style="font-size: 12px;">Seleccionar Archivo...</label>
                                                                    </div>
                                                                    <br>
                                                                    <br>
                                                                    <label for="exampleInputTareasControlCent" style="font-size: 12px;">Nombre de los archivos subidos :</label><br>
                                                                    <textarea name="textoPautas" id="textoPautas" cols="30" rows="5" class="form-control form-control-sm"></textarea>
                                                                    <script type="text/javascript">
                                                                        $('.custom-file input').change(function(e) {
                                                                            var files = [];
                                                                            for (var i = 0; i < $(this)[0].files.length; i++) {
                                                                                files.push($(this)[0].files[i].name);
                                                                            }
                                                                            $(this).next('.custom-file-label').html(files.join(', '));
                                                                        });
                                                                    </script>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <div class="form-group">
                                                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                                        <input type="checkbox" class="custom-control-input" id="certificado" onclick="toggleCertificados()">
                                                                        <label class="custom-control-label" for="certificado" style="font-size:12px;">Adjuntar Certificados</label>
                                                                        <input type="hidden" name="certificado" value="NO" id="tieneCertificado">
                                                                        <script type="text/javascript">
                                                                            document.getElementById('certificado').onchange = function() {
                                                                                var tieneCertificado = document.getElementById('tieneCertificado').value;
                                                                                var textoCertificados = document.getElementById('textoCertificados');
                                                                                var adjuntosCertificados = document.getElementById('adjuntosCertificados[]');

                                                                                if (tieneCertificado == 'SI') {
                                                                                    document.getElementById('tieneCertificado').value = 'NO';
                                                                                    textoCertificados.removeAttribute("required");
                                                                                    adjuntosCertificados.removeAttribute("required");

                                                                                } else if (tieneCertificado == 'NO') {
                                                                                    document.getElementById('tieneCertificado').value = 'SI';
                                                                                    textoCertificados.setAttribute("required", "");
                                                                                    adjuntosCertificados.setAttribute("required", "");

                                                                                } else {
                                                                                    document.getElementById('tieneCertificado').value = 'NO';
                                                                                    textoCertificados.removeAttribute("required");
                                                                                    adjuntosCertificados.removeAttribute("required");
                                                                                }
                                                                            };
                                                                        </script>
                                                                    </div>
                                                                    <script>
                                                                        function toggleCertificados() {
                                                                            // var element = document.getElementById("navBarPrincipal")
                                                                            // element.classList.toggle("dark-mode")
                                                                            var certificadosInput = document.getElementById("certificadosInput");
                                                                            certificadosInput.classList.toggle("ocultar-certificados");
                                                                        }
                                                                    </script>
                                                                </div>
                                                                <br>
                                                                <div class="form-group ocultar-certificados" id="certificadosInput">
                                                                    <!-- <label for="customFile">Custom File</label> -->
                                                                    <label for="exampleInputTareasControlCent" style="font-size:12px;">Subir Archivos Adjuntos: </label><br>
                                                                    <label for="exampleInputTareasControlCent" style="font-size:12px;">Formatos Permitidos: .pdf , .doc , .docx , .xls , .xlsx, .eml</label>
                                                                    <div class="custom-file">
                                                                        <input type="file" max-uploads="40" class="custom-file-input" multiple="" id="adjuntosCertificados[]" name="adjuntosCertificados[]" accept=".pdf, .doc, .docx, .xls, .xlsx, .eml">
                                                                        <label class="custom-file-label" for="customFile" style="font-size:12px;">Seleccionar Archivo...</label>
                                                                    </div>
                                                                    <br>
                                                                    <br>
                                                                    <label for="exampleInputTareasControlCent" style="font-size:12px;">Nombre de los archivos subidos :</label><br>
                                                                    <textarea name="textoCertificados" id="textoCertificados" cols="30" rows="5" class="form-control form-control-sm"></textarea>
                                                                    <script type="text/javascript">
                                                                        $('.custom-file input').change(function(e) {
                                                                            var files = [];
                                                                            for (var i = 0; i < $(this)[0].files.length; i++) {
                                                                                files.push($(this)[0].files[i].name);
                                                                            }
                                                                            $(this).next('.custom-file-label').html(files.join(', '));
                                                                        });
                                                                    </script>
                                                                </div>
                                                            </div>
                                                        </div><!-- .pauta-->

                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <div class="form-group">
                                                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                                        <input type="checkbox" class="custom-control-input" id="manual" onclick="toggleManual()">
                                                                        <label class="custom-control-label" for="manual" style="font-size:12px;">Adjuntar Manuales</label>
                                                                        <input type="hidden" name="manual" value="NO" id="tieneManual">
                                                                        <script type="text/javascript">
                                                                            document.getElementById('manual').onchange = function() {
                                                                                var tieneManual = document.getElementById('tieneManual').value;
                                                                                var textoManuales = document.getElementById('textoManuales');
                                                                                var adjuntosManuales = document.getElementById('adjuntosManuales[]');

                                                                                if (tieneManual == 'SI') {
                                                                                    document.getElementById('tieneManual').value = 'NO';
                                                                                    textoManuales.removeAttribute("required");
                                                                                    adjuntosManuales.removeAttribute("required");

                                                                                } else if (tieneManual == 'NO') {
                                                                                    document.getElementById('tieneManual').value = 'SI';
                                                                                    textoManuales.setAttribute("required", "");
                                                                                    adjuntosManuales.setAttribute("required", "");

                                                                                } else {
                                                                                    document.getElementById('tieneManual').value = 'NO';
                                                                                    textoManuales.removeAttribute("required");
                                                                                    adjuntosManuales.removeAttribute("required");
                                                                                }
                                                                            };
                                                                        </script>
                                                                    </div>
                                                                    <script>
                                                                        function toggleManual() {
                                                                            // var element = document.getElementById("navBarPrincipal")
                                                                            // element.classList.toggle("dark-mode")
                                                                            var manualInput = document.getElementById("manualInput");
                                                                            manualInput.classList.toggle("ocultar-manual");
                                                                        }
                                                                    </script>
                                                                </div>
                                                                <br>
                                                                <div class="form-group ocultar-manual" id="manualInput">
                                                                    <!-- <label for="customFile">Custom File</label> -->
                                                                    <label for="exampleInputTareasControlCent" style="font-size:12px;">Subir Archivos Adjuntos: </label><br>
                                                                    <label for="exampleInputTareasControlCent" style="font-size:12px;">Formatos Permitidos: .pdf , .doc , .docx , .xls , .xlsx, .eml</label>
                                                                    <div class="custom-file">
                                                                        <input type="file" max-uploads="40" class="custom-file-input" multiple="" id="adjuntosManuales[]" name="adjuntosManuales[]" accept=".pdf, .doc, .docx, .xls, .xlsx, .eml">
                                                                        <label class="custom-file-label" for="customFile" style="font-size:12px;">Seleccionar Archivo...</label>
                                                                    </div>
                                                                    <br>
                                                                    <br>
                                                                    <label for="exampleInputTareasControlCent" style="font-size:12px;">Nombre de los archivos subidos :</label><br>
                                                                    <textarea name="textoManuales" id="textoManuales" cols="30" rows="5" class="form-control"></textarea>
                                                                    <script type="text/javascript">
                                                                        $('.custom-file input').change(function(e) {
                                                                            var files = [];
                                                                            for (var i = 0; i < $(this)[0].files.length; i++) {
                                                                                files.push($(this)[0].files[i].name);
                                                                            }
                                                                            $(this).next('.custom-file-label').html(files.join(', '));
                                                                        });
                                                                    </script>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <div class="form-group">
                                                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                                        <input type="checkbox" class="custom-control-input" id="otros" onclick="toggleOtros()">
                                                                        <label class="custom-control-label" for="otros" style="font-size:12px;">Adjuntar Otros Tipos de Archivos</label>
                                                                        <input type="hidden" name="otros" value="NO" id="tieneOtros">
                                                                        <script type="text/javascript">
                                                                            document.getElementById('otros').onchange = function() {
                                                                                var tieneOtros = document.getElementById('tieneOtros').value;
                                                                                var textoOtros = document.getElementById('textoOtros');
                                                                                var adjuntosOtros = document.getElementById('adjuntosOtros[]');

                                                                                if (tieneOtros == 'SI') {
                                                                                    document.getElementById('tieneOtros').value = 'NO';
                                                                                    textoOtros.removeAttribute("required");
                                                                                    adjuntosOtros.removeAttribute("required");

                                                                                } else if (tieneOtros == 'NO') {
                                                                                    document.getElementById('tieneOtros').value = 'SI';
                                                                                    textoOtros.setAttribute("required", "");
                                                                                    adjuntosOtros.setAttribute("required", "");

                                                                                } else {
                                                                                    document.getElementById('tieneOtros').value = 'NO';
                                                                                    textoOtros.removeAttribute("required");
                                                                                    adjuntosOtros.removeAttribute("required");
                                                                                }
                                                                            };
                                                                        </script>
                                                                    </div>
                                                                    <script>
                                                                        function toggleOtros() {
                                                                            // var element = document.getElementById("navBarPrincipal")
                                                                            // element.classList.toggle("dark-mode")
                                                                            var otrosInput = document.getElementById("otrosInput");
                                                                            otrosInput.classList.toggle("ocultar-otros");
                                                                        }
                                                                    </script>
                                                                </div>
                                                                <br>
                                                                <div class="form-group ocultar-otros" id="otrosInput">
                                                                    <!-- <label for="customFile">Custom File</label> -->
                                                                    <label for="exampleInputTareasControlCent" style="font-size:12px;">Subir Archivos Adjuntos: (Imagenes y cualquier otro archivo que no cabe en las anteriores categorias)</label><br>
                                                                    <label for="exampleInputTareasControlCent" style="font-size:12px;">Formatos Permitidos: .pdf , .doc , .docx , .xls , .xlsx, .eml, .png, .jpeg, .gif, .svg, .jpg</label>
                                                                    <div class="custom-file">
                                                                        <input type="file" max-uploads="40" class="custom-file-input" multiple="" id="adjuntosOtros[]" name="adjuntosOtros[]" accept=".pdf, .doc, .docx, .xls, .xlsx, .eml, .png, .jpeg, .gif, .svg, .jpg">
                                                                        <label class="custom-file-label" for="customFile" style="font-size:12px;">Seleccionar Archivo...</label>
                                                                    </div>
                                                                    <br>
                                                                    <br>
                                                                    <label for="exampleInputTareasControlCent" style="font-size:12px;">Nombre de los archivos subidos : </label><br>
                                                                    <textarea name="textoOtros" id="textoOtros" cols="30" rows="5" class="form-control"></textarea>
                                                                    <script type="text/javascript">
                                                                        $('.custom-file input').change(function(e) {
                                                                            var files = [];
                                                                            for (var i = 0; i < $(this)[0].files.length; i++) {
                                                                                files.push($(this)[0].files[i].name);
                                                                            }
                                                                            $(this).next('.custom-file-label').html(files.join(', '));
                                                                        });
                                                                    </script>
                                                                </div>
                                                            </div>
                                                        </div><!-- .manuales-->

                                                        <div class="form-row">
                                                            <div class="from-group col-md-6">
                                                                <label for="accesorios" class="form-label" style="font-size:12px;">Accesorios:</label>
                                                                <textarea name="textoAccesorios" id="textoAccesorios" rows="3" class="form-control form-control-sm" placeholder="ESCRIBA LOS ACCESORIOS DEL EQUIPO"></textarea>
                                                            </div>
                                                            <div class="from-group col-md-6">
                                                                <label for="accesorios" class="form-label" style="font-size:12px;">Insumos:</label>
                                                                <textarea name="textoInsumos" id="textoInsumos" rows="3" class="form-control form-control-sm" placeholder="ESCRIBA LOS INSUMOS DEL EQUIPO"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header bg-primary" id="headingFour" style="padding: 6px 12px;">
                                                    <h5 class="mb-0">
                                                        <button type="button" class="btn btn-link collapsed text-white" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                                            <b>Datos contacto Servicio Técnico <i class="fas fa-arrow-alt-circle-down"></i></b>
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                                                    <div class="card-body">
                                                        <div class="form-row">
                                                            <div class="form-group col-md-3">
                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px;">Proveedor:</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-store"></i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control form-control-sm" style="text-transform: uppercase;" id="nombreProveedor" name="nombreProveedor" placeholder="ESCRIBA EL NOMBRE DEL PROVEEDOR">
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px;">Dirección:</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control form-control-sm" style="text-transform: uppercase;" id="direccionProveedor" name="direccionProveedor" placeholder="ESCRIBA LA DIRECCIÓN EXACTA DEL PROVEEDOR">
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px;">Teléfono/Fax:</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
                                                                    </div>
                                                                    <input type="tel" class="form-control form-control-sm" style="text-transform: uppercase;" id="telefonoProveedor" name="telefonoProveedor" placeholder="ESCRIBA EL NÚMERO TELÉFONICO DE CONTACTO CON CÓDIGO DE AREA ">
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px;">E-mail</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control form-control-sm" style="text-transform: uppercase;" id="emailProveedor" name="emailProveedor" placeholder="ESCRIBA EL EMAIL DE CONTACTO">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-row">


                                                            <div class="form-group col-md-3">
                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px;">Nombre del contacto</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control form-control-sm" style="text-transform: uppercase;" id="nombreContactoProveedor" name="nombreContactoProveedor" placeholder="ESCRIBA EL NOMBRE DEL CONTACTO">
                                                                </div>
                                                            </div>

                                                            <div class="form-group col-md-9">
                                                                <label for="" style="font-size:12px;">Observaciones o sugerencias:</label>
                                                                <textarea name="observacionesContactoProveedor" id="observacionesContactoProveedor"  rows="5" class="form-control form-control-sm" placeholder="Escriba todas las observaciones necesarios aqui o sugerencias..."></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <p style="color:green;font-weight:bold;text-align:center">**Este botón solo se activara si todos los campos obligatorios fueron llenados**</p>
                                        <button type="submit" id="guardar" onclick="numerar();" disabled class="btn bg-purple btn-block"> <b>Guardar</b> </button>
                                    </form>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!--/.col  -->
                </div>
                <!-- /.row -->
        </div><!-- /.container-fluid -->
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
    <!-- bs-custom-file-input -->
    <script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>
    <!-- Page specific script -->
    <script>
        $(function() {
            bsCustomFileInput.init();
        });
    </script>
    <script>
        $(function() {
            $('[data-toggle="popover"]').popover()
        })
    </script>
    <script src="js/validaFormIngresoEquipo.js"></script>
    <script src="js/dark-mode.js"></script>
    <!-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("formulario").addEventListener('submit', validarFormulario);
        });

        function validarFormulario(evento) {
            evento.preventDefault();
            var id = document.getElementById('id').value;
            if (id.length == 0) {
                alert('No has escrito nada en el identificador unico');
                return;
            }

            this.submit();
        }
    </script> -->
</body>

</html>