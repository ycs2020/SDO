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
    $idGrupo      = $_POST['idGrupo'];
    $nomGrupo     = $_POST['nomGrupo'];
    $descripGrupo = $_POST['descripGrupo'];
    $observacion  = $_POST['observacion'];

    if($idGrupo > 0 && $nomGrupo != '' && $descripGrupo != '' && $observacion != ''){

        $sqlBuscar = "SELECT COUNT(*) AS total FROM protoc_ccaa_grupo WHERE idGrupo = ? AND nomGrupo = ? AND descripGrupo = ?";
        $stmtBuscar = $conexion->prepare($sqlBuscar);
        $stmtBuscar->bind_param("iss", $idGrupo, $nomGrupo, $descripGrupo);
        $stmtBuscar->execute();
        $resultBuscar = $stmtBuscar->get_result();
        $row = $resultBuscar->fetch_assoc();
        $totalCoincidencia = $row['total'];

        if($totalCoincidencia > 0){
            $_SESSION['iconoMod'] = 'error';
            $_SESSION['mensajeMod'] = 'Modificación No Realizada: Datos son Iguales';
        }else{
            $sqlBuscar = "SELECT COUNT(*) AS total FROM protoc_ccaa_grupo WHERE nomGrupo = ?";
            $stmtBuscar = $conexion->prepare($sqlBuscar);
            $stmtBuscar->bind_param("s", $nomGrupo);
            $stmtBuscar->execute();
            $resultBuscar = $stmtBuscar->get_result();
            $row = $resultBuscar->fetch_assoc();
            $totalNombre = $row['total'];

            if($totalNombre > 0){
                $_SESSION['iconoMod'] = 'error';
                $_SESSION['mensajeMod'] = 'Modificación No Realizada: Grupo Existente.';
            }else{

                $sqlModificar = "UPDATE protoc_ccaa_grupo SET nomGrupo = ?, descripGrupo = ?, fechaGrupo = NOW() WHERE idGrupo = ?";
                $stmtModificar = $conexion->prepare($sqlModificar);
                $stmtModificar->bind_param("ssi", $nomGrupo, $descripGrupo, $idGrupo);
                $stmtModificar->execute();

                if ($stmtModificar->affected_rows > 0) {
                    $_SESSION['iconoMod'] = 'success';
                    $_SESSION['mensajeMod'] = 'Modificación Realizada.';

                    $sqlObservacion = "INSERT INTO protoc_ccaa_grupo_historial (idGrupo, fechaModificacion, usuario, observacion) VALUES (?, NOW(), ?, ?)";
                    $stmtObservacion = $conexion->prepare($sqlObservacion);
//                    $str = 'Modificación de Información';
                    $stmtObservacion->bind_param("iss", $idGrupo, $nomUsuario, $observacion);
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

header('Location: ../'.$rutaDireccion.'/ccaa_grupos_protocolo_detalle.php');
?>
