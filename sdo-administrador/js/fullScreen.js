var fullscreenButton = document.getElementById('fullscreenButton');

fullscreenButton.addEventListener('click', function() {
    enterFullScreen();
});

// Verificar si la pantalla completa está activa al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    var isFullScreen = localStorage.getItem('fullscreen');
    if (isFullScreen) {
        enterFullScreen();
    }
});

// Detectar cambios en el estado de pantalla completa
document.addEventListener('fullscreenchange', handleFullscreenChange);
document.addEventListener('webkitfullscreenchange', handleFullscreenChange);
document.addEventListener('mozfullscreenchange', handleFullscreenChange);
document.addEventListener('MSFullscreenChange', handleFullscreenChange);

function enterFullScreen() {
    var element = document.documentElement;

    if (element.requestFullscreen) {
        element.requestFullscreen().catch(function(error) {
            console.log('Error al solicitar pantalla completa:', error);
        });
    } else if (element.mozRequestFullScreen) { // Firefox
        element.mozRequestFullScreen();
    } else if (element.webkitRequestFullscreen) { // Chrome, Safari and Opera
        element.webkitRequestFullscreen();
    } else if (element.msRequestFullscreen) { // IE/Edge
        element.msRequestFullscreen();
    }

    // Almacenar el indicador en el almacenamiento local
    localStorage.setItem('fullscreen', 'true');
}

function handleFullscreenChange() {
    if (
        document.fullscreenElement ||
        document.webkitFullscreenElement ||
        document.mozFullScreenElement ||
        document.msFullscreenElement
    ) {
        // La pantalla completa está activa, almacenar el indicador en el almacenamiento local
        localStorage.setItem('fullscreen', 'true');
    } else {
        // La pantalla completa se ha desactivado, eliminar el indicador del almacenamiento local
        localStorage.removeItem('fullscreen');
    }
}