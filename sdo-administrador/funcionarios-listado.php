<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$nomUsuario = trim($_SESSION['nomUsuario']);
$idDepartamento = $_SESSION['idDepartamento'];
$nomDepartamento = getNomDepartamento($idDepartamento);
$idSubDepartamento = $_SESSION['idSubDepartamento'];


date_default_timezone_set('America/Santiago');


// $fecha = date('d-m-Y h:i a');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Funcionarios SDO</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <!-- DataTables -->
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>
    <style>
        .zoom-card {
            transition: transform 0.3s;
        }

        .zoom-card:hover {
            transform: scale(1.1);
        }

        .card-img-top {
            object-fit: cover;
            height: 200px;
        }

        .contador {
            display: inline-block;
            background-color: rgb(40, 82, 167);
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 10px;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);

        }

    </style>
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
        <div class="content-header">
            <div class="container-fluid">
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                            </div>
                            <div class="row" id="rowMenu3" name="rowMenu3" style="display: none;">

                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header d-flex p-0 text-center bg-success text-white">
                                                <ul style="font-size: 12pt;" class="nav nav-pills mr-auto ml-auto p-2 text-center text-white">
                                                    <li class="nav-item text-white"><a class="nav-link active text-white" href="#tabProfesionales" data-toggle="tab">Profesionales</a></li>
                                                    <li class="nav-item text-white"><a class="nav-link text-white" href="#tabTecnicos" data-toggle="tab">Tecnicos</a></li>

                                                </ul>
                                            </div>

                                            <div class="card-body text-center">
                                                <div class="tab-content">

                                                    <div class="tab-pane active" id="tabProfesionales">
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
                                                                $query = 'SELECT * FROM jefes_tecnicos ORDER BY idDepartamento, nomJefe ASC';

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

                                                                                    <form style="margin: 0; text-align: center;" action="../sdo-funciones/jefe-modificar.php" method="post">

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
                                                                                                    $sexo = $row['sexo'];
                                                                                                    $email = $row['emailJefe'];


                                                                                                    ?>
                                                                                                    <div class="modal-body">
                                                                                                        <div style="text-align:left;" class="form-group">
                                                                                                            <label for="exampleInputBorder">Nombre: <code>*</code></label>
                                                                                                            <input type="text" class="form-control form-control-border" id="modNombre" name="modNombre" placeholder="Nombre de Encargado de Departamento" value="<?php echo $nomJefe; ?>" required>
                                                                                                        </div>
                                                                                                        <div style="text-align:left;" class="form-group">
                                                                                                            <label for="exampleInputBorder">Usuario: <code>*</code></label>
                                                                                                            <input style="text-align:left;" type="text" style="text-align:center;" class="form-control form-control-border" id="modUser" name="modUser" placeholder="Usuario de Encargado de Departamento" value="<?php echo $userJefe; ?>" required>
                                                                                                        </div>
                                                                                                        <div style="text-align:left;" class="form-group">
                                                                                                            <label for="exampleInputBorder">Contrase&ntilde;a: <code>*</code></label>
                                                                                                            <input style="text-align:left;" type="text" style="text-align:center;" class="form-control form-control-border" id="modPassword" name="modPassword" placeholder="Contrase&ntilde;a de Encargado de Departamento" value="<?php echo $passJefe; ?>" required>
                                                                                                        </div>
                                                                                                        <div style="text-align: left;" class="form-group">
                                                                                                            <label for="exampleModalBoder">Email<code>*</code></label>
                                                                                                            <input type="text" class="form-control form-control-border" style="text-align: left" id="modEmail" name="modEmail" value="<?php echo $email?>">
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

                                                                                    <form style="margin: 0; text-align: center;" class="" action="../sdo-funciones/jefe-eliminar.php" method="post">

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
                                                    </div>

                                                    <div class="tab-pane" id="tabTecnicos">
                                                        <div class="col-12">
                                                            Tecnicos
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

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
<!-- jQuery UI 1.11.4 -->
<script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="../plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="../plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="../plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="../plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="../plugins/moment/moment.min.js"></script>
<script src="../plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<!-- overlayScrollbars -->
<script src="../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Page specific script -->
<script src="js/dark-mode.js"></script>


<script src="js/fullScreen.js"></script>
</body>

</html>