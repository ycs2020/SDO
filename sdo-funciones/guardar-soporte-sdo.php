<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

$rutaUsuario = "";

if ($rolUsuario2 == "personal") {

    $rutaUsuario = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {

    $rutaUsuario = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {

    $rutaUsuario = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {

    $rutaUsuario = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {

    $rutaUsuario = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $rutaUsuario = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //OBTENEMOS LOS DATOS DEL FORMULARIO Y LOS GUARDAMOS EN VARIABLES
    $categoria = trim($_POST['categoria']);
    $tipoUsuario = trim($_POST['tipoUsuario']);

    echo $categoria;
    echo $tipoUsuario;

    if ($tipoUsuario == 'tecnicos') {

        $departamento = $_POST['depaPersonal'];
        $idPersonal = $_POST['personalTecnico'];

        echo $departamento;
        echo $idPersonal;
    } else if ($tipoUsuario == 'jefes') {

        $departamento = $_POST['departamentoSolicitante'];
        $idPersonal = $_POST['nomTecnicoRecepcionador'];

        echo $departamento;
        echo $idPersonal;
    } else if ($tipoUsuario == 'directivos') {

        $departamento = 9;
        $idPersonal = $_POST['directivo'];
    } else {
        //AQUI SE OBTIENEN LOS DEPARTAMENTOS DE LOS USUARIOS TECNICOS
        $departamentoSolicitante = $_POST['departamentoSolicitante'];
        $idSolicitante = $_POST['idSolicitante'];
        $tipoUsuario = $_POST['tipoUsuario'];
    }

    $comentarios = trim($_POST['comentarios']);
    $comentarios = nl2br($comentarios);
    $adjuntosSC = $_FILES['adjuntosSC'];
    $idSolicitante = $_POST['idSolicitante'];


    //TRANSFORMAMOS LOS DATOS A MAYÚSCULAS
    $categoria = strtoupper($categoria);
    $tipoUsuario = strtoupper($tipoUsuario);
    $idResponsable = 0;

    $estado = 'En Progreso';

    //INSERTAMOS LA INFORMACION EN LA TABLA - SI EL ADMINISTRADOR FUE EL CREADOR DEL TICKET
    if (!empty($tipoUsuario)) {
        $sqlInsert = "INSERT INTO  soportesdo (categoria, tipoUsuario,idSolicitante,departamentoSolicitante,estado,subestado,comentarios,idResponsable) VALUES ('$categoria', '$tipoUsuario', $idPersonal, $departamento, '$estado','-', '$comentarios', '$idResponsable') ";
        $res = mysqli_query($conexion, $sqlInsert);

    } else {
        //INSERTAMOS LA INFORMACION EN LA TABLA - SI EL TECNICO CREO EL TICKET
        $sqlInsert = "INSERT INTO  soportesdo (categoria, tipoUsuario,idSolicitante,departamentoSolicitante,estado,subestado,comentarios,idResponsable) VALUES ('$categoria', '$tipoUsuario', $idUsuario, $departamentoSolicitante, '$estado','-', '$comentarios', '$idResponsable') ";
        $res = mysqli_query($conexion, $sqlInsert);

    }

    $id = mysqli_insert_id($conexion);

    $id_lenght = 3;
    $idGenerado = bin2hex(random_bytes($id_lenght));
    $idGenerado = strtoupper($idGenerado);

    //CREAMOS EL FOLIO Y ACTUALIZAMOS LA TABLA
    $folio = 'TICK-' . $idGenerado;

    $sqlUpdate = "UPDATE soportesdo SET folioSoporte = '$folio' WHERE idSoporte  = $id";
    $res = mysqli_query($conexion, $sqlUpdate);

    if (isset($adjuntosSC) && !empty($adjuntosSC)) {

        $directorio = "../sdo-administrador/archivos-soporte-sdo/" . $folio . "/";
    
        if (!file_exists($directorio)) {
            mkdir($directorio, 0777);
        }

        $tot = count($_FILES["adjuntosSC"]["name"]);
        for ($i = 0; $i < $tot; $i++) {

            $tmp_name = $_FILES["adjuntosSC"]["tmp_name"][$i];
            $name = $_FILES["adjuntosSC"]["name"][$i];

            if ($tmp_name == '' || $tmp_name == ' ' || empty($tmp_name)) {
                echo "VACIOS";
            } else {
                $directorio = "../sdo-administrador/archivos-soporte-sdo/" . $folio . "/";

                if (!file_exists($directorio)) {
                    mkdir($directorio, 0777);
                }

                $dir = opendir($directorio);

                $ruta = $directorio . $name;

                $separarNombre = explode(".", $name);

                $extImagen = end($separarNombre);

                $extImagen = mb_strtolower($extImagen);


                if (move_uploaded_file($tmp_name, $ruta)) {

                    $queryArchivos = "INSERT INTO archivos_soportesdo(idArchivo,nomArchivo,extArchivo,rutaArchivo,idSoporte) VALUES (NULL,'" . $name . "','" . $extImagen . "','" . $ruta . "','" . $id . "')";
                    $resArchivos = mysqli_query($conexion, $queryArchivos);

                    if ($resArchivos) {
                        echo "subi imagen";
                    } else {
                        echo "subi imagen";
                    }
                }
            }
        }
    }

    if ($sqlInsert && $sqlUpdate) {
        header('Location: ../' . $rutaUsuario . '/soporte-sdo.php?exitoTicket=true');
    } else {
        header('Location: ../' . $rutaUsuario . '/soporte-sdo.php?errorTicket=true');
    }
}
