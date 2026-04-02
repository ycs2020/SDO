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
$idDepartamento = $_SESSION['idDepartamento'];
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


if(isset($_POST['fechaOrden']) || $_POST['personaSolicitante'] || $_POST['turno'] || $_POST['tecnico1'] || $_POST['detalleTurno']) {
    $fechaOrden = $_POST['fechaOrden'];
    $personaSolicitante = $_POST['personaSolicitante'];
    $turno = $_POST['turno'];
    $detalleTurno = trim($_POST['detalleTurno']);
    $archivoAdjunto = $_FILES['archivoAdjunto'];
    $estado = 'Trabajo Ingresado';
    $nomCarpeta = $_POST['nomCarpeta'];
    $letraSolicitante = $_POST['letraSolicitante'];
    $tecnico1 = $_POST['tecnico1'];
    $folio = 'HBQP';
    $nit = $_POST['nit'];
    $trabajoTerminado = $_POST['trabajoTerminado'];
    $fechaProgramado = $_POST['fechaProgramado'];
    $ticketAsociado = $_POST['ticketAsociado'];
    $tipoTrabajo = $_POST['tipoTrabajo'];
    $detalleTurnoCamara = trim($_POST['detalleTurnoCamara']);
    $detalleTurnoLLE = trim($_POST['detalleTurnoLLE']);
    $detalleTurnoCA = trim($_POST['detalleTurnoCA']);
    $detalleTurnoRondas = trim($_POST['detalleTurnoRondas']);
    $detalleOtros = trim($_POST['detalleOtros']);
    $formularioRondas = $_POST['formularioRondas'];
    $descripcion = $_POST['descripcion'];
    //$checklist = $_POST['checklist'];
    //$cantidad = $_POST['cantidad'];

    if($formularioRondas == '' || empty($formularioRondas)){
        $formularioRondas = 0;
    }


    if ($tecnico1 == '' || empty($tecnico1)) {
        $tecnico1 = 0;
    }

    if($ticketAsociado == '' || empty($ticketAsociado)){
        $ticketAsociado = 0;
    }

    $fechaSubida = '0000-00-00 00:00:00';
    $avanceTGT = '';
    $observacionTGT = '';

    $servicioOrden = trim($_POST['servicios']);
    if($servicioOrden == '' || empty($servicioOrden)){
        $servicioOrden = 0;
    }

    if ($servicioOrden == 0 || $servicioOrden == 'Otro' || $servicioOrden == 'otro') {
        if (isset($_POST['otroServicio'])) {

            $otroServicio = trim($_POST['otroServicio']);

            if (!empty($otroServicio)) {
                $otroServicio = str_replace("'", "\'", $otroServicio);

                $buscarServicio = buscarServicio($otroServicio);

                if ($buscarServicio == 0) {

                    $queryOtroServicio = "INSERT INTO servicios(idServicio,nomServicio) VALUES (NULL,'" . $otroServicio . "')";

                    // echo $queryOtroServicio;

                    $resOtroServicio = mysqli_query($conexion, $queryOtroServicio);

                    $servicioOrden = mysqli_insert_id($conexion);
                } else {
                    $servicioOrden = $buscarServicio;
                }
            } else {
            }
        } else {
        }
    }

    $sqlTG = "INSERT INTO trabajos_general (idTG,folioTG,solicitanteTG,detalleTG,detalleTurnoCamara,detalleTurnoLLE,detalleTurnoCA,detalleTurnoRondas,detalleOtros,fechaTG,estadoTG,turnoTG,tecnico1,fechaProgramacion,ticketAsociado,tipoTrabajo,departamento,formularioRondas,descripcion,servicio) 
     VALUES(NULL,'" . $folio . "','" . $personaSolicitante . "','" . $detalleTurno . "','".$detalleTurnoCamara."','".$detalleTurnoLLE."','".$detalleTurnoCA."','".$detalleTurnoRondas."','".$detalleOtros."','" . $fechaOrden . "','" . $estado . "','" . $turno . "','".$tecnico1."','".$fechaProgramado."',$ticketAsociado,'".$tipoTrabajo."',$idDepartamento,$formularioRondas,'$descripcion',$servicioOrden)";
    $resTG = mysqli_query($conexion, $sqlTG);

    if($resTG){
        $idTG = mysqli_insert_id($conexion);

        if ($idTG < 10) {
            $idRecepcion2 = "000" . $idTG;
        } else if ($idTG > 9 && $idTG < 100) {
            $idRecepcion2 = "00" . $idTG;
        } else if ($idTG > 99 && $idTG < 1000) {
            $idRecepcion2 = "0" . $idTG;
        } else if ($idTG > 999) {
            $idRecepcion2 = $idTG;
        } else {
            $idRecepcion2 = $idTG;
        }
        /*
        if($checklist !== '' && $cantidad !== ''){
            $sqlChecklist = "INSERT INTO `tipos_check_list`(`idTipoChecklist`, `tipo`, `cantidad`, `idTG`) 
            VALUES (NULL,'$checklist',$cantidad,$idTG)";
            $resChecklist = mysqli_query($conexion,$sqlChecklist);
        }
        */
        if (isset($_POST['checklist'], $_POST['cantidad'])) {
            $checklistSeleccionado = $_POST['checklist'];
            $cantidadCheck1 = $_POST['cantidad'];
            if (!empty($checklistSeleccionado) && $cantidadCheck1 > 0) {
                for ($i = 0; $i < count($checklistSeleccionado); $i++) {
                    if (!empty($checklistSeleccionado[$i])) {
                        $checklistSeleccionadoFOR = $checklistSeleccionado[$i];
                        $cantidadCheck1FOR = $cantidadCheck1[$i];
                        $sqlChecklist = "INSERT INTO `tipos_check_list`(`idTipoChecklist`, `tipo`, `cantidad`, `idTG`) 
                        VALUES (NULL,'$checklistSeleccionadoFOR',$cantidadCheck1FOR,$idTG)";
                        $resChecklist = mysqli_query($conexion,$sqlChecklist);
                    }
                }
            }
        }

        if($idDepartamento == 1){
            $str = 'I';
        }else if($idDepartamento == 2){
            $str = 'EI';
        }else if($idDepartamento == 3){
            $str = 'CLIMA';
        }else if($idDepartamento == 4){
            $str = 'E';
        }else if($idDepartamento == 5){
            $str = 'INFRA';
        }else if($idDepartamento == 6){
            $str = 'EQMED';
        }else if($idDepartamento == 7){
            $str = 'LOG';
        }else if($idDepartamento == 8){
            $str = 'MOV';
        }else if($idDepartamento == 10){
            $str = 'EDAC';
        }else if($idDepartamento == 12){
            $str = 'INCEDIO';
        }else{
            $str = 'SDO';
        }

        $folioTG = 'OT' .'-'.$str.'-'. $idRecepcion2;

        $sqlUpdate = "UPDATE trabajos_general SET folioTG = '$folioTG' WHERE idTG = $idTG";
        $resUpdate = mysqli_query($conexion, $sqlUpdate);

        $detalleObs = 'Se ha ingresado la orden de trabajo '.$folioTG.' y se ha asignado a '.$tecnico1.' para realizar el trabajo';

        $sqlAvance = "INSERT INTO linea_vida_ot (idLineaVida,nomCreador,detalle,fechaSubida,idTG)
    VALUES (NULL,'".$personaSolicitante."','".$detalleObs."','".$fechaOrden."',$idTG)";
        $resAvance = mysqli_query($conexion,$sqlAvance);

        if (!empty($_FILES['archivoAdjunto']['name'])) {
            $directorio = "../sdo-archivosOT/";
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
                    $directorio = "../sdo-archivosOT/". $folioTG ."/";
                    if (!file_exists($directorio)) {
                        mkdir($directorio, 0777, true);
                    }
                    $dir = opendir($directorio);
                    $ruta = $directorio . $name;
                    $separarNombre = explode(".", $name);
                    $extImagen = end($separarNombre);
                    $extImagen = mb_strtolower($extImagen);
                }
                if ($extImagen == "docx" || $extImagen == "doc" || $extImagen == "xlsx" || $extImagen == "xls" || $extImagen == "ppt" || $extImagen == "pptx" ||$extImagen == "pdf" || $extImagen == "jpg" || $extImagen == "png" || $extImagen == "jpeg" || $extImagen == "bmp" || $extImagen == "tif" || $extImagen == "webp" || $extImagen == "svg" || $extImagen == "jfif") {
                    if(file_exists($ruta)){
                        header('Location: ../'.$rutaDireccion.'/trabajos_general_listado.php?otyaexiste=true');
                    }else{
                        if (move_uploaded_file($tmp_name, $ruta)) {
                            $sqlArchivo = "INSERT INTO Archivos_Trabajo_General (idArchivo,nomArchivo,rutaArchivo,extArchivo,fechaSubida,idTG)
                        VALUES (NULL,'".$name."','".$ruta."','".$extImagen."','".$fechaSubida."',$idTG)";
                            $resArchivo = mysqli_query($conexion,$sqlArchivo);

                            if($resArchivo){
                                header('Location: ../'.$rutaDireccion.'/trabajos_general_listado.php?exitoConArchivo=true');
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
            if($resUpdate){
                header('Location: ../' . $rutaDireccion . '/trabajos_general_listado.php?exitoSinArchivo=true');
            }else{
                header('Location: ../' . $rutaDireccion . '/trabajos_general_listado.php?error=true');
            }
        }
    }else{
        header('Location: ../' . $rutaDireccion . '/trabajos_general_listado.php?error=true');
    }
}else{
    header('Location: ../' . $rutaDireccion . '/trabajos_general_listado.php?error=true');
}




