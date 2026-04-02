<?php

include_once 'conexion.php';
include_once 'funciones.php';

header('Content-Type: application/json');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Establecer zona horaria
date_default_timezone_set('America/Santiago');
$now = date('Y-m-d H:i:s');

// Validar que existan las variables de sesión necesarias
if (
    !isset($_SESSION['userUsuario'], $_SESSION['idUsuario'], $_SESSION['nomUsuario'], $_SESSION['rolUsuario'])
) {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    exit();
}

// VARIABLES DE SESIÓN
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];
$rolUsuario = strtolower(trim($_SESSION['rolUsuario']));

$tipoUsuario = $rolUsuario;
$rutaDireccion = '';

switch ($rolUsuario) {
    case 'personal':
        $rutaDireccion = 'sdo-personal';
        break;
    case 'administrativo':
        $rutaDireccion = 'sdo-admtec';
        break;
    case 'bodega pañol':
        $rutaDireccion = 'sdo-bodega-panol';
        break;
    case 'jefe tecnico':
        $rutaDireccion = 'sdo-jefestecnicos';
        break;
    case 'administrador':
        $rutaDireccion = 'sdo-administrador';
        break;
    case 'directiva':
        $rutaDireccion = 'sdo-directiva';
        break;
    case 'oficina tecnica':
        $rutaDireccion = 'sdo-oficinaTecnica';
        break;
    default:
        header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
        exit();
}

$campoUpdate = '';



if (isset($_POST['tituloAviso'], $_POST['departamentoAviso'], $_POST['destinatarioAviso'], $_POST['creadorAviso'], $_POST['idReporteAlarmas'])
    && (isset($_POST['descripcionAviso']) || isset($_POST['descripcionAvisoHTML']) || isset($_POST['descripcionAvisoTxt']))) {


    $tituloAviso = trim($_POST['tituloAviso']);
    // Prioridad: HTML > TXT > descripcionAviso
    if (isset($_POST['descripcionAvisoHTML'])) {
        $descripcionAviso = trim($_POST['descripcionAvisoHTML']);
    } else{
        if (isset($_POST['descripcionAvisoTxt'])) {
            $descripcionAviso = trim($_POST['descripcionAvisoTxt']);
        } else {
            $descripcionAviso = trim($_POST['descripcionAviso']);
        }
    }


    $departamentoAviso = trim($_POST['departamentoAviso']);
    $destinatarioAviso = $_POST['destinatarioAviso'];
    $creadorAviso = trim($_POST['creadorAviso']);
    $idReporteAlarmas = (int)$_POST['idReporteAlarmas'];

    if (
        $tituloAviso !== '' &&
        $descripcionAviso !== '' &&
        $departamentoAviso !== '' &&
        $creadorAviso !== '' &&
        is_array($destinatarioAviso) &&
        count($destinatarioAviso) > 0 &&
        $idReporteAlarmas > 0
    ) {
        $tituloAviso = mysqli_real_escape_string($conexion, $tituloAviso);
        $descripcionAviso = mysqli_real_escape_string($conexion, $descripcionAviso);
        $departamentoAviso = mysqli_real_escape_string($conexion, $departamentoAviso);
        $creadorAviso = mysqli_real_escape_string($conexion, $creadorAviso);
        $idReporteAlarmas = mysqli_real_escape_string($conexion, $idReporteAlarmas);

        $sqlAviso = "INSERT INTO avisos_relevantes(idAviso, descripcionAviso, tituloAviso, fechaAviso, creadorAviso, sistemaAviso, idReporteAlarmas) VALUES (NULL,'$descripcionAviso','$tituloAviso','$now','$creadorAviso','$departamentoAviso',$idReporteAlarmas)";

        $resAviso = mysqli_query($conexion, $sqlAviso);

        if ($resAviso) {
            $idAviso = mysqli_insert_id($conexion);

            if ($idAviso > 0) {
                $totalDestinatarios = count($destinatarioAviso);
                $insertadosOk = 0;

                foreach ($destinatarioAviso as $destinatario) {
                    $destinatario = mysqli_real_escape_string($conexion, $destinatario);

                    $sqlDestinatario = "INSERT INTO avisos_destinatarios(idDestinatario, idAviso, nomDestinatario, fechaDestinatario) 
                        VALUES (NULL, $idAviso, '$destinatario', '$now')";

                    $resDestinatario = mysqli_query($conexion, $sqlDestinatario);

                    if ($resDestinatario) {
                        $insertadosOk++;
                    }
                }

                if ($insertadosOk === $totalDestinatarios) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => '✅ Aviso y todos los destinatarios insertados correctamente.'
                    ]);
                    exit;
                } else {
                    echo json_encode([
                        'status' => 'warning',
                        'message' => "⚠️ Aviso agregado, pero solo se insertaron $insertadosOk de $totalDestinatarios destinatarios."
                    ]);
                    exit;
                }

            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Error al obtener el ID del aviso.'
                ]);
                exit;
            }

        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Error al insertar el aviso.'
            ]);
            exit;
        }

    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Todos los campos son obligatorios y debe haber al menos un destinatario.'
        ]);
        exit;
    }

} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Faltan datos del formulario.'
    ]);
    exit;
}