<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('funciones.php');
include_once('conexion.php');


if (isset($_POST['addNombre'], $_POST['addUser'], $_POST['addPassword'], $_POST['departamentoAgregar'], $_POST['addSexo'], $_POST['addEmail'])) {

    $nomJefe = trim($_POST['addNombre']);
    $nomJefe = ucwords(strtolower($nomJefe));
    $userJefe = trim($_POST['addUser']);
    $passJefe = trim($_POST['addPassword']);
    $departamentoAgregar = trim($_POST['departamentoAgregar']);
    $sexo = trim($_POST['addSexo']);
    $email = trim($_POST['addEmail']);

    if (isset($_POST['idSubDepartamento'])) {
        $idSubDepartamento = trim($_POST['idSubDepartamento']);

        if ($idSubDepartamento < 1 || empty($idSubDepartamento) || $idSubDepartamento == '') {

            $idSubDepartamento = 0;
        } else {
            $idSubDepartamento = trim($_POST['idSubDepartamento']);
        }
    } else {
        $idSubDepartamento = 0;
    }


    if (empty($nomJefe) || empty($userJefe) || empty($passJefe) || empty($departamentoAgregar) || empty($sexo) || $nomJefe == '' || $userJefe == '' || $passJefe == '' || $departamentoAgregar == '' || $sexo == '') {
        header('Location: ../sdo-administrador/jefes-administrar.php?errorAgregarJefe=true');
    } else {
        $existePersonal = existePersonal($userJefe);

        $existeJefe = existeJefe($userJefe);

        $existeAdministrador = existeAdministrador($userJefe);

        if ($existePersonal == 0) {

            if ($existeAdministrador == 0) {

                if ($existeJefe == 0) {

                    $query = "INSERT INTO jefes_tecnicos(idJefe,nomJefe,cargoJefe,userJefe,passJefe,emailJefe,idDepartamento,idSubdepartamento,sexo)";
                    $query .=  "VALUES (NULL,'" . $nomJefe . "','Encargado del Departamento','" . $userJefe . "','" . $passJefe . "','".$email."','" . $departamentoAgregar . "','" . $idSubDepartamento . "','" . $sexo . "')";

                    $res = mysqli_query($conexion, $query);
    
                    if ($res) {
                        header('Location: ../sdo-administrador/jefes-administrar.php?exitoAgregarJefe=true');
                    } else {
                        header('Location: ../sdo-administrador/jefes-administrar.php?errorAgregarJefe=true');
                    }
                } else {
                    header('Location: ..sdo-administrador/jefes-administrar.php?existeUsuario=true');
                }
            } else {
                header('Location: ..sdo-administrador/jefes-administrar.php?existeUsuario=true');
            }
        } else {
            header('Location: ../sdo-administrador/jefes-administrar.php?existeUsuario=true');
        }
    }
} else {
    header('Location: ../sdo-administrador/jefes-administrar.php?errorAgregarJefe=true');
}

mysqli_close($conexion);
