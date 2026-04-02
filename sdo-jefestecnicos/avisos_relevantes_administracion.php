<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$nomUsuario = $_SESSION['nomUsuario'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Avisos Relevantes en SDO</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <link rel="stylesheet" href="../sdo-funciones/jquery-3.2.1.min.js">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">


    <!-- Bootstrap Switch CSS (si lo necesitas, AdminLTE puede ya tenerlo integrado) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css">

    <style>
        /* ============================
           MODO CLARO (por defecto)
        ============================ */

        /* Texto dentro del campo seleccionado */
        .select2-container--bootstrap4 .select2-selection__rendered {
            color: #000000; /* Texto negro */
        }

        /* Opciones en el listado desplegable */
        .select2-container--bootstrap4 .select2-results__option {
            color: #000000; /* Texto negro */
        }

        /* Fondo del dropdown */
        .select2-container--bootstrap4 .select2-dropdown {
            background-color: #ffffff; /* Fondo blanco */
            color: #000000;
        }

        /* Opciones seleccionadas múltiples (chips) */
        .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
            background-color: #007bff !important; /* Azul */
            color: #ffffff !important; /* Texto blanco */
            border: none;
        }

        /* ============================
           MODO OSCURO (con clase .dark-mode en <body>)
        ============================ */

        /* Fondo del campo de selección */
        .dark-mode .select2-container--bootstrap4 .select2-selection {
            background-color: #343a40 !important; /* Fondo oscuro */
            color: #ffffff !important; /* Texto blanco */
            border-color: #495057 !important;
        }

        /* Texto dentro del campo seleccionado */
        .dark-mode .select2-container--bootstrap4 .select2-selection__rendered {
            color: #ffffff !important;
        }

        /* Opciones seleccionadas múltiples (chips) */
        .dark-mode .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
            background-color: #007bff !important;
            color: #ffffff !important;
            border: none;
        }

        /* Dropdown fondo y texto */
        .dark-mode .select2-container--bootstrap4 .select2-dropdown {
            background-color: #343a40 !important;
            color: #ffffff !important;
            border-color: #495057 !important;
        }

        /* Opciones en la lista desplegable */
        .dark-mode .select2-container--bootstrap4 .select2-results__option {
            color: #ffffff !important;
        }

        /* Opción seleccionada en dropdown */
        .dark-mode .select2-container--bootstrap4 .select2-results__option[aria-selected="true"] {
            background-color: #007bff !important;
            color: #ffffff !important;
        }

        /* Opción resaltada con el mouse */
        .dark-mode .select2-container--bootstrap4 .select2-results__option--highlighted {
            background-color: #007bff !important;
            color: #ffffff !important;
        }

        .bg-rojo-personalizado {
            background-color: #b81414;
            color: white;
        }

        .bg-amarillo-personalizado {
            background-color: #fff475;
            color: black;
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

            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-9 col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="text-justify font-weight-bold ">Avisos Relevantes en SDO</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="post" action="index.php" class="text-right">
                                    <button type="submit" class="btn btn-danger btn-block">
                                        <i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atrás
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                if (isset($_GET['mensajeVar']) && isset($_SESSION['varRespuesta'])) {
                    $varRespuesta = $_SESSION['varRespuesta'];

                    if (!empty($varRespuesta)) {

                        ?>
                        <script>
                            Swal.fire(
                                '<?php echo addslashes($varRespuesta); ?>', // Asegúrate de escapar las comillas y caracteres especiales
                                '',
                                'info'
                            );
                        </script>
                        <?php
                    }

                    $_SESSION['varRespuesta'] = '';
                }
                ?>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row justify-content-center">
                                    <div class="col-md-3 col-12 mb-3">
                                        <form style="margin: 0; text-align: center;" class="" id="formAgregarAviso" action="../sdo-funciones/avisos_relevantes_agregar.php" method="post" enctype="multipart/form-data">

                                            <button type="button" data-toggle="modal" data-target="#modalAgregarAviso" id="agregarAviso" class="btn bg-gradient-success btn-block">
                                                <i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Aviso
                                            </button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="modalAgregarAviso" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-success">
                                                            <h5 class="modal-title" id="exampleModalLabel">Agregar Aviso Relevante</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-12 ml-auto">
                                                                    <div style="text-align:left;" class="form-group">
                                                                        <label for="tituloAviso">Titulo de Aviso...<code>*</code></label>
                                                                        <input type="text" name="tituloAviso" id="tituloAviso" class="form-control" required placeholder="Titulo del Aviso...">
                                                                    </div>
                                                                    <br>
                                                                    <div style="text-align:left;" class="form-group">
                                                                        <label for="departamentoAviso">Departamento que corresponde...<code>*</code></label>
                                                                        <select class="form-control listDepartamentoAviso" name="departamentoAviso" id="departamentoAviso">
                                                                            <option value="0">Seleccione...</option>
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
                                                                    <br>
                                                                    <div style="text-align:left;" class="form-group">
                                                                        <label for="descripcionAviso">Descripci&oacute;n de Aviso...<code>*</code></label>
                                                                        <textarea name="descripcionAviso" id="descripcionAviso" class="form-control" rows="5" style="resize: none" required placeholder="Descripcion del Aviso"></textarea>

                                                                    </div>
                                                                    <br>
                                                                    <div style="text-align:left;" class="form-group">
                                                                        <label for="departamentoAviso">Destinatarios...<code>*</code></label>
                                                                        <select class="form-control listDestinatarioAviso" name="destinatarioAviso[]" id="destinatarioAviso" multiple>
                                                                            <?php
                                                                            $usuariosAvisos = [];

                                                                            //                                                                            $queryUsuariosAvisos = "SELECT jt.nomJefe AS nomEmpleado
                                                                            //                                                                            FROM jefes_tecnicos jt
                                                                            //                                                                            WHERE jt.nomJefe NOT LIKE '%Jaime Amador Estay Jorquera%'
                                                                            //                                                                              AND jt.userActivado = 1
                                                                            //
                                                                            //                                                                            UNION
                                                                            //
                                                                            //                                                                            SELECT p.nomPersonal AS nomEmpleado
                                                                            //                                                                            FROM personal p
                                                                            //                                                                            WHERE p.activoPersonal = 1
                                                                            //
                                                                            //                                                                            UNION
                                                                            //
                                                                            //                                                                            SELECT d.nomDirectivo AS nomEmpleado
                                                                            //                                                                            FROM directivos d
                                                                            //
                                                                            //                                                                            ORDER BY nomEmpleado ASC;
                                                                            //                                                                            ";

                                                                            $queryUsuariosAvisos = "SELECT jt.nomJefe AS nomEmpleado
                                                                            FROM jefes_tecnicos jt
                                                                            WHERE jt.nomJefe NOT LIKE '%Jaime Amador Estay Jorquera%'
                                                                              AND jt.nomJefe NOT LIKE '%Prueba%'
                                                                              AND jt.userActivado = 1
                                                                            
                                                                            UNION
                                                                            
                                                                            SELECT d.nomDirectivo AS nomEmpleado
                                                                            FROM directivos d
                                                                            
                                                                            ORDER BY nomEmpleado ASC;
                                                                            ";
                                                                            $resUsuariosAvisos = mysqli_query($conexion, $queryUsuariosAvisos);

                                                                            // Guardar en array con nombre modificado si aplica
                                                                            while ($rowUsuariosAvisos = $resUsuariosAvisos->fetch_assoc()) {
                                                                                $nomEmpleadoUA = $rowUsuariosAvisos['nomEmpleado'];

                                                                                if ($nomEmpleadoUA == 'Alejandro Cantillano') {
                                                                                    $nomEmpleadoUA = 'Jorge Cantillano';
                                                                                }

                                                                                $usuariosAvisos[] = ['nombre' => $nomEmpleadoUA];
                                                                            }

                                                                            // Ordenar alfabéticamente por el nombre mostrado
                                                                            usort($usuariosAvisos, function ($a, $b) {
                                                                                return strcmp($a['nombre'], $b['nombre']);
                                                                            });

                                                                            foreach ($usuariosAvisos as $ua) {
                                                                                ?>
                                                                                <option value="<?php echo htmlspecialchars($ua['nombre']) ?>"><?php echo htmlspecialchars($ua['nombre']) ?></option>

                                                                                <?php
                                                                            } ?>
                                                                        </select>
                                                                    </div>
                                                                    <br>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary" id="btnSubir">
                                                                <input type="hidden" name="creadorAviso" id="creadorAviso" value="<?php echo $nomUsuario; ?>">
                                                                <span id="btnText"><i class="fas fa-upload"></i>&nbsp;&nbsp;Agregar</span>
                                                                <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                                            </button>
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                                <i class="fas fa-times"></i>&nbsp;&nbsp;Cancelar
                                                            </button>
                                                        </div>

                                                        <script>

                                                            document.querySelector("form").addEventListener("submit", function (event) {

                                                                let errorMensaje = "";


                                                                // Validación de título
                                                                const tituloInput = document.getElementById("tituloAviso");
                                                                if (!tituloInput || tituloInput.value.trim() === "") {
                                                                    errorMensaje = "Debe ingresar un título para el aviso.";
                                                                }

                                                                // Validación de descripción
                                                                const descripcionInput = document.getElementById("descripcionAviso");
                                                                if (!errorMensaje && (!descripcionInput || descripcionInput.value.trim() === "")) {
                                                                    errorMensaje = "Debe ingresar una descripción para el aviso.";
                                                                }

                                                                // Validación de departamento
                                                                const departamentoSelect = document.getElementById('departamentoAviso');
                                                                if (!errorMensaje && departamentoSelect && departamentoSelect.value === "0") {
                                                                    errorMensaje = "Debe seleccionar un departamento válido.";
                                                                }

                                                                // Validación de destinatarios
                                                                const destinatarioSelect = document.getElementById('destinatarioAviso');
                                                                const seleccionados = Array.from(destinatarioSelect?.selectedOptions || []);
                                                                if (!errorMensaje && seleccionados.length === 0) {
                                                                    errorMensaje = "Debe seleccionar al menos un destinatario.";
                                                                }


                                                                if (errorMensaje) {

                                                                    Swal.fire({
                                                                        icon: 'error',
                                                                        title: 'Error al Subir Archivo',
                                                                        text: errorMensaje
                                                                    });
                                                                    event.preventDefault();
                                                                    return;
                                                                }

                                                                // Deshabilitar botón y mostrar spinner
                                                                document.getElementById("btnSubir").disabled = true;
                                                                document.getElementById("btnText").classList.add("d-none");
                                                                document.getElementById("spinner").classList.remove("d-none");
                                                            });
                                                        </script>

                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row justify-content-center align-items-stretch mb-4">

                                    <div class="col-md-4">
                                        <label for="filtroEstado">Filtrar por Estado:</label>
                                        <select id="filtroEstado" class="form-control">
                                            <option value="">Todos</option>
                                            <option value="Activo">Activo</option>
                                            <option value="Finalizado">Finalizado</option>
                                        </select>
                                    </div>
                                </div>
                                <br>
                                <input type="hidden" name="deptoCargarAviso" id="deptoCargarAviso" value="<?php echo $nomDepartamento; ?>">
                                <div id="contenedorAvisos" class="row justify-content-center align-items-stretch">


                                    <?php
                                    $sqlAvisos = "SELECT * FROM avisos_relevantes ORDER BY fechaAviso DESC";
                                    $resAvisos = mysqli_query($conexion, $sqlAvisos);

                                    if (mysqli_num_rows($resAvisos) > 0) {
                                        while ($rowAvisos = $resAvisos->fetch_assoc()) {
                                            $idAviso = $rowAvisos['idAviso'];
                                            $tituloAviso = $rowAvisos['tituloAviso'];
                                            $descripcionAviso = $rowAvisos['descripcionAviso'];
                                            $fechaAviso = $rowAvisos['fechaAviso'];
                                            $departamentoAviso = $rowAvisos['departamentoAviso'];
                                            $destinatarioAviso = $rowAvisos['destinatarioAviso'];
                                            $creadorAviso = $rowAvisos['creadorAviso'];

                                            $fechaTime = strtotime($fechaAviso);
                                            $fechaFormato = fechaEspañol2($fechaTime);

                                            $horaFormato = date('H:i', $fechaTime);

                                            $fechaCompleta = $fechaFormato . ' a las ' . $horaFormato . ' hrs.';

                                            $estado = $rowAvisos['estadoAviso'];

                                            $colorEstado = '';

                                            if ($estado == 'Activo') {
                                                $colorEstado = 'background-color: #fff475; color: #000000;';
                                            } else if ($estado == 'Finalizado') {
                                                $colorEstado = 'background-color: #b81414; color: #ffffff;';
                                            } else {
                                                $colorEstado = 'background-color: #000000; color: #ffffff;';
                                            }

                                            ?>
                                            <div class="col-xl-2 col-lg-3 col-md-4 col-12 mb-3">
                                                <button class="btn w-100 h-100 d-flex flex-column align-items-center justify-start abrirModalAviso"
                                                        style="<?php echo $colorEstado; ?>; box-shadow: 5px 5px 10px rgba(0,0,0,0.3); position: relative;"
                                                        data-idaviso="<?php echo $idAviso; ?>"
                                                        data-toggle="modal"
                                                        data-target="#modalAvisoUnico">

                                                    <?php

                                                    $cantNotificaciones = obtenerCantidadNotificacionesAviso($idAviso, $nomUsuario);

                                                    if($cantNotificaciones > 0){
                                                        $styleSpanNotificaciones = 'background-color: red; color: white;';
                                                    }else{
                                                        $cantNotificaciones = 0;
                                                        $styleSpanNotificaciones = 'background-color: green; color: white;';
                                                    }
                                                    ?>

                                                    <!-- Cuadro con el número en la esquina superior derecha -->
                                                    <span style="position: absolute; top: 5px; right: 5px; font-size: 0.85rem; width: 26px; height: 26px;
                                                            display: flex; align-items: center; justify-content: center; <?php echo $styleSpanNotificaciones; ?>
                                                            border-radius: 50%; z-index: 10;" id="notificacionesAviso<?php echo $idAviso; ?>">
                                                        <?php echo $cantNotificaciones; ?>
                                                    </span>

                                                    <i class="fas fa-thumbtack"></i><br>
                                                    <b><?php echo $fechaCompleta; ?></b><br>
                                                    <b><?php echo $tituloAviso; ?></b>
                                                </button>
                                            </div>


                                            <?php
                                        }
                                    } else {
                                        echo '<div class="col-12 text-center"><h4>No Existen Avisos Relevantes</h4></div>';
                                    }
                                    ?>


                                </div>
                                <input type="hidden" name="filtroActualEstado" id="filtroActualEstado" value="">
                                <div class="modal fade" id="modalAvisoUnico" tabindex="-1" role="dialog" aria-labelledby="modalAvisoLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-amarillo-personalizado" id="modalAvisosHeader">
                                                <h5 class="modal-title" id="modalVerAvisoLabel"></h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body" id="contenidoAviso">
                                                <div class="row" id="infoAviso">
                                                    <div class="col-md-8 col-12">
                                                        <div class="table-responsive">
                                                            <table style="font-size: 15px;" class="table table-borderless table-sm">
                                                                <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <b>Descripción:</b>
                                                                    </td>
                                                                    <td id="descripcionVerAviso"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <b>Fecha:</b>
                                                                    </td>
                                                                    <td id="fechaVerAviso"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <b>Departamento:</b>
                                                                    </td>
                                                                    <td id="departamentoVerAviso"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <b>Creador:</b>
                                                                    </td>
                                                                    <td id="creadorVerAviso"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <b>Estado:</b>
                                                                    </td>
                                                                    <td id="estadoVerAviso"></td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="idAvisoFinalizado" id="idAvisoFinalizado">

                                                    <div class="col-md-4 col-12" id="infoExtra">
                                                        <!-- Aquí puedes agregar más info o imágenes -->

                                                        <button class="btn btn-block bg-danger finalizarAviso" id="btnFinalizarAviso">
                                                            <i class="fas fa-times"></i>&nbsp;&nbsp;Finalizar<br>
                                                        </button>
                                                    </div>
                                                </div>
                                                <hr>

                                                <div class="col-12">
                                                    <div class="card">
                                                        <div style="font-size: 13px;" class="card-header bg-secondary d-flex p-0">
                                                            <ul class="nav nav-pills mr-auto ml-auto p-2">
                                                                <li class="nav-item">
                                                                    <a class="nav-link text-white active" href="#tabDestinatarios" data-toggle="tab">Destinatarios</a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link text-white " href="#tabObservaciones" data-toggle="tab">Observaciones</a>
                                                                </li>
                                                            </ul>
                                                        </div>


                                                        <div class="card-body">
                                                            <div class="tab-content">
                                                                <div class="tab-pane active" id="tabDestinatarios">




















                                                                    <div id="tablaDestinatarios"></div>
                                                                </div>

                                                                <div class="tab-pane" id="tabObservaciones">
                                                                    <div class="row" id="infoObservAviso">
                                                                        <div class="col-12" id="infoObserva">
                                                                            <!-- Grupo para el switch -->
                                                                            <div class="form-group">
                                                                                <label for="visualObservacion">¿Observación Pública o Privada?
                                                                                    <code>*</code></label>
                                                                                <br>
                                                                                <input type="checkbox" checked data-bootstrap-switch
                                                                                        data-on-text="Pública" data-off-text="Privada"
                                                                                        name="visualObservacion" id="visualObservacion"
                                                                                        data-on-color="success" data-off-color="danger">
                                                                            </div>

                                                                            <!-- Grupo para el textarea -->
                                                                            <div class="form-group">
                                                                                <label for="agregarObsAvisoLabel">Agregar Observación
                                                                                    <code>*</code></label>
                                                                                <textarea name="agregarObsAvisoLabel" id="agregarObsAvisoLabel"
                                                                                        class="form-control" rows="2" style="resize: none" required
                                                                                        placeholder="Observación..."></textarea>
                                                                            </div>

                                                                            <!-- Input oculto para el nombre del creador -->
                                                                            <input type="hidden" name="nomCreadorObs" value="<?php echo $nomUsuario; ?>" id="nomCreadorObs">

                                                                            <!-- Botón -->
                                                                            <button class="btn btn-success btn-block agregarObsAviso" id="btnAgregarObsAviso">
                                                                                <i class="fas fa-plus"></i>&nbsp;&nbsp;Agregar
                                                                            </button>
                                                                        </div>

                                                                    </div>
                                                                    <hr>
                                                                    <br>

                                                                    <div id="tablaObservaciones"></div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
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
        <strong>Copyright &copy; 2022 - ∞. Derechos Reservados</strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

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

<!-- Bootstrap Switch JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-switch@3.3.4/dist/js/bootstrap-switch.min.js"></script>
<!-- Select2 -->
<script src="../plugins/select2/js/select2.full.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>

<script src="js/dark-mode.js"></script>


<script>
    $(document).ready(function () {

        $('.listDepartamentoAviso').select2({
            placeholder: 'Seleccione',
            theme: 'bootstrap4',
            allowClear: true,
            width: '100%',

        });

        $('.listDestinatarioAviso').select2({
            placeholder: 'Seleccione',
            theme: 'bootstrap4',
            allowClear: true,
            width: '100%',

        });







    });

    $(document).ready(function () {
        $("input[data-bootstrap-switch]").each(function () {
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        });
    });
</script>


<script>
    $(document).ready(function () {

        const nomUsuario = "<?php echo $nomUsuario; ?>";
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("href");

            if (target === "#tabObservaciones" && idAvisoActual) {
                asignarLeerNotificaciones(idAvisoActual, nomUsuario);
            }
        });

        inicializarEventosAvisos();


    });

    function inicializarEventosAvisos() {

        // Cargar automáticamente al cambiar estado
        $('#filtroEstado').on('change', function () {
            const estado = $(this).val();

            document.getElementById("filtroActualEstado").value = estado;

            $('#contenedorAvisos').html('<div class="col-12 text-center"><i class="fas fa-spinner fa-spin"></i> Cargando avisos...</div>');

            $.ajax({
                url: 'sdo-includes/avisos_filtrados.php',
                type: 'POST',
                data: {estado: estado},
                success: function (response) {
                    $('#contenedorAvisos').html(response);
                },
                error: function () {
                    $('#contenedorAvisos').html('<div class="col-12 text-danger text-center">❌ Error al cargar avisos.</div>');
                }
            });
        });

        // Delegación para los botones que abren modales
        $(document).on('click', '.abrirModalAviso', function () {
            const idAviso = $(this).data('idaviso');
            $('#modalAvisoUnico').modal('show');
            cargarDatosAviso(idAviso);
        });
    }

    function cargarAvisosPorEstado(estado) {
        // Actualizar campo oculto u otro elemento si es necesario
        document.getElementById("filtroActualEstado").value = estado;

        // Mostrar spinner de carga
        $('#contenedorAvisos').html('<div class="col-12 text-center"><i class="fas fa-spinner fa-spin"></i> Cargando avisos...</div>');

        // Llamada AJAX para obtener los avisos
        $.ajax({
            url: 'sdo-includes/avisos_filtrados.php',
            type: 'POST',
            data: {estado: estado},
            success: function (response) {
                $('#contenedorAvisos').html(response);
            },
            error: function () {
                $('#contenedorAvisos').html('<div class="col-12 text-danger text-center">❌ Error al cargar avisos.</div>');
            }
        });
    }

    function asignarLeerNotificaciones(idAviso, nomUsuario) {

        $.ajax({
            url: '/sdo-includes/avisos_leer_notificaciones.php',
            method: 'POST',
            data: {
                idAviso: idAviso,
                nomUsuario: nomUsuario
            },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'ok') {
                    console.log('🔔 Notificaciones marcadas como leídas.');
                    const spanNotificacion = document.getElementById(`notificacionesAviso${idAviso}`);
                    if (spanNotificacion) {
                        // Cambiar el número
                        spanNotificacion.textContent = '0';

                        // Cambiar los estilos directamente
                        spanNotificacion.style.backgroundColor = 'green';
                        spanNotificacion.style.color = 'white';

                        // O bien si estás usando clases tipo 'badge-danger', puedes hacer:
                        // spanNotificacion.classList.remove('badge-danger');
                        // spanNotificacion.classList.add('badge-success');
                    }
                } else {
                    console.warn('❗ Error al marcar notificaciones:', response.mensaje);
                }
            },
            error: function (xhr, status, error) {
                console.error('❌ Error AJAX:', error);
            }
        });
    }


    function cargarDatosAviso(idAviso) {
        // Mostrar contenido de carga
        $('#modalAvisoLabel').text('Cargando...');

        const header = document.getElementById('modalAvisosHeader');

        const btnFinalizarAviso = document.getElementById('btnFinalizarAviso');

        idAvisoActual = idAviso;

        const nomDepartamento1 = document.getElementById('deptoCargarAviso').value;

        console.log(nomDepartamento1);

        $.ajax({
            url: 'sdo-includes/avisos_cargar_datos.php',
            type: 'POST',
            data: {idAviso: idAviso, nomDepartamento: nomDepartamento1},
            dataType: 'json',
            success: function (data) {
                $('#modalVerAvisoLabel').text(data.titulo);
                $('#descripcionVerAviso').html(data.descripcion);
                $('#fechaVerAviso').text(data.fecha);
                $('#departamentoVerAviso').text(data.departamento);
                $('#creadorVerAviso').text(data.creador);
                $('#estadoVerAviso').text(data.estado);

                if (data.estado == 'Finalizado') {

                    document.getElementById("infoObserva").style.display = "none";

                    header.classList.remove('bg-rojo-personalizado', 'bg-amarillo-personalizado');

                    header.classList.add('bg-rojo-personalizado'); // o 'bg-amarillo-personalizado'

                    document.getElementById('btnFinalizarAviso').style.display = 'none';

                    document.getElementById('btnFinalizarAviso').dataset.idaviso = '';


                } else {

                    document.getElementById("infoObserva").style.display = "block";

                    header.classList.remove('bg-rojo-personalizado', 'bg-amarillo-personalizado');

                    header.classList.add('bg-amarillo-personalizado'); // o 'bg-amarillo-personalizado'

                    document.getElementById('btnFinalizarAviso').style.display = 'none';

                    document.getElementById('btnFinalizarAviso').dataset.idaviso = idAviso;
                }

                $.ajax({
                    url: 'sdo-includes/avisos_usuarios_disponibles.php',
                    type: 'POST',
                    data: {idAviso: idAviso},
                    dataType: 'json',
                    success: function (usuarios) {
                        const $select = $('#agregarDestinatarioAviso');

                        // Asegura que siempre empiece limpio
                        $select.prop('disabled', true).empty();

                        if (usuarios.length === 0) {
                            $select.append('<option disabled selected>No hay destinatarios disponibles</option>');
                            $('#btnAgregarDestinatarioAviso').prop('disabled', true); // Desactiva botón si está vacío
                        } else {
                            usuarios.forEach(nombre => {
                                $select.append(`<option value="${nombre}">${nombre}</option>`);
                            });

                            $('#btnAgregarDestinatarioAviso').prop('disabled', false); // Reactiva botón si hay disponibles
                        }

                        $select.prop('disabled', false).val(null).trigger('change');
                    }
                });

                $('#btnAgregarDestinatarioAviso').attr('data-idaviso', idAviso);


                $('#btnAgregarObsAviso').attr('data-idaviso', idAviso);

// Opcional: tabla de destinatarios
                let tabla = '';

                if (data.destinatarios && data.destinatarios.length > 0) {
                    tabla += `<table id="tablaFinalDestinatarios" class="table table-bordered table-sm mt-3 text-center align-items-center table-valign-middle">
                <thead class="bg-primary">
                    <tr>
                        <th>Destinatario</th>


                    </tr>
                </thead>
                <tbody>`;
                    data.destinatarios.forEach(det => {
                        tabla += `<tr>
                    <td>${det.nomDestinatario}</td>






                </tr>`;
                    });
                    tabla += `</tbody></table>`;
                } else {
                    tabla = `<p>No hay destinatarios registrados.</p>`;
                }

                $('#tablaDestinatarios').html(tabla);


// Opcional: tabla de observaciones
                let tablaObserv = '';

                if (data.observaciones && data.observaciones.length > 0) {
                    tablaObserv += `<table id="tablaFinalObservaciones" class="table table-bordered table-sm mt-3 text-center align-items-center table-valign-middle">
                <thead class="bg-primary">
                    <tr>
                        <th>Fecha</th>
                        <th>Creador</th>
                        <th>Detalle Observacion</th>


                    </tr>
                </thead>
                <tbody>`;
                    data.observaciones.forEach(obs => {
                        tablaObserv += `<tr>
                    <td>${obs.fechaObservacion}</td>
                    <td>${obs.creadorObservacion}</td>
                    <td>${obs.detalleObservacion.replace(/\n/g, '<br>')}</td>






                </tr>`;
                    });
                    tablaObserv += `</tbody></table>`;


                } else {
                    tablaObserv = `<p>No hay observaciones.</p>`;
                }

                $('#tablaObservaciones').html(tablaObserv);


                asignarEventoEliminar(); // importante si usas SweetAlert2 para eliminar
                agregarDestinatario();
                agregarObservacion();
                asignarEliminarObs();
                // asignarFinalizarAviso();


// Inicializar de nuevo
                $('#tablaFinalDestinatarios').DataTable({
                    pageLength: 10, // ← muestra solo 10 por página
                    paging: true,
                    lengthChange: false,
                    searching: true,
                    ordering: false,
                    info: false,
                    autoWidth: false,
                    responsive: true,
                    language: {
                        search: "Buscar:",
                        lengthMenu: "Visualizando _MENU_ Registros por Página",
                        zeroRecords: "No se han encontrado Registros.",
                        info: "Mostrando página _PAGE_ de _PAGES_",
                        infoEmpty: "No existen Registros.",
                        infoFiltered: "(Filtrado desde _MAX_ Registros Totales)",
                        paginate: {
                            previous: "Anterior",
                            next: "Siguiente"
                        }
                    }
                });

// Inicializar de nuevo
                $('#tablaFinalObservaciones').DataTable({
                    pageLength: 10, // ← muestra solo 10 por página
                    paging: true,
                    lengthChange: false,
                    searching: true,
                    ordering: false,
                    info: false,
                    autoWidth: false,
                    responsive: true,
                    language: {
                        search: "Buscar:",
                        lengthMenu: "Visualizando _MENU_ Registros por Página",
                        zeroRecords: "No se han encontrado Registros.",
                        info: "Mostrando página _PAGE_ de _PAGES_",
                        infoEmpty: "No existen Registros.",
                        infoFiltered: "(Filtrado desde _MAX_ Registros Totales)",
                        paginate: {
                            previous: "Anterior",
                            next: "Siguiente"
                        }
                    }
                });
            },
            error: function (xhr, status, error) {
                $('#contenidoAviso').html('<p class="text-danger">❌ Error al cargar los datos del aviso.</p>');
                $('#modalAvisoLabel').text('Error');
            }
        });
    }

    function asignarEventoEliminar() {
        $('.eliminarDestinatario').on('click', function () {
            const idDestinatario = $(this).data('iddestinatario');
            const idAviso = $(this).data('idaviso'); // nuevo

            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'sdo-includes/avisos_eliminar_destinatario.php',
                        type: 'POST',
                        data: {idDestinatario: idDestinatario},
                        success: function (respuesta) {
                            if (respuesta === 'ok') {
                                Swal.fire(
                                    '¡Eliminado!',
                                    'El destinatario ha sido eliminado.',
                                    'success'
                                ).then(() => {
                                    cargarDatosAviso(idAviso); // recargar directamente
                                });
                            } else {
                                Swal.fire(
                                    'Error',
                                    respuesta,
                                    'error'
                                );
                            }
                        },
                        error: function () {
                            Swal.fire(
                                'Error',
                                '❌ Error en la solicitud AJAX.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    }

    function asignarEliminarObs() {
        $('.eliminarObserv').on('click', function () {
            const idObservacion = $(this).data('idobs');
            const idAviso = $(this).data('idaviso'); // nuevo

            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'sdo-includes/avisos_eliminar_observacion.php',
                        type: 'POST',
                        data: {idObservacion: idObservacion},
                        success: function (respuesta) {
                            if (respuesta === 'ok') {
                                Swal.fire(
                                    '¡Exito!',
                                    'Observación Eliminada.',
                                    'success'
                                ).then(() => {
                                    cargarDatosAviso(idAviso); // recargar directamente
                                });
                            } else {
                                Swal.fire(
                                    'Error',
                                    respuesta,
                                    'error'
                                );
                            }
                        },
                        error: function () {
                            Swal.fire(
                                'Error',
                                '❌ Error en la solicitud AJAX.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    }

    function agregarDestinatario() {
        $(document).off('click', '#btnAgregarDestinatarioAviso').on('click', '#btnAgregarDestinatarioAviso', function () {
            const idAviso = $(this).data('idaviso');
            const destinatarios = $('#agregarDestinatarioAviso').val();

            if (!destinatarios || destinatarios.length === 0) {
                Swal.fire('Atención', 'Debes seleccionar al menos un destinatario.', 'warning');
                return;
            }

            $.ajax({
                url: 'sdo-includes/avisos_agregar_destinatario.php',
                type: 'POST',
                data: {
                    idAviso: idAviso,
                    destinatarios: destinatarios
                },
                success: function (respuesta) {
                    if (respuesta === 'ok') {
                        Swal.fire('¡Agregado!', 'Los destinatarios fueron añadidos correctamente.', 'success').then(() => {
                            $('#agregarDestinatarioAviso').val(null).trigger('change');
                            cargarDatosAviso(idAviso);
                        });
                    } else {
                        Swal.fire('Error', 'No se pudieron agregar los destinatarios.', 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'Error en la solicitud al servidor.', 'error');
                }
            });
        });

    }

    function agregarObservacion() {

        $(document).off('click', '#btnAgregarObsAviso').on('click', '#btnAgregarObsAviso', function () {
            const idAviso = $(this).data('idaviso');
            const observacion = $('#agregarObsAvisoLabel').val();
            let esPublica = $('#visualObservacion').prop('checked') ? 'Publica' : 'Privada';

            // alert(esPublica);

            const creador = $('#nomCreadorObs').val();

            if (!observacion) {
                Swal.fire('Atención', 'Debes ingresar una observación.', 'warning');
                return;
            }

            if (!creador) {
                Swal.fire('Atención', 'Error al Obtener Creador.', 'warning');
                return;
            }

            $.ajax({
                url: 'sdo-includes/avisos_agregar_observacion.php',
                type: 'POST',
                data: {
                    idAviso: idAviso,
                    observacion: observacion,
                    visibilidad: esPublica,
                    creador: creador,
                },
                success: function (respuesta) {
                    if (respuesta === 'ok') {
                        Swal.fire('¡Agregado!', 'Observación Agregada.', 'success').then(() => {
                            $('#agregarObsAvisoLabel').val('');
                            cargarDatosAviso(idAviso);
                        });
                    } else {
                        Swal.fire('Error', 'Error al Agregar Observacion.', 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'Error en la solicitud al servidor.', 'error');
                }
            });
        });

    }




</script>

</body>


</html>