<?php

// ASIGNAR ID
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agregar Acta</title>

    <link rel="icon" href="../dist/img/controlCent-HBQP-120.png" type="image/icon type">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="../dist/css/modelo.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
    <div class="wrapper">
        <?php
        include '../sdo-templates/admtec-sidebar.php';
        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Agregar Acta</h1>
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
                    <form action="../sdo-funciones/admtec-actas-generar.php" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <!-- left column -->
                            <div class="col-md-12">

                                <!-- general form elements -->
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Formulario de Actas</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <?php


                                    if (isset($_GET['errorCrearActa'])) {
                                        echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Crear Acta. Reintente.</h5></div>';
                                    }


                                    ?>
                                    <div class="card-body">

                                        
                                        <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                        <br>
                                        <br>
                                        <fieldset class="border p-3">
                                            <legend class="w-auto">T&eacute;cnico de Turno de Control Centralizado:</legend>

                                            <div class="form-row">


                                                <div class="form-group col-md-6">
                                                    <label for="exampleInput">T&eacute;cnico de Turno de Control Centralizado:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                        </div>

                                                        <select class="form-control" name="selectTecnicoTurno" id="selectTecnicoTurno">
                                                            <option value="">Seleccione...</option>
                                                            <?php

                                                            $queryTecnicos = "SELECT * FROM personal WHERE departamentoPersonal = " . getIDInstrumentacion() . " ORDER BY nomPersonal ASC";

                                                            $resTecnicos = mysqli_query($conexion, $queryTecnicos);


                                                            while ($rowTecnicos = $resTecnicos->fetch_assoc()) {

                                                            ?>
                                                                <option value="<?php echo $rowTecnicos['nomPersonal']; ?>">
                                                                    <?php echo $rowTecnicos['nomPersonal']; ?>
                                                                </option>
                                                            <?php

                                                            }


                                                            ?>
                                                        </select>


                                                    </div>
                                                </div>

                                            </div>

                                        </fieldset>

                                        <br>

                                        <fieldset class="border p-3">
                                            <legend class="w-auto">Informaci&oacute;n del Funcionario:</legend>


                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInput">Nombre de Funcionario:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                        </div>
                                                        <input type="text" required style="text-transform:capitalize" class="form-control" id="nomTarjeta" name="nomTarjeta" placeholder="Nombre y Apellido de Usuario de Tarjeta">
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for="exampleInput">RUT de Funcionario:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                        </div>
                                                        <input type="text" required style="text-transform:capitalize" class="form-control" id="rutTarjeta" name="rutTarjeta" placeholder="RUT de Usuario de Tarjeta (Formato XXXXXXXXX, la K es 0)">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-row">


                                                <div class="form-group col-md-6">
                                                    <label for="exampleInput">Calidad Contractual de Funcionario:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                        </div>
                                                        <select class="form-control" name="calidadContractual" id="calidadContractual">
                                                            <option value="Seleccione">Seleccione...</option>
                                                            <option value="Contrata">Contrata</option>
                                                            <option value="Empresa Externa">Empresa Externa</option>
                                                            <option value="Honorarios">Honorarios</option>
                                                            <option value="Reemplazo">Reemplazo</option>
                                                            <option value="Titular">Titular</option>
                                                            <option value="Clinico Interno">Clinico Interno</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for="exampleInput">N&uacute;mero de Contacto:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                        </div>
                                                        <input type="text" required class="form-control" id="numTelefono" name="numTelefono" placeholder="+569XXXXXXXX">
                                                    </div>
                                                </div>
                                            </div>

                                        </fieldset>

                                        <br>
                                        <fieldset class="border p-3">
                                            <legend class="w-auto">Informaci&oacute;n de la Tarjeta:</legend>

                                            <div class="form-row">


                                                <div class="form-group col-md-6">
                                                    <label for="exampleInput">Codigo de Tarjeta:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-code"></i></span>
                                                        </div>
                                                        <input type="text" required style="text-transform:uppercase" class="form-control" id="codigoTarjeta" name="codigoTarjeta" placeholder="Codigo de Tarjeta">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputUsuarioTarjeta">Unidad del Usuario de Tarjeta:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-list"></i></span>
                                                        </div>
                                                        <input type="text" required class="form-control" id="unidadTarjeta" name="unidadTarjeta" placeholder="Unidad del Usuario de Tarjeta">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInput">Nombre de Quien Solicita:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                        </div>
                                                        <input type="text" required style="text-transform:capitalize" class="form-control" id="nomSolicitante" name="nomSolicitante" placeholder="Nombre de Quien Solicita">
                                                    </div>
                                                </div>
                                            </div>

                                        </fieldset>

                                        <br>
                                        <fieldset class="border p-3">
                                            <legend class="w-auto">Informaci&oacute;n sobre Parking:</legend>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="checkbox" id="checkboxParking" name="checkboxParking" value="option1">
                                                        <label for="checkboxParking" class="custom-control-label">Necesita Permisos de Estacionamiento</label>
                                                    </div>
                                                    <script type="text/javascript">
                                                        document.getElementById('checkboxParking').onchange = function() {
                                                            document.getElementById('patente1').disabled = !this.checked;
                                                            document.getElementById('patente2').disabled = !this.checked;
                                                            document.getElementById('modelo1').disabled = !this.checked;
                                                            document.getElementById('modelo2').disabled = !this.checked;
                                                        };
                                                    </script>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputUsuarioTarjeta">Patente de Vehiculo N° 1:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-car"></i></span>
                                                        </div>
                                                        <input type="text" disabled class="form-control" id="patente1" name="patente1" placeholder="Patente de Vehiculo N° 1: (XXXXXX)">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputCodigoTarjeta">Modelo y Color de Vehiculo N° 1</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-car"></i></span>
                                                        </div>
                                                        <input type="text" disabled style="text-transform:capitalize" class="form-control" id="modelo1" name="modelo1" placeholder="Modelo y Color de Vehiculo N° 1">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputUsuarioTarjeta">Patente de Vehiculo N° 2</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-car"></i></span>
                                                        </div>
                                                        <input type="text" disabled class="form-control" id="patente2" name="patente2" placeholder="Unidad del Usuario de Tarjeta">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputCodigoTarjeta">Modelo y Color de Vehiculo N° 2</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-car"></i></span>
                                                        </div>
                                                        <input type="text" disabled style="text-transform:capitalize" class="form-control" id="modelo2" name="modelo2" placeholder="Modelo y Color de Vehiculo N° 2">
                                                    </div>
                                                </div>
                                            </div>

                                        </fieldset>

                                        <br>
                                        <br>


                                        <div class="form-group">
                                            <input type="hidden" name="idAdmTecnico" id="idAdmTecnico" value="<?php echo $idUsuario; ?>">
                                            <input type="hidden" id="nomTecnicoTurno" name="nomTecnicoTurno" value="" />
                                            <input type="submit" class="btn btn-block btn-primary" value="Guardar" />
                                        </div>
                                        <div class="form-group">
                                            <a href="index.php" class="btn btn-block btn-warning"> Volver </a>
                                        </div>

                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->

                            </div>
                            <!--/.col  -->

                        </div>
                    </form>
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
    <script src="js/dark-mode.js"></script>
    <!-- jQuery -->
    <script src="../plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>
    <!-- Page specific script -->
    <script>
        $(function() {
            bsCustomFileInput.init();
        });
    </script>
    <script>
        $('#selectTecnicoTurno').change(function() {
            var id = $(this).find(':selected')[0].value;
            $('#nomTecnicoTurno').val(id);
        })
    </script>

</body>

</html>