<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);

if ($rolUsuario2 == "personal") {

    $ruta = "sdo-personal";

    $nombre = 'nomPersonal';
    $fechaNacimientocampo = 'fechaNacimiento';
    $passwordCampo = 'passPersonal';
    $correoCampo = 'emailPersonal';
    $id = 'idPersonal ';

    $tabla = 'personal';

    $sql = "SELECT * FROM personal WHERE idPersonal = $idUsuario";
    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['passPersonal'] = $row['passPersonal'];
        $datos['emailPersonal'] = $row['emailPersonal'];
        $datos['fechaNacimiento'] = $row['fechaNacimiento'];
        $password = $datos['passAdministrador'];
        $correoElectronico = $datos['emailAdministrador'];
        $fechaNacimiento = $datos['fechaNacimiento'];
    }
} else if ($rolUsuario2 == "administrativo") {

    $ruta = "sdo-admtec";

    $nombre = 'nomAdmTecnicos';
    $fechaNacimientocampo = 'fechaNacimiento';
    $passwordCampo = 'passAdmTecnicos';
    $correoCampo = 'emailAdmTecnicos';
    $id = 'idAdmTecnicos ';

    $tabla = 'adm_tecnicos';

    $sql = "SELECT * FROM adm_tecnicos WHERE idAdmTecnicos  = $idUsuario";
    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['passAdmTecnicos'] = $row['passAdmTecnicos'];
        $datos['emailAdmTecnicos'] = $row['emailAdmTecnicos'];
        $datos['fechaNacimiento'] = $row['fechaNacimiento'];
        $password = $datos['passAdmTecnicos'];
        $correoElectronico = $datos['emailAdmTecnicos'];
        $fechaNacimiento = $datos['fechaNacimiento'];
    }
} else if ($rolUsuario2 == "bodega pañol") {

    $ruta = "sdo-bodega-panol";

    $nombre = 'nomBodegaPañol';
    $fechaNacimientocampo = 'fechaNacimiento';
    $passwordCampo = 'passBodegaPañol';
    $correoCampo = 'emailBodegaPañol';
    $id = 'idBodegaPañol ';

    $tabla = 'bodega_pañol';

    $sql = "SELECT * FROM bodega_pañol WHERE idBodegaPañol  = $idUsuario";
    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['passJefe'] = $row['passJefe'];
        $datos['emailJefe'] = $row['emailJefe'];
        $datos['fechaNacimiento'] = $row['fechaNacimiento'];
        $password = $datos['passJefe'];
        $correoElectronico = $datos['emailJefe'];
        $fechaNacimiento = $datos['fechaNacimiento'];
    }
} else if ($rolUsuario2 == "jefe tecnico") {
    $ruta = "sdo-jefestecnicos";

    $nombre = 'nomJefe';
    $fechaNacimientocampo = 'fechaNacimiento';
    $passwordCampo = 'passJefe';
    $correoCampo = 'emailJefe';
    $id = 'idJefe';

    $tabla = 'jefes_tecnicos';

    $sql = "SELECT * FROM jefes_tecnicos WHERE idJefe = $idUsuario";
    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['passJefe'] = $row['passJefe'];
        $datos['emailJefe'] = $row['emailJefe'];
        $datos['fechaNacimiento'] = $row['fechaNacimiento'];
        $password = $datos['passJefe'];
        $correoElectronico = $datos['emailJefe'];
        $fechaNacimiento = $datos['fechaNacimiento'];
    }
} else if ($rolUsuario2 == "administrador") {

    $nombre = 'nomAdministrador';
    $fechaNacimientocampo = 'fechaNacimiento';
    $passwordCampo = 'passAdministrador';
    $correoCampo = 'emailAdministrador';
    $id = 'idAdministrador';


    $ruta = "sdo-administrador";
    $tabla = 'administracion';

    $sql = "SELECT * FROM administracion WHERE idAdministrador  = $idUsuario";
    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['passAdministrador'] = $row['passAdministrador'];
        $datos['emailAdministrador'] = $row['emailAdministrador'];
        $datos['fechaNacimiento'] = $row['fechaNacimiento'];
        $password = $datos['passAdministrador'];
        $correoElectronico = $datos['emailAdministrador'];
        $fechaNacimiento = $datos['fechaNacimiento'];
    }
} else if ($rolUsuario2 == "directiva") {
    $ruta = "sdo-directiva";

    $nombre = 'nomDirectivo';
    $fechaNacimientocampo = 'fechaNacimiento';
    $passwordCampo = 'passDirectivo';
    $correoCampo = 'emailDirectivo';
    $id = 'idDirectivo';

    $tabla = 'directivos';

    $sql = "SELECT * FROM directivos WHERE idDirectivo   = $idUsuario";
    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['passDirectivo'] = $row['passDirectivo'];
        $datos['emailDirectivo'] = $row['emailDirectivo'];
        $datos['fechaNacimiento'] = $row['fechaNacimiento'];
        $password = $datos['passDirectivo'];
        $correoElectronico = $datos['emailDirectivo'];
        $fechaNacimiento = $datos['fechaNacimiento'];
    }
}else if ($rolUsuario2 == "mesa ayuda") {
    $ruta = "sdo-mesa-ayuda";

    $nombre = 'nomMesaAyuda';
    $fechaNacimientocampo = 'fechaNacimiento';
    $passwordCampo = 'passMesaAyuda';
    $correoCampo = 'emailMesaAyuda';
    $id = 'idMesaAyuda ';

    $tabla = 'mesa_ayuda';

    $sql = "SELECT * FROM mesa_ayuda WHERE idMesaAyuda = $idUsuario";
    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['passMesaAyuda'] = $row['passMesaAyuda'];
        $datos['emailMesaAyuda'] = $row['emailMesaAyuda'];
        $datos['fechaNacimiento'] = $row['fechaNacimiento'];
        $password = $datos['passMesaAyuda'];
        $correoElectronico = $datos['emailMesaAyuda'];
        $fechaNacimiento = $datos['fechaNacimiento'];
    }
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nomUsuarioForm = $_POST['nomUsuario'];
    $fechaNacimientoForm = $_POST['fechaNacimiento'];
    $correoElectronicoForm = $_POST['correoElectronico'];
    $nuevaContraseña = $_POST['password'];

    if ($nomUsuarioForm == '') {
        $nomUsuarioForm = $nomUsuario;
    }

    if ($fechaNacimientoForm == '') {
        $fechaNacimientoForm = $fechaNacimiento;
    }
    if ($correoElectronicoForm == '') {
        $correoElectronicoForm = $correoElectronico;
    }

    if ($nuevaContraseña == '') {

        $sqlUpdate = "UPDATE " . $tabla . " SET " . $nombre . " = '$nomUsuarioForm'," . $fechaNacimientocampo . " = '$fechaNacimientoForm', " . $correoCampo . " = '$correoElectronicoForm' WHERE " . $id .  " = $idUsuario";;
        $resUpdate = mysqli_query($conexion, $sqlUpdate);

        if ($resUpdate) {
            header('Location: ../' . $ruta . '/miperfil.php?exitoEditar=true');
        } else {
            header('Location: ../' . $ruta . '/miperfil.php?errorEditar=true');
        }
    } else {
        $sqlUpdate = "UPDATE " . $tabla . " SET " . $nombre . " = '$nomUsuarioForm' ," . $fechaNacimientocampo . " = '$fechaNacimientoForm' , " . $passwordCampo   . " = '$nuevaContraseña'," . $correoCampo . " = '$correoElectronicoForm' WHERE " . $id .  " = $idUsuario";;
        $resUpdate = mysqli_query($conexion, $sqlUpdate);

        if ($resUpdate) {
            header('Location: ../' . $ruta . '/miperfil.php?exitoEditar=true');
        } else {
            header('Location: ../' . $ruta . '/miperfil.php?errorEditar=true');
        }
    }
}
