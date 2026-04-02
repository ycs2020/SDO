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


<nav class="main-header navbar  navbar-expand">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item ">
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

<aside class="main-sidebar sidebar-primary  elevation-4">
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">

            <div class="image">
                <img src="../dist/img/ss-vina-quillota.png" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="index.php" class="d-block text-dark"><?php echo $nomUsuario; ?></a>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column " data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                <!-- INICIO -->
                <li class="nav-item">
                    <a href="../sdo-administrador/index.php" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Inicio</p>
                    </a>
                </li>
                <li class="nav-header"></li>
                <li class="nav-header"></li>
                <!-- FIN INICIO -->

                <li class="nav-item">

                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-boxes"></i>

                        <p>
                            Equipamiento
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../sdo-administrador/administrar-equipos.php" class="nav-link">
                                <i class="fas fa-clipboard-list"></i>
                                <p>Listado de equipos</p>
                                <i class="right fas fa-angle-left"></i>
                            </a>
                        </li>
                        <liv class="nav-item">
                            <a href="../sdo-administrador/dashboard-equipos.php" class="nav-link">
                                <i class="fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                                <i class="right fas fa-angle-left"></i>
                            </a>
                        </liv>
                    </ul>
                </li>
                <li class="nav-header"></li>
                <li class="nav-item">
                    <a href="../sdo-administrador/reportes-administrar.php" class="nav-link">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Reportes</p>
                        <i class="right fas fa-angle-left"></i>
                    </a>
                </li>
                <li class="nav-header"></li>

                <li class="nav-item">
                    <a href="../sdo-administrador/ordenes-administrar.php" class="nav-link">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Recepciones de Trabajo</p>
                        <i class="right fas fa-angle-left"></i>
                    </a>
                </li>
                <li class="nav-header"></li>
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
                            <a href="../sdo-administrador/ordenes-formulario-general.php" class="nav-link">
                                <i class="fa fa-plus nav-icon"></i>
                                <p>Agregar Hoja Técnica</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../sdo-administrador/ordenes-administrar-general.php" class="nav-link">
                                <i class="fa fa-list nav-icon"></i>
                                <p>Listado de Hojas Técnicas</p>
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
                            <a href="sistemas-listado.php" class="nav-link">
                                <i class="nav-icon fas fa-book"></i>
                                <p>Listado de Sistemas</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="forzados-listado.php" class="nav-link">
                                <i class="nav-icon fas fa-book"></i>
                                <p>Listado de Equipos Forzados</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="sistemas-observaciones.php" class="nav-link">
                                <i class="nav-icon fas fa-list-ul"></i>
                                <p>Observaciones</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-header"></li>
                <!-- INICIO -->
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
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="controladores-esclavos-listado.php" class="nav-link">
                                <i class="nav-icon fas fa-list"></i>
                                <p>
                                    Controladores BMS
                                </p>
                            </a>
                        </li>
                        <!-- INICIO -->
<!--                        <li class="nav-item">-->
<!--                            <a href="detalle_controladores_bms.php" class="nav-link">-->
<!--                                <i class="nav-icon fas fa-list"></i>-->
<!--                                <p>-->
<!--                                    Detalle Controladores BMS-->
<!--                                </p>-->
<!--                            </a>-->
<!--                        </li>-->

                    </ul>
                </li>

                <li class="nav-header"></li>
                <!-- USUARIOS -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Listado de Usuarios
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="../sdo-administrador/jefes-administrar.php" class="nav-link">
                                <i class="nav-icon fas fa-list"></i>
                                <p>
                                    Listado de Encargados de Departamento
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="../sdo-administrador/profesionales-administrar.php" class="nav-link">
                                <i class="nav-icon fas fa-list"></i>
                                <p>
                                    Listado de Profesionales de Departamento
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="../sdo-administrador/personal-administrar.php" class="nav-link">
                                <i class="nav-icon fas fa-list"></i>
                                <p>
                                    Listado de Tecnicos de Departamento
                                </p>
                            </a>
                        </li>

                    </ul>
                </li>
                <!-- USUARIOS -->

                <li class="nav-header"></li>
                <!-- SOLICITUDES -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                            Tickets
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="../sdo-administrador/solicitudes_recibidas.php" class="nav-link">
                                <i class="nav-icon fas fa-file"></i>
                                <p>
                                    Tickets Recibidos
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="solicitudes_finalizadas.php" class="nav-link">
                                <i class="nav-icon fas fa-check"></i>
                                <p>
                                    Tickets Finalizados
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="../sdo-administrador/solicitudes_canceladas.php" class="nav-link">
                                <i class="nav-icon fas fa-times-circle"></i>
                                <p>
                                    Tickets Cancelados
                                </p>
                            </a>
                        </li>

                    </ul>
                </li>
                <!-- SOLICITUDES -->

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
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                            Control de Acceso y Parking
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="../sdo-administrador/control-acceso.php" class="nav-link">
                                <i class="fa fa-users nav-icon"></i>
                                <p>Listado de Control de Acceso</p>
                                <i class="right fas fa-angle-left"></i>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="../sdo-administrador/parking-acceso.php" class="nav-link">
                                <i class="fa fa-list nav-icon"></i>
                                <p>Listado de Parking</p>
                                <i class="right fas fa-angle-left"></i>
                            </a>
                        </li>

                    </ul>
                </li>


                <li class="nav-header"></li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                            Bodega Pa&ntilde;ol
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        <!-- <li class="nav-item">
                            <a href="../sdo-administrador/arriendo-retiro-administrar.php" class="nav-link">
                                <i class="fas fa-store"></i>
                                <p> Arriendo Herramientas y Entrega Materiales(Usuario)</p>
                            </a>
                        </li> -->

                        <li class="nav-item">
                            <a href="../sdo-administrador/panel-arriendo-retiro.php" class="nav-link">
                                <i class="fas fa-store"></i>
                                <p> Arriendo Herramientas y Entrega Materiales</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="../sdo-administrador/bodega-administrar.php" class="nav-link">
                                <i class="nav-icon fas fa-tools"></i>
                                <p>Ingreso Herramientas</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="../sdo-administrador/materiales-administrar.php" class="nav-link">
                                <i class="nav-icon fas fa-box-open"></i>
                                <p>Ingreso Materiales</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../sdo-administrador/genera-cod-herra.php" class="nav-link">
                                <i class="fas fa-barcode"></i>
                                <p>Generador Código de Barras</p>
                            </a>
                        </li>

                    </ul>

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
                            <a href="../sdo-administrador/soliCompra_pm_administracion.php" class="nav-link">
                                <i class="nav-icon fas fa-shopping-bag"></i>
                                <p>SC - Puesta en Marcha
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="../sdo-administrador/soliCompra_sdo_administracion.php" class="nav-link">
                                <i class="nav-icon fas fa-shopping-bag"></i>
                                <p>
                                    SC - SDO
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../sdo-administrador/soliCompra_sdo_resumen.php" class="nav-link">
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
                    <a href="../sdo-administrador/listado-viajes.php" class="nav-link">
                        <i class="nav-icon fas fa-car"></i>
                        <p>Viajes</p>
                        <i class="right fas fa-angle-left"></i>
                    </a>
                </li>

                <li class="nav-header"></li>
                <li class="nav-item">
                    <a href="../sdo-administrador/administrar-moviles.php" class="nav-link">
                        <i class="fas fa-ambulance"></i>
                        <p>Moviles - Movilización</p>
                        <i class="right fas fa-angle-left"></i>
                    </a>
                </li>

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
                            <a href="combustible_administracion.php" class="nav-link">
                                <i class="nav-icon fas fa-gas-pump"></i>
                                <p>
                                    Informe de Combustible
                                </p>
                            </a>
                        </li>

                    </ul>
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

            </ul>

        </nav>
        <!-- /.sidebar-menu -->
    </div>
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