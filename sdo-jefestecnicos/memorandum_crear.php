<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];


date_default_timezone_set('America/Santiago');

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Creacion de Memoramdum</title>

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

    <script type="text/javascript">
        $(document).ready(function () {
            recargarSubDepartamento();
            recargarNomDepartamento();
            buscarcargo();

            $('#destinatarioMemo').change(function () {
                buscarcargo();
            });

            $('#departamentoSolicitante').change(function () {
                recargarSubDepartamento();
            });

            $('#servicioOrden').change(function () {
                recargarNomDepartamento();
            });

            $('#selectnCliente').change(function() {
                agregarCliente();
                agregarMedidor();
            });

            function buscarcargo(){

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-motivos/mostrarcargo.php",
                    data: "nombre=" + $('#destinatarioMemo').val(),
                    success: function (r) {
                        $('#divNit').html(r);
                    }
                });

            };
            function recargarSubDepartamento() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-departamentos/mostrarSubDepartamentos.php",
                    data: "idDepartamento=" + $('#departamentoSolicitante').val(),
                    success: function (r) {
                        $('#visualizarSubDepartamento').html(r);
                    }
                });

            };

            function recargarNomDepartamento() {
                $.ajax({
                    type: "POST",
                    url: "sdo-datos-servicios/habilitarIngresoServicio.php",
                    data: "idServicio=" + $('#servicioOrden').val(),
                    success: function (r) {
                        $('#visualizarNomServicio').html(r);
                    }
                });
            };

            function agregarCliente() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-cliente/ingresarCliente.php",
                    data: "idCliente=" + $('#selectnCliente').val(),
                    success: function(r) {
                        $('#visualizarCliente').html(r);
                    }
                });

            };
            function agregarMedidor() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-cliente/ingresarMedidor.php",
                    data: "idCliente=" + $('#selectnCliente').val(),
                    success: function(r) {
                        $('#visualizarMedidor').html(r);
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
                $("#listarMC").show();
                $("#registrarMC").hide();
                $("#listarMC2").show();
                $("#registrarMC2").hide();
            });

            // Show div by removing inline display none style rule
            $(".show-listar").click(function () {
                $("#rowMenu2").hide();
                $("#rowMenu1").show();
                $("#listarMC").hide();
                $("#registrarMC").show();
                $("#listarMC2").hide();
                $("#registrarMC2").show();
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
                        <h4>Crear Memorandum</h4>

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

                <!-- Timelime example  -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-3 ">
                                        <h4 class="card-title">Crear Memorandum</h4>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-3 text-right">

                                        <a href="memorandum_listado.php" class="btn btn-danger btn-md"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    </div>
                                </div>

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

                                <form method="post" action="../sdo-funciones/memorandum_generar_especialidades.php" id="formGuardarMemo" enctype="multipart/form-data">

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="materiaMemo">Materia Memorandum:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                </div>
                                                <input type="text" style="text-transform:capitalize" class="form-control" id="materiaMemo" name="materiaMemo" placeholder="Materia Memorandum">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="fechaMemo">Fecha Memorandum:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                </div>
                                                <input type="date" class="form-control" id="fechaMemo" name="fechaMemo" placeholder="Fecha Memorandum">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="nomEmisorMemo">Emisor Memorandum:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                </div>
                                                <input type="text" readonly="readonly" style="text-transform:capitalize" class="form-control" id="nomEmisorMemo" name="nomEmisorMemo" value="<?php echo $nomUsuario; ?>" placeholder="Emisor Memorandum">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="cargoEmisorMemo">Cargo Emisor Memorandum:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" readonly="readonly" class="form-control" id="cargoEmisorMemo" name="cargoEmisorMemo" value="<?php echo $cargoUsuario.' de '.$nomDepartamento; ?>" placeholder="Cargo">

                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-row" id="elegirDestinatario">
                                        <div class="form-group col-md-6">
                                            <label for="destinatarioMemo">Nombre Destinatario Memorandum:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <select class="form-control" id="destinatarioMemo" name="destinatarioMemo">
                                                    <option value="Seleccione">Seleccione...</option>
                                                    <option value="paolaNunez">Paola Nuñez Cárdenas</option>
                                                    <?php
                                                    $sqlDes = "SELECT nomDesti From destinatario_memo_especialidades WHERE idDepartamento = $idDepartamento";
                                                    $resDes = mysqli_query($conexion,$sqlDes);
                                                    while($rowDes = $resDes->fetch_assoc()){
                                                        $nomDesti = $rowDes['nomDesti'];
                                                        ?>
                                                        <option data-nit="<?php echo $nomDesti?>" value="<?php echo $nomDesti?>"><?php echo $nomDesti?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                    <option value="otro">Otro</option>
                                                </select>
                                                <input type="hidden" id="nit" name="nit" value="">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6" id="cargoPaolaNunez" style="display: none">
                                            <label for="cargoDestinatarioMemo">Cargo Destinatario Memorandum:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <select class="form-control" id="cargoDestinatarioMemo" name="cargoDestinatarioMemo">
                                                    <option value="Seleccione">Seleccione...</option>
                                                    <option value="Subdirectora de Operaciones">Subdirectora de Operaciones</option>
                                                    <option value="Director (s)">Director (s)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6" id="divNit" style="display:none">

                                        </div>

                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6" id="otroNomDestinatario">
                                            <label for="nomOtroDestinatarioMemo">Nombre Destinatario Memorandum:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" style="text-transform:capitalize" class="form-control" id="nomOtroDestinatarioMemo" name="nomOtroDestinatarioMemo" placeholder="Nombre Destinatario Memorandum">

                                            </div>
                                        </div>
                                        <div class="form-group col-md-6" id="otroCargoDestinatario">
                                            <label for="cargoOtroDestinatarioMemo">Cargo Destinatario Memorandum:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" style="text-transform:capitalize" class="form-control" id="cargoOtroDestinatarioMemo" name="cargoOtroDestinatarioMemo" placeholder="Cargo Destinatario Memorandum">

                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-12">
                                            <label for="textoMemo">Texto Memorandum:</label>
                                            <p id="textoMemoHelp" class="form-text">Máximo 1900 caracteres.</p>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <textarea name="textoMemo" id="textoMemo" class="form-control" rows="10" style="resize: none" maxlength="1900" placeholder="Texto Memorandum"></textarea>
                                            </div>
                                            <script>
                                                document.addEventListener("DOMContentLoaded", function () {
                                                    var textarea = document.getElementById("textoMemo");
                                                    var helpText = document.getElementById("textoMemoHelp");

                                                    textarea.addEventListener("input", function () {
                                                        var caracteres = textarea.value.length;
                                                        helpText.textContent = "Máximo 1900 caracteres. Actualmente: " + caracteres + " caracteres.";
                                                        if (caracteres >= 1600) {
                                                            helpText.addClass('text-red')
                                                        } else {
                                                            helpText.addClass('text-mute')
                                                        }
                                                    });
                                                });
                                            </script>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-12">
                                            <input type="hidden" name="idDepartamentoMemo" id="idDepartamentoMemo" value="<?php echo $idDepartamento; ?>">
                                            <button type="submit" id="btn-guardarMemo" name="btn-guardarMemo" class="btn btn-block btn-success btn-guardarMemo"><i class="fas fa-save"></i>&nbsp;&nbsp;Guardar</button>
                                        </div>
                                    </div>

                                </form>

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
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
    document.addEventListener("DOMContentLoaded", function () {
        // Obtén el formulario y el botón utilizando sus identificadores
        var formGuardarMemo = document.getElementById("formGuardarMemo");
        var guardarMemo = document.getElementById("btn-guardarMemo");

        // Agrega un evento clic al botón
        guardarMemo.addEventListener("click", function () {
            // Deshabilita el botón para evitar múltiples clics
            guardarMemo.disabled = true;

            // Cambia el contenido del botón a un indicador de carga
            guardarMemo.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Cargando...';

            // Envía el formulario después de un breve retraso para permitir que el indicador de carga sea visible
            setTimeout(function () {
                formGuardarMemo.submit();
            }, 500);
        });
    });
</script>
<script>
    // Función para mostrar u ocultar los divs según la opción seleccionada
    function toggleDestinatario() {
        var destinatarioSelect = document.getElementById("destinatarioMemo");
        var destinatario = destinatarioSelect.value;
        var cargoPaolaNunez = document.getElementById("cargoPaolaNunez");
        var otroNomDestinatario = document.getElementById("otroNomDestinatario");
        var otroCargoDestinatario = document.getElementById("otroCargoDestinatario");
        var nit = destinatarioSelect.selectedOptions[0].getAttribute("data-nit");
        document.getElementById("nit").value = nit;

        if (nit !== "") {
            divNit.style.display = "block";
        } else {
            divNit.style.display = "none";
        }

        // Si se selecciona "paolaNunez", mostrar el div "cargoPaolaNunez" y ocultar "otroDestinatario"
        if (destinatario === "paolaNunez") {
            cargoPaolaNunez.style.display = "block";
            otroNomDestinatario.style.display = "none";
            otroCargoDestinatario.style.display = "none";
            divNit.style.display = "none";

        }
        // Si se selecciona "otro", mostrar el div "otroDestinatario" y ocultar "cargoPaolaNunez"
        else if (destinatario === "otro") {
            cargoPaolaNunez.style.display = "none";
            otroNomDestinatario.style.display = "block";
            otroCargoDestinatario.style.display = "block";
            divNit.style.display = "none";
        }
        // En caso contrario, ocultar ambos divs
        else{
            cargoPaolaNunez.style.display = "none";
            otroNomDestinatario.style.display = "none";
            otroCargoDestinatario.style.display = "none";
        }
    }

    // Asignar la función al evento onchange del select
    document.getElementById("destinatarioMemo").onchange = toggleDestinatario;

    // Llamar a la función al cargar la página para manejar el estado inicial del select
    toggleDestinatario();
</script>


</body>

</html>