<?php

include_once 'conexion.php';
include_once 'funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('America/Santiago');
$anioActual = date('Y');

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


if(isset($_POST['materiaMemo'],$_POST['fechaMemo'],$_POST['nomEmisorMemo'],$_POST['cargoEmisorMemo'],$_POST['destinatarioMemo'],$_POST['textoMemo'])){

    $materiaMemo = trim($_POST['materiaMemo']);
    $fechaMemo = trim($_POST['fechaMemo']);
    $nomEmisorMemo = trim($_POST['nomEmisorMemo']);
    $cargoEmisorMemo = trim($_POST['cargoEmisorMemo']);
    $destinatarioMemo = trim($_POST['destinatarioMemo']);
    $textoMemo = $_POST['textoMemo'];
    $nit = $_POST['nit'];

    if(!empty($materiaMemo) && !empty($fechaMemo) && !empty($nomEmisorMemo) && !empty($cargoEmisorMemo) && !empty($destinatarioMemo)){

        if($nomEmisorMemo == 'Rafael Mendoza Saavedra' && ($cargoEmisorMemo == 'Jefe Ingeniería Operacional' || $cargoEmisorMemo == 'Subdirector de Operaciones (s)')){
            $nomEmisor = $nomEmisorMemo;
            $cargoEmisor = $cargoEmisorMemo;
        }else{
            header('Location: ../'.$rutaDireccion . '/memorandum_listado.php?errorGenerarMemo=true');
        }

        if($nit == '' || empty($nit)){
            if($destinatarioMemo == 'paolaNunez'){

                $nomDestinatario = 'Paola Nuñez Cárdenas';
                $cargoDestinatario = trim($_POST['cargoDestinatarioMemo']);

                if($cargoDestinatario == 'Subdirectora de Operaciones' || $cargoDestinatario == 'Director (s)'){
                    $cargoDestinatario2 = $cargoDestinatario;
                }else{
                    header('Location: ../'.$rutaDireccion . '/memorandum_listado.php?errorGenerarMemo=true');
                }


            }else if($destinatarioMemo == 'otro'){

                $nomDestinatario = trim($_POST['nomOtroDestinatarioMemo']);
                $cargoDestinatario = trim($_POST['cargoOtroDestinatarioMemo']);

                $buscarNomDes = buscarNombDes($nomDestinatario);
                $buscarCargo = buscarCargo($cargoDestinatario);

                if($buscarCargo > 0 || $buscarNomDes > 0){
                    $nomDestinatario = trim($_POST['nomOtroDestinatarioMemo']);
                    $cargoDestinatario = trim($_POST['cargoOtroDestinatarioMemo']);
                }else{
                    $sql = "INSERT INTO destinatario_memo (id,nomDesti,cargoDesti) 
                VALUES (NULL,'$nomDestinatario',' $cargoDestinatario')";
                    $res = mysqli_query($conexion,$sql);
                    if($res){
                        $cargoDestinatario2 = $cargoDestinatario;
                    }else{
                        header('Location: ../'.$rutaDireccion . '/memorandum_listado.php?errorGenerarMemo=true');
                    }
                }

                if(!empty($nomDestinatario) && !empty($cargoDestinatario)){
                    $cargoDestinatario2 = $cargoDestinatario;
                }else{
                    header('Location: ../'.$rutaDireccion . '/memorandum_listado.php?errorGenerarMemo=true');
                }

            }else{
                header('Location: ../'.$rutaDireccion . '/memorandum_listado.php?errorGenerarMemo=true');
            }
        }else{
            $nomDestinatario = trim($_POST['destinatarioMemo']);
            $cargoDestinatarioMemoSelect = trim($_POST['cargoDestinatarioMemoSelect']);
            $cargoDestinatario2 = $cargoDestinatarioMemoSelect;
        }

        if (!empty($textoMemo)) {

            $textoMemo = str_replace("'", "\'", $textoMemo);
        }else{
            header('Location: ../'.$rutaDireccion . '/memorandum_listado.php?errorGenerarMemo=true');
        }

        $numeroMemo = obtenerNumeroMemoIngOperativa($anioActual);

//        $numMemo = obtenerNumeroMemoSDO();
//        $numMemo = 1;

 //       if($numMemo > 0){
            $nowMemo = date('Y-m-d H:i:s');

            $nomEmisor = mb_convert_case($nomEmisor,MB_CASE_TITLE,'UTF-8');
            $cargoEmisor = capitalizarCargosMemo($cargoEmisor);
            $nomDestinatario = mb_convert_case($nomDestinatario,MB_CASE_TITLE,'UTF-8');
            $cargoDestinatario2 = capitalizarCargosMemo($cargoDestinatario2);

            $sqlMemo = "INSERT INTO memorandum_sdo (idMemo,materiaMemo,fechaCreacionMemo,nomEmisorMemo,cargoEmisorMemo,nomDestinatarioMemo,cargoDestinatarioMemo,textoMemo,numeroMemo,fechaEnvioMemo) 
           VALUES (NULL,'".$materiaMemo."','".$nowMemo."','".$nomEmisor."','".$cargoEmisor."','".$nomDestinatario."','".$cargoDestinatario2."','".$textoMemo."',$numeroMemo,NULL)";

//            echo $sqlMemo;

            $resMemo = mysqli_query($conexion , $sqlMemo);

            if($resMemo){
                header('Location: ../'.$rutaDireccion . '/memorandum_listado.php?exitoGenerarMemo=true');

            }else{
                header('Location: ../'.$rutaDireccion . '/memorandum_listado.php?errorGenerarMemo=true');
            }

 //       }else{
  //          header('Location: ../'.$rutaDireccion . '/memorandum_listado.php?errorGenerarMemo=true');
 //       }


    }else{
        header('Location: ../'.$rutaDireccion . '/memorandum_listado.php?errorGenerarMemo=true');
    }
}else{
    header('Location: ../'.$rutaDireccion . '/memorandum_listado.php?errorGenerarMemo=true');
}