<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


//OBTIENE EL GASTO TOTAL DE PETROLEO EN CADA VEHICULO
function obtieneGastoEnPetroleo($idMovil){
    include '../sdo-funciones/conexion.php';
    $sql = "SELECT SUM(totalConIva) as total FROM historial_petroleo_moviles WHERE idMovil = $idMovil";
    $res = mysqli_query($conexion, $sql);

    while($row = $res->fetch_assoc()){
        $datos = [];
        $datos['total'] = $row['total'];
        $total = $datos['total'];
    }
    return $total;
}


//OBTIENE EL GASTO TOTAL DE PETROLEO EN CADA VEHICULO
function obtieneTotalKilometraje($idMovil){
    include '../sdo-funciones/conexion.php';
    $sql = "SELECT cantKilometraje FROM historial_kilometraje_moviles WHERE idMovil = $idMovil";
    $res = mysqli_query($conexion, $sql);

    while($row = $res->fetch_assoc()){
        $datos = [];
        $datos['cantKilometraje'] = $row['cantKilometraje'];
        $total = $datos['cantKilometraje'];
    }
    return $total;
}





?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrar Equipos</title>
    <link rel="icon" href="../dist/img/ss-vina-quillota.png" type="image/icon type">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">

    <script src="../sdo-canvasdraw1/jquery.min.js"></script>
    <script src="../sdo-canvasdraw1/signature_pad.js"></script>
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://www.gstatic.com/charts/loader.js"></script>


    <!-- Popper JS-->
    <style>
        #tooltip {
            background: #8000ff;
            color: white;
            font-weight: bold;
            padding: 4px 8px;
            font-size: 13px;
            border-radius: 4px;
            display: none;
        }

        #tooltip[data-show] {
            display: block;
        }

        #arrow,
        #arrow::before {
            position: absolute;
            width: 8px;
            height: 8px;
            background: inherit;
        }

        #arrow {
            visibility: hidden;
        }

        #arrow::before {
            visibility: visible;
            content: '';
            transform: rotate(45deg);
        }

        #tooltip[data-popper-placement^='top']>#arrow {
            bottom: -4px;
        }

        #tooltip[data-popper-placement^='bottom']>#arrow {
            top: -4px;
        }

        #tooltip[data-popper-placement^='left']>#arrow {
            right: -4px;
        }

        #tooltip[data-popper-placement^='right']>#arrow {
            left: -4px;
        }
    </style>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
    <div class="preloader  flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="../dist/img/ssvq1.png" alt="logo">
        <br>
        <div class="spinner-border text-danger" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <div class="wrapper">
        <?php
        include '../sdo-templates/admin-sidebar.php';
        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <br>
            <!-- AVISOS -->
            <?php

            if (isset($_GET['errorGuardar'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Eror al guardar la información del vehículo, code: 1',
                    })
                </script>

            <?php
            }

            if (isset($_GET['exitoGuardar'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Exito!',
                        text: 'La información del vehículo se guardo correctamente.',
                    })
                </script>

            <?php
            }

            ?>
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h1 class="card-title"> <b> Listado de Vehiculos Ingresados</b></h1>
                        </div>
                        <div class="row">
                            <!-- left column -->
                            <div class="col-md-12">
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp; <b>Volver Atras</b> </a>
                                    <button class="btn  bg-purple" aria-describedby="tooltip" data-toggle="modal" data-target="#exampleModal" id="agregar"><i class="fas fa-plus"></i><b> Vehiculo</b> </button>
                                    <div id="tooltip" role="tooltip">
                                        <b>Agregar un nuevo Vehiculo</b>
                                        <div id="arrow" data-popper-arrow></div>
                                    </div>
                                    <br>
                                    <hr>
                                  <!--  <div class="align-content-center text-center">
                                        <button class="btn-info btn " id="visualizarDash"> <b><i class="fas fa-eye"></i> Visualizar Dashboard</b> </button>
                                        <button class="btn-danger btn align-content-center text-center" id="ocultarDash" style="display: none;"> <b><i class="fas fa-times"></i> Ocultar Dashboard</b> </button>
                                    </div>-->
                                    <br>
                                    <input type="hidden" id="gastoR67" value="<?php echo obtieneGastoEnPetroleo(5) ?>">
                                    <input type="hidden" id="gastoR68" value="<?php echo obtieneGastoEnPetroleo(6) ?>">
                                    <input type="hidden" id="gastoR69" value="<?php echo obtieneGastoEnPetroleo(9) ?>">
                                    <input type="hidden" id="gastoHILUX" value="<?php echo obtieneGastoEnPetroleo(10) ?>">
                                    <input type="hidden" id="gastoR66" value="<?php echo obtieneGastoEnPetroleo(12) ?>">

                                    <input type="hidden" id="kilometrajeR67" value="<?php echo obtieneTotalKilometraje(5) ?>">
                                    <input type="hidden" id="kilometrajeR68" value="<?php echo obtieneTotalKilometraje(6) ?>">
                                    <input type="hidden" id="kilometrajeR69" value="<?php echo obtieneTotalKilometraje(9) ?>">
                                    <input type="hidden" id="kilometrajeHILUX" value="<?php echo obtieneTotalKilometraje(10) ?>">
                                    <input type="hidden" id="kilometrajeR66" value="<?php echo obtieneTotalKilometraje(12) ?>">


                                    <div class="row " id="dashboard"  >
                                        <div class="col-12 col-md-4 d-flex align-items-center justify-content-center text-center">
                                            <div id="chart_div"></div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div id="pie_chart"></div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <?php echo 'Cargando...'; ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <br>
                                    <br>
                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header bg-purple">
                                                    <h5 class="modal-title" id="exampleModalLabel">Llene los campos solicitados</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="form" method="POST" action="../sdo-funciones/guardar-moviles.php">
                                                        <fieldset id="fielset" class="border p-3 shadow p-3 mb-5  rounded ">
                                                            <div class="form-row">
                                                                <div class="form-group  col-12 col-md-12">
                                                                    <label for="codigoBarra" style="font-size:12px">ID:<code>*</code></label>
                                                                    <div class="input-group mb-3 ">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-fingerprint"></i></span>
                                                                        </div>
                                                                        <input type="text" class="form-control form-control-sm " required id="idVehiculo" name="idVehiculo" placeholder="Escriba el id unico del vehiculo">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group  col-12 col-md-12">
                                                                    <label for="codigoBarra" style="font-size:12px">Nombre:<code>*</code></label>
                                                                    <div class="input-group mb-3 ">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="far fa-id-card"></i></span>
                                                                        </div>
                                                                        <input type="text" class="form-control form-control-sm " required id="nomVehiculo" name="nomVehiculo" placeholder="Escriba el nombre">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-row">

                                                                <div class="form-group col-md-4">
                                                                    <label for="codigoBarra" style="font-size:12px">Patente:<code>*</code></label>
                                                                    <div class="input-group mb-3 ">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-fingerprint"></i></span>
                                                                        </div>
                                                                        <input type="text" class="form-control form-control-sm" required id="patenteVehiculo" name="patenteVehiculo" placeholder="Escriba la patente">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group col-md-4">
                                                                    <label for="codigoBarra" style="font-size:12px">Marca:<code>*</code></label>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-car-alt"></i></span>
                                                                        </div>
                                                                        <input type="text" class="form-control form-control-sm" required id="nomVehiculo" name="marcaVehiculo" placeholder="Escriba la marca">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="codigoBarra" style="font-size:12px">Modelo:</label>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-car-alt"></i></span>
                                                                        </div>
                                                                        <input type="text" class="form-control form-control-sm" id="nomVehiculo" name="modeloVehiculo" placeholder="Escriba el modelo">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-row">

                                                                <div class="form-group col-md-4">
                                                                    <label for="codigoBarra" style="font-size:12px">N° de Inventario:</label>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-box"></i></span>
                                                                        </div>
                                                                        <input type="text" class="form-control form-control-sm" id="numInventario" name="numInventario" placeholder="Escriba la marca">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="codigoBarra" style="font-size:12px">Servicio:<code>*</code></label>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="far fa-hospital"></i></span>
                                                                        </div>
                                                                        <select name="servicioVehiculo" id="servicioVehiculo" class="form-control form-control-sm">

                                                                            <?php

                                                                            $sqlServicios = "SELECT * FROM servicios";
                                                                            $resServicios = mysqli_query($conexion, $sqlServicios);

                                                                            while ($rowServicios = $resServicios->fetch_assoc()) {
                                                                                $datosServicios = [];
                                                                                $datosServicios['idServicio'] = $rowServicios['idServicio'];
                                                                                $datosServicios['nomServicio'] = $rowServicios['nomServicio'];
                                                                                $idServicio = $datosServicios['idServicio'];
                                                                                $nomServicio = $datosServicios['nomServicio'];

                                                                            ?>

                                                                                <option value="<?php echo $nomServicio ?>"><?php echo $nomServicio ?></option>

                                                                            <?php
                                                                            }

                                                                            ?>


                                                                        </select>

                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="codigoBarra" style="font-size:12px">N° de Chasis:</label>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-exclamation-circle"></i></span>
                                                                        </div>
                                                                        <input type="text" class="form-control form-control-sm" id="numChasis" name="numChasis" placeholder="Escriba N° Chasis">
                                                                    </div>
                                                                </div>


                                                            </div>
                                                            <div class="form-row">

                                                                <div class="form-group col-md-4">
                                                                    <label for="codigoBarra" style="font-size:12px">N° de Motor:</label>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-exclamation-circle"></i></span>
                                                                        </div>
                                                                        <input type="text" class="form-control form-control-sm" id="numMotor" name="numMotor" placeholder="Escriba N° Motor">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="codigoBarra" style="font-size:12px">Fabricante:</label>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-exclamation-circle"></i></span>
                                                                        </div>
                                                                        <input type="text" class="form-control form-control-sm" id="frabricante" name="frabricante" placeholder="Escriba nombre fabricante">
                                                                    </div>
                                                                </div>


                                                            </div>
                                                            <div class="form-row">

                                                                <div class="form-group col-md-4">
                                                                    <label for="codigoBarra" style="font-size:12px">Fecha de Compra:</label>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                        </div>
                                                                        <input type="date" class="form-control form-control-sm" id="fechaCompra" name="fechaCompra">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="codigoBarra" style="font-size:12px">Orden de Compra:</label>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                                                        </div>
                                                                        <input type="text" class="form-control form-control-sm" id="ordenCompra" name="ordenCompra" placeholder="Escriba orden de compra">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="codigoBarra" style="font-size:12px">Vida Útil:</label>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-heartbeat"></i></span>
                                                                        </div>
                                                                        <input type="text" class="form-control form-control-sm" id="vidaUtil" name="vidaUtil" placeholder="Indique la vida útil">
                                                                    </div>
                                                                </div>


                                                            </div>
                                                            <div class="form-row justify-content-center">

                                                                <div class="form-group col-md-6">
                                                                    <label for="codigoBarra" style="font-size:12px">Valor con IVA $:</label>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-money-bill-alt"></i></span>
                                                                        </div>
                                                                        <input type="number" class="form-control form-control-sm" id="valorConIva" name="valorConIva">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </fieldset>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal"> <b>Cerrar Modal</b> </button>
                                                    <button type="submit" class="btn btn-success"> <b>Guardar Vehiculo</b> </button>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive">


                                    <table id="table_equipos" class="table table-bordered table-hover">
                                        <thead class="bg-secondary">
                                            <tr>
                                                <th scope="col" class="text-center">ID</th>
                                                <th scope="col" class="text-center">Nombre</th>
                                                <th scope="col" class="text-center">Patente</th>
                                                <th scope="col" class="text-center">Marca</th>
                                                <th scope="col" class="text-center">Modelo</th>
                                                <th scope="col" class="text-center">Servicio</th>
                                                <th scope="col" class="text-center">Valor Iva</th>
                                                <th scope="col" class="text-center">Estado</th>
                                                <th scope="col" class="text-center"><i class="fas fa-exclamation"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT `idUnico`, `idMovil`, `folio`, `nomVehiculo`, `patenteVehiculo`, `marcaVehiculo`, `modeloVehiculo`, `numInventario`, `servicioVehiculo`, `numChasis`, `numMotor`, `frabricante`, `fechaCompra`, `ordenCompra`, `valorConIva`, `estadoVehiculo`, `fechaRegistro` FROM `moviles_movilizacion` ";
                                            $res = mysqli_query($conexion, $sql);
                                            while ($row = $res->fetch_assoc()) {
                                                $datos = [];
                                                $datos['idUnico'] = $row['idUnico'];
                                                $datos['nomVehiculo'] = $row['nomVehiculo'];
                                                $datos['patenteVehiculo'] = $row['patenteVehiculo'];
                                                $datos['marcaVehiculo'] = $row['marcaVehiculo'];
                                                $datos['modeloVehiculo'] = $row['modeloVehiculo'];
                                                $datos['servicioVehiculo'] = $row['servicioVehiculo'];
                                                $datos['valorConIva'] = $row['valorConIva'];
                                                $datos['estadoVehiculo'] = $row['estadoVehiculo'];
                                                $datos['idMovil'] = $row['idMovil'];
                                                $folio = $datos['idUnico'];
                                                $nomVehiculo = $datos['nomVehiculo'];
                                                $patenteVehiculo = $datos['patenteVehiculo'];
                                                $marcaVehiculo = $datos['marcaVehiculo'];
                                                $modeloVehiculo = $datos['modeloVehiculo'];
                                                $servicioVehiculo = $datos['servicioVehiculo'];
                                                $valorConIva = $datos['valorConIva'];
                                                $estadoVehiculo = $datos['estadoVehiculo'];
                                                $idMovil = $datos['idMovil'];
                                            ?>
                                                <tr>
                                                    <td class="text-center font-weight-bold" style="font-size:12px;"><?php echo $folio ?> </td>
                                                    <td class="text-center font-weight-bold" style="font-size:12px;"><?php echo $nomVehiculo ?></td>
                                                    <td class="text-center font-weight-bold" style="font-size:12px;"><?php echo $patenteVehiculo ?></td>
                                                    <td class="text-center font-weight-bold" style="font-size:12px;"><?php echo $marcaVehiculo ?></td>
                                                    <td class="text-center font-weight-bold" style="font-size:12px;">
                                                    <?php

                                                    if (empty($modeloVehiculo)) {

                                                        echo '-';
                                                    } else {
                                                        echo $modeloVehiculo;
                                                    }



                                                    ?>
                                                    </td>
                                                    <td class="text-center font-weight-bold" style="font-size:12px;"><?php echo $servicioVehiculo ?></td>
                                                    <td class="text-center font-weight-bold" style="font-size:12px;">
                                                        <?php

                                                        if (empty($valorConIva)) {

                                                            echo '-';
                                                        } else {
                                                            $valorConIva = moneda_chilena($valorConIva);
                                                            echo $valorConIva;
                                                        }

                                                        ?>

                                                    </td>
                                                    <?php

                                                    if ($estadoVehiculo == 'OPERATIVA') {
                                                    ?>
                                                        <td class="text-center font-weight-bold  text-success" style="font-size: 12px"><?php echo '<i class="fas fa-check"></i> ' .  strtoupper($estadoVehiculo) ?></td>
                                                    <?php
                                                    } else if ($estadoVehiculo == 'MANTENCION PREVENTIVA' || $estadoVehiculo == 'mantencion preventiva') {
                                                    ?>
                                                        <td class="text-center font-weight-bold text-warning  " style="font-size: 12px"><?php echo '<i class="fas fa-exclamation-triangle"></i> ' .  strtoupper($estadoVehiculo) ?></td>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <td class=" text-center font-weight-bold text-danger " style="font-size: 12px"><?php echo '<i class="fas fa-skull-crossbones"></i> ' .  strtoupper($estadoVehiculo) ?></td>
                                                    <?php
                                                    }

                                                    ?>
                                                    <td class="text-center" style="font-size:12px;">

                                                        <form action="../sdo-administrador/visualizarMovil.php" method="POST" style="display: inline;">
                                                            <input type="hidden" id="idMovil" name="idMovil" value="<?php echo $idMovil ?>">
                                                            <button type="submit" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></button>
                                                        </form>
                                                        <button class="btn btn-danger btn-sm eliminar" id="<?php echo $idMovil ?>"><i class="fas fa-trash"></i></button>

                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot class="bg-secondary">
                                            <tr>
                                                <th scope="col" class="text-center">ID</th>
                                                <th scope="col" class="text-center">Nombre</th>
                                                <th scope="col" class="text-center">Patente</th>
                                                <th scope="col" class="text-center">Marca</th>
                                                <th scope="col" class="text-center">Modelo</th>
                                                <th scope="col" class="text-center">Servicio</th>
                                                <th scope="col" class="text-center">Valor Iva</th>
                                                <th scope="col" class="text-center">Estado</th>
                                                <th scope="col" class="text-center"><i class="fas fa-exclamation"></i></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    </div>
                                </div><!-- .tabla-responsive -->
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col  -->
        </div>
        <!-- /.row -->
        <!-- /.content -->
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

    <!-- jQuery -->
    <script src="../plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.2/js/dataTables.bootstrap4.min.js"></script>

    <!-- Page specific script -->
    <script>
        $(function() {
            bsCustomFileInput.init();
        });
    </script>
    <script src="js/dark-mode.js"></script>
    <script>
        $(document).ready(function() {
            $('#table_equipos').DataTable({
                "paging": true,
                "iDisplayLength": 50,
                "lengthChange": true,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "language": {
                    "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                    "zeroRecords": "No se han encontrado Registros.",
                    "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                    "infoEmpty": "Sin registros para mostrar.",
                    "search": "Buscar:",
                    "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                    "paginate": {
                        "previous": "Anterior",
                        "next": "Siguiente"
                    }
                },
                // initComplete: function() {
                //     this.api().columns([3]).every(function() {
                //         var column = this;
                //         var select = $('<select style="width: 40%;"><option value="">Servicio</option></select>')
                //             .appendTo($(column.header()).empty())
                //             .on('change', function() {
                //                 var val = $.fn.dataTable.util.escapeRegex(
                //                     $(this).val()
                //                 );

                //                 column
                //                     .search(val ? '^' + val + '$' : '', true, false)
                //                     .draw();
                //             });

                //         column.data().unique().sort().each(function(d, j) {
                //             select.append('<option value="' + d + '">' + d + '</option>')
                //         });
                //     });
                // }
            });
        });
    </script>
    <script>
        $(document).ready(
            $('.eliminar').click(function(e) {
                e.preventDefault();
                let id = $(this).attr('id');
                var dataString = 'id=' + id;
                Swal.fire({
                    icon: 'question',
                    title: '¿Eliminar este vehículo?',
                    text: 'Esta acción no se puede revertir',
                    showConfirmButton: true,
                    confirmButtonText: 'Si , eliminar',
                    confirmButtonColor: '#3085d6',
                    showCancelButton: true,
                    cancelButtonText: 'No, lo pensare',
                    cancelButtonColor: '#d33',
                    buttonsStyling: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '../sdo-funciones/eliminar-moviles.php',
                            type: 'POST',
                            data: dataString,
                            success: function(response) {
                                console.log('eliminado.. ' + dataString);
                                console.log(response)
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Registro del vehículo eliminado',
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
    </script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script>
        const button = document.querySelector('#agregar');
        const tooltip = document.querySelector('#tooltip');

        const popperInstance = Popper.createPopper(button, tooltip, {
            placement: 'right',
            modifiers: [{
                name: 'offset',
                options: {
                    offset: [0, 8],
                },
            }, ],
        });

        function show() {
            // Make the tooltip visible
            tooltip.setAttribute('data-show', '');

            // Enable the event listeners
            popperInstance.setOptions((options) => ({
                ...options,
                modifiers: [
                    ...options.modifiers,
                    {
                        name: 'eventListeners',
                        enabled: true
                    },
                ],
            }));

            // Update its position
            popperInstance.update();
        }

        function hide() {
            // Hide the tooltip
            tooltip.removeAttribute('data-show');

            // Disable the event listeners
            popperInstance.setOptions((options) => ({
                ...options,
                modifiers: [
                    ...options.modifiers,
                    {
                        name: 'eventListeners',
                        enabled: false
                    },
                ],
            }));
        }

        const showEvents = ['mouseenter', 'focus'];
        const hideEvents = ['mouseleave', 'blur'];

        showEvents.forEach((event) => {
            button.addEventListener(event, show);
        });

        hideEvents.forEach((event) => {
            button.addEventListener(event, hide);
        });
    </script>
    <script>
        let visualizarDash = document.getElementById("visualizarDash");
        let ocultarDash = document.getElementById("ocultarDash");
        let dashboard = document.getElementById("dashboard");

        visualizarDash.addEventListener("click", function (){
            visualizarDash.style.display = "none";
            ocultarDash.style.display = "inline-block";
            dashboard.style.display = "block";
        })

        ocultarDash.addEventListener("click", function (){
            visualizarDash.style.display = "inline-block";
            ocultarDash.style.display = "none";
            dashboard.style.display = "none";
        })

    </script>
    <script type="text/javascript">

        //OBTIENE LOS GATOS EN PETROLEO
        gastoR67 = document.getElementById("gastoR67").value;
        gastoR68 = document.getElementById("gastoR68").value;
        gastoR69 = document.getElementById("gastoR69").value;
        gastoR66 = document.getElementById("gastoR66").value;
        gastoHILUX = document.getElementById("gastoHILUX").value;

        gastoR67 = parseInt(gastoR67);
        gastoR68 = parseInt(gastoR68);
        gastoR69 = parseInt(gastoR69);
        gastoR66 = parseInt(gastoR66);
        gastoHILUX = parseInt(gastoHILUX);


        // Load the Visualization API and the corechart package.
        google.charts.load('current', {'packages':['corechart'], 'language':'es'});

        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawChart);

        // Callback that creates and populates a data table,
        // instantiates the pie chart, passes in the data and
        // draws it.
        function drawChart() {

            var data = google.visualization.arrayToDataTable([
                ['Vehiculo', 'Costo $', { role: 'style' }],
                ['R-67', gastoR67, '#5bc0de'],
                ['R-68', gastoR68, '#d9534f'],
                ['R-69', gastoR69, '#7c8184'],
                ['HILUX', gastoHILUX, '#2972fa'],
                ['R-66', gastoR66, '#fab633' ],
            ]);

            // Set chart options
            var options = {'title':'Gasto de Petroleo Anual por vehiculo',
                'width':500,
                'height':300,
                legend: 'none'

                };

            // Instantiate and draw our chart, passing in some options.

            var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }


    </script>
    <script>

        //OBTIENE LOS GATOS EN PETROLEO
        kilometrajeR67 = document.getElementById("kilometrajeR67").value;
        kilometrajeR68 = document.getElementById("kilometrajeR68").value;
        kilometrajeR69 = document.getElementById("kilometrajeR69").value;
        kilometrajeR66 = document.getElementById("kilometrajeR66").value;
        kilometrajeHILUX = document.getElementById("kilometrajeHILUX").value;

        kilometrajeR67 = parseFloat(kilometrajeR67);
        kilometrajeR68 = parseFloat(kilometrajeR68);
        kilometrajeR69 = parseFloat(kilometrajeR69);
        kilometrajeR66 = parseFloat(kilometrajeR66);
        kilometrajeHILUX = parseFloat(kilometrajeHILUX);


        // Load the Visualization API and the corechart package.
        google.charts.load('current', {'packages':['corechart'], 'language':'es'});

        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            var data = google.visualization.arrayToDataTable([
                ['Vehiculo', 'Kilometraje', { role: 'style' }],
                ['R-67', kilometrajeR67, '#5bc0de'],
                ['R-68', kilometrajeR68, '#d9534f'],
                ['R-69', kilometrajeR69, '#7c8184'],
                ['HILUX', kilometrajeHILUX, '#2972fa'],
                ['R-66', kilometrajeR66, '#fab633' ],
            ]);


            // Set chart options
            var options = {'title':'Kilometraje Anual de cada vehiculo',
                'width':500,
                'height':300,
                legend: 'none'

            };

            // Instantiate and draw our chart, passing in some options.

            var pie_chart = new google.visualization.BarChart(document.getElementById('pie_chart'));
            pie_chart.draw(data, options);
        }
    </script>

</body>

</html>