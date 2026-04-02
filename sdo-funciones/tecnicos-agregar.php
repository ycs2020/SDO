<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('funciones.php');
include_once('conexion.php');


if (isset($_POST['addNombre'], $_POST['addUser'], $_POST['addPassword'], $_POST['departamentoAgregar'], $_POST['addEmail'],$_POST['addSexo'],$_POST['cargoAgregar'])) {

    $nomPersonal = trim($_POST['addNombre']);
    $nomPersonal = ucwords(strtolower($nomPersonal));
    $userPersonal = trim($_POST['addUser']);
    $passPersonal = trim($_POST['addPassword']);
    $departamentoAgregar = trim($_POST['departamentoAgregar']);
    $email = trim($_POST['addEmail']);
    $sexo = trim($_POST['addSexo']);
    $cargo = trim($_POST['cargoAgregar']);


    $subdepartamentoAgregar = 0;

    if(isset($_POST['idSubDepartamento'])){
        $idSubDepartamento = trim($_POST['idSubDepartamento']);

        if($idSubDepartamento > 0){
            $subdepartamentoAgregar = $idSubDepartamento;
        }else{
            $subdepartamentoAgregar = 0;
        }
    }else{
        $subdepartamentoAgregar = 0;
    }


    if (empty($nomPersonal) || empty($userPersonal) || empty($passPersonal) || empty($departamentoAgregar) || empty($sexo) || empty($email) || $nomPersonal == '' || $userPersonal == '' || $passPersonal == '' || $departamentoAgregar == '' || $sexo == '' || $email == ''){
        header('Location: ../sdo-jefestecnicos/tecnicos-listado.php?errorAgregarTecnico=true');
    } else {
        $existePersonal = existePersonal($userPersonal);

        $existeAdministrador = existeAdministrador($userPersonal);

        if ($existePersonal == 0) {

            if ($existeAdministrador == 0) {

                $query = "INSERT INTO personal(idPersonal,nomPersonal,cargoPersonal,userPersonal,passPersonal,emailPersonal,activoPersonal,departamentoPersonal,idSubdepartamento,sexo)";
                $query .=  "VALUES (NULL,'" . $nomPersonal . "','" . $cargo . "','" . $userPersonal . "','" . $passPersonal . "','". $email ."',1,'" . $departamentoAgregar . "','" . $subdepartamentoAgregar . "','". $sexo ."')";

                $res = mysqli_query($conexion, $query);

                if ($res) {
                    header('Location: ../sdo-jefestecnicos/tecnicos-listado.php?exitoAgregarTecnico=true');
                } else {
                    header('Location: ../sdo-jefestecnicos/tecnicos-listado.php?errorAgregarTecnico1=true');
                }
            } else {
                header('Location: ..sdo-jefestecnicos/tecnicos-listado.php?existeUsuario=true');
            }
        } else {
            header('Location: ../sdo-jefestecnicos/tecnicos-listado.php?existeUsuario=true');
        }
    }
} else {
    header('Location: ../sdo-jefestecnicos/tecnicos-listado.php?errorAgregarTecnico=true');
}

mysqli_close($conexion);
