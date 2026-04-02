<?php
include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];
$idDepartamentos = $_SESSION['idDepartamentos'];
$userUsuario = $_SESSION['userUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);
$nomUsuario = $_SESSION['nomUsuario'];
date_default_timezone_set('America/Santiago');

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de OT Terminadas</title>

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
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"/>
    <!-- include summernote css-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- include summernote js-->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <style>
        .reset {
            all: revert;
        }

        #cargaCombustible,
        #medicionNormal {
            display: none;
        }
    </style>

    <script>
        $(document).ready(function () {


            // Show div by removing inline display none style rule
            $(".show-realizar").click(function () {
                $("#rowMenu2").show();
                $("#rowMenu1").hide();
                $("#listarAC").show();
                $("#listarAC2").show();
                $("#registrarAC").hide();
                $("#registrarAC2").hide();
            });

            // Show div by removing inline display none style rule
            $(".show-listar").click(function () {
                $("#rowMenu2").hide();
                $("#rowMenu1").show();
                $("#listarAC").hide();
                $("#listarAC2").hide();
                $("#registrarAC").show();
                $("#registrarAC2").show();
            });
        });
    </script>
    <script>
        function DoCheckUncheckDisplay(d, dchecked, dunchecked) {
            if (d.checked == true) {
                document.getElementById(dchecked).style.display = "block";
                document.getElementById(dunchecked).style.display = "none";
            } else {
                document.getElementById(dchecked).style.display = "none";
                document.getElementById(dunchecked).style.display = "block";
            }
        }
    </script>

    <script>
        function selectNit(e) {
            var nit = e.target.selectedOptions[0].getAttribute("data-nit")
            document.getElementById("nit").value = nit;
        }
    </script>


</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<!-- Site wrapper -->
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/admin-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <?php
                function mostrarAlerta($tipo, $titulo, $mensaje)
                {
                    echo "<script>
                        Swal.fire({
                            icon: '$tipo',
                            title: '$titulo',
                            text: '$mensaje'
                        });
                    </script>";
                }

                $alertas = [
                    'exitoSinArchivo' => ['success', 'Éxito', 'Orden de Trabajo ingresada correctamente sin archivo adjunto!'],
                    'exitoConArchivo' => ['success', 'Éxito', 'Orden de Trabajo ingresada correctamente junto con archivo adjunto!'],
                    'errorEnvioArchivo' => ['error', 'Algo salió mal', 'El Archivo Adjunto no se subió de manera correcta!'],
                    'errorArchivo' => ['error', 'Algo salió mal', 'Algo falló con la subida del archivo adjunto. ¡Reintente!'],
                    'archivoNoHabilitado' => ['error', 'Error', 'El archivo no se encuentra permitido.'],
                    'error' => ['error', 'Error', 'Ha ocurrido un error inesperado. Por favor reintente.'],
                    'errorTecnicoTG' => ['error', 'Error', '¡No se puede colocar el técnico dos veces!'],
                    'error4tg' => ['error', 'Error', 'Algunos datos no se han enviado correctamente. Reintente.'],
                    'errorEnlosDatos' => ['error', 'Error', 'Algunos datos no se encuentran llenados. Reintente.'],
                    'exito' => ['success', 'Éxito', 'Se ha ingresado la respuesta con éxito.']
                ];

                foreach ($alertas as $param => [$tipo, $titulo, $mensaje]) {
                    if (isset($_GET[$param])) {
                        mostrarAlerta($tipo, $titulo, $mensaje);
                        break; // solo mostramos una alerta por carga
                    }
                }
                ?>
            </div><!-- /.container-fluid -->
        </section>


        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <!-- Timelime example  -->
                <div class="row">
                    <div class="col-xl-9 col-lg-9 col-md-6 col-12 mb-3 d-flex">
                        <div class="card w-100">
                            <div class="card-body d-flex align-items-center">
                                <h5 class="mb-0">Listado de Ordenes de Trabajo Terminadas</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-12 mb-3 d-flex">
                        <div class="card w-100">
                            <div class="card-body d-flex align-items-center">
                                <a href="index.php" class="btn btn-danger btn-block m-0">
                                    <i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras
                                </a>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row" id="rowMenu1" name="rowMenu1">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <?php
                                $contarfaltavisado = contarfaltavisado($nomUsuario);
                                ?>
                                <h5 style="text-align: right">
                                    <b>Ordenes de Trabajo Sin Visar: </b><?php echo $contarfaltavisado ?>
                                </h5>
                                <?php
                                ?>
                                <hr>
                                <table id="tablaTrabajosTerminados" style="font-size: 10pt;" class="table table-bordered table-striped text-center table-sm" width="100%">
                                    <thead class="bg-secondary text-white">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Folio</th>
                                        <th>Turno</th>
                                        <th>Solicitante</th>
                                        <th>Técnico</th>
                                        <th>Estado</th>
                                        <th>Detalle Visado</th>
                                        <th>Visualizar</th>
                                    </tr>
                                    </thead>
                                    <tfoot class="bg-secondary text-white">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Folio</th>
                                        <th>Turno</th>
                                        <th>Solicitante</th>
                                        <th>Técnico</th>
                                        <th>Estado</th>
                                        <th>Detalle Visado</th>
                                        <th>Visualizar</th>
                                    </tr>
                                    </tfoot>
                                    <tbody></tbody>
                                </table>
                                <style>
                                    #tablaTrabajosTerminados td,
                                    #tablaTrabajosTerminados th {
                                        vertical-align: middle !important;
                                    }
                                </style>
                                <style>
                                    body.dark-mode .table-striped tbody tr:nth-of-type(odd) {
                                        background-color: #2c2c2c !important; /* más oscuro */
                                        color: #ffffff !important; /* texto blanco */
                                    }

                                    body.dark-mode .table-striped tbody tr:nth-of-type(even) {
                                        background-color: #1e1e1e !important; /* fondo aún más oscuro */
                                        color: #ffffff !important;
                                    }

                                    body.dark-mode .table thead th,
                                    body.dark-mode .table tfoot th {
                                        background-color: #3a3a3a !important;
                                        color: #ffffff !important;
                                    }
                                </style>
                                <script>
                                    $(document).ready(function () {
                                        $('#tablaTrabajosTerminados').DataTable({
                                            processing: true,
                                            serverSide: true,
                                            ajax: {
                                                url: '/sdo-includes/trabajos_terminados_cargar.php',
                                                type: 'GET'
                                            },
                                            order: [[0, 'desc'], [1, 'desc'], [2, 'desc']],
                                            pageLength: 10,       // mostrar 10 por página
                                            lengthChange: false,  // quitar "show entries"
                                            columns: [
                                                { title: "Fecha", orderable: true  },
                                                { title: "Folio", orderable: true },
                                                { title: "Turno", orderable: true },
                                                { title: "Solicitante", orderable: false },
                                                { title: "Técnico", orderable: false },
                                                { title: "Estado", orderable: false },
                                                { title: "Detalle Visado", orderable: false },
                                                { title: "Visualizar", orderable: false }
                                            ],
                                            language: {
                                                processing: "Procesando...",
                                                search: "Buscar:",
                                                lengthMenu: "Mostrar _MENU_ registros",
                                                info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                                                infoEmpty: "Mostrando 0 a 0 de 0 registros",
                                                infoFiltered: "(filtrado de _MAX_ registros totales)",
                                                infoPostFix: "",
                                                loadingRecords: "Cargando...",
                                                zeroRecords: "No se encontraron registros",
                                                emptyTable: "No hay datos disponibles en la tabla",
                                                paginate: {
                                                    first: "Primero",
                                                    previous: "Anterior",
                                                    next: "Siguiente",
                                                    last: "Último"
                                                },
                                                aria: {
                                                    sortAscending: ": activar para ordenar ascendente",
                                                    sortDescending: ": activar para ordenar descendente"
                                                }
                                            },
                                            createdRow: function (row, data, dataIndex) {
                                                $('td', row).css('vertical-align', 'middle');
                                            },
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
<!-- Page specific script -->


</body>
</html>
