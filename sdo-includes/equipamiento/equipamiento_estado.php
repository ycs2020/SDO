<?php
// Asegúrate de que estas rutas sean correctas según tu estructura en el servidor hospitalario
include_once '../../sdo-funciones/conexion.php';
include_once '../../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Validación de conexión
if (!$conexion) {
    header("HTTP/1.1 500 Internal Server Error");
    die("Error de conexión a la base de datos.");
}

// Recibimos el ID por POST
$idEquipo = $_POST['idEquipo'] ?? null;

if (!$idEquipo || !is_numeric($idEquipo)) {
    die("<div class='alert alert-danger'>ID de equipo no válido.</div>");
}

// Consultamos los datos actuales
$stmt = $conexion->prepare("SELECT e.estadoEquipo, e.nombreEquipo, e.idCategoria 
                             FROM equipamiento_hbqp e 
                             WHERE e.id = ?");
$stmt->bind_param("i", $idEquipo);
$stmt->execute();
$res = $stmt->get_result();
$equipo = $res->fetch_assoc();

if (!$equipo) {
    die("<div class='alert alert-warning'>No se encontró la información del equipo.</div>");
}

$estadoActual = $equipo['estadoEquipo'] ?? '';
?>


<div class="modal-content shadow-lg border-0">
    <div class="modal-header bg-primary py-2">
        <h5 class="modal-title text-white">Modificar Estado del Equipo</h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
    </div>
    <form id="formularioCambiaEstado">
        <div class="modal-body">
            <input type="hidden" value="<?= (int)$idEquipo ?>" name="idEquipo">
            <input type="hidden" value="<?= (int)$equipo['idCategoria'] ?>" name="idCategoria">
            <input type="hidden" value="<?= htmlspecialchars($equipo['nombreEquipo'], ENT_QUOTES, 'UTF-8') ?>" name="nombreEquipo">

            <div class="form-group">
                <label>Estado Actual:
                    <span class="badge badge-info"><?= htmlspecialchars($estadoActual) ?></span></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-sync-alt"></i></span>
                    </div>
                    <select name="opcionEstado" id="opcionEstado" required class="form-control">
                        <?php
                        $estados = [
                            'Operativo' => 'Operativo',
                            'Mantenimiento' => 'Mantenimiento',
                            'Fuera de Servicio' => 'Fuera de Servicio',
                            'OperativoObs' => 'Operativo con Observación',
                            'Extraviado' => 'Extraviado'
                        ];

                        foreach ($estados as $val => $label) {
                            $selected = ($estadoActual == $val) ? 'selected' : '';
                            echo "<option value='$val' $selected>$label</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Observaciones del Cambio:<code>*</code></label>
                <textarea name="observaciones" id="observaciones" rows="3" class="form-control" required placeholder="Motivo del cambio de estado..."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary" id="btnGuardarEstado">
                <i class="fas fa-save mr-1"></i> Guardar Estado
            </button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">
                <i class="fas fa-times mr-1"></i> Cerrar
            </button>
        </div>
    </form>
</div>