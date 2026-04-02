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

date_default_timezone_set('America/Santiago');

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
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}


if(isset($_POST['solicitanteRondas'], $_POST['problemaPresentaRondas'], $_POST['noOrdenTrabajoRondas'])){
    $personaSoli = $_POST['solicitanteRondas'];
    $noOrdenTrabajo = $_POST['noOrdenTrabajoRondas'];
    $problemaPresenta = trim($_POST['problemaPresentaRondas']);
    $ticketAsociado = $_POST['ticketAsociadoRondas'];
    $nomTecnicoRecepcionador = $_POST['nomTecnicoRecepcionadorRondas'];
    $departamentoSolicitante = $_POST['departamentoSolicitanteRondas'];
    $servicioOrden = $_POST['servicioOrdenRondas'];
    $recintoOrden = $_POST['recintoOrdenRondas'];
    $pmaOrden = $_POST['pmaOrdenRondas'];
    $coordenadasOrden = $_POST['coordenadasOrdenRondas'];
    $tipoMantencion = $_POST['tipoMantencionRondas'];
    $trabajoRealizado = trim($_POST['trabajoRealizadoRondas']);
    $accionesRealiza = trim($_POST['accionesRealizaRondas']);
    $comentariosTrabajos = trim($_POST['comentariosTrabajosRondas']);
    $archivosOrden = $_FILES['archivosOrdenRondas'];
    $fechaActualOrden = $_POST['fechaActualOrdenRondas'];
    $nomDepartamentoSolicitante = trim(getNomDepartamento($departamentoSolicitante));
    $now = date('Y-m-d H:i:s');
    $idTGRondas = $_POST['idTGRondas'];

    if (!empty($trabajoRealizado)) {

        $trabajoRealizado = str_replace("'", "\'", $trabajoRealizado);
    }

    if (!empty($detalleTrabajo)) {

        $detalleTrabajo = str_replace("'", "\'", $detalleTrabajo);
    }

    if (!empty($accionesRealiza)) {

        $accionesRealiza = str_replace("'", "\'", $accionesRealiza);
    }

    if (!empty($comentariosTrabajos)) {

        $comentariosTrabajos = str_replace("'", "\'", $comentariosTrabajos);
    }

    if($servicioOrden == '' || empty($servicioOrden) || $personaSoli == '' || empty($personaSoli) || $recintoOrden == '' || $recintoOrden == ' ' || empty($recintoOrden)){
        header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?errorGenerar1=true');
    }else{
        if ($departamentoSolicitante == '' || $departamentoSolicitante == ' ' || empty($departamentoSolicitante) || $trabajoRealizado == '' || $trabajoRealizado == ' ' || empty($trabajoRealizado)) {
            header('Location: ../' . $rutaDireccion . '/visualizardetalles.php?errorGenerar1=true');
        }else{
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


            $queryOrden = "INSERT INTO ordenes_trabajos_general(idOrden,numOrden,fechaOrden,nomSolicitante,nomTecnicoRecepcionador,idDepartamento,servicioOrden,recintoOrden,pmaOrden,coordenadasOrden,tipoMantencion,trabajoRealizado,problemaPresenta,accionesRealiza, comentariosTrabajos, ticketAsociado, idCreador, tipoUsuario, noOrdenTrabajo, idEquipo, idCategoria,tipoOT)
                VALUES (NULL,'".$numOrden."','" . $now . "','" . $personaSoli . "','" . $nomTecnicoRecepcionador . "','" . $departamentoSolicitante . "','" . $servicioOrden . "','" . $recintoOrden . "','" . $pmaOrden . "','" . $coordenadasOrden . "','" . $tipoMantencion . "','" . $trabajoRealizado . "','" . $problemaPresenta . "','" . $accionesRealiza . "','" . $comentariosTrabajos . "','" . $ticketAsociado . "', $idUsuario, '$tipoUsuario','" . $noOrdenTrabajo . "', 0, 0,'Rondas')";
            $resOrden = mysqli_query($conexion, $queryOrden);
            if($resOrden){
                $idOrden = mysqli_insert_id($conexion);

                $sqlInsertContadorHojas = "INSERT INTO numero_hojas_creadas (idOrden) VALUES ($idOrden) ";
                $resInsertContadorHojas = mysqli_query($conexion, $sqlInsertContadorHojas);

                $_SESSION['idOrden'] = $idOrden;

                $nomOrden = "Hoja Técnica: " . $folioOrden . ' - ' . $fechaActualOrden;

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
                if ($resFolio) {
                    $detalleObs1 = $nomUsuario.' a creado la hoja tecnica con folio: '. $folioOrden;

                    $sqlTrabajo = "INSERT INTO avances_trabajo_general (idAvance,nomCreador,detalleObs,estadoAvance,folioHT,fechaSubida,idTG,personaDerivada,noavance)
                    VALUES (NULL,'".$nomUsuario."','".$detalleObs1."','Avance','$folioOrden','".$now."',$idTGRondas,'NULL',1)";
                    $resTrabajo = mysqli_query($conexion,$sqlTrabajo);


                    if (!empty($_FILES["archivosOrdenRondas"])) {
                        $count = 0;
                        foreach ($_FILES['archivosOrdenRondas']['tmp_name'] as $tmpFilee) {
                            if (is_uploaded_file($tmpFilee)) {
                                $count++;
                            }
                        }
                        if ($count > 0) {

                            for ($i = 0; $i < $count; $i++) {
                                $tmp_name = $_FILES["archivosOrdenRondas"]["tmp_name"][$i];
                                $name = $_FILES["archivosOrdenRondas"]["name"][$i];

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
                    if($tipoUsuario != 'personal'){
                        header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?exitoGenerarRONDAS=true');
                    }else{
                        header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?exitoGenerarRONDAS=true');
                    }
                }else{
                    if($tipoUsuario != 'personal'){
                        header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?errorFolioRONDAS=true');
                    }else{
                        header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?errorFolioRONDAS=true');
                    }
                }
            }else{
                if($tipoUsuario != 'personal'){
                    header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?errorOrdenRONDAS=true');
                }else{
                    header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?errorOrdenRONDAS=true');
                }
            }
        }
    }
}else{
    if($tipoUsuario != 'personal'){
        header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?errorGenerarRondas=true');
    }else{
        header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?errorGenerarRondas=true');
    }
}