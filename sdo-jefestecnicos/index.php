<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$idDepartamento = $_SESSION['idDepartamento'];
$nomUsuario = $_SESSION['nomUsuario'];
$horaActual = date('H');
$anioActual = date('Y');


$ipPermitida = '10.69.250.151';

$ipCliente = $_SERVER['REMOTE_ADDR'];


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Men&uacute; del Sistema</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

    <!-- Theme style -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">
    <link rel="stylesheet" href="../dist/css/cuadro-cantidad.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .rotar {
            transform: rotate(180deg);
            transition: transform 0.7s ease;
        }

        .animacion_agrandar:hover {
            animation-name: bounce;
            animation-duration: 1s;
        }

        .cantidad1 {
            background-color: blue;
            color: white;
        }

        .cantidad4 {
            background-color: green;
            color: white;
        }

        .cantidad5 {
            background-color: green;
            color: white;
        }

    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">


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
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                <div class="row justify-content-center align-content-center">

                                    <?php
                                    // Array de tarjetas
                                    $tarjetas = [
                                        [
                                            'href' => 'ordenes-listado-general.php',
                                            'bg' => 'bg-gradient-primary',
                                            'icon' => 'fas fa-file-alt',
                                            'label' => 'Hojas Técnicas',
                                            'value' => countHTDeptoAnio($idDepartamento, $anioActual),
                                        ],
                                        [
                                            'href' => 'soliCompra_sdo_administracion.php',
                                            'bg' => 'bg-gradient-warning',
                                            'icon' => 'fas fa-shopping-cart',
                                            'label' => 'Solicitudes Compra',
                                            'value' => countSCSDODeptoAnio($nomDepartamento, $anioActual),
                                        ],
                                        [
                                            'href' => 'equipamiento_hbqp.php',
                                            'bg' => 'bg-gradient-purple',
                                            'icon' => 'fas fa-cogs',
                                            'label' => 'Equipos',
                                            'value' => countEquipamientoDepto($idDepartamento),
                                        ],
                                        [
                                            'href' => 'avisos_relevantes_administracion.php',
                                            'bg' => (countAvisosRelevantesDirigidos($nomUsuario) > 0) ? 'bg-gradient-danger' : 'bg-gradient-success',
                                            'icon' => 'fas fa-phone',
                                            'label' => 'Avisos Relevantes',
                                            'value' => countAvisosRelevantesDirigidos($nomUsuario),
                                        ],
                                    ];

                                    // Si corresponde agregar Reportes Turno
                                    if ($idDepartamento == 1 || $idDepartamento == 13) {
                                        $tarjetas[] = [
                                            'href' => 'reportes-listado.php',
                                            'bg' => 'bg-gradient-info',
                                            'icon' => 'fas fa-book',
                                            'label' => 'Reportes Turno',
                                            'value' => getCantTotalReportes(),
                                        ];
                                    }

                                    // Imprimir tarjetas
                                    foreach ($tarjetas as $card) {
                                        echo '
                                              <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2">
                                                <a href="' . $card['href'] . '" class="text-white text-decoration-none">
                                                  <div class="card ' . $card['bg'] . ' shadow-sm py-2">
                                                    <div class="card-body p-2 text-center">
                                                      <i class="' . $card['icon'] . ' fa-lg mb-2 d-block"></i>
                                                      <div class="h5 font-weight-bold mb-0">' . $card['value'] . '</div>
                                                      <small class="text-uppercase">' . $card['label'] . '</small>
                                                    </div>
                                                  </div>
                                                </a>
                                              </div>';
                                    }
                                    ?>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- /.container-fluid -->
        </section>



        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <?php
                include_once 'sdo-includes/jefes_menu_opciones.php'
                ?>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <strong>Copyright &copy; 2022. Derechos Reservados.
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->

<!-- SCRIPT DARK MODE -->
<script src="js/dark-mode.js"></script>

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
if (isset($_GET['accesoSoporteDenegado'])) {
    echo "<script>toastr.error('No se encuentra habilitado para ingresar a esta funcionalidad.');</script>";
}

if (isset($_GET['exitoMarcajeAsistencia'])) {
    echo "<script>toastr.success('Marcaje registrado correctamente.');</script>";
}
if (isset($_GET['errorMarcajeAsistencia'])) {
    echo "<script>toastr.error('Error al registrar la asistencia.');</script>";
}
if (isset($_GET['existeMarcajeAsistencia'])) {
    echo "<script>toastr.info('Existen Marcaje de Asistencia.');</script>";
}


$obtenerHistorialDocu = obtenerHistorialDocu($nomUsuario);
$obtenerOTSegunNombre = obtenerOTSegunNombre($nomUsuario);
$contarfaltavisado = contarfaltavisado($nomUsuario);
$obtenerSolicitudesSegunNombre = obtenerSolicitudesSegunNombre($nomUsuario);

if (isset($_GET['loginExito'])) {
    echo "<script>toastr.success('Ingreso Exitoso a la Plataforma.');</script>";
}

if ($obtenerSolicitudesSegunNombre > 0) {
    echo "<script>toastr.info('Tiene Solicitudes de Soporte Asignadas.');</script>";
}

if ($obtenerOTSegunNombre > 0) {
    echo "<script>toastr.info('Existen nuevas Ordenes de Trabajo.');</script>";
}


if ($idDepartamento == 1) {
    if ($contarfaltavisado > 0) {
        $mensajeSinVisar = "";
        echo "<script>toastr.error('Existen <b>$contarfaltavisado</b> Ordenes de Trabajo sin visar.');</script>";

    }
}
?>
<!-- Page specific script -->



</body>

</html>


