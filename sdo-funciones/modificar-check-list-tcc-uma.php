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
    'E12_1', 'E12_2', 'E12_3','id','idSensorTemp','idSensorPresion','idSensorHumedad','idVariadorFrecuencia','idSpdESTADO','idValvulasMoto','idSensorPresionDiferencial'
];

foreach ($variables as $var) {
    ${$var} = isset($_POST[$var]) && $_POST[$var] !== '' ? $_POST[$var] : 0;
}
$obs = $_POST['obs'];
$now = date('Y-m-d H:i:s');
$sql = "UPDATE `datos_tcc_uma` SET `tcc` = '$tcc', `controlador` = '$controlador', `uma` = '$uma', `frecuencia` = '$frecuencia', `obs` = '$obs', `limpiezaTablero` = '$limpiezaTablero', `verificacionVoltaje` = '$verificacionVoltaje', `limpiezaReapriete` = '$limpiezaReapriete', `listadoPuntos` = '$listadoPuntos', `rotuladoComponentes` = '$rotuladoComponentes', `verificacionIntegracion` = '$verificacionIntegracion', `chequeoRutina` = '$chequeoRutina' WHERE `id` = $id";
$res = mysqli_query($conexion,$sql);
if($res){
    $sqlSensorTemp = "UPDATE `sensor_temperatura` SET `sensorInyeccionTemp1` = $sensorInyeccionTemp1, `sensorInyeccionTemp2` = $sensorInyeccionTemp2, `sensorInyeccionTemp3` = $sensorInyeccionTemp3, `sensorRetornoTemp1` = $sensorRetornoTemp1, `sensorRetornoTemp2` = $sensorRetornoTemp2, `sensorRetornoTemp3` = $sensorRetornoTemp3 WHERE `id` = $idSensorTemp";
    $resSensorTemp = mysqli_query($conexion,$sqlSensorTemp);
    echo $sqlSensorTemp;
    echo '<br>';
    echo '<br>';

    //$resSensorTemp = mysqli_query($conexion, $sqlSensorTemp);
    $sqlSensorPresion = "UPDATE `sensor_presion` SET `sensorInyeccionPresion1` = $sensorInyeccionPresion1, `sensorInyeccionPresion2` = $sensorInyeccionPresion2, `sensorInyeccionPresion3` = $sensorInyeccionPresion3, `sensorRetornoPresion1` = $sensorRetornoPresion1, `sensorRetornoPresion2` = $sensorRetornoPresion2, `sensorRetornoPresion3` = $sensorRetornoPresion3 WHERE `id` = $idSensorPresion";
    $resSensorPresion = mysqli_query($conexion, $sqlSensorPresion);
    echo $sqlSensorPresion;
    echo '<br>';
    echo '<br>';

    $sqlSensorHumedad = "UPDATE `sensor_humedad` SET `sensorInyeccionHumedad1` = $sensorInyeccionHumedad1, `sensorInyeccionHumedad2` = $sensorInyeccionHumedad2, `sensorInyeccionHumedad3` = $sensorInyeccionHumedad3, `sensorRetornoHumedad1` = $sensorRetornoHumedad1, `sensorRetornoHumedad2` = $sensorRetornoHumedad2, `sensorRetornoHumedad3` = $sensorRetornoHumedad3 WHERE `id` = $idSensorHumedad";
    $resSensorHumedad = mysqli_query($conexion, $sqlSensorHumedad);
    echo $sqlSensorHumedad;
    echo '<br>';
    echo '<br>';

    $sqlVariadorFrecuencia = "UPDATE `variador_frecuencia` SET `variadorFrecuenciaInyeccion1` = $variadorFrecuenciaInyeccion1, `variadorFrecuenciaInyeccion2` = $variadorFrecuenciaInyeccion2, `variadorFrecuenciaInyeccion3` = $variadorFrecuenciaInyeccion3, `variadorFrecuenciaRetorno1` = $variadorFrecuenciaRetorno1, `variadorFrecuenciaRetorno2` = $variadorFrecuenciaRetorno2, `variadorFrecuenciaRetorno3` = $variadorFrecuenciaRetorno3 WHERE `id` = $idVariadorFrecuencia";
    $resVariadorFrecuencia = mysqli_query($conexion, $sqlVariadorFrecuencia);
    echo $sqlVariadorFrecuencia;
    echo '<br>';
    echo '<br>';

    $sqlSpdEstado = "UPDATE `spd_estado` SET `spdEstadoInyeccion1` = $spdEstadoInyeccion1, `spdEstadoInyeccion2` = $spdEstadoInyeccion2, `spdEstadoInyeccion3` = $spdEstadoInyeccion3, `spdEstadoRetorno1` = $spdEstadoRetorno1, `spdEstadoRetorno2` = $spdEstadoRetorno2, `spdEstadoRetorno3` = $spdEstadoRetorno3 WHERE `id` = $idSpdESTADO";
    $resSpdEstado = mysqli_query($conexion, $sqlSpdEstado);
    echo $sqlSpdEstado;
    echo '<br>';
    echo '<br>';

    $sqlValvulasMotorizadas = "UPDATE `valvulas_motorizadas` SET `valvulasMotorizadasInyeccion1` = $valvulasMotorizadasInyeccion1, `valvulasMotorizadasInyeccion2` = $valvulasMotorizadasInyeccion2, `valvulasMotorizadasInyeccion3` = $valvulasMotorizadasInyeccion3, `valvulasMotorizadasRetorno1` = $valvulasMotorizadasRetorno1, `valvulasMotorizadasRetorno2` = $valvulasMotorizadasRetorno2, `valvulasMotorizadasRetorno3` = $valvulasMotorizadasRetorno3 WHERE `id` = $idValvulasMoto";
    $resValvulasMotorizadas = mysqli_query($conexion, $sqlValvulasMotorizadas);
    echo $sqlValvulasMotorizadas;
    echo '<br>';
    echo '<br>';

    $sqlSensorPresionDiferencial = "UPDATE `sensor_presion_diferencial` SET `G4Inyeccion1` = $G4Inyeccion1, `G4Inyeccion2` = $G4Inyeccion2, `G4Inyeccion3` = $G4Inyeccion3, `G4Retorno1` = $G4Retorno1, `G4Retorno2` = $G4Retorno2, `G4Retorno3` = $G4Retorno3, `F7_1` = $F7_1, `F7_2` = $F7_2, `F7_3` = $F7_3, `F9_1` = $F9_1, `F9_2` = $F9_2, `F9_3` = $F9_3, `H13_1` = $H13_1, `H13_2` = $H13_2, `H13_3` = $H13_3, `E12_1` = $E12_1, `E12_2` = $E12_2, `E12_3` = $E12_3 WHERE `id` = $idSensorPresionDiferencial";
    $resSensorPresionDiferencial = mysqli_query($conexion, $sqlSensorPresionDiferencial);
    echo $sqlSensorPresionDiferencial;
    echo '<br>';
    echo '<br>';

    if($resSensorTemp && $resSensorPresion && $resSensorHumedad && $resVariadorFrecuencia && $resSpdEstado && $resValvulasMotorizadas && $resSensorPresionDiferencial){
        if($tipoUsuario != 'personal'){
            header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?exitoModificartccuma=true');
            exit();
        }else{
            header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?exitoModificartccuma=true');
            exit();
        }
    }else{
        if($tipoUsuario != 'personal'){
            header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?errorModificatccuma1=true');
            exit();
        }else{
            header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?errorModificatccuma1=true');
            exit();
        }
    }
}else{
    if($tipoUsuario != 'personal'){
        header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?errorModificatccuma2=true');
        exit();
    }else{
        header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?errorModificatccuma2=true');
        exit();
    }
}