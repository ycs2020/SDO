<?php
//include_once '../sdo-funciones/conexion.php';
//include_once '../sdo-funciones/funciones.php';
//
//if (session_status() == PHP_SESSION_NONE) {
//    session_start();
//}
//
//$userUsuario = $_SESSION['userUsuario'];
//$idUsuario = $_SESSION['idUsuario'];
//$nomUsuario = $_SESSION['nomUsuario'];
//
//$rolUsuario = $_SESSION['rolUsuario'];
//
//$rolUsuario2 = strtolower($rolUsuario);
//
//$rutaDireccion = "";
//
//if ($rolUsuario2 == "personal") {
//    $tipoUsuario = 'personal';
//    $rutaDireccion = "sdo-personal";
//} else if ($rolUsuario2 == "administrativo") {
//    $tipoUsuario = 'administrativo';
//    $rutaDireccion = "sdo-admtec";
//} else if ($rolUsuario2 == "bodega pañol") {
//    $tipoUsuario = 'bodega pañol';
//    $rutaDireccion = "sdo-bodega-panol";
//} else if ($rolUsuario2 == "jefe tecnico") {
//    $tipoUsuario = 'jefe tecnico';
//    $rutaDireccion = "sdo-jefestecnicos";
//} else if ($rolUsuario2 == "administrador") {
//    $tipoUsuario = 'administrador';
//    $rutaDireccion = "sdo-administrador";
//} else if ($rolUsuario2 == "directiva") {
//    $tipoUsuario = 'directiva';
//    $rutaDireccion = "sdo-directiva";
//} else if($rolUsuario2 == "oficina tecnica") {
//    $tipoUsuario = 'oficina tecnica';
//    $rutaDireccion = "sdo-oficinaTecnica";
//}else{
//    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
//}
//
//if(isset($_POST['noOrdenTrabajo'])){
//    $noOrdenTrabajo = $_POST['noOrdenTrabajo'];
//    $sqlTrabajoGeneral = "SELECT * FROM trabajos_general WHERE folioTG = '$noOrdenTrabajo'";
//    $resTrabajoGeneral = mysqli_query($conexion,$sqlTrabajoGeneral);
//    while($rowTrabajoGeneral = $resTrabajoGeneral->fetch_assoc()){
//        $idTG = $rowTrabajoGeneral['idTG'];
//        $sqlOtro = "SELECT * FROM archivos_recepcion_documento WHERE idTG = $idTG";
//        $resOtro = mysqli_query($conexion,$sqlOtro);
//        if(mysqli_num_rows($resOtro) > 0) {
//            while($rowOtro = $resOtro->fetch_assoc()){
//                $idRecepcion = $rowOtro['idRecepcion'];
//                $sqlRecepcion = "SELECT * FROM recepcion_documento WHERE idRecepcion = $idRecepcion";
//                $resRecepcion = mysqli_query($conexion,$sqlRecepcion);
//                while($rowRecepcion = $resRecepcion->fetch_assoc()){
//                    $sqlUpdateRecepcion = "UPDATE recepcion_documento SET estado = 'Finalizado' WHERE idRecepcion = $idRecepcion";
//                    $resUpdateRecepcion = mysqli_query($conexion,$sqlUpdateRecepcion);
//                    if($resUpdateRecepcion){
//                        $detalleObsRecep = "Se finaliza el documento con la Orden de Trabajo con folio: " .$noOrdenTrabajo;
//                        $insertObservacion = "INSERT INTO `recepcion_observacion`(`idObsRecepcion`, `detalleObservacion`, `fechaObs`, `nomCreador`, `idRecepcion`, `estado`)
//                    VALUES (NULL,'".$detalleObsRecep."','0000-00-00 00:00:00','".$nomUsuario."',$idRecepcion,'Finalizado')";
//                        $resInsertObservacion = mysqli_query($conexion,$insertObservacion);
//                        if(!$resInsertObservacion){
//                            if($tipoUsuario !== 'personal'){
//                                header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?errorUpdateRecepcion=true');
//                            }else{
//                                header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?errorUpdateRecepcion=true');
//                            }
//                        }
//                    }else{
//                        if($tipoUsuario !== 'personal'){
//                            header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?errorUpdateRecepcion=true');
//                        }else{
//                            header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?errorUpdateRecepcion=true');
//                        }
//                    }
//                }
//            }
//        }
//    }
//}