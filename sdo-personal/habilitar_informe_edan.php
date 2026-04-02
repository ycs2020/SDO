<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$nomUsuario = $_SESSION['nomUsuario'];

date_default_timezone_set('America/Santiago');
$now = date('Y-m-d H:i:s');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Habilitar Informe EDAN</title>

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
    <link rel="stylesheet" href="css/dark-mode.css">
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
                        <h4>Habilitar Informe EDAN</h4>
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
                <form action="../sdo-funciones/guardar-informe-edac.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" id="nomUsuario" name="nomUsuario" value="<?php echo $nomUsuario?>">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Informaci&oacute;n Requerida:</h3>
                                </div>
                                <div class="card-body">
                                    <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <br>
                                    <br>
                                    <fieldset class="border p-3">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Fecha:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                    </div>
                                                    <input readonly type="datetime-local" class="form-control" id="fecha" name="fecha" value="<?php echo $now?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputNomTarjeta">Técnicos Encargados del Turno:<code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                    </div>
                                                    <select class="form-control listadoEncInstru" required id="nomEncargadoTurno" name="nomEncargadoTurno[]" multiple="multiple" onchange="limitarSeleccion(this)" >
                                                        <option value="Seleccione..." style="font-weight: bold" disabled>Seleccione...</option>
                                                        <?php
                                                        $sqlUsuarios = "SELECT nomPersonal AS nomEmpleado FROM personal p WHERE departamentoPersonal = $idDepartamento AND activoPersonal = 1 ORDER BY nomPersonal";
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
                                        <button class="btn btn-block bg-success" type="submit">Guardar</button>
                                    </fieldset>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <footer class="main-footer">
        <strong>Copyright &copy; 2022 - ∞. Derechos Reservados</strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
</div>
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
<script src="../plugins/select2/js/select2.full.min.js"></script>

<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Page specific script -->
<script>
    $(function() {
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
        });
    });
</script>
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
<script src="js/dark-mode.js"></script>
</body>
</html>
