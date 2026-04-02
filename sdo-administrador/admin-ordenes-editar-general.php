<?php

include 'conexion.php';
include 'funciones.php';

if (isset($_POST['idOrden'])) {


    $idOrden = $_POST['idOrden'];
    $ticketAsociado = $_POST['ticketAsociado'];
    $nomSolicitante = trim($_POST['nomSolicitante']);

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

    //SQL UPDATE A LA TABLA DE HOJAS TÉCNICAS
    $ActualizaHojas = "UPDATE ordenes_trabajos_general SET ticketAsociado = '$ticketAsociado', idDepartamento = $departamentoSolicitante,  nomTecnicoRecepcionador = '$nomTecnicoRecepcionador', nomSolicitante ='$nomSolicitante', servicioOrden = $servicioOrden, recintoOrden = '$recintoOrden',pmaOrden = '$pmaOrden' ,coordenadasOrden = '$coordenadasOrden', tipoMantencion =  '$tipoMantencion', problemaPresenta = '$problemaPresenta', accionesRealiza = '$accionesRealiza', trabajoRealizado = '$trabajoRealizado', comentariosTrabajos = '$comentariosTrabajos'  WHERE idOrden = $idOrden";

    $resActualizarHojas = mysqli_query($conexion, $ActualizaHojas);

    if ($resActualizarHojas) {

        header('Location: ../sdo-administrador/ordenes-administrar-general.php?ExitoActualizar=true');
    } else {
        header('Location: ../sdo-administrador/ordenes-administrar-general.php?ExitoActualizar=true');
        echo "ERROR AL ACTUALIZAR";
        exit;
    }
} else {
    echo "NO IDORDEN";
    exit;
}
