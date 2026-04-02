<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}



date_default_timezone_set('America/Santiago');


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Men&uacute; Solicitudes de Compra</title>

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


    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

    <script>
        $(document).ready(function() {


            // Show div by removing inline display none style rule
            $(".show-realizar").click(function() {
                $("#rowMenu2").show();
                $("#rowMenu1").hide();
                $("#listarSC").show();
                $("#realizarSC").hide();
            });

            // Show div by removing inline display none style rule
            $(".show-listar").click(function() {
                $("#rowMenu2").hide();
                $("#rowMenu1").show();
                $("#listarSC").hide();
                $("#realizarSC").show();
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

    <link rel="stylesheet" href="../dist/css/modelo.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        include '../sdo-templates/admtec-sidebar.php';
        ?>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h4>Solicitudes de Compras para Puesta en Marcha</h4>

                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <?php

            if (isset($_GET['exitoCrearSC'])) {
            ?>
                <script>
                    Swal.fire({
                        title: 'Solicitud de Compra Ingresada con &Eacute;xito.',
                        confirmButtonText: 'Cerrar',
                        icon: 'success',
                    }).then((result) => {
                        <?php
                        if ($_GET['material'] == "Con") {
                        ?>
                            Swal.fire('Materiales solicitados con &eacute;xito.', '', 'success')
                        <?php
                        } else if ($_GET['material'] == "Sin") {
                        ?>
                            Swal.fire('Solicitud sin Materiales espec&iacute;ficos.', '', 'success')
                        <?php
                        } else {
                        ?>
                            Swal.fire('Error al Solicitar Materiales.', '', 'error')
                        <?php
                        }
                        ?>
                    })
                </script>
            <?php
            }
            if (isset($_GET['errorCrearSC'])) {
            ?>
                <script>
                    Swal.fire({
                        title: 'Error al Ingresar Solicitud de Compra.',
                        confirmButtonText: 'Cerrar',
                        icon: 'error',
                    });
                </script>
            <?php
            }


            ?>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <!-- Timelime example  -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>


                                </div>
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body" style="text-align:center;">
                                    <button class="btn bg-yellow show-realizar" id="realizarSC" name="realizarSC">Realizar Solicitud de Compra</button>
                                    <button class="btn bg-yellow show-listar" id="listarSC" style="display:none;" name="listarSC">Listar Solicitudes de Compra</button>

                                    <!-- <button type="button" class="hide-btn">Hide</button>
                                    <button type="button" class="show-btn">Show</button>
                                    <button type="button" class="toggle-btn">Toggle</button> -->


                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>

                    <div class="row" id="rowMenu1" name="rowMenu1">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5>1.- Listado de Solicitudes de Compra</h5>
                                    <hr>

                                    <table style="font-size:90%" id="example2" class="table table-bordered table-hover">
                                        <thead class="bg-secondary">
                                            <tr>
                                                <th style="vertical-align:middle;">
                                                    <center>Fecha de Solicitud</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Personal Solicitante</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Folio de Solicitud</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>N° Interno de <br>de Puesta en Marcha</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Estado de Solicitud</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Ver Solicitud de Compra</center>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $query = "SELECT * FROM solicitudes_compra_pm WHERE unidadSoliCompra = '" . $nomDepartamento . "' ORDER BY fechaSoliCompra DESC";

                                            $res = mysqli_query($conexion, $query);

                                            $numSolicitud = 1;

                                            while ($row = $res->fetch_assoc()) {
                                            ?>
                                                <tr>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <center>
                                                            <?php if ($row['fechaSoliCompra'] == 0) {
                                                                echo "No registra fecha";
                                                            } else {

                                                                $inicioFecha =  date('d/M/Y', (strtotime(str_replace('/', '-', $row['fechaSoliCompra']))));
                                                                $inicioHora = date('H:i', strtotime($row['fechaSoliCompra']));

                                                                echo $inicioFecha . '<br>' . $inicioHora;
                                                            } ?>
                                                        </center>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php
                                                        $personalSoliCompra = mb_strtoupper($row['personalSoliCompra']);
                                                        $unidadSoliCompra = mb_strtoupper($row['unidadSoliCompra']);
                                                        echo $personalSoliCompra . '<br>(' . $unidadSoliCompra . ')';

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php
                                                        $folioSoliCompra = mb_strtoupper($row['folioSoliCompra']);
                                                        echo $folioSoliCompra;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php
                                                        $numSoliCompra = mb_strtoupper($row['numSoliCompra']);

                                                        if ($numSoliCompra == 0) {
                                                            echo "SIN N&Uacute;MERO ASIGNADO";
                                                        } else {
                                                            echo $numSoliCompra;
                                                        }



                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php
                                                        $estadoSoliCompra = mb_strtoupper($row['estadoSoliCompra']);
                                                        echo $estadoSoliCompra;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;">
                                                        <center>
                                                            <?php
                                                            $idSoliCompra = $row['idSoliCompra'];
                                                            $idSoliCompra = trim($idSoliCompra);
                                                            if ($idSoliCompra == 0 || $idSoliCompra == "" || $idSoliCompra == " " || $idSoliCompra == "  ") {
                                                            ?>
                                                                <center><?php echo "Error al Cargar Datos."; ?></center>
                                                            <?php
                                                            } else {

                                                            ?>

                                                                <form style="margin: 0; text-align: center;" class="" action="soliCompra_visualizarSC_pm.php" method="post">

                                                                    <input type="hidden" name="idSolicitud" value="<?php echo $idSolicitud; ?>">
                                                                    <input type="hidden" name="idSoliCompra" value="<?php echo $idSoliCompra; ?>">
                                                                    <button type="submit" id="inputMozi" disabled class="btn btn-block btn-primary"><i class="fa fa-eye"></i></button>

                                                                </form>

                                                            <?php

                                                            }
                                                            ?>


                                                        </center>
                                                    </td>


                                                <?php

                                                $numSolicitud = $numSolicitud + 1;
                                            }
                                                ?>

                                                </tr>
                                        </tbody>
                                        <tfoot class="bg-secondary">
                                            <tr>
                                                <th style="vertical-align:middle;">
                                                    <center>Fecha de Solicitud</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Personal Solicitante</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Folio de Solicitud</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>N° Interno de <br>de Puesta en Marcha</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Estado de Solicitud</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Ver Solicitud de Compra</center>
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>

                    <div class="row" id="rowMenu2" name="rowMenu2" style="display:none;">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5>2.- Solicitar Compra de Materiales</h5>
                                    <hr>
                                    <form action="../sdo-funciones/soliCompra_solicitarCompraPM.php" method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="exampleInputArgumentos">Argumente los motivos y necesidad de la compra:<code>*</code></label>
                                            <textarea id="argumentosNecesidad" style="resize:none;" required name="argumentosNecesidad" class="form-control" rows="10" placeholder="Argumentos y Motivos de la Solicitud"></textarea>
                                        </div>


                                        <div class="form-row fieldGroup">
                                            <div class="form-group col-md-3">
                                                <label for="exampleInputRecinto">Material que necesita:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-list"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" id="materialSC[]" name="materialSC[]" placeholder="Material">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label for="exampleInputCoordenadas">Cantidad:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                                    </div>
                                                    <input type="number" min="1" class="form-control" id="cantidadSC[]" name="cantidadSC[]" placeholder="Cantidad">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label for="exampleInputCoordenadas">Observaci&oacute;n:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fa fa-comment"></i></span>
                                                    </div>
                                                    <input type="text" min="1" class="form-control" id="observacionSC[]" name="observacionSC[]" placeholder="Observaci&oacute;n">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label for="exampleInputCoordenadas">Referencia de Adquisici&oacute;n:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                    </div>
                                                    <input type="text" min="1" class="form-control" id="empresaSC[]" name="empresaSC[]" placeholder="Referencia de Adquisici&oacute;n">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="exampleInputCoordenadas">&nbsp;</label>
                                                <div class="input-group mb-3">
                                                    <a href="javascript:void(0)" class="btn btn-block btn-success addMore"><span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>Agregar Otro Material</a>
                                                </div>
                                            </div>
                                        </div>

                                        <br>




                                        <br>
                                        <hr>
                                        <br>

                                        <div class="form-group">
                                            <input type="hidden" name="personalSC" id="personalSC" value="<?php echo $nomUsuario; ?>">
                                            <input type="hidden" name="cargoSC" id="cargoSC" value="<?php echo $cargoJefe; ?>">
                                            <input type="hidden" name="deptoSC" id="deptoSC" value="<?php echo $nomDepartamento; ?>">
                                            <input type="hidden" name="emailSC" id="emailSC" value="<?php echo $emailJefe; ?>">
                                            <input type="submit" class="btn btn-block btn-primary" value="Guardar" />

                                        </div>
                                    </form>

                                    <div class="form-row fieldGroupCopy" style="display: none;">
                                        <div class="form-group col-md-3">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-list"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="materialSC[]" name="materialSC[]" placeholder="Material">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                                </div>
                                                <input type="number" min="1" class="form-control" id="cantidadSC[]" name="cantidadSC[]" placeholder="Cantidad">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fa fa-comment"></i></span>
                                                </div>
                                                <input type="text" min="1" class="form-control" id="observacionSC[]" name="observacionSC[]" placeholder="Observaci&oacute;n">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                </div>
                                                <input type="text" min="1" class="form-control" id="empresaSC[]" name="empresaSC[]" placeholder="Referencia de Adquisici&oacute;n">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <div class="input-group mb-3">
                                                <a href="javascript:void(0)" class="btn btn-block btn-danger remove"><span class="glyphicon glyphicon glyphicon-remove" aria-hidden="true"></span> Eliminar</a>
                                            </div>
                                        </div>
                                    </div>


                                    <script type="text/javascript">
                                        $(document).ready(function() {
                                            //group add limit
                                            var maxGroup = "100";

                                            //add more fields group
                                            $(".addMore").click(function() {
                                                if ($('body').find('.fieldGroup').length < maxGroup) {
                                                    var fieldHTML = '<div class="form-row fieldGroup">' + $(".fieldGroupCopy").html() + '</div>';
                                                    $('body').find('.fieldGroup:last').after(fieldHTML);

                                                } else {
                                                    alert('Limite de Materiales Completados. Solo se permiten ' + maxGroup + ' Materiales.');
                                                }
                                            });

                                            //remove fields group
                                            $("body").on("click", ".remove", function() {
                                                $(this).parents(".fieldGroup").remove();
                                            });
                                        });
                                    </script>
                                </div>

                            </div>

                        </div>


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
    <script src="js/dark-mode"></script>
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
            $('#example2').DataTable({
                "paging": true,
                "pageLength": 10,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": false,
                "autoWidth": true,
                "responsive": true,
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
            });
        });
    </script>
</body>

</html>