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


if (isset($_POST['nomSolicitante'], $_POST['servicioOrden'], $_POST['departamentoSolicitante'], $_POST['recintoOrden'],  $_POST['fechaActualOrden'])) {

    $ticketAsociado = $_POST['ticketAsociado'];
    $noOrdenTrabajo = $_POST['noOrdenTrabajo'];
    $nomSolicitante = trim($_POST['nomSolicitante']);
    $idHoja = $_POST['idHoja'];
    $idCategoria = $_POST['idCategoria'];
    $idEquipo = $_POST['idEquipo'];
    date_default_timezone_set('America/Santiago');
    $now = date('Y-m-d H:i:s', time());


    $sqlTrabajoGeneral = "SELECT * FROM trabajos_general WHERE folioTG = '$noOrdenTrabajo'";
    $resTrabajoGeneral = mysqli_query($conexion,$sqlTrabajoGeneral);
    while($rowTrabajoGeneral = $resTrabajoGeneral->fetch_assoc()){
        $idTG = $rowTrabajoGeneral['idTG'];
        $sqlOtro = "SELECT * FROM archivos_recepcion_documento WHERE idTG = $idTG";
        $resOtro = mysqli_query($conexion,$sqlOtro);
        if(mysqli_num_rows($resOtro) > 0) {
            while($rowOtro = $resOtro->fetch_assoc()){
                $idRecepcion = $rowOtro['idRecepcion'];
                $sqlRecepcion = "SELECT * FROM recepcion_documento WHERE idRecepcion = $idRecepcion";
                $resRecepcion = mysqli_query($conexion,$sqlRecepcion);
                while($rowRecepcion = $resRecepcion->fetch_assoc()){
                    $sqlUpdateRecepcion = "UPDATE recepcion_documento SET estado = 'Finalizado' WHERE idRecepcion = $idRecepcion";
                    $resUpdateRecepcion = mysqli_query($conexion,$sqlUpdateRecepcion);
                    if($resUpdateRecepcion){
                        $detalleObsRecep = "Se finaliza el documento con la Orden de Trabajo con folio: " .$noOrdenTrabajo;
                        $insertObservacion = "INSERT INTO `recepcion_observacion`(`idObsRecepcion`, `detalleObservacion`, `fechaObs`, `nomCreador`, `idRecepcion`, `estado`) 
                    VALUES (NULL,'".$detalleObsRecep."','".$now."','".$nomUsuario."',$idRecepcion,'Finalizado')";
                        $resInsertObservacion = mysqli_query($conexion,$insertObservacion);
                        if(!$resInsertObservacion){
                            if($tipoUsuario !== 'personal'){
                                header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?errorUpdateRecepcion=true');
                            }else{
                                header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?errorUpdateRecepcion=true');
                            }
                        }
                    }else{
                        if($tipoUsuario !== 'personal'){
                            header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?errorUpdateRecepcion=true');
                        }else{
                            header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?errorUpdateRecepcion=true');
                        }
                    }
                }
            }
        }
    }


    if($idHoja == ''){
        $idHoja = 0;
    }

    if($idCategoria == ''){
        $idCategoria = 0;
    }

    $nomTecnicoRecepcionador = trim($_POST['nomTecnicoRecepcionador']);



    $listadoUsuarios = $_POST['listadoUsuarios'];


    if (is_array($listadoUsuarios)) {
        sort($listadoUsuarios);
        // Unir los elementos del array con ' - ' como delimitador
        $listadoUsuarios = implode(' - ', $listadoUsuarios);
    } else {
        $listadoUsuarios = '';
    }

    $servicioOrden = trim($_POST['servicioOrden']);

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

    $recintoOrden = trim($_POST['recintoOrden']);
    $pmaOrden = trim($_POST['pmaOrden']);
    $coordenadasOrden = trim($_POST['coordenadasOrden']);
    $tipoMantencion = $_POST['tipoMantencion'];
    $radioInput = $_POST['radio'];
    $ordenCompra = $_POST['ordenCompra'];
    $otroTipoTrabajo = $_POST['otroTipoTrabajo'];
    $trabajoRealizado = trim($_POST['trabajoRealizado']);
    $problemaPresenta = trim($_POST['problemaPresenta']);
    $accionesRealiza = trim($_POST['accionesRealiza']);
    $comentariosTrabajos = trim($_POST['comentariosTrabajos']);
    $fechaActualOrden = $_POST['fechaActualOrden'];
    $departamentoSolicitante = trim($_POST['departamentoSolicitante']);
    $nomDepartamentoSolicitante = trim(getNomDepartamento($departamentoSolicitante));
    $selectDepa = $_POST['selectDepa'];

    $nomEquipo = $_POST['nomEquipo'];
    $numInventario = $_POST['numInventario'];
    $marcaEquipo = $_POST['marcaEquipo'];
    $modeloEquipo = $_POST['modeloEquipo'];
    $numSerie = $_POST['numSerie'];
    $selectservicio = $_POST['selectservicio'];
    $categoriaEquipo = $_POST['categoriaEquipo'];
    $inputpiso = $_POST['inputpiso'];
    $idDepaPertenece = $_POST['depa'];
    $nomEquipo1 = $_POST['nomEquipo1'];
    $numInventario1 = $_POST['numInventario1'];
    $marcaEquipo1 = $_POST['marcaEquipo1'];
    $modeloEquipo1 = $_POST['modeloEquipo1'];
    $numSerie1 = $_POST['numSerie1'];
    $idCheck = $_POST['cbox1'];
    $inputIDUnico = $_POST['inputIDUnico'];
    $estadoEquipo = 'Operativo';
    $idDepa = $_POST['idDepa'];
    $horarioInicio = $_POST['horarioInicio'];
    $horarioTermino = $_POST['horarioTermino'];

    if($inputpiso == '' || empty($inputpiso)){
        $inputpiso = 0;
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

    if ($nomSolicitante == '' || $nomSolicitante == ' ' || empty($nomSolicitante) || $servicioOrden == '' || $servicioOrden == ' ' || empty($servicioOrden) || $recintoOrden == '' || $recintoOrden == ' ' || empty($recintoOrden)) {
        if($tipoUsuario !== 'personal'){
            header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?errorGenerar1=true');
        }else{
            header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?errorGenerar1=true');
        }

    }else{
        if ($departamentoSolicitante == '' || $departamentoSolicitante == ' ' || empty($departamentoSolicitante)  || $trabajoRealizado == '' || $trabajoRealizado == ' ' || empty($trabajoRealizado)) {
            if($tipoUsuario !== 'personal'){
                header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?errorGenerar2=true');
            }else{
                header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?errorGenerar2=true');
            }
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
            if($idCheck == '' || empty($idCheck)){
                $queryOrden = "INSERT INTO ordenes_trabajos_general(idOrden,numOrden,fechaOrden,nomSolicitante,nomTecnicoRecepcionador,idDepartamento,servicioOrden,recintoOrden,pmaOrden,coordenadasOrden,tipoMantencion,trabajoRealizado,problemaPresenta,accionesRealiza, comentariosTrabajos, ticketAsociado, idCreador, tipoUsuario, noOrdenTrabajo, idEquipo, idCategoria, tipoOT,isVisado,nombreVisado,fechaVisado,horaInicio,horaTermino)
                VALUES (NULL,".$numOrden.",'".$now."','" . $nomSolicitante . "','" . $listadoUsuarios . "','" . $departamentoSolicitante . "','" . $servicioOrden . "','" . $recintoOrden . "','" . $pmaOrden . "','" . $coordenadasOrden . "','" . $tipoMantencion . "','" . $trabajoRealizado . "','" . $problemaPresenta . "','" . $accionesRealiza . "','" . $comentariosTrabajos . "','" . $ticketAsociado . "', $idUsuario, '$tipoUsuario','" . $noOrdenTrabajo . "', $idHoja, $idCategoria,'OT-Firma',0,'','0000-00-00 00:00:00','".$horarioInicio."','".$horarioTermino."')";
                $resOrden = mysqli_query($conexion, $queryOrden);

                if ($resOrden) {
                    $idOrden = mysqli_insert_id($conexion);

                    if (!empty($_POST['nomEquipo']) || !empty($_POST['marcaEquipo']) || !empty($_POST['numInventario'])) {
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



                        $sqlActualizarOT = "UPDATE trabajos_general SET estadoTG = 'Trabajo Terminado', fechaTermino = '$now' WHERE folioTG = '$noOrdenTrabajo'";
                        $resAcualizarOT = mysqli_query($conexion,$sqlActualizarOT);

                        $usoMaterial = '';

                        $usoFoto = '';



                        if(isset($_POST['cantidadFilas'])) {
                            $cantidadFilas = $_POST['cantidadFilas'];

                            if ($cantidadFilas > 0) {

                                for ($j = 1; $j <= $cantidadFilas; $j++) {

                                    $articuloSC = $_POST['articuloSC' . $j];
                                    $cantidadSC = $_POST['cantidadSC' . $j];
                                    $medidaSC = $_POST['medidaSC' . $j];
                                    $idInventarioAgregar = $_POST['idInventarioAgregar' . $j];

                                    if($idInventarioAgregar > 0){
                                        $obtenerCantMaxInvent = obtenerCantMaxInvent($idInventarioAgregar);

                                        $restaInv = $obtenerCantMaxInvent - $cantidadSC;

                                        $sqlInventario = "UPDATE inventario_equipos SET cantidadInventario = ".$restaInv." WHERE idInventario = ".$idInventarioAgregar;

                                        $resInventario = mysqli_query($conexion, $sqlInventario);

                                        if($resInventario){
                                            $_SESSION['usoMaterial'] = 'Usando';
                                        }else{
                                            $_SESSION['usoMaterial'] = 'Vacio';
                                        }
                                    }

                                    $queryMaterial = "INSERT INTO material_orden_general(idMaterial, nomMaterial, unidadMaterial, cantidadMaterial, idOrden, idInventario) 
                                    VALUES (NULL, '" . $articuloSC . "', '" . $medidaSC . "', " . $cantidadSC . ", " . $idOrden . ", " . $idInventarioAgregar . ")";



                                    echo '<br><br>'.$articuloSC.'  ----  '.$medidaSC.'  ----  '.$cantidadSC.'  ----  ';

                                    echo '<br><br>'.$queryMaterial;

                                    $resMaterial = mysqli_query($conexion, $queryMaterial);

                                    if ($resMaterial) {
                                        $_SESSION['usoMaterial'] = 'Usando';
                                    } else {
                                        $_SESSION['usoMaterial'] = 'Vacio';
                                    }


                                }

                            }else {
                                $_SESSION['usoMaterial'] = 'Vacio';
                            }

                        }else{
                            $_SESSION['usoMaterial'] = 'Vacio';
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
                        }
                        if($tipoUsuario !== 'personal'){
                            header('Location: ../'.$rutaDireccion . '/trabajos_general_listado_terminados.php?exitoGenerar=true');
                        }else{
                            header('Location: ../'.$rutaDireccion . '/trabajos_general_listado_finalizados.php?exitoGenerar=true');
                        }
                    }else{
                        if($tipoUsuario !== 'personal'){
                            header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?errorFolio=true');
                        }else{
                            header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?errorFolio=true');
                        }
                    }

                }else{
                    if($tipoUsuario !== 'personal'){
                        header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?errorGenerar3=true');
                    }else{
                        header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?errorGenerar3=true');
                    }
                }

            }else{
                if (!empty($_POST['nomEquipo1']) || !empty($_POST['marcaEquipo1']) || !empty($_POST['numInventario1']) || !empty($_POST['categoriaEquipo'])){
                    if($rolUsuario2 == 'administrador' || $rolUsuario == 'oficina tecnica'){
                        //$selectDepa
                        $sql = "INSERT INTO equipamiento_hbqp(id,identificadorUnico,numeroInventario,nombreEquipo,marca,modelo,idServicio,numeroSerie,idCategoria,piso,idDepaPertenece,estadoEquipo)
                            VALUES (NULL,'".$inputIDUnico."','".$numInventario1."','".$nomEquipo1."','".$marcaEquipo1."','".$modeloEquipo1."',$selectservicio,'".$numSerie1."',$categoriaEquipo,$inputpiso,$selectDepa,'".$estadoEquipo."')";
                    }else{
                        $sql = "INSERT INTO equipamiento_hbqp(id,identificadorUnico,numeroInventario,nombreEquipo,marca,modelo,idServicio,numeroSerie,idCategoria,piso,idDepaPertenece,estadoEquipo)
                            VALUES (NULL,'".$inputIDUnico."','".$numInventario1."','".$nomEquipo1."','".$marcaEquipo1."','".$modeloEquipo1."',$selectservicio,'".$numSerie1."',$categoriaEquipo,$inputpiso,$idDepaPertenece,'".$estadoEquipo."')";
                    }

                    $res = mysqli_query($conexion,$sql);
                    if($res){
                        $idEquipo1 = mysqli_insert_id($conexion);
                        $queryOrden = "INSERT INTO ordenes_trabajos_general(idOrden,fechaOrden,nomSolicitante,nomTecnicoRecepcionador,idDepartamento,servicioOrden,recintoOrden,pmaOrden,coordenadasOrden,tipoMantencion,trabajoRealizado,problemaPresenta,accionesRealiza, comentariosTrabajos, ticketAsociado, idCreador, tipoUsuario, noOrdenTrabajo, idEquipo, idCategoria)
                            VALUES (NULL,'".$now."','" . $nomSolicitante . "','" . $listadoUsuarios . "','" . $departamentoSolicitante . "','" . $servicioOrden . "','" . $recintoOrden . "','" . $pmaOrden . "','" . $coordenadasOrden . "','" . $tipoMantencion . "','" . $trabajoRealizado . "','" . $problemaPresenta . "','" . $accionesRealiza . "','" . $comentariosTrabajos . "','" . $ticketAsociado . "', $idUsuario, '$tipoUsuario','" . $noOrdenTrabajo . "', $idEquipo1, $categoriaEquipo)";
                        $resOrden = mysqli_query($conexion, $queryOrden);

                        if($resOrden){
                            $idOrden1 = mysqli_insert_id($conexion);

                            $queryEquipoOrden = "INSERT INTO equipos_recepcion_trabajo (equipo,numInventario,marca,modelo,numSerie,idOrden,idEquipo,idCategoria) VALUES ('$nomEquipo1', '$numInventario1', '$marcaEquipo1', '$modeloEquipo1', '$numSerie1', $idOrden1,$idEquipo1,$categoriaEquipo)";
                            $resEquipoOrden = mysqli_query($conexion, $queryEquipoOrden);

                            $sqlInsertContadorHojas = "INSERT INTO numero_hojas_creadas (idOrden) VALUES ($idOrden1) ";
                            $resInsertContadorHojas = mysqli_query($conexion, $sqlInsertContadorHojas);

                            $_SESSION['idOrden1'] = $idOrden1;

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

                            $queryFolio = "UPDATE ordenes_trabajos_general SET folioOrden = '" . $folioOrden . "', nomOrden = '" . $nomOrden . "', idSubDepartamento = '" . $idSubDepartamento . "' WHERE idOrden =" . $idOrden1;

                            $resFolio = mysqli_query($conexion, $queryFolio);

                            if ($resFolio) {
                                $usoMaterial = '';

                                $usoFoto = '';

                                if(isset($_POST['cantidadFilas'])) {
                                    $cantidadFilas = $_POST['cantidadFilas'];

                                    if ($cantidadFilas > 0) {

                                        for ($j = 1; $j <= $cantidadFilas; $j++) {

                                            $articuloSC = $_POST['articuloSC' . $j];
                                            $cantidadSC = $_POST['cantidadSC' . $j];
                                            $medidaSC = $_POST['medidaSC' . $j];
                                            $idInventarioAgregar = $_POST['idInventarioAgregar' . $j];

                                            if($idInventarioAgregar > 0){
                                                $obtenerCantMaxInvent = obtenerCantMaxInvent($idInventarioAgregar);

                                                $restaInv = $obtenerCantMaxInvent - $cantidadSC;

                                                $sqlInventario = "UPDATE inventario_equipos SET cantidadInventario = ".$restaInv." WHERE idInventario = ".$idInventarioAgregar;

                                                $resInventario = mysqli_query($conexion, $sqlInventario);

                                                if($resInventario){
                                                    $_SESSION['usoMaterial'] = 'Usando';
                                                }else{
                                                    $_SESSION['usoMaterial'] = 'Vacio';
                                                }
                                            }

                                            $queryMaterial = "INSERT INTO material_orden_general(idMaterial, nomMaterial, unidadMaterial, cantidadMaterial, idOrden, idInventario) 
                                    VALUES (NULL, '" . $articuloSC . "', '" . $medidaSC . "', " . $cantidadSC . ", " . $idOrden1 . ", " . $idInventarioAgregar . ")";


                                            echo '<br><br>'.$articuloSC.'  ----  '.$medidaSC.'  ----  '.$cantidadSC.'  ----  ';

                                            echo '<br><br>'.$queryMaterial;

                                            $resMaterial = mysqli_query($conexion, $queryMaterial);

                                            if ($resMaterial) {
                                                $_SESSION['usoMaterial'] = 'Usando';
                                            } else {
                                                $_SESSION['usoMaterial'] = 'Vacio';
                                            }


                                        }

                                    }else {
                                        $_SESSION['usoMaterial'] = 'Vacio';
                                    }

                                }else{
                                    $_SESSION['usoMaterial'] = 'Vacio';
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
                                                        $queryArchivos = "INSERT INTO archivos_orden_general(idArchivo,nomArchivo,rutaArchivo, idOrden) VALUES (NULL,'" . $name . "','" . $rutaArchivo . "','" . $idOrden1 . "')";
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

                                                    $queryImagen = "INSERT INTO imagenes_orden_general(idImagen,nomImagen,extImagen,rutaImagen,idReporte,idOrden) VALUES (NULL,'" . $name . "','" . $extImagen . "','" . $ruta . "',0,'" . $idOrden1 . "')";

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

                                if($tipoUsuario !== 'personal'){
                                    header('Location: ../'.$rutaDireccion . '/trabajos_general_listado_terminados.php?exitoGenerar=true');
                                }else{
                                    header('Location: ../'.$rutaDireccion . '/trabajos_general_listado_finalizados.php?exitoGenerar=true');
                                }

                            }else{
                                if($tipoUsuario !== 'personal'){
                                    header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?errorFolio=true');
                                }else{
                                    header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?errorFolio=true');
                                }
                            }
                        }else{
                            if($tipoUsuario !== 'personal'){
                                header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?errorGenerar4=true');
                            }else{
                                header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?errorGenerar3=true');
                            }
                        }
                    }else{
                        if($tipoUsuario !== 'personal'){
                            header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?errorGenerar5=true');
                        }else{
                            header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?errorGenerar5=true');
                        }
                    }
                }else{
                    if($tipoUsuario !== 'personal'){
                        header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?errorGenerar6=true');
                    }else{
                        header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?errorGenerar6=true');
                    }
                }
            }
        }
    }
}else{
    if($tipoUsuario !== 'personal'){
        header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?errorGenerar7=true');
    }else{
        header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?errorGenerar7=true');
    }
}