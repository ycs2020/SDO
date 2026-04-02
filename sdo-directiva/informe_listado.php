<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];


date_default_timezone_set('America/Santiago');

if (isset($_POST['folio'])) {

    $folio = $_POST['folio'];

    if (!empty($folio)) {

        ini_set("memory_limit", "1256M");
        $zip = new ZipArchive();
        $dir = '../sdo-informes-adjuntos/'.$folio;
        echo $dir;
        $dir2 = $dir . "/";
        $rutaFinal = "../sdo-directiva/";
        $archivoZip = "Archivos-" . $folio . ".zip";

        if ($zip->open($archivoZip, ZIPARCHIVE::CREATE) === true) {
            agregar_zipSC($dir2, $zip);
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
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Informes</title>

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


</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/directiva-sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Listado de Informe</h4>

                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col-xl-12 col-lg-12 col-md-6 col-12 mb-3 text-right">

                                        <a href="index.php" class="btn btn-danger btn-md"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                        <br>
                                        <br>
                                        <a href="informe_crear.php" class="btn btn-primary btn-md"><i class="fas fa-file"></i>  Crear Informe</a>
                                    </div>
                                </div>

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
                                <table id="tableInformes" class="table table-bordered table-hover">
                                    <thead class="bg-secondary">
                                    <tr>
                                        <th>N°</th>
                                        <th>Titulo</th>
                                        <th>Fecha</th>
                                        <th>Visualizar</th>
                                        <th>Editar</th>
                                        <th>Descargar Archivos</th>
                                    </tr>
                                    </thead>
                                    <tfoot class="bg-secondary">
                                    <tr>
                                        <th>N°</th>
                                        <th>Titulo</th>
                                        <th>Fecha</th>
                                        <th>Visualizar</th>
                                        <th>Editar</th>
                                        <th>Descargar Archivos</th>

                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <footer class="main-footer">
        <strong>Copyright &copy; 2022 - ∞. Derechos Reservados</strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
</div>
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
<script>
    $(document).ready(function () {
        var tableInformes = $('#tableInformes').DataTable({
            "lengthChange": false,
            "searching": true,
            "autoWidth": false,
            "responsive": true,
            "ordering": false,
            "processing": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "search": "Buscar:",
                "processing": "Procesando...",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
            "ajax": {
                "url": "ajax-listado-informes.php", // Archivo PHP que contiene la consulta SQL
                "type": "POST",
                "data": function (d) {
                    // Aquí podrías enviar parámetros adicionales si los necesitas
                    console.log(d); // Si no lo necesitas, puedes eliminarlo
                },
                "dataSrc": "" // Ajusta esto si el servidor devuelve los datos dentro de una clave, por ejemplo, "data".
            },
            "columns": [
                { "data": "id" }, // Columna para el título
                { "data": "titulo" }, // Columna para el título
                { "data": "fecha" }, // Columna para el título
                {
                    "data": null,
                    "render": function (data, type, row) {
                        if (row.id > 0) {
                            return '<form style="margin: 0; text-align: center;" class="" target="_blank" action="../sdo-pdf/SDO-Informe-PDF.php" method="post">' +
                                '<input type="hidden" name="idInformeVer" id="idInformeVer" value="' + row.id + '">' +
                                '<button type="submit" id="inputMozi" class="btn  btn-primary"><i class="fas fa-file"></i></button>' +
                                '</form>';
                        } else {
                            return '<center>Error al Cargar Datos.</center>';
                        }
                    }

                },
                { "data": null,
                    "render": function (data, type, row){
                        if(row.id > 0){
                            return '<form style="margin: 0; text-align: center;" class="" target="_blank" action="../sdo-directiva/editar_informe.php" method="post">' +
                                '<input type="hidden" name="idInformeVer" id="idInformeVer" value="' + row.id + '">' +
                                '<button type="submit" id="inputMozi" class="btn  btn-warning"><i class="fas fa-pen-alt"></i></button>' +
                                '</form>';
                        }else {
                            return '<center>Error al Cargar Datos.</center>';
                        }
                    }

                },
                {
                    "data": null,
                    "render": function (data, type, row){
                        if (row.total == 0) {
                            return 'Sin Archivos Adjuntos';
                        }else{
                            return '' +
                                '<form style="margin: 0; text-align: center;" class="" action="informe_listado.php" method="post">' +
                                '<input type="hidden" name="folio" value="'+row.folio+'">' +
                                '<input type="submit" id="inputMozi" class="btn btn-block btn-warning" value="Descargar Archivos Adjuntos">' +
                                '</form>'
                        }
                    }
                }
                // Columna para el título

                // Columna para el ID
            ],
            "createdRow": function (row, data, dataIndex) {
                $('td', row).css({
                    'vertical-align': 'middle',
                    'text-align': 'center'
                });
            }
        });
    });

</script>
</body>
</html>
