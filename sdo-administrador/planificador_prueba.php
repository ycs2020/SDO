<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

$dataPreg = '';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administraci&oacute;n Planificador</title>

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
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/admin-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Administraci&oacute;n Planificador </h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                        </ol>

                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-header">
                                <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>
                            </div>

                        </div>

                    </div>

                </div>
                <div class="row" style="text-align:center;">
                    <div class="col-12" style="text-align:center;">
                        <div class="card" style="text-align:center;">

                            <div class="card-body" style="text-align:center;">

                                <div class="row justify-content-md-center" style="text-align:center;">
                                    <div class="col-md-3">
                                        <form method="post" style="vertical-align: middle" action="../sdo-funciones/planificacion_agregarTarea.php">
                                            <input type="hidden" id="esEquipamiento" name="esEquipamiento" value="0">

                                            <button type="button" data-toggle="modal" data-target="#modalAddSubTarea" id="inputMozi" class="btn btn-block bg-gradient-primary"><i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Tarea en Planificaci&oacute;n</button>

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
                                                                        <textarea id="actividadTareaP" style="resize:none;" name="actividadTareaP" required class="form-control" rows="3" placeholder="Actividad..."></textarea>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4 ml-auto">
                                                                    <div style="text-align:left;" class="form-group">
                                                                        <label for="inicioPlanTareaP">Fecha Inicio - Planificaci&oacute;n: <code>*</code></label>
                                                                        <input type="date" name="inicioPlanTareaP" id="inicioPlanTareaP" placeholder="Fecha Inicio - Planificaci&oacute;n" class="form-control" required/>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4 ml-auto">
                                                                    <div style="text-align:left;" class="form-group">
                                                                        <label for="finPlanTareaP">Fecha Termino - Planificaci&oacute;n: <code>*</code></label>
                                                                        <input type="date" name="finPlanTareaP" id="finPlanTareaP" placeholder="Fecha Termino - Planificaci&oacute;n" class="form-control" required/>
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
                                                            <input type="hidden" name="idTareaP" id="idTareaP" value="0">
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

                                    <?php
                                    $contarTotalTareaP = contarTotalTareaP();

                                    if ($contarTotalTareaP > 0) {
                                        ?>

                                        <div class="col-md-3">
                                            <form target="_blank" method="post" action="../sdo-pdf/SDO-CartaGantt.php">
                                                <input type="hidden" name="valorCargaGantt" id="valorCargaGantt" value="total">
                                                <input type="hidden" name="idDepartamentoCreador" id="idDepartamentoCreador" value="<?php echo getIDControlCentra(); ?>">
                                                <button type="submit" class="btn btn-block bg-success"><i class="fas fa-download"></i>&nbsp;&nbsp;Descargar Carta Gantt</button>
                                            </form>


                                        </div>
                                        <?php
                                    }
                                    ?>

                                </div>
                            </div>

                        </div>

                    </div>

                </div>


                <div class="row" id="dashboard">

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex p-0">
                                <h3 class="card-title p-3">Dashboard de Informaci&oacute;n</h3>
                                <ul class="nav nav-pills ml-auto p-2">
                                    <?php
                                    date_default_timezone_set('America/Santiago');
                                    $nowAnioCG = date('Y', time());
                                    $sqlAniosCG = "SELECT DISTINCT YEAR(inicioPlanTareaP) AS anio FROM tareas_carta_gantt ORDER BY inicioPlanTareaP ASC;";
                                    $resAniosCG = mysqli_query($conexion, $sqlAniosCG);
                                    while ($rowCG = $resAniosCG->fetch_assoc()) {
                                        if ($rowCG['anio'] == $nowAnioCG) {
                                            ?>
                                            <li class="nav-item"><a class="nav-link active" href="#tabHT<?php echo $rowCG['anio']; ?>" data-toggle="tab"><?php echo $rowCG['anio']; ?></a></li>
                                            <?php
                                        } else {
                                            ?>
                                            <li class="nav-item"><a class="nav-link" href="#tabHT<?php echo $rowCG['anio']; ?>" data-toggle="tab"><?php echo $rowCG['anio']; ?></a></li>
                                            <?php
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>

                            <div class="card-body">
                                <div class="tab-content">
                                    <?php
                                    date_default_timezone_set('America/Santiago');
                                    $nowAnioHT = date('Y', time());

                                    $sqlAniosHT = "SELECT DISTINCT YEAR(inicioPlanTareaP) AS anio FROM tareas_carta_gantt ORDER BY inicioPlanTareaP ASC;";
                                    $resAniosHT = mysqli_query($conexion, $sqlAniosHT);
                                    while ($rowHT = $resAniosHT->fetch_assoc()) {
                                        $anioHT = $rowHT['anio'];

                                        $anioAnteriorHT = $anioHT - 1;
                                        $nowMesHT = date('m', time());
                                        if ($anioHT == $nowAnioHT) {
                                            $tabPane = 'active';
                                        } else {

                                            $tabPane = '';
                                        }
                                        ?>

                                        <div class="tab-pane <?php echo $tabPane; ?>" id="tabHT<?php echo $rowHT['anio']; ?>">
                                            <div class="col-12">
                                                <br>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="row">
                                                            <?php
                                                            date_default_timezone_set('America/Santiago');
                                                            $nowHT = date('Y-m', time());
                                                            $anioT = date('Y', time());

                                                            $titulo1 = 'Intervalo ' . fechasPlanificaciones($anioHT);

                                                            $titulo2 = mb_convert_case($titulo1, MB_CASE_UPPER, 'utf-8');
                                                            ?>
                                                            <p class="badge-info w-100 text-center" style="font-weight:bold; overflow-wrap: break-word;"><?php echo $titulo2; ?></p>

                                                            <div class="col-xl-3 col-lg-3 col-md-6 col-12">
                                                                <div class="info-box mb-3 bg-info">
                                                                    <span class="info-box-icon"><i class="far fa-file"></i></span>

                                                                    <div class="info-box-content">
                                                                        <span class="info-box-number">Total Planificaciones</span>
                                                                        <span class="info-box-number"><?php echo contarPlanificacionesTotal($anioHT); ?></span>
                                                                    </div>
                                                                    <!-- /.info-box-content -->
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-3 col-lg-3 col-md-6 col-12">
                                                                <div class="info-box mb-3 bg-success">
                                                                    <span class="info-box-icon"><i class="fas fa-check"></i></span>

                                                                    <div class="info-box-content">
                                                                        <span class="info-box-number">Planificaciones Realizadas</span>
                                                                        <span class="info-box-number"><?php echo contarPlanificacionesRealizadas($anioHT); ?></span>
                                                                    </div>
                                                                    <!-- /.info-box-content -->
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-3 col-lg-3 col-md-6 col-12">
                                                                <div class="info-box mb-3 bg-warning">
                                                                    <span class="info-box-icon"><i class="fas fa-cog"></i></span>

                                                                    <div class="info-box-content">
                                                                        <span class="info-box-number">Planificaciones En Desarrollo</span>
                                                                        <span class="info-box-number"><?php echo contarPlanificacionesEnDesarrollo($anioHT); ?></span>
                                                                    </div>
                                                                    <!-- /.info-box-content -->
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-3 col-lg-3 col-md-6 col-12">
                                                                <div class="info-box mb-3 bg-danger">
                                                                    <span class="info-box-icon"><i class="fas fa-user"></i></span>

                                                                    <div class="info-box-content">
                                                                        <span class="info-box-number">Planificaciones Sin Iniciar</span>
                                                                        <span class="info-box-number"><?php echo contarPlanificacionesSinIniciar($anioHT); ?></span>
                                                                    </div>
                                                                    <!-- /.info-box-content -->
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <br>
                                            <div class="col-12">
                                                <hr>
                                                <br>
                                                <div class="table-responsive">

                                                    <table id="example2<?php echo $rowHT['anio']; ?>" class="table table-bordered table-hover">
                                                        <thead>
                                                        <tr class="bg-gradient-dark">
                                                            <th>N°</th>
                                                            <th>Detalle</th>
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

                                                        $query = 'SELECT * FROM tareas_carta_gantt WHERE idPadreTareaP = 0 AND (deptoCreadorTareaP = ' . getIDControlCentra() . ' OR deptoCreadorTareaP = ' . getIDInstrumentacion() . ') AND YEAR(inicioPlanTareaP) = ' . $rowHT['anio'] . ' ORDER BY inicioPlanTareaP ASC';
                                                        $res = mysqli_query($conexion, $query);

                                                        $numTareaP = 1;
                                                        $numFila = 0;

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
                                                                    $numFila++;
                                                                    echo $numFila;
                                                                    ?>
                                                                </td>
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
                                                                    if ($contarTotalPorcentaje == 0) {
                                                                        echo "No Iniciado";
                                                                    } else {
                                                                        ?>
                                                                        <div class="form-group">

                                                                            <?php
                                                                            $porcentajeTareaP2 = round($porcentajePadre, 1);

                                                                            ?>
                                                                            <label><?php echo $porcentajeTareaP2 . '%'; ?></label>
                                                                            <div class="progress">
                                                                                <div class="progress-bar progress-bar-striped" role="progressbar" style="width: <?php echo $porcentajePadre; ?>%" aria-valuenow="<?php echo $porcentajePadre; ?>" aria-valuemin="0" aria-valuemax="100"></div>
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
                                                                            <?php
                                                                            echo '';
                                                                            ?>
                                                                        </td>
                                                                        <td style="width: 5%; vertical-align: middle; text-align: center;">
                                                                            <?php echo $numFila.'.'.$numTareaPadre; ?>
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
                                                                                $porcentajeTareaP2 = round($rowPadre['porcentajeTareaP'], 1);

                                                                                ?>
                                                                                <label><?php echo $porcentajeTareaP2 . '%'; ?></label>
                                                                                <div class="progress">
                                                                                    <div class="progress-bar progress-bar-striped" role="progressbar" style="width: <?php echo $porcentajeTareaP2; ?>%" aria-valuenow="<?php echo $porcentajeTareaP2; ?>" aria-valuemin="0" aria-valuemax="100"></div>
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
                                                            <th>Detalle</th>
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
                                        </div>

                                        <!-- Cargar jQuery (asegúrate de que esté antes de tu script) -->
                                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                                        <!-- Cargar el archivo JavaScript de DataTables (también antes de tu script) -->
                                        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
                                        <!-- Cargar el archivo CSS de DataTables -->
                                        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

                                        <script>
                                            $(function () {
                                                // Inicialización del primer DataTable
                                                $("#example1").DataTable({
                                                    "responsive": true,
                                                    "lengthChange": false,
                                                    "autoWidth": false,
                                                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                                                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

                                                // Inicialización del segundo DataTable con ID dinámico
                                                var tableId = '#example2<?php echo $rowHT['anio']; ?>';  // ID dinámico
                                                $(tableId).DataTable({
                                                    "paging": true,
                                                    "lengthChange": false,
                                                    "searching": true,
                                                    "ordering": false,
                                                    "info": false,
                                                    "autoWidth": false,
                                                    "responsive": true,
                                                    "pageLength": 100,
                                                });
                                            });
                                        </script>


                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="tableInformacion">

                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                 <!-- HASTA AQUI -->
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
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Page specific script -->

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