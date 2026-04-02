
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


        if (isset($_POST['idInventarioAgregar'], $_POST['nombreEquipo'], $_POST['marcaEquipo'], $_POST['modeloEquipo'])) {

            $idInventarioAgregar = $_POST['idInventarioAgregar'];
            $nombreEquipo = $_POST['nombreEquipo'];
            $marcaEquipo = $_POST['marcaEquipo'];
            $modeloEquipo = $_POST['modeloEquipo'];

            if (empty($nombreEquipo) || empty($marcaEquipo) || empty($modeloEquipo)) {
                header('Location: ../' . $rutaDireccion . '/agregar-equipo-inventario-general.php?errorAgregarEquipo=true');
            } else {
                if ($idInventarioAgregar > 0) {

                    $obtenerRutProveedor = obtenerRutProveedor($idInventarioAgregar);
                    $obtenerNomProveedorID = obtenerNomProveedorID($idInventarioAgregar);

                    if ($rutProveedor == $obtenerRutProveedor) {
                        header('Location: ../' . $rutaDireccion . '/agregar-equipo-inventario-general.php?errorEquipoExiste=true');
                    } else {
                        $mensaje = 'es diferente proveedor';
                    }

                } else {
                    $mensaje = 'es nuevo';
                }
                $idCategoria = $_POST['idCategoria'];
                $fechaEquipo = $_POST['fechaEquipo'];
                $cantidadEquipo = $_POST['cantidadEquipo'];
                $selectInventario = $_POST['selectInventario'];
                $precioUnitario = $_POST['precioUnitario'];
                $unidad = $_POST['unidadEquipo'];
                $tipoDocumento = $_POST['tipoDocumento'];
                $scsdo1 = $_POST['sc-sdo1'];
                $ce1 = $_FILES['cotizacionempresa1'];
                $ci1 = $_FILES['cotizacioninternet1'];
                $oc1 = $_POST['oc1'];
                $scpm1 = $_POST['sc-pm1'];
                $obs = $_POST['obs'];
                $soliCompra = '';


                if ($tipoDocumento == 'Solicitud de Compra - SDO') {
                    $soliCompra = $scsdo1;
                } else if ($tipoDocumento == 'Solicitud de Compra - PM') {
                    $soliCompra = $scpm1;
                } else if ($tipoDocumento == 'Orden de Compra') {
                    $soliCompra = $oc1;
                } else if ($tipoDocumento == 'Cotizacion Empresa') {
                    $soliCompra = 'cotizacion-empresa';
                } else if ($tipoDocumento == 'Cotizacion Internet') {
                    $soliCompra = 'cotizacion-internet';
                }

                date_default_timezone_set('America/Santiago');
                $now = date('Y-m-d H:i:s', time());

                if ($idCategoria == 0 || empty($idCategoria)) {
                    $sqlAgregarInv = "INSERT INTO inventario_equipos(idInventario, equipoInventario, marcaInventario, modeloInventario, cantidadInventario, categoriaInventario, fechaInventario, fechaActualizacion, nomProveedor, rutProveedor, tipoDocumento, solicompra, observacion, valorUnitario, unidad) 
VALUES (NULL,'" . $nombreEquipo . "','" . $marcaEquipo . "','" . $modeloEquipo . "'," . $cantidadEquipo . "," . $selectInventario . ",'" . $fechaEquipo . "','" . $fechaEquipo . "','" . $nombreProveedor . "','" . $rutProveedor . "','" . $tipoDocumento . "','" . $soliCompra . "','" . $obs . "','" . $precioUnitario . "','" . $unidad . "')";


                } else {
                    $sqlAgregarInv = "INSERT INTO inventario_equipos(idInventario, equipoInventario, marcaInventario, modeloInventario, cantidadInventario, categoriaInventario, fechaInventario, fechaActualizacion, nomProveedor, rutProveedor, tipoDocumento, solicompra, observacion, valorUnitario, unidad) 
VALUES (NULL,'" . $nombreEquipo . "','" . $marcaEquipo . "','" . $modeloEquipo . "'," . $cantidadEquipo . "," . $idCategoria . ",'" . $fechaEquipo . "','" . $fechaEquipo . "','" . $nombreProveedor . "','" . $rutProveedor . "','" . $tipoDocumento . "','" . $soliCompra . "','" . $obs . "','" . $precioUnitario . "','" . $unidad . "')";


                }


//                echo $sqlAgregarInv;

                $resAgregarInv = mysqli_query($conexion, $sqlAgregarInv);

                if ($resAgregarInv) {
                    $idInventario = mysqli_insert_id($conexion);

                    if ($ce1['name'] == '' || empty($ce1['name'])) {
                        // Procesar el archivo de internet
                        $tmp_name = $_FILES["cotizacioninternet1"]["tmp_name"];
                        $name = $_FILES["cotizacioninternet1"]["name"];
                        if ($tmp_name == '' || $tmp_name == ' ' || empty($tmp_name)) {
                            header('Location: ../' . $rutaDireccion . '/inventario-hbqp.php?exitoAgregarEquipo=true');
                            echo 'sin nombre internet';
                            exit;
                        } else {
                            $directorio = "../sdo-cotizacion-inventario/cotizacion-internet/id-" . $idInventario . "/";
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
                                        $query = "INSERT INTO `archivos_cotizacion_inventario`(`idArchivo`, `nomArchivo`, `rutaArchivo`, `extArchivo`, `tipoCotizacion`, `idInventario`) 
                                     VALUES (NULL,'$name','$rutaArchivo','$extImagen','$soliCompra',$idInventario)";
                                        $resquery = mysqli_query($conexion, $query);
                                        if ($resquery) {
                                            if ($idCategoria == 0 || empty($idCategoria)) {
                                                header('Location: ../' . $rutaDireccion . '/inventario-hbqp.php?exitoIngresoInventarioConArchivoInternet=true');
                                            } else {
                                                header('Location: ../' . $rutaDireccion . '/equipamiento_hbqp.php?exitoIngresoInventarioConArchivoInternet=true');
                                            }
                                        } else {
                                            if ($idCategoria == 0 || empty($idCategoria)) {
                                                header('Location: ../' . $rutaDireccion . '/inventario-hbqp.php?errorInventarioConArchivoInternet=true');
                                            } else {
                                                header('Location: ../' . $rutaDireccion . '/equipamiento_hbqp.php?errorInventarioConArchivoInternet=true');
                                            }
                                        }
                                    } else {
                                        echo 'error al subir archivo de internet';
                                        exit;
                                    }
                                } else {
                                    echo 'archivo ya fue subido';
                                    exit;
                                }
                            } else {
                                echo 'error con la extension de internet';
                                exit;
                            }

                        }

                    } else {
                        // Procesar el archivo de empresa
                        $tmp_nameempresa = $_FILES["cotizacionempresa1"]["tmp_name"];
                        $nameempresa = $_FILES["cotizacionempresa1"]["name"];
                        if ($tmp_nameempresa == '' || $tmp_nameempresa == ' ' || empty($tmp_nameempresa)) {
                            header('Location: ../' . $rutaDireccion . '/inventario-hbqp.php?exitoAgregarEquipo=true');
                            echo 'sin nombre empresa';
                            exit;
                        } else {
                            $directorioempresa = "../sdo-cotizacion-inventario/cotizacion-empresa/id-" . $idInventario . "/";
                            if (!file_exists($directorioempresa)) {
                                mkdir($directorioempresa, 0777);
                            }

                            $dirempresa = opendir($directorioempresa);

                            $rutaArchivoempresa = $directorioempresa . $nameempresa;

                            $separarNombreempresa = explode(".", $nameempresa);

                            $extImagenempresa = end($separarNombreempresa);

                            $extImagenempresa = mb_strtolower($extImagenempresa);

                            if ($extImagenempresa == "docx" || $extImagenempresa == "doc" || $extImagenempresa == "xlsx" || $extImagenempresa == "xls" || $extImagenempresa == "ppt" || $extImagenempresa == "pptx" || $extImagenempresa == "pdf" || $extImagenempresa == "jpg" || $extImagenempresa == "png" || $extImagenempresa == "jpeg" || $extImagenempresa == "bmp" || $extImagenempresa == "tif" || $extImagenempresa == "webp" || $extImagenempresa == "svg" || $extImagenempresa == "jfif") {
                                if (!file_exists($nameempresa)) {
                                    if (move_uploaded_file($tmp_nameempresa, $rutaArchivoempresa)) {
                                        chown($rutaArchivoempresa, "adminSDO");
                                        chmod($rutaArchivoempresa, 0777);

                                        $queryempresa = "INSERT INTO `archivos_cotizacion_inventario`(`idArchivo`, `nomArchivo`, `rutaArchivo`, `extArchivo`, `tipoCotizacion`, `idInventario`) 
                             VALUES (NULL,'$nameempresa','$rutaArchivoempresa','$extImagenempresa','$soliCompra',$idInventario)";
                                        $resqueryempresa = mysqli_query($conexion, $queryempresa);

                                        if ($resqueryempresa) {
                                            if ($idCategoria == 0 || empty($idCategoria)) {
                                                header('Location: ../' . $rutaDireccion . '/inventario-hbqp.php?exitoIngresoInventarioConArchivoEmpresa=true');
                                            } else {
                                                header('Location: ../' . $rutaDireccion . '/equipamiento_hbqp.php?exitoIngresoInventarioConArchivoEmpresa=true');
                                            }
                                        } else {
                                            if ($idCategoria == 0 || empty($idCategoria)) {
                                                header('Location: ../' . $rutaDireccion . '/inventario-hbqp.php?errorInventarioConArchivoEmpresa=true');
                                            } else {
                                                header('Location: ../' . $rutaDireccion . '/equipamiento_hbqp.php?errorInventarioConArchivoEmpresa=true');
                                            }
                                        }
                                    } else {
                                        echo 'error al subir el archivo 2';
                                        exit;
                                    }
                                } else {
                                    echo 'archivo ya existe';
                                    exit;
                                }
                            } else {
                                echo 'formato no permitido';
                                exit;
                            }
                        }
                    }


                    if ($idCategoria == 0 || empty($idCategoria)) {
                        header('Location: ../' . $rutaDireccion . '/inventario-hbqp.php?exitoAgregarEquipo=true');
                    } else {
                        header('Location: ../' . $rutaDireccion . '/equipamiento_hbqp.php?exitoIngresoInventario=true');
                    }

                } else {
                    if ($idCategoria == 0 || empty($idCategoria)) {
                        header('Location: ../' . $rutaDireccion . '/agregar-equipo-inventario-general.php?errorAgregarEquipo=true');
                    } else {
                        header('Location: ../' . $rutaDireccion . '/equipamiento_hbqp.php?errorIngresoInventario=true');
                    }
                }
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

