<?php
// Verificamos si se reciben datos del formulario mediante POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificamos que los datos del formulario estén presentes
    if (isset($_POST["nombre"]) && isset($_POST["email"])) {
        // Recibimos los datos del formulario
        $nombre = $_POST["nombre"];
        $email = $_POST["email"];

        // Aquí puedes hacer lo que necesites con los datos recibidos, como por ejemplo guardarlos en una base de datos

        // Enviamos una respuesta al cliente
        echo "¡Gracias por enviar el formulario, $nombre! Tu email es: $email";
    } else {
        // Si faltan datos del formulario, enviamos un mensaje de error
        echo "Error: Faltan datos del formulario";
    }
} else {
    // Si la solicitud no es de tipo POST, enviamos un mensaje de error
    echo "Error: La solicitud debe ser de tipo POST";
}
?>