<?php

include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Men&uacute; - Selecci&oacute;n de Formularios - Nuevo</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/admin-sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Men&uacute; - Ingreso de Formularios - Nuevo</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="formulariosCCAA_menuSeleccion.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <a href="#" class="h4"><b>Solicitud de Accesos</b></a>
                                <br>
                                <br>
                                <a href="formulariosCCAA_menuSeleccion.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>
                            </div>
                            <div class="card-body">
                                <!--<button id="agregarFila" class="btn bg-success">Agregar Funcionario</button>-->
                                <form action="../sdo-funciones/formulariosCCAA_agregarNuevo_2.php" id="miFormulario" method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="solicitanteFormRegAcceso">Nombre del Solicitante:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-user"></span>
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control" required placeholder="Nombre del Solicitante" id="solicitanteFormRegAcceso" name="solicitanteFormRegAcceso"/>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">

                                            <label for="unidadSolicitaFormRegAcceso">Unidad del Solicitante:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-building"></span>
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control" required placeholder="Unidad del Solicitante" id="unidadSolicitaFormRegAcceso" name="unidadSolicitaFormRegAcceso"/>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for="emailSolicitaFormRegAcceso">E-Mail del Solicitante:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-mail-bulk"></span>
                                                    </div>
                                                </div>
                                                <input type="email" class="form-control" required placeholder="Email del Solicitante" id="emailSolicitaFormRegAcceso" name="emailSolicitaFormRegAcceso"/>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <label style="float: right">Marcar Todas las Puertas</label>
                                    <br>
                                    <br>
                                    <input type="checkbox" name="marcar-todos" id="marcar-todos-checkbox" style="float:right;">
                                    <br>
                                    <?php
                                    $nombreUnidades = array();
                                    if(isset($_POST['contarunidades'])) {
                                        $cantidadFilas = $_POST['contarunidades'];
                                        if($cantidadFilas > 0) {
                                            for ($i = 1; $i <= $cantidadFilas; $i++) {
                                                $seleccionAccesoNuevo = $_POST['seleccionAccesoNuevo' . $i];
                                                if($seleccionAccesoNuevo > 0){
                                                    $sql = "SELECT * FROM formularios_acceso WHERE  idFormCCAA = $seleccionAccesoNuevo";
                                                    $res = mysqli_query($conexion,$sql);
                                                    while($row = $res->fetch_assoc()){
                                                        $idFormCCAA = $row['idFormCCAA'];
                                                        $unidadFormCCAA = $row['unidadFormCCAA'];
                                                        $unidadFormCCAA2 = str_replace([' ', '-', '(', ')'], '', $unidadFormCCAA);
                                                        $nombreUnidades[] = $unidadFormCCAA2;
                                                        $fechaFormCCAA = $row['fechaFormCCAA'];
                                                        $door1FormCCAA = $row['door1FormCCAA'];
                                                        $door1FormCCAA = mb_convert_case($door1FormCCAA, MB_CASE_UPPER, 'UTF-8');
                                                        $door2FormCCAA = $row['door2FormCCAA'];
                                                        $door2FormCCAA = mb_convert_case($door2FormCCAA, MB_CASE_UPPER, 'UTF-8');
                                                        $door3FormCCAA = $row['door3FormCCAA'];
                                                        $door3FormCCAA = mb_convert_case($door3FormCCAA, MB_CASE_UPPER, 'UTF-8');
                                                        $door4FormCCAA = $row['door4FormCCAA'];
                                                        $door4FormCCAA = mb_convert_case($door4FormCCAA, MB_CASE_UPPER, 'UTF-8');
                                                        $door5FormCCAA = $row['door5FormCCAA'];
                                                        $door5FormCCAA = mb_convert_case($door5FormCCAA, MB_CASE_UPPER, 'UTF-8');
                                                        $door6FormCCAA = $row['door6FormCCAA'];
                                                        $door6FormCCAA = mb_convert_case($door6FormCCAA, MB_CASE_UPPER, 'UTF-8');
                                                        $door7FormCCAA = $row['door7FormCCAA'];
                                                        $door7FormCCAA = mb_convert_case($door7FormCCAA, MB_CASE_UPPER, 'UTF-8');
                                                        $door8FormCCAA = $row['door8FormCCAA'];
                                                        $door8FormCCAA = mb_convert_case($door8FormCCAA, MB_CASE_UPPER, 'UTF-8');
                                                        $door9FormCCAA = $row['door9FormCCAA'];
                                                        $door9FormCCAA = mb_convert_case($door9FormCCAA, MB_CASE_UPPER, 'UTF-8');
                                                        $door10FormCCAA = $row['door10FormCCAA'];
                                                        $door10FormCCAA = mb_convert_case($door10FormCCAA, MB_CASE_UPPER, 'UTF-8');
                                                        $door11FormCCAA = $row['door11FormCCAA'];
                                                        $door11FormCCAA = mb_convert_case($door11FormCCAA, MB_CASE_UPPER, 'UTF-8');
                                                        $door12FormCCAA = $row['door12FormCCAA'];
                                                        $door12FormCCAA = mb_convert_case($door12FormCCAA, MB_CASE_UPPER, 'UTF-8');
                                                        $door13FormCCAA = $row['door13FormCCAA'];
                                                        $door13FormCCAA = mb_convert_case($door13FormCCAA, MB_CASE_UPPER, 'UTF-8');
                                                        $door14FormCCAA = $row['door14FormCCAA'];
                                                        $door14FormCCAA = mb_convert_case($door14FormCCAA, MB_CASE_UPPER, 'UTF-8');
                                                        $door15FormCCAA = $row['door15FormCCAA'];
                                                        $door15FormCCAA = mb_convert_case($door15FormCCAA, MB_CASE_UPPER, 'UTF-8');
                                                        $door16FormCCAA = $row['door16FormCCAA'];
                                                        $door16FormCCAA = mb_convert_case($door16FormCCAA, MB_CASE_UPPER, 'UTF-8');
                                                        $door17FormCCAA = $row['door17FormCCAA'];
                                                        $door17FormCCAA = mb_convert_case($door17FormCCAA, MB_CASE_UPPER, 'UTF-8');
                                                        $door18FormCCAA = $row['door18FormCCAA'];
                                                        $door18FormCCAA = mb_convert_case($door18FormCCAA, MB_CASE_UPPER, 'UTF-8');
                                                        ?>
                                                        <legend><?php echo $unidadFormCCAA?></legend>
                                                        <button class="float-right btn bg-gradient-success" type="button" id="btnAgregarFila<?php echo $unidadFormCCAA2?>">Agregar otro Funcionario a la Tabla</button>
                                                        <br>
                                                        <br>
                                                        <table id="miTabla<?php echo $unidadFormCCAA2?>" class="table table-bordered" style="font-size: 12px;">
                                                            <thead class="bg-secondary">
                                                            <tr>
                                                                <td style="white-space: nowrap; vertical-align:middle; text-align:center;">NOMBRE DEL FUNCIONARIO:</td>
                                                                <td style="white-space: nowrap; vertical-align:middle; text-align:center;">RUT DEL FUNCIONARIO:</td>
                                                                <td style="white-space: nowrap; vertical-align:middle; text-align:center;">CALIDAD JURIDICA DEL FUNCIONARIO:</td>
                                                                <td style="white-space: nowrap; vertical-align:middle; text-align:center;">UNIDAD DEL FUNCIONARIO:</td>
                                                                <td style="white-space: nowrap; vertical-align:middle; text-align:center;">CORRELATIVO TARJETA</td>
                                                                <?php
                                                                $numPuertas = 0;
                                                                if (!empty($door1FormCCAA)) {
                                                                    ?>
                                                                    <td style="white-space: nowrap; vertical-align:middle; text-align:center;"><?php echo $door1FormCCAA ?></td>
                                                                    <?php
                                                                    $numPuertas = 1;
                                                                }
                                                                if (!empty($door2FormCCAA)) {
                                                                    ?>
                                                                    <td style="white-space: nowrap; vertical-align:middle; text-align:center;"><?php echo $door2FormCCAA ?></td>
                                                                    <?php
                                                                    $numPuertas = 2;
                                                                }
                                                                if (!empty($door3FormCCAA)) {
                                                                    ?>
                                                                    <td style="white-space: nowrap; vertical-align:middle; text-align:center;"><?php echo $door3FormCCAA ?></td>
                                                                    <?php
                                                                    $numPuertas = 3;
                                                                }
                                                                if (!empty($door4FormCCAA)) {
                                                                    ?>
                                                                    <td style="white-space: nowrap; vertical-align:middle; text-align:center;"><?php echo $door4FormCCAA ?></td>
                                                                    <?php
                                                                    $numPuertas = 4;
                                                                }
                                                                if (!empty($door5FormCCAA)) {
                                                                    ?>
                                                                    <td style="white-space: nowrap; vertical-align:middle; text-align:center;"><?php echo $door5FormCCAA ?></td>
                                                                    <?php
                                                                    $numPuertas = 5;
                                                                }
                                                                if (!empty($door6FormCCAA)) {
                                                                    ?>
                                                                    <td style="white-space: nowrap; vertical-align:middle; text-align:center;"><?php echo $door6FormCCAA ?></td>
                                                                    <?php
                                                                    $numPuertas = 6;
                                                                }
                                                                if (!empty($door7FormCCAA)) {
                                                                    ?>
                                                                    <td style="white-space: nowrap; vertical-align:middle; text-align:center;"><?php echo $door7FormCCAA ?></td>
                                                                    <?php
                                                                    $numPuertas = 7;
                                                                }
                                                                if (!empty($door8FormCCAA)) {
                                                                    ?>
                                                                    <td style="white-space: nowrap; vertical-align:middle; text-align:center;"><?php echo $door8FormCCAA ?></td>
                                                                    <?php
                                                                    $numPuertas = 8;
                                                                }
                                                                if (!empty($door9FormCCAA)) {
                                                                    ?>
                                                                    <td style="white-space: nowrap; vertical-align:middle; text-align:center;"><?php echo $door9FormCCAA ?></td>
                                                                    <?php
                                                                    $numPuertas = 9;
                                                                }
                                                                if (!empty($door10FormCCAA)) {
                                                                    ?>
                                                                    <td style="white-space: nowrap; vertical-align:middle; text-align:center;"><?php echo $door10FormCCAA ?></td>
                                                                    <?php
                                                                    $numPuertas = 10;
                                                                }
                                                                if (!empty($door11FormCCAA)) {
                                                                    ?>
                                                                    <td style="white-space: nowrap; vertical-align:middle; text-align:center;"><?php echo $door11FormCCAA ?></td>
                                                                    <?php
                                                                    $numPuertas = 11;
                                                                }
                                                                if (!empty($door12FormCCAA)) {
                                                                    ?>
                                                                    <td style="white-space: nowrap; vertical-align:middle; text-align:center;"><?php echo $door12FormCCAA ?></td>
                                                                    <?php
                                                                    $numPuertas = 12;
                                                                }
                                                                if (!empty($door13FormCCAA)) {
                                                                    ?>
                                                                    <td style="white-space: nowrap; vertical-align:middle; text-align:center;"><?php echo $door13FormCCAA ?></td>
                                                                    <?php
                                                                    $numPuertas = 13;
                                                                }
                                                                if (!empty($door14FormCCAA)) {
                                                                    ?>
                                                                    <td style="white-space: nowrap; vertical-align:middle; text-align:center;"><?php echo $door14FormCCAA ?></td>
                                                                    <?php
                                                                    $numPuertas = 14;
                                                                }
                                                                if (!empty($door15FormCCAA)) {
                                                                    ?>
                                                                    <td style="white-space: nowrap; vertical-align:middle; text-align:center;"><?php echo $door15FormCCAA ?></td>
                                                                    <?php
                                                                    $numPuertas = 15;
                                                                }
                                                                if (!empty($door16FormCCAA)) {
                                                                    ?>
                                                                    <td style="white-space: nowrap; vertical-align:middle; text-align:center;"><?php echo $door16FormCCAA ?></td>
                                                                    <?php
                                                                    $numPuertas = 16;
                                                                }
                                                                if (!empty($door17FormCCAA)) {
                                                                    ?>
                                                                    <td style="white-space: nowrap; vertical-align:middle; text-align:center;"><?php echo $door17FormCCAA ?></td>
                                                                    <?php
                                                                    $numPuertas = 17;
                                                                }
                                                                if (!empty($door18FormCCAA)) {
                                                                    ?>
                                                                    <td style="white-space: nowrap; vertical-align:middle; text-align:center;"><?php echo $door18FormCCAA ?></td>
                                                                    <?php
                                                                    $numPuertas = 18;
                                                                }
                                                                ?>
                                                                <td style="white-space: nowrap; vertical-align:middle; text-align:center;">ACCIONES:</td>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="miTbody<?php echo $unidadFormCCAA2?>">
                                                            <tr>
                                                                <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="text" id="nomFuncionario<?php echo $unidadFormCCAA2?>1" required name="nomFuncionario<?php echo $unidadFormCCAA2?>1" class="form-control" placeholder="Nombre"></td>
                                                                <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="text" id="rut<?php echo $unidadFormCCAA2?>1" required name="rutFuncionario<?php echo $unidadFormCCAA2?>1" class="form-control rut" placeholder="RUT"></td>
                                                                <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;"><select class="form-control" id="calidadJuridica<?php echo $unidadFormCCAA2?>1" name="calidadJuridica<?php echo $unidadFormCCAA2?>1">
                                                                        <option value="Seleccione..." disabled selected>Seleccione</option>
                                                                        <option value="Alumno en Practica">Alumno en Practica</option>
                                                                        <option value="Becado">Becado</option>
                                                                        <option value="Contrata">Contrata</option>
                                                                        <option value="Docente Externo">Docente Externo</option>
                                                                        <option value="Empresa Externa">Empresa Externa</option>
                                                                        <option value="Honorarios">Honorarios</option>
                                                                        <option value="Medico PAO">Medico PAO</option>
                                                                        <option value="Reemplazo">Reemplazo</option>
                                                                        <option value="Titular">Titular</option>
                                                                    </select></td>
                                                                <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                                    <input type="text" id="unidadFuncionario<?php echo $unidadFormCCAA2?>1" required name="unidadFuncionario<?php echo $unidadFormCCAA2?>1" class="form-control" placeholder="Unidad" onkeyup="actualizarCantidadFilas<?php echo $unidadFormCCAA2?>()">
                                                                </td>
                                                                <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                                    <input type="number" id="correlativotarjeta<?php echo $unidadFormCCAA2?>" required name="correlativotarjeta<?php echo $unidadFormCCAA2?>1" class="form-control" placeholder="Correlativo">
                                                                </td>
                                                                <?php
                                                                for ($j = 1; $j <= $numPuertas; $j++) {
                                                                    ?>
                                                                    <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="checkbox"  style="transform: scale(1.5);" id="chkPuerta<?php echo $j ?>-1<?php echo $unidadFormCCAA2?>" name="chkPuerta<?php echo $j ?>-1<?php echo $unidadFormCCAA2?>" value="Door<?php echo $j ?><?php echo $unidadFormCCAA2?>"></td>
                                                                    <?php
                                                                }
                                                                ?>
                                                                <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">No se puede eliminar</td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                        <br>
                                                        <hr>
                                                        <script>
                                                            const tabla<?php echo $unidadFormCCAA2?> = document.getElementById('miTabla<?php echo $unidadFormCCAA2?>');
                                                            const btnAgregarFila<?php echo $unidadFormCCAA2?> = document.getElementById('btnAgregarFila<?php echo $unidadFormCCAA2?>');
                                                            let correlativo<?php echo $unidadFormCCAA2?> = 2;
                                                            var tbody<?php echo $unidadFormCCAA2?> = document.getElementById('miTbody<?php echo $unidadFormCCAA2?>');
                                                            btnAgregarFila<?php echo $unidadFormCCAA2?>.addEventListener('click', () => {
                                                                contenido<?php echo $unidadFormCCAA2?> = '';
                                                                const nuevaFila<?php echo $unidadFormCCAA2?> = document.createElement('tr');
                                                                contenido<?php echo $unidadFormCCAA2?> += '<td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="text" id="nomFuncionario<?php echo $unidadFormCCAA2?>' + correlativo<?php echo $unidadFormCCAA2?> + '" required name="nomFuncionario<?php echo $unidadFormCCAA2?>' + correlativo<?php echo $unidadFormCCAA2?> + '" class="form-control" placeholder="Nombre"></td>';
                                                                contenido<?php echo $unidadFormCCAA2?> += '<td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="text" id="rut<?php echo $unidadFormCCAA2?>" required name="rutFuncionario<?php echo $unidadFormCCAA2?>' + correlativo<?php echo $unidadFormCCAA2?> + '" class="form-control rut" placeholder="RUT"></td>';
                                                                contenido<?php echo $unidadFormCCAA2?> += '<td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;"><select class="form-control" id="calidadJuridica<?php echo $unidadFormCCAA2?>' + correlativo<?php echo $unidadFormCCAA2?> + '" name="calidadJuridica<?php echo $unidadFormCCAA2?>' + correlativo<?php echo $unidadFormCCAA2?> + '"><option value="Seleccione..." disabled selected>Seleccione</option><option value="Alumno en Practica">Alumno en Practica</option><option value="Becado">Becado</option><option value="Contrata">Contrata</option><option value="Docente Externo">Docente Externo</option><option value="Empresa Externa">Empresa Externa</option><option value="Honorarios">Honorarios</option><option value="Medico PAO">Medico PAO</option><option value="Reemplazo">Reemplazo</option><option value="Titular">Titular</option></select></td>';
                                                                contenido<?php echo $unidadFormCCAA2?> += '<td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="text" id="unidadFuncionario<?php echo $unidadFormCCAA2?>' + correlativo<?php echo $unidadFormCCAA2?> + '" required name="unidadFuncionario<?php echo $unidadFormCCAA2?>' + correlativo<?php echo $unidadFormCCAA2?> + '" class="form-control" placeholder="Unidad"></td>';
                                                                contenido<?php echo $unidadFormCCAA2?> += '<td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="number" id="correlativotarjeta<?php echo $unidadFormCCAA2?>'+ correlativo<?php echo $unidadFormCCAA2?> + '" required name="correlativotarjeta<?php echo $unidadFormCCAA2?>'+correlativo<?php echo $unidadFormCCAA2?> +'" class="form-control" placeholder="Correlativo"></td>';
                                                                for (var h = 1; h <= <?php echo $numPuertas ?>; h++) {
                                                                    contenido<?php echo $unidadFormCCAA2?> += '<td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="checkbox" style="transform: scale(1.5);" id="chkPuerta' + h + '-' + correlativo<?php echo $unidadFormCCAA2?> +'<?php echo $unidadFormCCAA2?>" name="chkPuerta' + h + '-' + correlativo<?php echo $unidadFormCCAA2?> +'<?php echo $unidadFormCCAA2?>" value="Door ' + h + '<?php echo $unidadFormCCAA2?>"></td>';
                                                                }
                                                                contenido<?php echo $unidadFormCCAA2?> += '<td style="font-size: 12px; white-space: nowrap; vertical-align: middle; text-align: center;"><button type="button" onclick="eliminarFila<?php echo $unidadFormCCAA2?>(this)" class="btn btn-block btn-danger"><span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>Eliminar</button></td>';
                                                                nuevaFila<?php echo $unidadFormCCAA2?>.innerHTML = contenido<?php echo $unidadFormCCAA2?>;

                                                                tabla<?php echo $unidadFormCCAA2?>.querySelector('tbody').appendChild(nuevaFila<?php echo $unidadFormCCAA2?>);
                                                                correlativo<?php echo $unidadFormCCAA2?>++;

                                                                var cantidadDeFilas2<?php echo $unidadFormCCAA2?> = tbody<?php echo $unidadFormCCAA2?>.getElementsByTagName('tr').length;

                                                                document.getElementById('cantidadFilas<?php echo $unidadFormCCAA2?>').value = cantidadDeFilas2<?php echo $unidadFormCCAA2?>;
                                                            });

                                                            function eliminarFila<?php echo $unidadFormCCAA2?>(boton) {
                                                                const fila<?php echo $unidadFormCCAA2?> = boton.closest('tr');
                                                                fila<?php echo $unidadFormCCAA2?>.remove();
                                                                // Actualiza los correlativos después de eliminar una fila
                                                                correlativo<?php echo $unidadFormCCAA2?>--;
                                                                // Obtén una referencia al elemento <tbody> por su id

                                                                // Cuenta la cantidad de elementos <tr> dentro del <tbody>
                                                                var cantidadDeFilas<?php echo $unidadFormCCAA2?> = tbody<?php echo $unidadFormCCAA2?>.getElementsByTagName('tr').length;

                                                                if (cantidadDeFilas<?php echo $unidadFormCCAA2?> < 1) {
                                                                    document.getElementById("enviarFormulario").disabled = true;
                                                                }
                                                                var cantidadDeFilas2<?php echo $unidadFormCCAA2?> = tbody<?php echo $unidadFormCCAA2?>.getElementsByTagName('tr').length;

                                                                document.getElementById('cantidadFilas<?php echo $unidadFormCCAA2?>').value = cantidadDeFilas2<?php echo $unidadFormCCAA2?>;
                                                            }
                                                            function actualizarCorrelativos() {
                                                                const filas = tabla.querySelectorAll('tbody tr');
                                                                filas.forEach((fila, index) => {
                                                                    const correlativoCell = fila.querySelector('td:first-child');
                                                                    correlativoCell.textContent = index + 1;
                                                                });
                                                            }

                                                        </script>
                                                        <input type="hidden" name="unidadFormCCAA<?php echo $unidadFormCCAA2?>" id="unidadFormCCAA<?php echo $unidadFormCCAA2?>" value="<?php echo $idFormCCAA ?>"/>
                                                        <input type="hidden" name="cantidadPuertas<?php echo $unidadFormCCAA2?>" id="cantidadPuertas<?php echo $unidadFormCCAA2?>" value="<?php echo $numPuertas; ?>"/>
                                                        <input type="hidden" name="cantidadFilas<?php echo $unidadFormCCAA2?>" id="cantidadFilas><?php echo $unidadFormCCAA2?>" value="<?php echo $cantidadFilas?>"/>

                                                        <script>
                                                            document.getElementById("marcar-todos-checkbox").addEventListener("change", function() {
                                                                var checkboxes = document.querySelectorAll("[name^='chkPuerta']"); // selecciona todos los checkboxes que empiecen con 'chkPuerta'
                                                                checkboxes.forEach(function(checkbox) {
                                                                    checkbox.checked = document.getElementById("marcar-todos-checkbox").checked; // marca o desmarca el checkbox según el estado del checkbox "marcar-todos"
                                                                });
                                                            });
                                                        </script>
                                                        <?php
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                    <input type="hidden" name="ultimo" id="ultimo" value="<?php echo $ultimo?>">
                                    <input type="hidden" name="nombreUnidades" id="nombreUnidades" value="<?php echo  base64_encode(serialize($nombreUnidades)); ?>">
                                    <br>
                                    <button type="submit" style="text-align: center;" id="enviarFormulario" class="btn btn-block bg-success"><i class="fas fa-upload"></i>&nbsp;&nbsp;Generar Solicitud de Acceso</button>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
</body>
<script src="js/dark-mode.js"></script>
<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../plugins/jszip/jszip.min.js"></script>
<script src="../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
</html>
