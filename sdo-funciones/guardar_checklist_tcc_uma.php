<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];
$idDepartamento = $_SESSION['idDepartamento'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);

date_default_timezone_set('America/Santiago');

$rutaDireccion = "";

if ($rolUsuario2 == "personal") {
    $tipoUsuario = 'personal';
    $rutaDireccion = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $tipoUsuario = 'administrativo';
    $rutaDireccion = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $tipoUsuario = 'bodega pañol';
    $rutaDireccion = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $tipoUsuario = 'jefe tecnico';
    $rutaDireccion = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $tipoUsuario = 'administrador';
    $rutaDireccion = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $tipoUsuario = 'directiva';
    $rutaDireccion = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    exit();
}

$variables = [
    'idTG', 'tcc', 'controlador', 'uma', 'frecuencia',
    'limpiezaTablero', 'verificacionVoltaje', 'limpiezaReapriete',
    'listadoPuntos', 'rotuladoComponentes', 'verificacionIntegracion',
    'chequeoRutina', 'sensorInyeccionTemp1', 'sensorInyeccionTemp2',
    'sensorInyeccionTemp3', 'sensorRetornoTemp1', 'sensorRetornoTemp2',
    'sensorRetornoTemp3', 'sensorInyeccionPresion1', 'sensorInyeccionPresion2',
    'sensorInyeccionPresion3', 'sensorRetornoPresion1', 'sensorRetornoPresion2',
    'sensorRetornoPresion3', 'sensorInyeccionHumedad1', 'sensorInyeccionHumedad2',
    'sensorInyeccionHumedad3', 'sensorRetornoHumedad1', 'sensorRetornoHumedad2',
    'sensorRetornoHumedad3', 'variadorFrecuenciaInyeccion1', 'variadorFrecuenciaInyeccion2',
    'variadorFrecuenciaInyeccion3', 'variadorFrecuenciaRetorno1', 'variadorFrecuenciaRetorno2',
    'variadorFrecuenciaRetorno3', 'spdEstadoInyeccion1', 'spdEstadoInyeccion2',
    'spdEstadoInyeccion3', 'spdEstadoRetorno1', 'spdEstadoRetorno2',
    'spdEstadoRetorno3', 'valvulasMotorizadasInyeccion1', 'valvulasMotorizadasInyeccion2',
    'valvulasMotorizadasInyeccion3', 'valvulasMotorizadasRetorno1', 'valvulasMotorizadasRetorno2',
    'valvulasMotorizadasRetorno3', 'G4Inyeccion1', 'G4Inyeccion2', 'G4Inyeccion3',
    'G4Retorno1', 'G4Retorno2', 'G4Retorno3', 'F7_1', 'F7_2', 'F7_3',
    'F9_1', 'F9_2', 'F9_3', 'H13_1', 'H13_2', 'H13_3',
    'E12_1', 'E12_2', 'E12_3'
];

// Asignación de valores con verificación
foreach ($variables as $var) {
    ${$var} = isset($_POST[$var]) && $_POST[$var] !== '' ? $_POST[$var] : 0;
}
$obs = $_POST['obs'];
$now = date('Y-m-d H:i:s');

$sql = "INSERT INTO `datos_tcc_uma`(`id`, `fecha`, `folio`, `tcc`, `controlador`, `uma`, `frecuencia`,obs, `limpiezaTablero`, `verificacionVoltaje`, `limpiezaReapriete`, `listadoPuntos`, `rotuladoComponentes`, `verificacionIntegracion`, `chequeoRutina`, `idTG`) 
VALUES (NULL,'$now',NULL,'$tcc','$controlador','$uma','$frecuencia','$obs','$limpiezaTablero','$verificacionVoltaje','$limpiezaReapriete','$listadoPuntos','$rotuladoComponentes','$verificacionIntegracion','$chequeoRutina',$idTG)";
$res = mysqli_query($conexion,$sql);
if($res){
    $id = mysqli_insert_id($conexion);
    if ($id < 10) {
        $idRecepcion2 = "000" . $id;
    } else if ($idTG > 9 && $id < 100) {
        $idRecepcion2 = "00" . $id;
    } else if ($idTG > 99 && $id < 1000) {
        $idRecepcion2 = "0" . $id;
    } else if ($idTG > 999) {
        $idRecepcion2 = $id;
    } else {
        $idRecepcion2 = $id;
    }
    $folio = 'CHK-'.$idRecepcion2.'-TCC-UMA';

    $sqlUpdate = "UPDATE datos_tcc_uma SET folio = '$folio' WHERE id = $id";
    $resUpdate = mysqli_query($conexion,$sqlUpdate);


    $sqlSensorTemp = "INSERT INTO `sensor_temperatura`(`id`, `sensorInyeccionTemp1`, `sensorInyeccionTemp2`, `sensorInyeccionTemp3`, `sensorRetornoTemp1`, `sensorRetornoTemp2`, `sensorRetornoTemp3`, `idCheckList`, `idTG`) 
    VALUES (NULL,$sensorInyeccionTemp1,$sensorInyeccionTemp2,$sensorInyeccionTemp3,$sensorRetornoTemp1,$sensorRetornoTemp2,$sensorRetornoTemp3,$id,$idTG)";
    $resSensorTemp = mysqli_query($conexion,$sqlSensorTemp);

    $sqlSensorPresion = "INSERT INTO `sensor_presion`(`id`, `sensorInyeccionPresion1`, `sensorInyeccionPresion2`, `sensorInyeccionPresion3`, `sensorRetornoPresion1`, `sensorRetornoPresion2`, `sensorRetornoPresion3`, `idCheckList`, `idTG`) 
    VALUES (NULL,$sensorInyeccionPresion1,$sensorInyeccionPresion2,$sensorInyeccionPresion3,$sensorRetornoPresion1,$sensorRetornoPresion2,$sensorRetornoPresion3,$id,$idTG)";
    $resSensorPresion = mysqli_query($conexion,$sqlSensorPresion);

    $sqlSensorHumedad = "INSERT INTO `sensor_humedad`(`id`, `sensorInyeccionHumedad1`, `sensorInyeccionHumedad2`, `sensorInyeccionHumedad3`, `sensorRetornoHumedad1`, `sensorRetornoHumedad2`, `sensorRetornoHumedad3`, `idCheckList`, `idTG`) 
    VALUES (NULL,$sensorInyeccionHumedad1,$sensorInyeccionHumedad2,$sensorInyeccionHumedad3,$sensorRetornoHumedad1,$sensorRetornoHumedad2,$sensorRetornoHumedad3,$id,$idTG)";
    $resSensorHumedad = mysqli_query($conexion,$sqlSensorHumedad);

    $sqlVariadorFrecuencia = "INSERT INTO `variador_frecuencia`(`id`, `variadorFrecuenciaInyeccion1`, `variadorFrecuenciaInyeccion2`, `variadorFrecuenciaInyeccion3`, `variadorFrecuenciaRetorno1`, `variadorFrecuenciaRetorno2`, `variadorFrecuenciaRetorno3`, `idCheckList`, `idTG`) 
    VALUES (NULL,$variadorFrecuenciaInyeccion1,$variadorFrecuenciaInyeccion2,$variadorFrecuenciaInyeccion3,$variadorFrecuenciaRetorno1,$variadorFrecuenciaRetorno2,$variadorFrecuenciaRetorno3,$id,$idTG)";
    $resVariadorFrecuencia = mysqli_query($conexion,$sqlVariadorFrecuencia);

    $sqlSpdEstado = "INSERT INTO `spd_estado`(`id`, `spdEstadoInyeccion1`, `spdEstadoInyeccion2`, `spdEstadoInyeccion3`, `spdEstadoRetorno1`, `spdEstadoRetorno2`, `spdEstadoRetorno3`, `idCheckList`, `idTG`) 
    VALUES (NULL,$spdEstadoInyeccion1,$spdEstadoInyeccion2,$spdEstadoInyeccion3,$spdEstadoRetorno1,$spdEstadoRetorno2,$spdEstadoRetorno3,$id,$idTG)";
    $resSpdEstado = mysqli_query($conexion,$sqlSpdEstado);

    $sqlValvulasMotorizadas = "INSERT INTO `valvulas_motorizadas`(`id`, `valvulasMotorizadasInyeccion1`, `valvulasMotorizadasInyeccion2`, `valvulasMotorizadasInyeccion3`, `valvulasMotorizadasRetorno1`, `valvulasMotorizadasRetorno2`, `valvulasMotorizadasRetorno3`, `idCheckList`, `idTG`) 
    VALUES (NULL,$valvulasMotorizadasInyeccion1,$valvulasMotorizadasInyeccion2,$valvulasMotorizadasInyeccion3,$valvulasMotorizadasRetorno1,$valvulasMotorizadasRetorno2,$valvulasMotorizadasRetorno3,$id,$idTG)";
    $resValvulasMotorizadas = mysqli_query($conexion,$sqlValvulasMotorizadas);

    $sqlSensorPresionDiferencial = "INSERT INTO `sensor_presion_diferencial`(`id`, `G4Inyeccion1`, `G4Inyeccion2`, `G4Inyeccion3`, `G4Retorno1`, `G4Retorno2`, `G4Retorno3`, `F7_1`, `F7_2`, `F7_3`, `F9_1`, `F9_2`, `F9_3`, `H13_1`, `H13_2`, `H13_3`, `E12_1`, `E12_2`, `E12_3`, `idCheckList`, `idTG`) 
    VALUES (NULL,$G4Inyeccion1,$G4Inyeccion2,$G4Inyeccion3,$G4Retorno1,$G4Retorno2,$G4Retorno3,$F7_1,$F7_2,$F7_3,$F9_1,$F9_2,$F9_3,$H13_1,$H13_2,$H13_3,$E12_1,$E12_2,$E12_3,$id,$idTG)";
    $resSensorPresionDiferencial = mysqli_query($conexion,$sqlSensorPresionDiferencial);


    if($resUpdate && $resSensorTemp && $resSensorPresion && $resSensorHumedad && $resVariadorFrecuencia && $resSpdEstado && $resValvulasMotorizadas && $resSensorPresionDiferencial){
        if($tipoUsuario != 'personal'){
            header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?exitotccuma=true');
            exit();
        }else{
            header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?exitotccuma=true');
            exit();
        }
    }else{
        if($tipoUsuario != 'personal'){
            header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?errortccuma1=true');
            exit();
        }else{
            header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?errortccuma1=true');
            exit();
        }
    }
}else{
    if($tipoUsuario != 'personal'){
        header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?errortccumaGeneral=true');
        exit();
    }else{
        header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?errortccumaGeneral=true');
        exit();
    }
}