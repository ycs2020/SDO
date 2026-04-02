<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Profesionales de Departamento</title>

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
    <script type="text/javascript">
        $(document).ready(function() {
            recargarSubDepartamento();

            $('#departamentoAgregar').change(function() {
                recargarSubDepartamento();
            });

            function recargarSubDepartamento() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-departamentos/mostrarSubDeptoJefes.php",
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
                            <h4>Listado de Profesionales del Departamento</h4>
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
                                    <span>
                                        <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                        <br>
                                        <br>
                                        <form method="post" action="../sdo-funciones/profesionales-agregar.php">
                                            <button type="button" data-toggle="modal" data-target="#modalAddJefes" id="inputMozi" class="btn btn-primary" style="float:right;"><i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Profesionales de Departamento</button>
                                            <!-- Modal -->
                                            <div class="modal fade" id="modalAddJefes" name="modalAddJefes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-primary">
                                                            <h5 class="modal-title" id="exampleModalLabel">Agregar Profesional de Departamento</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Nombre: <code>*</code></label>
                                                                <input type="text" class="form-control form-control-border" id="addNombre" name="addNombre" placeholder="Nombre de Profesional de Departamento" value="" required>
                                                            </div>
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Usuario: <code>*</code></label>
                                                                <input style="text-align:left;" type="text" style="text-align:center;" class="form-control form-control-border" id="addUser" name="addUser" placeholder="Usuario de Profesional de Departamento" value="" required>
                                                            </div>
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Contrase&ntilde;a: <code>*</code></label>
                                                                <input style="text-align:left;" type="text" style="text-align:center;" class="form-control form-control-border" id="addPassword" name="addPassword" placeholder="Contrase&ntilde;a de Profesional de Departamento" value="" required>
                                                            </div>
                                                             <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Email: <code>*</code></label>
                                                                <input style="text-align:left;" type="email" class="form-control form-control-border" id="addEmail" name="addEmail" placeholder="Email del Profesional" value="" required>
                                                            </div>
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Departamento: <code>*</code></label>
                                                                <select class="custom-select" name="departamentoAgregar" id="departamentoAgregar">
                                                                    <?php

                                                                    $queryDepartamentos = "SELECT * FROM departamentos ORDER BY nomDepartamento ASC";

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



                                                            <div name="visualizarSubDepartamento" id="visualizarSubDepartamento" class="form-group">

                                                            </div>
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Sexo: <code>*</code></label>
                                                                <select class="custom-select" name="sexoAgregar" id="sexoAgregar">
                                                                    <option value="f"><?php echo 'Femenino'?></option>
                                                                    <option value="m"><?php echo 'Masculino'?></option>

                                                                </select>
                                                            </div>
                                                            <div style="text-align:left;" class="form-group">
                                                                <input style="text-align:left;" type="hidden" class="form-control form-control-border" id="addCargo" name="addCargo" placeholder="Cargo" value="<?php echo 'Profesional del Departamento'?>" required>

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
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <?php

                                    //AVISOS AGREGAR
                                    if (isset($_GET['exitoAgregarProfesional'])) {
                                    ?>
                                        <script>
                                            Swal.fire(
                                                'Profesional Creado con &Eacute;xito.',
                                                '',
                                                'success'
                                            )
                                        </script>
                                    <?php
                                    }

                                    if (isset($_GET['errorAgregarProfesional'])) {
                                    ?>
                                        <script>
                                            Swal.fire(
                                                'Error al Agregar al Profesional. Reintente.',
                                                '',
                                                'error'
                                            )
                                        </script>
                                    <?php
                                    }

                                    if (isset($_GET['existeUsuario'])) {
                                    ?>
                                        <script>
                                            Swal.fire(
                                                'El Nombre de Usuario ya se encuentra registrado. Reintente.',
                                                '',
                                                'error'
                                            )
                                        </script>
                                    <?php
                                    }
                                    //AVISOS AGREGAR

                                    //AVISOS MODIFICAR
                                    if (isset($_GET['exitoModificarProfesional'])) {
                                    ?>
                                        <script>
                                            Swal.fire(
                                                'Profesional Modificado con &Eacute;xito.',
                                                '',
                                                'success'
                                            )
                                        </script>
                                    <?php
                                    }

                                    if (isset($_GET['errorModificarProfesional'])) {
                                    ?>
                                        <script>
                                            Swal.fire(
                                                'Error al Modificar al Profesional. Reintente.',
                                                '',
                                                'error'
                                            )
                                        </script>
                                    <?php
                                    }

                                    if (isset($_GET['errorSeleccionarModificarProfesional'])) {
                                    ?>
                                        <script>
                                            Swal.fire(
                                                'Error al Seleccionar el Profesional a Modificar. Reintente.',
                                                '',
                                                'error'
                                            )
                                        </script>
                                    <?php
                                    }
                                    //AVISOS MODIFICAR

                                    //AVISOS ELIMINAR
                                    if (isset($_GET['exitoEliminarProfesional'])) {
                                    ?>
                                        <script>
                                            Swal.fire(
                                                'Profesional Eliminado con &Eacute;xito.',
                                                '',
                                                'success'
                                            )
                                        </script>
                                    <?php
                                    }

                                    if (isset($_GET['errorEliminarProfesional'])) {
                                    ?>
                                        <script>
                                            Swal.fire(
                                                'Error al Eliminar al Profesional. Reintente.',
                                                '',
                                                'error'
                                            )
                                        </script>
                                    <?php
                                    }

                                    if (isset($_GET['errorSeleccionarEliminarProfesional'])) {
                                    ?>
                                        <script>
                                            Swal.fire(
                                                'Error al Seleccionar el Profesional a Eliminar. Reintente.',
                                                '',
                                                'error'
                                            )
                                        </script>
                                    <?php
                                    }
                                    //AVISOS ELIMINAR

                                    ?>



                                    <fieldset class="border p-3">
                                        <legend class="w-auto">Profesionales de Departamento:</legend>

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
                                                            <center>Usuario<center>
                                                        </th>
                                                        <th>
                                                            <center>Contrase&ntilde;a<center>
                                                        </th>
                                                        <th>
                                                            <center>Departamento<center>
                                                        </th>
                                                        <th>
                                                            <center>Cargo<center>
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
                                                    $query = 'SELECT * FROM jefes_tecnicos WHERE cargoJefe LIKE "%Profesional%" ORDER BY idDepartamento, cargoJefe ASC';

                                                    $res = mysqli_query($conexion, $query);

                                                    $numJefe = 1;

                                                    while ($row = $res->fetch_assoc()) {
                                                    ?>
                                                        <tr>
                                                            <td style="vertical-align:middle;">
                                                                <center><?php echo $numJefe; ?></center>
                                                            </td>
                                                            <td style="vertical-align:middle;" align="justify">
                                                                <?php
                                                                $nomJefe = mb_convert_case($row['nomJefe'], MB_CASE_TITLE, "UTF-8");
                                                                echo $nomJefe;

                                                                ?>
                                                            </td>
                                                            <td style="vertical-align:middle;" align="center">
                                                                <?php
                                                                $userJefe = $row['userJefe'];
                                                                echo $userJefe;

                                                                ?>
                                                            </td>
                                                            <td style="vertical-align:middle;" align="center">
                                                                <?php
                                                                $passJefe = $row['passJefe'];
                                                                echo $passJefe;

                                                                ?>
                                                            </td>
                                                            <td style="vertical-align:middle;">
                                                                <?php
                                                                $departamentoJefe = getNomDepartamento($row['idDepartamento']);

                                                                $subDepartamentoJefe = '';

                                                                if ($row['idSubdepartamento'] > 0) {
                                                                    $subDepartamentoJefe = " (" . getNomSubDepartamento($row['idSubdepartamento']) . ")";
                                                                }

                                                                echo $departamentoJefe . $subDepartamentoJefe;

                                                                ?>
                                                            </td>
                                                            <td style="vertical-align:middle;">
                                                                <?php
                                                                $cargoJefe = trim($row['cargoJefe']);

                                                                echo $cargoJefe;

                                                                ?>
                                                            </td>
                                                            <td style="vertical-align:middle;">
                                                                <center>
                                                                    <?php
                                                                    $idJefe = $row['idJefe'];
                                                                    $idJefe = trim($idJefe);
                                                                    if ($idJefe == 0 || $idJefe == "" || $idJefe == " " || $idJefe == "  ") {
                                                                    ?>
                                                                        <center><?php echo "Error al Cargar Datos."; ?></center>
                                                                    <?php
                                                                    } else {

                                                                    ?>

                                                                        <form style="margin: 0; text-align: center;" action="../sdo-funciones/profesionales-modificar.php" method="post">

                                                                            <input type="hidden" name="idJefe" value="<?php echo $idJefe; ?>">
                                                                            <button type="button" data-toggle="modal" data-target="#modalDJefe<?php echo $idJefe; ?>" id="inputMozi" class="btn btn-block btn-warning"><i class="fa fa-pen"></i></button>

                                                                            <!-- Modal -->
                                                                            <div class="modal fade" id="modalDJefe<?php echo $idJefe; ?>" name="modalDJefe<?php echo $idJefe; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header bg-primary">
                                                                                            <h5 class="modal-title" id="exampleModalLabel">Jefe: <?php echo $nomJefe; ?></h5>
                                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                <span aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <?php

                                                                                        $datosPersonalMobile = array();
                                                                                        $datosPersonalMobile = getDatosJefe($idJefe);

                                                                                        $nomJefe = mb_convert_case($row['nomJefe'], MB_CASE_TITLE, "UTF-8");
                                                                                        $userJefe = $row['userJefe'];
                                                                                        $passJefe = $row['passJefe'];
                                                                                        $idDepartamento = $row['idDepartamento'];
                                                                                        $idSubdepartamento = $row['idSubdepartamento'];
                                                                                        $emailJefe = $row['emailJefe'];


                                                                                        ?>
                                                                                        <div class="modal-body">
                                                                                            <div style="text-align:left;" class="form-group">
                                                                                                <label for="exampleInputBorder">Nombre: <code>*</code></label>
                                                                                                <input type="text" class="form-control form-control-border" id="modNombre" name="modNombre" placeholder="Nombre de Profesional de Departamento" value="<?php echo $nomJefe; ?>" required>
                                                                                            </div>
                                                                                            <div style="text-align:left;" class="form-group">
                                                                                                <label for="exampleInputBorder">Usuario: <code>*</code></label>
                                                                                                <input style="text-align:left;" type="text" style="text-align:center;" class="form-control form-control-border" id="modUser" name="modUser" placeholder="Usuario de Profesional de Departamento" value="<?php echo $userJefe; ?>" required>
                                                                                            </div>
                                                                                            <div style="text-align:left;" class="form-group">
                                                                                                <label for="exampleInputBorder">Contrase&ntilde;a: <code>*</code></label>
                                                                                                <input style="text-align:left;" type="text" style="text-align:center;" class="form-control form-control-border" id="modPassword" name="modPassword" placeholder="Contrase&ntilde;a de Profesional de Departamento" value="<?php echo $passJefe; ?>" required>
                                                                                            </div>
                                                                                            <div style="text-align:left;" class="form-group">
                                                                                                <label for="exampleInputBorder">Departamento: <code>*</code></label>
                                                                                                <select class="custom-select" name="departamentoModificar" id="departamentoModificar">
                                                                                                    <?php

                                                                                                    $queryDepartamentos = "SELECT * FROM departamentos ORDER BY nomDepartamento ASC";

                                                                                                    $resDepartamentos = mysqli_query($conexion, $queryDepartamentos);

                                                                                                    while ($rowDepartamentos = $resDepartamentos->fetch_assoc()) {

                                                                                                        if ($rowDepartamentos['idDepartamento'] == $idDepartamento) {
                                                                                                    ?>
                                                                                                            <option value="<?php echo $rowDepartamentos['idDepartamento']; ?>" selected="selected">
                                                                                                                <?php echo $rowDepartamentos['nomDepartamento']; ?>
                                                                                                            </option>
                                                                                                        <?php
                                                                                                        } else {
                                                                                                        ?>
                                                                                                            <option value="<?php echo $rowDepartamentos['idDepartamento']; ?>">
                                                                                                                <?php echo $rowDepartamentos['nomDepartamento']; ?>
                                                                                                            </option>
                                                                                                    <?php
                                                                                                        }
                                                                                                    }


                                                                                                    ?>
                                                                                                </select>
                                                                                                <input type="hidden" name="idSubDepartamento2" id="idSubDepartamento2" value="<?php echo $idSubdepartamento; ?>">
                                                                                            </div>
                                                                                            <div style="text-align:left;" class="form-group">
                                                                                                <label for="exampleInputBorder">Email:<code>*</code></label>
                                                                                                <input style="text-align:left;" type="text" style="text-align:center;" class="form-control form-control-border" id="modEmail" name="modEmail"  value="<?php echo $emailJefe; ?>" required>

                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <input type="hidden" name="idJefe" id="idJefe" value="<?php echo $idJefe; ?>">
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
                                                                    $idJefe = $row['idJefe'];
                                                                    $idJefe = trim($idJefe);
                                                                    if ($idJefe == 0 || $idJefe == "" || $idJefe == " " || $idJefe == "  ") {
                                                                    ?>
                                                                        <center><?php echo "Error al Cargar Datos."; ?></center>
                                                                    <?php
                                                                    } else {

                                                                    ?>

                                                                        <form style="margin: 0; text-align: center;" class="" action="../sdo-funciones/profesionales-eliminar.php" method="post">

                                                                            <input type="hidden" name="idJefe" value="<?php echo $idJefe; ?>">
                                                                            <button type="submit" id="inputMozi" class="btn btn-block btn-danger"><i class="fa fa-trash"></i></button>

                                                                        </form>

                                                                    <?php

                                                                    }
                                                                    ?>


                                                                </center>
                                                            </td>

                                                        </tr>
                                                    <?php

                                                        $numJefe = $numJefe + 1;
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
                                                            <center>Usuario<center>
                                                        </th>
                                                        <th>
                                                            <center>Contrase&ntilde;a<center>
                                                        </th>
                                                        <th>
                                                            <center>Departamento<center>
                                                        </th>
                                                        <th>
                                                            <center>Cargo<center>
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
                                    </fieldset>


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
    <script src="js/dark-mode.js"></script>
</body>

</html>