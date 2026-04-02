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
    <title>Listado de Tecnicos</title>

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

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            recargarSubDepartamento();

            $('#departamentoAgregar').change(function() {
                recargarSubDepartamento();
            });


            function recargarSubDepartamento() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-departamentos/subdepartamento-agregar.php",
                    data: "idDepartamento=" + $('#departamentoAgregar').val(),
                    success: function(r) {
                        $('#visualizarSubDepartamento').html(r);


                    }
                });

            };
        });
    </script>

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
                            <h4>Listado de Tecnicos</h4>
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
                                    <span>
                                        <form method="post" action="../sdo-funciones/tecnicos-agregar.php">
                                            <button type="button" data-toggle="modal" data-target="#modalAddTecnico" id="inputMozi" class="btn btn-primary" style="float:right;"><i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Tecnico</button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="modalAddTecnico" name="modalAddTecnico" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-primary">
                                                            <h5 class="modal-title" id="exampleModalLabel">Agregar Tecnico</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Nombre: <code>*</code></label>
                                                                <input type="text" class="form-control form-control-border" id="addNombre" name="addNombre" placeholder="Nombre del Tecnico" value="" required>
                                                            </div>
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Usuario: <code>*</code></label>
                                                                <input style="text-align:left;" type="text" style="text-align:center;" class="form-control form-control-border" id="addUser" name="addUser" placeholder="Usuario del Tecnico" value="" required>
                                                            </div>
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Contrase&ntilde;a: <code>*</code></label>
                                                                <input style="text-align:left;" type="text" style="text-align:center;" class="form-control form-control-border" id="addPassword" name="addPassword" placeholder="Contrase&ntilde;a del Tecnico" value="" required>
                                                            </div>

                                                             <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Email: <code>*</code></label>
                                                                <input style="text-align:left;" type="text" style="text-align:center;" class="form-control form-control-border" id="addEmail" name="addEmail" placeholder="Email del Técnico" value="" required>
                                                            </div>
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Sexo: <code>*</code></label>
                                                                <select class="custom-select" name="addSexo" id="addSexo">
                                                                    <option value="f"><?php echo 'Femenino'?></option>
                                                                    <option value="m"><?php echo 'Masculino'?></option>
                                                                </select>
                                                            </div>
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Departamento: <code>*</code></label>
                                                                <select class="custom-select" name="departamentoAgregar" id="departamentoAgregar">
                                                                    <?php

                                                                    $queryDepartamentos = "SELECT * FROM departamentos WHERE idDepartamento = '" . $idDepartamento . "' ORDER BY nomDepartamento ASC";

                                                                    $resDepartamentos = mysqli_query($conexion, $queryDepartamentos);

                                                                    while ($rowDepartamentos = $resDepartamentos->fetch_assoc()) {

                                                                    ?>
                                                                        <option value="<?php echo $rowDepartamentos['idDepartamento']; ?>">
                                                                            <?php echo $rowDepartamentos['nomDepartamento']; ?>
                                                                        </option>
                                                                    <?php

                                                                    }


                                                                    ?>
                                                                </select>
                                                            </div>

                                                            <div style="text-align:left;" class="form-group" name="visualizarSubDepartamento" id="visualizarSubDepartamento">

                                                            </div>
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Cargo: <code>*</code></label>
                                                                <select class="custom-select" name="cargoAgregar" id="cargoAgregar">
                                                                    <option value="Técnico del Departamento"><?php echo 'Técnico del Departamento'?></option>
                                                                    <option value="Auxiliar del Departamento"><?php echo 'Auxiliar del Departamento'?></option>
                                                                </select>
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
                                if (isset($_GET['exitoAgregarPersonal'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;Personal Creado con &Eacute;xito.</h5></div>';
                                }

                                if (isset($_GET['errorAgregarPersonal'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Agregar al Personal. Reintente.</h5></div>';
                                }

                                if (isset($_GET['existeUsuario'])) {
                                    echo '<div class="alert alert-warning alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5 style="vertical-align: middle;"><i class="fa fa-exclamation-triangle"></i>&nbsp;El Nombre de Usuario ya se encuentra registrado. Reintente.</h5></div>';
                                }
                                //AVISOS AGREGAR

                                //AVISOS MODIFICAR
                                if (isset($_GET['exitoModificarPersonal'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;Personal Modificado con &Eacute;xito.</h5></div>';
                                }

                                if (isset($_GET['errorModificarPersonal'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Modificar al Personal. Reintente.</h5></div>';
                                }

                                if (isset($_GET['errorSeleccionarModificarPersonal'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Seleccionar el Personal a Modificar. Reintente.</h5></div>';
                                }
                                //AVISOS MODIFICAR

                                //AVISOS ELIMINAR
                                if (isset($_GET['exitoEliminarPersonal'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;Personal Eliminado con &Eacute;xito.</h5></div>';
                                }

                                if (isset($_GET['errorEliminarPersonal'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Eliminar al Personal. Reintente.</h5></div>';
                                }

                                if (isset($_GET['errorSeleccionarEliminarPersonal'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Seleccionar el Personal a Eliminar. Reintente.</h5></div>';
                                }
                                //AVISOS ELIMINAR

                                ?>
                                <!-- /.card-header -->
                                <div class="card-body">

                                    <div class="row">

                                        <?php
                                        $query = "SELECT * FROM personal WHERE departamentoPersonal = '" . $idDepartamento . "' AND activoPersonal = 1  ORDER BY nomPersonal, departamentoPersonal ASC";

                                        $res = mysqli_query($conexion, $query);

                                        $numReporte = 1;

                                        while ($row = $res->fetch_assoc()) {
                                        ?>
                                            <?php
                                            $nuevoNom = mb_convert_case($row['nomPersonal'], MB_CASE_TITLE, "UTF-8");
                                            $departamentoPersonal = getNomDepartamento($row['departamentoPersonal']);

                                            if ($departamentoJefe == "Equipos Industriales" || $departamentoJefe == "equipos industriales") {
                                                $nomSubdepartamento = getNomSubDepartamento($row['idSubdepartamento']);
                                            }

                                            ?>

                                            <div class="col-lg-3 col-md-6 col-12 p-2">
                                                <!-- small box -->
                                                <a href="#" style="pointer-events: none; cursor: default;" class="btn btn-block bg-gradient-primary btn-lg">
                                                    <i class="nav-icon fas fa-user"></i>
                                                    <br>
                                                    <?php
                                                    echo $nuevoNom.'<br>'.$departamentoPersonal;
                                                    ?>
                                                </a>

                                            </div>

                                        <?php

                                            $numReporte = $numReporte + 1;
                                        }
                                        ?>


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
</body>

</html>