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

if(isset($_POST['id'])){
    $id = $_POST['id'];
    $ecbos = $_POST['ecbos'];
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
    $obsControlador = $_POST['obsControlador'];
    $ecbID = $_POST['ecbID'];
    $folio = $_POST['folio'];
    $opcionEstado = $_POST['estadoEquipo'];
    $idCategoria = $_POST['idCategoria'];
    $idUsuarioPost = $_POST['idUsuario'];

    $sql = "UPDATE datos_checklist_controlador_ecb SET idEquipamiento = $ecbID ,ecbos = '$ecbos',controlador = '$controlador',macaddress = '$macaddress',network = '$network',obs = '$obsControlador',limpiezadispositivo = $limpiezadispositivo,verficarEstadoDis = $verficarEstadoDis,limpiezaContactosElec = $limpiezaContactosElec,verificarVoltaje = $verificarVoltaje,chequeoComunicacion = $chequeoComunicacion,verificacionFuncionamiento = $verificacionFuncionamiento,verificacionLogicaControl = $verificacionLogicaControl,verificacionIntegracion = $verificacionIntegracion,chequeoRutina = $chequeoRutina WHERE id = $id";
    $res = mysqli_query($conexion,$sql);
    if($res){
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
            header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?exitoModiControladorECB=true');
        }else{
            header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?exitoModiControladorECB=true');
        }
    }else{
        if($tipoUsuario != 'personal'){
            header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?errorModiControladorECB=true');
        }else{
            header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?errorModiControladorECB=true');
        }
    }
}else{
    if($tipoUsuario != 'personal'){
        header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?errorModiGeneral=true');
    }else{
        header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?errorModiGeneral=true');
    }
}