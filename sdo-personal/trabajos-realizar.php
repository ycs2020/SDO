<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Listado de Trabajos seg&uacute;n T&eacute;cnico</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
  <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>
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
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Listado de Trabajos seg&uacute;n T&eacute;cnico</h1>
            </div>
            <!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
              </ol>
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
      </div>

      <br>

      <!-- Main content -->
      <section class="content">
        <div class="row">
          <!-- /.col -->
          <div class="col-md-12">
            <div class="card card-primary card-outline">
              <div class="card-header bg-primary">
                <h3 class="card-title">Trabajos a Realizar</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">

                <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                <br>
                <br>

                <div class="table-responsive mailbox-messages">
                  <br>
                  <br>
                  <table class="table table-hover table-bordered">
                    <thead class="bg-secondary">
                      <tr>
                        <th style="text-align:center; vertical-align:middle;">N°</th>
                        <th style="text-align:center; vertical-align:middle;">Solicitante</th>
                        <th style="text-align:center; vertical-align:middle;">Mensaje</th>
                        <th style="text-align:center; vertical-align:middle;">Fecha de Creaci&oacute;n</th>
                        <th style="text-align:center; vertical-align:middle;">Visualizar</th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php
                      $query = 'SELECT * FROM tareas_diarias WHERE nomEjecutor LIKE "%' . $nomUsuario . '%" ORDER BY inicioTarea DESC';

                      $res = mysqli_query($conexion, $query);

                      $numTarea = 1;

                      while ($row = $res->fetch_assoc()) {

                        $idTarea = $row['idTarea'];

                        $detalleTarea = $row['detalleTarea'];

                        $caracteres = 50;

                      ?>

                        <tr>
                          <td style="text-align:center; vertical-align:middle;"><?php echo $numTarea; ?></td>
                          <td style="text-align:center; vertical-align:middle;" class="mailbox-name"><?php echo $row['nomSolicitante']; ?></td>
                          <td style="vertical-align:middle;" class="mailbox-subject">
                            <b><?php echo $row['tituloTarea']; ?></b> - <?php echo substr($detalleTarea, 0, $caracteres) . '...'; ?>
                          </td>
                          <td style="text-align:center; vertical-align:middle;" class="mailbox-date"><?php echo time_elapsed_string($row['inicioTarea']); ?></td>
                          <td style="text-align:center; vertical-align:middle;">
                            <center>
                              <?php
                              $idTarea = $row['idTarea'];
                              $idTarea = trim($idTarea);
                              if ($idTarea == 0 || $idTarea == "" || $idTarea == " " || $idTarea == "  ") {
                              ?>
                                <center><?php echo "Error al Cargar Datos."; ?></center>
                              <?php
                              } else {

                              ?>

                                <form style="margin: 0; text-align: center;" class="" action="../sdo-pdf/SDO-Reporte.php" method="post">

                                  <input type="hidden" name="idTarea" value="<?php echo $idTarea; ?>">
                                  <input type="submit" id="inputMozi" class="btn btn-block btn-info" value="Visualizar">

                                </form>

                              <?php

                              }
                              ?>


                            </center>
                          </td>
                        </tr>

                      <?php
                        $numTarea++;
                      }
                      ?>

                    </tbody>

                    <tfoot class="bg-secondary">
                      <tr>
                        <th style="text-align:center; vertical-align:middle;">N°</th>
                        <th style="text-align:center; vertical-align:middle;">Solicitante</th>
                        <th style="text-align:center; vertical-align:middle;">Mensaje</th>
                        <th style="text-align:center; vertical-align:middle;">Fecha de Creaci&oacute;n</th>
                        <th style="text-align:center; vertical-align:middle;">Visualizar</th>
                      </tr>
                    </tfoot>
                  </table>

                  <br>
                  <br>
                  <!-- /.table -->
                </div>
                <!-- /.mail-box-messages -->
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
      <div class="float-right d-none d-sm-block">
        <b>Version</b> 3.2.0
      </div>
      <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
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
  <!-- AdminLTE App -->
  <script src="../dist/js/adminlte.min.js"></script>
  <!-- DARK MODE-->
  <script src="js/dark-mode.js"></script>
  <!-- Page specific script -->
</body>

</html>