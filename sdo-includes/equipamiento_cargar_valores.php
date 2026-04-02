<?php
include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';

if (!isset($_POST['tipo'], $_POST['idCategoria'])) {
    echo "<p class='text-danger'>Error: parámetros incompletos.</p>";
    exit;
}

$tipo = trim($_POST['tipo']);
$idCategoria = (int)$_POST['idCategoria'];

function h($string)
{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

// Mapeo de tipo a condición SQL
$map = [
    'Equipos Operativos' => "estadoEquipo LIKE 'Operativo'",
    'Equipos Con Observaciones' => "estadoEquipo LIKE 'OperativoObs'",
    'Equipos en Mantenimiento' => "estadoEquipo LIKE 'En Mantenimiento'",
    'Equipos Fuera de Servicio' => "estadoEquipo LIKE 'Fuera de Servicio'",
    'Equipos Extraviados' => "estadoEquipo LIKE 'Extraviado'"
];


// Mapeo de tipo a condición SQL
$map2 = [
    'Equipos Operativos' => "estado LIKE 'Operativo'",
    'Equipos Con Observaciones' => "estado LIKE 'OperativoObs'",
    'Equipos en Mantenimiento' => "estado LIKE 'En Mantenimiento'",
    'Equipos Fuera de Servicio' => "estado LIKE 'Fuera de Servicio'",
    'Equipos Extraviados' => "estado LIKE 'Extraviado'"
];

if (!array_key_exists($tipo, $map)) {
    echo "<p class='text-warning'>No hay detalles disponibles para: <strong>$tipo</strong></p>";
    exit;
}

$condicion = $map[$tipo];
$condicion2 = $map2[$tipo];

$query = "SELECT id, nombreEquipo, modelo, marca, recinto, tipoMenu FROM equipamiento_hbqp WHERE idCategoria = ? AND $condicion";

$stmt = $conexion->prepare($query);
$stmt->bind_param('i', $idCategoria);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo "<p>No se encontraron registros para esta categoría.</p>";
    exit;
}

$mapTipos = [
    'Equipos Operativos' => 'Operativo',
    'Equipos Con Observaciones' => 'OperativoObs',
    'Equipos en Mantenimiento' => 'En Mantenimiento',
    'Equipos Fuera de Servicio' => 'Fuera de Servicio',
    'Equipos Extraviados' => 'Extraviado'
];

$tipoBuscar = $mapTipos[$tipo] ?? '';

// Comenzar a capturar salida
ob_start();
?>

<div class="row mb-2 justify-content-center">
    <div class="col-xl-4 col-lg-4 col-md-6 col-12">
        <button
                class="btn btn-block btn-success btn-sm btn-exportar-cambios"
                data-idcategoria="<?= $idCategoria ?>"
                data-tipo="<?= h($tipoBuscar) ?>"
        >
            <i class="fas fa-file-excel"></i> Exportar
        </button>
    </div>
</div>

<script>
    $(document).on("click", ".btn-exportar-cambios", function () {

        let idCategoria = $(this).data("idcategoria");
        let tipo = $(this).data("tipo");

        let form = $('<form>', {
            action: '../sdo-pdf/SDO-Reporte-Equipamiento-Historial-Cambios.php',
            method: 'POST',
            target: '_blank'
        });

        form.append($('<input>', {
            type: 'hidden',
            name: 'idCategoria',
            value: idCategoria
        }));

        form.append($('<input>', {
            type: 'hidden',
            name: 'tipo',
            value: tipo
        }));

        $('body').append(form);
        form.submit();
        form.remove();
    });
</script>

<hr>

<table id="tablaDetalleEquipos" class="table table-sm table-bordered table-striped" style="font-size: 10pt;">
    <thead class="bg-secondary text-white">
    <tr>
        <th>Equipo</th>
        <th>Marca</th>
        <th>Modelo</th>
        <th>Ubicación</th>
        <th>Observacion</th>
        <th>Visualizar</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($row = $res->fetch_assoc()) {
        $idEquipo = $row['id'];
        $tipoMenu = $row['tipoMenu'];

        if (empty($tipoMenu)) {
            $tipoMenu = 'antiguo';
        }

        // Ejecutar directamente la consulta de observaciones
        $sqlObservaciones = "
        SELECT fecha, observaciones, idUsuarioCambia, tipoUsuario 
        FROM historial_cambios_equipos 
        WHERE idEquipo = $idEquipo AND $condicion2 
        ORDER BY fecha DESC LIMIT 1
    ";

        $resObs = $conexion->query($sqlObservaciones);
        $obsHtml = '<em class="text-muted">Sin registro</em>';

        if ($resObs && $obs = $resObs->fetch_assoc()) {
            $fecha = date('d-m-Y', strtotime($obs['fecha']));
            $observacion = htmlspecialchars($obs['observaciones']);
            $idUsuarioCambia = (int)$obs['idUsuarioCambia'];
            $tipoUsuario = htmlspecialchars($obs['tipoUsuario']);

            $obtenerNombreUsuario = obtenerNombreUsuario($tipoUsuario, $idUsuarioCambia);

            $obsHtml = "<strong>Fecha: $fecha</strong><br><strong>Usuario: $obtenerNombreUsuario</strong><br><span><strong>Observación: </strong>$observacion</span>";
        }
        ?>
        <tr>
            <td class="align-middle"><?= mb_strtoupper(htmlspecialchars($row['nombreEquipo']), 'UTF-8') ?></td>
            <td class="align-middle"><?= mb_strtoupper(htmlspecialchars($row['marca']), 'UTF-8') ?></td>
            <td class="align-middle"><?= mb_strtoupper(htmlspecialchars($row['modelo']), 'UTF-8') ?></td>
            <td class="align-middle"><?= mb_strtoupper(htmlspecialchars($row['recinto']), 'UTF-8') ?></td>
            <td class="align-middle"><?= mb_strtoupper($obsHtml); ?></td>
            <td class="align-middle">
                <button class="btn btn-block btn-sm btn-primary btn-infoEquipo"
                        data-idequipoinfo="<?= $row['id'] ?>"
                        data-idcategoriainfo="<?= $idCategoria ?>"
                        data-tipomenu="<?= $tipoMenu ?>">
                    <i class="fas fa-eye"></i>
                </button>
            </td>
        </tr>

    <?php } ?>
    </tbody>

    <tfoot class="bg-secondary text-white">
    <tr>
        <th>Equipo</th>
        <th>Marca</th>
        <th>Modelo</th>
        <th>Ubicación</th>
        <th>Observacion</th>
        <th>Visualizar</th>
    </tr>
    </tfoot>
</table>

<script>
    $('#tablaDetalleEquipos').DataTable({
        lengthChange: false,
        searching: true,
        autoWidth: false,
        pageLength: 7,
        responsive: true,
        ordering: false,
        processing: true,
        language: {
            lengthMenu: "Visualizando _MENU_ Registros por Página",
            zeroRecords: "No se han encontrado Registros.",
            info: "Mostrando página _PAGE_ de _PAGES_",
            infoEmpty: "No existen Registros.",
            infoFiltered: "(Filtrado desde _MAX_ Registros Totales)",
            search: "Buscar:",
            processing: "Procesando...",
            paginate: {
                previous: "Anterior",
                next: "Siguiente"
            }
        }
    });
</script>

<?php
// Fin del buffer, enviar contenido al cliente
$html = ob_get_clean();
echo $html;
?>
