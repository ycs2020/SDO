<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];
$idDepartamento = $_SESSION['idDepartamento'];

$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);

$rutaUsuario = "";
// echo '';
if ($rolUsuario2 == "personal") {
    $rutaUsuario = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $rutaUsuario = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $rutaUsuario = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $rutaUsuario = "sdo-jefestecnicos";
    //$idDepartamento = $_SESSION['idDepartamento'];
} else if ($rolUsuario2 == "administrador") {
    $rutaUsuario = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $rutaUsuario = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idEquipo = $_POST['idEquipo'];
    if (!empty($idEquipo)) {

        //Datos Obligatorios
        $servicio = $_POST['servicio'];
        $nombreEquipo = $_POST['nombreEquipo'];


        $idEquipamientoPadre = $_POST['idEquipamientoPadre'];

        if (empty($servicio) || empty($nombreEquipo)) {
            echo 'vacios los obligatorios';
            exit;
        } else {

            //INFORMACIÓN TÉCNICA
            $inputInventario = $_POST['inputInventario'];
            $IDLicitacion = $_POST['IDLicitacion'];
            $ordenCompra = $_POST['ordenCompra'];
            $inputMantenimiento = $_POST['inputMantenimiento'];
            $fechaRecepcion = $_POST['fechaRecepcion'];
            $fechaCompra = $_POST['fechaCompra'];
            $fechaMarcha = $_POST['fechaMarcha'];
            $clase = $_POST['clase'];
            $subClase = $_POST['subClase'];
            $complejidad = $_POST['complejidad'];
            $riesgo = $_POST['riesgo'];
            $costoMantencion = $_POST['costoMantencion'];
            $equipoQueControla = $_POST['equipoQueControlaMod'];
            $sourceAlarmaMod = $_POST['sourceAlarmaMod'];

            //INFORMACIÓN BÁSICA
            $valorConIva = $_POST['valorConIva'];
            $marcaEquipo = $_POST['marcaEquipo'];
            $modeloEquipo = $_POST['modeloEquipo'];
            $fechaFabricación = $_POST['fechaFabricación'];
            $vidaUtil = $_POST['vidaUtil'];
            $versionSoftware = $_POST['versionSoftware'];
            $paisOrigen = $_POST['paisOrigen'];
            $terminoGarantia = $_POST['terminoGarantia'];
            $numSerie = $_POST['numSerie'];
            $tipoControlador = $_POST['tipoControlador'];

//            if($tipoControlador == 'esclavo'){
            $device = $_POST['device'];
            $network = $_POST['network'];
            $mac = $_POST['mac'];
            $tcc = $_POST['tcc'];
            $ejestcc = $_POST['ejestcc'];
            $alimentaciontcc = $_POST['alimentaciontcc'];
            $ejesECBOSS = '';
            $ejesRack = '';
            $rack = '';
            $puerto = '';
            $direccionIP = '';
//            }
//            if($tipoControlador == 'maestro'){
            $ejesECBOSS = $_POST['ejesECBOSS'];
            $ejesRack = $_POST['ejesRack'];
            $rack = $_POST['rack'];
            $puerto = $_POST['puerto'];
            $direccionIP = $_POST['direccionIP'];
            $device = '';
            $network = '0';
            $mac = '0';
            $tcc = '';
            $ejestcc = '';
            $alimentaciontcc = '';

//            }

            //UBICACIÓN
            $recinto = $_POST['recinto'];
            $pma = $_POST['pma'];
            $coordenadas = $_POST['coordenadas'];
            $piso = $_POST['piso'];
            if ($piso == 'NULL' || $piso == '') {
                $piso = 0;
            }

            if ($idEquipamientoPadre > 0) {
                $_SESSION['idEquipo'] = $idEquipamientoPadre;
            } else {
                $_SESSION['idEquipo'] = $idEquipo;
            }

            if ($equipoQueControla > 0) {
                $equipoQueControla2 = $equipoQueControla;
            } else {
                $equipoQueControla2 = 0;
            }

            if (!empty($sourceAlarmaMod) && $sourceAlarmaMod !== 'Seleccione...') {
                $sourceAlarmaMod2 = $sourceAlarmaMod;
            } else {
                $sourceAlarmaMod2 = '';
            }

            $tipoInterfaz = $_POST['tipoInterfaz'];

            if (!empty($tipoInterfaz) && $tipoInterfaz !== 'Seleccione...') {
                $tipoInterfaz2 = $tipoInterfaz;

            } else {
                $tipoInterfaz2 = '';
            }

            if ($rolUsuario2 == 'administrador') {
                if ($tipoInterfaz2 == 'nuevo') {
                    $sitioWeb = 'equipo_menuNuevo.php';
                } else {
                    $sitioWeb = 'equipo_menuAntiguo.php';
                }
            } else {
                if ($idDepartamento == 6 && $rolUsuario2 == 'jefe tecnico') {
                    $sitioWeb = 'equipo.php';
                } else if($idDepartamento == 6){
                    $sitioWeb = 'equipo-inge-clinica.php';
                }else{
                    $sitioWeb = 'equipo.php';
                }
            }
            $_SESSION['idEquipo'] = $idEquipo;
            $ahnoAdquisicion = $_POST['ahnoAdquisicion'];
            $propiedades = $_POST['propiedades'];
            $evaluaciones = $_POST['evaluaciones'];
            $nombreProovedor = $_POST['nombreProovedor'];
            $mantenimientos = $_POST['mantenimientos'];
            $enGarantia = $_POST['enGarantia'];
            $vidaUtilResidual = $_POST['vidaUtilResidual'];
            $bajoPlanMantenimiento = $_POST['bajoPlanMantenimiento'];
            $idConvenioMantenimiento = $_POST['idConvenioMantenimiento'];
            $costoAnualMantenimiento = $_POST['costoAnualMantenimiento'];
            $frecuenciaAnualMantenimiento = $_POST['frecuenciaAnualMantenimiento'];
            $paginaWeb = $_POST['paginaWeb'];
            $mail = $_POST['mail'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'];
            $ahnoFuncionamiento = $_POST['ahnoFuncionamiento'];
            $factura = $_POST['factura'];
            $ahnoInstalacion = $_POST['ahnoInstalacion'];

            if($ahnoInstalacion == '' || empty($ahnoInstalacion)){
                $ahnoInstalacion = 0;
            }

            if($ahnoFuncionamiento == '' || empty($ahnoFuncionamiento)){
                $ahnoFuncionamiento = 0;
            }

            $sqlInsert = "UPDATE equipamiento_hbqp SET AhnoInstalacion = $ahnoInstalacion,factura = '$factura',ahnoFuncionamiento = $ahnoFuncionamiento,direccion = '$direccion',telefono = '$telefono',mail = '$mail',paginaWeb = '$paginaWeb',frecuenciaAnualMantenimiento = '$frecuenciaAnualMantenimiento',costoAnualMantenimiento = '$costoAnualMantenimiento',idConvenioMantenimiento = '$idConvenioMantenimiento',bajoPlanMantenimiento = '$bajoPlanMantenimiento',vidaUtilResidual = '$vidaUtilResidual',enGarantia = '$enGarantia',mantenimientos = '$mantenimientos',nombreProovedor = '$nombreProovedor',evaluaciones = '$evaluaciones',propiedades = '$propiedades',ahnoAdquisicion = '$ahnoAdquisicion',alimentaciontcc = '$alimentaciontcc', device_id = '$device', mac_address = '$mac', tcc = '$tcc', ejesTCC = '$ejestcc', network = '$network', rack = '$rack' , puerto = '$puerto', direccionIP = '$direccionIP', ejesRack = '$ejesRack' , ejesECBoss = '$ejesECBOSS', numeroInventario = '$inputInventario', fechaRecepcion = '$fechaRecepcion', nombreEquipo = '$nombreEquipo' , marca = '$marcaEquipo' , modelo = '$modeloEquipo', idServicio = $servicio, recinto = '$recinto' , pma = '$pma', coordenadas = '$coordenadas', fechaCompra = '$fechaCompra', valorConIva = '$valorConIva', idLicitacion = '$IDLicitacion', ordenCompra = '$ordenCompra', fechaMarcha = '$fechaMarcha', clase = '$clase', subclase = '$subClase', complejidadTec = '$complejidad', nivelRiesgo = '$riesgo', AhnoFrabricacion = '$fechaFabricación', vidaUtil = '$vidaUtil', versionSoftware = '$versionSoftware', paisOrigen = '$paisOrigen', terminoGarantia = '$terminoGarantia', costoMantAnual = '$costoMantencion', frecuenciaMantenimiento = '$inputMantenimiento',numeroSerie = '$numSerie', piso = '$piso', equipo_controla = '$equipoQueControla2', sourceAlarma = '$sourceAlarmaMod2', tipoMenu = '$tipoInterfaz2' WHERE id = $idEquipo";
            $resInsert = mysqli_query($conexion, $sqlInsert);
            if ($resInsert) {
                header('Location: ../' . $rutaUsuario . '/'.$sitioWeb.'?exitoEditarEquipo=true');
            } else {
                header('Location: ../' . $rutaUsuario . '/'.$sitioWeb.'?errorEditarEquipo=true');
            }
        }
    }
}