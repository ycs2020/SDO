<?php



if (session_status() == PHP_SESSION_NONE) {
    session_start();
}




if (isset($_SESSION['nomUsuario']) && $_SESSION['userUsuario'] && $_SESSION['idUsuario'] && $_SESSION['rolUsuario']) {

    $nomUsuario = trim($_SESSION['nomUsuario']);

    $userUsuario = trim($_SESSION['userUsuario']);

    $idUsuario = trim($_SESSION['idUsuario']);

    $rolUsuario = trim($_SESSION['rolUsuario']);

    $rolUsuario2 = '';
    if ($rolUsuario == 'Bodega Pañol') {
        $rolUsuario2 = 'Bodega del pañol';
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

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<nav class="main-header navbar navbar-expand  navbar-light navbar-dark ">
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
        </li>
    </ul>
</nav>

<aside class="main-sidebar sidebar-primary elevation-4 ">

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
                    <a href="../sdo-bodega-panol/index.php" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Inicio</p>
                    </a>
                </li>
                <!-- FIN INICIO -->


                <li class="nav-header"></li>
                <li class="nav-header"></li>
                <li class="nav-item">
                    <a href="../sdo-bodega-panol/reportes-listado.php" class="nav-link">
                        <i class="fas fa-file"></i>
                        <p> Reportes Control Centralizado</p>
                    </a>
                </li>
                <li class="nav-header"></li>
                <li class="nav-item">
                    <a href="../sdo-bodega-panol/soliCompra_sdo_administracion.php" class="nav-link">
                        <i class="fas fa-money-bill-wave"></i>
                        <p> Solicitudes de compra</p>
                    </a>
                </li>
                <li class="nav-header"></li>
                <li class="nav-item">
                    <a href="../sdo-bodega-panol/soliCompra_pm_administracion.php" class="nav-link">
                        <i class="fas fa-money-bill-alt"></i>
                        <p> Solicitudes de compra - PM</p>
                    </a>
                </li>

                <!-- <li class="nav-header"></li>
                <li class="nav-item">
                    <a href="../sdo-bodega-panol/bodega-administrar.php" class="nav-link">
                        <i class="nav-icon fas fa-tools"></i>
                        <p>Ingreso Herramientas</p>
                    </a>
                </li>

                <li class="nav-header"></li>
                <li class="nav-item">
                    <a href="../sdo-bodega-panol/materiales-administrar.php" class="nav-link">
                        <i class="nav-icon fas fa-box-open"></i>
                        <p>Ingreso Materiales</p>
                    </a>
                </li>

                <li class="nav-header"></li>
                <li class="nav-item">
                    <a href="../sdo-bodega-panol/genera-cod-herra.php" class="nav-link">
                        <i class="fas fa-barcode"></i>
                        <p>Generador Código de Barras</p>
                    </a>
                </li> -->




                <!-- SALIR -->

                <li class="nav-header"></li>
                <li class="nav-header"></li>
                <li class="nav-item">
                    <a href="../sdo-flujo/login_index.php?cerrarSesion=true" class="nav-link">
                        <i class="nav-icon fas fa-power-off"></i>
                        <p>
                            Salir
                        </p>
                    </a>
                </li>
                <!-- FIN SALIR -->


            </ul>

        </nav>
        <!-- /.sidebar-menu -->


    </div>
    <!-- /.sidebar -->



</aside>