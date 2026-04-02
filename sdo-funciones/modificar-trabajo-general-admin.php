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
$rutaDireccion = "";

if ($rolUsuario2 == "personal") {
    $rutaDireccion  = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $rutaDireccion  = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $rutaDireccion  = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $rutaDireccion  = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $rutaDireccion  = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $rutaDireccion  = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}


if(isset($_POST['idTG'])){

    $idTG = $_POST['idTG'];
    $turno = $_POST['turno'];
    $tecnico1 = $_POST['tecnico1'];
    //$detalleTurno = trim($_POST['detalleTurno']);
    $detalleTurno = $_POST['detalleTurno'];
    $detalleTurnoBMS = $_POST['detalleTurnoBMS'];
    $detalleTurnoNoControl = $_POST['detalleTurnoNoControl'];
    $nit = $_POST['nit'];
    $idTecnico1 = $_POST['idTecnico1'];
    $tipoTecnico1 = $_POST['tipoTecnico1'];
    $personaSolicitante = $_POST['personaSolicitante'];
    $folioTG = $_POST['$folioTG'];
    $fechaModi = $_POST['fechaModi'];
    $tecnicoRecepcionador = $_POST['tecnicoRecepcionador'];
    $fechaProgramado = $_POST['fechaProgramado'];
    $detalleTurnoRondas = trim($_POST['detalleTurnoRondas']);
    $detalleTurnoCA = trim($_POST['detalleTurnoCA']);
    $detalleTurnoLLE = trim($_POST['detalleTurnoLLE']);
    $detalleTurnoCCTV = trim($_POST['detalleTurnoCCTV']);
    $departamentoSolicitante = $_POST['departamentoSolicitante'];
    $detalleTurnoOtros = trim($_POST['detalleOtros']);
    $departamentosecreto = $_POST['departamentosecreto'];
    $now = date('Y-m-d H:i:s');
    $descripcion = $_POST['descripcion'];

    if(empty($turno) || empty($idTecnico1)){
        header('Location: ../' . $rutaDireccion . '/trabajos_general_listado.php?errorEnlosDatos=true');
    }else{

        if($departamentosecreto = 1){
            $sql = "UPDATE trabajos_general SET descripcion = '$descripcion',detalleTG = '$detalleTurnoBMS',detalleOtros = '$detalleTurnoOtros',detalleTurnoRondas = '$detalleTurnoRondas', detalleTurnoCA = '$detalleTurnoCA',detalleTurnoLLE = '$detalleTurnoLLE',detalleTurnoCamara = '$detalleTurnoCCTV',fechaProgramacion = '$fechaProgramado', solicitanteTG = '$personaSolicitante', turnoTG = '$turno', tecnico1 = '$tecnicoRecepcionador', departamento = $departamentosecreto WHERE idTG = $idTG";
            $res = mysqli_query($conexion,$sql);
            $detalleObs = 'Se ha modificado la orden de trabajo '.$folioTG.' y se ha asignado a '.$tecnico1.' para realizar el trabajo';

            $sqlAvance = "INSERT INTO avances_trabajo_general (idAvance,nomCreador,detalleObs,estadoAvance,fechaSubida,idTG,personaDerivada)
             VALUES (NULL,'".$personaSolicitante."','".$detalleObs."','Trabajo Ingresado','".$now."',$idTG,'NULL')";
            $resAvance = mysqli_query($conexion,$sqlAvance);
        }else{
            $sql = "UPDATE trabajos_general SET descripcion = '$descripcion',fechaProgramacion = '$fechaProgramado', solicitanteTG = '$personaSolicitante', detalleTG = '$detalleTurnoNoControl', turnoTG = '$turno', tecnico1 = '$idTecnico1' WHERE idTG = $idTG";

            $res = mysqli_query($conexion,$sql);
            $detalleObs = 'Se ha modificado la orden de trabajo '.$folioTG.' y se ha asignado a '.$tecnico1.' para realizar el trabajo';

            $sqlAvance = "INSERT INTO avances_trabajo_general (idAvance,nomCreador,detalleObs,estadoAvance,fechaSubida,idTG,personaDerivada)
             VALUES (NULL,'".$personaSolicitante."','".$detalleObs."','Trabajo Ingresado','".$now."',$idTG,'NULL')";
            $resAvance = mysqli_query($conexion,$sqlAvance);
        }

        if (!empty($_FILES['archivoAdjunto']['name'])) {
            $directorio = "../sdo-administrador/archivos-trabajo-general/folio-". $folioTG ."/carpeta-" . $idTG . "/";
            mkdir($directorio, 0777, true);
        }

        if (!empty($_FILES['archivoAdjunto']['name'])) {
            $tot = count($_FILES["archivoAdjunto"]["name"]);
            for ($i = 0; $i < $tot; $i++) {
                $tmp_name = $_FILES["archivoAdjunto"]["tmp_name"];
                $name = $_FILES["archivoAdjunto"]["name"];
                if ($tmp_name == '' || $tmp_name == ' ' || empty($tmp_name)) {
                    header('Location: ../' . $rutaDireccion. '/trabajos_general_listado.php?archivoSinNombre=true');
                }else{
                    $directorio = "../sdo-administrador/archivos-trabajo-general/folio-". $folioTG ."/carpeta-" . $idTG . "/";
                    if (!file_exists($directorio)) {
                        mkdir($directorio, 0777, true);
                    }
                    $dir = opendir($directorio);
                    $ruta = $directorio . $name;
                    $separarNombre = explode(".", $name);
                    $extImagen = end($separarNombre);
                    $extImagen = mb_strtolower($extImagen);
                }
                if ($extImagen == "docx" || $extImagen == "xlsx" || $extImagen == "pdf" || $extImagen == "jpg" || $extImagen == "png" || $extImagen == "jpeg" || $extImagen == "bmp" || $extImagen == "tif" || $extImagen == "webp" || $extImagen == "svg" || $extImagen == "jfif") {
                    if(file_exists($ruta)){
                        header('Location: ../'.$rutaDireccion.'/trabajos_general_listado.php?archivoyaexiste=true');
                    }else{
                        if (move_uploaded_file($tmp_name, $ruta)) {
                            $sqlArchivo = "INSERT INTO Archivos_Trabajo_General (idArchivo,nomArchivo,rutaArchivo,extArchivo,fechaSubida,idTG)
                        VALUES (NULL,'".$name."','".$ruta."','".$extImagen."','".$fechaSubida."',$idTG)";
                            $resArchivo = mysqli_query($conexion,$sqlArchivo);

                            if($resArchivo){
                                header('Location: ../'.$rutaDireccion.'/trabajos_general_listado.php?exitoModiConArchivo=true');
                            }else{
                                header('Location: ../' . $rutaDireccion . '/trabajos_general_listado.php?error=true');
                            }
                        }
                    }
                }else{
                    header('Location: ../'.$rutaDireccion.'/trabajos_general_listado.php?archivoNoHabilitado=true');
                }
            }
        }else{
            if($res){
                header('Location: ../' . $rutaDireccion . '/trabajos_general_listado.php?modificacionok=true');
            }else{
                header('Location: ../' . $rutaDireccion . '/trabajos_general_listado.php?errormodificacion=true');
            }
        }
    }
}