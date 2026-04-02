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
    $detalleTurno = trim($_POST['detalleTurno']);
    $nit = $_POST['nit'];
    $idTecnico1 = $_POST['idTecnico1'];
    $tipoTecnico1 = $_POST['tipoTecnico1'];
    $personaSolicitante = $_POST['personaSolicitante'];
    $folioTG = $_POST['folioTG'];
    $fechaModi = $_POST['fechaModi'];
    $tecnicoRecepcionador = $_POST['tecnicoRecepcionador'];
    $fechaProgramado = $_POST['fechaProgramado'];
    $detalleTurnoRondas = trim($_POST['detalleTurnoRondas']);
    $detalleTurnoCA = trim($_POST['detalleTurnoCA']);
    $detalleTurnoLLE = trim($_POST['detalleTurnoLLE']);
    $detalleTurnoCCTV = trim($_POST['detalleTurnoCCTV']);
    $formularioRondas = $_POST['formularioRondas'];
    $descripcion = $_POST['descripcion'];
    $checklist = $_POST['checklist'];
    $cantidad = $_POST['cantidad'];
    if($formularioRondas == '' || empty($formularioRondas)){
        $formularioRondas = 0;
    }

    $sqlSelect = "SELECT COUNT(*) AS TOTAL FROM tipos_check_list WHERE idTG = $idTG";
    $resSelect = mysqli_query($conexion,$sqlSelect);
    while($rowSelect = $resSelect->fetch_assoc()){
        $total = $rowSelect['TOTAL'];
    }

    $now = date('Y-m-d H:i:s');
    if(empty($turno) || empty($idTecnico1)){
        header('Location: ../' . $rutaDireccion . '/trabajos_general_listado.php?errorEnlosDatos=true');
    }else{
        if($tecnico1 == '' || empty($tecnico1)){
            $sql = "UPDATE trabajos_general SET descripcion = '$descripcion', formularioRondas = $formularioRondas,folioTG = '$folioTG',detalleTurnoRondas = '$detalleTurnoRondas', detalleTurnoCA = '$detalleTurnoCA',detalleTurnoLLE = '$detalleTurnoLLE',detalleTurnoCamara = '$detalleTurnoCCTV',fechaProgramacion = '$fechaProgramado', solicitanteTG = '$personaSolicitante', detalleTG = '$detalleTurno', turnoTG = '$turno', tecnico1 = '$tecnicoRecepcionador' WHERE idTG = $idTG ";
            $res = mysqli_query($conexion,$sql);
        }else{
            $sql = "UPDATE trabajos_general SET descripcion = '$descripcion',formularioRondas = $formularioRondas, folioTG = '$folioTG',detalleTurnoRondas = '$detalleTurnoRondas', detalleTurnoCA = '$detalleTurnoCA',detalleTurnoLLE = '$detalleTurnoLLE',detalleTurnoCamara = '$detalleTurnoCCTV',fechaProgramacion = '$fechaProgramado', solicitanteTG = '$personaSolicitante', detalleTG = '$detalleTurno', turnoTG = '$turno', tecnico1 = '$tecnico1' WHERE idTG = $idTG";
            $res = mysqli_query($conexion,$sql);

            $detalleObs = 'Se ha modificado la orden de trabajo '.$folioTG.' y se ha asignado a '.$tecnico1.' para realizar el trabajo';

            $sqlAvance = "INSERT INTO linea_vida_ot (idLineaVida,nomCreador,detalle,fechaSubida,idTG)
             VALUES (NULL,'".$personaSolicitante."','".$detalleObs."','".$now."',$idTG)";

            $resAvance = mysqli_query($conexion,$sqlAvance);

            /*
             INSERT INTO `linea_vida_ot`(`idLineaVida`, `nomCreador`, `detalle`, `fechaSubida`, `isMaterialSoliCompra`, `isPanol`, `respuestaPanol`, `respuestaSoli`, `rechazadoPanol`, `fechaAceptadoPanol`, `numSoliCompra`, `idTG`)
            VALUES ('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]','[value-6]','[value-7]','[value-8]','[value-9]','[value-10]','[value-11]','[value-12]')*/
        }

        if (isset($_POST['checklist'], $_POST['cantidad'])) {
            if($total > 0){
                $sqlUpdate = "UPDATE `tipos_check_list` SET `tipo`= '$checklist',`cantidad`= $cantidad WHERE idTG = $idTG";
                $resUpdate = mysqli_query($conexion,$sqlUpdate);
            }else{
                $sqlUpdate = "INSERT INTO `tipos_check_list`(`idTipoChecklist`, `tipo`, `cantidad`, `idTG`) 
             VALUES (NULL,'$checklist',$cantidad,$idTG)";
                $resUpdate = mysqli_query($conexion,$sqlUpdate);
            }
        }

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
                            $now = date('Y-m-d H:i:s');
                            $sqlArchivo = "INSERT INTO Archivos_Trabajo_General (idArchivo,nomArchivo,rutaArchivo,extArchivo,fechaSubida,idTG)
                        VALUES (NULL,'".$name."','".$ruta."','".$extImagen."','".$now."',$idTG)";
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