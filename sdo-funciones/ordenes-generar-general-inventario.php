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
    $horaInicio = $_POST['horarioInicio'];
    $horarioTermino = $_POST['horarioTermino'];
    $listadoUsuarios = $_POST['listadoUsuarios'];
    $nomTecnicoRecepcionador = $_POST['nomTecnicoRecepcionador'];

    if (is_array($listadoUsuarios)) {
        sort($listadoUsuarios);
        // Unir los elementos del array con ' - ' como delimitador
        $listadoUsuarios = implode(' - ', $listadoUsuarios);
    } else {
        $listadoUsuarios = '';
    }
    $listadoUsuarios = trim($listadoUsuarios);


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

    // //OBTIENE NOMBRE DE PERSONAL
    // $sqlPersonal = "SELECT * FROM personal WHERE idPersonal  = $idUsuario AND departamentoPersonal = $departamentoSolicitante";
    // echo $sqlPersonal;
    // $resPersonal = mysqli_query($conexion, $sqlPersonal);


    // while ($rowPersonal = $resPersonal->fetch_assoc()) {
    //     $datos = [];
    //     $datos['nomPersonal'] = $rowPersonal['nomPersonal'];

    //     $nomTecnicoRecepcionador = $datos['nomPersonal'];
    // }


    // DATOS SOLICITANTE
    echo "Nombre Solicitante = " . $nomSolicitante . "<br>";
    echo "Nombre Tecnico Recepcionador = " . $listadoUsuarios . "<br>";
    echo "Departamento Solicitante = " . $departamentoSolicitante . "<br>";
    echo "Nombre departamento Solicitante = " . $nomDepartamentoSolicitante . "<br>";
    echo "Servicio = " . $servicioOrden . "<br>";
    echo "Recinto = " . $recintoOrden . "<br>";
    echo "PMA = " . $pmaOrden . "<br>";
    echo "Coordenadas = " . $coordenadasOrden . "<br>";

    //DATOS EQUIPO
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

    if($inputpiso == '' || empty($inputpiso)){
        $inputpiso = 0;
    }

    echo "Nombre Equipo = " . $nomEquipo . "<br>";
    echo "Numero Inventario = " . $numInventario . "<br>";
    echo "Marca Equipo = " . $marcaEquipo . "<br>";
    echo "Modelo Equipo = " . $modeloEquipo . "<br>";
    echo "Numero Serie = " . $numSerie . "<br>";
    echo "IDCHECKED = ". $idCheck."<br>";

    //DETALLES SOLICITUD
    echo "Tipo de Mantención = " . $tipoMantencion . "<br>";
    echo "Garantia o Convenio = " . $radioInput . "<br>";
    echo "Orden de Compra = " . $ordenCompra . "<br>";
    echo "Otro tipo de trabajo = " . $otroTipoTrabajo . "<br>";
    echo "Trabajo realizado = " . $trabajoRealizado . "<br>";
    echo "Problema que presenta = " . $problemaPresenta . "<br>";
    echo "Acciones que realiza = " . $accionesRealiza . "<br>";
    echo "Comentarios = " . $comentariosTrabajos . "<br>";
    echo "Fecha de Orden = " . $fechaActualOrden . "<br>";

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
        header('Location: ../'.$rutaDireccion . '/ordenes-formulario-general.php?errorGenerar1=true');
    } else {

        if ($departamentoSolicitante == '' || $departamentoSolicitante == ' ' || empty($departamentoSolicitante)  || $trabajoRealizado == '' || $trabajoRealizado == ' ' || empty($trabajoRealizado)) {
            header('Location: ../'.$rutaDireccion . '/ordenes-formulario-general.php?errorGenerar2=true');
        } else {
            date_default_timezone_set('America/Santiago');
            $now = date('Y-m-d H:i:s', time());

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

                $queryOrden = "INSERT INTO ordenes_trabajos_general(idOrden,numOrden,fechaOrden,nomSolicitante,nomTecnicoRecepcionador,idDepartamento,servicioOrden,recintoOrden,pmaOrden,coordenadasOrden,tipoMantencion,trabajoRealizado,problemaPresenta,accionesRealiza, comentariosTrabajos, ticketAsociado, idCreador, tipoUsuario, noOrdenTrabajo, idEquipo, idCategoria,horaInicio,horaTermino)
                VALUES (NULL,".$numOrden.",'".$now."','" . $nomSolicitante . "','" . $listadoUsuarios . "','" . $departamentoSolicitante . "','" . $servicioOrden . "','" . $recintoOrden . "','" . $pmaOrden . "','" . $coordenadasOrden . "','" . $tipoMantencion . "','" . $trabajoRealizado . "','" . $problemaPresenta . "','" . $accionesRealiza . "','" . $comentariosTrabajos . "','" . $ticketAsociado . "', $idUsuario, '$tipoUsuario','" . $noOrdenTrabajo . "', $idHoja, $idCategoria,'".$horaInicio."','".$horarioTermino."')";
                $resOrden = mysqli_query($conexion, $queryOrden);

                if ($resOrden) {
                    $idOrden = mysqli_insert_id($conexion);

                    //INSERTAR EN TABLA EQUIPOS DE ORDEN
                    if (!empty($_POST['nomEquipo']) || !empty($_POST['marcaEquipo']) || !empty($_POST['numInventario'])) {
                        $queryEquipoOrden = "INSERT INTO equipos_recepcion_trabajo (equipo,numInventario,marca,modelo,numSerie,idOrden,idEquipo,idCategoria) VALUES ('$nomEquipo', '$numInventario', '$marcaEquipo', '$modeloEquipo', '$numSerie', $idOrden,$idEquipo,$idCategoria)";
                        $resEquipoOrden = mysqli_query($conexion, $queryEquipoOrden);
                    }

                    //INSERTA EN TABLA CONTADOR DE HOJAS
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

                    if ($resFolio) { //HASTA AQUI

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
                        if($rolUsuario2 == "administrativo" || $rolUsuario2 == "administrador"){
                            header('Location: ../'.$rutaDireccion . '/ordenes-subir-firmas.php?exitoGenerar=true');
                        }else{
                            if($idDepa == 10){
                                header('Location: ../'.$rutaDireccion . '/ordenes-listado-general1.php?exitoGenerar=true');
                            }else{
                                header('Location: ../'.$rutaDireccion . '/ordenes-listado-general.php?exitoGenerar=true');
                            }

                        }

                    } else {
                        // $queryBorrar = "DELETE FROM ordenes_trabajos WHERE idOrden = " . $idOrden;
                        header('Location: ../'.$rutaDireccion . '/ordenes-administrar.php?errorFolio=true');
                    }
                    //HASTA AQUI
                } else {
                    if($rolUsuario2 == "administrativo" || $rolUsuario2 == "administrador"){
                        header('Location: ../'.$rutaDireccion . '/ordenes-subir-firmas.php?errorGenerar3r=true');
                    }else{
                        if($idDepa == 10){
                            header('Location: ../'.$rutaDireccion . '/ordenes-formulario-general1.php?errorGenerar3=true');
                        }else{
                            header('Location: ../'.$rutaDireccion . '/ordenes-formulario-general.php?errorGenerar3=true');
                        }

                    }

                }
            } else{
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
                        $queryOrden = "INSERT INTO ordenes_trabajos_general(idOrden,numOrden,fechaOrden,nomSolicitante,nomTecnicoRecepcionador,idDepartamento,servicioOrden,recintoOrden,pmaOrden,coordenadasOrden,tipoMantencion,trabajoRealizado,problemaPresenta,accionesRealiza, comentariosTrabajos, ticketAsociado, idCreador, tipoUsuario, noOrdenTrabajo, idEquipo, idCategoria,horaInicio,horaTermino)
                            VALUES (NULL,".$numOrden.",'".$now."','" . $nomSolicitante . "','" . $listadoUsuarios . "','" . $departamentoSolicitante . "','" . $servicioOrden . "','" . $recintoOrden . "','" . $pmaOrden . "','" . $coordenadasOrden . "','" . $tipoMantencion . "','" . $trabajoRealizado . "','" . $problemaPresenta . "','" . $accionesRealiza . "','" . $comentariosTrabajos . "','" . $ticketAsociado . "', $idUsuario, '$tipoUsuario','" . $noOrdenTrabajo . "', $idEquipo1, $categoriaEquipo,'".$horaInicio."','".$horarioTermino."')";
                        $resOrden = mysqli_query($conexion, $queryOrden);

                        if($resOrden){
                            $idOrden1 = mysqli_insert_id($conexion);

                            $queryEquipoOrden = "INSERT INTO equipos_recepcion_trabajo (equipo,numInventario,marca,modelo,numSerie,idOrden,idEquipo,idCategoria) VALUES ('$nomEquipo1', '$numInventario1', '$marcaEquipo1', '$modeloEquipo1', '$numSerie1', $idOrden1,$idEquipo1,$categoriaEquipo)";
                            $resEquipoOrden = mysqli_query($conexion, $queryEquipoOrden);

                            $sqlInsertContadorHojas = "INSERT INTO numero_hojas_creadas (idOrden) VALUES ($idOrden1) ";
                            $resInsertContadorHojas = mysqli_query($conexion, $sqlInsertContadorHojas);


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

                            if ($resFolio) { //HASTA AQUI

                                $usoMaterial = '';

                                $usoFoto = '';

                                //AQUI VAN LOS MATERIALES


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

                                //FIN MATERIALES

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

                                if ($rolUsuario2 == "administrativo" || $rolUsuario2 == "administrador") {
                                    $_SESSION['idOrden'] = $idOrden1;
                                    header('Location: ../' . $rutaDireccion . '/ordenes-subir-firmas.php?exitoGenerar=true');
                                }else{
                                    if($idDepa == 10){
                                        header('Location: ../'.$rutaDireccion . '/ordenes-listado-general1.php?exitoGenerar=true');
                                    }else{
                                        header('Location: ../'.$rutaDireccion . '/ordenes-listado-general.php?exitoGenerar=true');
                                    }
                                }

                            } else {
                                // $queryBorrar = "DELETE FROM ordenes_trabajos WHERE idOrden = " . $idOrden;
                                header('Location: ../'.$rutaDireccion . '/ordenes-administrar.php?errorFolio=true');

                                if ($rolUsuario2 == "administrativo" || $rolUsuario2 == "administrador") {
                                    header('Location: ../' . $rutaDireccion . '/ordenes-subir-firmas.php?exitoGenerar=true');
                                }else{
                                    header('Location: ../'. $rutaDireccion . '/ordenes-administrar.php?exitoGenerar=true');
                                }
                            }

                        } else{
                            if ($rolUsuario2 == "administrativo" || $rolUsuario2 == "administrador") {
                                header('Location: ../' . $rutaDireccion . '/ordenes-subir-firmas.php?errorGenerar4=true');
                            }else{
                                header('Location: ../'.$rutaDireccion . '/ordenes-formulario-general.php?errorGenerar3=true');
                            }
                        }
                    } else{
                        if ($rolUsuario2 == "administrativo" || $rolUsuario2 == "administrador") {
                            header('Location: ../' . $rutaDireccion . '/ordenes-subir-firmas.php?errorGenerar5=true');
                        }else{
                            header('Location: ../'.$rutaDireccion . '/ordenes-formulario-general.php?errorGenerar5=true');
                        }

                    }
                } else{
                    if ($rolUsuario2 == "administrativo" || $rolUsuario2 == "administrador") {
                        header('Location: ../' . $rutaDireccion . '/ordenes-subir-firmas.php?errorGenerar6=true');
                    }else{
                        header('Location: ../'.$rutaDireccion . '/ordenes-formulario-general.php?errorGenerar6=true');
                    }
                }
            }
        } //Hasta aqui llegas
    }
} else {
    if ($rolUsuario2 == "administrativo" || $rolUsuario2 == "administrador") {
        header('Location: ../' . $rutaDireccion . '/ordenes-subir-firmas.php?errorGenerar7=true');
    }else{
        header('Location: ../'.$rutaDireccion . '/ordenes-formulario-general.php?errorGenerar7=true');
    }
}
