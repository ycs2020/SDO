<?php
include '../../sdo-funciones/conexion.php'; // Ajustado según tu estructura de carpetas
include '../../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');
$now = date('Y-m-d H:i:s');

$userUsuario = $_SESSION['userUsuario'] ?? '';
$idUsuario = $_SESSION['idUsuario'] ?? 0;
$nomUsuario = $_SESSION['nomUsuario'] ?? '';
$rolUsuario = $_SESSION['rolUsuario'] ?? '';
$rolUsuario2 = strtolower($rolUsuario);
$idDepartamento = $_SESSION['idDepartamento'] ?? 0;

if ($idDepartamento == 13) {
    $idDepartamento = 1;
}

// Validación inicial de sesión
if (empty($nomUsuario)) {
    echo json_encode(["status" => "error", "message" => "Sesión inválida o expirada."]);
    exit();
}

if (isset($_POST['fechaMonitoreo'], $_POST['tipoFormulario'], $_POST['personaAsignada'], $_POST['turnoAsignado'], $_POST['creadorOT'])) {
    $fechaMonitoreo = $_POST['fechaMonitoreo'];
    $tipoFormulario = intval($_POST['tipoFormulario']);
    $personaAsignada = $_POST['personaAsignada'];
    $turnoAsignado = intval($_POST['turnoAsignado']);
    $creadorOT = $_POST['creadorOT'];

    $turnoAsignado2 = ($turnoAsignado == 1) ? 'Personal Diurno' : (($turnoAsignado == 2) ? 'Personal Nocturno' : '');
    $folio = 'HBQP';
    $estado = 'Trabajo Ingresado';
    $descripcion = 'Monitoreo y Control de BMS';

    $servicioOrden = obtenerIDServicioControl();
    if ($servicioOrden <= 0) {
        $servicioOrden = 0;
    }

    if (($turnoAsignado !== 1 && $turnoAsignado !== 2) || $tipoFormulario < 0) {
        echo json_encode(["status" => "error", "message" => "Datos de turno o formulario incorrectos."]);
        exit();
    }

    // Estructuras de textos predefinidos para la OT
    $detalleTurno = ''; $detalleTurnoCamara = ''; $detalleTurnoLLE = ''; $detalleTurnoCA = ''; $detalleTurnoRondas = ''; $detalleOtros = '';

    if ($tipoFormulario == 0) {
        $detalleTurno = "Redactar en reporte de eventos relevantes (apartado de control centralizado) el estado de los parámetros de funcionamiento de las unidades manejadoras de aire de pabellones.\nRealizar AST correspondiente al turno.";
        $detalleOtros = "Ordenar herramientas y materiales utilizados en la jornada de trabajo.\nOrden general en sala de control y kitchenette de control.\nConfeccionar documento de entrega de turno (acta), con información de novedades y operatividad de los sistemas.";
    } else if ($turnoAsignado == 1 && $tipoFormulario == 1) {
        $detalleTurno = "Revisión e inspección de consolas de alarma y tablas resúmenes de equipos de clima pisos 3 al 6 en BMS.\nRedactar en reporte de eventos relevantes (apartado de control centralizado) el estado de los parámetros de funcionamiento de las unidades manejadoras de aire de pabellones.\nRealizar AST correspondiente al turno.";
        $detalleTurnoRondas = "Realizar rondas de inspección de equipos y bombas del sistema de central frio, calor y paneles solares. Además, se debe inspeccionar la variable de temperatura en el sector de laboratorio (piso 2, sector C), UPC Adulto y UTI Infantil. El objetivo es contrastar la información del estado de funcionamiento de los equipos, inspeccionada en terreno, con lo que muestra el BMS. \nRevisar estado de sensor de inundación ubicado en sala de caldera.\nRevisar estado de disyuntores trifásicos de bombas.";
        $detalleOtros = "Ordenar herramientas y materiales utilizados en la jornada de trabajo.\nOrden general en sala de control y kitchenette de control.\nConfeccionar documento de entrega de turno (acta), con información de novedades y operatividad de los sistemas.";
    } else if ($turnoAsignado == 2 && $tipoFormulario == 1) {
        $detalleTurno = "Redactar correo dirigido al servicio de pabellón, adjuntando tabla con parámetros de temperatura y humedad (Pabellón N°2 al 10).\nRevisar sistema de Ticket (SIGH) y derivar al departamento que corresponda.\nRevisión e inspección de consolas de alarma y tablas resúmenes de equipos de clima pisos 3 al 6 en BMS.\nRedactar en reporte de eventos relevantes (apartado de control centralizado) el estado de los parámetros de funcionamiento de las unidades manejadoras de aire de pabellones.\nRealizar AST correspondiente al turno.";
        $detalleTurnoCA = "Entrega de permisos correspondientes en software de control de accesos (formato de solicitud de permisos).";
        $detalleTurnoRondas = "Realizar rondas de inspección de equipos y bombas del sistema de central frio, calor y paneles solares. Además, se debe inspeccionar la variable de temperatura en el sector de laboratorio (piso 2, sector C), UPC Adulto y UTI Infantil. El objetivo es contrastar la información del estado de funcionamiento de los equipos, inspeccionada en terreno, con lo que muestra el BMS. \nRevisar estado de sensor de inundación ubicado en sala de caldera.\nRevisar estado de disyuntores trifásicos de bombas.";
        $detalleOtros = "Ordenar herramientas y materiales utilizados en la jornada de trabajo.\nOrden general en sala de control y kitchenette de control.\nConfeccionar documento de entrega de turno (acta), con información de novedades y operatividad de los sistemas.";
    } else if ($turnoAsignado == 1 && $tipoFormulario == 2) {
        $detalleTurno = "Revisión e inspección de parámetros de las 74 unidades manejadoras de aire y tablas resúmenes de equipos de clima pisos subterráneo al 2 en BMS.\nRedactar en reporte de eventos relevantes (apartado de control centralizado) el estado de los parámetros de funcionamiento de las unidades manejadoras de aire de pabellones.\nRealizar AST correspondiente al turno.";
        $detalleTurnoRondas = "Realizar rondas de inspección de equipos y bombas del sistema de central frio, calor y paneles solares. Además, se debe inspeccionar la variable de temperatura en el sector de laboratorio (piso 2, sector C), UPC Adulto y UTI Infantil. El objetivo es contrastar la información del estado de funcionamiento de los equipos, inspeccionada en terreno, con lo que muestra el BMS. \nRevisar estado de sensor de inundación ubicado en sala de caldera.\nRevisar estado de disyuntores trifásicos de bombas.";
        $detalleOtros = "Ordenar herramientas y materiales utilizados en la jornada de trabajo.\nOrden general en sala de control y kitchenette de control.\nConfeccionar documento de entrega de turno (acta), con información de novedades y operatividad de los sistemas.";
    } else if ($turnoAsignado == 2 && $tipoFormulario == 2) {
        $detalleTurno = "Reiniciar biométrico de bicicletero a las 07:00 hrs. (presionar interruptor en terreno).\nRevisar sistema de Ticket (SIGH) y derivar al departamento que corresponda.\nRevisión e inspección de parámetros de las 74 unidades manejadoras de aire y tablas resúmenes de equipos de clima pisos subterráneo al 2 en BMS.\nRedactar en reporte de eventos relevantes (apartado de control centralizado) el estado de los parámetros de funcionamiento de las unidades manejadoras de aire de pabellones.\nRealizar AST correspondiente al turno.";
        $detalleTurnoCA = "Entrega de permisos correspondientes en software de control de accesos (formato de solicitud de permisos).";
        $detalleTurnoRondas = "Realizar rondas de inspección de equipos y bombas del sistema de central frio, calor y paneles solares. Además, se debe inspeccionar la variable de temperatura en el sector de laboratorio (piso 2, sector C), UPC Adulto y UTI Infantil. El objetivo es contrastar la información del estado de funcionamiento de los equipos, inspeccionada en terreno, con lo que muestra el BMS. \nRevisar estado de sensor de inundación ubicado en sala de caldera.\nRevisar estado de disyuntores trifásicos de bombas.";
        $detalleOtros = "Ordenar herramientas y materiales utilizados en la jornada de trabajo.\nOrden general en sala de control y kitchenette de control.\nConfeccionar documento de entrega de turno (acta), con información de novedades y operatividad de los sistemas.";
    }

    // CONSULTA PREPARADA 1 CORREGIDA: Se ajustaron los campos y valores a insertar exactamente (17 marcadores '?')
    $stmt = $conexion->prepare("INSERT INTO trabajos_general 
        (idTG, folioTG, solicitanteTG, detalleTG, detalleTurnoCamara, detalleTurnoLLE, detalleTurnoCA, detalleTurnoRondas, detalleOtros, fechaTG, estadoTG, turnoTG, tecnico1, fechaProgramacion, ticketAsociado, tipoTrabajo, departamento, formularioRondas, descripcion, servicio) 
        VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, '', ?, ?, ?, ?)");

    // Explicación del string de tipos (17 caracteres):
    // s = string (13 variables de texto)
    // i = entero (4 variables numéricas: departamento, tipoFormulario, descripcion, servicioOrden)
    $stmt->bind_param("sssssssssssssiisi",
        $folio,
        $creadorOT,
        $detalleTurno,
        $detalleTurnoCamara,
        $detalleTurnoLLE,
        $detalleTurnoCA,
        $detalleTurnoRondas,
        $detalleOtros,
        $now,
        $estado,
        $turnoAsignado2,
        $personaAsignada,
        $fechaMonitoreo,
        $idDepartamento,
        $tipoFormulario,
        $descripcion,
        $servicioOrden
    );

    if ($stmt->execute()) {
        $idTG = $stmt->insert_id;
        $stmt->close();

        // Generar el correlativo numérico
        if ($idTG < 10) { $idRecepcion2 = "000" . $idTG; }
        else if ($idTG < 100) { $idRecepcion2 = "00" . $idTG; }
        else if ($idTG < 1000) { $idRecepcion2 = "0" . $idTG; }
        else { $idRecepcion2 = $idTG; }

        // Mapeo dinámico de prefijos por departamento
        $departamentos_str = [
            1 => 'I', 13 => 'I', 2 => 'EI', 3 => 'CLIMA', 4 => 'E',
            5 => 'INFRA', 6 => 'EQMED', 7 => 'LOG', 8 => 'MOV',
            10 => 'EDAC', 12 => 'INCEDIO'
        ];
        $str = $departamentos_str[$idDepartamento] ?? 'SDO';
        $folioTG = 'OT-' . $str . '-' . $idRecepcion2;

        // CONSULTA PREPARADA 2: Actualizar el Folio real de la OT
        $stmtUpdate = $conexion->prepare("UPDATE trabajos_general SET folioTG = ? WHERE idTG = ?");
        $stmtUpdate->bind_param("si", $folioTG, $idTG);
        $stmtUpdate->execute();
        $stmtUpdate->close();

        $detalleObs = 'Se ha ingresado la orden de trabajo ' . $folioTG . ' y se ha asignado a ' . $personaAsignada . ' para realizar el trabajo';

        // CONSULTA PREPARADA 3: Línea de vida de la orden
        $stmtAvance = $conexion->prepare("INSERT INTO linea_vida_ot (idLineaVida, nomCreador, detalle, fechaSubida, idTG) VALUES (NULL, ?, ?, ?, ?)");
        $stmtAvance->bind_param("sssi", $creadorOT, $detalleObs, $now, $idTG);
        $stmtAvance->execute();
        $stmtAvance->close();

        echo json_encode(["status" => "success", "message" => "La orden de monitoreo " . $folioTG . " fue generada e indexada correctamente."]);
    } else {
        $errorMysql = $stmt->error;
        $stmt->close();

        // Simulación exacta para el log en caso de que ocurra otro tipo de error (ej: restricciones de tipos o longitud)
        $sqlSimulada = "INSERT INTO trabajos_general (idTG, folioTG, solicitanteTG, detalleTG, detalleTurnoCamara, detalleTurnoLLE, detalleTurnoCA, detalleTurnoRondas, detalleOtros, fechaTG, estadoTG, turnoTG, tecnico1, fechaProgramacion, ticketAsociado, tipoTrabajo, departamento, formularioRondas, descripcion, servicio) VALUES (NULL, '$folio', '$creadorOT', '$detalleTurno', '$detalleTurnoCamara', '$detalleTurnoLLE', '$detalleTurnoCA', '$detalleTurnoRondas', '$detalleOtros', '$now', '$estado', '$turnoAsignado2', '$personaAsignada', '$fechaMonitoreo', 0, '', $idDepartamento, $tipoFormulario, '$descripcion', $servicioOrden)";

        echo json_encode([
            "status" => "error",
            "message" => "Fallo de ejecución al insertar la OT en la base de datos.",
            "mysql_error" => $errorMysql,
            "sql_debug" => $sqlSimulada
        ]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Faltan variables mandatorias en la petición POST."]);
}
exit();