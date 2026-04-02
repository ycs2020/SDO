<?php
include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';

$idCarpeta = (int)($_POST['idCarpeta'] ?? 0);
if ($idCarpeta <= 0) {
    echo '<div class="alert alert-danger">ID inválido</div>';
    exit;
}

/* =========================
   1) RESUMEN (1 query)
   ========================= */
$sqlResumen = "
SELECT
  (SELECT COUNT(*) FROM equipamiento_hbqp WHERE idCategoria = ?) AS cantidadEquiposRegistrados,
  (SELECT COUNT(*) FROM equipamiento_hbqp WHERE idCategoria = ? AND estadoEquipo = 'Operativo') AS cuentaEquiposOperativos,
  (SELECT COUNT(*) FROM equipamiento_hbqp WHERE idCategoria = ? AND estadoEquipo = 'OperativoObs') AS cuentaEquiposConObservaciones,
  (SELECT COUNT(*) FROM equipamiento_hbqp WHERE idCategoria = ? AND estadoEquipo = 'En Mantenimiento') AS cantEquiposMantenimiento,
  (SELECT COUNT(*) FROM equipamiento_hbqp WHERE idCategoria = ? AND estadoEquipo = 'Fuera de Servicio') AS cantEquiposFalla,
  (SELECT COUNT(*) FROM equipamiento_hbqp WHERE idCategoria = ? AND estadoEquipo = 'Extraviado') AS cantEquiposExtraviados,
  (SELECT COUNT(*) FROM ordenes_trabajos_general WHERE idCategoria = ?) AS cantidadHojasTecnicas,
  (SELECT COALESCE(SUM(costoTotal),0) FROM gasto_equipo_carpeta WHERE idCategoria = ?) AS gastoTotal,
  (SELECT COUNT(*) FROM solicitudes_compra_sdo WHERE idCategoria = ?) AS cuentaSoliCompra,
  (SELECT COUNT(*) FROM archivos_equipamiento_hbqp WHERE idCategoria = ?) AS cuentaArchivos
";
$stmt = $conexion->prepare($sqlResumen);
$stmt->bind_param(
    "iiiiiiiiii",
    $idCarpeta,$idCarpeta,$idCarpeta,$idCarpeta,$idCarpeta,
    $idCarpeta,$idCarpeta,$idCarpeta,$idCarpeta,$idCarpeta
);
$stmt->execute();
$resumen = $stmt->get_result()->fetch_assoc();

$cantidadHojasTecnicas = (int)$resumen['cantidadHojasTecnicas'];
$gastoTotal            = (float)$resumen['gastoTotal'];
$cuentaSoliCompra       = (int)$resumen['cuentaSoliCompra'];
$cuentaArchivos         = (int)$resumen['cuentaArchivos'];

/* =========================
   2) MATERIALES POR TIPO (1 query)
   ========================= */
$tipos = ['MPI','MRI','MPE','MRE','OM','IS'];

$sqlMat = "
SELECT otg.tipoMantencion AS tipo,
       COALESCE(SUM(ie.valorUnitario),0) AS totalValor
FROM ordenes_trabajos_general otg
INNER JOIN material_orden_general mog ON mog.idOrden = otg.idOrden
INNER JOIN inventario_equipos ie ON ie.equipoInventario = mog.nomMaterial
WHERE otg.idCategoria = ?
  AND otg.tipoMantencion IN ('MPI','MRI','MPE','MRE','OM','IS')
GROUP BY otg.tipoMantencion
";
$stmtMat = $conexion->prepare($sqlMat);
$stmtMat->bind_param("i", $idCarpeta);
$stmtMat->execute();
$rMat = $stmtMat->get_result();

$materialesPorTipo = array_fill_keys($tipos, 0.0);
while ($m = $rMat->fetch_assoc()) {
    $materialesPorTipo[$m['tipo']] = (float)$m['totalValor'];
}

/* =========================
   3) BOTONES MANTENCIONES
   ========================= */
$botones = [
    "MPI" => ["bg-info",      "MANTENIMIENTO PREVENTIVO INTERNO"],
    "MRI" => ["bg-danger",    "MANTENIMIENTO REPARATIVO INTERNO"],
    "MPE" => ["bg-secondary", "MANTENIMIENTO PREVENTIVO EXTERNO"],
    "MRE" => ["bg-purple",    "MANTENIMIENTO REPARATIVO EXTERNO"],
    "OM"  => ["bg-primary",   "OBRAS MENORES"],
    "IS"  => ["bg-orange",    "INSPECCIÓN Y SUPERVISIÓN"]
];
?>

<div class="row">
    <?php
    foreach ($botones as $codigo => $info) {
        $color = $info[0];

        $totalMantenimiento = (int) obtieneCantidadTiposMantencion($idCarpeta, $codigo);
        $costoMantenimiento = (float) obtieneGastoTiposMantencion($idCarpeta, $codigo);

        if ($totalMantenimiento < 0) {
            continue;
        }

        $totalValorMateriales = $materialesPorTipo[$codigo] ?? 0;
        $totalFinal = $costoMantenimiento + $totalValorMateriales;
        ?>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
            <button class="btn btn-block btn-sm small-box <?= $color ?> btnAbrirMantencion"
                    data-tipomant="<?= $codigo ?>"
                    data-idcarpeta="<?= $idCarpeta ?>">

                <h5><i class="fas fa-file"></i> <?= $codigo ?></h5>
                Cantidad: <b><?= $totalMantenimiento ?></b><br>
                <?= ($totalFinal > 0 ? '$ '.number_format($totalFinal,0,',','.') : '$ 0') ?>

            </button>
        </div>
        <input type="hidden" id="costoMant<?= $codigo ?>" value="<?= $costoMantenimiento ?>">
        <?php
    }
    ?>
</div>

<div class="modal fade" id="modalMantenciones" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">

            <div class="modal-header bg-secondary">
                <h5 class="modal-title">
                    <i class="fas fa-tools"></i> Detalle Mantenciones
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div id="contenidoMantenciones" class="text-center p-4">

                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <br>
                    Cargando información...

                </div>

            </div>

        </div>
    </div>
</div>

<script>
    $(document).on("click", ".btnAbrirMantencion", function () {

        let tipo = $(this).data("tipomant");
        let idCarpeta = $(this).data("idcarpeta");

        console.log(tipo, idCarpeta);

        $("#modalMantenciones").modal("show");

        $("#contenidoMantenciones").html(
            '<div class="text-center p-4">'+
            '<i class="fas fa-spinner fa-spin fa-2x"></i><br>'+
            'Cargando información...'+
            '</div>'
        );

        $.ajax({
            url: "../sdo-includes/equipamiento_detalle_mantenimiento.php",
            type: "POST",
            data: {
                tipo: tipo,
                idCarpeta: idCarpeta
            },
            success: function (data) {

                $("#contenidoMantenciones").html(data);

            },
            error: function () {

                $("#contenidoMantenciones").html(
                    '<div class="alert alert-danger">Error al cargar información</div>'
                );

            }
        });

    });
</script>

<hr>

<?php
/* =========================
   4) BOTONES “OTROS” AGRUPADOS
   ========================= */
$cards = [];

// Tickets (solo si > 0)
$totalTicket = (int) obtieneCantidadTicketCarpeta($idCarpeta);
if ($totalTicket > 0) {
    $cards[] = [
        "type"   => "button",
        "class"  => "bg-yellow btn-abrirModal",
        "icon"   => "fas fa-ticket-alt",
        "title"  => "Tickets",
        "line1"  => "Cantidad: <b>{$totalTicket}</b>",
        "line2"  => "&nbsp;",
        "url"    => "../sdo-includes/equipamiento/equipamiento_tickets.php",
        "titulo" => "Listado de Tickets"
    ];
}

// Inventario
$inv = (int) obtenerInventarioEquipos($idCarpeta);
if ($inv >= 0) {
    $cards[] = [
        "type"   => "button",
        "class"  => "bg-secondary btn-abrirModal",
        "icon"   => "fas fa-desktop",
        "title"  => "Inventario",
        "line1"  => "Equipos",
        "line2"  => "&nbsp;",
        "url"    => "../sdo-includes/equipamiento/equipamiento_inventario.php",
        "titulo" => "Inventario de Equipos"
    ];
}

// HT
$cards[] = [
    "type"   => "form",
    "action" => "carpeta-hojas-tecnicas.php",
    "class"  => "bg-primary",
    "icon"   => "fas fa-file-signature",
    "title"  => "HT",
    "line1"  => "Hojas T&eacute;cnicas",
    "line2"  => "Cantidad: <b>{$cantidadHojasTecnicas}</b>"
];

// Gastos
$valorGasto = ($gastoTotal > 0 ? moneda_chilena($gastoTotal) : '$ 0');
$cards[] = [
    "type"   => "form",
    "action" => "carpeta-gastos.php",
    "class"  => "bg-secondary",
    "icon"   => "fas fa-money-bill",
    "title"  => "Gastos",
    "line1"  => "Valor:",
    "line2"  => "<b>{$valorGasto}</b>"
];

// SC
$cards[] = [
    "type"   => "form",
    "action" => "carpeta-solicitudes-compra.php",
    "class"  => "bg-danger",
    "icon"   => "fas fa-cart-plus",
    "title"  => "SC",
    "line1"  => "Solicitudes Compra:",
    "line2"  => "<b>{$cuentaSoliCompra}</b>"
];

// Archivos
$cards[] = [
    "type"   => "form",
    "action" => "carpeta-archivos.php",
    "class"  => "bg-pink",
    "icon"   => "fas fa-download",
    "title"  => "Archivos",
    "line1"  => "Cantidad:",
    "line2"  => "<b>{$cuentaArchivos}</b>"
];
?>

<div class="row">
    <?php foreach ($cards as $c): ?>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
            <?php if ($c["type"] === "form"): ?>
                <form action="<?= htmlspecialchars($c["action"], ENT_QUOTES, 'UTF-8') ?>" method="POST">
                    <input type="hidden" name="idCarpeta" value="<?= (int)$idCarpeta ?>">
                    <button type="submit" class="btn btn-block btn-sm small-box <?= htmlspecialchars($c["class"], ENT_QUOTES, 'UTF-8') ?>">
                        <h5><i class="<?= htmlspecialchars($c["icon"], ENT_QUOTES, 'UTF-8') ?>"></i> <?= $c["title"] ?></h5>
                        <?= $c["line1"] ?><br>
                        <?= $c["line2"] ?>
                    </button>
                </form>
            <?php else: ?>
                <button
                    type="button"
                    class="btn btn-block btn-sm small-box <?= htmlspecialchars($c["class"], ENT_QUOTES, 'UTF-8') ?>"
                    data-url="<?= htmlspecialchars($c["url"], ENT_QUOTES, 'UTF-8') ?>"
                    data-titulo="<?= htmlspecialchars($c["titulo"], ENT_QUOTES, 'UTF-8') ?>"
                    data-idcarpeta="<?= (int)$idCarpeta ?>">
                    <h5><i class="<?= htmlspecialchars($c["icon"], ENT_QUOTES, 'UTF-8') ?>"></i> <?= $c["title"] ?></h5>
                    <?= $c["line1"] ?><br>
                    <?= $c["line2"] ?>
                </button>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<div class="modal fade" id="modalGeneral" tabindex="-1" role="dialog" aria-labelledby="tituloModal" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="tituloModal">Detalle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" id="contenidoModal">
                <div class="text-center p-5">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).on('click', '.btn-abrirModal', function () {
        const url = $(this).data('url');
        const titulo = $(this).data('titulo');
        const idCarpeta = $(this).data('idcarpeta');

        $('#tituloModal').text(titulo);
        $('#modalGeneral').modal('show');

        $('#contenidoModal').html(`
        <div class="text-center p-5">
            <i class="fas fa-spinner fa-spin fa-2x"></i>
        </div>
    `);

        $.ajax({
            url: url,
            type: 'POST',
            data: { idCarpeta: idCarpeta },
            success: function (response) {
                $('#contenidoModal').html(response);
            },
            error: function () {
                $('#contenidoModal').html(`
                <div class="alert alert-danger mb-0">
                    Error al cargar la información.
                </div>
            `);
            }
        });
    });
</script>