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

if (isset($_POST['idTGChillerReso']) || isset($_SESSION['idTGChillerReso'])) {
    if (isset($_POST['idTGChillerReso'])) {
        $idTG = $_POST['idTGChillerReso'];


        if ($idTG > 0) {
            $_SESSION['idTGChillerReso'] = $idTG;
        } else {
            header('Location: trabajos_general_listado.php?errorDetalle=true');
        }
    } else if (isset($_SESSION['idTGChillerReso'])) {
        $idTG = $_SESSION['idTGChillerReso'];
        if ($idTG > 0) {
            $_SESSION['idTGChillerReso'] = $idTG;
        } else {
            header('Location: trabajos_general_listado.php?errorDetalle=true');
        }

    }
    $sql = "SELECT * FROM trabajos_general WHERE idTG = $idTG";
    $res = mysqli_query($conexion,$sql);
    while($row = $res->fetch_assoc()){
        $folioTG = $row['folioTG'];
        $estadoTG = $row['estadoTG'];
        $solicitanteTG = $row['solicitanteTG'];
        $detalleTG = $row['detalleTG'];
        $fechaTG = $row['fechaTG'];
        $turnoTG = $row['turnoTG'];
        $tecnico1 = $row['tecnico1'];
        // $tipoTecnico = $row['tipoTecnico'];
        $fechaTG2 = strtotime($fechaTG);
        $fechaTG3 = fechaEspañol2($fechaTG2);
        $fechaProgramacion = $row['fechaProgramacion'];
        $fechaProgram1 = strtotime($fechaProgramacion);
        $fechaProgram2 = fechaEspañol2($fechaProgram1);
        $ticketAsociado = $row['ticketAsociado'];
        $tipoTrabajo = $row['tipoTrabajo'];
        $detalleTurnoCamara = $row['detalleTurnoCamara'];
        $detalleTurnoLLE = $row['detalleTurnoLLE'];
        $detalleTurnoCA = $row['detalleTurnoCA'];
        $detalleTurnoRonda = $row['detalleTurnoRondas'];
        $detalleOtros = $row['detalleOtros'];
        $departamentos = $row['departamento'];
        $formularioRondas = $row['formularioRondas'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Check List Chiller Resonador</title>

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
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" />
    <!-- include summernote css-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- include summernote js-->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <style>
        .form-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .col-md-4 {
            flex: 0 0 30%;
            max-width: 30%;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            margin-bottom: 15px;
        }

        th, td {
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: blue;
            color: white;
        }

        input[type="text"] {
            width: calc(100% - 20px);
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/user-sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h4>Orden de Trabajo N° <?php echo $folioTG?> - Check List Chiller Resonador</h4>
                            </div>
                            <div class="card-body">
                                <div class="col-lg-3 col-md-6 col-12 float-left" id="volverAtras2">
                                    <h5>
                                        <b>Cantidad de Check List hechas:</b>
                                    </h5>
                                </div>
                                <div class="col-lg-3 col-md-6 col-12 float-right" id="volverAtras2"
                                     name="volverAtras2">
                                    <!-- small box -->

                                    <a href="visualizardetalles.php" style="float: right;"
                                       class="btn bg-gradient-red float-right btn-md text-white"><i
                                            class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                </div>
                                <br>
                                <br>
                                <hr>
                                <form action="#" method="POST">
                                    <input type="hidden" name="idTG" id="idTG" value="<?php echo $idTG?>">
                                    <?php date_default_timezone_set('America/Santiago');?>
                                    <?php $now = date('Y-m-d H:i:s')?>
                                    <fieldset class="border p-3">
                                        <div class="row">
                                            <div class="form-row">
                                                <div class="form-group col-mb-2">
                                                    <label style="float: left;">Fecha:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-desktop"></i></span>
                                                        </div>
                                                        <input type="datetime-local" class="form-control" id="fecha" name="fecha" value="<?php echo $now?>" readonly required>
                                                    </div>
                                                </div>
                                                <div class="form-group col-mb-2">
                                                    <label style="float: left;">Funcionario Asignado:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user-md"></i></span>
                                                        </div>
                                                        <input type="text" style="text-transform:capitalize" class="form-control" id="tecnico" name="tecnico" value="<?php echo $nomUsuario?>" required>
                                                    </div>
                                                </div>
                                                <div class="form-group col-mb-2">
                                                    <label style="float: left;">Turno:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user-md"></i></span>
                                                        </div>
                                                        <input type="text" style="text-transform:capitalize" class="form-control" id="turno" name="turno" value="<?php echo $turnoTG?>" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <table class="table-bordered">
                                            <thead>
                                                <tr>
                                                    <th colspan="2">Parámetros Chiller Resonador</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Nivel de Helio</td>
                                                    <td><input type="text" name="nivelhelio" id="nivelhelio"></td>
                                                </tr>
                                                <tr>
                                                    <td>Presión Magneto</td>
                                                    <td><input type="text" name="presionmagneto" id="presionmagneto"></td>
                                                </tr>
                                                <tr>
                                                    <td>Compresor Timer (horas)</td>
                                                    <td><input type="text" name="compresortimer" id="compresortimer"></td>
                                                </tr>
                                                <tr>
                                                    <td>Compresor Supply Pressure</td>
                                                    <td><input type="text" name="compresorsupply" id="compresorsupply"></td>
                                                </tr>
                                                <tr>
                                                    <td>Compresor Return Pressure</td>
                                                    <td><input type="text" name="compresorreturn" id="compresorreturn"></td>
                                                </tr>
                                                <tr>
                                                    <td>Presión Entrada Chiller</td>
                                                    <td><input type="text" name="presionentradachiller" id="presionentradachiller"></td>
                                                </tr>
                                                <tr>
                                                    <td>Presión Salida Chiller</td>
                                                    <td><input type="text" name="presionsalidachiller" id="presionsalidachiller"></td>
                                                </tr>
                                                <tr>
                                                    <td>T° Entrada Chiller</td>
                                                    <td><input type="text" name="tentradachiller" id="tentradachiller"></td>
                                                </tr>
                                                <tr>
                                                    <td>T° Salida Chiller</td>
                                                    <td><input type="text" name="tsalidachiller" id="tsalidachiller"></td>
                                                </tr>
                                                <tr>
                                                    <td>Caudal L/M</td>
                                                    <td><input type="text" name="caudallm" id="caudallm"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </fieldset>
                                    <br>
                                    <fieldset class="border p-3">
                                        <legend>Observacion:</legend>
                                        <textarea class="form-control" id="obs" name="obs" rows="10" style="resize: none"></textarea>
                                    </fieldset>
                                    <br>
                                    <button type="submit" class="bg-gradient-primary btn btn-block" style="color: white">Guardar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
</body>
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="../plugins/select2/js/select2.full.min.js"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="../plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<!-- InputMask -->
<script src="../plugins/moment/moment.min.js"></script>
<script src="../plugins/inputmask/jquery.inputmask.min.js"></script>
<!-- date-range-picker -->
<script src="../plugins/daterangepicker/daterangepicker.js"></script>
<!-- bootstrap color picker -->
<script src="../plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Bootstrap Switch -->
<script src="../plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- BS-Stepper -->
<script src="../plugins/bs-stepper/js/bs-stepper.min.js"></script>
<!-- dropzonejs -->
<script src="../plugins/dropzone/min/dropzone.min.js"></script>
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
<script src="js/dark-mode.js"></script>
</html>
