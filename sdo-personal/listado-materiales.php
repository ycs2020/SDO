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
    <title>Listado de Materiales</title>

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

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


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
                            <h4>Listado de Materiales</h4>
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

                                    <a href="historial-materiales.php" style="float: right;" class="btn btn-warning"><i class="fa fa-eye"></i>&nbsp;&nbsp;Historial Materiales</a>


                                    <h5>Colores para Identificar:</h5>
                                    <ul>
                                        <li>
                                            <i style="color : #4BD30B ; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon"></i>&nbsp;&nbsp; Alto en stock
                                        </li>
                                        <li>
                                            <i style="color : #E05214 ; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon"></i>&nbsp;&nbsp; Bajo en stock
                                        </li>
                                        <li>
                                            <i style="color : #154BF3 ; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon"></i>&nbsp;&nbsp; Producto vencido
                                        </li>
                                        <li>
                                            <i style="color : #BA15F3 ; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon"></i>&nbsp;&nbsp; Sin fecha de vencimiento
                                        </li>

                                    </ul>

                                    <?php

                                    if (isset($_GET['exitoCrearMaterial'])) {

                                        echo " 

                                                    <script>
                                                    
                                                        Swal.fire({
                                                            position: 'center',
                                                            icon: 'success',
                                                            title: 'Registro del material ingresado exitosamente',
                                                            showConfirmButton: true,
                                                            timer: 3000
                                                        })
                                                    </script>
                                                ";
                                    }

                                    if (isset($_GET['eliminado'])) {
                                        echo " 

                                                    <script>
                                                    
                                                        Swal.fire({
                                                            position: 'center',
                                                            icon: 'info',
                                                            title: 'El registro del material ha sido borrado con exito',
                                                            showConfirmButton: true,
                                                            timer: 3000
                                                        })
                                                    </script>
                                                ";
                                    }

                                    if (isset($_GET['modificacion'])) {


                                        echo " 

                                                    <script>
                                                    
                                                        Swal.fire({
                                                            position: 'center',
                                                            icon: 'success',
                                                            title: 'Se ha modificado el registro del material exitosamente',
                                                            showConfirmButton: true,
                                                            timer: 3000
                                                        })
                                                    </script>
                                                ";
                                    }


                                    ?>
                                </div>
                                <div class="card-body">
                                    <div>
                                        <div class="table-responsive">
                                            <table id="example2" class="table table-bordered table-hover">
                                                <thead class="bg-secondary">
                                                    <tr>
                                                        <th style="vertical-align:middle;">
                                                            <center>Codigo Barras<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Codigo inventario<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Imagen<center>
                                                        </th>

                                                        <th style="vertical-align:middle;">
                                                            <center>Nombre Descriptivo<center>
                                                        </th>

                                                        <th style="vertical-align:middle;">
                                                            <center>Cantidad<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Fecha Ingreso<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Fecha Vencimiento<center>
                                                        </th>
                                                        <!-- <th style="vertical-align:middle;">
                                                            <center>Uso Departamento<center>
                                                        </th> -->
                                                        <th style="vertical-align:middle;">
                                                            <center>Visualizar<center>
                                                        </th>


                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php

                                                    $query = 'SELECT * FROM pañol_materiales ORDER BY fechaIngreso DESC;';

                                                    $res = mysqli_query($conexion, $query);

                                                    $numMaterial = 1;

                                                    while ($row = $res->fetch_assoc()) {

                                                        $color = "";
                                                        $fechaColor = "";
                                                        $cantidad = $row['cantMaterial'];
                                                        $fecha = $row['fechaVencimiento'];
                                                        $fechaActual = date('Y/m/d');
                                                        if ($cantidad <= 9) {
                                                            $color = "style=' background-color: #E05214;font-weight:bold;vertical-align:middle;color:black;'";
                                                        }
                                                        if ($cantidad >= 10) {

                                                            $color = "style=' background-color: #4BD30B; font-weight:bold;vertical-align:middle;color:black;'";
                                                        }

                                                        if ($fecha == 'Sin vencimiento') {

                                                            $fechaColor = "style=' background-color: #BA15F3;font-weight:bold;vertical-align:middle;'";
                                                        } else if ($fecha < $fechaActual) {

                                                            $fechaColor = "style=' background-color: #154BF3;font-weight:bold;vertical-align:middle; text-align:justify'";
                                                        }



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
                                                                    echo $row['codInventario'];
                                                                    ?>
                                                                </center>
                                                            </td>

                                                            <td nowrap="nowrap" style="vertical-align:middle;">
                                                                <center>
                                                                    <?php

                                                                    $imagen = $row['imagen'];

                                                                    ?>

                                                                    <img width="80px" src="../sdo-bodega-panol/img/<?php echo $imagen ?>" alt="Imagen Herramienta">

                                                                    <?php

                                                                    ?>
                                                                </center>
                                                            </td>
                                                            <td style="vertical-align:middle;">
                                                                <center>
                                                                    <?php
                                                                    // if ($row['fechaActa'] == 0) {
                                                                    //     echo "No registra fecha";
                                                                    // } else {
                                                                    //     $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $row['fechaActa'])));
                                                                    //     $inicioHora = date('H:i', strtotime($row['fechaActa']));
                                                                    //     echo $inicioFecha . '<br>' . $inicioHora;
                                                                    // }

                                                                    echo mb_strtoupper($row['nomMaterial']);
                                                                    ?>
                                                                </center>
                                                            </td>
                                                            <td <?php echo $color ?>>
                                                                <center>
                                                                    <?php
                                                                    echo  mb_strtoupper($row['cantMaterial']);



                                                                    ?>
                                                                </center>
                                                            </td>
                                                            <td style="vertical-align:middle; text-align:justify;">
                                                                <center>
                                                                    <?php
                                                                    echo mb_strtoupper($row['fechaIngreso']);
                                                                    ?>
                                                                </center>
                                                            </td>
                                                            <td <?php echo $fechaColor ?>>
                                                                <center>
                                                                    <?php
                                                                    echo mb_strtoupper($row['fechaVencimiento']);
                                                                    ?>
                                                                </center>
                                                            </td>
                                                            <!-- <td nowrap="nowrap" style="vertical-align:middle;font-weight:bold">
                                                                <center>
                                                                    <?php

                                                                    $idDepartamento = mb_strtoupper($row['idDepartamento']);


                                                                    if ($idDepartamento == 9) {

                                                                        $nomDepartamento = "Todos";
                                                                        echo $nomDepartamento;
                                                                    } else {
                                                                        $queryDepa = "SELECT * FROM departamentos WHERE idDepartamento = ${idDepartamento}";
                                                                        $resDepa = mysqli_query($conexion, $queryDepa);

                                                                        while ($rowDepa = $resDepa->fetch_assoc()) {

                                                                            $datos = [];

                                                                            $datos['nomDepartamento'] = $rowDepa['nomDepartamento'];

                                                                            $nomDepartamento = $datos['nomDepartamento'];
                                                                        }

                                                                        echo mb_strtoupper($nomDepartamento);
                                                                    }





                                                                    ?>
                                                                </center>
                                                            </td> -->
                                                            <td style="vertical-align:middle; text-align:justify;">
                                                                <center>
                                                                    <?php

                                                                    $idMaterial = $row['idMaterial'];


                                                                    ?>

                                                                    <!-- Button trigger modal -->
                                                                    <button type="button" class="btn btn-block btn-primary" data-toggle="modal" id="#idMaterial<?php echo $idMaterial ?>" data-target="#idMaterial<?php echo $idMaterial ?>">
                                                                        <i class="fas fa-eye"></i>
                                                                    </button>



                                                                    <!-- Modal -->
                                                                    <div class="modal fade" id="idMaterial<?php echo $idMaterial ?>" name="idMaterial<?php echo $idMaterial ?>" tabindex="-1" role="dialog" aria-labelledby="EjemploModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="EjemploModalLabel">Especificaciones Del Material</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">×</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">

                                                                                    <?php

                                                                                    $sqlDescript1 = "SELECT descriptMaterial FROM pañol_materiales WHERE idMaterial=${idMaterial}";
                                                                                    $resDescript1 = mysqli_query($conexion, $sqlDescript1);

                                                                                    while ($rowDescript1 = $resDescript1->fetch_assoc()) {

                                                                                        $descript = $rowDescript1['descriptMaterial'];
                                                                                    }


                                                                                    ?>
                                                                                    <div style="text-align:left;" class="form-group">
                                                                                        <textarea type="text" readonly class="form-control" rows="10"><?php echo $descript ?></textarea>

                                                                                    </div>

                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-info" data-dismiss="modal">Ya he leído esto!</button>


                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </center>
                                                            </td>


                                                        </tr>
                                                    <?php

                                                        $numMaterial++;
                                                    }


                                                    ?>



                                                </tbody>
                                                <tfoot class="bg-secondary">
                                                    <tr>
                                                        <th style="vertical-align:middle;">
                                                            <center>Codigo Barras<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Codigo inventario<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Imagen<center>
                                                        </th>

                                                        <th style="vertical-align:middle;">
                                                            <center>Nombre Descriptivo<center>
                                                        </th>

                                                        <th style="vertical-align:middle;">
                                                            <center>Cantidad<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Fecha Ingreso<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Fecha Vencimiento<center>
                                                        </th>
                                                        <!-- <th style="vertical-align:middle;">
                                                            <center>Uso Departamento<center>
                                                        </th> -->
                                                        <th style="vertical-align:middle;">
                                                            <center>Visualizar<center>
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
    <script src="js/alerta-material-eliminacion.js"></script>
    <!-- DARK MODE-->
    <script src="js/dark-mode.js"></script>
</body>

</html>