<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


date_default_timezone_set('America/Santiago');

$contarForzarEquipo = contarEquiposForzados();

// $fecha = date('d-m-Y h:i a');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema BMS - Control Centralizado</title>

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
    <link rel="stylesheet" href="../plugins/summernote/summernote-bs4.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>
</head>

<body class="hold-transition sidebar-mini dark-mode">
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
                            <h1 class="m-0">Sistema de Control Centralizado</h1>
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
            <!-- /.content-header -->
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
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
                                    <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <br>
                                    <br>
                                </div>
                                <div class="card-body">
                                    <div class="card card-primary card-tabs">
                                        <div class="card-header p-0 pt-1">
                                            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link " id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Sistema BMS</a>
                                                </li>
                                                <?php
                                                if ($contarForzarEquipo > 0) {

                                                    if ($contarForzarEquipo == 1) {
                                                ?>
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false"><?php echo '<b>Existe ' . $contarForzarEquipo . ' Equipo Forzado</b>'; ?></a>
                                                        </li>

                                                    <?php

                                                    } else {

                                                    ?>
                                                        <li class="nav-item">
                                                            <a class="nav-link active" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false"><?php echo '<b>Existen ' . $contarForzarEquipo . ' Equipos Forzados</b>'; ?></a>
                                                        </li>
                                                <?php

                                                    }
                                                }
                                                ?>

                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <div class="tab-content" id="custom-tabs-one-tabContent">
                                                <div class="tab-pane fade" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                                                    <div class="row">
                                                        <?php

                                                        $sqlCategorias = "SELECT * FROM categorias ORDER BY ordenCategoria ASC";

                                                        $resCategorias = mysqli_query($conexion, $sqlCategorias);

                                                        $numCategorias = 1;

                                                        while ($rowCategorias = $resCategorias->fetch_assoc()) {

                                                            if ($numCategorias == 1) {
                                                                $color = "bg-primary";
                                                            } else if ($numCategorias == 2) {
                                                                $color = "bg-secondary";
                                                            } else if ($numCategorias == 3) {
                                                                $color = "bg-success";
                                                            } else if ($numCategorias == 4) {
                                                                $color = "bg-danger";
                                                            } else if ($numCategorias == 5) {
                                                                $color = "bg-warning";
                                                            } else if ($numCategorias == 6) {
                                                                $color = "bg-info";
                                                            } else {
                                                                if ($numCategorias == 7) {
                                                                    $numCategorias = 1;
                                                                    if ($numCategorias == 1) {
                                                                        $color = "bg-primary";
                                                                    } else if ($numCategorias == 2) {
                                                                        $color = "bg-secondary";
                                                                    } else if ($numCategorias == 3) {
                                                                        $color = "bg-success";
                                                                    } else if ($numCategorias == 4) {
                                                                        $color = "bg-danger";
                                                                    } else if ($numCategorias == 5) {
                                                                        $color = "bg-warning";
                                                                    } else if ($numCategorias == 6) {
                                                                        $color = "bg-info";
                                                                    } else {
                                                                        if ($numCategorias == 7) {
                                                                            $numCategorias = 1;
                                                                        }
                                                                    }
                                                                }
                                                            }


                                                            $nomCategoria = trim($rowCategorias['nomCategoria']);

                                                            $nomCategoria = mb_strtolower($nomCategoria);

                                                            if ($nomCategoria == 'bitacora' || $nomCategoria == 'bitácora' || $nomCategoria == 'Bitácora') {
                                                            } else {
                                                        ?>

                                                                <div class="col-lg-2 col-12">
                                                                    <!-- small box -->
                                                                    <div class="small-box <?php echo $color; ?>">
                                                                        <div class="inner">
                                                                            <center>
                                                                                <h4><?php echo $rowCategorias['nomCategoria'] ?></h4>
                                                                                <?php echo $rowCategorias['imagenCategoria'] ?>
                                                                            </center>
                                                                        </div>

                                                                        <form id="formSistema<?php echo $rowCategorias['idCategoria']; ?>" action="sistemas-visualizar.php" method="post">
                                                                            <input type="hidden" name="idCategoria" value="<?php echo $rowCategorias['idCategoria']; ?>" />
                                                                        </form>
                                                                        <a class="small-box-footer" href="javascript:;" onclick="document.getElementById('formSistema<?php echo $rowCategorias['idCategoria']; ?>').submit();">Ingresar <i class="fas fa-arrow-circle-right"></i></a>
                                                                        <!-- <a href="#" class="small-box-footer">Deshabilitada</a> -->

                                                                    </div>
                                                                </div>
                                                                <!-- ./col -->
                                                        <?php
                                                                $numCategorias = $numCategorias + 1;
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade show active" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <h5>&nbsp;&nbsp; Colores para Identificar:</h5>
                                                            <ul>
                                                                <li><i style="color: #28A745; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Forzado - Encendido</li>

                                                                <li><i style="color: #E83E8C; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Forzado - Apagado</li>

                                                                <li><i style="color: #FFC107; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Forzado - Variables</li>

                                                                <li><i style="color: #6C757D; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Forzado</li>

                                                                <li><i style="color: #138496; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Modo Automatico</li>

                                                                <li><i style="color: #001F3F; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Observaci&oacute;n Normal</li>
                                                            </ul>



                                                            <table id="example2" class="table table-bordered table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="vertical-align:middle;">
                                                                            <center>Equipo</center>
                                                                        </th>
                                                                        <th style="vertical-align:middle;">
                                                                            <center>Departamento:</center>
                                                                        </th>
                                                                        <th style="vertical-align:middle;">
                                                                            <center>Nombre de Tecnico:</center>
                                                                        </th>
                                                                        <th style="vertical-align:middle;">
                                                                            <center>Tipo de Observaci&oacute;n:<center>
                                                                        </th>
                                                                        <th style="vertical-align:middle;">
                                                                            <center>Observaciones:<center>
                                                                        </th>
                                                                        <th style="vertical-align:middle;">
                                                                            <center>Fecha Observaciones:<center>
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $query = "SELECT idEquipo FROM equipos WHERE forzadoEquipo = 1 ORDER BY idEquipo DESC";

                                                                    $res = mysqli_query($conexion, $query);

                                                                    $numObservacion = 1;

                                                                    while ($row = $res->fetch_assoc()) {

                                                                        $idEquipo = $row['idEquipo'];

                                                                        $dataRegistro = array();
                                                                        $dataRegistro = getUltimoRegistroForzado($idEquipo);

                                                                        $idEquipo2 = $dataRegistro['idEquipo'];
                                                                        $personalObservacion = $dataRegistro['personalObservacion'];
                                                                        $detalleObservaciones = $dataRegistro['detalleObservaciones'];
                                                                        $fechaObservaciones = $dataRegistro['fechaObservaciones'];
                                                                        $esForzado = $dataRegistro['esForzado'];

                                                                        $classObservacion = "";

                                                                        if ($dataRegistro['esForzado'] == "Forzado - Encendido") {

                                                                            $classObservacion = "bg-success";
                                                                        } else if ($dataRegistro['esForzado'] == "Forzado - Apagado") {

                                                                            $classObservacion = "bg-pink";
                                                                        } else if ($dataRegistro['esForzado'] == "Forzado - Variables") {

                                                                            $classObservacion = "bg-yellow";
                                                                        } else if ($dataRegistro['esForzado'] == "Modo Automatico") {

                                                                            $classObservacion = "bg-info";
                                                                        } else if ($dataRegistro['esForzado'] == "Observación Normal") {

                                                                            $classObservacion = "bg-navy";
                                                                        } else if ($dataRegistro['esForzado'] == "Forzado") {

                                                                            $classObservacion = "bg-gray";
                                                                        } else {

                                                                            $classObservacion = "bg-light";
                                                                        }
                                                                    ?>

                                                                        <tr class="<?php echo $classObservacion; ?>">

                                                                            <td style="vertical-align:middle; font-weight: normal;">
                                                                                <center>
                                                                                    <?php
                                                                                    $nomEquipo = mb_strtoupper(getSubCategoriaConEquipo($row['idEquipo']), 'UTF-8');

                                                                                    $dataEquipo = explode(" / ", $nomEquipo);

                                                                                    echo $dataEquipo[0];
                                                                                    echo "<br>";
                                                                                    echo $dataEquipo[1];
                                                                                    ?>
                                                                                </center>
                                                                            </td>
                                                                            <td style="vertical-align:middle; font-weight: normal;">
                                                                                <center>
                                                                                    <?php echo getCategoriaEquipo($idEquipo); ?>
                                                                                </center>
                                                                            </td>

                                                                            <td style="vertical-align:middle; font-weight: normal;">
                                                                                <center>
                                                                                    <?php
                                                                                    $personalObservacion2 = explode("(", $personalObservacion);

                                                                                    echo $personalObservacion2[0];
                                                                                    echo "<br>";
                                                                                    echo "(" . $personalObservacion2[1];
                                                                                    ?>
                                                                                </center>
                                                                            </td>
                                                                            <td style="vertical-align:middle; font-weight: normal;">
                                                                                <center>
                                                                                    <?php echo $esForzado; ?>
                                                                                </center>
                                                                            </td>
                                                                            <td style="vertical-align:middle; text-align:justify; font-weight: normal;">
                                                                                <?php echo nl2br($detalleObservaciones); ?>
                                                                            </td>
                                                                            <td style="vertical-align:middle; font-weight: normal;">
                                                                                <center>
                                                                                    <?php if ($fechaObservaciones == 0) {
                                                                                        echo "No registra fecha";
                                                                                    } else {
                                                                                        echo $fechaObservaciones;
                                                                                    } ?>
                                                                                </center>
                                                                            </td>
                                                                        </tr>

                                                                    <?php

                                                                        $numObservacion++;
                                                                    }
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card -->
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
    <script src="../plugins/chart.js/Chart.min.js"></script>
    <!-- Sparkline -->
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
    <script src="../plugins/summernote/summernote-bs4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
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
            $('#example2').DataTable({
                "autoWidth": true,
                "responsive": true,
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "info": false,
                "order": [
                    [6, "desc"]
                ],
                "columnDefs": [{
                        orderable: false,
                        targets: 0
                    },
                    {
                        orderable: false,
                        targets: 1
                    },
                    {
                        orderable: false,
                        targets: 2
                    },
                    {
                        orderable: false,
                        targets: 3
                    },
                    {
                        orderable: false,
                        targets: 4
                    },
                    {
                        orderable: false,
                        targets: 5
                    }
                ],
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