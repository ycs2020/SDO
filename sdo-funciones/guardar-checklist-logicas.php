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
    $controlador = $_POST['controlador'];
    $hojasprogram = $_POST['hojasprogram'] ?? 0;
    $titulossub = $_POST['titulossub'] ?? 0;
    $ordenbloques = $_POST['ordenbloques'] ?? 0;
    $recursosinutilizados = $_POST['recursosinutilizados'] ?? 0;
    $listaerrores = $_POST['listaerrores'] ?? 0;
    $logicacontrol = $_POST['logicacontrol'] ?? 0;
    $now = date('Y-m-d H:i:s');
    $obs = $_POST['obs'];


    $sql = "INSERT INTO `datos_checklist_logica`(`id`, `fecha`, `folio`, `ecbos`, `controlador`,obs, `hojasprogram`, `titulossub`, `ordenbloques`, `recursosinutilizados`, `listaerrores`, `logicacontrol`, `idTG`) 
    VALUES (null,'$now','NULL','$ecbos','$controlador','$obs',$hojasprogram,$titulossub,$ordenbloques,$recursosinutilizados,$listaerrores,$logicacontrol,$idTG)";
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

        $folio = 'CHK-'.$idRecepcion2.'-LOG';

        $sqlUpdate = "UPDATE datos_checklist_logica SET folio = '$folio' WHERE id = $id";
        $resUpdate = mysqli_query($conexion,$sqlUpdate);
        if($resUpdate){
            if($tipoUsuario != 'personal'){
                header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?exitoLogicaProgram=true');
            }else{
                header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?exitoLogicaProgram=true');
            }
        }

    }else{
        if($tipoUsuario != 'personal'){
            header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?errorLogicaProgram=true');
        }else{
            header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?errorLogicaProgram=true');
        }
    }
}