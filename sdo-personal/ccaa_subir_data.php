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
    <title>Listado de Sistema de Control de Accesos</title>

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
    <link rel="stylesheet" href="../dist/css/cuadro-cantidad.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/user-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
        </section>

        <?php
        if(isset($_GET['mensajeVar'])){

            if(!empty($_SESSION['mensajeAlerta']) && !empty($_SESSION['iconAlerta'])){
                $mensajeAlerta = $_SESSION['mensajeAlerta'];
                $iconAlerta = $_SESSION['iconAlerta'];

                if($iconAlerta == 'Error' || $iconAlerta == 'Correcto'){
                    if($iconAlerta == 'Error'){
                        $iconoFinal = 'danger';
                    }else if($iconAlerta == 'Correcto'){
                        $iconoFinal = 'success';
                    }


                    ?>
                    <script>
                        Swal.fire({
                            icon: '<?php echo $iconoFinal; ?>', // Asegúrate de que esto sea una cadena
                            title: '<?php echo $mensajeAlerta; ?>',
                            text: '',
                        });
                    </script>

                    <?php
                }
            }
        }
        ?>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">

                    <!-- left column -->
                    <div class="col-xl-10 col-lg-9 col-md-8 col-12">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <a style="pointer-events: none;" class="btn font-weight-bold">Sistema de Control de Acceso</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                        <div class="card">
                            <div class="card-header bg-danger">
                                <a href="index.php" style="float: right;" class="btn btn-block btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <!-- Custom Tabs -->
                        <div class="card">
                            <div class="card-body" style="text-align:center;">

                                <div class="row text-center" style="display: flex; justify-content:center;">
                                    <div class="col-lg-2 col-12">

                                        <form style="margin: 0; text-align: center;" class="" action="../sdo-funciones/db_actualizarCA_Tablas.php" method="post" enctype="multipart/form-data">

                                            <button type="button" data-toggle="modal" data-target="#modalSubirExcel" id="subirExcel" class="btn bg-gradient-success btn-block"><i class="fa fa-upload"></i>&nbsp;&nbsp;Subir Datos</button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="modalSubirExcel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-success">
                                                            <h5 class="modal-title" id="exampleModalLabel">Subir Archivo DB</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-12 ml-auto">
                                                                    <div style="text-align:left;" class="form-group">
                                                                        <label for="categoria">Seleccione la Categoría...</label>
                                                                        <select name="categoria" id="categoria" class="form-control" required>
                                                                            <option value="">Seleccione...</option>
                                                                            <option value="Funcionarios">Funcionarios -- ACSManagerDbFile.db</option>
                                                                            <option value="Estacionamiento">Estacionamiento -- VideoTalk.db</option>
                                                                        </select>
                                                                    </div>
                                                                    <br>
                                                                    <div style="text-align:left;" class="form-group">
                                                                        <label for="archivo_db">Seleccione el Archivo DB...</label>
                                                                        <div class="custom-file">
                                                                            <input type="file" class="custom-file-input" id="archivo_db" name="archivo_db" accept=".db" required>
                                                                            <label class="custom-file-label">Seleccionar Archivo...</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary" id="btnSubir">
                                                                <span id="btnText"><i class="fas fa-upload"></i>&nbsp;&nbsp;Agregar</span>
                                                                <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                                            </button>
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <script>
                                            document.getElementById('archivo_db').addEventListener('change', function () {
                                                var fileName = this.value.split('\\').pop();
                                                document.querySelector('.custom-file-label').textContent = fileName;
                                            });

                                            document.querySelector("form").addEventListener("submit", function (event) {
                                                var categoria = document.getElementById('categoria').value;
                                                var archivoInput = document.getElementById('archivo_db');
                                                var archivoSeleccionado = archivoInput.value.split('\\').pop();
                                                var errorMensaje = "";

                                                if (categoria === "Funcionarios" && archivoSeleccionado !== "ACSManagerDbFile.db") {
                                                    errorMensaje = "Debe seleccionar el archivo 'ACSManagerDbFile.db' para Funcionarios.";
                                                } else if (categoria === "Estacionamiento" && archivoSeleccionado !== "VideoTalk.db") {
                                                    errorMensaje = "Debe seleccionar el archivo 'VideoTalk.db' para Estacionamiento.";
                                                } else if (categoria === "Eventos" && archivoSeleccionado !== "ACSEventInfo.db") {
                                                    errorMensaje = "Debe seleccionar el archivo 'ACSEventInfo.db' para Estacionamiento.";
                                                }

                                                if (errorMensaje) {

                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'Error al Subir Archivo',
                                                        text: errorMensaje
                                                    });
                                                    event.preventDefault();
                                                    return;
                                                }

                                                // Deshabilitar botón y mostrar spinner
                                                document.getElementById("btnSubir").disabled = true;
                                                document.getElementById("btnText").classList.add("d-none");
                                                document.getElementById("spinner").classList.remove("d-none");
                                            });
                                        </script>
                                    </div>
<!--                                    <div class="col-lg-2 col-12">-->
<!---->
<!--                                        <form method="post" action="../sdo-pdf/SDO-Estadistica-Actas.php">-->
<!---->
<!--                                            <button type="submit" style="float: right;" id="inputMozi" class="btn btn-primary btn-block"><i class="fa fa-download"></i>&nbsp;&nbsp;Descargar Estadistica</button>-->
<!---->
<!--                                        </form>-->
<!--                                    </div>-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
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
</body>

</html>

