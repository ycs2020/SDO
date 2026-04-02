<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');

$fechaHoyPara18 = date('Y-m-d H:i:s');
$anioActualizado = date('Y');

$fecha_inicio_chile = new DateTime($anioActualizado . '-09-01 00:00:00');
$fecha_fin_chile = new DateTime($anioActualizado . '-09-21 23:59:59');

if (isset($_SESSION['nomUsuario']) && $_SESSION['userUsuario'] && $_SESSION['idUsuario'] && $_SESSION['rolUsuario']) {

    $nomUsuario = isset($_SESSION['nomUsuario']) ? $_SESSION['nomUsuario'] : '';

    if (empty(trim($nomUsuario))) {
        header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
        exit();
    }

    $idUsuario = trim($_SESSION['idUsuario']);

    $rolUsuario = trim($_SESSION['rolUsuario']);

    $rolUsuarioMinuss = mb_convert_case($rolUsuario, MB_CASE_LOWER, 'UTF-8');

    if ($rolUsuarioMinuss !== 'administrador') {
        echo '<script language="javascript">window.location.href="../sdo-flujo/login_index.php?tipoUsuarioNoPermitido=true";</script>';
    }


    $cargoUsuario = trim($_SESSION['cargoUsuario']);

    $emailUsuario = trim($_SESSION['emailUsuario']);


    $userUsuario = trim($_SESSION['userUsuario']);

    $nomDepartamento = 'Control Centralizado';

    $isAdmin = isAdmin($userUsuario);

    if ($isAdmin < 1) {
        header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    }

    if (
        empty(trim($cargoUsuario)) ||
        empty(trim($nomUsuario)) ||
        empty(trim($userUsuario)) ||
        empty(trim($idUsuario)) ||
        empty(trim($rolUsuario))
    ) {
        header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
        exit;
    }

    $userUsuarioMinus = mb_convert_case($userUsuario, MB_CASE_LOWER, 'UTF-8');
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

<link rel="stylesheet" href="../plugins/toastr/toastr.min.css">
<script src="../plugins/toastr/toastr.min.js"></script>


<nav class="main-header navbar navbar-expand  navbar-light navbar-dark ">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" id="toggle-sidebar" data-widget="pushmenu" href="#" role="button"><i id="sidebar-icon" class="fas fa-hand-point-left"></i></a>
        </li>
        <li class="nav-item">
            <a href="miperfil.php" data-toggle="tooltip" title="Click para ir a tu perfil">
                <img src="../sdo-administrador/img/GOB.png" alt="User Avatar" class="img-size-50 img-circle mr-3">
            </a>
        </li>
        <li class="nav-item mt-2 mr-2">



            <a href="miperfil.php" class="btn btn-sm bg-gradient-warning"> <b><i class="fas fa-user-cog"></i> Mi Perfil: <?php echo $nomUsuario ?></b></a>
        </li>
        <li class="nav-item mt-2">
            <a href="soporte-sdo.php" class="btn btn-sm bg-gradient-danger"> <b><i class="fas fa-headset"></i> Soporte</b></a>
        </li>
    </ul>
</nav>
<aside class="main-sidebar sidebar-primary   elevation-4">
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
        <br>
        <nav class="mt-2 ">
            <ul class="nav nav-pills nav-child-indent nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="../sdo-administrador/index.php" class="nav-link text-bold">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Inicio</p>
                    </a>
                </li>
                <li class="nav-header"></li>

                <?php
                // Estructura del menú con submenús
                $menu = [
                    [
                        'title' => 'Dashboard',
                        'icon' => 'fas fa-home',
                        'link' => '../sdo-administrador/dashboard_admin.php'
                    ],
                    [
                        'title' => 'Equipamiento HBQP',
                        'icon' => 'fas fa-laptop-house',
                        'link' => '../sdo-administrador/equipamiento_hbqp.php'
                    ],
                    [
                        'title' => 'Documentos',
                        'icon' => 'fas fa-file',
                        'link' => '#',
                        'subitems' => [
                            [
                                'title' => 'Generar Documento',
                                'icon' => 'fas fa-file',
                                'link' => '../sdo-administrador/recepcion_documento.php'
                            ],
                            [
                                'title' => 'Listado Documentos',
                                'icon' => 'fas fa-list',
                                'link' => '../sdo-administrador/listar_recepcion_documento.php'
                            ]
                        ]
                    ],
                    [
                        'title' => 'Reporte de Eventos',
                        'icon' => 'fas fa-book',
                        'link' => '../sdo-administrador/reportes-administrar.php'
                    ],
                    [
                        'title' => 'Hojas T&eacute;cnicas',
                        'icon' => 'fas fa-file',
                        'link' => '#',
                        'subitems' => [
                            [
                                'title' => 'Generar HT',
                                'icon' => 'fas fa-file',
                                'link' => '../sdo-administrador/ordenes-formulario-general.php'
                            ],
                            [
                                'title' => 'Listado HT',
                                'icon' => 'fas fa-list',
                                'link' => '../sdo-administrador/ordenes-subir-firmas.php'
                            ]
                        ]
                    ],
                    [
                        'title' => 'Ordenes de Trabajo (OT)',
                        'icon' => 'fas fa-file',
                        'link' => '#',
                        'subitems' => [
                            [
                                'title' => 'OT en Curso',
                                'icon' => 'fas fa-file',
                                'link' => '../sdo-administrador/trabajos_general_listado.php'
                            ],
                            [
                                'title' => 'OT Finalizadas',
                                'icon' => 'fas fa-list',
                                'link' => '../sdo-administrador/trabajos_general_listado_terminado.php'
                            ]
                        ]
                    ],
                    [
                        'title' => 'Bitacora',
                        'icon' => 'fas fa-book',
                        'link' => '../sdo-administrador/bitacora-listado.php'
                    ],
                    [
                        'title' => 'Planificaci&oacute;n',
                        'icon' => 'fas fa-book',
                        'link' => '../sdo-administrador/planificador_administracion.php'
                    ],
                    [
                        'title' => 'Sistema BMS',
                        'icon' => 'fas fa-book',
                        'link' => '#',
                        'subitems' => [
                            [
                                'title' => 'Listado de Sistemas',
                                'icon' => 'fas fa-cog',
                                'link' => '../sdo-administrador/sistemas-listado.php'
                            ],
                            [
                                'title' => 'Equipos Forzados',
                                'icon' => 'fas fa-cog',
                                'link' => '../sdo-administrador/forzados-listado.php'
                            ],
                            [
                                'title' => 'Observaciones',
                                'icon' => 'fas fa-book',
                                'link' => '../sdo-administrador/sistemas-observaciones.php'
                            ],
                        ]
                    ],
                    [
                        'title' => 'Levantamientos BMS',
                        'icon' => 'fas fa-book',
                        'link' => '#',
                        'subitems' => [
                            [
                                'title' => 'Servidor BMS',
                                'icon' => 'fas fa-cog',
                                'link' => '../sdo-administrador/controladores-maestros-listado.php'
                            ],
                            [
                                'title' => 'Controladores BMS',
                                'icon' => 'fas fa-cog',
                                'link' => '../sdo-administrador/controladores-esclavos-listado.php'
                            ],
                            [
                                'title' => 'Detalle Controladores BMS',
                                'icon' => 'fas fa-book',
                                'link' => '../sdo-administrador/detalle_controladores_bms.php'
                            ],
                        ]
                    ],
                    [
                        'title' => 'Usuarios Plataforma',
                        'icon' => 'fas fa-users',
                        'link' => '#',
                        'subitems' => [
                            [
                                'title' => 'Encargados de Departamento',
                                'icon' => 'fas fa-users',
                                'link' => '../sdo-administrador/jefes-administrar.php'
                            ],
                            [
                                'title' => 'Profesionales de Departamento',
                                'icon' => 'fas fa-users',
                                'link' => '../sdo-administrador/profesionales-administrar.php'
                            ],
                            [
                                'title' => 'Tecnicos de Departamento',
                                'icon' => 'fas fa-users',
                                'link' => '../sdo-administrador/personal-administrar.php'
                            ],
                        ]
                    ],
                    [
                        'title' => 'Tickets',
                        'icon' => 'fas fa-book',
                        'link' => '#',
                        'subitems' => [
                            [
                                'title' => 'Usuarios',
                                'icon' => 'fas fa-users',
                                'link' => '../sdo-administrador/usuarios_tickets.php'
                            ],
                            [
                                'title' => 'Tickets Recibidos',
                                'icon' => 'fas fa-file',
                                'link' => '../sdo-administrador/solicitudes_recibidas.php'
                            ],
                            [
                                'title' => 'Tickets Finalizados',
                                'icon' => 'fas fa-check',
                                'link' => '../sdo-administrador/solicitudes_finalizadas.php'
                            ],
                            [
                                'title' => 'Tickets Cancelados',
                                'icon' => 'fas fa-times-circle',
                                'link' => '../sdo-administrador/solicitudes_canceladas.php'
                            ],
                        ]
                    ],
                    [
                        'title' => 'Llaves Entregadas',
                        'icon' => 'fas fa-key',
                        'link' => '../sdo-administrador/llaves_entregadas.php'
                    ],
                    [
                        'title' => 'Formularios CCAA',
                        'icon' => 'fas fa-door-closed',
                        'link' => '#',
                        'subitems' => [
                            [
                                'title' => 'Ingresar Formulario',
                                'icon' => 'fas fa-door-closed',
                                'link' => '../sdo-administrador/formulariosCCAA_menuSeleccion.php'
                            ],
                            [
                                'title' => 'Formularios CCAA - Recibidos',
                                'icon' => 'fas fa-door-closed',
                                'link' => '../sdo-administrador/formulariosCCAA_listado.php'
                            ],
                            [
                                'title' => 'Formularios CCAA - Finalizados',
                                'icon' => 'fas fa-door-closed',
                                'link' => '../sdo-administrador/formulariosCCAA_listadoFinalizado.php'
                            ],
                            [
                                'title' => 'Formularios CCAA - Usuarios',
                                'icon' => 'fas fa-door-closed',
                                'link' => '../sdo-administrador/solicitudes_canceladas.php'
                            ],
                        ]
                    ],
                    [
                        'title' => 'Actas, CCAA y Parking',
                        'icon' => 'fas fa-book',
                        'link' => '#',
                        'subitems' => [
                            [
                                'title' => 'Actas',
                                'icon' => 'fas fa-book',
                                'link' => '../sdo-administrador/actas-listado.php'
                            ],
                            [
                                'title' => 'Listado CCAA',
                                'icon' => 'fas fa-book',
                                'link' => '../sdo-administrador/control-acceso.php'
                            ],
                            [
                                'title' => 'Listado Parking',
                                'icon' => 'fas fa-parking',
                                'link' => '../sdo-administrador/parking-acceso.php'
                            ],
                            [
                                'title' => 'UCAS',
                                'icon' => 'fas fa-door-closed',
                                'link' => '../sdo-administrador/ucas_listado.php'
                            ],
                            [
                                'title' => 'Puertas',
                                'icon' => 'fas fa-door-closed',
                                'link' => '../sdo-administrador/puertas_ccaa_listado.php'
                            ],
                        ]
                    ],
                    [
                        'title' => 'Solicitudes de Compra',
                        'icon' => 'fas fa-shopping-bag',
                        'link' => '#',
                        'subitems' => [
                            [
                                'title' => 'SC - Puesta en Marcha',
                                'icon' => 'fas fa-shopping-bag',
                                'link' => '../sdo-administrador/soliCompra_pm_administracion.php'
                            ],
                            [
                                'title' => 'SC - SDO',
                                'icon' => 'fas fa-shopping-bag',
                                'link' => '../sdo-administrador/soliCompra_sdo_administracion.php'
                            ],
                            [
                                'title' => 'SC - SDO - Control',
                                'icon' => 'fas fa-shopping-bag',
                                'link' => '../sdo-administrador/soliCompra_sdo_administracion_nuevo.php'
                            ],
                            [
                                'title' => 'SC - SDO - Resumen',
                                'icon' => 'fas fa-shopping-bag',
                                'link' => '../sdo-administrador/soliCompra_sdo_resumen.php'
                            ],
                        ]
                    ],
                    [
                        'title' => 'Movilizaci&oacute;n',
                        'icon' => 'fas fa-car',
                        'link' => '#',
                        'subitems' => [
                            [
                                'title' => 'Moviles',
                                'icon' => 'fas fa-car',
                                'link' => '../sdo-administrador/administrar-moviles.php'
                            ],
                            [
                                'title' => 'Viajes',
                                'icon' => 'fas fa-car',
                                'link' => '../sdo-administrador/viajes-1.php'
                            ],
                        ]
                    ],
                    [
                        'title' => 'Combustible',
                        'icon' => 'fas fa-gas-pump',
                        'link' => '../sdo-administrador/combustible_mediciones.php'
                    ],
                    [
                        'title' => 'Consumos Basicos',
                        'icon' => 'fas fa-hospital',
                        'link' => '../sdo-administrador/consumos_basicos.php'
                    ],
                ];


                // Ordenar el array $menu por el título ('title')
                usort($menu, function ($a, $b) {
                    return strcmp($a['title'], $b['title']);
                });
                ?>


                <!-- Generación del menú dinámico -->
                <?php foreach ($menu as $navItem): ?>
                    <li class="nav-header"></li>
                    <li class="nav-item">
                        <a href="<?= $navItem['link'] ?>" class="nav-link text-bold">
                            <i class="nav-icon <?= $navItem['icon'] ?>"></i>
                            <p>
                                <?= $navItem['title'] ?>
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <?php if (!empty($navItem['subitems'])): ?>
                            <ul class="nav nav-treeview">
                                <?php foreach ($navItem['subitems'] as $subItem): ?>
                                    <li class="nav-item">
                                        <a href="<?= $subItem['link'] ?>" class="nav-link text-bold">
                                            <i class="nav-icon <?= $subItem['icon'] ?>"></i>
                                            <p><?= $subItem['title'] ?></p>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>


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