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
    <title>Administraci&oacute;n de Combustible</title>

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


    <script>
        $(document).ready(function() {


            // Show div by removing inline display none style rule
            $(".show-realizar").click(function() {
                $("#rowMenu2").show();
                $("#rowMenu1").hide();
                $("#listarMC").show();
                $("#registrarMC").hide();
            });

            // Show div by removing inline display none style rule
            $(".show-listar").click(function() {
                $("#rowMenu2").hide();
                $("#rowMenu1").show();
                $("#listarMC").hide();
                $("#registrarMC").show();
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
        include '../sdo-templates/user-sidebar.php';
        ?>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h4>Administraci&oacute;n de Combustible</h4>

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

                                    <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
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
                                <div class="card-body" style="text-align:center;">
                                    <button class="btn bg-yellow show-realizar" id="registrarMC" name="registrarMC">Registrar Medici&oacute;n de Combustible</button>
                                    <button class="btn bg-yellow show-listar" id="listarMC" style="display:none;" name="listarMC">Ver Mediciones de Combustible</button>


                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>

                    <div class="row" id="rowMenu1" name="rowMenu1" style="display:none;">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5>1.- Listado de Mediciones de Combustible</h5>
                                    <hr>

                                    <table style="font-size:90%" id="example2" class="table table-bordered table-hover">
                                        <thead class="bg-secondary">
                                            <tr>
                                                <th style="vertical-align:middle;">
                                                    <center>Fecha de Medici&oacute;n</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Funcionario Encargado</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Turno Reportado</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Estanque N° 1 en Terreno</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Estanque N° 2 en Terreno</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Estanque N° 1 Control Centralizado</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Estanque N° 2 Control Centralizado</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Estanque N° 1 Control Centralizado (Litros)</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Estanque N° 2 Control Centralizado (Litros)</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Diferencia Estanque N° 1</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Diferencia Estanque N° 2</center>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $queryMC = "SELECT * FROM combustible_medicion ORDER BY fechaMC ASC";

                                            $resMC = mysqli_query($conexion, $queryMC);

                                            $numMC = 1;

                                            while ($rowMC = $resMC->fetch_assoc()) {

                                                $fechaMC = $rowMC['fechaMC'];

                                                $tecnicoMC = $rowMC['tecnicoMC'];
                                                $tecnicoMC = trim($tecnicoMC);
                                                $tecnicoMC = mb_convert_case($tecnicoMC, MB_CASE_UPPER, "UTF-8");

                                                $turnoMC = $rowMC['turnoMC'];
                                                $turnoMC = trim($turnoMC);
                                                $turnoMC = mb_convert_case($turnoMC, MB_CASE_UPPER, "UTF-8");

                                                $terreno1MC = $rowMC['terreno1MC'];
                                                $terreno1MC = trim($terreno1MC);

                                                $terreno2MC = $rowMC['terreno2MC'];
                                                $terreno2MC = trim($terreno2MC);

                                                $control1MC = $rowMC['control1MC'];
                                                $control1MC = trim($control1MC);
                                                $litrosControl1MC = $control1MC * 100;

                                                $control2MC = $rowMC['control2MC'];
                                                $control2MC = trim($control2MC);
                                                $litrosControl2MC = $control2MC * 100;

                                                $diferenciaT1 = $terreno1MC - $litrosControl1MC;
                                                $diferenciaT2 = $terreno2MC - $litrosControl2MC;

                                            ?>

                                                <tr>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <center>
                                                            <?php if ($fechaMC == 0) {
                                                                echo "No registra fecha";
                                                            } else {

                                                                $registroFechaMC = date('d/m/Y', strtotime(str_replace('/', '-', $fechaMC)));
                                                                $registroHoraMC = date('H:i', strtotime($fechaMC));

                                                                // echo $registroFechaMC . ' a las ' . $registroHoraMC.' hrs.';

                                                                echo $registroFechaMC . ' a las ' . $registroHoraMC . ' hrs.';
                                                            } ?>
                                                        </center>

                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <center>
                                                            <?php if (empty($tecnicoMC)) {
                                                                echo "No registra T&eacute;cnico";
                                                            } else {

                                                                echo $tecnicoMC;
                                                            } ?>
                                                        </center>

                                                    </td>
                                                    <td style="vertical-align:middle;" align="center">
                                                        <center>
                                                            <?php if (empty($turnoMC)) {
                                                                echo "No registra turno";
                                                            } else {

                                                                echo $turnoMC;
                                                            } ?>
                                                        </center>

                                                    </td>
                                                    <td style="vertical-align:middle;" align="center">
                                                        <center>
                                                            <?php if ($terreno1MC < 0 || empty($terreno1MC)) {
                                                                echo "No registra Medida";
                                                            } else {

                                                                echo $terreno1MC;
                                                            } ?>
                                                        </center>

                                                    </td>
                                                    <td style="vertical-align:middle;" align="center">
                                                        <center>
                                                            <?php if ($terreno2MC < 0 || empty($terreno2MC)) {
                                                                echo "No registra Medida";
                                                            } else {

                                                                echo $terreno2MC;
                                                            } ?>
                                                        </center>

                                                    </td>
                                                    <td style="vertical-align:middle;" align="center">
                                                        <center>
                                                            <?php if ($control1MC < 0 || empty($control1MC)) {
                                                                echo "No registra Medida";
                                                            } else {

                                                                echo $control1MC . '%';
                                                            } ?>
                                                        </center>

                                                    </td>
                                                    <td style="vertical-align:middle;" align="center">
                                                        <center>
                                                            <?php if ($control2MC < 0 || empty($control2MC)) {
                                                                echo "No registra Medida";
                                                            } else {

                                                                echo $control2MC . '%';
                                                            } ?>
                                                        </center>

                                                    </td>
                                                    <td style="vertical-align:middle;" align="center">
                                                        <center>
                                                            <?php if ($control1MC < 0 || empty($control1MC)) {
                                                                echo "No registra Medida";
                                                            } else {

                                                                echo $litrosControl1MC;
                                                            } ?>
                                                        </center>

                                                    </td>
                                                    <td style="vertical-align:middle;" align="center">
                                                        <center>
                                                            <?php if ($control2MC < 0 || empty($control2MC)) {
                                                                echo "No registra Medida";
                                                            } else {

                                                                echo $litrosControl2MC;
                                                            } ?>
                                                        </center>

                                                    </td>
                                                    <td style="vertical-align:middle;" align="center">
                                                        <center>
                                                            <?php if (empty($diferenciaT1)) {
                                                                echo "No registra Medida";
                                                            } else {

                                                                echo $diferenciaT1;
                                                            } ?>
                                                        </center>

                                                    </td>
                                                    <td style="vertical-align:middle;" align="center">
                                                        <center>
                                                            <?php if (empty($diferenciaT2)) {
                                                                echo "No registra Medida";
                                                            } else {

                                                                echo $diferenciaT2;
                                                            } ?>
                                                        </center>

                                                    </td>
                                                </tr>

                                            <?php
                                            }
                                            ?>

                                        </tbody>
                                        <tfoot class="bg-secondary">
                                            <tr>
                                                <th style="vertical-align:middle;">
                                                    <center>Fecha de Medici&oacute;n</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Funcionario Encargado</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Turno Reportado</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Estanque N° 1 en Terreno</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Estanque N° 2 en Terreno</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Estanque N° 1 Control Centralizado</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Estanque N° 2 Control Centralizado</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Estanque N° 1 Control Centralizado (Litros)</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Estanque N° 2 Control Centralizado (Litros)</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Diferencia Estanque N° 1</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Diferencia Estanque N° 2</center>
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>

                    <div class="row" id="rowMenu2" name="rowMenu2">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5>2.- Registrar Medici&oacute;n de Combustible</h5>
                                    <br>
                                    <form action="../sdo-funciones/combustible_registrarMedTec.php" method="post" enctype="multipart/form-data">


                                        <fieldset class="border p-3">
                                            <legend class="w-auto">&nbsp;Informaci&oacute;n del Turno:&nbsp;&nbsp;</legend>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputTurno">Turno realizado: <code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                        </div>
                                                        <select id="turnoMC" required class="form-control" name="turnoMC">
                                                            <option value="" selected disabled>Seleccione...</option>
                                                            <option value="Día">Dia (08:00 - 20:00)</option>
                                                            <option value="Noche">Noche (20:00 - 08:00)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                        </fieldset>
                                        <hr>

                                        <fieldset class="border p-3">
                                            <legend class="w-auto">&nbsp;Informaci&oacute;n del Tanque 1:&nbsp;&nbsp;</legend>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputTerreno1">Medici&oacute;n Tanque 1 en Terreno: <code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-sort-numeric-up"></i></span>
                                                        </div>
                                                        <input class="form-control" required id="terreno1MC" type="number" name="terreno1MC" min="0"/>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputControl1">Medici&oacute;n en Porcentaje de Tanque 1 en Control Centralizado : <code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-sort-numeric-up"></i></span>
                                                        </div>
                                                        <input class="form-control" required id="control1MC" type="number" name="control1MC" min="0"/>
                                                    </div>
                                                </div>
                                            </div>

                                        </fieldset>

                                        <hr>

                                        <fieldset class="border p-3">
                                            <legend class="w-auto">&nbsp;Informaci&oacute;n del Tanque 2:&nbsp;&nbsp;</legend>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputTerreno1">Medici&oacute;n Tanque 1 en Terreno: <code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-sort-numeric-up"></i></span>
                                                        </div>
                                                        <input class="form-control" required id="terreno2MC" type="number" name="terreno2MC" min="0"/>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputControl1">Medici&oacute;n en Porcentaje de Tanque 2 en Control Centralizado : <code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-sort-numeric-up"></i></span>
                                                        </div>
                                                        <input class="form-control" required id="control2MC" type="number" name="control2MC" min="0"/>
                                                    </div>
                                                </div>
                                            </div>

                                        </fieldset>

                                        <hr>
                                        <br>

                                        <div class="form-group">
                                            <input type="hidden" name="personalMC" id="personalMC" value="<?php echo $nomUsuario; ?>">
                                            <input type="submit" class="btn btn-block btn-primary" value="Guardar" />

                                        </div>
                                    </form>

                                </div>

                            </div>

                        </div>

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
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "buttons": [{
                    extend: 'excelHtml5',
                    text: 'Exportar a Excel',
                    title: 'ADMINISTRACIÓN DE COMBUSTIBLE DE CALDERAS - SDO',
                    className: 'btn btn-success',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                    }
                }],
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": false,
                "autoWidth": false,
                "responsive": true,
                "language": {
                    "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                    "zeroRecords": "No se han encontrado Registros.",
                    "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                    "infoEmpty": "No existen Registros.",
                    "search": "Buscar :",
                    "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                    "paginate": {
                        "previous": "Anterior",
                        "next": "Siguiente"
                    }
                }

            }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>
    <!-- <script>
        function myFunction(name) {
            alert(name)
        }
    </script> -->
</body>

</html>