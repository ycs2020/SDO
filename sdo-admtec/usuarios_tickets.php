<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Personal</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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
    include '../sdo-templates/admtec-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Listado de Personal</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a>
                            </li>
                        </ol>
                        <br>
                        <br>

                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <?php

        if (isset($_GET['exitoActivarUsuario'])) {
            ?>
            <script>
                Swal.fire(
                    'Usuario Activado con &Eacute;xito.',
                    '',
                    'success'
                )
            </script>
            <?php
        }

        if (isset($_GET['errorActivarUsuario'])) {
            ?>
            <script>
                Swal.fire(
                    'Error al Activar Usuario. Reintente.',
                    '',
                    'error'
                )
            </script>
            <?php
        }

        if (isset($_GET['exitoActivarPersonal'])) {
            ?>
            <script>
                Swal.fire(
                    'Usuario verificado en Unidad de Personal.',
                    '',
                    'success'
                )
            </script>
            <?php
        }

        if (isset($_GET['errorActivarPersonal'])) {
            ?>
            <script>
                Swal.fire(
                    'Error al Verificar Usuario en Unidad de Personal. Reintente.',
                    '',
                    'error'
                )
            </script>
            <?php
        }

        if (isset($_GET['exitoActivarCCAA'])) {
            ?>
            <script>
                Swal.fire(
                    'Usuario verificado en Sistema de Control de Acceso.',
                    '',
                    'success'
                )
            </script>
            <?php
        }

        if (isset($_GET['errorActivarCCAA'])) {
            ?>
            <script>
                Swal.fire(
                    'Error al Verificar Usuario en Sistema de Control de Acceso. Reintente.',
                    '',
                    'error'
                )
            </script>
            <?php
        }
        if (isset($_GET['exitoDesactivarUsuario'])) {
            ?>
            <script>
                Swal.fire(
                    'Usuario Desactivado con Exito',
                    '',
                    'success'
                )
            </script>
            <?php
        }
        if (isset($_GET['errorDesactivarUsuario'])) {
            ?>
            <script>
                Swal.fire(
                    'Error al Desactivar Usuario . Reintente.',
                    '',
                    'error'
                )
            </script>
            <?php
        }
        ?>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                    <span>
                                        <a href="index.php" style="float: right;" class="btn btn-danger"><i
                                                    class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                      <br>
                                        <br>
                                    </span>


                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <br>
                                <h5>&nbsp;&nbsp; Colores para Identificar:</h5>
                                <ul>
                                    <li><i style="color: #A04000; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Usuario Desactivado</li>
                                    <li><i style="color: #287233; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Usuario Activado</li>
                                    <li><i style="color: #563D7C; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Usuario Nuevo por Activar</li>

                                </ul>
                                <div class="table-responsive">
                                    <table id="personalDesktop11" class="table table-bordered table-hover">
                                        <thead class="bg-secondary">
                                        <tr>
                                            <th>
                                                <center>N°</center>
                                            </th>
                                            <th>
                                                <center>Nombre</center>
                                            </th>
                                            <th>
                                                <center>Rut</center>
                                            </th>
                                            <th>
                                                <center>Cargo</center>
                                            </th>
                                            <th>
                                                <center>Departamento</center>
                                            </th>
                                            <th>
                                                <center>Correo</center>
                                            </th>
                                            <th>
                                                <center>Telefono</center>
                                            </th>

                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php
                                        $query = 'SELECT * FROM usuarios_tickets ORDER BY unidadUsuariosT ASC, nomUsuariosT ASC';

                                        $res = mysqli_query($conexion, $query);

                                        $numUT = 1;

                                        while ($row = $res->fetch_assoc()) {
                                            $activoUsuarioT1 = $row['activoUsuariosT'];
                                            $desacUsuarios = $row['usuarioDesacT'];
                                            $ccaaUsuarios = $row['ccaaUsuariosT'];
                                            $personalUsuariosT = $row['personalUsuariosT'];

                                            if($activoUsuarioT1 > 0 ){
                                                $color = 'background-color: #287233; color:white';
                                            } else{
                                                $color = 'background-color: #A04000; color:white';


                                            }

                                            if($ccaaUsuarios < 1 || $personalUsuariosT < 1){
                                                $color = 'background-color:#563D7C; color:white';
                                            }



                                            ?>
                                            <tr style="<?php echo $color?>" data-widget="expandable-table" aria-expanded="false">
                                                <td style="vertical-align:middle; ">
                                                    <center><?php echo $numUT; ?></center>
                                                </td>
                                                <td style="vertical-align:middle;" align="justify">
                                                    <?php
                                                    $nuevoNom = mb_convert_case($row['nomUsuariosT'], MB_CASE_TITLE, "UTF-8");
                                                    echo $nuevoNom;

                                                    ?>
                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php
                                                    $rutUsuariosT = $row['rutUsuariosT'];
                                                    echo $rutUsuariosT;

                                                    ?>
                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php
                                                    $cargoUsuariosT = $row['cargoUsuariosT'];
                                                    echo $cargoUsuariosT;

                                                    ?>
                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php
                                                    $unidadUsuariosT = $row['unidadUsuariosT'];
                                                    echo $unidadUsuariosT;

                                                    ?>
                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php
                                                    $emailUsuariosT = $row['emailUsuariosT'];
                                                    echo $emailUsuariosT;

                                                    ?>
                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php
                                                    $telefonoUsuariosT = $row['telefonoUsuariosT'];
                                                    echo $telefonoUsuariosT;

                                                    ?>
                                                </td>

                                            </tr>

                                            <tr class="expandable-body">
                                                <td colspan="7">
                                                    <div class="row">
                                                        <div style="vertical-align:middle;" class="col-4 col-lg-4 col-md-4 text-center">
                                                            <?php

                                                            if($row['ccaaUsuariosT'] == 0){
                                                                ?>
                                                                <center>
                                                                    <?php
                                                                    $idUsuariosT = $row['idUsuariosT'];
                                                                    $idUsuariosT = trim($idUsuariosT);
                                                                    if ($idUsuariosT == 0 || $idUsuariosT == "" || $idUsuariosT == " " || $idUsuariosT == "  ") {
                                                                        ?>
                                                                        <center><?php echo "Error al Cargar Datos."; ?></center>
                                                                        <?php
                                                                    } else {

                                                                        ?>

                                                                        <form style="margin: 0; text-align: center;" class="" action="../sdo-funciones/tickets_verificarCCAA.php" method="post">

                                                                            <input type="hidden" name="idUsuariosT" value="<?php echo $idUsuariosT; ?>">
                                                                            <button type="submit" id="inputMozi" class="btn btn-primary"><i class="fa fa-check"></i>&nbsp;&nbsp;Verificar Sistema CCAA</button>

                                                                        </form>

                                                                        <?php

                                                                    }
                                                                    ?>


                                                                </center>
                                                                <?php
                                                            }else{

                                                                $timestampCCAA = strtotime($row['fechaCCAAUsuariosT']);

                                                                $fechaCCAA = fechaEspañol2($timestampCCAA);

                                                                echo 'Verificaci&oacute;n en Sistema de Control de Acceso:';
                                                                echo '<br/>';
                                                                echo $fechaCCAA;
                                                            }

                                                            ?>
                                                        </div>
                                                        <div style="vertical-align:middle;" class="col-4 col-lg-4 col-md-4 text-center">
                                                            <?php

                                                            if($row['personalUsuariosT'] == 0){
                                                                ?>
                                                                <center>
                                                                    <?php
                                                                    $idUsuariosT = $row['idUsuariosT'];
                                                                    $idUsuariosT = trim($idUsuariosT);
                                                                    if ($idUsuariosT == 0 || $idUsuariosT == "" || $idUsuariosT == " " || $idUsuariosT == "  ") {
                                                                        ?>
                                                                        <center><?php echo "Error al Cargar Datos."; ?></center>
                                                                        <?php
                                                                    } else {

                                                                        ?>

                                                                        <form style="margin: 0; text-align: center;" class="" action="../sdo-funciones/tickets_verificarUnidadPersonal.php" method="post">

                                                                            <input type="hidden" name="idUsuariosT" value="<?php echo $idUsuariosT; ?>">
                                                                            <button type="submit" id="inputMozi" class="btn btn-primary"><i class="fa fa-check"></i>&nbsp;&nbsp;Verificar en Unidad de Personal</button>

                                                                        </form>

                                                                        <?php

                                                                    }
                                                                    ?>


                                                                </center>
                                                                <?php
                                                            }else{

                                                                $timestampPersonal = strtotime($row['fechaPersonalUsuariosT']);

                                                                $fechaPersonal = fechaEspañol2($timestampPersonal);

                                                                echo 'Verificaci&oacute;n en Unidad de Personal:';
                                                                echo '<br/>';
                                                                echo $fechaPersonal;
                                                            }

                                                            ?>
                                                        </div>
                                                        <div style="vertical-align:middle;" class="col-4 col-lg-4 col-md-4 text-center">
                                                            <?php

                                                            if($row['activoUsuariosT'] == 0){
                                                                if($row['ccaaUsuariosT'] == 1 && $row['personalUsuariosT'] == 1 && $row['activoUsuariosT'] == 0){
                                                                    ?>
                                                                    <center>
                                                                        <?php
                                                                        $idUsuariosT = $row['idUsuariosT'];
                                                                        $idUsuariosT = trim($idUsuariosT);
                                                                        if ($idUsuariosT == 0 || $idUsuariosT == "" || $idUsuariosT == " " || $idUsuariosT == "  ") {
                                                                            ?>
                                                                            <center><?php echo "Error al Cargar Datos."; ?></center>
                                                                            <?php
                                                                        } else {
                                                                            $timestampDesactivo = strtotime($row['fechaDesacUsuarioT']);

                                                                            $fechaDesactivo = fechaEspañol2($timestampDesactivo);
                                                                            if(empty($row['fechaDesacUsuarioT']) || $row['fechaDesacUsuarioT'] == '0000-00-00 00:00:00'){
                                                                                echo '';
                                                                            } else{

                                                                               echo 'Fecha de Desactivaci&oacute;n:';
                                                                                echo '<br/>';
                                                                                echo $fechaDesactivo;
                                                                                echo '<br/>';

                                                                            }

                                                                            ?>


                                                                            <form style="margin: 0; text-align: center;" class="" action="../sdo-funciones/tickets_activarUsuario.php" method="POST">
                                                                                <input type="hidden" name="idUsuariosT" value="<?php echo $idUsuariosT; ?>">
                                                                                <button type="submit" id="inputMozi" class="btn btn-primary"><i class="fa fa-check"></i>&nbsp;&nbsp;Activar Usuario</button>
                                                                            </form>
                                                                            <?php


                                                                        }
                                                                        ?>
                                                                    </center>
                                                                    <?php
                                                                }else{
                                                                    ?>
                                                                    <button type="button" disabled id="inputMozi" class="btn btn-primary"><i class="fa fa-check"></i>&nbsp;&nbsp;Activar Usuario</button>
                                                                    <?php
                                                                }
                                                            }else{

                                                                $timestampActivo = strtotime($row['fechaActivoUsuariosT']);

                                                                $fechaActivo = fechaEspañol2($timestampActivo);



                                                                echo 'Fecha de Activaci&oacute;n:';
                                                                echo '<br/>';
                                                                echo $fechaActivo;
                                                                echo '<br/>';





                                                                $idUsuariosT = $row['idUsuariosT'];
                                                                $idUsuariosT = trim($idUsuariosT);
                                                                ?>

                                                                <form action="../sdo-funciones/desactivar-usuario.php" method="POST" class="" style="margin: 0; text-align: center;">
                                                                    <input type="hidden" name="idUsuariosT" value="<?php echo $idUsuariosT; ?>">
                                                                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i>&nbsp;&nbsp;Desactivar Usuario</button>

                                                                </form>
                                                                <?php


                                                            }



                                                            ?>


                                                        </div>

                                                    </div>
                                                    <div style="vertical-align:middle;" class="col-2 col-lg-4 col-md-4 text-center">
                                                        <?php
                                                      /*  if(!empty($row['personaDesac'])){
                                                            //   echo $row['personaDesac'];
                                                            echo 'El usuario '.$row['personaDesac'].' desactivo a este usuario';
                                                        }*/
                                                        ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php

                                            $numUT = $numUT + 1;
                                        }
                                        ?>


                                        </tbody>
                                        <tfoot class="bg-secondary">
                                        <tr>
                                            <th>
                                                <center>N°</center>
                                            </th>
                                            <th>
                                                <center>Nombre</center>
                                            </th>
                                            <th>
                                                <center>Rut</center>
                                            </th>
                                            <th>
                                                <center>Cargo</center>
                                            </th>
                                            <th>
                                                <center>Departamento</center>
                                            </th>
                                            <th>
                                                <center>Correo</center>
                                            </th>
                                            <th>
                                                <center>Telefono</center>
                                            </th>
                                        </tr>
                                        </tfoot>
                                    </table>

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
        $('#personalDesktop').DataTable({
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
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            }
        });
    });
</script>
<script src="js/dark-mode.js"></script>
</body>

</html>