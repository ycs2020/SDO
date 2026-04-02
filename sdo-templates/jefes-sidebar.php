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

    if (empty($cargoJefe)) {
        $cargoUsuario = 'Profesional';
    } else {
        $cargoUsuario = $cargoJefe;
    }

    $idDepartamento = $_SESSION['idDepartamento'];

    $nomDepartamento = getNomDepartamento($idDepartamento);

    $userUsuario = trim($_SESSION['userUsuario']);

    $isJefe = isJefe($userUsuario);

    if ($isJefe < 1) {
        header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    }

    $_SESSION['nomDepartamento'] = $nomDepartamento;

    $idSubDepartamento = $_SESSION['idSubDepartamento'];

    $emailJefe = $_SESSION['emailJefe'];

    $rolUsuarioMinuss = mb_convert_case($rolUsuario, MB_CASE_LOWER, 'UTF-8');

    if ($rolUsuarioMinuss !== 'jefe tecnico') {
        echo '<script language="javascript">window.location.href="../sdo-flujo/login_index.php?tipoUsuarioNoPermitido=true";</script>';
    }

    if (!empty($nomUsuario) && !empty($userUsuario) && !empty($idUsuario) && !empty($rolUsuario) && !empty($idDepartamento)) {
    } else {
        header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    }
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

?>

<style>


    body.custom-cursor {
        cursor: url('../sdo-flujo/sdo-imagenes/chile24.png'), auto;
    }
</style>

<script>
    // Definir las fechas de inicio y fin
    const fechaInicioChile = new Date('2024-09-01');  // Reemplaza con la fecha de inicio
    const fechaFinChile = new Date('2024-09-21');     // Reemplaza con la fecha de fin
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
            <a class="nav-link" id="toggle-sidebar" data-widget="pushmenu" href="#" role="button"><i id="sidebar-icon"
                                                                                                     class="fas fa-hand-point-left"></i></a>
        </li>

        <li class="nav-item">
            <a href="#" data-toggle="tooltip" title="Click para ir a tu perfil">
                <img src="../sdo-administrador/img/ssvq1.png" alt="User Avatar" class="img-size-50 img-circle mr-3">
            </a>
        </li>
        <li class="nav-item mt-2 mr-2">
            <a href="miperfil.php" class="btn btn-sm bg-gradient-warning"> <b><i class="fas fa-user-cog"></i> Mi
                    Perfil: <?php echo $nomUsuario ?></b></a>
        </li>
        <li class="nav-item mt-2">
        </li>

    </ul>

</nav>

<aside class="main-sidebar  elevation-4">

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="text-center">
            <p style=" font-family: 'Roboto', sans-serif; font-weight: 700; margin-left: 20px; color: #03cea4"
               class="align-items-center; text-center">Light Mode / Dark Mode </p>
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
            <ul class="nav nav-pills nav-child-indent nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->

                <!-- INICIO -->
                <li class="nav-item">
                    <a href="../sdo-jefestecnicos/index.php" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Inicio</p>
                    </a>
                </li>
                <li class="nav-header"></li>
                <li class="nav-header"></li>
                <li class="nav-header"></li>

                <!-- FIN INICIO -->
                <?php

                $departamentoJefe = getNomDepartamento($idDepartamento);

                if ($departamentoJefe == "Instrumentación") {
                    ?>
                    <!-- REPORTE -->

                    <li class="nav-item">
                        <a href="../sdo-jefestecnicos/reportes-listado.php" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p>Listado de Reportes</p>
                            <i class="right fas fa-angle-left"></i>
                        </a>
                    </li>
                    <!-- FIN REPORTE -->

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
                                <a href="../sdo-jefestecnicos/recepcion_documento.php" class="nav-link">
                                    <i class="nav-icon fas fa-plus"></i>
                                    <p>Ingresar Documento</p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="../sdo-jefestecnicos/listar_recepcion_documento.php" class="nav-link">
                                    <i class="nav-icon fas fa-clipboard"></i>
                                    <p>Listar Documento</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <?php
                }

                if ($departamentoJefe == "Incendio y Seguridad" && $userUsuario == 'jefepruebas') {
                    ?>
                    <!-- REPORTE -->
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
                                <a href="../sdo-jefestecnicos/reportes-incendio-formulario.php" class="nav-link">
                                    <i class="nav-icon fas fa-file"></i>
                                    <p>Crear Reportes</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../sdo-jefestecnicos/reportes-incendio-listado.php" class="nav-link">
                                    <i class="nav-icon fas fa-book"></i>
                                    <p>Listado de Reportes</p>
                                </a>
                        </ul>
                    </li>
                    <!-- FIN REPORTE -->


                    <?php
                }

                if ($departamentoJefe == "Movilización") {

                    ?>

                    <li class="nav-header"></li>
                    <li class="nav-item">
                        <a href="../sdo-jefestecnicos/viajes-1.php" class="nav-link">
                            <i class="nav-icon fas fa-car"></i>
                            <p>Viajes</p>
                            <i class="right fas fa-angle-left"></i>
                        </a>
                    </li>
                    <li class="nav-header"></li>
                    <li class="nav-item">
                        <a href="../sdo-jefestecnicos/administrar-moviles.php" class="nav-link">
                            <i class="fas fa-ambulance"></i>
                            <p>Equipamiento - Moviles </p>
                            <i class="right fas fa-angle-left"></i>
                        </a>
                    </li>


                    <?php
                }
                ?>

                <?php
                if ($nomUsuario == 'Gustavo Loira') {
                    ?>
                    <li class="nav-header"></li>
                    <li class="nav-item">
                        <a href="reportes-listado.php" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                Reportes Control y Electricos
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                    </li>
                    <?php
                }
                ?>

                <li class="nav-header"></li>
                <!-- REPORTE -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-laptop-house"></i>
                        <p>
                            Equipamiento HBQP
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../sdo-jefestecnicos/equipamiento_hbqp.php" class="nav-link">
                                <i class="nav-icon fas fa-file"></i>
                                <p>Listado de Sistemas</p>
                            </a>
                        </li>
                    </ul>
                </li>

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
                            <?php
                                if($idDepartamento == 1){
                                    ?>
                                    <li class="nav-item">
                                        <a href="../sdo-jefestecnicos/ordenes-formulario-general4.php" class="nav-link">
                                            <i class="fa fa-plus nav-icon"></i>
                                            <p>Agregar Hoja Técnica</p>
                                        </a>
                                    </li>
                                    <?php
                                }
                            ?>
                            <?php
                            if ($idDepartamento == 6) {
                                ?>
                                <li class="nav-item">
                                    <a href="../sdo-jefestecnicos/ordenes-formulario-general-inge-clinica.php" class="nav-link">
                                        <i class="fa fa-plus nav-icon"></i>
                                        <p>Agregar Hoja Técnica</p>
                                    </a>
                                </li>
                                <?php
                            } else if($idDepartamento != 1){
                                ?>
                                <li class="nav-item">
                                    <a href="../sdo-jefestecnicos/ordenes-formulario-general.php" class="nav-link">
                                        <i class="fa fa-plus nav-icon"></i>
                                        <p>Agregar Hoja Técnica</p>
                                    </a>
                                </li>
                                <?php
                            }
                            ?>


                            <li class="nav-item">
                                <a href="../sdo-jefestecnicos/ordenes-listado-general.php" class="nav-link">
                                    <i class="fa fa-list nav-icon"></i>
                                    <p>Listado de Hojas Técnicas</p>
                                </a>
                            </li>


                        </ul>
                    </li>
                    <?php
                }
                ?>
                <?php
                $lopuedever = 'NO';
                if ($lopuedever == 'NO' || $nomDepartamento == 'Instrumentación') {
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
                                <a href="../sdo-jefestecnicos/trabajos_general_listado.php" class="nav-link">
                                    <i class="nav-icon fas fa-plus"></i>
                                    <p>Listado de Ordenes de Trabajo</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../sdo-jefestecnicos/trabajos_general_listado_terminados.php" class="nav-link">
                                    <i class="nav-icon fas fa-clipboard"></i>
                                    <p>Listado de Ordenes de Trabajo Terminadas</p>
                                </a>
                            </li>
                            <?php
                            if ($departamentoJefe == "Instrumentación") {
                                ?>

                                <li class="nav-item">
                                    <a href="../sdo-jefestecnicos/calendario_mantencion_ucas.php" class="nav-link">
                                        <i class="nav-icon fas fa-clipboard"></i>
                                        <p>Calendario Mantención UCAS</p>
                                    </a>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                    </li>
                    <?php
                }
                ?>
                <li class="nav-header"></li>
                <!-- TECNICOS -->
                <li class="nav-item">
                    <a href="../sdo-jefestecnicos/tecnicos-listado.php" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Listado de Tecnicos</p>
                        <i class="right fas fa-angle-left"></i>
                    </a>
                </li>
                <!-- FIN TECNICOS -->

                <?php

                if ($departamentoJefe == "Instrumentación") {
                    ?>

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
                            <i class="nav-icon fas fa-laptop-house"></i>
                            <p>
                                Sistemas
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="../sdo-jefestecnicos/sistemas-listado.php" class="nav-link">
                                    <i class="fa fa-list nav-icon"></i>
                                    <p>Listado de Sistemas</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../sdo-jefestecnicos/forzados-listado.php" class="nav-link">
                                    <i class="fa fa-list nav-icon"></i>
                                    <p>Listado de Equipos Forzados</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="../sdo-jefestecnicos/sistemas-observaciones.php" class="nav-link">
                                    <i class="fa fa-list nav-icon"></i>
                                    <p>Observaciones</p>
                                </a>
                            </li>


                        </ul>
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
<!--                             INICIO-->
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
                        <?php
                        if ($nomUsuario == 'Yorgo Casimis Subiabre' || $nomUsuario == 'Isaac Perez') {
                            ?>
                            <li class="nav-item">
                                <a href="dashboard_ticketera.php" class="nav-link">
                                    <i class="nav-icon fas fa-chart-bar"></i>
                                    <p>
                                        Dashboard Ticketera
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                            </li>

                            <?php
                        }
                        ?>
                        <li class="nav-item">
                            <a href="../sdo-jefestecnicos/solicitudes_recibidas.php" class="nav-link">
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
                            <a href="../sdo-jefestecnicos/solicitudes_canceladas.php" class="nav-link">
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

                if ($departamentoJefe == "Instrumentación") {
                    ?>
                    <li class="nav-header"</li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-door-closed"></i>
                            <p>
                                Formularios Control de Acceso
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">


                            <li class="nav-item">
                                <a href="../sdo-jefestecnicos/formulariosCCAA_menuSeleccion.php" class="nav-link">
                                    <i class="nav-icon fas fa-door-closed"></i>
                                    <p>Ingreso de Formularios de Acceso</p>
                                    <i class="right fas fa-angle-left"></i>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="../sdo-jefestecnicos/formulariosCCAA_listado.php" class="nav-link">
                                    <i class="nav-icon fas fa-door-closed"></i>
                                    <p>Formularios CCAA - Recibidos</p>
                                    <i class="right fas fa-angle-left"></i>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="../sdo-jefestecnicos/formulariosCCAA_listadoFinalizado.php" class="nav-link">
                                    <i class="nav-icon fas fa-door-closed"></i>
                                    <p>Formularios CCAA - Finalizados</p>
                                    <i class="right fas fa-angle-left"></i>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="../sdo-jefestecnicos/formulariosCCAA_listadoUsuarios.php" class="nav-link">
                                    <i class="nav-icon fas fa-door-closed"></i>
                                    <p>Formularios CCAA - Usuarios</p>
                                    <i class="right fas fa-angle-left"></i>
                                </a>
                            </li>


                        </ul>
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
                                <a href="actas-listado.php" class="nav-link">
                                    <i class="nav-icon fas fa-book"></i>
                                    <p>
                                        Sistema de Actas
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../sdo-jefestecnicos/control-acceso.php" class="nav-link">
                                    <i class="nav-icon fas fa-book"></i>
                                    <p>Listado de Control de Acceso</p>
                                    <i class="right fas fa-angle-left"></i>
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


                            <li class="nav-item">
                                <a href="../sdo-jefestecnicos/puertas_ccaa_listado.php" class="nav-link">
                                    <i class="nav-icon fas fa-door-closed"></i>
                                    <p>Listado de Puertas en CCAA</p>
                                    <i class="right fas fa-angle-left"></i>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="../sdo-jefestecnicos/ucas_listado.php" class="nav-link">
                                    <i class="nav-icon fas fa-door-closed"></i>
                                    <p>Listado de UCAS</p>
                                    <i class="right fas fa-angle-left"></i>
                                </a>
                            </li>


                        </ul>
                    </li>
                    <!-- CONTROL DE ACCESO Y PARKING -->

                    <!-- INICIO -->


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
                                <a href="combustible_mediciones.php" class="nav-link">
                                    <i class="nav-icon fas fa-gas-pump"></i>
                                    <p>
                                        Mediciones de Combustible
                                    </p>
                                </a>
                            </li>

                        </ul>
                    </li>
                    <?php
                }
                ?>

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
                            <a href="../sdo-jefestecnicos/soliCompra_pm_administracion.php" class="nav-link">
                                <i class="nav-icon fas fa-shopping-bag"></i>
                                <p>SC - Puesta en Marcha
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a href="../sdo-jefestecnicos/soliCompra_sdo_administracion.php" class="nav-link">
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
                    <a href="../sdo-jefestecnicos/consumos_basicos.php" class="nav-link">
                        <i class="nav-icon fas fa-hospital"></i>
                        <p>
                            Consumos Basicos
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                </li>
                <?php

                if ($departamentoJefe == 'Logística') {
                    ?>
                    <li class="nav-header"></li>

                    <!-- CONTROL DE ACCESO Y PARKING -->
                    <li class="nav-item">
                        <a href="parking-acceso.php" class="nav-link">
                            <i class="nav-icon fas fa-parking"></i>
                            <p>
                                Listado de Acceso a Parking
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                    </li>
                    <!-- CONTROL DE ACCESO Y PARKING -->
                    <?php
                }

                /*
                if ($userUsuario == 'ycasimis') {
                    ?>
                    <li class="nav-header"></li>

                    <!-- CONTROL DE ACCESO Y PARKING -->
                    <li class="nav-item">
                        <a href="../sdo-jefestecnicos/consumos_basicos.php" class="nav-link">
                            <i class="nav-icon fas fa-hospital"></i>
                            <p>
                                Consumos Basicos
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                    </li>
                    <!-- CONTROL DE ACCESO Y PARKING -->
                    <?php
                }

*/
                ?>
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