<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etiqueta Equipo Retirado - Generador</title>

    <!-- Librería para convertir HTML a Imagen -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f4f4f4;
            margin: 0;
        }

        /* Contenedor principal de la etiqueta */
        .etiqueta-container {
            width: 15cm;
            height: 7cm;
            background-color: #ffffff;
            border: 4px solid #006BB9;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 20px;
            box-sizing: border-box;
            text-align: center;
        }

        .header-alerta {
            border-bottom: 2px dashed #ccc;
            padding-bottom: 10px;
        }

        .titulo-rojo {
            color: #006BB9;
            font-size: 28px;
            font-weight: 900;
            text-transform: uppercase;
            margin: 0 0 5px 0;
            letter-spacing: 1px;
        }

        .sub-alerta {
            color: #212121;
            font-size: 20px;
            font-weight: bold;
            margin: 0;
        }

        /* Sección del Logo - Volvemos a columna para ordenarlos verticalmente */
        .footer-logo {
            display: flex;
            flex-direction: column; /* Cambiado a columna para ordenar arriba/abajo */
            justify-content: center;
            align-items: center;
            gap: 10px; /* Un poco menos de gap en columna */
            margin-top: 10px;
        }

        .logo-img {
            max-width: 120px;
            height: auto;
        }

        /* Estilos del Botón */
        .controles {
            margin-top: 30px;
        }

        #btnDescargar {
            padding: 12px 25px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            background-color: #006BB9;
            color: white;
            border: none;
            border-radius: 5px;
            transition: background 0.3s;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        #btnDescargar:hover {
            background-color: #004a80;
        }

        /* Reglas de impresión: Ocultar botón al imprimir */
        @media print {
            .controles {
                display: none;
            }
            body {
                background-color: #fff;
            }
            .etiqueta-container {
                box-shadow: none;
                border: 4px solid #006BB9 !important; /* Asegurar color en impresión */
            }
        }
    </style>
</head>
<body>

<!-- Area que se convertirá en imagen -->
<div id="miEtiqueta" class="etiqueta-container">
    <div class="header-alerta">
        <h1 class="titulo-rojo">Equipos Retirados de Unidad de Diálisis</h1
    </div>

    <!-- En el HTML, el orden determina cuál va arriba. Trabajando va primero. -->
    <div class="footer-logo">
        <!-- Asegúrate de que estas rutas sean correctas en tu servidor -->
        <img src="../dist/img/controlCent-HBQP.png" alt="Logo HBQP" class="logo-img">
        <img src="../dist/img/trabajando2.png" alt="Icono Trabajo" class="logo-img">
    </div>
</div>

<!-- Botón de acción -->
<div class="controles">
    <button id="btnDescargar">Descargar Etiqueta en Alta Definición (PNG)</button>
</div>

<script>
    document.getElementById('btnDescargar').addEventListener('click', function() {
        const etiqueta = document.getElementById('miEtiqueta');

        // Cambiamos el cursor para indicar que está procesando
        this.innerText = 'Generando imagen...';
        this.style.opacity = '0.7';

        html2canvas(etiqueta, {
            scale: 4,            // Alta resolución (4x tamaño original)
            useCORS: true,       // Para permitir imágenes de otros dominios/rutas
            backgroundColor: "#ffffff" // Asegura fondo blanco puro
        }).then(canvas => {
            // Crear el enlace de descarga
            const link = document.createElement('a');
            link.download = 'Etiqueta_Equipo_Retirado.png';
            link.href = canvas.toDataURL('image/png', 1.0);
            link.click();

            // Restaurar botón
            this.innerText = 'Descargar Etiqueta en Alta Definición (PNG)';
            this.style.opacity = '1';
        }).catch(err => {
            console.error('Error al generar la imagen:', err);
            alert('Hubo un error al generar la imagen. Revisa la consola.');
            this.innerText = 'Descargar Etiqueta en Alta Definición (PNG)';
            this.style.opacity = '1';
        });
    });
</script>

</body>
</html>