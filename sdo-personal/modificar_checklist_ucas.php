<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');


$fecha = date('d-m-Y H:i');

$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = trim($_SESSION['nomUsuario']);
$userUsuario = $_SESSION['userUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);
$idDepartamento = $_SESSION['idDepartamento'];

if (isset($_POST['idTG']) || isset($_SESSION['idTG'])) {

    if (isset($_POST['idTG'])) {
        $idTG = $_POST['idTG'];

        if ($idTG > 0) {
            $_SESSION['idTG'] = $idTG;
        } else {
            header('Location: visualizardetalles.php?errorDetalle=true');
        }
    } else if (isset($_SESSION['idTG'])) {
        $idTG = $_SESSION['idTG'];
        if ($idTG > 0) {
            $_SESSION['idTG'] = $idTG;
        } else {
            header('Location: visualizardetalles.php?errorDetalle=true');
        }
    }

    $idCheck = $_POST['idCheck'];

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
        // $tipoTecnico = $row['tipoTecnico'];
        $fechaTG2 = strtotime($fechaTG);
        $fechaTG3 = fechaEspañol2($fechaTG2);
        $fechaProgramacion = $row['fechaProgramacion'];
        $fechaProgram1 = strtotime($fechaProgramacion);
        $fechaProgram2 = fechaEspañol2($fechaProgram1);
        $ticketAsociado = $row['ticketAsociado'];
        $tipoTrabajo = $row['tipoTrabajo'];
        $detalleTurnoCamara = $row['detalleTurnoCamara'];
        $detalleTurnoLLE = $row['detalleTurnoLLE'];
        $detalleTurnoCA = $row['detalleTurnoCA'];
        $detalleTurnoRonda = $row['detalleTurnoRondas'];
        $detalleOtros = $row['detalleOtros'];
        $departamentos = $row['departamento'];
        $formularioRondas = $row['formularioRondas'];
    }

    $sqlDatosUcas = "SELECT * FROM `datos_checklist_ucas` WHERE idTG = $idTG AND id = $idCheck";
    $resDatosUcas = mysqli_query($conexion,$sqlDatosUcas);
    while($rowDatosUcas = $resDatosUcas->fetch_assoc()){

        $feckaCheck = $rowDatosUcas['fecha'];
        $uca = $rowDatosUcas['uca'];
        $obs = $rowDatosUcas['obs'];
        $limpiezaGeneralEquipo = $rowDatosUcas['limpiezaGeneralEquipo'];
        $verificarEstadoEquipo = $rowDatosUcas['verificarEstadoEquipo'];
        $limpiezaContactos = $rowDatosUcas['limpiezaContactos'];
        $verificacionEstadoPatchCord = $rowDatosUcas['verificacionEstadoPatchCord'];
        $verificacionVoltaje = $rowDatosUcas['verificacionVoltaje'];
        $verificacionIntegracion = $rowDatosUcas['verificacionIntegracion'];
        $verificacionFuncionamiento = $rowDatosUcas['verificacionFuncionamiento'];
        $cantidadPuertas = $rowDatosUcas['cantidadPuertas'];
        $folio = $rowDatosUcas['folio'];
        $idEquipamiento = $rowDatosUcas['idEquipamiento'];
    }

    $sqlEquipamiento = "SELECT * FROM `equipamiento_hbqp` WHERE id = $idEquipamiento";
    $resEquipamiento = mysqli_query($conexion, $sqlEquipamiento);
    while($rowEquipamiento = $resEquipamiento->fetch_assoc()){
        $estadoEquipo = $rowEquipamiento['estadoEquipo'];
    }

    $sqlFuentePoder = "SELECT * FROM `fuente_poder_uca` WHERE idChecList = $idCheck";
    $resFuentePoder = mysqli_query($conexion, $sqlFuentePoder);
    while($rowFuentePoder = $resFuentePoder->fetch_assoc()){
        $voltajeEntradaUCA = $rowFuentePoder['voltajeEntradaUCA'];
        $voltajeSalidaUCA = $rowFuentePoder['voltajeSalidaUCA'];
        $voltajeEntradaTablero = $rowFuentePoder['voltajeEntradaTablero'];
        $voltajeSalidaTablero = $rowFuentePoder['voltajeSalidaTablero'];
    }

}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Modificar Check List UCAS</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- AdminLTE css -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" />
    <!-- include summernote css-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- include summernote js-->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jssha@3.2.0/dist/sha256.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsSHA/3.2.0/sha.js"></script>

    <script>
        function marcarOtroInput(){
            var primerInput = document.querySelector('.show-mostrar');
            var enlaceBotones = document.getElementById('limpiezaGeneralEquipo');
            var enlaceImagenes = document.getElementById('verificarEstadoEquipo');
            var enlaceparametros = document.getElementById('limpiezaContactos');
            var enlacegraficas = document.getElementById('verificacionEstadoPatchCord');
            var enlacetendencias = document.getElementById('verificacionIntegracion');
            var ordenbloques = document.getElementById('verificacionFuncionamiento');
            var voltaje = document.getElementById('verificacionVoltaje');


            if (primerInput.checked) {
                enlaceBotones.checked = true;
                enlaceImagenes.checked = true;
                enlaceparametros.checked = true;
                enlacegraficas.checked = true;
                enlacetendencias.checked = true;
                ordenbloques.checked = true;
                voltaje.checked = true;
            }else{
                enlaceBotones.checked = false;
                enlaceImagenes.checked = false;
                enlaceparametros.checked = false;
                enlacegraficas.checked = false;
                enlacetendencias.checked = false;
                ordenbloques.checked = false;
                voltaje.checked = false;
            }
        }
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
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h4>Orden de Trabajo N° <?php echo $folioTG?> - Modificar Check List UCA: <?php echo $uca?></h4>
                            </div>
                            <div class="card-body">
                                <div class="col-lg-3 col-md-6 col-12 float-right" id="volverAtras2"
                                     name="volverAtras2">
                                    <!-- small box -->
                                    <a href="visualizardetalles.php" style="float: right;"
                                       class="btn bg-gradient-red float-right btn-md text-white"><i
                                                class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                </div>
                                <br>
                                <br>
                                <hr>
                                <form action="../sdo-funciones/modifcar-checklist-ucas.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="folio" value="<?php echo $folio?>">
                                    <input type="hidden" name="idCheck" value="<?php echo $idCheck?>">
                                    <input type="hidden" name="idEquipamiento" value="<?php echo $idEquipamiento?>">
                                    <fieldset class="border p-3">
                                        <div class="row">
                                            <div class="form-row">
                                                <div class="form-group col-mb-2">
                                                    <label style="float: left;">UCA actualmente seleccionada:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-door-open"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" readonly id="uca" name="uca" value="<?php echo $uca?>" oninput="this.value = this.value.toUpperCase()" required>
                                                    </div>
                                                </div>
                                                <div class="form-group col-mb-6">
                                                    <label>Cambiar UCA:</label>
                                                    <div class="form-row">
                                                        <select name="ucaID" id="ucaID" class="form-control select2" style="width: 100%;margin-left: 15px"  onchange="updateInput()">
                                                            <?php
                                                            $sqlServicio = "SELECT * FROM equipamiento_hbqp WHERE nombreEquipo LIKE ('%UCA-%') AND nombreEquipo NOT LIKE('%$uca%') ORDER BY nombreEquipo ASC";
                                                            $resServicio = mysqli_query($conexion,$sqlServicio);
                                                            ?>
                                                            <option value="" disabled selected>Seleccione una UCA</option>
                                                            <?php
                                                            while($rowServicio = $resServicio->fetch_assoc()){
                                                                $idEquipo = $rowServicio['id'];
                                                                $nombreEquipo = $rowServicio['nombreEquipo'];
                                                                ?>
                                                                <option value="<?php echo $idEquipo?>"><?php echo $nombreEquipo?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                        <input type="hidden" name="nombreEquipo" id="nombreEquipo" value="">
                                                        <script>
                                                            function updateInput() {
                                                                var selectElement = document.getElementById("ucaID");
                                                                var inputElement = document.getElementById("nombreEquipo");
                                                                var selectedOption = selectElement.options[selectElement.selectedIndex];

                                                                // Verifica si se ha seleccionado una opción válida
                                                                if (selectedOption.value !== "") {
                                                                    inputElement.value = selectedOption.textContent;
                                                                } else {
                                                                    // Si se selecciona la opción predeterminada, limpia el valor del input
                                                                    inputElement.value = "";
                                                                }
                                                            }
                                                        </script>
                                                    </div>
                                                </div>
                                                <label>Estado Actual:</label>
                                                <br>
                                                <input type="text" name="estado" value="<?php echo $estadoEquipo?>"  style="width: 10%; margin-left: 15px;" class="form-control" readonly>
                                                <label style="margin-left: 15px;">Cambiar el Estado:</label>
                                                <br>
                                                <br>
                                                <select id="estadoCambia" name="estadoCambia" class="form-control" style="width: 10%; margin-left: 15px;">
                                                    <option  disabled value="" selected>Seleccione....</option>
                                                    <option value="Operativo" >Operativo</option>
                                                    <option value="Fuera de Servicio">Fuera de Servicio</option>
                                                    <option value="OperativoObs">Operativo con Observaciones</option>
                                                    <option value="Mantenimiento">Mantenimiento</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm">
                                            <div style="display: flex; justify-content: space-between;">
                                                <table class="table table-hover table-bordered" style="text-align: center; width: 65%; font-size: 12px;">
                                                    <thead class="bg-gradient-info">
                                                    <th width="40%">
                                                        Items
                                                    </th>
                                                    <th width="40%">Estado</th>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td><b>Limpieza general del equipo y su entorno</b></td>
                                                        <?php
                                                        if($limpiezaGeneralEquipo == 1){
                                                            ?>
                                                            <td><input class="form-check-input mx-auto" checked type="checkbox" value="1" name="limpiezaGeneralEquipo" id="limpiezaGeneralEquipo"></td>
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <td><input class="form-check-input mx-auto" type="checkbox" value="1" name="limpiezaGeneralEquipo" id="limpiezaGeneralEquipo"></td>
                                                            <?php
                                                        }
                                                        ?>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Verificar el estados del equipo</b></td>
                                                        <?php
                                                        if($verificarEstadoEquipo == 1){
                                                            ?>
                                                            <td><input class="form-check-input mx-auto" type="checkbox" value="1" name="verificarEstadoEquipo" checked id="verificarEstadoEquipo"></td>
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <td><input class="form-check-input mx-auto" type="checkbox" value="1" name="verificarEstadoEquipo" id="verificarEstadoEquipo"></td>
                                                            <?php
                                                        }
                                                        ?>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Limpieza de contactos eléctricos y electrónicos</b></td>
                                                        <?php
                                                        if($limpiezaContactos == 1){
                                                            ?>
                                                            <td><input class="form-check-input mx-auto" checked type="checkbox" value="1" name="limpiezaContactos" id="limpiezaContactos"></td>
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <td><input class="form-check-input mx-auto" type="checkbox" value="1" name="limpiezaContactos" id="limpiezaContactos"></td>
                                                            <?php
                                                        }
                                                        ?>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Verificación del estado del patch Cord</b></td>
                                                        <?php
                                                        if($verificacionEstadoPatchCord == 1){
                                                            ?>
                                                            <td><input class="form-check-input mx-auto" type="checkbox" value="1" checked name="verificacionEstadoPatchCord" id="verificacionEstadoPatchCord"></td>
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <td><input class="form-check-input mx-auto" type="checkbox" value="1" name="verificacionEstadoPatchCord" id="verificacionEstadoPatchCord"></td>
                                                            <?php
                                                        }
                                                        ?>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Verificación de voltaje (DC/AC) en contactos, de acuerdo a ficha técnica</b></td>
                                                        <?php
                                                        if($verificacionVoltaje == 1){
                                                            ?>
                                                            <td><input class="form-check-input mx-auto" type="checkbox" value="1" checked name="verificacionVoltaje" id="verificacionVoltaje"></td>
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <td><input class="form-check-input mx-auto" type="checkbox" value="1" name="verificacionVoltaje" id="verificacionVoltaje"></td>
                                                            <?php
                                                        }
                                                        ?>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Verificación de integración de software de control de acceso</b></td>
                                                        <?php
                                                        if($verificacionIntegracion == 1){
                                                            ?>
                                                            <td><input class="form-check-input mx-auto" type="checkbox" checked value="1" name="verificacionIntegracion" id="verificacionIntegracion"></td>
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <td><input class="form-check-input mx-auto" type="checkbox" value="1" name="verificacionIntegracion" id="verificacionIntegracion"></td>
                                                            <?php
                                                        }
                                                        ?>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Verificación de funcionamiento</b></td>
                                                        <?php
                                                        if($verificacionFuncionamiento == 1){
                                                            ?>
                                                            <td><input class="form-check-input mx-auto" type="checkbox" checked value="1" name="verificacionFuncionamiento" id="verificacionFuncionamiento"></td>
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <td><input class="form-check-input mx-auto" type="checkbox" value="1" name="verificacionFuncionamiento" id="verificacionFuncionamiento"></td>
                                                            <?php
                                                        }
                                                        ?>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <table class="table table-hover table-bordered" style="text-align: center; width: 33%; font-size: 12px; margin-left: 10px;">
                                                    <thead  class="bg-gradient-info">
                                                    <th colspan="5">Dispositivos Terminales</th>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>
                                                            1
                                                        </td>
                                                        <td width="50%">
                                                            Se realiza limpieza a los equipos
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            2
                                                        </td>
                                                        <td width="50%">
                                                            Lectores de tarjeta se encuentran operativos
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            3
                                                        </td>
                                                        <td width="50%">
                                                            Lectores de huella se encuentran operativos
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            4
                                                        </td>
                                                        <td width="50%">
                                                            Luces indicadoras se encuentran operativas
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            5
                                                        </td>
                                                        <td width="50%">
                                                            Botones se encuentran operativos
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            6
                                                        </td>
                                                        <td width="50%">
                                                            Estado del Ferro Magnetico
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <button type="button" class="bg-primary btn" style="float: left" id="agregarFila"><i class="fas fa-plus"></i>&nbsp;&nbsp;Agregar Puerta</button>
                                        <br>
                                        <br>
                                        <div style="display: flex; gap: 20px;">
                                            <fieldset style="width: 70%" class="border p-3">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <input type="hidden" id="cantidadFilas" name="cantidadFilas" value="<?php echo $cantidadPuertas?>">
                                                        <?php
                                                        $archivosPorPuerta = []; // Nuevo arreglo

                                                        $sqlImagenesArchivos = "SELECT * FROM `archivos_uca_check_list` WHERE idCheckList = $idCheck";
                                                        $resImagenesArchivos = mysqli_query($conexion, $sqlImagenesArchivos);
                                                        while ($rowImagenesArchivos = $resImagenesArchivos->fetch_assoc()) {
                                                            $puerta = $rowImagenesArchivos['puerta']; // o el campo que indique la puerta relacionada
                                                            $archivosPorPuerta[$puerta] = $rowImagenesArchivos['id']; // guardas por nombre o número
                                                        }

                                                        for($i = 1; $i <= $cantidadPuertas; $i++){
                                                            $Puertas = 'Puerta '.$i;

                                                            $idArchivoImagen = isset($archivosPorPuerta[$Puertas]) ? $archivosPorPuerta[$Puertas] : '';

                                                            $sqlSelectPma = "SELECT * FROM `puertas_pma_checklist` WHERE puerta = '$Puertas' AND idCheck = $idCheck";
                                                            $resSelectPma = mysqli_query($conexion,$sqlSelectPma);
                                                            while($rowSelectPma = $resSelectPma->fetch_assoc()){
                                                                $pma = $rowSelectPma['pma'];
                                                            }
                                                            $sqlBiometrico = "SELECT * FROM `biometrico_checklist` WHERE puerta = '$Puertas' AND idCheck = $idCheck";
                                                            $resBiometrico = mysqli_query($conexion,$sqlBiometrico);
                                                            while($rowBiometrico = $resBiometrico->fetch_assoc()){
                                                                $limpiezaequiposbiometrico = $rowBiometrico['limpiezaequiposbiometrico'];
                                                                $lectortarjeta = $rowBiometrico['lectortarjeta'];
                                                                $lectorhuella = $rowBiometrico['lectorhuella'];
                                                                $lucesindicadorasbiometrico = $rowBiometrico['lucesindicadorasbiometrico'];
                                                            }
                                                            $sqlMagnetico = "SELECT * FROM `magnetico_checklist` WHERE puerta = '$Puertas' AND idCheck = $idCheck";
                                                            $resMagnetico = mysqli_query($conexion,$sqlMagnetico);
                                                            while($rowMagnetico = $resMagnetico->fetch_assoc()){
                                                                $limpiezaequiposmagnetico = $rowMagnetico['limpiezaequiposmagnetico'];
                                                                $lucesindicadorasmagnetico = $rowMagnetico['lucesindicadorasmagnetico'];
                                                                $estadoferromagnetico = $rowMagnetico['estadoferroMagnetico'];
                                                            }
                                                            $sqlBotonera = "SELECT * FROM botonera_checklist WHERE puerta = '$Puertas' AND idCheck = $idCheck";
                                                            $resBotonera = mysqli_query($conexion,$sqlBotonera);
                                                            while($rowBotonera = $resBotonera->fetch_assoc()){
                                                                $limpiezaequiposbotonera = $rowBotonera['limpiezaequiposbotonera'];
                                                                $botonesoperativos = $rowBotonera['botonesoperativos'];
                                                            }
                                                            ?>

                                                            <button style="float: right" type="button" class="btn btn-sm bg-danger eliminar" data-imagen="<?php echo $idArchivoImagen?>" data-puertas="<?php echo $cantidadPuertas?>" name="<?php echo $idCheck?>" id="<?php echo $Puertas?>">Eliminar Puerta y Foto <?php echo $i?></button>
                                                            <br>
                                                            <br>
                                                            <table class="table-bordered table"  style="text-align: center; font-size: 11px;width: 100%">
                                                                <thead>
                                                                <tr>
                                                                    <th rowspan="2" style="vertical-align: middle;">Puerta</th>
                                                                    <th>PMA:</th>
                                                                    <th colspan="5"><input required type="text" id="pmapuerta<?php echo $i?>" name="pmapuerta<?php echo $i?>" value="<?php echo $pma?>" class="form-control"></th>
                                                                </tr>
                                                                <tr>
                                                                    <th>1</th>
                                                                    <th>2</th>
                                                                    <th>3</th>
                                                                    <th>4</th>
                                                                    <th>5</th>
                                                                    <th>6</th>
                                                                </tr>
                                                                <input type="hidden" id="numeroPuerta<?php echo $i?>" name="numeroPuerta<?php echo $i?>" value="Puerta <?php echo $i?>">
                                                                </thead>
                                                                <tbody>
                                                                <tr>
                                                                    <td>Biometrico</td>
                                                                    <td style="font-family:DejaVu Sans">
                                                                        <?php
                                                                        if($limpiezaequiposbiometrico == 1){
                                                                            ?>
                                                                            <input type="checkbox" value="1" checked name="limpiezaequiposbiometrico<?php echo $i?>">
                                                                            <?php
                                                                        }else{
                                                                            ?>
                                                                            <input type="checkbox" value="1" name="limpiezaequiposbiometrico<?php echo $i?>">
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td style="font-family:DejaVu Sans">
                                                                        <?php
                                                                        if($lectortarjeta == 1){
                                                                            ?>
                                                                            <input type="checkbox" value="1" name="lectortarjeta<?php echo $i?>" checked>
                                                                            <?php
                                                                        }else{
                                                                            ?>
                                                                            <input type="checkbox" value="1" name="lectortarjeta<?php echo $i?>">
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td style="font-family:DejaVu Sans">
                                                                        <?php
                                                                        if($lectorhuella == 1){
                                                                            ?>
                                                                            <input type="checkbox" value="1" name="lectorhuella<?php echo $i?>" checked>
                                                                            <?php
                                                                        }else{
                                                                            ?>
                                                                            <input type="checkbox" value="1" name="lectorhuella<?php echo $i?>">
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td style="font-family:DejaVu Sans">
                                                                        <?php
                                                                        if($lucesindicadorasbiometrico == 1){
                                                                            ?>
                                                                            <input type="checkbox" value="1" name="lucesindicadorasbiometrico<?php echo $i?>" checked>
                                                                            <?php
                                                                        }else{
                                                                            ?>
                                                                            <input type="checkbox" value="1" name="lucesindicadorasbiometrico<?php echo $i?>">
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td>No Aplica</td>
                                                                    <td>No Aplica</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Magnetico</td>
                                                                    <td style="font-family:DejaVu Sans">
                                                                        <?php
                                                                        if($limpiezaequiposmagnetico == 1){
                                                                            ?>
                                                                            <input type="checkbox" value="1" name="limpiezaequiposmagnetico<?php echo $i?>" checked>
                                                                            <?php
                                                                        }else{
                                                                            ?>
                                                                            <input type="checkbox" value="1" name="limpiezaequiposmagnetico<?php echo $i?>">
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td>No Aplica</td>
                                                                    <td>No Aplica</td>
                                                                    <td style="font-family:DejaVu Sans">
                                                                        <?php
                                                                        if($lucesindicadorasmagnetico == 1){
                                                                            ?>
                                                                            <input type="checkbox" value="1" name="lucesindicadorasmagnetico<?php echo $i?>" checked>
                                                                            <?php
                                                                        }else{
                                                                            ?>
                                                                            <input type="checkbox" value="1" name="lucesindicadorasmagnetico<?php echo $i?>">
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td>No Aplica</td>
                                                                    <td style="font-family:DejaVu Sans">
                                                                        <?php
                                                                        if($estadoferromagnetico == 1){
                                                                            ?>
                                                                            <input type="checkbox" value="1" name="estadoferromagnetico<?php echo $i?>" checked>
                                                                            <?php
                                                                        }else{
                                                                            ?>
                                                                            <input type="checkbox" value="1" name="estadoferromagnetico<?php echo $i?>">
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Botonera</td>
                                                                    <td style="font-family:DejaVu Sans">
                                                                        <?php
                                                                        if($limpiezaequiposbotonera == 1){
                                                                            ?>
                                                                            <input type="checkbox" value="1" name="limpiezaequiposbotonera<?php echo $i?>" checked>
                                                                            <?php
                                                                        }else{
                                                                            ?>
                                                                            <input type="checkbox" value="1" name="limpiezaequiposbotonera<?php echo $i?>">
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td>No Aplica</td>
                                                                    <td>No Aplica</td>
                                                                    <td>No Aplica</td>
                                                                    <td style="font-family:DejaVu Sans">
                                                                        <?php
                                                                        if($botonesoperativos == 1){
                                                                            ?>
                                                                            <input type="checkbox" value="1" name="botonesoperativos<?php echo $i?>" checked>
                                                                            <?php
                                                                        }else{
                                                                            ?>
                                                                            <input type="checkbox" value="1" name="botonesoperativos<?php echo $i?>">
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td>No Aplica</td>

                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                            <?php
                                                        }
                                                        ?>
                                                        <div id="contenedorTablas">

                                                        </div>

                                                    </div>
                                                </div>
                                            </fieldset>
                                            <fieldset class="border p-3" style="width: 30%">
                                                <?php
                                                $sqlImagenes = "SELECT * FROM `archivos_uca_check_list` WHERE idCheckList = $idCheck";
                                                $resImagenes = mysqli_query($conexion,$sqlImagenes);
                                                while($rowImagenes = $resImagenes->fetch_assoc()){
                                                    $rutaArchivo = $rowImagenes['rutaArchivo'];
                                                    $pmaArchivo = $rowImagenes['pmaPuerta'];
                                                    $idArchivo = $rowImagenes['id'];
                                                    ?>
                                                    <table class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <td>Imagen Puerta <?php echo '('.$pmaArchivo.')'?></td>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td style="text-align: center;">
                                                                <input type="hidden" name="idArchivo[]" id="idArchivo" value="<?php echo $idArchivo?>">
                                                                <img src="<?php echo $rutaArchivo ?>" alt="imagen" width="250px">
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                    <?php
                                                }
                                                ?>
                                                <br>
                                                <div id="contenedorImagenes">

                                                </div>

                                            </fieldset>
                                        </div>



                                        <fieldset class="border p-3">
                                            <div style="display: flex; gap: 20px; justify-content: center;">
                                                <table style="width: 50%; text-align: center;" class="table table-bordered">
                                                    <thead>
                                                    <tr class="bg-secondary">
                                                        <th colspan="2">Fuentes de Poder (UCA)</th>
                                                    </tr>
                                                    <tr>
                                                        <th>Voltaje de Entrada</th>
                                                        <th>Voltaje de Salida</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td><input type="text" value="<?php echo $voltajeEntradaUCA?>" class="form-control" id="voltajeEntrada" name="voltajeEntrada"></td>
                                                        <td><input type="text" value="<?php echo $voltajeSalidaUCA?>" class="form-control" id="voltajeSalida" name="voltajeSalida"></td>
                                                    </tr>
                                                    </tbody>
                                                </table>

                                                <table style="width: 50%; text-align: center;" class="table table-bordered">
                                                    <thead>
                                                    <tr class="bg-secondary">
                                                        <th colspan="2">Fuentes de Poder (Tablero de Puertas)</th>
                                                    </tr>
                                                    <tr>
                                                        <th>Voltaje de Entrada</th>
                                                        <th>Voltaje de Salida</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td><input type="text" value="<?php echo $voltajeEntradaTablero?>" class="form-control" id="voltajeEntradaTablero" name="voltajeEntradaTablero"></td>
                                                        <td><input type="text" value="<?php echo $voltajeSalidaTablero?>" class="form-control" id="voltajeSalidaTablero" name="voltajeSalidaTablero"></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </fieldset>
                                        <br>
                                        <fieldset class="border p-3">
                                            <table style="text-align: center;width: 100%" class="table table-bordered">
                                                <thead>
                                                <tr class="bg-secondary">
                                                    <th colspan="6">Bateria (UCA)</th>
                                                </tr>
                                                <tr>
                                                    <th>Estado de la Bateria</th>
                                                    <th>SOH</th>
                                                    <th>SOC</th>
                                                    <th>A</th>
                                                    <th>V</th>
                                                    <th>R</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $sqlBateriasUca = "SELECT * FROM `baterias_ucas` WHERE idCheckList = $idCheck";
                                                $resBateriasUca = mysqli_query($conexion,$sqlBateriasUca);
                                                while($rowBateriasUca = $resBateriasUca->fetch_assoc()){
                                                    $estadoBateriaUca = $rowBateriasUca['estadoBateriaUCA'];
                                                    $SOH = $rowBateriasUca['SOH'];
                                                    $SOC = $rowBateriasUca['SOC'];
                                                    $A = $rowBateriasUca['A'];
                                                    $V = $rowBateriasUca['V'];
                                                    $R = $rowBateriasUca['R'];
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <?php
                                                                if($estadoBateriaUca == 'goodcondition'){
                                                                    ?>
                                                                        <select class="form-control" STYLE="text-align: center" name="estadoBateriaUCA">
                                                                            <option  disabled value="">Seleccione</option>
                                                                            <option value="goodcondition" selected>Good Condition (Color Verde)</option>
                                                                            <option value="replace">Replace (Color Rojo)</option>
                                                                            <option value="chargebattery">Charge the battery (Color Amarillo)</option>
                                                                            <option value="batterybadcondition">Battery Bad Condition (Sin Color)</option>
                                                                        </select>
                                                                    <?php
                                                                }else if($estadoBateriaUca == 'replace'){
                                                                    ?>
                                                                    <select class="form-control" STYLE="text-align: center" name="estadoBateriaUCA">
                                                                        <option  disabled value="">Seleccione</option>
                                                                        <option value="goodcondition" >Good Condition (Color Verde)</option>
                                                                        <option value="replace" selected>Replace (Color Rojo)</option>
                                                                        <option value="chargebattery">Charge the battery (Color Amarillo)</option>
                                                                        <option value="batterybadcondition">Battery Bad Condition (Sin Color)</option>
                                                                    </select>
                                                                    <?php
                                                                }else if($estadoBateriaUca == 'chargebattery'){
                                                                    ?>
                                                                    <select class="form-control" STYLE="text-align: center" name="estadoBateriaUCA">
                                                                        <option  disabled value="">Seleccione</option>
                                                                        <option value="goodcondition" >Good Condition (Color Verde)</option>
                                                                        <option value="replace">Replace (Color Rojo)</option>
                                                                        <option value="chargebattery" selected>Charge the battery (Color Amarillo)</option>
                                                                        <option value="batterybadcondition">Battery Bad Condition (Sin Color)</option>
                                                                    </select>
                                                                    <?php
                                                                }else if($estadoBateriaUca == 'batterybadcondition'){
                                                                    ?>
                                                                    <select class="form-control" STYLE="text-align: center" name="estadoBateriaUCA">
                                                                        <option  disabled value="">Seleccione</option>
                                                                        <option value="goodcondition" >Good Condition (Color Verde)</option>
                                                                        <option value="replace">Replace (Color Rojo)</option>
                                                                        <option value="chargebattery" >Charge the battery (Color Amarillo)</option>
                                                                        <option value="batterybadcondition" selected>Battery Bad Condition (Sin Color)</option>
                                                                    </select>
                                                                    <?php
                                                                }else{
                                                                    ?>
                                                                    <select class="form-control" STYLE="text-align: center" name="estadoBateriaUCA">
                                                                        <option  disabled value="" selected>Seleccione</option>
                                                                        <option value="goodcondition" >Good Condition (Color Verde)</option>
                                                                        <option value="replace">Replace (Color Rojo)</option>
                                                                        <option value="chargebattery" >Charge the battery (Color Amarillo)</option>
                                                                        <option value="batterybadcondition" >Battery Bad Condition (Sin Color)</option>
                                                                    </select>
                                                                    <?php
                                                                }
                                                            ?>

                                                        </td>
                                                        <td><input type="text" value="<?php echo $SOH?>" class="form-control" id="SOH" name="SOH"></td>
                                                        <td><input type="text" value="<?php echo $SOC?>" class="form-control" id="SOC" name="SOC"></td>
                                                        <td><input type="text" value="<?php echo $A?>" class="form-control" id="A" name="A"></td>
                                                        <td><input type="text" value="<?php echo $V?>" class="form-control" id="V" name="V"></td>
                                                        <td><input type="text" value="<?php echo $R?>" class="form-control" id="R" name="R"></td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>

                                                </tbody>
                                            </table>
                                            <br>
                                            <table style="text-align: center" width="50%" class="table-bordered table">
                                                <thead>
                                                <tr class="bg-secondary">
                                                    <th colspan="6">Bateria (Tablero de Puertas)</th>
                                                </tr>
                                                <tr>
                                                    <th>Estado de la Bateria</th>
                                                    <th>SOH</th>
                                                    <th>SOC</th>
                                                    <th>A</th>
                                                    <th>V</th>
                                                    <th>R</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $sqlBateriasTablero = "SELECT * FROM `baterias_tableros` WHERE idCheckList = $idCheck";
                                                $resBateriasTablero = mysqli_query($conexion,$sqlBateriasTablero);
                                                $contar = 1;
                                                while($rowBateriasTablero = $resBateriasTablero->fetch_assoc()){
                                                    $estadoBateriaTablero = $rowBateriasTablero['estadoBateriaTablero'];
                                                    $SOHTablero = $rowBateriasTablero['SOH'];
                                                    $SOCTablero = $rowBateriasTablero['SOC'];
                                                    $ATablero = $rowBateriasTablero['A'];
                                                    $VTablero = $rowBateriasTablero['V'];
                                                    $RTablero = $rowBateriasTablero['R'];
                                                    $idTablero = $rowBateriasTablero['id'];
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <?php
                                                            if($estadoBateriaUca == 'goodcondition'){
                                                                ?>
                                                                <select class="form-control" STYLE="text-align: center" name="estadoBateriaTablero<?php echo $contar?>">
                                                                    <option  disabled value="">Seleccione</option>
                                                                    <option value="goodcondition" selected>Good Condition (Color Verde)</option>
                                                                    <option value="replace">Replace (Color Rojo)</option>
                                                                    <option value="chargebattery">Charge the battery (Color Amarillo)</option>
                                                                    <option value="batterybadcondition">Battery Bad Condition (Sin Color)</option>
                                                                </select>
                                                                <?php
                                                            }else if($estadoBateriaUca == 'replace'){
                                                                ?>
                                                                <select class="form-control" STYLE="text-align: center" name="estadoBateriaTablero<?php echo $contar?>">
                                                                    <option  disabled value="">Seleccione</option>
                                                                    <option value="goodcondition" >Good Condition (Color Verde)</option>
                                                                    <option value="replace" selected>Replace (Color Rojo)</option>
                                                                    <option value="chargebattery">Charge the battery (Color Amarillo)</option>
                                                                    <option value="batterybadcondition">Battery Bad Condition (Sin Color)</option>
                                                                </select>
                                                                <?php
                                                            }else if($estadoBateriaUca == 'chargebattery'){
                                                                ?>
                                                                <select class="form-control" STYLE="text-align: center" name="estadoBateriaTablero<?php echo $contar?>">
                                                                    <option  disabled value="">Seleccione</option>
                                                                    <option value="goodcondition" >Good Condition (Color Verde)</option>
                                                                    <option value="replace">Replace (Color Rojo)</option>
                                                                    <option value="chargebattery" selected>Charge the battery (Color Amarillo)</option>
                                                                    <option value="batterybadcondition">Battery Bad Condition (Sin Color)</option>
                                                                </select>
                                                                <?php
                                                            }else if($estadoBateriaUca == 'batterybadcondition'){
                                                                ?>
                                                                <select class="form-control" STYLE="text-align: center" name="estadoBateriaTablero<?php echo $contar?>">
                                                                    <option  disabled value="">Seleccione</option>
                                                                    <option value="goodcondition" >Good Condition (Color Verde)</option>
                                                                    <option value="replace">Replace (Color Rojo)</option>
                                                                    <option value="chargebattery" >Charge the battery (Color Amarillo)</option>
                                                                    <option value="batterybadcondition" selected>Battery Bad Condition (Sin Color)</option>
                                                                </select>
                                                                <?php
                                                            }else{
                                                                ?>
                                                                <select class="form-control" STYLE="text-align: center" name="estadoBateriaTablero<?php echo $contar?>">
                                                                    <option  disabled value="" selected>Seleccione</option>
                                                                    <option value="goodcondition" >Good Condition (Color Verde)</option>
                                                                    <option value="replace">Replace (Color Rojo)</option>
                                                                    <option value="chargebattery" >Charge the battery (Color Amarillo)</option>
                                                                    <option value="batterybadcondition" >Battery Bad Condition (Sin Color)</option>
                                                                </select>
                                                                <?php
                                                            }
                                                            ?>

                                                        </td>

                                                        <td>
                                                            <input type="text" value="<?php echo $SOHTablero ?>" class="form-control" id="SOHTablero<?php echo $contar?>" name="SOHTablero<?php echo $contar?>">
                                                            <input type="hidden" name="idBateriasTablero<?php echo $contar?>" id="idBateriasTablero<?php echo $contar?>" value="<?php echo $idTablero?>">
                                                        </td>
                                                        <td><input type="text" value="<?php echo $SOCTablero ?>" class="form-control" id="SOCTablero<?php echo $contar?>" name="SOCTablero<?php echo $contar?>"></td>
                                                        <td><input type="text" value="<?php echo $ATablero?>" class="form-control" id="ATablero<?php echo $contar?>" name="ATablero<?php echo $contar?>"></td>
                                                        <td><input type="text" value="<?php echo $VTablero?>" class="form-control" id="VTablero<?php echo $contar?>" name="VTablero<?php echo $contar?>"></td>
                                                        <td><input type="text" value="<?php echo $RTablero?>" class="form-control" id="RTablero<?php echo $contar?>" name="RTablero<?php echo $contar?>"></td>
                                                    </tr>
                                                    <?php
                                                   $contar++;
                                                }
                                                ?>
                                                </tbody>
                                            </table>
                                        </fieldset>
                                        <br>
                                        <fieldset class="border p-3">
                                            <legend class="w-100">Observaciones</legend>
                                            <textarea rows="10" name="obs" style="resize: none;width: 100%"><?php echo $obs?></textarea>
                                        </fieldset>
                                    </fieldset>
                                    <br>
                                    <button type="submit" class="btn btn-success btn-block">Modificar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
</body>
<script>
    const cantidadFilasInput = document.getElementById('cantidadFilas');
    const cantidadFilasInputValor = document.getElementById('cantidadFilas').value;

    const btnAgregarFila = document.getElementById('agregarFila');
    const contenedorTablas = document.getElementById('contenedorTablas');
    let correlativo = cantidadFilasInputValor; // Iniciar el correlativo en 1
    let contadorTablas = cantidadFilasInputValor;
    console.log(correlativo);
    btnAgregarFila.addEventListener('click', () => {
        const nuevaTabla = document.createElement('table');
        nuevaTabla.className = 'table table-hover table-bordered';
        nuevaTabla.style.textAlign = 'center';
        nuevaTabla.style.marginBottom = '20px';

        correlativo++;
        nuevaTabla.innerHTML = `
        <thead>
            <tr>
                <th rowspan="2" style="vertical-align: middle;">Puerta</th>
                <th>PMA:</th>
                <th colspan="6"><input type="text" id="pmapuerta${correlativo}" name="pmapuerta${correlativo}" class="form-control"></th>
            </tr>
            <tr>
                <th>1</th>
                <th>2</th>
                <th>3</th>
                <th>4</th>
                <th>5</th>
                <th>6</th>
            </tr>
            <input type="hidden" id="numeroPuerta${correlativo}" name="numeroPuerta${correlativo}" value="Puerta ${correlativo}">
        </thead>
        <tbody>
            <tr>
                <td>Biometrico</td>
                <td><input type="checkbox" value="1" name="limpiezaequiposbiometrico${correlativo}"></td>
                <td><input type="checkbox" value="1" name="lectortarjeta${correlativo}"></td>
                <td><input type="checkbox" value="1" name="lectorhuella${correlativo}"></td>
                <td><input type="checkbox" value="1" name="lucesindicadorasbiometrico${correlativo}"></td>
                <td>No Aplica</td>
                <td>No Aplica</td>
            </tr>
            <tr>
                <td>Magnetico</td>
                <td><input type="checkbox" value="1" name="limpiezaequiposmagnetico${correlativo}"></td>
                <td>No Aplica</td>
                <td>No Aplica</td>
                <td><input type="checkbox" value="1" name="lucesindicadorasmagnetico${correlativo}"></td>
                <td>No Aplica</td>
                <td><input type="checkbox" value="1" name="estadoferromagnetico${correlativo}"></td>
            </tr>
            <tr>
                <td>Botonera</td>
                <td><input type="checkbox" value="1" name="limpiezaequiposbotonera${correlativo}"></td>
                <td>No Aplica</td>
                <td>No Aplica</td>
                <td>No Aplica</td>
                <td><input type="checkbox" value="1" name="botonesoperativos${correlativo}"></td>
                <td>No Aplica</td>
            </tr>
        </tbody>
    `;

        // Crear tabla para imagen y valor
        const tablaImagen = document.createElement('table');
        tablaImagen.className = 'table table-bordered';
        tablaImagen.innerHTML = `
        <thead class="bg-secondary" style="text-align: center">
            <tr>
                <th colspan="2">Puerta (Subir Imagen)</th>
            </tr>
            <tr>
                <th>Subir Imagen</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <input type="hidden" id="hashImagen${correlativo}" name="hashImagen${correlativo}">
                <td>
                    <input type="file" required class="form-control" name="imagenes${correlativo}">
                </td>
                <td><input type="text" required class="form-control" name="valorTermo${correlativo}" id="valorTermo${correlativo}"></td>
            </tr>
        </tbody>
    `;

        // Crear botón para eliminar tabla
        const botonEliminar = document.createElement('button');
        botonEliminar.textContent = 'Eliminar';
        botonEliminar.className = 'btn btn-danger';
        botonEliminar.style.marginTop = '10px';

        // Evento eliminar tabla y su imagen asociada
        botonEliminar.addEventListener('click', () => {
            nuevaTabla.remove();
            tablaImagen.remove();
            contadorTablas--;
            cantidadFilasInput.value = contadorTablas;
            correlativo--;
            console.log('Número total de tablas:', contadorTablas);
        });

        nuevaTabla.appendChild(botonEliminar);

        // Agregar ambas tablas
        contenedorTablas.appendChild(nuevaTabla);
        document.getElementById('contenedorImagenes').appendChild(tablaImagen);

        contadorTablas++;
        cantidadFilasInput.value = contadorTablas;
        console.log('Número total de tablas:', contadorTablas);
        // === Asignar evento de hash a input dinámico ===
        const inputFile = tablaImagen.querySelector(`input[type="file"]`);
        const hashInput = tablaImagen.querySelector(`#hashImagen${correlativo}`);

        inputFile.addEventListener('change', async function () {
            hashInput.value = ''; // Limpiar campo oculto

            const file = this.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function () {
                const shaObj = new jsSHA("SHA-256", "ARRAYBUFFER");
                shaObj.update(reader.result);
                const hashHex = shaObj.getHash("HEX");
                hashInput.value = hashHex;
                console.log(`Archivo: ${file.name}`);
                console.log(`Hash SHA-256: ${hashHex}`);
            };
            reader.readAsArrayBuffer(file);
        });

    });


</script>
<script>
    $(document).ready(
        $('.eliminar').click(function(e) {
            e.preventDefault();
            let puertas = $(this).attr('data-puertas');
            let id = $(this).attr('id');
            let name = $(this).attr('name');
            let imagen = $(this).attr('data-imagen');
            var dataString = 'id=' + id;
            var dataString2 = 'name=' + name;
            var dataStringPuertas = 'datapuertas=' + puertas;
            var dataImagen = 'dataimagen=' + imagen;
            var data = dataString + '&' + dataString2 + '&' + dataStringPuertas + '&' + dataImagen;
            console.log(data)
            Swal.fire({
                icon: 'question',
                title: '¿Eliminar esta puerta?',
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
                        url: '../sdo-funciones/eliminar-puertas-ucas.php',
                        type: 'POST',
                        data: data,
                        success: function(response) {
                            console.log(response);
                            Swal.fire({
                                icon: 'success',
                                title: 'Puerta eliminada',
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
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="../plugins/select2/js/select2.full.min.js"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="../plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<!-- InputMask -->
<script src="../plugins/moment/moment.min.js"></script>
<script src="../plugins/inputmask/jquery.inputmask.min.js"></script>
<!-- date-range-picker -->
<script src="../plugins/daterangepicker/daterangepicker.js"></script>
<!-- bootstrap color picker -->
<script src="../plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Bootstrap Switch -->
<script src="../plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- BS-Stepper -->
<script src="../plugins/bs-stepper/js/bs-stepper.min.js"></script>
<!-- dropzonejs -->
<script src="../plugins/dropzone/min/dropzone.min.js"></script>
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
<script src="js/dark-mode.js"></script>
</html>

