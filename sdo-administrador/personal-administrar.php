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
                    url: "sdo-datos-departamentos/mostrarSubDepartamentos.php",
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
                            <h4>Listado de Personal</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                            </ol>
                            <br>
                            <br>
                     
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
                                        <form method="post" action="../sdo-funciones/personal-agregar.php">
                                            <button type="button" data-toggle="modal" data-target="#modalAddPersonal" id="inputMozi" class="btn btn-primary" style="float:right;"><i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Personal</button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="modalAddPersonal" name="modalAddPersonal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-primary">
                                                            <h5 class="modal-title" id="exampleModalLabel">Agregar Personal</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Nombre: <code>*</code></label>
                                                                <input type="text" class="form-control form-control-border" id="addNombre" name="addNombre" placeholder="Nombre de Personal" value="" required>
                                                            </div>
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Usuario: <code>*</code></label>
                                                                <input style="text-align:left;" type="text" style="text-align:center;" class="form-control form-control-border" id="addUser" name="addUser" placeholder="Usuario de Personal" value="" required>
                                                            </div>
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Contrase&ntilde;a: <code>*</code></label>
                                                                <input style="text-align:left;" type="text" style="text-align:center;" class="form-control form-control-border" id="addPassword" name="addPassword" placeholder="Contrase&ntilde;a de Personal" value="" required>
                                                            </div>
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Email: <code>*</code></label>
                                                                <input style="text-align:left;" type="email" style="text-align:center;" class="form-control form-control-border" id="addCorreo" name="addCorreo" placeholder="Email del Personal" value="" required>
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
                                                            <!-- <div name="visualizarSubDepartamento" id="visualizarSubDepartamento" class="form-group">

                                                            </div> -->
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Cargo: <code>*</code></label>
                                                                <select class="custom-select" name="cargoAgregar" id="cargoAgregar">
                                                                    <option value="Técnico del Departamento">
                                                                        <?php
                                                                            echo 'Técnico del Departamento';
                                                                        ?>
                                                                    </option>
                                                                    <option value="Auxiliar del Departamento">
                                                                        <?php
                                                                        echo 'Auxiliar del Departamento';
                                                                        ?>
                                                                    </option>
                                                                    <?php
                                                                    /*
                                                                    $queryDepartamentos = "SELECT * FROM departamentos ORDER BY nomDepartamento ASC";

                                                                    $resDepartamentos = mysqli_query($conexion, $queryDepartamentos);

                                                                    while ($rowDepartamentos = $resDepartamentos->fetch_assoc()) {

                                                                        ?>
                                                                        <option value="<?php echo $rowDepartamentos['idDepartamento']; ?>">
                                                                            <?php echo $rowDepartamentos['nomDepartamento']; ?>
                                                                        </option>
                                                                        <?php

                                                                    }

                                                                    */
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Sexo: <code>*</code></label>
                                                                 <select class="custom-select" name="addSexo" id="addSexo">
                                                                        <option value="f">
                                                                            <?php
                                                                            echo 'Femenino';
                                                                            ?>
                                                                        </option>
                                                                        <option value="m">
                                                                            <?php
                                                                            echo 'Masculino';
                                                                            ?>
                                                                        </option>
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
                                <!-- /.card-header -->
                                <div class="card-body">
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


                                    <div>
                                        <form action="../sdo-funciones/personal-modificar.php" method="post">
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
                                                                <center>Estado<center>
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
                                                        $query = 'SELECT * FROM personal ORDER BY  nomPersonal ASC';

                                                        $res = mysqli_query($conexion, $query);

                                                        $numReporte = 1;

                                                        while ($row = $res->fetch_assoc()) {
                                                        ?>
                                                            <tr>
                                                                <td style="vertical-align:middle;">
                                                                    <center><?php echo $numReporte; ?></center>
                                                                </td>
                                                                <td style="vertical-align:middle;" align="justify">
                                                                    <?php
                                                                    $nuevoNom = mb_convert_case($row['nomPersonal'], MB_CASE_TITLE, "UTF-8");
                                                                    echo $nuevoNom;

                                                                    ?>
                                                                </td>
                                                                <td style="vertical-align:middle;" align="center">
                                                                    <?php
                                                                    $userPersonal = $row['userPersonal'];
                                                                    echo $userPersonal;

                                                                    ?>
                                                                </td>
                                                                <td style="vertical-align:middle;" align="center">
                                                                    <?php
                                                                    $passPersonal = $row['passPersonal'];
                                                                    echo $passPersonal;

                                                                    ?>
                                                                </td>
                                                                <td style="vertical-align:middle;">
                                                                    <?php
                                                                    $departamentoPersonal = getNomDepartamento($row['departamentoPersonal']);
                                                                    echo $departamentoPersonal;

                                                                    ?>
                                                                </td>
                                                                <td style="vertical-align:middle;">
                                                                    <center>
                                                                        <?php
                                                                        $activoPersonal = trim($row['activoPersonal']);

                                                                        if ($activoPersonal == 0) {
                                                                            echo "Pendiente";
                                                                        } else if ($row['activoPersonal'] == 1) {
                                                                            echo "Activo";
                                                                        } else {
                                                                            echo "Error";
                                                                        }
                                                                        ?>
                                                                    </center>
                                                                </td>
                                                                <td style="vertical-align:middle;">
                                                                    <center>
                                                                        <?php
                                                                        $idPersonal = $row['idPersonal'];
                                                                        $idPersonal = trim($idPersonal);
                                                                        if ($idPersonal == 0 || $idPersonal == "" || $idPersonal == " " || $idPersonal == "  ") {
                                                                        ?>
                                                                            <center><?php echo "Error al Cargar Datos."; ?></center>
                                                                        <?php
                                                                        } else {

                                                                        ?>

                                                                            <form style="margin: 0; text-align: center;" action="../sdo-funciones/personal-modificar.php" method="post">

                                                                                <input type="hidden" name="idPersonal" value="<?php echo $idPersonal; ?>">
                                                                                <button type="button" data-toggle="modal" data-target="#modalDPersonal<?php echo $idPersonal; ?>" id="inputMozi" class="btn btn-block btn-warning"><i class="fa fa-pen"></i></button>

                                                                                <!-- Modal -->
                                                                                <div class="modal fade" id="modalDPersonal<?php echo $idPersonal; ?>" name="modalDPersonal<?php echo $idPersonal; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                    <div class="modal-dialog" role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header bg-primary">
                                                                                                <h5 class="modal-title" id="exampleModalLabel">Personal: <?php echo $nuevoNom; ?></h5>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <?php

                                                                                            $datosPersonalMobile = array();
                                                                                            $datosPersonalMobile = getDatosPersonal($idPersonal);

                                                                                            $nomPersonal = mb_convert_case($row['nomPersonal'], MB_CASE_TITLE, "UTF-8");
                                                                                            $userPersonal = $row['userPersonal'];
                                                                                            $passPersonal = $row['passPersonal'];
                                                                                            $emailPersonal = $row['emailPersonal'];
                                                                                            $activoPersonal = $row['activoPersonal'];
                                                                                            $modDepartamentoPersonal = $row['departamentoPersonal'];
                                                                                            $modSubDepartamento = $row['idSubDepartamento'];
                                                                                            $nacimiento = $row['fechaNacimiento'];
                                                                                            $sexo = $row['sexo'];

                                                                                            ?>
                                                                                            <div class="modal-body">
                                                                                                <div style="text-align:left;" class="form-group">
                                                                                                    <label for="exampleInputBorder">Nombre: <code>*</code></label>
                                                                                                    <input type="text" class="form-control form-control-border" id="modNombre" name="modNombre" placeholder="Nombre de Personal" value="<?php echo $nomPersonal; ?>" required>
                                                                                                </div>
                                                                                                <div style="text-align:left;" class="form-group">
                                                                                                    <label for="exampleInputBorder">Usuario: <code>*</code></label>
                                                                                                    <input style="text-align:left;" type="text" style="text-align:center;" class="form-control form-control-border" id="modUser" name="modUser" placeholder="Usuario de Personal" value="<?php echo $userPersonal; ?>" required>
                                                                                                </div>
                                                                                                <div style="text-align:left;" class="form-group">
                                                                                                    <label for="exampleInputBorder">Contrase&ntilde;a: <code>*</code></label>
                                                                                                    <input style="text-align:left;" type="text" style="text-align:center;" class="form-control form-control-border" id="modPassword" name="modPassword" placeholder="Contrase&ntilde;a de Personal" value="<?php echo $passPersonal; ?>" required>
                                                                                                </div>
                                                                                                <div style="text-align:left;" class="form-group">
                                                                                                    <label for="exampleInputBorder">Estado: <code>*</code></label>
                                                                                                    <?php
                                                                                                    if ($activoPersonal) {
                                                                                                    ?>
                                                                                                        <div class="form-check">
                                                                                                            <input class="form-check-input" type="radio" value="false" name="modEstado">
                                                                                                            <label class="form-check-label">Pendiente</label>
                                                                                                        </div>
                                                                                                        <div class="form-check">
                                                                                                            <input checked class="form-check-input" type="radio" value="true" name="modEstado">
                                                                                                            <label class="form-check-label">Activo</label>
                                                                                                        </div>
                                                                                                    <?php
                                                                                                    } else {
                                                                                                    ?>
                                                                                                        <div class="form-check">
                                                                                                            <input checked class="form-check-input" type="radio" value="false" name="modEstado">
                                                                                                            <label class="form-check-label">Pendiente</label>
                                                                                                        </div>
                                                                                                        <div class="form-check">
                                                                                                            <input class="form-check-input" type="radio" value="true" name="modEstado">
                                                                                                            <label class="form-check-label">Activo</label>
                                                                                                        </div>
                                                                                                    <?php
                                                                                                    }
                                                                                                    ?>

                                                                                                </div>
                                                                                                <div style="text-align:left;" class="form-group">
                                                                                                    <label for="exampleInputBorder">Email: <code>*</code></label>
                                                                                                    <input style="text-align:left;" type="text" style="text-align:center;" class="form-control form-control-border" id="modEmail" name="modEmail" value="<?php echo $emailPersonal; ?>" required>
                                                                                                </div>

                                                                                                <div style="text-align:left;" class="form-group">
                                                                                                    <label for="exampleInputBorder">Departamento: <code>*</code></label>
                                                                                                    <select class="custom-select" name="departamentoModificar" id="departamentoModificar">
                                                                                                        <?php

                                                                                                        $queryDepartamentos = "SELECT * FROM departamentos ORDER BY nomDepartamento ASC";

                                                                                                        $resDepartamentos = mysqli_query($conexion, $queryDepartamentos);

                                                                                                        while ($rowDepartamentos = $resDepartamentos->fetch_assoc()) {

                                                                                                            if ($rowDepartamentos['idDepartamento'] == $modDepartamentoPersonal) {
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

                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="modal-footer">
                                                                                                <input type="hidden" name="idPersonal" id="idPersonal" value="<?php echo $idPersonal; ?>">
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
                                                                        $idPersonal = $row['idPersonal'];
                                                                        $idPersonal = trim($idPersonal);
                                                                        if ($idPersonal == 0 || $idPersonal == "" || $idPersonal == " " || $idPersonal == "  ") {
                                                                        ?>
                                                                            <center><?php echo "Error al Cargar Datos."; ?></center>
                                                                        <?php
                                                                        } else {

                                                                        ?>

                                                                            <form style="margin: 0; text-align: center;" class="" action="../sdo-funciones/personal-eliminar.php" method="post">

                                                                                <input type="hidden" name="idPersonal" value="<?php echo $idPersonal; ?>">
                                                                                <button type="submit" id="inputMozi" class="btn btn-block btn-danger"><i class="fa fa-trash"></i></button>

                                                                            </form>

                                                                        <?php

                                                                        }
                                                                        ?>


                                                                    </center>
                                                                </td>

                                                            </tr>
                                                        <?php

                                                            $numReporte = $numReporte + 1;
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
                                                                <center>Estado<center>
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
                                        </form>
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