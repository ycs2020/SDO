<?php
require '../vendor/autoload.php'; // Asegúrate de ajustar la ruta según tu configuración

use PhpOffice\PhpWord\Element;
use PhpOffice\PhpWord\IOFactory;

// Crear un nuevo objeto phpword
$phpWord = new PhpOffice\PhpWord\PhpWord();

// Agregar una sección al documento
$section = $phpWord->addSection();

// Agregar texto a la sección
$section->addText('¡Hola, mundo!');


// Guardar el documento en un archivo
$objWriter = IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('documento_word.docx');// Descargar el documento
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment;filename="documento_word.docx"');
$objWriter->save('php://output');
exit();
?>