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

if(isset($_POST['idLicitacion'])){
    $idLicitacion = $_POST['idLicitacion'];
    $sql = "SELECT * FROM licitaciones WHERE id = $idLicitacion";
    $res = mysqli_query($conexion, $sql);
    while($row = $res->fetch_assoc()){
        $numeroMemo = $row['numeroMemo'];
        $nombreProceso = $row['nombreProceso'];
        $cargo = $row['cargo'];
        $tipo = $row['tipo'];
        $presupuesto = $row['presupuesto'];
        $encargado = $row['encargado'];
        $estado = $row['estado'];
        $fechaIngreso = $row['fechaIngreso'];
        $unidad = $row['unidad'];
        $idLicitacionModif = $row['idLicitacion'];
        $fechaTermino = $row['fechaTermino'];

        $fechaInicio = $row['fechaInicio'];  // Asumiendo que $row['fechaInicio'] tiene el formato yyyy-mm-dd hh:mm:ss

        // Crear un objeto DateTime
        $date = new DateTime($fechaInicio);

        // Formatear la fecha al formato requerido para datetime-local
        $fechaFormateada = $date->format('Y-m-d\TH:i');

        $date2 = new DateTime($fechaTermino);

        $fechaFormateada2 = $date2->format('Y-m-d\TH:i');


    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Licitaciones</title>

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
                        <h4>Modificar Licitaciones</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
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
                                    <a href="licitaciones_admin.php" style="float: right;" class="btn bg-gradient-red float-right btn-md text-white"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
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
                            <div class="card-body">
                                <form action="../sdo-funciones/modificar_licitacion_jiji.php" method="POST" enctype="multipart/form-data">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTurno">N° de Memo: <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                </div>
                                                <input type="text" required class="form-control" id="numeroMemo" name="numeroMemo" value="<?php echo $numeroMemo?>" placeholder="Numero del Memo">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTurno">Nombre del Proceso: <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                </div>
                                                <input type="text" required class="form-control" id="nombreProceso" name="nombreProceso" value="<?php echo $nombreProceso?>" placeholder="Nombre del proceso">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTurno">Unidad: <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                </div>
                                                <select class="form-control" name="departamentos" id="departamentos">
                                                    <option value="" selected disabled>Seleccione....</option>
                                                    <?php
                                                    $sqlDepa = "SELECT * FROM departamentos WHERE idDepartamento != 13 AND idDepartamento != 11 ORDER BY nomDepartamento";
                                                    $resDepa = mysqli_query($conexion, $sqlDepa);

                                                    // Suponemos que $unidad contiene el valor del departamento seleccionado previamente desde la base de datos
                                                    while($rowDepa = $resDepa->fetch_assoc()){
                                                        $nomDepartamento = $rowDepa['nomDepartamento'];

                                                        // Si el nombre del departamento es "Instrumentación", lo cambiamos por "Control Centralizado"
                                                        if($nomDepartamento == "Instrumentación"){
                                                            $nomDepartamento = "Control Centralizado";
                                                        }

                                                        // Verificar si el departamento debe estar seleccionado
                                                        $selected = ($nomDepartamento == $unidad) ? 'selected' : '';
                                                        ?>
                                                        <option value="<?php echo $nomDepartamento; ?>" <?php echo $selected; ?>>
                                                            <?php echo $nomDepartamento; ?>
                                                        </option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTurno">Encargado: <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                </div>
                                                <input type="text" required class="form-control" id="encargado" name="encargado" value="<?php echo $encargado?>" placeholder="Encargado">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTurno">Cargo: <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                </div>
                                                <input type="text" required class="form-control" id="cargo" name="cargo" value="<?php echo $cargo?>" placeholder="Cargo">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTurno">Tipo (Trato Directo / Licitacion): <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                </div>
                                                <select name="tipo" required class="form-control">
                                                    <?php
                                                    if($tipo == 'Licitacion'){
                                                        ?>
                                                        <option value=""  disabled>Seleccione....</option>
                                                        <option value="Licitacion" selected>Licitacion</option>
                                                        <option value="Trato Directo">Trato Directo</option>
                                                        <?php
                                                    }else if($tipo == 'Trato Directo'){
                                                        ?>
                                                        <option value=""  disabled>Seleccione....</option>
                                                        <option value="Licitacion" >Licitacion</option>
                                                        <option value="Trato Directo" selected>Trato Directo</option>
                                                        <?php
                                                    }else{
                                                        ?>
                                                        <option value=""  disabled selected>Seleccione....</option>
                                                        <option value="Licitacion" >Licitacion</option>
                                                        <option value="Trato Directo">Trato Directo</option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">

                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTurno">Presupuesto:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="presupuesto" name="presupuesto" value="<?php echo $presupuesto?>" placeholder="Presupuesto">
                                            </div>

                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTurno">Estado: <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                </div>
                                                <input type="text" required class="form-control" id="estado" name="estado" value="<?php echo $estado?>" placeholder="Estado">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTurno">Fecha Inicio:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                </div>
                                                <input type="datetime-local"  class="form-control" id="fechaInicio" name="fechaInicio" value="<?php echo $fechaFormateada; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTurno">Fecha Termino:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                </div>
                                                <input type="datetime-local"  class="form-control" id="fechaTermino" name="fechaTermino" value="<?php echo $fechaFormateada2; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTurno">ID Licitacion:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                </div>
                                                <input type="text"  class="form-control" id="idLicitacionModi" name="idLicitacionModi" value="<?php echo $idLicitacionModif; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <input type="hidden" name="idLicitacion" value="<?php echo $idLicitacion?>">
                                    <button type="submit" class="btn btn-block bg-success">Modificar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
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
</body>
</html>