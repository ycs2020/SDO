<?php

include '../sdo-funciones/conexion.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}



?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Historial Ingreso de Herramientas</title>

    <link rel="icon" href="../dist/img/ss-vina-quillota.png" type="image/icon type">
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

<body class="hold-transition sidebar-mini">

    <div class="preloader  flex-column justify-content-center align-items-center">

        <img class="animation__shake" src="../dist/img/ssvq1.png" alt="logo">
        <br>
        <div class="spinner-border text-danger" role="status">
            <span class="sr-only">Loading...</span>
        </div>

    </div>

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
                            <h4>Historial Ingreso de Herramientas</h4>
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
                                <!-- /.card-header -->
                                <div class="card-header">
                                    <a href="materiales-administrar.php" style="float: right;" class="btn btn-info"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <br>
                                    <br>
                                    <a href="ingreso-material.php" style="float: right;" class="btn btn-success"><i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Nueva Material</a>
                                    <br>
                                    <br>
                                    <a href="listado-materiales.php" style="float: right;" class="btn btn-info"><i class="fa fa-list"></i>&nbsp;&nbsp;Listar Materiales</a>


                                    <!-- <a href="ingreso-herramientas.php" style="float: right;" class="btn btn-success"><i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Nueva Herramienta</a> -->

                                    <br>
                                </div>
                                <div class="card-body">
                                    <div>
                                        <div class="table-responsive">
                                            <table id="example2" class="table table-bordered table-hover " style="font-weight: bold;">
                                                <thead class="bg-secondary">
                                                    <tr>
                                                        <th style="vertical-align:middle;">
                                                            <center>Codigo Herramienta<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Nombre Herramienta<center>
                                                        </th>
                                        
                                                        <th style="vertical-align:middle;">
                                                            <center>Historial Ingreso/Modificación<center>
                                                        </th>
                                                     
                                                    </tr>
                                                </thead>
                                                <tbody  >

                                                    <?php

                                                    $query = 'SELECT * FROM pañol_materiales_historial GROUP BY idMaterial DESC;';

                                                    $res = mysqli_query($conexion, $query);

                                                    $numHerramienta = 1;

                                                    while ($row = $res->fetch_assoc()) {

                                                        $idMaterial = $row['idMaterial'];
                                                        $idMaterialIngreso = $row['idMaterialIngreso']

                                                    ?>
                                                        <tr>
                                                            
                                                            <td nowrap="nowrap" style="vertical-align:middle;">
                                                                <center>
                                                                    <?php

                                                                    $codigo = mb_strtoupper($row['codMaterial']);
                                                                    echo $codigo;

                                                                    ?>
                                                                </center>
                                                            </td>
                                                            <td nowrap="nowrap" style="vertical-align:middle; text-align:justify;">
                                                                <center>
                                                                    <?php
                                                                    echo $row['nomMaterial'];
                                                                    ?>
                                                                </center>
                                                            </td>
                                                                                              
                                                            <td style="vertical-align:middle; text-align:justify;">
                                                                <center>
                                                                        <a href="visualizarHistorialMate.php?idMaterial=<?php echo $idMaterialIngreso ?>" class="btn btn-lg btn-warning"><i class="fas fa-eye"></i></a>
                                                                </center>
                                                            </td>
                                                            
                                                            
                                                        </tr>
                                                    <?php

                                                        $numHerramienta++;
                                                    }


                                                    ?>



                                                </tbody>
                                                <tfoot class="bg-secondary">
                                                    <tr>
                                                        <th style="vertical-align:middle;">
                                                            <center>Codigo Herramienta<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Nombre Herramienta<center>
                                                        </th>
                                                      
                                                    
                                                       
                                                                                                  
                                                        <th style="vertical-align:middle;">
                                                            <center>Historial Ingreso/Modificación<center>
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
    <script>
        $(function() {
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
                "responsive": true,
                "language": {
                    "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                    "zeroRecords": "No se han encontrado Registros.",
                    "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                    "infoEmpty": "No existen Registros.",
                    "search": "Buscar:",
                    "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                    "paginate": {
                        "previous": "Anterior",
                        "next": "Siguiente"
                    }
                }
            });
        });
    </script>
    <script src="js/color.js"></script>
    <script src="js/dark-mode.js"></script>

</body>

</html>