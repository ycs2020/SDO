// Ejecutar al cargar la página
document.addEventListener('DOMContentLoaded', () => {
    // Buscar valor en localStorage, si no existe, poner en 'off'
    let darkMode = localStorage.getItem('darkMode') || 'off';
    // Obtener el checkbox
    let checkDark = document.querySelector('#customSwitch3');
    // Marcar checkbox y aplicar estilo a body si darkMode = 'on'
    if (darkMode == 'on') {
        checkDark.checked = true;
        document.body.classList.toggle('dark-mode');
    }
    // Escuchar cambios en checkbox
    checkDark.addEventListener('change', e => {
        // Cambiar estilo a body
        document.body.classList.toggle('dark-mode');
        // Actualizar variable de acuerdo a estado del checkbox
        darkMode = (checkDark.checked) ? 'on' : 'off';
        // Guardar variable en localStorage
        localStorage.setItem('darkMode', darkMode);
    });
});