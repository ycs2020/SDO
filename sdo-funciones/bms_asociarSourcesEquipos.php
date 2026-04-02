<?php

include_once 'conexion.php';
include_once 'funciones.php';

date_default_timezone_set('America/Santiago');
$fechaNow = date('Y-m-d H:i:s');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);

$rutaUsuario = "";
// echo '';

if ($rolUsuario2 == "personal") {
    $rutaUsuario = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $rutaUsuario = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $rutaUsuario = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $rutaUsuario = "sdo-jefestecnicos";
    $idDepartamento = $_SESSION['idDepartamento'];
} else if ($rolUsuario2 == "administrador") {
    $rutaUsuario = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $rutaUsuario = "sdo-directiva";
} else if ($rolUsuario2 == "oficina tecnica") {
    $rutaUsuario = "sdo-oficinaTecnica";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $idEquipamiento = $_POST['idEquipamiento'];
    $idEquipamientoPadre = $_POST['idEquipamientoPadre'];
    if ($idEquipamiento > 0) {
        if ($idEquipamientoPadre > 0) {
            $_SESSION['idEquipo'] = $idEquipamientoPadre;

            $obtenerTipoMenu = obtenerTipoMenu($idEquipamientoPadre);
        } else {
            $_SESSION['idEquipo'] = $idEquipamiento;
            $obtenerTipoMenu = obtenerTipoMenu($idEquipamiento);
        }

        if($obtenerTipoMenu == 'nuevo'){
            $sitioWeb = 'equipo_menuNuevo.php';
        }else{
            $sitioWeb = 'equipo_menuAntiguo.php';
        }


        $sourcesParaAsociar = $_POST['sourcesParaAsociar'];

        if (is_array($sourcesParaAsociar)) {
            sort($sourcesParaAsociar);
            $countSource = count($sourcesParaAsociar);
            $contadorCheck = 0;
            $contadorMalo = 0;


            foreach ($sourcesParaAsociar as $source) {
//                echo $source . "<br><br>";

                if(!empty($source)){
                    $sqlSources = "INSERT INTO bms_asocia_source(idAsocia, sourceAsocia, idEquipoAsocia) VALUES (NULL,'$source',$idEquipamiento)";
                    $resSources = mysqli_query($conexion,$sqlSources);

                    if($resSources){
                        $contadorCheck++;
                    }else{
                        $contadorMalo++;
                    }

//                    echo $sqlSources . "<br><br>";
                }else{
                    $contadorMalo++;
                }
            }

//            exit;

            if($contadorCheck > 0){
                header("Location: ../$rutaUsuario/".$sitioWeb."?equiposAsociarSource=true");
            }else{
                header("Location: ../$rutaUsuario/".$sitioWeb."?errorAsociarSource=true");
            }

        } else {
            header("Location: ../$rutaUsuario/".$sitioWeb."?errorAsociarSource=true");
        }
    } else {
        header("Location: ../$rutaUsuario/equipamiento_hbqp.php?errorObtenerEquipo=true");

    }

} else {
    header("Location: ../$rutaUsuario/equipamiento_hbqp.php?errorObtenerEquipo=true");
}
