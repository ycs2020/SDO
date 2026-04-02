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
        header('Location: soliCompra_sdo_administracion2.php?errorCargarSolicitud=true');
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
        $numInternoSDA = $datosSolicitud['numInternoSDA'];


        $fechaCreacion = strtotime($fechaSoliCompra);
        $fechaCreacion = fechaEspañol2($fechaCreacion);

        $horaCreacion = date('H:i', strtotime($fechaSoliCompra));

        $_SESSION['idSoliCompra'] = $idSoliCompra;
    }
} else if (isset($_SESSION['idSoliCompra'])) {
    $idSoliCompra = trim($_SESSION['idSoliCompra']);

    if ($idSoliCompra == 0 || empty($idSoliCompra)) {
        header('Location: soliCompra_sdo_administracion2.php?errorCargarSolicitud=true');
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
        $numInternoSDA = $datosSolicitud['numInternoSDA'];



        $fechaCreacion = strtotime($fechaSoliCompra);
        $fechaCreacion = fechaEspañol2($fechaCreacion);

        $horaCreacion = date('H:i', strtotime($fechaSoliCompra));

        $_SESSION['idSoliCompra'] = $idSoliCompra;
    }
} else {
    header('Location: soliCompra_sdo_administracion2.php?errorCargarSolicitud=true');
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrar Solicitud de Soporte</title>

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

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

</head>

<body class="hold-transition sidebar-mini dark-mode">
    <!-- Site wrapper -->
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
                            <h4>Administrar Solicitud de Soporte</h4>
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
                        icon: 'success',
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



            ?>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <!-- Timelime example  -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">

                                    <a href="soliCompra_sdo_administracion2.php" style="float: right;" class="btn btn-info"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
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
                                            <h5 style="font-size:150%"><b>Número Interno SDA:
                                                </b><?php echo isset($numInternoSDA) ?  $numInternoSDA : ''; ?></h5>

                                            <br>
                                        </div>

                                        <div style="font-size:85%;" class="col-md-3">

                                            <div class="col-md-12">

                                                <?php if ($estadoSoliCompra == 'Solicitada a Secretaria SDO') {
                                                ?>
                                                    <button class="btn bg-primary btn-block sda" data-toggle="modal" data-target="#entregaSDA" style="float:right;"><i class="fas fa-file-import"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Entregado a la SDA</b></button>
                                                <?php
                                                } else {
                                                ?>
                                                    <button class="btn disabled bg-primary btn-block sda" style="float:right;"><i class="fas fa-file-import"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Entregado a la SDA</b></button>
                                                <?php
                                                } ?>


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
                                                                                <option value="denegada">Denegada</option>
                                                                            </select>
                                                                        </div>
                                                                        <div style="text-align:left;display: none;" class="form-group" id="motivo">
                                                                            <label for="exampleInputBorder">Motivo/
                                                                                Razón: <code>*</code></label>

                                                                            <br>
                                                                            <textarea name="motivo" id="motivoText" class="form-control" required></textarea>
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

                                                    <button type="button" data-toggle="modal" data-target="#modalOrdenesSC" id="inputMozi" class="btn bg-navy btn-block" style="float:right;"><i class="fas fa-file-signature"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b> Ordenes de Compra</b> </button>

                                                    <!-- Modal -->
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

                                                                            <div class="form-row fieldGroup">

                                                                                <div class="form-group col-md-4">
                                                                                    <label for="exampleInputCoordenadas">Orden:</label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                                                                        </div>
                                                                                        <input type="text" class="form-control" name="ordenCompra[]">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group col-md-4">
                                                                                    <label for="exampleInputCoordenadas">Fecha Envio a proveedor:</label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                                        </div>
                                                                                        <input type="date" class="form-control" name="fecha[]" placeholder="Cantidad">
                                                                                    </div>
                                                                                </div>

                                                                                <div class="form-group col-md-4">
                                                                                    <label for="exampleInputCoordenadas">&nbsp;</label>
                                                                                    <div class="input-group mb-3">
                                                                                        <a href="javascript:void(0)" class="btn btn-block btn-success addMore"><span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span><b>Agregar</b> </a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

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

                                                </div>
                                                <br>
                                                <br>
                                                <br>
                                                <div class="col-md-12">

                                                    <button type="button" data-toggle="modal" data-target="#modalValorOrden" id="inputMozi" class="btn bg-danger btn-block" style="float:right;"><i class="fas fa-money-bill-wave-alt"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b> Valor Total</b> </button>

                                                    <!-- Modal -->
                                                    <form action="../sdo-funciones/SoliCompra_sdo_cambiaEstado.php" method="POST">
                                                        <div class="modal fade" id="modalValorOrden" name="modalValorOrden" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog " role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-danger">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            <b>Orden de compras</b>
                                                                        </h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>


                                                                    <div class="modal-body">



                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="id" id="id" value="<?php echo $idSoliCompra; ?>">
                                                                        <button type="submit" class="btn btn-success">Guardar</button>
                                                                        <input type="hidden" value="<?php echo "valorOrdenesCompra" ?>" name="opcion">

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
                                        </div>

                                        <div class="form-row fieldGroupCopy" style="display: none;">
                                            <div class="form-group col-md-4">
                                                <label for="exampleInputCoordenadas">Orden:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" name="ordenCompra[]">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="exampleInputCoordenadas">Fecha Envio:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                    </div>
                                                    <input type="date" class="form-control" name="fecha[]">
                                                </div>
                                            </div>



                                            <div class="form-group col-md-4">
                                                <label for="exampleInputCoordenadas">&nbsp;</label>
                                                <div class="input-group mb-3">
                                                    <a href="javascript:void(0)" class="btn btn-block btn-danger remove"><span class="glyphicon glyphicon glyphicon-remove" aria-hidden="true"></span> <b>Eliminar</b> </a>
                                                </div>
                                            </div>
                                        </div>



                                    </div>
                                    <br>
                                    <div class="row">
                                        <div style="font-size:85%" class="col-md-12">

                                            <h5 style="text-align:justify; font-size:150%">
                                                <b>Argumentos Solicitud de Compra: </b>
                                                <br><br>
                                                <textarea id="argumentosSoliCompra" style="resize:none; font-size:85%; text-align:justify" readonly="readonly" name="argumentosSoliCompra" class="form-control" rows="12" placeholder="Argumentos Solicitud de Compra..."><?php echo $argumentosSoliCompra; ?></textarea>

                                            </h5>
                                        </div>
                                    </div>

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

                                    <h4>Materiales Solicitados</h4>

                                    <table style="font-size:90%; width:100%;" id="example2" class="table table-bordered table-hover text-center">
                                        <thead class="bg-secondary">
                                            <tr>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    N°
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Nombre
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Cantidad
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Medida
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Valor
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php
                                            $query = "SELECT * FROM material_solicompra_sdo WHERE idSoliCompra = '$idSoliCompra' ORDER BY nomArticuloSC DESC";

                                            $res = mysqli_query($conexion, $query);

                                            $numMaterial = 1;

                                            while ($row = $res->fetch_assoc()) {
                                            ?>

                                                <tr>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php echo $numMaterial; ?>
                                                    </td>


                                                    <td style="vertical-align:middle;" nowrap="nowrap">
                                                        <?php
                                                        $nomArticuloSC = mb_strtoupper($row['nomArticuloSC']);
                                                        echo nl2br($nomArticuloSC);

                                                        ?>
                                                    </td>


                                                    <td style="vertical-align:middle;" nowrap="nowrap">
                                                        <?php
                                                        $cantidadArticuloSC = mb_strtoupper($row['cantidadArticuloSC']);
                                                        echo $cantidadArticuloSC;

                                                        ?>
                                                    </td>


                                                    <td style="vertical-align:middle;" nowrap="nowrap">
                                                        <?php
                                                        $medidaSC = mb_strtoupper($row['medidaSC']);
                                                        echo $medidaSC;

                                                        ?>
                                                    </td>


                                                    <td style="vertical-align:middle;" nowrap="nowrap">
                                                        <?php
                                                        $valorSC = mb_strtoupper($row['valorSC']);
                                                        $valorSC = trim($valorSC);

                                                        if (empty($valorSC)) {
                                                            echo 'No Aplica';
                                                        } else {
                                                            echo $valorSC;
                                                        }

                                                        ?>
                                                    </td>
                                                </tr>

                                            <?php

                                                $numMaterial++;
                                            }

                                            ?>
                                        </tbody>

                                        <tfoot class="bg-secondary">
                                            <tr>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    N°
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Nombre
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Cantidad
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Medida
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Valor
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
    <!-- <script>
        $(document).ready(

            $('.sda').click(function(e) {

                e.preventDefault();
                let id = $(this).attr('id');
                var dataString = 'id=' + id;
                Swal.fire({
                    icon: 'info',
                    title: '¿Seguro que quieres cambiar el estado de la solicitud a "Entregado a la SDA"?',
                    showConfirmButton: true,
                    confirmButtonText: 'SI',
                    confirmButtonColor: '#3085d6',
                    showCancelButton: true,
                    cancelButtonText: 'NO',
                    cancelButtonColor: '#d33',
                    buttonsStyling: true
                }).then((result) => {

                    if (result.isConfirmed) {
                        $.ajax({
                            url: '../sdo-funciones/SoliCompra_sdo_cambiaEstado.php',
                            type: 'POST',
                            data: dataString,
                            success: function(response) {
                                console.log('cambiado.. ' + dataString);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Estado de la solicitud modificado a "Entregado a la SDA" ',
                                    showConfirmButton: false,
                                    timer: 2000,
                                })
                                setTimeout(function() {
                                    location.reload();
                                }, 2100)
                            }
                        })
                    }
                })
            })


        )
    </script> -->
</body>

</html>