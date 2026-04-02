
<?php


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$camposRequeridos = ['nomUsuario', 'userUsuario', 'idUsuario', 'rolUsuario', 'idDepartamento'];

foreach ($camposRequeridos as $campo) {
    if (empty($_SESSION[$campo]) || trim($_SESSION[$campo]) === '') {
        header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
        exit;
    }
}

$idDepartamento = (int) $_SESSION['idDepartamento'];
if ($idDepartamento <= 0) {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    exit;
}

$nomUsuario     = trim($_SESSION['nomUsuario']);
$userUsuario    = trim($_SESSION['userUsuario']);
$idUsuario      = trim($_SESSION['idUsuario']);
$rolUsuario     = trim($_SESSION['rolUsuario']);
$nomDepartamento = getNomDepartamento($idDepartamento);

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
</style>

<link rel="stylesheet" href="../plugins/toastr/toastr.min.css">
<script src="../plugins/toastr/toastr.min.js"></script>



<nav class="main-header navbar navbar-expand  navbar-light navbar-dark ">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" id="toggle-sidebar" data-widget="pushmenu" href="#" role="button"><i id="sidebar-icon" class="fas fa-hand-point-left"></i></a>
        </li>

        <!-- Message Start -->
        <li class="nav-item">
            <a href="#" data-toggle="tooltip" title="Click para ir a tu perfil">
                <img src="../sdo-administrador/img/ssvq1.png " alt="User Avatar" class="img-size-50 img-circle mr-3">
            </a>
        </li>
        <li class="nav-item mt-2 mr-2">
            <a href="miperfil.php" class="btn btn-sm bg-gradient-warning"> <b><i class="fas fa-user-cog"></i> Mi Perfil: <?php echo $nomUsuario ?></b></a>

        </li>
        <li class="nav-item mt-2">
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
                    <a href="../sdo-admtec/index.php" class="nav-link text-bold">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Inicio</p>
                    </a>
                </li>

                <?php
                include_once 'menu_array_administrativa.php';

                $idDepartamento = $_SESSION['idDepartamento'] ?? 0;

                function isAllowed($item, $idDepartamento) {
                    return !isset($item['allowed_departments']) || in_array($idDepartamento, $item['allowed_departments']);
                }

                function renderMenu($menu, $idDepartamento) {
                    foreach ($menu as $navItem) {
                        if (!isAllowed($navItem, $idDepartamento)) continue;

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
                                if (!isAllowed($subItem, $idDepartamento)) continue;

                                echo '<li class="nav-item">';
                                echo '<a href="'.$subItem['link'].'" class="nav-link text-bold">';
                                echo '<i class="nav-icon '.$subItem['icon'].'"></i><p>'.$subItem['title'].'</p></a></li>';
                            }
                            echo '</ul>';
                        }

                        echo '</li>';
                    }
                }

                renderMenu($menuAdmin, $idDepartamento);
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
