<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

$nomUsuario = $_SESSION['nomUsuario'];

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formularios CCAA - Recibidos</title>

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
                        <h4>Formularios CCAA - Recibidos</h4>
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
                                        <h5>&nbsp;&nbsp; Colores para Identificar:</h5>
                                        <ul>
                                            <!--  <li><i style="color: #28A745; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Trabajo Ingresado</li> -->
                                            <li><i style="color: #E4080A;text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon"></i>&nbsp;&nbsp; Sin documento subido y ningun permiso dado</li>
                                            <li><i style="color: #FFDE59;text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon"></i>&nbsp; Con documento subido y casi todos los permisos dados</li>
                                        </ul>
                                        <table id="example2" class="table table-bordered table-hover">
                                            <thead class="bg-secondary">
                                            <tr>
                                                <th style="text-align: center">
                                                    Folio de Formulario
                                                </th>
                                                <th style="text-align: center">
                                                    Unidad de Formulario
                                                </th>
                                                <th style="text-align: center">
                                                    Fecha de Formulario
                                                </th>
                                                <th style="text-align: center">
                                                    Categoria de Formulario
                                                </th>
                                                <th style="text-align: center">
                                                    Visualizar
                                                </th>
                                                <th style="text-align: center">
                                                    PDF
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <?php
                                            $query = 'SELECT * FROM formularios_registro ORDER BY fechaFormRegAcceso DESC';

                                            $res = mysqli_query($conexion, $query);

                                            $numAcceso = 1;

                                            while ($row = $res->fetch_assoc()) {


                                                $idFormRegAcceso = $row['idFormRegAcceso'];

                                                $contarExisteDocumento = contarExisteDocumento($idFormRegAcceso);

                                                $contarFuncionariosPermisoRegistro = contarFuncionariosPermisoRegistro($idFormRegAcceso);

                                                $contarFuncionariosPermisoRegistroTodos = contarFuncionariosPermisoRegistroTodos($idFormRegAcceso);

                                                $contarFuncionariosTotalRegistro = contarFuncionariosTotalRegistro($idFormRegAcceso);

                                                if($contarExisteDocumento > 0 && $contarFuncionariosPermisoRegistroTodos > 0){
                                                    $color = 'bg-yellow';
                                                }else if($contarExisteDocumento < 1 && $contarFuncionariosPermisoRegistroTodos > 0 && $contarFuncionariosTotalRegistro <= $contarFuncionariosPermisoRegistroTodos){
                                                    $color = 'bg-blue';
                                                }else if ($contarExisteDocumento < 1 && $contarFuncionariosPermisoRegistroTodos < 1){
                                                    $color = 'bg-red';
                                                }else{
                                                    $color = '';
                                                }

                                                if ($contarFuncionariosPermisoRegistro < $contarFuncionariosTotalRegistro){

                                                    ?>

                                                    <tr class="<?php echo $color?>">
                                                        <td style="vertical-align:middle; text-align: center;">
                                                            <?php
                                                            $folioFormRegAcceso = mb_convert_case($row['folioFormRegAcceso'], MB_CASE_UPPER, "UTF-8");
                                                            echo $folioFormRegAcceso;

                                                            ?>
                                                        </td>
                                                        <td style="vertical-align:middle; text-align: center;">
                                                            <?php
                                                                $sqlFuncionarios = "SELECT idUnidad FROM formularios_funcionarios WHERE idFormRegAcceso = $idFormRegAcceso GROUP BY idUnidad";
                                                                $resFuncionarios = mysqli_query($conexion,$sqlFuncionarios);
                                                                while($rowFuncionarios = $resFuncionarios->fetch_assoc()){
                                                                    $idUnidad = $rowFuncionarios['idUnidad'];
                                                                    $buscarNomUnidad = buscarnombreunidad($idUnidad);
                                                                    echo $buscarNomUnidad.'<br>';
                                                                }
                                                            /*
                                                            $unidadFormRegAcceso = $row['unidadFormRegAcceso'];
                                                            $unidadFormRegAcceso2 = getUnidadFormatosCCAA($unidadFormRegAcceso);
                                                            $unidadFormRegAcceso3 = mb_convert_case($unidadFormRegAcceso2, MB_CASE_UPPER, "UTF-8");
                                                            echo $unidadFormRegAcceso3;
                                                            */
                                                            ?>
                                                        </td>
                                                        <td style="vertical-align:middle; text-align: center;">
                                                            <?php
                                                            $fechaFormRegAcceso = date('d-m-Y H:i', strtotime($row['fechaFormRegAcceso']));
                                                            echo $fechaFormRegAcceso;

                                                            ?>
                                                        </td>
                                                        <td style="vertical-align:middle; text-align: center;">
                                                            <?php
                                                            $categoriaFormRegAcceso = $row['categoriaFormRegAcceso'];
                                                            $categoriaFormRegAcceso2 = mb_convert_case($categoriaFormRegAcceso, MB_CASE_UPPER, "UTF-8");
                                                            echo $categoriaFormRegAcceso2;

                                                            ?>
                                                        </td>
                                                        <td style="vertical-align:middle; text-align: center;">
                                                            <?php
                                                            if ($row['idFormRegAcceso'] > 0) {
                                                                $idFormRegAcceso = $row['idFormRegAcceso'];
                                                                ?>
                                                                <form style="margin: 0; text-align: center;" target="_blank" class="" action="../sdo-personal/formulariosCCAA_administracion_2.php" method="post">

                                                                    <input type="hidden" name="idFormRegAcceso" id="idFormRegAcceso" value="<?php echo $idFormRegAcceso; ?>">
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
                                                        <td style="vertical-align:middle; text-align: center;">
                                                            <?php
                                                            if ($row['idFormRegAcceso'] > 0) {
                                                                $idFormRegAcceso = $row['idFormRegAcceso'];
                                                                ?>
                                                                <form style=" display: inline" target="_blank" class="" action="../sdo-pdf/SDO-Formulario-SolicitudAcceso-Nuevo-Con-Listado.php" method="post">

                                                                    <input type="hidden" name="idFormRegAcceso" value="<?php echo $idFormRegAcceso; ?>">
                                                                    <button type="submit" id="inputMozi" class="btn btn-block bg-gradient-primary btn-md"><i class="fa fa-file-pdf"></i></button>

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
                                                    $numAcceso = $numAcceso + 1;
                                                }

                                            }
                                            ?>



                                            </tbody>
                                            <tfoot class="bg-secondary">
                                            <tr>
                                                <th style="text-align: center">
                                                    Folio de Formulario
                                                </th>
                                                <th style="text-align: center">
                                                    Unidad de Formulario
                                                </th>
                                                <th style="text-align: center">
                                                    Fecha de Formulario
                                                </th>
                                                <th style="text-align: center">
                                                    Categoria de Formulario
                                                </th>
                                                <th style="text-align: center">
                                                    Visualizar
                                                </th>
                                                <th style="text-align: center">
                                                    PDF
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