<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['nomUsuario']) && $_SESSION['userUsuario'] && $_SESSION['idUsuario'] && $_SESSION['rolUsuario']) {

    $nomUsuario = trim($_SESSION['nomUsuario']);

    $userUsuario = trim($_SESSION['userUsuario']);

    $idUsuario = trim($_SESSION['idUsuario']);

    $rolUsuario = trim($_SESSION['rolUsuario']);

    $emailUsuario = trim($_SESSION['emailUsuario']);

    $nomDepartamento = 'Mesa de Ayuda - SDO';

    $rolUsuario2 = '';
    if ($rolUsuario == 'Mesa Ayuda') {
        $rolUsuario2 = 'Mesa de Ayuda';
    } else {
        header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    }

    if ($nomUsuario == '' || $nomUsuario == ' ' || empty($nomUsuario) || $userUsuario == '' || $userUsuario == ' ' || empty($userUsuario) || $idUsuario == '' || $idUsuario == ' ' || empty($idUsuario)  || $rolUsuario == '' || $rolUsuario == ' ' || empty($rolUsuario)) {
        header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    } else {
    }
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
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

            <a href="miperfil.php" class="btn btn-sm bg-gradient-warning"> <b><i class="fas fa-user-cog"></i> Mi Perfil: <?php echo $nomUsuario ?></b></a>
        </li>
        <li class="nav-item mt-2">
            <!--   <a href="soporte-sdo.php" class="btn btn-sm bg-gradient-danger"> <b><i class="fas fa-headset"></i> Soporte</b></a> -->
        </li>

    </ul>


</nav>

<aside class="main-sidebar elevation-4">

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
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

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->

                <!-- INICIO -->
                <li class="nav-item">
                    <a href="../sdo-mesa-ayuda/index.php" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Inicio</p>
                    </a>
                </li>
                <!-- FIN INICIO -->


                <?php

                if ($idUsuario == 1) {
                ?>
                    <li class="nav-header"></li>
                    <li class="nav-item">

                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-boxes"></i>

                            <p>
                                Equipamiento HBQP
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="../sdo-mesa-ayuda/equipamiento_hbqp.php" class="nav-link">
                                    <i class="fas fa-clipboard-list"></i>
                                    <p>Listado de equipos</p>
                                    <i class="right fas fa-angle-left"></i>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php
                }

                ?>

                <?php

                if($nomUsuario == 'Mesa De Ayuda S D O'){
                    ?>
                   <!-- <li class="nav-item">
                        <a href="usuarios_tickets.php" class="nav-link">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                Listado de Usuarios
                            </p>
                        </a>
                    </li> -->
                    <?php

                }
                ?>
                <li class="nav-header"></li>
                <li class="nav-item">
                    <a href="../sdo-mesa-ayuda/solicitudes_recibidas.php" class="nav-link">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Tickets Recibidos</p>
                    </a>
                </li>

                <li class="nav-header"></li>
                <li class="nav-item">
                    <a href="../sdo-mesa-ayuda/solicitudes_finalizadas.php" class="nav-link">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Tickets Finalizados</p>
                    </a>
                </li>

                <li class="nav-header"></li>
                <li class="nav-item">
                    <a href="../sdo-mesa-ayuda/solicitudes_canceladas.php" class="nav-link">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Tickets Cancelados</p>
                    </a>
                </li>

                <li class="nav-header"></li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-shopping-bag"></i>
                        <p>
                            Solicitudes de Compra
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="../sdo-mesa-ayuda/soliCompra_pm_administracion.php" class="nav-link">
                                <i class="nav-icon fas fa-shopping-bag"></i>
                                <p>SC - Puesta en Marcha
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="../sdo-mesa-ayuda/soliCompra_sdo_administracion.php" class="nav-link">
                                <i class="nav-icon fas fa-shopping-bag"></i>
                                <p>
                                    SC - SDO
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../sdo-mesa-ayuda/soliCompra_sdo_resumen.php" class="nav-link">
                                <i class="nav-icon fas fa-shopping-bag"></i>
                                <p>
                                    SC - SDO - Resumen
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                        </li>

                    </ul>
                </li>


                <li class="nav-header"></li>
                <li class="nav-item">
                    <a href="../sdo-mesa-ayuda/solicitudes_telefonicas.php" class="nav-link">
                        <i class="nav-icon fas fa-address-book"></i>
                        <p>Solicitudes Telefonicas</p>
                    </a>
                </li>


                <li class="nav-header"></li>
                <li class="nav-item">
                    <a href="../sdo-mesa-ayuda/consumos_basicos.php" class="nav-link">
                        <i class="nav-icon fas fa-hospital"></i>
                        <p>Consumos Básicos</p>
                    </a>
                </li>

            </ul>

        </nav>
        <!-- /.sidebar-menu -->
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