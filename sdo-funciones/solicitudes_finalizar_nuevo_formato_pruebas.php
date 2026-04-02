<?php

include_once 'conexion.php';
include_once 'funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$idUsuario = $_SESSION['idUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$nomCreador = $_SESSION['nomUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

$ruta = "";

if ($rolUsuario2 == "personal") {

    $sql = "SELECT * FROM personal WHERE idPersonal = $idUsuario ";
    $res = mysqli_query($conexion, $sql);

    $tipoUsuario = 'personal';

    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $tipoUsuario = 'administrativo';
    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "jefe tecnico") {
    $tipoUsuario = 'jefe tecnico';
    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $tipoUsuario = 'administrador';
    $ruta = "sdo-administrador";
} else {

    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

$headerTextCorrecto = 'Location: ../' . $ruta . '/solicitudes_detalle.php?exitoFinalizarSolicitud=true';
$headerError = 'Location: ../' . $ruta . '/solicitudes_recibidas.php?errorIdSolicitud=true';
$headerError2 = 'Location: ../' . $ruta . '/solicitudes_recibidas.php?errorDepartamentoResponsable=true';
$headerError3 = 'Location: ../' . $ruta . '/solicitudes_detalle.php?errorFinalizarSolicitud=true';

if (isset($_POST['idSolicitud'], $_POST['conclusionProblema'], $_POST['datosFinalizador'], $_POST['nomSolicitante'])) {
    $idSolicitud = $_POST['idSolicitud'];
    $conclusionProblema = $_POST['conclusionProblema'];
    $conclusionProblema = mysqli_real_escape_string($conexion, $conclusionProblema);
    $datosFinalizador = trim($_POST['datosFinalizador']);
    $nomSolicitante = trim($_POST['nomSolicitante']);
    $folioSolicitud = getFolioSolicitud($idSolicitud);
    $deptoSolicitante = $_POST['deptoSolicitante'];
    $pmaSolicitud = $_POST['pmaSolicitud'];
    $detalleSolicitud = $_POST['detalleSolicitud'];
    $servicioOrden = trim($_POST['servicios']);
    $deptoResponsable = trim($_POST['departamentoResponsable']);
    $idDepartamento = $_POST['idDepartamento'];
    $nomDepartamentoSolicitante = trim(getNomDepartamento($idDepartamento));
    $idHoja = $_POST['idHoja'];
    $idCategoria = $_POST['idCategoria'];
    $idEquipo = $_POST['idEquipo'];
    $horaInicio = $_POST['horarioInicio'];
    $horarioTermino = $_POST['horarioTermino'];
    $noOrdenTrabajo = $_POST['noOrdenTrabajo'];
    $tipoMantencion = $_POST['tipoMantencion'];
    $nomEquipo = $_POST['nomEquipo'];
    $numInventario = $_POST['numInventario'];
    $marcaEquipo = $_POST['marcaEquipo'];
    $modeloEquipo = $_POST['modeloEquipo'];
    $numSerie = $_POST['numSerie'];
    $tipoAcuerdo = $_POST['tipoAcuerdo'];
    $oc = $_POST['oc'];
    $estadoEquipo = $_POST['estadoEquipo'];
    $comentariosTrabajos = $_POST['comentariosTrabajos'];
    $now = date('Y-m-d H:i:s');
    $nowYear = date('Y-m-d');
    $recintoOrden = $_POST['recintoOrden'];


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

    if (!empty($conclusionProblema)) {

        $conclusionProblema = str_replace("'", "\'", $conclusionProblema);
    }

    if (!empty($detalleSolicitud)) {

        $detalleSolicitud = str_replace("'", "\'", $detalleSolicitud);
    }

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

    $queryOrden = "INSERT INTO ordenes_trabajos_general_pruebas_2(idOrden,numOrden,fechaOrden,nomSolicitante,nomTecnicoRecepcionador,idDepartamento,servicioOrden,recintoOrden,pmaOrden,coordenadasOrden,tipoMantencion,trabajoRealizado,problemaPresenta,accionesRealiza, comentariosTrabajos, ticketAsociado, idCreador, tipoUsuario, noOrdenTrabajo, idEquipo, idCategoria,horaInicio,horaTermino,tipoAcuerdo,estadoEquipo,oc)
                VALUES (NULL,".$numOrden.",'".$now."','" . $nomSolicitante . "','" . $datosFinalizador . "','" . $idDepartamento . "','" . $servicioOrden . "','" . $recintoOrden . "','" . $pmaSolicitud . "','" . $coordenadasOrden . "','" . $tipoMantencion . "','" . $conclusionProblema . "','" . $detalleSolicitud . "','" . $accionesRealiza . "','" . $comentariosTrabajos . "','" .$folioSolicitud . "', $idUsuario, '$tipoUsuario','" . $noOrdenTrabajo . "', $idHoja, $idCategoria,'".$horaInicio."','".$horarioTermino."','".$tipoAcuerdo."','".$estadoEquipo."','".$oc."')";
   // $resOrdem = mysqli_query($conexion,$queryOrden);
    echo $queryOrden;

}