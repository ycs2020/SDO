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
    <title>Solicitudes Telefonicas</title>

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


    <link rel="stylesheet" href="modwidth.css">

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

    <script>
        $(document).ready(function() {


            // Show div by removing inline display none style rule
            $(".show-realizar").click(function() {
                $("#rowMenu2").show();
                $("#rowMenu1").hide();
                $("#listarST").show();
                $("#realizarST").hide();
            });

            // Show div by removing inline display none style rule
            $(".show-listar").click(function() {
                $("#rowMenu2").hide();
                $("#rowMenu1").show();
                $("#listarST").hide();
                $("#realizarST").show();
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

    <link rel="stylesheet" href="css/dark-mode.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        include '../sdo-templates/mesaayuda-sidebar.php';
        ?>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h4>Solicitudes Telefonicas</h4>

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

                    <?php

                    if (isset($_GET['exitoAgregarSolicitud'])) {
                    ?>
                        <script>
                            Swal.fire(
                                'Solicitud Telefonica Agregada con &Eacute;xito.',
                                '',
                                'success'
                            )
                        </script>
                    <?php
                    }

                    if (isset($_GET['errorAgregarSolicitud'])) {
                    ?>
                        <script>
                            Swal.fire(
                                '¡ Ha ocurrido un Problema !',
                                'Error al Agregar Solicitud Telefonica. Reintente.',
                                'error'
                            )
                        </script>
                    <?php
                    }

                    if (isset($_GET['exitoDerivar'])) {
                    ?>
                        <script>
                            Swal.fire(
                                'Solicitud Telefonica Derivada con &Eacute;xito.',
                                '',
                                'success'
                            )
                        </script>
                    <?php
                    }

                    if (isset($_GET['errorDerivar'])) {
                    ?>
                        <script>
                            Swal.fire(
                                '¡ Ha ocurrido un Problema !',
                                'Error al Derivar Solicitud Telefonica. Reintente.',
                                'error'
                            )
                        </script>
                    <?php
                    }

                    ?>

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

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body" style="text-align:center;">
                                    <button class="btn bg-yellow show-realizar" id="realizarST" name="realizarST">Ingresar Solicitud Telefonica</button>
                                    <button class="btn bg-yellow show-listar" id="listarST" style="display:none;" name="listarST">Listado Solicitudes Telefonica</button>



                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>

                    <div class="row" id="rowMenu1" name="rowMenu1">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5>1.- Listado de Solicitudes Telefonicas</h5>
                                    <hr>

                                    <div class="downloadInformation">

                                    </div>

                                    <table style="font-size:100%; width:100%" id="example3" class="table table-bordered">
                                        <thead class="bg-secondary">
                                            <tr>
                                                <th style="vertical-align:middle; ">
                                                    FECHA SOLICITUD
                                                </th>
                                                <th style="vertical-align:middle; ">
                                                    HORA SOLICITUD
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    FUNCIONARIO SOLICITANTE
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    UNIDAD SOLICITANTE
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    RECINTO SOLICITUD
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    DETALLE SOLICITUD
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    PERSONAL DESIGNADO
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    CAMBIAR DESIGNADO
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $querySoliTelefono = "SELECT * FROM solicitudes_telefonicas ORDER BY fechaSoliTelefono DESC";

                                            $resSoliTelefono = mysqli_query($conexion, $querySoliTelefono);

                                            $numSolicitud = 1;

                                            while ($rowSoliTelefono = $resSoliTelefono->fetch_assoc()) {
                                            ?>
                                                <tr>
                                                    <td style="vertical-align:middle; text-align:justify;" nowrap="nowrap">
                                                        <?php if ($rowSoliTelefono['fechaSoliTelefono'] == 0) {
                                                            echo "No registra fecha";
                                                        } else {

                                                            $inicioFecha =  date('d/m/Y', (strtotime(str_replace('/', '-', $rowSoliTelefono['fechaSoliTelefono']))));

                                                            echo $inicioFecha;
                                                        } ?>
                                                    </td>
                                                    <td style="vertical-align:middle; text-align:justify;" nowrap="nowrap">
                                                        <?php if ($rowSoliTelefono['fechaSoliTelefono'] == 0) {
                                                            echo "No registra fecha";
                                                        } else {

                                                            $inicioHora = date('H:i', strtotime($rowSoliTelefono['fechaSoliTelefono']));

                                                            echo $inicioHora;
                                                        } ?>
                                                    </td>
                                                    <td style="vertical-align:middle; text-align:justify;" nowrap="nowrap">
                                                        <?php
                                                        $nomSoliTelefono = mb_strtoupper($rowSoliTelefono['nomSoliTelefono']);

                                                        $nomSoliTelefono = explode(' (', $nomSoliTelefono);

                                                        $cargoSoliTelefono = mb_strtoupper($rowSoliTelefono['cargoSoliTelefono']);

                                                        if (empty($nomSoliTelefono[1])) {
                                                            echo $nomSoliTelefono[0] . ' (' . $cargoSoliTelefono . ')';
                                                        } else {
                                                            echo $nomSoliTelefono[0] . ' (' . $nomSoliTelefono[1] . ' (' . $cargoSoliTelefono . ')';
                                                        }

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle; text-align:justify;" nowrap="nowrap">
                                                        <?php
                                                        $unidadSoliTelefono = mb_strtoupper($rowSoliTelefono['unidadSoliTelefono']);
                                                        echo $unidadSoliTelefono;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle; text-align:justify;" nowrap="nowrap">
                                                        <?php
                                                        $recintoSoliTelefono = mb_strtoupper($rowSoliTelefono['recintoSoliTelefono']);
                                                        echo $recintoSoliTelefono;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle; text-align:justify;" nowrap="nowrap">
                                                        <?php
                                                        $detalleSoliTelefono = mb_strtoupper($rowSoliTelefono['detalleSoliTelefono']);
                                                        echo nl2br($detalleSoliTelefono);

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle; text-align:justify;" nowrap="nowrap">
                                                        <?php
                                                        $derivaSoliTelefono = trim($rowSoliTelefono['derivaSoliTelefono']);
                                                        echo $derivaSoliTelefono;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle; text-align:justify;" nowrap="nowrap">
                                                        <form action="../sdo-funciones/soliTelefono_derivar.php" method="post">

                                                            <div class="form-group mx-sm-3 mb-2">
                                                                <select class="form-control" style="vertical-align:middle; text-align:justify;" name="selectPersonalDesignado" id="selectPersonalDesignado">

                                                                    <?php

                                                                    $derivaSoliTelefono = trim($rowSoliTelefono['derivaSoliTelefono']);

                                                                    if (empty($derivaSoliTelefono) || $derivaSoliTelefono == 0) {
                                                                    ?>
                                                                        <option value="0" disabled selected> -- Seleccione --</option>
                                                                    <?php
                                                                    } else {
                                                                    ?>
                                                                        <option value="0" disabled> -- Seleccione --</option>
                                                                    <?php
                                                                    }
                                                                    ?>


                                                                    <option value="0" disabled>&nbsp;</option>
                                                                    <?php
                                                                    $queryDepartamento = "SELECT * FROM departamentos WHERE nomDepartamento NOT LIKE '%Ingeniería Operacional%' ORDER BY nomDepartamento ASC";

                                                                    $resDepartamento = mysqli_query($conexion, $queryDepartamento);

                                                                    while ($rowDepartamento = $resDepartamento->fetch_assoc()) {
                                                                    ?>
                                                                        <option value="<?php echo $rowDepartamento['idDepartamento']; ?>" disabled><?php echo $rowDepartamento['nomDepartamento']; ?></option>
                                                                        <?php
                                                                        $queryJefe = "SELECT * FROM jefes_tecnicos WHERE idDepartamento = '" . $rowDepartamento['idDepartamento'] . "' ORDER BY cargoJefe ASC";

                                                                        $resJefe = mysqli_query($conexion, $queryJefe);

                                                                        while ($rowJefe = $resJefe->fetch_assoc()) {

                                                                            $optionDeriva = $rowJefe['nomJefe'] . ' (' . $rowJefe['cargoJefe'] . ' de ' . getNomDepartamento($rowDepartamento['idDepartamento']) . ')';
                                                                            if ($optionDeriva == $derivaSoliTelefono) {
                                                                        ?>
                                                                                <option value="<?php echo $rowJefe['nomJefe'] . ' (' . $rowJefe['cargoJefe'] . ' de ' . getNomDepartamento($rowDepartamento['idDepartamento']) . ')'; ?>" selected><?php echo $rowJefe['nomJefe'] . ' (' . $rowJefe['cargoJefe'] . ')'; ?></option>
                                                                            <?php
                                                                            } else {
                                                                            ?>
                                                                                <option value="<?php echo $rowJefe['nomJefe'] . ' (' . $rowJefe['cargoJefe'] . ' de ' . getNomDepartamento($rowDepartamento['idDepartamento']) . ')'; ?>"><?php echo $rowJefe['nomJefe'] . ' (' . $rowJefe['cargoJefe'] . ')'; ?></option>
                                                                            <?php
                                                                            }
                                                                            ?>


                                                                        <?php
                                                                        }
                                                                        ?>

                                                                        <option value="0" disabled>&nbsp;</option>
                                                                        <?php
                                                                        $queryPersonal = "SELECT * FROM personal WHERE departamentoPersonal = '" . $rowDepartamento['idDepartamento'] . "' ORDER BY cargoPersonal DESC";

                                                                        $resPersonal = mysqli_query($conexion, $queryPersonal);

                                                                        while ($rowPersonal = $resPersonal->fetch_assoc()) {
                                                                            $optionDeriva2 = $rowPersonal['nomPersonal'] . ' (' . $rowPersonal['cargoPersonal'] . ' de ' . getNomDepartamento($rowDepartamento['idDepartamento']) . ')';
                                                                            if ($optionDeriva2 == $derivaSoliTelefono) {
                                                                        ?>
                                                                                <option value="<?php echo $rowPersonal['nomPersonal'] . ' (' . $rowPersonal['cargoPersonal'] . ' de ' . getNomDepartamento($rowDepartamento['idDepartamento']) . ')'; ?>" selected><?php echo $rowPersonal['nomPersonal'] . ' (' . $rowPersonal['cargoPersonal'] . ')'; ?></option>
                                                                            <?php
                                                                            } else {
                                                                            ?>
                                                                                <option value="<?php echo $rowPersonal['nomPersonal'] . ' (' . $rowPersonal['cargoPersonal'] . ' de ' . getNomDepartamento($rowDepartamento['idDepartamento']) . ')'; ?>"><?php echo $rowPersonal['nomPersonal'] . ' (' . $rowPersonal['cargoPersonal'] . ')'; ?></option>
                                                                            <?php
                                                                            }
                                                                            ?>


                                                                            <?php
                                                                            ?>
                                                                        <?php
                                                                        }

                                                                        ?>

                                                                        <option value="0" disabled>&nbsp;</option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <br>
                                                                <input type="hidden" name="idSoliTelefono" id="idSoliTelefono" value="<?php echo $rowSoliTelefono['idSoliTelefono']; ?>">
                                                                <input type="submit" class="btn btn-block btn-primary" value="Guardar" />
                                                            </div>

                                                        </form>
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
                                                    FECHA SOLICITUD
                                                </th>
                                                <th style="vertical-align:middle; ">
                                                    HORA SOLICITUD
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    FUNCIONARIO SOLICITANTE
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    UNIDAD SOLICITANTE
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    RECINTO SOLICITUD
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    DETALLE SOLICITUD
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    PERSONAL DESIGNADO
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    CAMBIAR DESIGNADO
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
                                    <h5>2.- Ingresar Solicitud Telefonica</h5>
                                    <hr>
                                    <br>
                                    <form action="../sdo-funciones/soliTelefono_agregar.php" method="post" enctype="multipart/form-data">


                                        <fieldset class="border p-3">
                                            <legend class="w-auto">&nbsp;Informaci&oacute;n del Funcionario:&nbsp;</legend>


                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputNomTarjeta">Nombre de Funcionario:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                        </div>
                                                        <input type="text" required style="text-transform:uppercase" class="form-control" id="nomSoliTelefono" name="nomSoliTelefono" placeholder="Nombre y Apellido de Funcionario">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputNomTarjeta">Cargo de Funcionario:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                        </div>
                                                        <input type="text" required style="text-transform:uppercase" class="form-control" id="cargoSoliTelefono" name="cargoSoliTelefono" placeholder="Cargo de Funcionario">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputNomTarjeta">Unidad de Funcionario:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                        </div>
                                                        <input type="text" required style="text-transform:uppercase" class="form-control" id="unidadSoliTelefono" name="unidadSoliTelefono" placeholder="Unidad de Funcionario">
                                                    </div>
                                                </div>
                                            </div>

                                        </fieldset>

                                        <br>
                                        <br>
                                        <fieldset class="border p-3">
                                            <legend class="w-auto">&nbsp;Informaci&oacute;n de la Solicitud:&nbsp;</legend>


                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputNomTarjeta">Recinto (PMA: X-X-X):<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-home"></i></span>
                                                        </div>
                                                        <input type="text" style="text-transform:uppercase" class="form-control" id="recintoSoliTelefono" name="recintoSoliTelefono" placeholder="Recinto (PMA: X-X-X)">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-12">
                                                    <label for="exampleInputNomTarjeta">Detalle de Solicitud:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                        </div>
                                                        <textarea id="detalleSoliTelefono" style="resize:none;" name="detalleSoliTelefono" class="form-control" rows="8" placeholder="Detalle de Solicitud..."></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                        </fieldset>
                                        <hr>



                                        <div class="form-group">

                                            <input type="submit" class="btn btn-block btn-primary" value="Guardar" />

                                        </div>



                                    </form>
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
    <!-- Page specific script -->
    <script>
        $(function() {
            $("#example3").DataTable({
                "buttons": [{
                    extend: 'excelHtml5',
                    text: 'Exportar a Excel',
                    title: 'SOLICITUDES TELEFONICAS - SDO',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                }],
                "columnDefs": [{
                    target: 7,
                    visible: false,
                }, ],
                "responsive": {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal({


                            header: function(row) {
                                var data = row.data();
                                return 'Informaci&oacute;n de Solicitud Telefonica: ';
                            },
                            autoWidth: true,
                        }),
                        renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                            tableClass: 'table col-12 text-justify align-items-center'
                        })
                    }
                },
                "lengthChange": false,
                "autoWidth": false,
                "paging": true,
                "searching": true,
                "ordering": false,
                "info": true,
                "language": {
                    "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                    "zeroRecords": "No se han encontrado Registros.",
                    "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                    "infoEmpty": "No existen Registros.",
                    "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                    "paginate": {
                        "previous": "Anterior",
                        "next": "Siguiente"
                    },
                    "search": "Buscar:"
                }
            }).buttons().container().appendTo('#example3_wrapper .col-md-6:eq(0)');
        });
    </script>
    <!-- <script>
        function myFunction(name) {
            alert(name)
        }
    </script> -->
</body>

</html>