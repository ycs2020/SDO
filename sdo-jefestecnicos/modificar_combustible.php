<?php

include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);



if(isset($_POST['idAC'])){
    $idAC = $_POST['idAC'];
    $sql = "SELECT * FROM combustible_administracion WHERE idAC = $idAC";
    $res = mysqli_query($conexion,$sql);
    while($row = $res->fetch_assoc()){
        $datos = [''];
        $datos['fechaAC'] = $row['fechaAC'];
        $datos['ordenCompraAC'] = $row['ordenCompraAC'];
        $datos['facturaAC'] = $row['facturaAC'];
        $datos['boletaAC']  = $row['boletaAC'];
        $datos['guiaDespachoAC'] = $row['guiaDespachoAC'];
        $datos['noCargaAC'] = $row['noCargaAC'];
        $datos['tanque1AC'] = $row['tanque1AC'];
        $datos['tanque2AC'] = $row['tanque2AC'];
        $datos['tanque3AC'] = $row['tanque3AC'];
        $datos['tanque4AC'] = $row['tanque4AC'];
        $datos['precioAC'] = $row['precioAC'];
        $datos['observacionAC'] = $row['observacionAC'];
        $datos['tipoAC'] = $row['tipoAC'];

        $fechaAC = $datos['fechaAC'];
        $ordenCompraAC = $datos['ordenCompraAC'];
        $facturaAC = $datos['facturaAC'];
        $boletaAC = $datos['boletaAC'];
        $guiaDespachoAC = $datos['guiaDespachoAC'];
        $noCargaAC = $datos['noCargaAC'];
        $tanque1AC = $datos['tanque1AC'];
        $tanque2AC = $datos['tanque2AC'];
        $tanque3AC = $datos['tanque3AC'];
        $tanque4AC = $datos['tanque4AC'];
        $precioAC = $datos['precioAC'];
        $observacionAC = $datos['observacionAC'];
        $tipoAC = $datos['tipoAC'];


        if(empty($facturaAC)){
            $facturaAC = '-';

        }
        if(empty($boletaAC)){
            $boletaAC = '-';

        }
        if(empty($guiaDespachoAC)){
            $guiaDespachoAC = '-';

        }
        if(empty($precioAC)){
            $precioAC = '0';
        }
        if(empty($observacionAC)){
            $observacionAC = 'NO EXISTEN OBSERVACIONES';
        }

    }

}
date_default_timezone_set('America/Santiago');

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modificar Combustible</title>

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
                $("#verDashh").hide();
                $("#ocultarDashAC").show();
                $("#ocultarDashh").show();
            });

            $(".show-ocultardash").click(function() {
                $("#rowMenu3").hide();
                $("#verDashAC").show();
                $("#verDashh").show();
                $("#ocultarDashAC").hide();
                $("#ocultarDashh").hide();
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
<body class="hold-transition sidebar-mini ">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/jefes-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Modificar Combustible</h4>
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
        if (isset($_GET['exitoAsignarPersonal'])) {
            ?>
            <script>
                Swal.fire(
                    'Asignaci&oacute;n de Personal con &Eacute;xito.',
                    '',
                    'success'
                )
            </script>
            <?php
        }

        if (isset($_GET['errorAsignarPersonal'])) {
            ?>
            <script>
                Swal.fire(
                    '¡ Ha ocurrido un Problema !',
                    'Error al Asignar Personal a la Solicitud.',
                    'error'
                )
            </script>
            <?php
        }

        if (isset($_GET['exitoCambiarDepartamento'])) {
            ?>
            <script>
                Swal.fire(
                    'Cambio de Departamento Responsable con &Eacute;xito.',
                    '',
                    'success'
                )
            </script>
            <?php
        }

        if (isset($_GET['errorCambiarDepartamento'])) {
            ?>
            <script>
                Swal.fire(
                    '¡ Ha ocurrido un Problema !',
                    'Error al Cambiar Departamento Responsable.',
                    'error'
                )
            </script>
            <?php
        }

        if (isset($_GET['exitoCancelarSolicitud'])) {
            ?>
            <script>
                Swal.fire(
                    'Solicitud Cancelada con &Eacute;xito.',
                    '',
                    'success'
                )
            </script>
            <?php
        }

        if (isset($_GET['errorCancelarSolicitud'])) {
            ?>
            <script>
                Swal.fire(
                    '¡ Ha ocurrido un Problema !',
                    'Error al Cancelar Solicitud. Reintente.',
                    'error'
                )
            </script>
            <?php
        }

        if (isset($_GET['exitoAgregarObservacion'])) {
            ?>
            <script>
                Swal.fire(
                    'Observaci&oacute;n Agregada con &Eacute;xito.',
                    '',
                    'success'
                )
            </script>
            <?php
        }

        if (isset($_GET['errorAgregarObservacion'])) {
            ?>
            <script>
                Swal.fire(
                    '¡ Ha ocurrido un Problema !',
                    'Error al Agregar Observaci&oacute;n. Reintente.',
                    'error'
                )
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

                                <a href="combustible_visor.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>

                            </div>
                            <div class="card-body">

                                <form action="../sdo-funciones/modificar-combustible.php" method="post" enctype="multipart/form-data">

                                    <h4 class="card-title">
                                        <b>
                                            <u>Informaci&oacute;n de la carga de combustible</u>
                                        </b>
                                    </h4>

                                    <br>

                                    <br>



                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputQuienSolicita">Fecha de Medicion:<code></code></label>
                                            <div class="input-group mb-3" id="demo">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                </div>
                                                <input type="datetime-local" style="text-transform:capitalize" class="form-control" id="fechaMedicion" name="fechaMedicion" value="<?php echo $fechaAC ?>" >
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="exampleInputQuienSolicita">Factura:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file-invoice-dollar"></i></span>
                                                </div>
                                                 <input type="text" style="text-transform:capitalize" class="form-control" id="facturaAC" name="facturaAC" value="<?php echo $facturaAC ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputRounded0">Boleta: <code>*</code></label>
                                            <div class="input-group mb-2">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-ticket-alt"></span>
                                                    </div>
                                                </div>
                                                <input type="text" id="boletaAC" required name="boletaAC" class="form-control" placeholder="Departamento Responsable" value="<?php echo $boletaAC; ?>">

                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputRounded0">Guia de Despacho: <code></code></label>
                                            <div class="input-group mb-2">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-tools"></span>
                                                    </div>
                                                </div>
                                              <input type="text" class="form-control"  id="guiaDespachoAC" name="guiaDespachoAC" required value="<?php echo $guiaDespachoAC?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputRounded0">Número Carga: <code>*</code></label>
                                            <div class="input-group mb-2">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-ticket-alt"></span>
                                                    </div>
                                                </div>
                                                <input type="text" id="noCarga" required name="noCargaAC" class="form-control" placeholder="Departamento Responsable" value="<?php echo $noCargaAC; ?>">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputRounded0">Tanque 1: <code>*</code></label>
                                            <div class="input-group mb-2">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-sort-numeric-up"></span>
                                                    </div>
                                                </div>
                                                <input type="number" id="tanque1AC" required name="tanque1AC" class="form-control" placeholder="Departamento Responsable" value="<?php echo $tanque1AC; ?>">

                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputRounded0">Tanque 2: <code>*</code></label>
                                            <div class="input-group mb-2">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-sort-numeric-up"></span>
                                                    </div>
                                                </div>
                                                <input type="number" id="tanque2AC" required name="tanque2AC" class="form-control" placeholder="Departamento Responsable" value="<?php echo $tanque2AC;?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputRounded0">Tanque 3: <code>*</code></label>
                                            <div class="input-group mb-2">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-sort-numeric-up"></span>
                                                    </div>
                                                </div>
                                                <input type="number" id="tanque3AC" required name="tanque3AC" class="form-control" placeholder="Departamento Responsable" value="<?php echo $tanque3AC; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputRounded0">Tanque 4: <code>*</code></label>
                                            <div class="input-group mb-2">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-sort-numeric-up"></span>
                                                    </div>
                                                </div>
                                                <input type="number" id="tanque4AC" required name="tanque4AC" class="form-control" placeholder="Departamento Responsable" value="<?php echo $tanque4AC; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputRounded0">Precio: <code>*</code></label>
                                            <div class="input-group mb-2">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-money-bill-wave"></span>
                                                    </div>
                                                </div>
                                                <input type="text" id="precioAC" required name="precioAC" class="form-control" placeholder="Departamento Responsable" value="<?php echo $precioAC; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputRounded0">Tipo de Procedimiento: <code>*</code></label>
                                            <div class="input-group mb-2">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-list"></span>
                                                    </div>
                                                </div>
                                                <select id="selectProce" name="selectProce" class="form-control">
                                                    <?php
                                                        $medicion = 'Medición Normal';
                                                        $carga = 'Carga de Combustible';

                                                        if($tipoAC == 'Medición Normal'){
                                                    ?>
                                                            <option value="<?php echo $medicion?>" selected="selected">Medición Normal</option>
                                                            <option value="<?php echo $carga?>">Carga de Combustible</option>
                                                            <?php
                                                        } else{
                                                            ?>
                                                            <option value="<?php echo $carga?>" selected="selected">Carga de Combustible</option>
                                                            <option value="<?php echo $medicion?>">Medición Normal</option>
                                                            <?php
                                                        }
                                                    ?>
                                                </select>
                                               <!-- <input type="text" id="precioAC" required name="precioAC" class="form-control" placeholder="Departamento Responsable" value="<?php //echo $precioAC; ?>"> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputRounded0">Observaciones: </label>
                                        <div class="input-group mb-2">
                                            <textarea id="observacion"  style="resize:none;" name="observacion" class="form-control" rows="8"><?php echo $observacionAC?></textarea>
                                        </div>
                                    </div>

                                    <br>
                                    <br>


                                    <hr>

                                    <br>
                                    <br>

                                    <div class="form-group">
                                        <input type="hidden" name="idAC" id="idAC" value="<?php echo $idAC?>">
                                        <input type="hidden" name="usuarioAC" id="usuarioAC" value="<?php echo $nomUsuario?>">
                                        <input type="submit" onclick="myFunction()" class="btn btn-block btn-success" value="Modificar" />

                                    </div>
                                    <div class="form-group">
                                        <a href="index.php" class="btn btn-block btn-warning"> Volver </a>
                                    </div>


                                </form>

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
<script src="../dist/js/adminlte.min.js"></script>
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
<script>
    $(document).ready(function() {
        $('#tabla-equipos').DataTable({
            "paging": true,
            "iDisplayLength": 10,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "search": "Buscar:",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
            initComplete: function() {
                this.api().columns([1]).every(function() {
                    var column = this;
                    var select = $('<select style="width: 100%;"><option value="">Nombre Equipo</option></select>')
                        .appendTo($(column.header()).empty())
                        .on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                    column.data().unique().sort().each(function(d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });
                this.api().columns([4]).every(function() {
                    var column = this;
                    var select = $('<select style="width: 100%;"><option value="">Piso</option></select>')
                        .appendTo($(column.header()).empty())
                        .on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });
                    column.data().unique().sort().each(function(d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });

            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#tabla-tickets').DataTable({
            "paging": true,
            "iDisplayLength": 10,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "search": "Buscar:",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#tabla-hojas-mpi').DataTable({
            "paging": true,
            "iDisplayLength": 10,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "search": "Buscar:",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#tabla-hojas-mri').DataTable({
            "paging": true,
            "iDisplayLength": 10,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "search": "Buscar:",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#tabla-equipo-observacion').DataTable({
            "paging": true,
            "iDisplayLength": 10,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "search": "Buscar:",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#tabla-equipo-falla').DataTable({
            "paging": true,
            "iDisplayLength": 10,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "search": "Buscar:",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#tabla-gastos-mre').DataTable({
            "paging": true,
            "iDisplayLength": 10,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "search": "Buscar:",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#tabla-gastos-mpe').DataTable({
            "paging": true,
            "iDisplayLength": 10,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "search": "Buscar:",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
        });
    });
</script>
<script>
    $(document).ready(
        $('.eliminar').click(function(e) {
            e.preventDefault();
            let id = $(this).attr('id');
            var dataString = 'id=' + id;
            Swal.fire({
                icon: 'question',
                title: '¿Eliminar este equipo?',
                text: 'Esta acción no se puede revertir',
                showConfirmButton: true,
                confirmButtonText: 'Si , eliminar',
                confirmButtonColor: '#3085d6',
                showCancelButton: true,
                cancelButtonText: 'No, lo pensare',
                cancelButtonColor: '#d33',
                buttonsStyling: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../sdo-funciones/eliminar-equipo-carpeta.php',
                        type: 'POST',
                        data: dataString,
                        success: function(response) {
                            console.log('eliminado.. ' + dataString);
                            Swal.fire({
                                icon: 'success',
                                title: 'Registro del equipo eliminado',
                                showConfirmButton: false,
                                timer: 2000,
                            })
                            setTimeout(function() {
                                location.reload();
                            }, 2100)
                        }
                    })
                }
            })
        })
    )
</script>
<script>

    let id = document.getElementById('id').value;

    fetch('../sdo-funciones/obtener_tarea_programacion.php?id=' + id)
        .then(response => response.json())
        .then(data => {
            let jsonData = JSON.stringify(data);
            console.log(jsonData);
            anychart.onDocumentReady(function () {
                anychart.format.inputLocale("en");

                // create a data tree
                var treeData = anychart.data.tree(jsonData, "as-tree");
                // create a chart
                var chart = anychart.ganttProject();
                // set the data
                chart.data(treeData);
                // set the container id
                chart.container("container");
                // initiate drawing the chart
                chart.draw();
                // fit elements to the width of the timeline
                chart.fitAll();
            });

        })
        .catch(error => {
            console.log('Error en la consulta Fetch API: ', error)
        })
</script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

    let gastoTotal = document.getElementById('gastoTotal').value;

    let costoMRE = document.getElementById('costoMRE').value;
    let costoMPI = document.getElementById('costoMPI').value;
    let costoMRI = document.getElementById('costoMRI').value;
    let costoMPE = document.getElementById('costoMPE').value;
    let costoOM = document.getElementById('costoOM').value;
    let costoIS = document.getElementById('costoIS').value;

    costoMRE = parseInt(costoMRE);
    costoMPI = parseInt(costoMPI);
    costoMRI = parseInt(costoMRI);
    costoMPE = parseInt(costoMPE);
    costoOM = parseInt(costoOM);
    costoIS = parseInt(costoIS);

    // Load the Visualization API and the piechart package.
    google.charts.load('current', {'packages':['corechart']});

    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChart);

    // Callback that creates and populates a data table,
    // instantiates the pie chart, passes in the data and
    // draws it.
    function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
            ['MPI', costoMPI],
            ['MRI', costoMRI],
            ['MPE', costoMPE],
            ['MRE', costoMRE],
            ['OM', costoOM],
            ['IS', costoIS],
        ]);

        // Set chart options
        var options = {'title':' Total: ' + gastoTotal ,
            'width':400,
            'height':300,
            colors: ['#5bc0de', '#d9534f', '#7c8184', '#59287a', '#2972fa', '#fab633']
        };

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }

    let cantidadMRE = document.getElementById('cantidadMRE').value;
    let cantidadMPI = document.getElementById('cantidadMPI').value;
    let cantidadMRI = document.getElementById('cantidadMRI').value;
    let cantidadMPE = document.getElementById('cantidadMPE').value;
    let cantidadOM = document.getElementById('cantidadOM').value;
    let cantidadIS = document.getElementById('cantidadIS').value;

    console.log(cantidadMRE);

    cantidadMRE = parseInt(cantidadMRE);
    cantidadMPI = parseInt(cantidadMPI);
    cantidadMRI = parseInt(cantidadMRI);
    cantidadMPE = parseInt(cantidadMPE);
    cantidadOM = parseInt(cantidadOM);
    cantidadIS = parseInt(cantidadIS);

    google.charts.setOnLoadCallback(drawChartHojas);

    function drawChartHojas() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Tipos');
        data.addColumn('number', 'Cantidad');
        data.addRows([
            ['MPI', cantidadMPI],
            ['MRI', cantidadMRI],
            ['MPE', cantidadMPE],
            ['MRE', cantidadMRE],
            ['OM', cantidadOM],
            ['IS', cantidadIS],
        ]);

        // Instantiate and draw our chart, passing in some options.
        var barchart_options = {title:'Cantidad de tipos de mantenciones',
            width:400,
            height:300,
            legend: 'none',
            colors: ['#5bc0de', '#d9534f', '#7c8184', '#59287a', '#2972fa', '#fab633']
        };
        var barchart = new google.visualization.PieChart(document.getElementById('grafico_barras'));
        barchart.draw(data, barchart_options);
    }

</script>
</body>
</html>


