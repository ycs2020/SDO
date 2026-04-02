<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');

if (isset($_POST['idSoliCompra'])) {
    $idSoliCompra = trim($_POST['idSoliCompra']);

    if ($idSoliCompra == 0 || empty($idSoliCompra)) {
        header('Location: soliCompra_sdo_administracion.php?errorCargarSolicitud=true');
    } else {
        $datosSolicitud = array();
        $datosSolicitud = getSoliCompra_sdo($idSoliCompra);

        $idSoliCompra2 = $datosSolicitud['idSoliCompra'];

        $numSoliCompra = $datosSolicitud['numSoliCompra'];
        $folioSoliCompra = $datosSolicitud['folioSoliCompra'];
        $fechaSoliCompra = $datosSolicitud['fechaSoliCompra'];
        $personalSoliCompra = $datosSolicitud['personalSoliCompra'];
        $unidadSoliCompra = $datosSolicitud['unidadSoliCompra'];
        $emailSoliCompra = $datosSolicitud['emailSoliCompra'];
        $argumentosSoliCompra = $datosSolicitud['argumentosSoliCompra'];
        $adjuntaCotizacion = $datosSolicitud['adjuntaCotizacion'];
        $estadoSoliCompra = $datosSolicitud['estadoSoliCompra'];
        $isRevisado = $datosSolicitud['isRevisado'];


        $fechaCreacion = strtotime($fechaSoliCompra);
        $fechaCreacion = fechaEspañol2($fechaCreacion);

        $horaCreacion = date('H:i', strtotime($fechaSoliCompra));

        $_SESSION['idSoliCompra'] = $idSoliCompra;
    }
} else if (isset($_SESSION['idSoliCompra'])) {
    $idSoliCompra = trim($_SESSION['idSoliCompra']);

    if ($idSoliCompra == 0 || empty($idSoliCompra)) {
        header('Location: soliCompra_sdo_administracion.php?errorCargarSolicitud=true');
    } else {
        $datosSolicitud = array();
        $datosSolicitud = getSoliCompra_sdo($idSoliCompra);

        $idSoliCompra2 = $datosSolicitud['idSoliCompra'];

        $numSoliCompra = $datosSolicitud['numSoliCompra'];
        $folioSoliCompra = $datosSolicitud['folioSoliCompra'];
        $fechaSoliCompra = $datosSolicitud['fechaSoliCompra'];
        $personalSoliCompra = $datosSolicitud['personalSoliCompra'];
        $unidadSoliCompra = $datosSolicitud['unidadSoliCompra'];
        $emailSoliCompra = $datosSolicitud['emailSoliCompra'];
        $argumentosSoliCompra = $datosSolicitud['argumentosSoliCompra'];
        $adjuntaCotizacion = $datosSolicitud['adjuntaCotizacion'];
        $estadoSoliCompra = $datosSolicitud['estadoSoliCompra'];
        $isRevisado = $datosSolicitud['isRevisado'];



        $fechaCreacion = strtotime($fechaSoliCompra);
        $fechaCreacion = fechaEspañol2($fechaCreacion);

        $horaCreacion = date('H:i', strtotime($fechaSoliCompra));

        $_SESSION['idSoliCompra'] = $idSoliCompra;
    }
} else {
    header('Location: soliCompra_sdo_administracion.php?errorCargarSolicitud=true');
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Seguimiento de la solicitud</title>

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

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

    <link rel="stylesheet" href="css/dark-mode.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        include '../sdo-templates/mesaayuda-sidebar.php';
        ?>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h4>Seguimiento de la solicitud</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <!-- MENSAJES DE ACCIONES -->
            <?php

            //TRUE
            if (isset($_GET['errorNumSDA'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al Obtener Numero SDA',
                    })
                </script>
            <?php
            }

            //TRUE
            if (isset($_GET['exitoNumInterno'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Exito',
                        text: 'Número Interno Asignado Correctamente',
                    })
                </script>
            <?php
            }

            //FALSE
            if (isset($_GET['errorNumInterno'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error Al Asignar Número Interno SDA',
                    })
                </script>
            <?php
            }

            //TRUE
            if (isset($_GET['exitoEntrega'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Exito',
                        text: 'Exito al cambiar estado de la solicitud "Entregado a la SDA" ',
                    })
                </script>
            <?php
            }

            //FALSE
            if (isset($_GET['errorEntrega'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al cambiar estado de la solicitud "Entregado a la SDA',
                    })
                </script>
            <?php
            }

            //TRUE
            if (isset($_GET['exitoEditEstado'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Exito',
                        text: 'Exito cambio de estado de la SDA" ',
                    })
                </script>
            <?php
            }

            //FALSE
            if (isset($_GET['errorEditEstado'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error en el cambio de estado de la SDA',
                    })
                </script>
            <?php
            }

            //TRUE
            if (isset($_GET['exitoEntregaAbastecimiento'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Exito',
                        text: 'Exito al cambiar el estado de solicitud a "Derivado a Abastecimiento" ',
                    })
                </script>
            <?php
            }

            //FALSE
            if (isset($_GET['errorEntregaAbastecimiento'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al cambiar el estado de solicitud a "Derivado a Abastecimiento"',
                    })
                </script>
            <?php
            }

            //TRUE
            if (isset($_GET['exitoOrdenCompra'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Exito',
                        text: 'Exito al añadir ordenes de compra" ',
                    })
                </script>
            <?php
            }

            //FALSE
            if (isset($_GET['errorOrdenCompra'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al añadir ordenes de compra"',
                    })
                </script>
            <?php
            }




            //TRUE
            if (isset($_GET['ExitoEditOrdenCompra'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Exito',
                        text: 'Exito al editar ordenes de compra" ',
                    })
                </script>
            <?php
            }

            //FALSE
            if (isset($_GET['ErrorEditOrdenCompra'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al editar ordenes de compra"',
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

                                    <a href="soliCompra_sdo_administracion.php" style="float: right;" class="btn btn-info"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <br>
                                    <br>


                                </div>
                                <div style="font-size:85%" class="card-body">
                                    <div class="row">
                                        <div style="font-size:85%" class="col-md-9">

                                            <h5 style="font-size:150%"><b>Visualizando Solicitud:
                                                </b><?php echo $folioSoliCompra; ?></h5>
                                            <h5 style="font-size:150%"><b>Funcionario Solicitante:
                                                </b><?php echo $personalSoliCompra; ?></h5>
                                            <h5 style="font-size:150%"><b>Unidad Solicitante:
                                                </b><?php echo $unidadSoliCompra; ?></h5>
                                            <h5 style="font-size:150%"><b>Correo Solicitante:
                                                </b><?php echo $emailSoliCompra; ?></h5>
                                            <h5 style="font-size:150%"><b>Estado Solicitud:
                                                </b><?php echo $estadoSoliCompra; ?></h5>

                                            <br>
                                        </div>

                                        <div style="font-size:85%;" class="col-md-3">

                                            <div class="col-md-12">

                                                <?php

                                                if ($isRevisado == 0) {
                                                ?>
                                                    <button class="btn bg-success btn-block sda" data-toggle="modal" data-target="#revisadoSecre" style="float:right;"><i class="fas fa-check"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Revisado por Secretar&iacute;a&nbsp;&nbsp;<?php echo $isRevisado; ?></b></button>

                                                    <form action="../sdo-funciones/soliCompra_sdo_revisadoSecre.php" method="POST">
                                                        <div class="modal fade" id="revisadoSecre" name="revisadoSecre" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-primary">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            Revisado por Secretar&iacute;a</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>


                                                                    <div class="modal-body">
                                                                        <div style="text-align:left;" class="form-group">
                                                                            <label for="exampleInputBorder">Fecha Revisado: <code>*</code></label>
                                                                            <input type="datetime-local" class="form-control" name="fechaRevision" required id="fechaRevision">
                                                                        </div>


                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="id" id="id" value="<?php echo $idSoliCompra; ?>">
                                                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                                                        <input type="hidden" value="<?php echo "revisadoSecre" ?>" name="opcion">

                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>

                                                    <br>
                                                    <br>
                                                    <br>
                                                <?php
                                                } else {
                                                ?>
                                                    <button class="btn disabled bg-success btn-block sda" style="float:right;"><i class="fas fa-check"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Revisado por Secretar&iacute;a</b></button>
                                                <?php
                                                }

                                                ?>

                                                <?php
                                                if ($estadoSoliCompra == 'Solicitada a Secretaria SDO' || $estadoSoliCompra == 'Revisada por Secretaría SDO') {
                                                ?>
                                                    <button class="btn bg-primary btn-block sda" data-toggle="modal" data-target="#entregaSDA" style="float:right;"><i class="fas fa-file-import"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Entregado a la SDA</b></button>
                                                <?php
                                                } else {
                                                ?>
                                                    <button class="btn disabled bg-primary btn-block sda" style="float:right;"><i class="fas fa-file-import"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Entregado a la SDA</b></button>
                                                <?php
                                                }
                                                ?>


                                                <!-- Modal -->
                                                <form action="../sdo-funciones/SoliCompra_sdo_cambiaEstado.php" method="POST">
                                                    <div class="modal fade" id="entregaSDA" name="entregaSDA" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-primary">
                                                                    <h5 class="modal-title" id="exampleModalLabel">
                                                                        Entregado a la SDA</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>


                                                                <div class="modal-body">
                                                                    <div style="text-align:left;" class="form-group">
                                                                        <label for="exampleInputBorder">Fecha
                                                                            Entrega: <code>*</code></label>
                                                                        <input type="datetime-local" class="form-control" name="fechaEntrega" required id="fechaEntrega">
                                                                    </div>


                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="id" id="id" value="<?php echo $idSoliCompra; ?>">
                                                                    <input type="hidden" value="<?php echo "entregadoSDA" ?>" name="opcion">
                                                                    <button type="submit" class="btn btn-primary">Guardar</button>

                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div><!-- Entregado a la SDA -->
                                            <br>
                                            <br>
                                            <br>
                                            <?php

                                            if ($estadoSoliCompra == 'Entregado a la S.D.A' || $estadoSoliCompra == 'Autorizado por la S.D.A' || $estadoSoliCompra == 'Derivado a Abastecimiento') {

                                            ?>

                                                <div class="col-md-12">

                                                    <?php

                                                    if ($estadoSoliCompra !== 'Derivado a Abastecimiento') {

                                                        if (!empty($numInternoSDA)) {
                                                    ?>
                                                            <button type="button" data-toggle="modal" data-target="#editarAsignarNumero" id="inputMozi" class="btn bg-info btn-block" style="float:right;"><i class="fas fa-pencil-alt"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Editar Numero Interno SDA</b> </button>
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <button type="button" data-toggle="modal" data-target="#modalAsignarNumero" id="inputMozi" class="btn bg-info btn-block" style="float:right;"><i class="fas fa-pencil-alt"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Numero Interno SDA</b> </button>
                                                        <?php
                                                        }
                                                    } else {


                                                        if (!empty($numInternoSDA)) {
                                                        ?>
                                                            <button type="button" disabled id="inputMozi" class="btn bg-info btn-block" style="float:right;"><i class="fas fa-pencil-alt"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Editar Numero Interno SDA</b> </button>
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <button type="button" disabled id="inputMozi" class="btn bg-info btn-block" style="float:right;"><i class="fas fa-pencil-alt"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Numero Interno SDA</b> </button>
                                                    <?php
                                                        }
                                                    }

                                                    ?>
                                                    <!-- Modal -->
                                                    <form action="../sdo-funciones/SoliCompra_sdo_cambiaEstado.php" method="POST">
                                                        <div class="modal fade" id="modalAsignarNumero" name="modalAsignarNumero" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-info">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            Asignar Número Interno SDA</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>


                                                                    <div class="modal-body">
                                                                        <div style="text-align:left;" class="form-group">
                                                                            <label for="exampleInputBorder">N°
                                                                                Interno: <code>*</code></label>
                                                                            <input type="text" class="form-control " id="numInterno" name="numInterno" placeholder="Escriba Numero Interno" required>
                                                                        </div>


                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="id" id="id" value="<?php echo $idSoliCompra; ?>">
                                                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                                                        <input type="hidden" value="<?php echo "asignarNumeroInternoSDA" ?>" name="opcion">

                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>

                                                    <!-- Modal -->
                                                    <form action="../sdo-funciones/SoliCompra_sdo_cambiaEstado.php" method="POST">
                                                        <div class="modal fade" id="editarAsignarNumero" name="editarAsignarNumero" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-info">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            Editar Número Interno SDA</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>


                                                                    <div class="modal-body">
                                                                        <div style="text-align:left;" class="form-group">
                                                                            <label for="exampleInputBorder">N°
                                                                                Interno: <code>*</code></label>
                                                                            <input type="text" class="form-control " id="numInterno" name="numInterno" value="<?php echo $numInternoSDA ?>" placeholder="Escriba Numero Interno" required>
                                                                        </div>


                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="id" id="id" value="<?php echo $idSoliCompra; ?>">
                                                                        <button type="submit" class="btn btn-primary">Guardar Modificación</button>
                                                                        <input type="hidden" value="<?php echo "editarAsignarNumeroInternoSDA" ?>" name="opcion">

                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>



                                                </div><!-- Número interno SDA -->
                                                <br>
                                                <br>
                                                <br>
                                                <div class="col-md-12">

                                                    <?php

                                                    if ($estadoSoliCompra !== 'Derivado a Abastecimiento') {
                                                    ?>
                                                        <button type="button" data-toggle="modal" data-target="#modalCambiaEstado" id="inputMozi" class="btn bg-warning btn-block" style="float:right;"><i class="fas fa-thumbs-up"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Estado SDA</b> </button>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <button type="button" disabled id="inputMozi" class="btn bg-warning btn-block" style="float:right;"><i class="fas fa-thumbs-up"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Estado SDA</b> </button>
                                                    <?php
                                                    }

                                                    ?>


                                                    <!-- Modal -->
                                                    <form action="../sdo-funciones/SoliCompra_sdo_cambiaEstado.php" method="POST">
                                                        <div class="modal fade" id="modalCambiaEstado" name="modalCambiaEstado" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-warning">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            Cambiar Estado SDA</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>


                                                                    <div class="modal-body">
                                                                        <div style="text-align:left;" class="form-group">
                                                                            <label for="exampleInputBorder">Estado
                                                                                SDA: <code>*</code></label>
                                                                            <select name="estadoSDA" id="estadoSDA" class="form-control" required>
                                                                                <option value="" selected disabled>-- Seleccione --</option>
                                                                                <option value="autorizada">Autorizada</option>
                                                                                <option value="pendiente">Pendiente</option>
                                                                                <option value="denegada">Denegada</option>
                                                                            </select>
                                                                        </div>
                                                                        <div style="text-align:left;display: none;" class="form-group" id="motivo">
                                                                            <label for="exampleInputBorder">Motivo/
                                                                                Razón: <code>*</code></label>

                                                                            <br>
                                                                            <textarea name="motivo" id="motivoText" class="form-control" required></textarea>
                                                                        </div>
                                                                        <div style="text-align:left;display: none;" class="form-group" id="obs">
                                                                            <label for="exampleInputBorder">Observación: <code>*</code></label>
                                                                            <br>
                                                                            <textarea name="obs" id="obsText" class="form-control" required></textarea>
                                                                        </div>


                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="id" id="id" value="<?php echo $idSoliCompra; ?>">
                                                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                                                        <input type="hidden" value="<?php echo "cambiaEstadoSDA" ?>" name="opcion">

                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div><!-- Estado SDA -->
                                                <br>
                                                <br>
                                                <br>
                                                <div class="col-md-12">
                                                    <?php
                                                    if ($estadoSoliCompra == 'Autorizado por la S.D.A') {
                                                    ?>
                                                        <button type="button" data-toggle="modal" data-target="#modalAbastecimiento" id="inputMozi" class="btn bg-danger btn-block" style="float:right;"><i class="fas fa-cart-plus"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Entregado a Abastecimiento</b> </button>
                                                    <?php
                                                    }
                                                    ?>
                                                    <!-- Modal -->
                                                    <form action="../sdo-funciones/SoliCompra_sdo_cambiaEstado.php" method="POST">
                                                        <div class="modal fade" id="modalAbastecimiento" name="modalAbastecimiento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-danger">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            <b>Entregado a Abastecimiento</b>
                                                                        </h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                        <div style="text-align:left;" class="form-group">
                                                                            <label for="exampleInputBorder">Fecha recibido
                                                                                Abastecimiento: <code>*</code></label>
                                                                            <input type="datetime-local" class="form-control" name="fechaEntrega" id="fechaEntrega">

                                                                        </div>

                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="id" id="id" value="<?php echo $idSoliCompra; ?>">
                                                                        <button type="submit" class="btn btn-success">Guardar</button>
                                                                        <input type="hidden" value="<?php echo "entregaAbastecimiento" ?>" name="opcion">

                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div><!-- Estado SDA -->
                                                <br>
                                                <br>
                                                <br>
                                            <?php  } ?>

                                            <?php if ($estadoSoliCompra == 'Derivado a Abastecimiento') { ?>

                                                <div class="col-md-12">

                                                    <?php

                                                    $sql = "SELECT * FROM solicitudes_sc_sdo_resumen WHERE idSoliCompra = $idSoliCompra";
                                                    $resResumen = mysqli_query($conexion, $sql);

                                                    while ($rowResumen = $resResumen->fetch_assoc()) {
                                                        $datos = [];
                                                        $datos['ordenCompra'] = $rowResumen['ordenCompra'];
                                                        $datos['fechaEnvioOC'] = $rowResumen['fechaEnvioOC'];
                                                        $datos['valor'] = $rowResumen['valor'];

                                                        $ordenCompra = $datos['ordenCompra'];
                                                        $fechaEnvioOC = $datos['fechaEnvioOC'];
                                                        $valor = $datos['valor'];
                                                    }

                                                    if ($ordenCompra == '') {
                                                    ?>
                                                        <button type="button" data-toggle="modal" data-target="#modalOrdenesSC" id="inputMozi" class="btn bg-navy btn-block" style="float:right;"><i class="fas fa-file-signature"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b> Ordenes de Compra</b> </button>

                                                    <?php
                                                    } else {

                                                    ?>
                                                        <button type="button" data-toggle="modal" data-target="#editModalOrdenesSC" id="inputMozi" class="btn bg-navy btn-block" style="float:right;"><i class="fas fa-file-signature"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b> Editar Ordenes de Compra</b> </button>
                                                    <?php
                                                    }
                                                    ?>

                                                    <form action="../sdo-funciones/SoliCompra_sdo_cambiaEstado.php" method="POST">
                                                        <div class="modal fade" id="modalOrdenesSC" name="modalOrdenesSC" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-navy">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            <b>Orden de compras</b>
                                                                        </h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>


                                                                    <div class="modal-body">
                                                                        <div style="text-align:left;" class="form-group">
                                                                            <p style="font-weight:bold;text-transform:uppercase;color: greenyellow">**1 orden de compra y su respectiva fecha de envio por linea (Intente mantener el formato)</p>
                                                                            <div class="form-row fieldGroup">
                                                                                <div class="form-group col-md-6">
                                                                                    <label for="exampleInputCoordenadas">Orden de Compra:<code>*</code></label>
                                                                                    <div class="input-group mb-3">
                                                                                        <textarea class="form-control" rows="5" name="ordenCompra" placeholder="Ej: &#10; OC-0001 &#10; OC-0002 &#10; OC-0003" required></textarea>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group col-md-6">
                                                                                    <label for="exampleInputCoordenadas">Fecha Envio A Proveedor(dd/mm/yyy):</label>
                                                                                    <div class="input-group mb-3">
                                                                                        <textarea class="form-control" rows="5" name="fechaEnvio" placeholder="Ej: &#10; 27/01/23 &#10; 26/01/23 &#10; 25/01/23"></textarea>
                                                                                    </div>
                                                                                </div>


                                                                            </div>
                                                                            <div class="form-row fieldGroup">
                                                                                <div class="form-group col-md-12">
                                                                                    <label for="exampleInputCoordenadas">Valor Total Ordenes Compra:</label>
                                                                                    <input type="number" name="valorTotal" class="form-control">
                                                                                </div>

                                                                            </div>

                                                                        </div>


                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="id" id="id" value="<?php echo $idSoliCompra; ?>">
                                                                        <button type="submit" class="btn btn-success">Guardar</button>
                                                                        <input type="hidden" value="<?php echo "ordenCompras" ?>" name="opcion">

                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>

                                                    <!-- Modal -->
                                                    <form action="../sdo-funciones/SoliCompra_sdo_cambiaEstado.php" method="POST">
                                                        <div class="modal fade" id="editModalOrdenesSC" name="editModalOrdenesSC" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-navy">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            <b>Orden de compras</b>
                                                                        </h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>


                                                                    <div class="modal-body">
                                                                        <div style="text-align:left;" class="form-group">
                                                                            <p style="font-weight:bold; color:greenyellow">**1 Orden de compra y su respectiva fecha de envio al proveedor por linea (Asegurece que queden en el mismo nivel)</p>

                                                                            <div class="form-row fieldGroup">
                                                                                <div class="form-group col-md-6">
                                                                                    <label for="exampleInputCoordenadas">Orden:<code>*</code></label>
                                                                                    <textarea name="ordenCompra" id="ordenCompra" class="form-control" rows="5" required placeholder="Ej:&#10; SC-0001&#10; SC-0002"><?php echo $ordenCompra ?></textarea>
                                                                                </div>
                                                                                <div class="form-group col-md-6">
                                                                                    <label for="exampleInputCoordenadas">Fecha Envio a proveedor(dd-mm-aaaa):</label>
                                                                                    <textarea name="fechaEnvio" id="fechaEnvio" class="form-control" rows="5" placeholder="Ej:&#10; 27-01-2023&#10; 27-01-2023"><?php echo $fechaEnvioOC ?></textarea>
                                                                                </div>

                                                                            </div>
                                                                            <div class="form-row fieldGroup">
                                                                                <div class="form-group col-md-12">
                                                                                    <label for="exampleInputCoordenadas">Valor Total Ordenes:</label>
                                                                                    <input type="number" class="form-control" name="valorTotal" id="valorTotal" min="1" value="<?php echo $valor ?>">
                                                                                </div>


                                                                            </div>

                                                                        </div>


                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="id" id="id" value="<?php echo $idSoliCompra; ?>">
                                                                        <button type="submit" class="btn btn-success">Guardar</button>
                                                                        <input type="hidden" value="<?php echo "ordenComprasEdit" ?>" name="opcion">

                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>


                                                </div>
                                                <br>
                                                <br>
                                                <br>





                                            <?php } ?>
                                            <br>

                                            <button type="button" class="btn bg-navy btn-block" data-toggle="modal" id="inputMozi" data-target="#mostrarObs"><i class="fas fa-eye"></i> Mostrar Observaciones</button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="mostrarObs" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-xl">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-info">
                                                            <h5 class="modal-title" id="exampleModalLabel">Observaciones</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table style="font-size:90%; width:100%;" id="example3" class="table table-bordered table-hover text-center">
                                                                <thead class="bg-secondary">
                                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">Fecha</th>
                                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">Creado Por</th>
                                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">Detalles</th>
                                                                </thead>
                                                                <tbody>
                                                                <?php
                                                                $sqlObs = "SELECT * FROM solicitudes_compra_sdo_observaciones WHERE idSoliCompra = ".$idSoliCompra." ORDER BY idSoliCompra DESC";
                                                                $resObs = mysqli_query($conexion,$sqlObs);
                                                                while($rowObs = $resObs->fetch_assoc()){
                                                                    $inicioFecha = date('d/m/Y', strtotime($rowObs['fechaObservacion']));
                                                                    $inicioHora = date('H:i', strtotime($rowObs['fechaObservacion']));
                                                                    ?>
                                                                    <td style="vertical-align:middle;font-size: 15px" nowrap="nowrap">
                                                                        <?php echo $inicioFecha.'<br>'.$inicioHora?>
                                                                    </td>
                                                                    <td style="vertical-align:middle;font-size: 15px" nowrap="nowrap">
                                                                        <?php echo $rowObs['creadorObservacion']?>
                                                                    </td>
                                                                    <td style="vertical-align:middle;font-size: 15px" nowrap="nowrap">
                                                                        <?php echo $rowObs['detalleObservacion']?>
                                                                    </td>
                                                                    <?php
                                                                }
                                                                ?>
                                                                </tbody>
                                                                <tfoot class="bg-secondary">
                                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">Fecha</th>
                                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">Creado Por</th>
                                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">Detalles</th>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                        </div>
                                                    </div>
                                                </div>
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

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">

                                    <h4>¿Como va mi solicitud?</h4>
                                    <br>
                                    <br>

                                    <?php

                                    if ($estadoSoliCompra == 'Solicitada a Secretaria SDO') {
                                    ?>
                                        <div class="row justify-content-center">
                                            <img src="img/linea-sdo.png" alt="Linea mesa de ayuda">
                                        </div>
                                    <?php
                                    } else if ($estadoSoliCompra == 'Entregado a la S.D.A' || $estadoSoliCompra == 'Autorizado por la S.D.A' || $estadoSoliCompra == 'pendiente') {
                                    ?>
                                        <div class="row justify-content-center">
                                            <img src="img/linea-sda.png" alt="Linea SDA">
                                        </div>
                                    <?php
                                    } else if ($estadoSoliCompra == 'Derivado a Abastecimiento') {
                                    ?>
                                        <div class="row justify-content-center">
                                            <img src="img/linea-abastecimiento.png" alt="Linea Abastecimiento">
                                        </div>
                                    <?php
                                    } else if ($estadoSoliCompra == 'Recibido por pañol') {
                                    ?>
                                        <div class="row justify-content-center">
                                            <img src="img/linea-panol.png" alt="Linea Pañol">
                                        </div>
                                    <?php
                                    } else if ($estadoSoliCompra == 'Solicitud Completada') {
                                    ?>
                                        <div class="row justify-content-center">
                                            <img src="img/linea-completada.png" alt="Linea Pañol">
                                        </div>
                                    <?php
                                    } else if ($estadoSoliCompra == 'Solicitud Anulada') {
                                    ?>
                                        <div class="row justify-content-center">
                                            <img src="img/linea-sdo-anulada.png" alt="Linea Solicitud Anulada">
                                        </div>
                                    <?php
                                    } else if ($estadoSoliCompra == 'Rechazado por la S.D.A') {
                                    ?>
                                        <div class="row justify-content-center">
                                            <img src="img/linea-sda-rechazada.png" alt="Linea Rechazado sda">
                                        </div>
                                    <?php
                                    }

                                    ?>


                                    <br>

                                    <table style="font-size:90%; width:100%;" id="example2" class="table table-bordered table-hover text-center">
                                        <thead class="bg-secondary">
                                            <tr>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Fecha Actualización
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Tipo de Actualización
                                                </th>

                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Observación
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php
                                            $query = "SELECT * FROM seguimiento_sc_sdo WHERE idSoliCompra = '$idSoliCompra' ORDER BY fechaActualizacion DESC";
                                            $res = mysqli_query($conexion, $query);

                                            while ($row = $res->fetch_assoc()) {
                                            ?>
                                                <tr>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php

                                                        $inicioFecha =  date('d/m/Y', (strtotime(str_replace('/', '-', $row['fechaActualizacion']))));
                                                        $inicioHora = date('H:i', strtotime($row['fechaActualizacion']));

                                                        echo $inicioFecha . '<br>' . $inicioHora;
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap">
                                                        <?php
                                                        $cantidadArticuloSC = mb_strtoupper($row['tipoActualizacion']);
                                                        echo $cantidadArticuloSC;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap">
                                                        <?php
                                                        $nomArticuloSC = mb_strtoupper($row['observacion']);
                                                        echo nl2br($nomArticuloSC);

                                                        ?>
                                                    </td>
                                                </tr>

                                            <?php

                                            }

                                            ?>
                                        </tbody>

                                        <tfoot class="bg-secondary">
                                            <tr>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Fecha Actualización
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Tipo de Actualización
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Observación
                                                </th>


                                            </tr>
                                        </tfoot>
                                    </table>

                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>



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
                "pageLength": 10,
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
        });
    </script>
    <script>
        estadoSDA = document.getElementById('estadoSDA');
        motivo = document.getElementById('motivo');
        motivoText = document.getElementById('motivoText');
        obs = document.getElementById('obs');
        obsText = document.getElementById('obsText');

        estadoSDA.addEventListener('change', function(event) {

            valorEstado = estadoSDA.value;

            if (valorEstado == 'denegada') {
                motivo.style.display = 'block';
                obs.style.display = 'none';
                obsText.removeAttribute('required');
            } else if (valorEstado == '' || valorEstado == 'autorizada') {
                motivo.style.display = 'none';
                motivoText.removeAttribute("required");
                obs.style.display = 'none';
                obsText.removeAttribute('required');
            }else if(valorEstado == 'pendiente'){
                obs.style.display = 'block';
                motivo.style.display = 'none';
                motivoText.removeAttribute("required");
            }


        })
    </script>
</body>

</html>