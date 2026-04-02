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
$rutaDireccion = "";

if ($rolUsuario2 == "personal") {
    $rutaDireccion = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $rutaDireccion = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $rutaDireccion = "sdo-bodega-panol";
}else if ($rolUsuario2 == "jefe tecnico") {
    $rutaDireccion = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $rutaDireccion = "sdo-administrador";
} else if($rolUsuario2 == "directiva"){
    $rutaDireccion = "sdo-directiva";
} else if($rolUsuario2 == 'oficina tecnica'){
    $rutaDireccion = "sdo-oficinaTecnica";
}else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if(isset($_POST['responsable']) || isset($_POST['obs'])){
    $responsable = $_POST['responsable'];
    $asistentes = $_POST['asistentes'];

    if (is_array($responsable)) {
        sort($responsable);
        // Unir los elementos del array con ' - ' como delimitador
        $responsable = implode(' - ', $responsable);
    } else {
        $responsable = '';
    }

    if (is_array($asistentes)) {
        sort($asistentes);
        // Unir los elementos del array con ' - ' como delimitador
        $asistentes = implode(' - ', $asistentes);
    } else {
        $asistentes = '';
    }
    $asistentes = trim($asistentes);
    $responsable = trim($responsable);
    $obs = $_POST['obs'];
    $now = date('Y-m-d H:i:s');
    $turno = $_POST['turno'];
    $tema = $_POST['tema'];
    $inicio = $_POST['inicio'];
    $fin = $_POST['fin'];
    $sql = "INSERT INTO `registro_general_comunicacion`(`id`, `responsables`,asistentes, `obs`, `fecha`,turno,tema,inicio,fin) 
    VALUES (NULL,'$responsable','$asistentes','$obs','$now','$turno','$tema','$inicio','$fin')";
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
        $folio = 'RGC-'.$idRecepcion2;

        $sqlUpdate = "UPDATE registro_general_comunicacion SET folio = '$folio' WHERE id = $id";
        $resUpdate = mysqli_query($conexion,$sqlUpdate);
        if($resUpdate){
            header('Location: ../'.$rutaDireccion . '/registros_comunicacion.php?exito=true');
        }else{
            header('Location: ../'.$rutaDireccion . '/registros_comunicacion.php?error2=true');
        }
    }else{
        header('Location: ../'.$rutaDireccion . '/registros_comunicacion.php?error=true');
    }
}else{
    header('Location: ../'.$rutaDireccion . '/registros_comunicacion.php?error1=true');
}

