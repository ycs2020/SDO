<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['idTareaP'])) {
    $idTareaP = trim($_POST['idTareaP']);
} else if (isset($_SESSION['idTareaP'])) {
    $idTareaP = trim($_SESSION['idTareaP']);
} else {
    header('Location: planificador_administracion.php?errorCargaPlan=true');
}

if ($idTareaP > 0) {



    $dataTP = array();
    $dataTP = getTareaPlan($idTareaP);

    $idTareaP2 = $dataTP['idTareaP'];
    $actividadTareaP = $dataTP['actividadTareaP'];
    $inicioPlanTareaP = $dataTP['inicioPlanTareaP'];
    $finPlanTareaP = $dataTP['finPlanTareaP'];
    $diasPlanTareaP = $dataTP['diasPlanTareaP'];
    $tipoDiasHabil = $dataTP['tipoDiasHabil'];
    $inicioRealTareaP = $dataTP['inicioRealTareaP'];
    $finRealTareaP = $dataTP['finRealTareaP'];
    $porcentajeTareaP = $dataTP['porcentajeTareaP'];

    $contarTareasHijas = contarSubTareaP($idTareaP);

    if($contarTareasHijas > 0){

        $porcentajeTareaP = actualizarPorcentajeTareas($idTareaP);

    }

    $idPadreTareaP = $dataTP['idPadreTareaP'];
    $iniciadaTareaP = $dataTP['iniciadaTareaP'];
    $finalizadaTareaP = $dataTP['finalizadaTareaP'];


    $inicioPlanTareaP2 = strtotime($inicioPlanTareaP);
    $inicioPlanTareaP2 = fechaEspañol2($inicioPlanTareaP2);
    $horaInicioPlanTareaP = date('H:i', strtotime($inicioPlanTareaP));

    $finPlanTareaP2 = strtotime($finPlanTareaP);
    $finPlanTareaP2 = fechaEspañol2($finPlanTareaP2);
    $horaFinTareaTareaP = date('H:i', strtotime($finPlanTareaP));

    if (empty($inicioRealTareaP)) {
        $inicioRealTareaP2 = 'No Iniciado';
        $finRealTareaP2 = 'No Iniciado';
    } else {

        $inicioRealTareaP2 = strtotime($inicioRealTareaP);
        $inicioRealTareaP2 = fechaEspañol2($inicioRealTareaP2);
        $horaInicioRealTareaP = date('H:i', strtotime($inicioRealTareaP));

        if (empty($finRealTareaP)) {
            $finRealTareaP2 = 'No Finalizado';
        } else {

            $finRealTareaP2 = strtotime($finRealTareaP);
            $finRealTareaP2 = fechaEspañol2($finRealTareaP2);
            $horaFinRealTareaP = date('H:i', strtotime($finRealTareaP));
        }
    }


    $_SESSION['idTareaP'] = $idTareaP;

    $idPadreTareaPVolver = tienePadreTarea($idTareaP);


    $obtenerIDPadreTarea = obtenerIDPadreTarea($idTareaP);
    $countIDPadre = 1;

    if ($obtenerIDPadreTarea > 0) {
        do {
            $obtenerIDPadreTarea = obtenerIDPadreTarea($obtenerIDPadreTarea);
            $countIDPadre++;
        } while ($obtenerIDPadreTarea > 0);
    }
} else {
    header('Location: planificador_administracion.php?errorCargaPlan=true');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalle Planificaci&oacute;n</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- AdminLTE css -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<!-- Site wrapper -->
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
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Detalle Planificaci&oacute;n</h4>
                    </div>

                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- MENSAJES DE ACCIONES -->

        <?php



        if (isset($_GET['exitoIniciarNoPadre'])) {
            ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Exito!',
                    text: 'Tarea Iniciada.',
                })
            </script>

            <?php
        }


        if (isset($_GET['exitoIniciar'])) {
            ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Exito!',
                    text: 'Tarea Iniciada.',
                })
            </script>

            <?php
        }


        if (isset($_GET['errorIniciar'])) {
            ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: '¡ Ha ocurrido un Problema !',
                    text: 'Error al Iniciar Tarea.',
                })
            </script>

            <?php
        }

        if (isset($_GET['exitoModPorcentaje'])) {
            ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Exito!',
                    text: 'Porcentaje Modificado.',
                })
            </script>

            <?php
        }

        if (isset($_GET['exitoModPorcentajeSinObservacion'])) {
            ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Exito!',
                    text: 'Porcentaje Modificado Sin Observacion.',
                })
            </script>

            <?php
        }

        if (isset($_GET['errorModPorcentaje'])) {
            ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: '¡ Ha ocurrido un error !',
                    text: 'Error al Modificar el Porcentaje. Reintente.',
                })
            </script>

            <?php
        }

        if (isset($_GET['errorGenerarCG'])) {
            ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: '¡ Ha ocurrido un error !',
                    text: 'Error al Generar Carta Gantt. Reintente.',
                })
            </script>

            <?php
        }

        ?>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <!-- Timelime example  -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">

                                <?php
                                if ($idPadreTareaPVolver > 0) {
                                    ?>
                                    <form style="margin: 0; text-align: right;" class="" action="planificador_detalle.php" method="post">

                                        <input type="hidden" name="idTareaP" value="<?php echo $idPadreTareaPVolver; ?>">
                                        <button type="submit" id="inputMozi" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</button>

                                    </form>
                                    <?php
                                } else {
                                    ?>
                                    <a href="planificador_administracion.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <?php
                                }
                                ?>


                            </div>
                            <div style="font-size:85%" class="card-body">
                                <div class="row">
                                    <div style="font-size:85%" class="col-md-9">

                                        <h5 style="font-size:150%"><b>Visualizando Planificaci&oacute;n: </b><?php echo $actividadTareaP; ?></h5>
                                        <br>
                                        <h5 style="font-size:150%"><b>Fecha Inicio Planificada: </b><?php echo $inicioPlanTareaP2; ?></h5>
                                        <h5 style="font-size:150%"><b>Fecha Termino Planificada: </b><?php echo $finPlanTareaP2; ?></h5>
                                        <br>
                                        <h5 style="font-size:150%"><b>Fecha Inicio Real: </b><?php echo $inicioRealTareaP2; ?></h5>
                                        <h5 style="font-size:150%"><b>Fecha Termino Real: </b><?php echo $finRealTareaP2; ?></h5>
                                        <br>
                                        <h5 style="font-size:150%"><b>Porcentaje Trabajo: </b>
                                            <?php
                                            $porcentajeTareaP2 = round($porcentajeTareaP, 2);

                                            echo $porcentajeTareaP2;
                                            ?>
                                        </h5>

                                        <br>
                                    </div>

                                    <div style="font-size:85%;" class="col-md-3">

                                        <div class="col-md-12">

                                            <?php
                                            if ($idTareaP > 0) {

                                                if ($iniciadaTareaP == 0) {
                                                    //AQUI VA EL INICIO DE LA PLANIFICACION
                                                    ?>

                                                    <form style="margin: 0; text-align: center;" class="" action="../sdo-funciones/planificacion_iniciar.php" method="post">
                                                        <button type="button" data-toggle="modal" data-target="#inicioModPlan" id="inputMozi" class="btn btn-block bg-primary" style="float:right;"><i class="fa fa-pen"></i>&nbsp;&nbsp;Iniciar Planificaci&oacute;n</button>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="inicioModPlan" name="inicioModPlan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-primary">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Inicio Planificaci&oacute;n</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-md-12 ml-auto">
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="finPlanTareaPMod">Fecha Inicio - Planificaci&oacute;n: <code>*</code></label>
                                                                                    <input type="date" name="inicioPlanTareaPMod" id="inicioPlanTareaPMod" placeholder="Fecha Inicio - Planificaci&oacute;n" class="form-control" value="" required />

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">



                                                                        <input type="hidden" name="idTareaP" value="<?php echo $idTareaP; ?>">
                                                                        <input type="hidden" name="textoTareaP" value="Iniciar">
                                                                        <input type="hidden" name="nomInicia" id="nomInicia" value="<?php echo $nomUsuario; ?>">
                                                                        <input type="hidden" name="deptoInicia" id="deptoInicia" value="<?php echo getIDControlCentra(); ?>">
                                                                        <button type="submit" id="inputMozi" class="btn btn-primary"><i class="fa fa-play"></i>&nbsp;&nbsp;Iniciar Planificaci&oacute;n</button>
                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </form>



                                                    <br>
                                                    &nbsp;
                                                    <?php
                                                } else if ($iniciadaTareaP == 1) {
                                                    if ($finalizadaTareaP == 0) {
                                                        //AQUI VA EL FIN DE LA PLANIFICACION
                                                        ?>

                                                        <form style="margin: 0; text-align: center;" class="" action="../sdo-funciones/planificacion_finalizar.php" method="post">
                                                            <button type="button" data-toggle="modal" data-target="#finalizarModPlan" id="inputMozi" class="btn btn-block bg-purple" style="float:right;"><i class="fa fa-pen"></i>&nbsp;&nbsp;Finalizar Planificaci&oacute;n</button>

                                                            <!-- Modal -->
                                                            <div class="modal fade" id="finalizarModPlan" name="finalizarModPlan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header bg-primary">
                                                                            <h5 class="modal-title" id="exampleModalLabel">Finalizar Planificaci&oacute;n</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>

                                                                        <div class="modal-body">
                                                                            <div class="row">
                                                                                <div class="col-md-12 ml-auto">
                                                                                    <div style="text-align:left;" class="form-group">
                                                                                        <label for="finPlanTareaPMod">Fecha Termino - Planificaci&oacute;n: <code>*</code></label>
                                                                                        <input type="date" name="finPlanTareaPMod" min="<?php echo getInicioPadreTareaMin($idTareaP); ?>" max="<?php echo getTerminoTareaMax($idPadreTareaP); ?>" id="finPlanTareaPMod" placeholder="Fecha Termino - Planificaci&oacute;n" class="form-control" value="<?php echo $finPlanTareaP ?>" required />

                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">

                                                                            <input type="hidden" name="idTareaP" value="<?php echo $idTareaP; ?>">
                                                                            <input type="hidden" name="textoTareaP" value="Finalizar">
                                                                            <input type="hidden" name="nomInicia" id="nomInicia" value="<?php echo $nomUsuario; ?>">
                                                                            <input type="hidden" name="deptoInicia" id="deptoInicia" value="<?php echo getIDControlCentra(); ?>">
                                                                            <button type="submit" id="inputMozi" class="btn bg-purple"><i class="fa fa-check"></i>&nbsp;&nbsp;Finalizar Planificaci&oacute;n</button>
                                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </form>

                                                        <br>
                                                        &nbsp;
                                                        <?php
                                                    } else {
                                                        //AQUI VA PORCENTAJE
                                                    }
                                                }

                                                ?>


                                                <form method="post" style="margin: 0; text-align: center;" action="../sdo-funciones/planificacion_modificarTarea.php">
                                                    <button type="button" data-toggle="modal" data-target="#modalModPlan" id="inputMozi" class="btn btn-block btn-warning" style="float:right;"><i class="fa fa-pen"></i>&nbsp;&nbsp;Modificar Planificaci&oacute;n</button>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="modalModPlan" name="modalModPlan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-primary">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Modificar Planificaci&oacute;n</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <div style="text-align:left;" class="form-group">
                                                                        <label for="actividadTareaPMod">Detalle de Tarea: <code>*</code></label>
                                                                        <textarea id="actividadTareaPMod" style="resize:none;" name="actividadTareaPMod" required class="form-control" rows="3" placeholder="Actividad..."><?php echo $actividadTareaP; ?></textarea>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-md-4 ml-auto">
                                                                            <div style="text-align:left;" class="form-group">
                                                                                <label for="inicioPlanTareaPMod">Fecha Inicio - Planificaci&oacute;n: <code>*</code></label>
                                                                                <?php
                                                                                if ($idPadreTareaP > 0) {
                                                                                    ?>
                                                                                    <input type="date" name="inicioPlanTareaPMod" min="<?php echo getInicioPadreTareaMin($idTareaP); ?>" max="<?php echo getTerminoPadreTareaMax($idTareaP); ?>" id="inicioPlanTareaP" placeholder="Fecha Inicio - Planificaci&oacute;n" class="form-control" value="<?php echo $inicioPlanTareaP ?>" required />

                                                                                    <?php
                                                                                } else {
                                                                                    ?>
                                                                                    <input type="date" name="inicioPlanTareaPMod" max="<?php echo getInicioPadreTareaMax($idTareaP); ?>" id="inicioPlanTareaPMod" placeholder="Fecha Inicio - Planificaci&oacute;n" class="form-control" value="<?php echo $inicioPlanTareaP ?>" required />

                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4 ml-auto">
                                                                            <div style="text-align:left;" class="form-group">
                                                                                <label for="finPlanTareaPMod">Fecha Termino - Planificaci&oacute;n: <code>*</code></label>
                                                                                <?php
                                                                                if ($idPadreTareaP > 0) {
                                                                                    ?>
                                                                                    <input type="date" name="finPlanTareaPMod" min="<?php echo getInicioPadreTareaMin($idTareaP); ?>" max="<?php echo getTerminoTareaMax($idPadreTareaP); ?>" id="finPlanTareaPMod" placeholder="Fecha Termino - Planificaci&oacute;n" class="form-control" value="<?php echo $finPlanTareaP ?>" required />

                                                                                    <?php
                                                                                } else {
                                                                                    ?>
                                                                                    <input type="date" name="finPlanTareaPMod" min="<?php echo getTerminoPadreTareaMax($idTareaP); ?>" id="finPlanTareaPMod" placeholder="Fecha Termino - Planificaci&oacute;n" class="form-control" value="<?php echo $finPlanTareaP ?>" required />

                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4 ml-auto">
                                                                            <div style="text-align:left;" class="form-group">
                                                                                <label for="tipoDiasHabilMod">Tipo de Días: <code>*</code></label>
                                                                                <select name="tipoDiasHabilMod" id="tipoDiasHabilMod" class="form-control">
                                                                                    <option value="Seleccione...">Seleccione...</option>
                                                                                    <?php
                                                                                    if ($tipoDiasHabil == 'Habiles') {
                                                                                        ?>
                                                                                        <option value="Habiles" selected>Habiles</option>
                                                                                        <option value="Inhabiles">Inhabiles</option>
                                                                                        <?php
                                                                                    } else if ($tipoDiasHabil == 'Inhabiles') {
                                                                                        ?>
                                                                                        <option value="Habiles">Habiles</option>
                                                                                        <option value="Inhabiles" selected>Inhabiles</option>
                                                                                        <?php
                                                                                    } else {
                                                                                        ?>
                                                                                        <option value="Habiles">Habiles</option>
                                                                                        <option value="Inhabiles">Inhabiles</option>
                                                                                        <?php
                                                                                    }

                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="idTareaPMod" id="idTareaPMod" value="<?php echo $idTareaP; ?>">
                                                                    <input type="hidden" name="nomCreadorTareaPMod" id="nomCreadorTareaPMod" value="<?php echo $nomUsuario; ?>">
                                                                    <input type="hidden" name="deptoCreadorTareaPMod" id="deptoCreadorTareaPMod" value="<?php echo getIDControlCentra(); ?>">
                                                                    <button type="submit" class="btn btn-primary">Modificar</button>
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>

                                                &nbsp;

                                                <form method="post" style="margin: 0; text-align: center;" action="../sdo-funciones/planificacion_modificarPorcentaje.php">
                                                    <button type="button" data-toggle="modal" data-target="#modalEditPorcent" id="inputMozi" class="btn btn-block bg-gray" style="float:right;"><i class="fa fa-pen"></i>&nbsp;&nbsp;Modificar Porcentaje</button>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="modalEditPorcent" name="modalEditPorcent" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-primary">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Modificar Porcentaje</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <div style="text-align:left;" class="form-group">
                                                                        <label for="nuevoPorcentaje">Nuevo Porcentaje: <code>*</code></label>
                                                                        <input type="number" class="form-control" name="nuevoPorcentaje" id="nuevoPorcentaje" min="0" max="100" />
                                                                    </div>

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="idTareaPMod" id="idTareaPMod" value="<?php echo $idTareaP; ?>">
                                                                    <input type="hidden" name="nomCreadorTareaPMod" id="nomCreadorTareaPMod" value="<?php echo $nomUsuario; ?>">
                                                                    <input type="hidden" name="deptoCreadorTareaPMod" id="deptoCreadorTareaPMod" value="<?php echo getIDControlCentra(); ?>">
                                                                    <button type="submit" class="btn btn-primary">Modificar</button>
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>


                                                <?php

                                                if (contarSubTareaP($idTareaP) == 0) {
                                                    ?>
                                                    &nbsp;
                                                    &nbsp;

                                                    <form target="_blank" method="post" action="#">
                                                        <input type="hidden" name="valorCargaGantt" id="valorCargaGantt" value="<?php echo $idTareaP; ?>">
                                                        <button type="submit" disabled class="btn btn-block bg-success"><i class="fas fa-download"></i>&nbsp;&nbsp;Deshabilitado</button>
                                                    </form>
                                                    &nbsp;

                                                    <a class=" btn btn-block btn-danger eliminar" id="<?php echo $idTareaP ?>" href="#"><i class="fas fa-trash-alt"></i>&nbsp;&nbsp;Eliminar</a>


                                                    <br>
                                                    <?php
                                                } else {
                                                    ?>
                                                    &nbsp;
                                                    &nbsp;

                                                    <form target="_blank" method="post" action="../sdo-pdf/SDO-CartaGantt.php">
                                                        <input type="hidden" name="valorCargaGantt" id="valorCargaGantt" value="<?php echo $idTareaP; ?>">
                                                        <input type="hidden" name="idDepartamentoCreador" id="idDepartamentoCreador" value="<?php echo getIDControlCentra(); ?>">
                                                        <button type="submit" class="btn btn-block bg-success"><i class="fas fa-download"></i>&nbsp;&nbsp;Descargar Carta Gantt</button>
                                                    </form>
                                                    &nbsp;

                                                    <a class=" btn btn-block btn-danger" id="error" href="#" style="pointer-events: none; cursor: default; text-decoration:none;"><i class="fas fa-trash-alt"></i>&nbsp;&nbsp;Deshabilitado</a>


                                                    <br>
                                                    <?php
                                                }
                                            }
                                            ?>

                                        </div>
                                    </div>


                                </div>
                                <br>


                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>

                <?php

                if ($countIDPadre < 4) {
                    ?>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <form method="post" action="../sdo-funciones/planificacion_agregarTarea.php">

                                        <button type="button" style="float: right;" data-toggle="modal" data-target="#modalAddSubTarea" id="inputMozi" class="btn bg-gradient-primary"><i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Tarea</button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="modalAddSubTarea" name="modalAddSubTarea" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary">
                                                        <h5 class="modal-title" id="exampleModalLabel">Agregar Tarea</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12 ml-auto">
                                                                <div style="text-align:left;" class="form-group">
                                                                    <label for="exampleInputBorder">Actividad: <code>*</code></label>
                                                                    <textarea id="actividadTareaP" style="resize:none;" name="actividadTareaP" required class="form-control" rows="3" placeholder="Actividad..." oninput="this.value = this.value.toUpperCase()"></textarea>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4 ml-auto">
                                                                <div style="text-align:left;" class="form-group">
                                                                    <label for="inicioPlanTareaP">Fecha Inicio - Planificaci&oacute;n: <code>*</code></label>
                                                                    <?php
                                                                    if ($idPadreTareaP > 0) {
                                                                        ?>
                                                                        <input type="date" name="inicioPlanTareaP" min="<?php echo $inicioPlanTareaP; ?>" max="<?php echo getTerminoTareaMax($idTareaP); ?>" id="inicioPlanTareaP" placeholder="Fecha Inicio - Planificaci&oacute;n" class="form-control" required />

                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <input type="date" name="inicioPlanTareaP" min="<?php echo $inicioPlanTareaP; ?>" max="<?php echo $finPlanTareaP; ?>" id="inicioPlanTareaP" placeholder="Fecha Inicio - Planificaci&oacute;n" class="form-control" required />

                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 ml-auto">
                                                                <div style="text-align:left;" class="form-group">
                                                                    <label for="finPlanTareaP">Fecha Termino - Planificaci&oacute;n: <code>*</code></label>
                                                                    <?php
                                                                    if ($idPadreTareaP > 0) {
                                                                        ?>
                                                                        <input type="date" name="finPlanTareaP" max="<?php echo getTerminoTareaMax($idTareaP); ?>" id="finPlanTareaP" placeholder="Fecha Termino - Planificaci&oacute;n" class="form-control" required />

                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <input type="date" name="finPlanTareaP" min="<?php echo $inicioPlanTareaP; ?>" max="<?php echo $finPlanTareaP; ?>" id="finPlanTareaP" placeholder="Fecha Termino - Planificaci&oacute;n" class="form-control" required />

                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 ml-auto">
                                                                <div style="text-align:left;" class="form-group">
                                                                    <label for="finPlanTareaP">Tipo de Días a Trabajar: <code>*</code></label>
                                                                    <select name="tipoDiasHabil" id="tipoDiasHabil" class="form-control">
                                                                        <option value="Seleccione...">Seleccione...</option>
                                                                        <option value="Habiles">Habiles</option>
                                                                        <option value="Inhabiles">Inhabiles</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="idTareaP" id="idTareaP" value="<?php echo $idTareaP; ?>">
                                                        <input type="hidden" name="nomCreadorTareaP" id="nomCreadorTareaP" value="<?php echo $nomUsuario; ?>">
                                                        <input type="hidden" name="deptoCreadorTareaP" id="deptoCreadorTareaP" value="<?php echo getIDControlCentra(); ?>">
                                                        <button type="submit" class="btn btn-primary">Agregar</button>
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                                <div class="card-body">

                                    <div class="table-responsive">

                                        <table id="example2" style="width: 100%;" class="table table-bordered table-hover">
                                            <thead>
                                            <tr class="bg-gradient-dark">
                                                <th>N°</th>
                                                <th>Actividad</th>
                                                <th>Inicio Planificado</th>
                                                <th>Termino Planificado</th>
                                                <th>Inicio Real</th>
                                                <th>Termino Real</th>
                                                <th>Porcentaje</th>
                                                <th>Detalle</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php

                                            $query = 'SELECT * FROM tareas_carta_gantt WHERE idPadreTareaP = ' . $idTareaP . ' ORDER BY inicioPlanTareaP ASC';

                                            $res = mysqli_query($conexion, $query);

                                            $numTareaP = 1;

                                            while ($row = $res->fetch_assoc()) {

                                                $idTareaP = $row['idTareaP'];

                                                $contarSubTareaP = contarSubTareaP($idTareaP);

                                                $contarTotalPorcentaje = contarTotalPorcentaje($idTareaP);

                                                $contarCantidadPadre = contarCantidadPadre($idTareaP);

                                                if ($contarTotalPorcentaje > 0) {
                                                    $porcentajePadre = $contarTotalPorcentaje / $contarCantidadPadre;
                                                } else {
                                                    $porcentajePadre = 0;
                                                }

                                                if ($contarSubTareaP > 0) {
                                                    $argumentoTR = 'data-widget="expandable-table" aria-expanded="false"';
                                                } else {
                                                    $argumentoTR = '';
                                                }
                                                ?>
                                                <tr class="fila-clickeable">
                                                    <td style="width: 5%; vertical-align: middle; text-align: center;">
                                                        <?php
                                                        if ($contarSubTareaP > 0) {
                                                            ?>
                                                            <button type="submit" style="text-wrap: nowrap;" id="button<?php echo $numTareaP; ?>" class="btn btn-primary" onclick="mostrarOcultarFilas('info<?php echo $numTareaP; ?>')"><i class="fa fa-plus"></i>&nbsp;&nbsp;Expandir</button>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <button type="submit" class="btn btn-primary" disabled="disabled">Deshabilitado</button>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="width: 25%; vertical-align: middle;">
                                                        <?php
                                                        echo mb_convert_case($row['actividadTareaP'], MB_CASE_UPPER, 'UTF-8');
                                                        ?>
                                                    </td>
                                                    <td style="width: 10%; vertical-align: middle;">
                                                        <?php
                                                        $inicioPlanTareaP = $row['inicioPlanTareaP'];
                                                        $inicioPlanTareaP2 = date("d-m-Y", strtotime($inicioPlanTareaP));
                                                        echo $inicioPlanTareaP2;
                                                        ?>
                                                    </td>
                                                    <td style="width: 10%; vertical-align: middle;">
                                                        <?php
                                                        $finPlanTareaP = $row['finPlanTareaP'];
                                                        $finPlanTareaP2 = date("d-m-Y", strtotime($finPlanTareaP));
                                                        echo $finPlanTareaP2;
                                                        ?>
                                                    </td>
                                                    <td style="width: 10%; vertical-align: middle;">
                                                        <?php
                                                        $inicioRealTareaP = $row['inicioRealTareaP'];

                                                        if (empty($inicioRealTareaP)) {
                                                            echo 'No Iniciado';
                                                        } else {
                                                            $inicioRealTareaP2 = date("d-m-Y", strtotime($inicioRealTareaP));
                                                            echo $inicioRealTareaP2;
                                                        }

                                                        ?>
                                                    </td>
                                                    <td style="width: 10%; vertical-align: middle;">
                                                        <?php
                                                        $finRealTareaP = $row['finRealTareaP'];

                                                        if (empty($inicioRealTareaP)) {
                                                            echo 'No Iniciado';
                                                        } else {
                                                            if (empty($finRealTareaP)) {
                                                                echo 'No Finalizado';
                                                            } else {
                                                                $finRealTareaP2 = date("d-m-Y", strtotime($finRealTareaP));
                                                                echo $finRealTareaP2;
                                                            }
                                                        }

                                                        ?>
                                                    </td>
                                                    <td style="width: 20%; vertical-align: middle; text-align: center;">
                                                        <?php

                                                        if ($contarSubTareaP > 0) {
                                                            if ($contarTotalPorcentaje == 0) {
                                                                echo "No Iniciado";
                                                            } else {
                                                                ?>
                                                                <div class="form-group">

                                                                    <?php
                                                                    $porcentajePadre = round($porcentajePadre, 1);

                                                                    ?>
                                                                    <label><?php echo $porcentajePadre . '%'; ?></label>
                                                                    <div class="progress">
                                                                        <div class="progress-bar progress-bar-striped" role="progressbar" style="width: <?php echo $porcentajePadre; ?>%" aria-valuenow="<?php echo $porcentajePadre; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                            }
                                                        } else {
                                                            $getPorcentajeTareaP = getPorcentajeTareaP($idTareaP);

                                                            $getPorcentajeTareaP = round($getPorcentajeTareaP, 1);


                                                            ?>
                                                            <div class="form-group">
                                                                <label><?php echo $getPorcentajeTareaP . '%'; ?></label>
                                                                <div class="progress">
                                                                    <div class="progress-bar progress-bar-striped" role="progressbar" style="width: <?php echo $getPorcentajeTareaP; ?>%" aria-valuenow="<?php echo $getPorcentajeTareaP; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                            <?php
                                                        }


                                                        ?>
                                                    </td>
                                                    <td style="width: 10%; vertical-align: middle; text-align: center;">
                                                        <?php
                                                        if ($row['idTareaP'] > 0) {
                                                            ?>

                                                            <form style="margin: 0; text-align: center;" class="" action="planificador_detalle.php" method="post">

                                                                <input type="hidden" name="idTareaP" value="<?php echo $row['idTareaP']; ?>">
                                                                <button type="submit" id="inputMozi" class="btn btn-block btn-info"><i class="fas fa-eye"></i></button>

                                                            </form>
                                                            <?php
                                                        } else {
                                                            echo 'Deshabilitado';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>

                                                <?php

                                                if ($contarSubTareaP > 0) {
                                                    $queryPadre = 'SELECT * FROM tareas_carta_gantt WHERE idPadreTareaP = ' . $row['idTareaP'] . ' ORDER BY inicioPlanTareaP ASC';

                                                    $resPadre = mysqli_query($conexion, $queryPadre);

                                                    $numTareaPadre = 1;

                                                    while ($rowPadre = $resPadre->fetch_assoc()) {
                                                        ?>
                                                        <tr class="info<?php echo $numTareaP; ?> fila-oculta" style="display: none; background-color: lightgrey;">
                                                            <td style="width: 5%; vertical-align: middle; text-align: center;">
                                                                <?php echo $numTareaPadre; ?>
                                                            </td>
                                                            <td style="width: 25%; vertical-align: middle;">
                                                                <?php
                                                                echo mb_convert_case($rowPadre['actividadTareaP'], MB_CASE_UPPER, 'UTF-8');
                                                                ?>
                                                            </td>
                                                            <td style="width: 10%; vertical-align: middle;">
                                                                <?php
                                                                $inicioPlanTareaPadre = $rowPadre['inicioPlanTareaP'];
                                                                $inicioPlanTareaPadre2 = date("d-m-Y", strtotime($inicioPlanTareaPadre));
                                                                echo $inicioPlanTareaPadre2;
                                                                ?>
                                                            </td>
                                                            <td style="width: 10%; vertical-align: middle;">
                                                                <?php
                                                                $finPlanTareaPadre = $rowPadre['finPlanTareaP'];
                                                                $finPlanTareaPadre2 = date("d-m-Y", strtotime($finPlanTareaPadre));
                                                                echo $finPlanTareaPadre2;
                                                                ?>
                                                            </td>
                                                            <td style="width: 10%; vertical-align: middle;">
                                                                <?php
                                                                $inicioRealTareaPadre = $row['inicioRealTareaP'];

                                                                if (empty($inicioRealTareaPadre)) {
                                                                    echo 'No Iniciado';
                                                                } else {
                                                                    $inicioRealTareaPadre2 = date("d-m-Y", strtotime($inicioRealTareaPadre));
                                                                    echo $inicioRealTareaPadre2;
                                                                }

                                                                ?>
                                                            </td>
                                                            <td style="width: 10%; vertical-align: middle;">
                                                                <?php
                                                                $finRealTareaPadre = $row['finRealTareaP'];

                                                                if (empty($inicioRealTareaPadre)) {
                                                                    echo 'No Iniciado';
                                                                } else {
                                                                    if (empty($finRealTareaPadre)) {
                                                                        echo 'No Finalizado';
                                                                    } else {
                                                                        $finRealTareaPadre2 = date("d-m-Y", strtotime($finRealTareaPadre));
                                                                        echo $finRealTareaPadre2;
                                                                    }
                                                                }

                                                                ?>
                                                            </td>
                                                            <td style="width: 20%; vertical-align: middle; text-align: center;">
                                                                <div class="form-group">
                                                                    <?php

                                                                    $porcentajeHija = round($rowPadre['porcentajeTareaP'], 1);
                                                                    ?>
                                                                    <label><?php echo $porcentajeHija; ?>%</label>
                                                                    <div class="progress">
                                                                        <div class="progress-bar progress-bar-striped" role="progressbar" style="width: <?php echo $porcentajeHija; ?>%" aria-valuenow="<?php echo $porcentajeHija; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                                    </div>
                                                                </div>
                                                            </td>

                                                            <td style="width: 10%; vertical-align: middle; text-align: center;">
                                                                <?php
                                                                echo '';
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        $numTareaPadre++;
                                                    }
                                                }
                                                $numTareaP++;
                                            }


                                            ?>


                                            </tbody>
                                            <tfoot>

                                            <tr class="bg-gradient-dark">
                                                <th>N°</th>
                                                <th>Actividad</th>
                                                <th>Inicio Planificado</th>
                                                <th>Termino Planificado</th>
                                                <th>Inicio Real</th>
                                                <th>Termino Real</th>
                                                <th>Porcentaje</th>
                                                <th>Detalle</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <?php
                }

                ?>


            </div>
            <!-- /.timeline -->

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
<script src="../dist/js/adminlte.min.js"></script>
<!-- Page specific script -->
<script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            "paging": true,
            "pageLength": 100,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": false,
            "autoWidth": true,
            "responsive": {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({


                        header: function(row) {
                            var data = row.data();
                            return 'Informaci&oacute;n de Solicitud Telefonica: ';
                        },
                        autoWidth: true,
                    }),
                    renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                        tableClass: 'table col-12 text-justify align-items-center'
                    })
                }
            },
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "search": "Buscar :",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            }
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        //group add limit
        var maxGroup = "10";

        //add more fields group
        $(".addMore").click(function() {
            if ($('body').find('.fieldGroup').length < maxGroup) {
                var fieldHTML = '<div class="form-row fieldGroup">' + $(".fieldGroupCopy").html() + '</div>';
                $('body').find('.fieldGroup:last').after(fieldHTML);

            } else {
                alert('Limite de Materiales Completados. Solo se permiten ' + maxGroup + ' Materiales.');
            }
        });

        //remove fields group
        $("body").on("click", ".remove", function() {
            $(this).parents(".fieldGroup").remove();
        });


        $('.eliminar').click(function(e) {

            e.preventDefault();
            let id = $(this).attr('id');
            var dataString = 'idTareaP=' + id;
            Swal.fire({
                icon: 'question',
                title: '¿Desea eliminar esta Tarea?',
                text: 'Esta acción no se puede revertir',
                showConfirmButton: true,
                confirmButtonText: 'Eliminar',
                confirmButtonColor: '#3085d6',
                showCancelButton: true,
                cancelButtonText: 'Salir',
                cancelButtonColor: '#d33',
                buttonsStyling: true
            }).then((result) => {

                if (result.isConfirmed) {
                    var formTest = document.createElement('form');
                    formTest.setAttribute("method", "post");
                    formTest.setAttribute("action", "../sdo-funciones/planificacion_eliminarTarea.php");

                    var post = document.createElement("input");
                    post.setAttribute("type", "hidden");
                    post.setAttribute("name", "idTareaP");
                    post.setAttribute("value", id);
                    formTest.appendChild(post);

                    document.body.appendChild(formTest);
                    formTest.submit();
                }
            })
        });
    });
</script>
<script>
    estadoSDA = document.getElementById('estadoSDA');
    motivo = document.getElementById('motivo');
    motivoText = document.getElementById('motivoText');

    estadoSDA.addEventListener('change', function(event) {

        valorEstado = estadoSDA.value;

        if (valorEstado == 'denegada') {
            motivo.style.display = 'block';
        } else if (valorEstado == '' || valorEstado == 'autorizada') {
            motivo.style.display = 'none';
            motivoText.removeAttribute("required");
        }


    })
</script>
<script src="js/dark-mode.js"></script>
<script>
    function mostrarOcultarFilas(id) {
        var filas = document.getElementsByClassName(id);

        var idd = id;
        var subcadenas = idd.split("info");

        var btn = "button" + subcadenas[1];

        for (var i = 0; i < filas.length; i++) {
            if (filas[i].style.display === "none" || filas[i].style.display === "") {
                filas[i].style.display = "table-row";
                document.getElementById(btn).innerHTML = '<i class="fa fa-minus"></i>&nbsp;&nbsp;Minimizar';
            } else {
                filas[i].style.display = "none";
                document.getElementById(btn).innerHTML = '<i class="fa fa-plus"></i>&nbsp;&nbsp;Expandir';
            }
        }


    }
</script>
</body>

</html>