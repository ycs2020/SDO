<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$nomUsuario = $_SESSION['nomUsuario'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Men&uacute; del Sistema</title>

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
    <link rel="stylesheet" href="../dist/css/cuadro-cantidad.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

    <style>

        .cantidad1 {
            background-color: blue;
            color: white;
        }

    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php

    $ipCliente = $_SERVER['REMOTE_ADDR'];

    $enlace = '../sdo-templates/admtec-sidebar.php';


    include $enlace;
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">

            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <?php


                if (in_array($idDepartamento, [1, 6, 13])) {
                    include_once "../sdo-includes/div_weather.php";
                }

                include_once '../sdo-includes/admtec_menu_opciones.php'

                ?>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    toastr.options = {
        "positionClass": "toast-top-right",
        "timeOut": "4000",
        "closeButton": true,
        "progressBar": true
    };
</script>

<?php
$obtenerSolicitudesSegunNombre = obtenerSolicitudesSegunNombre($nomUsuario);
$obtenerHistorialDocu = obtenerHistorialDocu($nomUsuario);
$obtenerSolicitudesAdministrativo = obtenerSolicitudesAdministrativo($nomUsuario);

if (empty($nomDepartamento)) {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

$toastrMessages = [
    'loginExito' => [
        'type' => 'success',
        'msg' => 'Ingreso Exitoso a la Plataforma.'
    ],
    'exitoCambioPassword' => [
        'type' => 'success',
        'msg' => 'Contraseña Modificada con Éxito'
    ],
];

foreach ($toastrMessages as $param => $data) {
    if (isset($_GET[$param])) {
        echo "<script>toastr.{$data['type']}('{$data['msg']}');</script>";
    }
}


// Mensajes dinámicos
$toastrMessages = [];

if ($obtenerSolicitudesSegunNombre > 0) {
    $toastrMessages[] = [
        'type' => 'info',
        'msg' => 'Existen nuevas Solicitudes Creadas.'
    ];
}

if ($obtenerHistorialDocu > 0) {
    $toastrMessages[] = [
        'type' => 'info',
        'msg' => 'Existen nuevos Documentos.'
    ];
}

if ($obtenerSolicitudesAdministrativo > 0) {
    $toastrMessages[] = [
        'type' => 'info',
        'msg' => 'Ud. tiene Solicitudes Asignadas.'
    ];
}

foreach ($toastrMessages as $data) {
    echo "<script>toastr.{$data['type']}('{$data['msg']}');</script>";
}
?>


<script src="js/dark-mode.js"></script>
</body>

</html>