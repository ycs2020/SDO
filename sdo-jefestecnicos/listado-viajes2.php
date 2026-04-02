<?php

include_once '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set("America/Santiago");
setlocale(LC_TIME, 'es_CL.UTF-8', 'esp');

if (isset($_POST['idViaje'])) {
    $idViaje = $_POST['idViaje'];

    if ($idViaje > 0) {
        if (empty($idViaje) || $idViaje == '') {
        } else {

            ini_set("memory_limit", "1256M");
            $zip = new ZipArchive();
            $dir = "../sdo-archivos-viajesMovilizacion/registro-" . $idViaje;
            $dir2 = $dir . "/";
            $rutaFinal = "../sdo-jefestecnicos/";
            $archivoZip = "Archivos-viaje.zip";
            if ($zip->open($archivoZip, ZIPARCHIVE::CREATE) === true) {
                agregar_zip($dir2, $zip);
                $zip->close();
                // echo '<script type="text/javascript">alert("'.$dir.'");</script>';
                if (file_exists($rutaFinal . $archivoZip)) {
                    $absoluteFilePath = $rutaFinal . "/" . $archivoZip;
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename=' . basename($archivoZip));
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($archivoZip));
                    ob_clean();
                    flush();
                    readfile($archivoZip);
                    unlink($archivoZip); //Esta línea destruye el elemento zip que se genera
                    exit;
                } else {
                    $descargaConfirma .= "N";
                }
            }
        }
    }
} {
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulario de viajes</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">

    <script src="../sdo-canvasdraw1/jquery.min.js"></script>
    <script src="../sdo-canvasdraw1/signature_pad.js"></script>
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
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

                        </div>

                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <!-- Main content -->
            <?php

            if (isset($_GET['noNumero'])) {
            ?>
                <script>
                    Swal.fire(
                        'oh Problemas!',
                        'No ingreso Numero de documento!',
                        'error'
                    )
                </script>
            <?php
            }

            if (isset($_GET['existenVacios'])) {
            ?>
                <script>
                    Swal.fire(
                        'oh Problemas!',
                        'Todos los inputs son obligatorios!',
                        'error'
                    )
                </script>
            <?php
            }

            if (isset($_GET['exitoCrearViaje'])) {
            ?>
                <script>
                    Swal.fire(
                        'Bien Echo!',
                        'Se ha creado el registro del viaje correctamente!',
                        'success'
                    )
                </script>
            <?php
            }

            if (isset($_GET['noValorViaje'])) {
            ?>
                <script>
                    Swal.fire(
                        'oh Problemas!',
                        'No fue posible generar el valor del viaje!',
                        'error'
                    )
                </script>
            <?php
            }

            if (isset($_GET['errorCrearViaje'])) {
            ?>
                <script>
                    Swal.fire(
                        'oh Problemas!',
                        'Erro al crear el viaje!',
                        'error'
                    )
                </script>
            <?php
            }

            if (isset($_GET['exitoModificar'])) {
            ?>
                <script>
                    Swal.fire(
                        'Exito!',
                        'Registro modificado correctamente!',
                        'success'
                    )
                </script>
            <?php
            }

            if (isset($_GET['errorModificar'])) {
            ?>
                <script>
                    Swal.fire(
                        'oh Problemas!',
                        'Error al modificar registro!',
                        'error'
                    )
                </script>
            <?php
            }

            ?>
            <section class="content">
                <div class="container-fluid">

                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-12">

                            <!-- general form elements -->
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4 class="card-title"> <b> Listado de viajes realizados</b></h4>
                                </div>
                                <!-- /.card-header -->

                                <div class="card-body">

                                    <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;<b>Volver Atras</b> </a>
                                    <br>
                                    <br>
                                    <div class="row justify-content-center">
                                        <button class="btn btn-success" data-toggle="modal" data-target=".modalViaje"><b><i class="fas fa-plus"></i> Añadir Viaje</b> </button>

                                    </div>

                                    <div class="modal fade modalViaje" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success">
                                                    <h5 class="modal-title" id="exampleModalLabel"> <b>Formulario - Información del viaje</b> </h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="../sdo-funciones/guardar-viaje-movilizacion.php" method="POST" enctype="multipart/form-data">

                                                        <div class="row">
                                                            <div class="form-group col-md-4">
                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px">Numero Boleta:<code>*</code></label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control form-control-sm" required id="numeroViaje" name="numeroViaje">
                                                                    <p id="mensaje"></p>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px">Fecha:<code>*</code></label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                    </div>
                                                                    <input type="date" required class="form-control form-control-sm" id="fechaViaje" name="fechaViaje">
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px">Patente:<code>*</code></label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-car"></i></span>
                                                                    </div>
                                                                    <input type="text" name="patenteVehiculo" class="form-control form-control-sm" required id="patenteVehiculo">

                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-md-4">
                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px">Tipo Vehiculo:<code>*</code></label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-car"></i></span>
                                                                    </div>
                                                                    <select type="text" name="tipoVehiculo" class="form-control form-control-sm" required id="tipoVehiculo">
                                                                        <option value="" selected disabled>-- Seleccione --</option>
                                                                        <option value="1">Sedan/Sub o Camioneta</option>
                                                                        <option value="2">Van</option>
                                                                        <option value="3">3/4 Pick Up o camioneta de carga</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px">Conductor:<code>*</code></label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                                    </div>
                                                                    <input type="text" id="conductorViaje" name="conductorViaje" class="form-control form-control-sm">
                                                                </div>
                                                            </div>
                                                            <div class="destino"></div>
                                                            <div class="form-group col-md-4">
                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px">Pasajero:</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control form-control-sm" id="pasajeroViaje" name="pasajeroViaje">
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <!-- <label for="customFile">Custom File</label> -->
                                                                <label for="exampleInputTareasControlCent" style="font-size:12px;">Pto. Partida:</label><br>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-street-view"></i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control form-control-sm" id="partidaViaje" name="partidaViaje">
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px">Motivo:<code>*</code></label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                                    </div>
                                                                    <input type="text" id="motivoViaje" name="motivoViaje" class="form-control form-control-sm">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">

                                                            <div class="form-group col-md-4">
                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px">Destino:<code>*</code></label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                                                    </div>
                                                                    <select name="destinoViaje" required id="destinoViaje" class="form-control form-control-sm">
                                                                        <option value="" disabled selected>-- Seleccione --</option>
                                                                        <option value="Comuna de Quillota">Comuna de Quillota</option>
                                                                        <option value="Provincia de Quillota">Provincia de Quillota</option>
                                                                        <option value="Vina del mar">Viña del mar</option>
                                                                        <option value="Valparaiso">Valparaiso</option>
                                                                        <option value="Region Metropolitana">Región Metropolitana</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="form-group col-md-4">
                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px">Hora Inicio:<code>*</code></label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-hourglass-half"></i></span>
                                                                    </div>
                                                                    <input type="time" required class="form-control form-control-sm" id="horaInicio" name="horaInicio">
                                                                </div>
                                                            </div>

                                                            <div class="form-group col-md-4">
                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px">Hora Termino:<code>*</code></label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-hourglass-half"></i></span>
                                                                    </div>
                                                                    <input type="time" required class="form-control form-control-sm" id="horaTermino" name="horaTermino">
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-md-6">
                                                                <!-- <label for="customFile">Custom File</label> -->
                                                                <label for="exampleInputTareasControlCent" style="font-size:12px;">Subir Archivos:</label><br>
                                                                <div class="custom-file">
                                                                    <input type="file" multiple class="custom-file-input" id="archivosViajes" name="archivosViajes">
                                                                    <label class="custom-file-label" for="customFile" style="font-size:12px;"><b>Seleccionar Archivos...</b> </label>
                                                                </div>
                                                            </div>

                                                        </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-warning" data-dismiss="modal"> <b>Cerrar</b> </button>
                                                    <button type="submit" disabled id="AnadirViaje" class="btn btn-success"> <b>Añadir Viaje</b> </button>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <hr>

                                    <table id="example2" class="table table-bordered table-hover text-center ">
                                        <thead class="bg-secondary">
                                            <tr>
                                                <th scope="col">Fecha</th>
                                                <th scope="col">Destino</th>
                                                <th scope="col">Patente</th>
                                                <th scope="col">Tipo de Vehiculo</th>
                                                <th scope="col">Motivo</th>
                                                <th scope="col">Conductor</th>
                                                <th scope="col">Cantidad</th>
                                                <th scope="col">N° Boleta</th>
                                                <th scope="col">Valor</th>
                                                <th scope="col">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php

                                            $sqlViaje = "SELECT * FROM viajes_movilizacion ORDER BY idViaje DESC";
                                            $resViaje = mysqli_query($conexion, $sqlViaje);

                                            while ($row = $resViaje->fetch_assoc()) {

                                                $datos = [];
                                                $datos['idViaje'] = $row['idViaje'];
                                                $datos['valorViaje'] = $row['valorViaje'];
                                                $datos['numeroViaje'] = $row['numeroViaje'];
                                                $datos['fechaViaje'] = $row['fechaViaje'];
                                                $datos['tipoVehiculo'] = $row['tipoVehiculo'];
                                                $datos['patenteVehiculo'] = $row['patenteVehiculo'];
                                                $datos['conductorViaje'] = $row['conductorViaje'];
                                                $datos['pasajeroViaje'] = $row['pasajeroViaje'];
                                                $datos['partidaViaje'] = $row['partidaViaje'];
                                                $datos['destinoViaje'] = $row['destinoViaje'];
                                                $datos['tipoJornada'] = $row['tipoJornada'];
                                                $datos['motivoViaje'] = $row['motivoViaje'];


                                                $idViaje = $datos['idViaje'];
                                                $valorViaje = $datos['valorViaje'];
                                                $numeroViaje = $datos['numeroViaje'];
                                                $tipoVehiculo = $datos['tipoVehiculo'];
                                                $fechaViaje = $datos['fechaViaje'];
                                                $patenteVehiculo = $datos['patenteVehiculo'];
                                                $conductorViaje = $datos['conductorViaje'];
                                                $pasajeroViaje = $datos['pasajeroViaje'];
                                                $partidaViaje = $datos['partidaViaje'];
                                                $destinoViaje = $datos['destinoViaje'];
                                                $tipoJornada = $datos['tipoJornada'];
                                                $motivoViaje = $datos['motivoViaje'];

                                                $valorViaje = moneda_chilena($valorViaje);

                                            ?>
                                                <tr>
                                                    <td style="font-size: 12px">
                                                        <?php

                                                        $fechaViaje = date('d-m-Y', (strtotime(str_replace('/', '-', $row['fechaViaje']))));

                                                        echo $fechaViaje
                                                        ?>
                                                    </td>
                                                    <td style="font-size: 12px"><?php echo strtoupper($destinoViaje) ?> </td>
                                                    <td style="font-size: 12px"><?php echo strtoupper($patenteVehiculo) ?> </td>
                                                    <td style="font-size: 12px">
                                                        <?php
                                                        if ($tipoVehiculo == 1) {
                                                            $tipoVehiculo = 'AUTOMOVIL';
                                                        } else if ($tipoVehiculo == 2) {
                                                            $tipoVehiculo = 'VAN';
                                                        } else if ($tipoVehiculo == 3) {
                                                            $tipoVehiculo = 'CAMIONETA DE CARGA';
                                                        }
                                                        echo $tipoVehiculo

                                                        ?>
                                                    </td>
                                                    <td style="font-size: 12px"><?php echo strtoupper($motivoViaje)  ?> </td>
                                                    <td style="font-size: 12px"><?php echo strtoupper($conductorViaje) ?> </td>
                                                    <td style="font-size: 12px"><?php echo strtoupper($tipoJornada)  ?> </td>
                                                    <td style="font-size: 12px" class="text-danger text-bold"> <?php echo $numeroViaje ?> </td>
                                                    <td style="font-size: 12px" class="text-success text-bold"> <?php echo $valorViaje ?> </td>
                                                    <td style="font-size: 12px" class="text-success text-bold">
                                                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">

                                                            <div class="btn-group" role="group">
                                                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">

                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    <form action="editar-viaje-movilizacion.php" method="post" enctype="multipart/form-data">
                                                                        <input type="hidden" name="idViaje" value="<?php echo $idViaje ?>">
                                                                        <button class="dropdown-item" tyoe="submit"><i class="fas fa-edit"></i> Editar </button>
                                                                    </form>
                                                                    <form action="listado-viajes.php" method="POST">
                                                                        <input type="hidden" name="idViaje" value="<?php echo $idViaje ?>">
                                                                        <button type="submit" class="dropdown-item" href="#"><i class="fas fa-download"></i> Descargar </button>
                                                                    </form>
                                                                    <a class="dropdown-item eliminar" id="<?php echo $idViaje ?>" href="#"><i class="fas fa-trash-alt"></i> Eliminar</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                            <?php
                                            }

                                            ?>

                                        </tbody>
                                        <tfoot class="bg-secondary">
                                            <th scope="col">Fecha</th>
                                            <th scope="col">Destino</th>
                                            <th scope="col">Patente</th>
                                            <th scope="col">Tipo de Vehiculo</th>
                                            <th scope="col">Motivo</th>
                                            <th scope="col">Conductor</th>
                                            <th scope="col">Cantidad</th>
                                            <th scope="col">N° Boleta</th>
                                            <th scope="col">Valor</th>
                                            <th scope="col">Acciones</th>
                                        </tfoot>
                                    </table>



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
            bsCustomFileInput.init();
        });
    </script>
    <!-- <script>
        $(document).ready(function() {
            $('#table_equipos').DataTable({
                "buttons": [{
                    extend: 'excelHtml5',
                    text: 'Exportar a Excel',
                    title: 'Resumen Solicitudes de Compra',
                    exportOptions: {
                        columns: [0,1,2,3],
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
                "lengthChange": true,
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
                    "search": "Buscar:",
                    "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                    "paginate": {
                        "previous": "Anterior",
                        "next": "Siguiente"
                    }
                }
            })
        });
    </script> -->
    <script>
        $(function() {

            $('#example2').DataTable({
                "buttons": [{
                    extend: 'excelHtml5',
                    text: 'Exportar a Excel',
                    title: 'Resumen Viajes Movilización',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
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
                },
                initComplete: function() {
                    this.api().columns([4]).every(function() {
                        var column = this;
                        var select = $('<select style="width: 100%;"><option value="">Seleccione Motivo...</option></select>')
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
                    this.api().columns([2]).every(function() {
                        var column = this;
                        var select = $('<select style="width: 100%;"><option value="">Seleccione Patente...</option></select>')
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
            }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
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
                    title: '¿Eliminar el registro de este viaje?',
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
                            url: '../sdo-funciones/eliminar-viaje-movilizacion.php',
                            type: 'POST',
                            data: dataString,
                            success: function(response) {
                                console.log('eliminado.. ' + dataString);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Registro del viaje eliminado',
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
        $("#numeroViaje").blur(function() {
            AnadirViaje = document.getElementById('AnadirViaje');
            campo = document.getElementById('numeroViaje');
            mensaje = document.getElementById('mensaje');
            valor = $(this).val();
            var dataString = 'valor=' + valor;
            console.log(valor);
            $.ajax({
                url: 'consulta-numero-viaje.php',
                type: 'POST',
                data: dataString,
                success: function(response) {
                    console.log(response)
                    if (response == '1') {
                        AnadirViaje.disabled = true;
                        campo.style.borderColor = "red";
                        mensaje.textContent = 'Ya existe el codigo ingresado';
                        mensaje.style.color = "red";
                        mensaje.style.fontWeight = "bold";

                    } else if (response == '0') {

                        AnadirViaje.disabled = false;
                        campo.style.borderColor = "green";
                        mensaje.textContent = 'Todo ok, llene el formulario';
                        mensaje.style.color = "green";
                        mensaje.style.fontWeight = "bold";

                    }
                }
            })

        });
    </script>
</body>

</html>