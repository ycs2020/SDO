document.addEventListener('DOMContentLoaded', function () {
    // Set the date for Christmas
    const christmasDate = new Date('December 25, 2023 00:00:00').getTime();

    // Update the countdown every second
    const countdownElement = document.getElementById('countdown');
    const countdownContainer = document.getElementById('countdown-container');
    const countdownInterval = setInterval(function () {
        const currentDate = new Date().getTime();
        const timeDifference = christmasDate - currentDate;

        if (timeDifference >= 0) {
            const days = Math.floor(timeDifference / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeDifference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeDifference % (1000 * 60)) / 1000);

            countdownElement.innerHTML = `Faltan ${days}d ${hours}h ${minutes}m ${seconds}s para Navidad!!!`;
        } else {
            clearInterval(countdownInterval);
            countdownContainer.innerHTML = '<h1>¡Feliz Navidad!</h1>';
        }
    }, 1000);
});
