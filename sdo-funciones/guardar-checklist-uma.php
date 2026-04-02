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
    $UMA = $_POST['UMA'];
    $observacionesGenerales = $_POST['observacionesGenerales'];
    $observacionesGenerales = $conexion->real_escape_string($observacionesGenerales);


    $now = date('Y-m-d H:i:s');
    $now2 = date('Y-m-d');

    $idTareaP = $_POST['idTareaP'];
    $idCategoria = $_POST['idCategoria'];
    $opcionEstado = $_POST['estadoEquipo'];
    $idUsuarioPost = $_POST['idUsuario'];
    $rolUsuarioPost = $_POST['rolUsuario'];
    $nombreEquipoInput = $_POST['nombreEquipoInput'];


    $sql = "INSERT INTO `datos_uma`(`idUma`,fecha, `ecbos`, `macaddress`, `controlador`, `redComunicación`, `sistemaosub`, `folio`, `obsGeneral`, `idTG`,idEquipamiento) 
    VALUES (NULL,'$now','$ecbos','$macaddress','$controlador','$redComunicacion','$nombreEquipoInput','NULL','$observacionesGenerales',$idTG,$UMA)";
   // echo $sql;
    $res = mysqli_query($conexion, $sql);
    if($res){
        $id = mysqli_insert_id($conexion);
        $folio = 'CHECKLIST-UMA-'.$id;
        $update = "UPDATE `datos_uma` SET `folio`='$folio' WHERE `idUma` = $id";
        $resUpdate = mysqli_query($conexion,$update);
        if($resUpdate){
            $todobien = true;

            // Número de preguntas que tienes
            $numPreguntas = 48; // según tu array

            for ($i = 1; $i <= $numPreguntas; $i++) {
                // Capturar datos con validación básica
                $respuesta = isset($_POST["f$i"]) ? mysqli_real_escape_string($conexion, $_POST["f$i"]) : '';
                $si = isset($_POST["SI$i"]) ? 1 : 0;
                $na = isset($_POST["NA$i"]) ? 1 : 0;
                $no = isset($_POST["NO$i"]) ? 1 : 0;
                $condicion = isset($_POST["condicion$i"]) ? mysqli_real_escape_string($conexion, $_POST["condicion$i"]) : '';
                $parametros = isset($_POST["parametros$i"]) ? mysqli_real_escape_string($conexion, $_POST["parametros$i"]) : '';
                $observacion = isset($_POST["observacion$i"]) ? mysqli_real_escape_string($conexion, $_POST["observacion$i"]) : '';


                // Insertar en la tabla
                $sqlRespuestas = "INSERT INTO respuestas_uma (id,item_id,fecha,f, si, na, no, condicion, parametros, observacion,idUMA)
                VALUES (NULL,$i,'$now','$respuesta', $si, $na, $no, '$condicion', '$parametros', '$observacion',$id)";

                $resRepuestas = mysqli_query($conexion, $sqlRespuestas);

                if(!$resRepuestas){
                    $todobien = false;
                    error_log("Error al insertar respuesta $i: " . mysqli_error($conexion));
                }
            }

            if($todobien == true){
                $observaciones = "El cambio del estado del equipo esta detallado en el Check List - ".$folio;

                if(empty($UMA)) {
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
                        VALUES ('$opcionEstado', '$observaciones', $idUsuarioPost, '$rolUsuario2', $UMA, $idCategoria,'$nombreEquipoInput')";
                            $resInsert = mysqli_query($conexion, $sqlInsert);
                        }

                        //INSERTAMOS EN LA TABLA HISTORIAL DE CAMBIOS
                        $sqlInsert = "INSERT INTO historial_cambios_equipos (estado, observaciones, idUsuarioCambia, tipoUsuario, idEquipo, idCategoria,nombreEquipo) 
                    VALUES ('$opcionEstado', '$observaciones', $idUsuarioPost, '$rolUsuario2', $UMA, $idCategoria,'$nombreEquipoInput')";
                        $resInsert = mysqli_query($conexion, $sqlInsert);

                        $sqlInsert = "INSERT INTO historial_cambios_equipos_respaldo (estado, observaciones , idUsuarioCambia, tipoUsuario, idEquipo, idCategoria,nombreEquipo) 
                    VALUES ('$opcionEstado', '$observaciones', $idUsuarioPost, '$rolUsuario2', $UMA, $idCategoria,'$nombreEquipoInput')";
                        $resInsert = mysqli_query($conexion, $sqlInsert);
                    }
                }
                if(empty($idTareaP)){
                    header('Location: ../'.$ruta.'/visualizardetalles.php?exitoCheckListUMASinTarea=true');
                }else{
                    $queryAccion = "UPDATE tareas_carta_gantt SET inicioRealTareaP = '" . $now2 . "', porcentajeTareaP = 50, iniciadaTareaP = 1 WHERE idTareaP = " . $idTareaP;
                    $resAccion = mysqli_query($conexion,$queryAccion);
                    if($resAccion){
                        header('Location: ../'.$ruta.'/visualizardetalles.php?exitoCheckListUMATarea=true');
                    }else{
                        header('Location: ../'.$ruta.'/visualizardetalles.php?errorCheckListUMATarea=true');
                    }
                }
            }else{
                header('Location: ../'.$ruta.'/visualizardetalles.php?errorCheckListUMA=true');
            }
        }else{
            header('Location: ../'.$ruta.'/visualizardetalles.php?errorCheckListUMA=true');
        }
    }else{
        header('Location: ../'.$ruta.'/visualizardetalles.php?errorCheckListUMA=true');
    }
}else{
    header('Location: ../'.$ruta.'/visualizardetalles.php?errorCheckListUMA=true');
}