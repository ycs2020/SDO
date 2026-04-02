document.addEventListener('DOMContentLoaded', function() {

    //Variables
    const id = document.getElementById('id');
    const nombreEquipo = document.getElementById('nombreEquipo');
    const marcaEquipo = document.getElementById('marcaEquipo');
    const modeloEquipo = document.getElementById('modeloEquipo');
    const guardar = document.getElementById('guardar');


    const datosAComprobar = {
        id: '',
        nombreEquipo: '',
        marcaEquipo: '',
        modeloEquipo: ''
    }

    //Eventos
    id.addEventListener('input', validar);
    nombreEquipo.addEventListener('input', validar);
    marcaEquipo.addEventListener('input', validar);
    modeloEquipo.addEventListener('input', validar);

    diaFechaRecepcion.addEventListener('change', validaFecha);
    mesFechaRecepcion.addEventListener('change', validaFecha);
    anhoFechaRecepcion.addEventListener('change', validaFecha);

    //Funciones
    function validar(e) {
        if (e.target.value.trim() === '') {
            console.log('esta vacio');
            datosAComprobar[e.target.name] = '';
            comprobarObjeto();
            return
        }

        datosAComprobar[e.target.name] = e.target.value.trim().toLowerCase();

        comprobarObjeto();
    }

    function comprobarObjeto() {
        if (Object.values(datosAComprobar).includes('')) {
            guardar.disabled = true;
        } else {
            guardar.disabled = false;
        }
    }

})