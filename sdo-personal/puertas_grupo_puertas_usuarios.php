<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');

if(isset($_POST['idGrupoPuerta'])){

    $idGrupoPuerta = $_POST['idGrupoPuerta'];

    if($idGrupoPuerta > 0){

        $nomGrupoPuerta = obtenerNameGrupoPuerta($idGrupoPuerta);

        $_SESSION['idGrupoPuerta'] = $idGrupoPuerta;

    }else{

        header('Location: ../sdo-personal/puertas_ccaa_listado.php?errorPuerta=true');

    }

}else if(isset($_SESSION['idGrupoPuerta'])){
    $idGrupoPuerta = $_SESSION['idGrupoPuerta'];

    if($idGrupoPuerta > 0){

        $nomGrupoPuerta = obtenerNameGrupoPuerta($idGrupoPuerta);

    }else{

        header('Location: ../sdo-personal/puertas_ccaa_listado.php?errorPuerta=true');

    }
}else{
    header('Location: ../sdo-personal/puertas_ccaa_listado.php?errorPuerta=true');
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Usuarios por Grupo de Puertas</title>

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
                        <h4>Listado de Grupo de Puertas</h4>
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

                                <a href="puertas_ccaa_listado.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <h4><b>Visualizando: </b><?php echo $nomGrupoPuerta; ?></h4>
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
                                                    <center>Departamento<center>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <?php
                                            $query = 'SELECT * FROM ACS_DOOR_GROUP_USER WHERE DoorGroupID = "'.$idGrupoPuerta.'" ORDER BY DoorGroupID ASC';

                                            $res = mysqli_query($conexion, $query);

                                            $numAcceso = 1;

                                            $arrayFuncionarios = array();

                                            while ($row = $res->fetch_assoc()) {

                                                $datosUsuarioPuerta = array();

                                                $datosUsuarioPuerta = getACMOwner($row['UserID']);

                                                $rutUsuarioPuerta = $datosUsuarioPuerta['OWNER_CODE'];

                                                $nomUsuarioPuerta = $datosUsuarioPuerta['OWNER_Name'];

                                                $idDeptoUsuarioPuerta = $datosUsuarioPuerta['DEPT_ID'];

                                                $nomDeptoUsuarioPuerta = $datosUsuarioPuerta['nomDepartament'];

                                                $tarjetaUsuarioPuerta = $datosUsuarioPuerta['CARD_NUMBER'];

                                                $arrayFuncionarios[] = $datosUsuarioPuerta;

                                            }


                                            foreach ($arrayFuncionarios as $key => $row) {
                                                $aux[$key] = $row['OWNER_Name'];
                                            }

                                            array_multisort($aux, SORT_ASC, $arrayFuncionarios);

                                            foreach ($arrayFuncionarios as $key => $row) {

                                                ?>

                                                <tr>
                                                    <td style="vertical-align:middle;">
                                                        <center><?php echo $numAcceso; ?></center>
                                                    </td>
                                                    <td style="vertical-align:middle;" align="justify">
                                                        <?php
                                                        $nuevoNom = mb_convert_case($row['OWNER_Name'], MB_CASE_UPPER, "UTF-8");
                                                        echo $nuevoNom;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" align="justify">
                                                        <?php
                                                        $deptoUP = mb_convert_case($row['nomDepartament'], MB_CASE_UPPER, "UTF-8");
                                                        echo $deptoUP;

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
                                                <th>
                                                    <center>Departamento<center>
                                                </th>
                                            </tr>
                                            </tfoot>
                                        </table>

                                        <br>
                                        <br>



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
            "buttons": [{
                extend: 'excelHtml5',
                text: 'Exportar a Excel',
                title: 'LISTADO DE FUNCIONARIOS EN PUERTA <?php echo $nomGrupoPuerta; ?>',
                className: 'btn btn-success',
                exportOptions: {
                    columns: [0, 1, 2],
                    format: {
                        body: function(data, row, column, node) {
                            data = $('<p>' + data + '</p>').text();
                            return $.isNumeric(data.replace(',', '.')) ? data.replace('.', ',') : data;
                        }
                    }
                }
            }],
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