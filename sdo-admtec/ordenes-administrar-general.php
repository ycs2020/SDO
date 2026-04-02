<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$descargaConfirma = "";
if (isset($_POST['idOrden'])) {

    $idOrden = $_POST['idOrden'];
    $folio = $_POST['folio'];

    if ($idOrden > 0) {

        ini_set("memory_limit", "1256M");
        $zip = new ZipArchive();
        $dir = '../sdo-archivos-orden/' . $folio;
        $dir2 = $dir . "/";
        $rutaFinal = "../sdo-admtec/";
        $archivoZip = "Archivos-" . $folio . ".zip";

        if ($zip->open($archivoZip, ZIPARCHIVE::CREATE) === true) {
            agregar_zip($dir2, $zip);
            $zip->close();
            if (file_exists($rutaFinal . "/" . $archivoZip)) {
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
} {
}

if (isset($_POST['rutaArchivoHT'],$_POST['folioOrdenHT'])) {
    $file = $_POST['rutaArchivoHT'];
    $folioOrdenHT = $_POST['folioOrdenHT'];
    if (is_file($file)) {
        $filename = "Hoja Tecnica ".$folioOrdenHT.".pdf"; // el nombre con el que se descargará, puede ser diferente al original
        /*header("Content-Type: application/octet-stream");*/
        header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        readfile($file);
    } else {
        die("Error: no se encontró el archivo '$file'");
    }
}

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Recepciones de Trabajo</title>

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
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">

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
                            <h4> <b>Listado de Hojas Técnicas</b> </h4>
                        </div>

                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">

                                    <?php

                                    if (isset($_GET['noEditId'])) {
                                        echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;No fue posible recuperar el ID del registro para editar.</h5></div>';
                                    }

                                    if (isset($_GET['ExitoActualizar'])) {
                                        echo '<div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Exito al actualizar la hoja tecnica</h5></div>';
                                    }

                                    if (isset($_GET['exitoEliminarHoja'])) {
                                        echo '<div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Exito al eliminar hoja tecnica</h5></div>';
                                    }

                                    if (isset($_GET['exitoGenerar'])) {
                                        echo '<div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Exito la Hoja Técnica</h5></div>';
                                    }

                                    if (isset($_GET['exitoUpload'])) {
                                        echo '<div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Exito al subir hoja técnica firmada</h5></div>';
                                    }

                                    if (isset($_GET['errorUpload'])) {
                                        echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al subir hoja técnica firmada</h5></div>';
                                    }

                                    ?>
                                    <br>
                                    <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <br>
                                    <br>
                                    <form method="post" action="ordenes-formulario-general.php">

                                        <button type="submit" style="float: right;" id="inputMozi" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;&nbsp; <b>Agregar Hoja Técnica</b> </button>

                                    </form>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead class="bg-secondary">
                                            <tr>
                                                <th>
                                                     N°
                                                </th>
                                                <th>
                                                    Folio
                                                </th>
                                                <th>
                                                    Tec. Recepcionador
                                                </th>
                                                <th>
                                                    Solicitante
                                                </th>
                                                <th>
                                                    Fecha de Recepci&oacute;n
                                                </th>
                                                <th>
                                                    Visualizar
                                                </th>
                                                <th>
                                                    Descargar
                                                </th>
                                                <th>
                                                    Modificar
                                                </th>
                                                <th>
                                                    Eliminar
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $query = 'SELECT * FROM ordenes_trabajos_general ORDER BY fechaOrden DESC';
                                            $res = mysqli_query($conexion, $query);

                                            $numOrden = 1;

                                            while ($row = $res->fetch_assoc()) {

                                            ?>
                                                <tr>
                                                    <td style="vertical-align:middle;">
                                                        <?php echo $numOrden; ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" align="justify">
                                                        <?php
                                                        $folioOrden = mb_strtoupper($row['folioOrden']);
                                                        echo $folioOrden;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" align="justify">
                                                        <?php
                                                        $tecRecep = mb_strtoupper($row['nomTecnicoRecepcionador']);
                                                        echo $tecRecep;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" align="justify">
                                                        <?php
                                                        $nuevoNom = mb_strtoupper($row['nomSolicitante']);
                                                        echo $nuevoNom;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;">
                                                        <CENTER><?php if ($row['fechaOrden'] == 0) {
                                                                    echo "No registra fecha";
                                                                } else {
                                                                    $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $row['fechaOrden'])));
                                                                    $inicioHora = date('H:i', strtotime($row['fechaOrden']));
                                                                    echo $inicioFecha . '<br>' . $inicioHora;
                                                                } ?></CENTER>
                                                    </td>
                                                    <td style="vertical-align:middle;">

                                                            <?php
                                                            $idOrden = $row['idOrden'];
                                                            $idOrden = trim($idOrden);
                                                            if ($idOrden == 0 || $idOrden == "" || $idOrden == " " || $idOrden == "  ") {
                                                            ?>
                                                                <?php echo "Error al Cargar Datos."; ?>
                                                            <?php
                                                            } else {

                                                            ?>

                                                                <form style=" display: inline" target="_blank" class="" action="../sdo-pdf/SDO-OrdenTrabajoGeneral.php" method="post">

                                                                    <input type="hidden" name="idOrden" value="<?php echo $idOrden; ?>">
                                                                    <button type="submit" id="inputMozi" class="btn  btn-sm btn-primary"><i class="fa fa-eye"></i></button>

                                                                </form>

                                                                <?php

                                                                $sqlImagenHT = "SELECT * FROM archivos_hoja_firmada WHERE idHoja = $idOrden";
                                                                $resImagenHT = mysqli_query($conexion, $sqlImagenHT);

                                                                while ($rowImagenHT = $resImagenHT->fetch_assoc()) {
                                                                    $datos = [];
                                                                    $datos['rutaArchivoHT'] = $rowImagenHT['rutaArchivoHT'];
                                                                    $rutaArchivoHT = $datos['rutaArchivoHT'];
                                                                }

                                                                if ($resImagenHT->num_rows > 0) {
                                                                ?>
                                                                    <form action="ordenes-administrar-general.php" method="POST" style="display:inline">
                                                                        <input type="hidden" value="<?php echo $rutaArchivoHT ?>" name="rutaArchivoHT">
                                                                        <input type="hidden" name="idHojaTabla" id="idHoja" value="<?php echo $idOrden ?>">
                                                                        <input type="hidden" name="folioOrdenHT" id="folioOrdenHT" value="<?php echo $folioOrden ?>">
                                                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-download"></i></button>
                                                                    </form>

                                                                <?php
                                                                } else {
                                                                ?>
                                                                    <a id="<?php echo $row['idOrden']; ?>" class="btn btn-sm btn-success abrirmodal" data-toggle="modal" data-target="#exampleModal" for="upload"><i class="fas fa-upload"></i></a>
                                                                <?php
                                                                }

                                                                ?>


                                                                <!-- Modal -->
                                                                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-info">
                                                                                <h5 class="modal-title" id="exampleModalLabel">Subir Hoja Firmada</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <!-- Alerta de éxito -->
                                                                                <div class="alert alert-success alert-dismissible fade" id="successAlert" role="alert">
                                                                                    <b>Se adjunto un archivo correctamente! Por favor haz click en el botón verde Subir.</b> 
                                                                                </div>

                                                                                <p class="text-bold text-purple"><b> **Adjunte la hoja técnica firmada para respaldarla**</b></p>
                                                                                <p class="text-bold text-danger"><b> **Formato PDF**</b></p>

                                                                                <?php

                                                                                $idHojaFirmada = $_GET['idHoja'];

                                                                                ?>

                                                                                <form action="../sdo-funciones/subirHojaFirmada.php" method="POST" enctype="multipart/form-data" style="display: inline;">
                                                                                    <input type="hidden" name="idHoja" id="idmodal" value="<?php echo $idHojaFirmada ?>">
                                                                                    <input type="file" onchange="showSuccessAlert()" required accept="application/pdf" name="imagenHoja" id="upload" class="ocultar ">
                                                                                    <label for="upload" class="btn btn-block btn-warning"> <i class="fas fa-file-contract"></i> Adjuntar Imagen <i class="fas fa-file-contract"></i></label>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                                <button type="submit" class="btn btn-success">Subir</button>
                                                                                </form>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                <style>
                                                                    .ocultar {
                                                                        display: none;
                                                                    }
                                                                </style>


                                                            <?php

                                                            }
                                                            ?>



                                                    </td>
                                                    <td style="vertical-align:middle;">

                                                            <?php
                                                            $idOrden = $row['idOrden'];
                                                            $idOrden = trim($idOrden);

                                                            $sqlArchivo = "SELECT count(*) as cantidad FROM archivos_orden_general WHERE idOrden = $idOrden ";
                                                            $resArchivo = mysqli_query($conexion, $sqlArchivo);

                                                            while ($rowArchivo = $resArchivo->fetch_assoc()) {
                                                                $datos = [];
                                                                $datos['cantidad'] = $rowArchivo['cantidad'];
                                                                $cantidad = $datos['cantidad'];
                                                            }

                                                            if ($cantidad > 0) {
                                                            ?>
                                                                <form style="margin: 0; text-align: center;" class="" action="ordenes-administrar-general.php" method="post">
                                                                    <input type="hidden" name="idOrden" value="<?php echo $idOrden; ?>">
                                                                    <input type="hidden" name="folio" value="<?php echo $folioOrden; ?>">
                                                                    <button type="submit" id="inputMozi" class="btn btn-block btn-info"> <i class="fas fa-download"></i> </button>
                                                                </form>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <p>Sin Archivos Adjuntos</p>
                                                            <?php
                                                            }

                                                            ?>
                                                    </td>
                                                    <td style="vertical-align:middle;">
                                                            <?php
                                                            $idOrden = $row['idOrden'];
                                                            $idOrden = trim($idOrden);
                                                            if ($idOrden == 0 || $idOrden == "" || $idOrden == " " || $idOrden == "  ") {
                                                            ?>
                                                                <?php echo "Error al Cargar Datos."; ?>
                                                            <?php
                                                            } else {

                                                            ?>

                                                                <form style="margin: 0; text-align: center;" class="" action="ordenes-modificar-general.php" method="post">

                                                                    <input type="hidden" name="idOrden" value="<?php echo $idOrden; ?>">
                                                                    <button type="submit" id="inputMozi" class="btn btn-block btn-warning"><i class="fa fa-pen"></i></button>

                                                                </form>

                                                            <?php

                                                            }
                                                            ?>
                                                    </td>
                                                    <td style="vertical-align:middle;">
                                                            <?php
                                                            $idOrden = $row['idOrden'];
                                                            $idOrden = trim($idOrden);
                                                            if ($idOrden == 0 || $idOrden == "" || $idOrden == " " || $idOrden == "  ") {
                                                            ?>
                                                                <?php echo "Error al Cargar Datos."; ?>
                                                            <?php
                                                            } else {

                                                            ?>
                                                                <form action="../sdo-funciones/ordenes-eliminar-general.php" method="POST">
                                                                    <input type="hidden" value="<?php echo $idOrden ?>" name="idOrden">
                                                                    <button type="submit" class="btn btn-block btn-danger "><i class="fa fa-trash"></i></button>
                                                                </form>

                                                            <?php

                                                            }
                                                            ?>
                                                    </td>
                                                </tr>
                                            <?php

                                                $numOrden = $numOrden + 1;
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot class="bg-secondary">
                                            <tr>
                                                <th>
                                                    N°
                                                </th>
                                                <th>
                                                    Folio
                                                </th>
                                                <th>
                                                    Tec. Recepcionador
                                                </th>
                                                <th>
                                                    Solicitante
                                                </th>
                                                <th>
                                                    Fecha de Recepci&oacute;n
                                                </th>
                                                <th>
                                                    Visualizar
                                                </th>
                                                <th>
                                                    Descargar
                                                </th>
                                                <th>
                                                    Modificar
                                                </th>
                                                <th>
                                                    Eliminar
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
                    <!-- /.row -->
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
    <script src="js/dark-mode.js"></script>
    <script>
        $(document).ready(
            $('.eliminar').click(function(e) {
                e.preventDefault();
                let id = $(this).attr('id');
                var dataString = 'id=' + id;
                Swal.fire({
                    icon: 'question',
                    title: '¿Eliminar la hoja técnica seleccionada?',
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
                            url: '../sdo-funciones/ordenes-eliminar-general.php',
                            type: 'POST',
                            data: dataString,
                            success: function(response) {
                                console.log('eliminado.. ' + dataString);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Hoja Técnica Eliminada Correctamente',
                                    showConfirmButton: false,
                                    timer: 2000,
                                })
                                setTimeout(function() {
                                    location.reload();
                                }, 1400)
                            }
                        })
                    }
                })
            })
        )
    </script>
    <script>
        $("a.abrirmodal").click(function() {
            //Capturamos el valor del id para enviarlo al modal
            let id = $(this).attr('id');
            //Pasamos el id al campo input hiddien del modal.
            $("input#idmodal").val(id);
        });
    </script>
    <script>
        // Función para mostrar la alerta de éxito
        function showSuccessAlert() {
            var fileInput = document.getElementById('upload');
            if (fileInput.files.length > 0) {
                document.getElementById('successAlert').classList.add('show');
            }
        }

        $("#exampleModal").on("hidden.bs.modal", function() {
            // Aquí va el código a disparar en el evento
            document.getElementById('successAlert').classList.remove('show');
            document.getElementById('upload').value = '';
        });
       
    </script>


</body>

</html>