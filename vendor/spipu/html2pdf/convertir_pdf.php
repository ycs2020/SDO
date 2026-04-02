<?php
require_once('html2pdf.class.php');

$html2pdf = new HTML2PDF('P', 'A4', 'es');
$html2pdf->WriteHTML('<h1>¡Hola mundo!</h1>');
$html2pdf->Output('mi_pdf.pdf');
?>