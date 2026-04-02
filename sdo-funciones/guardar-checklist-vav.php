<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');

$rolUsuario = $_SESSION['rolUsuario'];

$nomCreador = $_SESSION['nomUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

$idDepartamento = $_SESSION['idDepartamento'];


$ruta = "";

if ($rolUsuario2 == "personal") {

    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {

    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "administrativo") {

    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "jefe tecnico") {

    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "directiva") {

    $ruta = "sdo-directiva";

    $cargoUsuario = $_SESSION['cargoUsuario'];

    $cargoUsuario = trim($cargoUsuario);
} else if ($rolUsuario2 == "administrador") {

    $ruta = "sdo-administrador";

    $cargoUsuario = $_SESSION['cargoUsuario'];

    $cargoUsuario = trim($cargoUsuario);

    if (empty($cargoUsuario)) {
        header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    }
} else if ($rolUsuario2 == "mesa ayuda") {

    $ruta = "sdo-mesa-ayuda";
} else {

    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idTG = $_POST['idTG'];
    $ecbos = $_POST['ecbos'];
    $macaddress = $_POST['macaddress'];
    $controlador = $_POST['controlador'];
    $redComunicacion = $_POST['redComunicacion'];
    $sistemaosub = $_POST['sistemaosub'];
    $observacionesGenerales = $_POST['observacionesGenerales'];
    $observacionesGenerales = $conexion->real_escape_string($observacionesGenerales);

    $now = date('Y-m-d H:i:s');
    $now2 = date('Y-m-d');
    $vavID = $_POST['vavID'];
    $idTareaP = $_POST['idTareaP'];
    $idCategoria = $_POST['idCategoria'];
    $opcionEstado = $_POST['estadoEquipo'];
    $idUsuarioPost = $_POST['idUsuario'];
    $rolUsuarioPost = $_POST['rolUsuario'];


    $f1 = $_POST['f1'];
    $SI1 = $_POST['SI1'];
    $NA1 = $_POST['NA1'];
    $NO1 = $_POST['NO1'];
    $condicion1 = $_POST['condicion1'];
    $parametros1 = $_POST['parametros1'];
    $observacion1 = $_POST['observacion1'];

    $f2 = $_POST['f2'];
    $SI2 = $_POST['SI2'];
    $NA2 = $_POST['NA2'];
    $NO2 = $_POST['NO2'];
    $condicion2 = $_POST['condicion2'];
    $parametros2 = $_POST['parametros2'];
    $observacion2 = $_POST['observacion2'];

    $f3 = $_POST['f3'];
    $SI3 = $_POST['SI3'];
    $NA3 = $_POST['NA3'];
    $NO3 = $_POST['NO3'];
    $condicion3 = $_POST['condicion3'];
    $parametros3 = $_POST['parametros3'];
    $observacion3 = $_POST['observacion3'];

    $f4 = $_POST['f4'];
    $SI4 = $_POST['SI4'];
    $NA4 = $_POST['NA4'];
    $NO4 = $_POST['NO4'];
    $condicion4 = $_POST['condicion4'];
    $parametros4 = $_POST['parametros4'];
    $observacion4 = $_POST['observacion4'];

    $f5 = $_POST['f5'];
    $SI5 = $_POST['SI5'];
    $NA5 = $_POST['NA5'];
    $NO5 = $_POST['NO5'];
    $condicion5 = $_POST['condicion5'];
    $parametros5 = $_POST['parametros5'];
    $observacion5 = $_POST['observacion5'];

    $f6 = $_POST['f6'];
    $SI6 = $_POST['SI6'];
    $NA6 = $_POST['NA6'];
    $NO6 = $_POST['NO6'];
    $condicion6 = $_POST['condicion6'];
    $parametros6 = $_POST['parametros6'];
    $observacion6 = $_POST['observacion6'];

    $f7 = $_POST['f7'];
    $SI7 = $_POST['SI7'];
    $NA7 = $_POST['NA7'];
    $NO7 = $_POST['NO7'];
    $condicion7 = $_POST['condicion7'];
    $parametros7 = $_POST['parametros7'];
    $observacion7 = $_POST['observacion7'];

    $f8 = $_POST['f8'];
    $SI8 = $_POST['SI8'];
    $NA8 = $_POST['NA8'];
    $NO8 = $_POST['NO8'];
    $condicion8 = $_POST['condicion8'];
    $parametros8 = $_POST['parametros8'];
    $observacion8 = $_POST['observacion8'];

    $f9 = $_POST['f9'];
    $SI9 = $_POST['SI9'];
    $NA9 = $_POST['NA9'];
    $NO9 = $_POST['NO9'];
    $condicion9 = $_POST['condicion9'];
    $parametros9 = $_POST['parametros9'];
    $observacion9 = $_POST['observacion9'];

    $f10 = $_POST['f10'];
    $SI10 = $_POST['SI10'];
    $NA10 = $_POST['NA10'];
    $NO10 = $_POST['NO10'];
    $condicion10 = $_POST['condicion10'];
    $parametros10 = $_POST['parametros10'];
    $observacion10 = $_POST['observacion10'];

    $f11 = $_POST['f11'];
    $SI11 = $_POST['SI11'];
    $NA11 = $_POST['NA11'];
    $NO11 = $_POST['NO11'];
    $condicion11 = $_POST['condicion11'];
    $parametros11 = $_POST['parametros11'];
    $observacion11 = $_POST['observacion11'];

    $f12 = $_POST['f12'];
    $SI12 = $_POST['SI12'];
    $NA12 = $_POST['NA12'];
    $NO12 = $_POST['NO12'];
    $condicion12 = $_POST['condicion12'];
    $parametros12 = $_POST['parametros12'];
    $observacion12 = $_POST['observacion12'];

    $f13 = $_POST['f13'];
    $SI13 = $_POST['SI13'];
    $NA13 = $_POST['NA13'];
    $NO13 = $_POST['NO13'];
    $condicion13 = $_POST['condicion13'];
    $parametros13 = $_POST['parametros13'];
    $observacion13 = $_POST['observacion13'];

    $f14 = $_POST['f14'];
    $SI14 = $_POST['SI14'];
    $NA14 = $_POST['NA14'];
    $NO14 = $_POST['NO14'];
    $condicion14 = $_POST['condicion14'];
    $parametros14 = $_POST['parametros14'];
    $observacion14 = $_POST['observacion14'];

    foreach (range(1, 14) as $i) {
        $varName = "observacion$i";
        $$varName = $conexion->real_escape_string($$varName);
    }

    $sql = "INSERT INTO `datos_vav`(`idVav`,fecha, `ecbos`, `macaddress`, `controlador`, `redComunicación`, `sistemaosub`, `folio`, `obsGeneral`, `idTG`,idEquipamiento) 
    VALUES (NULL,'$now','$ecbos','$macaddress','$controlador','$redComunicacion','$sistemaosub','NULL','$observacionesGenerales',$idTG,$vavID)";

    $res = mysqli_query($conexion,$sql);
    if($res){
        $id = mysqli_insert_id($conexion);
        $folio = 'CHECKLIST-VAV-'.$id;
        $update = "UPDATE `datos_vav` SET `folio`='$folio' WHERE `idVav` = $id";
        $resUpdate = mysqli_query($conexion,$update);
        if($resUpdate){
            // Construir la consulta SQL
            $sqlGuardar = "INSERT INTO `respuestas`(`id`, `item_id`, `fecha`, `f`, `si`, `na`, `no`, `condicion`, `parametros`, `observacion`, `idVAV`) 
               VALUES 
               (NULL, 1, '$now', '$f1', " . ($SI1 ? 1 : 'NULL') . ", " . ($NA1 ? 1 : 'NULL') . ", " . ($NO1 ? 1 : 'NULL') . ", '$condicion1', '$parametros1', '$observacion1', $id),
               (NULL, 2, '$now', '$f2', " . ($SI2 ? 1 : 'NULL') . ", " . ($NA2 ? 1 : 'NULL') . ", " . ($NO2 ? 1 : 'NULL') . ", '$condicion2', '$parametros2', '$observacion2', $id),
               (NULL, 3, '$now', '$f3', " . ($SI3 ? 1 : 'NULL') . ", " . ($NA3 ? 1 : 'NULL') . ", " . ($NO3 ? 1 : 'NULL') . ", '$condicion3', '$parametros3', '$observacion3', $id),
               (NULL, 4, '$now', '$f4', " . ($SI4 ? 1 : 'NULL') . ", " . ($NA4 ? 1 : 'NULL') . ", " . ($NO4 ? 1 : 'NULL') . ", '$condicion4', '$parametros4', '$observacion4', $id),
               (NULL, 5, '$now', '$f5', " . ($SI5 ? 1 : 'NULL') . ", " . ($NA5 ? 1 : 'NULL') . ", " . ($NO5 ? 1 : 'NULL') . ", '$condicion5', '$parametros5', '$observacion5', $id),
               (NULL, 6, '$now', '$f6', " . ($SI6 ? 1 : 'NULL') . ", " . ($NA6 ? 1 : 'NULL') . ", " . ($NO6 ? 1 : 'NULL') . ", '$condicion6', '$parametros6', '$observacion6', $id),
               (NULL, 7, '$now', '$f7', " . ($SI7 ? 1 : 'NULL') . ", " . ($NA7 ? 1 : 'NULL') . ", " . ($NO7 ? 1 : 'NULL') . ", '$condicion7', '$parametros7', '$observacion7', $id),
               (NULL, 8, '$now', '$f8', " . ($SI8 ? 1 : 'NULL') . ", " . ($NA8 ? 1 : 'NULL') . ", " . ($NO8 ? 1 : 'NULL') . ", '$condicion8', '$parametros8', '$observacion8', $id),
               (NULL, 9, '$now', '$f9', " . ($SI9 ? 1 : 'NULL') . ", " . ($NA9 ? 1 : 'NULL') . ", " . ($NO9 ? 1 : 'NULL') . ", '$condicion9', '$parametros9', '$observacion9', $id),
               (NULL, 10, '$now', '$f10', " . ($SI10 ? 1 : 'NULL') . ", " . ($NA10 ? 1 : 'NULL') . ", " . ($NO10 ? 1 : 'NULL') . ", '$condicion10', '$parametros10', '$observacion10', $id),
               (NULL, 11, '$now', '$f11', " . ($SI11 ? 1 : 'NULL') . ", " . ($NA11 ? 1 : 'NULL') . ", " . ($NO11 ? 1 : 'NULL') . ", '$condicion11', '$parametros11', '$observacion11', $id),
               (NULL, 12, '$now', '$f12', " . ($SI12 ? 1 : 'NULL') . ", " . ($NA12 ? 1 : 'NULL') . ", " . ($NO12 ? 1 : 'NULL') . ", '$condicion12', '$parametros12', '$observacion12', $id),
               (NULL, 13, '$now', '$f13', " . ($SI13 ? 1 : 'NULL') . ", " . ($NA13 ? 1 : 'NULL') . ", " . ($NO13 ? 1 : 'NULL') . ", '$condicion13', '$parametros13', '$observacion13', $id),
               (NULL, 14, '$now', '$f14', " . ($SI14 ? 1 : 'NULL') . ", " . ($NA14 ? 1 : 'NULL') . ", " . ($NO14 ? 1 : 'NULL') . ", '$condicion14', '$parametros14', '$observacion14', $id)";
            $resGuardar = mysqli_query($conexion,$sqlGuardar);
            if($resGuardar){

                $observaciones = "El cambio del estado del equipo esta detallado en el Check List - ".$folio;

                if(empty($vavID)){
                }else{
                    $sqlUpdateEquipo = "UPDATE equipamiento_hbqp SET estadoEquipo = '$opcionEstado' WHERE id = $vavID";
                    $resUpdateEquipo = mysqli_query($conexion, $sqlUpdateEquipo);
                    if($resUpdateEquipo){
                        $existeRegistroObservacion = compruebaRegistroObservacion($vavID);

                        if($existeRegistroObservacion == "Si"){
                            $sqlElimina = "DELETE FROM historial_cambios_equipos_observacion WHERE idEquipo = $vavID";
                            $resElimina = mysqli_query($conexion, $sqlElimina);
                        }

                        $existeRegistroFalla = compruebaRegistroFalla($vavID);
                        if($existeRegistroFalla == "Si"){
                            $sqlElimina = "DELETE FROM historial_cambios_equipos_falla WHERE idEquipo = $vavID";
                            $resElimina = mysqli_query($conexion, $sqlElimina);
                        }
                        if($opcionEstado == 'Fuera de Servicio'){
                            $sqlInsert = "INSERT INTO historial_cambios_equipos_falla (estado, observaciones, idUsuarioCambia, tipoUsuario, idEquipo, idCategoria,nomEquipo) 
                        VALUES ('$opcionEstado', '$observaciones', $idUsuarioPost, '$rolUsuario2', $vavID, $idCategoria,'$controlador')";
                            $resInsert = mysqli_query($conexion, $sqlInsert);
                        }

                        //INSERTAMOS EN LA TABLA HISTORIAL DE CAMBIOS
                        $sqlInsert = "INSERT INTO historial_cambios_equipos (estado, observaciones, idUsuarioCambia, tipoUsuario, idEquipo, idCategoria,nombreEquipo) 
                    VALUES ('$opcionEstado', '$observaciones', $idUsuarioPost, '$rolUsuario2', $vavID, $idCategoria,'$controlador')";
                        $resInsert = mysqli_query($conexion, $sqlInsert);

                        $sqlInsert = "INSERT INTO historial_cambios_equipos_respaldo (estado, observaciones , idUsuarioCambia, tipoUsuario, idEquipo, idCategoria,nombreEquipo) 
                    VALUES ('$opcionEstado', '$observaciones', $idUsuarioPost, '$rolUsuario2', $vavID, $idCategoria,'$controlador')";
                        $resInsert = mysqli_query($conexion, $sqlInsert);
                    }
                }

                if(empty($idTareaP)){
                    header('Location: ../'.$ruta.'/visualizardetalles.php?exitoCheckListVAVSinTarea=true');
                }else{
                    $queryAccion = "UPDATE tareas_carta_gantt SET inicioRealTareaP = '" . $now2 . "', porcentajeTareaP = 50, iniciadaTareaP = 1 WHERE idTareaP = " . $idTareaP;
                    $resAccion = mysqli_query($conexion,$queryAccion);
                    if($resAccion){
                        header('Location: ../'.$ruta.'/visualizardetalles.php?exitoCheckListVAVTarea=true');
                    }else{
                        header('Location: ../'.$ruta.'/visualizardetalles.php?errorCheckListVAVTarea=true');
                    }
                }

            }else{
                header('Location: ../'.$ruta.'/visualizardetalles.php?errorCheckListVAV=true');
            }
        }else{
            header('Location: ../'.$ruta.'/visualizardetalles.php?errorCheckListVAV1=true');
        }
    }else{
        header('Location: ../'.$ruta.'/visualizardetalles.php?errorCheckListVAV2=true');
    }
}else{
    header('Location: ../'.$ruta.'/visualizardetalles.php?errorCheckListVAV3=true');
}