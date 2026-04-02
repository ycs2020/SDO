<?php

include_once 'conexion.php';
include_once 'funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

$ruta = "";

if ($rolUsuario2 == "personal") {

    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {

    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {

    $ruta = "sdo-bodega-panol";
}else if ($rolUsuario2 == "jefe tecnico") {

    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {

    $ruta = "sdo-administrador";
} else if($rolUsuario2 == "directiva"){
    $ruta = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if (isset($_POST['idOrden'])) {

    $idOrden = trim($_POST['idOrden']);

    if (empty($idOrden) || $idOrden == "" || $idOrden == " ") {
        header('Location: ../'. $ruta . '/ordenes-administrar-general.php?errorEliminarOrden=true');
    } else {

        $queryOrden = "DELETE FROM ordenes_trabajos_general WHERE idOrden=" . $idOrden;
        $resOrden = mysqli_query($conexion, $queryOrden);

        if ($resOrden) {

            $querySelectImagenes = "SELECT * FROM imagenes_orden_general WHERE idOrden=" . $idOrden;
            $resSelectImagenes = mysqli_query($conexion, $querySelectImagenes);

            $querySelectArchivos = "SELECT * FROM archivos_orden_general WHERE idOrden=" . $idOrden;
            $resSelectArchivos = mysqli_query($conexion, $querySelectArchivos);

            if($resSelectArchivos->num_rows > 0){

                while ($row1 = $resSelectArchivos->fetch_assoc()) {
                    $datos = [];
                    $datos['rutaArchivo'] = $row1['rutaArchivo'];
                    $rutaArchivo = $datos['rutaArchivo'];
                }

                unlink($rutaArchivo);

                $queryArchivos = "DELETE FROM archivos_orden_general WHERE idOrden=" . $idOrden;
                $resArchivps = mysqli_query($conexion, $queryArchivos);
    
                if ($resArchivps) {
                    echo 'REGISTRO DEl ARCHIVO ELIMINADO';
                } else {
                    echo 'NO SE PUDO ELIMINAR EL REGISTRO DE LA IMAGEN';
                }

            }

            if ($resSelectImagenes->num_rows > 0) {
    
                while ($row = $resSelectImagenes->fetch_assoc()) {
                    $datos = [];
                    $datos['rutaImagen'] = $row['rutaImagen'];
                    $rutaImagen = $datos['rutaImagen'];
                }
              
    
                unlink($rutaImagen);

                $queryImagenes = "DELETE FROM imagenes_orden_general WHERE idOrden=" . $idOrden;
                $resImagenes = mysqli_query($conexion, $queryImagenes);
    
                if ($resImagenes) {
                    echo 'REGISTRO DE LA IMAGEN ELIMINADO';
                } else {
                    echo 'NO SE PUDO ELIMINAR EL REGISTRO DE LA IMAGEN';
                }
            }

            if ($rolUsuario2 == "administrativo" || $rolUsuario2 == "administrador") {
                header('Location: ../' . $ruta . '/ordenes-subir-firmas.php?exitoEliminarHoja=true');
            }else{
                header('Location: ../'. $ruta . '/ordenes-administrar-general.php?exitoEliminarHoja=true');
            }

         
        } else {

            if ($rolUsuario2 == "administrativo" || $rolUsuario2 == "administrador") {
                header('Location: ../' . $ruta . '/ordenes-menu-admin.php?errorEliminarHoja=true');
            }else{
                header('Location: ../'. $ruta . '/ordenes-administrar-general.php?errorEliminarHoja=true');
            }
        }
    }
} else {

    if ($rolUsuario2 == "administrativo" || $rolUsuario2 == "administrador") {
        header('Location: ../' . $ruta . '/ordenes-subir-firmas.php?errorSeleccionarOrden=true');
    }else{
        header('Location: ../'. $ruta . '/ordenes-administrar-general.php?errorSeleccionarOrden=true');
    }
}
