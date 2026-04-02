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
    $controladorAntiguo = $_POST['controladorAntiguo'];
    $idVF = $_POST['idVF'];
    if($vavID == '' || empty($vavID)){
        $vavID = 0;
    }
    $idEquipamiento = $_POST['idEquipamiento'];
    $folio = $_POST['folio'];
    $idUsuario = $_POST['idUsuario'];
    $idCategoria = 2;

    $opcionEstado = $_POST['opcionEstado'];

    $limpiezaGeneral = (isset($_POST['limpiezaGeneral']) && $_POST['limpiezaGeneral'] >= 1) ? $_POST['limpiezaGeneral'] : 0;
    $estadoDispositivo = (isset($_POST['estadoDispositivo']) && $_POST['estadoDispositivo'] >= 1) ? $_POST['estadoDispositivo'] : 0;
    $limpiezaContactos = (isset($_POST['limpiezaContactos']) && $_POST['limpiezaContactos'] >= 1) ? $_POST['limpiezaContactos'] : 0;
    $verificacionVoltaje = (isset($_POST['verificacionVoltaje']) && $_POST['verificacionVoltaje'] >= 1) ? $_POST['verificacionVoltaje'] : 0;
    $chequeoRed = (isset($_POST['chequeoRed']) && $_POST['chequeoRed'] >= 1) ? $_POST['chequeoRed'] : 0;
    $verificacionFuncionamiento = (isset($_POST['verificacionFuncionamiento']) && $_POST['verificacionFuncionamiento'] >= 1) ? $_POST['verificacionFuncionamiento'] : 0;
    $verificacionLogicaControl = (isset($_POST['verificacionLogicaControl']) && $_POST['verificacionLogicaControl'] >= 1) ? $_POST['verificacionLogicaControl'] : 0;
    $verificacionIntegracionSoftware = (isset($_POST['verificacionIntegracionSoftware']) && $_POST['verificacionIntegracionSoftware'] >= 1) ? $_POST['verificacionIntegracionSoftware'] : 0;
    $chequeoProgramacion = (isset($_POST['chequeoProgramacion']) && $_POST['chequeoProgramacion'] >= 1) ? $_POST['chequeoProgramacion'] : 0;

    if($controlador == '' || empty($controlador)){
        $sqlUpdate = "UPDATE datos_vf SET ecbos = '$ecbos',macaddress = '$macaddress',ubicacion = '$ubicacion',network = '$network',`limpiezaGeneral`='$limpiezaGeneral',`estadoDispositivo`='$estadoDispositivo',`limpiezaContactos`='$limpiezaContactos',`verificacionVoltaje`='$verificacionVoltaje',`chequeoRed`='$chequeoRed',`verificacionFuncionamiento`='$verificacionFuncionamiento',`verificacionLogicaControl`='$verificacionLogicaControl',`verificacionIntegracionSoftware`='$verificacionIntegracionSoftware',`chequeoProgramacion`='$chequeoProgramacion',`obs`='$obs' WHERE id = $idVF";
    }else{
        $sqlUpdate = "UPDATE datos_vf SET controlador = '$controlador',idEquipo = '$vavID',ecbos = '$ecbos',macaddress = '$macaddress',ubicacion = '$ubicacion',network = '$network',`limpiezaGeneral`='$limpiezaGeneral',`estadoDispositivo`='$estadoDispositivo',`limpiezaContactos`='$limpiezaContactos',`verificacionVoltaje`='$verificacionVoltaje',`chequeoRed`='$chequeoRed',`verificacionFuncionamiento`='$verificacionFuncionamiento',`verificacionLogicaControl`='$verificacionLogicaControl',`verificacionIntegracionSoftware`='$verificacionIntegracionSoftware',`chequeoProgramacion`='$chequeoProgramacion',`obs`='$obs' WHERE id = $idVF";
    }

    $resUpdate = mysqli_query($conexion, $sqlUpdate);
    if($resUpdate){

        $observaciones = "El cambio del estado del equipo esta detallado en el Check List - ".$folio;

        if(empty($vavID)){
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
                        VALUES ('$opcionEstado', '$observaciones', $idUsuario, '$rolUsuario2', $vavID, $idCategoria,'$controladorAntiguo')";
                    $resInsert = mysqli_query($conexion, $sqlInsert);
                }

                //INSERTAMOS EN LA TABLA HISTORIAL DE CAMBIOS
                $sqlInsert = "INSERT INTO historial_cambios_equipos (estado, observaciones, idUsuarioCambia, tipoUsuario, idEquipo, idCategoria,nombreEquipo) 
                    VALUES ('$opcionEstado', '$observaciones', $idUsuario, '$rolUsuario2', $vavID, $idCategoria,'$controladorAntiguo')";
                $resInsert = mysqli_query($conexion, $sqlInsert);

                $sqlInsert = "INSERT INTO historial_cambios_equipos_respaldo (estado, observaciones , idUsuarioCambia, tipoUsuario, idEquipo, idCategoria,nombreEquipo) 
                    VALUES ('$opcionEstado', '$observaciones', $idUsuario, '$rolUsuario2', $vavID, $idCategoria,'$controladorAntiguo')";
                $resInsert = mysqli_query($conexion, $sqlInsert);

            }
        }

        header('Location: ../'.$ruta.'/visualizardetalles.php?exitoModificarIngresoVF=true');
        exit();
    }else{
        header('Location: ../'.$ruta.'/visualizardetalles.php?errorModificarIngresoVF=true');
        exit();
    }
}else{
    header('Location: ../'.$ruta.'/visualizardetalles.php?errorModificarGeneralVF=true');
    exit();
}