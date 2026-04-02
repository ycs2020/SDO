<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$idReporte = isset($_SESSION['idReporte']) ? trim($_SESSION['idReporte']) : 0;

if (!empty($idReporte) && $idReporte > 0) {

    if (isset($_POST['nomEncargadoTurno'], $_POST['nomFuncionariosTurno'], $_POST['nomEncargadoTurnoAnterior'])) {

        // Función para procesar arrays de nombres
        function procesarArrayNombres($array) {
            if (is_array($array)) {
                sort($array);
                return trim(implode(' - ', $array));
            }
            return '';
        }

        // Procesar y escapar nombres
        $nomEncargadoTurno         = mysqli_real_escape_string($conexion, procesarArrayNombres($_POST['nomEncargadoTurno']));
        $nomFuncionariosTurno      = mysqli_real_escape_string($conexion, procesarArrayNombres($_POST['nomFuncionariosTurno']));
        $nomEncargadoTurnoAnterior = mysqli_real_escape_string($conexion, procesarArrayNombres($_POST['nomEncargadoTurnoAnterior']));

        if (
            isset($_POST['tareasControlCentralizado'], $_POST['tareasInstalaciones'], $_POST['tareasEquiposIndustriales'],
                $_POST['tareasIncendiosCombustibles'], $_POST['tareasDeteccionElectrica'],
                $_POST['tareasInfraestructura'], $_POST['pendientesCoordinacion'])
        ) {
            // Obtener y limpiar + escapar para SQL
            $tareasControlCentralizado   = mysqli_real_escape_string($conexion, trim($_POST['tareasControlCentralizado']));
            $tareasInstalaciones         = mysqli_real_escape_string($conexion, trim($_POST['tareasInstalaciones']));
            $tareasEquiposIndustriales   = mysqli_real_escape_string($conexion, trim($_POST['tareasEquiposIndustriales']));
            $tareasIncendiosCombustibles = mysqli_real_escape_string($conexion, trim($_POST['tareasIncendiosCombustibles']));
            $tareasDeteccionElectrica    = mysqli_real_escape_string($conexion, trim($_POST['tareasDeteccionElectrica']));
            $tareasInfraestructura       = mysqli_real_escape_string($conexion, trim($_POST['tareasInfraestructura']));
            $pendientesCoordinacion      = mysqli_real_escape_string($conexion, trim($_POST['pendientesCoordinacion']));

            // Guardar como borrador antes de actualizar
            $queryBorrador = "
                INSERT INTO reportes_generales_borradores (
                    idReporte,
                    nomEncargadoTurno,
                    nomFuncionariosTurno,
                    nomEncargadoTurnoAnterior,
                    tareasControlCentralizado,
                    tareasInstalaciones,
                    tareasEquiposIndustriales,
                    tareasIncendiosCombustibles,
                    tareasDeteccionElectrica,
                    tareasInfraestructura,
                    pendientesCoordinacion
                ) VALUES (
                    $idReporte,
                    '$nomEncargadoTurno',
                    '$nomFuncionariosTurno',
                    '$nomEncargadoTurnoAnterior',
                    '$tareasControlCentralizado',
                    '$tareasInstalaciones',
                    '$tareasEquiposIndustriales',
                    '$tareasIncendiosCombustibles',
                    '$tareasDeteccionElectrica',
                    '$tareasInfraestructura',
                    '$pendientesCoordinacion'
                )";

            mysqli_query($conexion, $queryBorrador); // Puedes validar error si quieres

            // Ejecutar UPDATE del reporte
            $queryUpdate = "
                UPDATE reportes_generales SET
                    nomEncargadoTurno = '$nomEncargadoTurno',
                    nomFuncionariosTurno = '$nomFuncionariosTurno',
                    nomEncargadoTurnoAnterior = '$nomEncargadoTurnoAnterior',
                    tareasControlCentralizado = '$tareasControlCentralizado',
                    tareasInstalaciones = '$tareasInstalaciones',
                    tareasEquiposIndustriales = '$tareasEquiposIndustriales',
                    tareasIncendiosCombustibles = '$tareasIncendiosCombustibles',
                    tareasDeteccionElectrica = '$tareasDeteccionElectrica',
                    tareasInfraestructura = '$tareasInfraestructura',
                    pendientesCoordinacion = '$pendientesCoordinacion'
                WHERE idReporte = $idReporte
            ";

            $resultado = mysqli_query($conexion, $queryUpdate);

            if ($resultado) {
                header('Location: ../sdo-personal/reportes-videos.php?exitoModificarEventosReporte=true');
                exit;
            } else {
                header('Location: ../sdo-personal/reportes-listado.php?errorModificarReporte=true');
                exit;
            }

        } else {
            header('Location: ../sdo-personal/reportes-listado.php?errorModificarReporte=true');
            exit;
        }

    } else {
        header('Location: ../sdo-personal/reportes-listado.php?errorModificarReporte=true');
        exit;
    }

} else {
    header('Location: ../sdo-personal/reportes-listado.php?errorModificarReporte=true');
    exit;
}
