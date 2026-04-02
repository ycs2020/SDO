<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
    // echo '';
}

if (isset($_POST['idOrden'])) {
    $idOrden = $_POST['idOrden'];
    $folio = $_POST['folio'];
    if ($idOrden > 0) {
        if (empty($idOrden) || $idOrden == '') {
        } else {
            ini_set("memory_limit", "1256M");
            $zip = new ZipArchive();
            $dir = "../sdo-archivos-orden/" . $folio;
            $dir2 = $dir . "/";
            $rutaFinal = "../sdo-administrador/";
            $archivoZip = "HojaTecnica-" . $folio . ".zip";
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
    header('Location: ../sdo-flujo/login.php?errorSesion=true');
}

$id = $_GET['idEquipo'];


if ($_GET['idEquipo']) {
    $sql = "SELECT * FROM equipamiento_hbqp WHERE id = $id";
    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['nombreEquipo'] = $row['nombreEquipo'];
        $nomEquipo = $datos['nombreEquipo'];
    }
    $_SESSION['idEquipo'] = $id;
} else if ($_SESSION['idEquipo']) {

    $id = $_SESSION['idEquipo'];

    $sql = "SELECT * FROM equipamiento_hbqp WHERE id = $id";
    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['nombreEquipo'] = $row['nombreEquipo'];
        $nomEquipo = $datos['nombreEquipo'];
    }
} else {
    header('Location: administrar-equipos-seleccion.php?nohojas=true');
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrar Equipos</title>
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
    <style>
        #mesInforme {
            display: none;
        }

        #rangoFecha {
            display: none;
        }
    </style>
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


            if (isset($_GET['exitoHoja'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Exito',
                        text: 'Exito al añadir hoja técnica',
                    })
                </script>

            <?php
            }

            ?>
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- general form elements -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h1 class="card-title"> <b> Hojas Técnicas - <?php echo $nomEquipo ?></b></h1>
                        </div>
                        <div class="row">
                            <!-- left column -->
                            <div class="col-md-12">
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <a href="visualizarEquipo-cc.php?id=<?php echo $id ?>" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp; <b>Volver Atras</b> </a>
                                    <div class="text-center justify-content-center">
                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#staticBackdrop"> <b><i class="fas fa-plus"></i> Añadir Hoja Técnica</b> </button>
                                    </div>
                                    <br>
                                    <br>


                                    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success">
                                                    <h5 class="modal-title" id="staticBackdropLabel">Escoja la hoja técnica a añadir</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <table id="hoja_tecnicas" class="table">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">N°</th>
                                                                <th scope="col">Nombre</th>
                                                                <th scope="col">Tecnico Recepcionador</th>
                                                                <th scope="col">Ticket Asociado</th>
                                                                <th scope="col">Seleccionar</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php

                                                            $sqlHojaTec = "SELECT * FROM ordenes_trabajos_general WHERE idEquipo is null ORDER BY idOrden DESC";
                                                            $resHojaTec = mysqli_query($conexion, $sqlHojaTec);

                                                            $num = 1;

                                                            while ($rowHojaTec = $resHojaTec->fetch_assoc()) {
                                                                $datos = [];
                                                                $datos['idOrden'] = $rowHojaTec['idOrden'];
                                                                $datos['nomOrden'] = $rowHojaTec['nomOrden'];
                                                                $datos['nomTecnicoRecepcionador'] = $rowHojaTec['nomTecnicoRecepcionador'];
                                                                $datos['ticketAsociado'] = $rowHojaTec['ticketAsociado'];

                                                                $idOrden  = $datos['idOrden'];
                                                                $nomOrden = $datos['nomOrden'];
                                                                $nomTecnicoRecepcionador = $datos['nomTecnicoRecepcionador'];
                                                                $ticketAsociado = $datos['ticketAsociado'];

                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $num ?> </td>
                                                                    <td><?php echo mb_convert_case($nomOrden, MB_CASE_UPPER, 'UTF-8');  ?></td>
                                                                    <td><?php echo mb_convert_case($nomTecnicoRecepcionador, MB_CASE_UPPER, 'UTF-8'); ?></td>
                                                                    <td>
                                                                        <?php
                                                                        if ($ticketAsociado == '') {
                                                                            echo "SIN INFORMACIÓN";
                                                                        } else {
                                                                            echo strtoupper($ticketAsociado);
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td>

                                                                        <form action="../sdo-funciones/agregarHojaTecnica-cc.php" method="POST">
                                                                            <input type="hidden" name="idEquipo" value="<?php echo $id ?>">
                                                                            <input type="hidden" name="idHojaTecnica" value="<?php echo $idOrden ?>">
                                                                            <input type="submit" value="Añadir Esta Hoja" class="btn btn-danger">
                                                                        </form>

                                                                    </td>
                                                                </tr>
                                                            <?php
                                                                $num++;
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <table id="gasto_equipo" class="table table-striped table-sm table-bordered">
                                        <thead>
                                            <tr class="bg-info ">
                                                <th scope="col">N°</th>
                                                <th scope="col">Nombre</th>
                                                <th scope="col">Tecnico Recepcionador</th>
                                                <th scope="col">Ticket Asociado</th>
                                                <th scope="col">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT * FROM ordenes_trabajos_general  WHERE idEquipo = $id ORDER BY idOrden DESC";

                                            $res = mysqli_query($conexion, $sql);
                                            $num = 1;
                                            while ($row = $res->fetch_assoc()) {

                                                $datos['idOrden'] = $row['idOrden'];
                                                $datos['nomOrden'] = $row['nomOrden'];
                                                $datos['nomTecnicoRecepcionador'] = $row['nomTecnicoRecepcionador'];
                                                $datos['ticketAsociado'] = $row['ticketAsociado'];
                                                $datos['folioOrden'] = $row['folioOrden'];

                                                $idOrden  = $datos['idOrden'];
                                                $nomOrden = $datos['nomOrden'];
                                                $nomTecnicoRecepcionador = $datos['nomTecnicoRecepcionador'];
                                                $ticketAsociado = $datos['ticketAsociado'];
                                                $folioOrden = $datos['folioOrden'];

                                            ?>
                                                <tr>
                                                    <td style=" font-weight:bold; font-size: 12px"><?php echo $num; ?></td>
                                                    <td style=" font-weight:bold; font-size: 12px"><?php echo $nomOrden ?></td>
                                                    <td style=" font-weight:bold; font-size: 12px"><?php echo mb_strtoupper($nomTecnicoRecepcionador); ?></td>
                                                    <td style=" font-weight:bold; font-size: 12px"> <?php
                                                                                                    if ($ticketAsociado == '') {
                                                                                                        echo "SIN INFORMACIÓN";
                                                                                                    } else {
                                                                                                        echo mb_convert_case($ticketAsociado, MB_CASE_UPPER, 'UTF-8');
                                                                                                    }
                                                                                                    ?></td>
                                                    <td>

                                                        <form action="../sdo-pdf/SDO-OrdenTrabajoGeneral.php" target="_blank" method="POST" style="display: inline;">
                                                            <input type="hidden" name="idOrden" value="<?php echo $idOrden ?>">
                                                            <button type="submit" class="btn btn-sm btn-info "><i class="far fa-eye"></i></button>
                                                        </form>

                                                        <?php

                                                        $sqlArchivosHT = "SELECT * FROM archivos_orden_general WHERE idOrden =  $idOrden";
                                                        $resArchivosHT = mysqli_query($conexion, $sqlArchivosHT);

                                                        if ($resArchivosHT->num_rows > 0) {
                                                        ?>
                                                            <form action="hojastecnicas-carpeta.php" method="POST" style="display: inline;">
                                                                <input type="hidden" name="idOrden" value="<?php echo $idOrden ?>">
                                                                <input type="hidden" name="folio" value="<?php echo $folioOrden ?>">
                                                                <button type="submit" class="btn btn-sm btn-warning "><i class="fas fa-download"></i></button>
                                                            </form>
                                                        <?php
                                                        }

                                                        ?>


                                                        <a class="btn btn-sm btn-danger eliminar" id="<?php echo $idOrden ?>"><i class="fas fa-trash-alt"></i></a>

                                                    </td>

                                                <?php
                                                $num++;
                                            }
                                                ?>
                                        </tbody>
                                    </table>

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
                "scrollX": true,
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
        $(document).ready(function() {
            $('#hoja_tecnicas').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "language": {
                    "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                    "zeroRecords": "No se han encontrado hojas técnicas, añada uno.",
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
                    title: '¿Eliminar el registro seleccionado?',
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
                            url: '../sdo-funciones/eliminar-hojatecnica-equipo.php',
                            type: 'POST',
                            data: dataString,
                            success: function(response) {
                                console.log('eliminado.. ' + dataString);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Registro eliminado',
                                    showConfirmButton: false,
                                    timer: 2000,
                                })
                                setTimeout(function() {
                                    location.reload();
                                }, 1800)
                            }
                        })
                    }
                })
            })
        )
    </script>
    <script>
        $(document).ready(
            $('.eliminar').click(function(e) {
                e.preventDefault();
                let id = $(this).attr('id');
                var dataString = 'id=' + id;
                Swal.fire({
                    icon: 'question',
                    title: '¿Eliminar el registro seleccionado?',
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
                            url: '../sdo-funciones/eliminar-hojatecnica-equipo.php',
                            type: 'POST',
                            data: dataString,
                            success: function(response) {
                                console.log('eliminado.. ' + dataString);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Registro eliminado',
                                    showConfirmButton: false,
                                    timer: 2000,
                                })
                                setTimeout(function() {
                                    location.reload();
                                }, 1800)
                            }
                        })
                    }
                })
            })
        )
    </script>

</body>

</html>