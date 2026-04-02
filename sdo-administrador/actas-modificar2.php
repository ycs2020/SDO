<?php

include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';

if (isset($_POST['idActa'])) {

    $idActa = trim($_POST['idActa']);

    if ($idActa > 0) {

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
    } else {
        header('Location: ../sdo-administrador/actas-funcionario.php?errorModificarActa=true');
    }
} else {
    header('Location: ../sdo-administrador/actas-funcionario.php?errorModificarActa=true');
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modificar Acta</title>

    <link rel="icon" href="../dist/img/controlCent-HBQP-120.png" type="image/icon type">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">

</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php
        include '../sdo-templates/admin-sidebar.php';
        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Modificar Acta</h1>
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
                        <!-- left column -->
                        <div class="col-md-12">

                            <!-- general form elements -->
                            <div class="card">
                                <div class="card-header">

                                    

                                    <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <br>
                                    <br>
                                    <br>
                                    <?php


                                    if (isset($_GET['errorCrearActa'])) {
                                        echo '<div class="alert alert-danger alert-dismissible">
                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Crear Acta. Reintente.</h5></div>';
                                    }


                                    ?>

                                </div>
                                <!-- /.card-header -->


                                <div class="card-body">

                                    <form action="../sdo-funciones/actas-modificar.php" method="post" enctype="multipart/form">

                                        <fieldset class="border p-3">
                                            <legend class="w-auto">T&eacute;cnico de Turno de Control Centralizado:</legend>

                                            <div class="form-row">


                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputNomTarjeta">T&eacute;cnico de Turno de Control Centralizado:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                        </div>

                                                        <select class="form-control" name="tecnicoControl" id="tecnicoControl">

                                                            <option value="">Seleccione...</option>

                                                            <?php

                                                            $queryTecnicos = "SELECT * FROM personal WHERE departamentoPersonal = 1 ORDER BY nomPersonal ASC";

                                                            $resTecnicos = mysqli_query($conexion, $queryTecnicos);

                                                            while ($rowTecnicos = $resTecnicos->fetch_assoc()) {

                                                                if ($rowTecnicos['nomPersonal'] == $nomControlCentralizado) {
                                                            ?>
                                                                    <option value="<?php echo $rowTecnicos['nomPersonal'] ?>" selected="selected"><?php echo $rowTecnicos['nomPersonal'] ?></option>
                                                                <?php
                                                                } else {
                                                                ?>
                                                                    <option value="<?php echo $rowTecnicos['nomPersonal'] ?>"><?php echo $rowTecnicos['nomPersonal'] ?></option>
                                                            <?php
                                                                }
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
                                                    <label for="exampleInputNomTarjeta">Nombre de Funcionario:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                        </div>
                                                        <input type="text" required style="text-transform:capitalize" class="form-control" id="nomTarjeta" name="nomTarjeta" value="<?php echo $nomTarjeta ?>" placeholder="Nombre y Apellido de Usuario de Tarjeta">
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputNomTarjeta">RUT de Funcionario:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                        </div>
                                                        <input type="text" style="text-transform:capitalize" class="form-control" id="rutTarjeta" name="rutTarjeta" value="<?php echo $rutTarjeta ?>" placeholder="RUT de Usuario de Tarjeta (Formato XXXXXXXXX, la K es 0)">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-row">


                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputNomTarjeta">Calidad Contractual de Funcionario:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                        </div>
                                                        <select class="form-control" name="calidadContractual" id="calidadContractual">

                                                            <?php

                                                            if ($calidadContractual == 'Seleccione') {
                                                            ?>
                                                                <option value="Seleccione" selected="selected">Seleccione...</option>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <option value="Seleccione">Seleccione...</option>
                                                            <?php
                                                            }

                                                            if ($calidadContractual == 'Alumno en Practica') {
                                                            ?>
                                                                <option value="Alumno en Practica" selected="selected">Alumno en Practica</option>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <option value="Alumno en Practica">Alumno en Practica</option>
                                                            <?php
                                                            }


                                                            if ($calidadContractual == 'Contrata') {
                                                            ?>
                                                                <option value="Contrata" selected="selected">Contrata</option>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <option value="Contrata">Contrata</option>
                                                            <?php
                                                            }


                                                            if ($calidadContractual == 'Empresa Externa') {
                                                            ?>
                                                                <option value="Empresa Externa" selected="selected">Empresa Externa</option>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <option value="Empresa Externa">Empresa Externa</option>
                                                            <?php
                                                            }


                                                            if ($calidadContractual == 'Honorarios') {
                                                            ?>
                                                                <option value="Honorarios" selected="selected">Honorarios</option>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <option value="Honorarios">Honorarios</option>
                                                            <?php
                                                            }


                                                            if ($calidadContractual == 'Reemplazo') {
                                                            ?>
                                                                <option value="Reemplazo" selected="selected">Reemplazo</option>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <option value="Reemplazo">Reemplazo</option>
                                                            <?php
                                                            }


                                                            if ($calidadContractual == 'Titular') {
                                                            ?>
                                                                <option value="Titular" selected="selected">Titular</option>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <option value="Titular">Titular</option>
                                                            <?php
                                                            }

                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputCodigoTarjeta">N&uacute;mero de Contacto:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                        </div>
                                                        <input type="text" style="text-transform:capitalize" class="form-control" id="numTelefono" name="numTelefono" value="<?php echo $numTelefono ?>" placeholder="+569XXXXXXXX">
                                                    </div>
                                                </div>
                                            </div>

                                        </fieldset>

                                        <br>
                                        <fieldset class="border p-3">
                                            <legend class="w-auto">Informaci&oacute;n de la Tarjeta:</legend>

                                            <div class="form-row">


                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputCodigoTarjeta">Codigo de Tarjeta:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-code"></i></span>
                                                        </div>
                                                        <input type="text" style="text-transform:uppercase" class="form-control" id="codigoTarjeta" name="codigoTarjeta" value="<?php echo $codigoTarjeta ?>" placeholder="Codigo de Tarjeta">
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
                                                        <input type="text" required class="form-control" id="unidadTarjeta" name="unidadTarjeta" value="<?php echo $unidadTarjeta ?>" placeholder="Unidad del Usuario de Tarjeta">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputCodigoTarjeta">Nombre de Quien Solicita:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                        </div>
                                                        <input type="text" required style="text-transform:capitalize" class="form-control" id="nomSolicitante" value="<?php echo $nomSolicitante ?>" name="nomSolicitante" placeholder="Nombre de Quien Solicita">
                                                    </div>
                                                </div>
                                            </div>

                                        </fieldset>


                                        <br>

                                        <fieldset class="border p-3">
                                            <legend class="w-auto">&nbsp;Informaci&oacute;n Adicional:&nbsp;</legend>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">

                                                    <div class="custom-control custom-checkbox">
                                                        <?php
                                                        if ($personaBecada == 1) {
                                                        ?>
                                                            <input class="custom-control-input" checked type="checkbox" id="chkBecado" name="chkBecado" value="Becado">
                                                            <label id="" for="chkBecado" class="custom-control-label">Persona Becada</label>
                                                            <input type="hidden" name="valorBeca" id="valorBeca" value="SI">
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <input class="custom-control-input" type="checkbox" id="chkBecado" name="chkBecado" value="Becado">
                                                            <label id="" for="chkBecado" class="custom-control-label">Persona Becada</label>
                                                            <input type="hidden" name="valorBeca" id="valorBeca" value="NO">
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>
                                                    <script type="text/javascript">
                                                        document.getElementById('chkBecado').onchange = function() {
                                                            var esBecado = document.getElementById('valorBeca').value;

                                                            if (esBecado == 'SI') {
                                                                document.getElementById('valorBeca').value = 'NO';
                                                            } else if (esBecado == 'NO') {
                                                                document.getElementById('valorBeca').value = 'SI';
                                                            } else {
                                                                document.getElementById('valorBeca').value = 'NO';
                                                            }
                                                        };
                                                    </script>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">

                                                    <div class="custom-control custom-checkbox">
                                                        <?php
                                                        if ($personaDiscapacitada == 1) {
                                                        ?>
                                                            <input class="custom-control-input" checked type="checkbox" id="checkBoxDiscapacidad" name="checkBoxDiscapacidad" value="Discapacitado">
                                                            <label id="" for="checkBoxDiscapacidad" class="custom-control-label">Persona con Discapacidad</label>
                                                            <input type="hidden" name="valorDiscapacidad" id="valorDiscapacidad" value="SI">
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <input class="custom-control-input" type="checkbox" id="checkBoxDiscapacidad" name="checkBoxDiscapacidad" value="Discapacitado">
                                                            <label id="" for="checkBoxDiscapacidad" class="custom-control-label">Persona con Discapacidad</label>
                                                            <input type="hidden" name="valorDiscapacidad" id="valorDiscapacidad" value="NO">
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>
                                                    <script type="text/javascript">
                                                        document.getElementById('checkBoxDiscapacidad').onchange = function() {
                                                            var esDiscapacitado = document.getElementById('valorDiscapacidad').value;

                                                            if (esDiscapacitado == 'SI') {
                                                                document.getElementById('valorDiscapacidad').value = 'NO';
                                                            } else if (esDiscapacitado == 'NO') {
                                                                document.getElementById('valorDiscapacidad').value = 'SI';
                                                            } else {
                                                                document.getElementById('valorDiscapacidad').value = 'NO';
                                                            }
                                                        };
                                                    </script>
                                                </div>
                                            </div>
                                        </fieldset>

                                        <hr>



                                        <div class="form-group">
                                            <input type="hidden" name="rolUsuario" id="rolUsuario" value="<?php echo $rolUsuario; ?>">
                                            <input type="hidden" name="nomControlCentralizado" id="nomControlCentralizado" value="">
                                            <input type="hidden" name="antiguoRutTarjeta" id="antiguoRutTarjeta" value="<?php echo $rutTarjeta; ?>">
                                            <input type="hidden" name="idActa2" id="idActa2" value="<?php echo $idActa; ?>">
                                            <input type="submit" class="btn btn-block btn-primary" value="Guardar" />
                                        </div>

                                    </form>
                                    <div class="form-group">
                                        <a href="actas-listado.php" class="btn btn-block btn-warning"> Volver </a>
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
        $('#tecnicoControl').change(function() {
            var id = $(this).find(':selected')[0].value;
            $('#nomControlCentralizado').val(id);
        })
    </script>
    <script src="js/dark-mode.js"></script>

</body>

</html>