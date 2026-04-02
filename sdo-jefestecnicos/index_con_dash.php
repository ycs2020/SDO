<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$idDepartamento = $_SESSION['idDepartamento'];
$nomUsuario = $_SESSION['nomUsuario'];
$horaActual = date('H');
$anioActual = date('Y');


$ipPermitida = '10.69.250.151';

$ipCliente = $_SERVER['REMOTE_ADDR'];

if ($horaActual >= '08' && $horaActual <= '11') {
    $mensajeBienvenida = '¡ Buenos Dias';
    $icono = '<i class="far fa-smile-beam fa-lg"></i> !';

} else if ($horaActual >= '12' && $horaActual <= '19') {
    $mensajeBienvenida = '¡ Buenas Tardes';
    $icono = '<i class="far fa-smile-beam fa-lg"></i> !';

} else if ($horaActual >= '20' && $horaActual <= '07') {
    $mensajeBienvenida = '¡ Buenas Noches';
    $icono = '<i class="far fa-smile-beam fa-lg"></i> !';

} else {
    $mensajeBienvenida = '¡ Bienvenido(a)';
    $icono = '<i class="far fa-smile-beam fa-lg"></i> !';

}


//resumen solicitudes de compra

// Define las categorías y sus respectivas unidades
$categorias = [
    'totalInst' => 'INSTRUMENTACIÓN',
    'totalElect' => 'Electricidad',
    'totalIndustrial' => 'Equipos Industriales',
    'totalClima' => 'Inst. Térmicas, Climatización y Refrigeración',
    'totalLogistica' => 'Logística',
    'totalInfraestructura' => 'Infraestructura',
    'totalMovilizacion' => 'Movilización',
    'totalOperacional' => 'Subdirección de Operaciones'
];

$datos = []; // Arreglo para almacenar los resultados

// Ejecuta las consultas en un bucle
foreach ($categorias as $key => $unidad) {
    $sql = "SELECT SUM(valor) as $key FROM solicitudes_sc_sdo_resumen WHERE unidadSoliCompra = '$unidad'";
    $res = mysqli_query($conexion, $sql);
    $row = $res->fetch_assoc();
    $datos[$key] = $row[$key] ?? 0; // Almacena el resultado o 0 si es nulo
}

// Función para convertir a moneda chilena
function formato_moneda($valor)
{
    return $valor == 0 ? '$ 0' : moneda_chilena($valor);
}

// Formatea los valores
$totalInst = formato_moneda($datos['totalInst']);
$totalElect = formato_moneda($datos['totalElect']);
$totalIndustrial = formato_moneda($datos['totalIndustrial']);
$totalClima = formato_moneda($datos['totalClima']);
$totalLogistica = formato_moneda($datos['totalLogistica']);
$totalInfraestructura = formato_moneda($datos['totalInfraestructura']);
$totalMovilizacion = formato_moneda($datos['totalMovilizacion']);
$totalOperacional = formato_moneda($datos['totalOperacional']);


$sqlContador = "SELECT COUNT(*) AS totalSolicitudes FROM  solicitudes_sc_sdo_resumen ";
$resContador = mysqli_query($conexion, $sqlContador);

while ($rowContador = $resContador->fetch_assoc()) {
    $datos = [];
    $datos['totalSolicitudes'] = $rowContador['totalSolicitudes'];
    $totalSolicitudes = $datos['totalSolicitudes'];
}


$sqlContador1 = "SELECT COUNT(*) AS totalAprobadas FROM  solicitudes_compra_sdo WHERE estadoSoliCompra = 'Autorizado por la S.D.A' || estadoSoliCompra =  'Derivado a Abastecimiento' ";
$resContador1 = mysqli_query($conexion, $sqlContador1);

while ($rowContador1 = $resContador1->fetch_assoc()) {
    $datos1 = [];
    $datos1['totalAprobadas'] = $rowContador1['totalAprobadas'];
    $totalAprobadas = $datos1['totalAprobadas'];
}


$sqlContador2 = "SELECT COUNT(*) AS totalRechazadas FROM  solicitudes_compra_sdo WHERE estadoSoliCompra = 'Rechazado por la S.D.A' ";
$resContador2 = mysqli_query($conexion, $sqlContador2);

while ($rowContador2 = $resContador2->fetch_assoc()) {
    $datos2 = [];
    $datos2['totalRechazadas'] = $rowContador2['totalRechazadas'];
    $totalRechazadas = $datos2['totalRechazadas'];
}


$sqlContador3 = "SELECT COUNT(*) AS totalAnuladas FROM  solicitudes_compra_sdo WHERE estadoSoliCompra = 'Solicitud Anulada' ";
$resContador3 = mysqli_query($conexion, $sqlContador3);

while ($rowContador3 = $resContador3->fetch_assoc()) {
    $datos3 = [];
    $datos3['totalAnuladas'] = $rowContador3['totalAnuladas'];
    $totalAnuladas = $datos3['totalAnuladas'];
}

$sqlContador4 = "SELECT COUNT(*) AS totalSecretaria FROM  solicitudes_compra_sdo WHERE estadoSoliCompra = 'SOLICITADA A SECRETARIA SDO' ";
$resContador4 = mysqli_query($conexion, $sqlContador4);

while ($rowContador4 = $resContador4->fetch_assoc()) {
    $datos4 = [];
    $datos4['totalSecretaria'] = $rowContador4['totalSecretaria'];
    $totalSecretaria = $datos4['totalSecretaria'];
}

$sqlTotalRevisados = "SELECT COUNT(*) AS totalSecretaria FROM  solicitudes_compra_sdo WHERE estadoSoliCompra = 'Revisada por Secretaría SDO' ";
$resTotalRevisados = mysqli_query($conexion, $sqlTotalRevisados);

while ($rowRevisados = $resTotalRevisados->fetch_assoc()) {
    $datos4 = [];
    $datos4['totalSecretaria'] = $rowRevisados['totalSecretaria'];
    $totalRevisados = $datos4['totalSecretaria'];
}


$sqlContador5 = "SELECT COUNT(*) AS totalSDA FROM  solicitudes_compra_sdo WHERE estadoSoliCompra = 'ENTREGADO A LA S.D.A' ||  estadoSoliCompra = 'Autorizado por la S.D.A' ";
$resContador5 = mysqli_query($conexion, $sqlContador5);

while ($rowContador5 = $resContador5->fetch_assoc()) {
    $datos5 = [];
    $datos5['totalSDA'] = $rowContador5['totalSDA'];
    $totalSDA = $datos5['totalSDA'];
}


$sqlContador6 = "SELECT COUNT(*) AS totalAbastecimiento FROM  solicitudes_compra_sdo WHERE estadoSoliCompra = 'DERIVADO A ABASTECIMIENTO' ";
$resContador6 = mysqli_query($conexion, $sqlContador6);

while ($rowContador6 = $resContador6->fetch_assoc()) {
    $datos6 = [];
    $datos6['totalAbastecimiento'] = $rowContador6['totalAbastecimiento'];
    $totalAbastecimiento = $datos6['totalAbastecimiento'];
}


$sqlContador7 = "SELECT COUNT(*) AS totalPanol FROM  solicitudes_compra_sdo WHERE estadoSoliCompra = 'Recibido por pañol' ";
$resContador7 = mysqli_query($conexion, $sqlContador7);

while ($rowContador7 = $resContador7->fetch_assoc()) {
    $datos7 = [];
    $datos7['totalPanol'] = $rowContador7['totalPanol'];
    $totalPanol = $datos7['totalPanol'];
}


$sqlContador8 = "SELECT COUNT(*) AS totalCompletadas FROM  solicitudes_compra_sdo WHERE estadoSoliCompra = 'Solicitud Completada' ";
$resContador8 = mysqli_query($conexion, $sqlContador8);

while ($rowContador8 = $resContador8->fetch_assoc()) {
    $datos8 = [];
    $datos8['totalCompletadas'] = $rowContador8['totalCompletadas'];
    $totalCompletadas = $datos8['totalCompletadas'];
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

    <!-- Theme style -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">
    <link rel="stylesheet" href="../dist/css/cuadro-cantidad.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        .cantidad4 {
            background-color: green;
            color: white;
        }

        .cantidad5 {
            background-color: green;
            color: white;
        }

    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">


<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/jefes-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2 animate__animated animate__fadeInDown">
                    <div class="col-12">
                        <div class="card card-widget widget-user">
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div class="widget-user-header bg-primary">
                                <h3 class="widget-user-username"><?php echo $nomUsuario; ?></h3>
                                <h3 class="widget-user-username"><?php echo $cargoUsuario; ?></h3>
                            </div>
                            <div class="widget-user-image">
                                <img class="img-circle elevation-2" src="../dist/img/simboloAdmin.jpg" alt="User Avatar"/>
                            </div>
                            <div class="card-footer">
                                <div class="row justify-content-center ">
                                    <div class="col-sm-3 mb-3">
                                        <a href="ordenes-listado-general.php" class="btn btn-block bg-gradient-info">
                                            <div class="description-block">
                                                <h5 class="description-header">
                                                    <?php
                                                    $cantHojasTec = "SELECT COUNT(*) as TOTAL FROM ordenes_trabajos_general WHERE YEAR(fechaOrden) = " . $anioActual . " AND idDepartamento =" . $idDepartamento;
                                                    $resCantHojasTec = mysqli_query($conexion, $cantHojasTec);
                                                    while ($rowCantHojasTec = $resCantHojasTec->fetch_assoc()) {
                                                        $datos = [];
                                                        $datos['TOTAL'] = $rowCantHojasTec['TOTAL'];
                                                        $TOTAL = $datos['TOTAL'];
                                                    }

                                                    ?>
                                                    <?php echo $TOTAL; ?>
                                                </h5>
                                                <span class="description-text">Hojas Tecnicas</span>
                                            </div>
                                            <!-- /.description-block -->
                                        </a>
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-3 mb-3">
                                        <a href="soliCompra_sdo_administracion.php" class="btn btn-block bg-gradient-yellow">
                                            <div class="description-block">
                                                <h5 class="description-header">

                                                    <?php
                                                    $cantHojasTec = "SELECT COUNT(*) as TOTAL FROM solicitudes_compra_sdo where YEAR(fechaSoliCompra) = " . $anioActual . " AND unidadSoliCompra = '" . $nomDepartamento . "'";
                                                    $resCantHojasTec = mysqli_query($conexion, $cantHojasTec);
                                                    while ($rowCantHojasTec = $resCantHojasTec->fetch_assoc()) {
                                                        $datos = [];
                                                        $datos['TOTAL'] = $rowCantHojasTec['TOTAL'];
                                                        $TOTAL = $datos['TOTAL'];
                                                    }

                                                    ?>
                                                    <?php echo $TOTAL ?>
                                                </h5>
                                                <span class="description-text">Solicitudes de Compra</span>
                                            </div>
                                            <!-- /.description-block -->
                                        </a>
                                    </div>
                                    <div class="col-sm-3 mb-3">
                                        <a href="equipamiento_hbqp.php" class="btn btn-block bg-gradient-purple">
                                            <div class="description-block">
                                                <h5 class="description-header">
                                                    <?php
                                                    $sqlCategorias = "SELECT * FROM categorias_equipos WHERE idDepartamento = $idDepartamento";
                                                    $resCategorias = mysqli_query($conexion, $sqlCategorias);

                                                    $cantidadEquipos = 0;

                                                    while ($row = $resCategorias->fetch_assoc()) {
                                                        $datos = [];
                                                        $datos['idCategoria'] = $row['idCategoria'];
                                                        $idCategoria = $datos['idCategoria'];

                                                        $sqlEquipos = "SELECT COUNT(*) AS total FROM equipamiento_hbqp WHERE idCategoria = $idCategoria";
                                                        $resEquipos = mysqli_query($conexion, $sqlEquipos);

                                                        while ($rowEquipos = $resEquipos->fetch_assoc()) {
                                                            $datosEquipos = [];
                                                            $datosEquipos['total'] = $rowEquipos['total'];
                                                            $total = $datosEquipos['total'];
                                                        }

                                                        $cantidadEquipos = $cantidadEquipos + $total;

                                                    }
                                                    ?>
                                                    <?php echo $cantidadEquipos ?>
                                                </h5>
                                                <span class="description-text">Equipamiento</span>
                                            </div>
                                        </a>
                                        <!-- /.description-block -->
                                    </div>

                                    <div class="col-sm-3 mb-3">
                                        <a href="avisos_relevantes_administracion.php" class="btn btn-block bg-gradient-danger">
                                            <div class="description-block">
                                                <h5 class="description-header">
                                                    <?php
                                                    $sqlCountAvisos = "SELECT COUNT(*) AS total FROM avisos_destinatarios WHERE nomDestinatario LIKE '$nomUsuario'";
                                                    $resCountAvisos = mysqli_query($conexion, $sqlCountAvisos);

                                                    $rowCountAvisos = mysqli_fetch_assoc($resCountAvisos);
                                                    $totalCountAvisos = $rowCountAvisos['total'];
                                                    ?>
                                                    <?php echo $totalCountAvisos ?>
                                                </h5>
                                                <span class="description-text">Avisos Relevantes</span>
                                            </div>
                                        </a>
                                        <!-- /.description-block -->
                                    </div>
                                    <!-- /.col -->
                                    <?php

                                    if ($nomDepartamento == 'Instrumentación') {
                                        ?>
                                        <div class="col-sm-3 mb-3">
                                            <a href="reportes-listado.php" class="btn btn-block bg-gradient-success">
                                                <div class="description-block">
                                                    <h5 class="description-header">
                                                        <?php
                                                        $cantHojasTec = "SELECT COUNT(*) as TOTAL FROM reportes_generales";
                                                        $resCantHojasTec = mysqli_query($conexion, $cantHojasTec);
                                                        while ($rowCantHojasTec = $resCantHojasTec->fetch_assoc()) {
                                                            $datos = [];
                                                            $datos['TOTAL'] = $rowCantHojasTec['TOTAL'];
                                                            $TOTAL = $datos['TOTAL'];
                                                        }

                                                        echo $TOTAL;
                                                        ?>
                                                    </h5>
                                                    <span class="description-text">Reportes Turno</span>
                                                </div>
                                                <!-- /.description-block -->
                                            </a>
                                        </div>

                                        <?php
                                    }

                                    ?>

                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <?php
        $obtenerHistorialDocu = obtenerHistorialDocu($nomUsuario);
        $obtenerOTSegunNombre = obtenerOTSegunNombre($nomUsuario);
        $contarfaltavisado = contarfaltavisado($nomUsuario);
        $obtenerSolicitudesSegunNombre = obtenerSolicitudesSegunNombre($nomUsuario);

        if ($obtenerSolicitudesSegunNombre > 0) {
            ?>
            <script>
                Swal.fire(
                    'Ud.Tiene Solicitudes Asignadas',
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
        if ($idDepartamento == 1) {
            if ($contarfaltavisado > 0) {
                ?>
                <script>
                    Swal.fire(
                        'Existen Ordenes de Trabajo sin visar, en total: <?php echo $contarfaltavisado?>.',
                        '',
                        'info'
                    )
                </script>
                <?php
            }
        }
        if ($idDepartamento == 1) {
            if (isset($_GET['loginExito'])) {
                ?>
                <script>
                    Swal.fire({
                        title: 'Ingreso Exitoso a la Plataforma.',
                        confirmButtonText: 'Cerrar',
                        icon: 'success',
                    }).then(async (result) => {
                        <?php
                        if ($obtenerOTSegunNombre > 0) {
                        ?>
                        await Swal.fire(
                            'Existen nuevas Órdenes de Trabajo.',
                            '',
                            'info'
                        );
                        <?php
                        }
                        ?>
                        <?php
                        if ($contarfaltavisado > 0) {
                        ?>
                        await Swal.fire(
                            'Existen <?php echo $contarfaltavisado ?> Órdenes de Trabajo sin visar.',
                            '',
                            'info'
                        );
                        <?php
                        }
                        if($obtenerSolicitudesSegunNombre > 0){
                        ?>
                        await Swal.fire(
                            'Ud.Tiene Solicitudes Asignadas',
                            '',
                            'info'
                        );
                        <?php
                        }
                        ?>
                    });
                </script>
                <?php
            }
        }


        ?>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <div class="row mb-2">
                    <div class="col-sm-12">

                        <div class="row" style="font-size: 15px;">
                            <div class="col-12">
                                <!-- Custom Tabs -->
                                <div class="card">
                                    <div class="card-header d-flex p-0" style="background-color: #03cea4;">
                                        <h3 class="card-title p-3">
                                            <b>Accesos Rápidos</b>
                                        </h3>
                                        <ul class="nav nav-pills ml-auto p-2">
                                            <li class="nav-item">
                                                <a class="nav-link active " href="#tab_4" data-toggle="tab">
                                                    <b>Menu</b>
                                                </a>
                                            </li>
                                            <?php

                                            $mostrarOcultar = deshabilitarOpciones();

                                            if ($mostrarOcultar == 'Mostrar') {
                                                ?>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#tab_1" data-toggle="tab">
                                                        <b>Hojas Técnicas</b>
                                                    </a>
                                                </li>
                                                <?php
                                            }
                                            ?>

                                            <li class="nav-item">
                                                <a class="nav-link" href="#tab_2" data-toggle="tab"><b>Solicitudes de Compra</b></a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#tab_5" data-toggle="tab"><b>Ordenes de Trabajo</b></a>
                                            </li>
                                            <?php
                                            if ($nomUsuario == 'Yorgo Casimis Subiabre') {
                                                ?>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#tab_6" data-toggle="tab"><b>Pulsador de Cama</b></a>
                                                </li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="tab_4">
                                                <?php

                                                if ($nomUsuario == 'Yorgo Casimis Subiabre' && $ipCliente == '10.69.250.151') {
                                                    ?>
                                                    <h5>
                                                        <b>Trabajos Informativo</b>
                                                    </h5>
                                                    <br>
                                                    <div class="row">

                                                        <div class="col-lg-2 col-12">
                                                            <!-- small box -->

                                                            <!-- Botón para abrir el modal -->
                                                            <button type="button" class="btn btn-block bg-gradient-primary btn-lg" data-toggle="modal" data-target="#modalClave">
                                                                <i class="fas fa-file-pdf"></i><br>Acceder a trabajos
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <hr>

                                                    <!-- Modal Bootstrap -->
                                                    <div class="modal fade" id="modalClave" tabindex="-1" aria-labelledby="modalClaveLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-primary">
                                                                    <h5 class="modal-title" id="modalClaveLabel">Ingrese la contraseña</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div id="mensaje-error" class="text-danger mb-2" style="display:none;">Contraseña incorrecta</div>
                                                                    <input type="password" id="input-clave" class="form-control" placeholder="Contraseña">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-primary" id="btn-validar-clave">Acceder</button>
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <script>
                                                        const userUsuario = "<?php echo addslashes($userUsuario); ?>";

                                                        document.getElementById('btn-validar-clave').addEventListener('click', function() {
                                                            const claveIngresada = document.getElementById('input-clave').value;
                                                            console.log(claveIngresada);

                                                            fetch('/sdo-funciones/trabajos_validar_clave.php', {
                                                                method: 'POST',
                                                                headers: {
                                                                    'Content-Type': 'application/x-www-form-urlencoded'
                                                                },
                                                                body: 'usuario=' + encodeURIComponent(userUsuario) +
                                                                    '&clave=' + encodeURIComponent(claveIngresada)
                                                            })
                                                                .then(response => response.json())
                                                                .then(data => {
                                                                    if (data.status === 'ok') {
                                                                        // Redirigir a la página protegida
                                                                        window.location.href = 'trabajos_justificacion.php';
                                                                    } else {
                                                                        document.getElementById('mensaje-error').style.display = 'block';
                                                                    }
                                                                })
                                                                .catch(error => {
                                                                    console.error('Error:', error);
                                                                });
                                                        });
                                                    </script>
                                                    <?php
                                                }

                                                if ($nomDepartamento == 'Instrumentación') {
                                                    ?>
                                                    <h5>
                                                        <b>Asistencia</b>
                                                    </h5>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-lg-2 col-12">
                                                            <button class="btn btn-block bg-gradient-primary btn-lg" data-toggle="modal" data-target="#modalAsistencia">
                                                                <i class="fas fa-calendar-check"></i>
                                                                <br>
                                                                Asistencia
                                                            </button>
                                                        </div>

                                                        <div class="modal fade" id="modalAsistencia" tabindex="-1" role="dialog" aria-labelledby="modalAsistenciaLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-primary">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Registrar Asistencia</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <form action="../sdo-funciones/asistencia_registrar.php" method="POST">

                                                                        <div class="modal-body">
                                                                            <div class="form-group">
                                                                                <label for="nombreFuncionario" style="font-size:15px">Nombre Funcionario:<code>*</code></label>
                                                                                <input type="text" class="form-control" required id="nombreFuncionario" name="nombreFuncionario" placeholder="Nombre de Funcionario" value="<?php echo $nomUsuario; ?>" readonly="readonly">
                                                                            </div>
                                                                            <?php
                                                                            $now = date("Y-m-d");
                                                                            ?>
                                                                            <div class="form-group">
                                                                                <label for="fechaAsistencia" style="font-size:15px">Fecha Asistencia:<code>*</code></label>
                                                                                <input type="date" class="form-control" required id="fechaAsistencia" name="fechaAsistencia" value="<?php echo $now; ?>" readonly="readonly">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="turnoFuncionario" style="font-size:15px">Turno Funcionario:<code>*</code></label>
                                                                                <select id="turnoFuncionario" name="turnoFuncionario" class="form-control" required>
                                                                                    <option value="">Seleccione...</option>
                                                                                    <option value="Turno Dia">Turno Día</option>
                                                                                    <option value="Turno Noche">Turno Noche</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="departamentoFuncionario" style="font-size:15px">Departamento de Turno:<code>*</code></label>
                                                                                <select id="departamentoFuncionario" name="departamentoFuncionario" class="form-control" required>
                                                                                    <option value="">Seleccione...</option>
                                                                                    <?php
                                                                                    $departamentos = [];

                                                                                    $queryDepartamentos1 = "SELECT * FROM departamentos WHERE nomDepartamento NOT IN('Informática', 'Ingeniería Operacional', 'Control Centralizado')";
                                                                                    $resDepartamentos1 = mysqli_query($conexion, $queryDepartamentos1);

                                                                                    // Guardar en array con nombre modificado si aplica
                                                                                    while ($row = $resDepartamentos1->fetch_assoc()) {
                                                                                        $idDepa = $row['idDepartamento'];
                                                                                        $nomDepa1 = $row['nomDepartamento'];

                                                                                        if ($nomDepa1 == 'Instrumentación') {
                                                                                            $nomDepa1 = 'Control Centralizado';
                                                                                        }

                                                                                        $departamentos[] = ['id' => $idDepa, 'nombre' => $nomDepa1];
                                                                                    }

                                                                                    // Ordenar alfabéticamente por el nombre mostrado
                                                                                    usort($departamentos, function ($a, $b) {
                                                                                        return strcmp($a['nombre'], $b['nombre']);
                                                                                    });

                                                                                    foreach ($departamentos as $dep) {
                                                                                        ?>
                                                                                        <option value="<?php echo htmlspecialchars($dep['nombre']) ?>"><?php echo htmlspecialchars($dep['nombre']) ?></option>

                                                                                        <?php
                                                                                    } ?>
                                                                                </select>
                                                                            </div>

                                                                        </div>
                                                                        <div class="modal-footer justify-content-center">
                                                                            <button type="submit" id="btnGuardarAsistencia" class="btn btn-success" disabled>
                                                                                <i class="fas fa-save"></i>&nbsp;&nbsp;Guardar
                                                                            </button>
                                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                                                <i class="fas fa-times"></i>&nbsp;&nbsp;Cerrar
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                    <script>
                                                                        document.addEventListener('DOMContentLoaded', function () {
                                                                            const turnoSelect = document.getElementById('turnoFuncionario');
                                                                            const departamentoSelect = document.getElementById('departamentoFuncionario');
                                                                            const submitBtn = document.getElementById('btnGuardarAsistencia');

                                                                            function validarFormulario() {
                                                                                const turnoVal = turnoSelect.value.trim();
                                                                                const departamentoVal = departamentoSelect.value.trim();
                                                                                submitBtn.disabled = (turnoVal === '' || departamentoVal === '');
                                                                            }

                                                                            turnoSelect.addEventListener('change', validarFormulario);
                                                                            departamentoSelect.addEventListener('change', validarFormulario);

                                                                            // Ejecutar al cargar
                                                                            validarFormulario();
                                                                        });
                                                                    </script>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="col-lg-2 col-12">
                                                            <!-- small box -->
                                                            <a href="asistencia_registro.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="fas fa-file-pdf"></i><br>Administración Asistencia</a>
                                                        </div>
                                                    </div>
                                                    <hr>

                                                    <?php
                                                }


                                                if ($ipCliente === $ipPermitida) {

                                                    ?>
                                                    <h5>
                                                        <b>Creador de Documentos</b>
                                                    </h5>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-lg-2 col-12">
                                                            <!-- small box -->
                                                            <a href="documentosGenerados_generar.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-file-pdf"></i><br>Generar Documento</a>
                                                        </div>
                                                        <div class="col-lg-2 col-12">
                                                            <!-- small box -->
                                                            <a href="documentosGenerados_listado.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="fas fa-file-pdf"></i><br>Listado Documentos</a>
                                                        </div>

                                                    </div>
                                                    <hr>
                                                <?php }


                                                if ($nomUsuario == 'Karla Jeldres') {
                                                    ?>
                                                    <h5>
                                                        <b>Memorandum</b>
                                                    </h5>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-lg-2 col-12">
                                                            <!-- small box -->
                                                            <a href="memorandum_listado.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-file-pdf"></i><br>Listado Memorandum</a>
                                                        </div>

                                                    </div>
                                                    <hr>
                                                    <?php
                                                }
                                                ?>
                                                <?php
                                                    if($idDepartamento == 1 || $idDepartamento == 4){
                                                        ?>
                                                        <h5>
                                                            <b>Reporte Electrico</b>
                                                        </h5>
                                                        <br>
                                                        <div class="row">
                                                            <div class="col-lg-2 col-12">
                                                                <!-- small box -->
                                                                <a href="reportes_electricos.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-file"></i><br>Reportes Electricos</a>
                                                            </div>

                                                        </div>
                                                        <hr>
                                                        <?php
                                                    }
                                                ?>
                                                <?php
                                                    if($idDepartamento == 1){
                                                        ?>
                                                        <h5>
                                                            <b>Memorandum Control de Acceso</b>
                                                        </h5>
                                                        <br>
                                                        <div class="row">
                                                            <div class="col-lg-2 col-12">
                                                                <!-- small box -->
                                                                <a href="memo_control_acceso.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-file"></i><br>Memo Control de Acceso</a>
                                                            </div>

                                                        </div>
                                                        <hr>
                                                        <?php
                                                    }
                                                ?>
                                                <h5>
                                                    <b>Organigrama</b>
                                                </h5>
                                                <br>
                                                <div class="row">
                                                    <div class="col-lg-2 col-12">
                                                        <!-- small box -->
                                                        <a href="hbqp_organigrama.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-project-diagram"></i><br>Organigrama</a>
                                                    </div>

                                                </div>
                                                <hr>
                                                <h5>
                                                    <b>Informe EDAN</b>
                                                </h5>
                                                <br>
                                                <div class="row">
                                                    <div class="col-lg-2 col-12">
                                                        <!-- small box -->
                                                        <a href="listar_informe_edan_especialidades.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-clipboard-list"></i><br>Listar Informe EDAN</a>
                                                    </div>

                                                </div>
                                                <hr>

                                                <?php

                                                if ($idDepartamento == 12 && $nomUsuario == 'Usuario Incendio') {
                                                    ?>
                                                    <h5>
                                                        <b>Incendio</b>
                                                    </h5>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-lg-2 col-12">
                                                            <!-- small box -->
                                                            <a href="incendio-boletin.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-fire"></i><br>Boletin Incendio</a>

                                                        </div>

                                                    </div>
                                                    <hr>

                                                    <?php
                                                }

                                                $departamentoJefe = getNomDepartamento($idDepartamento);

                                                if ($departamentoJefe != "Instrumentación") {
                                                    ?>
                                                    <!--<h5><b>Informe EDAN</b></h5>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-lg-2 col-12">
                                                            <a href="listar_informe_edan_especialidades.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-clipboard-list"></i><br>Listar Informe EDAN</a>
                                                        </div>
                                                    </div>
                                                    <hr>-->
                                                    <?php

                                                    if ($idDepartamento == 8) {
                                                        ?>
                                                        <h5>
                                                            <b>Viajes - Transporte </b>
                                                        </h5>
                                                        <br>
                                                        <div class="row">
                                                            <div class="col-lg-2 col-12">
                                                                <!-- small box -->
                                                                <a href="viajes-1.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-car"></i><br>Ingresar Viaje</a>

                                                            </div>

                                                        </div>
                                                        <hr>

                                                        <?php
                                                    }
                                                    ?>

                                                    <?php
                                                    $lopuedever = 'NO';
                                                    if ($lopuedever == 'NO') {
                                                        ?>
                                                        <h5>
                                                            <b>Orden de Trabajo</b>
                                                        </h5>
                                                        <br>

                                                        <div class="row">
                                                            <?php
                                                            $contarOT = contarOTTecnico($nomUsuario);
                                                            if ($contarOT > 0) {
                                                                ?>
                                                                <div class="col-lg-2 col-12">
                                                                    <a href="trabajos_general_listado.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-list"></i><br>Trabajos<span class="cantidad-badge cantidad5"><i class="fas fa-flag"><span id="cantidad5">0</span></i></span></a>
                                                                    <script>
                                                                        document.getElementById("cantidad5").innerHTML = '&nbsp;<?php echo $contarOT ?>';
                                                                    </script>
                                                                </div>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <div class="col-lg-2 col-12">
                                                                    <a href="trabajos_general_listado.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-list"></i><br>Trabajos</a>
                                                                </div>
                                                                <?php
                                                            }
                                                            ?>

                                                            <div class="col-lg-2 col-12">
                                                                <a href="trabajos_general_listado_terminados.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-list"></i><br>Trabajos Terminados</a>
                                                            </div>

                                                        </div>

                                                        <hr>
                                                        <?php
                                                    }
                                                    ?>

                                                    <?php
                                                    if ($nomUsuario == 'Gustavo Loira' || $nomUsuario == 'Haroll Álvarez Barrera') {
                                                        ?>
                                                        <h5>
                                                            <b>Informes de los Departamentos</b>
                                                        </h5>
                                                        <br>
                                                        <div class="row">

                                                            <div class="col-lg-2 col-12">
                                                                <!-- small box -->
                                                                <a href="reportes-listado.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-book"></i><br>Historial Reportes</a>
                                                            </div>

                                                        </div>

                                                        <hr>
                                                        <?php

                                                    }
                                                    ?>
                                                    <!-- Equipamiento HBQP -->
                                                    <h5>
                                                        <b>Equipamiento HBQP</b>
                                                    </h5>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-lg-2 col-12">
                                                            <!-- small box -->
                                                            <a href="equipamiento_hbqp.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-desktop"></i><br>Listado de Equipos</a>

                                                        </div>
                                                        <?php
                                                        if ($idDepartamento == 10 || $idDepartamento == 1) {
                                                            ?>

                                                            <div class="col-lg-2 col-12">
                                                                <!-- small box -->
                                                                <a href="protocolo_refrigeradores.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-desktop"></i><br>Protocolo Refrigeradores</a>

                                                            </div>
                                                            <?php
                                                        }
                                                        ?>

                                                    </div>
                                                    <hr>

                                                    <!-- LISTADO DE TECNICOS -->
                                                    <h5>
                                                        <b>Administraci&oacute;n de Tecnicos</b>
                                                    </h5>
                                                    <br>
                                                    <div class="row">

                                                        <div class="col-lg-2 col-12">
                                                            <!-- small box -->
                                                            <a href="tecnicos-listado.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-users"></i><br>Listado de Tecnicos</a>

                                                        </div>
                                                    </div>

                                                    <!-- LISTADO DE TECNICOS -->
                                                    <hr>
                                                    <?php

                                                    $mostrarOcultar = deshabilitarOpciones();

                                                    if ($mostrarOcultar == 'Mostrar') {
                                                        ?>
                                                        <!-- HOJAS TECNICAS -->
                                                        <h5>
                                                            <b>Hojas Técnicas</b>
                                                        </h5>
                                                        <br>
                                                        <div class="row">
                                                            <div class="col-lg-2 col-12">
                                                                <!-- small box -->
                                                                <?php
                                                                if ($idDepartamento == 6) {
                                                                    $activo = "ordenes-formulario-general-inge-clinica.php";
                                                                } else {
                                                                    $activo = "ordenes-formulario-general.php";
                                                                }
                                                                ?>
                                                                <a href="<?php echo $activo ?>" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-file"></i><br>Crear Hoja T&eacute;cnica</a>

                                                            </div>
                                                            &nbsp;
                                                            <div class="col-lg-2 col-12">
                                                                <!-- small box -->
                                                                <a href="ordenes-listado-general.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-book"></i><br>Historial Hojas T&eacute;cnicas</a>

                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>


                                                    <!-- HOJAS TECNICAS -->
                                                    <?php
                                                } else {
                                                    ?>
                                                    <h5>
                                                        <b>Informe EDAN</b>
                                                    </h5>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-lg-2 col-12">
                                                            <!-- small box -->
                                                            <a href="listar_informe_edan.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-clipboard-list"></i><br>Listar Informe EDAN</a>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <h5>
                                                        <b>Recepcion de Documentos</b>
                                                    </h5>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-lg-2 col-12">
                                                            <!-- small box -->
                                                            <a href="recepcion_documento.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-clipboard-list"></i><br>Agregar Documento</a>
                                                        </div>
                                                        <div class="col-lg-2 col-12">
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
                                                                <a href="listar_recepcion_documento.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="fas fa-clipboard-list"></i><br>Historial Documentos</a>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>

                                                    <hr>
                                                    <h5>
                                                        <b>Reporte Alarmas BMS</b>
                                                    </h5>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-lg-2 col-12">
                                                            <!-- small box -->
                                                            <a href="BMS_ReporteAlarmas.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-clipboard-list"></i><br>Reporte Alarmas BMS</a>
                                                        </div>
                                                        <div class="col-lg-2 col-12">
                                                            <!-- small box -->
                                                            <a href="BMS_Alarmas.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="fas fa-clipboard-list"></i><br>Alarmas Farmacia BMS</a>
                                                        </div>
                                                    </div>

                                                    <hr>
                                                    <h5>
                                                        <b>Revisi&oacute;n Formatos de Acceso</b>
                                                    </h5>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-lg-2 col-12">
                                                            <!-- small box -->
                                                            <a href="formulariosCCAA_menuSeleccion.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-clipboard-list"></i><br>Selecci&oacute;n Documentos</a>

                                                        </div>
                                                        &nbsp;

                                                        <div class="col-lg-2 col-12">
                                                            <!-- small box -->
                                                            <?php
                                                            $cantidadRecibidos = contarFormulariosRecibidos();

                                                            if ($cantidadRecibidos > 0) {
                                                                ?>

                                                                <a href="formulariosCCAA_listado.php" id="formCCAARecibidos" name="formCCAARecibidos" class="btn btn-block bg-gradient-secondary btn-lg" style="position: relative;"><i class="fas fa-clipboard-list"></i><br>Formatos - Recibidos<span class="cantidad-badge"><i class="fas fa-flag"><span id="cantidad">0</span></i></span></a>


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
                                                        <div class="col-lg-2 col-12">
                                                            <!-- small box -->
                                                            <a href="formulariosCCAA_listadoFinalizado.php" class="btn btn-block bg-gradient-info btn-lg"><i class="fas fa-clipboard-list"></i><br>Formatos - Finalizados</a>

                                                        </div>
                                                        &nbsp;
                                                        <div class="col-lg-2 col-12">
                                                            <a href="formulariosCCAA_listadoUsuarios.php" class="btn btn-block bg-gradient-yellow btn-lg"><i class="fas fa-clipboard-list"></i><br>Formatos - Usuarios</a>

                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <!-- RUTINA DIARIA -->
                                                    <h5>
                                                        <b>Rutina Diaria</b>
                                                    </h5>
                                                    <br>
                                                    <div class="row">

                                                        <div class="col-lg-2 col-12">
                                                            <!-- small box -->
                                                            <a href="bitacora-listado.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-clock"></i><br>Bitacora</a>

                                                        </div>

                                                        <?php

                                                        if ($nomUsuario == 'Yorgo Casimis Subiabre' || $nomUsuario == 'Isaac Perez') {
                                                            $contarOT = contarOTTecnico($nomUsuario);

                                                        if ($contarOT > 0){
                                                            ?>
                                                            <div class="col-lg-2 col-12">
                                                                <!-- small box -->
                                                                <a href="trabajos_general_listado.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-list"></i><br>Trabajos<span class="cantidad-badge cantidad4"><i class="fas fa-flag"><span id="cantidad4">0</span></i></span></a>
                                                            </div>
                                                            <script>
                                                                document.getElementById("cantidad4").innerHTML = '&nbsp;<?php echo $contarOT ?>';
                                                            </script>
                                                        <?php
                                                        }else{
                                                        ?>
                                                            <div class="col-lg-2 col-12">
                                                                <!-- small box -->
                                                                <a href="trabajos_general_listado.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-list"></i><br>Trabajos</a>
                                                            </div>
                                                        <?php
                                                        }
                                                        ?>

                                                            <div class="col-lg-2 col-12">
                                                                <!-- small box -->
                                                                <a href="trabajos_general_listado_terminados.php" class="btn btn-block bg-gradient-info btn-lg"><i class="nav-icon fas fa-list"></i><br>Trabajos Terminados</a>

                                                            </div>

                                                            <div class="col-lg-2 col-12">
                                                                <a href="ucas_levantamiento_incendio.php" class="btn btn-block bg-gradient-warning btn-lg"><i class="nav-icon fas fa-list"></i><br>Levant. UCAS Incendio</a>
                                                            </div>

                                                            <?php
                                                        }

                                                        ?>
                                                        <?php
                                                        if ($idDepartamento == 1) {
                                                            ?>
                                                            <div class="col-lg-2 col-12">
                                                                <!-- small box -->
                                                                <a href="check_list_todos.php" class="btn btn-block bg-gradient-success btn-lg"><i class="nav-icon fas fa-list"></i><br>Check List</a>

                                                            </div>
                                                            <?php
                                                        }
                                                        ?>

                                                    </div>
                                                    <hr>
                                                    <!-- RUTINA DIARIA-->

                                                    <!-- PLANIFICADOR -->
                                                    <h5>
                                                        <b>Planificaci&oacute;n</b>
                                                    </h5>
                                                    <br>
                                                    <div class="row">

                                                        <div class="col-lg-2 col-12">
                                                            <!-- small box -->
                                                            <a href="planificador_administracion.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-folder"></i><br>Planificaci&oacute;n</a>

                                                        </div>

                                                        <?php
                                                        if ($ipCliente === $ipPermitida && ($idDepartamento == 1)) {
                                                            ?>

                                                            <div class="col-lg-2 col-12">
                                                                <!-- small box -->
                                                                <a href="protocolo_refrigeradores.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-desktop"></i><br>Protocolo Refrigeradores</a>

                                                            </div>
                                                            <?php
                                                        }

                                                        ?>

                                                    </div>
                                                    <hr>

                                                    <!-- ALMACENAMIENTO -->
                                                    <h5>
                                                        <b>Nube de Archivos</b>
                                                    </h5>
                                                    <br>
                                                    <div class="row">

                                                        <div class="col-lg-2 col-12">
                                                            <!-- small box -->
                                                            <a href="carpetas_listado.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-folder"></i><br>Nube de Archivos</a>

                                                        </div>

                                                    </div>
                                                    <hr>

                                                    <!-- REPORTES GENERALES -->
                                                    <h5>
                                                        <b>Reportes de Eventos</b>
                                                    </h5>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-lg-2 col-12">
                                                            <!-- small box -->
                                                            <a href="reportes-formulario.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-file"></i><br>Crear Reporte Eventos</a>

                                                        </div>
                                                        &nbsp;
                                                        <div class="col-lg-2 col-12">
                                                            <!-- small box -->
                                                            <a href="reportes-listado.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-book"></i><br>Historial Reportes</a>

                                                        </div>
                                                    </div>
                                                    <hr>

                                                    <!-- REPORTES GENERALES -->


                                                    <!-- HOJAS TECNICAS -->
                                                    <?php

                                                    $mostrarOcultar = deshabilitarOpciones();

                                                    if ($mostrarOcultar == 'Mostrar') {
                                                        ?>
                                                        <h5>
                                                            <b>Hojas Técnicas</b>
                                                        </h5>
                                                        <br>
                                                        <div class="row">
                                                            <div class="col-lg-2 col-12">
                                                                <!-- small box -->
                                                                <a href="ordenes-formulario-general.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-file"></i><br>Crear Hoja T&eacute;cnica</a>

                                                            </div>
                                                            &nbsp;
                                                            <div class="col-lg-2 col-12">
                                                                <!-- small box -->
                                                                <a href="ordenes-listado-general.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-book"></i><br>Historial Hojas T&eacute;cnicas</a>

                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <?php
                                                    }
                                                    ?>


                                                    <h5>
                                                        <b>Visualizador Pabellón</b>
                                                    </h5>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-lg-2 col-12">
                                                            <!-- small box -->
                                                            <a href="pabellones_administracion.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-cloud"></i><br>Visualizador Pabellones</a>

                                                        </div>
                                                        <div class="col-lg-2 col-12">
                                                            <!-- small box -->
                                                            <a href="pabellones_administracion_correo.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-cloud"></i><br>Informar Variables</a>

                                                        </div>
                                                    </div>
                                                    <hr>


                                                    <!-- HOJAS TECNICAS -->

                                                    <!-- SISTEMA BMS -->
                                                    <h5>
                                                        <b>Observaciones de Sistema BMS</b>
                                                    </h5>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-lg-2 col-12">
                                                            <!-- small box -->
                                                            <a href="sistemas-listado.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-bars"></i><br>Listado de Sistemas</a>

                                                        </div>
                                                        &nbsp;
                                                        <div class="col-lg-2 col-12">
                                                            <!-- small box -->
                                                            <a href="forzados-listado.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-cogs"></i><br>Equipos Forzados</a>

                                                        </div>
                                                        &nbsp;
                                                        <div class="col-lg-2 col-12">
                                                            <!-- small box -->
                                                            <a href="sistemas-observaciones.php" class="btn btn-block bg-gradient-info btn-lg"><i class="nav-icon fas fa-pen"></i><br>Observaciones Equipos</a>

                                                        </div>
                                                    </div>
                                                    <hr>

                                                    <!-- SISTEMA BMS -->

                                                    <!-- LEVANTAMIENTO SISTEMA BMS -->
                                                    <h5>
                                                        <b>Levantamiento de Sistema BMS</b>
                                                    </h5>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-lg-2 col-12">
                                                            <!-- small box -->
                                                            <a href="controladores-maestros-listado.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-list"></i><br>Controladores Maestros</a>

                                                        </div>
                                                        &nbsp;
                                                        <div class="col-lg-2 col-12">
                                                            <!-- small box -->
                                                            <a href="controladores-esclavos-listado.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-list"></i><br>Controladores Esclavos</a>

                                                        </div>
                                                        &nbsp;
                                                        <!--                                                            <div class="col-lg-3 col-12">-->
                                                        <!--                                                                <a href="detalle_controladores_bms.php" class="btn btn-block bg-gradient-info btn-lg"><i class="nav-icon fas fa-list"></i><br>Detalle Controladores</a>-->
                                                        <!---->
                                                        <!--                                                            </div>-->
                                                    </div>
                                                    <hr>

                                                    <!-- LEVANTAMIENTO SISTEMA BMS -->

                                                    <!-- CONTROL DE ACCESO -->
                                                    <h5>
                                                        <b>Control de Acceso</b>
                                                    </h5>
                                                    <br>
                                                    <div class="row">

                                                        <div class="col-md-2 col-12">
                                                            <!-- small box -->
                                                            <a href="control-acceso.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-users"></i><br>Control de Acceso</a>

                                                        </div>
                                                        &nbsp;
                                                        <div class="col-md-2 col-12">
                                                            <!-- small box -->
                                                            <a href="parking-acceso.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-parking"></i><br>Acceso a Parking</a>

                                                        </div>
                                                        &nbsp;
                                                        <div class="col-md-2 col-12">
                                                            <!-- small box -->
                                                            <a href="actas-listado.php" class="btn btn-block bg-gradient-info btn-lg"><i class="nav-icon fas fa-book"></i><br>Administraci&oacute;n - Actas</a>

                                                        </div>
                                                        &nbsp;
                                                        <div class="col-md-2 col-12">
                                                            <!-- small box -->
                                                            <a href="llaves_entregadas.php" class="btn btn-block bg-gradient-warning btn-lg"><i class="nav-icon fas fa-key"></i><br>Llaves Entregadas</a>

                                                        </div>
                                                        &nbsp;
                                                        <div class="col-md-2 col-12">
                                                            <!-- small box -->
                                                            <a href="eventos_registro.php" class="btn btn-block bg-gradient-purple btn-lg"><i class="nav-icon fas fa-clock"></i><br>Eventos</a>
                                                        </div>
                                                    </div>

                                                    <hr>

                                                    <!-- CONTROL DE ACCESO -->

                                                    <!-- SISTEMA SMARTPSS -->
                                                    <h5>
                                                        <b>Informaci&oacute;n de Puertas</b>
                                                    </h5>
                                                    <br>
                                                    <div class="row">

                                                        <div class="col-md-2 col-12">
                                                            <!-- small box -->
                                                            <a href="puertas_ccaa_listado.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-door-closed"></i><br>Listado Puertas</a>

                                                        </div>
                                                        &nbsp;
                                                        <div class="col-md-2 col-12">
                                                            <!-- small box -->
                                                            <a href="ucas_listado.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-door-closed"></i><br>Listado de UCAS</a>

                                                        </div>
                                                    </div>

                                                    <hr>

                                                    <!-- SISTEMA SMARTPSS -->

                                                    <!-- LISTADO DE TECNICOS -->
                                                    <h5>
                                                        <b>Administraci&oacute;n de Tecnicos</b>
                                                    </h5>
                                                    <br>
                                                    <div class="row">

                                                        <div class="col-lg-2 col-12">
                                                            <!-- small box -->
                                                            <a href="tecnicos-listado.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-users"></i><br>Listado de Tecnicos</a>

                                                        </div>
                                                    </div>

                                                    <!-- LISTADO DE TECNICOS -->
                                                    <?php
                                                }
                                                ?>
                                                <hr>
                                                <!-- TICKETS DE SOPORTE -->
                                                <h5>
                                                    <b>Tickets de Soporte</b>
                                                </h5>
                                                <br>
                                                <div class="row">

                                                    <div class="col-lg-2 col-12">
                                                        <!-- small box -->
                                                        <a href="solicitudes_recibidas.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-ticket-alt"></i><br>Tickets Recibidos</a>

                                                    </div>
                                                    &nbsp;
                                                    <div class="col-lg-2 col-12">
                                                        <!-- small box -->
                                                        <a href="solicitudes_finalizadas.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-check"></i><br>Tickets Finalizados</a>

                                                    </div>
                                                    &nbsp;
                                                    <div class="col-lg-2 col-12">
                                                        <!-- small box -->
                                                        <a href="solicitudes_canceladas.php" class="btn btn-block bg-gradient-info btn-lg"><i class="nav-icon fas fa-times"></i><br>Tickets Cancelados</a>
                                                    </div>
                                                    <?php
                                                    if ($nomUsuario == 'Yorgo Casimis Subiabre' || $nomUsuario == 'Isaac Perez') {
                                                        ?>
                                                        <div class="col-lg-2 col-12">
                                                            <a href="usuarios_tickets.php" class="btn btn-block bg-gradient-warning btn-lg"><i class="nav-icon fas fa-list"></i><br>Listado de Usuarios</a>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                                <hr>
                                                <!-- TICKETS DE SOPORTE -->

                                                <!-- TICKETS DE SOPORTE -->
                                                <h5>
                                                    <b>Solicitudes de Compra</b>
                                                </h5>
                                                <br>
                                                <div class="row">

                                                    <div class="col-lg-2 col-12">
                                                        <!-- small box -->
                                                        <a href="soliCompra_pm_administracion.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-shopping-bag"></i><br>SC - Puesta en Marcha</a>

                                                    </div>
                                                    &nbsp;
                                                    <div class="col-lg-2 col-12">
                                                        <!-- small box -->
                                                        <a href="soliCompra_sdo_administracion.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-shopping-bag"></i><br>SC - SDO</a>

                                                    </div>
                                                </div>
                                                <!-- TICKETS DE SOPORTE -->


                                                <hr>
                                                <h5>
                                                    <b>Consumos Basicos</b>
                                                </h5>
                                                <br>
                                                <div class="row">

                                                    <div class="col-lg-2 col-12">
                                                        <!-- small box -->
                                                        <a href="consumos_basicos.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-gas-pump"></i>&nbsp;&nbsp;<i class="nav-icon fas fa-tint"></i>&nbsp;&nbsp;<i class="nav-icon fas fa-bolt"></i><br>Consumos Basicos</a>

                                                    </div>
                                                </div>


                                                <?php
                                                if ($nomDepartamento == 'Equipos Industriales') {
                                                    ?>
                                                    <hr>


                                                    <!-- Combustible -->
                                                    <h5>
                                                        <b>Combustible</b>
                                                    </h5>
                                                    <br>
                                                    <div class="row">

                                                        <div class="col-lg-2 col-12">
                                                            <!-- small box -->
                                                            <a href="combustible_mediciones.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-gas-pump"></i><br>Registrar Medici&oacute;n</a>

                                                        </div>
                                                    </div>
                                                    <!-- Combustible -->


                                                    <hr>

                                                    <?php
                                                }
                                                ?>


                                            </div>
                                            <div class="tab-pane" id="tab_1">

                                                <?php


                                                $tipoUsuarioVer = 'unico';
                                                include_once '../sdo-includes/hojastecnicas_dashboard.php';

                                                ?>

                                            </div>
                                            <!-- /.tab-pane -->
                                            <?php
                                            if ($nomUsuario == 'Yorgo Casimis Subiabre') {
                                                ?>
                                                <div class="tab-pane" id="tab_2">
                                                    <?php include_once('../sdo-includes/solicitudes_compra_dashboard_depto.php') ?>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="tab-pane" id="tab_2">
                                                    <h4 class="text-center text-bold text-danger" style="font-size:20px">Información General</h4>
                                                    <br>
                                                    <div class="row ">
                                                        <div class="col-12  col-sm-6 col-md-3">
                                                            <div class="col-12 col-sm-6 col-md-12">
                                                                <div class="info-box">
                                                                    <span class="info-box-icon bg-info elevation-1">
                                                                        <i class="fas fa-list-ol"></i>
                                                                    </span>
                                                                    <div class="info-box-content">
                                                                        <span class="info-box-text text-info" style="font-size:15px"> <b>Total Solicitudes de Compra</b> </span>
                                                                        <span class="info-box-number text-info" style="font-size:15px"><?php echo $totalSolicitudes ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-sm-6 col-md-12">
                                                                <div class="info-box">
                                                                    <span class="info-box-icon bg-success elevation-1">
                                                                        <i class="fas fa-cart-arrow-down"></i>
                                                                    </span>

                                                                    <div class="info-box-content">
                                                                        <span class="info-box-text text-success"> <b>Solicitudes Aprobadas por la S.D.A</b> </span>
                                                                        <span class="info-box-number text-success" id="Aprobadas"><?php echo $totalAprobadas ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <div class="col-12 col-sm-6 col-md-12">
                                                                <div class="info-box">
                                                                    <span class="info-box-icon bg-danger elevation-1">
                                                                        <i class="fas fa-times-circle"></i>
                                                                    </span>
                                                                    <div class="info-box-content">
                                                                        <span class="info-box-text text-danger"> <b>Solicitudes Rechazadas por la S.D.A</b> </span>
                                                                        <span class="info-box-number text-danger" id="Rechazadas"><?php echo $totalRechazadas ?? 0 ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-sm-6 col-md-12">
                                                                <div class="info-box">
                                                                    <span class="info-box-icon bg-warning elevation-1">
                                                                        <i class="fas fa-exclamation-triangle"></i>
                                                                    </span>
                                                                    <div class="info-box-content">
                                                                        <span class="info-box-text text-warning"> <b>Solicitudes Anuladas</b> </span>
                                                                        <span class="info-box-number text-warning" id="Anuladas"><?php echo $totalAnuladas ?? 0 ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-md-6 ">
                                                            <div class="col-12 col-sm-6 col-md-4  mx-auto">
                                                                <canvas id="myChart"></canvas>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <h4 class="text-center text-bold text-info" style="font-size:20px">Cantidad de solicitudes por fase</h4>
                                                    <br>
                                                    <div class="row">
                                                        <br>
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <div class="info-box">
                                                                <span class="info-box-icon bg-purple elevation-1">
                                                                    <i class="fas fa-user"></i>
                                                                </span>
                                                                <div class="info-box-content">
                                                                    <span class="info-box-text text-purple" style="font-size:15px"> <b>Solicitadas a la secretaria S.D.O</b> </span>
                                                                    <span class="info-box-number text-purple" style="font-size:15px"><?php echo $totalSecretaria ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <div class="info-box">
                                                                <span class="info-box-icon bg-pink elevation-1">
                                                                    <i class="fas fa-user"></i>
                                                                </span>
                                                                <div class="info-box-content">
                                                                    <span class="info-box-text text-pink" style="font-size:15px"> <b>Revisadas por secretaria S.D.O</b> </span>
                                                                    <span class="info-box-number text-pink" style="font-size:15px"><?php echo $totalRevisados ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <div class="info-box">
                                                                <span class="info-box-icon bg-orange elevation-1">
                                                                    <i class="fas fa-wallet"></i>
                                                                </span>

                                                                <div class="info-box-content">
                                                                    <span class="info-box-text text-orange"> <b>Recibidas por la S.D.A</b> </span>
                                                                    <span class="info-box-number text-orange"><?php echo $totalSDA ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <div class="info-box">
                                                                <span class="info-box-icon bg-lightblue elevation-1">
                                                                    <i class="fas fa-truck-loading"></i>
                                                                </span>
                                                                <div class="info-box-content">
                                                                    <span class="info-box-text text-lightblue"> <b>Derivados a Abastecimiento</b> </span>
                                                                    <span class="info-box-number text-lightblue"><?php echo $totalAbastecimiento ?? 0 ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <div class="info-box">
                                                                <span class="info-box-icon bg-dark elevation-1">
                                                                    <i class="fas fa-warehouse"></i>
                                                                </span>
                                                                <div class="info-box-content">
                                                                    <span class="info-box-text text-dark"> <b>Recibidos por pañol</b> </span>
                                                                    <span class="info-box-number text-dark"><?php echo $totalPanol ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <div class="info-box">
                                                                <span class="info-box-icon bg-green elevation-1">
                                                                    <i class="fas fa-smile-wink"></i>
                                                                </span>
                                                                <div class="info-box-content">
                                                                    <span class="info-box-text text-green"> <b>Completadas</b> </span>
                                                                    <span class="info-box-number text-green"><?php echo $totalCompletadas ?></span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>


                                                    <hr>
                                                    <h4 class="text-center text-bold text-success" style="font-size:20px">$$ Dinero Gastado por departamento $$</h4>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <div class="info-box">
                                                                <span class="info-box-icon bg-purple elevation-1">
                                                                    <i class="fas fa-cogs"></i>
                                                                </span>
                                                                <div class="info-box-content">
                                                                    <span class="info-box-text text-purple"> <b>Instrumentación</b> </span>
                                                                    <span class="info-box-number text-purple"><?php echo $totalInst ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <div class="info-box">
                                                                <span class="info-box-icon bg-dark elevation-1">
                                                                    <i class="fas fa-building"></i>
                                                                </span>
                                                                <div class="info-box-content">
                                                                    <span class="info-box-text text-dark"> <b>Infraestructura</b> </span>
                                                                    <span class="info-box-number text-dark"><?php echo $totalInfraestructura
                                                                        ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <div class="info-box">
                                                                <span class="info-box-icon bg-warning elevation-1">
                                                                    <i class="fas fa-industry"></i>
                                                                </span>
                                                                <div class="info-box-content">
                                                                    <span class="info-box-text text-warning"> <b>Industrial</b> </span>
                                                                    <span class="info-box-number text-warning"><?php echo $totalIndustrial ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <div class="info-box">
                                                                <span class="info-box-icon bg-success elevation-1">
                                                                    <i class="far fa-snowflake"></i>
                                                                </span>
                                                                <div class="info-box-content">
                                                                    <span class="info-box-text text-success"> <b>Climatización</b> </span>
                                                                    <span class="info-box-number text-success"><?php echo $totalClima ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <div class="info-box">
                                                                <span class="info-box-icon bg-danger elevation-1">
                                                                    <i class="fas fa-lightbulb"></i>
                                                                </span>
                                                                <div class="info-box-content">
                                                                    <span class="info-box-text text-danger"> <b>Electrico</b> </span>
                                                                    <span class="info-box-number text-danger"><?php echo $totalElect ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <div class="info-box">
                                                                <span class="info-box-icon bg-info elevation-1">
                                                                    <i class="fas fa-dolly-flatbed"></i>
                                                                </span>
                                                                <div class="info-box-content">
                                                                    <span class="info-box-text text-info"> <b>Logistica</b> </span>
                                                                    <span class="info-box-number text-info"><?php echo $totalLogistica ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <div class="info-box">
                                                                <span class="info-box-icon bg-orange elevation-1">
                                                                    <i class="fas fa-car-side"></i>
                                                                </span>
                                                                <div class="info-box-content">
                                                                    <span class="info-box-text text-orange"> <b>Movilización</b> </span>
                                                                    <span class="info-box-number text-orange"><?php echo $totalMovilizacion ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <div class="info-box">
                                                                <span class="info-box-icon bg-pink elevation-1">
                                                                    <i class="fas fa-users"></i>
                                                                </span>
                                                                <div class="info-box-content">
                                                                    <span class="info-box-text text-pink"> <b>Ingenieria Operacional</b> </span>
                                                                    <span class="info-box-number text-pink"><?php echo $totalOperacional ?></span>
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>

                                                </div>
                                                <?php
                                            }
                                            ?>

                                            <!-- /.tab-pane -->
                                            <div class="tab-pane" id="tab_5">
                                                <?php
                                                $tipoUsuarioVer = 'unico';
                                                include_once('../sdo-includes/ordenes_trabajo_dashboard.php')
                                                ?>
                                            </div>
                                            <div class="tab-pane" id="tab_6">
                                                <?php
                                                include_once('../sdo-includes/mostrar-peras.php')
                                                ?>
                                            </div>
                                            <!-- /.tab-pane -->
                                        </div>
                                        <!-- /.tab-content -->
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- ./card -->
                            </div>
                            <!-- /.col -->
                        </div>
                    </div>

                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <strong>Copyright &copy; 2022. Derechos Reservados.
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->

<!-- SCRIPT DARK MODE -->
<script src="js/dark-mode.js"></script>

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
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    toastr.options = {
        "positionClass": "toast-top-right",
        "timeOut": "5000",
        "closeButton": true,
        "progressBar": true
    };
</script>

<?php
// Aquí tu PHP con los toastr según corresponda
//if ($obtenerOTSegunNombre > 0) {
//    echo "<script>toastr.info('Existen nuevas Ordenes de Trabajo.');</script>";
//}
if (isset($_GET['exitoMarcajeAsistencia'])) {
    echo "<script>toastr.success('Marcaje registrado correctamente.');</script>";
}
if (isset($_GET['errorMarcajeAsistencia'])) {
    echo "<script>toastr.error('Error al registrar la asistencia.');</script>";
}
if (isset($_GET['existeMarcajeAsistencia'])) {
    echo "<script>toastr.info('Existen Marcaje de Asistencia.');</script>";
}
?>
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
<script>
    const toggle = document.getElementById('toggle-sidebar');
    const icon = document.getElementById('sidebar-icon');


    toggle.addEventListener('click', function () {
        console.log('hola');
        icon.classList.toggle('rotar');
    })
</script>
<script>
    const Anuladas = document.getElementById('Anuladas').textContent;
    const Rechazadas = document.getElementById('Rechazadas').textContent;
    const Aprobadas = document.getElementById('Aprobadas').textContent;
    const ctx = document.getElementById('myChart');

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Aprobadas', 'Rechazadas', 'Anuladas'],
            datasets: [{
                label: 'Cantidad',
                data: [Aprobadas, Rechazadas, Anuladas],
                borderWidth: 1,
                backgroundColor: ['green', 'red', 'orange'],
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

</body>

</html>


