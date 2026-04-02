<?php
// Datos del formulario y archivo adjunto
$destinatario = 'destinatario@example.com';
$asunto = 'Correo con archivo adjunto';
$mensaje = 'Este es un correo con un archivo adjunto.';
$archivo = '/ruta/al/archivo.pdf'; // Ruta completa al archivo que se va a adjuntar
$nombreArchivo = 'archivo.pdf'; // Nombre con el que se enviará el archivo adjunto

// Encabezados para el correo
$headers = "From: remitente@example.com\r\n";
$headers .= "Reply-To: remitente@example.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/mixed; boundary=\"boundary\"\r\n";

// Contenido del correo
$body = "--boundary\r\n";
$body .= "Content-Type: text/plain; charset=UTF-8\r\n";
$body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
$body .= $mensaje . "\r\n\r\n";

// Adjuntar archivo
if (file_exists($archivo)) {
    $contenidoArchivo = file_get_contents($archivo);
    $body .= "--boundary\r\n";
    $body .= "Content-Type: application/pdf; name=\"" . $nombreArchivo . "\"\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n";
    $body .= "Content-Disposition: attachment; filename=\"" . $nombreArchivo . "\"\r\n\r\n";
    $body .= chunk_split(base64_encode($contenidoArchivo)) . "\r\n";
}

// Envío del correo
if (mail($destinatario, $asunto, $body, $headers)) {
    echo "El correo con el archivo adjunto se ha enviado correctamente.";
} else {
    echo "Error al enviar el correo.";
}
?>
