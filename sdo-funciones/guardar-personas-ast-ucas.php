<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');

$rolUsuario = $_SESSION['rolUsuario'];

$nomCreador = $_SESSION['nomUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

$ruta = "";

if ($rolUsuario2 == "personal") {

    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {

    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "administrativo") {

    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "jefe tecnico") {

    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "directiva") {

    $ruta = "sdo-directiva";

    $cargoUsuario = $_SESSION['cargoUsuario'];

    $cargoUsuario = trim($cargoUsuario);
} else if ($rolUsuario2 == "administrador") {

    $ruta = "sdo-administrador";

    $cargoUsuario = $_SESSION['cargoUsuario'];

    $cargoUsuario = trim($cargoUsuario);

    if (empty($cargoUsuario)) {
        header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    }
} else if ($rolUsuario2 == "mesa ayuda") {

    $ruta = "sdo-mesa-ayuda";
} else {

    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}


if (isset($_POST['idTG'])) {
    $idTG = $_POST['idTG'];

    $nomEncargadoTurno = $_POST['nomEncargadoTurno'];
    $servicio = "AUTOMATIZACIÓN E INSTRUMENTACIÓN.";
    $tipoTarea = "MANTENCIÓN PREVENTIVA E INSPECCIÓN DE LOS SISTEMAS";
    $now2 = date('Y-m-d');


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

    $sqlASTPersonal = "INSERT INTO `ast_participantes_otros`(`id`, `nomParticipantes`, `rutParticipantes`, `servicio`, `tipoTarea`, `fecha`,idTG) 
            VALUES (NULL,'$nomEncargadoTurno','$rutsEncargadosStr','$servicio','$tipoTarea','$now2',$idTG)";

    $resASTPersonal = mysqli_query($conexion, $sqlASTPersonal);

    if($resASTPersonal){
        header('Location: ../'.$ruta.'/visualizardetalles.php?exitoAST');
    }else{
        header('Location: ../'.$ruta.'/visualizardetalles.php?errorAST');
    }

}else{
    echo 'Error';
}
