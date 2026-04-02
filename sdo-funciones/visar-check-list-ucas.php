
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
} else if($rolUsuario2 == "oficina tecnica"){
    $tipoUsuario = 'oficina tecnica';
    $rutaDireccion = "sdo-oficinaTecnica";
}else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}


if(isset($_POST['idCheck'])){
    $now = date('Y-m-d H:i:s');
    $idOrden = $_POST['idCheck'];
    $contra = $_POST['contra'];
    $jefe = obtenerPasswordJefeVisado($nomUsuario);
    $finPlanTareaPMod = date('Y-m-d');
    $idTareaP = $_POST['idTareaP'];

//    echo $idOrden.'<br>';
//    echo $contra.'<br>';
//    echo $jefe.'<br>';
//    echo $finPlanTareaPMod.'<br>';
//    echo $idTareaP.'<br>';
//    echo $nomUsuario.'<br>';
//    exit();

    if(empty($idTareaP) || $idTareaP == ''){
       $idTareaP = 0;
    }

    if($jefe == $contra){
        $sql = "UPDATE datos_checklist_ucas SET isVisado = 1, nombreVisado = '$nomUsuario', fechaVisado = '$now' WHERE id = $idOrden";
        $res = mysqli_query($conexion,$sql);
        if($res){
            if($idTareaP > 0){
                $queryAccion = "UPDATE tareas_carta_gantt SET finRealTareaP = '" . $finPlanTareaPMod . "', porcentajeTareaP = 100, finalizadaTareaP = 1 WHERE idTareaP = " . $idTareaP;
                $resPlan = mysqli_query($conexion, $queryAccion);
                if($resPlan){
                    header('Location: ../'.$rutaDireccion . '/visualizardetallesterminadas.php?exitoVisarCheckListConPorcentaje=true');
                }else{
                    header('Location: ../'.$rutaDireccion . '/visualizardetallesterminadas.php?errorVisarJefes=true');
                }
            }else{
                header('Location: ../'.$rutaDireccion . '/visualizardetallesterminadas.php?exitoVisarCheckList=true');
            }
        }else{
            header('Location: ../'.$rutaDireccion . '/visualizardetallesterminadas.php?errorVisarJefes=true');
        }

//        echo 'jefecito';
    }else{
        $admin = obtenerPasswordAdminVisado($nomUsuario);
        if($admin == $contra){
            $sqlAdmin = "UPDATE datos_checklist_ucas SET isVisado = 1, nombreVisado = '$nomUsuario', fechaVisado = '$now' WHERE id = $idOrden";
            $resAdmin = mysqli_query($conexion,$sqlAdmin);
            if($resAdmin){
                if($idTareaP > 0){
                    $queryAccion = "UPDATE tareas_carta_gantt SET finRealTareaP = '" . $finPlanTareaPMod . "', porcentajeTareaP = 100, finalizadaTareaP = 1 WHERE idTareaP = " . $idTareaP;
                    $resPlan = mysqli_query($conexion, $queryAccion);
                    if($resPlan){
                        header('Location: ../'.$rutaDireccion . '/visualizardetallesterminadas.php?exitoVisarCheckListConPorcentaje=true');
                    }else{
                        header('Location: ../'.$rutaDireccion . '/visualizardetallesterminadas.php?errorVisarAdmin=true');
                    }
                }else{
                    header('Location: ../'.$rutaDireccion . '/visualizardetallesterminadas.php?exitoVisarCheckList=true');
                }
            }else{
                header('Location: ../'.$rutaDireccion . '/visualizardetallesterminadas.php?errorVisarAdmin=true');
            }
        }else{
            header('Location: ../'.$rutaDireccion . '/visualizardetallesterminadas.php?errorVisar2=true');
        }


//        echo 'admin';
    }
}