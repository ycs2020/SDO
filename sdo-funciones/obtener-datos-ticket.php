<?php
include 'conexion.php';
include 'funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(isset($_POST['id'])){

    $id = $_POST['id'];

    //Consultamos los datos del ticket
    $sql = "SELECT * FROM soportesdo WHERE idSoporte = $id";
    $res = mysqli_query($conexion, $sql);
    
    while($row = $res->fetch_assoc()){
        $datos = array();
        $datos['idSoporte'] = $row['idSoporte'];
        $datos['folioSoporte'] = $row['folioSoporte'];
        $datos['categoria'] = $row['categoria'];
        $datos['tipoUsuario'] = $row['tipoUsuario'];
        $datos['idSolicitante'] = $row['idSolicitante'];
        $datos['departamentoSolicitante'] = $row['departamentoSolicitante'];
        $datos['estado'] = $row['estado'];
        $datos['subestado'] = $row['subestado'];
        $datos['fechaResolucion'] = $row['fechaResolucion'];
        $datos['comentarios'] = $row['comentarios'];
        $datos['idResponsable'] = $row['idResponsable'];
        $datos['respuesta'] = $row['respuesta'];

        $idSolicitante = $datos['idSolicitante'];
        $departamentoSolicitante = $datos['departamentoSolicitante'];
        $tipoUsuario = $row['tipoUsuario'];
        $idSoporte = $row['idSoporte'];
        $idResponsable = $datos['idResponsable'];
        $respuesta = $datos['respuesta'];

        if($tipoUsuario == 'TECNICOS'){
            $nomSolicitante = getNomPersonal($idSolicitante);
            $datos['nomSolicitante'] = $nomSolicitante;

            $nomDepartamento = getNomDepartamento($departamentoSolicitante);
            $datos['nomDepartamento'] = $nomDepartamento;
        }else if($tipoUsuario == 'JEFES'){
            $nomSolicitante = getNomJefeTecnico($idSolicitante);
            $datos['nomSolicitante'] = $nomSolicitante;

            $nomDepartamento = getNomDepartamento($departamentoSolicitante);
            $datos['nomDepartamento'] = $nomDepartamento;
        }else if($tipoUsuario == 'DIRECTIVOS'){
            $nomSolicitante = getNomDirectivo($idSolicitante);
            $datos['nomSolicitante'] = $nomSolicitante;

            $datos['nomDepartamento'] = 'Ingenieria Operacional';
        }
    }


    echo json_encode($datos);

}