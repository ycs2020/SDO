<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$idReporte = $_SESSION['idReporte'];

$idReporte = trim($idReporte);

if (!empty($idReporte) || $idReporte == '') {
    if (isset($_POST['nomEncargadoTurno'], $_POST['nomFuncionariosTurno'], $_POST['nomEncargadoTurnoAnterior'], $_POST['listaTurno'], $_POST['fechaActualReporte2'])) {

        $nomEncargadoTurno = trim($_POST['nomEncargadoTurno']);
        $nomFuncionariosTurno = trim($_POST['nomFuncionariosTurno']);
        $nomEncargadoTurnoAnterior = trim($_POST['nomEncargadoTurnoAnterior']);
        $listaTurno = trim($_POST['listaTurno']);
        $fechaActualReporte = $_POST['fechaActualReporte2'];

        if ($listaTurno == "Dia") {
            $turno = "D";
        } else if ($listaTurno == "Noche") {
            $turno = "N";
        } else {
            header('Location: ../sdo-personal/reportes-listado.php?errorModificar=true');
        }

        $nuevoNombre = 'Reporte General de Eventos - '.$listaTurno.' - '.$fechaActualReporte;

        $nuevoFolio = 'HBQP-RGE-'.$turno.'-'.$idReporte;

        if (!empty($idReporte) || !empty($nomFuncionariosTurno) || !empty($nomEncargadoTurnoAnterior)) {

            if (isset($_POST['tareasControlCentralizado'], $_POST['tareasInstalaciones'], $_POST['tareasEquiposIndustriales'], $_POST['tareasIncendiosCombustibles'], $_POST['tareasDeteccionElectrica'], $_POST['pendientesCoordinacion'])) {

                $tareasControlCentralizado = trim($_POST['tareasControlCentralizado']);
                $tareasInstalaciones = trim($_POST['tareasInstalaciones']);
                $tareasEquiposIndustriales = trim($_POST['tareasEquiposIndustriales']);
                $tareasIncendiosCombustibles = trim($_POST['tareasIncendiosCombustibles']);
                $tareasDeteccionElectrica = trim($_POST['tareasDeteccionElectrica']);
                $pendientesCoordinacion = trim($_POST['pendientesCoordinacion']);

                if (!empty($tareasControlCentralizado)) {

                    $tareasControlCentralizado = str_replace("'", "\'", $tareasControlCentralizado);
                }

                if (!empty($tareasInstalaciones)) {

                    $tareasInstalaciones = str_replace("'", "\'", $tareasInstalaciones);
                }

                if (!empty($tareasEquiposIndustriales)) {

                    $tareasEquiposIndustriales = str_replace("'", "\'", $tareasEquiposIndustriales);
                }

                if (!empty($tareasIncendiosCombustibles)) {

                    $tareasIncendiosCombustibles = str_replace("'", "\'", $tareasIncendiosCombustibles);
                }

                if (!empty($tareasDeteccionElectrica)) {

                    $tareasDeteccionElectrica = str_replace("'", "\'", $tareasDeteccionElectrica);
                }

                if (!empty($pendientesCoordinacion)) {

                    $pendientesCoordinacion = str_replace("'", "\'", $pendientesCoordinacion);
                }

                $queryModificar1 = "UPDATE reportes_generales SET nomEncargadoTurno = '" . $nomEncargadoTurno . "', nomFuncionariosTurno = '" . $nomFuncionariosTurno . "', nomEncargadoTurnoAnterior = '" . $nomEncargadoTurnoAnterior . "', nomReporte = '" . $nuevoNombre . "', folioReporte = '" . $nuevoFolio . "', turnoReporte = '" . $listaTurno . "' WHERE idReporte = " . $idReporte;

                $resModificar1 = mysqli_query($conexion, $queryModificar1);

                if ($resModificar1) {
                    $queryModificar2 = "UPDATE reportes_generales SET tareasControlCentralizado = '" . $tareasControlCentralizado . "', tareasInstalaciones = '" . $tareasInstalaciones . "', tareasEquiposIndustriales = '" . $tareasEquiposIndustriales . "', tareasIncendiosCombustibles = '" . $tareasIncendiosCombustibles . "', tareasDeteccionElectrica = '" . $tareasDeteccionElectrica . "', pendientesCoordinacion = '" . $pendientesCoordinacion . "' WHERE idReporte = " . $idReporte;

                    $resModificar2 = mysqli_query($conexion, $queryModificar2);

                    if ($resModificar2) {
                        header('Location: ../sdo-jefestecnicos/reportes-videos.php?exitoModificarEventosReporte=true');
                    } else {
                        header('Location: ../sdo-jefestecnicos/reportes-listado.php?errorModificarReporte=true');
                    }
                } else {
                    header('Location: ../sdo-jefestecnicos/reportes-listado.php?errorModificarReporte=true');
                }
            } else {
                header("Location: ../sdo-jefestecnicos/reportes-listado?errorModificarReporte.php");
            }
        } else {
            header("Location: ../sdo-jefestecnicos/reportes-listado?errorModificarReporte.php");
        }
    } else {
        header("Location: ../sdo-jefestecnicos/reportes-listado?errorModificarReporte.php");
    }
} else {
    header("Location: ../sdo-jefestecnicos/reportes-listado?errorModificarReporte.php");
}
