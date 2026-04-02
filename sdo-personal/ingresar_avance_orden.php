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
        $fechaTG2 = strtotime($fechaTG);
        $fechaTG3 = fechaEspañol2($fechaTG2);
        $fechaProgramacion = $row['fechaProgramacion'];
        $fechaProgram1 = strtotime($fechaProgramacion);
        $fechaProgram2 = fechaEspañol2($fechaProgram1);
        $detalleTurnoCamara = $row['detalleTurnoCamara'];
        $detalleTurnoLLE = $row['detalleTurnoLLE'];
        $detalleTurnoCA = $row['detalleTurnoCA'];
        $detalleTurnoRonda = $row['detalleTurnoRondas'];
        $detalleOtros = $row['detalleOtros'];
    }
}else{

}

$bms = contarBMS($folioTG);
$general = concatenargeneralavance1($idTG,$tecnico1);
$observacion = concatenargeneralobs1($idTG,$tecnico1);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Ingresar Avance del Trabajo</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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
    <script>
        $(document).ready(function() {


            // Show div by removing inline display none style rule
            $(".show-bms").click(function() {
                $("#fielsetTrabajoBMS").show();
                $("#fielsetTrabajoRondas").hide();
                $("#fielsetTrabajoCA").hide();
                $("#fielsetTrabajoCCTV").hide();
                $("#fielsetTrabajoLLE").hide();
                $("#GuardarBms").show();
                $("#GuardarRondas").hide();
                $("#GuardarCA").hide();
                $("#GuardarCCTV").hide();
                $("#GuardarLLE").hide();
                $("#fielsetTrabajoOtros").hide();
                $("#GuardarOtros").hide();
            });
            $(".show-rondas").click(function() {
                $("#fielsetTrabajoRondas").show();
                $("#fielsetTrabajoBMS").hide();
                $("#fielsetTrabajoCA").hide();
                $("#fielsetTrabajoCCTV").hide();
                $("#fielsetTrabajoLLE").hide();
                $("#GuardarBms").hide();
                $("#GuardarRondas").show();
                $("#GuardarCA").hide();
                $("#GuardarCCTV").hide();
                $("#GuardarLLE").hide();
                $("#GuardarOtros").hide();
                $("#fielsetTrabajoOtros").hide();
                servicioOrden = document.getElementById("servicioOrdenRondas");
                recintoOrden = document.getElementById("recintoOrdenRondas");
                pmaOrden = document.getElementById("pmaOrdenRondas");
                coordenadasOrden = document.getElementById("coordenadasOrdenRondas");
                tipoMantencion = document.getElementById("tipoMantencionRondas");
                trabajoRealizado = document.getElementById("trabajoRealizadoRondas");
                accionesRealiza = document.getElementById("accionesRealizaRondas");
                comentariosTrabajos = document.getElementById("comentariosTrabajosRondas");
                time = document.getElementById("time");
                servicioOrden.value = '22';
                recintoOrden.value = 'Central Energetica';
                pmaOrden.value = 'L-S1-16';
                coordenadasOrden.value = '22-27/B-E';
                tipoMantencion.value = 'IS';
                trabajoRealizado.innerHTML =
                    'Revisión de parámetros y estado de funcionamiento de los equipos en terreno. Revisión de Temperaturas en recintos.';
                accionesRealiza.innerHTML = '1- Revisión de equipos y dispositivos, Central Frío.\n' +
                    '2- Revisión de equipos y dispositivos, Central Calor.\n' +
                    '3-Revisión de equipos y dispositivos, Paneles Solares.\n' +
                    '4-Revisión de Temperatura de recintos de Laboratorio (VDRL Y IFI (C-2-9)).\n' +
                    '5-Revisión de Temperaturas de Recintos UPC.'
                comentariosTrabajos.innerHTML = time.value + '\n' +
                    'Las temperaturas de los recintos de Laboratorio en esta ronda son: VDRL:°C,IFI(C-2-9): °C\n' +
                    'Las temperaturas de UPC Adulto: °C\n' +
                    'Las temperatura de UPC Pediátrica: °C';
            });
            // Show div by removing inline display none style rule
            $(".show-ca").click(function() {
                $("#fielsetTrabajoBMS").hide();
                $("#fielsetTrabajoRondas").hide();
                $("#fielsetTrabajoCA").show();
                $("#fielsetTrabajoCCTV").hide();
                $("#fielsetTrabajoLLE").hide();
                $("#fielsetTrabajoOtros").hide();
                $("#GuardarBms").hide();
                $("#GuardarRondas").hide();
                $("#GuardarCA").show();
                $("#GuardarCCTV").hide();
                $("#GuardarLLE").hide();
                $("#GuardarOtros").hide();

            });

            $(".show-cctv").click(function() {
                $("#fielsetTrabajoCCTV").show();
                $("#fielsetTrabajoBMS").hide();
                $("#fielsetTrabajoRondas").hide();
                $("#fielsetTrabajoCA").hide();
                $("#fielsetTrabajoLLE").hide();
                $("#fielsetTrabajoOtros").hide();
                $("#GuardarBms").hide();
                $("#GuardarRondas").hide();
                $("#GuardarCA").hide();
                $("#GuardarCCTV").show();
                $("#GuardarLLE").hide();
                $("#GuardarOtros").hide();

            });

            $(".show-lle").click(function() {
                $("#fielsetTrabajoCCTV").hide();
                $("#fielsetTrabajoBMS").hide();
                $("#fielsetTrabajoRondas").hide();
                $("#fielsetTrabajoLLE").show();
                $("#fielsetTrabajoCA").hide();
                $("#fielsetTrabajoOtros").hide();
                $("#GuardarBms").hide();
                $("#GuardarRondas").hide();
                $("#GuardarCA").hide();
                $("#GuardarCCTV").hide();
                $("#GuardarLLE").show();
                $("#GuardarOtros").hide();
            });

            $(".show-otros").click(function() {
                $("#fielsetTrabajoCCTV").hide();
                $("#fielsetTrabajoBMS").hide();
                $("#fielsetTrabajoRondas").hide();
                $("#fielsetTrabajoLLE").hide();
                $("#fielsetTrabajoCA").hide();
                $("#fielsetTrabajoOtros").show();
                $("#GuardarBms").hide();
                $("#GuardarRondas").hide();
                $("#GuardarCA").hide();
                $("#GuardarCCTV").hide();
                $("#GuardarLLE").hide();
                $("#GuardarOtros").show();
            });

        });
    </script>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/user-sidebar.php';
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
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio"
                                                           class="text-dark">Inicio</a>
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
                            <a href="visualizardetalles.php" style="float: right;"
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
                                                    <span class="input-group-text"><i
                                                                class="fas fa-calendar"></i></span>
                                            </div>
                                            <input type="text" readonly style="text-transform:capitalize"
                                                   class="form-control" id="fechaTrabajo" name="fechaTrabajo"
                                                   value="<?php echo $fechaTG3?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="exampleInputQuienSolicita">Fecha de Programacion del
                                            Trabajo:</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                    <span class="input-group-text"><i
                                                                class="fas fa-calendar"></i></span>
                                            </div>
                                            <input type="text" readonly style="text-transform:capitalize"
                                                   class="form-control" id="fechaTrabajo" name="fechaTrabajo"
                                                   value="<?php echo $fechaProgram2?>">
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
                                            <input type="text" readonly style="text-transform:capitalize"
                                                   class="form-control" id="fechaTrabajo" name="fechaTrabajo"
                                                   value="<?php echo $solicitanteTG?>">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="exampleInputQuienSolicita">Seleccione una opcion:</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            </div>
                                            <select class="form-control" name="selectAvance" required
                                                    onchange="visible(this.value)" id="selectAvance">
                                                <option value="" disabled selected>Seleccione....</option>
                                                <option value="Avance">Avance del Trabajo</option>
                                                <option value="Termino Trabajo">Finalizar OT (Con Hoja Técnica)
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </fieldset>
                            <br>
                            <form action="../sdo-funciones/ingresar-avance-orden-1.php" method="POST"
                                  enctype="multipart/form-data">
                                    <button id="finavance" name="<?php echo $idTG ?>" style="display: none;float:right;"
                                            type="button" class="btn float:right bg-danger terminartodo">Finalizar Orden
                                        de Trabajo</button>
                                <br>
                                <hr>
                                <fieldset class="border p-3" id="fielsetAvance" style="display: none">
                                    <legend class="w-auto">Avances del Trabajo: </legend>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                                <textarea name="detalleObs" id="detalleObs" rows="10"
                                                          class="form-control"></textarea>
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
                                <input type="hidden" id="avance" name="avance" value="">
                                <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG?>">
                                <?php
                                if($estadoTG == 'Trabajo Terminado'){
                                    ?>
                                    <button type="submit" disabled id="Guardar" style="display: none" name="Guardar"
                                            class="btn btn-block btn-primary">Guardar</button>
                                    <?php
                                }else{
                                    ?>
                                    <button type="submit" id="Guardar" style="display: none" name="Guardar"
                                            class="btn btn-block btn-primary">Guardar</button>
                                    <?php
                                }
                                ?>

                            </form>
                            <div class="row">
                                <?php
                                if($estadoTG == 'Trabajo Terminado'){
                                    ?>
                                    <div class="col-sm-2">
                                        <button type="button" id="bms" disabled style="display: none"
                                                class="btn btn-block btn-success show-bms">Crear Hoja Técnica de
                                            BMS</button>
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="button" id="rondas" disabled style="display: none"
                                                class="btn btn-block btn-success show-rondas">Crear Hoja Técnica de
                                            Rondas</button>
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="button" id="ca" disabled style="display: none"
                                                class="btn btn-block btn-success show-ca">Crear Hoja Técnica de CA</button>
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="button" disabled id="cctv" style="display: none"
                                                class="btn btn-block btn-success show-cctv">Crear Hoja Técnica de
                                            CCTV</button>
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="button" disabled id="lle" style="display: none"
                                                class="btn btn-block btn-success show-lle">Crear Hoja Técnica de
                                            LLE</button>
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="button" disabled id="otros" style="display: none;"
                                                class="btn btn-block btn-success show-otros">Crear Hoja Técnica de
                                            Otros Trabajos</button>
                                    </div>
                                    <?php
                                }else{
                                    ?>
                                    <?php
                                    if($detalleTG == '' || empty($detalleTG)){
                                        ?>
                                        <div class="col-sm-2">
                                            <button disabled type="button" id="bms" style="display: none"
                                                    class="btn btn-block btn-success show-bms">Crear Hoja Técnica de
                                                BMS</button>
                                        </div>
                                        <?php
                                    }else{
                                        ?>
                                        <div class="col-sm-2">
                                            <button type="button" id="bms" style="display: none"
                                                    class="btn btn-block btn-success show-bms">Crear Hoja Técnica de
                                                BMS</button>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if($detalleTurnoRonda == '' || empty($detalleTurnoRonda)){
                                        ?>
                                        <div class="col-sm-2">
                                            <button disabled type="button" id="rondas" style="display: none"
                                                    class="btn btn-block btn-success show-rondas">Crear Hoja Técnica de
                                                Rondas</button>
                                        </div>
                                        <?php
                                    }else{
                                        ?>
                                        <div class="col-sm-2">
                                            <button type="button" id="rondas" style="display: none"
                                                    class="btn btn-block btn-success show-rondas">Crear Hoja Técnica de
                                                Rondas</button>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if($detalleTurnoCA == '' || empty($detalleTurnoCA)){
                                        ?>
                                        <div class="col-sm-2">
                                            <button disabled type="button" id="ca" style="display: none"
                                                    class="btn btn-block btn-success show-ca">Crear Hoja Técnica de CA</button>
                                        </div>
                                        <?php
                                    }else{
                                        ?>
                                        <div class="col-sm-2">
                                            <button type="button" id="ca" style="display: none"
                                                    class="btn btn-block btn-success show-ca">Crear Hoja Técnica de CA</button>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if($detalleTurnoCamara == '' || empty($detalleTurnoCamara)){
                                        ?>
                                        <div class="col-sm-2">
                                            <button disabled type="button"  id="cctv" style="display: none"
                                                    class="btn btn-block btn-success show-cctv">Crear Hoja Técnica de
                                                CCTV</button>
                                        </div>
                                        <?php
                                    }else{
                                        ?>
                                        <div class="col-sm-2">
                                            <button type="button"  id="cctv" style="display: none"
                                                    class="btn btn-block btn-success show-cctv">Crear Hoja Técnica de
                                                CCTV</button>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if($detalleTurnoLLE == '' || empty($detalleTurnoLLE)){
                                        ?>
                                        <div class="col-sm-2">
                                            <button disabled type="button" id="lle" style="display: none"
                                                    class="btn btn-block btn-success show-lle">Crear Hoja Técnica de
                                                LLE</button>
                                        </div>
                                        <?php
                                    }else{
                                        ?>
                                        <div class="col-sm-2">
                                            <button  type="button" id="lle" style="display: none"
                                                     class="btn btn-block btn-success show-lle">Crear Hoja Técnica de
                                                LLE</button>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if($detalleOtros == '' || empty($detalleOtros)){
                                        ?>
                                        <div class="col-sm-2">
                                            <button disabled type="button"  id="otros" style="display: none;"
                                                    class="btn btn-block btn-success show-otros">Crear Hoja Técnica de
                                                Otros Trabajos</button>
                                        </div>
                                        <?php
                                    }else{
                                        ?>
                                        <div class="col-sm-2">
                                            <button type="button"  id="otros" style="display: none;"
                                                    class="btn btn-block btn-success show-otros">Crear Hoja Técnica de
                                                Otros Trabajos</button>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                }
                                ?>
                            </div>
                            <br>
                            <form action="../sdo-funciones/crear-ht-bms.php" method="POST"
                                  enctype="multipart/form-data">
                                <fieldset class="border p-3" id="fielsetTrabajoBMS" style="display: none">
                                    <legend class="w-auto">Trabajo Terminado - BMS</legend>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputQuienSolicita">N° Orden de Trabajo:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-file"></i></span>
                                                </div>
                                                <input type="text" style="text-transform:capitalize"
                                                       class="form-control" id="noOrdenTrabajo" name="noOrdenTrabajo"
                                                       value="<?php echo $folioTG?>"
                                                       placeholder="Escriba el N° de Orden de Trabajo">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputQuienSolicita">Ticket Asociado:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-file"></i></span>
                                                </div>
                                                <input type="text" style="text-transform:capitalize"
                                                       class="form-control" id="ticketAsociado" name="ticketAsociado"
                                                       value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputNomSolicitante">Tecnico Recepcionador:</label>
                                            <div class="input-group mb-3">

                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" readonly style="text-transform:capitalize"
                                                       class="form-control" id="nomTecnicoRecepcionador"
                                                       name="nomTecnicoRecepcionador"
                                                       value="<?php echo $nomUsuario; ?>"
                                                       placeholder=" Escriba El Nombre Funcionario Recepcionador">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputDepartamento">Departamento:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-server"></i></span>
                                                </div>
                                                <select id="departamentoSolicitante" class="form-control"
                                                        name="departamentoSolicitante">
                                                    <?php

                                                    $queryDepartamentos = "SELECT * FROM departamentos WHERE nomDepartamento NOT LIKE '%Control Centralizado%'  ORDER BY nomDepartamento ASC";

                                                    $resDepartamentos = mysqli_query($conexion, $queryDepartamentos);

                                                    while ($rowDepartamentos = $resDepartamentos->fetch_assoc()) {

                                                        if ($rowDepartamentos['idDepartamento'] == $idDepartamento) {
                                                            ?>
                                                            <option
                                                                    value="<?php echo $rowDepartamentos['idDepartamento']; ?>"
                                                                    selected="selected">
                                                                <?php echo $rowDepartamentos['nomDepartamento']; ?>
                                                            </option>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <option
                                                                    value="<?php echo $rowDepartamentos['idDepartamento']; ?>">
                                                                <?php echo $rowDepartamentos['nomDepartamento']; ?>
                                                            </option>
                                                            <?php
                                                        }
                                                    }

                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div name="visualizarSubDepartamento" id="visualizarSubDepartamento"
                                             class="form-group col-md-6">

                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTurno">Servicio:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-server"></i></span>
                                                </div>
                                                <select id="servicioOrden" class="form-control"
                                                        name="servicioOrden">
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
                                        <div name="visualizarNomServicio" id="visualizarNomServicio"
                                             class="form-group col-md-6">

                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputRecinto">Recinto:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-map-marker"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="recintoOrden"
                                                       name="recintoOrden" placeholder="Recinto">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="exampleInputPMA">PMA:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-map-marker"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="pmaOrden"
                                                       name="pmaOrden" placeholder="PMA">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="exampleInputCoordenadas">Coordenadas:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-map-marker"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="coordenadasOrden"
                                                       name="coordenadasOrden" placeholder="Coordenadas">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="exampleInputCodigoTarjeta">Tipo Mantención:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-th-list"></i></span>
                                                </div>
                                                <select name="tipoMantencion" class="form-control"
                                                        id="tipoMantencion">
                                                    <option value="" disabled selected>-- Seleccione --</option>
                                                    <option value="MPE">MPE - Mantenimiento Preventivo Externo
                                                    </option>
                                                    <option value="MRE">MRE - Mantenimiento Reparativo Externo
                                                    </option>
                                                    <option value="MPI">MPI - Mantenimiento Preventivo Interno
                                                    </option>
                                                    <option value="MRI">MRI - Mantenimiento Reparativo Interno
                                                    </option>
                                                    <option value="OM">OM - Obras Menores</option>
                                                    <option value="IS">IS - Inspección/Supervisión</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputDetalleTrabajo">Problema que presenta:</label>
                                        <textarea id="detalleTrabajo" style="resize:none;" name="problemaPresenta"
                                                  readonly class="form-control" rows="3"
                                                  placeholder="Detalle el problema que presenta.."><?php echo $detalleTG?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputDetalleTrabajo">Trabajo Realizado:</label>
                                        <textarea id="trabajoRealizado" style="resize:none;" name="trabajoRealizado"
                                                  class="form-control" rows="3"
                                                  placeholder="Escriba el trabajo que realizo.."></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputDetalleTrabajo">Acciones Realizadas:</label>
                                        <textarea id="accionesRealiza" style="resize:none;" name="accionesRealiza"
                                                  class="form-control" rows="3"
                                                  placeholder="Acciones realizadas en el trabajo..."><?php echo $general?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputComentarios">Comentarios:</label>
                                        <textarea id="comentariosTrabajos" style="resize:none;"
                                                  name="comentariosTrabajos" class="form-control" rows="3"
                                                  placeholder="Comentarios"><?php echo $observacion?></textarea>
                                    </div>
                                    <div class="form-row fieldGroup">
                                        <div class="form-group col-md-4">
                                            <label for="exampleInputRecinto">Materiales Usados:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-list"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="materialUsado[]"
                                                       name="materialUsado[]" placeholder="Materiales Usados">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="exampleInputPMA">Unidad:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-layer-group"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="unidad[]"
                                                       name="unidad[]" placeholder="Unidad">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="exampleInputCoordenadas">Cantidad:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-list-ol"></i></span>
                                                </div>
                                                <input type="number" min="1" class="form-control" id="cantidad[]"
                                                       name="cantidad[]" placeholder="Cantidad">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="exampleInputCoordenadas">&nbsp;</label>
                                            <div class="input-group mb-3">
                                                <a href="javascript:void(0)"
                                                   class="btn btn-block btn-success addMore"><span
                                                            class="glyphicon glyphicon glyphicon-plus"
                                                            aria-hidden="true"></span><b>Agregar Material Usado</b> </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <!-- <label for="customFile">Custom File</label> -->
                                        <label for="exampleInputTareasControlCent">Subir Imagenes: (Maximo 10
                                            Imagenes)</label><br>
                                        <label for="exampleInputTareasControlCent">Formatos Permitidos: .jpg, .png,
                                            .jpeg, .bmp, .tif, .webp, .svg, .jfif</label>
                                        <div class="custom-file">
                                            <input type="file" max-uploads="10" class="custom-file-input"
                                                   multiple="" id="archivoImagenOrden[]" name="archivoImagenOrden[]"
                                                   accept=".jpg, .png, .jpeg, .bmp, .tif, .webp, .svg, .jfif">
                                            <label class="custom-file-label" for="customFile"><b>Seleccionar
                                                    Archivo...</b> </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <!-- <label for="customFile">Custom File</label> -->
                                        <label for="exampleInputTareasControlCent">Subir Archivos: (Maximo 10
                                            Archivos)</label><br>
                                        <label for="exampleInputTareasControlCent">Formatos Permitidos: .pdf,
                                            .xlsx(Excel), .docx(Word) </label>
                                        <div class="custom-file">
                                            <input type="file" max-uploads="10" class="custom-file-input"
                                                   multiple="" id="archivosOrden[]" name="archivosOrden[]"
                                                   accept=".pdf, .xlsx, .docx">
                                            <label class="custom-file-label" for="customFile"><b>Seleccionar
                                                    Archivo...</b> </label>
                                        </div>
                                    </div>
                                    <br>
                                    <?php
                                    date_default_timezone_set('America/Santiago');
                                    ?>
                                    <input type="hidden" readonly="readonly" id="fechaActualOrden"
                                           name="fechaActualOrden" class="form-control"
                                           value="<?php echo date('d-m-Y'); ?>" />
                                    <fieldset class="border p-2">
                                        <!--<legend class="w-auto">Información del Equipo</legend>-->
                                        <div class="row col-md-12 col-12 text-center justify-content-center">
                                            <br>
                                            <br>
                                            <button type="button" class="btn btn-primary text-center mb-5 ml-2"
                                                    id="botonAddHT" data-toggle="modal" data-target="#modalHojas"><i
                                                        class="fa-solid fas fa-plus"></i>&nbsp;&nbsp;<b>Añadir Equipo a
                                                    la Hoja Técnica</b> </button>
                                        </div>
                                        <div class="modal fade" id="modalHojas" data-backdrop="static"
                                             data-keyboard="false" tabindex="-1" aria-labelledby="modalHojasLabel"
                                             aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary">
                                                        <h5 class="modal-title" id="modalHojasLabel">Escoja el
                                                            equipamiento</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table id="hoja_tecnicas"
                                                               class="table table-sm table-hover  table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th scope="col" class="text-center">N°</th>
                                                                <th scope="col" class="text-center">Fecha
                                                                    Registro</th>
                                                                <th scope="col" class="text-center">Nombre del
                                                                    Equipamiento</th>
                                                                <th scope="col" class="text-center">Marca</th>
                                                                <th scope="col" class="text-center">Recinto</th>
                                                                <th scope="col" class="text-center">Servicio
                                                                </th>
                                                                <th scope="col" class="text-center">PMA</th>
                                                                <th scope="col" class="text-center">Seleccionar
                                                                </th>
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
                                                                    <td><?php echo mb_convert_case($fechaRegistro, MB_CASE_UPPER, 'UTF-8');  ?>
                                                                    </td>
                                                                    <td><?php echo mb_convert_case($nombreEquipo, MB_CASE_UPPER, 'UTF-8'); ?>
                                                                    </td>
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
                                                                        <button type="button"
                                                                                onclick="obtenerid(<?php echo $id?>)"
                                                                                class="btn btn-danger">Añadir Este
                                                                            Equipo</button>
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
                                                                <th scope="col" class="text-center">Fecha
                                                                    Registro</th>
                                                                <th scope="col" class="text-center">Nombre del
                                                                    Equipamiento</th>
                                                                <th scope="col" class="text-center">Marca</th>
                                                                <th scope="col" class="text-center">Recinto</th>
                                                                <th scope="col" class="text-center">Servicio
                                                                </th>
                                                                <th scope="col" class="text-center">PMA</th>
                                                                <th scope="col" class="text-center">Seleccionar
                                                                </th>

                                                            </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="modalHojas" data-backdrop="static"
                                             data-keyboard="false" tabindex="-1" aria-labelledby="modalHojasLabel"
                                             aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary">
                                                        <h5 class="modal-title" id="modalHojasLabel">Escoja el
                                                            equipamiento</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table id="hoja_tecnicas"
                                                               class="table table-sm table-hover  table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th scope="col" class="text-center">N°</th>
                                                                <th scope="col" class="text-center">Fecha
                                                                    Registro</th>
                                                                <th scope="col" class="text-center">Nombre del
                                                                    Equipamiento</th>
                                                                <th scope="col" class="text-center">Marca</th>
                                                                <th scope="col" class="text-center">Recinto</th>
                                                                <th scope="col" class="text-center">Servicio
                                                                </th>
                                                                <th scope="col" class="text-center">Seleccionar
                                                                </th>
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
                                                                    <td><?php echo mb_convert_case($fechaRegistro, MB_CASE_UPPER, 'UTF-8');  ?>
                                                                    </td>
                                                                    <td><?php echo mb_convert_case($nombreEquipo, MB_CASE_UPPER, 'UTF-8'); ?>
                                                                    </td>
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
                                                                        <button type="button"
                                                                                onclick="obtenerid(<?php echo $id?>)"
                                                                                class="btn btn-danger">Añadir Este
                                                                            Equipo</button>
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
                                                                <th scope="col" class="text-center">Fecha
                                                                    Registro</th>
                                                                <th scope="col" class="text-center">Nombre del
                                                                    Equipamiento</th>
                                                                <th scope="col" class="text-center">Marca</th>
                                                                <th scope="col" class="text-center">Recinto</th>
                                                                <th scope="col" class="text-center">Servicio
                                                                </th>
                                                                <th scope="col" class="text-center">Seleccionar
                                                                </th>

                                                            </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Cerrar</button>
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
                                                           class="form-control" id="numInventario" name="numInventario"
                                                           value="" placeholder="Número de inventario del equipo">
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
                                                           class="form-control" id="modeloEquipo" name="modeloEquipo"
                                                           value="" placeholder="Modelo del equipo">
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
                                                           class="form-control" id="numSerie" name="numSerie" value=""
                                                           placeholder="N° de serie del equipo">
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>


                                </fieldset>
                                <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG?>">
                                <input type="hidden" id="folioTG" name="folioTG" value="<?php echo $folioTG?>">
                                <input type="hidden" readonly="readonly" id="fechaActualOrden"
                                       name="fechaActualOrden" class="form-control"
                                       value="<?php echo date('d-m-Y'); ?>" />
                                <input type="hidden" id="idCategoria" name="idCategoria" value="">
                                <input type="hidden" id="idHoja" name="idHoja" value="">
                                <input type="hidden" id="idEquipo" name="idEquipo" value="">
                                <input type="hidden" readonly style="text-transform:capitalize" class="form-control"
                                       id="solicitante" name="solicitante" value="<?php echo $solicitanteTG?>">
                                <button type="submit" class="btn btn-block btn-primary" id="GuardarBms"
                                        style="display: none" name="GuardarBms">Guardar</button>
                            </form>
                            <form action="../sdo-funciones/crear-ht-rondas.php" method="POST"
                                  enctype="multipart/form-data">
                                <fieldset class="border p-3" id="fielsetTrabajoRondas" style="display: none">
                                    <legend class="w-auto">Trabajo Terminado - RONDAS</legend>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputQuienSolicita">N° Orden de Trabajo:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-file"></i></span>
                                                </div>
                                                <input type="text" style="text-transform:capitalize"
                                                       class="form-control" id="noOrdenTrabajoRondas"
                                                       name="noOrdenTrabajoRondas" value="<?php echo $folioTG?>"
                                                       placeholder="Escriba el N° de Orden de Trabajo">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputQuienSolicita">Ticket Asociado:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-file"></i></span>
                                                </div>
                                                <input type="text" style="text-transform:capitalize"
                                                       class="form-control" id="ticketAsociadoRondas"
                                                       name="ticketAsociadoRondas" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputNomSolicitante">Tecnico Recepcionador:</label>
                                            <div class="input-group mb-3">

                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" readonly style="text-transform:capitalize"
                                                       class="form-control" id="nomTecnicoRecepcionadorRondas"
                                                       name="nomTecnicoRecepcionadorRondas"
                                                       value="<?php echo $nomUsuario; ?>"
                                                       placeholder=" Escriba El Nombre Funcionario Recepcionador">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputDepartamento">Departamento:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-server"></i></span>
                                                </div>
                                                <select id="departamentoSolicitanteRondas" class="form-control"
                                                        name="departamentoSolicitanteRondas">
                                                    <?php

                                                    $queryDepartamentos = "SELECT * FROM departamentos WHERE nomDepartamento NOT LIKE '%Control Centralizado%'  ORDER BY nomDepartamento ASC";

                                                    $resDepartamentos = mysqli_query($conexion, $queryDepartamentos);

                                                    while ($rowDepartamentos = $resDepartamentos->fetch_assoc()) {

                                                        if ($rowDepartamentos['idDepartamento'] == $idDepartamento) {
                                                            ?>
                                                            <option
                                                                    value="<?php echo $rowDepartamentos['idDepartamento']; ?>"
                                                                    selected="selected">
                                                                <?php echo $rowDepartamentos['nomDepartamento']; ?>
                                                            </option>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <option
                                                                    value="<?php echo $rowDepartamentos['idDepartamento']; ?>">
                                                                <?php echo $rowDepartamentos['nomDepartamento']; ?>
                                                            </option>
                                                            <?php
                                                        }
                                                    }

                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div name="visualizarSubDepartamento" id="visualizarSubDepartamento"
                                             class="form-group col-md-6">

                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTurno">Servicio:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-server"></i></span>
                                                </div>
                                                <select id="servicioOrdenRondas" class="form-control"
                                                        name="servicioOrdenRondas">
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
                                        <div name="visualizarNomServicio" id="visualizarNomServicio"
                                             class="form-group col-md-6">

                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputRecinto">Recinto:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-map-marker"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="recintoOrdenRondas"
                                                       name="recintoOrdenRondas" placeholder="Recinto">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="exampleInputPMA">PMA:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-map-marker"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="pmaOrdenRondas"
                                                       name="pmaOrdenRondas" placeholder="PMA">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="exampleInputCoordenadas">Coordenadas:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-map-marker"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="coordenadasOrdenRondas"
                                                       name="coordenadasOrdenRondas" placeholder="Coordenadas">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="exampleInputCodigoTarjeta">Tipo Mantención:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-th-list"></i></span>
                                                </div>
                                                <select name="tipoMantencionRondas" class="form-control"
                                                        id="tipoMantencionRondas">
                                                    <option value="" disabled selected>-- Seleccione --</option>
                                                    <option value="MPE">MPE - Mantenimiento Preventivo Externo
                                                    </option>
                                                    <option value="MRE">MRE - Mantenimiento Reparativo Externo
                                                    </option>
                                                    <option value="MPI">MPI - Mantenimiento Preventivo Interno
                                                    </option>
                                                    <option value="MRI">MRI - Mantenimiento Reparativo Interno
                                                    </option>
                                                    <option value="OM">OM - Obras Menores</option>
                                                    <option value="IS">IS - Inspección/Supervisión</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputDetalleTrabajo">Problema que presenta:</label>
                                        <textarea id="detalleTrabajoRondas" style="resize:none;"
                                                  name="problemaPresentaRondas" readonly class="form-control" rows="3"
                                                  placeholder="Detalle el problema que presenta.."><?php echo $detalleTurnoRonda?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputDetalleTrabajo">Trabajo Realizado:</label>
                                        <textarea id="trabajoRealizadoRondas" style="resize:none;"
                                                  name="trabajoRealizadoRondas" class="form-control" rows="3"
                                                  placeholder="Escriba el trabajo que realizo.."></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputDetalleTrabajo">Acciones Realizadas:</label>
                                        <textarea id="accionesRealizaRondas" style="resize:none;"
                                                  name="accionesRealizaRondas" class="form-control" rows="3"
                                                  placeholder="Acciones realizadas en el trabajo..."></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputComentarios">Comentarios:</label>
                                        <textarea id="comentariosTrabajosRondas" style="resize:none;"
                                                  name="comentariosTrabajosRondas" class="form-control" rows="3"
                                                  placeholder="Comentarios"></textarea>
                                    </div>
                                    <br>
                                    <?php
                                    date_default_timezone_set('America/Santiago');
                                    ?>
                                    <div class="form-group">
                                        <!-- <label for="customFile">Custom File</label> -->
                                        <label for="exampleInputTareasControlCent">Subir Archivos: (Maximo 10
                                            Archivos)</label><br>
                                        <label for="exampleInputTareasControlCent">Formatos Permitidos: .pdf,
                                            .xlsx(Excel), .docx(Word) </label>
                                        <div class="custom-file">
                                            <input type="file" max-uploads="10" class="custom-file-input"
                                                   multiple="" id="archivosOrdenRondas[]" name="archivosOrdenRondas[]"
                                                   accept=".pdf, .xlsx, .docx">
                                            <label class="custom-file-label" for="customFile"><b>Seleccionar
                                                    Archivo...</b> </label>
                                        </div>
                                    </div>
                                </fieldset>
                                <input type="hidden" id="time" name="time" value="<?php echo date('H:i')?>">
                                <input type="hidden" id="idTGRondas" name="idTGRondas" value="<?php echo $idTG?>">
                                <input type="hidden" id="folioTGRondas" name="folioTGRondas"
                                       value="<?php echo $folioTG?>">
                                <input type="hidden" readonly="readonly" id="fechaActualOrdenRondas"
                                       name="fechaActualOrdenRondas" class="form-control"
                                       value="<?php echo date('d-m-Y'); ?>" />
                                <input type="hidden" readonly style="text-transform:capitalize" class="form-control"
                                       id="solicitanteRondas" name="solicitanteRondas"
                                       value="<?php echo $solicitanteTG?>">
                                <button type="submit" class="btn btn-block btn-primary" id="GuardarRondas"
                                        style="display: none" name="GuardarRondas">Guardar</button>
                            </form>
                            <form action="../sdo-funciones/crear-ht-CA.php" method="POST"
                                  enctype="multipart/form-data">
                                <fieldset class="border p-3" id="fielsetTrabajoCA" style="display: none">
                                    <legend class="w-auto">Trabajo Terminado - CONTROL ACCESO</legend>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputQuienSolicita">N° Orden de Trabajo:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-file"></i></span>
                                                </div>
                                                <input type="text" style="text-transform:capitalize"
                                                       class="form-control" id="noOrdenTrabajoCA"
                                                       name="noOrdenTrabajoCA" value="<?php echo $folioTG?>"
                                                       placeholder="Escriba el N° de Orden de Trabajo">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputQuienSolicita">Ticket Asociado:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-file"></i></span>
                                                </div>
                                                <input type="text" style="text-transform:capitalize"
                                                       class="form-control" id="ticketAsociadoCA"
                                                       name="ticketAsociadoCA" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputNomSolicitante">Tecnico Recepcionador:</label>
                                            <div class="input-group mb-3">

                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" readonly style="text-transform:capitalize"
                                                       class="form-control" id="nomTecnicoRecepcionadorCA"
                                                       name="nomTecnicoRecepcionadorCA"
                                                       value="<?php echo $nomUsuario; ?>"
                                                       placeholder=" Escriba El Nombre Funcionario Recepcionador">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputDepartamento">Departamento:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-server"></i></span>
                                                </div>
                                                <select id="departamentoSolicitanteCA" class="form-control"
                                                        name="departamentoSolicitanteCA">
                                                    <?php

                                                    $queryDepartamentos = "SELECT * FROM departamentos WHERE nomDepartamento NOT LIKE '%Control Centralizado%'  ORDER BY nomDepartamento ASC";

                                                    $resDepartamentos = mysqli_query($conexion, $queryDepartamentos);

                                                    while ($rowDepartamentos = $resDepartamentos->fetch_assoc()) {

                                                        if ($rowDepartamentos['idDepartamento'] == $idDepartamento) {
                                                            ?>
                                                            <option
                                                                    value="<?php echo $rowDepartamentos['idDepartamento']; ?>"
                                                                    selected="selected">
                                                                <?php echo $rowDepartamentos['nomDepartamento']; ?>
                                                            </option>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <option
                                                                    value="<?php echo $rowDepartamentos['idDepartamento']; ?>">
                                                                <?php echo $rowDepartamentos['nomDepartamento']; ?>
                                                            </option>
                                                            <?php
                                                        }
                                                    }

                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div name="visualizarSubDepartamento" id="visualizarSubDepartamento"
                                             class="form-group col-md-6">

                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTurno">Servicio:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-server"></i></span>
                                                </div>
                                                <select id="servicioOrdenCA" class="form-control"
                                                        name="servicioOrdenCA">
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
                                        <div name="visualizarNomServicio" id="visualizarNomServicio"
                                             class="form-group col-md-6">

                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputRecinto">Recinto:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-map-marker"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="recintoOrdenCA"
                                                       name="recintoOrdenCA" placeholder="Recinto">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="exampleInputPMA">PMA:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-map-marker"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="pmaOrdenCA"
                                                       name="pmaOrdenCA" placeholder="PMA">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="exampleInputCoordenadas">Coordenadas:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-map-marker"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="coordenadasOrdenCA"
                                                       name="coordenadasOrdenCA" placeholder="Coordenadas">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="exampleInputCodigoTarjeta">Tipo Mantención:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-th-list"></i></span>
                                                </div>
                                                <select name="tipoMantencionCA" class="form-control"
                                                        id="tipoMantencionCA">
                                                    <option value="" disabled selected>-- Seleccione --</option>
                                                    <option value="MPE">MPE - Mantenimiento Preventivo Externo
                                                    </option>
                                                    <option value="MRE">MRE - Mantenimiento Reparativo Externo
                                                    </option>
                                                    <option value="MPI">MPI - Mantenimiento Preventivo Interno
                                                    </option>
                                                    <option value="MRI">MRI - Mantenimiento Reparativo Interno
                                                    </option>
                                                    <option value="OM">OM - Obras Menores</option>
                                                    <option value="IS">IS - Inspección/Supervisión</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputDetalleTrabajo">Problema que presenta:</label>
                                        <textarea id="detalleTrabajoCA" style="resize:none;"
                                                  name="problemaPresentaCA" readonly class="form-control" rows="3"
                                                  placeholder="Detalle el problema que presenta.."><?php echo $detalleTurnoCA?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputDetalleTrabajo">Trabajo Realizado:</label>
                                        <textarea id="trabajoRealizadoCA" style="resize:none;"
                                                  name="trabajoRealizadoCA" class="form-control" rows="3"
                                                  placeholder="Escriba el trabajo que realizo.."></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputDetalleTrabajo">Acciones Realizadas:</label>
                                        <textarea id="accionesRealizaCA" style="resize:none;"
                                                  name="accionesRealizaCA" class="form-control" rows="3"
                                                  placeholder="Acciones realizadas en el trabajo..."><?php echo $general?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputComentarios">Comentarios:</label>
                                        <textarea id="comentariosTrabajosCA" style="resize:none;"
                                                  name="comentariosTrabajosCA" class="form-control" rows="3"
                                                  placeholder="Comentarios"><?php echo $observacion?></textarea>
                                    </div>
                                    <div class="form-row fieldGroupCA">
                                        <div class="form-group col-md-4">
                                            <label for="exampleInputRecinto">Materiales Usados:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-list"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="materialUsadoCA[]"
                                                       name="materialUsadoCA[]" placeholder="Materiales Usados">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="exampleInputPMA">Unidad:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-layer-group"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="unidadCA[]"
                                                       name="unidadCA[]" placeholder="Unidad">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="exampleInputCoordenadas">Cantidad:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-list-ol"></i></span>
                                                </div>
                                                <input type="number" min="1" class="form-control" id="cantidadCA[]"
                                                       name="cantidadCA[]" placeholder="Cantidad">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="exampleInputCoordenadas">&nbsp;</label>
                                            <div class="input-group mb-3">
                                                <a href="javascript:void(0)"
                                                   class="btn btn-block btn-success addMoreCA"><span
                                                            class="glyphicon glyphicon glyphicon-plus"
                                                            aria-hidden="true"></span><b>Agregar Material Usado</b> </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <!-- <label for="customFile">Custom File</label> -->
                                        <label for="exampleInputTareasControlCent">Subir Imagenes: (Maximo 10
                                            Imagenes)</label><br>
                                        <label for="exampleInputTareasControlCent">Formatos Permitidos: .jpg, .png,
                                            .jpeg, .bmp, .tif, .webp, .svg, .jfif</label>
                                        <div class="custom-file">
                                            <input type="file" max-uploads="10" class="custom-file-input"
                                                   multiple="" id="archivoImagenOrdenCA[]"
                                                   name="archivoImagenOrdenCA[]"
                                                   accept=".jpg, .png, .jpeg, .bmp, .tif, .webp, .svg, .jfif">
                                            <label class="custom-file-label" for="customFile"><b>Seleccionar
                                                    Archivo...</b> </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <!-- <label for="customFile">Custom File</label> -->
                                        <label for="exampleInputTareasControlCent">Subir Archivos: (Maximo 10
                                            Archivos)</label><br>
                                        <label for="exampleInputTareasControlCent">Formatos Permitidos: .pdf,
                                            .xlsx(Excel), .docx(Word) </label>
                                        <div class="custom-file">
                                            <input type="file" max-uploads="10" class="custom-file-input"
                                                   multiple="" id="archivosOrdenCA[]" name="archivosOrdenCA[]"
                                                   accept=".pdf, .xlsx, .docx">
                                            <label class="custom-file-label" for="customFile"><b>Seleccionar
                                                    Archivo...</b> </label>
                                        </div>
                                    </div>
                                    <br>
                                    <?php
                                    date_default_timezone_set('America/Santiago');
                                    ?>
                                    <input type="hidden" readonly="readonly" id="fechaActualOrden"
                                           name="fechaActualOrden" class="form-control"
                                           value="<?php echo date('d-m-Y'); ?>" />
                                    <fieldset class="border p-2">
                                        <!--<legend class="w-auto">Información del Equipo</legend>-->
                                        <div class="row col-md-12 col-12 text-center justify-content-center">
                                            <br>
                                            <br>
                                            <button type="button" class="btn btn-primary text-center mb-5 ml-2"
                                                    id="botonAddHTCA" data-toggle="modal" data-target="#modalHojasCA"><i
                                                        class="fa-solid fas fa-plus"></i>&nbsp;&nbsp;<b>Añadir Equipo a
                                                    la Hoja Técnica</b> </button>
                                        </div>

                                        <div class="modal fade" id="modalHojasCA" data-backdrop="static"
                                             data-keyboard="false" tabindex="-1" aria-labelledby="modalHojasLabel"
                                             aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary">
                                                        <h5 class="modal-title" id="modalHojasLabel">Escoja el
                                                            equipamiento</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table id="hoja_tecnicasCA"
                                                               class="table table-sm table-hover  table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th scope="col" class="text-center">N°</th>
                                                                <th scope="col" class="text-center">Fecha
                                                                    Registro</th>
                                                                <th scope="col" class="text-center">Nombre del
                                                                    Equipamiento</th>
                                                                <th scope="col" class="text-center">Marca</th>
                                                                <th scope="col" class="text-center">Recinto</th>
                                                                <th scope="col" class="text-center">Servicio
                                                                </th>
                                                                <th scope="col" class="text-center">PMA</th>
                                                                <th scope="col" class="text-center">Seleccionar
                                                                </th>
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
                                                                    <td><?php echo mb_convert_case($fechaRegistro, MB_CASE_UPPER, 'UTF-8');  ?>
                                                                    </td>
                                                                    <td><?php echo mb_convert_case($nombreEquipo, MB_CASE_UPPER, 'UTF-8'); ?>
                                                                    </td>
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
                                                                        <button type="button"
                                                                                onclick="obtenerid2(<?php echo $id?>)"
                                                                                class="btn btn-danger">Añadir Este
                                                                            Equipo</button>
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
                                                                <th scope="col" class="text-center">Fecha
                                                                    Registro</th>
                                                                <th scope="col" class="text-center">Nombre del
                                                                    Equipamiento</th>
                                                                <th scope="col" class="text-center">Marca</th>
                                                                <th scope="col" class="text-center">Recinto</th>
                                                                <th scope="col" class="text-center">Servicio
                                                                </th>
                                                                <th scope="col" class="text-center">PMA</th>
                                                                <th scope="col" class="text-center">Seleccionar
                                                                </th>

                                                            </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="modalHojasCA" data-backdrop="static"
                                             data-keyboard="false" tabindex="-1" aria-labelledby="modalHojasLabel"
                                             aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary">
                                                        <h5 class="modal-title" id="modalHojasLabel">Escoja el
                                                            equipamiento</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table id="hoja_tecnicasCA"
                                                               class="table table-sm table-hover  table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th scope="col" class="text-center">N°</th>
                                                                <th scope="col" class="text-center">Fecha
                                                                    Registro</th>
                                                                <th scope="col" class="text-center">Nombre del
                                                                    Equipamiento</th>
                                                                <th scope="col" class="text-center">Marca</th>
                                                                <th scope="col" class="text-center">Recinto</th>
                                                                <th scope="col" class="text-center">Servicio
                                                                </th>
                                                                <th scope="col" class="text-center">Seleccionar
                                                                </th>
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
                                                                    <td><?php echo mb_convert_case($fechaRegistro, MB_CASE_UPPER, 'UTF-8');  ?>
                                                                    </td>
                                                                    <td><?php echo mb_convert_case($nombreEquipo, MB_CASE_UPPER, 'UTF-8'); ?>
                                                                    </td>
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
                                                                        <button type="button"
                                                                                onclick="obtenerid2(<?php echo $id?>)"
                                                                                class="btn btn-danger">Añadir Este
                                                                            Equipo</button>
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
                                                                <th scope="col" class="text-center">Fecha
                                                                    Registro</th>
                                                                <th scope="col" class="text-center">Nombre del
                                                                    Equipamiento</th>
                                                                <th scope="col" class="text-center">Marca</th>
                                                                <th scope="col" class="text-center">Recinto</th>
                                                                <th scope="col" class="text-center">Servicio
                                                                </th>
                                                                <th scope="col" class="text-center">Seleccionar
                                                                </th>

                                                            </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Cerrar</button>
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
                                                           class="form-control" id="nomEquipoCA" name="nomEquipoCA"
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
                                                           class="form-control" id="numInventarioCA"
                                                           name="numInventarioCA" value=""
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
                                                           class="form-control" id="marcaEquipoCA" name="marcaEquipoCA"
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
                                                           class="form-control" id="modeloEquipoCA"
                                                           name="modeloEquipoCA" value=""
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
                                                           class="form-control" id="numSerieCA" name="numSerieCA"
                                                           value="" placeholder="N° de serie del equipo">
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </fieldset>
                                <input type="hidden" id="idTGCA" name="idTGCA" value="<?php echo $idTG?>">
                                <input type="hidden" id="folioTGCA" name="folioTGCA" value="<?php echo $folioTG?>">
                                <input type="hidden" readonly="readonly" id="fechaActualOrdenCA"
                                       name="fechaActualOrdenCA" class="form-control"
                                       value="<?php echo date('d-m-Y'); ?>" />
                                <input type="hidden" id="idHojaCA" name="idHojaCA" value="">
                                <input type="hidden" id="idEquipoCA" name="idEquipoCA" value="">
                                <input type="hidden" id="idCategoriaCA" name="idCategoriaCA" value="">
                                <input type="hidden" readonly style="text-transform:capitalize" class="form-control"
                                       id="solicitanteCA" name="solicitanteCA" value="<?php echo $solicitanteTG?>">
                                <button type="submit" class="btn btn-block btn-primary" id="GuardarCA"
                                        style="display: none" name="GuardarCA">Guardar</button>
                            </form>
                            <form action="../sdo-funciones/crear-ht-CCTV.php" method="POST"
                                  enctype="multipart/form-data">
                                <fieldset class="border p-3" id="fielsetTrabajoCCTV" style="display: none">

                                    <legend class="w-auto">Trabajo Terminado - CCTV</legend>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputQuienSolicita">N° Orden de Trabajo:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-file"></i></span>
                                                </div>
                                                <input type="text" style="text-transform:capitalize"
                                                       class="form-control" id="noOrdenTrabajoCCTV"
                                                       name="noOrdenTrabajoCCTV" value="<?php echo $folioTG?>"
                                                       placeholder="Escriba el N° de Orden de Trabajo">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputQuienSolicita">Ticket Asociado:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-file"></i></span>
                                                </div>
                                                <input type="text" style="text-transform:capitalize"
                                                       class="form-control" id="ticketAsociadoCCTV"
                                                       name="ticketAsociadoCCTV" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputNomSolicitante">Tecnico Recepcionador:</label>
                                            <div class="input-group mb-3">

                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" readonly style="text-transform:capitalize"
                                                       class="form-control" id="nomTecnicoRecepcionadorCCTV"
                                                       name="nomTecnicoRecepcionadorCCTV"
                                                       value="<?php echo $nomUsuario; ?>"
                                                       placeholder=" Escriba El Nombre Funcionario Recepcionador">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputDepartamento">Departamento:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-server"></i></span>
                                                </div>
                                                <select id="departamentoSolicitanteCCTV" class="form-control"
                                                        name="departamentoSolicitanteCCTV">
                                                    <?php

                                                    $queryDepartamentos = "SELECT * FROM departamentos WHERE nomDepartamento NOT LIKE '%Control Centralizado%'  ORDER BY nomDepartamento ASC";

                                                    $resDepartamentos = mysqli_query($conexion, $queryDepartamentos);

                                                    while ($rowDepartamentos = $resDepartamentos->fetch_assoc()) {

                                                        if ($rowDepartamentos['idDepartamento'] == $idDepartamento) {
                                                            ?>
                                                            <option
                                                                    value="<?php echo $rowDepartamentos['idDepartamento']; ?>"
                                                                    selected="selected">
                                                                <?php echo $rowDepartamentos['nomDepartamento']; ?>
                                                            </option>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <option
                                                                    value="<?php echo $rowDepartamentos['idDepartamento']; ?>">
                                                                <?php echo $rowDepartamentos['nomDepartamento']; ?>
                                                            </option>
                                                            <?php
                                                        }
                                                    }

                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div name="visualizarSubDepartamento" id="visualizarSubDepartamento"
                                             class="form-group col-md-6">

                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTurno">Servicio:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-server"></i></span>
                                                </div>
                                                <select id="servicioOrdenCCTV" class="form-control"
                                                        name="servicioOrdenCCTV">
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
                                        <div name="visualizarNomServicio" id="visualizarNomServicio"
                                             class="form-group col-md-6">

                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputRecinto">Recinto:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-map-marker"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="recintoOrdenCCTV"
                                                       name="recintoOrdenCCTV" placeholder="Recinto">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="exampleInputPMA">PMA:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-map-marker"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="pmaOrdenCCTV"
                                                       name="pmaOrdenCCTV" placeholder="PMA">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="exampleInputCoordenadas">Coordenadas:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-map-marker"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="coordenadasOrdenCCTV"
                                                       name="coordenadasOrdenCCTV" placeholder="Coordenadas">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="exampleInputCodigoTarjeta">Tipo Mantención:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-th-list"></i></span>
                                                </div>
                                                <select name="tipoMantencionCCTV" class="form-control"
                                                        id="tipoMantencionCCTV">
                                                    <option value="" disabled selected>-- Seleccione --</option>
                                                    <option value="MPE">MPE - Mantenimiento Preventivo Externo
                                                    </option>
                                                    <option value="MRE">MRE - Mantenimiento Reparativo Externo
                                                    </option>
                                                    <option value="MPI">MPI - Mantenimiento Preventivo Interno
                                                    </option>
                                                    <option value="MRI">MRI - Mantenimiento Reparativo Interno
                                                    </option>
                                                    <option value="OM">OM - Obras Menores</option>
                                                    <option value="IS">IS - Inspección/Supervisión</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputDetalleTrabajo">Problema que presenta:</label>
                                        <textarea id="detalleTrabajoCCTV" style="resize:none;"
                                                  name="problemaPresentaCCTV" readonly class="form-control" rows="3"
                                                  placeholder="Detalle el problema que presenta.."><?php echo $detalleTurnoCamara?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputDetalleTrabajo">Trabajo Realizado:</label>
                                        <textarea id="trabajoRealizadoCCTV" style="resize:none;"
                                                  name="trabajoRealizadoCCTV" class="form-control" rows="3"
                                                  placeholder="Escriba el trabajo que realizo.."></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputDetalleTrabajo">Acciones Realizadas:</label>
                                        <textarea id="accionesRealizaCCTV" style="resize:none;"
                                                  name="accionesRealizaCCTV" class="form-control" rows="3"
                                                  placeholder="Acciones realizadas en el trabajo..."><?php echo $general?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputComentarios">Comentarios:</label>
                                        <textarea id="comentariosTrabajosCCTV" style="resize:none;"
                                                  name="comentariosTrabajosCCTV" class="form-control" rows="3"
                                                  placeholder="Comentarios"><?php echo $observacion?></textarea>
                                    </div>
                                    <div class="form-row fieldGroupCCTV">
                                        <div class="form-group col-md-4">
                                            <label for="exampleInputRecinto">Materiales Usados:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-list"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="materialUsadoCCTV[]"
                                                       name="materialUsadoCCTV[]" placeholder="Materiales Usados">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="exampleInputPMA">Unidad:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-layer-group"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="unidadCCTV[]"
                                                       name="unidadCCTV[]" placeholder="Unidad">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="exampleInputCoordenadas">Cantidad:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-list-ol"></i></span>
                                                </div>
                                                <input type="number" min="1" class="form-control"
                                                       id="cantidadCCTV[]" name="cantidadCCTV[]"
                                                       placeholder="Cantidad">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="exampleInputCoordenadas">&nbsp;</label>
                                            <div class="input-group mb-3">
                                                <a href="javascript:void(0)"
                                                   class="btn btn-block btn-success addMoreCCTV"><span
                                                            class="glyphicon glyphicon glyphicon-plus"
                                                            aria-hidden="true"></span><b>Agregar Material Usado</b> </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <!-- <label for="customFile">Custom File</label> -->
                                        <label for="exampleInputTareasControlCent">Subir Imagenes: (Maximo 10
                                            Imagenes)</label><br>
                                        <label for="exampleInputTareasControlCent">Formatos Permitidos: .jpg, .png,
                                            .jpeg, .bmp, .tif, .webp, .svg, .jfif</label>
                                        <div class="custom-file">
                                            <input type="file" max-uploads="10" class="custom-file-input"
                                                   multiple="" id="archivoImagenOrdenCCTV[]"
                                                   name="archivoImagenOrdenCCTV[]"
                                                   accept=".jpg, .png, .jpeg, .bmp, .tif, .webp, .svg, .jfif">
                                            <label class="custom-file-label" for="customFile"><b>Seleccionar
                                                    Archivo...</b> </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <!-- <label for="customFile">Custom File</label> -->
                                        <label for="exampleInputTareasControlCent">Subir Archivos: (Maximo 10
                                            Archivos)</label><br>
                                        <label for="exampleInputTareasControlCent">Formatos Permitidos: .pdf,
                                            .xlsx(Excel), .docx(Word) </label>
                                        <div class="custom-file">
                                            <input type="file" max-uploads="10" class="custom-file-input"
                                                   multiple="" id="archivosOrdenCCTV[]" name="archivosOrdenCCTV[]"
                                                   accept=".pdf, .xlsx, .docx">
                                            <label class="custom-file-label" for="customFile"><b>Seleccionar
                                                    Archivo...</b> </label>
                                        </div>
                                    </div>
                                    <br>
                                    <?php
                                    date_default_timezone_set('America/Santiago');
                                    ?>
                                    <input type="hidden" readonly="readonly" id="fechaActualOrden"
                                           name="fechaActualOrden" class="form-control"
                                           value="<?php echo date('d-m-Y'); ?>" />
                                    <fieldset class="border p-2">
                                        <!--<legend class="w-auto">Información del Equipo</legend>-->
                                        <div class="row col-md-12 col-12 text-center justify-content-center">
                                            <br>
                                            <br>
                                            <button type="button" class="btn btn-primary text-center mb-5 ml-2"
                                                    id="botonAddHTCCTV" data-toggle="modal"
                                                    data-target="#modalHojasCCTV"><i
                                                        class="fa-solid fas fa-plus"></i>&nbsp;&nbsp;<b>Añadir Equipo a
                                                    la Hoja Técnica</b> </button>
                                        </div>

                                        <div class="modal fade" id="modalHojasCCTV" data-backdrop="static"
                                             data-keyboard="false" tabindex="-1" aria-labelledby="modalHojasLabel"
                                             aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary">
                                                        <h5 class="modal-title" id="modalHojasLabel">Escoja el
                                                            equipamiento</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table id="hoja_tecnicasCCTV"
                                                               class="table table-sm table-hover  table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th scope="col" class="text-center">N°</th>
                                                                <th scope="col" class="text-center">Fecha
                                                                    Registro</th>
                                                                <th scope="col" class="text-center">Nombre del
                                                                    Equipamiento</th>
                                                                <th scope="col" class="text-center">Marca</th>
                                                                <th scope="col" class="text-center">Recinto</th>
                                                                <th scope="col" class="text-center">Servicio
                                                                </th>
                                                                <th scope="col" class="text-center">PMA</th>
                                                                <th scope="col" class="text-center">Seleccionar
                                                                </th>
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
                                                                    <td><?php echo mb_convert_case($fechaRegistro, MB_CASE_UPPER, 'UTF-8');  ?>
                                                                    </td>
                                                                    <td><?php echo mb_convert_case($nombreEquipo, MB_CASE_UPPER, 'UTF-8'); ?>
                                                                    </td>
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
                                                                        <button type="button"
                                                                                onclick="obtenerid3(<?php echo $id?>)"
                                                                                class="btn btn-danger">Añadir Este
                                                                            Equipo</button>
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
                                                                <th scope="col" class="text-center">Fecha
                                                                    Registro</th>
                                                                <th scope="col" class="text-center">Nombre del
                                                                    Equipamiento</th>
                                                                <th scope="col" class="text-center">Marca</th>
                                                                <th scope="col" class="text-center">Recinto</th>
                                                                <th scope="col" class="text-center">Servicio
                                                                </th>
                                                                <th scope="col" class="text-center">PMA</th>
                                                                <th scope="col" class="text-center">Seleccionar
                                                                </th>

                                                            </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="modalHojasCCTV" data-backdrop="static"
                                             data-keyboard="false" tabindex="-1" aria-labelledby="modalHojasLabel"
                                             aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary">
                                                        <h5 class="modal-title" id="modalHojasLabel">Escoja el
                                                            equipamiento</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table id="hoja_tecnicasCCTV"
                                                               class="table table-sm table-hover  table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th scope="col" class="text-center">N°</th>
                                                                <th scope="col" class="text-center">Fecha
                                                                    Registro</th>
                                                                <th scope="col" class="text-center">Nombre del
                                                                    Equipamiento</th>
                                                                <th scope="col" class="text-center">Marca</th>
                                                                <th scope="col" class="text-center">Recinto</th>
                                                                <th scope="col" class="text-center">Servicio
                                                                </th>
                                                                <th scope="col" class="text-center">Seleccionar
                                                                </th>
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
                                                                    <td><?php echo mb_convert_case($fechaRegistro, MB_CASE_UPPER, 'UTF-8');  ?>
                                                                    </td>
                                                                    <td><?php echo mb_convert_case($nombreEquipo, MB_CASE_UPPER, 'UTF-8'); ?>
                                                                    </td>
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
                                                                        <button type="button"
                                                                                onclick="obtenerid3(<?php echo $id?>)"
                                                                                class="btn btn-danger">Añadir Este
                                                                            Equipo</button>
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
                                                                <th scope="col" class="text-center">Fecha
                                                                    Registro</th>
                                                                <th scope="col" class="text-center">Nombre del
                                                                    Equipamiento</th>
                                                                <th scope="col" class="text-center">Marca</th>
                                                                <th scope="col" class="text-center">Recinto</th>
                                                                <th scope="col" class="text-center">Servicio
                                                                </th>
                                                                <th scope="col" class="text-center">Seleccionar
                                                                </th>

                                                            </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Cerrar</button>
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
                                                           class="form-control" id="nomEquipoCCTV" name="nomEquipoCCTV"
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
                                                           class="form-control" id="numInventarioCCTV"
                                                           name="numInventarioCCTV" value=""
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
                                                           class="form-control" id="marcaEquipoCCTV"
                                                           name="marcaEquipoCCTV" value=""
                                                           placeholder="Marca del equipo">
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
                                                           class="form-control" id="modeloEquipoCCTV"
                                                           name="modeloEquipoCCTV" value=""
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
                                                           class="form-control" id="numSerieCCTV" name="numSerieCCTV"
                                                           value="" placeholder="N° de serie del equipo">
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </fieldset>
                                <input type="hidden" id="idTGCCTV" name="idTGCCTV" value="<?php echo $idTG?>">
                                <input type="hidden" id="folioTGCCTV" name="folioTGCCTV"
                                       value="<?php echo $folioTG?>">
                                <input type="hidden" readonly="readonly" id="fechaActualOrdenCCTV"
                                       name="fechaActualOrdenCCTV" class="form-control"
                                       value="<?php echo date('d-m-Y'); ?>" />
                                <input type="hidden" id="idHojaCCTV" name="idHojaCCTV" value="">
                                <input type="hidden" id="idEquipoCCTV" name="idEquipoCCTV" value="">
                                <input type="hidden" id="idCategoriaCCTV" name="idCategoriaCCTV" value="">
                                <input type="hidden" readonly style="text-transform:capitalize" class="form-control"
                                       id="solicitanteCCTV" name="solicitanteCCTV" value="<?php echo $solicitanteTG?>">
                                <button type="submit" class="btn btn-block btn-primary" id="GuardarCCTV"
                                        style="display: none" name="GuardarCCTV">Guardar</button>
                            </form>
                            <form action="../sdo-funciones/crear-ht-lle.php" method="POST"
                                  enctype="multipart/form-data">
                                <fieldset class="border p-3" id="fielsetTrabajoLLE" style="display: none">
                                    <legend class="w-auto">Trabajo Terminado - LLE</legend>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputQuienSolicita">N° Orden de Trabajo:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-file"></i></span>
                                                </div>
                                                <input type="text" style="text-transform:capitalize"
                                                       class="form-control" id="noOrdenTrabajoLLE"
                                                       name="noOrdenTrabajoLLE" value="<?php echo $folioTG?>"
                                                       placeholder="Escriba el N° de Orden de Trabajo">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputQuienSolicita">Ticket Asociado:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-file"></i></span>
                                                </div>
                                                <input type="text" style="text-transform:capitalize"
                                                       class="form-control" id="ticketAsociadoLLE"
                                                       name="ticketAsociadoLLE" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputNomSolicitante">Tecnico Recepcionador:</label>
                                            <div class="input-group mb-3">

                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" readonly style="text-transform:capitalize"
                                                       class="form-control" id="nomTecnicoRecepcionadorLLE"
                                                       name="nomTecnicoRecepcionadorLLE"
                                                       value="<?php echo $nomUsuario; ?>"
                                                       placeholder=" Escriba El Nombre Funcionario Recepcionador">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputDepartamento">Departamento:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-server"></i></span>
                                                </div>
                                                <select id="departamentoSolicitanteLLE" class="form-control"
                                                        name="departamentoSolicitanteLLE">
                                                    <?php

                                                    $queryDepartamentos = "SELECT * FROM departamentos WHERE nomDepartamento NOT LIKE '%Control Centralizado%'  ORDER BY nomDepartamento ASC";

                                                    $resDepartamentos = mysqli_query($conexion, $queryDepartamentos);

                                                    while ($rowDepartamentos = $resDepartamentos->fetch_assoc()) {

                                                        if ($rowDepartamentos['idDepartamento'] == $idDepartamento) {
                                                            ?>
                                                            <option
                                                                    value="<?php echo $rowDepartamentos['idDepartamento']; ?>"
                                                                    selected="selected">
                                                                <?php echo $rowDepartamentos['nomDepartamento']; ?>
                                                            </option>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <option
                                                                    value="<?php echo $rowDepartamentos['idDepartamento']; ?>">
                                                                <?php echo $rowDepartamentos['nomDepartamento']; ?>
                                                            </option>
                                                            <?php
                                                        }
                                                    }

                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div name="visualizarSubDepartamento" id="visualizarSubDepartamento"
                                             class="form-group col-md-6">

                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTurno">Servicio:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-server"></i></span>
                                                </div>
                                                <select id="servicioOrdenLLE" class="form-control"
                                                        name="servicioOrdenLLE">
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
                                        <div name="visualizarNomServicio" id="visualizarNomServicio"
                                             class="form-group col-md-6">

                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputRecinto">Recinto:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-map-marker"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="recintoOrdenLLE"
                                                       name="recintoOrdenLLE" placeholder="Recinto">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="exampleInputPMA">PMA:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-map-marker"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="pmaOrdenLLE"
                                                       name="pmaOrdenLLE" placeholder="PMA">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="exampleInputCoordenadas">Coordenadas:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-map-marker"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="coordenadasOrdenLLE"
                                                       name="coordenadasOrdenLLE" placeholder="Coordenadas">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="exampleInputCodigoTarjeta">Tipo Mantención:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-th-list"></i></span>
                                                </div>
                                                <select name="tipoMantencionLLE" class="form-control"
                                                        id="tipoMantencionLLE">
                                                    <option value="" disabled selected>-- Seleccione --</option>
                                                    <option value="MPE">MPE - Mantenimiento Preventivo Externo
                                                    </option>
                                                    <option value="MRE">MRE - Mantenimiento Reparativo Externo
                                                    </option>
                                                    <option value="MPI">MPI - Mantenimiento Preventivo Interno
                                                    </option>
                                                    <option value="MRI">MRI - Mantenimiento Reparativo Interno
                                                    </option>
                                                    <option value="OM">OM - Obras Menores</option>
                                                    <option value="IS">IS - Inspección/Supervisión</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputDetalleTrabajo">Problema que presenta:</label>
                                        <textarea id="detalleTrabajoLLE" style="resize:none;"
                                                  name="problemaPresentaLLE" readonly class="form-control" rows="3"
                                                  placeholder="Detalle el problema que presenta.."><?php echo $detalleTurnoLLE?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputDetalleTrabajo">Trabajo Realizado:</label>
                                        <textarea id="trabajoRealizadoLLE" style="resize:none;"
                                                  name="trabajoRealizadoLLE" class="form-control" rows="3"
                                                  placeholder="Escriba el trabajo que realizo.."></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputDetalleTrabajo">Acciones Realizadas:</label>
                                        <textarea id="accionesRealizaLLE" style="resize:none;"
                                                  name="accionesRealizaLLE" class="form-control" rows="3"
                                                  placeholder="Acciones realizadas en el trabajo..."><?php echo $general?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputComentarios">Comentarios:</label>
                                        <textarea id="comentariosTrabajosLLE" style="resize:none;"
                                                  name="comentariosTrabajosLLE" class="form-control" rows="3"
                                                  placeholder="Comentarios"><?php echo $observacion?></textarea>
                                    </div>
                                    <div class="form-row fieldGroupLLE">
                                        <div class="form-group col-md-4">
                                            <label for="exampleInputRecinto">Materiales Usados:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-list"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="materialUsadoLLE[]"
                                                       name="materialUsadoLLE[]" placeholder="Materiales Usados">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="exampleInputPMA">Unidad:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-layer-group"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="unidadLLE[]"
                                                       name="unidadLLE[]" placeholder="Unidad">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="exampleInputCoordenadas">Cantidad:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-list-ol"></i></span>
                                                </div>
                                                <input type="number" min="1" class="form-control" id="cantidadLLE[]"
                                                       name="cantidadLLE[]" placeholder="Cantidad">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="exampleInputCoordenadas">&nbsp;</label>
                                            <div class="input-group mb-3">
                                                <a href="javascript:void(0)"
                                                   class="btn btn-block btn-success addMoreLLE"><span
                                                            class="glyphicon glyphicon glyphicon-plus"
                                                            aria-hidden="true"></span><b>Agregar Material Usado</b> </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <!-- <label for="customFile">Custom File</label> -->
                                        <label for="exampleInputTareasControlCent">Subir Imagenes: (Maximo 10
                                            Imagenes)</label><br>
                                        <label for="exampleInputTareasControlCent">Formatos Permitidos: .jpg, .png,
                                            .jpeg, .bmp, .tif, .webp, .svg, .jfif</label>
                                        <div class="custom-file">
                                            <input type="file" max-uploads="10" class="custom-file-input"
                                                   multiple="" id="archivoImagenOrdenLLE[]"
                                                   name="archivoImagenOrdenLLE[]"
                                                   accept=".jpg, .png, .jpeg, .bmp, .tif, .webp, .svg, .jfif">
                                            <label class="custom-file-label" for="customFile"><b>Seleccionar
                                                    Archivo...</b> </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <!-- <label for="customFile">Custom File</label> -->
                                        <label for="exampleInputTareasControlCent">Subir Archivos: (Maximo 10
                                            Archivos)</label><br>
                                        <label for="exampleInputTareasControlCent">Formatos Permitidos: .pdf,
                                            .xlsx(Excel), .docx(Word) </label>
                                        <div class="custom-file">
                                            <input type="file" max-uploads="10" class="custom-file-input"
                                                   multiple="" id="archivosOrdenLLE[]" name="archivosOrdenLLE[]"
                                                   accept=".pdf, .xlsx, .docx">
                                            <label class="custom-file-label" for="customFile"><b>Seleccionar
                                                    Archivo...</b> </label>
                                        </div>
                                    </div>
                                    <br>
                                    <?php
                                    date_default_timezone_set('America/Santiago');
                                    ?>
                                    <input type="hidden" readonly="readonly" id="fechaActualOrden"
                                           name="fechaActualOrden" class="form-control"
                                           value="<?php echo date('d-m-Y'); ?>" />
                                    <fieldset class="border p-2">
                                        <!--<legend class="w-auto">Información del Equipo</legend>-->
                                        <div class="row col-md-12 col-12 text-center justify-content-center">
                                            <br>
                                            <br>
                                            <button type="button" class="btn btn-primary text-center mb-5 ml-2"
                                                    id="botonAddHTLLE" data-toggle="modal"
                                                    data-target="#modalHojasLLE"><i
                                                        class="fa-solid fas fa-plus"></i>&nbsp;&nbsp;<b>Añadir Equipo a
                                                    la Hoja Técnica</b> </button>
                                        </div>

                                        <div class="modal fade" id="modalHojasLLE" data-backdrop="static"
                                             data-keyboard="false" tabindex="-1" aria-labelledby="modalHojasLabel"
                                             aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary">
                                                        <h5 class="modal-title" id="modalHojasLabel">Escoja el
                                                            equipamiento</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table id="hoja_tecnicasLLE"
                                                               class="table table-sm table-hover  table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th scope="col" class="text-center">N°</th>
                                                                <th scope="col" class="text-center">Fecha
                                                                    Registro</th>
                                                                <th scope="col" class="text-center">Nombre del
                                                                    Equipamiento</th>
                                                                <th scope="col" class="text-center">Marca</th>
                                                                <th scope="col" class="text-center">Recinto</th>
                                                                <th scope="col" class="text-center">Servicio
                                                                </th>
                                                                <th scope="col" class="text-center">PMA</th>
                                                                <th scope="col" class="text-center">Seleccionar
                                                                </th>
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
                                                                    <td><?php echo mb_convert_case($fechaRegistro, MB_CASE_UPPER, 'UTF-8');  ?>
                                                                    </td>
                                                                    <td><?php echo mb_convert_case($nombreEquipo, MB_CASE_UPPER, 'UTF-8'); ?>
                                                                    </td>
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
                                                                        <button type="button"
                                                                                onclick="obtenerid4(<?php echo $id?>)"
                                                                                class="btn btn-danger">Añadir Este
                                                                            Equipo</button>
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
                                                                <th scope="col" class="text-center">Fecha
                                                                    Registro</th>
                                                                <th scope="col" class="text-center">Nombre del
                                                                    Equipamiento</th>
                                                                <th scope="col" class="text-center">Marca</th>
                                                                <th scope="col" class="text-center">Recinto</th>
                                                                <th scope="col" class="text-center">Servicio
                                                                </th>
                                                                <th scope="col" class="text-center">PMA</th>
                                                                <th scope="col" class="text-center">Seleccionar
                                                                </th>

                                                            </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="modalHojasLLE" data-backdrop="static"
                                             data-keyboard="false" tabindex="-1" aria-labelledby="modalHojasLabel"
                                             aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary">
                                                        <h5 class="modal-title" id="modalHojasLabel">Escoja el
                                                            equipamiento</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table id="hoja_tecnicasLLE"
                                                               class="table table-sm table-hover  table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th scope="col" class="text-center">N°</th>
                                                                <th scope="col" class="text-center">Fecha
                                                                    Registro</th>
                                                                <th scope="col" class="text-center">Nombre del
                                                                    Equipamiento</th>
                                                                <th scope="col" class="text-center">Marca</th>
                                                                <th scope="col" class="text-center">Recinto</th>
                                                                <th scope="col" class="text-center">Servicio
                                                                </th>
                                                                <th scope="col" class="text-center">Seleccionar
                                                                </th>
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
                                                                    <td><?php echo mb_convert_case($fechaRegistro, MB_CASE_UPPER, 'UTF-8');  ?>
                                                                    </td>
                                                                    <td><?php echo mb_convert_case($nombreEquipo, MB_CASE_UPPER, 'UTF-8'); ?>
                                                                    </td>
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
                                                                        <button type="button"
                                                                                onclick="obtenerid4(<?php echo $id?>)"
                                                                                class="btn btn-danger">Añadir Este
                                                                            Equipo</button>
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
                                                                <th scope="col" class="text-center">Fecha
                                                                    Registro</th>
                                                                <th scope="col" class="text-center">Nombre del
                                                                    Equipamiento</th>
                                                                <th scope="col" class="text-center">Marca</th>
                                                                <th scope="col" class="text-center">Recinto</th>
                                                                <th scope="col" class="text-center">Servicio
                                                                </th>
                                                                <th scope="col" class="text-center">Seleccionar
                                                                </th>

                                                            </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Cerrar</button>
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
                                                           class="form-control" id="nomEquipoLLE" name="nomEquipoLLE"
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
                                                           class="form-control" id="numInventarioLLE"
                                                           name="numInventarioLLE" value=""
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
                                                           class="form-control" id="marcaEquipoLLE"
                                                           name="marcaEquipoLLE" value=""
                                                           placeholder="Marca del equipo">
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
                                                           class="form-control" id="modeloEquipoLLE"
                                                           name="modeloEquipoLLE" value=""
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
                                                           class="form-control" id="numSerieLLE" name="numSerieLLE"
                                                           value="" placeholder="N° de serie del equipo">
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </fieldset>
                                <!--HASTA AQUI-->
                                <input type="hidden" id="idTGCCTV" name="idTGLLE" value="<?php echo $idTG?>">
                                <input type="hidden" id="folioTGCCTV" name="folioTGLLE"
                                       value="<?php echo $folioTG?>">
                                <input type="hidden" readonly="readonly" id="fechaActualOrdenLLE"
                                       name="fechaActualOrdenLLE" class="form-control"
                                       value="<?php echo date('d-m-Y'); ?>" />
                                <input type="hidden" id="idEquipoLLE" name="idEquipoLLE" value="">
                                <input type="hidden" id="idCategoriaLLE" name="idCategoriaLLE" value="">
                                <input type="hidden" id="idHojaLLE" name="idHojaLLE" value="">
                                <input type="hidden" readonly style="text-transform:capitalize" class="form-control"
                                       id="solicitanteLLE" name="solicitanteLLE" value="<?php echo $solicitanteTG?>">
                                <button type="submit" class="btn btn-block btn-primary" id="GuardarLLE"
                                        style="display: none" name="GuardarLLE">Guardar</button>
                            </form>
                            <form action="../sdo-funciones/crear-ht-otros.php" method="POST"
                                  enctype="multipart/form-data">
                                <fieldset class="border p-3" id="fielsetTrabajoOtros" style="display: none">
                                    <legend class="w-auto">Trabajo Terminado - Otros</legend>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputQuienSolicita">N° Orden de Trabajo:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-file"></i></span>
                                                </div>
                                                <input type="text" style="text-transform:capitalize"
                                                       class="form-control" id="noOrdenTrabajoOTROS"
                                                       name="noOrdenTrabajoOTROS" value="<?php echo $folioTG?>"
                                                       placeholder="Escriba el N° de Orden de Trabajo">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputQuienSolicita">Ticket Asociado:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-file"></i></span>
                                                </div>
                                                <input type="text" style="text-transform:capitalize"
                                                       class="form-control" id="ticketAsociadoOTROS"
                                                       name="ticketAsociadoOTROS" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputNomSolicitante">Tecnico Recepcionador:</label>
                                            <div class="input-group mb-3">

                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" readonly style="text-transform:capitalize"
                                                       class="form-control" id="nomTecnicoRecepcionadorOTROS"
                                                       name="nomTecnicoRecepcionadorOTROS"
                                                       value="<?php echo $nomUsuario; ?>"
                                                       placeholder=" Escriba El Nombre Funcionario Recepcionador">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputDepartamento">Departamento:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-server"></i></span>
                                                </div>
                                                <select id="departamentoSolicitanteOTROS" class="form-control"
                                                        name="departamentoSolicitanteOTROS">
                                                    <?php

                                                    $queryDepartamentos = "SELECT * FROM departamentos WHERE nomDepartamento NOT LIKE '%Control Centralizado%'  ORDER BY nomDepartamento ASC";

                                                    $resDepartamentos = mysqli_query($conexion, $queryDepartamentos);

                                                    while ($rowDepartamentos = $resDepartamentos->fetch_assoc()) {

                                                        if ($rowDepartamentos['idDepartamento'] == $idDepartamento) {
                                                            ?>
                                                            <option
                                                                    value="<?php echo $rowDepartamentos['idDepartamento']; ?>"
                                                                    selected="selected">
                                                                <?php echo $rowDepartamentos['nomDepartamento']; ?>
                                                            </option>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <option
                                                                    value="<?php echo $rowDepartamentos['idDepartamento']; ?>">
                                                                <?php echo $rowDepartamentos['nomDepartamento']; ?>
                                                            </option>
                                                            <?php
                                                        }
                                                    }

                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div name="visualizarSubDepartamento" id="visualizarSubDepartamento"
                                             class="form-group col-md-6">

                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTurno">Servicio:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-server"></i></span>
                                                </div>
                                                <select id="servicioOrdenOTROS" class="form-control"
                                                        name="servicioOrdenOTROS">
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
                                        <div name="visualizarNomServicio" id="visualizarNomServicio"
                                             class="form-group col-md-6">

                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputRecinto">Recinto:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-map-marker"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="recintoOrdenOTROS"
                                                       name="recintoOrdenOTROS" placeholder="Recinto">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="exampleInputPMA">PMA:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-map-marker"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="pmaOrdenOTROS"
                                                       name="pmaOrdenOTROS" placeholder="PMA">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="exampleInputCoordenadas">Coordenadas:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-map-marker"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="coordenadasOrdenOTROS"
                                                       name="coordenadasOrdenOTROS" placeholder="Coordenadas">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="exampleInputCodigoTarjeta">Tipo Mantención:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-th-list"></i></span>
                                                </div>
                                                <select name="tipoMantencionOTROS" class="form-control"
                                                        id="tipoMantencionOTROS">
                                                    <option value="" disabled selected>-- Seleccione --</option>
                                                    <option value="MPE">MPE - Mantenimiento Preventivo Externo
                                                    </option>
                                                    <option value="MRE">MRE - Mantenimiento Reparativo Externo
                                                    </option>
                                                    <option value="MPI">MPI - Mantenimiento Preventivo Interno
                                                    </option>
                                                    <option value="MRI">MRI - Mantenimiento Reparativo Interno
                                                    </option>
                                                    <option value="OM">OM - Obras Menores</option>
                                                    <option value="IS">IS - Inspección/Supervisión</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputDetalleTrabajo">Problema que presenta:</label>
                                        <textarea id="detalleTrabajoOTROS" style="resize:none;"
                                                  name="problemaPresentaOTROS" readonly class="form-control" rows="3"
                                                  placeholder="Detalle el problema que presenta.."><?php echo $detalleOtros?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputDetalleTrabajo">Trabajo Realizado:</label>
                                        <textarea id="trabajoRealizadoOTROS" style="resize:none;"
                                                  name="trabajoRealizadoOTROS" class="form-control" rows="3"
                                                  placeholder="Escriba el trabajo que realizo.."></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputDetalleTrabajo">Acciones Realizadas:</label>
                                        <textarea id="accionesRealizaOTROS" style="resize:none;"
                                                  name="accionesRealizaOTROS" class="form-control" rows="3"
                                                  placeholder="Acciones realizadas en el trabajo..."><?php echo $general?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputComentarios">Comentarios:</label>
                                        <textarea id="comentariosTrabajosOTROS" style="resize:none;"
                                                  name="comentariosTrabajosOTROS" class="form-control" rows="3"
                                                  placeholder="Comentarios"><?php echo $observacion?></textarea>
                                    </div>
                                    <div class="form-row fieldGroupOTROS">
                                        <div class="form-group col-md-4">
                                            <label for="exampleInputRecinto">Materiales Usados:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-list"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="materialUsadoOTROS[]"
                                                       name="materialUsadoOTROS[]" placeholder="Materiales Usados">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="exampleInputPMA">Unidad:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-layer-group"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="unidadOTROS[]"
                                                       name="unidadOTROS[]" placeholder="Unidad">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="exampleInputCoordenadas">Cantidad:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-list-ol"></i></span>
                                                </div>
                                                <input type="number" min="1" class="form-control"
                                                       id="cantidadOTROS[]" name="cantidadOTROS[]"
                                                       placeholder="Cantidad">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="exampleInputCoordenadas">&nbsp;</label>
                                            <div class="input-group mb-3">
                                                <a href="javascript:void(0)"
                                                   class="btn btn-block btn-success addMoreOTROS"><span
                                                            class="glyphicon glyphicon glyphicon-plus"
                                                            aria-hidden="true"></span><b>Agregar Material Usado</b> </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <!-- <label for="customFile">Custom File</label> -->
                                        <label for="exampleInputTareasControlCent">Subir Imagenes: (Maximo 10
                                            Imagenes)</label><br>
                                        <label for="exampleInputTareasControlCent">Formatos Permitidos: .jpg, .png,
                                            .jpeg, .bmp, .tif, .webp, .svg, .jfif</label>
                                        <div class="custom-file">
                                            <input type="file" max-uploads="10" class="custom-file-input"
                                                   multiple="" id="archivoImagenOrdenOTROS[]"
                                                   name="archivoImagenOrdenOTROS[]"
                                                   accept=".jpg, .png, .jpeg, .bmp, .tif, .webp, .svg, .jfif">
                                            <label class="custom-file-label" for="customFile"><b>Seleccionar
                                                    Archivo...</b> </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <!-- <label for="customFile">Custom File</label> -->
                                        <label for="exampleInputTareasControlCent">Subir Archivos: (Maximo 10
                                            Archivos)</label><br>
                                        <label for="exampleInputTareasControlCent">Formatos Permitidos: .pdf,
                                            .xlsx(Excel), .docx(Word) </label>
                                        <div class="custom-file">
                                            <input type="file" max-uploads="10" class="custom-file-input"
                                                   multiple="" id="archivosOrdenOTROS[]" name="archivosOrdenOTROS[]"
                                                   accept=".pdf, .xlsx, .docx">
                                            <label class="custom-file-label" for="customFile"><b>Seleccionar
                                                    Archivo...</b> </label>
                                        </div>
                                    </div>
                                    <br>
                                    <?php
                                    date_default_timezone_set('America/Santiago');
                                    ?>
                                    <input type="hidden" readonly="readonly" id="fechaActualOrden"
                                           name="fechaActualOrden" class="form-control"
                                           value="<?php echo date('d-m-Y'); ?>" />
                                    <fieldset class="border p-2">
                                        <!--<legend class="w-auto">Información del Equipo</legend>-->
                                        <div class="row col-md-12 col-12 text-center justify-content-center">
                                            <br>
                                            <br>
                                            <button type="button" class="btn btn-primary text-center mb-5 ml-2"
                                                    id="botonAddHTOTROS" data-toggle="modal"
                                                    data-target="#modalHojasOTROS"><i
                                                        class="fa-solid fas fa-plus"></i>&nbsp;&nbsp;<b>Añadir Equipo a
                                                    la Hoja Técnica</b> </button>
                                        </div>

                                        <div class="modal fade" id="modalHojasOTROS" data-backdrop="static"
                                             data-keyboard="false" tabindex="-1" aria-labelledby="modalHojasLabel"
                                             aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary">
                                                        <h5 class="modal-title" id="modalHojasLabel">Escoja el
                                                            equipamiento</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table id="hoja_tecnicasOTROS"
                                                               class="table table-sm table-hover  table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th scope="col" class="text-center">N°</th>
                                                                <th scope="col" class="text-center">Fecha
                                                                    Registro</th>
                                                                <th scope="col" class="text-center">Nombre del
                                                                    Equipamiento</th>
                                                                <th scope="col" class="text-center">Marca</th>
                                                                <th scope="col" class="text-center">Recinto</th>
                                                                <th scope="col" class="text-center">Servicio
                                                                </th>
                                                                <th scope="col" class="text-center">PMA</th>
                                                                <th scope="col" class="text-center">Seleccionar
                                                                </th>
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
                                                                    <td><?php echo mb_convert_case($fechaRegistro, MB_CASE_UPPER, 'UTF-8');  ?>
                                                                    </td>
                                                                    <td><?php echo mb_convert_case($nombreEquipo, MB_CASE_UPPER, 'UTF-8'); ?>
                                                                    </td>
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
                                                                        <button type="button"
                                                                                onclick="obtenerid5(<?php echo $id?>)"
                                                                                class="btn btn-danger">Añadir Este
                                                                            Equipo</button>
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
                                                                <th scope="col" class="text-center">Fecha
                                                                    Registro</th>
                                                                <th scope="col" class="text-center">Nombre del
                                                                    Equipamiento</th>
                                                                <th scope="col" class="text-center">Marca</th>
                                                                <th scope="col" class="text-center">Recinto</th>
                                                                <th scope="col" class="text-center">Servicio
                                                                </th>
                                                                <th scope="col" class="text-center">PMA</th>
                                                                <th scope="col" class="text-center">Seleccionar
                                                                </th>

                                                            </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="modalHojasOTROS" data-backdrop="static"
                                             data-keyboard="false" tabindex="-1" aria-labelledby="modalHojasLabel"
                                             aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary">
                                                        <h5 class="modal-title" id="modalHojasLabel">Escoja el
                                                            equipamiento</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table id="hoja_tecnicasOTROS"
                                                               class="table table-sm table-hover  table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th scope="col" class="text-center">N°</th>
                                                                <th scope="col" class="text-center">Fecha
                                                                    Registro</th>
                                                                <th scope="col" class="text-center">Nombre del
                                                                    Equipamiento</th>
                                                                <th scope="col" class="text-center">Marca</th>
                                                                <th scope="col" class="text-center">Recinto</th>
                                                                <th scope="col" class="text-center">Servicio
                                                                </th>
                                                                <th scope="col" class="text-center">Seleccionar
                                                                </th>
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
                                                                    <td><?php echo mb_convert_case($fechaRegistro, MB_CASE_UPPER, 'UTF-8');  ?>
                                                                    </td>
                                                                    <td><?php echo mb_convert_case($nombreEquipo, MB_CASE_UPPER, 'UTF-8'); ?>
                                                                    </td>
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
                                                                        <button type="button"
                                                                                onclick="obtenerid5(<?php echo $id?>)"
                                                                                class="btn btn-danger">Añadir Este
                                                                            Equipo</button>
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
                                                                <th scope="col" class="text-center">Fecha
                                                                    Registro</th>
                                                                <th scope="col" class="text-center">Nombre del
                                                                    Equipamiento</th>
                                                                <th scope="col" class="text-center">Marca</th>
                                                                <th scope="col" class="text-center">Recinto</th>
                                                                <th scope="col" class="text-center">Servicio
                                                                </th>
                                                                <th scope="col" class="text-center">Seleccionar
                                                                </th>

                                                            </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Cerrar</button>
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
                                                           class="form-control" id="nomEquipoOTROS"
                                                           name="nomEquipoOTROS" value=""
                                                           placeholder="Nombre del equipo">
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
                                                           class="form-control" id="numInventarioOTROS"
                                                           name="numInventarioOTROS" value=""
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
                                                           class="form-control" id="marcaEquipoOTROS"
                                                           name="marcaEquipoOTROS" value=""
                                                           placeholder="Marca del equipo">
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
                                                           class="form-control" id="modeloEquipoOTROS"
                                                           name="modeloEquipoOTROS" value=""
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
                                                           class="form-control" id="numSerieOTROS" name="numSerieOTROS"
                                                           value="" placeholder="N° de serie del equipo">
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </fieldset>
                                <input type="hidden" id="idTGOTROS" name="idTGOTROS" value="<?php echo $idTG?>">
                                <input type="hidden" id="folioTGOTROS" name="folioTGOTROS"
                                       value="<?php echo $folioTG?>">
                                <input type="hidden" readonly="readonly" id="fechaActualOrdenOTROS"
                                       name="fechaActualOrdenOTROS" class="form-control"
                                       value="<?php echo date('d-m-Y'); ?>" />
                                <input type="hidden" id="idEquipoOTROS" name="idEquipoOTROS" value="">
                                <input type="hidden" id="idCategoriaOTROS" name="idCategoriaOTROS" value="">
                                <input type="hidden" id="idHojaOTROS" name="idHojaOTROS" value="">
                                <input type="hidden" readonly style="text-transform:capitalize" class="form-control"
                                       id="solicitanteOTROS" name="solicitanteOTROS"
                                       value="<?php echo $solicitanteTG?>">
                                <button type="submit" class="btn btn-block btn-primary" id="GuardarOtros"
                                        style="display: none;" name="GuardarOtros">Guardar</button>
                            </form>
                            <br>
                            <div class="form-row fieldGroupCopy" style="display: none;">
                                <div class="form-group col-md-4">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-list"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="materialUsado[]"
                                               name="materialUsado[]" placeholder="Materiales Usados">
                                    </div>
                                </div>

                                <div class="form-group col-md-3">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                        </div>
                                        <input type="text" style="text-transform:capitalize" class="form-control"
                                               id="unidad[]" name="unidad[]" placeholder="Unidad">
                                    </div>
                                </div>

                                <div class="form-group col-md-3">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                        </div>
                                        <input type="text" style="text-transform:capitalize" class="form-control"
                                               id="cantidad[]" name="cantidad[]" placeholder="Cantidad">
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <div class="input-group mb-3">
                                        <a href="javascript:void(0)" class="btn btn-block btn-danger remove"><span
                                                    class="glyphicon glyphicon glyphicon-remove"
                                                    aria-hidden="true"></span> <b>Eliminar</b> </a>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row fieldGroupCopyCA" style="display: none;">
                                <div class="form-group col-md-4">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-list"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="materialUsadoCA[]"
                                               name="materialUsadoCA[]" placeholder="Materiales Usados">
                                    </div>
                                </div>

                                <div class="form-group col-md-3">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                        </div>
                                        <input type="text" style="text-transform:capitalize" class="form-control"
                                               id="unidadCA[]" name="unidadCA[]" placeholder="Unidad">
                                    </div>
                                </div>

                                <div class="form-group col-md-3">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                        </div>
                                        <input type="text" style="text-transform:capitalize" class="form-control"
                                               id="cantidadCA[]" name="cantidadCA[]" placeholder="Cantidad">
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <div class="input-group mb-3">
                                        <a href="javascript:void(0)" class="btn btn-block btn-danger remove"><span
                                                    class="glyphicon glyphicon glyphicon-remove"
                                                    aria-hidden="true"></span> <b>Eliminar</b> </a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row fieldGroupCopyCCTV" style="display: none;">
                                <div class="form-group col-md-4">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-list"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="materialUsadoCCTV[]"
                                               name="materialUsadoCCTV[]" placeholder="Materiales Usados">
                                    </div>
                                </div>

                                <div class="form-group col-md-3">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                        </div>
                                        <input type="text" style="text-transform:capitalize" class="form-control"
                                               id="unidadCCTV[]" name="unidadCCTV[]" placeholder="Unidad">
                                    </div>
                                </div>

                                <div class="form-group col-md-3">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                        </div>
                                        <input type="text" style="text-transform:capitalize" class="form-control"
                                               id="cantidadCCTV[]" name="cantidadCCTV[]" placeholder="Cantidad">
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <div class="input-group mb-3">
                                        <a href="javascript:void(0)" class="btn btn-block btn-danger remove"><span
                                                    class="glyphicon glyphicon glyphicon-remove"
                                                    aria-hidden="true"></span> <b>Eliminar</b> </a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row fieldGroupCopyLLE" style="display: none;">
                                <div class="form-group col-md-4">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-list"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="materialUsadoLLE[]"
                                               name="materialUsadoLLE[]" placeholder="Materiales Usados">
                                    </div>
                                </div>

                                <div class="form-group col-md-3">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                        </div>
                                        <input type="text" style="text-transform:capitalize" class="form-control"
                                               id="unidadLLE[]" name="unidadLLE[]" placeholder="Unidad">
                                    </div>
                                </div>

                                <div class="form-group col-md-3">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                        </div>
                                        <input type="text" style="text-transform:capitalize" class="form-control"
                                               id="cantidadLLE[]" name="cantidadLLE[]" placeholder="Cantidad">
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <div class="input-group mb-3">
                                        <a href="javascript:void(0)" class="btn btn-block btn-danger remove"><span
                                                    class="glyphicon glyphicon glyphicon-remove"
                                                    aria-hidden="true"></span> <b>Eliminar</b> </a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row fieldGroupCopyOTROS" style="display: none;">
                                <div class="form-group col-md-4">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-list"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="materialUsadoOTROS[]"
                                               name="materialUsadoOTROS[]" placeholder="Materiales Usados">
                                    </div>
                                </div>

                                <div class="form-group col-md-3">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                        </div>
                                        <input type="text" style="text-transform:capitalize" class="form-control"
                                               id="unidadOTROS[]" name="unidadOTROS[]" placeholder="Unidad">
                                    </div>
                                </div>

                                <div class="form-group col-md-3">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                        </div>
                                        <input type="text" style="text-transform:capitalize" class="form-control"
                                               id="cantidadOTROS[]" name="cantidadOTROS[]" placeholder="Cantidad">
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <div class="input-group mb-3">
                                        <a href="javascript:void(0)" class="btn btn-block btn-danger remove"><span
                                                    class="glyphicon glyphicon glyphicon-remove"
                                                    aria-hidden="true"></span> <b>Eliminar</b> </a>
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
                                            var fieldHTML = '<div class="form-row fieldGroup">' + $(
                                                ".fieldGroupCopy").html() + '</div>';
                                            $('body').find('.fieldGroup:last').after(fieldHTML);
                                        } else {
                                            alert('Limite de Materiales Completados. Solo se permiten ' +
                                                maxGroup + ' Materiales.');
                                        }
                                    });
                                    //remove fields group
                                    $("body").on("click", ".remove", function() {
                                        $(this).parents(".fieldGroup").remove();
                                    });
                                });
                            </script>
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    //group add limit
                                    var maxGroup = "100";
                                    //add more fields group
                                    $(".addMoreOTROS").click(function() {
                                        if ($('body').find('.fieldGroupOTROS').length < maxGroup) {
                                            var fieldHTML = '<div class="form-row fieldGroupOTROS">' +
                                                $(
                                                    ".fieldGroupCopyOTROS").html() + '</div>';
                                            $('body').find('.fieldGroupOTROS:last').after(fieldHTML);
                                        } else {
                                            alert('Limite de Materiales Completados. Solo se permiten ' +
                                                maxGroup + ' Materiales.');
                                        }
                                    });
                                    //remove fields group
                                    $("body").on("click", ".remove", function() {
                                        $(this).parents(".fieldGroupOTROS").remove();
                                    });
                                });
                            </script>
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    //group add limit
                                    var maxGroup = "100";
                                    //add more fields group
                                    $(".addMoreCA").click(function() {
                                        if ($('body').find('.fieldGroupCA').length < maxGroup) {
                                            var fieldHTML = '<div class="form-row fieldGroupCA">' + $(
                                                ".fieldGroupCopyCA").html() + '</div>';
                                            $('body').find('.fieldGroupCA:last').after(fieldHTML);
                                        } else {
                                            alert('Limite de Materiales Completados. Solo se permiten ' +
                                                maxGroup + ' Materiales.');
                                        }
                                    });
                                    //remove fields group
                                    $("body").on("click", ".remove", function() {
                                        $(this).parents(".fieldGroupCA").remove();
                                    });
                                });
                            </script>
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    //group add limit
                                    var maxGroup = "100";
                                    //add more fields group
                                    $(".addMoreCCTV").click(function() {
                                        if ($('body').find('.fieldGroupCCTV').length < maxGroup) {
                                            var fieldHTML = '<div class="form-row fieldGroupCCTV">' + $(
                                                ".fieldGroupCopyCCTV").html() + '</div>';
                                            $('body').find('.fieldGroupCCTV:last').after(fieldHTML);
                                        } else {
                                            alert('Limite de Materiales Completados. Solo se permiten ' +
                                                maxGroup + ' Materiales.');
                                        }
                                    });
                                    //remove fields group
                                    $("body").on("click", ".remove", function() {
                                        $(this).parents(".fieldGroupCCTV").remove();
                                    });
                                });
                            </script>
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    //group add limit
                                    var maxGroup = "100";
                                    //add more fields group
                                    $(".addMoreLLE").click(function() {
                                        if ($('body').find('.fieldGroupLLE').length < maxGroup) {
                                            var fieldHTML = '<div class="form-row fieldGroupLLE">' + $(
                                                ".fieldGroupCopyLLE").html() + '</div>';
                                            $('body').find('.fieldGroupLLE:last').after(fieldHTML);
                                        } else {
                                            alert('Limite de Materiales Completados. Solo se permiten ' +
                                                maxGroup + ' Materiales.');
                                        }
                                    });
                                    //remove fields group
                                    $("body").on("click", ".remove", function() {
                                        $(this).parents(".fieldGroupLLE").remove();
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
    function visible(valor) {
        if (valor == 'Avance') {
            document.getElementById('fielsetAvance').style.display = "block";
            document.getElementById('fielsetTrabajoBMS').style.display = "none";
            document.getElementById('Guardar').style.display = "block";
            document.getElementById('bms').style.display = "none";
            document.getElementById('cctv').style.display = "none";
            document.getElementById('rondas').style.display = "none";
            document.getElementById('ca').style.display = "none";
            document.getElementById('lle').style.display = "none";
            document.getElementById('Guardar').style.display = "block";
            document.getElementById('finavance').style.display = "block";
            document.getElementById('otros').style.display = "none";
            document.getElementById('fielsetTrabajoOtros').style.display = "none";
            document.getElementById('fielsetTrabajoRondas').style.display = "none";
            document.getElementById('fielsetTrabajoCA').style.display = "none";
            document.getElementById('fielsetTrabajoCCTV').style.display = "none";
            document.getElementById('fielsetTrabajoLLE').style.display = "none";
            document.getElementById('GuardarBms').style.display = "none";
            document.getElementById('GuardarRondas').style.display = "none";
            document.getElementById('GuardarCA').style.display = "none";
            document.getElementById('GuardarCCTV').style.display = "none";
            document.getElementById('GuardarLLE').style.display = "none";
            document.getElementById('GuardarOtros').style.display = "none";
            hola = document.getElementById('avance');
            hola.value = 'Avance';

        } else if (valor == 'Termino Trabajo') {
            document.getElementById('fielsetAvance').style.display = "none";
            document.getElementById('Guardar').style.display = "block";
            document.getElementById('bms').style.display = "block";
            document.getElementById('cctv').style.display = "block";
            document.getElementById('rondas').style.display = "block";
            document.getElementById('ca').style.display = "block";
            document.getElementById('lle').style.display = "block";
            document.getElementById('Guardar').style.display = "none";
            document.getElementById('finavance').style.display = "block";
            document.getElementById('otros').style.display = "block";
            hola = document.getElementById('avance');
            hola.value = 'Termino Trabajo';

        } else {
            document.getElementById('fielsetAvance').style.display = "none";
            document.getElementById('fielsetTrabajoTerminado').style.display = "none";
        }
    }
</script>

<script>
    function obtenerid5(id) {
        console.log(id);
        idHojaOTROS = document.getElementById('idHojaOTROS');
        idHojaOTROS.value = id;
        $("#modalHojasOTROS").modal("hide");
        Swal.fire(
            'Exito!',
            'Equipo seleccionado correctamente!',
            'success'
        )
        botonAddHTOTROS = document.getElementById('botonAddHTOTROS');

        botonAddHTOTROS.classList.remove('btn-primary');
        botonAddHTOTROS.classList.add('btn-danger');
        botonAddHTOTROS.innerText = 'Equipo Seleccionado';

        var dataId = id;
        console.log(dataId);
        var elementos = {};
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

                $('#nomEquipoOTROS').val(elementos.nombreEquipo);
                $('#marcaEquipoOTROS').val(elementos.marca);
                $('#modeloEquipoOTROS').val(elementos.modelo);
                $('#numInventarioOTROS').val(elementos.numeroInventario);
                $('#numSerieOTROS').val(elementos.numeroSerie);
                $('#idCategoriaOTROS').val(elementos.idCategoria);
                $('#idEquipoOTROS').val(elementos.id);
                $('#idServicioOTROS').val(elementos.idServicio);
                $('#idDepaPerteneceOTROS').val(elementos.idDepaPertenece);
                $('#pisoOTROS').val(elementos.piso);

            }
        })
    }
</script>

<script>
    function obtenerid(id) {
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
    function obtenerid2(id) {
        console.log(id);
        idHojaCA = document.getElementById('idHojaCA');
        idHojaCA.value = id;
        $("#modalHojasCA").modal("hide");
        Swal.fire(
            'Exito!',
            'Equipo seleccionado correctamente!',
            'success'
        )
        botonAddHTCA = document.getElementById('botonAddHTCA');

        botonAddHTCA.classList.remove('btn-primary');
        botonAddHTCA.classList.add('btn-danger');
        botonAddHTCA.innerText = 'Equipo Seleccionado';

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

                $('#nomEquipoCA').val(elementos.nombreEquipo);
                $('#marcaEquipoCA').val(elementos.marca);
                $('#modeloEquipoCA').val(elementos.modelo);
                $('#numInventarioCA').val(elementos.numeroInventario);
                $('#numSerieCA').val(elementos.numeroSerie);
                $('#idCategoriaCA').val(elementos.idCategoria);
                $('#idEquipoCA').val(elementos.id);
                $('#idServicioCA').val(elementos.idServicio);
                $('#idDepaPerteneceCA').val(elementos.idDepaPertenece);
                $('#pisoCA').val(elementos.piso);

            }
        })

    }
</script>
<script>
    function obtenerid3(id) {
        console.log(id);
        idHojaCCTV = document.getElementById('idHojaCCTV');
        idHojaCCTV.value = id;
        $("#modalHojasCCTV").modal("hide");
        Swal.fire(
            'Exito!',
            'Equipo seleccionado correctamente!',
            'success'
        )
        botonAddHTCCTV = document.getElementById('botonAddHTCCTV');

        botonAddHTCCTV.classList.remove('btn-primary');
        botonAddHTCCTV.classList.add('btn-danger');
        botonAddHTCCTV.innerText = 'Equipo Seleccionado';

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

                $('#nomEquipoCCTV').val(elementos.nombreEquipo);
                $('#marcaEquipoCCTV').val(elementos.marca);
                $('#modeloEquipoCCTV').val(elementos.modelo);
                $('#numInventarioCCTV').val(elementos.numeroInventario);
                $('#numSerieCCTV').val(elementos.numeroSerie);
                $('#idCategoriaCCTV').val(elementos.idCategoria);
                $('#idEquipoCCTV').val(elementos.id);
                $('#idServicioCCTV').val(elementos.idServicio);
                $('#idDepaPerteneceCCTV').val(elementos.idDepaPertenece);
                $('#pisoCCTV').val(elementos.piso);

            }
        })

    }
</script>
<script>
    function obtenerid4(id) {
        console.log(id);
        idHojaLLE = document.getElementById('idHojaLLE');
        idHojaLLE.value = id;
        $("#modalHojasLLE").modal("hide");
        Swal.fire(
            'Exito!',
            'Equipo seleccionado correctamente!',
            'success'
        )
        botonAddHTLLE = document.getElementById('botonAddHTLLE');

        botonAddHTLLE.classList.remove('btn-primary');
        botonAddHTLLE.classList.add('btn-danger');
        botonAddHTLLE.innerText = 'Equipo Seleccionado';

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

                $('#nomEquipoLLE').val(elementos.nombreEquipo);
                $('#marcaEquipoLLE').val(elementos.marca);
                $('#modeloEquipoLLE').val(elementos.modelo);
                $('#numInventarioLLE').val(elementos.numeroInventario);
                $('#numSerieLLE').val(elementos.numeroSerie);
                $('#idCategoriaLLE').val(elementos.idCategoria);
                $('#idEquipoLLE').val(elementos.id);
                $('#idServicioLLE').val(elementos.idServicio);
                $('#idDepaPerteneceLLE').val(elementos.idDepaPertenece);
                $('#pisoLLE').val(elementos.piso);

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
    $(document).ready(function() {
        $('#hoja_tecnicasCA').DataTable({
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
    $(document).ready(function() {
        $('#hoja_tecnicasCCTV').DataTable({
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
    $(document).ready(function() {
        $('#hoja_tecnicasLLE').DataTable({
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
    $(document).ready(function() {
        $('#hoja_tecnicasOTROS').DataTable({
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