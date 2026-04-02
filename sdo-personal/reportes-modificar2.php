<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


if (isset($_POST['idReporte'])) {

    $idReporte = trim($_POST['idReporte']);

    if (!empty($idReporte)) {

        $datosReporte = array();
        $datosReporte = getReporte($idReporte);

        $idReporte2 = $datosReporte['idReporte'];
        $nomReporte = $datosReporte['nomReporte'];
        $turnoReporte = $datosReporte['turnoReporte'];

        $fechaReporte = $datosReporte['fechaReporte'];
        $fechaReporte2 = date("d-m-Y", strtotime($fechaReporte));

        $folioReporte = $datosReporte['folioReporte'];
        $nomEncargadoTurno = $datosReporte['nomEncargadoTurno'];
        $nomFuncionariosTurno = $datosReporte['nomFuncionariosTurno'];
        $nomEncargadoTurnoAnterior = $datosReporte['nomEncargadoTurnoAnterior'];
        $tareasControlCentralizado = $datosReporte['tareasControlCentralizado'];
        $tareasInstalaciones = $datosReporte['tareasInstalaciones'];
        $tareasEquiposIndustriales = $datosReporte['tareasEquiposIndustriales'];
        $tareasIncendiosCombustibles = $datosReporte['tareasIncendiosCombustibles'];
        $tareasDeteccionElectrica = $datosReporte['tareasDeteccionElectrica'];
        $tareasInfraestructura = $datosReporte['tareasInfraestructura'];
        $pendientesCoordinacion = $datosReporte['pendientesCoordinacion'];
    } else {
        header('Location: ../sdo-personal/reportes-listado.php?errorModificarReporte=true');
    }
} else {
    header('Location: ../sdo-personal/reportes-listado.php?errorModificarReporte=true');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reporte de Turnos</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>


</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/user-sidebar.php';

    $_SESSION['idReporte'] = $idReporte;
    ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Modificar Reporte</h1>
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
                <form action="reportes-modificarTexto.php" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-12">

                            <!-- general form elements -->
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Modificar Reporte</h3>
                                </div>
                                <!-- /.card-header -->
                                <?php


                                if (isset($_GET['errorModificar'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Modificar Reporte. Reintente.</h5></div>';
                                }


                                ?>
                                <div class="card-body">


                                    <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
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
                                                <input type="datetime" readonly="readonly" id="fechaActualReporte2" name="fechaActualReporte2" class="form-control" value="<?php echo $fechaReporte2; ?>"/>
                                                <input type="hidden" id="fechaActualReporte" name="fechaActualReporte" class="form-control" value="<?php echo $fechaReporte; ?>"/>

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
                                                <select class="form-control listadoEncInstru" required id="nomEncargadoTurno" name="nomEncargadoTurno[]" multiple="multiple" onchange="limitarSeleccion(this)">
                                                    <option value="Seleccione..." style="font-weight: bold" disabled>Seleccione...</option>
                                                    <?php
                                                    $sqlUsuarios = "SELECT nomPersonal AS nomEmpleado FROM personal p WHERE departamentoPersonal = $idDepartamento ORDER BY nomPersonal";
                                                    $resUsuarios = mysqli_query($conexion, $sqlUsuarios);


                                                    if ($resUsuarios->num_rows > 0) {
                                                        while ($rowUsuarios = $resUsuarios->fetch_assoc()) {
                                                            $nomEmpleado = trim($rowUsuarios['nomEmpleado']);

                                                            $nombres = explode(" - ", $nomEncargadoTurno);

                                                            $encontrado = false;
                                                            foreach ($nombres as $nombre) {
                                                                if (strpos($nombre, $nomEmpleado) !== false) {
                                                                    $encontrado = true;
                                                                    break;
                                                                }
                                                            }

                                                            if ($encontrado) {
                                                                ?>
                                                                <option value="<?php echo $nomEmpleado ?>" selected><?php echo $nomEmpleado ?></option>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <option value="<?php echo $nomEmpleado ?>"><?php echo $nomEmpleado ?></option>
                                                                <?php
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputNomEncargadoTurno">Funcionarios de Especialidades de Turno: <code>*</code></label>
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

                                                                    $nombres = explode(" - ", $nomFuncionariosTurno);

                                                                    $encontrado = false;
                                                                    foreach ($nombres as $nombre) {
                                                                        if (strpos($nombre, $nomEmpleado) !== false) {
                                                                            $encontrado = true;
                                                                            break;
                                                                        }
                                                                    }

                                                                    if ($encontrado) {
                                                                        ?>
                                                                        <option value="<?php echo $nomEmpleado ?>" selected><?php echo $nomEmpleado ?></option>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <option value="<?php echo $nomEmpleado ?>"><?php echo $nomEmpleado ?></option>
                                                                        <?php
                                                                    }

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
                                                    $sqlUsuarios = "SELECT nomPersonal AS nomEmpleado FROM personal p WHERE departamentoPersonal = $idDepartamento ORDER BY nomPersonal";
                                                    $resUsuarios = mysqli_query($conexion, $sqlUsuarios);

                                                    if ($resUsuarios->num_rows > 0) {
                                                        while ($rowUsuarios = $resUsuarios->fetch_assoc()) {
                                                            $nomEmpleado = trim($rowUsuarios['nomEmpleado']);

                                                            $nombres = explode(" - ", $nomEncargadoTurnoAnterior);

                                                            $encontrado = false;
                                                            foreach ($nombres as $nombre) {
                                                                if (strpos($nombre, $nomEmpleado) !== false) {
                                                                    $encontrado = true;
                                                                    break;
                                                                }
                                                            }

                                                            if ($encontrado) {
                                                                ?>
                                                                <option value="<?php echo $nomEmpleado ?>" selected><?php echo $nomEmpleado ?></option>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <option value="<?php echo $nomEmpleado ?>"><?php echo $nomEmpleado ?></option>
                                                                <?php
                                                            }
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
                                                <select id="listaTurno" class="form-control" disabled name="listaTurno">
                                                    <?php

                                                    if ($turnoReporte == 'Dia') {
                                                        ?>
                                                        <option value="Dia" selected="selected">
                                                            Dia (08:00 - 20:00)
                                                        </option>
                                                        <option value="Noche">
                                                            Noche (20:00 - 08:00)
                                                        </option>
                                                        <?php
                                                    } else if ($turnoReporte == 'Noche') {
                                                        ?>
                                                        <option value="Dia">
                                                            Dia (08:00 - 20:00)
                                                        </option>
                                                        <option value="Noche" selected="selected">
                                                            Noche (20:00 - 08:00)
                                                        </option>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <option value="Dia" selected="selected">
                                                            Dia (08:00 - 20:00)
                                                        </option>
                                                        <option value="Noche">
                                                            Noche (20:00 - 08:00)
                                                        </option>
                                                        <?php
                                                    }


                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputtareasControlCentralizado">Eventos Relevantes en Control Centralizado:</label>
                                            <textarea id="tareasControlCentralizado" style="resize:none;" name="tareasControlCentralizado" class="form-control" rows="8" placeholder="Eventos Relevantes en Control Centralizado"><?php echo $tareasControlCentralizado; ?></textarea>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputNomEncargadoTurno">Eventos Relevantes en Inst. T&eacute;rmicas, Climatizaci&oacute;n y Refrigeraci&oacute;n:</label>
                                            <textarea id="tareasInstalaciones" style="resize:none;" name="tareasInstalaciones" class="form-control" rows="8" placeholder="Eventos Relevantes en Inst. T&eacute;rmicas, Climatizaci&oacute;n y Refrigeraci&oacute;n"><?php echo $tareasInstalaciones; ?></textarea>

                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTareasControlCent">Eventos Relevantes en Equipos Industriales:</label>
                                            <textarea id="tareasEquiposIndustriales" style="resize:none;" name="tareasEquiposIndustriales" class="form-control" rows="8" placeholder="Eventos Relevantes en Equipos Industriales"><?php echo $tareasEquiposIndustriales; ?></textarea>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputNomEncargadoTurno">Eventos Relevantes en Detecci&oacute;n de Incendios y Combustibles:</label>
                                            <textarea id="tareasIncendiosCombustibles" style="resize:none;" name="tareasIncendiosCombustibles" class="form-control" rows="8" placeholder="Eventos Relevantes en Detecci&oacute;n de Incendios y Combustibles"><?php echo $tareasIncendiosCombustibles; ?></textarea>

                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTareasControlCent">Eventos Relevantes en Detecci&oacute;n de Instalaci&oacute;n El&eacute;ctrica:</label>
                                            <textarea id="tareasDeteccionElectrica" style="resize:none;" name="tareasDeteccionElectrica" class="form-control" rows="8" placeholder="Eventos Relevantes en Detecci&oacute;n de Instalaci&oacute;n El&eacute;ctrica"><?php echo $tareasDeteccionElectrica; ?></textarea>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputNomEncargadoTurno">Eventos Relevantes en Infraestructura:</label>
                                            <textarea id="tareasInfraestructura" style="resize:none;" name="tareasInfraestructura" class="form-control" rows="8" placeholder="Eventos Relevantes en Infraestructura"><?php echo $tareasInfraestructura; ?></textarea>

                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputNomEncargadoTurno">Pendientes de Coordinaci&oacute;n y/o Ejecuci&oacute;n:</label>
                                            <textarea id="pendientesCoordinacion" style="resize:none;" name="pendientesCoordinacion" class="form-control" rows="8" placeholder="Pendientes de Coordinaci&oacute;n y/o Ejecuci&oacute;n"><?php echo $pendientesCoordinacion; ?></textarea>

                                        </div>
                                    </div>

                                    <hr>

                                    <br>
                                    <br>

                                    <div class="form-group">
                                        <input type="hidden" name="idReporte" id="idReporte" value="<?php echo $idReporte; ?>">
                                        <input type="submit" class="btn btn-block btn-primary" value="Guardar"/>

                                    </div>
                                    <div class="form-group">
                                        <a href="reportes-listado.php" class="btn btn-block btn-warning"> Volver </a>
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
        // Inicializa Select2
        const select2Options = {
            theme: 'bootstrap',
            allowClear: true,
            placeholder: function() {
                $(this).data('placeholder');
            }

        };

        $('.listadoEncInstru').select2(select2Options);
        $('.listadoEspecialidades').select2(select2Options);
        $('.listadoEncInstruAnterior').select2(select2Options);

        // Maneja el modo oscuro
        function handleDarkMode(isDarkMode) {
            const color = isDarkMode ? '#000' : '#fff';
            $('.select2-container--bootstrap4 .select2-selection').css('color', color);
        }

        // Detectar cambio de tema (esto depende de cómo implementes el modo oscuro)
        const isDarkMode = $('body').hasClass('dark-mode'); // ajusta esto según tu implementación
        handleDarkMode(isDarkMode);
    });
</script>
<script>
    $(function () {
        bsCustomFileInput.init();
    });
</script>
<!-- DARK MODE-->
<script src="js/dark-mode.js"></script>

</body>

</html>