<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['conocerUsuario'])) {

  $conocerUsuario = trim($_POST['conocerUsuario']);

  if (empty($conocerUsuario)) {
    header('Location: tareas_administrar.php?errorEjecutor=true');
  } else {

    $conocerUsuario = mb_convert_case($conocerUsuario, MB_CASE_LOWER, "UTF-8");

    $_SESSION['conocerUsuario'] = $conocerUsuario;

    $separador1 = "tecnico";
    $separador2 = "profesional";


    if (strpos($conocerUsuario, $separador1) !== false) {

      $tecnico = explode($separador1, $conocerUsuario);

      $datosTecnico = array();
      $datosTecnico = getDatosPersonal($tecnico[1]);

      $nomEjecutor =  $datosTecnico['nomPersonal'];
      $cargoEjecutor = $datosTecnico['cargoPersonal'];
      
    } else if (strpos($conocerUsuario, $separador2) !== false) {

      $profesional = explode($separador2, $conocerUsuario);
      
      $datosProfesional = array();
      $datosProfesional = getDatosJefe($profesional[1]);

      $nomEjecutor =  $datosProfesional['nomJefe'];
      $cargoEjecutor = $datosProfesional['cargoJefe'];
    } else {
      header('Location: tareas_administrar.php?errorEjecutor=true');
    }
  }

} else if (isset($_SESSION['conocerUsuario'])) {
  $conocerUsuario = trim($_SESSION['conocerUsuario']);

  if (empty($conocerUsuario)) {
    header('Location: tareas_administrar.php?errorEjecutor=true');
  } else {

    $conocerUsuario = mb_convert_case($conocerUsuario, MB_CASE_LOWER, "UTF-8");

    $_SESSION['conocerUsuario'] = $conocerUsuario;

    $separador1 = "tecnico";
    $separador2 = "profesional";


    if (strpos($conocerUsuario, $separador1) !== false) {

      $tecnico = explode($separador1, $conocerUsuario);

      $datosTecnico = array();
      $datosTecnico = getDatosPersonal($tecnico[1]);

      $nomEjecutor =  $datosTecnico['nomPersonal'];
      $cargoEjecutor = $datosTecnico['cargoPersonal'];
      
    } else if (strpos($conocerUsuario, $separador2) !== false) {

      $profesional = explode($separador2, $conocerUsuario);
      
      $datosProfesional = array();
      $datosProfesional = getDatosJefe($profesional[1]);

      $nomEjecutor =  $datosProfesional['nomJefe'];
      $cargoEjecutor = $datosProfesional['cargoJefe'];
    } else {
      header('Location: tareas_administrar.php?errorEjecutor=true');
    }
  }

} else {
  header('Location: tareas_administrar.php?errorEjecutor=true');
}

date_default_timezone_set('America/Santiago');
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Listado de Trabajos seg&uacute;n Personal</title>

  <link rel="icon" href="../dist/img/controlCent-HBQP-120.png" type="image/icon type">
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
  <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>
</head>

<body class="hold-transition sidebar-mini dark-mode">
  <div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/jefes-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Listado de Trabajos seg&uacute;n Personal</h1>
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

                  }
                </script>
                <br>
                <br>
                <a href="tareas_administrar.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                <br>
                <br>
              </div>
              <!-- /.card-header -->
              <div class="card-body">


                <div class="row">
                  <div class="col-md-8">
                    <h5 style="font-size:150%"><b>Funcionario : </b><?php echo $nomEjecutor; ?></h5>
                    <h5 style="font-size:150%"><b>Cargo Funcionario: </b><?php echo $cargoEjecutor; ?></h5>

                  </div>

                </div>

                <form method="post" action="tareas_formulario.php">

                  <input type="hidden" name="nomEjecutor" id="nomEjecutor" value="<?php echo $nomEjecutor; ?>">
                  <input type="hidden" name="nomCreador" id="nomCreador" value="<?php echo $nomUsuario; ?>">
                  <button type="submit" style="float: right;" id="inputMozi" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Tareas</button>

                </form>

                <br>
                <br>
                <br>

                <div class="table-responsive">
                  <table id="example2" class="table table-bordered">
                    <thead class="bg-secondary">
                      <tr>
                        <th style="text-align:center; vertical-align:middle;">N°</th>
                        <th style="text-align:center; vertical-align:middle;">Solicitante</th>
                        <th style="text-align:center; vertical-align:middle;">Fecha de Creaci&oacute;n</th>
                        <th style="text-align:center; vertical-align:middle;">Mensaje</th>
                        <th style="text-align:center; vertical-align:middle;">Visualizar</th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php
                      $query = 'SELECT * FROM tareas_diarias WHERE nomEjecutor = "' . $nomEjecutor . '" ORDER BY inicioTarea DESC';

                      $res = mysqli_query($conexion, $query);

                      $numTarea = 1;

                      while ($row = $res->fetch_assoc()) {

                        $idTarea = $row['idTarea'];

                        $detalleTarea = $row['detalleTarea'];

                        $caracteres = 20;

                      ?>

                        <tr>
                          <td style="text-align:center; vertical-align:middle;"><?php echo $numTarea; ?></td>
                          <td style="text-align:center; vertical-align:middle;"><?php echo $row['nomSolicitante']; ?></td>
                          <td style="text-align:center; vertical-align:middle;"><?php echo time_elapsed_string($row['inicioTarea']); ?></td>
                          <td style="vertical-align:middle;">

                            <?php

                            $detalleTarea = nl2br($detalleTarea);

                            ?>

                            <b><?php echo $row['tituloTarea']; ?></b> <br><br> <?php echo substr($detalleTarea, 0, $caracteres) . '...'; ?>
                          </td>
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

                                <form style="margin: 0; text-align: center;" class="" action="../sdo-jefestecnicos/tareas_detalle.php" method="post">

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
                        <th style="text-align:center; vertical-align:middle;">Fecha de Creaci&oacute;n</th>
                        <th style="text-align:center; vertical-align:middle;">Mensaje</th>
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
      <strong>Copyright &copy; 2022. Derechos Reservados.
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