<?php

include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
} else {
    session_destroy();
    session_start();
}
//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

$rutaDireccion = "";

if ($rolUsuario2 == "personal") {
    $tipoUsuario = 'personal';
    $rutaDireccion = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $tipoUsuario = 'administrativo';
    $rutaDireccion = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $tipoUsuario = 'bodega pañol';
    $rutaDireccion = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $tipoUsuario = 'jefe tecnico';
    $rutaDireccion = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $tipoUsuario = 'administrador';
    $rutaDireccion = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $tipoUsuario = 'directiva';
    $rutaDireccion = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}


$nomUsuario = trim($_SESSION['nomUsuario']);
$idDepartamento = $_SESSION['idDepartamento'];
$nomDepartamento = getNomDepartamento($idDepartamento);
$idSubDepartamento = $_SESSION['idSubDepartamento'];
$nowAnio1 = date('Y');

date_default_timezone_set('America/Santiago');

$fechaSistema = date('d-m-Y h:i a');

if(isset($_POST['idProtocolo'])){

    $idProtocolo = $_POST['idProtocolo'];

    if($idProtocolo > 0){

        $datosProtocolo = array();
        $datosProtocolo = getProtocoloRefrigeradores($idProtocolo);

        $idProtocolo2 = $datosProtocolo['idProtocolo'];
        $fechaProtocolo = $datosProtocolo['fechaProtocolo'];
        $ejecutanteProtocolo = $datosProtocolo['ejecutanteProtocolo'];
        $folioProtocolo = $datosProtocolo['folioProtocolo'];
        $idEquipo = $datosProtocolo['idEquipo'];

        $estadoEquipo = $datosProtocolo['estadoEquipo'];

        $entornoEquipo = $datosProtocolo['entornoEquipo'];

        $tempEquipo = intval($datosProtocolo['tempEquipo']);

        $sonidoEquipo = $datosProtocolo['sonidoEquipo'];

        $luzEquipo = $datosProtocolo['luzEquipo'];

        $controlEquipo = $datosProtocolo['controlEquipo'];

        $observacionApoyoClinico = $datosProtocolo['observacionApoyoClinico'];
        $observacionControl = $datosProtocolo['observacionControl'];


    }else{
        header("Location: ../$rutaDireccion/protocolo_refrigeradores.php?errorCargar=true");
    }

}else{
    header("Location: ../$rutaDireccion/protocolo_refrigeradores.php?errorCargar=true");
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Protocolo de Refrigeradores</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <!-- summernote -->
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>

    <style>
        /* El botón activo tendrá fondo coloreado */
        .btn-group-toggle .btn.active {
            background-color: var(--bs-success);
            color: white;
            border-color: var(--bs-success);
        }

        /* El otro botón será transparente */
        .btn-group-toggle .btn {
            background-color: transparent;
            color: inherit;
            border-color: transparent;
        }

        /* Ajuste del botón no activo con hover */
        .btn-group-toggle .btn:hover {
            background-color: rgba(0, 0, 0, 0.1);
        }

    </style>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/jefes-sidebar.php';
    ?>
    <!-- /.navbar -->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            <br>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header text-center">
                            <h5>PROTOCOLO DE REVISIÓN DE REFRIGERADORES</h5>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card">

                        <div class="card-body">
                            <div class="tab-content">

                                <div id="tabApoyoClinico">
                                    <h6 class="text-center">MODIFICACIÓN DE PROTOCOLO DE REVISIÓN</h6>
                                    <br>

                                    <div class="col-12">
                                        <!-- Profile Image -->
                                        <div class="card card-primary card-outline">
                                            <div class="card-body box-profile">

                                                <form action="../sdo-funciones/protocolo_refrigeradores_modificar.php" method="post" enctype="multipart/form-data" >


                                                    <div class="form-row">
                                                        <div class="form-group col-md-6">
                                                            <label for="exampleInputNomTarjeta">NOMBRE FUNCIONARIO:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                                </div>
                                                                <input type="text" required style="text-transform:uppercase" readonly="readonly" class="form-control" id="nomFuncionario" name="nomFuncionario" placeholder="Nombre de Usuario" value="<?php echo $ejecutanteProtocolo; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="exampleInputNomTarjeta">EQUIPO:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                                </div>
                                                                <select class="form-control  select2equipo" name="select2equipo">
                                                                    <option value="Seleccione...">Seleccione...</option>
                                                                    <?php
                                                                    $sqlEquipos = "SELECT * FROM equipamiento_hbqp WHERE idDepaPertenece = 10 ORDER BY nombreEquipo ASC";
                                                                    $resEquipoSistema = mysqli_query($conexion, $sqlEquipos);
                                                                    if ($resEquipoSistema->num_rows > 0) {
                                                                        while ($rowEquipoSistema = $resEquipoSistema->fetch_assoc()) {

                                                                            $nombreEquipo = $rowEquipoSistema['nombreEquipo'];
                                                                            $numeroInventario = $rowEquipoSistema['numeroInventario'];

                                                                            if (!empty($numeroInventario)) {
                                                                                $nombreConInventario = $nombreEquipo . ' ( N° Inventario: ' . $numeroInventario . ' )';
                                                                            } else {
                                                                                $nombreConInventario = $nombreEquipo;
                                                                            }

                                                                            $nombreConInventario = mb_convert_case($nombreConInventario, MB_CASE_UPPER, 'UTF-8');


                                                                            if($rowEquipoSistema['id'] == $idEquipo){
                                                                                echo '<option value="' . $rowEquipoSistema['id'] . '" selected>' . $nombreConInventario . '</option>';

                                                                            }else{
                                                                                echo '<option value="' . $rowEquipoSistema['id'] . '">' . $nombreConInventario . '</option>';

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
                                                            <label for="exampleInputNomTarjeta">ESTADO GENERAL DE EQUIPO:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="btn-group btn-group-toggle form-control p-0" data-toggle="buttons">
                                                                    <label class="btn btn-outline-success <?php echo $estadoEquipo == 1 ? 'active' : ''; ?>">
                                                                        <input type="radio" name="estadoEquipo" id="operativo" value="Operativo" <?php echo $estadoEquipo == 1 ? 'checked' : ''; ?>> Operativo
                                                                    </label>
                                                                    <label class="btn btn-outline-danger <?php echo $estadoEquipo == 0 ? 'active' : ''; ?>">
                                                                        <input type="radio" name="estadoEquipo" id="noOperativo" value="No Operativo" <?php echo $estadoEquipo == 0 ? 'checked' : ''; ?>> No Operativo
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-md-6">
                                                            <label for="exampleInputNomTarjeta">ENTORNO DE EQUIPO:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="btn-group btn-group-toggle form-control p-0" data-toggle="buttons">

                                                                    <label class="btn btn-outline-success <?php echo $entornoEquipo == 1 ? 'active' : ''; ?>">
                                                                        <input type="radio" name="entornoEquipo" id="apto" value="Apto" <?php echo $entornoEquipo == 1 ? 'checked' : ''; ?>> Apto
                                                                    </label>
                                                                    <label class="btn btn-outline-danger <?php echo $entornoEquipo == 0 ? 'active' : ''; ?>">
                                                                        <input type="radio" name="entornoEquipo" id="noApto" value="No Apto" <?php echo $entornoEquipo == 0 ? 'checked' : ''; ?>> No Apto
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="form-row">
                                                        <div class="form-group col-md-6">
                                                            <label for="exampleInputNomTarjeta">T° DISPLAY EQUIPO:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-sort-numeric-up"></i></span>
                                                                </div>
                                                                <input type="number" required class="form-control" id="tempEquipo" name="tempEquipo" placeholder="Temperatura del Equipo" step="0.1" value="<?php echo $tempEquipo; ?>">
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="form-row">
                                                        <div class="form-group col-md-6">
                                                            <label for="exampleInputNomTarjeta">SONIDO DE ALARMA EN TERRENO:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="btn-group btn-group-toggle form-control p-0" data-toggle="buttons">


                                                                    <label class="btn btn-outline-success <?php echo $sonidoEquipo == 1 ? 'active' : ''; ?>">
                                                                        <input type="radio" name="sonidoAlarma" id="activado" value="Activado" <?php echo $sonidoEquipo == 1 ? 'checked' : ''; ?>> Activado
                                                                    </label>
                                                                    <label class="btn btn-outline-danger <?php echo $sonidoEquipo == 0 ? 'active' : ''; ?>">
                                                                        <input type="radio" name="sonidoAlarma" id="noActivado" value="No Activado" <?php echo $sonidoEquipo == 0 ? 'checked' : ''; ?>> No Activado
                                                                    </label>

                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-md-6">
                                                            <label for="exampleInputNomTarjeta">LUZ DE ALARMA EN TERRENO:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="btn-group btn-group-toggle form-control p-0" data-toggle="buttons">


                                                                    <label class="btn btn-outline-success <?php echo $luzEquipo == 1 ? 'active' : ''; ?>">
                                                                        <input type="radio" name="luzAlarma" id="activado" value="Activado" <?php echo $luzEquipo == 1 ? 'checked' : ''; ?>> Activado
                                                                    </label>
                                                                    <label class="btn btn-outline-danger <?php echo $luzEquipo == 0 ? 'active' : ''; ?>">
                                                                        <input type="radio" name="luzAlarma" id="noActivado" value="No Activado" <?php echo $luzEquipo == 0 ? 'checked' : ''; ?>> No Activado
                                                                    </label>


                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="form-row">
                                                        <div class="form-group col-12">
                                                            <label for="exampleInputNomTarjeta" id="charCount">OBSERVACIONES (MAX. 1200 CARACTERES):<code>*</code></label>
                                                            <div class="input-group mb-3">

                                                                <textarea id="observacionesApoyo" style="resize:none;" name="observacionesApoyo" class="form-control" rows="7" maxlength="1200" placeholder="Observaciones..." oninput="updateCharCount()"><?php echo $observacionApoyoClinico ?></textarea>
                                                            </div>

                                                            <script>
                                                                function updateCharCount() {
                                                                    const textarea = document.getElementById('observacionesApoyo');
                                                                    const charCount = document.getElementById('charCount');
                                                                    charCount.innerHTML = `OBSERVACIONES (${textarea.value.length}/1200 caracteres usados):<code>*</code>`;
                                                                }
                                                                document.addEventListener('DOMContentLoaded', updateCharCount);

                                                            </script>
                                                        </div>


                                                    </div>

                                                    <div class="col-12">
                                                        <!-- Profile Image -->
                                                        <div class="card card-primary card-outline">
                                                            <div class="card-body box-profile">
                                                                <div class="form-group">
                                                                    <input type="submit" class="btn btn-block btn-primary" value="Guardar" />
                                                                </div>


                                                                <div class="form-group">
                                                                    <a href="index.php" class="btn btn-block btn-warning"> Volver </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


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
<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="../plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="../plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="../plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="../plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="../plugins/moment/moment.min.js"></script>
<script src="../plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<!-- overlayScrollbars -->
<script src="../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Page specific script -->
<script src="js/dark-mode.js"></script>

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
<!-- Select2 -->
<script src="../plugins/select2/js/select2.full.min.js"></script>
<script>

    $(document).ready(function () {
        //Initialize Select2 Elements
        $('.select2equipo').select2({
            theme: 'bootstrap4'
        })


    });
</script>

</body>

</html>