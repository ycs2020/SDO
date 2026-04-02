<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];



date_default_timezone_set('America/Santiago');

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Niveles de Combustible</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- AdminLTE css -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" />
    <!-- include summernote css-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- include summernote js-->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <style>
        .reset {
            all: revert;
        }

        #cargaCombustible,
        #medicionNormal {
            display: none;
        }
    </style>

    <script>
        $(document).ready(function() {


            // Show div by removing inline display none style rule
            $(".show-realizar").click(function() {
                $("#rowMenu2").show();
                $("#rowMenu1").hide();
                $("#listarAC").show();
                $("#registrarAC").hide();
            });

            // Show div by removing inline display none style rule
            $(".show-listar").click(function() {
                $("#rowMenu2").hide();
                $("#rowMenu1").show();
                $("#listarAC").hide();
                $("#registrarAC").show();
            });

            $(".show-verdash").click(function() {
                $("#rowMenu3").show();
                $("#verDashAC").hide();
                $("#ocultarDashAC").show();
            });

            $(".show-ocultardash").click(function() {
                $("#rowMenu3").hide();
                $("#verDashAC").show();
                $("#ocultarDashAC").hide();
            });
        });
    </script>
    <script>
        function DoCheckUncheckDisplay(d, dchecked, dunchecked) {
            if (d.checked == true) {
                document.getElementById(dchecked).style.display = "block";
                document.getElementById(dunchecked).style.display = "none";
            } else {
                document.getElementById(dchecked).style.display = "none";
                document.getElementById(dunchecked).style.display = "block";
            }
        }
    </script>


</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
    <!-- Site wrapper -->
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
                            <h4>Administraci&oacute;n de Combustible</h4>

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

                    <!-- Timelime example  -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">

                                    <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <br>
                                    <br>

                                </div>
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>

                    <?php

                    if (isset($_GET['exitoAgregarAC'])) {
                    ?>
                        <script>
                            Swal.fire({
                                title: 'Medici&oacute;n de Combustible Agregada con &Eacute;xito.',
                                confirmButtonText: 'Cerrar',
                                icon: 'success',
                            });
                        </script>
                    <?php
                    }

                    if (isset($_GET['errorAgregarAC'])) {
                    ?>
                        <script>
                            Swal.fire({
                                title: 'Error al Ingresar Medici&oacute;n de Combustible.',
                                confirmButtonText: 'Cerrar',
                                icon: 'error',
                            });
                        </script>
                    <?php
                    }
                    ?>


                    <div class="row" id="rowMenu1" name="rowMenu1">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5>1.- Listado de Niveles de Combustible</h5>
                                    <hr>
                                    <br>
                                    <h5>&nbsp;&nbsp; Colores para Identificar:</h5>
                                    <ul>
                                        <li><i style="color: #FFC107; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Cargas de Combustible</li>
                                    </ul>
                                    <div class="table-responsive">
                                        <table style="font-size:80%" id="example2" class="table table-bordered cell-border">
                                            <thead class="bg-secondary">
                                                <tr>
                                                    <th style="vertical-align:middle;">
                                                        <center>Fecha de Medici&oacute;n</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Tanque N° 1 (10.000 Lts.)</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Tanque N° 2 (10.000 Lts.)</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Tanque N° 3 (40.000 Lts.)</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Tanque N° 4 (40.000 Lts.)</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>PRUEBA T1</center>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php
                                                $numAnterior1 = 0;
                                                $numAnterior2 = 0;

                                                $restaAC = 0;

                                                $valorFinal = '';
                                                $identificadorAC = '';
                                                $colorIdentAC = '';


                                                $queryAC = "SELECT * FROM combustible_administracion ORDER BY fechaAC ASC";

                                                $resAC = mysqli_query($conexion, $queryAC);

                                                $numAC = 1;

                                                while ($rowAC = $resAC->fetch_assoc()) {

                                                    $fechaAC = $rowAC['fechaAC'];


                                                    $tanque1AC = $rowAC['tanque1AC'];
                                                    $tanque1AC = trim($tanque1AC);

                                                    $tanque2AC = $rowAC['tanque2AC'];
                                                    $tanque2AC = trim($tanque2AC);

                                                    $tanque3AC = $rowAC['tanque3AC'];
                                                    $tanque3AC = trim($tanque3AC);

                                                    $tanque4AC = $rowAC['tanque4AC'];
                                                    $tanque4AC = trim($tanque4AC);

                                                    $tipoAC = $rowAC['tipoAC'];
                                                    $tipoAC = trim($tipoAC);

                                                    if ($tipoAC == 'Carga de Combustible') {
                                                        $classTR = 'bg-yellow';
                                                    } else {
                                                        $classTR = '';
                                                    }
                                                ?>

                                                    <tr class="<?php echo $classTR; ?>">
                                                        <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                            <?php if ($fechaAC == 0) {
                                                                echo "No registra fecha";
                                                            } else {

                                                                $registrofechaAC = date('d/m/Y', strtotime(str_replace('/', '-', $fechaAC)));
                                                                $registroHoraAC = date('H:i', strtotime($fechaAC));

                                                                // echo $registrofechaAC . ' a las ' . $registroHoraMC.' hrs.';

                                                                echo $registrofechaAC . ' a las ' . $registroHoraAC . ' hrs.';
                                                            } ?>

                                                        </td>
                                                        <td style="vertical-align:middle;" align="center">
                                                            <?php if ($tanque1AC > -1 || !empty($tanque1AC)) {
                                                                echo number_format($tanque1AC, 0, ",", ".");
                                                            } else {
                                                                echo "No registra Medida";
                                                            } ?>

                                                        </td>
                                                        <td style="vertical-align:middle;" align="center">
                                                            <?php if ($tanque2AC > -1 || !empty($tanque2AC)) {
                                                                echo number_format($tanque2AC, 0, ",", ".");
                                                            } else {
                                                                echo "No registra Medida";
                                                            } ?>

                                                        </td>
                                                        <td style="vertical-align:middle;" align="center">
                                                            <?php if ($tanque3AC > -1 || !empty($tanque3AC)) {
                                                                echo number_format($tanque3AC, 0, ",", ".");
                                                            } else {
                                                                echo "No registra Medida";
                                                            } ?>

                                                        </td>
                                                        <td style="vertical-align:middle;" align="center">
                                                            <?php if ($tanque4AC > -1 || !empty($tanque4AC)) {
                                                                echo number_format($tanque4AC, 0, ",", ".");
                                                            } else {
                                                                echo "No registra Medida";
                                                            } ?>

                                                        </td>
                                                        <?php



                                                        if ($numAC == 1) {
                                                            $numAnterior1 = $tanque1AC;
                                                        } else {

                                                            if ($tipoAC == 'Carga de Combustible') {

                                                                $diferencia = $tanque1AC - $numAnterior1;
                                                                $diferencia = number_format($diferencia, 0, ",", ".");
                                                                if ($diferencia == 0) {
                                                                    $valorFinal = $diferencia;
                                                                    $identificadorAC = '0';
                                                                } else {
                                                                    $valorFinal = '+ ' . $diferencia;
                                                                    $identificadorAC = '+';
                                                                }
                                                                $numAnterior1 = $tanque1AC;
                                                            } else {
                                                                $diferencia = $numAnterior1 - $tanque1AC;
                                                                $diferencia = number_format($diferencia, 0, ",", ".");
                                                                if ($diferencia == 0) {
                                                                    $valorFinal = $diferencia;
                                                                    $identificadorAC = '0';
                                                                } else {
                                                                    $valorFinal = '- ' . $diferencia;
                                                                    $identificadorAC = '-';
                                                                }
                                                                $numAnterior1 = $tanque1AC;
                                                            }
                                                        }

                                                        if ($identificadorAC == '+') {
                                                            $colorIdentAC = 'color: green;';
                                                        } else if ($identificadorAC == '-') {
                                                            $colorIdentAC = 'color: red;';
                                                        }else{
                                                            $colorIdentAC = '';
                                                        }

                                                        ?>



                                                        <td style="vertical-align: middle; <?php echo $colorIdentAC; ?>" align="center">
                                                            <?php echo $valorFinal; ?>
                                                        </td>

                                                    </tr>

                                                <?php
                                                    $numAC++;
                                                }
                                                ?>

                                            </tbody>
                                            <tfoot class="bg-secondary">
                                                <tr>
                                                    <th style="vertical-align:middle;">
                                                        <center>Fecha de Medici&oacute;n</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Tanque N° 1 (10.000 Lts.)</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Tanque N° 2 (10.000 Lts.)</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Tanque N° 3 (40.000 Lts.)</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Tanque N° 4 (40.000 Lts.)</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>PRUEBA T1</center>
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
                </div>
                <!-- /.timeline -->

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
    <script src="js/dark-mode.js"></script>
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
            $('#example2').DataTable({
                "buttons": [{
                    extend: 'excelHtml5',
                    className: 'btn btn-success',
                    text: 'Exportar a Excel',
                    title: 'Resumen Gasolina',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                        format: {
                            body: function(data, column, row) {
                                if (typeof data === 'string' || data instanceof String) {
                                    data = data.replace(/<br\s*\/?>/ig, "\r\n");
                                }
                                return data;
                            }
                        }
                    }
                }],
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": false,
                "autoWidth": false,
                "responsive": false,
                "language": {
                    "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                    "zeroRecords": "No se han encontrado Registros.",
                    "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                    "infoEmpty": "No existen Registros.",
                    "search": "Buscar :",
                    "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                    "paginate": {
                        "previous": "Anterior",
                        "next": "Siguiente"
                    }
                }

            }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>


    <script type="text/javascript">
        $("input[name='opciones']").change(function() {
            if ($(this).val() == 'medicionN') {
                $('#medicionNormal').show();
                $('#cargaCombustible').hide();
            } else if ($(this).val() == 'cargaC') {
                $('#medicionNormal').hide();
                $('#cargaCombustible').show();
            } else {
                $('#medicionNormal').hide();
                $('#cargaCombustible').hide();
            }
        })
    </script>
</body>

</html>