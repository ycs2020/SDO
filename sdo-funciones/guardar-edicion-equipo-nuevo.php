<?php


include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// VARIABLES DE SESSION
$userUsuario = $_SESSION['nomUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];
$idDepartamento = $_SESSION['idDepartamento'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);

$rutaUsuario = "";
if ($rolUsuario2 == "personal") {
    $rutaUsuario = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $rutaUsuario = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $rutaUsuario = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $rutaUsuario = "sdo-jefestecnicos";
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

        // Datos obligatorios
        $servicio = $_POST['servicio'];
        $nombreEquipo = $_POST['nombreEquipo'];
        $idEquipamientoPadre = isset($_POST['idEquipamientoPadre']) ? $_POST['idEquipamientoPadre'] : 0;

        if (empty($servicio) || empty($nombreEquipo)) {
            echo 'vacios los obligatorios';
            exit;
        } else {

            // Obtener datos anteriores del equipo
            $sqlDatosAnteriores = "SELECT * FROM equipamiento_hbqp WHERE id = $idEquipo";
            $resDatosAnteriores = mysqli_query($conexion, $sqlDatosAnteriores);
            $datosAntes = mysqli_fetch_assoc($resDatosAnteriores);

            // Guardar historial
            // Guardar historial legible
            if ($datosAntes) {
                $nombreEquipoAnterior = $datosAntes['nombreEquipo'];
                $fechaAhora = date('Y-m-d H:i:s');

                $textoHistorial =
                    "Nombre del equipo: " . $datosAntes['nombreEquipo'] . "\n" .
                    "Marca: " . $datosAntes['marca'] . "\n" .
                    "Modelo: " . $datosAntes['modelo'] . "\n" .
                    "N° de Serie: " . $datosAntes['numeroSerie'] . "\n" .
                    "N° de Inventario: " . $datosAntes['numeroInventario'] . "\n" .
                    "Fecha de Compra: " . $datosAntes['fechaCompra'] . "\n" .
                    "Fecha Recepción: " . $datosAntes['fechaRecepcion'] . "\n" .
                    "Clase: " . $datosAntes['clase'] . "\n" .
                    "Subclase: " . $datosAntes['subclase'] . "\n" .
                    "Vida Útil: " . $datosAntes['vidaUtil'] . "\n" .
                    "Versión Software: " . $datosAntes['versionSoftware'] . "\n" .
                    "Pais de Origen: " . $datosAntes['paisOrigen'] . "\n" .
                    "Costo Mantención: " . $datosAntes['costoMantAnual'] . "\n" .
                    "ID Licitación: " . $datosAntes['idLicitacion'] . "\n" .
                    "Orden Compra: " . $datosAntes['ordenCompra'] . "\n" .
                    "Frecuencia Mantención: " . $datosAntes['frecuenciaMantenimiento'] . "\n" .
                    "Tipo Interfaz: " . $datosAntes['tipoMenu'] . "\n" .
                    "Complejidad Tecnológica: " . $datosAntes['complejidadTec'] . "\n" .
                    "Nivel de Riesgo: " . $datosAntes['nivelRiesgo'] . "\n" .
                    "Dirección IP: " . $datosAntes['direccionIP'] . "\n" .
                    "MAC Address: " . $datosAntes['mac_address'] . "\n" .
                    "Device ID: " . $datosAntes['device_id'] . "\n" .
                    "Ejes ECBOSS: " . $datosAntes['ejesECBoss'] . "\n" .
                    "Ejes Rack: " . $datosAntes['ejesRack'] . "\n" .
                    "TCC: " . $datosAntes['tcc'] . "\n" .
                    "Ejes TCC: " . $datosAntes['ejesTCC'] . "\n" .
                    "Alimentación TCC: " . $datosAntes['alimentaciontcc'] . "\n" .
                    "UMA Control: " . $datosAntes['umaControl'] . "\n" .
                    "Piso: " . $datosAntes['piso'] . "\n" .
                    "Recinto: " . $datosAntes['recinto'] . "\n" .
                    "PMA: " . $datosAntes['pma'] . "\n" .
                    "Coordenadas: " . $datosAntes['coordenadas'];

                $sqlHistorial = "INSERT INTO log_ediciones_equipamiento (
        idEquipo,
        nombreEquipoAnterior,
        nombreEquipoNuevo,
        usuarioModifica,
        fechaModificacion,
        datosPrevios
    ) VALUES (
        '$idEquipo',
        '" . mysqli_real_escape_string($conexion, $nombreEquipoAnterior) . "',
        '" . mysqli_real_escape_string($conexion, $nombreEquipo) . "',
        '" . mysqli_real_escape_string($conexion, $userUsuario) . "',
        '$fechaAhora',
        '" . mysqli_real_escape_string($conexion, $textoHistorial) . "'
    )";
                mysqli_query($conexion, $sqlHistorial);
            }


            // INFORMACIÓN TÉCNICA
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

            // INFORMACIÓN BÁSICA
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

            // Campos tipoControlador esclavo/maestro
            $device = $_POST['device'];
            $network = $_POST['network'];
            $mac = $_POST['mac'];
            $tcc = $_POST['tcc'];
            $ejestcc = $_POST['ejestcc'];
            $alimentaciontcc = $_POST['alimentaciontcc'];
            $ejesECBOSS = $_POST['ejesECBOSS'];
            $ejesRack = $_POST['ejesRack'];
            $rack = $_POST['rack'];
            $puerto = $_POST['puerto'];
            $direccionIP = $_POST['direccionIP'];
            $umaControl = $_POST['umaControl'];

            // UBICACIÓN
            $recinto = $_POST['recinto'];
            $pma = $_POST['pma'];
            $coordenadas = $_POST['coordenadas'];
            $piso = $_POST['piso'];
            if ($piso == 'NULL' || $piso == '') $piso = 0;

            $_SESSION['idEquipo'] = ($idEquipamientoPadre > 0) ? $idEquipamientoPadre : $idEquipo;
            $equipoQueControla2 = ($equipoQueControla > 0) ? $equipoQueControla : 0;
            $sourceAlarmaMod2 = (!empty($sourceAlarmaMod) && $sourceAlarmaMod !== 'Seleccione...') ? $sourceAlarmaMod : '';
            $tipoInterfaz = $_POST['tipoInterfaz'];
            $tipoInterfaz2 = (!empty($tipoInterfaz) && $tipoInterfaz !== 'Seleccione...') ? $tipoInterfaz : '';

            if ($rolUsuario2 == 'administrador') {
                $sitioWeb = ($tipoInterfaz2 == 'nuevo') ? 'equipo_menuNuevo.php' : 'equipo_menuAntiguo.php';
            } else {
                $sitioWeb = ($idDepartamento == 6) ? 'equipo-inge-clinica.php' : 'equipo.php';
            }

            // ACTUALIZAR DATOS
            $sqlInsert = "UPDATE equipamiento_hbqp SET 
                alimentaciontcc = '$alimentaciontcc',
                device_id = '$device',
                mac_address = '$mac',
                tcc = '$tcc',
                ejesTCC = '$ejestcc',
                network = '$network',
                rack = '$rack',
                puerto = '$puerto',
                direccionIP = '$direccionIP',
                ejesRack = '$ejesRack',
                ejesECBoss = '$ejesECBOSS',
                numeroInventario = '$inputInventario',
                fechaRecepcion = '$fechaRecepcion',
                nombreEquipo = '$nombreEquipo',
                marca = '$marcaEquipo',
                modelo = '$modeloEquipo',
                idServicio = $servicio,
                recinto = '$recinto',
                pma = '$pma',
                coordenadas = '$coordenadas',
                fechaCompra = '$fechaCompra',
                valorConIva = '$valorConIva',
                idLicitacion = '$IDLicitacion',
                ordenCompra = '$ordenCompra',
                fechaMarcha = '$fechaMarcha',
                clase = '$clase',
                subclase = '$subClase',
                complejidadTec = '$complejidad',
                nivelRiesgo = '$riesgo',
                AhnoFrabricacion = '$fechaFabricación',
                vidaUtil = '$vidaUtil',
                versionSoftware = '$versionSoftware',
                paisOrigen = '$paisOrigen',
                terminoGarantia = '$terminoGarantia',
                costoMantAnual = '$costoMantencion',
                frecuenciaMantenimiento = '$inputMantenimiento',
                numeroSerie = '$numSerie',
                piso = '$piso',
                equipo_controla = '$equipoQueControla2',
                sourceAlarma = '$sourceAlarmaMod2',
                tipoMenu = '$tipoInterfaz2',
                umaControl = '$umaControl'
                WHERE id = $idEquipo";

            $resInsert = mysqli_query($conexion, $sqlInsert);
            if ($resInsert) {
                header('Location: ../' . $rutaUsuario . '/' . $sitioWeb . '?exitoEditarEquipo=true');
            } else {
                header('Location: ../' . $rutaUsuario . '/' . $sitioWeb . '?errorEditarEquipo=true');
            }
        }
    } else {
        header('Location: ../' . $rutaUsuario . '/equipamiento_hbqp.php');
    }
}
