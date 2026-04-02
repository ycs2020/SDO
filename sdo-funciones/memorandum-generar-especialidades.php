<?php

include_once 'conexion.php';
include_once 'funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('America/Santiago');

//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

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
} else if($rolUsuario2 == "oficina tecnica") {
    $tipoUsuario = 'oficina tecnica';
    $rutaDireccion = "sdo-oficinaTecnica";
}else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}


if(isset($_POST['materiaMemo'],$_POST['fechaMemo'],$_POST['nomEmisorMemo'],$_POST['cargoEmisorMemo'],$_POST['destinatarioMemo'],$_POST['textoMemo'],$_POST['idDepartamentoMemo'])){

    $materiaMemo = trim($_POST['materiaMemo']);
    $fechaMemo = trim($_POST['fechaMemo']);
    $nomEmisorMemo = trim($_POST['nomEmisorMemo']);
    $cargoEmisorMemo = trim($_POST['cargoEmisorMemo']);
    $destinatarioMemo = trim($_POST['destinatarioMemo']);
    $textoMemo = $_POST['textoMemo'];
    $idDepartamentoMemo = $_POST['idDepartamentoMemo'];

    if(!empty($materiaMemo) && !empty($fechaMemo) && !empty($nomEmisorMemo) && !empty($cargoEmisorMemo) && !empty($destinatarioMemo) && $idDepartamentoMemo > 0){

        if($destinatarioMemo == 'paolaNunez'){

            $nomDestinatario = 'Paola Nuñez Cárdenas';
            $cargoDestinatario = trim($_POST['cargoDestinatarioMemo']);

            if($cargoDestinatario == 'Subdirectora de Operaciones' || $cargoDestinatario == 'Director (s)'){
                $cargoDestinatario2 = $cargoDestinatario;
            }else{
                header('Location: ../'.$rutaDireccion . '/memorandum_listado.php?errorGenerarMemo1=true');
            }


        }else if($destinatarioMemo == 'otro'){

            $nomDestinatario = trim($_POST['nomOtroDestinatarioMemo']);
            $cargoDestinatario = trim($_POST['cargoOtroDestinatarioMemo']);

            if(!empty($nomDestinatario) && !empty($cargoDestinatario)){
                $cargoDestinatario2 = $cargoDestinatario;
            }else{
                header('Location: ../'.$rutaDireccion . '/memorandum_listado.php?errorGenerarMemo2=true');
            }

        }else{
            header('Location: ../'.$rutaDireccion . '/memorandum_listado.php?errorGenerarMemo3=true');
        }


        if (!empty($textoMemo)) {

            $textoMemo = str_replace("'", "\'", $textoMemo);
        }else{
            header('Location: ../'.$rutaDireccion . '/memorandum_listado.php?errorGenerarMemo4=true');
        }

        $numMemo = obtenerNumeroMemoEspecialidades($idDepartamentoMemo);
//        $numMemo = 1;

        if($numMemo > 0){
            $nowMemo = date('Y-m-d H:i:s');

            $nomEmisorMemo = mb_convert_case($nomEmisorMemo,MB_CASE_TITLE,'UTF-8');
            $cargoEmisorMemo = capitalizarCargosMemo($cargoEmisorMemo);
            $nomDestinatario = mb_convert_case($nomDestinatario,MB_CASE_TITLE,'UTF-8');
            $cargoDestinatario2 = capitalizarCargosMemo($cargoDestinatario2);

            $existeDestinatario = buscarDestinatarioEspecialidades($nomDestinatario,$cargoDestinatario2,$idDepartamentoMemo);

            if($existeDestinatario == 0){
                $sqlDestinatario = "INSERT INTO destinatario_memo_especialidades(id,nomDesti,cargoDesti,idDepartamento) VALUES (NULL,'$nomDestinatario','$cargoDestinatario2',$idDepartamentoMemo)";

                $resDestinatario = mysqli_query($conexion,$sqlDestinatario);
            }


            $sqlMemo = "INSERT INTO memorandum_especialidades (idMemo,materiaMemo,fechaCreacionMemo,nomEmisorMemo,cargoEmisorMemo,nomDestinatarioMemo,cargoDestinatarioMemo,textoMemo,numeroMemo,fechaEnvioMemo,idDepartamentoMemo) VALUES (NULL,'".$materiaMemo."','".$nowMemo."','".$nomEmisorMemo."','".$cargoEmisorMemo."','".$nomDestinatario."','".$cargoDestinatario2."','".$textoMemo."',NULL,NULL,$idDepartamentoMemo)";

//            echo $sqlMemo;

            $resMemo = mysqli_query($conexion , $sqlMemo);

            if($resMemo){
                header('Location: ../'.$rutaDireccion . '/memorandum_listado.php?exitoGenerarMemo=true');
            }else{
                header('Location: ../'.$rutaDireccion . '/memorandum_listado.php?errorGenerarMemo5=true');
            }

        }else{
            header('Location: ../'.$rutaDireccion . '/memorandum_listado.php?errorGenerarMemo6=true');
        }


    }else{
        header('Location: ../'.$rutaDireccion . '/memorandum_listado.php?errorGenerarMemo7=true');
    }
}else{
    header('Location: ../'.$rutaDireccion . '/memorandum_listado.php?errorGenerarMemo8=true');
}