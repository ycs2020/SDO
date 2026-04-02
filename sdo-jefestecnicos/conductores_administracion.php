
<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Conductores</title>

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

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/jefes-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Listado de Conductores</h4>
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


                                <a href="listado-viajes-nuevo.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>
                                <span>
                                        <form method="post" action="../sdo-funciones/conductores-agregar.php">
                                            <button type="button" data-toggle="modal" data-target="#modalAddConductores" id="inputMozi" class="btn btn-primary" style="float:right;"><i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Conductor</button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="modalAddConductores" name="modalAddConductores" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-primary">
                                                            <h5 class="modal-title" id="exampleModalLabel">Agregar Conductor</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Nombre: <code>*</code></label>
                                                                <input type="text" class="form-control form-control-border" id="addNombre" name="addNombre" placeholder="Nombre del Conductor" value="" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                                                            </div>
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Usuario: <code>*</code></label>
                                                                <input style="text-align:left;" type="text" style="text-align:center;" class="form-control form-control-border" id="addRUT" name="addRUT" placeholder="RUT del Conductor (Formato: XXXXXXXXX)" value="" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Agregar</button>
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </span>
                            </div>
                            <?php

                            //AVISOS AGREGAR
                            if (isset($_GET['exitoAgregarConductor'])) {
                                ?>
                                <script>
                                    Swal.fire(
                                        'Conductor Creado con &Eacute;xito.',
                                        '',
                                        'success'
                                    )
                                </script>
                                <?php
                            }
                            if (isset($_GET['errorAgregarConductor'])) {
                                ?>
                                <script>
                                    Swal.fire(
                                        '¡ Ha ocurrido un problema !',
                                        'Error al Agregar al Conductor. Reintente.',
                                        'error'
                                    )
                                </script>
                                <?php
                            }

                            //AVISOS AGREGAR

                            //AVISOS MODIFICAR
                            if (isset($_GET['exitoModificarConductor'])) {
                                ?>
                                <script>
                                    Swal.fire(
                                        'Conductor Modificado con &Eacute;xito.',
                                        '',
                                        'success'
                                    )
                                </script>
                                <?php
                            }

                            if (isset($_GET['errorModificarConductor'])) {
                                ?>
                                <script>
                                    Swal.fire(
                                        '¡ Ha ocurrido un problema !',
                                        'Error al Modificar al Conductor. Reintente.',
                                        'error'
                                    )
                                </script>
                                <?php
                            }

                            if (isset($_GET['RutConductorExiste'])) {
                                ?>
                                <script>
                                    Swal.fire(
                                        '¡ Ha ocurrido un problema !',
                                        'RUT existente en Sistema. Reintente.',
                                        'error'
                                    )
                                </script>
                                <?php
                            }
                            //AVISOS MODIFICAR

                            //AVISOS ELIMINAR
                            if (isset($_GET['exitoEliminarConductor'])) {
                                ?>
                                <script>
                                    Swal.fire(
                                        'Conductor Eliminado con &Eacute;xito.',
                                        '',
                                        'success'
                                    )
                                </script>
                                <?php
                            }

                            if (isset($_GET['errorEliminarConductor'])) {
                                ?>
                                <script>
                                    Swal.fire(
                                        '¡ Ha ocurrido un problema !',
                                        'Error al Eliminar al Conductor. Reintente.',
                                        'error'
                                    )
                                </script>
                                <?php
                            }
                            //AVISOS ELIMINAR

                            ?>
                            <!-- /.card-header -->
                            <div class="card-body">

                                <div class="table-responsive">
                                    <table id="personalDesktop" class="table table-bordered table-hover">
                                        <thead class="bg-secondary">
                                        <tr>
                                            <th>
                                                <center>N°</center>
                                            </th>
                                            <th>
                                                <center>Nombre<center>
                                            </th>
                                            <th>
                                                <center>RUT<center>
                                            </th>
                                            <th>
                                                <center>Modificar<center>
                                            </th>
                                            <th>
                                                <center>Eliminar<center>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php
                                        $query = "SELECT * FROM conductores_movilizacion WHERE antesConductor > 0  ORDER BY nomConductor ASC";

                                        $res = mysqli_query($conexion, $query);

                                        $numConductor = 1;

                                        while ($row = $res->fetch_assoc()) {
                                            ?>
                                            <tr>
                                                <td style="vertical-align:middle;">
                                                    <center><?php echo $numConductor; ?></center>
                                                </td>
                                                <td style="vertical-align:middle;" align="justify">
                                                    <?php
                                                    $nuevoNom = mb_convert_case($row['nomConductor'], MB_CASE_UPPER, "UTF-8");
                                                    echo $nuevoNom;

                                                    ?>
                                                </td>

                                                <td style="vertical-align:middle;" align="justify">
                                                    <?php
                                                    $nuevoRUT = mb_convert_case($row['rutConductor'], MB_CASE_UPPER, "UTF-8");
                                                    echo $nuevoRUT;

                                                    ?>
                                                </td>

                                                <td style="vertical-align:middle;">
                                                    <center>
                                                        <?php

                                                        $idConductor = $row['idConductor'];
                                                        $idConductor = trim($idConductor);
                                                        if ($idConductor == 0 || $idConductor == "" || $idConductor == " " || $idConductor == "  ") {
                                                            ?>
                                                            <center><?php echo "Error al Cargar Datos."; ?></center>
                                                            <?php
                                                        } else {

                                                            $nomConductor2 = mb_convert_case($row['nomConductor'],MB_CASE_UPPER,'UTF-8');
                                                            $rutConductor2 = mb_convert_case($row['rutConductor'],MB_CASE_UPPER,'UTF-8');
                                                            ?>

                                                            <form method="post" action="../sdo-funciones/conductores-modificar.php">
                                                                <button type="button" data-toggle="modal" data-target="#modalModConductores<?php echo $idConductor; ?>" id="inputMozi" class="btn btn-warning"><i class="fa fa-pen"></i></button>

                                                                <!-- Modal -->
                                                                <div class="modal fade" id="modalModConductores<?php echo $idConductor; ?>" name="modalModConductores<?php echo $idConductor; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-primary">
                                                                                <h5 class="modal-title" id="exampleModalLabel">Modificar Conductor</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>

                                                                            <div class="modal-body">
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Nombre: <code>*</code></label>
                                                                                    <input type="text" class="form-control form-control-border" id="modNombre" name="modNombre" placeholder="Nombre del Conductor" value="<?php echo $nomConductor2; ?>" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                                                                                </div>
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Usuario: <code>*</code></label>
                                                                                    <input style="text-align:left;" type="text" style="text-align:center;" class="form-control form-control-border" id="modRUT" name="modRUT" placeholder="RUT del Conductor (Formato: XXXXXXXXX)" value="<?php echo $rutConductor2; ?>" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                                                                                </div>

                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <input type="hidden" name="antesRUT" id="antesRUT" value="<?php echo $rutConductor2; ?>">
                                                                                <input type="hidden" name="idConductor" id="idConductor" value="<?php echo $idConductor; ?>">
                                                                                <button type="submit" class="btn btn-primary">Modificar</button>
                                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>

                                                            <?php

                                                        }
                                                        ?>


                                                    </center>
                                                </td>

                                                <td style="vertical-align:middle;">
                                                    <center>
                                                        <?php
                                                        $idConductor = $row['idConductor'];
                                                        $idConductor = trim($idConductor);
                                                        if ($idConductor == 0 || $idConductor == "" || $idConductor == " " || $idConductor == "  ") {
                                                            ?>
                                                            <center><?php echo "Error al Cargar Datos."; ?></center>
                                                            <?php
                                                        } else {

                                                            ?>

                                                            <a class=" btn btn-danger eliminar" id="<?php echo $idConductor ?>" href="#"><i class="fas fa-trash-alt"></i></a>

                                                            <?php

                                                        }
                                                        ?>


                                                    </center>
                                                </td>

                                            </tr>
                                            <?php

                                            $numConductor = $numConductor + 1;
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
                                                <center>RUT<center>
                                            </th>
                                            <th>
                                                <center>Modificar<center>
                                            </th>
                                            <th>
                                                <center>Eliminar<center>
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
<script>
    $(function() {
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
                "search": "Buscar:",
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

<script>
    $(document).ready(

        $('.eliminar').click(function(e) {

            e.preventDefault();
            let id = $(this).attr('id');
            var dataString = 'idConductor=' + id;
            Swal.fire({
                icon: 'question',
                title: '¿Desea eliminar este Conductor?',
                text: 'Esta acción no se puede revertir',
                showConfirmButton: true,
                confirmButtonText: 'Eliminar',
                confirmButtonColor: '#3085d6',
                showCancelButton: true,
                cancelButtonText: 'Salir',
                cancelButtonColor: '#d33',
                buttonsStyling: true
            }).then((result) => {

                if (result.isConfirmed) {
                    var formTest = document.createElement('form');
                    formTest.setAttribute("method", "post");
                    formTest.setAttribute("action", "../sdo-funciones/conductores-eliminar.php");

                    var post = document.createElement("input");
                    post.setAttribute("type", "hidden");
                    post.setAttribute("name", "idConductor2");
                    post.setAttribute("value", id);
                    formTest.appendChild(post);

                    document.body.appendChild(formTest);
                    formTest.submit();
                }
            })
        })
    )
</script>
</body>

</html>