<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';

$idCategoria = isset($_POST['idCategoria']) ? (int)$_POST['idCategoria'] : 0;

if ($idCategoria <= 0) {
    echo "<div class='alert alert-danger'><i class='fas fa-exclamation-triangle'></i> ID de categoría inválido o no enviado.</div>";
    exit;
}

$sql = "SELECT id FROM equipamiento_hbqp WHERE idCategoria = ? AND estadoEquipo LIKE 'Fuera de Servicio' AND idEquipoPadre > -1 ORDER BY nombreEquipo ASC";
$stmt = mysqli_prepare($conexion, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $idCategoria);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result || mysqli_num_rows($result) === 0) {
        echo "<div class='alert alert-success text-center font-weight-bold'><i class='fas fa-check-circle'></i> ¡Excelente! No hay equipos fuera de servicio en esta categoría.</div>";
        mysqli_stmt_close($stmt);
        exit;
    }

    $numFueraServicio = 1;
    ?>
    <style>
        #tablaEquiposFueraServicio td,
        #tablaEquiposFueraServicio th {
            padding: 0.5rem !important;
            vertical-align: middle !important;
        }
    </style>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover w-100" id="tablaEquiposFueraServicio" style="font-size: 10pt;">
            <thead class="bg-danger text-white text-center">
            <tr>
                <th>N°</th>
                <th>Usuario Observador</th>
                <th>Nombre del Equipo</th>
                <th>Acción</th>
                <th>Fecha FS</th>
                <th>Tipo Estado</th>
                <th>Detalle / Observación</th>
            </tr>
            </thead>
            <tbody>
            <?php
            // Mapeo estructurado para evitar inyecciones dinámicas en consultas secundarias
            $tablaUsuarios = [
                'administrativo' => ['adm_tecnicos', 'nomAdmTecnicos', 'idAdmTecnicos'],
                'jefe tecnico'   => ['jefes_tecnicos', 'nomJefe', 'idJefe'],
                'personal'       => ['personal', 'nomPersonal', 'idPersonal'],
                'administrador'  => ['administracion', 'nomAdministrador', 'idAdministrador'],
                'mesa ayuda'     => ['mesa_ayuda', 'nomMesaAyuda', 'idMesaAyuda'],
                'directiva'      => ['directivos', 'nomDirectivo', 'idDirectivo']
            ];

            while ($row = mysqli_fetch_assoc($result)) {
                $idEquipo = (int)$row['id'];

                $sqlHistorial = "SELECT * FROM historial_cambios_equipos WHERE idEquipo = ? AND estado LIKE '%Fuera de Servicio%' ORDER BY fecha DESC LIMIT 1";
                $stmtHist = mysqli_prepare($conexion, $sqlHistorial);

                if ($stmtHist) {
                    mysqli_stmt_bind_param($stmtHist, "i", $idEquipo);
                    mysqli_stmt_execute($stmtHist);
                    $resHistorial = mysqli_stmt_get_result($stmtHist);

                    if ($resHistorial && $historial = mysqli_fetch_assoc($resHistorial)) {
                        $fechaFS          = date('d/m/Y', strtotime($historial['fecha']));
                        $estadoFS         = mb_convert_case($historial['estado'], MB_CASE_UPPER, 'UTF-8');
                        $observacionesFS  = mb_convert_case($historial['observaciones'], MB_CASE_UPPER, 'UTF-8');
                        $nombreEquipoFS   = mb_convert_case($historial['nombreEquipo'], MB_CASE_UPPER, 'UTF-8');
                        $idUsuario        = (int)$historial['idUsuarioCambia'];
                        $tipoUsuario      = strtolower(trim($historial['tipoUsuario']));
                        $idEquipoFS       = (int)$historial['idEquipo'];

                        $nomUsuarioFS = 'NO APLICA';

                        if (isset($tablaUsuarios[$tipoUsuario])) {
                            [$tabla, $campoNombre, $campoID] = $tablaUsuarios[$tipoUsuario];

                            // Aseguramos los nombres de las columnas usando una Whitelist fija
                            $sqlUser = "SELECT $campoNombre AS nombre FROM $tabla WHERE $campoID = ? LIMIT 1";
                            $stmtUser = mysqli_prepare($conexion, $sqlUser);
                            if ($stmtUser) {
                                mysqli_stmt_bind_param($stmtUser, "i", $idUsuario);
                                mysqli_stmt_execute($stmtUser);
                                $resUser = mysqli_stmt_get_result($stmtUser);
                                if ($resUser && $rowUser = mysqli_fetch_assoc($resUser)) {
                                    $nomUsuarioFS = $rowUser['nombre'] ?: 'NO APLICA';
                                }
                                mysqli_stmt_close($stmtUser);
                            }
                        }

                        $nomUsuarioFS2 = mb_convert_case($nomUsuarioFS, MB_CASE_UPPER, 'UTF-8');
                        $tipoMenuFS = obtenerTipoMenu($idEquipoFS);
                        $rutaMenuFS = ($tipoMenuFS == 'nuevo') ? 'equipo_menuNuevo.php' : 'equipo_menuAntiguo.php';
                        ?>
                        <tr>
                            <td class="text-center font-weight-bold"><?= $numFueraServicio ?></td>
                            <td><?= htmlspecialchars($nomUsuarioFS2) ?></td>
                            <td><?= htmlspecialchars($nombreEquipoFS) ?></td>
                            <td class="text-center">
                                <form method="POST" action="<?= htmlspecialchars($rutaMenuFS) ?>" target="_blank">
                                    <input type="hidden" name="idEquipo" value="<?= $idEquipoFS ?>">
                                    <button type="submit" class="btn btn-outline-primary btn-sm px-3" title="Ver detalles del equipo">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="text-center whitespace-nowrap"><?= $fechaFS ?></td>
                            <td class="text-center"><span class="badge badge-danger"><?= htmlspecialchars($estadoFS) ?></span></td>
                            <td><?= htmlspecialchars($observacionesFS) ?></td>
                        </tr>
                        <?php
                        $numFueraServicio++;
                    }
                    mysqli_stmt_close($stmtHist);
                }
            }
            ?>
            </tbody>
        </table>
    </div>
    <?php
    mysqli_stmt_close($stmt);
} else {
    echo "<div class='alert alert-danger'><i class='fas fa-bug'></i> Error interno del servidor.</div>";
}
?>