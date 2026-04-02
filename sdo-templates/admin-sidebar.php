<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');

$fechaHoyPara18 = date('Y-m-d H:i:s');
$anioActualizado = date('Y');

$fecha_inicio_chile = new DateTime($anioActualizado . '-09-01 00:00:00');
$fecha_fin_chile = new DateTime($anioActualizado . '-09-21 23:59:59');

$camposSesion = ['nomUsuario', 'userUsuario', 'idUsuario', 'rolUsuario', 'cargoUsuario', 'emailUsuario'];
foreach ($camposSesion as $campo) {
    if (!isset($_SESSION[$campo]) || empty(trim($_SESSION[$campo]))) {
        header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
        exit();
    }
}

$nomUsuario = trim($_SESSION['nomUsuario']);
$userUsuario = trim($_SESSION['userUsuario']);
$idUsuario = trim($_SESSION['idUsuario']);
$rolUsuario = trim($_SESSION['rolUsuario']);
$cargoUsuario = trim($_SESSION['cargoUsuario']);
$emailUsuario = trim($_SESSION['emailUsuario']);

$rolUsuarioMinuss = mb_convert_case($rolUsuario, MB_CASE_LOWER, 'UTF-8');


if (!$isAdmin = isAdmin($userUsuario)) {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    exit();
}

$userUsuarioMinus = mb_convert_case($userUsuario, MB_CASE_LOWER, 'UTF-8');
$nomDepartamento = 'Control Centralizado';

$mensaje = '';
?>

<style>
    .nav-link:hover {
        color: #03cea4 !important;
        font-weight: bold;
    }
    .nav-link {
        color: #ced4da;
        font-weight: bold;
    }
    body.custom-cursor {
        cursor: url('../sdo-flujo/sdo-imagenes/chile24.png'), auto;
    }
</style>

<script>
    const fechaInicioChile = new Date("<?= $fecha_inicio_chile->format('Y-m-d') ?>");
    const fechaFinChile = new Date("<?= $fecha_fin_chile->format('Y-m-d') ?>");
    const fechaHoy = new Date();

    if (fechaHoy >= fechaInicioChile && fechaHoy <= fechaFinChile) {
        document.body.classList.add('custom-cursor');
    }
</script>

<link rel="stylesheet" href="../plugins/toastr/toastr.min.css">
<script src="../plugins/toastr/toastr.min.js"></script>

<?php if (!empty($mensaje)): ?>
    <script>
        window.onload = function () {
            setTimeout(function () {
                toastr.options = {
                    closeButton: true,
                    timeOut: 10000,
                    extendedTimeOut: 10000,
                    progressBar: true
                };
                toastr.success("<?= $mensaje ?>");
            }, 500);
        };
    </script>
<?php endif; ?>

<nav class="main-header navbar navbar-expand navbar-light navbar-dark">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button" title="Ocultar/Mostrar menú"><i class="fas fa-hand-point-left"></i></a>
        </li>
        <li class="nav-item">
            <a href="../sdo-administrador/miperfil.php" data-toggle="tooltip" title="Mi Perfil">
                <img src="../sdo-administrador/img/GOB.png" alt="User Avatar" class="img-size-50 img-circle mr-3">
            </a>
        </li>
        <li class="nav-item mt-2 mr-2">
            <a href="../sdo-administrador/miperfil.php" class="btn btn-sm bg-gradient-warning"><b><i class="fas fa-user-cog"></i> Mi Perfil: <?= $nomUsuario ?></b></a>
        </li>
        <li class="nav-item mt-2">
            <a href="../sdo-administrador/soporte-sdo.php" class="btn btn-sm bg-gradient-danger"><b><i class="fas fa-headset"></i> Soporte</b></a>
        </li>
    </ul>
</nav>

<aside class="main-sidebar sidebar-primary elevation-4">
    <div class="sidebar">
        <div class="text-center">
            <p style="font-family: 'Roboto', sans-serif; font-weight: 700; margin-left: 20px; color: #03cea4">Light Mode / Dark Mode</p>
            <li class="nav-item d-none d-sm-inline-block">
                <div class="form-group" style="margin-left: 12px; margin-bottom: 0px;">
                    <label class="switch">
                        <input type="checkbox" id="customSwitch3">
                        <span class="slider"></span>
                    </label>
                </div>
            </li>
        </div>
        <br>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-child-indent nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="../sdo-administrador/index.php" class="nav-link text-bold">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Inicio</p>
                    </a>
                </li>

                <?php
                include_once 'menu_array_admin.php'; // Suponiendo que defines $menu ahí con usort

                function renderMenu($menu) {
                    foreach ($menu as $navItem) {
                        echo '<li class="nav-header"></li>';
                        echo '<li class="nav-item">';
                        echo '<a href="'.$navItem['link'].'" class="nav-link text-bold">';
                        echo '<i class="nav-icon '.$navItem['icon'].'"></i><p>'.$navItem['title'];
                        if (!empty($navItem['subitems'])) {
                            echo '<i class="right fas fa-angle-left"></i>';
                        }
                        echo '</p></a>';
                        if (!empty($navItem['subitems'])) {
                            echo '<ul class="nav nav-treeview">';
                            foreach ($navItem['subitems'] as $subItem) {
                                echo '<li class="nav-item">';
                                echo '<a href="'.$subItem['link'].'" class="nav-link text-bold">';
                                echo '<i class="nav-icon '.$subItem['icon'].'"></i><p>'.$subItem['title'].'</p></a></li>';
                            }
                            echo '</ul>';
                        }
                        echo '</li>';
                    }
                }

                renderMenu($menuAdmin);
                ?>
            </ul>
        </nav>
    </div>
    <div class="sidebar-custom">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="../sdo-flujo/login_index.php?cerrarSesion=true" class="nav-link text-bold">
                        <i class="nav-icon fas fa-power-off"></i>
                        <p>Salir</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
