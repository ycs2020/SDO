<?php

include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$camposSesion = ['nomUsuario', 'userUsuario', 'idUsuario', 'rolUsuario', 'departamentoPersonal'];
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
$cargoPersonal = trim($_SESSION['cargoUsuario']);
$departamentoPersonal = trim($_SESSION['departamentoPersonal']);
$idDepartamento = trim($_SESSION['idDepartamento']);
$idSubDepartamento = trim($_SESSION['idSubDepartamento']);

$rolUsuarioMinuss = mb_convert_case($rolUsuario, MB_CASE_LOWER, 'UTF-8');
if ($rolUsuarioMinuss !== 'personal') {
    echo '<script language="javascript">window.location.href="../sdo-flujo/login_index.php?tipoUsuarioNoPermitido=true";</script>';
}

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


<style>
    body.custom-cursor {
        cursor: url('../sdo-flujo/sdo-imagenes/chile24.png'), auto;
    }
</style>

<script>
    // Definir las fechas de inicio y fin
    const anioActual = new Date().getFullYear();
    const fechaInicioChile = new Date(`${anioActual}-09-01`);
    const fechaFinChile = new Date(`${anioActual}-09-21`);
    const fechaHoy = new Date();

    // Verificar si la fecha actual está dentro del rango
    if (fechaHoy >= fechaInicioChile && fechaHoy <= fechaFinChile) {
        // Añadir la clase que cambia el cursor
        document.body.classList.add('custom-cursor');
    }
</script>

<nav class="main-header navbar navbar-expand  navbar-light navbar-dark ">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" id="toggle-sidebar" data-widget="pushmenu" href="#" role="button"><i id="sidebar-icon" class="fas fa-hand-point-left"></i></a>
        </li>

        <li class="nav-item">
            <a href="#" data-toggle="tooltip" title="Click para ir a tu perfil">
                <img src="../sdo-administrador/img/ssvq1.png" alt="User Avatar" class="img-size-50 img-circle mr-3">
            </a>
        </li>
        <li class="nav-item mt-2 mr-2">
            <a href="../sdo-personal/miperfil.php" class="btn btn-sm bg-gradient-warning">
                <b><i class="fas fa-user-cog"></i> Mi Perfil: <?php echo $nomUsuario ?>
                </b></a>
        </li>
<!--        <li class="nav-item mt-2">-->
<!--             <a href="soporte-sdo.php" class="btn btn-sm bg-gradient-danger"><b><i class="fas fa-headset"></i> Soporte</b></a> -->
<!--        </li>-->

    </ul>

</nav>
<aside class="main-sidebar  elevation-4 ">

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="text-center">
            <p style=" font-family: 'Roboto', sans-serif; font-weight: 700; margin-left: 20px; color: #03cea4" class="align-items-center; text-center">Light Mode / Dark Mode </p>
            <li class="nav-item d-none d-sm-inline-block ">
                <div class="form-group" style="margin-left: 12px; margin-bottom: 0px;">
                    <label class="switch">
                        <input type="checkbox" id="customSwitch3">
                        <span class="slider"></span>
                    </label>
                </div>
            </li>

        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-child-indent nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="../sdo-personal/index.php" class="nav-link text-bold">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Inicio</p>
                    </a>
                </li>


                <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
                <div class="form-inline mt-2 mb-2 px-2">
                    <input class="form-control form-control-sm" id="sidebarSearch" type="text" placeholder="Buscar opción..." style="width: 100%;">
                </div>


                <li class="nav-header"></li>
                <li class="nav-header"></li>

                <?php
                include_once 'menu_array_personal.php'; // Suponiendo que defines $menu ahí con usort

                function renderMenu($menu) {
                    foreach ($menu as $item) {
                        $hasSub = !empty($item['subitems']);
                        $treeviewClass = $hasSub ? ' has-treeview' : '';
                        $linkHref = $item['link'] ?? '#';

                        echo '<li class="nav-item' . $treeviewClass . '">';
                        echo '  <a href="' . $linkHref . '" class="nav-link">';
                        echo '    <i class="nav-icon ' . $item['icon'] . '"></i>';
                        echo '    <p>' . $item['title'];
                        if ($hasSub) {
                            echo ' <i class="right fas fa-angle-left"></i>';
                        }
                        echo '    </p>';
                        echo '  </a>';

                        if ($hasSub) {
                            echo '<ul class="nav nav-treeview">';
                            foreach ($item['subitems'] as $sub) {
                                $subHref = $sub['link'] ?? '#';
                                echo '<li class="nav-item">';
                                echo '  <a href="' . $subHref . '" class="nav-link">';
                                echo '    <i class="nav-icon ' . $sub['icon'] . '"></i>';
                                echo '    <p>' . $sub['title'] . '</p>';
                                echo '  </a>';
                                echo '</li>';
                            }
                            echo '</ul>';
                        }

                        echo '</li>';
                    }
                }



                renderMenu($menuPersonal);
                ?>
            </ul>
        </nav>
    </div>
    <!-- /.sidebar -->
    <div class="sidebar-custom">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="../sdo-flujo/login_index.php?cerrarSesion=true" class="nav-link">
                        <i class="nav-icon fas fa-power-off"></i>
                        <p>
                            Salir
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
<script>
    $(document).ready(function () {
        $('#sidebarSearch').on('keyup', function () {
            let value = $(this).val().toLowerCase();

            // Itera sobre cada ítem del menú
            $('.nav-sidebar .nav-item').each(function () {
                let textoPrincipal = $(this).find('> a > p').text().toLowerCase();
                let coincide = textoPrincipal.includes(value);

                // Si el ítem tiene subitems
                if ($(this).hasClass('has-treeview')) {
                    let encontradoEnSubitem = false;

                    $(this).find('.nav-treeview .nav-link').each(function () {
                        let textoSub = $(this).find('p').text().toLowerCase();
                        if (textoSub.includes(value)) {
                            encontradoEnSubitem = true;
                            $(this).closest('li').show();
                        } else {
                            $(this).closest('li').hide();
                        }
                    });

                    if (coincide || encontradoEnSubitem) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }

                    if (encontradoEnSubitem) {
                        $(this).addClass('menu-open');
                        $(this).find('.nav-treeview').show();
                    } else {
                        $(this).removeClass('menu-open');
                        $(this).find('.nav-treeview').hide();
                    }

                } else {
                    // Ítem sin subitems
                    $(this).toggle(coincide);
                }
            });
        });
    });
</script>

