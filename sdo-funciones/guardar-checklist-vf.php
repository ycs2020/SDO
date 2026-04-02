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
    $vavID = $_POST['vavID'];
    $macaddress = $_POST['macaddress'];
    $controlador = $_POST['controlador'];
    $ubicacion = $_POST['ubicacion'];
    $network = $_POST['network'];
    $now = date('Y-m-d H:i:s');
    $obs = $_POST['obs'];
    $opcionEstado = $_POST['estadoEquipo'];
    $idCategoria = 2;
    $idUsuarioPost = $_POST['idUsuario'];
    $idTareaP = $_POST['idTareaP'];

    $now2 = date('Y-m-d');


    $limpiezaGeneral = (isset($_POST['limpiezaGeneral']) && $_POST['limpiezaGeneral'] >= 1) ? $_POST['limpiezaGeneral'] : 0;
    $estadoDispositivo = (isset($_POST['estadoDispositivo']) && $_POST['estadoDispositivo'] >= 1) ? $_POST['estadoDispositivo'] : 0;
    $limpiezaContactos = (isset($_POST['limpiezaContactos']) && $_POST['limpiezaContactos'] >= 1) ? $_POST['limpiezaContactos'] : 0;
    $verificacionVoltaje = (isset($_POST['verificacionVoltaje']) && $_POST['verificacionVoltaje'] >= 1) ? $_POST['verificacionVoltaje'] : 0;
    $chequeoRed = (isset($_POST['chequeoRed']) && $_POST['chequeoRed'] >= 1) ? $_POST['chequeoRed'] : 0;
    $verificacionFuncionamiento = (isset($_POST['verificacionFuncionamiento']) && $_POST['verificacionFuncionamiento'] >= 1) ? $_POST['verificacionFuncionamiento'] : 0;
    $verificacionLogicaControl = (isset($_POST['verificacionLogicaControl']) && $_POST['verificacionLogicaControl'] >= 1) ? $_POST['verificacionLogicaControl'] : 0;
    $verificacionIntegracionSoftware = (isset($_POST['verificacionIntegracionSoftware']) && $_POST['verificacionIntegracionSoftware'] >= 1) ? $_POST['verificacionIntegracionSoftware'] : 0;
    $chequeoProgramacion = (isset($_POST['chequeoProgramacion']) && $_POST['chequeoProgramacion'] >= 1) ? $_POST['chequeoProgramacion'] : 0;


    $sql = "INSERT INTO `datos_vf`(`id`, `folio`, `fecha`, `ecbos`, `macaddress`, `controlador`, `idEquipo`, `ubicacion`, `network`, `limpiezaGeneral`, `estadoDispositivo`, `limpiezaContactos`, `verificacionVoltaje`, `chequeoRed`, `verificacionFuncionamiento`, `verificacionLogicaControl`, `verificacionIntegracionSoftware`, `chequeoProgramacion`,`obs`, `idTG`) 
    VALUES (null,'NULL','$now','$ecbos','$macaddress','$controlador','$vavID','$ubicacion','$network','$limpiezaGeneral','$estadoDispositivo','$limpiezaContactos','$verificacionVoltaje','$chequeoRed','$verificacionFuncionamiento','$verificacionLogicaControl','$verificacionIntegracionSoftware','$chequeoProgramacion','$obs','$idTG')";

    $res = mysqli_query($conexion, $sql);
    if($res){
        $id = mysqli_insert_id($conexion);
        $folio = 'CHECKLIST-VF-'.$id;

        $sqlUpdate = "UPDATE datos_vf SET folio='$folio' WHERE id=$id";
        $resUpdate = mysqli_query($conexion, $sqlUpdate);

        if($resUpdate){

            $observaciones = "El cambio del estado del equipo esta detallado en el Check List - ".$folio;

            if(empty($vavID)) {
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
                header('Location: ../'.$ruta.'/visualizardetalles.php?exitoCheckListVFSinTarea=true');
            }else{
                $queryAccion = "UPDATE tareas_carta_gantt SET inicioRealTareaP = '" . $now2 . "', porcentajeTareaP = 50, iniciadaTareaP = 1 WHERE idTareaP = " . $idTareaP;
                $resAccion = mysqli_query($conexion,$queryAccion);
                if($resAccion){
                    header('Location: ../'.$ruta.'/visualizardetalles.php?exitoIngresoVF=true');
                }else{
                    header('Location: ../'.$ruta.'/visualizardetalles.php?errorCheckListVFTarea=true');
                }
            }
        }else{
            header('Location: ../'.$ruta.'/visualizardetalles.php?errorIngresoUpdate=true');
            exit();
        }
    }else{
        header('Location: ../'.$ruta.'/visualizardetalles.php?errorInsert=true');
        exit();
    }
}else{
    header('Location: ../'.$ruta.'/visualizardetalles.php?errorGeneral=true');
    exit();
}