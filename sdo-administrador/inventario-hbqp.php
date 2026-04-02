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
        <?php
        if (isset($_GET['exitoAgregarEquipo'])) {
            ?>
            <script>
                Swal.fire(
                    'Completado.',
                    'Equipo Ingresado.',
                    'success'
                )
            </script>
            <?php
        }

        if(isset($_GET['exitoModificar'])){
            ?>
            <script>
                Swal.fire(
                    'Completado.',
                    'Equipo Modificado.',
                    'success'
                )
            </script>
            <?php
        }

        if(isset($_GET['errorModificar'])){
            ?>
            <script>
                Swal.fire(
                    'Error.',
                    'Error al Modificar Equipo.',
                    'error'
                )
            </script>
            <?php
        }

        ?>
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
                                            <button type="submit" class="btn btn-info btn-block"><i class="fas fa-plus"></i> Agregar Equipo a Inventario</button>
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
                                <table class="table table-bordered table-sm justify-content-center" style="font-size: small" id="tabla-equipo-falla">
                                    <thead class="bg-info">
                                    <tr>
                                        <th scope="col" class="text-center align-middle">Nombre del Equipo</th>
                                        <th scope="col" class="text-center align-middle">Marca y Modelo</th>
                                        <th scope="col" class="text-center align-middle">Serie</th>
                                        <th scope="col" class="text-center align-middle">Cantidad</th>
                                        <th scope="col" class="text-center align-middle">Unidad</th>
                                        <th scope="col" class="text-center align-middle">Fecha de Ingreso</th>
                                        <th scope="col" class="text-center align-middle">Categoria a la que pertenece</th>
                                        <th scope="col" class="text-center align-middle">Valor Unitario Sin IVA</th>
                                        <th scope="col" class="text-center align-middle">Descargar Archivo Adjunto</th>
                                        <th scope="col" class="text-center align-middle">Documento</th>
                                        <th scope="col" class="text-center align-middle">Ver Documento</th>
                                        <th scope="col" class="text-center align-middle">Modificar</th>
                                        <th scope="col" class="text-center align-middle">Eliminar</th>
                                    </tr>
                                    </thead>
                                    <tfoot class="bg-info">
                                    <tr>
                                        <th scope="col" class="text-center align-middle">Nombre del Equipo</th>
                                        <th scope="col" class="text-center align-middle">Marca y Modelo</th>
                                        <th scope="col" class="text-center align-middle">Serie</th>
                                        <th scope="col" class="text-center align-middle">Cantidad</th>
                                        <th scope="col" class="text-center align-middle">Unidad</th>
                                        <th scope="col" class="text-center align-middle">Fecha de Ingreso</th>
                                        <th scope="col" class="text-center align-middle">Categoria a la que pertenece</th>
                                        <th scope="col" class="text-center align-middle">Valor Unitario Sin IVA</th>
                                        <th scope="col" class="text-center align-middle">Descargar Archivo Adjunto</th>
                                        <th scope="col" class="text-center align-middle">Documento</th>
                                        <th scope="col" class="text-center align-middle">Ver Documento</th>
                                        <th scope="col" class="text-center align-middle">Modificar</th>
                                        <th scope="col" class="text-center align-middle">Eliminar</th>
                                    </tr>
                                    </tfoot>
                                </table>
                                <script>
                                    $(document).ready(function () {
                                        var miTabla = $('#tabla-equipo-falla').DataTable({
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
                                                "url": "ajax-inventario-listado.php", // Archivo PHP que contiene la consulta SQL
                                                "type": "POST",
                                                "data": function (d) {
                                                },
                                            },
                                            "columns": [
                                                {"data": "equipoInventario"},
                                                {
                                                    "data": null,
                                                    "render": function (data, type, row) {
                                                        // Agrega el formulario y el botón de visualización para cada fila
                                                        return '' +
                                                            'MARCA: ' + row.marcaInventario + '<br>' +
                                                            'MODELO: ' + row.modeloInventario;
                                                    }
                                                },
                                                {"data": "serieInventario"},
                                                {"data": "cantidadInventario"},
                                                {"data": "unidad"},
                                                {"data": "fechaInventario"},
                                                {"data": "nomCategoria"},
                                                {"data": "valorUnitario"},
                                                {
                                                    "data": null,
                                                    "render": function (data, type, row) {
                                                        if (row.total == 0) {
                                                            return 'Sin Archivos Adjuntos';
                                                        }else{
                                                            return '' +
                                                                '<form style="margin: 0; text-align: center;" class="" action="inventario-hbqp.php" method="post">' +
                                                                '<input type="hidden" name="idTG1" value="'+row.idParaDescarga+'">' +
                                                                '<input type="hidden" name="folio" value="'+row.tipoDocumento+'">' +
                                                                '<input type="submit" id="inputMozi" class="btn btn-block bg-gradient-info" value="Descargar">' +
                                                                '</form>'
                                                        }
                                                    }
                                                },
                                                {
                                                    "data": null,
                                                    "render": function (data, type, row) {
                                                        if (row.tipoDocumento == 'Solicitud de Compra - SDO' || row.tipoDocumento == 'Orden de Compra') {
                                                            return row.solicompra;
                                                        }else{
                                                            return 'No Aplica';
                                                        }
                                                    }
                                                },
                                                {
                                                    "data": "ocUrlPdf",
                                                    "render": function (data, type, row) {
                                                        if (data && data !== '') {
                                                            return 'No Disponible2';
                                                        } else {
                                                            return 'No Disponible';
                                                        }
                                                    }
                                                },
                                                {
                                                    "data": null,
                                                    "render": function (data, type, row) {
                                                        // Agrega el formulario y el botón de visualización para cada fila
                                                        return '' +
                                                            '<form style="margin: 0; text-align: center;" class="" action="modificar-equipos-inventario.php" method="post">' +
                                                            '<input type="hidden" name="idEquipoModificar" value="' + row.idInventario + '">' +
                                                            '<input type="hidden" name="selectInventario" value="' + row.categoriaInventario + '">' +
                                                            '<center>' +
                                                            '<button type="submit" class="btn btn-warning"><i class="fas fa-pen"></i></button>' +
                                                            '</center>' +
                                                            '</form>';
                                                    }
                                                },
                                                {
                                                    "data": null,
                                                    "render": function (data, type, row) {
                                                        // Agrega el formulario y el botón de visualización para cada fila
                                                        return '' +
                                                            '<form style="margin: 0; text-align: center;" class="" action="../sdo-funciones/eliminar-equipos-inventario.php" method="post">' +
                                                            '<input type="hidden" name="idEquipoEliminar" value="' + row.idInventario + '">' +
                                                            '<input type="hidden" name="selectInventario" value="' + row.categoriaInventario + '">' +
                                                            '<center>' +
                                                            '<button type="button" class="btn btn-danger delete-btn"><i class="fas fa-trash"></i></button>' +
                                                            '</center>' +
                                                            '</form>';
                                                    }
                                                }
                                                // Agrega más columnas según sea necesario
                                            ],

                                            // "columnDefs": [
                                            //     {
                                            //         "targets": 1,
                                            //         "render": function (data, type, row) {
                                            //             return "Marca: " + row.marca + "<br> Modelo: " + row.modelo;
                                            //         }
                                            //     }
                                            // ],
                                            "createdRow": function (row, data, dataIndex) {
                                                $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                                                $('td', row).css('text-align', 'center'); // Aplica el estilo de centrado vertical a todas las celdas
                                            }
                                        });


                                        $('#tabla-equipo-falla').on('click', '.delete-btn', function () {
                                            var idPresupuesto = $(this).closest('form').find('input[name="idEquipoEliminar"]').val();

                                            // Mostrar SweetAlert para confirmar la eliminación
                                            Swal.fire({
                                                title: '¿Estás seguro?',
                                                text: 'Esta acción no se puede revertir.',
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#d33',
                                                cancelButtonColor: '#3085d6',
                                                confirmButtonText: 'Sí, eliminar',
                                                cancelButtonText: 'Cancelar'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    // Si el usuario confirma, enviar el formulario para eliminar el presupuesto
                                                    $(this).closest('form').submit();
                                                }
                                            });
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
<script>
    $(function () {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#tabla-equipo-falla222').DataTable({
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