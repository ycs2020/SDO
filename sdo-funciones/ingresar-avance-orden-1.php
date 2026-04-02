<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');


$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = trim($_SESSION['nomUsuario']);
$userUsuario = $_SESSION['userUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);

$rutaDireccion = "";

if ($rolUsuario2 == "personal") {
    $rutaDireccion = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $rutaDireccion = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $rutaDireccion = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $rutaDireccion = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $rutaDireccion = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $rutaDireccion = "sdo-directiva";
} else if ($rolUsuario2 == 'oficina tecnica'){
    $rutaDireccion = "sdo-oficinaTecnica";
}else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if(isset($_POST['idTG'])){
    $idTG = $_POST['idTG'];
    $detalleObs = trim($_POST['detalleObs']);
    $problemaPresenta = trim($_POST['problemaPresenta']);
    $trabajoRealizado = trim($_POST['trabajoRealizado']);
    $accionesRealiza = trim($_POST['accionesRealiza']);
    $comentariosTrabajos = trim($_POST['comentariosTrabajos']);
    $solicitante = $_POST['solicitante'];
    $servicio = 22;
    $tipoMantencion = $_POST['tipoMantencion'];
    $nomTecnicoRecepcionador = $_POST['nomTecnicoRecepcionador'];
    $departamentoSolicitante = $_POST['departamentoSolicitante'];
   // $selectAvance = $_POST['selectAvance'];
    $folioTG = $_POST['folioTG'];
    $fechaTG = $_POST['fechaTG'];
    $now = date('Y-m-d H:i:s');
    $avance = $_POST['avance'];
    $servicioOrden = $_POST['servicioOrden'];
    $recintoOrden = $_POST['recintoOrden'];
    $pmaOrden = $_POST['pmaOrden'];
    $coordenadasOrden = $_POST['coordenadasOrden'];
    $nomDepartamentoSolicitante = trim(getNomDepartamento($departamentoSolicitante));
    $fechaActualOrden = $_POST['fechaActualOrden'];
    $nomEquipo = $_POST['nomEquipo'];
    $numInventario = $_POST['numInventario'];
    $marcaEquipo = $_POST['marcaEquipo'];
    $modeloEquipo = $_POST['modeloEquipo'];
    $numSerie = $_POST['numSerie'];
    $idHoja = $_POST['idHoja'];
    $idCategoria = $_POST['idCategoria'];
    $idEquipo = $_POST['idEquipo'];
    $observacion = trim($_POST['observacion']);
    $ticketAsociado = $_POST['ticketAsociado'];
    $noOrdenTrabajo = $_POST['noOrdenTrabajo'];

    if($observacion == '' || empty($observacion)){
        $observacion = 'NULL';
    }

    if($detalleObs == '' || empty($detalleObs)){
        $detalleObs = 'NULL';
    }

    if($idHoja == '' || empty($idHoja)){
        $idHoja = 0;
    }
    if($idCategoria == '' || empty($idCategoria)){
        $idCategoria = 0;
    }
    if (!empty($trabajoRealizado)) {

        $trabajoRealizado = str_replace("'", "\'", $trabajoRealizado);
    }

    if (!empty($problemaPresenta)) {

        $problemaPresenta = str_replace("'", "\'", $problemaPresenta);
    }

    if (!empty($accionesRealiza)) {

        $accionesRealiza = str_replace("'", "\'", $accionesRealiza);
    }

    if (!empty($comentariosTrabajos)) {

        $comentariosTrabajos = str_replace("'", "\'", $comentariosTrabajos);
    }

    if($avance == 'Avance'){
        $sql = "INSERT INTO avances_trabajo_general (idAvance,nomCreador,detalleObs,observacion,estadoAvance,fechaSubida,idTG,personaDerivada,noavance)
        VALUES (NULL,'".$nomUsuario."','".$detalleObs."','".$observacion."','".$avance."','".$now."',$idTG,'NULL',NULL)";
        $res = mysqli_query($conexion,$sql);

        if($res){
            $sqlUpdate = "UPDATE trabajos_general SET estadoTG = 'Avance del Trabajo' WHERE idTG = $idTG";
            $resUpdate = mysqli_query($conexion,$sqlUpdate);

            if($resUpdate){
                header('Location: ../'.$rutaDireccion . '/trabajos_general_listado.php?exitoGenerar=true');

            }else{
                header('Location: ../'.$rutaDireccion . '/trabajos_general_listado.php?errorGenerar0=true');
            }
        }else{
            header('Location: ../'.$rutaDireccion . '/trabajos_general_listado.php?errorGenerar1=true');
        }
    }else if($avance == 'Termino Trabajo'){

        $queryOrden = "INSERT INTO ordenes_trabajos_general(idOrden,fechaOrden,nomSolicitante,nomTecnicoRecepcionador,idDepartamento,servicioOrden,recintoOrden,pmaOrden,coordenadasOrden,tipoMantencion,trabajoRealizado,problemaPresenta,accionesRealiza, comentariosTrabajos, ticketAsociado, idCreador, tipoUsuario, noOrdenTrabajo, idEquipo, idCategoria, tipoOT)
        VALUES (NULL,'".$now."','" . $solicitante . "','" . $nomTecnicoRecepcionador . "','" . $departamentoSolicitante . "','" . $servicioOrden . "','" . $recintoOrden . "','" . $pmaOrden . "','" . $coordenadasOrden . "','" . $tipoMantencion . "','" . $trabajoRealizado . "','" . $problemaPresenta . "','" . $accionesRealiza . "','" . $comentariosTrabajos . "','" . $ticketAsociado . "', $idUsuario, '$rolUsuario2','" . $noOrdenTrabajo . "', $idHoja, $idCategoria,'')";

        $resOrden = mysqli_query($conexion, $queryOrden);

        if($resOrden) {
            $idOrden = mysqli_insert_id($conexion);

            if (!empty($_POST['nomEquipo']) || !empty($_POST['marcaEquipo']) || !empty($_POST['numInventario'])) {
                $queryEquipoOrden = "INSERT INTO equipos_recepcion_trabajo (equipo,numInventario,marca,modelo,numSerie,idOrden,idEquipo,idCategoria) 
                VALUES ('$nomEquipo', '$numInventario', '$marcaEquipo', '$modeloEquipo', '$numSerie', $idOrden,$idEquipo,$idCategoria)";
                $resEquipoOrden = mysqli_query($conexion, $queryEquipoOrden);
            }

            $sqlInsertContadorHojas = "INSERT INTO numero_hojas_creadas (idOrden) VALUES ($idOrden) ";
            $resInsertContadorHojas = mysqli_query($conexion, $sqlInsertContadorHojas);

            $_SESSION['idOrden'] = $idOrden;

            $pieces = explode(" ", $nomDepartamentoSolicitante);
            $str = "";
            foreach ($pieces as $piece) {
                $str .= mb_strtoupper($piece[0], 'UTF-8');
            }

            $folioOrden = "HBQP-HT-" . $str . '-' . $idOrden;

            $nomOrden = "Hoja Técnica: " . $folioOrden . ' - ' . $fechaActualOrden;

            $queryFolio = "UPDATE ordenes_trabajos_general SET folioOrden = '" . $folioOrden . "', nomOrden = '" . $nomOrden . "' WHERE idOrden =" . $idOrden;

            $resFolio = mysqli_query($conexion, $queryFolio);

            if($resFolio){

                $usoMaterial = '';

                $usoFoto = '';

                if (isset($_POST['materialUsado'], $_POST['unidad'], $_POST['cantidad'])) {

                    $materialUsado = $_POST['materialUsado'];
                    $unidad = $_POST['unidad'];
                    $cantidad = $_POST['cantidad'];

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
                                    $_SESSION['usoMaterial'] = 'Usando';
                                } else {
                                    $_SESSION['usoMaterial'] = 'Vacio';
                                }
                            } else {
                                $_SESSION['usoMaterial'] = 'Vacio';
                            }
                        }
                    } else {
                        $_SESSION['usoMaterial'] = 'Vacio';
                    }
                } else {
                    $_SESSION['usoMaterial'] = 'Vacio';
                }

                $detalleObs1 = $nomUsuario.' a creado la hoja tecnica con folio: '. $nomOrden;

                $sqlTrabajo = "INSERT INTO avances_trabajo_general (idAvance,nomCreador,detalleObs,estadoAvance,folioHT,fechaSubida,idTG,personaDerivada,noavance)
                VALUES (NULL,'".$nomUsuario."','".$detalleObs1."','".$avance."','".$folioOrden."','".$now."',$idTG,'NULL',1)";
                $resTrabajo = mysqli_query($conexion,$sqlTrabajo);

                if($resTrabajo){
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
                    }
                    header('Location: ../'.$rutaDireccion . '/trabajos_general_listado.php?exitoGenerar=true');
                }else{
                    header('Location: ../'.$rutaDireccion . '/trabajos_general_listado.php?errorGenerar5=true');
                }
            }else{
                header('Location: ../'.$rutaDireccion . '/trabajos_general_listado.php?errorGenerar2=true');
            }
        }else{
            header('Location: ../'.$rutaDireccion . '/trabajos_general_listado.php?errorGenerar3=true');
        }
    }else{
        echo 'NO ENTRO';
    }

}else{
    header('Location: ../'.$rutaDireccion . '/trabajos_general_listado.php?errorGenerar4=true');
}


