<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$idReporte = $_SESSION['idReporte'];

$idReporte = trim($idReporte);

if (!empty($idReporte) && $idReporte > 0) {
    if (isset($_POST['nomEncargadoTurno'], $_POST['nomEncargadoTurnoAnterior'])) {

        $nomEncargadoTurno = trim($_POST['nomEncargadoTurno']);
        $nomEncargadoTurnoAnterior = trim($_POST['nomEncargadoTurnoAnterior']);

        if (!empty($idReporte) || !empty($nomEncargadoTurnoAnterior) || !empty($listaTurno)) {

            if (isset($_POST['tareasDeteccionElectrica'])) {

                $tareasDeteccionElectrica = trim($_POST['tareasDeteccionElectrica']);

                if (!empty($tareasDeteccionElectrica)) {

                    $tareasDeteccionElectrica = str_replace("'", "\'", $tareasDeteccionElectrica);
                }

                $queryModificar1 = "UPDATE reportes_electricidad SET nomEncargadoTurno = '" . $nomEncargadoTurno . "', nomEncargadoTurnoAnterior = '" . $nomEncargadoTurnoAnterior . "' WHERE idReporte = " . $idReporte;

                $resModificar1 = mysqli_query($conexion, $queryModificar1);

                if ($resModificar1) {
                    $queryModificar2 = "UPDATE reportes_electricidad SET tareasDeteccionElectrica = '" . $tareasDeteccionElectrica . "' WHERE idReporte = " . $idReporte;

                    $resModificar2 = mysqli_query($conexion, $queryModificar2);

                    if ($resModificar2) {
                        header('Location: ../sdo-personal/electricidad_reportes_turno.php?exitoModificarEventosReporte=true');
                    } else {
                        header('Location: ../sdo-personal/electricidad_reportes_turno.php?errorModificarReporte=true');
                    }
                } else {
                    header('Location: ../sdo-personal/electricidad_reportes_turno.php?errorModificarReporte=true');
                }
            } else {
                header("Location: ../sdo-personal/electricidad_reportes_turno?errorModificarReporte.php");
            }
        } else {
            header("Location: ../sdo-personal/electricidad_reportes_turno?errorModificarReporte.php");
        }
    } else {
        header("Location: ../sdo-personal/electricidad_reportes_turno?errorModificarReporte.php");
    }
} else {
    header("Location: ../sdo-personal/electricidad_reportes_turno?errorModificarReporte.php");
}
