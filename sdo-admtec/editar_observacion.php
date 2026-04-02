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


date_default_timezone_set('America/Santiago');


if (isset($_POST['idCambio'])) {
    $idCambio = $_POST['idCambio'];
    $sql = "SELECT * FROM historial_cambios_equipos WHERE idCambio = $idCambio";
    $res = mysqli_query($conexion,$sql);
    while($row = $res->fetch_assoc()) {
        $datos = [''];
        $datos['fecha'] = $row['fecha'];
        $datos['estado'] = $row['estado'];
        $datos['observaciones'] = $row['observaciones'];
        $datos['idEquipo'] = $row['idEquipo'];
        $datos['idCategoria'] = $row['idCategoria'];
        $datos['tipoUsuario'] = $row['tipoUsuario'];
        $datos['idUsuarioCambia'] = $row['idUsuarioCambia'];
        $datos['nombreEquipo'] = $row['nombreEquipo'];
        $nombreEquipo1 = $row['nombreEquipo'];
        $fecha = $datos['fecha'];
        $estado = $datos['estado'];
        $observaciones = $datos['observaciones'];
        $idEquipo = $datos['idEquipo'];
        $idCategoria = $datos['idCategoria'];
        $tipoUsuario = $datos['tipoUsuario'];
        $idUsuarioCambia = $datos['idUsuarioCambia'];


        /* echo $fecha;
         echo '<br>';
         echo $estado;
         echo '<br>';
         echo $observaciones;
         echo '<br>';
         echo $idEquipo;
         echo '<br>';
         echo $idCategoria;
         echo '<br>';
         echo $tipoUsuario;
         echo '<br>';
         echo $idUsuarioCambia;*/
        $sqlEquipo = "SELECT * FROM equipamiento_hbqp WHERE id = $idEquipo";
        $resEquipo = mysqli_query($conexion,$sqlEquipo);
        while($rowEquipo = $resEquipo->fetch_assoc()){
            $datos2 = [''];
            $datos2['nombreEquipo'] = $rowEquipo['nombreEquipo'];
            $nomEquipo = $datos2['nombreEquipo'];
            //  echo $nomEquipo;
        }





    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Observacion</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap4.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dhtmlx-gantt@8.0.1/codebase/dhtmlxgantt.min.css">
    <script src="https://cdn.jsdelivr.net/npm/dhtmlx-gantt@8.0.1/codebase/dhtmlxgantt.min.js"></script>
    <script src="https://cdn.anychart.com/releases/8.11.0/js/anychart-base.min.js" type="text/javascript"></script>
    <script src="https://cdn.anychart.com/releases/8.11.0/js/anychart-core.min.js" type="text/javascript"></script>
    <script src="https://cdn.anychart.com/releases/8.11.0/js/anychart-gantt.min.js" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            cargarTipoObservacion();

            $('#departamentoNuevoResponsable').change(function() {
                cargarTipoObservacion();
            });


            function cargarTipoObservacion() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-solicitud/cargarTipoSolicitud.php",
                    data: "idDepartamento=" + $('#departamentoNuevoResponsable').val(),
                    success: function(r) {
                        $('#dataTipoSolicitud').html(r);


                    }
                });

            };

        });
    </script>
    <script>
        function selectNit(e) {
            var nit =  e.target.selectedOptions[0].getAttribute("data-nit")
            document.getElementById("nit").value = nit;
        }
    </script>
</head>
<body class="hold-transition sidebar-mini ">
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
                        <h4>Editar Observacion</h4>
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

                                <a href="equipamiento_hbqp.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>

                            </div>
                            <div class="card-body">

                                <form action="../sdo-funciones/modificar-observacion.php" method="post" enctype="multipart/form-data">

                                    <h4 class="card-title">
                                        <b>
                                            <u>Informaci&oacute;n de la Observacion</u>
                                        </b>
                                    </h4>

                                    <br>

                                    <br>



                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputQuienSolicita">Fecha del Cambio de Estado:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                </div>
                                                <input type="date" style="text-transform:capitalize" class="form-control" id="fechaEstado" name="fechaEstado" value="<?php echo $fecha ?>" >
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="exampleInputQuienSolicita">Estado del Equipo:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-industry"></i></span>
                                                </div>
                                                <select name="estadoCambiar" class="form-control" required id="estadoCambiar">
                                                    <?php
                                                    $fuera = 'Fuera de Servicio';
                                                    $manten = 'Mantencion';
                                                    $opera = 'Operativo';
                                                    if ($estado == 'Fuera de Servicio') {
                                                        ?>
                                                        <option value="<?php echo $fuera?>" selected="selected">Fuera de Servicio</option>
                                                        <option value="<?php echo $manten?>">Mantencion</option>
                                                        <option value="<?php echo $opera?>">Operativo</option>
                                                        <?php
                                                    }
                                                    if($estado == 'Mantenimiento'){
                                                        ?>
                                                        <option value="<?php echo $manten?>" selected="selected">Mantencion</option>
                                                        <option value="<?php echo $fuera?>">Fuera de Servicio</option>
                                                        <option value="<?php echo $opera?>">Operativo</option>
                                                        <?php
                                                    }
                                                    if($estado == 'Operativo'){
                                                        ?>
                                                        <option value="<?php echo $opera?>" selected="selected">Operativo</option>
                                                        <option value="<?php echo $manten?>">Mantencion</option>
                                                        <option value="<?php echo $fuera?>">Fuera de Servicio</option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                                <!-- <input type="text" style="text-transform:capitalize" class="form-control" id="estadoEquipo" name="estadoEquipo" value="<?php //echo $estado ?>" placeholder="Departamento Solicitante"> -->
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputRounded0">Equipamiento Utilizado: <code>*</code></label>
                                            <div class="input-group mb-2">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-tools"></span>
                                                    </div>
                                                </div>
                                                <input type="text" id="nomEquipo" readonly="readonly" required name="nomEquipo" class="form-control" placeholder="Departamento Responsable" value="<?php echo $nomEquipo; ?>">

                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputRounded0">Si desea cambiar el equipamiento, por favor seleccione el equipamiento que desea: <code></code></label>
                                            <div class="input-group mb-2">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-tools"></span>
                                                    </div>
                                                </div>
                                                <select name="idSeleccionar" class="form-control" id="idSeleccionar" onchange="selectNit(event)">
                                                    <option value="Seleccionar">Seleccione una opcion....</option>
                                                    <?php
                                                    $sqlEquipamiento = "SELECT * FROM equipamiento_hbqp order by nombreEquipo ASC";
                                                    $resEquipamiento = mysqli_query($conexion,$sqlEquipamiento);
                                                    while($rowEquipamiento = $resEquipamiento->fetch_assoc()){
                                                        $datos3 = [''];
                                                        $datos3['id'] = $rowEquipamiento['id'];
                                                        $datos3['nombreEquipo'] = $rowEquipamiento['nombreEquipo'];
                                                        $datos3['identificadorUnico'] = $rowEquipamiento['identificadorUnico'];
                                                        $nombreEquipo = $datos3['nombreEquipo'];
                                                        $id = $datos3['id'];
                                                        $id1 = $datos3['identificadorUnico'];
                                                        ?>
                                                        <option data-nit="<?php echo $nombreEquipo?>" value="<?php echo $id;?>"><?php echo $nombreEquipo.'/ '.$id1 ?></option>
                                                        <?php
                                                    }

                                                    ?>

                                                </select>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleInputRounded0">Observaciones: </label>
                                        <div class="input-group mb-2">
                                            <textarea id="observacion"  style="resize:none;" name="observacion" class="form-control" rows="8"><?php echo $observaciones?></textarea>
                                        </div>
                                    </div>

                                    <br>
                                    <br>


                                    <hr>

                                    <br>
                                    <br>

                                    <div class="form-group">

                                        <input type="hidden" name="idCambio" id="idCambio" value="<?php echo $idCambio; ?>">
                                        <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipo;?>">
                                        <input type="hidden" name="idUsuario1" id="idUsuario1" value="<?php echo $idUsuarioCambia; ?>">
                                        <input type="hidden" name="idCategoria" id="idCategoria" value="<?php echo $idCategoria?>">
                                        <input type="hidden" name="tipoUsuario" id="tipoUsuario" value="<?php echo $tipoUsuario?>">
                                        <input type="hidden" name="idUsuarioObs" id="idUsuarioObs" value="<?php echo $id1?>">
                                        <input type="hidden" name="rolusu" id="rolusu" value="<?php echo $rolusu?>">
                                        <input id="nit" type="hidden" name="nombreEquipo" id="nombreEquipo">
                                        <input type="submit" class="btn btn-block btn-success" value="Modificar" />

                                    </div>
                                    <div class="form-group">
                                        <a href="equipamiento_hbqp.php" class="btn btn-block btn-warning"> Volver </a>
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


