<?php
$anios = [];
$vacio = true;

if (isset($conexion)) {
    // CAMBIO AQUÍ: Cambiamos DESC por ASC para ordenar de más antiguo a más nuevo
    $queryAnios = "SELECT DISTINCT YEAR(fechaOrden) AS anio 
                   FROM ordenes_trabajos_general 
                   WHERE fechaOrden IS NOT NULL AND fechaOrden != '0000-00-00'
                   ORDER BY anio ASC";

    if ($stmt = $conexion->prepare($queryAnios)) {
        $stmt->execute();
        $resultado = $stmt->get_result();
        while ($row = $resultado->fetch_assoc()) {
            if (!empty($row['anio'])) {
                $anios[] = $row['anio'];
                $vacio = false;
            }
        }
        $stmt->close();
    }
}

$anioActual = date('Y');
$anioPorDefecto = $anioActual;

if (!$vacio) {
    // Si el año actual (2026) no está en el array, activará el primero que devuelva la BD (el más antiguo debido al ASC)
    if (!in_array($anioActual, $anios)) {
        $anioPorDefecto = $anios[0];
    }
}
?>

<div class="card card-primary card-outline shadow-sm border-0">
    <?php if ($vacio) { ?>
        <div class="card-body text-center p-5">
            <div class="text-muted mb-3"><i class="fas fa-folder-open fa-4x text-secondary"></i></div>
            <h5 class="text-secondary font-weight-bold">Sin Información</h5>
            <p class="text-muted mb-0">No se encontraron órdenes de trabajo registradas en el sistema.</p>
        </div>
    <?php } else { ?>
        <div class="card-header bg-white border-bottom-0 pt-3 pb-2">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <div class="mb-2">
                    <h6 class="text-muted text-uppercase font-weight-bold mb-0" style="font-size: 11px; letter-spacing: 1px;">
                        <i class="fas fa-filter mr-1 text-primary"></i> Seleccionar Período
                    </h6>
                </div>
                <div>
                    <ul class="nav nav-pills custom-pills" id="subTabAnios" role="tablist">
                        <?php
                        foreach ($anios as $anio) {
                            $claseActiva = ($anio == $anioPorDefecto) ? 'active shadow-sm' : 'bg-light text-secondary';
                            ?>
                            <li class="nav-item mx-1">
                                <a class="nav-link nav-anios-ajax font-weight-bold px-3 <?php echo $claseActiva; ?>"
                                    data-toggle="tab"
                                    href="#contenedor-tabla-ajax"
                                    data-anio="<?php echo $anio; ?>"
                                    role="tab"
                                    style="border-radius: 8px; font-size: 14px; transition: all 0.2s;">
                                    <i class="far fa-calendar-alt mr-1"></i> <?php echo $anio; ?>
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card-body pt-0">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="contenedor-tabla-ajax" role="tabpanel">
                    <div id="loading-dashboard" class="text-center p-5 d-none">
                        <div class="spinner-border text-primary" role="status" style="width: 2.5rem; height: 2.5rem;"></div>
                        <p class="text-muted font-weight-light mt-3 mb-0" style="font-size: 15px;">Procesando datos del período...</p>
                    </div>
                    <div id="contenido-tabla-dinamica">
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<script>
    $(document).ready(function() {
        function cargarTablaAnio(anio) {
            $('#contenido-tabla-dinamica').html('');
            $('#loading-dashboard').removeClass('d-none');

            $.ajax({
                url: '../sdo-includes/dashboard/ht_dashboard_datos.php',
                type: 'POST',
                data: {
                    anio: anio,
                    tipoUsuarioVer: '<?php echo $tipoUsuarioVer; ?>',
                    nomDepartamento: '<?php echo $nomDepartamento; ?>'
                },
                success: function(respuesta) {
                    $('#loading-dashboard').addClass('d-none');
                    $('#contenido-tabla-dinamica').html(respuesta);
                },
                error: function() {
                    $('#loading-dashboard').addClass('d-none');
                    $('#contenido-tabla-dinamica').html('<div class="alert alert-warning text-center rounded border-0 shadow-sm"><i class="fas fa-exclamation-triangle mr-2"></i> Error al conectar con el servidor. Por favor, reintente.</div>');
                }
            });
        }

        var anioInicial = $('.nav-anios-ajax.active').data('anio');
        if(anioInicial) {
            cargarTablaAnio(anioInicial);
        }

        $('.nav-anios-ajax').on('shown.bs.tab', function(e) {
            // Estilo visual dinámico para los botones al hacer click
            $('.nav-anios-ajax').removeClass('shadow-sm').addClass('bg-light text-secondary');
            $(this).removeClass('bg-light text-secondary').addClass('active shadow-sm');

            var anioSeleccionado = $(this).data('anio');
            cargarTablaAnio(anioSeleccionado);
        });
    });
</script>