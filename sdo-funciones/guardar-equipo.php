<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

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

$rutaUsuario = "";
// echo '';
if ($rolUsuario2 == "personal") {
    $rutaUsuario = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $rutaUsuario = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $rutaUsuario = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $rutaUsuario = "sdo-jefestecnicos";
    $idDepartamento = $_SESSION['idDepartamento'];
} else if ($rolUsuario2 == "administrador") {
    $rutaUsuario = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $rutaUsuario = "sdo-directiva";
} else if ($rolUsuario2 == 'mesa ayuda') {
    $rutaUsuario = "sdo-mesa-ayuda";
} else if ($rolUsuario2 == 'oficina tecnica') {
    $rutaUsuario = "sdo-oficinaTecnica";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $idCategoria = $_POST['idCategoria'];
    $iddepartamento = $_POST['iddepartamento'];

    $idDepartamento = $_POST['idDepartamento'];

    $idDepaCategoria = $_POST['idDepaCategoria'];

    if($idDepaCategoria == '' || empty($idDepaCategoria)){
        $idDepaCategoria = 0;
    }

    if (!empty($idCategoria)) {

        //Datos Obligatorios
        $inputIDUnico = $_POST['inputIDUnico'];
        $servicio = $_POST['servicio'];
        $nombreEquipo = $_POST['nombreEquipo'];
        $idEquipoPadre = $_POST['idEquipoPadre'];
        if ($idEquipoPadre == '' || empty($idEquipoPadre)) {
            $idEquipoPadre = 0;
        }
        if (empty($inputIDUnico) || empty($servicio) || empty($nombreEquipo)) {
            echo 'vacios los obligatorios';
            exit;
        } else {

            //INFORMACIÓN TÉCNICA
            $inputInventario = $_POST['inputInventario'];
            $IDLicitacion = $_POST['IDLicitacion'];
            $ordenCompra = $_POST['ordenCompra'];
            $inputMantenimiento = $_POST['inputMantenimiento'];
            $fechaRecepcion = $_POST['fechaRecepcion'];
            $fechaCompra = $_POST['fechaCompra'];
            $fechaMarcha = $_POST['fechaMarcha'];
            $clase = $_POST['clase'];
            $subClase = $_POST['subClase'];
            $complejidad = $_POST['complejidad'];
            $riesgo = $_POST['riesgo'];
            $costoMantencion = $_POST['costoMantencion'];
            $equipoQueControla = $_POST['equipoQueControlaAgregar'];
            $sourceAlarmaAgregar = $_POST['sourceAlarmaAgregar'];
            $tipoInterfazAgregar = $_POST['tipoInterfazAgregar'];

            //INFORMACIÓN BÁSICA
            $valorConIva = $_POST['valorConIva'];
            $marcaEquipo = $_POST['marcaEquipo'];
            $modeloEquipo = $_POST['modeloEquipo'];
            $fechaFabricación = $_POST['fechaFabricación'];
            $vidaUtil = $_POST['vidaUtil'];
            $versionSoftware = $_POST['versionSoftware'];
            $paisOrigen = $_POST['paisOrigen'];
            $terminoGarantia = $_POST['terminoGarantia'];
            $numSerie = $_POST['numSerie'];

            //UBICACIÓN
            $recinto = $_POST['recinto'];
            $pma = $_POST['pma'];
            $coordenadas = $_POST['coordenadas'];
            $piso = $_POST['piso'];

            $ahnoAdquisicion = $_POST['AhnoAdquisicion'];
            $propiedades = $_POST['propiedades'];
            $evaluaciones = $_POST['evaluaciones'];
            $nombreProovedor = $_POST['nombreProovedor'];
            $mantenimientos = $_POST['mantenimientos'];
            $enGarantia = $_POST['enGarantia'];
            $vidaUtilResidual = $_POST['vidaUtilResidual'];
            $bajoPlanMantenimiento = $_POST['bajoPlanMantenimiento'];
            $idConvenioMantenimiento = $_POST['idConvenioMantenimiento'];
            $costoAnualMantenimiento = $_POST['costoAnualMantenimiento'];
            $frecuenciaAnualMantenimiento = $_POST['frecuenciaAnualMantenimiento'];
            $paginaWeb = $_POST['paginaWeb'];
            $mail = $_POST['mail'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'];
            $ahnoFuncionamiento = $_POST['ahnoFuncionamiento'];
            $factura = $_POST['factura'];
            $ahnoInstalacion = $_POST['AahnoInstalacion'];

            $estadoEquipo = 'Operativo';

            if($ahnoInstalacion == '' || empty($ahnoInstalacion)){
                $ahnoInstalacion = 0;
            }

            if($ahnoFuncionamiento == '' || empty($ahnoFuncionamiento)){
                $ahnoFuncionamiento = 0;
            }

            if ($equipoQueControla > 0) {
                $equipoQueControla2 = $equipoQueControla;
            } else {
                $equipoQueControla2 = 0;
            }


            if (!empty($sourceAlarmaAgregar) && $sourceAlarmaAgregar !== 'Seleccione...') {
                $sourceAlarmaAgregar2 = $sourceAlarmaAgregar;
            } else {
                $sourceAlarmaAgregar2 = '';
            }

            if (!empty($tipoInterfazAgregar) && $tipoInterfazAgregar !== 'Seleccione...') {
                $tipoInterfazAgregar2 = $tipoInterfazAgregar;

            } else {
                $tipoInterfazAgregar2 = '';
            }


            //INSERTAMOS EN LA TABLA CORRESPONDIENTE
            if ($idDepaCategoria == 0) {
                $sqlInsert = "INSERT INTO equipamiento_hbqp(id, identificadorUnico, numeroInventario, fechaRecepcion, nombreEquipo, marca, modelo, idServicio, recinto, pma, coordenadas, fechaCompra, valorConIva, idLicitacion, ordenCompra, fechaMarcha, clase, subclase, complejidadTec, nivelRiesgo, AhnoFrabricacion, vidaUtil, versionSoftware, paisOrigen, terminoGarantia, costoMantAnual, frecuenciaMantenimiento,numeroSerie,piso, idCategoria,idEquipoPadre,estadoEquipo, idDepaPertenece,equipo_controla,sourceAlarma,tipoMenu,`AhnoAdquisicion`, `propiedades`, `evaluaciones`, `nombreProovedor`, `mantenimientos`, `enGarantia`, `vidaUtilResidual`, `bajoPlanMantenimiento`, `idConvenioMantenimiento`, `costoAnualMantenimiento`, `frecuenciaAnualMantenimiento`,paginaWeb,mail,telefono,direccion,existeDocuTecnico,AhnoFuncionamiento,existeManual,ejecutorManteni,factura,AhnoInstalacion) 
                VALUES (null,'$inputIDUnico','$inputInventario','$fechaRecepcion','$nombreEquipo','$marcaEquipo','$modeloEquipo','$servicio','$recinto','$pma','$coordenadas','$fechaCompra','$valorConIva','$IDLicitacion','$ordenCompra','$fechaMarcha','$clase','$subClase','$complejidad','$riesgo','$fechaFabricación','$vidaUtil','$versionSoftware','$paisOrigen','$terminoGarantia','$costoMantencion','$inputMantenimiento','$numSerie',$piso,$idCategoria,$idEquipoPadre, '$estadoEquipo', 0, $equipoQueControla2, '$sourceAlarmaAgregar2', '$tipoInterfazAgregar2','$ahnoAdquisicion','$propiedades','$evaluaciones','$nombreProovedor','$mantenimientos','$enGarantia','$vidaUtilResidual','$bajoPlanMantenimiento','$idConvenioMantenimiento','$costoAnualMantenimiento','$frecuenciaAnualMantenimiento','$paginaWeb','$mail','$telefono','$direccion',NULL,$ahnoFuncionamiento,NULL,'NULL','$factura',$ahnoInstalacion)";
                echo $sqlInsert;
                $resInsert = mysqli_query($conexion, $sqlInsert);
            } else {
                $sqlInsert = "INSERT INTO equipamiento_hbqp(id, identificadorUnico, numeroInventario, fechaRecepcion, nombreEquipo, marca, modelo, idServicio, recinto, pma, coordenadas, fechaCompra, valorConIva, idLicitacion, ordenCompra, fechaMarcha, clase, subclase, complejidadTec, nivelRiesgo, AhnoFrabricacion, vidaUtil, versionSoftware, paisOrigen, terminoGarantia, costoMantAnual, frecuenciaMantenimiento,numeroSerie,piso, idCategoria,idEquipoPadre,estadoEquipo, idDepaPertenece,equipo_controla,sourceAlarma,tipoMenu,`AhnoAdquisicion`, `propiedades`, `evaluaciones`, `nombreProovedor`, `mantenimientos`, `enGarantia`, `vidaUtilResidual`, `bajoPlanMantenimiento`, `idConvenioMantenimiento`, `costoAnualMantenimiento`, `frecuenciaAnualMantenimiento`,paginaWeb,mail,telefono,direccion,existeDocuTecnico,AhnoFuncionamiento,existeManual,ejecutorManteni,factura,AhnoInstalacion) 
                VALUES (null,'$inputIDUnico','$inputInventario','$fechaRecepcion','$nombreEquipo','$marcaEquipo','$modeloEquipo','$servicio','$recinto','$pma','$coordenadas','$fechaCompra','$valorConIva','$IDLicitacion','$ordenCompra','$fechaMarcha','$clase','$subClase','$complejidad','$riesgo','$fechaFabricación','$vidaUtil','$versionSoftware','$paisOrigen','$terminoGarantia','$costoMantencion','$inputMantenimiento','$numSerie',$piso,$idCategoria,$idEquipoPadre, '$estadoEquipo', $idDepaCategoria, $equipoQueControla2, '$sourceAlarmaAgregar2', '$tipoInterfazAgregar2','$ahnoAdquisicion','$propiedades','$evaluaciones','$nombreProovedor','$mantenimientos','$enGarantia','$vidaUtilResidual','$bajoPlanMantenimiento','$idConvenioMantenimiento','$costoAnualMantenimiento','$frecuenciaAnualMantenimiento','$paginaWeb','$mail','$telefono','$direccion',NULL,$ahnoFuncionamiento,NULL,'NULL','$factura',$ahnoInstalacion)";
                echo $sqlInsert;

                $resInsert = mysqli_query($conexion, $sqlInsert);
            }



            if ($resInsert) {

                $idEquipamientoGenerado = mysqli_insert_id($conexion);

                if ($idEquipamientoGenerado > 0) {

                    $_SESSION['idEquipo'] = $idEquipamientoGenerado;

                    if ($rolUsuario2 == 'administrador') {

                        if ($tipoInterfazAgregar2 == 'nuevo') {
                            $sitioWeb = 'equipo_menuNuevo.php';
                        } else {
                            $sitioWeb = 'equipo_menuAntiguo.php';
                        }
                    } else {
                        $sitioWeb = 'equipo.php';
                    }

                } else {

                    $_SESSION['idEquipo'] = $idEquipoPadre;

                    if ($idEquipoPadre > 0) {

                        $obtenerTipoMenu = obtenerTipoMenu($idEquipoPadre);
                        if ($rolUsuario2 == 'administrador') {

                            if ($obtenerTipoMenu == 'nuevo') {
                                $sitioWeb = 'equipo_menuNuevo.php';
                            } else {
                                $sitioWeb = 'equipo_menuAntiguo.php';
                            }
                        } else {
                            $sitioWeb = 'equipo.php';
                        }
                    } else {
                        header('Location: ../' . $rutaUsuario . '/carpeta-equipo.php?exitoGuardarEquipo=true');
                    }
                }


                header('Location: ../' . $rutaUsuario . '/' . $sitioWeb . '?exitoGuardarEquipo=true');


            } else {
//                if ($idEquipoPadre > 0) {
//                    $_SESSION['idEquipo'] = $idEquipoPadre;
//                    $obtenerTipoMenu = obtenerTipoMenu($idEquipoPadre);
//
//                    if ($rolUsuario2 == 'administrador') {
//
//                        if ($obtenerTipoMenu == 'nuevo') {
//                            $sitioWeb = 'equipo_menuNuevo.php';
//                        } else {
//                            $sitioWeb = 'equipo_menuAntiguo.php';
//                        }
//                    } else {
//                        $sitioWeb = 'equipo.php';
//                    }
//                    header('Location: ../' . $rutaUsuario . '/' . $sitioWeb . '?errorGuardarEquipo1=true');
//                } else {
//                    header('Location: ../' . $rutaUsuario . '/carpeta-equipo.php?errorGuardarEquipo2=true');
//                }
                echo "Error: " . mysqli_error($conexion);
            }
        }
    } else {
        header('Location: ../' . $rutaUsuario . '/equipamiento_hbqp.php');
    }
}
