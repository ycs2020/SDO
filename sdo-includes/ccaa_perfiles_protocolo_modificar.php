<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

session_start();

// Validar sesión
if (!isset($_SESSION['idUsuario'], $_SESSION['userUsuario'], $_SESSION['nomUsuario'], $_SESSION['rolUsuario'])) {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    exit();
}

// Variables de sesión
$idUsuario    = $_SESSION['idUsuario'];
$userUsuario  = $_SESSION['userUsuario'];
$nomUsuario   = $_SESSION['nomUsuario'];
$rolUsuario   = strtolower(trim($_SESSION['rolUsuario']));
$usuario      = $_SESSION['usuarioSDO'] ?? $userUsuario;

// Determinar ruta destino según rol
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

// Procesar POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idPerfil      = $_POST['idPerfil'];
    $nomPerfil     = $_POST['nomPerfil'];
    $descripPerfil = $_POST['descripPerfil'];
    $observacion  = $_POST['observacion'];

    if($idPerfil > 0 && $nomPerfil != '' && $descripPerfil != '' && $observacion != ''){

        $sqlBuscar = "SELECT COUNT(*) AS total FROM protoc_ccaa_perfil WHERE idPerfil = ? AND nomPerfil = ? AND descripPerfil = ?";
        $stmtBuscar = $conexion->prepare($sqlBuscar);
        $stmtBuscar->bind_param("iss", $idPerfil, $nomPerfil, $descripPerfil);
        $stmtBuscar->execute();
        $resultBuscar = $stmtBuscar->get_result();
        $row = $resultBuscar->fetch_assoc();
        $totalCoincidencia = $row['total'];

        if($totalCoincidencia > 0){
            $_SESSION['iconoMod'] = 'error';
            $_SESSION['mensajeMod'] = 'Modificación No Realizada: Datos son Iguales';
        }else{
            $sqlBuscar = "SELECT COUNT(*) AS total FROM protoc_ccaa_perfil WHERE nomPerfil = ?";
            $stmtBuscar = $conexion->prepare($sqlBuscar);
            $stmtBuscar->bind_param("s", $nomPerfil);
            $stmtBuscar->execute();
            $resultBuscar = $stmtBuscar->get_result();
            $row = $resultBuscar->fetch_assoc();
            $totalNombre = $row['total'];

            if($totalNombre > 0){
                $_SESSION['iconoMod'] = 'error';
                $_SESSION['mensajeMod'] = 'Modificación No Realizada: Perfil Existente.';
            }else{

                $sqlModificar = "UPDATE protoc_ccaa_perfil SET nomPerfil = ?, descripPerfil = ? WHERE idPerfil = ?";
                $stmtModificar = $conexion->prepare($sqlModificar);
                $stmtModificar->bind_param("ssi", $nomPerfil, $descripPerfil, $idPerfil);
                $stmtModificar->execute();

                if ($stmtModificar->affected_rows > 0) {
                    $_SESSION['iconoMod'] = 'success';
                    $_SESSION['mensajeMod'] = 'Modificación Realizada.';

                    $sqlObservacion = "INSERT INTO protoc_ccaa_perfil_historial (idPerfil, fechaModificacion, usuario, observacion) VALUES (?, NOW(), ?, ?)";
                    $stmtObservacion = $conexion->prepare($sqlObservacion);
//                    $str = 'Modificación de Información';
                    $stmtObservacion->bind_param("iss", $idPerfil, $nomUsuario, $observacion);
                    $resObservacion = $stmtObservacion->execute();

                    if ($resObservacion && $stmtObservacion->affected_rows > 0) {
                        $_SESSION['iconoMod'] = 'success';
                        $_SESSION['mensajeMod'] = 'Modificación Realizada.';
                    } else {
                        $_SESSION['iconoMod'] = 'success';
                        $_SESSION['mensajeMod'] = 'Modificación Realizada. Sin Registro en Historial de Modificaciones.';
                    }

                } else {
                    $_SESSION['iconoMod'] = 'error';
                    $_SESSION['mensajeMod'] = 'Modificación No Realizada: Error al Realizar Actualización.';
                }


            }
        }

    }else{
        $_SESSION['iconoMod'] = 'error';
        $_SESSION['mensajeMod'] = 'Modificación No Realizada: Error al Obtener Datos del Formulario.';
    }


}else{
    $_SESSION['iconoMod'] = 'error';
    $_SESSION['mensajeMod'] = 'Modificación No Realizada: Error al Obtener Datos del Formulario.';
}

header('Location: ../'.$rutaDireccion.'/ccaa_perfiles_protocolo_detalle.php');
?>
