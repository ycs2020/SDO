<?php
include_once 'conexion.php';

if(isset($_POST['nomEncargadoTurno'], $_POST['nomFuncionariosTurno'])){
    $nomUsuario = $_POST['nomUsuario'];
    $fecha = $_POST['fecha'];
    $nomEncargadoTurno = $_POST['nomEncargadoTurno'];
    $nomFuncionariosTurno = $_POST['nomFuncionariosTurno'];

    if (is_array($nomEncargadoTurno)) {
        sort($nomEncargadoTurno);
        // Unir los elementos del array con ' - ' como delimitador
        $nomEncargadoTurno = implode(' - ', $nomEncargadoTurno);
    } else {
        $nomEncargadoTurno = '';
    }
    $nomEncargadoTurno = trim($nomEncargadoTurno);

    if (is_array($nomFuncionariosTurno)) {
        sort($nomFuncionariosTurno);
        // Unir los elementos del array con ' - ' como delimitador
        $nomFuncionariosTurno = implode(' - ', $nomFuncionariosTurno);
    } else {
        $nomFuncionariosTurno = '';
    }
    $nomFuncionariosTurno = trim($nomFuncionariosTurno);

    $sql = "INSERT INTO `informes_edan`(`id`, `encargados_turno`, `especialidades`, `fecha`) 
    VALUES (NULL,'$nomEncargadoTurno','$nomFuncionariosTurno','$fecha')";
    $res = mysqli_query($conexion,$sql);

}else{
    header('Location: ../sdo-personal/habilitar_informe_edan.php?errorGenerar5=true');
}
