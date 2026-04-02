<?php

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

        <?php
        if($_SERVER['REMOTE_ADDR'] == '10.69.250.151'){
            ?>
            <nav class="mt-2">
                <ul class="nav nav-pills nav-child-indent nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="../sdo-personal/index.php" class="nav-link text-bold">
                            <i class="nav-icon fas fa-home"></i>
                            <p>Inicio</p>
                        </a>
                    </li>


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
        <?php
        }else{
            ?>
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
                    <!-- INICIO -->
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-laptop-house"></i>
                            <p>
                                Listado de Equipos
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="../sdo-personal/equipamiento_hbqp.php" class="nav-link">
                                    <i class="nav-icon fas fa-file"></i>
                                    <p>Equipamientos</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <?php
                    if ($idDepartamento == 8) {
                        ?>
                        <li class="nav-header"></li>
                        <!-- INICIO -->
                        <li class="nav-item">
                            <a href="soliCompra_sdo_administracion.php" class="nav-link">
                                <i class="nav-icon fas fa-shopping-bag"></i>
                                <p>
                                    Solic. Compra - SDO
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                        </li>
                        <?php
                    }
                    ?>

                    <?php

                    if ($_SESSION['departamentoPersonal'] == "Instrumentación") {
                        ?>
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
                                    <a href="../sdo-personal/listar_recepcion_documento.php" class="nav-link">
                                        <i class="nav-icon fas fa-clipboard"></i>
                                        <p>Listar Documento</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- COMBUSTIBLE -->
                        <!-- FIN INICIO -->
                        <?php
                    }

                    if ($_SESSION['departamentoPersonal'] !== "Instrumentación") {


                        if ($_SESSION['departamentoPersonal'] == "Movilización") {

                            ?>

                            <li class="nav-header"></li>
                            <li class="nav-item">
                                <a href="../sdo-personal/viajes-1.php" class="nav-link">
                                    <i class="nav-icon fas fa-car"></i>
                                    <p>Viajes</p>
                                    <i class="right fas fa-angle-left"></i>
                                </a>
                            </li>


                            <li class="nav-item">
                                <a href="../sdo-personal/administrar-moviles.php" class="nav-link">
                                    <i class="fas fa-ambulance"></i>
                                    <p>Equipamiento - Moviles </p>
                                    <i class="right fas fa-angle-left"></i>
                                </a>
                            </li>


                            <?php
                        }
                        ?>
                        <?php
                        $mostrarOcultar = deshabilitarOpciones();

                        if ($mostrarOcultar == 'Mostrar') {
                            ?>
                            <li class="nav-item">

                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-laptop-house"></i>
                                <p>
                                    Hojas Técnicas
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <?php
                                if ($idDepartamento == 6) {
                                    ?>
                                    <li class="nav-item">
                                        <a href="../sdo-personal/ordenes-formulario-general-inge-clinica.php" class="nav-link">
                                            <i class="fa fa-plus nav-icon"></i>
                                            <p>Agregar Hoja Técnica</p>
                                        </a>
                                    </li>
                                    <?php
                                } else {
                                    ?>
                                    <li class="nav-item">
                                        <a href="../sdo-personal/ordenes-formulario-general.php" class="nav-link">
                                            <i class="fa fa-plus nav-icon"></i>
                                            <p>Agregar Hoja Técnica</p>
                                        </a>
                                    </li>
                                    <?php
                                }
                                ?>

                                <?php
                                if ($_SESSION['departamentoPersonal'] == "Equipos de Apoyo Clínico") {
                                    ?>
                                    <li class="nav-item">
                                        <a href="../sdo-personal/ordenes-listado-general1.php" class="nav-link">
                                            <i class="fa fa-list nav-icon"></i>
                                            <p>Listado de Hojas Técnicas</p>
                                        </a>
                                    </li>
                                    <?php
                                } else {
                                    ?>
                                    <li class="nav-item">
                                        <a href="../sdo-personal/ordenes-listado-general.php" class="nav-link">
                                            <i class="fa fa-list nav-icon"></i>
                                            <p>Listado de Hojas Técnicas</p>
                                        </a>
                                    </li>
                                    <?php
                                }
                                ?>


                            </ul>
                            <?php
                        }
                        ?>

                        <!-- INICIO -->


                        <!-- FIN INICIO -->
                        <?php
                    } else {


                        $mostrarOcultar = deshabilitarOpciones();

                        if ($mostrarOcultar == 'Mostrar') {
                            ?>
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
                                        <a href="../sdo-personal/ordenes-formulario-general2.php" class="nav-link">
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
                                    <a href="../sdo-personal/trabajos_general_listado.php" class="nav-link">
                                        <i class="nav-icon fas fa-plus"></i>
                                        <p>Listado de Ordenes de Trabajo</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="../sdo-personal/trabajos_general_listado_finalizados.php" class="nav-link">
                                        <i class="nav-icon fas fa-clipboard"></i>
                                        <p>Listado de Ordenes de Trabajo Terminadas</p>
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
                                <!--                                     INICIO -->
                                <li class="nav-item">
                                    <a href="detalle_controladores_bms.php" class="nav-link">
                                        <i class="nav-icon fas fa-list"></i>
                                        <p>
                                            Detalle Controladores BMS
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                </li>

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
                                <i class="nav-icon fas fa-book"></i>
                                <p>
                                    Control de Acceso y Parking
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">


                                <li class="nav-item">
                                    <a href="../sdo-personal/control-acceso.php" class="nav-link">
                                        <i class="nav-icon fas fa-upload"></i>
                                        <p>
                                            Administracion Funcionarios
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                </li>


                                <li class="nav-item">
                                    <a href="../sdo-personal/personal-controlAcceso.php" class="nav-link">
                                        <i class="nav-icon fas fa-list-ul"></i>
                                        <p>
                                            Funcionarios con Accesos
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="../sdo-personal/parking-acceso.php" class="nav-link">
                                        <i class="nav-icon fas fa-parking"></i>
                                        <p>
                                            Listado de Acceso a Parking
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                </li>


                                <li class="nav-item">
                                    <a href="../sdo-personal/puertas_ccaa_listado.php" class="nav-link">
                                        <i class="nav-icon fas fa-door-closed"></i>
                                        <p>Listado de Puertas en CCAA</p>
                                        <i class="right fas fa-angle-left"></i>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="../sdo-personal/ucas_listado.php" class="nav-link">
                                        <i class="nav-icon fas fa-door-closed"></i>
                                        <p>Listado de UCAS</p>
                                        <i class="right fas fa-angle-left"></i>
                                    </a>
                                </li>


                            </ul>
                        </li>
                        <!-- CONTROL DE ACCESO Y PARKING -->

                        <li class="nav-header"></li>
                        <!-- CONTROL DE ACCESO Y PARKING -->

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
                    <li class="nav-header"></li>
                    <li class="nav-item">
                        <a href="../sdo-personal/consumos_basicos.php" class="nav-link">
                            <i class="nav-icon fas fa-hospital"></i>
                            <p>
                                Consumos Basicos
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                    </li>


                </ul>

            </nav>
        <?php
        }
        ?>
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
