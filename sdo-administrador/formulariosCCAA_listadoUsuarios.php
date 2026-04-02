<?php

include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');
$nomUsuario = $_SESSION['nomUsuario'];

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formularios CCAA - Usuarios</title>

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

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
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
                        <h4>Formularios CCAA - Usuarios</h4>
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

                                <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div>
                                    <div class="table-responsive">
                                        <table id="example2" class="table table-bordered table-hover">
                                            <thead class="bg-secondary">
                                            <tr>
                                                <th style="text-align: center">
                                                    Nombre del Funcionario
                                                </th>
                                                <th style="text-align: center">
                                                    RUT del Funcionario
                                                </th>
                                                <th style="text-align: center">
                                                    Calidad Juridica del Funcionario
                                                </th>
                                                <th style="text-align: center">
                                                    Unidad del Funcionario
                                                </th>
                                                <th style="text-align: center">
                                                    Visualizar
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <?php
                                            $query = 'SELECT * FROM formularios_funcionarios WHERE (rutFormFuncCCAA, idFormFuncCCAA) IN ( SELECT rutFormFuncCCAA, MIN(idFormFuncCCAA) FROM formularios_funcionarios GROUP BY rutFormFuncCCAA ) ORDER BY nomFormFuncCCAA ;';

                                            $res = mysqli_query($conexion, $query);

                                            $numAcceso = 1;



                                            while ($row = $res->fetch_assoc()) {


                                                ?>

                                                <tr>
                                                    <td style="vertical-align:middle; text-align: center;">
                                                        <?php

                                                        $nomFormFuncCCAA = mb_convert_case($row['nomFormFuncCCAA'], MB_CASE_UPPER, "UTF-8");
                                                        echo $nomFormFuncCCAA;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle; text-align: center;">
                                                        <?php

                                                        $rutFormFuncCCAA = mb_convert_case($row['rutFormFuncCCAA'], MB_CASE_UPPER, "UTF-8");
                                                        echo $rutFormFuncCCAA;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle; text-align: center;">
                                                        <?php

                                                        $calidadFormFuncCCAA = mb_convert_case($row['calidadFormFuncCCAA'], MB_CASE_UPPER, "UTF-8");
                                                        echo $calidadFormFuncCCAA;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle; text-align: center;">
                                                        <?php
                                                        $unidadFormFuncCCAA = $row['unidadFormFuncCCAA'];
                                                        $unidadFormRegAcceso2 = mb_convert_case($unidadFormFuncCCAA, MB_CASE_UPPER, "UTF-8");
                                                        echo $unidadFormRegAcceso2;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle; text-align: center;">
                                                        <?php
                                                        if ($row['idFormRegAcceso'] > 0 && $row['idFormFuncCCAA'] > 0) {
                                                            $idFormRegAcceso = $row['idFormRegAcceso'];
                                                            $idFormFuncCCAA = $row['idFormFuncCCAA'];
                                                            ?>
                                                            <form style="margin: 0; text-align: center;" class="" action="formulariosCCAA_administrarUsuarios.php" method="post">

                                                                <input type="hidden" name="idFormRegAcceso" id="idFormRegAcceso" value="<?php echo $idFormRegAcceso; ?>">
                                                                <input type="hidden" name="idFormFuncCCAA" id="idFormFuncCCAA" value="<?php echo $idFormFuncCCAA; ?>">
                                                                <button type="submit" id="inputMozi" class="btn btn-block btn-success"><i class="fa fa-eye"></i></button>

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

                                            }
                                            ?>



                                            </tbody>
                                            <tfoot class="bg-secondary">
                                            <tr>
                                                <th style="text-align: center">
                                                    Nombre del Funcionario
                                                </th>
                                                <th style="text-align: center">
                                                    RUT del Funcionario
                                                </th>
                                                <th style="text-align: center">
                                                    Calidad Juridica del Funcionario
                                                </th>
                                                <th style="text-align: center">
                                                    Unidad del Funcionario
                                                </th>
                                                <th style="text-align: center">
                                                    Visualizar
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
<!-- Page specific script -->
<script>
    $(function() {
        $('#example2').DataTable({
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
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "search": "Buscar:",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            }
        });
    });
</script>
</body>

</html>