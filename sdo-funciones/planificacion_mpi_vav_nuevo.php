<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');
$now = date('Y-m-d H:i:s');

if (
    !isset($_SESSION['userUsuario'], $_SESSION['idUsuario'], $_SESSION['nomUsuario'], $_SESSION['rolUsuario'])
) {
    echo json_encode([
        'status' => 'error',
        'message' => 'La sesión ha expirado. Vuelva a iniciar sesión.'
    ]);
    exit();
}

$userUsuario = $_SESSION['userUsuario'];
$idUsuario = (int)$_SESSION['idUsuario'];
$nomUsuario = trim((string)$_SESSION['nomUsuario']);
$rolUsuario = strtolower(trim((string)$_SESSION['rolUsuario']));

switch ($rolUsuario) {
    case 'personal':
    case 'administrativo':
    case 'bodega pañol':
    case 'jefe tecnico':
    case 'administrador':
    case 'directiva':
    case 'oficina tecnica':
        break;
    default:
        echo json_encode([
            'status' => 'error',
            'message' => 'Rol de usuario no válido.'
        ]);
        exit();
}

function responderError($mensaje)
{
    echo json_encode([
        'status' => 'error',
        'message' => $mensaje
    ]);
    exit();
}

function obtenerNombreTurnoTG($turnoAsignado)
{
    if ((string)$turnoAsignado === '1') {
        return 'Personal Diurno';
    }

    if ((string)$turnoAsignado === '2') {
        return 'Personal Nocturno';
    }

    return '';
}

function obtenerSiglaDepartamento($deptoCreadorTareaP)
{
    if ($deptoCreadorTareaP == 1 || $deptoCreadorTareaP == 13) {
        return 'I';
    } elseif ($deptoCreadorTareaP == 2) {
        return 'EI';
    } elseif ($deptoCreadorTareaP == 3) {
        return 'CLIMA';
    } elseif ($deptoCreadorTareaP == 4) {
        return 'E';
    } elseif ($deptoCreadorTareaP == 5) {
        return 'INFRA';
    } elseif ($deptoCreadorTareaP == 6) {
        return 'EQMED';
    } elseif ($deptoCreadorTareaP == 7) {
        return 'LOG';
    } elseif ($deptoCreadorTareaP == 8) {
        return 'MOV';
    } elseif ($deptoCreadorTareaP == 10) {
        return 'EDAC';
    } elseif ($deptoCreadorTareaP == 12) {
        return 'INCEDIO';
    }

    return 'SDO';
}

function formatearCorrelativoOT($idTG)
{
    if ($idTG < 10) {
        return '000' . $idTG;
    } elseif ($idTG >= 10 && $idTG < 100) {
        return '00' . $idTG;
    } elseif ($idTG >= 100 && $idTG < 1000) {
        return '0' . $idTG;
    }

    return (string)$idTG;
}

$idEquipo = isset($_POST['idEquipo']) ? (int)$_POST['idEquipo'] : 0;
$nombreEquipo = trim((string)($_POST['nombreEquipo'] ?? ''));
$tituloTarea = trim((string)($_POST['tituloTareaP'] ?? ''));
$turnoAsignado = trim((string)($_POST['turnoAsignado'] ?? ''));
$funcionarioAsignado = trim((string)($_POST['funcionarioMPIVAV'] ?? ''));
$fechaInicio = trim((string)($_POST['inicioPlanTareaP'] ?? ''));
//$observacion = trim((string)($_POST['observacion'] ?? ''));
//$nomCreadorTareaP = trim((string)($_POST['nomCreadorTareaP'] ?? 'Jorge Cantillano'));
$nomCreadorTareaP = "Yorgo Casimis Subiabre";
$deptoCreadorTareaP = isset($_POST['deptoCreadorTareaP']) ? (int)$_POST['deptoCreadorTareaP'] : 1;
$esEquipamiento = isset($_POST['esEquipamiento']) ? (int)$_POST['esEquipamiento'] : 0;

if ($idEquipo <= 0) {
    responderError('Equipo no válido.');
}

if ($tituloTarea === '') {
    responderError('Debe ingresar el título de la tarea.');
}

if ($turnoAsignado === '') {
    responderError('Debe seleccionar un turno.');
}

if ($funcionarioAsignado === '') {
    responderError('Debe seleccionar un funcionario.');
}

if ($fechaInicio === '') {
    responderError('Debe seleccionar la fecha de planificación.');
}

if ($turnoAsignado !== '1' && $turnoAsignado !== '2') {
    responderError('El turno seleccionado no es válido.');
}

if ($esEquipamiento !== 1) {
    responderError('Los datos del equipamiento no son válidos.');
}

$turnoAsignado2 = obtenerNombreTurnoTG($turnoAsignado);

if ($turnoAsignado2 === '') {
    responderError('No fue posible determinar el turno.');
}

$tipoTrabajo = 'MPI';
$idTareaP = 0;
$campoUpdate = 'detalleTG';

$_SESSION['idEquipo'] = $idEquipo;

if ($turnoAsignado === '1') {
    $finPlanTareaP = date('Y-m-d', strtotime($fechaInicio));
} elseif ($turnoAsignado === '2') {
    $finPlanTareaP = date('Y-m-d', strtotime($fechaInicio . ' + 1 day'));
} else {
    responderError('Turno no permitido.');
}

$feriadosChile = obtenerFeriadosChile();
$cantidadDiasTarea = obtenerDiasCorridos($fechaInicio, $finPlanTareaP, $feriadosChile);

mysqli_begin_transaction($conexion);

try {
    $sqlPlan = "
        INSERT INTO tareas_carta_gantt
        (
            idTareaP,
            tituloTarea,
            actividadTareaP,
            inicioPlanTareaP,
            diasPlanTareaP,
            tipoDiasHabil,
            finPlanTareaP,
            inicioRealTareaP,
            finRealTareaP,
            porcentajeTareaP,
            idPadreTareaP,
            iniciadaTareaP,
            finalizadaTareaP,
            nomCreadorTareaP,
            deptoCreadorTareaP,
            idEquipamiento,
            funcionariosSeleccionados
        )
        VALUES
        (
            NULL,
            ?,
            ?,
            ?,
            ?,
            'Inhabiles',
            ?,
            NULL,
            NULL,
            0,
            ?,
            0,
            0,
            ?,
            ?,
            ?,
            ?
        )
    ";

    $stmtPlan = mysqli_prepare($conexion, $sqlPlan);

    if (!$stmtPlan) {
        throw new Exception('No se pudo preparar la planificación.');
    }

    mysqli_stmt_bind_param(
        $stmtPlan,
        'sssisisiis',
        $tituloTarea,
        $tituloTarea,
        $fechaInicio,
        $cantidadDiasTarea,
        $finPlanTareaP,
        $idTareaP,
        $nomCreadorTareaP,
        $deptoCreadorTareaP,
        $idEquipo,
        $funcionarioAsignado
    );

    if (!mysqli_stmt_execute($stmtPlan)) {
        throw new Exception('No se pudo crear la tarea programada: ' . mysqli_stmt_error($stmtPlan));
    }

    $ultimoID = mysqli_insert_id($conexion);
    $_SESSION['idTareaP'] = $ultimoID;

    $folio = 'HBQP';
    $estado = 'Trabajo Ingresado';

    $servicioOrden = obtenerIDServicioControl();
    if ($servicioOrden <= 0) {
        $servicioOrden = 0;
    }

    $descripcionTG = $tituloTarea;
//    if ($observacion !== '') {
//        $descripcionTG .= ' | Observación: ' . $observacion;
//    }

    $sqlTG = "
        INSERT INTO trabajos_general
        (
            idTG,
            folioTG,
            solicitanteTG,
            detalleTG,
            detalleTurnoCamara,
            detalleTurnoLLE,
            detalleTurnoCA,
            detalleTurnoRondas,
            detalleOtros,
            fechaTG,
            estadoTG,
            turnoTG,
            tecnico1,
            fechaProgramacion,
            ticketAsociado,
            tipoTrabajo,
            departamento,
            formularioRondas,
            descripcion,
            servicio,
            idTareaProgramada,
            idEquipamiento
        )
        VALUES
        (
            NULL,
            ?,
            ?,
            '',
            '',
            '',
            '',
            '',
            '',
            ?,
            ?,
            ?,
            ?,
            ?,
            0,
            ?,
            ?,
            0,
            ?,
            ?,
            ?,
            ?
        )
    ";

    $stmtTG = mysqli_prepare($conexion, $sqlTG);

    if (!$stmtTG) {
        throw new Exception('No se pudo preparar la orden de trabajo.');
    }

    mysqli_stmt_bind_param(
        $stmtTG,
        'ssssssssisiii',
        $folio,
        $nomCreadorTareaP,
        $now,
        $estado,
        $turnoAsignado2,
        $funcionarioAsignado,
        $fechaInicio,
        $tipoTrabajo,
        $deptoCreadorTareaP,
        $descripcionTG,
        $servicioOrden,
        $ultimoID,
        $idEquipo
    );

    if (!mysqli_stmt_execute($stmtTG)) {
        throw new Exception('No se pudo crear la orden de trabajo: ' . mysqli_stmt_error($stmtTG));
    }

    $idTG = mysqli_insert_id($conexion);

    if ($idTG <= 0) {
        throw new Exception('No se obtuvo el ID de la orden de trabajo.');
    }

    $checkListSeleccionado = 'VAV';
    $cantidadCheck1 = 1;

    $sqlChecklist = "
        INSERT INTO tipos_check_list
        (
            idTipoChecklist,
            tipo,
            cantidad,
            idTG
        )
        VALUES
        (
            NULL,
            ?,
            ?,
            ?
        )
    ";

    $stmtChecklist = mysqli_prepare($conexion, $sqlChecklist);

    if (!$stmtChecklist) {
        throw new Exception('No se pudo preparar el checklist.');
    }

    mysqli_stmt_bind_param(
        $stmtChecklist,
        'sii',
        $checkListSeleccionado,
        $cantidadCheck1,
        $idTG
    );

    if (!mysqli_stmt_execute($stmtChecklist)) {
        throw new Exception('No se pudo crear el checklist: ' . mysqli_stmt_error($stmtChecklist));
    }


    $str = obtenerSiglaDepartamento($deptoCreadorTareaP);
    $idRecepcion2 = formatearCorrelativoOT($idTG);
    $folioTG = 'OT-' . $str . '-' . $idRecepcion2;

    $sqlUpdate = "UPDATE trabajos_general SET folioTG = ?, $campoUpdate = ? WHERE idTG = ?";
    $stmtUpdate = mysqli_prepare($conexion, $sqlUpdate);

    if (!$stmtUpdate) {
        throw new Exception('No se pudo actualizar el folio de la OT.');
    }

    mysqli_stmt_bind_param($stmtUpdate, 'ssi', $folioTG, $tituloTarea, $idTG);

    if (!mysqli_stmt_execute($stmtUpdate)) {
        throw new Exception('No se pudo actualizar el detalle de la OT: ' . mysqli_stmt_error($stmtUpdate));
    }

    $detalleObs = 'Se ha ingresado la orden de trabajo ' . $folioTG . ' y se ha asignado a ' . $funcionarioAsignado . ' para realizar el trabajo';

//    if ($observacion !== '') {
//        $detalleObs .= '. Observación: ' . $observacion;
//    }

    $sqlAvance = "
        INSERT INTO linea_vida_ot
        (
            idLineaVida,
            nomCreador,
            detalle,
            fechaSubida,
            idTG
        )
        VALUES
        (
            NULL,
            ?,
            ?,
            ?,
            ?
        )
    ";

    $stmtAvance = mysqli_prepare($conexion, $sqlAvance);

    if (!$stmtAvance) {
        throw new Exception('No se pudo preparar la línea de vida.');
    }

    mysqli_stmt_bind_param(
        $stmtAvance,
        'sssi',
        $nomCreadorTareaP,
        $detalleObs,
        $now,
        $idTG
    );

    if (!mysqli_stmt_execute($stmtAvance)) {
        throw new Exception('No se pudo registrar la línea de vida: ' . mysqli_stmt_error($stmtAvance));
    }

    mysqli_commit($conexion);

    echo json_encode([
        'status' => 'success',
        'message' => 'La MPI fue agregada correctamente.',
        'idTG' => $idTG,
        'folioTG' => $folioTG,
        'idTareaProgramada' => $ultimoID
    ]);
    exit();
} catch (Exception $e) {
    mysqli_rollback($conexion);

    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
    exit();
}
?>