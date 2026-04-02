document.addEventListener("DOMContentLoaded", () => {
    const $codigo = document.querySelector("#codigoBarra1");
    $codigo.addEventListener("keydown", evento => {
        if (evento.keyCode === 13) {
            // El lector ya terminó de leer
            const codigoDeBarras = $codigo.value;
            // Aquí ya podemos hacer algo con el código. Yo solo lo imprimiré
            console.log("Tenemos un código de barras:");
            console.log(codigoDeBarras);
            // Limpiar el campo
            $codigo.value = codigoDeBarras;
        }
    });


    const $example1_wrapper = document.querySelector("#example1_wrapper");
    $example1_wrapper.addEventListener("keydown", evento => {
        if (evento.keyCode === 13) {
            // El lector ya terminó de leer
            const codigoDeBarras1 = $example1_wrapper.value;
            // Aquí ya podemos hacer algo con el código. Yo solo lo imprimiré
            console.log("Tenemos un código de barras:");
            console.log(codigoDeBarras);
            // Limpiar el campo
            $example1_wrapper.value = codigoDeBarras1;
        }
    });

    const $codigo2 = document.querySelector("#codigoBarra3");
    $codigo2.addEventListener("keydown", evento => {
        if (evento.keyCode === 13) {
            // El lector ya terminó de leer
            const codigoDeBarras2 = $codigo2.value;
            // Aquí ya podemos hacer algo con el código. Yo solo lo imprimiré
            console.log("Tenemos un código de barras:");
            console.log(codigoDeBarras);
            // Limpiar el campo
            $codigo2.value = codigoDeBarras2;
        }
    });

    const $codigo3 = document.querySelector("#codigoBarra4");
    $codigo3.addEventListener("keydown", evento => {
        if (evento.keyCode === 13) {
            // El lector ya terminó de leer
            const codigoDeBarras3 = $codigo3.value;
            // Aquí ya podemos hacer algo con el código. Yo solo lo imprimiré
            console.log("Tenemos un código de barras:");
            console.log(codigoDeBarras);
            // Limpiar el campo
            $codigo3.value = codigoDeBarras3;
        }
    });

    const $codigo4 = document.querySelector("#codigoBarra5");
    $codigo4.addEventListener("keydown", evento => {
        if (evento.keyCode === 13) {
            // El lector ya terminó de leer
            const codigoDeBarras3 = $codigo4.value;
            // Aquí ya podemos hacer algo con el código. Yo solo lo imprimiré
            console.log("Tenemos un código de barras:");
            console.log(codigoDeBarras);
            // Limpiar el campo
            $codigo4.value = codigoDeBarras3;
        }
    });

    const $codigoBarraMate = document.querySelector("#codigoBarraMate");
    $codigoBarraMate.addEventListener("keydown", evento => {
        if (evento.keyCode === 13) {
            // El lector ya terminó de leer
            const codBarras = $codigoBarraMate.value;
            // Aquí ya podemos hacer algo con el código. Yo solo lo imprimiré
            console.log("Tenemos un código de barras:");
            console.log(codigoDeBarras);
            // Limpiar el campo
            $codigoBarraMate.value = codBarras;
        }
    });

    
});