<?php

include_once '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            recargarSubDepartamento();
            recargarNomDepartamento();
            recargarCantidadInventario();

            $('#departamentoSolicitante').change(function() {
                recargarSubDepartamento();
            });

            $('#servicioOrden').change(function() {
                recargarNomDepartamento();
            });

            $('#inventarioOrden').change(function() {
                recargarCantidadInventario();
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

            function recargarCantidadInventario() {
                $.ajax({
                    type: "POST",
                    url: "sdo-datos-inventario/habilitarCantidadInventario.php",
                    data: "idInventario=" + $('#inventarioOrden').val(),
                    success: function(r) {
                        $('#visualizarNomInventario').html(r);
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

                            <h3> <b>Formulario de Hojas Técnicas</b> </h3>
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
                                    <h4 class="card-title"> <b> Formulario de Hojas Técnicas</b></h4>
                                </div>
                                <!-- /.card-header -->
                                <?php

                                if (isset($_GET['errorGenerar'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Crear Recepción de Trabajo. Reintente.</h5></div>';
                                }
                                ?>

                                <form action="../sdo-funciones/ordenes-generar-general2.php" method="post" enctype="multipart/form-data">
                                    <div class="card-body">

                                        <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;<b>Volver Atras</b> </a>
                                        <br>
                                        <br>
                                        <p><code>*</code> <b style="color:red">Campo obligatorio</b> </p>
                                        <br>
                                    
                                        <div class="tab-content" id="pills-tabContent">
                                            <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">

                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label for="exampleInputQuienSolicita">Ticket Asociado:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-ticket-alt"></i></span>
                                                            </div>
                                                            <input type="text" style="text-transform:capitalize" class="form-control" id="ticketAsociado" name="ticketAsociado" value="" placeholder="Escriba el ticket asociado">
                                                        </div>
                                                    </div>
                                                </div>

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
                                                        <label for="exampleInputDepartamento">Departamento:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-server"></i></span>
                                                            </div>
                                                            <select id="departamentoSolicitante"  class="form-control" name="departamentoSolicitante">
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
                                                        <label for="exampleInputNomSolicitante">Tecnico Recepcionador:<code>*</code></label>
                                                        <div class="input-group mb-3">

                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                            </div>
                                                            <input type="text"  style="text-transform:capitalize" class="form-control" id="nomTecnicoRecepcionador" name="nomTecnicoRecepcionador" value="" placeholder=" Escriba El Nombre Funcionario Recepcionador">
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
                                                            <input type="text"  style="text-transform:capitalize" class="form-control" id="nomSolicitante" name="nomSolicitante" value="" placeholder="Persona que Solicita">
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label for="exampleInputTurno">Servicio:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-server"></i></span>
                                                            </div>
                                                            <select id="servicioOrden"  class="form-control" name="servicioOrden">
                                                                <?php

                                                                $queryServicios = "SELECT * FROM servicios ORDER BY nomServicio ASC";

                                                                $resServicios = mysqli_query($conexion, $queryServicios);

                                                                while ($rowServicios = $resServicios->fetch_assoc()) {

                                                                ?>
                                                                    <option value="<?php echo $rowServicios['idServicio']; ?>">
                                                                        <?php echo mb_convert_case($rowServicios['nomServicio'],MB_CASE_UPPER,"UTF-8"); ?>
                                                                    </option>
                                                                <?php
                                                                }

                                                                ?>
                                                                <option value="Otro">
                                                                    OTRO
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
                                                            <input type="text" required  class="form-control" id="recintoOrden" name="recintoOrden" placeholder="Recinto">
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-3">
                                                        <label for="exampleInputPMA">PMA:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-map-marker"></i></span>
                                                            </div>
                                                            <input type="text"  class="form-control" id="pmaOrden" name="pmaOrden" placeholder="PMA">
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-3">
                                                        <label for="exampleInputCoordenadas">Coordenadas:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-map-marker"></i></span>
                                                            </div>
                                                            <input type="text"  class="form-control" id="coordenadasOrden" name="coordenadasOrden" placeholder="Coordenadas">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div><!--Aqui termina la pestaña del solicitante -->

                                            <div class="tab-pane fade show active" id="pills-equipo" role="tabpanel" aria-labelledby="pills-equipo-tab">

                                                <div class="row col-md-12 col-12 text-center justify-content-center">
                                                    <button type="button" class="btn btn-primary text-center mb-5 ml-2" id="botonAddHT" data-toggle="modal" data-target="#modalHojas"><i class="fa-solid fas fa-plus"></i>&nbsp;&nbsp;<b>Añadir Equipo a la Hoja Técnica</b> </button>
                                                </div>

                                                <p class="text-muted">*Llene los datos manualmente, si el equipo que necesita aún no se encuentra ingresado en el sistema</p>
                                                <div class="modal fade" id="modalHojas" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalHojasLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xl">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-primary">
                                                                <h5 class="modal-title" id="modalHojasLabel">Escoja el equipamiento</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <table id="hoja_tecnicas" class="table table-sm table-hover  table-bordered">
                                                                    <thead>
                                                                    <tr>
                                                                        <th scope="col" class="text-center">N°</th>
                                                                        <th scope="col" class="text-center">Fecha Registro</th>
                                                                        <th scope="col" class="text-center">Nombre del Equipamiento</th>
                                                                        <th scope="col" class="text-center">Marca</th>
                                                                        <th scope="col" class="text-center">Recinto</th>
                                                                        <th scope="col" class="text-center">Servicio</th>
                                                                        <th scope="col" class="text-center">Seleccionar</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php

                                                                    $sqlHojaTec1 = "SELECT * FROM equipamiento_hbqp WHERE idDepaPertenece = $idDepartamento ORDER BY nombreEquipo ASC";
                                                                    $resHojaTec1 = mysqli_query($conexion, $sqlHojaTec1);

                                                                    $num = 1;
                                                                    while($rowHojaTec1 = $resHojaTec1->fetch_assoc()){
                                                                        $datos = [];
                                                                        $datos['id'] = $rowHojaTec1['id'];
                                                                        $datos['fechaRegistro'] = $rowHojaTec1['fechaRegistro'];
                                                                        $datos['nombreEquipo'] = $rowHojaTec1['nombreEquipo'];
                                                                        $datos['marca'] = $rowHojaTec1['marca'];
                                                                        $datos['recinto'] = $rowHojaTec1['recinto'];
                                                                        $datos['idServicio'] = $rowHojaTec1['idServicio'];
                                                                        $id = $datos['id'];
                                                                        $fechaRegistro = $datos['fechaRegistro'];
                                                                        $nombreEquipo = $datos['nombreEquipo'];
                                                                        $marca = $datos['marca'];
                                                                        $recinto = $datos['recinto'];
                                                                        $idServicio = $datos['idServicio'];

                                                                        ?>
                                                                        <tr>
                                                                            <td><?php echo $num ?> </td>
                                                                            <td><?php echo mb_convert_case($fechaRegistro, MB_CASE_UPPER, 'UTF-8');  ?></td>
                                                                            <td><?php echo mb_convert_case($nombreEquipo, MB_CASE_UPPER, 'UTF-8'); ?></td>
                                                                            <td>
                                                                                <?php
                                                                                echo $marca;
                                                                                ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php
                                                                                if($recinto == ''){
                                                                                    echo '-';
                                                                                } else{
                                                                                    echo $recinto;
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php
                                                                                $sql = "SELECT * FROM servicios WHERE idServicio = $idServicio";
                                                                                $res = mysqli_query($conexion,$sql);
                                                                                while($row = $res->fetch_assoc()){
                                                                                    $datos1 = [];
                                                                                    $datos1['nomServicio'] = $row['nomServicio'];
                                                                                    $nomServicio = $datos1['nomServicio'];
                                                                                    echo $nomServicio;
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                            <td>
                                                                                <button type="button" onclick="obtenerid(<?php echo $id?>)" class="btn btn-danger">Añadir Este Equipo</button>
                                                                            </td>
                                                                        </tr>
                                                                        <?php
                                                                        $num++;
                                                                    }
                                                                    ?>
                                                                    </tbody>
                                                                    <tfoot>
                                                                    <tr>
                                                                        <th scope="col" class="text-center">N°</th>
                                                                        <th scope="col" class="text-center">Fecha Registro</th>
                                                                        <th scope="col" class="text-center">Nombre del Equipamiento</th>
                                                                        <th scope="col" class="text-center">Marca</th>
                                                                        <th scope="col" class="text-center">Recinto</th>
                                                                        <th scope="col" class="text-center">Servicio</th>
                                                                        <th scope="col" class="text-center">Seleccionar</th>

                                                                    </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <fieldset class="border p-3">

                                                    <div class="form-row">
                                                        <div class="form-group col-md-6">
                                                            <label for="exampleInputQuienSolicita">Equipo o Instrumento:</label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-box"></i></span>
                                                                </div>
                                                                <input type="text" style="text-transform:capitalize"
                                                                       class="form-control" id="nomEquipo" name="nomEquipo"
                                                                       value="" placeholder="Nombre del equipo">
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-md-6">
                                                            <label for="exampleInputQuienSolicita">Numero Inventario:</label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-barcode"></i></span>
                                                                </div>
                                                                <input type="text" style="text-transform:capitalize"
                                                                       class="form-control" id="numInventario"
                                                                       name="numInventario" value=""
                                                                       placeholder="Número de inventario del equipo">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-row">
                                                        <div class="form-group col-md-6">
                                                            <label for="exampleInputQuienSolicita">Marca:</label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-copyright"></i></span>
                                                                </div>
                                                                <input type="text" style="text-transform:capitalize"
                                                                       class="form-control" id="marcaEquipo" name="marcaEquipo"
                                                                       value="" placeholder="Marca del equipo">
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-md-6">
                                                            <label for="exampleInputQuienSolicita">Modelo:</label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-copyright"></i></span>
                                                                </div>
                                                                <input type="text" style="text-transform:capitalize"
                                                                       class="form-control" id="modeloEquipo"
                                                                       name="modeloEquipo" value=""
                                                                       placeholder="Modelo del equipo">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-row">
                                                        <div class="form-group col-md-6">
                                                            <label for="exampleInputQuienSolicita">N° de Serie:</label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-code"></i></span>
                                                                </div>
                                                                <input type="text" style="text-transform:capitalize"
                                                                       class="form-control" id="numSerie" name="numSerie"
                                                                       value="" placeholder="N° de serie del equipo">
                                                            </div>
                                                        </div>

                                                    </div>
                                                </fieldset>



                                            </div><!--Aqui termina la pestaña del equipo -->

                                            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">

                                                <fieldset class="border p-3">
                                                    <legend class="w-auto">Tipo de trabajo</legend>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputCodigoTarjeta">Tipo Mantención:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-th-list"></i></span>
                                                                </div>
                                                                <select name="tipoMantencion" class="form-control" required id="tipoMantencion">
                                                                    <option value="" disabled selected>-- Seleccione --</option>
                                                                    <option value="MPE">MPE - Mantenimiento Preventivo Externo</option>
                                                                    <option value="MRE">MRE - Mantenimiento Reparativo Externo</option>
                                                                    <option value="MPI">MPI - Mantenimiento Preventivo Interno</option>
                                                                    <option value="MRI">MRI - Mantenimiento Reparativo Interno</option>
                                                                    <option value="OM">OM - Obras Menores</option>
                                                                    <option value="IS">IS - Inspección/Supervisión</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                                <br>
                                                <br>

                                                <div class="modal fade" id="modalHojas" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalHojasLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xl">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-primary">
                                                                <h5 class="modal-title" id="modalHojasLabel">Escoja el equipamiento</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <table id="hoja_tecnicas" class="table table-sm table-hover  table-bordered">
                                                                    <thead>
                                                                    <tr>
                                                                        <th scope="col" class="text-center">N°</th>
                                                                        <th scope="col" class="text-center">Fecha Registro</th>
                                                                        <th scope="col" class="text-center">Nombre del Equipamiento</th>
                                                                        <th scope="col" class="text-center">Marca</th>
                                                                        <th scope="col" class="text-center">Recinto</th>
                                                                        <th scope="col" class="text-center">Servicio</th>
                                                                        <th scope="col" class="text-center">Seleccionar</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php
                                                                    $sqlHojaTec1 = "SELECT * FROM equipamiento_hbqp ORDER BY nombreEquipo ASC";
                                                                    $resHojaTec1 = mysqli_query($conexion, $sqlHojaTec1);

                                                                    $num = 1;
                                                                    while($rowHojaTec1 = $resHojaTec1->fetch_assoc()){
                                                                        $datos = [];
                                                                        $datos['id'] = $rowHojaTec1['id'];
                                                                        $datos['fechaRegistro'] = $rowHojaTec1['fechaRegistro'];
                                                                        $datos['nombreEquipo'] = $rowHojaTec1['nombreEquipo'];
                                                                        $datos['marca'] = $rowHojaTec1['marca'];
                                                                        $datos['recinto'] = $rowHojaTec1['recinto'];
                                                                        $datos['idServicio'] = $rowHojaTec1['idServicio'];
                                                                        $id = $datos['id'];
                                                                        $fechaRegistro = $datos['fechaRegistro'];
                                                                        $nombreEquipo = $datos['nombreEquipo'];
                                                                        $marca = $datos['marca'];
                                                                        $recinto = $datos['recinto'];
                                                                        $idServicio = $datos['idServicio'];

                                                                        ?>
                                                                        <tr>
                                                                            <td><?php echo $num ?> </td>
                                                                            <td><?php echo mb_convert_case($fechaRegistro, MB_CASE_UPPER, 'UTF-8');  ?></td>
                                                                            <td><?php echo mb_convert_case($nombreEquipo, MB_CASE_UPPER, 'UTF-8'); ?></td>
                                                                            <td>
                                                                                <?php
                                                                                echo $marca;
                                                                                ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php
                                                                                if($recinto == ''){
                                                                                    echo '-';
                                                                                } else{
                                                                                    echo $recinto;
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php
                                                                                $sql = "SELECT * FROM servicios WHERE idServicio = $idServicio";
                                                                                $res = mysqli_query($conexion,$sql);
                                                                                while($row = $res->fetch_assoc()){
                                                                                    $datos1 = [];
                                                                                    $datos1['nomServicio'] = $row['nomServicio'];
                                                                                    $nomServicio = $datos1['nomServicio'];
                                                                                    echo $nomServicio;
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                            <td>
                                                                                <button type="button" onclick="obtenerid(<?php echo $id?>)" class="btn btn-danger">Añadir Este Equipo</button>
                                                                            </td>
                                                                        </tr>
                                                                        <?php
                                                                        $num++;
                                                                    }
                                                                    ?>
                                                                    </tbody>
                                                                    <tfoot>
                                                                    <tr>
                                                                        <th scope="col" class="text-center">N°</th>
                                                                        <th scope="col" class="text-center">Fecha Registro</th>
                                                                        <th scope="col" class="text-center">Nombre del Equipamiento</th>
                                                                        <th scope="col" class="text-center">Marca</th>
                                                                        <th scope="col" class="text-center">Recinto</th>
                                                                        <th scope="col" class="text-center">Servicio</th>
                                                                        <th scope="col" class="text-center">Seleccionar</th>

                                                                    </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="exampleInputDetalleTrabajo">Problema que presenta:<code>*</code></label>
                                                    <textarea id="detalleTrabajo" style="resize:none;" required name="problemaPresenta" class="form-control" rows="3" placeholder="Detalle el problema que presenta.."></textarea>
                                                </div>

                                                <div class="form-group">
                                                    <label for="exampleInputDetalleTrabajo">Trabajo Realizado:<code>*</code></label>
                                                    <textarea id="detalleTrabajo" style="resize:none;" required name="trabajoRealizado" class="form-control" rows="3" placeholder="Escriba el trabajo que realizo.."></textarea>
                                                </div>

                                                <div class="form-group">
                                                    <label for="exampleInputDetalleTrabajo">Acciones Realizadas:</label>
                                                    <textarea id="detalleTrabajo" style="resize:none;" name="accionesRealiza" class="form-control" rows="3" placeholder="Acciones realizadas en el trabajo..."></textarea>
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
                                                            <a href="javascript:void(0)" class="btn btn-block btn-success addMore"><span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span><b>Agregar Material Usado</b> </a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr>

                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label for="exampleInputTurno">Materiales en Inventario:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-server"></i></span>
                                                            </div>
                                                            <select id="inventarioOrden"  class="form-control" name="inventarioOrden">
                                                                <option value="0">Seleccione...</option>
                                                                <?php

                                                                $queryInventario = "SELECT * FROM inventario_equipos WHERE cantidadInventario > 0 ORDER BY equipoInventario";

                                                                $resInventario = mysqli_query($conexion, $queryInventario);

                                                                while ($rowInventario = $resInventario->fetch_assoc()) {

                                                                    $nomCompletoInv = $rowInventario['equipoInventario'].' - '.$rowInventario['modeloInventario'].' (Cantidad: '.$rowInventario['cantidadInventario'].')';
                                                                    ?>
                                                                    <option style="font-size: 14px;" value="<?php echo $rowInventario['idInventario']; ?>">
                                                                        <?php echo mb_convert_case($nomCompletoInv,MB_CASE_UPPER,"UTF-8"); ?>
                                                                    </option>
                                                                    <?php
                                                                }

                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div name="visualizarNomInventario" id="visualizarNomInventario" class="form-group col-md-6">

                                                    </div>
                                                </div>

                                                <hr>

                                                <div class="form-group">
                                                    <label for="exampleInputComentarios">Comentarios:</label>
                                                    <textarea id="comentariosTrabajos" style="resize:none;" name="comentariosTrabajos" class="form-control" rows="3" placeholder="Comentarios"></textarea>
                                                </div>

                                                <div class="form-group">
                                                    <!-- <label for="customFile">Custom File</label> -->
                                                    <label for="exampleInputTareasControlCent">Subir Imagenes: (Maximo 10 Imagenes)</label><br>
                                                    <label for="exampleInputTareasControlCent">Formatos Permitidos: .jpg, .png, .jpeg, .bmp, .tif, .webp, .svg, .jfif</label>
                                                    <div class="custom-file">
                                                        <input type="file" max-uploads="10" class="custom-file-input" multiple="" id="archivoImagenOrden[]" name="archivoImagenOrden[]" accept=".jpg, .png, .jpeg, .bmp, .tif, .webp, .svg, .jfif">
                                                        <label class="custom-file-label" for="customFile"><b>Seleccionar Archivo...</b> </label>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <!-- <label for="customFile">Custom File</label> -->
                                                    <label for="exampleInputTareasControlCent">Subir Archivos: (Maximo 10 Archivos)</label><br>
                                                    <label for="exampleInputTareasControlCent">Formatos Permitidos: .pdf, .xlsx(Excel), .docx(Word) </label>
                                                    <div class="custom-file">
                                                        <input type="file" max-uploads="10" class="custom-file-input" multiple="" id="archivosOrden[]" name="archivosOrden[]" accept=".pdf, .xlsx, .docx">
                                                        <label class="custom-file-label" for="customFile"><b>Seleccionar Archivo...</b> </label>
                                                    </div>
                                                </div>
                                                <br><br>

                                                <div class="form-group">
                                                    <input type="hidden" id="idHoja" name="idHoja" value="">
                                                    <input type="hidden" id="idCategoria" name="idCategoria" value="">
                                                    <button type="submit" id="guardarRecepcion" name="guardarRecepcion" class="btn btn-block btn-success"> <b>Guardar</b> </button>

                                                </div>


                                            </div><!--Aqui termina la pestaña detalles solicitud -->
                                            <!-- <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">

                                                <fieldset class="border p-3">
                                                    <legend class="w-auto">Solicitante</legend>

                                                    <h6><b>Persona que Solicita:
                                                        </b> <span id="personaSolicita"></span> </h6>
                                                    <h6><b>Departamento:
                                                        </b> <span id="depa"></span></h6>
                                                    <h6><b>Tecnico Recepcionador:
                                                        </b> <span id="tecnico"></span> </h6>
                                                    <h6><b>Servicio:
                                                        </b><span id="servicio"></span></h6>
                                                    <h6><b>Recinto:
                                                        </b><span id="recinto"></span></h6>
                                                    <h6><b>PMA:
                                                        </b><span id="pma"></span></h6>
                                                    <h6><b>Coordenadas:
                                                        </b><span id="coordenadas"></span></h6>
                                                </fieldset>
                                                <br>
                                                <fieldset class="border p-3">
                                                    <legend class="w-auto">Equipo</legend>

                                                    <h6><b>Equipo o Instrumento:
                                                        </b> <span id="equipo"></span> </h6>
                                                    <h6><b>N° Inventario:
                                                        </b> <span id="inventario"></span></h6>
                                                    <h6><b>Marca:
                                                        </b> <span id="marca"></span> </h6>
                                                    <h6><b>Modelo:
                                                        </b><span id="modelo"></span></h6>
                                                    <h6><b>N° de Serie:
                                                        </b><span id="serie"></span></h6>

                                                </fieldset>
                                                <br>
                                                <fieldset class="border p-3">
                                                    <legend class="w-auto">Detalle Solicitud</legend>

                                                    <div class="row">
                                                        <div class="form-group col-md-4">
                                                            <label for="exampleInputCoordenadas">Trabajo Realizado:</label>
                                                            <textarea id="comentariosTrabajos" disabled style="resize:none;" name="comentariosTrabajos" class="form-control" rows="3"></textarea>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="exampleInputCoordenadas">Problema que presenta:</label>
                                                            <textarea id="comentariosTrabajos" disabled style="resize:none;" name="comentariosTrabajos" class="form-control" rows="3"></textarea>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="exampleInputCoordenadas">Acciones realizadas:</label>
                                                            <textarea id="comentariosTrabajos" disabled style="resize:none;" name="comentariosTrabajos" class="form-control" rows="3"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputCoordenadas">Comentarios:</label>
                                                            <textarea id="comentariosTrabajos" disabled style="resize:none;" name="comentariosTrabajos" class="form-control" rows="3"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        
                                                    </div>

                                                </fieldset>
                                                <hr>

                                             

                                            </div>Aqui termina la pestaña detalles solicitud -->
                                        </div>
                                        <hr>

                                        <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="pills-equipo-tab" data-toggle="pill" href="#pills-equipo" role="tab" aria-controls="pills-equipo" aria-selected="false"> <b>Equipo</b> </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true"><b>Solicitante</b> </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false"><b>Detalles Solicitud</b> </a>
                                            </li>

                                            <!-- <li class="nav-item">
                                                <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false"> <b>Resumen</b> </a>
                                            </li> -->
                                        </ul>


                                        <br>
                                        <br>

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
                                            <a href="javascript:void(0)" class="btn btn-block btn-danger remove"><span class="glyphicon glyphicon glyphicon-remove" aria-hidden="true"></span> <b>Eliminar</b> </a>
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

    <script src="js/dark-mode.js"></script>
    <!-- jQuery -->
    <script src="../plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>
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
    <!-- Page specific script -->
    <script>
        $(document).ready(function() {
            $('#hoja_tecnicas').DataTable({
                "paging": true,
                "iDisplayLength": 10,
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
                    "infoEmpty": "No existen Registros.",
                    "search": "Buscar:",
                    "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                    "paginate": {
                        "previous": "Anterior",
                        "next": "Siguiente"
                    }
                },
            });
        });
    </script>
    <script>
        $(function() {
            bsCustomFileInput.init();
        });
    </script>
    <script>
        function obtenerid(id){
            console.log(id);
            idHoja = document.getElementById('idHoja');
            idHoja.value = id;
            $("#modalHojas").modal("hide");
            Swal.fire(
                'Exito!',
                'Equipo seleccionado correctamente!',
                'success'
            )
            botonAddHT = document.getElementById('botonAddHT');

            botonAddHT.classList.remove('btn-primary');
            botonAddHT.classList.add('btn-danger');
            botonAddHT.innerText = 'Equipo Seleccionado';

            var dataId = id;
            console.log(dataId);
            var elementos = {};
            //AJAX
            $.ajax({
                type: 'POST',
                url: '../sdo-funciones/obtener-datos-equipo.php',
                data: {
                    'id': dataId
                },
                success: function(response) {
                    console.log(response);
                    var datos = JSON.parse(response);

                    elementos.nombreEquipo = datos.nombreEquipo;
                    elementos.marca = datos.marca;
                    elementos.modelo = datos.modelo;
                    elementos.numeroInventario = datos.numeroInventario;
                    elementos.numeroSerie = datos.numeroSerie;
                    elementos.idCategoria = datos.idCategoria;

                    $('#nomEquipo').val(elementos.nombreEquipo);
                    $('#marcaEquipo').val(elementos.marca);
                    $('#modeloEquipo').val(elementos.modelo);
                    $('#numInventario').val(elementos.numeroInventario);
                    $('#numSerie').val(elementos.numeroSerie);
                    $('#idCategoria').val(elementos.idCategoria);

                }
            })

        }
    </script>
</body>

</html>