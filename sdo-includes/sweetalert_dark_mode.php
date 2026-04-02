<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const avisosActas = <?php echo json_encode($notificacionesSweet, JSON_UNESCAPED_UNICODE); ?>;
        const urlParams = new URLSearchParams(window.location.search);
        const clave = Object.keys(avisosActas).find(k => urlParams.has(k));

        if (clave) {
            const [mensaje, tipo] = avisosActas[clave];

            // Detectar dark mode por clase CSS
            const esDarkMode = document.body.classList.contains('dark-mode') || document.documentElement.classList.contains('dark-mode');

            // Estilos según modo
            const bgColor = esDarkMode ? '#1e1e2f' : '#ffffff';
            const textColor = esDarkMode ? '#f0f0f0' : '#000000';
            const iconColor = tipo === 'success' ? '#28a745' : '#dc3545';

            Swal.fire({
                title: tipo === 'success' ? 'Éxito' : 'Error',
                html: mensaje,
                icon: tipo,
                confirmButtonText: 'Aceptar',
                background: bgColor,
                color: textColor,
                iconColor: iconColor
            });

            // Limpiar la URL
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    });
</script>