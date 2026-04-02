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


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['nombreProveedor'], $_POST['rutProveedor'])) {

        $nombreProveedor = $_POST['nombreProveedor'];
        $rutProveedor = $_POST['rutProveedor'];

        if (empty($rutProveedor) || empty($nombreProveedor)) {
            header('Location: ../' . $rutaDireccion . '/agregar-equipo-inventario-general.php?errorAgregarEquipo=true');
        } else {
            $buscarRUTProveedor = buscarRUTProveedor($rutProveedor);

            if ($buscarRUTProveedor > 0) {

                $nombreProveedor = obtenerNombreProveedor($rutProveedor);


            } else {

                $sqlProveedor = "INSERT INTO proveedor_equipos (idProveedor,nomProveedor,rutProveedor) VALUES (NULL,'" . $nombreProveedor . "','" . $rutProveedor . "')";

                $resProveedor = mysqli_query($conexion, $sqlProveedor);

                if ($resProveedor) {
                    $mensaje = 'proveedor agregado';
                } else {
                    header('Location: ../' . $rutaDireccion . '/agregar-equipo-inventario-general.php?errorAgregarEquipo=true');
                }

            }
        }

        if (isset($_POST['fechaEquipo'], $_POST['selectInventario'],$_POST['tipoDocumento'])) {
            $fechaEquipo = $_POST['fechaEquipo'];
            $selectInventario = $_POST['selectInventario'];
            $tipoDocumento = $_POST['tipoDocumento'];



            $cantidadFilas = $_POST['cantidadFilas'];
            $observacion = $_POST['obs'];
            $articuloSC1 = trim($_POST['articuloSC1']);

            $idCategoria = $_POST['idCategoria'];


            if (!empty($fechaEquipo) && $selectInventario > 0 && !empty($tipoDocumento) && $cantidadFilas > 0 && !empty($articuloSC1)) {

                if($tipoDocumento == 'Solicitud de Compra - SDO' || $tipoDocumento == 'Solicitud de Compra - PM' || $tipoDocumento == 'Orden de Compra'){

                    if($tipoDocumento == 'Solicitud de Compra - SDO'){
                        $nombreDocumento = $_POST['folio-documento-sdo'];
                    }else if($tipoDocumento == 'Solicitud de Compra - PM'){
                        $nombreDocumento = $_POST['folio-documento-pm'];
                    }else if($tipoDocumento == 'Orden de Compra'){
                        $nombreDocumento = $_POST['folio-documento-oc'];
                    }else{
                        header('Location: ../' . $rutaDireccion . '/agregar-equipo-inventario-general.php?errorAgregarEquipo=true');
                    }

                    $cargoDocumento = 'SIN DOC';

                }else if($tipoDocumento == 'Cotizacion Empresa' || $tipoDocumento == 'Cotizacion Internet'){



                    $idCupoArchivo = generarCupoArchivoCotizacionInvent();

                    if($tipoDocumento == 'Cotizacion Empresa' && $idCupoArchivo > 0){
                        // Procesar el archivo de internet
                        $tmp_name = $_FILES["cotiza-documento-e"]["tmp_name"];
                        $name = $_FILES["cotiza-documento-e"]["name"];
                        $rutaTipoCotizacion = 'cotizacion-empresa';
                        $nombreDocumento = 'CE-';
                    }else if($tipoDocumento == 'Cotizacion Internet' && $idCupoArchivo > 0){
                        // Procesar el archivo de internet
                        $tmp_name = $_FILES["cotiza-documento-i"]["tmp_name"];
                        $name = $_FILES["cotiza-documento-i"]["name"];
                        $rutaTipoCotizacion = 'cotizacion-internet';

                        $nombreDocumento = 'CI-';
                    }else{
                        header('Location: ../' . $rutaDireccion . '/agregar-equipo-inventario-general.php?errorAgregarEquipo=true');
                    }

                    $directorio = "../sdo-cotizacion-inventario/" . $rutaTipoCotizacion . "/id-" . $idCupoArchivo . "/";
                    if (!file_exists($directorio)) {
                        mkdir($directorio, 0777);
                    }

                    $dir = opendir($directorio);

                    $rutaArchivo = $directorio . $name;

                    $separarNombre = explode(".", $name);

                    $extImagen = end($separarNombre);

                    $extImagen = mb_strtolower($extImagen);

                    if ($extImagen == "docx" || $extImagen == "doc" || $extImagen == "xlsx" || $extImagen == "xls" || $extImagen == "ppt" || $extImagen == "pptx" || $extImagen == "pdf" || $extImagen == "jpg" || $extImagen == "png" || $extImagen == "jpeg" || $extImagen == "bmp" || $extImagen == "tif" || $extImagen == "webp" || $extImagen == "svg" || $extImagen == "jfif") {
                        if (!file_exists($name)) {
                            if (move_uploaded_file($tmp_name, $rutaArchivo)) {
                                chown($rutaArchivo, "adminSDO");
                                chmod($rutaArchivo, 0777);

                                $queryUpdate = "UPDATE archivos_cotizacion_inventario SET nomArchivo='".$name."',rutaArchivo='".$rutaArchivo."',extArchivo='".$extImagen."',tipoCotizacion='".$rutaTipoCotizacion."',idInventario=0 WHERE idArchivo = ".$idCupoArchivo;
                                $resquery = mysqli_query($conexion, $queryUpdate);
                                if ($resquery) {
                                    $cargoDocumento = 'SI';

                                    $nombreDocumento = $nombreDocumento.$idCupoArchivo;
                                } else {
                                    header('Location: ../' . $rutaDireccion . '/agregar-equipo-inventario-general.php?errorAgregarEquipo=true');
                                }
                            } else {
                                header('Location: ../' . $rutaDireccion . '/agregar-equipo-inventario-general.php?errorAgregarEquipo=true');
                            }
                        } else {
                            header('Location: ../' . $rutaDireccion . '/agregar-equipo-inventario-general.php?errorAgregarEquipo=true');
                        }
                    } else {
                        header('Location: ../' . $rutaDireccion . '/agregar-equipo-inventario-general.php?errorAgregarEquipo=true');
                    }

                }else {
                    header('Location: ../' . $rutaDireccion . '/agregar-equipo-inventario-general.php?errorAgregarEquipo=true');
                }


                if($cargoDocumento == 'SI' || $cargoDocumento == 'SIN DOC'){

                    if($cantidadFilas > 0){

                        $sumaTotal = 0;

                        for ($i = 1; $i <= $cantidadFilas; $i++) {

                            $articuloSC = $_POST['articuloSC' . $i];
                            $marcaSC = $_POST['marcaSC' . $i];
                            $modeloSC = $_POST['modeloSC'.$i];
                            $serieSC = $_POST['serieSC'.$i];
                            $serieSC = mb_convert_case($serieSC,MB_CASE_UPPER,'UTF-8');
                            $cantidadSC = $_POST['cantidadSC' . $i];
                            $medidaSC = $_POST['medidaSC' . $i];
                            $valorSC = $_POST['valorSC' . $i];

                            if ($idCategoria == 0 || empty($idCategoria)) {
                                $datoGuardar = $selectInventario;
                            }else{
                                $datoGuardar = $idCategoria;
                            }

                            if (!empty($articuloSC) && !empty($cantidadSC) && $cantidadSC > 0) {
                                $queryArticuloSC = "INSERT INTO inventario_equipos(idInventario, equipoInventario, marcaInventario, modeloInventario, serieInventario, cantidadInventario, categoriaInventario, fechaInventario, fechaActualizacion, nomProveedor, rutProveedor, tipoDocumento, solicompra, observacion, valorUnitario, unidad)";
                                $queryArticuloSC .= "VALUES (NULL,'".$articuloSC."','".$marcaSC."','".$modeloSC."','".$serieSC."','".$cantidadSC."','".$datoGuardar."','".$fechaEquipo."','".$fechaEquipo."','".$nombreProveedor."','".$rutProveedor."','".$tipoDocumento."','".$nombreDocumento."','".$observacion."','".$valorSC."','".$medidaSC."')";

                                $resArticuloSC = mysqli_query($conexion, $queryArticuloSC);

                                if ($resArticuloSC) {
                                    $sumaTotal = $sumaTotal + 1;
                                } else {
                                    $sumaTotal = $sumaTotal + 0;
                                }
                            }else{
                                $sumaTotal = $sumaTotal + 0;
                            }

                        }

                        if($sumaTotal > 0){
                            header('Location: ../' . $rutaDireccion . '/inventario-hbqp.php?exitoAgregarEquipo=true');
                        }else{
                            header('Location: ../' . $rutaDireccion . '/agregar-equipo-inventario-general.php?errorAgregarEquipo=true');
                        }

                    }else{
                        header('Location: ../' . $rutaDireccion . '/agregar-equipo-inventario-general.php?errorAgregarEquipo=true');
                    }


                }else{
                    header('Location: ../' . $rutaDireccion . '/agregar-equipo-inventario-general.php?errorAgregarEquipo=true');
                }



            }else {
                header('Location: ../' . $rutaDireccion . '/agregar-equipo-inventario-general.php?errorAgregarEquipo=true');
            }


        } else {
            header('Location: ../' . $rutaDireccion . '/agregar-equipo-inventario-general.php?errorAgregarEquipo=true');
        }

    } else {
        header('Location: ../' . $rutaDireccion . '/agregar-equipo-inventario-general.php?errorAgregarEquipo=true');
    }

} else {
    header('Location: ../' . $rutaDireccion . '/agregar-equipo-inventario-general.php?errorAgregarEquipo=true');
}

?>

