<?php

include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';

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

if (isset($_POST['numCliente'])) {
    $numCliente = $_POST['numCliente'];

    $sql = "SELECT idCliente, medidor 
            FROM cliente_agua 
            WHERE nCliente = '$numCliente' 
            ORDER BY medidor ASC";
    $res = mysqli_query($conexion, $sql);

    $equipos = [];
    while ($row = $res->fetch_assoc()) {
        $equipos[] = [
            'id' => $row['idCliente'],
            'nombre' => $row['medidor']
        ];
    }

    if (!empty($equipos)) {
        // ✅ Se encontraron equipos
        echo json_encode([
            'success' => true,
            'equipos' => $equipos
        ]);
    } else {
        // ⚠️ No se encontraron equipos
        echo json_encode([
            'success' => false,
            'message' => 'No se encontraron equipos para este cliente.'
        ]);
    }

} else {
    // ❌ No se recibió el parámetro esperado
    echo json_encode([
        'success' => false,
        'message' => 'Parámetro no recibido.'
    ]);
}
