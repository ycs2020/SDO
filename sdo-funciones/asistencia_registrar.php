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
        $rutaDireccion = 'sdo-personal/index.php';
        break;
    case 'administrativo':
        $rutaDireccion = 'sdo-admtec/index.php';
        break;
    case 'bodega pañol':
        $rutaDireccion = 'sdo-bodega-panol/index.php';
        break;
    case 'jefe tecnico':
        $rutaDireccion = 'sdo-jefestecnicos/index.php';
        break;
    case 'administrador':
        $rutaDireccion = 'sdo-administrador/asistencia_registro.php';
        break;
    case 'directiva':
        $rutaDireccion = 'sdo-directiva/index.php';
        break;
    case 'oficina tecnica':
        $rutaDireccion = 'sdo-oficinaTecnica/index.php';
        break;
    default:
        header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
        exit();
}

if(isset($_POST['nombreFuncionario'],$_POST['fechaAsistencia'],$_POST['turnoFuncionario'],$_POST['departamentoFuncionario'])){

    $nombreFuncionario = trim($_POST['nombreFuncionario']);
    $fechaAsistencia = $_POST['fechaAsistencia'];
    $turnoFuncionario = trim($_POST['turnoFuncionario']);
    $departamentoFuncionario = trim($_POST['departamentoFuncionario']);

    if(!empty($nombreFuncionario) && !empty($fechaAsistencia) && !empty($turnoFuncionario) && !empty($departamentoFuncionario)){

        $obtenerCantidadAsistenciaTurno = obtenerCantidadAsistenciaTurno($nombreFuncionario,$fechaAsistencia,$turnoFuncionario);

        if($obtenerCantidadAsistenciaTurno > 0){
            header('Location: ../'.$rutaDireccion . '?existeMarcajeAsistencia=true');
        }else{
            if (in_array($turnoFuncionario, ['Turno Dia', 'Turno Noche'])) {
                // Código a ejecutar si coincide

                $stmt = $conexion->prepare("INSERT INTO asistencia_sdo (personalAsistencia, deptoAsistencia, fechaAsistencia, turnoAsistencia) VALUES (?, ?, ?, ?)");

                if ($stmt) {
                    $stmt->bind_param("ssss", $nombreFuncionario, $departamentoFuncionario, $fechaAsistencia, $turnoFuncionario);

                    if ($stmt->execute()) {
                        header('Location: ../' . $rutaDireccion . '?exitoMarcajeAsistencia=true');
                    } else {
                        header('Location: ../' . $rutaDireccion . '?errorMarcajeAsistencia=true');
                    }

                    $stmt->close();
                } else {
                    // Error al preparar la sentencia
                    header('Location: ../' . $rutaDireccion . '?errorMarcajeAsistencia=true');
                }

            }else{
                header('Location: ../'.$rutaDireccion . '?errorMarcajeAsistencia=true');
            }
        }




    }else{
        header('Location: ../'.$rutaDireccion . '?errorMarcajeAsistencia=true');
    }
}else{
    header('Location: ../'.$rutaDireccion . '?errorMarcajeAsistencia=true');
}