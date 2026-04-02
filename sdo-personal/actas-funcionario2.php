
<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if (isset($_POST['rutActa2'])) {

    $rutActa2 = trim($_POST['rutActa2']);

    if (empty($rutActa2) || $rutActa2 == '') {
        header('Location: ../sdo-personal/actas-listado.php?errorModificarActa=true');
    } else {
        if (isset($_POST['idActa'])) {

            $idActa = trim($_POST['idActa']);

            if (empty($idActa) || $idActa == '' || $idActa < 1) {
                header('Location: ../sdo-personal/actas-listado.php?errorModificarActa=true');
            } else {

                $_SESSION['rutActa2'] = $rutActa2;
                $_SESSION['idActa'] = $idActa;

                $datosActa = array();
                $datosActa = getActa($idActa);

                $idActa2 = $datosActa['idActa'];
                $folioActa = $datosActa['folioActa'];
                $fechaActa = $datosActa['fechaActa'];
                $rutTarjeta = $datosActa['rutTarjeta'];
                $codigoTarjeta = $datosActa['codigoTarjeta'];
                $nomTarjeta = $datosActa['nomTarjeta'];
                $unidadTarjeta = $datosActa['unidadTarjeta'];
                $nomSolicitante = $datosActa['nomSolicitante'];
                $nomControlCentralizado = $datosActa['nomControlCentralizado'];
                $calidadContractual = $datosActa['calidadContractual'];
                $numTelefono = $datosActa['numTelefono'];
                $tipoActa = $datosActa['tipoActa'];
                $personaDiscapacitada = $datosActa['personaDiscapacitada'];
                $personaBecada = $datosActa['personaBecada'];


                $codigoTarjeta = mb_convert_case($codigoTarjeta, MB_CASE_UPPER, "UTF-8");
                $unidadTarjeta = mb_convert_case($unidadTarjeta, MB_CASE_TITLE, "UTF-8");
            }
        } else {
            header('Location: ../sdo-personal/actas-listado.php?errorModificarActa=true');
        }
    }
} else if (isset($_SESSION['rutActa2'])) {

    $rutActa2 = trim($_SESSION['rutActa2']);

    if (empty($rutActa2) || $rutActa2 == '') {
        header('Location: ../sdo-personal/actas-listado.php?errorModificarActa=true');
    } else {
        if (isset($_SESSION['idActa'])) {

            $idActa = trim($_SESSION['idActa']);

            if (empty($idActa) || $idActa == '' || $idActa < 1) {
                header('Location: ../sdo-personal/actas-listado.php?errorModificarActa=true');
            } else {
                $datosActa = array();
                $datosActa = getActa($idActa);

                $idActa2 = $datosActa['idActa'];
                $folioActa = $datosActa['folioActa'];
                $fechaActa = $datosActa['fechaActa'];
                $rutTarjeta = $datosActa['rutTarjeta'];
                $codigoTarjeta = $datosActa['codigoTarjeta'];
                $nomTarjeta = $datosActa['nomTarjeta'];
                $unidadTarjeta = $datosActa['unidadTarjeta'];
                $nomSolicitante = $datosActa['nomSolicitante'];
                $nomControlCentralizado = $datosActa['nomControlCentralizado'];
                $calidadContractual = $datosActa['calidadContractual'];
                $numTelefono = $datosActa['numTelefono'];
                $tipoActa = $datosActa['tipoActa'];
                $personaDiscapacitada = $datosActa['personaDiscapacitada'];
                $personaBecada = $datosActa['personaBecada'];

                $codigoTarjeta = mb_convert_case($codigoTarjeta, MB_CASE_UPPER, "UTF-8");
                $unidadTarjeta = mb_convert_case($unidadTarjeta, MB_CASE_TITLE, "UTF-8");
            }
        } else {
            header('Location: ../sdo-personal/actas-listado.php?errorModificarActa=true');
        }
    }
} else {
    header('Location: ../sdo-personal/actas-listado.php?errorModificarActa=true');
}

$sqlObs = "SELECT * FROM actas_observacion WHERE idActa = $idActa";
$resObs = mysqli_query($conexion,$sqlObs);
while($rowObs = $resObs->fetch_assoc()){
    $idActasObs = $rowObs['idActasObs'];
}


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Informaci&oacute;n del Funcionario</title>

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

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">


</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/user-sidebar.php';
    ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Informaci&oacute;n del Funcionario</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">

                        <!-- general form elements -->
                        <div class="card">
                            <div class="card-header">
                                <a href="actas-listado.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>
                                <?php
                                if (isset($_GET['exitoCrearActa'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <h5><i class="fa fa-check"></i>&nbsp;Acta Creada con Exito.</h5></div>';
                                }

                                if (isset($_GET['exitoModActa'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <h5><i class="fa fa-check"></i>&nbsp;Acta Modificada con Exito.</h5></div>';
                                }

                                if (isset($_GET['errorCrearActa'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Crear Acta. Reintente.</h5></div>';
                                }

                                if (isset($_GET['errorCrearFolio'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Crear Folio de Acta. Reintente.</h5></div>';
                                }

                                if (isset($_GET['errorVisualizarActa'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Visualizar Acta. Reintente.</h5></div>';
                                }

                                if (isset($_GET['vehiculo1Vacio'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Datos de Vehiculo N° 1 estan Vacios. Reintente.</h5></div>';
                                }

                                if (isset($_GET['soloVehiculo1'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <h5><i class="fa fa-check"></i>&nbsp;Se ha inscrito Vehiculo N° 1.</h5></div>';
                                }

                                if (isset($_GET['sinEstacionamiento'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <h5><i class="fa fa-check"></i>&nbsp;Persona No Requiere Estacionamiento.</h5></div>';
                                }

                                if (isset($_GET['exitoCrearActaVehiculo1y2'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <h5><i class="fa fa-check"></i>&nbsp;Se ha inscrito Vehiculo N° 1 y Vehiculo N° 2.</h5></div>';
                                }

                                if (isset($_GET['errorVehiculo1'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Inscribir Vehiculo N° 1. Reintente.</h5></div>';
                                }

                                if (isset($_GET['errorVehiculo2'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Inscribir Vehiculo N° 2. Reintente.</h5></div>';
                                }

                                if (isset($_GET['existePatente1'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Patente de Vehiculo N° 1 ya se encuentra registrada.</h5></div>';
                                }

                                if (isset($_GET['existePatente2'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Patente de Vehiculo N° 2 ya se encuentra registrada.</h5></div>';
                                }
                                ?>
                                <fieldset class="border p-3">
                                    <legend class="w-auto">&nbsp;Informaci&oacute;n del Funcionario:&nbsp;</legend>


                                    <h5>
                                        <b>Nombre de Funcionario: </b>
                                        <?php

                                        $nomTarjeta = mb_convert_case($nomTarjeta, MB_CASE_TITLE, "UTF-8");

                                        echo $nomTarjeta;

                                        ?>
                                    </h5>
                                    <h5><b>RUT de Funcionario: </b><?php echo $rutTarjeta; ?></h5>
                                    <h5><b>Codigo de la Tarjeta: </b><?php echo $codigoTarjeta; ?></h5>
                                    <h5>
                                        <b>Unidad del Funcionario: </b>
                                        <?php

                                        $unidadTarjeta = mb_convert_case($unidadTarjeta, MB_CASE_UPPER, "UTF-8");

                                        echo $unidadTarjeta;

                                        ?>
                                    </h5>
                                    <h5>
                                        <b>Fecha de Ingreso/Actualizaci&oacute;n: </b>
                                        <?php

                                        $fechaParaFormatear = strtotime($fechaActa);

                                        $fechaActa2 = fechaEspañol2($fechaParaFormatear);

                                        $horaActa = date('H:i', strtotime($fechaActa));

                                        echo $fechaActa2 . ' a las ' . $horaActa . ' hrs.';

                                        ?>
                                    </h5>
                                    <?php

                                    if (empty($calidadContractual) || $calidadContractual == '') {
                                    } else {
                                        ?>

                                        <h5>
                                            <b>Calidad Contractual: </b>
                                            <?php

                                            $calidadContractual = mb_convert_case($calidadContractual, MB_CASE_TITLE, "UTF-8");

                                            echo $calidadContractual;

                                            ?>
                                        </h5>
                                        <?php
                                    }

                                    if (empty($numTelefono) || $numTelefono == '') {
                                    } else {
                                        ?>

                                        <h5>
                                            <b>N° Telefono: </b>
                                            <?php

                                            $numTelefono = mb_convert_case($numTelefono, MB_CASE_TITLE, "UTF-8");

                                            echo $numTelefono;

                                            ?>
                                        </h5>
                                        <?php
                                    }

                                    if (empty($personaDiscapacitada) || $personaDiscapacitada == '') {
                                    } else {
                                        ?>

                                        <h5>
                                            <b>Funcionario con Discapacidad: </b>
                                            <?php

                                            $personaDiscapacitada = mb_convert_case($personaDiscapacitada, MB_CASE_TITLE, "UTF-8");

                                            if ($personaDiscapacitada == 1) {
                                                echo 'S&iacute;';
                                            } else {
                                                echo 'No;';
                                            }

                                            ?>
                                        </h5>
                                        <?php
                                    }

                                    if (empty($personaBecada) || $personaBecada == '') {
                                    } else {
                                        ?>

                                        <h5>
                                            <b>Funcionario Becado: </b>
                                            <?php

                                            $personaBecada = mb_convert_case($personaBecada, MB_CASE_TITLE, "UTF-8");

                                            if ($personaBecada == 1) {
                                                echo 'S&iacute';
                                            } else {
                                                echo 'No';
                                            }

                                            ?>
                                        </h5>
                                        <?php
                                    }
                                    ?>

                                </fieldset>

                            </div>
                            <?php

                            if (isset($_GET['exitoModActa'])) {
                                ?>
                                <script>
                                    Swal.fire(
                                        'Modificaci&oacute;n de Acta con &Eacute;xito.',
                                        '',
                                        'success'
                                    )
                                </script>
                                <?php
                            }

                            if (isset($_GET['errorModActa'])) {
                                ?>
                                <script>
                                    Swal.fire(
                                        'Error al Modificar Acta. Reintente.',
                                        '',
                                        'error'
                                    )
                                </script>
                                <?php
                            }


                            if (isset($_GET['exitoAgregarPatente'])) {
                                ?>
                                <script>
                                    Swal.fire(
                                        'Vehiculo Agregado con Exito.',
                                        '',
                                        'success'
                                    )
                                </script>
                                <?php
                            }

                            if (isset($_GET['exitoModPatente'])) {
                                ?>
                                <script>
                                    Swal.fire(
                                        'Vehiculo Modificado con Exito.',
                                        '',
                                        'success'
                                    )
                                </script>
                                <?php
                            }

                            if (isset($_GET['errorMaximoParking'])) {
                                ?>
                                <script>
                                    Swal.fire(
                                        'No es posible registrar m&aacute;s de 20 Parkings por Usuario.',
                                        '',
                                        'error'
                                    )
                                </script>
                                <?php
                            }

                            if (isset($_GET['errorPatenteExiste'])) {
                                ?>
                                <script>
                                    Swal.fire(
                                        'Patente de Vehiculo ya se encuentra registrada. Reintente.',
                                        '',
                                        'error'
                                    )
                                </script>
                                <?php
                            }

                            if (isset($_GET['errorAgregarPatente'])) {
                                ?>
                                <script>
                                    Swal.fire(
                                        'Error al Agregar Vehiculo. Reintente.',
                                        '',
                                        'error'
                                    )
                                </script>
                                <?php
                            }

                            if (isset($_GET['errorModPatente'])) {
                                ?>
                                <script>
                                    Swal.fire(
                                        'Error al Modificar Vehiculo. Reintente.',
                                        '',
                                        'error'
                                    )
                                </script>
                                <?php
                            }

                            if (isset($_GET['exitoEliminarPatente'])) {
                                ?>
                                <script>
                                    Swal.fire(
                                        'Vehiculo Eliminado con Exito.',
                                        '',
                                        'success'
                                    )
                                </script>
                                <?php
                            }

                            if (isset($_GET['errorEliminarPatente'])) {
                                ?>
                                <script>
                                    Swal.fire(
                                        'Error al Eliminar Vehiculo. Reintente.',
                                        '',
                                        'error'
                                    )
                                </script>
                                <?php
                            }

                            if(isset($_GET['exitoObservacion'])){
                                ?>
                                <script>
                                    Swal.fire(
                                        'Observacion ingresada con exito.',
                                        '',
                                        'success'
                                    )
                                </script>
                                <?php
                            }
                            ?>
                            <!-- /.card-header -->

                            <div class="card-body">


                                <fieldset class="border p-3">
                                    <legend class="w-auto">&nbsp;Administraci&oacute;n de Funcionario:&nbsp;</legend>

                                    <div class="row justify-content-md-center">

                                        <!-- VISUALIZAR ACTA -->
                                        <div class="col-md-2">

                                            <?php
                                            if ($idActa == 0 || $idActa == "" || $idActa == " " || $idActa == "  ") {
                                                ?>
                                                <button title="Error al Obtener ID de Acta. Opcion Deshabilitada." disabled="disabled" type="submit" id="inputMozi" class="btn btn-block btn-info"><i class="fas fa-eye"></i>&nbsp;&nbsp;Visualizar Acta</button>
                                                <?php
                                            } else {

                                                ?>

                                                <form target="_blank" style="margin: 0; text-align: center;" class="" action="../sdo-pdf/SDO-Acta.php" method="post">

                                                    <input type="hidden" name="idActa" value="<?php echo $idActa; ?>">
                                                    <button type="submit" id="inputMozi" class="btn btn-block btn-info"><i class="fas fa-eye"></i>&nbsp;&nbsp;Visualizar Acta</button>

                                                </form>

                                                <?php

                                            }
                                            ?>

                                        </div>
                                        <!-- FIN VISUALIZAR ACTA -->

                                        &nbsp;
                                        &nbsp;

                                        <!-- MODIFICAR FUNCIONARIO -->
                                        <div class="col-md-2">

                                            <?php
                                            if ($idActa == 0 || $idActa == "" || $idActa == " " || $idActa == "  ") {
                                                ?>
                                                <button title="Error al Obtener ID de Acta. Opcion Deshabilitada." disabled="disabled" type="submit" id="inputMozi" class="btn btn-block btn-warning"><i class="fas fa-pen"></i>&nbsp;&nbsp;Modificar Funcionario</button>
                                                <?php
                                            } else {

                                                ?>

                                                <form style="margin: 0; text-align: center;" class="" action="../sdo-personal/actas-modificar.php" method="post">

                                                    <input type="hidden" name="idActa" value="<?php echo $idActa; ?>">
                                                    <button type="submit" id="inputMozi" class="btn btn-block btn-warning"><i class="fas fa-pen"></i>&nbsp;&nbsp;Modificar Funcionario</button>

                                                </form>

                                                <?php

                                            }
                                            ?>

                                        </div>
                                        <!-- FIN MODIFICAR FUNCIONARIO -->

                                        &nbsp;
                                        &nbsp;

                                        <!-- AGREGAR ESTACIONAMIENTO -->
                                        <div class="col-md-2">

                                            <?php
                                            if ($idActa == 0 || $idActa == "" || $idActa == " " || $idActa == "  ") {
                                                ?>
                                                <button title="Error al Obtener ID de Acta. Opcion Deshabilitada." disabled="disabled" type="submit" id="inputMozi" class="btn btn-block bg-purple"><i class="fas fa-parking"></i>&nbsp;&nbsp;Agregar Estacionamiento</button>
                                                <?php
                                            } else {

                                                ?>

                                                <form method="post" action="../sdo-funciones/estacionamiento-agregar.php">

                                                    <button class="btn bg-purple" data-toggle="modal" data-target="#modalAddEstacionamiento" type="button"><i class="fa fa-parking"></i>&nbsp;&nbsp;Agregar Estacionamiento</button>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="modalAddEstacionamiento" name="modalAddEstacionamiento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-primary">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Agregar Estacionamiento</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <div style="text-align:left;" class="form-group">
                                                                        <label for="exampleInputBorder">Nombre de Usuario de la Tarjeta: <code>*</code></label>
                                                                        <input type="text" style="text-transform:uppercase" readonly="readonly" class="form-control " id="addNombre" name="addNombre" placeholder="Nombre de Usuario de la Tarjeta" value="<?php echo mb_convert_case(obtenerNombreRutCA($rutTarjeta), MB_CASE_TITLE, "UTF-8"); ?>" required>
                                                                    </div>
                                                                    <div style="text-align:left;" class="form-group">
                                                                        <label for="exampleInputBorder">RUT de Usuario de la Tarjeta: <code>*</code></label>
                                                                        <input style="text-align:left; text-transform:uppercase;" readonly="readonly" type="text" style="text-align:center;" class="form-control " id="rutAcceso" name="rutAcceso" value="<?php echo $rutTarjeta; ?>" required>
                                                                    </div>
                                                                    <div style="text-align:left;" class="form-group">
                                                                        <label for="exampleInputBorder">Patente de Vehiculo: <code>*</code></label>
                                                                        <input type="text" style="text-transform:uppercase" class="form-control " id="patenteParking" name="patenteParking" placeholder="Patente de Vehiculo" value="" required>
                                                                    </div>
                                                                    <div style="text-align:left;" class="form-group">
                                                                        <label for="exampleInputBorder">Modelo del Vehiculo: <code>*</code></label>
                                                                        <input style="text-align:left; text-transform:uppercase;" type="text" style="text-align:center;" class="form-control " id="modeloParking" name="modeloParking" placeholder="Modelo del Vehiculo" value="" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="idActa" id="idActa" value="<?php echo $idActa; ?>">
                                                                    <input type="hidden" name="rutTarjeta" id="rutTarjeta" value="<?php echo $rutTarjeta; ?>">
                                                                    <input type="hidden" name="rolUsuario" id="rolUsuario" value="<?php echo $rolUsuario; ?>">
                                                                    <input type="hidden" name="nomControlCentralizado" id="nomControlCentralizado" value="<?php echo $nomUsuario; ?>">
                                                                    <button type="submit" class="btn btn-primary">Agregar</button>
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>

                                                <?php

                                            }
                                            ?>

                                        </div>


                                        <!-- FIN AGREGAR ESTACIONAMIENTO -->
                                        <div class="col-md-2">
                                            <button class="btn bg-success"  data-toggle="modal" data-target="#modalAgregarObs" type="button"><i class="fa fa-eye"></i>&nbsp;&nbsp;Agregar Observacion</button>
                                        </div>
                                        <div class="col-md-2">
                                            <?php
                                            if($idActasObs == 0 || $idActasObs == "" || $idActasObs == " " || $idActasObs == "  "){
                                                ?>
                                                <button class="btn bg-red"  data-toggle="modal" data-target="#modalVisualizarObs" disabled type="button"><i class="fa fa-eye"></i>&nbsp;&nbsp;Historial Observacion</button>
                                                <?php
                                            }else{
                                                ?>
                                                <button class="btn bg-red" data-toggle="modal" data-target="#modalVisualizarObs"  type="button"><i class="fa fa-eye"></i>&nbsp;&nbsp;Historial Observacion</button>

                                                <?php
                                            }
                                            ?>
                                        </div>

                                    </div>

                                    <br>
                                </fieldset>

                                <br>

                                <fieldset class="border p-3">
                                    <legend class="w-auto">&nbsp;Estacionamiento:&nbsp;</legend>

                                    <div class="table-responsive">
                                        <table id="example2" class="table table-bordered table-hover">
                                            <thead class="bg-secondary">
                                            <tr>
                                                <th>
                                                    <center>N°</center>
                                                </th>
                                                <th>
                                                    <center>Patente<center>
                                                </th>
                                                <th>
                                                    <center>Modelo<center>
                                                </th>
                                                <th>
                                                    <center>Modificar<center>
                                                </th>
                                                <th>
                                                    <center>Eliminar<center>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <?php

                                            $contarPatente = contarPatente($rutActa2);

                                            if ($contarPatente > 0) {



                                                $query = 'SELECT * FROM parking WHERE rutParking = "' . $rutActa2 . '"';

                                                $res = mysqli_query($conexion, $query);

                                                $numAcceso = 1;

                                                $totalAcceso = 0;

                                                while ($row = $res->fetch_assoc()) {
                                                    ?>
                                                    <tr>
                                                        <td style="vertical-align:middle;">
                                                            <center><?php echo $numAcceso; ?></center>
                                                        </td>
                                                        <td style="vertical-align:middle;" align="center">
                                                            <?php
                                                            $patenteParking = mb_convert_case($row['patenteParking'], MB_CASE_UPPER, "UTF-8");
                                                            echo $patenteParking;

                                                            ?>
                                                        </td>
                                                        <td style="vertical-align:middle;" align="center">
                                                            <?php
                                                            $modeloParking = mb_convert_case($row['modeloParking'], MB_CASE_UPPER, "UTF-8");
                                                            echo $modeloParking;

                                                            ?>
                                                        </td>
                                                        <td style="vertical-align:middle;">
                                                            <center>
                                                                <?php
                                                                $idParking = $row['idParking'];
                                                                $idParking = trim($idParking);
                                                                if ($idParking == 0 || $idParking == "" || $idParking == " " || $idParking == "  ") {
                                                                    ?>
                                                                    <center><?php echo "Error al Cargar Datos."; ?></center>
                                                                    <?php
                                                                } else {

                                                                    ?>

                                                                    <form method="post" action="../sdo-funciones/estacionamiento-modificar.php">
                                                                        <button class="btn btn-warning" data-toggle="modal" data-target="#modalModEstacionamiento<?php echo $numAcceso; ?>" type="button"><i class="fa fa-pen"></i></button>

                                                                        <!-- Modal -->
                                                                        <div class="modal fade" id="modalModEstacionamiento<?php echo $numAcceso; ?>" name="modalModEstacionamiento<?php echo $numAcceso; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header bg-primary">
                                                                                        <h5 class="modal-title" id="exampleModalLabel">Modificar Estacionamiento</h5>
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                            <span aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                    </div>

                                                                                    <div class="modal-body">
                                                                                        <div style="text-align:left;" class="form-group">
                                                                                            <label for="exampleInputBorder">Nombre de Usuario de la Tarjeta: <code>*</code></label>
                                                                                            <input type="text" style="text-transform:uppercase" readonly="readonly" class="form-control " id="addNombre" name="addNombre" placeholder="Nombre de Usuario de la Tarjeta" value="<?php echo mb_convert_case(obtenerNombreRutCA($row['rutParking']), MB_CASE_TITLE, "UTF-8"); ?>" required>
                                                                                        </div>
                                                                                        <div style="text-align:left;" class="form-group">
                                                                                            <label for="exampleInputBorder">RUT de Usuario de la Tarjeta: <code>*</code></label>
                                                                                            <input style="text-align:left; text-transform:uppercase" readonly="readonly" type="text" style="text-align:center;" class="form-control " id="rutAcceso" name="rutAcceso" value="<?php echo $row['rutParking']; ?>" required>
                                                                                        </div>
                                                                                        <div style="text-align:left;" class="form-group">
                                                                                            <label for="exampleInputBorder">Patente de Vehiculo: <code>*</code></label>
                                                                                            <input type="text" style="text-transform:uppercase" class="form-control " id="patenteParking" name="patenteParking" placeholder="Patente de Vehiculo" value="<?php echo $row['patenteParking']; ?>" required>
                                                                                        </div>
                                                                                        <div style="text-align:left;" class="form-group">
                                                                                            <label for="exampleInputBorder">Modelo del Vehiculo: <code>*</code></label>
                                                                                            <input style="text-align:left; text-transform:uppercase" type="text" style="text-align:center;" class="form-control " id="modeloParking" name="modeloParking" placeholder="Modelo del Vehiculo" value="<?php echo $row['modeloParking']; ?>" required>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <input type="hidden" name="antiguaPatente" id="antiguaPatente" value="<?php echo $row['patenteParking']; ?>">
                                                                                        <input type="hidden" name="idParking" id="idParking" value="<?php echo $row['idParking']; ?>">
                                                                                        <input type="hidden" name="rutActa2" id="rutActa2" value="<?php echo $rutActa2; ?>">
                                                                                        <input type="hidden" name="idActa" id="idActa" value="<?php echo $idActa; ?>">
                                                                                        <input type="hidden" name="nomControlCentralizado" id="nomControlCentralizado" value="<?php echo $nomUsuario; ?>">
                                                                                        <input type="hidden" name="rolUsuario" id="rolUsuario" value="<?php echo $rolUsuario; ?>">
                                                                                        <button type="submit" class="btn btn-primary">Modificar</button>
                                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </form>

                                                                    <?php

                                                                }
                                                                ?>


                                                            </center>
                                                        </td>
                                                        <td style="vertical-align:middle;">
                                                            <center>
                                                                <?php
                                                                $idParking = $row['idParking'];
                                                                $idParking = trim($idParking);
                                                                if ($idParking == 0 || $idParking == "" || $idParking == " " || $idParking == "  ") {
                                                                    ?>
                                                                    <center><?php echo "Error al Cargar Datos."; ?></center>
                                                                    <?php
                                                                } else {

                                                                    ?>

                                                                    <form action="../sdo-funciones/estacionamiento-eliminar.php" method="post">
                                                                        <input type="hidden" name="rolUsuario" id="rolUsuario" value="<?php echo $rolUsuario; ?>">
                                                                        <input type="hidden" name="idParking" id="idParking" value="<?php echo $idParking; ?>">
                                                                        <button class="btn btn-danger" type="submit" name="eliminarParking<?php echo $idParking; ?>" id="eliminarParking<?php echo $idParking ?>"><i class="fa fa-trash"></i></button>
                                                                    </form>

                                                                    <?php

                                                                }
                                                                ?>


                                                            </center>
                                                        </td>

                                                    </tr>
                                                    <?php

                                                    $numAcceso = $numAcceso + 1;
                                                    $totalAcceso = $totalAcceso + 1;
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="7" align="center">
                                                        No Existen Registros...
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>



                                            </tbody>
                                            <tfoot class="bg-secondary">
                                            <tr>
                                                <th>
                                                    <center>N°</center>
                                                </th>
                                                <th>
                                                    <center>Patente<center>
                                                </th>
                                                <th>
                                                    <center>Modelo<center>
                                                </th>
                                                <th>
                                                    <center>Modificar<center>
                                                </th>
                                                <th>
                                                    <center>Eliminar<center>
                                                </th>
                                            </tr>
                                            </tfoot>
                                        </table>

                                    </div>

                                </fieldset>


                                <br>
                                <br>




                                <div class="form-group">
                                    <a href="actas-listado.php" class="btn btn-block btn-warning"> Volver </a>
                                </div>

                                <div class="modal fade" id="modalVisualizarObs" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary">
                                                <h5 class="modal-title" id="exampleModalLabel">Revisar todas las observaciones de esta acta</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <table id="observacionTotal" class="table table-bordered table-sm">
                                                    <thead style="background-color: indianred; color: white" class="text-center">
                                                    <tr>
                                                        <th style="font-size: 15px">Fecha de Ingreso de la Observacion</th>
                                                        <th style="font-size: 15px">Creador de la Observacion</th>
                                                        <th style="font-size: 15px">Nombre del Funcionario</th>
                                                        <th style="font-size: 15px">Rut del Funcionario</th>
                                                        <th style="font-size: 15px">Detalle de la Observacion</th>
                                                    </tr>

                                                    </thead>
                                                    <tbody style="font-size: 15px">
                                                    <?php
                                                    $sqlObservaciones = "SELECT * FROM actas_observacion WHERE idActa = $idActa";
                                                    $resObservaciones = mysqli_query($conexion,$sqlObservaciones);
                                                    while($rowObservaciones = $resObservaciones->fetch_assoc()){
                                                        $fechaIngreso = $rowObservaciones['fechaIngreso'];
                                                        $creadorObs = $rowObservaciones['creadorObs'];
                                                        $nomFuncionario = $rowObservaciones['nomFuncionario'];
                                                        $rutFuncionario = $rowObservaciones['rutFuncionario'];
                                                        $detalleObservacion = $rowObservaciones['detalleObservacion'];
                                                        ?>
                                                        <tr>
                                                            <td class="text-center">
                                                                <?php echo $fechaIngreso;?>
                                                            </td>
                                                            <td class="text-center">
                                                                <?php echo $creadorObs?>
                                                            </td>
                                                            <td class="text-center">
                                                                <?php echo $nomFuncionario?>
                                                            </td>
                                                            <td class="text-center">
                                                                <?php echo $rutFuncionario?>
                                                            </td>
                                                            <td class="text-center">
                                                                <?php echo nl2br($detalleObservacion)?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }

                                                    ?>
                                                    </tbody>
                                                    <tfoot style="background-color: indianred; color: white" class="text-center">
                                                    <tr>
                                                        <th style="font-size: 15px">Fecha de Ingreso de la Observacion</th>
                                                        <th style="font-size: 15px">Creador de la Observacion</th>
                                                        <th style="font-size: 15px">Nombre del Funcionario</th>
                                                        <th style="font-size: 15px">Rut del Funcionario</th>
                                                        <th style="font-size: 15px">Detalle de la Observacion</th>
                                                    </tr>
                                                    </tfoot>
                                                </table>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="modalAgregarObs" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success">
                                                <h5 class="modal-title" id="exampleModalLabel">Agregar Observacion al Acta</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="../sdo-funciones/ingresar-observacion-acta.php" method="POST">
                                                <div class="modal-body">

                                                    <label class="w-auto">Usuario Creador:</label>
                                                    <input type="text" readonly class="form-control form-control-border" name="nomUsuario" id="nomUsuario" value="<?php echo $nomUsuario?>">
                                                    <br>
                                                    <label class="w-auto">Fecha de la Observacion:</label>
                                                    <?php
                                                    date_default_timezone_set('America/Santiago');
                                                    $now = date('Y-m-d H:i:s');
                                                    ?>
                                                    <input type="text" readonly class="form-control form-control-border" name="fechaObs" id="fechaObs" value="<?php echo $now?>">
                                                    <br>
                                                    <label class="w-auto">Detalle de la Observacion:</label>
                                                    <textarea class="form-control" rows="10" name="detalleObs" id="detalleObs"></textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <input type="hidden" name="rutFuncionario" id="rutFuncionario" value="<?php echo $rutTarjeta?>">
                                                    <input type="hidden" name="nomFuncionario" id="nomFuncionario" value="<?php echo $nomTarjeta?>">
                                                    <input type="hidden" name="calidadContractual" id="calidadContractual" value="<?php echo $calidadContractual?>">
                                                    <input type="hidden" name="unidadFuncionario" id="unidadFuncionario" value="<?php echo $unidadTarjeta?>">
                                                    <input type="hidden" name="idActa" id="idActa" value="<?php echo $idActa?>">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->

                    </div>
                    <!--/.col  -->

                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
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
<!-- jQuery -->
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
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>

<script>
    $(function() {
        bsCustomFileInput.init();
    });
</script>
<script>
    $(function () {
        $('#observacionTotal').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": false,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "search": "Buscar:",
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
