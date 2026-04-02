<?php

include_once 'conexion.php';
include_once 'funciones.php';

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

if (isset($_POST['tituloAviso'], $_POST['descripcionAviso'], $_POST['departamentoAviso'], $_POST['destinatarioAviso'], $_POST['creadorAviso'])) {

    $tituloAviso = trim($_POST['tituloAviso']);
    $descripcionAviso = trim($_POST['descripcionAviso']);
    $departamentoAviso = trim($_POST['departamentoAviso']);
    $destinatarioAviso = $_POST['destinatarioAviso'];
    $creadorAviso = trim($_POST['creadorAviso']);

    // Verificamos que no estén vacías y que destinatarioAviso sea un array con datos
    if (
        $tituloAviso !== '' &&
        $descripcionAviso !== '' &&
        $departamentoAviso !== '' &&
        $creadorAviso !== '' &&
        is_array($destinatarioAviso) &&
        count($destinatarioAviso) > 0
    ) {
        // Todos los datos son válidos, continuar con la lógica...

        $tituloAviso = mysqli_real_escape_string($conexion, $tituloAviso);
        $descripcionAviso = mysqli_real_escape_string($conexion, $descripcionAviso);
        $departamentoAviso = mysqli_real_escape_string($conexion, $departamentoAviso);
        $creadorAviso = mysqli_real_escape_string($conexion, $creadorAviso);

        $sqlAviso = "INSERT INTO avisos_relevantes(idAviso, descripcionAviso, tituloAviso, fechaAviso, creadorAviso, sistemaAviso) VALUES (NULL,'$descripcionAviso','$tituloAviso','$now','$creadorAviso','$departamentoAviso')";

        $resAviso = mysqli_query($conexion, $sqlAviso);

        if ($resAviso) {
            $varRespuesta = "Aviso agregado correctamente.";

            $idAviso = mysqli_insert_id($conexion);

            if ($idAviso > 0) {

                $totalDestinatarios = count($destinatarioAviso); // Total en el array
                $insertadosOk = 0; // Contador de inserciones exitosas

                foreach ($destinatarioAviso as $destinatario) {
                    $destinatario = mysqli_real_escape_string($conexion, $destinatario);

                    $sqlDestinatario = "INSERT INTO avisos_destinatarios(idDestinatario, idAviso, nomDestinatario, fechaDestinatario) 
                        VALUES (NULL, $idAviso, '$destinatario', '$now')";

                    $resDestinatario = mysqli_query($conexion, $sqlDestinatario);

                    if ($resDestinatario) {
                        $insertadosOk++;
                    }
                }

// Comparativa
                if ($insertadosOk === $totalDestinatarios) {
                    $varRespuesta = "✅ Todos los destinatarios fueron insertados correctamente.";
                } else {
                    $varRespuesta = "⚠️ Se insertaron $insertadosOk de $totalDestinatarios destinatarios.";
                }

            } else {
                $varRespuesta = "Error al Obtener ID de Aviso.";
            }

        } else {
            $varRespuesta = $sqlAviso;
        }
    } else {
        $varRespuesta = "Todos los campos son obligatorios y debe haber al menos un destinatario.";

    }

} else {
    $varRespuesta = "Faltan datos del formulario.";
}


$_SESSION['varRespuesta'] = $varRespuesta;
header("Location: ../$rutaDireccion/avisos_relevantes_administracion.php?mensajeVar=true");