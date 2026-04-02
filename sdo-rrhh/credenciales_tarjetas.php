<?php
require_once '../sdo-funciones/conexion.php';
require_once '../sdo-funciones/funciones.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validar rol del usuario
$rolUsuario = mb_strtolower($_SESSION['rolUsuario'] ?? '', 'UTF-8');
if ($rolUsuario !== 'rrhh') {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    exit;
}
$ruta = 'sdo-rrhh';


function traspasoFechasEspañol($fecha)
{
    $timestamp = strtotime(str_replace('/', '-', $fecha));

    if ($timestamp !== false && $timestamp > 0) {
        $fecha2 = fechaEspañol2($timestamp);
    } else {
        $fecha2 = 'Fecha inválida';
    }
    $fecha3 = mb_convert_case($fecha2, MB_CASE_UPPER, "UTF-8");
    return $fecha3;
}

// Obtener ID de la credencial desde POST o sesión
$idTarjetaVer = $_POST['idTarjetaVer'] ?? ($_SESSION['idTarjetaVer'] ?? null);
$idTarjetaVer = trim($idTarjetaVer ?? '');

// Validar ID
if (empty($idTarjetaVer) || !is_numeric($idTarjetaVer)) {
    header("Location: ../$ruta/tarjetas_administracion.php?errorCargaTarjeta=true");
    exit;
}

// Obtener datos
$tarjeta = getDatosTarjetasRRHH($idTarjetaVer);

// Validar si existe
if (isset($tarjeta['idTarjeta'])) {
//    echo "✅ Registro encontrado: {$tarjeta['nombreCredencial']} {$tarjeta['apellidoCredencial']}";
    // Aquí puedes continuar con la lógica (mostrar, renderizar, retornar JSON, etc.)

    $idTarjeta = $tarjeta['idTarjeta'];
    $tipoTarjeta = $tarjeta['tipoTarjeta'];
    $codigoTarjeta = $tarjeta['codigoTarjeta'];
    $textoTarjeta = $tarjeta['textoTarjeta'];
    $unidadTarjeta = $tarjeta['unidadTarjeta'];
    $fechaTarjeta = $tarjeta['fechaTarjeta'];



} else {
    header("Location: ../$ruta/tarjetas_administracion.php?errorCargaTarjeta=true");
    exit;
}


function enMayus($texto)
{
    return mb_convert_case(trim($texto), MB_CASE_UPPER, "UTF-8");
}

$campos = [
    'tipoTarjeta', 'codigoTarjeta', 'textoTarjeta', 'unidadTarjeta'
];

foreach ($campos as $campo) {
    ${$campo . '2'} = enMayus($$campo);
}


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Informaci&oacute;n de la Tarjeta</title>

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

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <!-- Theme style -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>


</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/unidadPersonal-sidebar.php';
    ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-xl-10 col-lg-9 col-md-8 col-12">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <a style="pointer-events: none;" class="btn font-weight-bold text-black ">Informaci&oacute;n de la Tarjeta</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                        <div class="card">
                            <div class="card-header bg-danger ">
                                <a href="tarjetas_administracion.php" class="btn btn-block btn-danger">
                                    <i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atrás
                                </a>
                            </div>
                        </div>
                    </div>


                    <div class="col-12">
                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile">

                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm" style="font-size: 15px;">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <b>TIPO TARJETA:</b>
                                            </td>
                                            <td><?php echo $tipoTarjeta2; ?></td>
                                            <td>
                                                <b>CODIGO TARJETA:</b>
                                            </td>
                                            <td><?php echo $codigoTarjeta2; ?></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>TEXTO TARJETA:</b>
                                            </td>
                                            <td><?php echo $textoTarjeta2; ?></td>
                                            <td>
                                                <b>UNIDAD TARJETA:</b>
                                            </td>
                                            <td><?php echo $unidadTarjeta2; ?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>



                            </div>
                        </div>
                    </div>


                    <div class="col-12">
                        <div class="card">
                            <div style="font-size: 10pt;" class="card-header bg-secondary d-flex p-0">
                                <ul class="nav nav-pills mr-auto ml-auto p-2">
                                    <li class="nav-item">
                                        <a class="nav-link text-white active" href="#tabFuncionariosAsoc" data-toggle="tab">Historico de Funcionarios</a>
                                    </li>

                                </ul>
                            </div>

                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tabFuncionariosAsoc">
                                        <div class="col-12">
                                            <h5 class="text-center align-content-center">Historico de Funcionarios</h5>

                                            <hr>

                                            <input type="hidden" name="idTarjetaCargar" id="idTarjetaCargar" value="<?php echo $idTarjetaVer; ?>">

                                            <div class="table-responsive">
                                                <table id="tableListFuncionariosAsoc" class="table table-bordered table-hover table-sm">
                                                    <thead class="bg-gradient-success">
                                                    <tr>
                                                        <th style="vertical-align: middle; text-align: center;">
                                                            #
                                                        </th>
                                                        <th style="vertical-align: middle; text-align: center;">
                                                            NOMBRE
                                                        </th>
                                                        <th style="vertical-align: middle; text-align: center;">
                                                            RUT
                                                        </th>
                                                        <th style="vertical-align: middle; text-align: center;">
                                                            CARGO
                                                        </th>
                                                        <th style="vertical-align: middle; text-align: center;">
                                                            CALIDAD JURIDICA
                                                        </th>
                                                        <th style="vertical-align: middle; text-align: center;">
                                                            UNIDAD
                                                        </th>
                                                        <th style="vertical-align: middle; text-align: center;">
                                                            JEFATURA
                                                        </th>
                                                        <th style="vertical-align: middle; text-align: center;">
                                                            FECHA INICIO
                                                        </th>
                                                        <th style="vertical-align: middle; text-align: center;">
                                                            FECHA TERMINO
                                                        </th>
                                                        <th style="vertical-align: middle; text-align: center;">
                                                            ¿CONTRATO ACTIVO?
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                    <tfoot class="bg-gradient-success">
                                                    <tr>
                                                        <th style="vertical-align: middle; text-align: center;">
                                                            #
                                                        </th>
                                                        <th style="vertical-align: middle; text-align: center;">
                                                            NOMBRE
                                                        </th>
                                                        <th style="vertical-align: middle; text-align: center;">
                                                            RUT
                                                        </th>
                                                        <th style="vertical-align: middle; text-align: center;">
                                                            CARGO
                                                        </th>
                                                        <th style="vertical-align: middle; text-align: center;">
                                                            CALIDAD JURIDICA
                                                        </th>
                                                        <th style="vertical-align: middle; text-align: center;">
                                                            UNIDAD
                                                        </th>
                                                        <th style="vertical-align: middle; text-align: center;">
                                                            JEFATURA
                                                        </th>
                                                        <th style="vertical-align: middle; text-align: center;">
                                                            FECHA INICIO
                                                        </th>
                                                        <th style="vertical-align: middle; text-align: center;">
                                                            FECHA TERMINO
                                                        </th>
                                                        <th style="vertical-align: middle; text-align: center;">
                                                            ¿CONTRATO ACTIVO?
                                                        </th>
                                                    </tr>
                                                    </tfoot>


                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


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

<!-- jQuery -->
<!-- jQuery -->
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="../dist/js/adminlte.min.js"></script>
<!-- Select2 -->
<script src="../plugins/select2/js/select2.full.min.js"></script>

<script>
    $(function () {
        bsCustomFileInput.init();
    });
</script>

<script>

    $(document).ready(function () {
        var tableListFuncionariosAsoc = $('#tableListFuncionariosAsoc').DataTable({
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
                "url": "/sdo-includes/credenciales_rrhh_tarjetas_funcionarios.php", // Archivo PHP que contiene la consulta SQL
                "type": "POST",
                "data": function (d) {
                    d.idTarjetaCargar = $('#idTarjetaCargar').val(); // Pasa el valor seleccionado del select como parámetro

                },
                "dataSrc": function(json) {
                    console.log("🔍 JSON recibido:", json);
                    return json.data;
                },
                "error": function (xhr, error, thrown) {
                    console.error("Error occurred during AJAX request:", error, thrown);
                    console.log("XHR object:", xhr);
                }
            },
            "columns": [
                { "data": null, render: (d,t,r,m)=> m.row+1 },
                { "data": "nombreCredencial" },
                { "data": "rutCredencial" },
                { "data": "nombreCargo" },
                { "data": "nombreCalidadJuridica" },
                { "data": "nombreUnidad" },
                { "data": "nombreJefatura" },
                { "data": "fechaInicioContrato2" },
                { "data": "fechaTerminoContrato2" },
                { "data": "contratoActivo2" },
            ]
            ,
            "createdRow": function (row, data, dataIndex) {
                $('td', row).css('vertical-align', 'middle');
                $('td', row).css('text-align', 'center');
                $('td', row).css('text-transform', 'uppercase');
                $('td:eq(0)', row).html(dataIndex + 1); // Actualiza el contenido de la primera celda (columna "N°")
                // $('td', row).css('font-size', '10pt');

            }
        });
    });
</script>

</body>

</html>
