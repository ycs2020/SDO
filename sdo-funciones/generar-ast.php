<?php

include_once 'conexion.php';
include_once 'funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

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
} else if($rolUsuario2 == "oficina tecnica") {
    $tipoUsuario = 'oficina tecnica';
    $rutaDireccion = "sdo-oficinaTecnica";
}else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if(isset($_POST['nomEncargadoTurno'])){

    $nomEncargadoTurno = $_POST['nomEncargadoTurno'];
    $tipoTarea = $_POST['tipoTarea'];
    $now2 = date('Y-m-d');
    $turno = $_POST['turno'];
    $idDepartamento = $_POST['idDepartamento'];

    if (is_array($nomEncargadoTurno)) {
        sort($nomEncargadoTurno);

        $rutsEncargados = [];

        foreach ($nomEncargadoTurno as $nombre) {
            $nombre = trim($nombre);

            $query = "SELECT rutPersonal FROM personal WHERE nomPersonal = ?";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("s", $nombre);
            $stmt->execute();
            $stmt->bind_result($rut);

            if ($stmt->fetch()) {
                $rutsEncargados[] = $rut;
            }

            $stmt->close();
        }

        $rutsEncargadosStr = implode(' - ', $rutsEncargados);
        $nomEncargadoTurno = trim(implode(' - ', $nomEncargadoTurno));

    } else {
        $nomEncargadoTurno = '';
        $rutsEncargadosStr = '';
    }

    if($idDepartamento === 1){
        $servicio = "AUTOMATIZACIÓN E INSTRUMENTACIÓN.";
    }else{
        $servicio = $_POST['servicio'];
    }

    $sqlASTPersonal = "INSERT INTO `ast_participantes_otros`(`id`, `nomParticipantes`, `rutParticipantes`, turno ,`servicio`, `tipoTarea`, `fecha`) 
            VALUES (NULL,'$nomEncargadoTurno','$rutsEncargadosStr','$turno','$servicio','$tipoTarea','$now2')";

    $resASTPersonal = mysqli_query($conexion, $sqlASTPersonal);

    if($resASTPersonal){
        $idAST = mysqli_insert_id($conexion);

        $cantidadFilas = $_POST['cantidadFilas'];

        // Bandera para detectar errores
        $error = false;

        $stmt = mysqli_prepare($conexion, "
                INSERT INTO ast_etapas_riesgos_medidas
                (etapasTrabajo, riesgos, medidasControl, idCheckList, idAstPersonas, idTG)
                VALUES (?, ?, ?, 0, ?, 0)
            ");

        for($i = 1; $i <= $cantidadFilas; $i++){

            $etapas  = $_POST['etapasTrabajo'.$i];
            $riesgos = $_POST['riesgos'.$i];
            $medidas = $_POST['medidasControl'.$i];

            mysqli_stmt_bind_param($stmt, "sssi", $etapas, $riesgos, $medidas, $idAST);

            if (!mysqli_stmt_execute($stmt)) {
                header('Location: ../'.$rutaDireccion.'/listadosAST.php?errorIngreso=true');
                exit;
            }
        }

        mysqli_stmt_close($stmt);

        header('Location: ../'.$rutaDireccion.'/listadosAST.php?exitoIngreso=true');
        exit;


    }

}

