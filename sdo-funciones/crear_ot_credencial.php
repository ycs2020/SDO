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



if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $idRegistroTarjeta = $_POST['idRegistroTarjeta'];
    $activacionCredencial3 = $_POST['activacionCredencial3'];
    $usuarioCreador = $_POST['usuarioCreador'];
    $funcionarioOT = $_POST['funcionarioOT'];
    $turnoAsignado = $_POST['turnoAsignado'];
    $fechaProgramacion = $_POST['fechaProgramacion'];
    $nombreCredencial = $_POST['nombreCredencial'];
    $apellidoCredencial = $_POST['apellidoCredencial'];
    $rutCredencial = $_POST['rutCredencial'];
    $nombreCompleto = $nombreCredencial.' '.$apellidoCredencial;


    if($turnoAsignado == 1){
        $turnoReal = "Personal Diurno";
    }else if($turnoAsignado == 2){
        $turnoReal = "Personal Nocturno";
    }else{
        $turnoReal = "Error";
    }

    if ($activacionCredencial3 == 2) {
        $texto = "Habilitar";
    } else if ($activacionCredencial3 == 3) {
        $texto = "Deshabilitar";
    } else {
        $texto = "Error";
    }

    $textoGrande = "Se solicita $texto la credencial de $nombreCompleto rut: $rutCredencial esto a solicitud de la Unidad de Personal";

    $now = date('Y-m-d H:i:s');

    $sql = "INSERT INTO `trabajos_general`(`idTG`, `folioTG`, `solicitanteTG`, `detalleTG`, `detalleTurnoCamara`, `detalleTurnoLLE`, `detalleTurnoCA`, `detalleTurnoRondas`, `detalleOtros`, `fechaTG`, `estadoTG`, `turnoTG`, `tecnico1`, `fechaProgramacion`, `ticketAsociado`, `tipoTrabajo`, `fechaTermino`, `departamento`, `formularioRondas`, `isVisado`, `nombreVisado`, `fechaVisado`, `idRecepcionDouc`, `descripcion`, `servicio`, `idTareaProgramada`, `idEquipamiento`) 
    VALUES (NULL,'NULL','$usuarioCreador','','','','$textoGrande','','','$now','Trabajo Ingresado','$turnoReal','$funcionarioOT','$fechaProgramacion','0','',NULL,1,0,0,'',null,null,'Realizar trabajos con la credencial',22,0,0)";

    $res = mysqli_query($conexion, $sql);

    if($res){

        $id = mysqli_insert_id($conexion);

        $modificar = "UPDATE trabajos_general SET folioTG = 'OT-I-$id' WHERE idTG = $id";
        $resModificar = mysqli_query($conexion, $modificar);


        $sqlInsert = "INSERT INTO `datos_credencial_funcionarios`(`id`, `idRegistroTarjeta`, `idTG`) 
        VALUES (null,$idRegistroTarjeta,$id)";

        $resInsert = mysqli_query($conexion, $sqlInsert);


        header('Location: ../'.$rutaDireccion.'/credenciales_administracion.php?exitoCreacionOT=true');
        exit();
    }else{
        header('Location: ../'.$rutaDireccion.'/credenciales_administracion.php?erroCreacionOT=true');
        exit();
    }

}else{
    header('Location: ../'.$rutaDireccion.'/credenciales_administracion.php?errorGeneral=true');
    exit();
}