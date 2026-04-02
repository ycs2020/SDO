<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reporte de Turnos</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini dark-mode">
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
                            <h1>Formulario de Reportes</h1>
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
                    <form action="../sdo-funciones/reportes-generar.php" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <!-- left column -->
                            <div class="col-md-12">

                                <!-- general form elements -->
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Formulario de Reportes</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <?php


                                    if (isset($_GET['errorGenerar'])) {
                                        echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Crear Reporte. Reintente.</h5></div>';
                                    }


                                    ?>
                                    <div class="card-body">
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
                                                    <input type="datetime" readonly="readonly" id="fechaActualReporte" name="fechaActualReporte" class="form-control" value="<?php echo date('d-m-Y'); ?>" />

                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputNomEncargadoTurno">T&eacute;cnico Encargado de Instrumentaci&oacute;n en Turno:<code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                    </div>
                                                    <input type="text" readonly="readonly" required style="text-transform:capitalize" class="form-control" id="nomEncargadoTurno" name="nomEncargadoTurno" value="<?php echo $nomUsuario; ?>" placeholder="T&eacute;cnico Encargado de Instrumentaci&oacute;n en Turno">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputNomEncargadoTurno">Funcionarios de Especialidades de Turno: (Separar nombres con: ' , ' )<code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-users"></i></span>
                                                    </div>
                                                    <input type="text" required style="text-transform:capitalize" class="form-control" id="nomFuncionariosTurno" name="nomFuncionariosTurno" placeholder="Funcionarios de Especialidades de Turno">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputNomEncargadoTurnoAnterior">T&eacute;cnico Encargado de Instrumentaci&oacute;n en Turno Anterior:<code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                    </div>
                                                    <input type="text" required style="text-transform:capitalize" class="form-control" id="nomEncargadoTurnoAnterior" name="nomEncargadoTurnoAnterior" placeholder="T&eacute;cnico Encargado de Instrumentaci&oacute;n en Turno Anterior">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Turno realizado:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                    </div>
                                                    <select id="listaTurno" class="form-control" name="listaTurno">
                                                        <option value="Dia">Dia (08:00 - 20:00)</option>
                                                        <option value="Noche">Noche (20:00 - 08:00)</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputtareasControlCentralizado">Eventos Relevantes en Control Centralizado:</label>
                                                <textarea id="tareasControlCentralizado" style="resize:none;" name="tareasControlCentralizado" class="form-control" rows="8" placeholder="Eventos Relevantes en Control Centralizado"></textarea>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputNomEncargadoTurno">Eventos Relevantes en Inst. T&eacute;rmicas, Climatizaci&oacute;n y Refrigeraci&oacute;n:</label>
                                                <textarea id="tareasInstalaciones" style="resize:none;" name="tareasInstalaciones" class="form-control" rows="8" placeholder="Eventos Relevantes en Inst. T&eacute;rmicas, Climatizaci&oacute;n y Refrigeraci&oacute;n"></textarea>

                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTareasControlCent">Eventos Relevantes en Equipos Industriales:</label>
                                                <textarea id="tareasEquiposIndustriales" style="resize:none;" name="tareasEquiposIndustriales" class="form-control" rows="8" placeholder="Eventos Relevantes en Equipos Industriales"></textarea>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputNomEncargadoTurno">Eventos Relevantes en Detecci&oacute;n de Incendios y Combustibles:</label>
                                                <textarea id="tareasIncendiosCombustibles" style="resize:none;" name="tareasIncendiosCombustibles" class="form-control" rows="8" placeholder="Eventos Relevantes en Detecci&oacute;n de Incendios y Combustibles"></textarea>

                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTareasControlCent">Eventos Relevantes en Detecci&oacute;n de Instalaci&oacute;n El&eacute;ctrica:</label>
                                                <textarea id="tareasDeteccionElectrica" style="resize:none;" name="tareasDeteccionElectrica" class="form-control" rows="8" placeholder="Eventos Relevantes en Detecci&oacute;n de Instalaci&oacute;n El&eacute;ctrica"></textarea>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputNomEncargadoTurno">Eventos Relevantes en Infraestructura:</label>
                                                <textarea id="tareasInfraestructura" style="resize:none;" name="tareasInfraestructura" class="form-control" rows="8" placeholder="Eventos Relevantes en Infraestructura"></textarea>

                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputNomEncargadoTurno">Pendientes de Coordinaci&oacute;n y/o Ejecuci&oacute;n:</label>
                                                <textarea id="pendientesCoordinacion" style="resize:none;" name="pendientesCoordinacion" class="form-control" rows="8" placeholder="Pendientes de Coordinaci&oacute;n y/o Ejecuci&oacute;n"></textarea>

                                            </div>
                                        </div>

                                        <hr>

                                        <div class="form-group">
                                            <!-- <label for="customFile">Custom File</label> -->
                                            <label for="exampleInputTareasControlCent">Subir Imagenes: (Maximo 10 Imagenes)<code>*</code></label><br>
                                            <label for="exampleInputTareasControlCent">Formatos Permitidos: .jpg, .png, .jpeg, .bmp, .tif, .webp, .svg, .jfif</label>
                                            <div class="custom-file">
                                                <input type="file" max-uploads="10" class="custom-file-input" multiple="" id="archivoImagen[]" name="archivoImagen[]" accept=".jpg, .png, .jpeg, .bmp, .tif, .webp, .svg, .jfif">
                                                <label class="custom-file-label" for="customFile">Seleccionar Archivo...</label>
                                            </div>
                                        </div>

                                        <hr>

                                        <br>
                                        <br>

                                        <div class="form-group">
                                            <input type="hidden" name="idUsuario" id="idUsuario" value="<?php echo $idUsuario; ?>">
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