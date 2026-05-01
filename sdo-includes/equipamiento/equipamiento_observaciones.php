<?php
include_once '../../sdo-funciones/conexion.php';
include_once '../../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function formatearFechaCompletaAJAX($fechaRaw)
{
    $fechaCompleta = 'Fecha inválida';

    if (!empty($fechaRaw)) {
        $timestamp = strtotime(str_replace('/', '-', $fechaRaw));

        if ($timestamp !== false && $timestamp > 0) {
            $inicioFecha = fechaEspañol2($timestamp);
            $fechaCompleta = $inicioFecha;
        }
    }

    return $fechaCompleta;
}

if (!$conexion) {
    header("HTTP/1.1 500 Internal Server Error");
    die("Error de conexión a la base de datos.");
}

$idEquipo = $_POST['idEquipo'] ?? null;

if (!$idEquipo || !is_numeric($idEquipo)) {
    echo "<div class='p-4 text-center'><div class='alert alert-danger'>Error: ID de equipo no válido.</div></div>";
    exit;
}

/**
 * SQL OPTIMIZADA:
 * Usamos LEFT JOIN con todas las posibles tablas de usuarios.
 * COALESCE tomará el primer valor que no sea NULL entre todas las tablas.
 */
$sql = "SELECT h.*, 
        COALESCE(adm.nomAdmTecnicos, jef.nomJefe, per.nomPersonal, admin.nomAdministrador, mesa.nomMesaAyuda, dir.nomDirectivo, 'SISTEMA') AS nombreReal
        FROM historial_cambios_equipos h
        LEFT JOIN adm_tecnicos adm ON (h.idUsuarioCambia = adm.idAdmTecnicos AND h.tipoUsuario = 'administrativo')
        LEFT JOIN jefes_tecnicos jef ON (h.idUsuarioCambia = jef.idJefe AND h.tipoUsuario = 'jefe técnico')
        LEFT JOIN personal per ON (h.idUsuarioCambia = per.idPersonal AND h.tipoUsuario = 'personal')
        LEFT JOIN administracion admin ON (h.idUsuarioCambia = admin.idAdministrador AND h.tipoUsuario = 'administrador')
        LEFT JOIN mesa_ayuda mesa ON (h.idUsuarioCambia = mesa.idMesaAyuda AND h.tipoUsuario = 'mesa ayuda')
        LEFT JOIN directivos dir ON (h.idUsuarioCambia = dir.idDirectivo AND h.tipoUsuario = 'directiva')
        WHERE h.idEquipo = ? 
        ORDER BY h.idEquipo DESC";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idEquipo);
$stmt->execute();
$res = $stmt->get_result();
?>

<div class="modal-content shadow-lg border-0">
    <div class="modal-header bg-primary py-2">
        <h5 class="modal-title text-white"><i class="fas fa-history mr-2"></i>Historial de Cambios</h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
    </div>

    <div class="modal-body">
        <div class="table-responsive">
            <table id="tablaHistorialCambios" class="table table-bordered table-hover text-center align-middle m-0 w-100">
                <thead class="bg-secondary">
                <tr>
                    <th>Usuario</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Detalle / Observación</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if ($res->num_rows > 0) {
                    while ($fila = $res->fetch_assoc()) {
                        $estado = $fila['estado'] ?? 'Desconocido';
                        $claseBadge = 'badge-info';
                        if ($estado == 'Operativo') $claseBadge = 'badge-success';
                        if ($estado == 'Fuera de Servicio') $claseBadge = 'badge-danger';
                        if ($estado == 'Mantenimiento') $claseBadge = 'badge-warning';
                        ?>
                        <tr>
                            <td style="font-size: 14px">
                                <b><?= htmlspecialchars($fila['nombreReal']) ?></b>
                            </td>
                            <td><?= formatearFechaCompletaAJAX($fila['fecha']) ?></td>
                            <td><span class="badge <?= $claseBadge ?>"><?= htmlspecialchars($estado) ?></span></td>
                            <td class="text-left text-sm"><?= nl2br(htmlspecialchars($fila['observaciones'] ?? '')) ?></td>
                        </tr>
                        <?php
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal-footer bg-light">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times mr-1"></i> Cerrar</button>
    </div>
</div>

<script>
    $(document).ready(function() {
        if ($.fn.DataTable.isDataTable('#tablaHistorialCambios')) {
            $('#tablaHistorialCambios').DataTable().destroy();
        }
        $('#tablaHistorialCambios').DataTable({
            ...dataTableConfig,
            pageLength: 5,
            order: [[1, 'desc']]
        });
    });
</script>