<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
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
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">


    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
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
        include '../sdo-templates/jefes-sidebar.php';
        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- left column -->
                        <div class="col-xl-10 col-lg-9 col-md-8 col-12">
                            <div class="card">
                                <div class="card-header bg-primary">
                                    <a style="pointer-events: none; font-size: 13pt; text-transform: uppercase" class="btn font-weight-bold">Listado de Reportes</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                            <div class="card">
                                <div class="card-header bg-danger">
                                    <a href="solicitudes_detalle.php" class="btn btn-block btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="fas fa-clipboard-list mr-2"></i>Datos del Turno</h5>
                                </div>
                                <div class="card-body">

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="fechaActualReporte">📅 Fecha Actual:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                </div>
                                                <input type="text" readonly class="form-control" id="fechaActualReporte" name="fechaActualReporte" value="<?php echo date('d-m-Y'); ?>">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="listaTurno">🕒 Turno realizado:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                </div>
                                                <select id="listaTurno" name="listaTurno" class="form-control" required>
                                                    <option value="">Seleccione...</option>
                                                    <option value="Dia">Día (08:00 - 20:00)</option>
                                                    <option value="Noche">Noche (20:00 - 08:00)</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="nomEncargadoTurno">👨 Funcionarios Turno - Control Centralizado: <code>*</code></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user-check"></i></span>
                                                </div>
                                                <select class="form-control listadoEncInstru" required id="nomEncargadoTurno" name="nomEncargadoTurno[]" multiple="multiple" onchange="limitarSeleccion(this)" >
                                                    <option value="Seleccione..." style="font-weight: bold" disabled>Seleccione...</option>
                                                    <?php
                                                    // Consulta SQL ajustada
                                                    $sqlUsuarios = "
                                                        SELECT nomEmpleado FROM (
                                                            SELECT nomPersonal AS nomEmpleado 
                                                            FROM personal 
                                                            WHERE departamentoPersonal IN (1, 13)
                                                            AND activoPersonal = 1
                                                            
                                                            UNION
                                                            
                                                            SELECT nomJefe AS nomEmpleado
                                                            FROM jefes_tecnicos
                                                            WHERE idDepartamento IN (1, 13)
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
                                            <label for="nomEncargadoTurnoAnterior">👨 Funcionarios Turno Anterior - Control Centralizado: <code>*</code></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user-clock"></i></span>
                                                </div>
                                                <select class="form-control listadoEncInstruAnterior" required id="nomEncargadoTurnoAnterior" name="nomEncargadoTurnoAnterior[]" multiple="multiple" onchange="limitarSeleccion(this)" >
                                                    <option value="Seleccione..." style="font-weight: bold" disabled >Seleccione...</option>
                                                    <?php
                                                    // Consulta SQL ajustada
                                                    $sqlUsuarios = "
                                                        SELECT nomEmpleado FROM (
                                                            SELECT nomPersonal AS nomEmpleado 
                                                            FROM personal 
                                                            WHERE departamentoPersonal IN (1, 13)
                                                            AND activoPersonal = 1
                                                            
                                                            UNION
                                                            
                                                            SELECT nomJefe AS nomEmpleado
                                                            FROM jefes_tecnicos
                                                            WHERE idDepartamento IN (1, 13)
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
                                    </div>

                                    <div class="form-group">
                                        <label for="nomFuncionariosTurno">👥 Funcionarios Especialidades en Turno <code>*</code></label>
                                        <div class="input-group">
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
                            </div>
                        </div>
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
        $(function() {
            bsCustomFileInput.init();
        });
    </script>
    <script src="js/dark-mode.js"></script>
    <script>

        $(document).ready(function () {

            // Mapeo de selectores a placeholders
            const select2Config = {
                '.listadoEncInstru': {
                    placeholder: 'Funcionarios Turno - Control Centralizado',
                    multiple: true
                },
                '.listadoEspecialidades': {
                    placeholder: 'Funcionarios Especialidades en Turno',
                    multiple: true
                },
                '.listadoEncInstruAnterior': {
                    placeholder: 'Funcionarios Turno Anterior - Control Centralizado',
                    multiple: true
                }
            };

// Inicialización dinámica
            Object.entries(select2Config).forEach(([selector, config]) => {
                const $element = $(selector);

                $element.select2({
                    placeholder: config.placeholder,
                    theme: 'bootstrap4',
                    allowClear: true,
                });

                // Solo limpiar input si el select es múltiple
                if ($element.prop('multiple') || config.multiple) {
                    $element.on('select2:select', function () {
                        setTimeout(() => {
                            const container = $(this).data('select2');
                            if (container?.dropdown?.$search) {
                                container.dropdown.$search.val('');
                            }
                        }, 10);
                    });
                }
            });
        });
    </script>
</body>

</html>