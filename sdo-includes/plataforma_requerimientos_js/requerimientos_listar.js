$(document).ready(function() {
    // Verificamos que la tabla exista en el DOM
    if ($('#tablaRequerimientos').length > 0) {

        // Creamos la nueva configuración extendiendo la base
        const configRequerimientos = {
            ...dataTableConfig, // Hereda: language, info, responsive, etc.
            serverSide: true,    // Habilita procesamiento en servidor
            processing: true,    // Muestra el indicador de "Cargando..."
            ordering: false,      // Permite ordenar (aunque tu base diga false, aquí es útil)
            ajax: {
                url: "../sdo-includes/plataforma_requerimientos_funciones/requerimientos_listar_solicitud.php",
                type: "POST"
            },
            columns: [
                { "data": "id_requerimiento" },
                {
                    "data": "fecha_solicitud_format",
                    "render": function(data) {
                        return data ? data.split(' ')[0] : '---';
                    }
                },
                { "data": "tipo_solicitud" },
                { "data": "usuario_solicita" },
                { "data": "asunto" },
                { "data": "estado_html" },
                { "data": "boton_accion" },
            ],
            // Forzamos el orden por ID descendente (el más nuevo arriba)
            order: [[1, "desc"]]
        };

        // Inicializar la tabla
        $('#tablaRequerimientos').DataTable(configRequerimientos);
    }
});

/**
 * Función para ver el historial (Tabla sdo_observaciones)
 */
function verDetalle(id) {
    // Aquí puedes llamar a un Modal de Bootstrap y cargar
    // las observaciones mediante otro AJAX usando ese ID.
    console.log("Cargando historial para el ID: " + id);
}