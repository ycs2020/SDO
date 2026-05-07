<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validación de sesión rápida
$idUsuario = $_SESSION['idUsuario'] ?? null;
if (!$idUsuario) {
    header("Location: login.php"); // Opcional: Redirigir si no hay sesión
    exit;
}

$descargaConfirma = "";
date_default_timezone_set('America/Santiago');

// Lógica de Procesamiento POST
if (isset($_POST['descargaCotizacion'], $_POST['folioSoliCompraDescarga'])) {
    $idDescarga = (int)$_POST['descargaCotizacion'];
    $folioSoliDescarga = trim($_POST['folioSoliCompraDescarga']);

    if ($idDescarga > 0 && !empty($folioSoliDescarga)) {

        // Uso de sprintf para simplificar los ceros a la izquierda (SC-SDO-0001)
        $folioFormateado = sprintf("SC-SDO-%04d", $idDescarga);

        $dir = "../sdo-solicCompra/{$folioFormateado}/";
        $archivoZip = "Cotizaciones_{$folioSoliDescarga}.zip";

        if (is_dir($dir)) {
            $zip = new ZipArchive();
            if ($zip->open($archivoZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                agregar_zip($dir, $zip);
                $zip->close();

                if (file_exists($archivoZip)) {
                    // Limpiar cualquier salida previa para evitar corrupción del ZIP
                    if (ob_get_level()) ob_end_clean();

                    header('Content-Description: File Transfer');
                    header('Content-Type: application/zip');
                    header('Content-Disposition: attachment; filename="' . basename($archivoZip) . '"');
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($archivoZip));

                    readfile($archivoZip);
                    unlink($archivoZip);
                    exit;
                }
            }
        }
        $descargaConfirma = "N"; // Si llega aquí, algo falló
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Men&uacute; Solicitudes de Compra - SDO</title>

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
    <!-- Theme style -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>



    <script>
        $(document).ready(function () {
            // Escuchamos el click en ambos botones a la vez
            $(".show-realizar, .show-listar").on("click", function () {
                // Intercambia la visibilidad de las filas de menú
                $("#rowMenu1, #rowMenu2").toggle();

                // Intercambia la visibilidad de los botones de acción
                $("#realizarSC, #listarSC").toggle();
            });
        });
    </script>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<!-- Site wrapper -->
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
                <div class="row">
                    <!-- left column -->
                    <div class="col-xl-10 col-lg-9 col-md-8 col-12">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <a style="pointer-events: none;" class="btn font-weight-bold text-black ">Solicitudes de Compra - SDO</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                        <div class="card">
                            <div class="card-header bg-danger ">
                                <a href="index.php" class="btn btn-block btn-danger">
                                    <i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atrás
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">


                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body" style="text-align:center;">

                                <button class="btn bg-yellow show-realizar" id="realizarSC" name="realizarSC">Realizar Solicitud de Compra</button>

                                <button class="btn bg-yellow show-listar" id="listarSC" style="display:none;" name="listarSC">Listar Solicitudes de Compra</button>

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>

                <div class="row" id="rowMenu1" name="rowMenu1">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>1.- Listado de Solicitudes de Compra</h5>

                                <hr>

                                <input type="hidden" id="nomDeptoEnviar" name="nomDeptoEnviar" value="<?php echo $nomDepartamento; ?>">
                                <div class="table-responsive">
                                    <table style="font-size:90%" id="tableSCSDO" class="table table-bordered table-hover table-sm">
                                        <thead class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle; text-align: center;">
                                                Fecha de Solicitud
                                            </th>
                                            <th style="vertical-align:middle; text-align: center;">
                                                Personal Solicitante
                                            </th>
                                            <th style="vertical-align:middle; text-align: center;">
                                                Folio de Solicitud
                                            </th>
                                            <th style="vertical-align:middle; text-align: center;">
                                                N° Interno
                                            </th>
                                            <th style="vertical-align:middle; text-align: center;">
                                                Estado de Solicitud
                                            </th>
                                            <th style="vertical-align:middle; text-align: center;">
                                                Ticket
                                            </th>
                                            <th style="vertical-align:middle; text-align: center;">
                                                Descripci&oacute;n
                                            </th>
                                            <th style="vertical-align:middle; text-align: center;">
                                                Codigo SC SIGH
                                            </th>
                                        </tr>
                                        </thead>

                                        <tbody></tbody>
                                        <tfoot class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle; text-align: center;">
                                                Fecha de Solicitud
                                            </th>
                                            <th style="vertical-align:middle; text-align: center;">
                                                Personal Solicitante
                                            </th>
                                            <th style="vertical-align:middle; text-align: center;">
                                                Folio de Solicitud
                                            </th>
                                            <th style="vertical-align:middle; text-align: center;">
                                                N° Interno
                                            </th>
                                            <th style="vertical-align:middle; text-align: center;">
                                                Estado de Solicitud
                                            </th>
                                            <th style="vertical-align:middle; text-align: center;">
                                                Ticket
                                            </th>
                                            <th style="vertical-align:middle; text-align: center;">
                                                Descripci&oacute;n
                                            </th>
                                            <th style="vertical-align:middle; text-align: center;">
                                                Codigo SC SIGH
                                            </th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>


                                <script>
                                    $(document).ready(function () {
                                        var columns = [
                                            {"data": "fechaSoliCompra"},
                                            {"data": "personalSoliCompra"},
                                            {"data": "folioSoliCompra"},
                                            {"data": "numSoliCompra"},
                                            {
                                                "data": null,
                                                "render": function(data, type, row) {
                                                    let estadoSoliCompra = row.estadoSoliCompra.toUpperCase();

                                                    let formHTML = '<form action="soliCompra_sdo_seguimiento.php" method="POST" target="_blank" style="display:inline;" enctype="multipart/form">' +
                                                        '<input type="hidden" name="idSoliCompra" value="' + row.idSoliCompra + '">' +
                                                        '<button type="submit" class="btn btn-xs btn-warning"><b><i class="fa fa-edit"></i>&nbsp;&nbsp;Detalles</b></button>' +
                                                        '</form>';

                                                    return estadoSoliCompra + '<br><br>' + formHTML;
                                                }
                                            },
                                            {"data": "textoTicket"},
                                            {"data": "descripcionSoliCompra"},
                                            {"data": "botonSigh"},

                                            // Agregar columnas dinámicamente
                                        ];

                                        var nomDeptoEnviar = $('#nomDeptoEnviar').val();

                                        $('#tableSCSDO').DataTable({
                                            "lengthChange": false,
                                            "searching": true,
                                            "autoWidth": false,
                                            "responsive": true,
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
                                                "url": "ajax-listado-sc-sdo.php", // Archivo PHP que contiene la consulta SQL
                                                "type": "POST",
                                                "data": function (d) {
                                                    d.nomDeptoEnviar = $('#nomDeptoEnviar').val(); // Pasa el valor seleccionado del select como parámetro
                                                },
                                                "dataSrc": ""
                                            },
                                            "columns": columns,
                                            "createdRow": function (row, data, dataIndex) {
                                                $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                                                $('td', row).css('text-align', 'center'); // Aplica el estilo de centrado vertical a todas las celdas

                                            }
                                        });
                                    });
                                </script>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>

                <div class="row" id="rowMenu2" name="rowMenu2" style="display:none;">

                    <?php
                    if($nomDepartamento == 'Instrumentación' || $nomDepartamento == 'Control Centralizado'){
                        include_once 'sdo-includes/sc-sdo-control.php';
                    }else{
                        include_once 'sdo-includes/sc-sdo-especialidades.php';
                    }
                    ?>

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    toastr.options = {
        "positionClass": "toast-top-right",
        "timeOut": "5000",
        "closeButton": true,
        "progressBar": true
    };
</script>

<?php
// 1. Manejo de Descarga de Cotizaciones
if (!empty($descargaConfirma)) {
    if (strtolower($descargaConfirma) === "y") {
        echo "toastr.success('Descarga de Cotizaciones con éxito', 'Operación Exitosa');";
    } else {
        echo "toastr.error('Error al descargar cotización', 'Error');";
    }
}

// 2. Manejo de Creación de Solicitud de Compra (URL GET)
if (isset($_GET['exitoCrearSC'])) {
    echo "toastr.success('Solicitud de Compra Ingresada con Éxito');";

    $material = $_GET['material'] ?? '';
    if ($material === "Con") {
        echo "toastr.info('Materiales solicitados correctamente');";
    } elseif ($material === "Sin") {
        echo "toastr.warning('Solicitud sin materiales específicos');";
    } else {
        echo "toastr.error('Error al procesar materiales');";
    }
}

if (isset($_GET['errorCrearSC'])) {
    echo "toastr.error('Error al ingresar solicitud de compra', 'Error de Registro');";
}

// 3. Manejo de Anulación
if (isset($_GET['exitoAnularSolicitud'])) {
    echo "toastr.success('Anulación de solicitud realizada', 'Anulado');";
}

if (isset($_GET['errorAnularSolicitud'])) {
    echo "toastr.error('Error al anular la solicitud', 'Error');";
}

echo "</script>";
?>
<!-- Page specific script -->
<script>
    function mostrarRadio(inputFile) {
        radios = document.getElementById("radios");
        inputRadio1 = document.getElementById("isCotizacion1");
        inputRadio2 = document.getElementById("isCotizacion2");
        if (inputFile.files.length > 0) {
            radios.style.display = "block";
            inputRadio1.required = true;
            inputRadio2.required = true;
        } else {
            radios.style.display = "none";
            inputRadio1.required = false;
            inputRadio2.required = false;
        }
    }
</script>
<script>
    $(document).ready(function () {
        $(document).on('click', '.btn-buscar', function () {
            const boton = $(this);
            const correlativoOriginal = boton.data('correlativo');
            const idSoliCompra = boton.data('id');
            const originalHTML = boton.html();

            // Función interna para actualizar la base de datos SDO
            const guardarCodigoSDO = (nuevoValor) => {
                return $.ajax({
                    url: '../sdo-funciones/sc_sigh_actualizar_codigo.php',
                    type: 'POST',
                    dataType: 'json',
                    data: { id: idSoliCompra, nuevoSigh: nuevoValor }
                });
            };

            // --- FLUJO 1: AGREGAR (Si el correlativo viene vacío desde el AJAX) ---
            if (!correlativoOriginal || correlativoOriginal === '') {
                Swal.fire({
                    title: 'Agregar Código SIGH',
                    input: 'text',
                    inputLabel: 'Ingrese el correlativo (Ej: 005/2026)',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fas fa-save"></i> Guardar',
                    confirmButtonColor: '#28a745',
                    preConfirm: (nuevoValor) => {
                        if (!nuevoValor) return Swal.showValidationMessage('El código es obligatorio');
                        return guardarCodigoSDO(nuevoValor);
                    }
                }).then((result) => {
                    if (result.isConfirmed && result.value.status === 'success') {
                        toastr.success('Código vinculado correctamente');
                        $('#tableSCSDO').DataTable().ajax.reload(null, false);
                    }
                });
                return;
            }

            // --- FLUJO 2: CONSULTA Y EDICIÓN (Si ya existe un correlativo) ---
            boton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

            fetch('/sdo-funciones/solic_adquis_tic_a_sdo.php?correlativo=' + encodeURIComponent(correlativoOriginal))
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let etapa = data.etapa_actual.toLowerCase();
                        let icono = etapa === "proceso completado" ? 'success' : (etapa.includes("pendiente") ? 'warning' : 'info');
                        let claseBadge = etapa === "proceso completado" ? 'badge-success' : (etapa.includes("pendiente") ? 'badge-warning' : 'badge-info');

                        Swal.fire({
                            icon: icono,
                            title: 'Solicitud SIGH Encontrada',
                            html: `
                            <div style="text-align: left; font-size: 0.95rem;">
                                <p><b>Solicitante:</b> ${data.nombre_solicitante}</p>
                                <p><b>Etapa:</b> <span class="badge ${claseBadge}">${data.etapa_actual.toUpperCase()}</span></p>
                                <hr>
                                <div class="form-group">
                                    <label for="nuevoCorrelativo"><b>Corregir Número SIGH:</b></label>
                                    <input type="text" id="nuevoCorrelativo" class="form-control" value="${data.correlativo}">
                                    <small class="text-muted">Ejemplo: 005/2026</small>
                                </div>
                            </div>
                        `,
                            showCancelButton: true,
                            confirmButtonText: 'Actualizar',
                            preConfirm: () => {
                                const nuevoV = $('#nuevoCorrelativo').val();
                                return guardarCodigoSDO(nuevoV);
                            }
                        }).then((result) => {
                            if (result.isConfirmed && result.value.status === 'success') {
                                toastr.success('Código actualizado correctamente');
                                $('#tableSCSDO').DataTable().ajax.reload(null, false);
                            }
                        });
                    } else {
                        // CASO: CÓDIGO MAL INGRESADO (No existe en SIGH)
                        Swal.fire({
                            icon: 'warning',
                            title: 'Código no validado',
                            html: `
                            <div style="text-align: center;">
                                <p>El código <b>${correlativoOriginal}</b> no existe en SIGH.</p>
                                <div class="form-group text-left">
                                    <label>Ingrese el código correcto:</label>
                                    <input type="text" id="corregirSigh" class="form-control" placeholder="000/0000">
                                </div>
                            </div>
                        `,
                            showCancelButton: true,
                            confirmButtonText: 'Corregir',
                            preConfirm: () => {
                                const nuevoV = $('#corregirSigh').val();
                                if (!nuevoV) return Swal.showValidationMessage('Debe ingresar un valor');
                                return guardarCodigoSDO(nuevoV);
                            }
                        }).then((result) => {
                            if (result.isConfirmed && result.value.status === 'success') {
                                toastr.success('Código corregido');
                                $('#tableSCSDO').DataTable().ajax.reload(null, false);
                            }
                        });
                    }
                })
                .catch(error => Swal.fire('Error', 'No se pudo conectar con el servidor SIGH', 'error'))
                .finally(() => boton.prop('disabled', false).html(originalHTML));
        });
    });
</script>
</body>

</html>


