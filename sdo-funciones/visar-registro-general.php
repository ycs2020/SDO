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

if($_POST['id']){
    $idOrden = $_POST['id'];
    $contra = $_POST['passwordVisar'];
    $jefe = obtenerPasswordJefeVisado($nomUsuario);
    $personal = obstenerPasswordPersonalVisado($nomUsuario);
    $admin = obtenerPasswordAdminVisado($nomUsuario);
    $adminTec = obtenerPasswordAdminTecVisado($nomUsuario);
    $now = date('Y-m-d H:i:s');

    if($jefe == $contra){
        $jefe_cargo = obtenerCargoNom($nomUsuario);
        $sql = "INSERT INTO `visado_registro_comunicacion`(`id`, `nomVisado`, `fechaVisado`,cargo, `idRegistro`) 
        VALUES (NULL,'$nomUsuario','$now','$jefe_cargo',$idOrden)";
        $res = mysqli_query($conexion,$sql);

        if($res){
            header('Location: ../'.$rutaDireccion . '/registros_comunicacion.php?exitoVisar=true');
        }else{
            header('Location: ../'.$rutaDireccion . '/registros_comunicacion.php?errorVisarJefes=true');
        }
    }else if($admin == $contra){
        $admin_cargo = obtenerCargoNomAdmin($nomUsuario);
        $sqlAdmin = "INSERT INTO `visado_registro_comunicacion`(`id`, `nomVisado`, `fechaVisado`,cargo, `idRegistro`) 
        VALUES (NULL,'$nomUsuario','$now','$admin_cargo',$idOrden)";
        $resAdmin = mysqli_query($conexion,$sqlAdmin);

        if($resAdmin){
            header('Location: ../'.$rutaDireccion . '/registros_comunicacion.php?exitoVisar=true');
        }else{
            header('Location: ../'.$rutaDireccion . '/registros_comunicacion.php?errorVisarAdmin=true');
        }
    }else if($personal == $contra){
        $personal_cargo = obtenerCargoNomPersonal($nomUsuario);
        $sqlPersonal = "INSERT INTO `visado_registro_comunicacion`(`id`, `nomVisado`, `fechaVisado`,cargo, `idRegistro`) 
        VALUES (NULL,'$nomUsuario','$now','$personal_cargo',$idOrden)";
        $resPersonal = mysqli_query($conexion,$sqlPersonal);

        if($resPersonal){
            header('Location: ../'.$rutaDireccion . '/registros_comunicacion.php?exitoVisar=true');
        }else{
            header('Location: ../'.$rutaDireccion . '/registros_comunicacion.php?errorVisarPersonal=true');
        }
    }else if($adminTec == $contra){
        $sqlAdmTec = "INSERT INTO `visado_registro_comunicacion`(`id`, `nomVisado`, `fechaVisado`,cargo, `idRegistro`) 
        VALUES (NULL,'$nomUsuario','$now','Administrativo del Departamento',$idOrden)";
        $resAdmTec = mysqli_query($conexion,$sqlAdmTec);

        if($resAdmTec){
            header('Location: ../'.$rutaDireccion . '/registros_comunicacion.php?exitoVisar=true');
        }else{
            header('Location: ../'.$rutaDireccion . '/registros_comunicacion.php?errorVisarAdminTec=true');
        }
    }else{
        header('Location: ../'.$rutaDireccion . '/registros_comunicacion.php?errorVisar=true');
    }
}else{
    header('Location: ../'.$rutaDireccion . '/registros_comunicacion.php?errorVisar1=true');
}
