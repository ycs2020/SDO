<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('funciones.php');
include_once('conexion.php');

if (isset($_POST['addNombre'], $_POST['addUser'], $_POST['addPassword'], $_POST['departamentoAgregar'],$_POST['cargoAgregar'],$_POST['addSexo'], $_POST['addCorreo'])) {

    $nomPersonal = trim($_POST['addNombre']);
    $nomPersonal = ucwords(strtolower($nomPersonal));
    $userPersonal = trim($_POST['addUser']);
    $passPersonal = trim($_POST['addPassword']);
    $departamentoAgregar = trim($_POST['departamentoAgregar']);
    $cargoPersonal = trim($_POST['cargoAgregar']);
    $sexo = trim($_POST['addSexo']);
    $correo = trim($_POST['addCorreo']);


    if (empty($nomPersonal) || empty($userPersonal) || empty($passPersonal) || empty($departamentoAgregar) || $nomPersonal == '' || $userPersonal == '' || $passPersonal == '' || $departamentoAgregar == '' || empty($cargoPersonal) || $cargoPersonal == '' || empty($sexo) || $sexo == '' || empty($correo) || $correo == ''){
        header('Location: ../sdo-administrador/personal-administrar.php?errorAgregarPersonal=true');
    } else {
        $existePersonal = existePersonal($userPersonal);

        $existeAdministrador = existeAdministrador($userPersonal);

        if ($existePersonal == 0) {

            if ($existeAdministrador == 0) {

                $query = "INSERT INTO personal(idPersonal,nomPersonal,cargoPersonal,userPersonal,passPersonal,emailPersonal,activoPersonal,departamentoPersonal,idSubdepartamento,sexo)";
                $query .=  "VALUES (NULL,'" . $nomPersonal . "','" .$cargoPersonal  . "','".$userPersonal."','" . $passPersonal . "','".$correo."',1,'" . $departamentoAgregar . "','0','" .$sexo. "')";

                $res = mysqli_query($conexion, $query);

                if ($res) {
                    header('Location: ../sdo-administrador/personal-administrar.php?exitoAgregarPersonal=true');
                } else {
                    header('Location: ../sdo-administrador/personal-administrar.php?errorAgregarPersonal1=true');
                }
            } else {
                header('Location: ..sdo-administrador/personal-administrar.php?existeUsuario=true');
            }
        } else {
            header('Location: ../sdo-administrador/personal-administrar.php?existeUsuario=true');
        }
    }
} else {
    header('Location: ../sdo-administrador/personal-administrar.php?errorAgregarPersonal=true');
}

mysqli_close($conexion);
