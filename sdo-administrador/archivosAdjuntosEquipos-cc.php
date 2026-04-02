<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// echo '';

if (isset($_POST['idGasto'])) {
    $idGasto = $_POST['idGasto'];
    $idEquipo = $_POST['idEquipo'];

    if ($idGasto > 0) {
        if (empty($idGasto) || $idGasto == '') {
        } else {

            ini_set("memory_limit", "1256M");
            $zip = new ZipArchive();
            $dir = "../sdo-archivos-gastoEquipos/EQUIPO-" . $idEquipo . "/Gasto-" . $idGasto;
            $dir2 = $dir . "/";
            $rutaFinal = "../sdo-administrador/";
            $archivoZip = "OrdenCompra-Equipo-" . $idEquipo . "-Gasto-" . $idGasto . ".zip";
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

//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

$ruta = "";

if ($rolUsuario2 == "personal") {
    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $ruta = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $ruta = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $ruta = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

$idHoja = $_GET['idHoja'];

if ($_GET['idHoja']) {
    $sql = "SELECT * FROM hoja_de_vida WHERE idHoja = $idHoja";
    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {
        $datos = [];

        $datos['nomEquipo'] = $row['nomEquipo'];

        $nomEquipo = $datos['nomEquipo'];
    }
    $_SESSION['idHoja'] = $idHoja;
} else if ($_SESSION['idHoja']) {

    $idHoja = $_SESSION['idHoja'];

    $sql = "SELECT * FROM hoja_de_vida WHERE idHoja = $idHoja";
    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {
        $datos = [];

        $datos['nomEquipo'] = $row['nomEquipo'];

        $nomEquipo = $datos['nomEquipo'];
    }
} else {
    header('Location: administrar-equipos.php?noGasto=true');
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Archivos Equipos</title>
    <link rel="icon" href="../dist/img/ss-vina-quillota.png" type="image/icon type">
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
    <div class="preloader  flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="../dist/img/ssvq1.png" alt="logo">
        <br>
        <div class="spinner-border text-danger" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <div class="wrapper">
        <?php
        include '../sdo-templates/admin-sidebar.php';
        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <br>
            <!-- AVISOS -->
            <?php

            if (isset($_GET['noId'])) {
            ?>
                <script>
                    Swal.fire(
                        'Oh que mal!',
                        'ID no valido',
                        'error'
                    )
                </script>
            <?php
            }

            if (isset($_GET['exitoCrear'])) {
            ?>
                <script>
                    Swal.fire(
                        'Bien!',
                        'Se subio y guardo el archivo correctamente',
                        'success'
                    )
                </script>
            <?php
            }

            if (isset($_GET['noInsert'])) {
            ?>
                <script>
                    Swal.fire(
                        'Error!',
                        'No fue posible guardar los datos',
                        'error'
                    )
                </script>
            <?php
            }


            if (isset($_GET['noInsert'])) {
            ?>
                <script>
                    Swal.fire(
                        'Error!',
                        'No fue posible guardar los datos',
                        'error'
                    )
                </script>
            <?php
            }






            ?>
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- general form elements -->
                    <div class="card card-danger">
                        <div class="card-header">
                            <h1 class="card-title"> <b> Archivos del equipo - <?php echo $nomEquipo ?></b></h1>
                        </div>
                        <div class="row">
                            <!-- left column -->
                            <div class="col-md-12">
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <a href="visualizarEquipo.php?idHoja=<?php echo $idHoja ?>" style="float: right;" class="btn btn-warning"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp; <b>Volver Atras</b> </a>
                                    <br>
                                    <br>

                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target=".modalGasto"> <b><i class="fas fa-plus"></i> Añadir Archivo</b> </button>

                                    <div class="modal fade modalGasto" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger">
                                                    <h5 class="modal-title" id="exampleModalLabel"> <b>Formulario - Información del archivo</b> </h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="../sdo-funciones/hoja-vida-guardarArchivo.php" method="POST" enctype="multipart/form-data">

                                                        <div class="row">
                                                            <div class="form-group col-md-4">
                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px">Nombre Archivo:<code>*</code></label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control form-control-sm" required id="nomArchivo" name="nomArchivo">
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px">Tipo Archivo:<code>*</code></label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-th-list"></i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control form-control-sm" required id="tipoArchivo" name="tipoArchivo">
                                                                    <!-- <select name="tipoMantencion" class="form-control form-control-sm" required id="tipoMantencion">
                                                                        <option value="" disabled selected>-- Seleccione --</option>
                                                                        <option value="MPE">MPE - Mantenimiento Preventivo Externo</option>
                                                                        <option value="MRE">MRE - Mantenimiento Reparativo Externo</option>
                                                                        <option value="MPI">MPI - Mantenimiento Preventivo Interno</option>
                                                                        <option value="MRI">MRI - Mantenimiento Reparativo Interno</option>
                                                                        <option value="OTROS">OTROS - Tipos de trabajos</option>
                                                                    </select> -->
                                                                </div>
                                                            </div>

                                                            <div class="form-group col-md-4">
                                                                <!-- <label for="customFile">Custom File</label> -->
                                                                <label for="exampleInputTareasControlCent" style="font-size:12px;">Adjuntar el archivo:</label><br>
                                                                <div class="custom-file">
                                                                    <input type="file" required accept="application/pdf, image/png, image/jpg, image/jpeg,application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint " class="custom-file-input" id="archivoAD" name="archivoAD">
                                                                    <label class="custom-file-label" for="customFile" style="font-size:12px;"><b>Seleccionar Archivo...</b> </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <input type="hidden" name="idHoja" id="idHoja" value="<?php echo $idHoja ?>">
                                                    <button type="button" class="btn btn-warning" data-dismiss="modal"> <b>Cerrar</b> </button>
                                                    <button type="submit" class="btn btn-success"> <b>Añadir Archivo</b> </button>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <br>
                                    <br>
                                    <div class="row">
                                        <div class="col-12">

                                            <table id="gasto_equipo" class="table table-striped table-bordered">
                                                <thead>
                                                    <tr class="bg-danger ">
                                                        <th scope="col">N°</th>
                                                        <th scope="col">Fecha Subida</th>
                                                        <th scope="col">Nombre Archivo</th>
                                                        <th scope="col">Tipo Archivo</th>
                                                        <th scope="col">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sql = "SELECT * FROM archivos_adjunto_equipo WHERE idEquipo = $idHoja ORDER BY idRegArchivo  DESC";
                                                    $res = mysqli_query($conexion, $sql);
                                                    $num = 1;
                                                    while ($row = $res->fetch_assoc()) {
                                                        $datos = [];
                                                        $datos['idRegArchivo '] = $row['idRegArchivo '];
                                                        $datos['fechaRegistro'] = $row['fechaRegistro'];
                                                        $datos['nomArchivo'] = $row['nomArchivo'];
                                                        $datos['tipoArchivo'] = $row['tipoArchivo'];
                                                        $datos['creadoPor'] = $row['creadoPor'];
                                                        $datos['depaCreadoPor'] = $row['depaCreadoPor'];

                                                        $idRegArchivo =  $datos['idRegArchivo'];
                                                        $fechaRegistro = $datos['fechaRegistro'];
                                                        $nomArchivo =  $datos['nomArchivo'];
                                                        $tipoArchivo =  $datos['tipoArchivo'];
                                                        $creadoPor =  $datos['creadoPor'];
                                                        $depaCreadoPor =  $datos['depaCreadoPor'];

                                                        $fechaRegistro = date('d-m-Y', (strtotime(str_replace('/', '-', $row['fechaRegistro']))));



                                                    ?>
                                                        <tr>
                                                            <td style=" font-weight:bold"><?php echo $num; ?></td>
                                                            <td style=" font-weight:bold"><?php echo $fechaRegistro ?></td>
                                                            <td style=" font-weight:bold"><?php echo strtoupper($nomArchivo); ?></td>
                                                            <td style=" font-weight:bold"> <?php echo strtoupper($tipoArchivo); ?></td>
                                                            <td>

                                                                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">

                                                                    <div class="btn-group" role="group">
                                                                        <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">

                                                                        </button>
                                                                        <div class="dropdown-menu">
                                                                            <form action="editar-viaje-movilizacion.php" method="post" enctype="multipart/form-data">
                                                                                <input type="hidden" name="idViaje" value="<?php echo $idViaje ?>">
                                                                                <button class="dropdown-item" tyoe="submit"><i class="fas fa-edit"></i> Editar </button>
                                                                            </form>

                                                                            <?php

                                                                            $sqlArchivos = "SELECT * FROM archivos_viajes_movilizacion WHERE idViaje = $idViaje";
                                                                            $resArchivos = mysqli_query($conexion, $sqlArchivos);

                                                                            if ($resArchivos->num_rows > 0) {
                                                                            ?>
                                                                                <form action="listado-viajes.php" method="POST">
                                                                                    <input type="hidden" name="idViaje" value="<?php echo $idViaje ?>">
                                                                                    <button type="submit" class="dropdown-item" href="#"><i class="fas fa-download"></i> Descargar </button>
                                                                                </form>
                                                                            <?php
                                                                            }

                                                                            ?>
                                                                            <a class="dropdown-item eliminar" id="<?php echo $idViaje ?>" href="#"><i class="fas fa-trash-alt"></i> Eliminar</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                    <?php
                                                        $num++;
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div><!-- .tabla-responsive -->
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col  -->
        </div>
        <!-- /.row -->
        </section>
        <!-- /.content -->
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
    <!-- jQuery -->
    <script src="../plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.2/js/dataTables.bootstrap4.min.js"></script>
    <!-- Page specific script -->
    <script>
        $(function() {
            bsCustomFileInput.init();
        });
    </script>
    <script src="js/dark-mode.js"></script>
    <script>
        $(document).ready(function() {
            $('#gasto_equipo').DataTable({
                "paging": true,
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
                }
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
                    title: '¿Eliminar el registro de este archivo adjunto?',
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
                            url: '../sdo-funciones/hoja-vida-eliminarArchivo.php',
                            type: 'POST',
                            data: dataString,
                            success: function(response) {
                                console.log('eliminado.. ' + dataString);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Registro del archivo eliminado correctamente',
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
</body>

</html>