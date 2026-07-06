<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

// Asegurar respuesta estrictamente JSON
header('Content-Type: application/json; charset=utf-8');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$response = ['status' => 'error', 'message' => 'Acceso no autorizado o datos incompletos.'];

// Validación de sesión
if (!isset($_SESSION['idUsuario'])) {
    $response['message'] = 'Tu sesión ha expirado. Por favor, vuelve a iniciar sesión.';
    echo json_encode($response);
    exit;
}

$nomUsuario = $_SESSION['nomUsuario'];
$rolUsuario = strtolower($_SESSION['rolUsuario'] ?? '');

// Mapeo de rutas (por compatibilidad si necesitas redirigir en otros flujos, aunque aquí usamos JSON)
$rolesRutas = [
    "personal"        => "sdo-personal",
    "administrativo"  => "sdo-admtec",
    "bodega pañol"    => "sdo-bodega-panol",
    "jefe tecnico"    => "sdo-jefestecnicos",
    "administrador"   => "sdo-administrador",
    "directiva"       => "sdo-directiva",
    "oficina tecnica" => "sdo-oficinaTecnica"
];

if (!array_key_exists($rolUsuario, $rolesRutas)) {
    $response['message'] = 'No tienes permisos suficientes para modificar órdenes de trabajo.';
    echo json_encode($response);
    exit;
}

// Validar petición POST y existencia del ID
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['idTG'])) {

    // Saneamiento de variables
    $idTG               = (int)$_POST['idTG'];
    $folioTG            = trim($_POST['folioTG'] ?? '');
    $descripcion        = trim($_POST['descripcion'] ?? '');
    $personaSolicitante = trim($_POST['personaSolicitante'] ?? $nomUsuario);
    $fechaProgramado    = trim($_POST['fechaProgramado'] ?? date('Y-m-d'));
    $ticketAsociado     = trim($_POST['ticketAsociado'] ?? '');
    $tipoTrabajo        = trim($_POST['tipoTrabajo'] ?? '');
    $turno              = trim($_POST['turno'] ?? 'Personal Diurno');

    // Determinar qué técnico y departamento se va a guardar
    // Si se cambió el departamento en el select, usamos el nuevo y el técnico seleccionado en 'tecnicoRecepcionador' o 'tecnico1'
    $departamentoActual = isset($_POST['departamentosecreto']) ? (int)$_POST['departamentosecreto'] : 0;
    $tecnicoAsignado    = !empty($_POST['tecnicoRecepcionador']) ? trim($_POST['tecnicoRecepcionador']) : trim($_POST['idTecnico1'] ?? '');

    // Si el select de cambio de técnico mandó datos por $_POST['tecnico1'] (el select dinámico), lo priorizamos
    if (!empty($_POST['tecnico1']) && $_POST['tecnico1'] !== 'null') {
        $tecnicoAsignado = trim($_POST['tecnico1']);
    }

    $now = date('Y-m-d H:i:s');

    if (empty($idTG) || empty($folioTG)) {
        $response['message'] = 'Falta el identificador de la Orden o el Folio.';
        echo json_encode($response);
        exit;
    }

    // =========================================================
    // 1. ACTUALIZACIÓN SEGÚN EL DEPARTAMENTO (PREPARED STATEMENTS)
    // =========================================================
    if ($departamentoActual === 1) {
        // Departamento de Instrumentación / Control (Mapeo de las múltiples cajas de texto)
        $detalleTurnoBMS    = trim($_POST['detalleTurnoBMS'] ?? '');
        $detalleTurnoCCTV   = trim($_POST['detalleTurnoCCTV'] ?? '');
        $detalleTurnoLLE    = trim($_POST['detalleTurnoLLE'] ?? '');
        $detalleTurnoCA     = trim($_POST['detalleTurnoCA'] ?? '');
        $detalleTurnoRondas = trim($_POST['detalleTurnoRondas'] ?? '');
        $detalleOtros       = trim($_POST['detalleOtros'] ?? '');

        $sqlUp = "UPDATE trabajos_general SET 
                    descripcion = ?, detalleTG = ?, detalleTurnoCamara = ?, 
                    detalleTurnoLLE = ?, detalleTurnoCA = ?, detalleTurnoRondas = ?, 
                    detalleOtros = ?, fechaProgramacion = ?, ticketAsociado = ?, 
                    tipoTrabajo = ?, solicitanteTG = ?, turnoTG = ?, 
                    tecnico1 = ?, departamento = ? 
                  WHERE idTG = ?";

        $stmt = mysqli_prepare($conexion, $sqlUp);
        mysqli_stmt_bind_param($stmt, "sssssssssssssii",
            $descripcion, $detalleTurnoBMS, $detalleTurnoCCTV,
            $detalleTurnoLLE, $detalleTurnoCA, $detalleTurnoRondas,
            $detalleOtros, $fechaProgramado, $ticketAsociado,
            $tipoTrabajo, $personaSolicitante, $turno,
            $tecnicoAsignado, $departamentoActual, $idTG
        );
    } else {
        // Otros departamentos (Usan la caja de texto general 'detalleTurnoNoControl')
        $detalleGeneral = trim($_POST['detalleTurnoNoControl'] ?? '');

        $sqlUp = "UPDATE trabajos_general SET 
                    descripcion = ?, detalleTG = ?, fechaProgramacion = ?, 
                    ticketAsociado = ?, tipoTrabajo = ?, solicitanteTG = ?, 
                    turnoTG = ?, tecnico1 = ? 
                  WHERE idTG = ?";

        $stmt = mysqli_prepare($conexion, $sqlUp);
        mysqli_stmt_bind_param($stmt, "ssssssssi",
            $descripcion, $detalleGeneral, $fechaProgramado,
            $ticketAsociado, $tipoTrabajo, $personaSolicitante,
            $turno, $tecnicoAsignado, $idTG
        );
    }

    $exitoUpdate = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($exitoUpdate) {
        // =========================================================
        // 2. REGISTRAR EL AVANCE / HISTORIAL DE MODIFICACIÓN
        // =========================================================
        $detalleObs = "Se ha modificado la orden de trabajo {$folioTG} y se asignó/confirmó a {$tecnicoAsignado}.";
        $estadoAvance = "Trabajo Modificado";

        $sqlAvance = "INSERT INTO avances_trabajo_general (idAvance, nomCreador, detalleObs, estadoAvance, fechaSubida, idTG, personaDerivada) 
                      VALUES (NULL, ?, ?, ?, ?, ?, NULL)";
        if ($stmtAvance = mysqli_prepare($conexion, $sqlAvance)) {
            mysqli_stmt_bind_param($stmtAvance, "ssssi", $personaSolicitante, $detalleObs, $estadoAvance, $now, $idTG);
            mysqli_stmt_execute($stmtAvance);
            mysqli_stmt_close($stmtAvance);
        }

        // También lo registramos en la línea de vida si tu flujo lo requiere
        $stmtLinea = mysqli_prepare($conexion, "INSERT INTO linea_vida_ot (idLineaVida, nomCreador, detalle, fechaSubida, idTG) VALUES (NULL, ?, ?, ?, ?)");
        if ($stmtLinea) {
            mysqli_stmt_bind_param($stmtLinea, "sssi", $personaSolicitante, $detalleObs, $now, $idTG);
            mysqli_stmt_execute($stmtLinea);
            mysqli_stmt_close($stmtLinea);
        }

        // =========================================================
        // 3. SUBIDA DE ARCHIVOS ADJUNTOS (OPCIONAL)
        // =========================================================
        $errorArchivos = "";
        if (!empty($_FILES['archivoAdjunto']['name']) && $_FILES['archivoAdjunto']['error'] === UPLOAD_ERR_OK) {
            // Estandarizado a tu carpeta moderna
            $directorioOT = "../sdo-archivosOT/" . $folioTG . "/";
            if (!file_exists($directorioOT)) {
                mkdir($directorioOT, 0777, true);
            }

            $tmp_name  = $_FILES["archivoAdjunto"]["tmp_name"];
            $name      = $_FILES["archivoAdjunto"]["name"];
            $extImagen = mb_strtolower(pathinfo($name, PATHINFO_EXTENSION));
            $rutaFinal = $directorioOT . $name;

            $extensionesPermitidas = ["docx", "doc", "xlsx", "xls", "pdf", "jpg", "png", "jpeg", "bmp", "tif", "webp", "svg", "jfif"];

            if (in_array($extImagen, $extensionesPermitidas)) {
                if (!file_exists($rutaFinal)) {
                    if (move_uploaded_file($tmp_name, $rutaFinal)) {
                        $sqlArchivo = "INSERT INTO Archivos_Trabajo_General (idArchivo, nomArchivo, rutaArchivo, extArchivo, fechaSubida, idTG) 
                                       VALUES (NULL, ?, ?, ?, ?, ?)";
                        if ($stmtFile = mysqli_prepare($conexion, $sqlArchivo)) {
                            mysqli_stmt_bind_param($stmtFile, "ssssi", $name, $rutaFinal, $extImagen, $now, $idTG);
                            mysqli_stmt_execute($stmtFile);
                            mysqli_stmt_close($stmtFile);
                        }
                    } else {
                        $errorArchivos = " No se pudo mover el archivo adjunto.";
                    }
                } else {
                    $errorArchivos = " El archivo adjunto ya existe en el servidor.";
                }
            } else {
                $errorArchivos = " Formato de archivo no permitido (.{$extImagen}).";
            }
        }

        // Respuesta final
        $response['status']  = 'success';
        $response['message'] = "La Orden de Trabajo {$folioTG} fue modificada correctamente." . $errorArchivos;

    } else {
        $response['message'] = 'Ocurrió un error en la base de datos al intentar actualizar la OT.';
    }
}

echo json_encode($response);
exit;