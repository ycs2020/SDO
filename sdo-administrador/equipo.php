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

$fecha = date('d-m-Y H:i');
//
//$ipPermitida = '10.69.250.151'; // Reemplaza con la IP permitida
//
//$ipCliente = $_SERVER['REMOTE_ADDR'];
//
//if ($ipCliente === $ipPermitida) {
//    $valorDesarrollo = 'Pasaste';
//} else {
//    header('Location: plataforma_mantenimiento.php');
//}

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
} else if ($rolUsuario2 == "oficina tecnica") {
    $tipoUsuario = 'oficina tecnica';
    $rutaDireccion = "sdo-oficinaTecnica";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if(isset($_GET['exitoGenerarPlanificador']) && isset($_GET['idEquipo'])) {
    $idEquipo = $_GET['idEquipo'];

}

if (isset($_POST['idEquipo']) || isset($_SESSION['idEquipo'])) {

    if (isset($_POST['idEquipo'])) {
        $idEquipo = $_POST['idEquipo'];
        $_SESSION['idEquipo'] = $idEquipo;
    } else if (isset($_SESSION['idEquipo'])) {
        $idEquipo = $_SESSION['idEquipo'];
    } else {
        header('Location: ../' . $rutaDireccion . '/carpeta-equipo.php?errorCargarEquipo=true');
    }

    //$_SESSION['idEquipo'] = $idEquipo;

    $sql = "SELECT * FROM equipamiento_hbqp WHERE id = " . $idEquipo;

    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {

        $fechaRegistro = $row['fechaRegistro'];
        $identificadorUnico = $row['identificadorUnico'];
        $numeroInventario = $row['numeroInventario'];
        $fechaRecepcion = $row['fechaRecepcion'];
        $nombreEquipo = $row['nombreEquipo'];
        $marca = $row['marca'];
        $modelo = $row['modelo'];

        $modeloCadena = $modelo;

        $servicioDB = $row['idServicio'];
        $recinto = $row['recinto'];
        $pma = $row['pma'];
        $coordenadas = $row['coordenadas'];
        $fechaCompra = $row['fechaCompra'];
        $valorConIva = $row['valorConIva'];
        $idLicitacion = $row['idLicitacion'];
        $ordenCompra = $row['ordenCompra'];
        $fechaMarcha = $row['fechaMarcha'];
        $clase = $row['clase'];
        $subclase = $row['subclase'];
        $complejidadTec = $row['complejidadTec'];
        $nivelRiesgo = $row['nivelRiesgo'];
        $AhnoFrabricacion = $row['AhnoFrabricacion'];
        $vidaUtil = $row['vidaUtil'];
        $versionSoftware = $row['versionSoftware'];
        $paisOrigen = $row['paisOrigen'];
        $terminoGarantia = $row['terminoGarantia'];
        $costoMantAnual = $row['costoMantAnual'];
        $frecuenciaMantenimiento = $row['frecuenciaMantenimiento'];
        $numeroSerie = $row['numeroSerie'];
        $piso = $row['piso'];
        $idCategoria = $row['idCategoria'];
        $estadoEquipo = $row['estadoEquipo'];
        $tipoControlador = $row['tipoControlador'];
        $device = $row['device_id'];
        $network = $row['network'];
        $mac = $row['mac_address'];
        $tcc = $row['tcc'];
        $ejesTCC = $row['ejesTCC'];
        $alimentacionTCC = $row['alimentacionTCC'];
        $ejesECBoss = $row['ejesECBoss'];
        $ejesRack = $row['ejesRack'];
        $rack = $row['rack'];
        $puerto = $row['puerto'];
        $direccionIP = $row['direccionIP'];
        $idEquipoPadre = $row['idEquipoPadre'];
        $rutaImagen = $row['rutaImagen'];
        $equipo_controla = $row['equipo_controla'];

        $equipoSource = $row['sourceAlarma'];
        $tipoMenu = $row['tipoMenu'];

        $sql = "SELECT * FROM categorias_equipos WHERE idCategoria = $idCategoria";
        $res = mysqli_query($conexion, $sql);

        while ($row = $res->fetch_assoc()) {
            $datos = [];
            $datos['nomCategoria'] = $row['nomCategoria'];
            $nomCategoria = $datos['nomCategoria'];
        }

        $sql = "SELECT COUNT(*) AS cantidadHojasTec FROM ordenes_trabajos_general WHERE idEquipo = $idEquipo";
        $res = mysqli_query($conexion, $sql);

        while ($row = $res->fetch_assoc()) {
            $datos = [];
            $datos['cantidadHojasTec'] = $row['cantidadHojasTec'];
            $cantidadHojasTec = $datos['cantidadHojasTec'];
        }

        $sqlSolicitud = "SELECT COUNT(*) AS total FROM solicitudes_compra_sdo WHERE idEquipo = $idEquipo";
        $resSolicitud = mysqli_query($conexion, $sqlSolicitud);

        while ($rowSolicitud = $resSolicitud->fetch_assoc()) {
            $datosSolicitud = [];
            $datosSolicitud['total'] = $rowSolicitud['total'];
            $total = $datosSolicitud['total'];
        }

        $sql = "SELECT COUNT(*) AS cantidadSubEquipos FROM equipamiento_hbqp WHERE idEquipoPadre = $idEquipo";
        $res = mysqli_query($conexion, $sql);

        while ($row = $res->fetch_assoc()) {
            $datos = [];
            $datos['cantidadSubEquipos'] = $row['cantidadSubEquipos'];
            $cantidadSubEquipos = $datos['cantidadSubEquipos'];
        }

        $sql = "SELECT SUM(costoTotal) AS gastoTotal FROM  gasto_equipo_carpeta WHERE idEquipo = $idEquipo";
        $res = mysqli_query($conexion, $sql);

        while ($row = $res->fetch_assoc()) {
            $datos = [];
            $datos['gastoTotal'] = $row['gastoTotal'];
            $gastoTotal = $datos['gastoTotal'];
        }

        $sql = "SELECT COUNT(*) AS cantidadArchivos FROM archivos_equipamiento_hbqp WHERE idEquipo = $idEquipo";
        $res = mysqli_query($conexion, $sql);

        while ($row = $res->fetch_assoc()) {
            $datos = [];
            $datos['cantidadArchivos'] = $row['cantidadArchivos'];
            $cantidadArchivos = $datos['cantidadArchivos'];
        }

        $sqlx = "SELECT MAX(id) AS ultimo_id FROM equipamiento_hbqp";
        $resx = mysqli_query($conexion,$sqlx);
        while($rowx = $resx->fetch_assoc()){
            $ultimo_id = $rowx['ultimo_id'];
            $ultimo_id = $ultimo_id + 1;
        }

        $idGenerado = 'EQUIP-HBQP-' . $ultimo_id;

    }
}


$porcionesAntes = explode("ECB", $nombreEquipo);

$variableParaTablaAntes = 'ECB ' . $porcionesAntes[1];

$porcionesAntes2 = explode("_", $variableParaTablaAntes);

$variableParaTablaFinalAntes = $porcionesAntes2[0] . '-' . $porcionesAntes2[1];


//CONTAR DIGITAL INPUT Y ANALOG INPUT
$contardigitalinput = contardigitalinput($variableParaTablaFinalAntes);
$contaranaloginput = contaranaloginput($variableParaTablaFinalAntes);
//FIN DEL CONTEO INPUT

//CONTAR DIGITAL Y ANALOG OUTPUT
$contardigitaloutput = contardigitaloutput($variableParaTablaFinalAntes);

$contaranalogoutput = contaranalogoutput($variableParaTablaFinalAntes);
//FIN CONTEO OUTPUT
$contarambosinput = $contardigitalinput + $contaranaloginput;
$contarambosoutput = $contardigitaloutput + $contaranalogoutput;

$buscarequipoPadre = buscarequipoPadre($idEquipoPadre);

$contarsiguientes = 1;



?>
