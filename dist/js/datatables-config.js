// Configuración base de DataTables reutilizable y adaptable
const dataTableConfig = {
    paging: true,
    lengthChange: false, // Permitir cambiar el número de filas ayuda en volúmenes grandes
    searching: true,
    ordering: false,
    info: true,
    autoWidth: false,
    responsive: true,
    deferRender: true,   // Crucial: No satura el DOM del navegador con miles de nodos de golpe
    language: {
        lengthMenu: "Mostrar _MENU_ registros",
        zeroRecords: "No se encontraron registros.",
        info: "Mostrando _START_ a _END_ de _TOTAL_ mediciones",
        infoEmpty: "No existen registros.",
        search: "Buscar:",
        infoFiltered: "(Filtrado de _MAX_ registros totales)",
        processing: '<i class="fas fa-sync fa-spin text-primary"></i> Procesando datos...',
        paginate: {
            previous: "Anterior",
            next: "Siguiente"
        }
    }
};