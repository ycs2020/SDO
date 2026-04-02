<?php

include '../sdo-funciones/funciones.php';
include '../sdo-funciones/conexion.php';

$contarTotal = 0;

$sql = "SELECT * FROM solicitudes_compra_sdo_observaciones_cc";
$res = mysqli_query($conexion, $sql);

$observacionesCC = [];
while ($row = $res->fetch_assoc()) {
    $observacionesCC[$row['idObservacion']] = $row;
}

$sql1 = "SELECT * FROM solicitudes_compra_sdo_observaciones";
$res1 = mysqli_query($conexion, $sql1);

while ($row1 = $res1->fetch_assoc()) {
    $idObservacion = $row1['idObservacion'];

    if (!isset($observacionesCC[$idObservacion])) {

        $fechaObservacion = mysqli_real_escape_string($conexion, $row1['fechaObservacion']);
        $detalleObservacion = mysqli_real_escape_string($conexion, $row1['detalleObservacion']);
        $creadorObservacion = mysqli_real_escape_string($conexion, $row1['creadorObservacion']);
        $idSoliCompra = mysqli_real_escape_string($conexion, $row1['idSoliCompra']);

        // Aquí generamos la consulta de inserción
        $sqlInsert = "INSERT INTO `solicitudes_compra_sdo_observaciones_cc` (`fechaObservacion`, `detalleObservacion`, `creadorObservacion`, `idSoliCompra`)
                      VALUES ('$fechaObservacion', '$detalleObservacion', '$creadorObservacion', '$idSoliCompra')";
        //$resInsert = mysqli_query($conexion,$sqlInsert);
        /*if($resInsert){
            $contarTotal++;
        }
        */
    }
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sqlMaterial = "SELECT * FROM material_solicompra_sdo_cc";
$resMaterial = mysqli_query($conexion, $sqlMaterial);
$observacionesCCMaterial = [];
while ($rowMaterial = $resMaterial->fetch_assoc()) {
    $observacionesCCMaterial[$rowMaterial['idArticuloSC']] = $rowMaterial;
}

$sqlMaterial1 = "SELECT * FROM material_solicompra_sdo";
$resMaterial1 = mysqli_query($conexion, $sqlMaterial1);
while ($rowMaterial1 = $resMaterial1->fetch_assoc()) {
    $idArticuloSC = $rowMaterial1['idArticuloSC'];
    if (!isset($observacionesCCMaterial[$idArticuloSC])) {
        $nomArticuloSC = mysqli_real_escape_string($conexion, $rowMaterial1['nomArticuloSC']);
        $cantidadArticuloSC = mysqli_real_escape_string($conexion, $rowMaterial1['cantidadArticuloSC']);
        $medidaSC = mysqli_real_escape_string($conexion, $rowMaterial1['medidaSC']);
        $valorSC = mysqli_real_escape_string($conexion,$rowMaterial1['valorSC']);
        $idSoliCompraMaterial = mysqli_real_escape_string($conexion, $rowMaterial1['idSoliCompra']);
        $entregado = mysqli_real_escape_string($conexion,$rowMaterial1['entregado']);
        $cantidadLlego = mysqli_real_escape_string($conexion,$rowMaterial1['cantidadLlego']);
        $cantidadLlegoActual = mysqli_real_escape_string($conexion,$rowMaterial1['cantidadLlegoActual']);
        $cantidadFaltante = mysqli_real_escape_string($conexion,$rowMaterial1['cantidadFaltante']);
        $mostrarEnTabla = mysqli_real_escape_string($conexion,$rowMaterial1['mostrarEnTabla']);

        $sqlInsertMaterial = "INSERT INTO `material_solicompra_sdo_cc`(`idArticuloSC`, `nomArticuloSC`, `cantidadArticuloSC`, `medidaSC`, `valorSC`, `idSoliCompra`, `entregado`, `cantidadLlego`, `cantidadLlegoActual`, `cantidadFaltante`, `mostrarEnTabla`) 
        VALUES (NULL,'$nomArticuloSC',$cantidadArticuloSC,'$medidaSC','$valorSC',$idSoliCompraMaterial,$entregado,$cantidadLlego,$cantidadLlegoActual,$cantidadFaltante,$mostrarEnTabla)";
        //echo $sqlInsertMaterial.'<br>';
        //$resInsertMaterial = mysqli_query($conexion,$sqlInsertMaterial);
        /*
        if($resInsertMaterial){
            $contarTotal++;
        }
        */
    }
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sqlSeguimiento = "SELECT * FROM seguimiento_sc_sdo_cc";
$resSeguimiento = mysqli_query($conexion, $sqlSeguimiento);
$observacionesCCSeguimiento = [];
while ($rowSeguimiento = $resSeguimiento->fetch_assoc()) {
    $observacionesCCSeguimiento[$rowSeguimiento['idSeguimientoSC']] = $rowSeguimiento;
}

$sqlSeguimiento1 = "SELECT * FROM seguimiento_sc_sdo";
$resSeguimiento1 = mysqli_query($conexion, $sqlSeguimiento1);
while ($rowSeguimiento1 = $resSeguimiento1->fetch_assoc()) {
    $idSeguimientoSC = $rowSeguimiento1['idSeguimientoSC'];
    if (!isset($observacionesCCSeguimiento[$idSeguimientoSC])) {
       $fechaActualizacion = mysqli_real_escape_string($conexion,$rowSeguimiento1['fechaActualizacion']);
       $observacion = mysqli_real_escape_string($conexion,$rowSeguimiento1['observacion']);
       $tipoActualizacion = mysqli_real_escape_string($conexion,$rowSeguimiento1['tipoActualizacion']);
       $idSoliCompraSeguimiento = mysqli_real_escape_string($conexion,$rowSeguimiento1['idSoliCompra']);

       $sqlInsertSeguimiento = "INSERT INTO `seguimiento_sc_sdo_cc`(`idSeguimientoSC`, `fechaActualizacion`, `observacion`, `tipoActualizacion`, `idSoliCompra`) 
        VALUES (NULL,'$fechaActualizacion','$observacion','$tipoActualizacion',$idSoliCompraSeguimiento)";
       //echo $sqlInsertSeguimiento.'<br>';
       //$resInsertSeguimiento = mysqli_query($conexion,$sqlInsertSeguimiento);
       /*
        if($resInsertSeguimiento){
              $contarTotal++;
        }
        */
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sqlDetalleOrden = "SELECT * FROM detalle_ordencompra_cc";
$resDetalleOrden = mysqli_query($conexion,$sqlDetalleOrden);
while($rowDetalleOrden = $resDetalleOrden->fetch_assoc()){
    $observacionesCCDetalleOrden[$rowDetalleOrden['idDetalle']] = $rowDetalleOrden;
}

$sqlDetalleOrden1 = "SELECT * FROM detalle_ordencompra";
$resDetalleOrden1 = mysqli_query($conexion,$sqlDetalleOrden1);
while($rowDetalleOrden1 = $resDetalleOrden1->fetch_assoc()){
    $idDetalle = $rowDetalleOrden1['idDetalle'];
    if (!isset($observacionesCCDetalleOrden[$idDetalle])) {
        $codOrdenCompra = mysqli_real_escape_string($conexion,$rowDetalleOrden1['codOrdenCompra']);
        $fechaEnvioProv = mysqli_real_escape_string($conexion,$rowDetalleOrden1['fechaEnvioProv']);
        $valorTotal = mysqli_real_escape_string($conexion,$rowDetalleOrden1['valorTotal']);
        $unidadSoliCompra = mysqli_real_escape_string($conexion,$rowDetalleOrden1['unidadSoliCompra']);
        $idSoliCompraDetalleOrden = mysqli_real_escape_string($conexion,$rowDetalleOrden1['idSoliCompra']);
        $fechaRegistro = mysqli_real_escape_string($conexion,$rowDetalleOrden1['fechaRegistro']);

        $sqlInsertDetalle = "INSERT INTO `detalle_ordencompra_cc`(`idDetalle`, `codOrdenCompra`, `fechaEnvioProv`, `valorTotal`, `unidadSoliCompra`, `idSoliCompra`, `fechaRegistro`) 
        VALUES (NULL,'$codOrdenCompra','$fechaEnvioProv',$valorTotal,'$unidadSoliCompra',$idSoliCompraDetalleOrden,'$fechaRegistro')";
       // echo $sqlInsertDetalle;
        //$resInsertDetalle = mysqli_query($conexion,$sqlInsertDetalle);
        /*
         if($resInsertDetalle){
               $contarTotal++;
         }
         */
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sqlSolicitudesCompra = "SELECT * FROM solicitudes_compra_sdo_cc";
$resSolicitudesCompra = mysqli_query($conexion,$sqlSolicitudesCompra);
while($rowSolicitudesCompra = $resSolicitudesCompra->fetch_assoc()){
    $observacionesCCDSolicitudesCompra[$rowSolicitudesCompra['idSoliCompra']] = $rowSolicitudesCompra;
}
$sqlSolicitudesCompra1 = "SELECT * FROM solicitudes_compra_sdo";
$resSolicitudesCompra1 = mysqli_query($conexion,$sqlSolicitudesCompra1);
while($rowSolicitudesCompra1 = $resSolicitudesCompra1->fetch_assoc()) {
    $idSoliCompra = $rowSolicitudesCompra1['idSoliCompra'];
    if (!isset($observacionesCCDSolicitudesCompra[$idSoliCompra])) {
        //echo $idSoliCompra.'<br>';
        $numSoliCompra = mysqli_real_escape_string($conexion,$rowSolicitudesCompra1['numSoliCompra']);
        $folioSoliCompra = mysqli_real_escape_string($conexion,$rowSolicitudesCompra1['folioSoliCompra']);
        $descripcionSoliCompra = mysqli_real_escape_string($conexion,$rowSolicitudesCompra1['descripcionSoliCompra']);
        $fechaSoliCompra = mysqli_real_escape_string($conexion,$rowSolicitudesCompra1['fechaSoliCompra']);
        $personalSoliCompra = mysqli_real_escape_string($conexion,$rowSolicitudesCompra1['personalSoliCompra']);
        $unidadSoliCompra = mysqli_real_escape_string($conexion,$rowSolicitudesCompra1['unidadSoliCompra']);
        $emailSoliCompra = mysqli_real_escape_string($conexion,$rowSolicitudesCompra1['emailSoliCompra']);
        $argumentosSoliCompra = mysqli_real_escape_string($conexion,$rowSolicitudesCompra1['argumentosSoliCompra']);
        $adjuntaCotizacion = mysqli_real_escape_string($conexion,$rowSolicitudesCompra1['adjuntaCotizacion']);
        $estadoSoliCompra = mysqli_real_escape_string($conexion,$rowSolicitudesCompra1['estadoSoliCompra']);
        $notificacion = mysqli_real_escape_string($conexion,$rowSolicitudesCompra1['notificacion']);
        $isCotizacion = mysqli_real_escape_string($conexion,$rowSolicitudesCompra1['isCotizacion']);
        $isRevisado = mysqli_real_escape_string($conexion,$rowSolicitudesCompra1['isRevisado']);
        $idEquipo = mysqli_real_escape_string($conexion,$rowSolicitudesCompra1['idEquipo']);
        $idCategoria = mysqli_real_escape_string($conexion,$rowSolicitudesCompra1['idCategoria']);
        $idSolicitudSoporte = mysqli_real_escape_string($conexion,$rowSolicitudesCompra1['idSolicitudSoporte']);
        $numTicketSIGH = mysqli_real_escape_string($conexion,$rowSolicitudesCompra1['numTicketSIGH']);
        $idSubAsigna = mysqli_real_escape_string($conexion,$rowSolicitudesCompra1['idSubAsigna']);

        if($isRevisado = '' || empty($isRevisado)){
            $isRevisado = 0;
        }

        if(empty($idEquipo)){
            $idEquipo = 0;
        }

        if(empty($idCategoria)){
            $idCategoria = 0;
        }

        $sqlInsertSoliCompra = "INSERT INTO `solicitudes_compra_sdo`(`idSoliCompra`, `numSoliCompra`, `folioSoliCompra`, `descripcionSoliCompra`, `fechaSoliCompra`, `personalSoliCompra`, `unidadSoliCompra`, `emailSoliCompra`, `argumentosSoliCompra`, `adjuntaCotizacion`, `estadoSoliCompra`, `notificacion`, `isCotizacion`, `isRevisado`, `idEquipo`, `idCategoria`, `idSolicitudSoporte`, `numTicketSIGH`, `idSubAsigna`) 
        VALUES (NULL,$numSoliCompra,'$folioSoliCompra','$descripcionSoliCompra','$fechaSoliCompra','$personalSoliCompra','$unidadSoliCompra','$emailSoliCompra','$argumentosSoliCompra',$adjuntaCotizacion,'$estadoSoliCompra','$notificacion','$isCotizacion','$isRevisado','$idEquipo','$idCategoria','$idSolicitudSoporte','$numTicketSIGH','$idSubAsigna')";
        /* $resInsertSoliCompra = mysqli_query($conexion,$sqlInsertSoliCompra);
            if($resInsertSoliCompra){
               $contarTotal++;
            }
         */
       // echo $sqlInsertSoliCompra.'<br><br>';


    }
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sqlSolicitudesResumen = "SELECT * FROM solicitudes_sc_sdo_resumen_cc";
$resSolicitudesResumen = mysqli_query($conexion,$sqlSolicitudesResumen);
while($rowSolicitudesResumen = $resSolicitudesResumen->fetch_assoc()){
    $observacionesCCDSolicitudesResumen[$rowSolicitudesResumen['idSoliCompraRes']] = $rowSolicitudesResumen;
}
$sqlSolicitudesResumen1 = "SELECT * FROM solicitudes_sc_sdo_resumen";
$resSolicitudesResumen1 = mysqli_query($conexion,$sqlSolicitudesResumen1);
while($rowSolicitudesResumen1 = $resSolicitudesResumen1->fetch_assoc()) {
    $idSoliCompraRes = $rowSolicitudesResumen1['idSoliCompraRes'];
    if (!isset($observacionesCCDSolicitudesResumen[$idSoliCompraRes])) {
        $descripcion = mysqli_real_escape_string($conexion,$rowSolicitudesResumen1['descripcion']);
        $fechaSoliCompra = mysqli_real_escape_string($conexion,$rowSolicitudesResumen1['fechaSoliCompra']);
        $solicitante = mysqli_real_escape_string($conexion,$rowSolicitudesResumen1['solicitante']);
        $unidadSoliCompra = mysqli_real_escape_string($conexion,$rowSolicitudesResumen1['unidadSoliCompra']);
        $numInterior = mysqli_real_escape_string($conexion,$rowSolicitudesResumen1['numInterior']);
        $estadoSDA = mysqli_real_escape_string($conexion,$rowSolicitudesResumen1['estadoSDA']);
        $fechaSDA = mysqli_real_escape_string($conexion,$rowSolicitudesResumen1['fechaSDA']);
        $areaDerivacion = mysqli_real_escape_string($conexion,$rowSolicitudesResumen1['areaDerivacion']);
        $ordenCompra = mysqli_real_escape_string($conexion,$rowSolicitudesResumen1['ordenCompra']);
        $fechaEnvioOC = mysqli_real_escape_string($conexion,$rowSolicitudesResumen1['fechaEnvioOC']);
        $valor = mysqli_real_escape_string($conexion,$rowSolicitudesResumen1['valor']);
        $estadoSolicitud = mysqli_real_escape_string($conexion,$rowSolicitudesResumen1['estadoSolicitud']);
        $idSoliCompra = mysqli_real_escape_string($conexion,$rowSolicitudesResumen1['idSoliCompra']);

        if(empty($valor)){
            $valor = 0;
        }

        $sqlInsertSolicitudesResumen = "INSERT INTO `solicitudes_sc_sdo_resumen_cc`(`idSoliCompraRes`, `descripcion`, `fechaSoliCompra`, `solicitante`, `unidadSoliCompra`, `numInterior`, `estadoSDA`, `fechaSDA`, `areaDerivacion`, `ordenCompra`, `fechaEnvioOC`, `valor`, `estadoSolicitud`, `idSoliCompra`) 
        VALUES (NULL,'$descripcion','$fechaSoliCompra','$solicitante','$unidadSoliCompra','$numInterior','$estadoSDA','$fechaSDA','$areaDerivacion','$ordenCompra','$fechaEnvioOC','$valor','$estadoSolicitud',$idSoliCompra)";
        /* $resInsertSolicitudesResumen = mysqli_query($conexion,$sqlInsertSolicitudesResumen);
           if($resInsertSolicitudesResumen){
              $contarTotal++;
           }
        */
        //echo $sqlInsertSolicitudesResumen.'<br><br>';
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$sqlArchivosOrden = "SELECT * FROM `archivo-orden-de-compra-panol-cc`";
$resArchivosOrden = mysqli_query($conexion,$sqlArchivosOrden);
while($rowArchivosOrden = $resArchivosOrden->fetch_assoc()){
    $observacionesCCDArchivosOrden[$rowArchivosOrden['id']] = $rowArchivosOrden;
}
$sqlArchivosOrden1 = "SELECT * FROM `archivo-orden-de-compra-panol`";
$resArchivosOrden1 = mysqli_query($conexion,$sqlArchivosOrden1);
while($rowArchivosOrden1 = $resArchivosOrden1->fetch_assoc()) {
    $idArchivosOrden = $rowArchivosOrden1['id'];
    if (!isset($observacionesCCDArchivosOrden[$idArchivosOrden])) {
        $nomArchivo = mysqli_real_escape_string($conexion,$rowArchivosOrden1['nomArchivo']);
        $rutaArchivo = mysqli_real_escape_string($conexion,$rowArchivosOrden1['rutaArchivo']);
        $tipoArchivo = mysqli_real_escape_string($conexion,$rowArchivosOrden1['tipoArchivo']);
        $idSoliCompra = mysqli_real_escape_string($conexion,$rowArchivosOrden1['idSoliCompra']);
        $sqlInsertArchivosOrden = "INSERT INTO `archivo-orden-de-compra-panol`(`id`, `nomArchivo`, `rutaArchivo`, `tipoArchivo`, `idSoliCompra`) 
        VALUES (NULL,'$nomArchivo','$rutaArchivo','$tipoArchivo',$idSoliCompra)";
        //$resInsertArchivosOrden = mysqli_query($conexion,$sqlInsertArchivosOrden);
        /*
          if($resInsertArchivosOrden){
               $contarTotal++;
          }
         */
    }
}