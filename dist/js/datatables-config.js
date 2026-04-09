// Configuración base de DataTables reutilizable
const dataTableConfig = {
    paging: true,
    lengthChange: false,
    searching: true,
    ordering: false,
    info: true,
    autoWidth: false,
    responsive: true,
    language: {
        lengthMenu: "Visualizando _MENU_ Registros",
        zeroRecords: "No se encontraron registros.",
        info: "Página _PAGE_ de _PAGES_",
        infoEmpty: "No existen registros.",
        search: "Buscar:",
        infoFiltered: "(Filtrado de _MAX_ registros)",
        paginate: {
            previous: "Anterior",
            next: "Siguiente"
        }
    }
};