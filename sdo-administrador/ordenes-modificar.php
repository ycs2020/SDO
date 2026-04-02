<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


if (isset($_POST['idOrden'])) {

    $idOrden = trim($_POST['idOrden']);

    if (!empty($idOrden)) {

        $datosOrden = array();
        $datosOrden = getOrdenTrabajo($idOrden);

        $idOrden2 = $datosOrden['idOrden'];
        $folioOrden = trim($datosOrden['folioOrden']);
        $nomOrden = trim($datosOrden['nomOrden']);
        $fechaOrden = $datosOrden['fechaOrden'];
        $fechaOrden2 = date("d-m-Y", strtotime($fechaOrden));
        $nomSolicitante = trim($datosOrden['nomSolicitante']);
        $idDepartamentoOrden = $datosOrden['idDepartamento'];
        $nomDepartamentoOrden = trim($datosOrden['nomDepartamento']);
        $idSubDepartamento = $datosOrden['idSubDepartamento'];
        $nomSubDepartamento = trim($datosOrden['nomSubDepartamento']);
        $servicioOrden = $datosOrden['servicioOrden'];
        $recintoOrden = trim($datosOrden['recintoOrden']);
        $pmaOrden = trim($datosOrden['pmaOrden']);
        $coordenadasOrden = trim($datosOrden['coordenadasOrden']);
        $detalleTrabajo = $datosOrden['detalleTrabajo'];
        $comentariosTrabajos = $datosOrden['comentariosTrabajos'];
        $recepcionistaOrden = trim($datosOrden['recepcionistaOrden']);
        $ejecutorOrden = trim($datosOrden['ejecutorOrden']);
        $autorizadorOrden = trim($datosOrden['autorizadorOrden']);
    } else {
        header('Location: ../sdo-administrador/ordenes-administrar.php?errorModificarReporte=true');
    }
} else {
    header('Location: ../sdo-administrador/ordenes-administrar.php?errorModificarReporte=true');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modificar Orden de Trabajo</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            recargarSubDepartamento();
            recargarNomServicio();

            $('#departamentoSolicitante').change(function() {
                recargarSubDepartamento();
            });

            $('#servicioOrden').change(function() {
                recargarNomServicio();
            });


            function recargarSubDepartamento() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-departamentos/mostrarSubDepartamentos.php",
                    data: "idDepartamento=" + $('#departamentoSolicitante').val(),
                    success: function(r) {
                        $('#visualizarSubDepartamento').html(r);


                    }
                });

            };

            function recargarNomServicio() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-servicios/habilitarIngresoServicio.php",
                    data: "idServicio=" + $('#servicioOrden').val(),
                    success: function(r) {
                        $('#visualizarNomServicio').html(r);


                    }
                });

            };
        });
    </script>
</head>

<body class="hold-transition sidebar-mini dark-mode">
    <div class="wrapper">
        <?php
        include '../sdo-templates/admin-sidebar.php';

        $_SESSION['idOrden'] = $idOrden;
        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Modificar Orden</h1>
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
                    <form action="../sdo-funciones/ordenes-modificarTexto.php" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <!-- left column -->
                            <div class="col-md-12">

                                <!-- general form elements -->
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Modificar Orden</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <?php


                                    if (isset($_GET['errorModificar'])) {
                                        echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Modificar Orden. Reintente.</h5></div>';
                                    }


                                    ?>
                                    <div class="card-body">

                                        <div class="form-row">

                                            <div class="form-group col-md-6">
                                                <label for="exampleInputFecha">Fecha Actual:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                    </div>
                                                    <?php
                                                    date_default_timezone_set('America/Santiago');
                                                    ?>
                                                    <input type="datetime" readonly="readonly" id="fechaActualOrden2" name="fechaActualOrden2" class="form-control" value="<?php echo $fechaOrden2; ?>" />
                                                    <input type="hidden" id="fechaActualOrden" name="fechaActualOrden" class="form-control" value="<?php echo $fechaOrden; ?>" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputNomSolicitante">Nombre Solicitante:<code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                    </div>
                                                    <input type="text" readonly="readonly" style="text-transform:capitalize" value="<?php echo $nomSolicitante; ?>" class="form-control" id="nomSolicitante" name="nomSolicitante" placeholder="Nombre Solicitante">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputDepartamento">Departamento:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-server"></i></span>
                                                    </div>
                                                    <select readonly="readonly" style="pointer-events: none;" id="departamentoSolicitante" class="form-control" name="departamentoSolicitante">
                                                        <?php

                                                        $queryDepartamentos = "SELECT * FROM departamentos ORDER BY nomDepartamento ASC";

                                                        $resDepartamentos = mysqli_query($conexion, $queryDepartamentos);

                                                        while ($rowDepartamentos = $resDepartamentos->fetch_assoc()) {

                                                            if ($rowDepartamentos['idDepartamento'] == $idDepartamentoOrden) {
                                                        ?>
                                                                <option value="<?php echo $rowDepartamentos['idDepartamento']; ?>" selected="selected">
                                                                    <?php echo $rowDepartamentos['nomDepartamento']; ?>
                                                                </option>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <option value="<?php echo $rowDepartamentos['idDepartamento']; ?>">
                                                                    <?php echo $rowDepartamentos['nomDepartamento']; ?>
                                                                </option>
                                                        <?php
                                                            }
                                                        }


                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div name="visualizarSubDepartamento" id="visualizarSubDepartamento" class="form-group col-md-6">

                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Servicio:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-server"></i></span>
                                                    </div>
                                                    <select readonly="readonly" style="pointer-events: none;" id="servicioOrden" class="form-control" name="servicioOrden">
                                                        <?php

                                                        $queryServicios = "SELECT * FROM servicios ORDER BY nomServicio ASC";

                                                        $resServicios = mysqli_query($conexion, $queryServicios);

                                                        while ($rowServicios = $resServicios->fetch_assoc()) {

                                                            if ($rowServicios['idServicio'] == $servicioOrden) {
                                                        ?>
                                                                <option value="<?php echo $rowServicios['idServicio']; ?>" selected="selected">
                                                                    <?php echo $rowServicios['nomServicio']; ?>
                                                                </option>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <option value="<?php echo $rowServicios['idServicio']; ?>">
                                                                    <?php echo $rowServicios['nomServicio']; ?>
                                                                </option>
                                                        <?php
                                                            }
                                                        }


                                                        ?>
                                                        <option value="Otro">
                                                            Otro
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div name="visualizarNomServicio" id="visualizarNomServicio" class="form-group col-md-6">

                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputRecinto">Recinto:<code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-map-marker"></i></span>
                                                    </div>
                                                    <input type="text" readonly="readonly" value="<?php echo $recintoOrden; ?>" required class="form-control" id="recintoOrden" name="recintoOrden" placeholder="Recinto">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label for="exampleInputPMA">PMA:<code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-map-marker"></i></span>
                                                    </div>
                                                    <input type="text" readonly="readonly" value="<?php echo $pmaOrden; ?>" required class="form-control" id="pmaOrden" name="pmaOrden" placeholder="PMA">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label for="exampleInputCoordenadas">Coordenadas:<code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-map-marker"></i></span>
                                                    </div>
                                                    <input type="text" readonly="readonly" value="<?php echo $coordenadasOrden; ?>" required class="form-control" id="coordenadasOrden" name="coordenadasOrden" placeholder="Coordenadas">
                                                </div>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="form-group">
                                            <label for="exampleInputDetalleTrabajo">Detalle del Trabajo:<code>*</code></label>
                                            <textarea id="detalleTrabajo" style="resize:none;" name="detalleTrabajo" class="form-control" rows="12" placeholder="Detalle del Trabajo"><?php echo $detalleTrabajo; ?></textarea>
                                        </div>


                                        <?php

                                        $queryMateriales = "SELECT idMaterial, nomMaterial, unidadMaterial, cantidadMaterial FROM material_orden WHERE idOrden = " . $idOrden . " ORDER BY nomMaterial";

                                        $resMateriales = mysqli_query($conexion, $queryMateriales);

                                        $contarMateriales = contarMateriales($idOrden);

                                        $quedanMateriales = 100 - $contarMateriales;

                                        $numMaterial = 1;

                                        while ($rowMateriales = $resMateriales->fetch_assoc()) {


                                            if ($numMaterial == 1) {
                                        ?>
                                                <div class="form-row fieldGroup">
                                                    <div class="form-group col-md-4">
                                                        <label for="exampleInputRecinto">Material Usado:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-list"></i></span>
                                                            </div>
                                                            <input type="text" readonly="readonly" class="form-control" id="materialUsado[]" name="materialUsado[]" placeholder="Materiales Usados" value="<?php echo $rowMateriales['nomMaterial']; ?>">
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-4">
                                                        <label for="exampleInputPMA">Unidad:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                                            </div>
                                                            <input type="text" readonly="readonly" class="form-control" id="unidad[]" name="unidad[]" placeholder="Unidad" value="<?php echo $rowMateriales['unidadMaterial']; ?>">
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-4">
                                                        <label for="exampleInputCoordenadas">Cantidad:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                                            </div>
                                                            <input type="number" readonly="readonly" min="1" class="form-control" id="cantidad[]" name="cantidad[]" placeholder="Cantidad" value="<?php echo $rowMateriales['cantidadMaterial']; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                            } else if ($numMaterial > 1) {
                                            ?>
                                                <div class="form-row fieldGroup">
                                                    <div class="form-group col-md-4">
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-list"></i></span>
                                                            </div>
                                                            <input type="text" readonly="readonly" class="form-control" id="materialUsado[]" name="materialUsado[]" placeholder="Materiales Usados" value="<?php echo $rowMateriales['nomMaterial']; ?>">
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-4">
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                                            </div>
                                                            <input type="text" readonly="readonly" style="text-transform:capitalize" class="form-control" id="unidad[]" name="unidad[]" placeholder="Unidad" value="<?php echo $rowMateriales['unidadMaterial']; ?>">
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-4">
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                                            </div>
                                                            <input type="text" readonly="readonly" style="text-transform:capitalize" class="form-control" id="cantidad[]" name="cantidad[]" placeholder="Cantidad" value="<?php echo $rowMateriales['cantidadMaterial']; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                            } else {
                                            ?>
                                                <div class="form-row fieldGroup">
                                                    <div class="form-group col-md-4">
                                                        <label for="exampleInputRecinto">Material Usado:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-list"></i></span>
                                                            </div>
                                                            <input type="text" readonly="readonly" class="form-control" id="materialUsado[]" name="materialUsado[]" placeholder="Materiales Usados">
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-4">
                                                        <label for="exampleInputPMA">Unidad:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                                            </div>
                                                            <input type="text" readonly="readonly" class="form-control" id="unidad[]" name="unidad[]" placeholder="Unidad">
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-4">
                                                        <label for="exampleInputCoordenadas">Cantidad:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                                            </div>
                                                            <input type="number" readonly="readonly" min="1" class="form-control" id="cantidad[]" name="cantidad[]" placeholder="Cantidad">
                                                        </div>
                                                    </div>

                                                </div>
                                            <?php
                                            }

                                            ?>

                                        <?php
                                            $numMaterial++;
                                        }

                                        ?>




                                        <hr>



                                        <div class="form-group">
                                            <label for="exampleInputComentarios">Comentarios:</label>
                                            <textarea id="comentariosTrabajos" style="resize:none;" name="comentariosTrabajos" class="form-control" rows="8" placeholder="Comentarios"><?php echo $comentariosTrabajos; ?></textarea>
                                        </div>


                                        <br>
                                        <hr>
                                        <br>
                                        <div class="form-group">
                                            <label for="exampleInputNomSolicitante">Recepcionado por:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" readonly="readonly" style="text-transform:capitalize" value="<?php echo $recepcionistaOrden; ?>" class="form-control" id="recepcionistaOrden" name="recepcionistaOrden" placeholder="Recepcionado por">
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <label for="exampleInputNomSolicitante">Ejecutado por:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" readonly="readonly" style="text-transform:capitalize" value="<?php echo $ejecutorOrden; ?>" class="form-control" id="ejecutorOrden" name="ejecutorOrden" placeholder="Ejecutado por">
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <label for="exampleInputNomSolicitante">Autorizado por:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" readonly="readonly" style="text-transform:capitalize" value="<?php echo $autorizadorOrden; ?>" class="form-control" id="autorizadorOrden" name="autorizadorOrden" placeholder="Autorizado por">
                                            </div>
                                        </div>

                                        <hr>

                                        <br>
                                        <br>

                                        <div class="form-group">
                                            <input type="hidden" name="idOrden" id="idOrden" value="<?php echo $idOrden; ?>">
                                            <input type="submit" class="btn btn-block btn-primary" value="Guardar" />

                                        </div>
                                        <div class="form-group">
                                            <a href="ordenes-administrar.php" class="btn btn-block btn-warning"> Volver </a>
                                        </div>

                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->

                            </div>
                            <!--/.col  -->

                        </div>
                    </form>

                    <div class="form-row fieldGroupCopy" style="display: none;">
                        <div class="form-group col-md-4">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-list"></i></span>
                                </div>
                                <input type="text" class="form-control" id="materialUsado[]" name="materialUsado[]" placeholder="Materiales Usados">
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                </div>
                                <input type="text" style="text-transform:capitalize" class="form-control" id="unidad[]" name="unidad[]" placeholder="Unidad">
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                </div>
                                <input type="text" style="text-transform:capitalize" class="form-control" id="cantidad[]" name="cantidad[]" placeholder="Cantidad">
                            </div>
                        </div>
                        <div class="form-group col-md-2">
                            <div class="input-group mb-3">
                                <a href="javascript:void(0)" class="btn btn-block btn-danger removeMaterial"><span class="glyphicon glyphicon glyphicon-remove" aria-hidden="true"></span> Eliminar</a>
                            </div>
                        </div>
                    </div>

                    <script type="text/javascript">
                        $(document).ready(function() {
                            //group add limit
                            var maxGroup = <?php echo $quedanMateriales ?>;

                            //add more fields group
                            $(".addMaterial").click(function() {
                                if ($('body').find('.fieldGroup').length < maxGroup) {
                                    var fieldHTML = '<div class="form-row fieldGroup">' + $(".fieldGroupCopy").html() + '</div>';
                                    $('body').find('.fieldGroup:last').after(fieldHTML);

                                } else {
                                    alert('Limite de Materiales Completados. Solo se permiten ' + maxGroup + ' Materiales.');
                                }
                            });

                            //remove fields group
                            $("body").on("click", ".removeMaterial", function() {
                                $(this).parents(".fieldGroup").remove();
                            });
                        });
                    </script>
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

</body>

</html>