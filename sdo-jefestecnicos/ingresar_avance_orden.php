<?php

include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


date_default_timezone_set('America/Santiago');


$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = trim($_SESSION['nomUsuario']);
$userUsuario = $_SESSION['userUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);

$sqlUsuario = "SELECT * FROM personal WHERE idPersonal = $idUsuario";
$resUsuario = mysqli_query($conexion,$sqlUsuario);
while($rowUsuario = $resUsuario->fetch_assoc()){
    $nomPersonal = $rowUsuario['nomPersonal'];

}

if(isset($_POST['idTG']) || isset($_SESSION['idTG'])){

    if(isset($_POST['idTG'])){

        $idTG = $_POST['idTG'];

        if($idTG > 0){
            $_SESSION['idTG'] = $idTG;
        }else{
            header('Location: trabajos_general_listado.php?errorDetalle=true');
        }
    }else if(isset($_SESSION['idTG'])){
        $idTG = $_SESSION['idTG'];
        if($idTG > 0){
            $_SESSION['idTG'] = $idTG;
        }else{
            header('Location: trabajos_general_listado.php?errorDetalle=true');
        }
    }





    $sql = "SELECT * FROM trabajos_general WHERE idTG = $idTG";
    $res = mysqli_query($conexion,$sql);
    while($row = $res->fetch_assoc()){
        $folioTG = $row['folioTG'];
        $estadoTG = $row['estadoTG'];
        $solicitanteTG = $row['solicitanteTG'];
        $detalleTG = $row['detalleTG'];
        $fechaTG = $row['fechaTG'];
        $turnoTG = $row['turnoTG'];
        $tecnico1 = $row['tecnico1'];
        $tipoTecnico = $row['tipoTecnico'];
        $fechaTG2 = strtotime($fechaTG);
        $fechaTG3 = fechaEspañol2($fechaTG2);
        $fechaProgramacion = $row['fechaProgramacion'];
        $fechaProgram1 = strtotime($fechaProgramacion);
        $fechaProgram2 = fechaEspañol2($fechaProgram1);

    }
}else{

}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Ingresar Avance del Trabajo</title>

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
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>


</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/jefes-sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">

                        <h4>Orden de Trabajo N°<?php echo $idTG?></h4>

                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a>
                            </li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="container-fluid">

                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Orden de Trabajo N°<?php echo $idTG?></h4>
                        </div>
                        <div class="card-body">
                            <!-- small box -->
                            <a href="detalle_ordenes_trabajo.php" style="float: right;"
                               class="btn bg-gradient-red float-right btn-md text-white"><i
                                        class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            <br>
                            <br>
                            <hr>
                            <fieldset class="border p-3">
                                <legend class="w-auto">&nbsp;Informaci&oacute;n de la Orden de Trabajo</legend>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="exampleInputQuienSolicita">Fecha Trabajo:</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                            </div>
                                            <input type="text" readonly style="text-transform:capitalize" class="form-control" id="fechaTrabajo" name="fechaTrabajo" value="<?php echo $fechaTG3?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="exampleInputQuienSolicita">Fecha de Programacion del Trabajo:</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                            </div>
                                            <input type="text" readonly style="text-transform:capitalize" class="form-control" id="fechaTrabajo" name="fechaTrabajo" value="<?php echo $fechaProgram2?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="exampleInputQuienSolicita">Persona Solicitante:</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            </div>
                                            <input type="text" readonly style="text-transform:capitalize" class="form-control" id="fechaTrabajo" name="fechaTrabajo" value="<?php echo $solicitanteTG?>">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="exampleInputQuienSolicita">Seleccione una opcion:</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            </div>
                                            <select class="form-control" name="selectAvance" required onchange="visible(this.value)" id="selectAvance">
                                                <option value="" disabled selected>Seleccione....</option>
                                                <option value="Avance">Avance del Trabajo</option>
                                                <option value="Termino Trabajo">Finalizar OT con Hoja Tecnica</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </fieldset>
                            <br>
                            <form action="../sdo-funciones/ingresar-avance-orden-1.php" method="POST" enctype="multipart/form-data">
                                <button id="finavance" name="<?php echo $idTG ?>" style="display: none;float:right;"
                                        type="button" class="btn float:right bg-danger terminaravance">Finalizar Orden
                                    de Trabajo</button>
                                <br><br>
                                <fieldset class="border p-3" id="fielsetAvance" style="display: none">
                                    <legend class="w-auto">Avances del Trabajo</legend>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <textarea name="detalleObs" id="detalleObs" rows="10" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <legend class="w-auto">Observacion/es: </legend>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                                <textarea name="observacion" id="observacion" rows="10"
                                                          class="form-control"></textarea>
                                        </div>
                                    </div>
                                </fieldset>
                                <button type="submit" id="finavance" name="<?php echo $idTG ?>"
                                        style="display: none;float:right;"
                                        class="btn float:right bg-danger terminartodo">Finalizar Orden de
                                    Trabajo</button>
                                <br>
                                <br>
                                <fieldset class="border p-3" id="fielsetTrabajoTerminado" style="display: none">
                                    <legend class="w-auto">Trabajo Terminado</legend>
                                    <!--
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputQuienSolicita">N° Orden de Trabajo:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-file"></i></span>
                                                </div>
                                                <input type="text" style="text-transform:capitalize"
                                                       class="form-control" id="noOrdenTrabajo"
                                                       name="noOrdenTrabajo" value=""
                                                       placeholder="Escriba el N° de Orden de Trabajo">
                                            </div>
                                        </div>

                                    </div>
                                    -->
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputNomSolicitante">Tecnico Recepcionador:</label>
                                            <div class="input-group mb-3">

                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" readonly style="text-transform:capitalize" class="form-control" id="nomTecnicoRecepcionador" name="nomTecnicoRecepcionador" value="<?php echo $nomUsuario; ?>" placeholder=" Escriba El Nombre Funcionario Recepcionador">
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
                                            <label for="exampleInputRecinto">Recinto:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-map-marker"></i></span>
                                                </div>
                                                <input type="text"   class="form-control" id="recintoOrden" name="recintoOrden" placeholder="Recinto">
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
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="exampleInputCodigoTarjeta">Tipo Mantención:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-th-list"></i></span>
                                                </div>
                                                <select name="tipoMantencion" class="form-control" id="tipoMantencion">
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
                                    <div class="form-group">
                                        <label for="exampleInputDetalleTrabajo">Problema que presenta:</label>
                                        <textarea id="detalleTrabajo" readonly style="resize:none;" name="problemaPresenta" class="form-control" rows="3" placeholder="Detalle el problema que presenta.."><?php echo $detalleTG?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputDetalleTrabajo">Trabajo Realizado:</label>
                                        <textarea id="trabajoRealizado" style="resize:none;"  name="trabajoRealizado" class="form-control" rows="3" placeholder="Escriba el trabajo que realizo.."></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputDetalleTrabajo">Acciones Realizadas:</label>
                                        <textarea id="accionesRealiza" style="resize:none;" name="accionesRealiza" class="form-control" rows="3" placeholder="Acciones realizadas en el trabajo..."></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputComentarios">Comentarios:</label>
                                        <textarea id="comentariosTrabajos" style="resize:none;" name="comentariosTrabajos" class="form-control" rows="3" placeholder="Comentarios"></textarea>
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
                                    <br>
                                    <?php
                                    date_default_timezone_set('America/Santiago');
                                    ?>
                                    <input type="hidden" readonly="readonly" id="fechaActualOrden" name="fechaActualOrden" class="form-control" value="<?php echo date('d-m-Y'); ?>" />
                                    <fieldset class="border p-2">
                                        <!--<legend class="w-auto">Información del Equipo</legend>-->
                                        <div class="row col-md-12 col-12 text-center justify-content-center">
                                            <br>
                                            <br>
                                            <button type="button" class="btn btn-primary text-center mb-5 ml-2" id="botonAddHT" data-toggle="modal" data-target="#modalHojas"><i class="fa-solid fas fa-plus"></i>&nbsp;&nbsp;<b>Añadir Equipo a la Hoja Técnica</b> </button>
                                        </div>
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
                                                                <th scope="col" class="text-center">PMA</th>
                                                                <th scope="col" class="text-center">Seleccionar</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            if($idDepartamento == 2){
                                                                $sqlHojaTec1 = "SELECT * FROM equipamiento_hbqp WHERE idDepaPertenece = $idDepartamento OR idDepaPertenece = 12 ORDER BY nombreEquipo ASC";
                                                                $resHojaTec1 = mysqli_query($conexion, $sqlHojaTec1);
                                                            }elseif($idDepartamento == 12){
                                                                $sqlHojaTec1 = "SELECT * FROM equipamiento_hbqp WHERE idDepaPertenece = $idDepartamento OR idDepaPertenece = 2 ORDER BY nombreEquipo ASC";
                                                                $resHojaTec1 = mysqli_query($conexion, $sqlHojaTec1);
                                                            }else{
                                                                $sqlHojaTec1 = "SELECT * FROM equipamiento_hbqp WHERE idDepaPertenece = $idDepartamento ORDER BY nombreEquipo ASC";
                                                                $resHojaTec1 = mysqli_query($conexion, $sqlHojaTec1);
                                                            }

                                                            $num = 1;
                                                            while($rowHojaTec1 = $resHojaTec1->fetch_assoc()){
                                                                $datos = [];
                                                                $datos['id'] = $rowHojaTec1['id'];
                                                                $datos['fechaRegistro'] = $rowHojaTec1['fechaRegistro'];
                                                                $datos['nombreEquipo'] = $rowHojaTec1['nombreEquipo'];
                                                                $datos['marca'] = $rowHojaTec1['marca'];
                                                                $datos['recinto'] = $rowHojaTec1['recinto'];
                                                                $datos['idServicio'] = $rowHojaTec1['idServicio'];
                                                                $datos['pma'] = $rowHojaTec1['pma'];
                                                                $id = $datos['id'];
                                                                $fechaRegistro = $datos['fechaRegistro'];
                                                                $nombreEquipo = $datos['nombreEquipo'];
                                                                $marca = $datos['marca'];
                                                                $recinto = $datos['recinto'];
                                                                $idServicio = $datos['idServicio'];
                                                                $pma = $datos['pma'];

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
                                                                        <?php echo $pma ?>
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
                                                                <th scope="col" class="text-center">PMA</th>
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

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputQuienSolicita">Equipo o Instrumento:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-box"></i></span>
                                                    </div>
                                                    <input type="text" style="text-transform:capitalize" readonly
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
                                                    <input type="text" style="text-transform:capitalize" readonly
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
                                                    <input type="text" style="text-transform:capitalize" readonly
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
                                                    <input type="text" style="text-transform:capitalize" readonly
                                                           class="form-control" id="modeloEquipo"
                                                           name="modeloEquipo" value=""
                                                           placeholder="Modelo del equipo">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputQuienSolicita">N° de Serie:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-code"></i></span>
                                                    </div>
                                                    <input type="text" style="text-transform:capitalize" readonly
                                                           class="form-control" id="numSerie" name="numSerie"
                                                           value="" placeholder="N° de serie del equipo">
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>


                                </fieldset>
                                <br>
                                <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG?>">
                                <input type="hidden" id="idHoja" name="idHoja" value="">
                                <input type="hidden" id="folioTG" name="folioTG" value="<?php echo $folioTG?>">
                                <input type="hidden" id="avance" name="avance" value="">
                                <input type="hidden" readonly style="text-transform:capitalize" class="form-control" id="solicitante" name="solicitante" value="<?php echo $solicitanteTG?>">
                                <input type="hidden" id="idHoja" name="idHoja" value="">
                                <input type="hidden" id="idEquipo" name="idEquipo" value="">
                                <input type="hidden" id="idCategoria" name="idCategoria" value="">

                                <button id="Guardar" style="display: none" name="Guardar" class="btn btn-block btn-primary">Guardar</button>

                                <?php
                                $sqlTrabajos = "SELECT * FROM avances_trabajo_general WHERE idTG = $idTG AND nomCreador = '$nomUsuario' AND detalleObs NOT LIKE '%a creado la hoja tecnica con folio%' AND detalleObs NOT LIKE '%La Orden de Trabajo se ha finalizado con exito%' ";
                                $resTrabajos = mysqli_query($conexion,$sqlTrabajos);
                                if ($resTrabajos->num_rows > 0) {
                                    $datosConcatenados = "";
                                    $datosConcatenados1 = "";
                                    while($rowTrabajos = $resTrabajos->fetch_assoc()) {
                                        $datosConcatenados .= $rowTrabajos["detalleObs"] . "\n";
                                        $datosConcatenados1 .= $rowTrabajos["observacion"]. "\n";
                                    }
                                    echo '<script>document.getElementById("trabajoRealizado").value = ' . json_encode($datosConcatenados) . ';</script>';
                                    echo '<script>document.getElementById("comentariosTrabajos").value = ' . json_encode($datosConcatenados1) . ';</script>';
                                }
                                ?>
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
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <footer class="main-footer">
        <strong>Copyright &copy; 2022 - ∞. Derechos Reservados</strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
</div>
<script src="../plugins/jquery/jquery.min.js"></script>
<script src="js/dark-mode.js"></script>
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
<script type="text/javascript">
    function visible(valor)
    {
        if(valor == 'Avance')
        {
            document.getElementById('fielsetAvance').style.display = "block";
            document.getElementById('fielsetTrabajoTerminado').style.display = "none";
            document.getElementById('Guardar').style.display = "block";
            document.getElementById('finavance').style.display = "block";
            hola = document.getElementById('avance');
            hola.value = 'Avance';

        }else if(valor == 'Termino Trabajo'){
            document.getElementById('fielsetAvance').style.display = "none";
            document.getElementById('fielsetTrabajoTerminado').style.display = "block";
            document.getElementById('finavance').style.display = "block";
            document.getElementById('Guardar').style.display = "block";
            hola = document.getElementById('avance');
            hola.value = 'Termino Trabajo';

        }else{
            document.getElementById('fielsetAvance').style.display = "none";
            document.getElementById('fielsetTrabajoTerminado').style.display = "none";
            document.getElementById('Guardar').style.display = "none";

        }
    }
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
                elementos.idServicio = datos.idServicio;
                elementos.idDepaPertenece = datos.idDepaPertenece;
                elementos.piso = datos.piso;
                elementos.id = datos.id;

                $('#nomEquipo').val(elementos.nombreEquipo);
                $('#marcaEquipo').val(elementos.marca);
                $('#modeloEquipo').val(elementos.modelo);
                $('#numInventario').val(elementos.numeroInventario);
                $('#numSerie').val(elementos.numeroSerie);
                $('#idCategoria').val(elementos.idCategoria);
                $('#idEquipo').val(elementos.id);
                $('#idServicio').val(elementos.idServicio);
                $('#idDepaPertenece').val(elementos.idDepaPertenece);
                $('#piso').val(elementos.piso);

            }
        })

    }
</script>
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
    $(document).ready(
        $('.terminaravance').click(function(e) {
            e.preventDefault();
            let id = $(this).attr('name');
            var dataString = 'id=' + id;
            Swal.fire({
                icon: 'question',
                title: '¿Desea finalizar esta Orden de Trabajo?',
                text: 'Esta acción no se puede revertir',
                showConfirmButton: true,
                confirmButtonText: 'Si , finalizar',
                confirmButtonColor: '#3085d6',
                showCancelButton: true,
                cancelButtonText: 'No, lo pensare',
                cancelButtonColor: '#d33',
                buttonsStyling: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../sdo-funciones/finalizar-ot-avance.php',
                        type: 'POST',
                        data: dataString,
                        success: function(response) {
                            console.log('finalizando.. ' + dataString);
                            Swal.fire({
                                icon: 'success',
                                title: 'OT finalizada con exito',
                                showConfirmButton: false,
                                timer: 2000,
                            })
                            setTimeout(function() {
                                location.href = "trabajos_general_listado_finalizados.php";
                            }, 2100)
                        }
                    })
                }
            })
        })
    )
</script>
<script>
    $(document).ready(
        $('.terminartodo').click(function(e) {
            e.preventDefault();
            let id = $(this).attr('name');
            var dataString = 'id=' + id;
            Swal.fire({
                icon: 'question',
                title: '¿Desea finalizar esta Orden de Trabajo?',
                text: 'Esta acción no se puede revertir',
                showConfirmButton: true,
                confirmButtonText: 'Si , finalizar',
                confirmButtonColor: '#3085d6',
                showCancelButton: true,
                cancelButtonText: 'No, lo pensare',
                cancelButtonColor: '#d33',
                buttonsStyling: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../sdo-funciones/finalizar_OT.php',
                        type: 'POST',
                        data: dataString,
                        success: function(response) {
                            console.log('finalizando.. ' + dataString);
                            Swal.fire({
                                icon: 'success',
                                title: 'OT finalizada con exito',
                                showConfirmButton: false,
                                timer: 2000,
                            })
                            setTimeout(function() {
                                location.href = "trabajos_general_listado_finalizados.php";
                            }, 2100)
                        }
                    })
                }
            })
        })
    )
</script>

</body>
</html>

