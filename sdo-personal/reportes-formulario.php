<?php

include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reporte de Turnos</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <script type="text/javascript">
        $(document).ready(function () {
            verificarListaTurno();

            $('#listaTurno').change(function () {
                verificarListaTurno();
            });


            function verificarListaTurno() {
                var listaTurno = document.getElementById("listaTurno");
                var guardarReporte = document.getElementById("guardarReporte");
                if (listaTurno.value === "") {
                    guardarReporte.disabled = true;
                } else {
                    guardarReporte.disabled = false;
                }
            };
        });
    </script>
    <style>
        /* select2-custom.css */
        /* Estilos para modo claro */
        .select2-container--bootstrap4 .select2-results__option {
            color: #000000; /* Color rojo para el texto en modo claro */
        }

        .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice,
        .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            color: #000000; /* Color rojo para el texto de elementos seleccionados en modo claro */
            background-color: #b1b1b3;
        }

        /* Estilos para modo oscuro */
        .dark-mode .select2-container--bootstrap4 .select2-results__option {
            color: #ffffff; /* Color rojo para el texto en modo oscuro */

        }

        .dark-mode .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice,
        .dark-mode .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            color: #000000; /* Color rojo para el texto de elementos seleccionados en modo oscuro */
            background-color: #b1b1b3;
        }

    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/user-sidebar.php';
    ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Formulario de Reportes</h1>
                    </div>
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
                <form id="formGuardarReporte" action="../sdo-funciones/reportes-generar.php" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-12">

                            <!-- general form elements -->
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Formulario de Reportes</h3>
                                </div>
                                <!-- /.card-header -->
                                <?php


                                if (isset($_GET['errorGenerar'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Crear Reporte. Reintente.</h5></div>';
                                }


                                ?>
                                <div class="card-body">

                                    <a href="reportes-listado.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
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
                                                <input type="datetime" readonly="readonly" id="fechaActualReporte" name="fechaActualReporte" class="form-control" value="<?php echo date('d-m-Y'); ?>"/>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputNomEncargadoTurno">T&eacute;cnico Encargado de Instrumentaci&oacute;n en Turno:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <select class="form-control listadoEncInstru" required id="nomEncargadoTurno" name="nomEncargadoTurno[]" multiple="multiple" onchange="limitarSeleccion(this)" >
                                                    <option value="Seleccione..." style="font-weight: bold" disabled>Seleccione...</option>
                                                    <?php
//                                                    $sqlUsuarios = "SELECT nomPersonal AS nomEmpleado FROM personal p WHERE departamentoPersonal = $idDepartamento ORDER BY nomPersonal";

                                                    // Consulta SQL ajustada
                                                    $sqlUsuarios = "
                                                        SELECT nomEmpleado FROM (
                                                            SELECT nomPersonal AS nomEmpleado 
                                                            FROM personal 
                                                            WHERE departamentoPersonal = $idDepartamento 
                                                            AND activoPersonal = 1
                                                            
                                                            UNION
                                                            
                                                            SELECT nomJefe AS nomEmpleado
                                                            FROM jefes_tecnicos
                                                            WHERE idDepartamento = $idDepartamento
                                                            AND userActivado = 1
                                                        ) AS empleados
                                                        ORDER BY nomEmpleado
                                                    ";



                                                    $resUsuarios = mysqli_query($conexion, $sqlUsuarios);

                                                    // Agrupar los nombres de usuarios y sus respectivos empleados
                                                    $grupoNombres = [
                                                        'Abel Torres Arancibia' => ['Abel Torres Arancibia', 'Manuel Torres Sagredo'],
                                                        'Manuel Torres Sagredo' => ['Abel Torres Arancibia', 'Manuel Torres Sagredo'],
                                                        'Felipe Retamal Fuentes' => ['Felipe Retamal Fuentes', 'Jesús Silva Pellet'],
                                                        'Jesús Silva Pellet' => ['Felipe Retamal Fuentes', 'Jesús Silva Pellet'],
                                                        'Gonzalo Cáceres Carreño' => ['Gonzalo Cáceres Carreño', 'Luis Pérez Bernal'],
                                                        'Luis Pérez Bernal' => ['Gonzalo Cáceres Carreño', 'Luis Pérez Bernal'],
                                                        'Joaquín Jorquera Gonzalez' => ['Joaquín Jorquera Gonzalez', 'Rene Condell Vargas'],
                                                        'Rene Condell Vargas' => ['Joaquín Jorquera Gonzalez', 'Rene Condell Vargas']
                                                    ];

                                                    // Función para determinar si un nombre debe ser seleccionado
                                                    function esSeleccionado($nomUsuario, $nomEmpleado, $grupoNombres) {
                                                        return in_array($nomUsuario, array_keys($grupoNombres)) && in_array($nomEmpleado, $grupoNombres[$nomUsuario]);
                                                    }

                                                    if ($resUsuarios->num_rows > 0) {
                                                        while ($rowUsuarios = $resUsuarios->fetch_assoc()) {
                                                            $nomEmpleado = trim($rowUsuarios['nomEmpleado']);
                                                            $selected = esSeleccionado($nomUsuario, $nomEmpleado, $grupoNombres) ? 'selected' : '';
                                                            echo "<option value=\"$nomEmpleado\" $selected>$nomEmpleado</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputNomEncargadoTurno">Funcionarios de Especialidades de Turno: (Separar nombres con: ' , ' )<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-users"></i></span>
                                                </div>
                                                <select class="form-control listadoEspecialidades" required id="nomFuncionariosTurno" name="nomFuncionariosTurno[]" multiple="multiple" onchange="limitarSeleccion(this)">
                                                    <option value="Seleccione..." disabled>Seleccione...</option>
                                                    <?php
                                                    $sqlDepto = "SELECT idDepartamento, nomDepartamento FROM departamentos WHERE idDepartamento = 1 OR idDepartamento = 2 OR idDepartamento = 3 OR idDepartamento = 4 OR idDepartamento = 5 OR idDepartamento = 12 ORDER BY nomDepartamento";
                                                    $resDepto = mysqli_query($conexion, $sqlDepto);

                                                    $departamentos = [];

                                                    if ($resDepto->num_rows > 0) {
                                                        while ($rowDepto = $resDepto->fetch_assoc()) {
                                                            if ($rowDepto['nomDepartamento'] == 'Instrumentación') {
                                                                $rowDepto['nomDepartamento'] = 'Control Centralizado';
                                                            }
                                                            $departamentos[] = $rowDepto;
                                                        }

                                                        usort($departamentos,function ($a,$b){
                                                            return strcmp($a['nomDepartamento'],$b['nomDepartamento']);
                                                        });

                                                        foreach ($departamentos as $departamento){
                                                            $idDeptoBuscar = $departamento['idDepartamento'];
                                                            echo '<optgroup label="' . $departamento['nomDepartamento'] . '">';
                                                            $sqlUsuarios = "SELECT nomPersonal AS nomEmpleado FROM personal p WHERE departamentoPersonal = $idDeptoBuscar AND activoPersonal = 1 ORDER BY nomPersonal";
                                                            $resUsuarios = mysqli_query($conexion, $sqlUsuarios);

                                                            if ($resUsuarios->num_rows > 0) {
                                                                while ($rowUsuarios = $resUsuarios->fetch_assoc()) {
                                                                    $nomEmpleado = trim($rowUsuarios['nomEmpleado']);

                                                                    echo "<option value=\"$nomEmpleado\">$nomEmpleado</option>";
                                                                }
                                                            }
                                                        }
                                                    }


                                                    ?>
                                                </select>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputNomEncargadoTurnoAnterior">T&eacute;cnico Encargado de Instrumentaci&oacute;n en Turno Anterior:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <select class="form-control listadoEncInstruAnterior" required id="nomEncargadoTurnoAnterior" name="nomEncargadoTurnoAnterior[]" multiple="multiple" onchange="limitarSeleccion(this)" >
                                                    <option value="Seleccione..." style="font-weight: bold" disabled >Seleccione...</option>
                                                    <?php
                                                    // Consulta SQL ajustada
                                                    $sqlUsuarios = "
                                                        SELECT nomEmpleado FROM (
                                                            SELECT nomPersonal AS nomEmpleado 
                                                            FROM personal 
                                                            WHERE departamentoPersonal = $idDepartamento 
                                                            AND activoPersonal = 1
                                                            
                                                            UNION
                                                            
                                                            SELECT nomJefe AS nomEmpleado
                                                            FROM jefes_tecnicos
                                                            WHERE idDepartamento = $idDepartamento
                                                            AND userActivado = 1
                                                        ) AS empleados
                                                        ORDER BY nomEmpleado
                                                    ";

                                                    $resUsuarios = mysqli_query($conexion, $sqlUsuarios);

                                                    if ($resUsuarios->num_rows > 0) {
                                                        while ($rowUsuarios = $resUsuarios->fetch_assoc()) {
                                                            $nomEmpleado = trim($rowUsuarios['nomEmpleado']);

                                                            echo "<option value=\"$nomEmpleado\">$nomEmpleado</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTurno">Turno realizado:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                </div>
                                                <select id="listaTurno" class="form-control" required name="listaTurno">
                                                    <option value="">Seleccione...</option>
                                                    <option value="Dia">Día (08:00 - 20:00)</option>
                                                    <option value="Noche">Noche (20:00 - 08:00)</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>


                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputtareasControlCentralizado">Eventos Relevantes en Control Centralizado:</label>
                                            <textarea id="tareasControlCentralizado" style="resize:none;" name="tareasControlCentralizado" class="form-control" rows="8" placeholder="Eventos Relevantes en Control Centralizado"></textarea>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputNomEncargadoTurno">Eventos Relevantes en Inst. T&eacute;rmicas, Climatizaci&oacute;n y Refrigeraci&oacute;n:</label>
                                            <textarea id="tareasInstalaciones" style="resize:none;" name="tareasInstalaciones" class="form-control" rows="8" placeholder="Eventos Relevantes en Inst. T&eacute;rmicas, Climatizaci&oacute;n y Refrigeraci&oacute;n"></textarea>

                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTareasControlCent">Eventos Relevantes en Equipos Industriales y Combustibles:</label>
                                            <textarea id="tareasEquiposIndustriales" style="resize:none;" name="tareasEquiposIndustriales" class="form-control" rows="8" placeholder="Eventos Relevantes en Equipos Industriales y Combustibles"></textarea>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputNomEncargadoTurno">Eventos Relevantes en Detecci&oacute;n de Incendios:</label>
                                            <textarea id="tareasIncendiosCombustibles" style="resize:none;" name="tareasIncendiosCombustibles" class="form-control" rows="8" placeholder="Eventos Relevantes en Detecci&oacute;n de Incendios"></textarea>

                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTareasControlCent">Eventos Relevantes en Detecci&oacute;n de Instalaci&oacute;n El&eacute;ctrica:</label>
                                            <textarea id="tareasDeteccionElectrica" style="resize:none;" name="tareasDeteccionElectrica" class="form-control" rows="8" placeholder="Eventos Relevantes en Detecci&oacute;n de Instalaci&oacute;n El&eacute;ctrica"></textarea>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputNomEncargadoTurno">Eventos Relevantes en Infraestructura:</label>
                                            <textarea id="tareasInfraestructura" style="resize:none;" name="tareasInfraestructura" class="form-control" rows="8" placeholder="Eventos Relevantes en Infraestructura"></textarea>

                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="pendientesCoordinacion">Otros Eventos y/o Informaciones:</label>
                                            <textarea id="pendientesCoordinacion" style="resize:none;" name="pendientesCoordinacion" class="form-control" rows="8" placeholder="Otros Eventos y/o Informaciones"></textarea>

                                        </div>
                                    </div>

                                    <hr>

                                    <div class="form-group">
                                        <!-- <label for="customFile">Custom File</label> -->
                                        <label for="exampleInputTareasControlCent">Subir Imagenes: (Maximo 20 Imagenes)<code>*</code></label><br>
                                        <label for="exampleInputTareasControlCent">Formatos Permitidos: .jpg, .png, .jpeg, .bmp, .tif, .webp, .svg, .jfif</label>
                                        <div class="custom-file">
                                            <input type="file" max-uploads="20" class="custom-file-input" multiple="" id="archivoImagen[]" name="archivoImagen[]" accept=".jpg, .png, .jpeg, .bmp, .tif, .webp, .svg, .jfif">
                                            <label class="custom-file-label" for="customFile">Seleccionar Archivo...</label>
                                        </div>
                                    </div>

                                    <hr>

                                    <br>
                                    <br>

                                    <div class="form-group">
                                        <input type="hidden" name="idUsuario" id="idUsuario" value="<?php echo $idUsuario; ?>">
                                        <input type="submit" id="guardarReporte" class="btn btn-block btn-primary" value="Guardar"/>

                                    </div>
                                    <div class="form-group">
                                        <a href="index.php" class="btn btn-block btn-warning"> Volver </a>
                                    </div>

                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->

                        </div>
                        <!--/.col  -->

                    </div>
                </form>
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

<script src="js/dark-mode.js"></script>
<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- bs-custom-file-input -->
<script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Page specific script -->
<!-- Select2 -->
<script src="../plugins/select2/js/select2.full.min.js"></script>

<script>

    $(document).ready(function () {

        //Initialize Select2 Elements
        $('.listadoEncInstru').select2({
            placeholder: 'Técnico Encargado de Instrumentación en Turno',
            theme: 'bootstrap4',
            allowClear: true
        });

        $('.listadoEspecialidades').select2({
            placeholder: 'Técnicos de Especialidades',
            theme: 'bootstrap4',
            allowClear: true
        });

        $('.listadoEncInstruAnterior').select2({
            placeholder: 'Técnico Encargado de Instrumentación en Turno Anterior',
            theme: 'bootstrap4',
            allowClear: true
        });

    });
</script>
<script>
    $(function () {
        bsCustomFileInput.init();
    });
</script>
<script>

    document.addEventListener("DOMContentLoaded", function () {
        // Obtén el botón y el formulario
        var guardarReporte = document.getElementById("guardarReporte");
        var formGuardarReporte = document.getElementById("formGuardarReporte");

        // Agrega un evento clic al botón
        guardarReporte.addEventListener("click", function () {
            // Deshabilita el botón para evitar múltiples clics
            guardarReporte.disabled = true;

            // Cambia el contenido del botón a un indicador de carga
            guardarReporte.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Cargando...';

            // Envía el formulario después de un breve retraso para permitir que el indicador de carga sea visible
            setTimeout(function () {
                formGuardarReporte.submit();
            }, 500);
        });
    });
</script>
<!-- DARK MODE-->
</body>

</html>
