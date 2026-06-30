<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

// Asegurar que la respuesta sea estrictamente JSON
header('Content-Type: application/json; charset=utf-8');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Inicializar respuesta por defecto
$response = ['status' => 'error', 'message' => 'Acceso no autorizado o solicitud vacía.'];

// Verificar sesión activa
if (!isset($_SESSION['idUsuario'])) {
    $response['message'] = 'Sesión expirada. Por favor, vuelva a iniciar sesión.';
    echo json_encode($response);
    exit;
}

$idUsuario = $_SESSION['idUsuario'];
$idDepartamentoSesion = $_SESSION['idDepartamento'] ?? null;
$nomUsuario = $_SESSION['nomUsuario'];
$rolUsuario = strtolower($_SESSION['rolUsuario'] ?? '');
$rutaDireccion = "";

// Mapeo de rutas por rol
$rolesRutas = [
    "personal"        => "sdo-personal",
    "administrativo"  => "sdo-admtec",
    "bodega pañol"    => "sdo-bodega-panol",
    "jefe tecnico"    => "sdo-jefestecnicos",
    "administrador"   => "sdo-administrador",
    "directiva"       => "sdo-directiva",
    "oficina tecnica" => "sdo-oficinaTecnica"
];

if (array_key_exists($rolUsuario, $rolesRutas)) {
    $rutaDireccion = $rolesRutas[$rolUsuario];
} else {
    $response['message'] = 'Su rol no tiene permisos para ejecutar esta acción.';
    echo json_encode($response);
    exit;
}

// Validar que se reciban los datos mínimos requeridos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['descripcion'])) {

    // Captura y saneamiento básico de datos comunes
    $personaSolicitante      = $_POST['personaSolicitante'] ?? $nomUsuario;
    $turno                   = $_POST['turno'] ?? '';
    $tecnico1                = !empty($_POST['tecnico1']) ? $_POST['tecnico1'] : '';
    $fechaProgramado         = $_POST['fechaProgramado'] ?? date('Y-m-d');
    $ticketAsociado          = !empty($_POST['ticketAsociado']) ? $_POST['ticketAsociado'] : '';
    $tipoTrabajo             = $_POST['tipoTrabajo'] ?? '';
    $departamentoSolicitante = isset($_POST['departamentoSolicitante']) ? (int)$_POST['departamentoSolicitante'] : 0;
    $formularioRondas        = !empty($_POST['formularioRondas']) ? (int)$_POST['formularioRondas'] : 0;
    $descripcion             = $_POST['descripcion'] ?? '';
    $idRecepcionArray        = json_decode($_POST['idRecepcionArrayInput'] ?? '', true);

    // Configurar ID del departamento prioritario
    $idDepartamento = $departamentoSolicitante;

    $folioDefault = 'HBQP';
    $fechaOrden   = date('Y-m-d H:i:s');
    $estado       = 'Trabajo Ingresado';

    // Inicializar variables de detalles según el Departamento
    $detalle_bms    = "";
    $detalle_cctv   = "";
    $detalle_lle    = "";
    $detalle_acceso = "";
    $detalle_rondas = "";
    $detalle_otros  = "";

    if ($idDepartamento === 1) {
        // Cargar campos específicos mapeados del formulario de Instrumentación
        $detalle_bms    = trim($_POST['detalle_bms'] ?? '');
        $detalle_cctv   = trim($_POST['detalle_cctv'] ?? '');
        $detalle_lle    = trim($_POST['detalle_lle'] ?? '');
        $detalle_acceso = trim($_POST['detalle_acceso'] ?? '');
        $detalle_rondas = trim($_POST['detalle_rondas'] ?? '');
        $detalle_otros  = trim($_POST['detalle_otros'] ?? '');
    } else {
        // Para cualquier otro departamento, la descripción unificada va al detalle general antiguo
        $detalle_bms    = trim($_POST['detalle_general'] ?? '');
    }

    // === PROCESAMIENTO DEL SERVICIO O UNIDAD ===
    $servicioOrden = 0;
    if (isset($_POST['servicios'])) {
        $servicioInput = trim($_POST['servicios']);

        // Si el servicio es un número de ID existente
        if (is_numeric($servicioInput)) {
            $servicioOrden = (int)$servicioInput;
        } else if (!empty($servicioInput) && strtolower($servicioInput) !== 'otro') {
            // Si el usuario escribió un servicio nuevo, buscar si existe o crearlo de forma segura
            $servicioOrden = buscarServicio($servicioInput); // Asumiendo que tu función interna maneja prepared statements, si no, se inserta abajo:

            if ($servicioOrden == 0) {
                $stmtServ = mysqli_prepare($conexion, "INSERT INTO servicios (idServicio, nomServicio) VALUES (NULL, ?)");
                mysqli_stmt_bind_param($stmtServ, "s", $servicioInput);
                if (mysqli_stmt_execute($stmtServ)) {
                    $servicioOrden = mysqli_insert_id($conexion);
                }
                mysqli_stmt_close($stmtServ);
            }
        }
    }

    // =========================================================
    // 1. INSERCIÓN PRINCIPAL DE LA ORDEN DE TRABAJO (PREPARED STATEMENT)
    // =========================================================
    $sqlTG = "INSERT INTO trabajos_general (
                idTG, folioTG, solicitanteTG, detalleTG, detalleTurnoCamara, 
                detalleTurnoLLE, detalleTurnoCA, detalleTurnoRondas, detalleOtros, fechaTG, 
                estadoTG, turnoTG, tecnico1, fechaProgramacion, ticketAsociado, 
                tipoTrabajo, departamento, formularioRondas, descripcion, servicio 
              ) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmtTG = mysqli_prepare($conexion, $sqlTG)) {

        // Cadena corregida: 15 strings ('s'), 2 enteros ('i'), 1 string ('s'), 1 entero ('i')
        mysqli_stmt_bind_param($stmtTG, "sssssssssssssssiisi",
            $folioDefault,          // 1. s
            $personaSolicitante,    // 2. s
            $detalle_bms,           // 3. s
            $detalle_cctv,          // 4. s
            $detalle_lle,           // 5. s
            $detalle_acceso,        // 6. s
            $detalle_rondas,        // 7. s
            $detalle_otros,         // 8. s
            $fechaOrden,            // 9. s
            $estado,                // 10. s
            $turno,                 // 11. s
            $tecnico1,              // 12. s
            $fechaProgramado,       // 13. s
            $ticketAsociado,        // 14. s ("SS-T-67134" -> String)
            $tipoTrabajo,           // 15. s ("MRI" -> String)
            $idDepartamento,        // 16. i (ID numérico)
            $formularioRondas,      // 17. i (ID numérico)
            $descripcion,           // 18. s (Texto extenso)
            $servicioOrden          // 19. i (ID numérico)
        );

        if (mysqli_stmt_execute($stmtTG)) {
            $idTG = mysqli_insert_id($conexion);

            // =========================================================
            // 2. CONSTRUCCIÓN DEL FOLIO DINÁMICO SEGÚN DEPARTAMENTO
            // =========================================================
            $idPad = str_pad($idTG, 4, "0", STR_PAD_LEFT); // Reemplaza los IF repetitivos de ceros a la izquierda

            $prefijosDepa = [
                1 => 'I', 2 => 'EI', 3 => 'CLIMA', 4 => 'E', 5 => 'INFRA',
                6 => 'EQMED', 7 => 'LOG', 8 => 'MOV', 10 => 'EDAC', 12 => 'INCEDIO'
            ];
            $str = $prefijosDepa[$idDepartamento] ?? 'SDO';
            $folioTG = 'OT-' . $str . '-' . $idPad;

            // Actualizar Folio Real
            $stmtUp = mysqli_prepare($conexion, "UPDATE trabajos_general SET folioTG = ? WHERE idTG = ?");
            mysqli_stmt_bind_param($stmtUp, "si", $folioTG, $idTG);
            mysqli_stmt_execute($stmtUp);
            mysqli_stmt_close($stmtUp);

            // =========================================================
            // 3. INSERCIÓN EN LÍNEA DE VIDA
            // =========================================================
            $detalleObs = 'Se ha ingresado la orden de trabajo ' . $folioTG . ' y se ha asignado a ' . $tecnico1 . ' para realizar el trabajo.';
            $stmtLine = mysqli_prepare($conexion, "INSERT INTO linea_vida_ot (idLineaVida, nomCreador, detalle, fechaSubida, idTG) VALUES (NULL, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmtLine, "sssi", $personaSolicitante, $detalleObs, $fechaOrden, $idTG);
            mysqli_stmt_execute($stmtLine);
            mysqli_stmt_close($stmtLine);

            // =========================================================
            // 4. PROCESAR CHECKLISTS (SÓLO INSTRUMENTACIÓN ID = 1)
            // =========================================================
            if ($idDepartamento === 1 && isset($_POST['checklist'], $_POST['cantidad'])) {
                $checklistSeleccionado = $_POST['checklist'];
                $cantidadCheck = $_POST['cantidad'];

                // Si viene un valor único de texto/número de los selects simples, los convertimos a arrays
                if (!is_array($checklistSeleccionado)) { $checklistSeleccionado = [$checklistSeleccionado]; }
                if (!is_array($cantidadCheck)) { $cantidadCheck = [$cantidadCheck]; }

                $stmtCheck = mysqli_prepare($conexion, "INSERT INTO `tipos_check_list`(`idTipoChecklist`, `tipo`, `cantidad`, `idTG`) VALUES (NULL, ?, ?, ?)");

                for ($i = 0; $i < count($checklistSeleccionado); $i++) {
                    if (!empty($checklistSeleccionado[$i]) && (int)$cantidadCheck[$i] > 0) {
                        $cNom = $checklistSeleccionado[$i];
                        $cCant = (int)$cantidadCheck[$i];
                        mysqli_stmt_bind_param($stmtCheck, "sii", $cNom, $cCant, $idTG);
                        mysqli_stmt_execute($stmtCheck);
                    }
                }
                mysqli_stmt_close($stmtCheck);
            }

            // =========================================================
            // 5. ASOCIACIÓN DE ARCHIVOS DE RECEPCIÓN PREVIOS
            // =========================================================
            if (is_array($idRecepcionArray) && !empty($idRecepcionArray)) {
                $stmtDoc = mysqli_prepare($conexion, "UPDATE `archivos_recepcion_documento` SET idTG = ? WHERE idArchivosDocu = ?");
                foreach ($idRecepcionArray as $idRecepcion) {
                    $idRecInt = (int)$idRecepcion;
                    mysqli_stmt_bind_param($stmtDoc, "ii", $idTG, $idRecInt);
                    mysqli_stmt_execute($stmtDoc);
                }
                mysqli_stmt_close($stmtDoc);
            }

            // =========================================================
            // 6. PROCESAMIENTO DE ARCHIVOS ADJUNTOS DE TRABAJO GENERAL
            // =========================================================
            $archivosCargadosConExito = true;
            $mensajeArchivoError = "";

            if (!empty($_FILES['archivoAdjunto']['name'][0])) {
                $directorioBase = "../sdo-archivosOT/";
                $directorioOT = $directorioBase . $folioTG . "/";

                if (!file_exists($directorioOT)) {
                    mkdir($directorioOT, 0777, true);
                }

                $extensionesHabilitadas = ["docx", "doc", "xlsx", "xls", "ppt", "pptx", "pdf", "jpg", "png", "jpeg", "bmp", "tif", "webp", "svg", "jfif"];

                // Soporte nativo por si envías un archivo único o un array de múltiples archivos
                $fileNames = is_array($_FILES['archivoAdjunto']['name']) ? $_FILES['archivoAdjunto']['name'] : [$_FILES['archivoAdjunto']['name']];
                $fileTmpNames = is_array($_FILES['archivoAdjunto']['tmp_name']) ? $_FILES['archivoAdjunto']['tmp_name'] : [$_FILES['archivoAdjunto']['tmp_name']];

                $stmtFile = mysqli_prepare($conexion, "INSERT INTO Archivos_Trabajo_General (idArchivo, nomArchivo, rutaArchivo, extArchivo, fechaSubida, idTG) VALUES (NULL, ?, ?, ?, ?, ?)");

                for ($i = 0; $i < count($fileNames); $i++) {
                    $name = $fileNames[$i];
                    $tmp_name = $fileTmpNames[$i];

                    if (empty($tmp_name)) {
                        continue;
                    }

                    $rutaFinalArchivo = $directorioOT . $name;
                    $extImagen = mb_strtolower(pathinfo($name, PATHINFO_EXTENSION));

                    if (in_array($extImagen, $extensionesHabilitadas)) {
                        if (file_exists($rutaFinalArchivo)) {
                            $archivosCargadosConExito = false;
                            $mensajeArchivoError = "El archivo '{$name}' ya existe en el servidor.";
                            break;
                        } else {
                            if (move_uploaded_file($tmp_name, $rutaFinalArchivo)) {
                                $fechaSubidaNull = '0000-00-00 00:00:00';
                                mysqli_stmt_bind_param($stmtFile, "ssssi", $name, $rutaFinalArchivo, $extImagen, $fechaSubidaNull, $idTG);
                                mysqli_stmt_execute($stmtFile);
                            } else {
                                $archivosCargadosConExito = false;
                                $mensajeArchivoError = "No se pudo mover el archivo adjunto al directorio de destino.";
                                break;
                            }
                        }
                    } else {
                        $archivosCargadosConExito = false;
                        $mensajeArchivoError = "La extensión '.{$extImagen}' del archivo '{$name}' no está permitida.";
                        break;
                    }
                }
                mysqli_stmt_close($stmtFile);
            }

            // =========================================================
            // 7. RESPUESTA DE RETORNO FINAL
            // =========================================================
            if ($archivosCargadosConExito) {
                $response['status'] = 'success';
                $response['message'] = "La Orden de Trabajo {$folioTG} fue registrada exitosamente.";
            } else {
                $response['status'] = 'success'; // Se guardó la OT, pero falló el archivo adjunto
                $response['message'] = "La OT se creó pero hubo un detalle con los archivos: " . $mensajeArchivoError;
            }

        } else {
            $response['message'] = 'Error al ejecutar la inserción de la Orden de Trabajo.';
        }
        mysqli_stmt_close($stmtTG);
    } else {
        $response['message'] = 'Error de sintaxis al preparar la consulta SQL principal.';
    }
}

// Retornar respuesta codificada en JSON hacia el cliente AJAX
echo json_encode($response);
exit;