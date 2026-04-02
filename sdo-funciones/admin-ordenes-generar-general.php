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
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}


if (isset($_POST['nomSolicitante'], $_POST['nomTecnicoRecepcionador'], $_POST['servicioOrden'], $_POST['departamentoSolicitante'], $_POST['recintoOrden'],    $_POST['fechaActualOrden'])) {

    $ticketAsociado = $_POST['ticketAsociado'];
    $nomSolicitante = trim($_POST['nomSolicitante']);

    $nomTecnicoRecepcionador = trim($_POST['nomTecnicoRecepcionador']);

    $servicioOrden = trim($_POST['servicioOrden']);

    $servicioOrden = mb_strtoupper($servicioOrden, 'UTF-8');

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
    $idHoja = trim($_POST['idHoja']);
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

    // DATOS SOLICITANTE
    echo "Nombre Solicitante = " . $nomSolicitante . "<br>";
    echo "Nombre Tecnico Recepcionador = " . $nomTecnicoRecepcionador . "<br>";
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

    echo "Nombre Equipo = " . $nomEquipo . "<br>";
    echo "Numero Inventario = " . $numInventario . "<br>";
    echo "Marca Equipo = " . $marcaEquipo . "<br>";
    echo "Modelo Equipo = " . $modeloEquipo . "<br>";
    echo "Numero Serie = " . $numSerie . "<br>";

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
        header('Location: ../sdo-administrador/ordenes-formulario-general.php?errorGenerar=true');
    } else {
        if ($departamentoSolicitante == '' || $departamentoSolicitante == ' ' || empty($departamentoSolicitante)  || $trabajoRealizado == '' || $trabajoRealizado == ' ' || empty($trabajoRealizado)) {
            header('Location: ../sdo-administrador/ordenes-formulario-general.php?errorGenerar=true');
        } else {

            date_default_timezone_set('America/Santiago');
            $now = date('Y-m-d H:i:s', time());
            $queryOrden = "INSERT INTO ordenes_trabajos_general(idOrden,fechaOrden,nomSolicitante,nomTecnicoRecepcionador,idDepartamento,servicioOrden,recintoOrden,pmaOrden,coordenadasOrden,tipoMantencion,trabajoRealizado,problemaPresenta,accionesRealiza, comentariosTrabajos, ticketAsociado, idEquipo, idCreador, tipoUsuario)
            VALUES (NULL,'" . $now . "','" . $nomSolicitante . "','" . $nomTecnicoRecepcionador . "','" . $departamentoSolicitante . "','" . $servicioOrden . "','" . $recintoOrden . "','" . $pmaOrden . "','" . $coordenadasOrden . "','" . $tipoMantencion . "','" . $trabajoRealizado . "','" . $problemaPresenta . "','" . $accionesRealiza . "','" . $comentariosTrabajos . "','" . $ticketAsociado . "','".$idHoja."', $idUsuario, '$tipoUsuario')";


            $resOrden = mysqli_query($conexion, $queryOrden);

            if ($resOrden) {
                $idOrden = mysqli_insert_id($conexion);

                //INSERTAR EN TABLA EQUIPOS DE ORDEN
                if (!empty($_POST['nomEquipo']) || !empty($_POST['marcaEquipo']) || !empty($_POST['numInventario'])) {
                    $queryEquipoOrden = "INSERT INTO equipos_recepcion_trabajo (equipo,numInventario,marca,modelo,numSerie,idOrden) VALUES ('$nomEquipo', '$numInventario', '$marcaEquipo', '$modeloEquipo', '$numSerie', $idOrden)";
                    echo $queryEquipoOrden;
                    exit;
                    $resEquipoOrden = mysqli_query($conexion, $queryEquipoOrden);

                }

                //INSERTA EN TABLA CONTADOR DE HOJAS
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

                    if (isset($_FILES["archivoImagenOrden"])) {
                        $tot = count($_FILES["archivoImagenOrden"]["name"]);
                        for ($i = 0; $i < $tot; $i++) {
                            $tmp_name = $_FILES["archivoImagenOrden"]["tmp_name"][$i];
                            $name = $_FILES["archivoImagenOrden"]["name"][$i];

                            if ($tmp_name == '' || $tmp_name == ' ' || empty($tmp_name)) {
                                echo 'sin nombres';
                                //exit;
                            } else {

                                $directorio = "../sdo-imagenes-orden/" . $folioOrden . "/";

                                if (!file_exists($directorio)) {
                                    mkdir($directorio, 0777, true);
                                }

                                $dir = opendir($directorio);

                                $ruta = $directorio . $name;

                                $separarNombre = explode(".", $name);

                                $extImagen = end($separarNombre);

                                $extImagen = mb_strtolower($extImagen);


                                if ($extImagen == "jpg" || $extImagen == "png" || $extImagen == "jpeg" || $extImagen == "bmp" || $extImagen == "tif" || $extImagen == "webp" || $extImagen == "svg" || $extImagen == "jfif") {

                                    if (move_uploaded_file($tmp_name, $ruta)) {

                                        $queryImagen = "INSERT INTO imagenes_orden_general(idImagen,nomImagen,extImagen,rutaImagen,idReporte,idOrden) VALUES (NULL,'" . $name . "','" . $extImagen . "','" . $ruta . "',0,'" . $idOrden . "')";

                                        $resImagen = mysqli_query($conexion, $queryImagen);

                                        if ($resImagen) {
                                            echo 'Si subi';
                                            //exit;
                                        }
                                    } else {
                                        echo 'No subi al servidor';
                                        //exit;
                                    }
                                }
                            }
                        }
                    }

                    if (isset($_FILES["archivosOrden"])) {

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
                                    exit;
                                } else {

                                    $directorio = "../sdo-archivos-orden/" . $folioOrden . '/';
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

                    header('Location: ../' . $rutaDireccion . '/ordenes-administrar-general.php?exitoGenerar=true');
                } else {
                    // $queryBorrar = "DELETE FROM ordenes_trabajos WHERE idOrden = " . $idOrden;
                    header('Location: ../' . $rutaDireccion . '/ordenes-formulario-general.php?errorFolio=true');
                }
            } else {
                header('Location: ../' . $rutaDireccion . '/ordenes-formulario-general.php?errorGenerar=true');
            }
        }
    }
} else {
    header('Location: ../' . $rutaDireccion . '/ordenes-formulario-general.php?errorGenerar=true');
}
