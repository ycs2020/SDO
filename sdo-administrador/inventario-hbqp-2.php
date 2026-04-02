<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if (isset($_POST['idTG1'])) {
    $idTG1 = $_POST['idTG1'];
    $folioTG = $_POST['folio'];

    if ($idTG1 > 0) {
        if (empty($idTG1) || $idTG1 == '') {
        } else {
            ini_set("memory_limit", "5256M");
            $zip = new ZipArchive();
            if ($folioTG == 'Cotizacion Empresa') {
                $dir = "../sdo-cotizacion-inventario/cotizacion-empresa/id-" . $idTG1 . "/";
                $folioTG2 = 'Cotizacion-Empresa';
            } else {
                $dir = "../sdo-cotizacion-inventario/cotizacion-internet/id-" . $idTG1 . "/";
                $folioTG2 = 'Cotizacion-Internet';
            }
            $dir2 = $dir . "/";
            $rutaFinal = "../sdo-administrador/";
            $archivoZip = "Documento-" . $folioTG2 . "-" . $idTG1 . ".zip";
            if ($zip->open($archivoZip, ZIPARCHIVE::CREATE) === true) {
                agregar_zipSC($dir2, $zip);
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
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inventario</title>

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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/admin-sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-9 col-lg-9 col-md-6 col-12 d-flex">
                        <div class="card w-100">
                            <div class="card-body d-flex align-items-center">
                                <h5 class="mb-0">Inventario</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-12 d-flex">
                        <div class="card w-100">
                            <div class="card-body d-flex align-items-center">
                                <a href="index.php" class="btn btn-danger btn-block m-0">
                                    <i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row justify-content-center">
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 mb-3">
                                        <form action="agregar-equipo-inventario-general.php" method="POST">
                                            <?php
                                            $idCarpeta = 0;
                                            ?>
                                            <input type="hidden" name="idCategoria" id="idCategoria" value="<?php echo $idCarpeta ?>">
                                            <button type="submit" class="btn btn-info btn-block">
                                                <i class="fas fa-plus"></i> Agregar Equipo a Inventario
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-bordered table-sm justify-content-center" style="font-size: small" id="tabla_inventario">
                                    <thead class="bg-info">
                                    <tr>
                                        <th scope="col" class="text-center align-middle">N°</th>
                                        <th scope="col" class="text-center align-middle">Nombre del Equipo</th>
                                        <th scope="col" class="text-center align-middle">Cantidad</th>
                                        <th scope="col" class="text-center align-middle">Unidad</th>
                                        <th scope="col" class="text-center align-middle">Valor Unitario Sin IVA</th>
                                        <th scope="col" class="text-center align-middle">Visualizar</th>
                                    </tr>
                                    </thead>
                                    <tfoot class="bg-info">
                                    <tr>
                                        <th scope="col" class="text-center align-middle">N°</th>
                                        <th scope="col" class="text-center align-middle">Nombre del Equipo</th>
                                        <th scope="col" class="text-center align-middle">Cantidad</th>
                                        <th scope="col" class="text-center align-middle">Unidad</th>
                                        <th scope="col" class="text-center align-middle">Valor Unitario Sin IVA</th>
                                        <th scope="col" class="text-center align-middle">Visualizar</th>
                                    </tr>
                                    </tfoot>
                                </table>
                                <div class="modal fade" id="modalEquiposVer" tabindex="-1" role="dialog" aria-labelledby="modalEquiposVerLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title" id="modalAutorizacionesLabel">Equipos en Inventario</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body" id="contenidoEquipos">
                                                <p class="text-muted">Cargando información...</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    $(document).ready(function () {
                                        var tabla_inventario = $('#tabla_inventario').DataTable({
                                            "lengthChange": false,
                                            "searching": true,
                                            "autoWidth": false,
                                            "responsive": true,
                                            "processing": true,
                                            "ordering": false,
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
                                                "url": "/sdo-includes/inventario_cargar_datos.php", // Archivo PHP que contiene la consulta SQL
                                                "type": "POST",
                                                "data": function (d) {
                                                },
                                            },
                                            "columns": [
                                                {
                                                    "data": null,
                                                    "render": function (data, type, row, meta) {
                                                        return meta.row + 1; // Devuelve el número de fila (empezando desde 1)
                                                    }
                                                },
                                                {"data": "equipoInventario"},
                                                {"data": "total_cantidad"},
                                                {"data": "unidades"},
                                                {"data": "valor_promedio"},
                                                {"data": "btnVerEquipos"},
                                                // Agrega más columnas según sea necesario
                                            ],
                                            "createdRow": function (row, data, dataIndex) {
                                                $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                                                $('td', row).css('text-align', 'center'); // Aplica el estilo de centrado vertical a todas las celdas
                                                $('td:eq(0)', row).html(dataIndex + 1); // Actualiza el contenido de la primera celda (columna "N°")
                                            }
                                        });
                                    });

                                    $(document).on('click', '#btnVerEquipos', function () {
                                        let nomEquipo = $(this).data('nomequipo');

                                        // alert("Equipo: " + nomEquipo);

                                        // Cambiar contenido antes de la carga
                                        $('#contenidoEquipos').html('<p class="text-info">Cargando autorizaciones...</p>');

                                        // Mostrar modal
                                        $('#modalEquiposVer').modal('show');

                                        // AJAX para cargar información
                                        $.ajax({
                                            url: '/sdo-includes/equipos_tipos_cargar_detalle.php', // archivo PHP que vas a crear
                                            method: 'POST',
                                            data: {
                                                nomEquipo: nomEquipo,
                                            },
                                            success: function (data) {
                                                $('#contenidoEquipos').html(data);
                                            },
                                            error: function () {
                                                $('#contenidoEquipos').html('<p class="text-danger">Error al cargar la información.</p>');
                                            }
                                        });
                                    });
                                </script>
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
<script src="js/dark-mode.js"></script>
<?php
$notificacionesSweet = [
    'exitoAgregarEquipo' => ['Equipo Ingresado con Éxito.', 'success'],
    'exitoModificar' => ['Equipo Modificado con Éxito.', 'success'],
    'errorModificar' => ['Error al Modificar Equipo. Reintente.', 'error'],
];

include_once "../sdo-includes/sweetalert_dark_mode.php";
?>

</body>
</html>