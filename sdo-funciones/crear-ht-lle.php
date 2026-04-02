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

if(isset($_POST['solicitanteLLE'], $_POST['problemaPresentaLLE'], $_POST['noOrdenTrabajoLLE'])){

    $personalSoli = $_POST['solicitanteLLE'];
    $ticketAsociado = $_POST['ticketAsociadoLLE'];
    $noOrdenTrabajo = $_POST['noOrdenTrabajoLLE'];
    $idHoja = $_POST['idHojaLLE'];
    $idCategoria = $_POST['idCategoriaLLE'];
    $idEquipo = $_POST['idEquipoLLE'];
    $departamentoSolicitante = $_POST['departamentoSolicitanteLLE'];
    $servicioOrden = $_POST['servicioOrdenLLE'];
    $recintoOrden = $_POST['recintoOrdenLLE'];
    $pmaOrden = $_POST['pmaOrdenLLE'];
    $coordenadasOrden = $_POST['coordenadasOrdenLLE'];
    $tipoMantencion = $_POST['tipoMantencionLLE'];
    $detalleTrabajo = trim($_POST['problemaPresentaLLE']);
    $trabajoRealizado = trim($_POST['trabajoRealizadoLLE']);
    $accionesRealiza = trim($_POST['accionesRealizaLLE']);
    $comentariosTrabajos = trim($_POST['comentariosTrabajosLLE']);
    $nomDepartamentoSolicitante = trim(getNomDepartamento($departamentoSolicitante));
    $fechaActualOrden = $_POST['fechaActualOrdenLLE'];
    $idTG = $_POST['idTGLLE'];
    $folioTG = $_POST['folioTGLLE'];

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

    if($idHoja == ''){
        $idHoja = 0;
    }

    if($idCategoria == '' || empty($idCategoria )){
        $idCategoria = 0;
    }

    $nomTecnicoRecepcionador = trim($_POST['nomTecnicoRecepcionadorLLE']);
    $archivoImagenOrden = $_FILES['archivoImagenOrdenLLE'];
    $archivosOrden = $_FILES['archivosOrdenLLE'];
    $time = date('Y-m-d H:i:s');
    $nomEquipo = $_POST['nomEquipoLLE'];
    $numInventario = $_POST['numInventarioLLE'];
    $marcaEquipo = $_POST['marcaEquipoLLE'];
    $modeloEquipo = $_POST['modeloEquipoLLE'];
    $numSerie = $_POST['numSerieLLE'];

    if($personalSoli == '' || empty($personalSoli) || $servicioOrden == '' || empty($servicioOrden) || $recintoOrden == '' || $recintoOrden == ' ' || empty($recintoOrden)){
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
            VALUES (NULL,'".$numOrden."','" . $time . "','" . $personalSoli . "','" . $nomTecnicoRecepcionador . "','" . $departamentoSolicitante . "','" . $servicioOrden . "','" . $recintoOrden . "','" . $pmaOrden . "','" . $coordenadasOrden . "','" . $tipoMantencion . "','" . $trabajoRealizado . "','" . $detalleTrabajo . "','" . $accionesRealiza . "','" . $comentariosTrabajos . "','" . $ticketAsociado . "', $idUsuario, '$tipoUsuario','" . $noOrdenTrabajo . "', $idHoja, $idCategoria,'LLE')";
            $resOrden = mysqli_query($conexion, $queryOrden);
            if($resOrden){
                $idOrden = mysqli_insert_id($conexion);
                if (!empty($_POST['nomEquipoLLE']) || !empty($_POST['marcaEquipoLLE']) || !empty($_POST['numInventarioLLE'])) {
                    $queryEquipoOrden = "INSERT INTO equipos_recepcion_trabajo (equipo,numInventario,marca,modelo,numSerie,idOrden,idEquipo,idCategoria) VALUES ('$nomEquipo', '$numInventario', '$marcaEquipo', '$modeloEquipo', '$numSerie', $idOrden,$idEquipo,$idCategoria)";
                    $resEquipoOrden = mysqli_query($conexion, $queryEquipoOrden);
                }

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
                    $usoMaterial = '';
                    $usoFoto = '';
                    if (isset($_POST['materialUsadoLLE'], $_POST['unidadLLE'], $_POST['cantidadLLE'])) {
                        $materialUsado = $_POST['materialUsadoLLE'];
                        $unidad = $_POST['unidadLLE'];
                        $cantidad = $_POST['cantidadLLE'];
                        if (!empty($materialUsado) && !empty($unidad) && !empty($cantidad)) {

                            for ($i = 0; $i < count($materialUsado); $i++) {
                                if (!empty($materialUsado[$i])) {
                                    $materialFor = $materialUsado[$i];
                                    $unidadFor = $unidad[$i];

                                    if(empty($unidadFor)){
                                        $unidadFor = NULL;
                                    }

                                    $cantidadFor = $cantidad[$i];

                                    if(empty($cantidadFor)){
                                        $cantidadFor = 0;
                                    }

                                    $queryMaterial = "INSERT INTO material_orden_general(idMaterial,nomMaterial,unidadMaterial,cantidadMaterial,idOrden) VALUES(NULL,'" . $materialFor . "','" . $unidadFor . "'," . $cantidadFor . "," . $idOrden . ")";

                                    echo '<br><br>'.$materialFor.'  ----  '.$unidadFor.'  ----  '.$cantidadFor.'  ----  ';

                                    echo '<br><br>'.$queryMaterial;

                                    $resMaterial = mysqli_query($conexion, $queryMaterial);

                                    if ($resMaterial) {
                                        $_SESSION['usoMaterialLLE'] = 'Usando';
                                    } else {
                                        $_SESSION['usoMaterialLLE'] = 'Vacio';
                                    }
                                } else {
                                    $_SESSION['usoMaterialLLE'] = 'Vacio';
                                }
                            }
                        }else{
                            $_SESSION['usoMaterialLLE'] = 'Vacio';
                        }
                    }else{
                        $_SESSION['usoMaterialLLE'] = 'Vacio';
                    }

                    $detalleObs1 = $nomUsuario.' a creado la hoja tecnica con folio: '. $folioOrden;

                    $sqlTrabajo = "INSERT INTO avances_trabajo_general (idAvance,nomCreador,detalleObs,estadoAvance,folioHT,fechaSubida,idTG,personaDerivada,noavance)
                    VALUES (NULL,'".$nomUsuario."','".$detalleObs1."','Avance','$folioOrden','".$time."',$idTG,'NULL',1)";
                    $resTrabajo = mysqli_query($conexion,$sqlTrabajo);

                    if (!empty($_FILES["archivosOrdenLLE"])) {
                        $count = 0;
                        foreach ($_FILES['archivosOrdenLLE']['tmp_name'] as $tmpFilee) {
                            if (is_uploaded_file($tmpFilee)) {
                                $count++;
                            }
                        }
                        if ($count > 0) {

                            for ($i = 0; $i < $count; $i++) {
                                $tmp_name = $_FILES["archivosOrdenLLE"]["tmp_name"][$i];
                                $name = $_FILES["archivosOrdenLLE"]["name"][$i];

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

                    if (isset($_FILES["archivoImagenOrdenLLE"])) {
                        $tot = count($_FILES["archivoImagenOrdenLLE"]["name"]);
                        for ($i = 0; $i < $tot; $i++) {
                            $tmp_name = $_FILES["archivoImagenOrdenLLE"]["tmp_name"][$i];
                            $name = $_FILES["archivoImagenOrdenLLE"]["name"][$i];

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
                    }
                    if($tipoUsuario != 'personal'){
                        header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?exitoGenerarLLE=true');
                    }else{
                        header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?exitoGenerarLLE=true');
                    }
                }else{
                    if($tipoUsuario != 'personal'){
                        header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?errorFolioLLE=true');
                    }else{
                        header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?errorFolioLLE=true');
                    }
                }
            }else{
                if($tipoUsuario != 'personal'){
                    header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?errorGenerarLLE=true');
                }else{
                    header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?errorGenerarLLE=true');
                }
            }
        }
    }
}else{
    if($tipoUsuario != 'personal'){
        header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?errorGenerarLLE=true');
    }else{
        header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?errorGenerarLLE=true');
    }
}