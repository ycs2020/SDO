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


if(isset($_POST['id'])){
    $id = $_POST['id'];
    $ecbos = $_POST['ecbos'];
    $controlador = $_POST['controlador'];
    $hojasprogram = $_POST['hojasprogram'] ?? 0;
    $titulossub = $_POST['titulossub'] ?? 0;
    $ordenbloques = $_POST['ordenbloques'] ?? 0;
    $recursosinutilizados = $_POST['recursosinutilizados'] ?? 0;
    $listaerrores = $_POST['listaerrores'] ?? 0;
    $logicacontrol = $_POST['logicacontrol'] ?? 0;
    $obs = $_POST['obsLogica'];

    $sql = "UPDATE datos_checklist_logica SET ecbos = '$ecbos',controlador = '$controlador', obs = '$obs',hojasprogram = $hojasprogram,titulossub = $titulossub, ordenbloques = $ordenbloques,recursosinutilizados = $recursosinutilizados,listaerrores = $listaerrores,logicacontrol = $logicacontrol WHERE id = $id ";
    $res = mysqli_query($conexion, $sql);

    if($res){
        if($tipoUsuario != 'personal'){
            header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?exitoModificarLogicaProgramacion=true');
        }else{
            header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?exitoModificarLogicaProgramacion=true');
        }
    }else{
        if($tipoUsuario != 'personal'){
            header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?errorModificarLogicaProgramacion=true');
        }else{
            header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?errorModificarLogicaProgramacion=true');
        }
    }

}