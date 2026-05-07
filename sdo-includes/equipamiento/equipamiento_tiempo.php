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
?>

<div class="modal-content shadow-lg border-0">
    <div class="modal-header bg-primary py-2">
        <h5 class="modal-title text-white"><i class="fas fa-history mr-2"></i>Historial de Funcionamiento</h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
    </div>

    <div class="modal-body">
        <?php
        // Configuración inicial
        $fechaActual = new DateTime();
        $sqlEquipamiento = "SELECT fechaRegistro, estadoEquipo FROM equipamiento_hbqp WHERE id = $idEquipo";
        $resEquipamiento = mysqli_query($conexion, $sqlEquipamiento);
        $rowEquipamiento = $resEquipamiento->fetch_assoc();

        $fechaRegistro = new DateTime($rowEquipamiento['fechaRegistro']);
        $estadoActual = $rowEquipamiento['estadoEquipo'];

        // 1. Calcular Tiempo Total transcurrido
        $totalSegundosVida = $fechaActual->getTimestamp() - $fechaRegistro->getTimestamp();

        // Inicializamos acumuladores en segundos
        $segundos = [
            'Operativo' => 0,
            'Fuera de Servicio' => 0,
            'Mantenimiento' => 0,
            'OperativoObs' => 0
        ];

        // 2. Obtener historial
        $sqlHistorial = "SELECT estado, fecha FROM historial_cambios_equipos WHERE idEquipo = $idEquipo ORDER BY fecha ASC";
        $resHistorial = mysqli_query($conexion, $sqlHistorial);

        $fechaPuntoA = clone $fechaRegistro;
        $estadoPuntoA = null; // El estado inicial se asume operativo o se define según lógica de negocio

        while ($row = $resHistorial->fetch_assoc()) {
            $fechaPuntoB = new DateTime($row['fecha']);
            $estadoAnterior = $row['estado']; // El estado que cambió en ese momento

            // Calculamos la duración del periodo anterior
            $diferencia = $fechaPuntoB->getTimestamp() - $fechaPuntoA->getTimestamp();

            // Asignamos el tiempo al estado correspondiente (aquí podrías necesitar ajustar
            // si el 'estado' en la DB representa el estado ENTRANTE o el SALIENTE)
            if (isset($segundos[$estadoAnterior])) {
                $segundos[$estadoAnterior] += $diferencia;
            }

            $fechaPuntoA = clone $fechaPuntoB;
        }

        // 3. Sumar el tiempo desde el último cambio hasta "Ahora"
        $tiempoRestante = $fechaActual->getTimestamp() - $fechaPuntoA->getTimestamp();
        if (isset($segundos[$estadoActual])) {
            $segundos[$estadoActual] += $tiempoRestante;
        }

        // 4. Función para convertir segundos a texto legible
        function formatearSegundos($segundosTotales) {
            if ($segundosTotales <= 0) return "0 Días, 0 Horas, 0 Minutos";

            $dtF = new DateTime('@0');
            $dtT = new DateTime("@$segundosTotales");
            $diff = $dtF->diff($dtT);

            $partes = [];
            if ($diff->y > 0) $partes[] = $diff->y . " Año(s)";
            if ($diff->m > 0) $partes[] = $diff->m . " Mes(es)";
            if ($diff->d > 0) $partes[] = $diff->d . " Día(s)";
            if ($diff->h > 0) $partes[] = $diff->h . " Hora(s)";
            if ($diff->i > 0) $partes[] = $diff->i . " Minuto(s)";

            return empty($partes) ? "0 Minutos" : implode(", ", $partes);
        }

        // Preparar frases para la tabla
        $fraseTotal = formatearSegundos($totalSegundosVida);
        $fraseOperativo = formatearSegundos($segundos['Operativo']);
        $fraseFuera = formatearSegundos($segundos['Fuera de Servicio']);
        $fraseMantenimiento = formatearSegundos($segundos['Mantenimiento']);
        $fraseObserva = formatearSegundos($segundos['OperativoObs']);
        ?>

        <div class="table-responsive">
            <table class="table table-hover table-bordered" style="text-align: center">
                <thead class="thead-dark">
                <tr>
                    <th colspan="2">Historial de Disponibilidad: ID <?php echo $idEquipo ?></th>
                </tr>
                </thead>
                <tbody>
                <tr class="table-secondary">
                    <td><strong>Tiempo Total de Vida:</strong></td>
                    <td><?php echo $fraseTotal; ?></td>
                </tr>
                <tr>
                    <td><span class="badge badge-success">Operativo</span></td>
                    <td><?php echo $fraseOperativo; ?></td>
                </tr>
                <tr>
                    <td><span class="badge badge-warning">Operativo (Obs)</span></td>
                    <td><?php echo $fraseObserva; ?></td>
                </tr>
                <tr>
                    <td><span class="badge badge-info">Mantenimiento</span></td>
                    <td><?php echo $fraseMantenimiento; ?></td>
                </tr>
                <tr>
                    <td><span class="badge badge-danger">Fuera de Servicio</span></td>
                    <td><?php echo $fraseFuera; ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal-footer bg-light">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times mr-1"></i> Cerrar</button>
    </div>
</div>
