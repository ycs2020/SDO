<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

date_default_timezone_set('America/Santiago');

$rutaDireccion = "";

if ($rolUsuario2 == "personal") {
    $tipoUsuario = 'personal';
    $rutaDireccion = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $tipoUsuario = 'administrativo';
    $rutaDireccion = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $tipoUsuario = 'bodega pañol';
    $rutaDireccion = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $tipoUsuario = 'jefe tecnico';
    $rutaDireccion = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $tipoUsuario = 'administrador';
    $rutaDireccion = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $tipoUsuario = 'directiva';
    $rutaDireccion = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if(isset($_POST['idUMA'])) {
    $idUMA = $_POST['idUMA'];
    $ecbos = $_POST['ecbos'];
    $macaddress = $_POST['macaddress'];
    $controlador = $_POST['controlador'];
    $redComunicacion = $_POST['redComunicacion'];
    $sistemaosub = $_POST['sistemaosub'];
    $observacionesGenerales = $_POST['observacionesGenerales'];
    $folio = $_POST['folio'];
    $nuevoUma = $_POST['UMA'];
    $nombreEquipoInput = $_POST['nombreEquipoInput'];
    $idEquipamiento = $_POST['idEquipamiento'];
    $opcionEstado = $_POST['estadoEquipo'];
    $idCategoria = $_POST['idCategoria'];
    $UMA = $_POST['UMA'];

    if($nuevoUma == '' || empty($nuevoUma)){
        $sqlUpdate = "UPDATE `datos_uma` SET `ecbos`='$ecbos',`macaddress`='$macaddress',`controlador`='$controlador',`redComunicación`='$redComunicacion',`obsGeneral`='$observacionesGenerales' WHERE idUMA = $idUMA";
    }else{
        $sqlUpdate = "UPDATE `datos_uma` SET `ecbos`='$ecbos',`macaddress`='$macaddress',`controlador`='$controlador',`redComunicación`='$redComunicacion',`sistemaosub`='$nombreEquipoInput',idEquipamiento = $nuevoUma,`obsGeneral`='$observacionesGenerales' WHERE idUMA = $idUMA";
    }

    $resUpdate = mysqli_query($conexion,$sqlUpdate);

    if($resUpdate){
        $observaciones = "El cambio del estado del equipo esta detallado en el Check List - ".$folio;


        $updateSuccess = true; // Variable para llevar el control de si todas las actualizaciones fueron exitosas
        for ($item_id = 1; $item_id <= 48; $item_id++) {
            // Obtener los valores desde el formulario dinámicamente
            $f = $_POST["f$item_id"];
            $si = isset($_POST["SI$item_id"]) ? 1 : 0; // Si está marcado, es 1, si no, es 0
            $na = isset($_POST["NA$item_id"]) ? 1 : 0;
            $no = isset($_POST["NO$item_id"]) ? 1 : 0;
            $condicion = $_POST["condicion$item_id"];
            $parametros = $_POST["parametros$item_id"];
            $observacion = $_POST["observacion$item_id"];
            $idRegistro = $_POST["idRegistro$item_id"];

            $sqlActulizar = "UPDATE `respuestas_uma` SET `f`='$f',`si`='$si',`na`='$na',`no`='$no',`condicion`='$condicion',`parametros`='$parametros',`observacion`='$observacion' WHERE idUMA = $idUMA AND id = $idRegistro";
            $resActulizar = mysqli_query($conexion,$sqlActulizar);
            if(!$resActulizar){
                $updateSuccess = false;
            }
        }


        if(empty($UMA)){
            $sqlUpdateEquipo = "UPDATE equipamiento_hbqp SET estadoEquipo = '$opcionEstado' WHERE id = $idEquipamiento";
            $resUpdateEquipo = mysqli_query($conexion, $sqlUpdateEquipo);
            if($resUpdateEquipo){
                $existeRegistroObservacion = compruebaRegistroObservacion($idEquipamiento);

                if($existeRegistroObservacion == "Si"){
                    $sqlElimina = "DELETE FROM historial_cambios_equipos_observacion WHERE idEquipo = $idEquipamiento";
                    $resElimina = mysqli_query($conexion, $sqlElimina);
                }

                $existeRegistroFalla = compruebaRegistroFalla($idEquipamiento);
                if($existeRegistroFalla == "Si"){
                    $sqlElimina = "DELETE FROM historial_cambios_equipos_falla WHERE idEquipo = $idEquipamiento";
                    $resElimina = mysqli_query($conexion, $sqlElimina);
                }
                if($opcionEstado == 'Fuera de Servicio'){
                    $sqlInsert = "INSERT INTO historial_cambios_equipos_falla (estado, observaciones, idUsuarioCambia, tipoUsuario, idEquipo, idCategoria,nomEquipo) 
                        VALUES ('$opcionEstado', '$observaciones', $idUsuario, '$rolUsuario2', $idEquipamiento, $idCategoria,'$controlador')";
                    $resInsert = mysqli_query($conexion, $sqlInsert);
                }

                //INSERTAMOS EN LA TABLA HISTORIAL DE CAMBIOS
                $sqlInsert = "INSERT INTO historial_cambios_equipos (estado, observaciones, idUsuarioCambia, tipoUsuario, idEquipo, idCategoria,nombreEquipo) 
                    VALUES ('$opcionEstado', '$observaciones', $idUsuario, '$rolUsuario2', $idEquipamiento, $idCategoria,'$controlador')";
                $resInsert = mysqli_query($conexion, $sqlInsert);

                $sqlInsert = "INSERT INTO historial_cambios_equipos_respaldo (estado, observaciones , idUsuarioCambia, tipoUsuario, idEquipo, idCategoria,nombreEquipo) 
                    VALUES ('$opcionEstado', '$observaciones', $idUsuario, '$rolUsuario2', $idEquipamiento, $idCategoria,'$controlador')";
                $resInsert = mysqli_query($conexion, $sqlInsert);
            }
        }else{
            $sqlUpdateEquipo = "UPDATE equipamiento_hbqp SET estadoEquipo = '$opcionEstado' WHERE id = $UMA";
            $resUpdateEquipo = mysqli_query($conexion, $sqlUpdateEquipo);
            if($resUpdateEquipo){
                $existeRegistroObservacion = compruebaRegistroObservacion($UMA);

                if($existeRegistroObservacion == "Si"){
                    $sqlElimina = "DELETE FROM historial_cambios_equipos_observacion WHERE idEquipo = $UMA";
                    $resElimina = mysqli_query($conexion, $sqlElimina);
                }

                $existeRegistroFalla = compruebaRegistroFalla($UMA);
                if($existeRegistroFalla == "Si"){
                    $sqlElimina = "DELETE FROM historial_cambios_equipos_falla WHERE idEquipo = $UMA";
                    $resElimina = mysqli_query($conexion, $sqlElimina);
                }
                if($opcionEstado == 'Fuera de Servicio'){
                    $sqlInsert = "INSERT INTO historial_cambios_equipos_falla (estado, observaciones, idUsuarioCambia, tipoUsuario, idEquipo, idCategoria,nomEquipo) 
                        VALUES ('$opcionEstado', '$observaciones', $idUsuario, '$rolUsuario2', $UMA, $idCategoria,'$controlador')";
                    $resInsert = mysqli_query($conexion, $sqlInsert);
                }

                //INSERTAMOS EN LA TABLA HISTORIAL DE CAMBIOS
                $sqlInsert = "INSERT INTO historial_cambios_equipos (estado, observaciones, idUsuarioCambia, tipoUsuario, idEquipo, idCategoria,nombreEquipo) 
                    VALUES ('$opcionEstado', '$observaciones', $idUsuario, '$rolUsuario2', $UMA, $idCategoria,'$controlador')";
                $resInsert = mysqli_query($conexion, $sqlInsert);

                $sqlInsert = "INSERT INTO historial_cambios_equipos_respaldo (estado, observaciones , idUsuarioCambia, tipoUsuario, idEquipo, idCategoria,nombreEquipo) 
                    VALUES ('$opcionEstado', '$observaciones', $idUsuario, '$rolUsuario2', $UMA, $idCategoria,'$controlador')";
                $resInsert = mysqli_query($conexion, $sqlInsert);

            }
        }


        // Verificamos si todas las actualizaciones fueron exitosas
        if ($updateSuccess) {
            header('Location: ../'.$rutaDireccion.'/visualizardetalles.php?exitoModificarCheckListUMA=true');
            exit();
        } else {
            header('Location: ../'.$rutaDireccion.'/visualizardetalles.php?errorModificarCheckListUMA=true');
            exit();
        }
    }else{
        header('Location: ../'.$rutaDireccion.'/visualizardetalles.php?errorModificarCheckListUMA1=true');
        exit();
    }

}else{
    header('Location: ../'.$rutaDireccion.'/visualizardetalles.php?errorModificarCheckListUMA2=true');
    exit();
}