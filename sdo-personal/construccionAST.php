<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


date_default_timezone_set('America/Santiago');

$fecha = date('d-m-Y H:i');


$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = trim($_SESSION['nomUsuario']);
$userUsuario = $_SESSION['userUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);
$idDepartamento = $_SESSION['idDepartamento'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Construcción de AST</title>

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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
    <div class="wrapper">
        <?php
        include '../sdo-templates/user-sidebar.php';
        ?>
        <div class="content-wrapper">
            <section class="content-header">

            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header" style="background-color: #0a30f2; color: white">
                                    <h4>Construcción de AST</h4>
                                </div>
                                <div class="card-body">
                                    <div class="col-lg-3 col-md-6 col-12 float-right" id="volverAtras2" name="volverAtras2">
                                        <!-- small box -->
                                        <a href="listadosAST.php" style="float: right;" class="btn bg-gradient-red float-right btn-md text-white"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    </div>
                                </div>
                                <hr>
                                <form action="../sdo-funciones/generar-ast.php" method="POST">
                                    <input type="hidden" id="idDepartamento" name="idDepartamento" value="<?php echo $idDepartamento; ?>">
                                <fieldset class="border p-3">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTurno">Personas Asignadas: <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <select class="form-control listadoEncInstru" required id="nomEncargadoTurno" name="nomEncargadoTurno[]" multiple="multiple" onchange="limitarSeleccion(this)">
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
                                            <label for="exampleInputTurno">Turno correspondiente: <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                </div>
                                                <select name="turno" id="turno" class="form-control" required>
                                                    <option value="" selected disabled>Seleccione</option>
                                                    <option value="Turno Dia">Turno Dia</option>
                                                    <option value="Turno Noche">Turno Noche</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTurno">Tipo de Tarea: <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                </div>
                                                <?php
                                                    if($idDepartamento == 1){
                                                        $valor = "MANTENCIÓN PREVENTIVA E INSPECCIÓN DE LOS SISTEMAS";
                                                    }else{
                                                        $valor = "";
                                                    }
                                                ?>
                                                <input type="text" value="<?php echo $valor?>" class="form-control" id="tipoTarea" name="tipoTarea" placeholder="Ingrese el tipo de tarea" required>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <br>
                                    <button name="btnAgregarFila" style="float: right" type="button" class="btn btn-sm bg-primary"><i class="fas fa-plus"></i> Agregar una nueva fila</button>
                                    <br>
                                    <br>
                                    <table id="creacionAST" class="table table-bordered">
                                        <thead class="text-center bg-secondary">
                                            <tr>
                                                <th>Etapas del Trabajo</th>
                                                <th>Riesgos</th>
                                                <th>Medidas de Control</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <tr>
                                                <td>
                                                    <textarea rows="10" style="width: 100%" name="etapasTrabajo1"></textarea>
                                                </td>
                                                <td><textarea rows="10" style="width: 100%" name="riesgos1"></textarea></td>
                                                <td><textarea rows="10" style="width: 100%" name="medidasControl1"></textarea></td>
                                                <td>No Corresponde</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </fieldset>
                                <br>
                                    <input type="hidden" name="cantidadFilas" id="cantidadFilas" value="">
                                <button style="width: 100%" class="btn btn-success" type="submit">Guardar</button>
                                </form>
                            </div>
                        </div>
                    </div>
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
</body>
<script src="../plugins/jquery/jquery.min.js"></script>
<script src="js/dark-mode.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/select2/js/select2.full.min.js"></script>

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
    // Función que renumera todas las filas
    function renumerarFilas() {
        const filas = document.querySelectorAll("#creacionAST tbody tr");

        // **Actualizar cantidad de filas**
        document.getElementById("cantidadFilas").value = filas.length;

        filas.forEach((fila, index) => {
            let numero = index + 1;

            let textareas = fila.querySelectorAll("textarea");

            if (textareas.length === 3) {
                textareas[0].name = "etapasTrabajo" + numero;
                textareas[1].name = "riesgos" + numero;
                textareas[2].name = "medidasControl" + numero;
            }

            // Primera fila → No Corresponde
            let celdaAccion = fila.querySelector("td:last-child");
            if (numero === 1) {
                celdaAccion.innerHTML = "No Corresponde";
            } else {
                celdaAccion.innerHTML = `
                    <button type="button" class="btn btn-danger btn-sm btnEliminarFila">
                        Eliminar
                    </button>
                `;
            }
        });
    }

    // Agregar fila
    document.querySelector("button[name='btnAgregarFila']").addEventListener("click", () => {
        let tablaBody = document.querySelector("#creacionAST tbody");

        let fila = document.createElement("tr");
        fila.innerHTML = `
            <td><textarea rows="10" style="width: 100%"></textarea></td>
            <td><textarea rows="10" style="width: 100%"></textarea></td>
            <td><textarea rows="10" style="width: 100%"></textarea></td>
            <td></td>
        `;

        tablaBody.appendChild(fila);
        renumerarFilas();
    });

    // Eliminar fila (delegado)
    document.querySelector("#creacionAST tbody").addEventListener("click", function (e) {
        if (e.target.classList.contains("btnEliminarFila")) {
            e.target.closest("tr").remove();
            renumerarFilas();
        }
    });

    // Renumerar al cargar la página
    renumerarFilas();
</script>
</html>
