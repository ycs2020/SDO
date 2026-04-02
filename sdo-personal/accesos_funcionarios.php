<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');

$owner_code = '';

if(isset($_POST['owner_code'])){
    $owner_code = $_POST['owner_code'];
}else if(isset($_SESSION['owner_code'])){
    $owner_code = $_SESSION['owner_code'];
}else{
    header('Location: ../sdo-personal/personal-controlAcceso.php?errorPuerta=true');
}

if($owner_code > 0){
    $_SESSION['owner_code'] = $owner_code;

    $dataUsuario = array();
    $dataUsuario = getFuncionarioACM($owner_code);
    $rutACMUser = $dataUsuario['OWNER_CODE'];
    $nomACMUser = $dataUsuario['OWNER_Name'];
    $idDeptACMUser = $dataUsuario['DEPT_ID'];
    $nomDeptACMUser = buscarNomDeptoCCAA($dataUsuario['DEPT_ID']);
    $cardACMUser = $dataUsuario['CARD_NUMBER'];


}else{

    header('Location: ../sdo-personal/personal-controlAcceso.php?errorPuerta=true');

}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Permisos seg&uacute;n Usuario</title>

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
                        <h4>Listado de Permisos seg&uacute;n Usuario</h4>
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

                                <a href="personal-controlAcceso.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>
                                <form action="../sdo-pdf/SDO-PERMISOS-USUARIOS.php" target="_blank" method="POST">
                                    <input type="hidden" id="owner_code" name="owner_code" value="<?php echo $owner_code?>">
                                    <button type="submit" style="float: right" class="btn btn-sm bg-primary"><i class="fas fa-file-pdf"></i> Exportar Permisos Funcionario PDF</button>
                                </form>
                                <br>
                                <br>
                                <form action="#" method="POST" target="_blank">
                                    <input type="hidden" id="owner_code" name="owner_code" value="<?php echo $owner_code?>">

                                </form>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row">


                                    <h5>
                                        <ul>
                                            <li><b>Funcionario: </b><?php echo $nomACMUser; ?></li>
                                            <br>
                                            <li><b>Unidad: </b><?php echo $nomDeptACMUser; ?></li>
                                        </ul>
                                    </h5>

                                </div>
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
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <?php
                                            $query = 'SELECT * FROM ACS_DOOR_GROUP_USER WHERE userid = "'.$rutACMUser.'"';

                                            $res = mysqli_query($conexion, $query);

                                            $numAcceso = 1;

                                            $arrayPuertas = array();

                                            while ($row = $res->fetch_assoc()) {

                                                $datosPuerta = array();

                                                $datosPuerta = getACSGroupDoor($row['DoorGroupID']);

                                                $kidACS = $datosPuerta['KID'];

                                                $idGroupACS = $datosPuerta['DoorGroupID'];

                                                $nomGroupACS = $datosPuerta['DoorGroupName'];

                                                $arrayPuertas[] = $datosPuerta;

                                            }


                                            foreach ($arrayPuertas as $key => $row) {
                                                $aux[$key] = $row['DoorGroupName'];
                                            }

                                            array_multisort($aux, SORT_ASC, $arrayPuertas);

                                            foreach ($arrayPuertas as $key => $row) {

                                                ?>

                                                <tr>
                                                    <td style="vertical-align:middle;">
                                                        <center><?php echo $numAcceso; ?></center>
                                                    </td>
                                                    <td style="vertical-align:middle;" align="justify">
                                                        <?php
                                                        $nuevoNom = mb_convert_case($row['DoorGroupName'], MB_CASE_UPPER, "UTF-8");
                                                        echo $nuevoNom;

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
                                                    <center>Nombre<center>
                                                </th>
                                            </tr>
                                            </tfoot>
                                        </table>

                                        <br>
                                        <br>

<!--                                        --><?php
//                                        var_export($arrayPuertas);
//                                        ?>

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
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            "order": [[1, 'ASC']],
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando _TOTAL_ funcionarios",
                "infoEmpty": "No existen Registros.",
                "search": "Buscar:",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            }
        }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
    });
</script>
<script src="js/dark-mode.js"></script>
</body>

</html>