<?php
require_once '../vendor/autoload.php';

include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';

use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

$defaultConfig = (new ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

ini_set('pcre.backtrack_limit', '4000000');
date_default_timezone_set('America/Santiago');
$fechahoy = date('Y-m-d');
$horaHoy = date('H:i');

$options = [
    'mode' => 'utf-8',
    'format' => [215.9, 330.2],
    'tempDir' => __DIR__ . '/tmp',
    'margin_top' => 40,
    'margin_bottom' => 30,
    'margin_left' => 20,
    'margin_right' => 20,
    'default_font' => 'Arial',
    'default_font_size' => 10,
    'fontDir' => array_merge($fontDirs, [
        __DIR__ . '/../plugins/fontawesome-free/webfonts',
    ]),
    'fontdata' => $fontData + [
            'fa' => [
                'R' => 'fa-solid-900.ttf',
            ],
        ],
];

$mpdf = new Mpdf($options);

$css = ".fa { font-family: 'fa'; font-style: normal; }";
$mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$rolUsuario = strtolower($_SESSION['rolUsuario'] ?? '');
$rutasPorRol = [
    'personal' => 'sdo-personal',
    'administrativo' => 'sdo-admtec',
    'bodega pañol' => 'sdo-bodega-panol',
    'jefe tecnico' => 'sdo-jefestecnicos',
    'administrador' => 'sdo-administrador',
    'directiva' => 'sdo-directiva',
    'mesa ayuda' => 'sdo-mesa-ayuda',
    'oficina tecnica' => 'sdo-oficinaTecnica'
];

if (!array_key_exists($rolUsuario, $rutasPorRol)) {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    exit;
}

$timestampActual = time();
$fechaActual2 = fechaEspañol2($timestampActual);
$tituloFooterInforme = "Listado de Funcionarios de Unidad de Alimentación";
$tituloFooterInforme2 = mb_convert_case($tituloFooterInforme, MB_CASE_UPPER, 'UTF-8');

$mpdf->SetHTMLHeader('
    <table width="100%" border="1" cellpadding="5" style="border-collapse: collapse; font-size: 8pt;">
        <tr>
            <td width="20%" align="center" rowspan="3">
                <img src="../dist/img/Logo_HBQP.png" width="100">
            </td>
            <td width="60%" align="center">
                <strong>HOSPITAL BIPROVINCIAL QUILLOTA - PETORCA<br>SUBDIRECCIÓN DE OPERACIONES</strong>
            </td>
            <td width="20%" align="center" rowspan="3">
                <img src="../dist/img/controlCent-HBQP.png" width="100">
            </td>
        </tr>
        <tr><td align="center"><strong>CONTROL CENTRALIZADO</strong></td></tr>
        <tr><td align="center"><strong>' . $tituloFooterInforme2 . '</strong></td></tr>
    </table>
');

$mpdf->SetHTMLFooter('
    <table width="100%" style="border-top: 1px solid #000000; font-size: 8pt;">
        <tr>
            <td width="100%" align="center">
                ' . $tituloFooterInforme . ' - Página {PAGENO} de {nbpg}
            </td>
        </tr>
    </table>
');

ob_start();
?>

    <h2 style="text-align: center;"><?php echo $tituloFooterInforme2; ?></h2>
    <br>
    <!--    <p style="text-align:justify; text-indent:40px;">Este documento señala la cantidad de ingresos al Sistema de Control de Accesos que posee el Departamento de Automatización e Instrumentación, según Actas existentes en Plataforma SDO desde su inicio en febrero de 2022 hasta el --><?php //echo $fechaActual2; ?><!--.</p>-->
    <!--    <br>-->
    <table width="100%" border="1" cellpadding="5" style="border-collapse: collapse; font-size: 9pt;">
        <thead>
        <tr>
            <th style="text-align:center; background-color:#0c84ff; color:white; font-weight:bold; width: 100%;">
                <i class="fa">&#xf201;</i>&nbsp;&nbsp;LISTADO DE FUNCIONARIOS
            </th>
        </tr>
        </thead>
    </table>
    <br>
    <table width="100%" border="1" cellpadding="5" style="border-collapse: collapse; font-size: 8pt;">
        <thead>
        <tr>
            <th style="background-color:#ccc; font-weight:bold; width: 10%;">N°</th>
            <th style="background-color:#ccc; font-weight:bold; width: 40%;">NOMBRE</th>
            <th style="background-color:#ccc; font-weight:bold; width: 15%;">RUT</th>
            <th style="background-color:#ccc; font-weight:bold; width: 35%;">FECHA CADUCIDAD</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>1</td>
            <td>Alfaro Arancibia Juan Gabriel</td>
            <td>17.210.104-6</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>2</td>
            <td>Amaro Galarce Rodrigo Ignacio</td>
            <td>16.889.048-6</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>3</td>
            <td>Arancibia Santibáñez Pamela</td>
            <td>13.983.197-7</td>
            <td>31 de Diciembre de 2025</td>
        </tr>
        <tr>
            <td>4</td>
            <td>Aravena Sanhueza Iris Esperanza</td>
            <td>9.163.386-8</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>5</td>
            <td>Barrera Millache Eduardo Hernán</td>
            <td>18.323.470-6</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>6</td>
            <td>Brignardello Murua Xiomara Ignacia</td>
            <td>20.081.866-0</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>7</td>
            <td>Chamy Tapia Fabiola Georgette</td>
            <td>10.867.201-3</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>8</td>
            <td>Collao Carrasco Claudina del Carmen</td>
            <td>10.242.150-7</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>9</td>
            <td>Collao Olivares Melisa Andrea</td>
            <td>19.340.099-K</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>10</td>
            <td>Cuadro Hernández José Segundo</td>
            <td>10.050.560-6</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>11</td>
            <td>Espinosa Peña Alejandro Luis</td>
            <td>13.542.687-3</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>12</td>
            <td>Faras Aguilera Richard Andrés</td>
            <td>16.700.879-8</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>13</td>
            <td>Figueroa Muñoz Camila Francisca</td>
            <td>19.602.623-1</td>
            <td>31 de Diciembre de 2026</td>
        </tr>
        <tr>
            <td>14</td>
            <td>Flores Cisternas Paola Gricelda</td>
            <td>11.733.060-5</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>15</td>
            <td>Flores Sotomayor Marcia Verónica</td>
            <td>11.092.718-5</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>16</td>
            <td>Galarce Vásquez Jovany Hernán</td>
            <td>16.819.247-9</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>17</td>
            <td>González Gutierrez Martina Francisca</td>
            <td>20.796.304-6</td>
            <td>31 de Diciembre de 2025</td>
        </tr>
        <tr>
            <td>18</td>
            <td>Ilijic Astudillo Yuvitza Susana</td>
            <td>19.981.119-3</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>19</td>
            <td>Iturrieta Aguilera Angela Rafaella</td>
            <td>16.851.807-2</td>
            <td>01 de Febrero de 2040</td>
        </tr>
        <tr>
            <td>20</td>
            <td>Leiva Martínez Tamara Denisse</td>
            <td>17.210.679-k</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>21</td>
            <td>Leiva Vergara Katherine Daniela</td>
            <td>19.980.592-4</td>
            <td>31 de Diciembre de 2025</td>
        </tr>
        <tr>
            <td>22</td>
            <td>Leon Miranda Elizabeth</td>
            <td>14.300.840-1</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>23</td>
            <td>López Segura Priscila Nataly</td>
            <td>19.760.976-1</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>24</td>
            <td>Luco Hidalgo Tamara de la Paz</td>
            <td>17.945.272-3</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>25</td>
            <td>Montiel Campos Camila Rocío</td>
            <td>19.981.068-5</td>
            <td>31 de Diciembre de 2025</td>
        </tr>
        <tr>
            <td>26</td>
            <td>Oyanedel Rozas Paz Belén</td>
            <td>20.177.602-3</td>
            <td>31 de Diciembre de 2025</td>
        </tr>
        <tr>
            <td>27</td>
            <td>Pacheco Figueroa Ignacia Isidora</td>
            <td>19.982.155-5</td>
            <td>31 de Diciembre de 2025</td>
        </tr>
        <tr>
            <td>28</td>
            <td>Porras Zapata Paulina Alexandra</td>
            <td>17.793.228-0</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>29</td>
            <td>Quiroga Ogaz Ingrid Katherine</td>
            <td>13.186.244-K</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>30</td>
            <td>Rodriguez Parejas Evelyn Trinidad</td>
            <td>16.538.805-4</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>31</td>
            <td>Rodriguez Tirado Nathaly Andrea</td>
            <td>16.000.570-K</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>32</td>
            <td>Saavedra Tapia Hilda del Carmen</td>
            <td>9.644.029-4</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>33</td>
            <td>Salazar Álvarez Carol Macarena</td>
            <td>17.977.952-8</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>34</td>
            <td>Saldivar Ríos Maira Nicole</td>
            <td>19.048.191-3</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>35</td>
            <td>Smith Sáez Marcela Camila</td>
            <td>17.866.319-4</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>36</td>
            <td>Tapia Cea Carolina Fernanda</td>
            <td>16.888.964-K</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>37</td>
            <td>Vega Muñoz Maritza Isabel</td>
            <td>11.516.302-7</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>38</td>
            <td>Vera Robles Andrés</td>
            <td>18.259.259-5</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>39</td>
            <td>Vilches Moraga Gabriela Ivonne</td>
            <td>17.209.146-6</td>
            <td>Sin Información</td>
        </tr>
        </tbody>
    </table>

    <br>
    <br>

    <table width="100%" border="1" cellpadding="5" style="border-collapse: collapse; font-size: 9pt;">
        <thead>
        <tr>
            <th style="text-align:center; background-color:#0c84ff; color:white; font-weight:bold; width: 100%;">
                <i class="fa">&#xf201;</i>&nbsp;&nbsp;LISTADO DE FUNCIONARIOS EMPRESA
            </th>
        </tr>
        </thead>
    </table>

    <br>

    <table width="100%" border="1" cellpadding="5" style="border-collapse: collapse; font-size: 8pt;">
        <thead>
        <tr>
            <th style="background-color:#ccc; font-weight:bold; width: 10%;">N°</th>
            <th style="background-color:#ccc; font-weight:bold; width: 40%;">NOMBRE</th>
            <th style="background-color:#ccc; font-weight:bold; width: 15%;">RUT</th>
            <th style="background-color:#ccc; font-weight:bold; width: 35%;">FECHA CADUCIDAD</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>1</td>
            <td>Briceño Valenzuela Emilio Alexis</td>
            <td>17.394.531-0</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>2</td>
            <td>Cano Aranda Elizabeth Andrea</td>
            <td>13.989.147-3</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>3</td>
            <td>Castro Carrasco Nadia Sabina</td>
            <td>18.257.732-4</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>4</td>
            <td>Chirinos Rodríguez Calber Enrique</td>
            <td>27.121.178-3</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>5</td>
            <td>Cordero Flores Patricia Verónica</td>
            <td>11.732.510-5</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>6</td>
            <td>Cortinez Padovani Gonzalo Esteban</td>
            <td>13.545.337-4</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>7</td>
            <td>Fernández Pérez Pamela del Carmen</td>
            <td>11.195.894-7</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>8</td>
            <td>Fernández Uribe Constanza Camila</td>
            <td>18.057.359-3</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>9</td>
            <td>Gonzalez Gonzalez Claudia Andrea</td>
            <td>13.574.488-3</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>10</td>
            <td>Guajardo Castillo Paula</td>
            <td>13.331.546-2</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>11</td>
            <td>Herrera Astudillo Matías Ignacio</td>
            <td>19.048.379-7</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>12</td>
            <td>Hidalgo Galarce Yasmin Alexandra</td>
            <td>20.833.058-6</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>13</td>
            <td>Hurtado Pedreros Andrea Magdalena</td>
            <td>10.589.486-4</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>14</td>
            <td>Iglesias Morales Katalina Margarita</td>
            <td>20.062.169-7</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>15</td>
            <td>Lopez Borquez Valentina Alejandra</td>
            <td>21.108.091-4</td>
            <td>31 de Diciembre de 2025</td>
        </tr>
        <tr>
            <td>16</td>
            <td>Merino Tapia Vanessa Jacqueline</td>
            <td>16.540.188-3</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>17</td>
            <td>Muñoz Vera Mabel Eugenia</td>
            <td>14.253.802-4</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>18</td>
            <td>Novoa Montaño Verónica Angélica</td>
            <td>11.377.736-2</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>19</td>
            <td>Oyanedel Campos Bernarda Elizabeth</td>
            <td>9.111.930-7</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>20</td>
            <td>Padilla Sanchez Nicole Stefanny</td>
            <td>17.077.521-k</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>21</td>
            <td>Romo Arias Manuel Patricio</td>
            <td>8.407.337-7</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>22</td>
            <td>Rosales Gloria Margarita</td>
            <td>26.628.618-k</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>23</td>
            <td>Salazar Barahona Perla Andrea</td>
            <td>15.063.495-4</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>24</td>
            <td>Sanchez Jeria Mariela Jessica</td>
            <td>12.602.267-0</td>
            <td>Persona no se encuentra registrada en Sistema</td>
        </tr>
        <tr>
            <td>25</td>
            <td>Torres Melendez Jimena Paola</td>
            <td>12.086.378-9</td>
            <td>Sin Información</td>
        </tr>
        <tr>
            <td>26</td>
            <td>Vargas Meneses Cesia Gabriele</td>
            <td>19.981.364-1</td>
            <td>Sin Información</td>
        </tr>

        </tbody>
    </table>
<?php


$html = ob_get_clean();
$mpdf->WriteHTML($html);
$tituloFinalInforme = "$tituloFooterInforme.pdf";
$mpdf->Output($tituloFinalInforme, 'I');
