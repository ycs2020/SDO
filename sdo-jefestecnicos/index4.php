

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
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                <div class="row justify-content-center align-content-center">

                                    <?php
                                    // Array de tarjetas
                                    $tarjetas = [
                                        [
                                            'href' => 'ordenes-listado-general.php',
                                            'bg' => 'bg-gradient-primary',
                                            'icon' => 'fas fa-file-alt',
                                            'label' => 'Hojas Técnicas',
                                            'value' => countHTDeptoAnio($idDepartamento, $anioActual),
                                        ],
                                        [
                                            'href' => 'soliCompra_sdo_administracion.php',
                                            'bg' => 'bg-gradient-warning',
                                            'icon' => 'fas fa-shopping-cart',
                                            'label' => 'Solicitudes Compra',
                                            'value' => countSCSDODeptoAnio($nomDepartamento, $anioActual),
                                        ],
                                        [
                                            'href' => 'equipamiento_hbqp.php',
                                            'bg' => 'bg-gradient-purple',
                                            'icon' => 'fas fa-cogs',
                                            'label' => 'Equipos',
                                            'value' => countEquipamientoDepto($idDepartamento),
                                        ],
                                        [
                                            'href' => 'avisos_relevantes_administracion.php',
                                            'bg' => (countAvisosRelevantesDirigidos($nomUsuario) > 0) ? 'bg-gradient-danger' : 'bg-gradient-success',
                                            'icon' => 'fas fa-phone',
                                            'label' => 'Avisos Relevantes',
                                            'value' => countAvisosRelevantesDirigidos($nomUsuario),
                                        ],
                                    ];

                                    // Si corresponde agregar Reportes Turno
                                    if ($idDepartamento == 1 || $idDepartamento == 13) {
                                        $tarjetas[] = [
                                            'href' => 'reportes-listado.php',
                                            'bg' => 'bg-gradient-info',
                                            'icon' => 'fas fa-book',
                                            'label' => 'Reportes Turno',
                                            'value' => getCantTotalReportes(),
                                        ];
                                    }

                                    // Imprimir tarjetas
                                    foreach ($tarjetas as $card) {
                                        echo '
                                              <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2">
                                                <a href="' . $card['href'] . '" class="text-white text-decoration-none">
                                                  <div class="card ' . $card['bg'] . ' shadow-sm py-2">
                                                    <div class="card-body p-2 text-center">
                                                      <i class="' . $card['icon'] . ' fa-lg mb-2 d-block"></i>
                                                      <div class="h5 font-weight-bold mb-0">' . $card['value'] . '</div>
                                                      <small class="text-uppercase">' . $card['label'] . '</small>
                                                    </div>
                                                  </div>
                                                </a>
                                              </div>';
                                    }
                                    ?>

                                </div>

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

                                                        document.getElementById('btn-validar-clave').addEventListener('click', function () {
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
                                                if ($idDepartamento == 1 || $idDepartamento == 4) {
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
                                                if ($idDepartamento == 1) {
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
                                                                <?php
                                                                if ($idDepartamento == 1) {
                                                                    $activo = "ordenes-formulario-general4.php";
                                                                }
                                                                ?>
                                                                <?php
                                                                if ($idDepartamento == 6) {
                                                                    $activo = "ordenes-formulario-general-inge-clinica.php";
                                                                } else if ($idDepartamento != 1) {
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


