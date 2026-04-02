document.addEventListener("DOMContentLoaded", function() {
    const fireworksContainer = document.querySelector(".fireworks-container");

    function createFirework() {
        const firework = document.createElement("div");
        firework.classList.add("firework");
        fireworksContainer.appendChild(firework);

        const randomX = Math.random() * window.innerWidth;
        const randomY = Math.random() * window.innerHeight;

        firework.style.left = `${randomX}px`;
        firework.style.top = `${randomY}px`;

        setTimeout(() => {
            firework.remove();
        }, 2000);
    }

    function launchFireworks() {
        setInterval(createFirework, 500);
    }

    launchFireworks();

    // Establece la fecha límite (1 de enero del próximo año)
    const fechaLimite = new Date("January 1, " + (new Date().getFullYear() + 1) + " 00:00:00").getTime();

    // Actualiza la cuenta regresiva cada segundo
    const intervalo = setInterval(function() {
        const ahora = new Date().getTime();
        const diferencia = fechaLimite - ahora;

        const dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));
        const horas = Math.floor((diferencia % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutos = Math.floor((diferencia % (1000 * 60 * 60)) / (1000 * 60));
        const segundos = Math.floor((diferencia % (1000 * 60)) / 1000);

        // Actualiza el contenido del elemento countdown
        document.getElementById("countdown").innerHTML = `${dias}d ${horas}h ${minutos}m ${segundos}s`;

        // Si la cuenta regresiva ha terminado, detén el intervalo
        if (diferencia < 0) {
            clearInterval(intervalo);
            document.getElementById("countdown").innerHTML = "¡Feliz Año Nuevo!";
        }
    }, 1000); // Actualiza cada segundo


});
