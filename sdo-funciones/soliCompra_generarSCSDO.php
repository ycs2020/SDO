<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$rolUsuario = strtolower(trim($_SESSION['rolUsuario']));
$nomCreador = $_SESSION['nomUsuario'];
$cargoUsuario = isset($_SESSION['cargoUsuario']) ? trim($_SESSION['cargoUsuario']) : '';

date_default_timezone_set('America/Santiago');

$idSolicitudSoporte = (int) ($_POST['idSolicitudSoporte'] ?? 0);

$rutas = [
    "personal" => "sdo-personal",
    "administrativo" => "sdo-admtec",
    "jefe tecnico" => "sdo-jefestecnicos",
    "directiva" => "sdo-directiva",
    "administrador" => "sdo-administrador",
    "mesa ayuda" => "sdo-mesa-ayuda",
    "bodega pañol" => "sdo-bodega-panol"
];

if (array_key_exists($rolUsuario, $rutas)) {
    $ruta = $rutas[$rolUsuario];

    // Verifica si el rol requiere cargoUsuario
    if (in_array($rolUsuario, ["directiva", "administrador"]) && empty($cargoUsuario)) {
        header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
        exit;
    }

    // Definir ruta de retorno para tickets si aplica
    if ($idSolicitudSoporte > 0) {
        switch ($rolUsuario) {
            case 'administrador':
                $rutaVueltaTicket = "tickets_solicitarCompras_sdo.php";
                break;
            case 'jefe tecnico':
                $rutaVueltaTicket = "tickets_solicitarCompras_sdo_jefes.php";
                break;
            default:
                $rutaVueltaTicket = "tickets_solicitarCompras_sdo.php"; // ruta genérica por defecto
                break;
        }
    }else{
        $rutaVueltaTicket = "soliCompra_sdo_administracion.php";
    }

} else {
    // Rol no reconocido
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    exit;
}

if (isset($_POST['cargaSolicitante'])) {

    $cargaSolicitante = $_POST['cargaSolicitante'];
    $cargaSolicitante = trim($cargaSolicitante);

    $isCotizacion = (int) $_POST['isCotizacion'];


    $idProfesional = '';

    $personalSC = '';
    $cargoSC = '';
    $deptoSC = '';
    $emailSC = '';

    $deptoParaFolio = '';


    if (!empty($cargaSolicitante)) {

        if ($cargaSolicitante == 'Jefe' || $cargaSolicitante == 'Directivo' || $cargaSolicitante == 'JefeCC' || $cargaSolicitante == 'PerfilEncargado') {

            // Inicializa variables
            $dataSolicitante = [];
            $idProfesional = '';

// Procesamiento según tipo de carga del solicitante
            switch ($cargaSolicitante) {

                case 'Jefe':
                    // Verificación de técnico recepcionador
                    if (!empty($_POST['nomTecnicoRecepcionador'])) {
                        $idProfesional = trim($_POST['nomTecnicoRecepcionador']);
                        $dataSolicitante = getDatosJefe($idProfesional);

                        // Excepción para ciertos nombres
                        if (in_array($personalSC, ['Alejandro Cantillano', 'Jorge Cantillano Sánchez'])) {
                            $personalSC = 'Alejandro Cantillano';
                            $cargoSC = 'Jefe de Control Centralizado';
                            $deptoSC = 'Control Centralizado';
                            $emailSC = 'jorge.cantillano@redsalud.gob.cl';
                        } else {
                            $personalSC = $dataSolicitante['nomJefe'] ?? '';
                            $cargoSC    = $dataSolicitante['cargoJefe'] ?? '';
                            $deptoSC    = getNomDepartamento($dataSolicitante['idDepartamento'] ?? '');
                            $emailSC    = $dataSolicitante['emailJefe'] ?? '';
                        }

                    } else {

                        header("Location: ../$ruta/$rutaVueltaTicket?errorCrearSC=true");
                        exit;
                    }
                    break;

                case 'Directivo':
                    if (!empty($_POST['directivo'])) {
                        $idProfesional = trim($_POST['directivo']);
                        $dataSolicitante = getDatosDirectivo($idProfesional);

                        $personalSC = $dataSolicitante['nomDirectivo'] ?? '';
                        $cargoSC    = $dataSolicitante['cargoDirectivo'] ?? '';
                        $deptoSC    = 'Ingeniería Operacional';
                        $emailSC    = $dataSolicitante['emailDirectivo'] ?? '';
                    } else {
                        header("Location: ../$ruta/$rutaVueltaTicket?errorCrearSC=true");
                    }
                    break;

                case 'JefeCC':
                    $personalSC = 'Jorge Cantillano Sánchez';
                    $cargoSC    = 'Jefe de Control Centralizado';
                    $deptoSC    = 'Control Centralizado';
                    $emailSC    = 'jorge.cantillano@redsalud.gob.cl';
                    break;

                case 'PerfilEncargado':
                    $personalSC = trim($_POST['personalSC'] ?? '');
                    $cargoSC    = trim($_POST['cargoSC'] ?? '');
                    $deptoSC    = trim($_POST['deptoSC'] ?? '');
                    $direccion  = $_POST['direccion'] ?? '';
                    $emailSC    = trim($_POST['emailSC'] ?? '');
                    break;

                default:
                    // Si el tipo no coincide con ninguno esperado
                    header("Location: ../$ruta/$rutaVueltaTicket?errorCrearSC=true");
                    exit;
            }
// Mapeo de nombres de departamento a sus siglas para el folio
            $mapaDeptos = [
                'Instrumentación' => ['nombre' => 'Control Centralizado', 'folio' => 'CC'],
                'Equipos Industriales' => ['folio' => 'EQINDUS'],
                'Inst. Térmicas, Climatización y Refrigeración' => ['folio' => 'CLIMA'],
                'Electricidad' => ['folio' => 'ELECT'],
                'Infraestructura' => ['folio' => 'INFRA'],
                'Equipos Médicos' => ['folio' => 'EQMED'],
                'Logística' => ['folio' => 'LOG'],
                'Movilización' => ['folio' => 'MOV']
            ];

// Verifica si el departamento está en el mapeo
            if (array_key_exists($deptoSC, $mapaDeptos)) {
                // Cambia el nombre si se especifica uno distinto
                if (isset($mapaDeptos[$deptoSC]['nombre'])) {
                    $deptoSC = $mapaDeptos[$deptoSC]['nombre'];
                }

                $deptoParaFolio = $mapaDeptos[$deptoSC]['folio'] ?? $mapaDeptos[$deptoSC]['folio'];
            } else {
                $deptoParaFolio = 'SDO';
            }

            $archivoCotizacion = '';


            $nomCreadorSC = $personalSC . ' (' . $cargoSC . ')';

            $descripcionSoliCompra = trim($_POST['descripcionSoliCompra']);

            $ticketSIGH = $_POST['ticketSIGH'];

            if(empty($ticketSIGH)){
                $ticketSIGH = 'No Aplica';
            }


            $idSubAsigna = 0;


            if (isset($_POST['argumentosNecesidad'])) {

                $argumentosNecesidad = $_POST['argumentosNecesidad'];

                if ($argumentosNecesidad == '' || $argumentosNecesidad == ' ') {
                    header("Location: ../$ruta/$rutaVueltaTicket?errorCrearSC=true");
                } else {
                    $argumentosNecesidad = str_replace("'", "\'", $argumentosNecesidad);
                }

                $now = date('Y-m-d H:i:s');
                $año = date('Y');

                // Obtener el último número de solicitud del año actual
                $selectAct = "SELECT numSoliCompra FROM solicitudes_compra_sdo WHERE YEAR(fechaSoliCompra) = $año ORDER BY fechaSoliCompra DESC LIMIT 1";
                $resAct = mysqli_query($conexion, $selectAct);

                // Si no hay solicitudes este año, empezamos con 1
                if ($resAct->num_rows == 0) {
                    $numSoliC = 1;
                } else {
                    $fila = $resAct->fetch_assoc();
                    $numSoliC = (int)$fila['numSoliCompra'] + 1;
                }

                // Mostrar el número de solicitud (opcional)
//                echo $numSoliC;

                // Formatear número con ceros a la izquierda (mínimo 4 dígitos)
                $numSoliC = str_pad($numSoliC, 4, "0", STR_PAD_LEFT);

                // Obtener iniciales
                $iniciales = getIniciales($personalSC);

                // Crear folio
                $folioSoliCompra = "SC-SDO-$año-$deptoParaFolio-$numSoliC-$iniciales";


                $codigoSC = ''; // Inicializas

                if (!empty($_POST['scSIGH'])) {
                    $codigoSC = $_POST['scSIGH'];
                }


                $querySC = "INSERT INTO solicitudes_compra_sdo(idSoliCompra,numSoliCompra,folioSoliCompra,descripcionSoliCompra,fechaSoliCompra,personalSoliCompra,unidadSoliCompra,emailSoliCompra,argumentosSoliCompra,adjuntaCotizacion,estadoSoliCompra, isCotizacion,idSolicitudSoporte,numTicketSIGH,idSubAsigna,scSigh)
                VALUES (NULL,$numSoliC,'$folioSoliCompra','".$descripcionSoliCompra."','" . $now . "','" . $nomCreadorSC . "','" . $deptoSC . "','" . $emailSC . "','" . $argumentosNecesidad . "','0','Solicitada a Secretaria SDO', $isCotizacion, $idSolicitudSoporte, '$ticketSIGH', $idSubAsigna,'$codigoSC')";

//                echo $querySC;
//                exit;


                $resSC = mysqli_query($conexion, $querySC);

                if ($resSC) {
                    $idSolicitudCompra = mysqli_insert_id($conexion);

                    // Escapar valores para mayor seguridad
                    $descripcion = mysqli_real_escape_string($conexion, $argumentosNecesidad);
                    $solicitante = mysqli_real_escape_string($conexion, $nomCreadorSC);
                    $unidad = mysqli_real_escape_string($conexion, $deptoSC);
                    $tipoActualizacion = "SOLICITADA A SECRETARIA SDO";

                    // Insertar en tabla de resumen
                    $queryResumen = "
                    INSERT INTO solicitudes_sc_sdo_resumen (
                        descripcion, fechaSoliCompra, solicitante, unidadSoliCompra, idSoliCompra
                    ) VALUES (
                        '$descripcion', '$now', '$solicitante', '$unidad', $idSolicitudCompra
                    )";
                    $resResumen = mysqli_query($conexion, $queryResumen);

                    // Insertar en seguimiento
                    $querySeguimiento = "
                    INSERT INTO seguimiento_sc_sdo (
                        tipoActualizacion, idSoliCompra
                    ) VALUES (
                        '$tipoActualizacion', $idSolicitudCompra
                    )";
                    $resSeguimiento = mysqli_query($conexion, $querySeguimiento);

                    // Actualizar situación de soporte
                    $querySituacion = "
                    UPDATE solicitudes_soportes
                    SET situacionSolicitud = 'Se encuentra con Solicitud de Compra'
                    WHERE idSolicitud = $idSolicitudSoporte";
                    $resSituacion = mysqli_query($conexion, $querySituacion);

                    // Insertar observación
                    $detalleObs = "Se ha generado una solicitud de compra con el número: $idSolicitudCompra";
                    $detalleObs = mysqli_real_escape_string($conexion, $detalleObs);
                    $nomObs = mysqli_real_escape_string($conexion, $nomCreadorSC);

                    $queryObservacion = "
                    INSERT INTO seguimiento_sc_sdo (
                        idObservaSolicitudes, obsPrivada, detalleObservaSolicitudes,
                        fechaObservaSolicitudes, tipoObservaSolicitudes, creadorObservaSolicitudes, idSolicitud
                    ) VALUES (
                        NULL, NULL, '$detalleObs', '$now', 'Creacion de Solicitud de Compra', '$nomObs', $idSolicitudSoporte
                    )";
                    $resObservacion = mysqli_query($conexion, $queryObservacion);

                    // Guardar en sesión
                    $_SESSION['idSolicitudCompra'] = $idSolicitudCompra;


                    echo '<br><br>';

                    $usoMaterial = "Sin";

                    if (isset($_POST['cantidadFilas'])) {
                        $cantidadFilas = (int) $_POST['cantidadFilas'];

                        if ($cantidadFilas > 0) {

                            // Preparar statement
                            $stmt = $conexion->prepare("INSERT INTO material_solicompra_sdo (
                                idArticuloSC, nomArticuloSC, cantidadArticuloSC, medidaSC, valorSC, idSoliCompra, cantidadFaltante
                            ) VALUES (
                                NULL, ?, ?, ?, ?, ?, ?
                            )");

                            for ($i = 1; $i <= $cantidadFilas; $i++) {

                                $articuloSC = trim($_POST['articuloSC' . $i] ?? '');
                                $cantidadSC = (int) ($_POST['cantidadSC' . $i] ?? 0);
                                $medidaSC = trim($_POST['medidaSC' . $i] ?? '');
                                $valorSC = (float)($_POST['valorSC' . $i] ?? 0);

                                if (!empty($articuloSC) && $cantidadSC > 0) {
                                    $stmt->bind_param("sisdii",
                                        $articuloSC,
                                        $cantidadSC,
                                        $medidaSC,
                                        $valorSC,
                                        $idSolicitudCompra,
                                        $cantidadSC
                                    );

                                    if ($stmt->execute()) {
                                        $usoMaterial = "Con";
                                    }
                                }
                            }

                            $stmt->close();
                        }
                    }

                    // Mostrar mensaje según uso de material
                    echo ($usoMaterial === "Con") ? "Creado Con Material!" : "Creado Sin Material!";

                    // Formatear ID con ceros a la izquierda (4 dígitos)
                    $idSolicitudCompra = str_pad($idSolicitudCompra, 4, "0", STR_PAD_LEFT);


                    //SUBIR ARCHIVOS

                    $archivoCotizacion = "Vacio";

                    if (isset($_FILES["adjuntosSC"])) {

                        $archivos = $_FILES["adjuntosSC"];
                        $cantidadArchivos = count($archivos['tmp_name']);
                        $permitidos = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'eml', 'jpeg', 'jpg', 'png'];

                        $archivosValidos = 0;

                        // Contar archivos realmente cargados
                        foreach ($archivos['tmp_name'] as $tmpFile) {
                            if (is_uploaded_file($tmpFile)) {
                                $archivosValidos++;
                            }
                        }

                        if ($archivosValidos > 0) {

                            $queryCotizacion1 = "UPDATE solicitudes_compra_sdo SET adjuntaCotizacion = '1' WHERE idSoliCompra = $idSolicitudCompra";
                            $resCotizacion1 = mysqli_query($conexion, $queryCotizacion1);

                            if ($resCotizacion1) {
                                $directorio = "../sdo-solicCompra/SC-SDO-" . $idSolicitudCompra . "/";

                                if (!file_exists($directorio)) {
                                    mkdir($directorio, 0755, true);
                                }

                                for ($i = 0; $i < $cantidadArchivos; $i++) {
                                    $tmp_name = $archivos["tmp_name"][$i];
                                    $name = basename($archivos["name"][$i]);

                                    if (!empty($tmp_name) && is_uploaded_file($tmp_name)) {

                                        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

                                        if (in_array($ext, $permitidos)) {

                                            $rutaArchivo = $directorio . $name;

                                            if (move_uploaded_file($tmp_name, $rutaArchivo)) {
                                                chmod($rutaArchivo, 0644);

                                                // Escapar ruta y nombre para SQL
                                                $nombreArchivoSQL = mysqli_real_escape_string($conexion, $name);
                                                $rutaArchivoSQL = mysqli_real_escape_string($conexion, $rutaArchivo);

                                                $queryCotizacion = "
                                                INSERT INTO cotizacion_solicompra_sdo (
                                                    idCotizacionSC, nomCotizacionSC, rutaCotizacionSC,
                                                    tipoSoliCompra, destinoSoliCompra, idSoliCompra
                                                ) VALUES (
                                                    NULL, '$nombreArchivoSQL', '$rutaArchivoSQL',
                                                    'Normal', 'Subdirección de Operaciones', '$idSolicitudCompra'
                                                )";

                                                $resCotizacion = mysqli_query($conexion, $queryCotizacion);

                                                if ($resCotizacion) {
                                                    $archivoCotizacion = 'Usando';
                                                } else {
                                                    $archivoCotizacion = 'Error';
                                                }
                                            } else {
                                                $archivoCotizacion = 'Error';
                                            }
                                        } else {
                                            $archivoCotizacion = 'formatoIncorrecto';
                                        }
                                    }
                                }
                            } else {
                                $archivoCotizacion = 'Error';
                            }
                        }
                    }


                    //FIN SUBIR ARCHIVOS


                    header("Location: ../$ruta/$rutaVueltaTicket?exitoCrearSC=true&material=$usoMaterial");

                } else {

                    header("Location: ../$ruta/$rutaVueltaTicket?errorCrearSC=true");
                }
            } else {

                header("Location: ../$ruta/$rutaVueltaTicket?errorCrearSC=true");
            }
        } else {

            header("Location: ../$ruta/$rutaVueltaTicket?errorCrearSC=true");
        }
    } else {

        header("Location: ../$ruta/$rutaVueltaTicket?errorCrearSC=true");
    }
} else {
    header("Location: ../$ruta/$rutaVueltaTicket?errorCrearSC=true");
}
