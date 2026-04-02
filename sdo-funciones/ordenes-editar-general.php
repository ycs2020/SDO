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
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if (isset($_POST['idOrden'])) {

    $idOrden = $_POST['idOrden'];
    $ticketAsociado = $_POST['ticketAsociado'];
    $noOrdenTrabajo = $_POST['noOrdenTrabajo'];
    $nomSolicitante = trim($_POST['nomSolicitante']);

    $idHoja = $_POST['idHoja'];
    $idCategoria = $_POST['idCategoria'];

    if($idHoja == ''){
        $idHoja = 0;
    }

    if($idCategoria == ''){
        $idCategoria = 0;
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

    if (!empty($detalleTrabajo)) {

        $detalleTrabajo = str_replace("'", "\'", $detalleTrabajo);
    }

    if (!empty($comentariosTrabajos)) {

        $comentariosTrabajos = str_replace("'", "\'", $comentariosTrabajos);
    }

    // SQL UPDATE A LA TABLA DE HOJAS TÉCNICAS
    $ActualizaHojas = "UPDATE ordenes_trabajos_general SET ticketAsociado = '$ticketAsociado', idDepartamento = $departamentoSolicitante,  nomTecnicoRecepcionador = '$nomTecnicoRecepcionador', nomSolicitante ='$nomSolicitante', servicioOrden = $servicioOrden, recintoOrden = '$recintoOrden',pmaOrden = '$pmaOrden' ,coordenadasOrden = '$coordenadasOrden', tipoMantencion =  '$tipoMantencion', problemaPresenta = '$problemaPresenta', accionesRealiza = '$accionesRealiza', trabajoRealizado = '$trabajoRealizado', comentariosTrabajos = '$comentariosTrabajos', noOrdenTrabajo = '$noOrdenTrabajo', idEquipo = $idHoja, idCategoria = $idCategoria  WHERE idOrden = $idOrden";
//    $resActualizarHojas = mysqli_query($conexion, $ActualizaHojas);

   
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
//        $resActualizaMateriales = mysqli_query($conexion, $actualizaMateriales);
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
                    $cantidadFor = $cantidad[$i];

                    $queryMaterial = "INSERT INTO material_orden_general(idMaterial,nomMaterial,unidadMaterial,cantidadMaterial,idOrden)
                    VALUES(NULL,'" . $materialFor . "','" . $unidadFor . "','" . $cantidadFor . "'," . $idOrden . ")";

//                    $resMaterial = mysqli_query($conexion, $queryMaterial);

                    echo '<br>'.$queryMaterial.'</br>';
//                    if ($resMaterial) {
//                        $_SESSION['usoMaterial'] = 'Usando';
//                    } else {
//                        $_SESSION['usoMaterial'] = 'Vacio';
//                    }
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
//    $resEquipo = mysqli_query($conexion, $sqlEquipo);

//    if ($resEquipo->num_rows > 0) {
//
//        $sqlUpdateEquipo = "UPDATE equipos_recepcion_trabajo SET equipo = '$nomEquipo', numInventario = '$numInventario', marca = '$marcaEquipo', modelo = '$modeloEquipo', numSerie = '$numSerie' WHERE idOrden = '$idOrden' ";
//        $resUpdateEquipo = mysqli_query($conexion, $sqlUpdateEquipo);
//    } else {
//
//        if ($nomEquipo !== '' || $numInventario !== '' || $marcaEquipo !== '' || $modeloEquipo !== '' || $numSerie !== '') {
//
//            $sqlInsertEquipo = "INSERT INTO equipos_recepcion_trabajo (equipo,numInventario,marca,modelo,numSerie,idOrden) VALUES ('$nomEquipo','$numInventario','$marcaEquipo','$modeloEquipo','$numSerie',$idOrden)";
//            $resInsertEquipo = mysqli_query($conexion, $sqlInsertEquipo);
//        }
//    }

    echo '<br>'.$sqlEquipo.'</br>';


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
//                            $resArchivos = mysqli_query($conexion, $queryArchivos);
                            echo $queryArchivos.'<br>';
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

//                        $resImagen = mysqli_query($conexion, $queryImagen);
//
//                        if ($resImagen) {
//                            echo "SI SUBI";
//
//                        }

                        echo '<br>'.$queryImagen.'<br>';
                    } else {
                      echo 'NO SUBI';
                    }
                } else {
                    echo "FORMATO CORRECTO";
                }
            }
        }
    }


    if(isset($_POST['idInvAnterior'],$_POST['equipoInvAnterior'],$_POST['marcaInvAnterior'],$_POST['modeloInvAnterior'],$_POST['categoriaInvAnterior'],$_POST['idInventarioOriginal'],$_POST['cantidadInvAnterior'],$_POST['cantidadInvAnterior'],$_POST['inventarioOrden'],$_POST['cantMaxInventario'])){

        $idInvAnterior = trim($_POST['idInvAnterior']);
        $equipoInvAnterior = trim($_POST['equipoInvAnterior']);
        $marcaInvAnterior = trim($_POST['marcaInvAnterior']);
        $modeloInvAnterior = trim($_POST['modeloInvAnterior']);
        $categoriaInvAnterior = trim($_POST['categoriaInvAnterior']);
        $idInventarioOriginal = trim($_POST['idInventarioOriginal']);
        $cantidadInvAnterior = trim($_POST['cantidadInvAnterior']);
        $inventarioOrden = trim($_POST['inventarioOrden']);
        $cantMaxInventario = trim($_POST['cantMaxInventario']);

        echo '<br>';

        echo 'ID Inv Anterior: '.$idInvAnterior .'<br>';

        echo 'Equipo: '.$equipoInvAnterior .'<br>';

        echo 'Marca: '.$marcaInvAnterior .'<br>';

        echo 'Modelo: '.$modeloInvAnterior .'<br>';

        echo 'Categoria: '.$categoriaInvAnterior .'<br>';

        echo 'ID Inv Original: '.$idInventarioOriginal .'<br>';

        echo 'Cantidad: '.$cantidadInvAnterior .'<br>';

        echo 'Inventario Nuevo: '.$inventarioOrden .'<br>';

        echo 'Cantidad Nuevo: '.$cantMaxInventario .'<br>';

        if($inventarioOrden == $idInventarioOriginal){

            if($cantMaxInventario == $cantidadInvAnterior){
                echo 'Cantidad es la misma <br>';

            }else if($cantMaxInventario > $cantidadInvAnterior){

                $obtenerCantMaxInvent = obtenerCantMaxInvent($inventarioOrden);
                $obtenerCantMaxInventMasUno = $obtenerCantMaxInvent + 1;

                $cantidadMayor = $cantMaxInventario - $cantidadInvAnterior;

                $cantidadQuedaInv = $obtenerCantMaxInvent - $cantidadMayor;

                if($cantidadMayor < $obtenerCantMaxInventMasUno){
                    echo 'sumar '.$cantidadMayor.'<br>';
                    echo 'queda '.$cantidadQuedaInv.'<br>';
                }else{
                    echo 'Error <br>';
                }


            }else if($cantMaxInventario < $cantidadInvAnterior){
                echo 'Cantidad es menor <br>';

            }else{
                echo 'Error <br>';
            }

        }else{
            echo 'No coincide <br>';
        }



    }

//    if ($resActualizarHojas) {
//        if ($rolUsuario2 == "administrador") {
//            header('Location: ../' . $rutaDireccion . '/ordenes-administrar-general.php?ExitoActualizar=true');
//        } else {
//            header('Location: ../' . $rutaDireccion . '/ordenes-listado-general.php?ExitoActualizar=true');
//        }
//    } else {
//        if ($rolUsuario2 == "administrador") {
//            header('Location: ../' . $rutaDireccion . '/ordenes-administrar-general.php?errorActualizar=true');
//        } else {
//            header('Location: ../' . $rutaDireccion . '/ordenes-listado-general.php?errorActualizar=true');
//        }
//    }
} else {
    echo "NO IDORDEN";
    exit;
}
