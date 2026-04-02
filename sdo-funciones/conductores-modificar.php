<?php
include 'conexion.php';
include 'funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


//VARIABLES DE SESSION
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
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if(isset($_POST['idConductor'])){
    $idConductor = $_POST['idConductor'];
//    $sql = "SELECT * FROM conductores_movilizacion WHERE idConductor = $idConductor";
//    echo $sql;
//    exit;

    if($idConductor > 0){

        if(isset($_POST['modNombre'],$_POST['modRUT'],$_POST['antesRUT'])){

            $modNombre = trim($_POST['modNombre']);
            $modRUT = trim($_POST['modRUT']);
            $antesRut = trim($_POST['antesRUT']);

            if(!empty($modNombre) && !empty($modRUT) && !empty($antesRut)){

                if($modRUT == $antesRut){

                    $sqlModificar = "UPDATE conductores_movilizacion SET nomConductor = '".$modNombre."' WHERE idConductor = '".$idConductor."'";

                }else{
                    $buscarRUTConductor = buscarRUTConductor($modRUT);

                    if($buscarRUTConductor == 0){

                        $sqlModificar = "UPDATE conductores_movilizacion SET nomConductor = '".$modNombre."', rutConductor = '".$modRUT."' WHERE idConductor = '".$idConductor."'";

                    }else{
                        header('Location: ../'.$ruta.'/conductores_administracion.php?RutConductorExiste=true');
                    }
                }

//                echo $sqlModificar;
                $resModificar = mysqli_query($conexion, $sqlModificar);

                if($resModificar){
                    header('Location: ../'.$ruta.'/conductores_administracion.php?exitoModificarConductor=true');
                }else{
                    header('Location: ../'.$ruta.'/conductores_administracion.php?errorModificarConductor=true');
                }


            }else{
                header('Location: ../'.$ruta.'/conductores_administracion.php?errorModificarConductor=true');
            }

        }else{
            header('Location: ../'.$ruta.'/conductores_administracion.php?errorModificarConductor=true');
        }

    }else{
        header('Location: ../'.$ruta.'/conductores_administracion.php?errorModificarConductor=true');
    }

}


