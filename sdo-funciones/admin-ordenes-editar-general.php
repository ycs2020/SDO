<?php

include 'conexion.php';
include 'funciones.php';

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

if (isset($_POST['idOrden'])) {

    $idOrden = $_POST['idOrden'];
    $ticketAsociado = $_POST['ticketAsociado'];
    $noOrdenTrabajo = $_POST['noOrdenTrabajo'];
    $nomSolicitante = trim($_POST['nomSolicitante']);
    $idHoja = $_POST['idHoja'];
    $idCategoria = $_POST['idCategoria'];
    $idCategoria1 = $_POST['idCategoria1'];
    $idEquipo = $_POST['idEquipo'];
    $idDepa = $_POST['idDepa'];
    

    if ($idEquipo == '' || $idEquipo == 0){
        $idEquipo = 0;
    }

    if($idHoja == '' || $idHoja == 0 || empty($idHoja)){
        $idHoja = 0;
    }

    if($idCategoria == ''){
        $idCategoria = 0;
    }

    if($idCategoria1 == '' || $idCategoria1 == 0 || empty($idCategoria1)){
        $idCategoria1 = 0;
    }

    $nomTecnicoRecepcionador = trim($_POST['nomTecnicoRecepcionador']);

    $servicioOrden = trim($_POST['servicioOrden']);
    $servicioOrden = mb_strtoupper($servicioOrden, 'UTF-8');

    $recintoOrden = trim($_POST['recintoOrden']);
    $pmaOrden = trim($_POST['pmaOrden']);
    $coordenadasOrden = trim($_POST['coordenadasOrden']);
    $comentariosTrabajos = trim($_POST['comentariosTrabajos']);
    $fechaActualOrden = $_POST['fechaActualOrden'];
    $departamentoSolicitante = trim($_POST['departamentoSolicitante']);
    $nomDepartamentoSolicitante = trim(getNomDepartamento($departamentoSolicitante));
    $servicioOrden = $_POST['servicioOrden'];
    $tipoMantencion = $_POST['tipoMantencion'];
    $problemaPresenta = $_POST['problemaPresenta'];
    $trabajoRealizado = $_POST['trabajoRealizado'];
    $accionesRealiza = $_POST['accionesRealiza'];
    $nomEquipo1 = $_POST['nomEquipo1'];
    $numInventario1 = $_POST['numInventario1'];
    $marcaEquipo1 = $_POST['marcaEquipo1'];
    $modeloEquipo1 = $_POST['modeloEquipo1'];
    $numSerie1 = $_POST['numSerie1'];
    $selectservicio = $_POST['selectservicio'];
    $categoriaEquipo = $_POST['categoriaEquipo'];
    $inputpiso = $_POST['inputpiso'];
    $idCheck = $_POST['cbox1'];
    $inputIDUnico = $_POST['inputIDUnico'];
    $idDepaPertenece = $_POST['depa'];
    $estadoEquipo = 'Operativo';
    $horarioInicio = $_POST['horarioInicio'];
    $horarioTermino = $_POST['horarioTermino'];
    $listadoUsuarios = $_POST['listadoUsuarios'];
    $tipoAcuerdo = $_POST['tipoAcuerdo'];
    $estadoEquipo = $_POST['estadoEquipo'];
    $oc = $_POST['oc'];
    $folioOrden = $_POST['folioOrden'];

    if (is_array($listadoUsuarios)) {
        sort($listadoUsuarios);
        // Unir los elementos del array con ' - ' como delimitador
        $listadoUsuarios = implode(' - ', $listadoUsuarios);
    } else {
        $listadoUsuarios = '';
    }

    if($horarioInicio == '' || empty($horarioInicio)){
        $horarioInicio = '0000-00-00 00:00:00';
    }

    if($horarioTermino == '' || empty($horarioTermino)){
        $horarioTermino = '0000-00-00 00:00:00';
    }

    if(!isset($selectservicio)){
        $selectservicio = '';
    }

    if (!empty($problemaPresenta)) {

        $problemaPresenta = str_replace("'", "\'", $problemaPresenta);
    }

    if (!empty($detalleTrabajo)) {

        $detalleTrabajo = str_replace("'", "\'", $detalleTrabajo);
    }

    if (!empty($comentariosTrabajos)) {

        $accionesRealiza = str_replace("'", "\'", $accionesRealiza);
    }

    if (!empty($comentariosTrabajos)) {

        $comentariosTrabajos = str_replace("'", "\'", $comentariosTrabajos);
    }


    $detalleTrabajo = mysqli_real_escape_string($conexion,$detalleTrabajo);
    $accionesRealiza = mysqli_real_escape_string($conexion,$accionesRealiza);
    $comentariosTrabajos = mysqli_real_escape_string($conexion,$comentariosTrabajos);
    $problemaPresenta = mysqli_real_escape_string($conexion,$problemaPresenta);
    $trabajoRealizado = mysqli_real_escape_string($conexion,$trabajoRealizado);


    // SQL UPDATE A LA TABLA DE HOJAS TÉCNICAS
    if($idHoja == 0 || $idHoja == '' || empty($idHoja)){
        $ActualizaHojas = "UPDATE ordenes_trabajos_general SET oc = '$oc',estadoEquipo = '$estadoEquipo',tipoAcuerdo = '$tipoAcuerdo',horaInicio = '$horarioInicio',horaTermino = '$horarioTermino',ticketAsociado = '".$ticketAsociado."', idDepartamento = ".$departamentoSolicitante.",  nomTecnicoRecepcionador = '".$listadoUsuarios."', nomSolicitante ='".$nomSolicitante."', servicioOrden = ".$servicioOrden.", recintoOrden = '".$recintoOrden."',pmaOrden = '".$pmaOrden."' ,coordenadasOrden = '".$coordenadasOrden."', tipoMantencion =  '".$tipoMantencion."', problemaPresenta = '".$problemaPresenta."', accionesRealiza = '".$accionesRealiza."', trabajoRealizado = '".$trabajoRealizado."', comentariosTrabajos = '".$comentariosTrabajos."', noOrdenTrabajo = '".$noOrdenTrabajo."', idEquipo = ".$idEquipo.", idCategoria = ".$idCategoria1."  WHERE idOrden = ".$idOrden;

//        echo $ActualizaHojas;
//        exit;
        $resActualizarHojas = mysqli_query($conexion, $ActualizaHojas);

    } else{
        $ActualizaHojas = "UPDATE ordenes_trabajos_general SET oc = '$oc',estadoEquipo = '$estadoEquipo',tipoAcuerdo = '$tipoAcuerdo',horaInicio = '$horarioInicio',horaTermino = '$horarioTermino',ticketAsociado = '".$ticketAsociado."', idDepartamento = ".$departamentoSolicitante.",  nomTecnicoRecepcionador = '".$listadoUsuarios."', nomSolicitante ='".$nomSolicitante."', servicioOrden = ".$servicioOrden.", recintoOrden = '".$recintoOrden."',pmaOrden = '".$pmaOrden."' ,coordenadasOrden = '".$coordenadasOrden."', tipoMantencion =  '".$tipoMantencion."', problemaPresenta = '".$problemaPresenta."', accionesRealiza = '".$accionesRealiza."', trabajoRealizado = '".$trabajoRealizado."', comentariosTrabajos = '".$comentariosTrabajos."', noOrdenTrabajo = '".$noOrdenTrabajo."', idEquipo = ".$idHoja.", idCategoria = ".$idCategoria."  WHERE idOrden = ".$idOrden;


//        echo $ActualizaHojas;
//        exit;
        $resActualizarHojas = mysqli_query($conexion, $ActualizaHojas);
    }

   //echo $ActualizaHojas;
//
    //exit();
    if(!$idCheck == '' || !empty($idCheck)){
        if (!empty($_POST['nomEquipo1']) || !empty($_POST['marcaEquipo1']) || !empty($_POST['numInventario1']) || !empty($_POST['categoriaEquipo'])){
            $sql = "INSERT INTO equipamiento_hbqp(id,identificadorUnico,numeroInventario,nombreEquipo,marca,modelo,idServicio,numeroSerie,idCategoria,piso,idDepaPertenece,estadoEquipo)
                            VALUES (NULL,'".$inputIDUnico."','".$numInventario1."','".$nomEquipo1."','".$marcaEquipo1."','".$modeloEquipo1."',$selectservicio,'".$numSerie1."',$categoriaEquipo,$inputpiso,$idDepaPertenece,'".$estadoEquipo."')";
            $res = mysqli_query($conexion,$sql);

            if($res){
                $idEquipo1 = mysqli_insert_id($conexion);
                $sqlActualizar = "UPDATE ordenes_trabajos_general SET idEquipo = $idEquipo1, idCategoria = $categoriaEquipo WHERE idOrden = $idOrden";
                $resActualizar = mysqli_query($conexion,$sqlActualizar);
               /* INSERT INTO `equipos_recepcion_trabajo`(`idRecepcion`, `equipo`, `numInventario`, `marca`, `modelo`, `numSerie`, `idOrden`, `idEquipo`, `idCategoria`) VALUES ('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]','[value-6]','[value-7]','[value-8]','[value-9]')*/

            }else{
                header('Location: ../' . $rutaDireccion . '/ordenes-listado-general.php?errorActualizar1=true');
            }
        }else{
            header('Location: ../' . $rutaDireccion . '/ordenes-listado-general.php?errorActualizar2=true');
        }
    }
   
    /* CODIGO PARA GUARDAR LA EDICION DE LOS MATERIALES*/
    $idMaterialEditar = $_POST['idMaterialEditar'];
    $materialUsadoEditar = $_POST['materialUsadoEditar'];
    $unidadEditar = $_POST['unidadEditar'];
    $cantidadEditar = $_POST['cantidadEditar'];

    
    // foreach ($idMaterialEditar as $valor) {
    //     echo $valor . "<br>";
    // }

    for ($i = 0; $i < count($idMaterialEditar) ; $i++) {
        $actualizaMateriales = "UPDATE `material_orden_general` SET `nomMaterial`= '$materialUsadoEditar[$i]',`unidadMaterial`='$unidadEditar[$i]',`cantidadMaterial`=$cantidadEditar[$i] WHERE idMaterial = $idMaterialEditar[$i] ";
        $resActualizaMateriales = mysqli_query($conexion, $actualizaMateriales);
        echo $actualizaMateriales . "<br>";
    }

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

    //DATOS DEL EQUIPO
    $nomEquipo = $_POST['nomEquipo'];
    $numInventario = $_POST['numInventario'];
    $marcaEquipo = $_POST['marcaEquipo'];
    $modeloEquipo = $_POST['modeloEquipo'];
    $numSerie = $_POST['numSerie'];


    $sqlEquipo = "SELECT * FROM equipos_recepcion_trabajo WHERE idOrden = $idOrden";
    $resEquipo = mysqli_query($conexion, $sqlEquipo);

    if ($resEquipo->num_rows > 0) {
        if($idHoja == 0 || $idHoja == '' || empty($idHoja)) {
            $sqlUpdateEquipo = "UPDATE equipos_recepcion_trabajo SET equipo = '$nomEquipo', numInventario = '$numInventario', marca = '$marcaEquipo', modelo = '$modeloEquipo', numSerie = '$numSerie', idEquipo = '$idEquipo', idCategoria = '$idCategoria1' WHERE idOrden = '$idOrden' ";
            $resUpdateEquipo = mysqli_query($conexion, $sqlUpdateEquipo);
        }else if($idEquipo1 == 0 || $idEquipo1 == '' || empty($idEquipo1)){
            $sqlUpdateEquipo = "UPDATE equipos_recepcion_trabajo SET equipo = '$nomEquipo', numInventario = '$numInventario', marca = '$marcaEquipo', modelo = '$modeloEquipo', numSerie = '$numSerie', idEquipo = '$idHoja', idCategoria = '$idCategoria' WHERE idOrden = '$idOrden' ";
            $resUpdateEquipo = mysqli_query($conexion, $sqlUpdateEquipo);
        }

    } else {

        if ($nomEquipo !== '' || $numInventario !== '' || $marcaEquipo !== '' || $modeloEquipo !== '' || $numSerie !== '') {
            if($idHoja == 0 || $idHoja == '' || empty($idHoja)) {
                $sqlInsertEquipo = "INSERT INTO equipos_recepcion_trabajo (equipo,numInventario,marca,modelo,numSerie,idOrden,idEquipo,idCategoria) VALUES ('$nomEquipo','$numInventario','$marcaEquipo','$modeloEquipo','$numSerie',$idOrden,$idEquipo,$idCategoria1)";
                $resInsertEquipo = mysqli_query($conexion, $sqlInsertEquipo);
            } else if($idEquipo == 0 || $idEquipo == '' || empty($idEquipo)){
                $sqlInsertEquipo = "INSERT INTO equipos_recepcion_trabajo (equipo,numInventario,marca,modelo,numSerie,idOrden,idEquipo,idCategoria) VALUES ('$nomEquipo','$numInventario','$marcaEquipo','$modeloEquipo','$numSerie',$idOrden,$idHoja,$idCategoria)";
                $resInsertEquipo = mysqli_query($conexion, $sqlInsertEquipo);
            }
        } else{
            if (!empty($_POST['nomEquipo1']) || !empty($_POST['marcaEquipo1']) || !empty($_POST['numInventario1']) || !empty($_POST['categoriaEquipo'])){
                $queryEquipoOrden1 = "INSERT INTO `equipos_recepcion_trabajo`(`idRecepcion`, `equipo`, `numInventario`, `marca`, `modelo`, `numSerie`, `idOrden`, `idEquipo`, `idCategoria`) VALUES (NULL,'".$nomEquipo1."','$numInventario1','".$marcaEquipo1."','".$modeloEquipo1."','".$numSerie1."',$idOrden,$idEquipo1,$categoriaEquipo)";
                $resEquipoOrden1 = mysqli_query($conexion, $queryEquipoOrden1);
            }

        }
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
                    echo "FORMATO incorrecto";

                }
            }
        }
    }else{
        echo 'no entro imagen';
    }

    if ($resActualizarHojas) {
        if ($rolUsuario2 == "administrador") {
            header('Location: ../' . $rutaDireccion . '/ordenes-menu-admin.php?ExitoActualizar=true');
        }else if ($rolUsuario2 == "administrativo") {
            header('Location: ../' . $rutaDireccion . '/ordenes-menu-admin.php?ExitoActualizar=true');
        } else {
            if($idDepa == 10){
                header('Location: ../' . $rutaDireccion . '/ordenes-listado-general1.php?ExitoActualizar=true');
            }else if($idDepa == 6){
                header('Location: ../' . $rutaDireccion . '/ordenes-listado-general.php?ExitoActualizar=true');
            }else{
                header('Location: ../' . $rutaDireccion . '/ordenes-listado-general.php?ExitoActualizar=true');
            }
        }
    } else {
        if ($rolUsuario2 == "administrador") {
            header('Location: ../' . $rutaDireccion . '/ordenes-menu-admin.php?errorActualizar3=true');
        }else if ($rolUsuario2 == "administrativo") {
            header('Location: ../' . $rutaDireccion . '/ordenes-menu-admin.php?errorActualizar4=true');
        } else {
            if($idDepa == 10){
                header('Location: ../' . $rutaDireccion . '/ordenes-listado-general1.php?errorActualizar5=true');
            }else if($idDepa == 6){
                header('Location: ../' . $rutaDireccion . '/ordenes-listado-general.php?errorActualizar5=true');
            }else{
                header('Location: ../' . $rutaDireccion . '/ordenes-listado-general.php?errorActualizar5=true');
            }
        }

    }
} else {
    echo "NO IDORDEN";
    if ($rolUsuario2 == "administrador") {
        header('Location: ../' . $rutaDireccion . '/ordenes-menu-admin.php?errorActualizar7=true');
    }else if ($rolUsuario2 == "administrativo") {
        header('Location: ../' . $rutaDireccion . '/ordenes-menu-admin.php?errorActualizar8=true');
    } else {
        if($idDepa == 10){
            header('Location: ../' . $rutaDireccion . '/ordenes-listado-general1.php?errorActualizar9=true');
        }else if($idDepa == 6){
            header('Location: ../' . $rutaDireccion . '/ordenes-listado-general.php?errorActualizar9=true');
        }else{
            header('Location: ../' . $rutaDireccion . '/ordenes-listado-general.php?errorActualizar9=true');
        }
    }
}
