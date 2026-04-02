<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];
$idDepartamento = $_SESSION['idDepartamento'];

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

if(isset($_POST['idTG'])) {
    $idTG = $_POST['idTG'];
    $ecbos = $_POST['ecbos'];
    $ecbID = $_POST['ecbID'];
    $controlador = $_POST['controlador'];
    $macaddress = $_POST['macaddress'];
    $network = $_POST['network'];
    $limpiezadispositivo = $_POST['limpiezadispositivo'] ?? 0;
    $verficarEstadoDis = $_POST['verficarEstadoDis'] ?? 0;
    $limpiezaContactosElec = $_POST['limpiezaContactosElec'] ?? 0;
    $verificarVoltaje = $_POST['verificarVoltaje'] ?? 0;
    $chequeoComunicacion = $_POST['chequeoComunicacion'] ?? 0;
    $verificacionFuncionamiento = $_POST['verificacionFuncionamiento'] ?? 0;
    $verificacionLogicaControl = $_POST['verificacionLogicaControl'] ?? 0;
    $verificacionIntegracion = $_POST['verificacionIntegracion'] ?? 0;
    $chequeoRutina = $_POST['chequeoRutina'] ?? 0;
    $now = date('Y-m-d H:i:s');
    $obs = $_POST['obs'];
    $opcionEstado = $_POST['opcionEstado'];
    $idCategoria = $_POST['idCategoria'];
    $idUsuarioPost = $_POST['idUsuario'];


    $sql = "INSERT INTO `datos_checklist_controlador_ecb`(`id`, `fecha`, `folio`, `ecbos`, `controlador`, `macaddress`, `network`, `obs`, `limpiezadispositivo`, `verficarEstadoDis`, `limpiezaContactosElec`, `verificarVoltaje`, `chequeoComunicacion`, `verificacionFuncionamiento`, `verificacionLogicaControl`, `verificacionIntegracion`, `chequeoRutina`,idTG,idEquipamiento) 
    VALUES (NULL,'$now','NULL','$ecbos','$controlador','$macaddress','$network','$obs','$limpiezadispositivo','$verficarEstadoDis','$limpiezaContactosElec','$verificarVoltaje','$chequeoComunicacion','$verificacionFuncionamiento','$verificacionLogicaControl','$verificacionIntegracion','$chequeoRutina',$idTG,$ecbID)";
    $res = mysqli_query($conexion,$sql);
    if($res){
        $id = mysqli_insert_id($conexion);

        if ($id < 10) {
            $idRecepcion2 = "000" . $id;
        } else if ($idTG > 9 && $id < 100) {
            $idRecepcion2 = "00" . $id;
        } else if ($idTG > 99 && $id < 1000) {
            $idRecepcion2 = "0" . $id;
        } else if ($idTG > 999) {
            $idRecepcion2 = $id;
        } else {
            $idRecepcion2 = $id;
        }

        $folio = 'CHK-'.$idRecepcion2.'-CONTROL-ECB';

        $sqlUpdate = "UPDATE datos_checklist_controlador_ecb SET folio = '$folio' WHERE id = $id";
        $resUpdate = mysqli_query($conexion,$sqlUpdate);
        if($resUpdate){
            $observaciones = "El cambio del estado del equipo esta detallado en el Check List - ".$folio;

            if(empty($ecbID)){
            }else{
                $sqlUpdateEquipo = "UPDATE equipamiento_hbqp SET estadoEquipo = '$opcionEstado' WHERE id = $ecbID";
                $resUpdateEquipo = mysqli_query($conexion, $sqlUpdateEquipo);
                if($resUpdateEquipo){
                    $existeRegistroObservacion = compruebaRegistroObservacion($ecbID);

                    if($existeRegistroObservacion == "Si"){
                        $sqlElimina = "DELETE FROM historial_cambios_equipos_observacion WHERE idEquipo = $ecbID";
                        $resElimina = mysqli_query($conexion, $sqlElimina);
                    }

                    $existeRegistroFalla = compruebaRegistroFalla($ecbID);
                    if($existeRegistroFalla == "Si"){
                        $sqlElimina = "DELETE FROM historial_cambios_equipos_falla WHERE idEquipo = $ecbID";
                        $resElimina = mysqli_query($conexion, $sqlElimina);
                    }
                    if($opcionEstado == 'Fuera de Servicio'){
                        $sqlInsert = "INSERT INTO historial_cambios_equipos_falla (estado, observaciones, idUsuarioCambia, tipoUsuario, idEquipo, idCategoria,nomEquipo) 
                        VALUES ('$opcionEstado', '$observaciones', $idUsuarioPost, '$rolUsuario2', $ecbID, $idCategoria,'$controlador')";
                        $resInsert = mysqli_query($conexion, $sqlInsert);
                    }

                    //INSERTAMOS EN LA TABLA HISTORIAL DE CAMBIOS
                    $sqlInsert = "INSERT INTO historial_cambios_equipos (estado, observaciones, idUsuarioCambia, tipoUsuario, idEquipo, idCategoria,nombreEquipo) 
                    VALUES ('$opcionEstado', '$observaciones', $idUsuarioPost, '$rolUsuario2', $ecbID, $idCategoria,'$controlador')";
                    $resInsert = mysqli_query($conexion, $sqlInsert);

                    $sqlInsert = "INSERT INTO historial_cambios_equipos_respaldo (estado, observaciones , idUsuarioCambia, tipoUsuario, idEquipo, idCategoria,nombreEquipo) 
                    VALUES ('$opcionEstado', '$observaciones', $idUsuarioPost, '$rolUsuario2', $ecbID, $idCategoria,'$controlador')";
                    $resInsert = mysqli_query($conexion, $sqlInsert);
                }
            }

            if($tipoUsuario != 'personal'){
                header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?exitoControladorECB=true');
            }else{
                header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?exitoControladorECB=true');
            }
        }
    }else{
        if($tipoUsuario != 'personal'){
            header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?errorControladorECB=true');
        }else{
            header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?errorControladorECB=true');
        }
    }
}