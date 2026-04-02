<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('America/Santiago');
$horaActual = date('H');
$nomUsuario = $_SESSION['nomUsuario'];
$horaActual = date('H');
$idUsuario = $_SESSION['idUsuario'];


if ($horaActual >= '08' && $horaActual <= '11') {
    $mensajeBienvenida = '¡ Buenos Dias';
    $icono = '<i class="far fa-smile-beam fa-lg"></i> !';
} else if ($horaActual >= '12' && $horaActual <= '19') {
    $mensajeBienvenida = '¡ Buenas Tardes';
    $icono = '<i class="far fa-smile-beam fa-lg"></i> !';
} else if ($horaActual >= '20' && $horaActual <= '07') {
    $mensajeBienvenida = '¡ Buenas Noches';
    $icono = '<i class="far fa-moon"></i> !';
} else {
    $mensajeBienvenida = '¡ Bienvenido(a)';
    $icono = '<i class="far fa-smile-beam fa-lg"></i> !';
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Men&uacute; del Sistema</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">


    <script type="text/javascript">
        var keep_alive = false;
        $(document).bind("click keydown keyup mousemove", function () {
            keep_alive = true;
        });
        setInterval(function () {
            if (keep_alive) {
                pingServer();
                keep_alive = false;
            }
        }, 1200000);

        function pingServer() {
            $.ajax('/keepAlive');
        }
    </script>
    <style>
        .rotar {
            transform: rotate(180deg);
            transition: transform 0.7s ease;
        }

        .animacion_agrandar:hover {
            animation-name: bounce;
            animation-duration: 1s;
        }

        .cantidad1 {
            background-color: blue;
            color: white;
        }

        .cantidad3 {
            background-color: green;
            color: white;
        }

    </style>
    <link rel="stylesheet" href="../dist/css/cuadro-cantidad.css">
    <style>
        #countdown {
            font-size: 20px;
            text-align: center;
            border: 3px solid #fff; /* Borde blanco */
            padding: 20px;
            border-radius: 10px;
            background-color: #0f3057; /* Color de fondo azul medio */
            box-shadow: 0 0 10px rgba(2, 213, 35, 0.5); /* Sombra con opacidad */
            color: white;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/user-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2 animate__animated animate__fadeInDown">
                    <div class="col-sm-6">
                        <h3 style="font-family: 'Roboto', sans-serif; font-weight: 900;  color: #03cea4 ;"><?php echo $mensajeBienvenida . ", " . $nomUsuario . " " . $icono ?> </h3>
                    </div>

                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <?php
        $obtenerSolicitudesSegunNombre = obtenerSolicitudesSegunNombre($nomUsuario);
        $obtenerHistorialDocu = obtenerHistorialDocu($nomUsuario);
        $obtenerOTSegunNombre = obtenerOTSegunNombre($nomUsuario);
        if ($obtenerSolicitudesSegunNombre > 0) {
            ?>
            <script>
                Swal.fire(
                    'Existen nuevas Solicitudes Creadas.',
                    '',
                    'info'
                )
            </script>
            <?php
        }

        if ($obtenerOTSegunNombre > 0) {
            ?>
            <script>
                Swal.fire(
                    'Existen nuevas Ordenes de Trabajo.',
                    '',
                    'info'
                )
            </script>
            <?php
        }

        if (isset($_GET['loginExito'])) {
            ?>
            <script>
                Swal.fire({
                    title: 'Ingreso Exitoso a la Plataforma.',
                    confirmButtonText: 'Cerrar',
                    icon: 'success',
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    <?php
                    if ($obtenerSolicitudesSegunNombre > 0) {
                    ?>
                    Swal.fire({
                        title: 'Existen nuevas Solicitudes Creadas.',
                        confirmButtonText: 'Cerrar',
                        icon: 'info',
                    }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        <?php
                        if (isset($_GET['exitoCambioPassword'])) {
                        ?>

                        Swal.fire(
                            'Contrase&ntilde;a Modificada con Exito.',
                            '',
                            'success'
                        )

                        <?php
                        }

                        ?>
                    })

                    <?php
                    }elseif($obtenerHistorialDocu > 0){
                    ?>
                    Swal.fire(
                        'Existen nuevos documentos a ingresar.',
                        '',
                        'info'
                    )
                    <?php
                    }elseif($obtenerOTSegunNombre > 0){
                    ?>
                    Swal.fire(
                        'Existen nuevas Ordenes de Trabajo.',
                        '',
                        'info'
                    )
                    <?php
                    }else{
                    if (isset($_GET['exitoCambioPassword'])) {
                    ?>

                    Swal.fire(
                        'Contrase&ntilde;a Modificada con Exito.',
                        '',
                        'success'
                    )
                    <?php
                    }
                    }
                    ?>
                })
            </script>
            <?php
        }
        //            if ($userUsuario == 'lperez') {
        //                ?>
        <!--                <script>-->
        <!--                    Swal.fire(-->
        <!--                        '<img src="../dist/img/chill.jpg" alt="NICE">'-->
        <!--                    )-->
        <!--                </script>-->
        <!--                --><?php
        //            }else

        $fecha_actual = new DateTime();

        $fecha_inicio_navidad = new DateTime('2023-12-25 00:00:00');
        $fecha_fin_navidad = new DateTime('2023-12-25 23:59:59');

        // Compara si la fecha actual está dentro del rango
        if ($fecha_actual >= $fecha_inicio_navidad && $fecha_actual <= $fecha_fin_navidad) {
            if ($userUsuario == 'jsilva') {
                ?>
                <script>
                    Swal.fire(
                        '<img style="width: 100%" src="../dist/img/Navidad/pesebre.png" alt="Pesebre">'
                    )
                </script>
            <?php
            }else{

            ?>
                <script>
                    Swal.fire(
                        '<img style="width: 50%" src="../dist/img/Navidad/Arbol%20de%20Navidad.png" alt="Arbol de Navidad">'
                    )
                </script>
                <?php
            }
        }


        ?>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <!--   <div class="card-header">

                                    <table>
                                        <tr>
                                            <td>
                                                <h5><b>Bienvenido: </b><?php //echo $nomUsuario . ' ( ' . $cargoPersonal . ' de ' . getNomDepartamento($idDepartamento) . ' )'; ?></h5>

                                            </td>
                                        </tr>
                                        <?php
                            // if ($idSubDepartamento > 0) {
                            ?>
                                            <tr>
                                                <td>
                                                    <h5><b>SubDepartamento: </b><? php// echo getNomSubDepartamento($idSubDepartamento); ?></h5>
                                                </td>
                                            </tr>
                                        <?php
                            //  } else {
                            //     }
                            ?>
                                    </table>


                                </div> -->
                            <!-- /.card-header -->
                            <div style="font-size: 85%;" class="card-body">

                                <?php

                                ?>
                                <h5><b>Listado de Equipamiento <?php if ($userUsuario == 'ilanino') {
                                            echo '¡ AQUI ESTA !';
                                        } ?></b></h5>
                                <br>
                                <div class="row">

                                    <div class="col-lg-3 col-12">
                                        <!-- small box -->
                                        <a href="equipamiento_hbqp.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-desktop"></i><br>Visualizar Listado de equipos</a>

                                    </div>
                                </div>
                                <hr>
                                <?php

                                if ($_SESSION['departamentoPersonal'] == "Instrumentación") {
                                    ?>
                                    <!-- <h5><b>Listado de Equipamiento</b></h5>
                                     <br>
                                     <div class="row">

                                         <div class="col-lg-3 col-12">
                                                <a href="equipamiento_hbqp.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-desktop"></i><br>Visualizar Listado de equipos</a>

                                            </div>
                                        </div>
                                        <hr> -->
                                    <h5><b>Recepcion de Documentos</b></h5>
                                    <br>
                                    <div class="row">
                                        <!--
                                        <div class="col-lg-3 col-12">

                                            <a href="recepcion_documento.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-clipboard-list"></i><br>Agregar Documento</a>
                                        </div>
                                        -->
                                        <div class="col-lg-3 col-12">
                                            <?php
                                            $obtenerHistorialDocu = obtenerHistorialDocu($nomUsuario);
                                            if ($obtenerHistorialDocu > 0) {
                                                ?>
                                                <a href="listar_recepcion_documento.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="fas fa-clipboard-list"></i><br>Historial Documentos<span class="cantidad-badge cantidad1"><i class="fas fa-flag"><span id="cantidad1">0</span></i></span></a>
                                                <script>
                                                    document.getElementById("cantidad1").innerHTML = '&nbsp;<?php echo $obtenerHistorialDocu ?>';
                                                </script>
                                            <?php
                                            }else{
                                            ?>
                                                <a href="listar_recepcion_documento.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-clipboard-list"></i><br>Historial Documentos</a>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <h5><b>Revisi&oacute;n Formatos de Acceso</b></h5>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-3 col-12">
                                            <!-- small box -->
                                            <a href="formulariosCCAA_menuSeleccion.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-clipboard-list"></i><br>Selecci&oacute;n Formularios</a>

                                        </div>


                                        <div class="col-lg-3 col-12">
                                            <!-- small box -->
                                            <?php
                                            $cantidadRecibidos = contarFormulariosRecibidos();

                                            if ($cantidadRecibidos > 0) {
                                                ?>

                                                <a href="formulariosCCAA_listado.php" id="formCCAARecibidos" name="formCCAARecibidos" class="btn btn-block bg-gradient-secondary btn-lg" style="position: relative;"><i class="fas fa-clipboard-list"></i><br>Formularios CCAA - Recibidos<span class="cantidad-badge"><i class="fas fa-flag"><span id="cantidad">0</span></i></span></a>

                                                <script>
                                                    document.getElementById("cantidad").innerHTML = '&nbsp;<?php echo $cantidadRecibidos ?>';
                                                </script>
                                            <?php
                                            }else{
                                            ?>

                                                <a href="formulariosCCAA_listado.php" id="formCCAARecibidos" name="formCCAARecibidos" class="btn btn-block bg-gradient-secondary btn-lg" style="position: relative;"><i class="fas fa-clipboard-list"></i><br>Formularios CCAA - Recibidos</a>

                                                <?php
                                            }
                                            ?>

                                        </div>
                                        &nbsp;
                                        <div class="col-lg-3 col-12">
                                            <!-- small box -->
                                            <a href="formulariosCCAA_listadoFinalizado.php" class="btn btn-block bg-gradient-info btn-lg"><i class="fas fa-clipboard-list"></i><br>Formularios CCAA - Finalizados</a>

                                        </div>
                                    </div>
                                    <hr>
                                    <!-- RUTINA DIARIA -->
                                    <h5><b>Rutina Diaria</b></h5>
                                    <br>
                                    <div class="row">

                                        <div class="col-lg-3 col-12">

                                            <a href="bitacora-listado.php" class="btn btn-block bg-gradient-primary btn-lg">
                                                <i class="nav-icon fas fa-clock"></i><br>Visualizar Bitacora
                                            </a>

                                        </div>
                                        <?php
                                        $contarOT = contarOTTecnico($nomUsuario);
                                        if ($contarOT > 0) {
                                            ?>
                                            <div class="col-lg-3 col-12">
                                                <a href="trabajos_general_listado.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-list"></i><br>Visualizar Orden de Trabajo<span class="cantidad-badge cantidad3"><i class="fas fa-flag"><span id="cantidad3">0</span></i></span></a>
                                            </div>
                                            <script>
                                                document.getElementById("cantidad3").innerHTML = '&nbsp;<?php echo $contarOT ?>';
                                            </script>
                                        <?php
                                        }else{
                                        ?>
                                            <div class="col-lg-3 col-12">
                                                <a href="trabajos_general_listado.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-list"></i><br>Visualizar Orden de Trabajo</a>
                                            </div>
                                            <?php
                                        }
                                        ?>

                                        <div class="col-lg-3 col-12">
                                            <a href="trabajos_general_listado_finalizados.php" class="btn btn-block bg-gradient-info btn-lg"><i class="nav-icon fas fa-list"></i><br>Visualizar Orden de Trabajo Terminadas</a>
                                        </div>

                                    </div>
                                    <hr>
                                    <!-- RUTINA DIARIA -->

                                    <!-- REPORTES GENERALES -->
                                    <h5><b>Reportes Generales de Eventos Relevantes</b></h5>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-3 col-12">
                                            <!-- small box -->
                                            <a href="reportes-formulario.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-file"></i><br>Crear Reporte General</a>

                                        </div>
                                        &nbsp;
                                        <div class="col-lg-3 col-12">
                                            <!-- small box -->
                                            <a href="reportes-listado.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-book"></i><br>Historial Reportes Generales</a>

                                        </div>
                                    </div>


                                    <!-- REPORTES GENERALES -->

                                    <!-- RECEPCIONES DE TRABAJO -->
                                    <!--
                                    <h5><b>Recepciones de trabajo</b></h5>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-3 col-12">
                                            <a href="ordenes-formulario.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-file"></i><br>Crear Recepci&oacute;n de Trabajo</a>

                                        </div>
                                        &nbsp;
                                        <div class="col-lg-3 col-12">
                                            <a href="ordenes-listado.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-book"></i><br>Historial Recepciones de Trabajo</a>

                                        </div>
                                    </div>
                                    -->
                                    <hr>

                                    <!-- RECEPCIONES DE TRABAJO -->
                                    <?php

                                    $mostrarOcultar = deshabilitarOpciones();

                                    if ($mostrarOcultar == 'Mostrar') {
                                        ?>
                                        <!-- HOJAS TECNICAS -->
                                        <h5><b>Hojas Técnicas</b></h5>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <a href="ordenes-formulario-general.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-file"></i><br>Crear Hoja T&eacute;cnica</a>

                                            </div>
                                            &nbsp;
                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <a href="ordenes-listado-general.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-book"></i><br>Historial Hojas T&eacute;cnicas</a>

                                            </div>
                                        </div>
                                        <hr>
                                        <?php
                                    }
                                    ?>


                                    <!-- HOJAS TECNICAS -->
                                    <!-- SISTEMA BMS -->
                                    <h5><b>Observaciones de Sistema BMS</b></h5>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-3 col-12">
                                            <!-- small box -->
                                            <a href="sistemas-listado.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-bars"></i><br>Listado de Sistemas</a>

                                        </div>
                                        &nbsp;
                                        <div class="col-lg-3 col-12">
                                            <!-- small box -->
                                            <a href="forzados-listado.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-cogs"></i><br>Listado de Equipos Forzados</a>

                                        </div>
                                        &nbsp;
                                        <div class="col-lg-3 col-12">
                                            <!-- small box -->
                                            <a href="sistemas-observaciones.php" class="btn btn-block bg-gradient-info btn-lg"><i class="nav-icon fas fa-pen"></i><br>Observaciones de Equipos</a>

                                        </div>
                                    </div>
                                    <hr>

                                    <!-- SISTEMA BMS -->

                                    <!-- LEVANTAMIENTO SISTEMA BMS -->
                                    <h5><b>Levantamiento de Sistema BMS</b></h5>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-3 col-12">
                                            <!-- small box -->
                                            <a href="controladores-maestros-listado.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-list"></i><br>Controladores Maestros</a>

                                        </div>
                                        &nbsp;
                                        <div class="col-lg-3 col-12">
                                            <!-- small box -->
                                            <a href="controladores-esclavos-listado.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-list"></i><br>Controladores Esclavos</a>

                                        </div>
                                        &nbsp;
                                        <!--                                            <div class="col-lg-3 col-12">-->
                                        <!--                                                <a href="detalle_controladores_bms.php" class="btn btn-block bg-gradient-info btn-lg"><i class="nav-icon fas fa-list"></i><br>Detalle Controladores</a>-->
                                        <!---->
                                        <!--                                            </div>-->
                                    </div>
                                    <hr>

                                    <!-- LEVANTAMIENTO SISTEMA BMS -->

                                    <!-- CONTROL DE ACCESO -->
                                    <h5><b>Control de Acceso</b></h5>
                                    <br>
                                    <div class="row">

                                        <div class="col-lg-3 col-12">
                                            <!-- small box -->
                                            <a href="personal-controlAcceso.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-users"></i><br>Listado Control de Acceso</a>

                                        </div>
                                        &nbsp;
                                        <div class="col-lg-3 col-12">
                                            <!-- small box -->
                                            <a href="parking-acceso.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-parking"></i><br>Acceso a Parking</a>

                                        </div>
                                        &nbsp;
                                        <div class="col-lg-3 col-12">
                                            <!-- small box -->
                                            <a href="actas-listado.php" class="btn btn-block bg-gradient-info btn-lg"><i class="nav-icon fas fa-book"></i><br>Administraci&oacute;n de Actas</a>

                                        </div>
                                        &nbsp;
                                        <div class="col-lg-2 col-12">
                                            <!-- small box -->
                                            <a href="llaves_entregadas.php" class="btn btn-block bg-gradient-warning btn-lg"><i class="nav-icon fas fa-key"></i><br>Llaves Entregadas</a>

                                        </div>
                                    </div>

                                    <!-- CONTROL DE ACCESO -->

                                    <hr>


                                    <!-- SISTEMA SMARTPSS -->
                                    <h5><b>Informaci&oacute;n de Puertas</b></h5>
                                    <br>
                                    <div class="row">

                                        <div class="col-md-3 col-12">
                                            <!-- small box -->
                                            <a href="puertas_ccaa_listado.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-door-closed"></i><br>Listado Puertas</a>

                                        </div>
                                        &nbsp;
                                        <div class="col-md-3 col-12">
                                            <!-- small box -->
                                            <a href="ucas_listado.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-door-closed"></i><br>Listado de UCAS</a>

                                        </div>
                                    </div>

                                    <hr>

                                    <!-- SISTEMA SMARTPSS -->
                                    <h5><b>Combustible </b></h5>
                                    <br>
                                    <div class="row">

                                        <div class="col-lg-3 col-12">
                                            <!-- small box -->
                                            <a href="combustible_mediciones.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-gas-pump    "></i><br>Registrar Medici&oacute;n Combustible</a>

                                        </div>

                                    </div>


                                    <?php
                                } else {
                                    ?>


                                    <?php
                                    if ($idDepartamento == 8) {
                                        ?>
                                        <h5><b>Equipamiento - Moviles </b></h5>
                                        <br>
                                        <div class="row">

                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <a href="administrar-moviles.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-ambulance"></i><br>Administrar</a>

                                            </div>
                                        </div>
                                        <hr>


                                        <!-- VIAJES MOVILIZACION -->
                                        <h5><b>Viajes - Transporte </b></h5>
                                        <br>
                                        <div class="row">

                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <a href="viajes-1.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-car"></i><br>Viajes</a>

                                            </div>
                                        </div>
                                        <hr>


                                        <!-- VIAJES MOVILIZACION -->

                                        <?php
                                    }

                                    ?>

                                    <?php
                                    $lopuedever = 'NO';
                                    if ($lopuedever == 'NO') {
                                        ?>
                                        <h5><b>Orden de Trabajo</b></h5>
                                        <br>
                                        <div class="row">
                                            <?php
                                            $contarOT = contarOTTecnico($nomUsuario);
                                            if ($contarOT > 0) {
                                                ?>
                                                <div class="col-lg-3 col-12">
                                                    <a href="trabajos_general_listado.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-list"></i><br>Visualizar Orden de Trabajo<span class="cantidad-badge cantidad4"><i class="fas fa-flag"><span id="cantidad4">0</span></i></span></a>
                                                </div>
                                                <script>
                                                    document.getElementById("cantidad4").innerHTML = '&nbsp;<?php echo $contarOT ?>';
                                                </script>
                                            <?php
                                            }else{
                                            ?>
                                                <div class="col-lg-3 col-12">

                                                    <a href="trabajos_general_listado.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-list"></i><br>Visualizar Orden de Trabajo</a>
                                                </div>
                                                <?php
                                            }
                                            ?>

                                            <div class="col-lg-3 col-12">
                                                <a href="trabajos_general_listado_finalizados.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-list"></i><br>Visualizar Orden de Trabajo Terminadas</a>
                                            </div>
                                        </div>

                                        <hr>
                                        <?php
                                    }
                                    ?>
                                    <?php

                                    $mostrarOcultar = deshabilitarOpciones();

                                    if ($mostrarOcultar == 'Mostrar') {
                                        ?>
                                        <!-- HOJAS TECNICAS -->
                                        <h5><b>Hojas Técnicas</b></h5>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <a href="ordenes-formulario-general.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-file"></i><br>Crear Hoja T&eacute;cnica</a>

                                            </div>
                                            &nbsp; <?php
                                            if ($idDepartamento == '10') {
                                                ?>
                                                <div class="col-lg-3 col-12">
                                                    <!-- small box -->
                                                    <a href="ordenes-listado-general1.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-book"></i><br>Historial Hojas T&eacute;cnicas</a>

                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="col-lg-3 col-12">
                                                    <!-- small box -->
                                                    <a href="ordenes-listado-general.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-book"></i><br>Historial Hojas T&eacute;cnicas</a>

                                                </div>
                                                <?php
                                            }
                                            ?>

                                        </div>

                                        <?php
                                    }
                                    ?>


                                    <!-- HOJAS TECNICAS -->

                                    <?php
                                }


                                if ($nomUsuario == 'Técnico Pruebas') {
                                    ?>
                                    <hr>

                                    <h5><b>Reportes - Turno Electricidad</b></h5>
                                    <br>
                                    <div class="row">

                                        <div class="col-lg-3 col-12">
                                            <a href="electricidad_reportes_turno.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-file"></i><br>Reporte de Turno</a>

                                        </div>
                                    </div>


                                    <?php
                                }

                                ?>
                                <hr>
                                <h5><b>Solicitudes de Soporte</b></h5>
                                <br>
                                <div class="row">

                                    <div class="col-lg-3 col-12">
                                        <!-- small box -->
                                        <a href="solicitudes_recibidas.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-ticket-alt"></i><br>Tickets Recibidos</a>

                                    </div>
                                    &nbsp;
                                    <div class="col-lg-3 col-12">
                                        <!-- small box -->
                                        <a href="solicitudes_finalizadas.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-check"></i><br>Tickets Finalizados</a>

                                    </div>
                                    &nbsp;
                                    <div class="col-lg-3 col-12">
                                        <!-- small box -->
                                        <a href="solicitudes_canceladas.php" class="btn btn-block bg-gradient-info btn-lg"><i class="nav-icon fas fa-times"></i><br>Tickets Cancelados</a>

                                    </div>
                                </div>

                                <?php
                                if ($idDepartamento == 2 || $departamentoPersonal == 'Incendio y Seguridad') {
                                    ?>
                                    <hr>
                                    <h5><b>Combustible </b></h5>
                                    <br>
                                    <div class="row">

                                        <div class="col-lg-3 col-12">
                                            <!-- small box -->
                                            <a href="combustible_mediciones.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-gas-pump    "></i><br>Registrar Medici&oacute;n Combustible</a>

                                        </div>

                                    </div>


                                    <?php
                                }
                                ?>
                                <hr>
                                <h5><b>Consumos Basicos</b></h5>
                                <br>
                                <div class="row">

                                    <div class="col-lg-3 col-12">
                                        <!-- small box -->
                                        <a href="consumos_basicos.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-gas-pump"></i>&nbsp;&nbsp;<i class="nav-icon fas fa-tint"></i>&nbsp;&nbsp;<i class="nav-icon fas fa-bolt"></i><br>Consumos Basicos</a>

                                    </div>
                                </div>


                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->


                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <strong>Copyright &copy; 2022 - ∞. Derechos Reservados</strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<script src="js/dark-mode.js"></script>
<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../plugins/jszip/jszip.min.js"></script>
<script src="../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<script src="../dist/sweetalert-master/src/sweetalert.js"></script>
<link rel="stylesheet" type="text/css" href="../dist/sweetalert-master/src/sweetalert.css">
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Page specific script -->
<script>
    $(function () {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": false,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            }
        });
    });
</script>
</body>

</html>