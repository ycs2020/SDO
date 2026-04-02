<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

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
}


if(isset($_POST['idTG'])){
    $folioTG = $_POST['folioTG'];
    $contar = $_POST['contarRondas'];
    $idTG = $_POST['idTG'];
    $horario = $_POST['horario'];
    $vdrl = $_POST['vdrl'];
    $ifi = $_POST['ifi'];
    $uci = $_POST['uci'];
    $uti = $_POST['uti'];
    $vdrlBMS = $_POST['vdrlBMS'];
    $ifiBMS = $_POST['ifiBMS'];
    $uciBMS = $_POST['uciBMS'];
    $utiBMS = $_POST['utiBMS'];
    $pisosub = $_POST['pisosub'];
    $piso3 = $_POST['piso3'];
    $piso7 = $_POST['piso7'];
    $pabellones = $_POST['pabellones'];
    $crd = $_POST['crd'];
    $urgencias = $_POST['urgencias'];
    $hospitalizados = $_POST['hospitalizados'];
    $torre1 = $_POST['torre1'];
    $torre2 = $_POST['torre2'];
    $torre3 = $_POST['torre3'];
    $torre4 = $_POST['torre4'];
    $chiller1 = $_POST['chiller1'];
    $chiller2 = $_POST['chiller2'];
    $chiller3 = $_POST['chiller3'];
    $chiller4 = $_POST['chiller4'];
    $bac1 = $_POST['bac1'];
    $bac2 = $_POST['bac2'];
    $bac3R = $_POST['bac3R'];
    $bac4 = $_POST['bac4'];
    $bac4R = $_POST['bac4R'];
    $barvf2 = $_POST['barvf2'];
    $baep1 = $_POST['baep1'];
    $baep2 = $_POST['baep2'];
    $baep3R = $_POST['baep3R'];
    $baep4 = $_POST['baep4'];
    $baep4R = $_POST['baep4R'];
    $baes1 = $_POST['baes1'];
    $baes1R = $_POST['baes1R'];
    $baes2 = $_POST['baes2'];
    $baes2R = $_POST['baes2R'];
    $baes2_1 = $_POST['baes2_1'];
    $baes3 = $_POST['baes3'];
    $baes3R = $_POST['baes3R'];
    $baes4 = $_POST['baes4'];
    $baes4R = $_POST['baes4R'];
    $caldera1 = $_POST['caldera1'];
    $caldera2 = $_POST['caldera2'];
    $caldera3 = $_POST['caldera3'];
    $caldera4 = $_POST['caldera4'];
    $caldera5 = $_POST['caldera5'];
    $bcap5 = $_POST['bcap5'];
    $bcap5R = $_POST['bcap5R'];
    $bcap1 = $_POST['bcap1'];
    $bcap2 = $_POST['bcap2'];
    $bcap3 = $_POST['bcap3'];
    $bcap4R = $_POST['bcap4R'];
    $bcas1 = $_POST['bcas1'];
    $bcas1R = $_POST['bcas1R'];
    $bcas2 = $_POST['bcas2'];
    $bcas2R = $_POST['bcas2R'];
    $bcas2_1 = $_POST['bcas2_1'];
    $bcas3 = $_POST['bcas3'];
    $bcas3R = $_POST['bcas3R'];
    $bcas4 = $_POST['bcas4'];
    $bcas4R = $_POST['bcas4R'];
    $bracs1 = $_POST['bracs1'];
    $bracs1R = $_POST['bracs1R'];
    $bracs2 = $_POST['bracs2'];
    $bracs2R = $_POST['bracs2R'];
    $bcc1 = $_POST['bcc1'];
    $bcc1R = $_POST['bcc1R'];
    $bcc2 = $_POST['bcc2'];
    $bcc2R = $_POST['bcc2R'];
    $bcpss1 = $_POST['bcpss1'];
    $bcpss1R = $_POST['bcpss1R'];
    $bcpss2 = $_POST['bcpss2'];
    $bcpss2R = $_POST['bcpss2R'];
    $bsap1 = $_POST['bsap1'];
    $bsap1R = $_POST['bsap1R'];
    $valv_264 = $_POST['valv_264'];
    $valv_262 = $_POST['valv_262'];
    $valv_260 = $_POST['valv_260'];
    $valv_261 = $_POST['valv_261'];
    $valv_208 = $_POST['valv_208'];
    $valv_205 = $_POST['valv_205'];
    $valv_263 = $_POST['valv_263'];
    $valv_265 = $_POST['valv_265'];
    $drycooler = $_POST['drycooler'];

    if($torre1 == '' || empty($torre1)){
        $torre1 = 0;
    }

    if($torre2 == '' || empty($torre2)){
        $torre2 = 0;
    }

    if($torre3 == '' || empty($torre3)){
        $torre3 = 0;
    }

    if($torre4 == '' || empty($torre4)){
        $torre4 = 0;
    }

    if($chiller1 == '' || empty($chiller1)){
        $chiller1 = 0;
    }

    if($chiller2 == '' || empty($chiller2)){
        $chiller2 = 0;
    }

    if($chiller3 == '' || empty($chiller3)){
        $chiller3 = 0;
    }

    if($chiller4 == '' || empty($chiller4)){
        $chiller4 = 0;
    }

    if($bac1 == '' || empty($bac1)){
        $bac1 = 0;
    }

    if($bac2 == '' || empty($bac2)){
        $bac2 = 0;
    }

    if($bac3R == '' || empty($bac3R)){
        $bac3R = 0;
    }

    if($bac4 == '' || empty($bac4)){
        $bac4 = 0;
    }

    if($bac4R == '' || empty($bac4R)){
        $bac4R = 0;
    }

    if($barvf2 == '' || empty($barvf2)){
        $barvf2 = 0;
    }

    if($baep1 == '' || empty($baep1)){
        $baep1 = 0;
    }

    if($baep2 == '' || empty($baep2)){
        $baep2 = 0;
    }

    if($baep3R == '' || empty($baep3R)){
        $baep3R = 0;
    }

    if($baep4 == '' || empty($baep4)){
        $baep4 = 0;
    }

    if($baep4R == '' || empty($baep4R)){
        $baep4R = 0;
    }

    if($baes1 == '' || empty($baes1)){
        $baes1 = 0;
    }

    if($baes1R == '' || empty($baes1R)){
        $baes1R = 0;
    }

    if($baes2 == '' || empty($baes2)){
        $baes2 = 0;
    }

    if($baes2R == '' || empty($baes2R)){
        $baes2R = 0;
    }

    if($baes2_1 == '' || empty($baes2_1)){
        $baes2_1 = 0;
    }

    if($baes3 == '' || empty($baes3)){
        $baes3 = 0;
    }

    if($baes3R == '' || empty($baes3R)){
        $baes3R = 0;
    }

    if($baes4 == '' || empty($baes4)){
        $baes4 = 0;
    }

    if($baes4R == '' || empty($baes4R)){
        $baes4R = 0;
    }

    if($caldera1 == '' || empty($caldera1)){
        $caldera1 = 0;
    }

    if($caldera2 == '' || empty($caldera2)){
        $caldera2 = 0;
    }

    if($caldera3 == '' || empty($caldera3)){
        $caldera3 = 0;
    }

    if($caldera4 == '' || empty($caldera4)){
        $caldera4 = 0;
    }

    if($caldera5 == '' || empty($caldera5)){
        $caldera5 = 0;
    }

    if($bcap5 == '' || empty($bcap5)){
        $bcap5 = 0;
    }

    if($bcap5R == '' || empty($bcap5R)){
        $bcap5R = 0;
    }

    if($bcap1 == '' || empty($bcap1)){
        $bcap1 = 0;
    }

    if($bcap2 == '' || empty($bcap2)){
        $bcap2 = 0;
    }

    if($bcap3 == '' || empty($bcap3)){
        $bcap3 = 0;
    }

    if($bcap4R == '' || empty($bcap4R)){
        $bcap4R = 0;
    }

    if($bcas1 == '' || empty($bcas1)){
        $bcas1 = 0;
    }

    if($bcas1R == '' || empty($bcas1R)){
        $bcas1R = 0;
    }

    if($bcas2 == '' || empty($bcas2)){
        $bcas2 = 0;
    }

    if($bcas2R == '' || empty($bcas2R)){
        $bcas2R = 0;
    }

    if($bcas2_1 == '' || empty($bcas2_1)){
        $bcas2_1 = 0;
    }

    if($bcas3 == '' || empty($bcas3)){
        $bcas3 = 0;
    }

    if($bcas3R == '' || empty($bcas3R)){
        $bcas3R = 0;
    }

    if($bcas4 == '' || empty($bcas4)){
        $bcas4 = 0;
    }

    if($bcas4R == '' || empty($bcas4R)){
        $bcas4R = 0;
    }

    if($bracs1 == '' || empty($bracs1)){
        $bracs1 = 0;
    }

    if($bracs1R == '' || empty($bracs1R)){
        $bracs1R = 0;
    }

    if($bracs2 == '' || empty($bracs2)){
        $bracs2 = 0;
    }

    if($bracs2R == '' || empty($bracs2R)){
        $bracs2R = 0;
    }

    if($bcc1 == '' || empty($bcc1)){
        $bcc1 = 0;
    }

    if($bcc1R == '' || empty($bcc1R)){
        $bcc1R = 0;
    }

    if($bcc2 == '' || empty($bcc2)){
        $bcc2 = 0;
    }

    if($bcc2R == '' || empty($bcc2R)){
        $bcc2R = 0;
    }

    if($bcpss1 == '' || empty($bcpss1)){
        $bcpss1 = 0;
    }

    if($bcpss1R == '' || empty($bcpss1R)){
        $bcpss1R = 0;
    }

    if($bcpss2 == '' || empty($bcpss2)){
        $bcpss2 = 0;
    }

    if($bcpss2R == '' || empty($bcpss2R)){
        $bcpss2R = 0;
    }

    if($bsap1 == '' || empty($bsap1)){
        $bsap1 = 0;
    }

    if($bsap1R == '' || empty($bsap1R)){
        $bsap1R = 0;
    }

    if($valv_264 == '' || empty($valv_264)){
        $valv_264 = 0;
    }

    if($valv_262 == '' || empty($valv_262)){
        $valv_262 = 0;
    }

    if($valv_260 == '' || empty($valv_260)){
        $valv_260 = 0;
    }

    if($valv_261 == '' || empty($valv_261)){
        $valv_261 = 0;
    }

    if($valv_208 == '' || empty($valv_208)){
        $valv_208 = 0;
    }

    if($valv_205 == '' || empty($valv_205)){
        $valv_205 = 0;
    }

    if($valv_263 == '' || empty($valv_263)){
        $valv_263 = 0;
    }

    if($valv_265 == '' || empty($valv_265)){
        $valv_265 = 0;
    }

    if($drycooler == '' || empty($drycooler)){
        $drycooler = 0;
    }

    /*$comentariosTrabajos = $horario . '\n' .
        'Las temperaturas de los recintos de Laboratorio en esta ronda son: VDRL: '.$vdrl.'°C,IFI(C-2-9): '.$ifi.'°C\n' .
        'Las temperaturas de UPC Adulto: '.$uci.'°C\n' .
        'Las temperatura de UPC Pediátrica: '.$uti.'°C';*/

    $nomSolicitante = 'YORGO CASIMIS SUBIABRE';
    $nomTecnicoRecepcionador = $nomUsuario;
    $servicioOrden = 22;
    $recintoOrden = 'Central Energetica';
    $pmaOrden = 'L-S1-16';
    $coordenadasOrden = '22-27/B-E';
    $tipoMantencion = 'IS';
    $problemaPresenta = 'Necesidad de inspeccionar el correcto funcionamiento de los sistemas integrados en BMS.';
    $trabajoRealizado = $horario . '\n' .
        'Las temperaturas de los recintos de Laboratorio en esta ronda son: VDRL: '.$vdrl.'°C,IFI(C-2-9): '.$ifi.'°C\n' .
        'Las temperaturas de UPC Adulto: '.$uci.'°C\n' .
        'Las temperatura de UPC Pediátrica: '.$uti.'°C';
    /*$accionesRealiza = '1- Revisión de equipos y dispositivos, Central Frío.\n' .
        '2- Revisión de equipos y dispositivos, Central Calor.\n' .
        '3-Revisión de equipos y dispositivos, Paneles Solares.\n' .
        '4-Revisión de Temperatura de recintos de Laboratorio (VDRL Y IFI (C-2-9)).\n' .
        '5-Revisión de Temperaturas de Recintos UPC.';*/

    $accionesRealiza = '';
    $comentariosTrabajos = '';
    $idHoja = 0;
    $idCategoria = 0;
    $departamentoSolicitante = 1;
    $ticketAsociado = 0;
    $año = date('Y');

    $selectAct = "SELECT numOrden FROM ordenes_trabajos_general WHERE YEAR(fechaOrden) = ".$año." ORDER BY fechaOrden DESC LIMIT 1";
    $resAct = mysqli_query($conexion,$selectAct);
    if ($resAct->num_rows == 0) {
        $numOrden = 1;
    }else{

        $fila = $resAct->fetch_assoc();
        $numOrden = $fila['numOrden'];
        $numOrden = $numOrden + 1;
    }

    $pieces = explode(" ", $nomDepartamentoSolicitante);

    $str = "";
    foreach ($pieces as $piece) {
        $str .= mb_strtoupper($piece[0], 'UTF-8');
    }

    $folioOrden = "HBQP-HT-A".$año."-" . 'I' . '-' . $numOrden;


    $fechaActualOrden = date('d-m-Y');

    $now = date('Y-m-d H:i:s');
    if($contar >= 1){
        $sqlConsolaAlarma = "INSERT INTO `parametros_uma_bms`(`idParametros_Uma`,`hora`, `fecha`, `pisosuby2`, `piso3`, `piso7`, `pabellones`, `crd`, `urgencias`, `hospitalizados`,`idTG`) 
        VALUES (NULL,'".$horario."','".$now."','".$pisosub."','".$piso3."','".$piso7."','".$pabellones."','".$crd."','".$urgencias."','".$hospitalizados."',$idTG)";
        $resConsolaAlarma = mysqli_query($conexion,$sqlConsolaAlarma);
    }

    $sqlInsertParametros = "INSERT INTO `parametros_ot_rondas`(`idParametros`, `hora`, `fecha`, `nomCreador`, `Torre1`, `Torre2`, `Torre3`, `Torre4`, `Chiller1`, `Chiller2`, `Chiller3`, `Chiller4`, `BAC1`, `BAC2`, `BAC3R`, `BAC4`, `BAC4R`, `BARVF2`, `BAEP1`, `BAEP2`, `BAEP3R`, `BAEP4`, `BAEP4R`, `BAES1`, `BAES1R`, `BAES2`, `BAES2R`, `BAES2_1`, `BAES3`, `BAES3R`, `BAES4`, `BAES4R`, `Caldera1`, `Caldera2`, `Caldera3`, `Caldera4`, `Caldera5`, `BCAP5`, `BCAP5R`, `BCAP1`, `BCAP2`, `BCAP3`, `BCAP4R`, `BCAS1`, `BCAS1R`, `BCAS2`, `BCAS2R`, `BCAS2_1`, `BCAS3`, `BCAS3R`, `BCAS4`, `BCAS4R`, `BRACS1`, `BRACS1R`, `BRACS2`, `BRACS2R`, `BCC1`, `BCC1R`, `BCC2`, `BCC2R`, `BCPSS1`, `BCPSS1R`, `BCPSS2`, `BCPSS2R`, `BSAP1`, `BSAP1R`, `valv_264`, `valv_262`, `valv_260`, `valv_261`, `valv_208`, `valv_205`, `valv_263`, `valv_265`, `Dry_cooler`, `idTG`) 
    VALUES (NULL,'".$horario."','".$now."','".$nomUsuario."',$torre1,$torre2,$torre3,$torre4,$chiller1,$chiller2,$chiller3,$chiller4,$bac1,$bac2,$bac3R,$bac4,$bac4R,$barvf2,$baep1,$baep2,$baep3R,$baep4,$baep4R,$baes1,$baes1R,$baes2,$baes2R,$baes2_1,$baes3,$baes3R,$baes4,$baes4R,$caldera1,$caldera2,$caldera3,$caldera4,$caldera5,$bcap5,$bcap5R,$bcap1,$bcap2,$bcap3,$bcap4R,$bcas1,$bcas1R,$bcas2,$bcas2R,$bcas2_1,$bcas3,$bcas3R,$bcas4,$bcas4R,$bracs1,$bracs1R,$bracs2,$bracs2R,$bcc1,$bcc1R,$bcc2,$bcc2R,$bcpss1,$bcpss1R,$bcpss2,$bcpss2R,$bsap1,$bsap1R,$valv_264,$valv_262,$valv_260,$valv_261,$valv_208,$valv_205,$valv_263,$valv_265,$drycooler,$idTG)";
    $resInsertParametros = mysqli_query($conexion,$sqlInsertParametros);

    $sqlTemperatura = "INSERT INTO `temperaturas_rondas`(`idTemperatura`,`hora`,`fecha`,`vdrl`, `ifi`, `uci_adulto`, `uti_pediatrica`, `vdrl_bms`, `ifi_bms`, `uci_adulto_bms`, `uti_pediatrica_bms`, `idTG`) 
    VALUES (NULL,'".$horario."','".$now."','".$vdrl."','".$ifi."','".$uci."','".$uti."','".$vdrlBMS."','".$ifiBMS."','".$uciBMS."','".$utiBMS."',$idTG)";
    $resTemperatura = mysqli_query($conexion,$sqlTemperatura);

    $queryOrden = "INSERT INTO ordenes_trabajos_general(idOrden,numOrden,fechaOrden,nomSolicitante,nomTecnicoRecepcionador,idDepartamento,servicioOrden,recintoOrden,pmaOrden,coordenadasOrden,tipoMantencion,trabajoRealizado,problemaPresenta,accionesRealiza, comentariosTrabajos, ticketAsociado, idCreador, tipoUsuario, noOrdenTrabajo, idEquipo, idCategoria)
    VALUES (NULL,".$numOrden.",'".$now."','" . $nomSolicitante . "','" . $nomTecnicoRecepcionador . "','" . $departamentoSolicitante . "','" . $servicioOrden . "','" . $recintoOrden . "','" . $pmaOrden . "','" . $coordenadasOrden . "','" . $tipoMantencion . "','" . $trabajoRealizado . "','" . $problemaPresenta . "','" . $accionesRealiza . "','" . $comentariosTrabajos . "','" . $ticketAsociado . "', $idUsuario, '$tipoUsuario','" . $folioTG. "', $idHoja, $idCategoria)";
    $resOrden = mysqli_query($conexion, $queryOrden);

    if($resOrden){
        $idOrden = mysqli_insert_id($conexion);
        $_SESSION['idOrden'] = $idOrden;

        $nomOrden = "Hoja Técnica: " . $folioOrden . ' - ' . $fechaActualOrden;

        $idSubDepartamento = '';

        if (isset($_POST['idSubDepartamento'])) {
            $idSubDepartamento = trim($_POST['idSubDepartamento']);

            if ($idSubDepartamento < 1 || empty($idSubDepartamento) || $idSubDepartamento == '') {

                $idSubDepartamento = 0;
            } else {
                $idSubDepartamento = trim($_POST['idSubDepartamento']);
            }
        } else {
            $idSubDepartamento = 0;
        }

        $queryFolio = "UPDATE ordenes_trabajos_general SET folioOrden = '" . $folioOrden . "', nomOrden = '" . $nomOrden . "', idSubDepartamento = '" . $idSubDepartamento . "' WHERE idOrden =" . $idOrden;
        $resFolio = mysqli_query($conexion, $queryFolio);
    }


    if($resInsertParametros && $resTemperatura){
        if($tipoUsuario != 'personal'){
            header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?exitoRondas=true');
        }else{
            header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?exitoRondas=true');
        }
    }else{
        if($tipoUsuario != 'personal'){
            header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?errorRondas=true');
        }else{
            header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?errorRondas=true');
        }
    }

}else{
    if($tipoUsuario != 'personal'){
        header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?errorRondas=true');
    }else{
        header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?errorRondas=true');
    }
}