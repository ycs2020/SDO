<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['rutActa2'])) {

    $rutActa2 = trim($_POST['rutActa2']);

    if (empty($rutActa2) || $rutActa2 == '') {
        header('Location: ../sdo-jefestecnicos/actas-funcionario.php?errorModificarActa=true');
    } else {
        if (isset($_POST['idActa'])) {

            $idActa = trim($_POST['idActa']);

            if (empty($idActa) || $idActa == '' || $idActa < 1) {
                header('Location: ../sdo-jefestecnicos/actas-funcionario.php?errorModificarActa=true');
            } else {

                $_SESSION['rutActa2'] = $rutActa2;
                $_SESSION['idActa'] = $idActa;

                $datosActa = array();
                $datosActa = getActa($idActa);

                $idActa2 = $datosActa['idActa'];
                $folioActa = $datosActa['folioActa'];
                $fechaActa = $datosActa['fechaActa'];
                $rutTarjeta = $datosActa['rutTarjeta'];
                $codigoTarjeta = $datosActa['codigoTarjeta'];
                $nomTarjeta = $datosActa['nomTarjeta'];
                $unidadTarjeta = $datosActa['unidadTarjeta'];
                $nomSolicitante = $datosActa['nomSolicitante'];
                $nomControlCentralizado = $datosActa['nomControlCentralizado'];

                $codigoTarjeta = mb_convert_case($codigoTarjeta, MB_CASE_UPPER, "UTF-8");
                $unidadTarjeta = mb_convert_case($unidadTarjeta, MB_CASE_TITLE, "UTF-8");
            }
        } else {
            header('Location: ../sdo-jefestecnicos/actas-funcionario.php?errorModificarActa=true');
        }
    }
} else if (isset($_SESSION['rutActa2'])) {

    $rutActa2 = trim($_SESSION['rutActa2']);

    if (empty($rutActa2) || $rutActa2 == '') {
        header('Location: ../sdo-jefestecnicos/actas-funcionario.php?errorModificarActa=true');
    } else {
        if (isset($_SESSION['idActa'])) {

            $idActa = trim($_SESSION['idActa']);

            if (empty($idActa) || $idActa == '' || $idActa < 1) {
                header('Location: ../sdo-jefestecnicos/actas-funcionario.php?errorModificarActa=true');
            } else {
                $datosActa = array();
                $datosActa = getActa($idActa);

                $idActa2 = $datosActa['idActa'];
                $folioActa = $datosActa['folioActa'];
                $fechaActa = $datosActa['fechaActa'];
                $rutTarjeta = $datosActa['rutTarjeta'];
                $codigoTarjeta = $datosActa['codigoTarjeta'];
                $nomTarjeta = $datosActa['nomTarjeta'];
                $unidadTarjeta = $datosActa['unidadTarjeta'];
                $nomSolicitante = $datosActa['nomSolicitante'];
                $nomControlCentralizado = $datosActa['nomControlCentralizado'];

                $codigoTarjeta = mb_convert_case($codigoTarjeta, MB_CASE_UPPER, "UTF-8");
                $unidadTarjeta = mb_convert_case($unidadTarjeta, MB_CASE_TITLE, "UTF-8");
            }
        } else {
            header('Location: ../sdo-jefestecnicos/actas-funcionario.php?errorModificarActa=true');
        }
    }
} else {
    header('Location: ../sdo-jefestecnicos/actas-funcionario.php?errorModificarActa=true');
}


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrar Estacionamiento</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
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
            $(".delete_imagen").click(function() {
                var idParking = $(this).attr('id');
                $.ajax({
                    type: 'POST',
                    url: '../sdo-funciones/estacionamiento-eliminar.php',
                    data: 'idParking=' + idParking,
                    success: function(r) {
                        $('#eliminarParking').html(r);
                        location.reload();
                    }
                });
            });
        });
    </script>

</head>

<body class="hold-transition sidebar-mini dark-mode">
    <div class="wrapper">
        <?php
        include '../sdo-templates/jefes-sidebar.php';
        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Administrar Estacionamiento</h1>
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
                        <!-- left column -->
                        <div class="col-md-12">

                            <!-- general form elements -->
                            <div class="card">
                                <div class="card-header">

                                    <div class="form-group">
                                        <div style="float: right;" class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" id="customSwitch3" onclick="toggleDark()">
                                            <label class="custom-control-label" for="customSwitch3">Modo Claro</label>
                                        </div>
                                    </div>

                                    <script>
                                        function toggleDark() {

                                            document.body.classList.toggle("dark-mode");
                                            var element2 = document.getElementById("hrefInicio");

                                            const color = element2.style.color;

                                            if(color == 'white'){
                                                element2.style.color = 'blue';
                                            }else{
                                                element2.style.color = 'white';
                                            }

                                        }
                                    </script>
                                    <br>
                                    <br>
                                    <a href="actas-funcionario.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <br>
                                    <br>

                                    <h5><b>Datos de la Tarjeta:</b></h5>
                                    <br>
                                    <h5><b>Usuario(a) de la Tarjeta: </b><?php echo $nomTarjeta; ?></h5>
                                    <h5><b>RUT de la Tarjeta: </b><?php echo $rutTarjeta; ?></h5>
                                    <h5><b>N&uacute;mero de la Tarjeta: </b><?php echo $codigoTarjeta; ?></h5>
                                    <h5><b>Unidad de la Tarjeta: </b><?php echo $unidadTarjeta; ?></h5>


                                </div>
                                <?php

                                if (isset($_GET['exitoAgregarPatente'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-check"></i>&nbsp;Parking Agregado con Exito.</h5></div>';
                                }

                                if (isset($_GET['exitoModPatente'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-check"></i>&nbsp;Parking Modificado con Exito.</h5></div>';
                                }

                                if (isset($_GET['errorMaximoParking'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;No es posible registrar m&aacute;s de 2 Parkings por Usuario.</h5></div>';
                                }

                                if (isset($_GET['errorPatenteExiste'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Patente de Vehiculo ya se encuentra registrada. Reintente.</h5></div>';
                                }

                                if (isset($_GET['errorAgregarPatente'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Agregar Parking. Reintente.</h5></div>';
                                }

                                if (isset($_GET['errorModPatente'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Modificar Parking. Reintente.</h5></div>';
                                }


                                ?>
                                <div id="eliminarParking" name="eliminarParking">

                                </div>
                                <!-- /.card-header -->

                                <div class="card-body">
                                    <?php

                                    if (contarPatente($rutTarjeta) > 1) {
                                    } else {
                                    ?>
                                        <form method="post" action="../sdo-funciones/estacionamiento-agregar.php">

                                            <button style="float:right;" class="btn btn-primary" data-toggle="modal" data-target="#modalAddEstacionamiento" type="button"><i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Estacionamiento</button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="modalAddEstacionamiento" name="modalAddEstacionamiento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-primary">
                                                            <h5 class="modal-title" id="exampleModalLabel">Agregar Estacionamiento</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Nombre de Usuario de la Tarjeta: <code>*</code></label>
                                                                <input type="text" readonly="readonly" class="form-control " id="addNombre" name="addNombre" placeholder="Nombre de Usuario de la Tarjeta" value="<?php echo mb_convert_case(obtenerNombreRutCA($rutTarjeta), MB_CASE_TITLE, "UTF-8"); ?>" required>
                                                            </div>
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">RUT de Usuario de la Tarjeta: <code>*</code></label>
                                                                <input style="text-align:left;" readonly="readonly" type="text" style="text-align:center;" class="form-control " id="rutAcceso" name="rutAcceso" value="<?php echo $rutTarjeta; ?>" required>
                                                            </div>
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Patente de Vehiculo: <code>*</code></label>
                                                                <input type="text" class="form-control " id="patenteParking" name="patenteParking" placeholder="Patente de Vehiculo" value="" required>
                                                            </div>
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Modelo del Vehiculo: <code>*</code></label>
                                                                <input style="text-align:left;" type="text" style="text-align:center;" class="form-control " id="modeloParking" name="modeloParking" placeholder="Modelo del Vehiculo" value="" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="hidden" name="idActa" id="idActa" value="<?php echo $idActa; ?>">
                                                            <input type="hidden" name="rutTarjeta" id="rutTarjeta" value="<?php echo $rutTarjeta; ?>">
                                                            <input type="hidden" name="rolUsuario" id="rolUsuario" value="<?php echo $rolUsuario; ?>">
                                                            <button type="submit" class="btn btn-primary">Agregar</button>
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                    <?php
                                    }

                                    ?>

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
                                                        <center>RUT<center>
                                                    </th>
                                                    <th>
                                                        <center>Patente<center>
                                                    </th>
                                                    <th>
                                                        <center>Modelo<center>
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

                                                $contarPatente = contarPatente($rutActa2);

                                                if ($contarPatente > 0) {



                                                    $query = 'SELECT * FROM parking WHERE rutParking = "' . $rutActa2 . '"';

                                                    $res = mysqli_query($conexion, $query);

                                                    $numAcceso = 1;

                                                    $totalAcceso = 0;

                                                    while ($row = $res->fetch_assoc()) {
                                                ?>
                                                        <tr>
                                                            <td style="vertical-align:middle;">
                                                                <center><?php echo $numAcceso; ?></center>
                                                            </td>
                                                            <td style="vertical-align:middle;" align="justify">
                                                                <?php
                                                                $nuevoNom = mb_convert_case(obtenerNombreRutCA($row['rutParking']), MB_CASE_TITLE, "UTF-8");
                                                                echo $nuevoNom;

                                                                ?>
                                                            </td>
                                                            <td style="vertical-align:middle;" align="center">
                                                                <?php
                                                                $rutAcceso = $row['rutParking'];
                                                                echo $rutAcceso;

                                                                ?>
                                                            </td>
                                                            <td style="vertical-align:middle;" align="center">
                                                                <?php
                                                                $patenteParking = $row['patenteParking'];
                                                                echo $patenteParking;

                                                                ?>
                                                            </td>
                                                            <td style="vertical-align:middle;" align="center">
                                                                <?php
                                                                $modeloParking = $row['modeloParking'];
                                                                echo $modeloParking;

                                                                ?>
                                                            </td>
                                                            <td style="vertical-align:middle;">
                                                                <center>
                                                                    <?php
                                                                    $idParking = $row['idParking'];
                                                                    $idParking = trim($idParking);
                                                                    if ($idParking == 0 || $idParking == "" || $idParking == " " || $idParking == "  ") {
                                                                    ?>
                                                                        <center><?php echo "Error al Cargar Datos."; ?></center>
                                                                    <?php
                                                                    } else {

                                                                    ?>

                                                                        <form method="post" action="../sdo-funciones/estacionamiento-modificar.php">
                                                                            <button class="btn btn-warning" data-toggle="modal" data-target="#modalModEstacionamiento<?php echo $numAcceso; ?>" type="button"><i class="fa fa-pen"></i></button>

                                                                            <!-- Modal -->
                                                                            <div class="modal fade" id="modalModEstacionamiento<?php echo $numAcceso; ?>" name="modalModEstacionamiento<?php echo $numAcceso; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header bg-primary">
                                                                                            <h5 class="modal-title" id="exampleModalLabel">Modificar Estacionamiento</h5>
                                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                <span aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>

                                                                                        <div class="modal-body">
                                                                                            <div style="text-align:left;" class="form-group">
                                                                                                <label for="exampleInputBorder">Nombre de Usuario de la Tarjeta: <code>*</code></label>
                                                                                                <input type="text" readonly="readonly" class="form-control " id="addNombre" name="addNombre" placeholder="Nombre de Usuario de la Tarjeta" value="<?php echo mb_convert_case(obtenerNombreRutCA($row['rutParking']), MB_CASE_TITLE, "UTF-8"); ?>" required>
                                                                                            </div>
                                                                                            <div style="text-align:left;" class="form-group">
                                                                                                <label for="exampleInputBorder">RUT de Usuario de la Tarjeta: <code>*</code></label>
                                                                                                <input style="text-align:left;" readonly="readonly" type="text" style="text-align:center;" class="form-control " id="rutAcceso" name="rutAcceso" value="<?php echo $row['rutParking']; ?>" required>
                                                                                            </div>
                                                                                            <div style="text-align:left;" class="form-group">
                                                                                                <label for="exampleInputBorder">Patente de Vehiculo: <code>*</code></label>
                                                                                                <input type="text" class="form-control " id="patenteParking" name="patenteParking" placeholder="Patente de Vehiculo" value="<?php echo $row['patenteParking']; ?>" required>
                                                                                            </div>
                                                                                            <div style="text-align:left;" class="form-group">
                                                                                                <label for="exampleInputBorder">Modelo del Vehiculo: <code>*</code></label>
                                                                                                <input style="text-align:left;" type="text" style="text-align:center;" class="form-control " id="modeloParking" name="modeloParking" placeholder="Modelo del Vehiculo" value="<?php echo $row['modeloParking']; ?>" required>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <input type="hidden" name="antiguaPatente" id="antiguaPatente" value="<?php echo $row['patenteParking']; ?>">
                                                                                            <input type="hidden" name="idParking" id="idParking" value="<?php echo $row['idParking']; ?>">
                                                                                            <input type="hidden" name="rutActa2" id="rutActa2" value="<?php echo $rutActa2; ?>">
                                                                                            <input type="hidden" name="idActa" id="idActa" value="<?php echo $idActa; ?>">
                                                                                            <input type="hidden" name="rolUsuario" id="rolUsuario" value="<?php echo $rolUsuario; ?>">
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
                                                                    $idParking = $row['idParking'];
                                                                    $idParking = trim($idParking);
                                                                    if ($idParking == 0 || $idParking == "" || $idParking == " " || $idParking == "  ") {
                                                                    ?>
                                                                        <center><?php echo "Error al Cargar Datos."; ?></center>
                                                                    <?php
                                                                    } else {

                                                                    ?>

                                                                        <button class="btn btn-danger delete_imagen" type="button" name="<?php echo $idParking; ?>" id="<?php echo $idParking ?>"><i class="fa fa-trash"></i></button>

                                                                    <?php

                                                                    }
                                                                    ?>


                                                                </center>
                                                            </td>

                                                        </tr>
                                                    <?php

                                                        $numAcceso = $numAcceso + 1;
                                                        $totalAcceso = $totalAcceso + 1;
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="7" align="center">
                                                            No Existen Registros...
                                                        </td>
                                                    </tr>
                                                <?php
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
                                                        <center>Patente<center>
                                                    </th>
                                                    <th>
                                                        <center>Modelo<center>
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

                                    <hr>





                                    <div class="form-group">
                                        <a href="actas-funcionario.php" class="btn btn-block btn-warning"> Volver </a>
                                    </div>

                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->

                        </div>
                        <!--/.col  -->

                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
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
    <!-- bs-custom-file-input -->
    <script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>
    <!-- Page specific script -->
    <script>
        $(function() {
            bsCustomFileInput.init();
        });
    </script>

</body>

</html>