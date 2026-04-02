<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form action="../tu_script_php.php" method="post">
    <input type="text" id="buscador" placeholder="Buscar">
</form>

<table id="tabla_resultados">
    <thead>
    <tr>
        <th>Columna 1</th>
        <th>Columna 2</th>
        <!-- Agrega más columnas según tus necesidades -->
    </tr>
    </thead>
    <tbody>
    <!-- Los resultados se agregarán aquí -->
    </tbody>
</table>
</body>
</html>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const buscador = document.getElementById("buscador");
        const tablaResultados = document.getElementById("tabla_resultados");

        buscador.addEventListener("input", function () {
            const busqueda = buscador.value.toLowerCase();
            const filas = tablaResultados.getElementsByTagName("tr");

            for (let i = 1; i < filas.length; i++) { // Empezamos desde 1 para omitir la fila de encabezado
                const fila = filas[i];
                const textoFila = fila.textContent.toLowerCase();

                if (textoFila.includes(busqueda)) {
                    fila.style.display = "table-row";
                } else {
                    fila.style.display = "none";
                }
            }
        });
    });
</script>