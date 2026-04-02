<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticias de Chile</title>
    <style>
        /* Agrega estilos CSS según sea necesario */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h2 {
            color: #333;
        }

        .noticia {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<h1>Últimas noticias de Chile</h1>

<div id="noticias-container"></div>

<script>
    async function cargarNoticias() {
        try {
            const apiKey = '4e8d8ebd25404a5baf93ed4235fc930b'; // Reemplaza con tu clave API de NewsAPI
            const response = await fetch(`https://newsapi.org/v2/top-headlines?country=cl&apiKey=${apiKey}`);
            const data = await response.json();

            const contenedorNoticias = document.getElementById('noticias-container');

            data.articles.forEach(article => {
                const titulo = article.title;
                const enlace = article.url;

                const divNoticia = document.createElement('div');
                divNoticia.className = 'noticia';

                const h2 = document.createElement('h2');
                h2.textContent = titulo;

                const p = document.createElement('p');
                p.textContent = `Enlace: ${enlace}`;

                divNoticia.appendChild(h2);
                divNoticia.appendChild(p);

                contenedorNoticias.appendChild(divNoticia);
            });
        } catch (error) {
            console.error('Error al cargar noticias:', error);
        }
    }

    cargarNoticias();
</script>

</body>
</html>
