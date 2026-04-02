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

<aside class="main-sidebar  sidebar-primary elevation-4">

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

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-child-indent nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->

                <!-- INICIO -->
                <li class="nav-item">
                    <a href="../sdo-admtec/index.php" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Inicio</p>
                    </a>
                </li>
                <!-- FIN INICIO -->

                <?php
                if ($nomDepartamento == "Instrumentación") {
                    ?>
                    <!-- HOJAS TECNICAS -->
                    <?php

                    $mostrarOcultar = deshabilitarOpciones();

                    if ($mostrarOcultar == 'Mostrar') {
                        ?>
                        <li class="nav-header"></li>
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
                                    <a href="../sdo-admtec/ordenes-formulario-general.php" class="nav-link">
                                        <i class="fa fa-plus nav-icon"></i>
                                        <p>Agregar Hoja Técnica</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="../sdo-admtec/ordenes-subir-firmas.php" class="nav-link">
                                        <i class="fa fa-list nav-icon"></i>
                                        <p>Listado de Hojas Técnicas</p>
                                    </a>
                                </li>


                            </ul>
                        </li>
                        <?php
                    }
                    ?>

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
                                <a href="../sdo-admtec/trabajos_general_listado.php" class="nav-link">
                                    <i class="nav-icon fas fa-plus"></i>
                                    <p>Listado de Ordenes de Trabajo</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../sdo-admtec/trabajos_general_listado_terminado.php" class="nav-link">
                                    <i class="nav-icon fas fa-clipboard"></i>
                                    <p>Listado de Ordenes de Trabajo Terminadas</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- FIN HOJAS TECNICAS -->

                    <!-- EQUIPAMIENTO -->
                    <li class="nav-header"></li>
                    <li class="nav-item">

                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-boxes"></i>

                            <p>
                                Equipamiento
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul>

                        </ul>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="../sdo-admtec/equipamiento_hbqp.php" class="nav-link">
                                    <i class="fas fa-clipboard-list"></i>
                                    <p>Listado de equipos</p>
                                    <i class="right fas fa-angle-left"></i>
                                </a>
                            </li>

                        </ul>

                    </li>

                    <li class="nav-header"></li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-file"></i>
                            <p>
                                Recepcion de Documentos
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="../sdo-admtec/recepcion_documento.php" class="nav-link">
                                    <i class="nav-icon fas fa-plus"></i>
                                    <p>Ingresar Documento</p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="../sdo-admtec/listar_recepcion_documento.php" class="nav-link">
                                    <i class="nav-icon fas fa-clipboard"></i>
                                    <p>Listar Documento</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- FIN EQUIPAMIENTO -->

                    <!-- CONTROL DE ACCESO Y PARKING -->
                    <li class="nav-header"></li>
                    <li class="nav-item">

                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-door-closed"></i>

                            <p>
                                Control de Acceso y Parking
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul>

                        </ul>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="../sdo-admtec/control-acceso.php" class="nav-link">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>
                                        Listado de Control de Acceso
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                            </li>

                        </ul>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="../sdo-admtec/parking-acceso.php" class="nav-link">
                                    <i class="nav-icon fas fa-parking"></i>
                                    <p>
                                        Listado de Acceso a Parking
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                            </li>

                        </ul>

                    </li>
                    <!-- FIN CONTROL DE ACCESO Y PARKING -->

                    <!-- ACTAS -->
                    <li class="nav-header"></li>
                    <li class="nav-item">

                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>

                            <p>
                                Sistema Actas
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul>

                        </ul>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="../sdo-admtec/actas-formulario.php" class="nav-link">
                                    <i class="nav-icon fas fa-file"></i>
                                    <p>
                                        Crear Acta de Activaci&oacute;n
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                            </li>

                        </ul>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="../sdo-admtec/actas-listado.php" class="nav-link">
                                    <i class="nav-icon fas fa-list"></i>
                                    <p>
                                        Listado de Actas
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                            </li>

                        </ul>

                    </li>
                    <!-- FIN ACTAS -->


                    <!-- REPORTES DE EVENTOS -->
                    <li class="nav-header"></li>
                    <li class="nav-item">
                        <a href="../sdo-admtec/reportes-listado.php" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                Reportes de Eventos Relevantes
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                    </li>
                    <!-- FIN REPORTES DE EVENTOS -->
                    <li class="nav-header"></li>
                    <li class="nav-item">
                        <a href="solicitudes_telefonicas.php" class="nav-link">
                            <i class="nav-icon fas fa-address-book"></i>
                            <p>
                                Solicitudes Telefonicas
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                    </li>
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
                    <!-- BITACORA -->
                    <li class="nav-header"></li>
                    <li class="nav-item">
                        <a href="../sdo-admtec/bitacora-listado.php" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                Bitacora
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                    </li>

                    <!-- FIN BITACORA -->

                    <?php
                }
                ?>

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
                        <?php
                        $idDepartamento = trim($_SESSION['idDepartamento']);

                        if ($idDepartamento == 1) {
                            ?>
                            <li class="nav-item">
                                <a href="usuarios_tickets.php" class="nav-link">
                                    <i class="nav-icon fas fa-list"></i>
                                    <p>
                                        Listado de Usuarios
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                            </li>
                            <?php

                        }
                        ?>


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

                <!-- INICIO -->

                <li class="nav-header"></li>
                <!-- INICIO -->
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
                            <a href="../sdo-admtec/soliCompra_pm_administracion.php" class="nav-link">
                                <i class="nav-icon fas fa-shopping-bag"></i>
                                <p>SC - Puesta en Marcha
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a href="../sdo-admtec/soliCompra_sdo_administracion.php" class="nav-link">
                                <i class="nav-icon fas fa-shopping-bag"></i>
                                <p>
                                    SC - SDO
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                        </li>

                    </ul>

                </li>
                <li class="nav-header"></li>
                <li class="nav-item">
                    <a href="../sdo-admtec/consumos_basicos.php" class="nav-link">
                        <i class="nav-icon fas fa-hospital"></i>
                        <p>
                            Consumos Basicos
                            <i class="right fas fa-angle-left"></i>
                        </p>
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
