<?php

include 'conexion.php';

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

$ruta = "";

if ($rolUsuario2 == "personal") {

    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {

    $ruta = "sdo-admtec";
}else if ($rolUsuario2 == "mesa ayuda") {

    $ruta = "sdo-mesa-ayuda";
} else if ($rolUsuario2 == "bodega pañol") {

    $ruta = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {

    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {

    $ruta = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $ruta = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['id'])) {

        $opcion = $_POST['opcion'];
        $idSoliCompra = $_POST['id'];

        if ($opcion == 'entregadoSDA') {
            $nuevoEstado = 'Entregado a la S.D.A';
            $observacion = "";
            $fecha = $_POST['fechaEntrega'];

            $sqlInsertSeguimiento = "INSERT INTO seguimiento_sc_sdo (fechaActualizacion,observacion, tipoActualizacion, idSoliCompra) VALUES ('$fecha','$observacion','$nuevoEstado', $idSoliCompra)";
            $resInsertSeguimiento = mysqli_query($conexion, $sqlInsertSeguimiento);

            $sqlUpdateResumen = "UPDATE solicitudes_sc_sdo_resumen SET  fechaSDA = '$fecha' WHERE idSoliCompra = $idSoliCompra";
            $resUpdateResumen = mysqli_query($conexion, $sqlUpdateResumen);

            if ($resInsertSeguimiento) {
                $sqlUpdateSC = "UPDATE solicitudes_compra_sdo SET estadoSoliCompra = '$nuevoEstado' WHERE idSoliCompra = $idSoliCompra";
                $resUpdateSC = mysqli_query($conexion, $sqlUpdateSC);
            } else {
                echo "error insertar en la tabla de seguimiento";
                exit;
            }

            if ($resUpdateSC) {
                header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento.php?exitoEntrega=true');
            } else {
                header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento.php?errorNumInterno=true');
            }
        } else if ($opcion == 'asignarNumeroInternoSDA') {
            if (isset($_POST['numInterno'])) {
                $numInternoSDA = $_POST['numInterno'];
                $nuevoEstado = 'Asignacion Numero Interno S.D.A';

                $sqlInsertSeguimiento = "INSERT INTO seguimiento_sc_sdo (observacion, tipoActualizacion, idSoliCompra) VALUES ('$numInternoSDA','$nuevoEstado', $idSoliCompra)";
                $resInsertSeguimiento = mysqli_query($conexion, $sqlInsertSeguimiento);

                if ($resInsertSeguimiento) {
                    $sqlUpdateSC = "UPDATE solicitudes_sc_sdo_resumen SET numInterior = '$numInternoSDA' WHERE idSoliCompra = $idSoliCompra";
                    $resUpdateSC = mysqli_query($conexion, $sqlUpdateSC);
                } else {
                    echo "error insertar en la tabla de seguimiento";
                    exit;
                }

                if ($resUpdateSC) {
                    header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento.php?exitoNumInterno=true');
                } else {
                    header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento.php?errorNumInterno=true');
                }
            } else {
                header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento.php?errorNumSDA=true');
            }
        } else if ($opcion == 'editarAsignarNumeroInternoSDA') {
            if (isset($_POST['numInterno'])) {
                $numInternoSDA = $_POST['numInterno'];

                $sqlUpdateSC = "UPDATE solicitudes_sc_sdo_resumen SET numInterior = '$numInternoSDA' WHERE idSoliCompra = $idSoliCompra";
                $resUpdateSC = mysqli_query($conexion, $sqlUpdateSC);

                if ($resUpdateSC) {
                    header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento.php?exitoNumInterno=true');
                } else {
                    header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento.php?errorNumInterno=true');
                }
            } else {
                header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento.php?errorNumSDA=true');
            }
        } else if ($opcion == 'cambiaEstadoSDA') {
            if (isset($_POST['estadoSDA'])) {
                $estadoSDA = $_POST['estadoSDA'];
                $motivo = $_POST['motivo'];
                $obs = $_POST['obs'];
                $nuevoEstado = 'Actualizacion Estado S.D.A';

                if ($estadoSDA == 'autorizada') {
                    $estadoSC = 'Autorizado por la S.D.A';
                    $estadoResumen = 'Autorizada';
                    $estadoSeguimiento = 'Autorizada';
                    $derivado = 'Abastecimiento';
                    $fecha = date('Y-m-d H:i:s');
                } else if($estadoSDA == 'denegada'){
                    $estadoSeguimiento = 'Rechazado por la S.D.A' . '<br> Razón/Motivo: ' . $motivo;
                    $estadoResumen = 'Rechazado';
                    $estadoSC = 'Rechazado por la S.D.A';
                    $fecha = date('Y-m-d H:i:s');
                }else if($estadoSDA == 'pendiente'){
                    $estadoSeguimiento = 'Pendiente';
                    $fecha = date('Y-m-d H:i:s');
                    $estadoResumen = 'Pendiente';
                    $estadoSC = 'Pendiente';

                    $sqlInsert = "INSERT INTO solicitudes_compra_sdo_observaciones (detalleObservacion,creadorObservacion,idSoliCompra) 
                    VALUES ('$obs', '$nomUsuario', $idSoliCompra)";
                    $resInsert = mysqli_query($conexion, $sqlInsert);
                }

                $sqlInsertSeguimiento = "INSERT INTO seguimiento_sc_sdo (observacion, tipoActualizacion, idSoliCompra) 
                VALUES ('$estadoSeguimiento','$nuevoEstado', $idSoliCompra)";

                $resInsertSeguimiento = mysqli_query($conexion, $sqlInsertSeguimiento);

                $sqlUpdateResumen = "UPDATE solicitudes_sc_sdo_resumen SET estadoSDA = '$estadoResumen', areaDerivacion = '$derivado' WHERE idSoliCompra = $idSoliCompra";
                $resUpdateResumen = mysqli_query($conexion, $sqlUpdateResumen);


                if ($resInsertSeguimiento) {
                    $sqlUpdateSC = "UPDATE solicitudes_compra_sdo SET estadoSoliCompra = '$estadoSC' WHERE idSoliCompra = $idSoliCompra";
                    $resUpdateSC = mysqli_query($conexion, $sqlUpdateSC);
                } else {
                    echo "error insertar en la tabla de seguimiento";
                    exit;
                }


                if ($resUpdateSC) {
                    header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento.php?exitoEditEstado=true');
                } else {
                    header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento.php?errorEditEstado=true');
                }
            } else {
                header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento.php?errorNumSDA=true');
            }
        } else if ($opcion == 'entregaAbastecimiento') {
            if (isset($_POST['fechaEntrega'])) {


                $nuevoEstado = 'Derivado a Abastecimiento';
                $fecha = $_POST['fechaEntrega'];

                $sqlInsertSeguimiento = "INSERT INTO seguimiento_sc_sdo (fechaActualizacion,observacion, tipoActualizacion, idSoliCompra) VALUES ('$fecha','$estadoSDA','$nuevoEstado', $idSoliCompra)";
                $resInsertSeguimiento = mysqli_query($conexion, $sqlInsertSeguimiento);



                if ($resInsertSeguimiento) {
                    $sqlUpdateSC = "UPDATE solicitudes_compra_sdo SET estadoSoliCompra = '$nuevoEstado' WHERE idSoliCompra = $idSoliCompra";
                    $resUpdateSC = mysqli_query($conexion, $sqlUpdateSC);
                } else {
                    echo "error insertar en la tabla de seguimiento";
                    exit;
                }


                if ($resUpdateSC) {
                    header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento.php?exitoEntregaAbastecimiento=true');
                } else {
                    header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento.php?errorEntregaAbastecimiento=true');
                }
            } else {
                header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento.php?errorNumSDA=true');
            }
        } else if ($opcion == 'ordenCompras') {
            if (isset($_POST['ordenCompra'])) {
                $ordenesCompra = $_POST['ordenCompra'];
                $fechaEnvio = $_POST['fechaEnvio'];
                $valorTotal = $_POST['valorTotal'];

                $nuevoEstado = 'Se han generado ordenes de compra';
                $estadoSDA = 'Ordenes Compra: <br> ' .  $ordenesCompra . '<br> Fecha envio al Proveedor: <br>' . $fechaEnvio;


                $sqlInsertSeguimiento = "INSERT INTO seguimiento_sc_sdo (observacion, tipoActualizacion, idSoliCompra) VALUES ('$estadoSDA','$nuevoEstado', $idSoliCompra)";
                $resInsertSeguimiento = mysqli_query($conexion, $sqlInsertSeguimiento);

                $sqlUpdateResumen = "UPDATE solicitudes_sc_sdo_resumen SET ordenCompra = '$ordenesCompra', fechaEnvioOC = '$fechaEnvio', valor = $valorTotal, estadoSolicitud = 'Pendiente' WHERE idSoliCompra = $idSoliCompra";
                $resUpdateResumen = mysqli_query($conexion, $sqlUpdateResumen);


                if ($sqlUpdateResumen) {
                    header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento.php?exitoOrdenCompra=true');
                } else {
                    header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento.php?errorOrdenCompra=true');
                }
            } else {
                header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento.php?errorNumSDA=true');
            }
        } else if ($opcion == 'ordenComprasEdit') {
            if (isset($_POST['ordenCompra'])) {

                $ordenesCompra = $_POST['ordenCompra'];
                $fechaEnvio = $_POST['fechaEnvio'];
                $valorTotal = $_POST['valorTotal'];

                $nuevoEstado = 'Se han Actualizado las ordenes de compra';
                $estadoSDA = 'Ordenes Compra: <br> ' .  $ordenesCompra . '<br> Fecha envio al Proveedor: <br>' . $fechaEnvio;


                $sqlInsertSeguimiento = "INSERT INTO seguimiento_sc_sdo (observacion, tipoActualizacion, idSoliCompra) VALUES ('$estadoSDA','$nuevoEstado', $idSoliCompra)";
                $resInsertSeguimiento = mysqli_query($conexion, $sqlInsertSeguimiento);

                $sqlUpdateResumen = "UPDATE solicitudes_sc_sdo_resumen SET ordenCompra = '$ordenesCompra', fechaEnvioOC = '$fechaEnvio', valor = $valorTotal, estadoSolicitud = 'Pendiente' WHERE idSoliCompra = $idSoliCompra";
                $resUpdateResumen = mysqli_query($conexion, $sqlUpdateResumen);


                if ($resUpdateResumen) {
                    header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento.php?ExitoEditOrdenCompra=true');
                } else {
                    header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento.php?ErrorEditOrdenCompra=true');
                }
            } else {
                header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento.php?errorNumSDA=true');
            }
        }
    } else {
        header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento.php?idNoValido=true');
    }
}
