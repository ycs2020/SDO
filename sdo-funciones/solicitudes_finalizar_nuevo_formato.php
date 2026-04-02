<?php

include_once 'conexion.php';
include_once 'funciones.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';

require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';

$mail = new PHPMailer(true);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$idUsuario = $_SESSION['idUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$nomCreador = $_SESSION['nomUsuario'];

$rolUsuario2 = strtolower($rolUsuario);


$ruta = "";

if ($rolUsuario2 == "personal") {

    $sql = "SELECT * FROM personal WHERE idPersonal = $idUsuario ";
    $res = mysqli_query($conexion, $sql);

    $tipoUsuario = 'personal';

    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $tipoUsuario = 'administrativo';
    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "jefe tecnico") {
    $tipoUsuario = 'jefe tecnico';
    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $tipoUsuario = 'administrador';
    $ruta = "sdo-administrador";
} else {

    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

$now = date('Y-m-d H:i:s');
$nowYear = date('Y-m-d');

$headerTextCorrecto = 'Location: ../' . $ruta . '/solicitudes_detalle.php?exitoFinalizarSolicitud=true';
$headerError = 'Location: ../' . $ruta . '/solicitudes_recibidas.php?errorIdSolicitud=true';
$headerError2 = 'Location: ../' . $ruta . '/solicitudes_recibidas.php?errorDepartamentoResponsable=true';
$headerError3 = 'Location: ../' . $ruta . '/solicitudes_detalle.php?errorFinalizarSolicitud=true';


if (isset($_POST['idSolicitud'], $_POST['conclusionProblema'], $_POST['datosFinalizador'], $_POST['nomSolicitante'])) {
    $idSolicitud = $_POST['idSolicitud'];
    $conclusionProblema = $_POST['conclusionProblema'];
    $conclusionProblema = mysqli_real_escape_string($conexion, $conclusionProblema);
    $datosFinalizador = trim($_POST['datosFinalizador']);
    $nomSolicitante = trim($_POST['nomSolicitante']);
    $folioSolicitud = getFolioSolicitud($idSolicitud);
    $deptoSolicitante = $_POST['deptoSolicitante'];
    $pmaSolicitud = $_POST['pmaSolicitud'];
    $detalleSolicitud = $_POST['detalleSolicitud'];
    $servicioOrden = trim($_POST['servicios']);
    $deptoResponsable = trim($_POST['departamentoResponsable']);
    $idDepartamento = $_POST['idDepartamento'];
    $nomDepartamentoSolicitante = trim(getNomDepartamento($idDepartamento));
    $idHoja = $_POST['idHoja'];
    $idCategoria = $_POST['idCategoria'];
    $idEquipo = $_POST['idEquipo'];
    $horaInicio = $_POST['horarioInicio'];
    $horarioTermino = $_POST['horarioTermino'];
    $noOrdenTrabajo = $_POST['noOrdenTrabajo'];
    $tipoMantencion = $_POST['tipoMantencion'];
    $nomEquipo = $_POST['nomEquipo'];
    $numInventario = $_POST['numInventario'];
    $marcaEquipo = $_POST['marcaEquipo'];
    $modeloEquipo = $_POST['modeloEquipo'];
    $numSerie = $_POST['numSerie'];
    $recintoOrden = $_POST['recintoOrden'];
    $oc = $_POST['oc'];
    $estadoEquipo = $_POST['estadoEquipo'];
    $comentariosTrabajos = $_POST['comentariosTrabajos'];
    $tipoAcuerdo = $_POST['tipoAcuerdo'];
    $accionesRealiza = trim($_POST['accionesRealiza']);
    $coordenadasOrden = trim($_POST['coordenadasOrden']);

    if (!empty($accionesRealiza)) {

        $accionesRealiza = str_replace("'", "\'", $accionesRealiza);
    }

    if($horaInicio == '' || empty($horaInicio)){
        $horaInicio = '0000-00-00 00:00:00';
    }

    if($horarioTermino == '' || empty($horarioTermino)){
        $horarioTermino = '0000-00-00 00:00:00';
    }

    if($idHoja == ''){
        $idHoja = 0;
    }

    if($idCategoria == ''){
        $idCategoria = 0;
    }

    if (!empty($conclusionProblema)) {

        $conclusionProblema = str_replace("'", "\'", $conclusionProblema);
    }

    if (!empty($detalleSolicitud)) {

        $detalleSolicitud = str_replace("'", "\'", $detalleSolicitud);
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

    $año = date('Y');
//            $selectAct = "SELECT numOrden FROM ordenes_trabajos_general WHERE YEAR(fechaOrden) = 2024 ORDER BY fechaOrden DESC LIMIT 1";
    $selectAct = "SELECT numOrden FROM ordenes_trabajos_general WHERE YEAR(fechaOrden) = ".$año." ORDER BY fechaOrden DESC LIMIT 1";
    $resAct = mysqli_query($conexion,$selectAct);
    if ($resAct->num_rows == 0) {
        $numOrden = 1;
    }else{

        $fila = $resAct->fetch_assoc();
        $numOrden = $fila['numOrden'];
        $numOrden = $numOrden + 1;
    }

    $pieces = explode(" ", $nomDepartamentoSolicitante);

    $str = "";
    foreach ($pieces as $piece) {
        $str .= mb_strtoupper($piece[0], 'UTF-8');
    }

    $folioOrden = "HBQP-HT-A".$año."-" . $str . '-' . $numOrden;

    $queryOrden = "INSERT INTO ordenes_trabajos_general(idOrden,numOrden,fechaOrden,nomSolicitante,nomTecnicoRecepcionador,idDepartamento,servicioOrden,recintoOrden,pmaOrden,coordenadasOrden,tipoMantencion,trabajoRealizado,problemaPresenta,accionesRealiza, comentariosTrabajos, ticketAsociado, idCreador, tipoUsuario, noOrdenTrabajo, idEquipo, idCategoria,horaInicio,horaTermino,tipoAcuerdo,estadoEquipo,oc)
                VALUES (NULL,".$numOrden.",'".$now."','" . $nomSolicitante . "','" . $datosFinalizador . "','" . $idDepartamento . "','" . $servicioOrden . "','" . $recintoOrden . "','" . $pmaSolicitud . "','" . $coordenadasOrden . "','" . $tipoMantencion . "','" . $conclusionProblema . "','" . $detalleSolicitud . "','" . $accionesRealiza . "','" . $comentariosTrabajos . "','" .$folioSolicitud . "', $idUsuario, '$tipoUsuario','" . $noOrdenTrabajo . "', $idHoja, $idCategoria,'".$horaInicio."','".$horarioTermino."','".$tipoAcuerdo."','".$estadoEquipo."','".$oc."')";
    $resOrdem = mysqli_query($conexion,$queryOrden);
    if($resOrdem){
        $idOrden = mysqli_insert_id($conexion);

        if (!empty($_POST['nomEquipo']) || !empty($_POST['marcaEquipo']) || !empty($_POST['numInventario'])) {
            $queryEquipoOrden = "INSERT INTO equipos_recepcion_trabajo (equipo,numInventario,marca,modelo,numSerie,idOrden,idEquipo,idCategoria) 
            VALUES ('$nomEquipo', '$numInventario', '$marcaEquipo', '$modeloEquipo', '$numSerie', $idOrden,$idEquipo,$idCategoria)";
            $resEquipoOrden = mysqli_query($conexion, $queryEquipoOrden);
        }

        $_SESSION['idOrden'] = $idOrden;

        $nomOrden = "Hoja Técnica: " . $folioOrden . ' - ' . $nowYear;

        $idSubDepartamento = '';

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

        $queryFolio = "UPDATE ordenes_trabajos_general SET folioOrden = '" . $folioOrden . "', nomOrden = '" . $nomOrden . "', idSubDepartamento = '" . $idSubDepartamento . "' WHERE idOrden =" . $idOrden;

        $resFolio = mysqli_query($conexion, $queryFolio);

        $queryFinalizar = "UPDATE solicitudes_soportes SET terminoSolicitud = '" . $now . "' , finalizadaSolicitud = 1 , situacionSolicitud = 'Solicitud Finalizada', conclusionSolicitud = '" . $conclusionProblema . "' WHERE idSolicitud = " . $idSolicitud;

        $resFinalizar = mysqli_query($conexion, $queryFinalizar);

        if($resFolio && $resFinalizar){

            $observacion = $datosFinalizador . ' ha finalizado la Solicitud de Soporte # ' . getFolioSolicitud($idSolicitud) . ' .';

            $tipoObservaSolicitudes = "Finalización de Solicitud";

            date_default_timezone_set('America/Santiago');
            $now = date('Y-m-d H:i:s', time());

            $sqlObservacion = "INSERT INTO observaciones_tickets(idObservaSolicitudes,detalleObservaSolicitudes,fechaObservaSolicitudes,tipoObservaSolicitudes,creadorObservaSolicitudes,idSolicitud)
            VALUES(NULL,'" . $observacion . "','" . $now . "','" . $tipoObservaSolicitudes . "','" . $datosFinalizador . "'," . $idSolicitud . ")";

            $resObservacion = mysqli_query($conexion, $sqlObservacion);


            if($resObservacion){
                $usoMaterial = '';

                $usoFoto = '';

                if(isset($_POST['cantidadFilas'])) {
                    $cantidadFilas = $_POST['cantidadFilas'];

                    if ($cantidadFilas > 0) {

                        for ($i = 1; $i <= $cantidadFilas; $i++) {

                            $articuloSC = $_POST['articuloSC' . $i];
                            $cantidadSC = $_POST['cantidadSC' . $i];
                            $medidaSC = $_POST['medidaSC' . $i];
                            $idInventarioAgregar = $_POST['idInventarioAgregar' . $i];

                            if($idInventarioAgregar > 0){
                                $obtenerCantMaxInvent = obtenerCantMaxInvent($idInventarioAgregar);

                                $restaInv = $obtenerCantMaxInvent - $cantidadSC;

                                $sqlInventario = "UPDATE inventario_equipos SET cantidadInventario = ".$restaInv." WHERE idInventario = ".$idInventarioAgregar;

                                $resInventario = mysqli_query($conexion, $sqlInventario);

                                if($resInventario){
                                    $_SESSION['usoMaterialSolicitud'] = 'Usando';
                                }else{
                                    $_SESSION['usoMaterialSolicitud'] = 'Vacio';
                                }
                            }

                            $queryMaterial = "INSERT INTO material_orden_general(idMaterial,nomMaterial,unidadMaterial,cantidadMaterial,idOrden) VALUES(NULL,'" . $articuloSC . "','" . $medidaSC . "'," . $cantidadSC . "," . $idOrden . ")";


                            echo '<br><br>'.$articuloSC.'  ----  '.$medidaSC.'  ----  '.$cantidadSC.'  ----  ';

                            echo '<br><br>'.$queryMaterial;

                            $resMaterial = mysqli_query($conexion, $queryMaterial);

                            if ($resMaterial) {
                                $_SESSION['usoMaterialSolicitud'] = 'Usando';
                            } else {
                                $_SESSION['usoMaterialSolicitud'] = 'Vacio';
                            }


                        }

                    }else {
                        $_SESSION['usoMaterialSolicitud'] = 'Vacio';
                    }

                }else{
                    $_SESSION['usoMaterialSolicitud'] = 'Vacio';
                }


                if (!empty($_FILES["archivosOrden"])) {

                    $count = 0;
                    foreach ($_FILES['archivosOrden']['tmp_name'] as $tmpFilee) {
                        if (is_uploaded_file($tmpFilee)) {
                            $count++;
                        }
                    }
                    if ($count > 0) {

                        for ($i = 0; $i < $count; $i++) {
                            $tmp_name = $_FILES["archivosOrden"]["tmp_name"][$i];
                            $name = $_FILES["archivosOrden"]["name"][$i];

                            if ($tmp_name == '' || $tmp_name == ' ' || empty($tmp_name)) {
                                echo "Vacios";
                            } else {

                                $directorio = "../sdo-archivos-orden/" . $folioOrden. '/';
                                if (!file_exists($directorio)) {
                                    mkdir($directorio, 0777, true);
                                }

                                $dir = opendir($directorio);

                                $rutaArchivo = $directorio . $name;

                                $separarNombre = explode(".", $name);

                                $extImagen = end($separarNombre);

                                $extImagen = mb_strtolower($extImagen);

                                if ($extImagen == "pdf" || $extImagen == "doc" || $extImagen == "docx" || $extImagen == "xls" || $extImagen == "xlsx" || $extImagen == "eml") {

                                    if (move_uploaded_file($tmp_name, $rutaArchivo)) {

                                        chmod($rutaArchivo, 0777);
                                        $queryArchivos = "INSERT INTO archivos_orden_general(idArchivo,nomArchivo,rutaArchivo, idOrden) VALUES (NULL,'" . $name . "','" . $rutaArchivo . "','" . $idOrden . "')";
                                        $resArchivos = mysqli_query($conexion, $queryArchivos);
                                        // echo $queryArchivos;
                                        // exit;
                                    } else {
                                        echo "FALLO EN LA SUBIDA DE archivos";
                                    }
                                }
                            }
                        }
                    }
                }
                if (isset($_FILES["archivoImagenOrden"])) {
                    $tot = count($_FILES["archivoImagenOrden"]["name"]);
                    for ($i = 0; $i < $tot; $i++) {
                        $tmp_name = $_FILES["archivoImagenOrden"]["tmp_name"][$i];
                        $name = $_FILES["archivoImagenOrden"]["name"][$i];

                        if ($tmp_name == '' || $tmp_name == ' ' || empty($tmp_name)) {
                            echo 'Vacios';
                        } else {

                            $directorio = "../sdo-imagenes-orden/" . $folioOrden . "/";

                            if (!file_exists($directorio)) {
                                mkdir($directorio, 0777);
                            }

                            $dir = opendir($directorio);

                            $ruta = $directorio . $name;

                            $separarNombre = explode(".", $name);

                            $extImagen = end($separarNombre);

                            $extImagen = mb_strtolower($extImagen);

                            if ($extImagen == "jpg" || $extImagen == "png" || $extImagen == "jpeg" || $extImagen == "bmp" || $extImagen == "tif" || $extImagen == "webp" || $extImagen == "svg" || $extImagen == "jfif" || $extImagen == "pdf" || $extImagen == "docx" || $extImagen == "xlsx") {

                                // if (is_dir($directorio) && is_writable($directorio)) {
                                //     echo 'hola';
                                // } else {
                                //     echo 'Upload directory is not writable, or does not exist.';
                                // }

                                if (move_uploaded_file($tmp_name, $ruta)) {

                                    $queryImagen = "INSERT INTO imagenes_orden_general(idImagen,nomImagen,extImagen,rutaImagen,idReporte,idOrden) VALUES (NULL,'" . $name . "','" . $extImagen . "','" . $ruta . "',0,'" . $idOrden . "')";

                                    $resImagen = mysqli_query($conexion, $queryImagen);

                                    if ($resImagen) {
                                        echo "SI SUBI";

                                    }
                                } else {
                                    echo 'NO SUBI';
                                }
                            } else {
                                echo "FORMATO CORRECTO";
                            }
                        }
                    }
                }else {
                    $_SESSION['usoFotoSolicitud'] = 'Vacio';
                }
                header($headerTextCorrecto);
            }else {
                header($headerError3);
            }
        }else{
            header($headerError3);
        }
    }else{
        header($headerError);
    }
}else{
    header($headerError);
}