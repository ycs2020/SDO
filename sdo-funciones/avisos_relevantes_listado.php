<?php
include_once 'conexion.php';
include_once 'funciones.php';

$sqlAvisos = "SELECT * FROM avisos_relevantes ORDER BY fechaAviso DESC";
$resAvisos = mysqli_query($conexion, $sqlAvisos);

if (mysqli_num_rows($resAvisos) > 0) {
    while ($rowAvisos = $resAvisos->fetch_assoc()) {
        $idAviso = $rowAvisos['idAviso'];
        $tituloAviso = $rowAvisos['tituloAviso'];
        $descripcionAviso = $rowAvisos['descripcionAviso'];
        $fechaAviso = $rowAvisos['fechaAviso'];
        $departamentoAviso = $rowAvisos['departamentoAviso'];
        $destinatarioAviso = $rowAvisos['destinatarioAviso'];
        $creadorAviso = $rowAvisos['creadorAviso'];

        $fechaTime = strtotime($fechaAviso);
        $fechaFormato = fechaEspañol2($fechaTime);

        $horaFormato = date('H:i', $fechaTime);

        $fechaCompleta = $fechaFormato . ' a las ' . $horaFormato . ' hrs.';

        ?>
        <div class="col-xl-2 col-lg-3 col-md-4 col-12 mb-3">
            <button class="btn w-100 h-100 abrirModalAviso"
                    style="background-color: #fff475; box-shadow: 5px 5px 10px rgba(0,0,0,0.3);"
                    data-idaviso="<?php echo $idAviso; ?>"
                    data-toggle="modal"
                    data-target="#modalAvisoUnico">
                <i class="fas fa-thumbtack"></i><br><br>
                <b><?php echo $fechaCompleta; ?></b><br><br>
                <b><?php echo $tituloAviso; ?></b>
            </button>
        </div>

        <?php
    }
    ?>
    <div class="modal fade" id="modalAvisoUnico" tabindex="-1" role="dialog" aria-labelledby="modalAvisoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="modalAvisoLabel">Cargando...</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="contenidoAviso">
                    <p>
                        <i class="fas fa-spinner fa-spin"></i> Cargando información...
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        <i class="fas fa-times"></i>&nbsp;&nbsp;Cerrar
                    </button>

                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('.abrirModalAviso').on('click', function () {
                const idAviso = $(this).data('idaviso');

                // Mostrar contenido de carga
                $('#modalAvisoLabel').text('Cargando...');
                $('#contenidoAviso').html('<p><i class="fas fa-spinner fa-spin"></i> Cargando información...</p>');

                // Llamada AJAX
                $.ajax({
                    url: 'avisos_cargar_datos.php',
                    type: 'POST',
                    data: { idAviso: idAviso },
                    dataType: 'json',
                    success: function (data) {
                        console.log('✅ Datos recibidos:', data);

                        if (data.error) {
                            $('#contenidoAviso').html(`<p class="text-danger">❌ ${data.error}</p>`);
                            $('#modalAvisoLabel').text('Error');
                        } else {
                            $('#modalAvisoLabel').text(data.titulo);
                            $('#contenidoAviso').html(`
                        <p><b>Descripción:</b> ${data.descripcion}</p>
                        <p><b>Fecha:</b> ${data.fecha}</p>
                        <p><b>Departamento:</b> ${data.departamento}</p>
                        <p><b>Creador:</b> ${data.creador}</p>
                    `);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('❌ Error AJAX:', status, error);
                        console.log('❌ Respuesta del servidor:', xhr.responseText);
                        $('#contenidoAviso').html('<p class="text-danger">❌ Error al cargar los datos del aviso.</p>');
                        $('#modalAvisoLabel').text('Error');
                    }
                });
            });
        });

    </script>
    <?php
} else {
    echo '<div class="col-12 text-center"><h4>No Existen Avisos Relevantes</h4></div>';
}
?>
