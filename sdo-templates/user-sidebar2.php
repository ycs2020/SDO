<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['nomUsuario']) && $_SESSION['userUsuario'] && $_SESSION['idUsuario'] && $_SESSION['rolUsuario']) {

    $nomUsuario = trim($_SESSION['nomUsuario']);

    $userUsuario = trim($_SESSION['userUsuario']);

    $idUsuario = trim($_SESSION['idUsuario']);

    $rolUsuario = trim($_SESSION['rolUsuario']);

    $cargoUsuario = trim($_SESSION['cargoUsuario']);

    $emailUsuario = trim($_SESSION['emailUsuario']);

    if ($cargoUsuario == '' || empty($cargoUsuario) || $nomUsuario == '' || $nomUsuario == ' ' || empty($nomUsuario) || $userUsuario == '' || $userUsuario == ' ' || empty($userUsuario) || $idUsuario == '' || $idUsuario == ' ' || empty($idUsuario)  || $rolUsuario == '' || $rolUsuario == ' ' || empty($rolUsuario)) {
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
<!-- 
<nav class="main-header navbar navbar-expand">
    <ul class="navbar-nav">
        <li class="nav-item ">
            <a class="nav-link text-orange" id="toggle-sidebar" data-widget="pushmenu" href="#" role="button"><i id="sidebar-icon" class="fas fa-hand-point-left"></i></a>
        </li>

    </ul>
</nav> -->

<nav class="main-header navbar navbar-expand  navbar-light navbar-dark ">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" id="toggle-sidebar" data-widget="pushmenu" href="#" role="button"><i id="sidebar-icon" class="fas fa-hand-point-left"></i></a>
        </li>

        <!-- Message Start -->
        <li class="nav-item">
            <a href="miperfil.php" data-toggle="tooltip" title="Click para ir a tu perfil">
                <img src="../sdo-administrador/img/GOB.png" alt="User Avatar" class="img-size-50 img-circle mr-3">
            </a>
        </li>
        <li class="nav-item mt-2 mr-2">

            <a href="miperfil.php" class="btn btn-sm bg-gradient-warning"> <b><i class="fas fa-user-cog"></i> Mi Perfil</b></a>
        </li>
        <li class="nav-item mt-2">
            <a href="soporte-sdo.php" class="btn btn-sm bg-gradient-danger" > <b><i class="fas fa-headset"></i> Soporte</b></a>
        </li>
        

        <!-- Message End -->
    </ul>



    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        <!-- Navbar Search -->
        <!-- <li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="fas fa-search"></i>
            </a>
            <div class="navbar-search-block">
                <form class="form-inline">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li> -->

        <!-- Messages Dropdown Menu -->
        <!-- <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-comments"></i>
                <span class="badge badge-danger navbar-badge">3</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="#" class="dropdown-item">
                    <div class="media">
                        <img src="../../dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                Brad Diesel
                                <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">Call me whenever you can...</p>
                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                        </div>
                    </div>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <div class="media">
                        <img src="../../dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                John Pierce
                                <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">I got your message bro</p>
                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                        </div>
                    </div>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <div class="media">
                        <img src="../../dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                Nora Silvester
                                <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">The subject goes here</p>
                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                        </div>
                    </div>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
            </div>
        </li> -->
        <!-- Notifications Dropdown Menu -->
        <!-- <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">15</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">15 Notifications</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-envelope mr-2"></i> 4 new messages
                    <span class="float-right text-muted text-sm">3 mins</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-users mr-2"></i> 8 friend requests
                    <span class="float-right text-muted text-sm">12 hours</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-file mr-2"></i> 3 new reports
                    <span class="float-right text-muted text-sm">2 days</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
        </li> -->
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

    </ul>
</nav>

<aside class="main-sidebar sidebar-primary   elevation-4">
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">

            <div class="image">
                <img src="../dist/img/ss-vina-quillota.png" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="index.php" class="d-block "><?php echo $nomUsuario; ?></a>
            </div>
        </div> -->
        <br>



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
        <br>
        <!-- Sidebar Menu -->
        <nav class="mt-2 ">
                    <ul class="nav nav-pills nav-child-indent nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->

                        <!-- INICIO -->
                        <li class="nav-item">
                            <a href="../sdo-personal/index.php" class="nav-link">
                                <i class="nav-icon fas fa-home"></i>
                                <p>Inicio</p>
                            </a>
                        </li>
                        <li class="nav-header"></li>
                        <li class="nav-header"></li>
                        <li class="nav-header"></li>
                        <!-- FIN INICIO -->

                        <?php

                        if ($_SESSION['departamentoPersonal'] == "Instrumentación") {
                        ?>
                            <!-- INICIO -->
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-laptop-house"></i>
                                    <p>
                                        Reportes
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="../sdo-personal/reportes-formulario.php" class="nav-link">
                                            <i class="nav-icon fas fa-file"></i>
                                            <p>Crear Reportes</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="../sdo-personal/reportes-listado.php" class="nav-link">
                                            <i class="nav-icon fas fa-book"></i>
                                            <p>Listado de Reportes</p>
                                        </a>
                                </ul>
                            </li>
                            <!-- FIN INICIO -->
                            <?php
                        } else {
                        }
                        if ($_SESSION['departamentoPersonal'] !== "Instrumentación") {


                            if ($_SESSION['departamentoPersonal'] == "Movilización") {

                            ?>

                                <li class="nav-header"></li>
                                <li class="nav-item">
                                    <a href="../sdo-personal/listado-viajes.php" class="nav-link">
                                        <i class="nav-icon fas fa-car"></i>
                                        <p>Viajes</p>
                                        <i class="right fas fa-angle-left"></i>
                                    </a>
                                </li>

                            <?php
                            }
                            ?>
                            <li class="nav-header"></li>
                            <!-- INICIO -->
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-laptop-house"></i>
                                    <p>
                                        Hojas Técnicas
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="../sdo-personal/ordenes-formulario-general.php" class="nav-link">
                                            <i class="fa fa-plus nav-icon"></i>
                                            <p>Agregar Hoja Técnica</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="../sdo-personal/ordenes-listado-general.php" class="nav-link">
                                            <i class="fa fa-list nav-icon"></i>
                                            <p>Listado de Hojas Técnicas</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- FIN INICIO -->
                        <?php
                        } else {
                        ?>
                            <li class="nav-header"></li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-laptop-house"></i>
                                    <p>
                                        Recepciones de Trabajo
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="../sdo-personal/ordenes-formulario.php" class="nav-link">
                                            <i class="fa fa-plus nav-icon"></i>
                                            <p>Agregar Recepci&oacute;n</p>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="../sdo-personal/ordenes-listado.php" class="nav-link">
                                            <i class="fa fa-list nav-icon"></i>
                                            <p>Listado de Recepciones de Trabajo</p>
                                        </a>
                                    </li>


                                </ul>
                            </li>
                            <li class="nav-header"></li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-file"></i>
                                    <p>
                                        Hojas Técnicas
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="../sdo-personal/ordenes-formulario-general.php" class="nav-link">
                                            <i class="fa fa-plus nav-icon"></i>
                                            <p>Agregar Hoja Técnica</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="../sdo-personal/ordenes-listado-general.php" class="nav-link">
                                            <i class="fa fa-list nav-icon"></i>
                                            <p>Listado de Hojas Técnicas</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-header"></li>
                        <?php
                        }
                        if ($_SESSION['departamentoPersonal'] == "Instrumentación") {
                        ?>
                            <!-- INICIO -->
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-list-ul"></i>
                                    <p>
                                        Sistemas
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="../sdo-personal/sistemas-listado.php" class="nav-link">
                                            <i class="nav-icon fas fa-book"></i>
                                            <p>Listado de Sistemas</p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="../sdo-personal/forzados-listado.php" class="nav-link">
                                            <i class="nav-icon fas fa-book"></i>
                                            <p>Listado de Equipos Forzados</p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="../sdo-personal/sistemas-observaciones.php" class="nav-link">
                                            <i class="nav-icon fas fa-list-ul"></i>
                                            <p>Observaciones</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-header"></li>
                            <!-- INICIO -->
                            <li class="nav-item">
                                <a href="bitacora-listado.php" class="nav-link">
                                    <i class="nav-icon fas fa-clock"></i>
                                    <p>
                                        Bitacora
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                            </li>
                            <li class="nav-header"></li>
                            <!-- LEVANTAMIENTO BMS -->
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-laptop"></i>
                                    <p>
                                        Levantamientos BMS
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="controladores-maestros-listado.php" class="nav-link">
                                            <i class="nav-icon fas fa-list"></i>
                                            <p>
                                                Servidor BMS
                                                <i class="right fas fa-angle-left"></i>
                                            </p>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="controladores-esclavos-listado.php" class="nav-link">
                                            <i class="nav-icon fas fa-list"></i>
                                            <p>
                                                Controladores BMS
                                                <i class="right fas fa-angle-left"></i>
                                            </p>
                                        </a>
                                    </li>
                                    <!-- INICIO -->
<!--                                    <li class="nav-item">-->
<!--                                        <a href="detalle_controladores_bms.php" class="nav-link">-->
<!--                                            <i class="nav-icon fas fa-list"></i>-->
<!--                                            <p>-->
<!--                                                Detalle Controladores BMS-->
<!--                                                <i class="right fas fa-angle-left"></i>-->
<!--                                            </p>-->
<!--                                        </a>-->
<!--                                    </li>-->

                                </ul>
                            </li>
                            <!-- LEVANTAMIENTO BMS -->

                            <li class="nav-header"></li>

                            <li class="nav-item">
                                <a href="salas_electricas_listado.php" class="nav-link">
                                    <i class="nav-icon fa fa-laptop"></i>
                                    <p>
                                        Salas Electricas y Comunicaci&oacute;n HBQP
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                            </li>

                            <li class="nav-header"></li>
                            <!-- CONTROL DE ACCESO Y PARKING -->
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-laptop"></i>
                                    <p>
                                        Control de Acceso y Parking
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="personal-controlAcceso.php" class="nav-link">
                                            <i class="nav-icon fas fa-list-ul"></i>
                                            <p>
                                                Listado de Control de Acceso
                                                <i class="right fas fa-angle-left"></i>
                                            </p>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="parking-acceso.php" class="nav-link">
                                            <i class="nav-icon fas fa-parking"></i>
                                            <p>
                                                Listado de Acceso a Parking
                                                <i class="right fas fa-angle-left"></i>
                                            </p>
                                        </a>
                                    </li>

                                </ul>
                            </li>
                            <!-- CONTROL DE ACCESO Y PARKING -->

                        <?php
                        }



                        if ($_SESSION['departamentoPersonal'] == "Equipos Industriales") {

                        ?>

                            <li class="nav-header"></li>
                            <!-- COMBUSTIBLE -->
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-gas-pump"></i>
                                    <p>
                                        Combustible
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">

                                    <li class="nav-item">
                                        <a href="combustible_mediciones.php" class="nav-link">
                                        <i class="nav-icon fas fa-gas-pump"></i>
                                            <p>
                                                Mediciones de Combustible
                                            </p>
                                        </a>
                                    </li>

                                </ul>
                            </li>
                            <!-- COMBUSTIBLE --> 

                        <?php
                        }
                        ?>
                        <li class="nav-header"></li>
                        <!-- SOLICITUDES -->
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-book"></i>
                                <p>
                                    Ticketera
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">

                                <li class="nav-item">
                                    <a href="solicitudes_recibidas.php" class="nav-link">
                                        <i class="nav-icon fas fa-file"></i>
                                        <p>
                                            Tickets Recibidos
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="solicitudes_finalizadas.php" class="nav-link">
                                        <i class="nav-icon fas fa-check"></i>
                                        <p>
                                            Tickets Finalizados
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="solicitudes_canceladas.php" class="nav-link">
                                        <i class="nav-icon fas fa-times-circle"></i>
                                        <p>
                                            Tickets Cancelados
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <!-- SOLICITUDES -->

                        <?php

                        if ($_SESSION['departamentoPersonal'] == "Instrumentación") {
                        ?>


                            <li class="nav-header"></li>
                            <!-- INICIO -->
                            <li class="nav-item">
                                <a href="llaves_entregadas.php" class="nav-link">
                                    <i class="nav-icon fas fa-key"></i>
                                    <p>
                                        Llaves Entregadas
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                            </li>




                            <li class="nav-header"></li>
                            <!-- INICIO -->
                            <li class="nav-item">
                                <a href="actas-listado.php" class="nav-link">
                                    <i class="nav-icon fas fa-book"></i>
                                    <p>
                                        Sistema de Actas
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                            </li>

                            <!-- FIN INICIO -->
                        <?php
                        } else {
                        }

                        ?>



                    </ul>

                </nav>
        <!-- /.sidebar-menu -->
    </div>
    <div class="sidebar-custom">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="../sdo-flujo/login_index.php?cerrarSesion=true" class="nav-link  text-bold">
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
