<?php


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['nomUsuario'], $_SESSION['userUsuario'], $_SESSION['idUsuario'], $_SESSION['rolUsuario'], $_SESSION['idDepartamento'], $_SESSION['idSubDepartamento'])) {

    $nomUsuario = trim($_SESSION['nomUsuario']);

    $userUsuario = trim($_SESSION['userUsuario']);

    $idUsuario = $_SESSION['idUsuario'];

    $rolUsuario = trim($_SESSION['rolUsuario']);

    $cargoJefe = trim($_SESSION['cargoJefe']);

    $idDepartamento = $_SESSION['idDepartamento'];

    $nomDepartamento = getNomDepartamento($idDepartamento);

    $idSubDepartamento = $_SESSION['idSubDepartamento'];

    $emailJefe = $_SESSION['emailJefe'];

    if (!empty($nomUsuario) && !empty($userUsuario) && !empty($idUsuario) && !empty($rolUsuario) && !empty($idDepartamento)) {
    } else {
        header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    }
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

?>


<!-- Preloader -->
<div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../dist/img/controlCent-HBQP-120.png" alt="Control Centralizado - HBQP">
</div>

<nav class="main-header navbar navbar-expand">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <div class="form-group" style="margin-left: 12px; margin-bottom: 0px;">
                <label class="switch">
                    <input type="checkbox" id="customSwitch3">
                    <span class="slider"></span>
                </label>
            </div>
        </li>
    </ul>

</nav>


<aside class="main-sidebar elevation-4">

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="../dist/img/ss-vina-quillota.png" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block text-dark"><?php echo $nomUsuario; ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->

                <!-- INICIO -->
                <li class="nav-item">
                    <a href="../sdo-gestionDocumental/index.php" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Inicio</p>
                    </a>
                </li>
                <!-- FIN INICIO -->

                <li class="nav-header"></li>
                <li class="nav-header"></li>



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
                            <a href="../sdo-gestionDocumental/soliCompra_pm_administracion.php" class="nav-link">
                                <i class="nav-icon fas fa-shopping-bag"></i>
                                <p>SC - Puesta en Marcha
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="../sdo-gestionDocumental/soliCompra_sdo_administracion.php" class="nav-link">
                                <i class="nav-icon fas fa-shopping-bag"></i>
                                <p>
                                    SC - SDO
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                        </li>

                    </ul>
                </li>



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