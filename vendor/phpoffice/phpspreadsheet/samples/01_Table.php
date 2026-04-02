<?php

require '..vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IO\Word2007;

// Crear un nuevo objeto PhpWord
$phpWord = new PhpWord();

// Agregar una sección al documento
$section = $phpWord->addSection();

// Agregar una tabla
$table = $section->addTable();

// Datos de ejemplo para la tabla
$data = array(
    array('Nombre', 'Edad', 'Ciudad'),
    array('Juan', '25', 'Ciudad A'),
    array('María', '30', 'Ciudad B'),
    array('Carlos', '22', 'Ciudad C'),
);

// Llenar la tabla con datos
foreach ($data as $row) {
    $table->addRow();
    foreach ($row as $cell) {
        $table->addCell()->addText($cell);
    }
}

// Guardar el documento como un archivo Word
$objWriter = new Word2007($phpWord);
$objWriter->save('tabla_word_export.docx');

echo 'Documento Word exportado correctamente.';
?>
