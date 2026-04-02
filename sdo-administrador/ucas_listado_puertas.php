<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');

if (isset($_POST['idUCA'])) {

    $idUCA = $_POST['idUCA'];

    if ($idUCA > 0) {

        $nomUCA = obtenerNomUCA($idUCA);

        $_SESSION['idUCA'] = $idUCA;

    } else {

        header('Location: ../sdo-administrador/ucas_listado.php?errorPuerta=true');

    }

} else if (isset($_SESSION['idUCA'])) {
    $idUCA = $_SESSION['idUCA'];

    if ($idUCA > 0) {

        $nomUCA = obtenerNomUCA($idUCA);

        $_SESSION['idUCA'] = $idUCA;

    } else {

        header('Location: ../sdo-administrador/ucas_listado.php?errorPuerta=true');

    }
} else {
    header('Location: ../sdo-administrador/ucas_listado.php?errorPuerta=true');
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Puertas por Grupo de Puertas</title>

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
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/admin-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Listado de Puertas seg&uacute;n <?php echo $nomUCA; ?></h4>
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
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">

                                <a href="ucas_listado.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <h4><b>Visualizando UCA: </b><?php echo $nomUCA; ?></h4>
                                <div>
                                    <div class="table-responsive">
                                        <table id="example2" class="table table-bordered table-hover">
                                            <thead class="bg-secondary">
                                            <tr>
                                                <th>
                                                    <center>N°</center>
                                                </th>
                                                <th>
                                                    <center>Nombre<center>
                                                </th>
                                                <th>
                                                    <center>Grupo de Puerta<center>
                                                </th>
                                                <th>
                                                    <center>Usuarios Grupo de Puerta<center>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <?php
                                            $query = 'SELECT * FROM ACS_DOOR_GROUP_DOOR WHERE DeviceID = "' . $idUCA . '" ORDER BY DoorID ASC';

                                            $res = mysqli_query($conexion, $query);

                                            $numAcceso = 1;

                                            $arrayFuncionarios = array();

                                            while ($row = $res->fetch_assoc()) {

                                                ?>
                                                <tr>
                                                    <td style="vertical-align:middle;">
                                                        <center><?php echo $numAcceso; ?></center>
                                                    </td>
                                                    <td style="vertical-align:middle;" align="justify">
                                                        <?php

                                                        if ($row['DoorUniqueID'] > -1) {
                                                            $nomPuerta = obtenerNomPuerta2($row['DoorUniqueID']);

                                                            $nuevoNom = mb_convert_case($nomPuerta, MB_CASE_UPPER, "UTF-8");

                                                            $nuevoNom = trim($nuevoNom);

                                                            if(empty($nuevoNom)) {
                                                                if ($row['DeviceID'] > 0 && $row['DoorID'] > -1) {
                                                                    $nomPuerta = obtenerNomPuerta($row['DeviceID'],$row['DoorID']);

                                                                    $nuevoNom = mb_convert_case($nomPuerta, MB_CASE_UPPER, "UTF-8");
                                                                    echo $nuevoNom;
                                                                } else {
                                                                    ?>
                                                                    <center><?php echo "Error al Cargar Datos."; ?></center>
                                                                    <?php
                                                                }
                                                            }else{
                                                                echo $nuevoNom;
                                                            }

                                                        } else {
                                                            ?>
                                                            <center><?php echo "Error al Cargar Datos."; ?></center>
                                                            <?php
                                                        }

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" align="justify">
                                                        <?php

                                                        if ($row['DoorGroupID'] > 0) {
                                                            $nomGrupoPuerta = obtenerNameGrupoPuerta($row['DoorGroupID']);

                                                            $nomGrupoPuerta = mb_convert_case($nomGrupoPuerta, MB_CASE_UPPER, "UTF-8");
                                                            echo $nomGrupoPuerta;
                                                        } else {
                                                            ?>
                                                            <center><?php echo "Error al Cargar Datos."; ?></center>
                                                            <?php
                                                        }

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" align="justify">
                                                        <?php

                                                        if ($row['DoorGroupID'] > 0) {
                                                            $DoorGroupID = $row['DoorGroupID'];
                                                            ?>
                                                            <form style="margin: 0; text-align: center;" class="" action="../sdo-jefestecnicos/ucas_grupo_puertas_usuarios.php" method="post">

                                                                <input type="hidden" name="idGrupoPuerta" id="idGrupoPuerta" value="<?php echo $DoorGroupID; ?>">
                                                                <button type="submit" id="inputMozi" class="btn btn-block btn-success"><i class="fa fa-users"></i></button>

                                                            </form>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <center><?php echo "Error al Cargar Datos."; ?></center>
                                                            <?php
                                                        }

                                                        ?>
                                                    </td>

                                                </tr>
                                                <?php

                                                $numAcceso++;
                                            }

                                            ?>


                                            </tbody>
                                            <tfoot class="bg-secondary">
                                            <tr>
                                                <th>
                                                    <center>N°</center>
                                                </th>
                                                <th>
                                                    <center>Nombre
                                                        <center>
                                                </th>
                                                <th>
                                                    <center>Grupo de Puerta
                                                        <center>
                                                </th>
                                                <th>
                                                    <center>Usuarios Grupo de Puerta
                                                        <center>
                                                </th>
                                            </tr>
                                            </tfoot>
                                        </table>

                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->


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
<!-- Page specific script -->
<script>
    $(function () {
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
            "responsive": true
        });
    });
</script>
<script src="js/dark-mode.js"></script>
</body>

</html>