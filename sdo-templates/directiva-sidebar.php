<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['nomUsuario']) && $_SESSION['userUsuario'] && $_SESSION['idUsuario'] && $_SESSION['rolUsuario'] && $_SESSION['cargoUsuario'] && $_SESSION['correoUsuario']) {

    $nomUsuario = trim($_SESSION['nomUsuario']);

    $userUsuario = trim($_SESSION['userUsuario']);

    $idUsuario = trim($_SESSION['idUsuario']);

    $rolUsuario = trim($_SESSION['rolUsuario']);

    $cargoUsuario = trim($_SESSION['cargoUsuario']);

    $correoUsuario = trim($_SESSION['correoUsuario']);

    $rolUsuario2 = '';
    if ($rolUsuario == 'directiva' || $rolUsuario == 'DIRECTIVA' || $rolUsuario == 'Directiva') {
        $rolUsuario2 = 'Direcci&oacute;n SDO';
    } else {
        $rolUsuario2 = $rolUsuario;
    }

    if ($nomUsuario == '' || $nomUsuario == ' ' || empty($nomUsuario) || $cargoUsuario == '' || $cargoUsuario == ' ' || empty($cargoUsuario) || $userUsuario == '' || $userUsuario == ' ' || empty($userUsuario) || $idUsuario == '' || $idUsuario == ' ' || empty($idUsuario)  || $rolUsuario == '' || $rolUsuario == ' ' || empty($rolUsuario) || empty($correoUsuario)) {
        header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
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

<!-- Preloader -->
<!--<div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../dist/img/controlCent-HBQP-120.png" alt="Control Centralizado - HBQP">
</div> -->

<nav class="main-header navbar navbar-expand  navbar-light navbar-dark ">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" id="toggle-sidebar" data-widget="pushmenu" href="#" role="button"><i id="sidebar-icon" class="fas fa-hand-point-left"></i></a>
        </li>


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


<aside class="main-sidebar  elevation-4">

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

        <hr>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-child-indent nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="../sdo-directiva/index.php" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Inicio</p>
                    </a>
                </li>

                <li class="nav-header"></li>

                <li class="nav-item">
                    <a href="../sdo-directiva/dashboard.php" class="nav-link">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-header"></li>

                <li class="nav-item">
                    <a href="../sdo-directiva/equipamiento_hbqp.php" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p>Listado de Equipos</p>
                    </a>
                </li>

                <li class="nav-header"></li>

                <li class="nav-item">
                    <a href="../sdo-directiva/reportes-visualizar.php" class="nav-link">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Reportes de Eventos Relevantes</p>
                    </a>
                </li>
                <!--
                <li class="nav-header"></li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file"></i>
                        <p>
                            Ordenes de Trabajo (OT)
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../sdo-directiva/trabajos_general_listado.php" class="nav-link">
                                <i class="nav-icon fas fa-plus"></i>
                                <p>Listado de Ordenes de Trabajo</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../sdo-directiva/trabajos_general_listado_terminado.php" class="nav-link">
                                <i class="nav-icon fas fa-clipboard"></i>
                                <p>Listado de Ordenes de Trabajo Terminadas</p>
                            </a>
                        </li>
                    </ul>
                </li>
                -->
                <!--
                <li class="nav-header"></li>
                <li class="nav-item">
                    <a href="../sdo-directiva/ordenes-visualizar.php" class="nav-link">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Recepciones de Trabajo</p>
                    </a>
                </li>
                -->
                <?php

                $mostrarOcultar = deshabilitarOpciones();

                if ($mostrarOcultar == 'Mostrar') {
                    ?>
                    <li class="nav-header"></li>
                    <li class="nav-item">
                        <a href="../sdo-directiva/ordenes-listado-general.php" class="nav-link">
                            <i class="fa fa-list nav-icon"></i>
                            <p>Listado de Hojas Técnicas</p>
                        </a>
                    </li>
                    <?php
                }
                ?>

                <li class="nav-header"></li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Funcionarios SDO
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../sdo-directiva/jefes-listado.php" class="nav-link">
                                <i class="nav-icon fas fa-user"></i>
                                <p>Encargados de Departamento</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../sdo-directiva/profesionales-listado.php" class="nav-link">
                                <i class="nav-icon fas fa-user"></i>
                                <p>Profesionales de Departamento</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../sdo-directiva/tecnicos-listado.php" class="nav-link">
                                <i class="nav-icon fas fa-user"></i>
                                <p>Tecnicos de Departamento</p>
                            </a>
                        </li>
                    </ul>
                </li>

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
                            <a href="../sdo-directiva/soliCompra_pm_administracion.php" class="nav-link">
                                <i class="nav-icon fas fa-shopping-bag"></i>
                                <p>
                                    SC - Puesta en Marcha
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="../sdo-directiva/soliCompra_sdo_administracion.php" class="nav-link">
                                <i class="nav-icon fas fa-shopping-bag"></i>
                                <p>
                                    SC - SDO
                                </p>
                            </a>
                        </li>

                    </ul>
                </li>
                <!--
                <li class="nav-header"></li>

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
                            <a href="combustible_administracion.php" class="nav-link">
                                <i class="nav-icon fas fa-gas-pump"></i>
                                <p>
                                    Informe de Combustible
                                </p>
                            </a>
                        </li>

                    </ul>

                </li>
                -->

                <li class="nav-header"></li>
                <li class="nav-item">
                    <a href="../sdo-directiva/viajes-1.php" class="nav-link">
                        <i class="nav-icon fas fa-car"></i>
                        <p>Viajes</p>
                        <i class="right fas fa-angle-left"></i>
                    </a>
                </li>
                <li class="nav-header"></li>
                <li class="nav-item">
                    <a href="../sdo-directiva/administrar-moviles.php" class="nav-link">
                        <i class="fas fa-ambulance"></i>
                        <p>Equipamiento - Moviles </p>
                        <i class="right fas fa-angle-left"></i>
                    </a>
                </li>

                    <li class="nav-header"></li>
                    <li class="nav-item">
                        <a href="../sdo-directiva/consumos_basicos.php" class="nav-link">
                            <i class="fas fa-hospital"></i>
                            <p>&nbsp;Consumos Básicos </p>
                            <i class="right fas fa-angle-left"></i>
                        </a>
                    </li>
                <li class="nav-header"></li>
                <li class="nav-item">
                    <a href="../sdo-directiva/combustible_mediciones.php" class="nav-link">
                        <i class="fas fa-gas-pump"></i>
                        <p>&nbsp;Combustible Mediciones/Técnicos</p>
                        <i class="right fas fa-angle-left"></i>
                    </a>
                </li>



                <!-- <li class="nav-header"></li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-laptop-house"></i>
                        <p>
                            Hoja Técnica
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../sdo-directiva/ordenes-formulario-general.php" class="nav-link">
                                <i class="fa fa-plus nav-icon"></i>
                                <p>Agregar Hoja Técnica</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="../sdo-directiva/ordenes-listado-general.php" class="nav-link">
                                <i class="fa fa-list nav-icon"></i>
                                <p>Listado de Hojas Técnicas</p>
                            </a>
                        </li>


                    </ul>
                </li> -->

                <li class="nav-header"></li>

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