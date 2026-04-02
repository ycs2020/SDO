<?php
include 'conexion.php';
include 'funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


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
} else if ($rolUsuario2 == "jefe tecnico") {
    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $ruta = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $ruta = "sdo-directiva";
} else if ($rolUsuario2 == "mesa ayuda") {
    $ruta = "sdo-mesa-ayuda";
}else if ($rolUsuario2 == "oficina tecnica"){
    $ruta = "sdo-oficinaTecnica";
}else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if(isset($_POST['idCambio'], $_POST['rolusu'],$_POST['idUsuarioObs'], $_POST['fechaActual'], $_POST['fechaEstado'], $_POST['estadoEquipo'], $_POST['idEquipo'], $_POST['observacion'], $_POST['razonesTermino'], $_POST['nomEquipo'], $_POST['idUsuario1'], $_POST['idCategoria'], $_POST['tipoUsuario'])){

    $idCambio = $_POST['idCambio'];
    $fechaActual = $_POST['fechaActual'];
    $fechaEstado = $_POST['fechaEstado'];
    $estadoEquipo = $_POST['estadoEquipo'];
    $idEquipo = $_POST['idEquipo'];
    $idUsuario1 = $_POST['idUsuario1'];
    $idCategoria = $_POST['idCategoria'];
    $tipoUsuario = $_POST['tipoUsuario'];
    $observacion = $_POST['observacion'];
    $razonestermino = $_POST['razonesTermino'];
    $rolusu = $_POST['rolusu'];
    $idUsuarioObs = $_POST['idUsuarioObs'];
    $cambioEstado = $_POST['cambioEstado'];



    if($cambioEstado == '' || empty($cambioEstado)){
        $sql = "INSERT INTO `historial_observaciones_check`(`fechaObs`, `observacion`, `razonesCheck`, `estado`, `idUsuarioCambia`, `idEquipo`, `idCategoria`, `tipoUsuario`,`idUsuarioObs`,`tipoUsuarioObs`) VALUES ('$fechaEstado','$observacion','$razonestermino','$estadoEquipo','$idUsuario1','$idEquipo','$idCategoria','$tipoUsuario','$idUsuarioObs','$rolusu')";
        $res = mysqli_query($conexion,$sql);
    }else {
        $sql = "INSERT INTO `historial_observaciones_check`(`fechaObs`, `observacion`, `razonesCheck`, `estado`,estadoPostCheck, `idUsuarioCambia`, `idEquipo`, `idCategoria`, `tipoUsuario`,`idUsuarioObs`,`tipoUsuarioObs`) VALUES ('$fechaEstado','$observacion','$razonestermino','$estadoEquipo','$cambioEstado','$idUsuario1','$idEquipo','$idCategoria','$tipoUsuario','$idUsuarioObs','$rolusu')";
        $res = mysqli_query($conexion, $sql);

        $sqlUpdate = "UPDATE equipamiento_hbqp SET estadoEquipo = '$cambioEstado'";
        $resUpdate = mysqli_query($conexion, $sqlUpdate);
    }


    if($estadoEquipo = 'Fuera de Servicio'){
        $sqlDelete = "DELETE FROM historial_cambios_equipos_falla WHERE idEquipo = $idEquipo";
        $resDelete = mysqli_query($conexion,$sqlDelete);

    }

    $sql1 = "DELETE FROM historial_cambios_equipos WHERE idCambio = $idCambio";
    $res1 = mysqli_query($conexion,$sql1);



    if($res && $res1){
        header('Location: ../'.$ruta.'/carpeta-equipo.php?exito=true');
    }else{
        header('Location: ../'.$ruta.'/carpeta-equipo.php?error1=true');
    }

    // echo $sql;

}else{
    header('Location: ../'.$ruta.'/carpeta-equipo.php?error0=true');

}

