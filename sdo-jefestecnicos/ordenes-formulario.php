<?php

include_once '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulario de Recepción de Trabajo</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="css/dark-mode.css">

    <script type="text/javascript">
        $(document).ready(function() {
            recargarSubDepartamento();
            recargarNomDepartamento();

            $('#departamentoSolicitante').change(function() {
                recargarSubDepartamento();
            });

            $('#servicioOrden').change(function() {
                recargarNomDepartamento();
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

            function recargarNomDepartamento() {

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

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
    <div class="wrapper">
        <?php
        include '../sdo-templates/jefes-sidebar.php';
        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Formulario de Recepción de Trabajo</h1>
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
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Formulario de Recepción de Trabajo</h3>
                                </div>
                                <!-- /.card-header -->
                                <?php


                                if (isset($_GET['errorGenerar'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Crear Recepción de Trabajo. Reintente.</h5></div>';
                                }


                                ?>

                                <form action="../sdo-funciones/jefe-ordenes-generar.php" method="post" enctype="multipart/form-data">
                                    <div class="card-body">
                                     <!--   <div class="form-group">
                                            <label class="switch" style="float:right">
                                                <input type="checkbox" id="customSwitch3">
                                                <span class="slider"></span>
                                            </label>
                                        </div> -->


                                        <br>

                                        <script>
                                            function toggleDark() {

                                                document.body.classList.toggle("dark-mode");

                                            }
                                        </script>
                                        <br>
                                        <br>
                                        <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                        <br>
                                        <br>

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
                                                    <input type="datetime" readonly="readonly" id="fechaActualOrden" name="fechaActualOrden" class="form-control" value="<?php echo date('d-m-Y'); ?>" />

                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputQuienSolicita">Persona que Solicita:<code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                    </div>
                                                    <input type="text" style="text-transform:capitalize" class="form-control" id="nomSolicitante" name="nomSolicitante" value="" placeholder="Persona que Solicita">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputNomSolicitante">Tecnico Recepcionador:<code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                    </div>
                                                    <select id="nomTecnicoRecepcionador" class="form-control" name="nomTecnicoRecepcionador">
                                                        <?php

                                                        $queryTecnicos = "SELECT * FROM personal WHERE departamentoPersonal = " . $idDepartamento . " AND activoPersonal = 1 ORDER BY nomPersonal ASC";

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

                                                            if ($rowDepartamentos['idDepartamento'] == $idDepartamento) {
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
                                                    <select id="servicioOrden" class="form-control" name="servicioOrden">
                                                        <?php

                                                        $queryServicios = "SELECT * FROM servicios ORDER BY nomServicio ASC";

                                                        $resServicios = mysqli_query($conexion, $queryServicios);

                                                        while ($rowServicios = $resServicios->fetch_assoc()) {

                                                        ?>
                                                            <option value="<?php echo $rowServicios['idServicio']; ?>">
                                                                <?php echo $rowServicios['nomServicio']; ?>
                                                            </option>
                                                        <?php

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
                                                    <input type="text" required class="form-control" id="recintoOrden" name="recintoOrden" placeholder="Recinto">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label for="exampleInputPMA">PMA:<code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-map-marker"></i></span>
                                                    </div>
                                                    <input type="text" required class="form-control" id="pmaOrden" name="pmaOrden" placeholder="PMA">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label for="exampleInputCoordenadas">Coordenadas:<code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-map-marker"></i></span>
                                                    </div>
                                                    <input type="text" required class="form-control" id="coordenadasOrden" name="coordenadasOrden" placeholder="Coordenadas">
                                                </div>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="form-group">
                                            <label for="exampleInputDetalleTrabajo">Detalle del Trabajo:<code>*</code></label>
                                            <textarea id="detalleTrabajo" style="resize:none;" name="detalleTrabajo" class="form-control" rows="12" placeholder="Detalle del Trabajo"></textarea>
                                        </div>

                                        <div class="form-row fieldGroup">
                                            <div class="form-group col-md-4">
                                                <label for="exampleInputRecinto">Materiales Usados:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-list"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" id="materialUsado[]" name="materialUsado[]" placeholder="Materiales Usados">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label for="exampleInputPMA">Unidad:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" id="unidad[]" name="unidad[]" placeholder="Unidad">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label for="exampleInputCoordenadas">Cantidad:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                                    </div>
                                                    <input type="number" min="1" class="form-control" id="cantidad[]" name="cantidad[]" placeholder="Cantidad">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="exampleInputCoordenadas">&nbsp;</label>
                                                <div class="input-group mb-3">
                                                    <a href="javascript:void(0)" class="btn btn-block btn-success addMore"><span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>Agregar Material Usado</a>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>



                                        <div class="form-group">
                                            <label for="exampleInputComentarios">Comentarios:</label>
                                            <textarea id="comentariosTrabajos" style="resize:none;" name="comentariosTrabajos" class="form-control" rows="8" placeholder="Comentarios"></textarea>
                                        </div>

                                        <div class="form-group">
                                            <!-- <label for="customFile">Custom File</label> -->
                                            <label for="exampleInputTareasControlCent">Subir Imagenes: (Maximo 10 Imagenes)<code>*</code></label><br>
                                            <label for="exampleInputTareasControlCent">Formatos Permitidos: .jpg, .png, .jpeg, .bmp, .tif, .webp, .svg, .jfif</label>
                                            <div class="custom-file">
                                                <input type="file" max-uploads="10" class="custom-file-input" multiple="" id="archivoImagenOrden[]" name="archivoImagenOrden[]" accept=".jpg, .png, .jpeg, .bmp, .tif, .webp, .svg, .jfif">
                                                <label class="custom-file-label" for="customFile">Seleccionar Archivo...</label>
                                            </div>
                                        </div>





                                        <hr>

                                        <br>
                                        <br>

                                        <div class="form-group">
                                            <input type="hidden" name="idSubDep" id="idSubDep" value="<?php echo $idSubDepartamento; ?>">
                                            <input type="hidden" name="idUsuario" id="idUsuario" value="<?php echo $idUsuario; ?>">
                                            <input type="submit" class="btn btn-block btn-primary" value="Guardar" />

                                        </div>
                                        <div class="form-group">
                                            <a href="index.php" class="btn btn-block btn-warning"> Volver </a>
                                        </div>

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
                                            <a href="javascript:void(0)" class="btn btn-block btn-danger remove"><span class="glyphicon glyphicon glyphicon-remove" aria-hidden="true"></span> Eliminar</a>
                                        </div>
                                    </div>
                                </div>


                                <script type="text/javascript">
                                    $(document).ready(function() {
                                        //group add limit
                                        var maxGroup = "100";

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
    <script src="js/dark-mode.js"></script>
</body>

</html>