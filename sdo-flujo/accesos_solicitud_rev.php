<?php

include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
} else {
    session_destroy();
    session_start();
}

if (isset($_POST['seleccionAcceso'],$_POST['solicitanteFormRegAcceso'],$_POST['unidadSolicitaFormRegAcceso'],$_POST['emailSolicitaFormRegAcceso'])) {

    $seleccionAcceso = $_POST['seleccionAcceso'];
    $solicitanteFormRegAcceso = $_POST['solicitanteFormRegAcceso'];
    $unidadSolicitaFormRegAcceso = $_POST['unidadSolicitaFormRegAcceso'];
    $emailSolicitaFormRegAcceso = $_POST['emailSolicitaFormRegAcceso'];

    if ($seleccionAcceso > 0 && !empty($solicitanteFormRegAcceso) && !empty($unidadSolicitaFormRegAcceso) && !empty($emailSolicitaFormRegAcceso)) {

        $dataPuertas = array();
        $dataPuertas = getPuertasFormulario($seleccionAcceso);

        $idFormCCAA = $dataPuertas['idFormCCAA'];
        $unidadFormCCAA = $dataPuertas['unidadFormCCAA'];
        $fechaFormCCAA = $dataPuertas['fechaFormCCAA'];
        $door1FormCCAA = $dataPuertas['door1FormCCAA'];
        $door1FormCCAA = mb_convert_case($door1FormCCAA, MB_CASE_UPPER, 'UTF-8');
        $door2FormCCAA = $dataPuertas['door2FormCCAA'];
        $door2FormCCAA = mb_convert_case($door2FormCCAA, MB_CASE_UPPER, 'UTF-8');
        $door3FormCCAA = $dataPuertas['door3FormCCAA'];
        $door3FormCCAA = mb_convert_case($door3FormCCAA, MB_CASE_UPPER, 'UTF-8');
        $door4FormCCAA = $dataPuertas['door4FormCCAA'];
        $door4FormCCAA = mb_convert_case($door4FormCCAA, MB_CASE_UPPER, 'UTF-8');
        $door5FormCCAA = $dataPuertas['door5FormCCAA'];
        $door5FormCCAA = mb_convert_case($door5FormCCAA, MB_CASE_UPPER, 'UTF-8');
        $door6FormCCAA = $dataPuertas['door6FormCCAA'];
        $door6FormCCAA = mb_convert_case($door6FormCCAA, MB_CASE_UPPER, 'UTF-8');
        $door7FormCCAA = $dataPuertas['door7FormCCAA'];
        $door7FormCCAA = mb_convert_case($door7FormCCAA, MB_CASE_UPPER, 'UTF-8');
        $door8FormCCAA = $dataPuertas['door8FormCCAA'];
        $door8FormCCAA = mb_convert_case($door8FormCCAA, MB_CASE_UPPER, 'UTF-8');
        $door9FormCCAA = $dataPuertas['door9FormCCAA'];
        $door9FormCCAA = mb_convert_case($door9FormCCAA, MB_CASE_UPPER, 'UTF-8');
        $door10FormCCAA = $dataPuertas['door10FormCCAA'];
        $door10FormCCAA = mb_convert_case($door10FormCCAA, MB_CASE_UPPER, 'UTF-8');
        $door11FormCCAA = $dataPuertas['door11FormCCAA'];
        $door11FormCCAA = mb_convert_case($door11FormCCAA, MB_CASE_UPPER, 'UTF-8');
        $door12FormCCAA = $dataPuertas['door12FormCCAA'];
        $door12FormCCAA = mb_convert_case($door12FormCCAA, MB_CASE_UPPER, 'UTF-8');
        $door13FormCCAA = $dataPuertas['door13FormCCAA'];
        $door13FormCCAA = mb_convert_case($door13FormCCAA, MB_CASE_UPPER, 'UTF-8');
        $door14FormCCAA = $dataPuertas['door14FormCCAA'];
        $door14FormCCAA = mb_convert_case($door14FormCCAA, MB_CASE_UPPER, 'UTF-8');
        $door15FormCCAA = $dataPuertas['door15FormCCAA'];
        $door15FormCCAA = mb_convert_case($door15FormCCAA, MB_CASE_UPPER, 'UTF-8');
        $door16FormCCAA = $dataPuertas['door16FormCCAA'];
        $door16FormCCAA = mb_convert_case($door16FormCCAA, MB_CASE_UPPER, 'UTF-8');
        $door17FormCCAA = $dataPuertas['door17FormCCAA'];
        $door17FormCCAA = mb_convert_case($door17FormCCAA, MB_CASE_UPPER, 'UTF-8');
        $door18FormCCAA = $dataPuertas['door18FormCCAA'];
        $door18FormCCAA = mb_convert_case($door18FormCCAA, MB_CASE_UPPER, 'UTF-8');

    } else {
        header('Location: accesos_seleccion.php?errorCarga=true');
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Solicitud de Formato de Acceso</title>

    <link rel="icon" href="../dist/img/controlCent-HBQP-120.png" type="image/icon type">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">


    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>

    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
</head>

<body style="background-image:url(../dist/img/Hospital.png); background-size: cover;" class="hold-transition login-page ">

<!-- Preloader -->
<div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../dist/img/controlCent-HBQP-120.png" alt="AdminLTELogo">
</div>


<div class="login-box w-100">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="#" class="h2"><b>Solicitud de Accesos - <?php echo $unidadFormCCAA; ?></b></a>
            <br>
            <br>
            <input type="image" src="../dist/img/controlCent-HBQP-80.png">

        </div>
        <div class="card-body" style="height: 700px; overflow: scroll;">

            <a href="accesos_seleccion.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
            <br>
            <br>
            <p class="login-box-msg">
                Ingrese Informaci&oacute;n de Funcionarios seg&uacute;n los campos solicitados.
            </p>


            <hr>

<!--            <button id="agregarFila" class="btn bg-success">Agregar Funcionario</button>-->
            <form action="accesos_descargarPDF.php" id="miFormulario" method="post" enctype="multipart/form-data">

                <br><br>
                <div class="table-responsive">
                    <table id="miTabla" class="table table-bordered" style="font-size: 12px;">
                        <thead class="bg-secondary">
                        <tr>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">NOMBRE DEL FUNCIONARIO:</td>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">RUT DEL FUNCIONARIO:</td>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">CALIDAD JURIDICA DEL FUNCIONARIO:</td>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">UNIDAD DEL FUNCIONARIO:</td>
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
                        <tbody id="miTbody">
                        <tr>
                            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="text" id="nomFuncionario1" required name="nomFuncionario1" class="form-control" placeholder="Nombre"></td>
                            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="text" id="rut" required name="rutFuncionario1" class="form-control rut" placeholder="RUT"></td>

                            <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;"><select class="form-control" id="calidadJuridica1" name="calidadJuridica1">
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
                            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="text" id="unidadFuncionario1" required name="unidadFuncionario1" class="form-control" placeholder="Unidad"></td>
                            <?php
                            for ($i = 1; $i <= $numPuertas; $i++) {
                                ?>
                                <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="checkbox" style="transform: scale(1.5);" id="chkPuerta<?php echo $i ?>-1" name="chkPuerta<?php echo $i ?>-1" value="Door<?php echo $i ?>"></td>
                                <?php
                            }
                            ?>
                            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">No se puede eliminar</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="text" id="nomFuncionario2" name="nomFuncionario2" class="form-control" placeholder="Nombre"></td>
                            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="text" id="rut" name="rutFuncionario2" class="form-control rut" placeholder="RUT"></td>

                            <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;"><select class="form-control" id="calidadJuridica2" name="calidadJuridica2">
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
                            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="text" id="unidadFuncionario2" name="unidadFuncionario2" class="form-control" placeholder="Unidad"></td>
                            <?php
                            for ($i = 1; $i <= $numPuertas; $i++) {
                                ?>
                                <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="checkbox" style="transform: scale(1.5);" id="chkPuerta<?php echo $i ?>-2" name="chkPuerta<?php echo $i ?>-2" value="Door<?php echo $i ?>"></td>
                                <?php
                            }
                            ?>
                            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">No se puede eliminar</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="text" id="nomFuncionario3" name="nomFuncionario3" class="form-control" placeholder="Nombre"></td>
                            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="text" id="rut" name="rutFuncionario3" class="form-control rut" placeholder="RUT"></td>

                            <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;"><select class="form-control" id="calidadJuridica3" name="calidadJuridica3">
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
                            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="text" id="unidadFuncionario3" name="unidadFuncionario3" class="form-control" placeholder="Unidad"></td>
                            <?php
                            for ($i = 1; $i <= $numPuertas; $i++) {
                                ?>
                                <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="checkbox" style="transform: scale(1.5);" id="chkPuerta<?php echo $i ?>-3" name="chkPuerta<?php echo $i ?>-3" value="Door<?php echo $i ?>"></td>
                                <?php
                            }
                            ?>
                            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">No se puede eliminar</td>
                        </tr>

                        </tbody>
                    </table>
                </div>
                <br>
                <br>

                <input type="hidden" name="unidadFormCCAA" id="unidadFormCCAA" value="<?php echo $idFormCCAA ?>"/>
                <input type="hidden" name="cantidadPuertas" id="cantidadPuertas" value="<?php echo $numPuertas; ?>"/>
                <input type="hidden" name="solicitanteFormRegAcceso" id="solicitanteFormRegAcceso" value="<?php echo $solicitanteFormRegAcceso; ?>"/>
                <input type="hidden" name="unidadSolicitaFormRegAcceso" id="unidadSolicitaFormRegAcceso" value="<?php echo $unidadSolicitaFormRegAcceso; ?>"/>
                <input type="hidden" name="emailSolicitaFormRegAcceso" id="emailSolicitaFormRegAcceso" value="<?php echo $emailSolicitaFormRegAcceso; ?>"/>
                <input type="hidden" name="cantidadPuertas" id="cantidadPuertas" value="<?php echo $numPuertas; ?>"/>
                <input type="hidden" name="cantidadPuertas" id="cantidadPuertas" value="<?php echo $numPuertas; ?>"/>
                <input type="hidden" name="cantidadFilas" id="cantidadFilas" value="1"/>
                <button type="button" class="bg-primary btn-block btn" id="agregarFila">Agregar Funcionario</button>
                <br>

                <button type="submit" style="text-align: center;" id="enviarFormulario" class="btn btn-block bg-success">Generar Solicitud de Acceso</button>

            </form>

            <script>
                const tabla = document.getElementById('miTabla');
                const btnAgregarFila = document.getElementById('agregarFila');
                let correlativo = 4;
                var tbody = document.getElementById('miTbody');

                btnAgregarFila.addEventListener('click', () => {
                    contenido = '';
                    const nuevaFila = document.createElement('tr');
                    contenido += '<td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="text" id="nomFuncionario' + correlativo + '" required name="nomFuncionario' + correlativo + '" class="form-control" placeholder="Nombre"></td>';

                    contenido += '<td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="text" id="rut" required name="rutFuncionario' + correlativo + '" class="form-control rut" placeholder="RUT"></td>';

                    contenido += '<td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;"><select class="form-control" id="calidadJuridica' + correlativo + '" name="calidadJuridica' + correlativo + '"><option value="Seleccione..." disabled selected>Seleccione</option><option value="Alumno en Practica">Alumno en Practica</option><option value="Becado">Becado</option><option value="Contrata">Contrata</option><option value="Docente Externo">Docente Externo</option><option value="Empresa Externa">Empresa Externa</option><option value="Honorarios">Honorarios</option><option value="Medico PAO">Medico PAO</option><option value="Reemplazo">Reemplazo</option><option value="Titular">Titular</option></select></td>';

                    contenido += '<td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="text" id="unidadFuncionario' + correlativo + '" required name="unidadFuncionario' + correlativo + '" class="form-control" placeholder="Unidad"></td>';

                    for (var i = 1; i <= <?php echo $numPuertas ?>; i++) {
                        contenido += '<td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="checkbox" style="transform: scale(1.5);" id="chkPuerta' + i + '-' + correlativo + '" name="chkPuerta' + i + '-' + correlativo + '" value="Door ' + i + '"></td>';
                    }

                    contenido += '<td style="font-size: 12px; white-space: nowrap; vertical-align: middle; text-align: center;"><button onclick="eliminarFila(this)" class="btn btn-block btn-danger"><span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>Eliminar</button></td>';


                    nuevaFila.innerHTML = contenido;

                    document.getElementById("enviarFormulario").disabled = false;

                    tabla.querySelector('tbody').appendChild(nuevaFila);
                    correlativo++;

                    var cantidadDeFilas2 = tbody.getElementsByTagName('tr').length;

                    document.getElementById('cantidadFilas').value = cantidadDeFilas2;

                });

                function eliminarFila(boton) {
                    const fila = boton.closest('tr');
                    fila.remove();
                    // Actualiza los correlativos después de eliminar una fila
                    correlativo--;
                    // Obtén una referencia al elemento <tbody> por su id

                    // Cuenta la cantidad de elementos <tr> dentro del <tbody>
                    var cantidadDeFilas = tbody.getElementsByTagName('tr').length;

                    if (cantidadDeFilas < 1) {
                        document.getElementById("enviarFormulario").disabled = true;
                    }
                    var cantidadDeFilas2 = tbody.getElementsByTagName('tr').length;

                    document.getElementById('cantidadFilas').value = cantidadDeFilas2;
                }

                function actualizarCorrelativos() {
                    const filas = tabla.querySelectorAll('tbody tr');
                    filas.forEach((fila, index) => {
                        const correlativoCell = fila.querySelector('td:first-child');
                        correlativoCell.textContent = index + 1;
                    });
                }


            </script>




        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
<!-- /.login-box -->

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

<script>
    function formatearRut(rut) {
        rut = rut.replace(/[^\dkK]/g, '');

        if (rut.length > 1) {
            let formateado = rut.slice(-4, -1) + '-' + rut.substr(rut.length - 1);
            for (let i = 4; i < rut.length; i += 3) {
                formateado = rut.slice(-i - 3, -i) + '.' + formateado;
            }
            return formateado;
        } else {
            return rut;
        }
    }

    // Agrega un evento delegado al document
    document.addEventListener('input', function (event) {
        // Verifica si el elemento que desencadenó el evento pertenece a la clase RUT
        if (event.target.classList.contains('rut')) {
            event.target.value = formatearRut(event.target.value).toUpperCase();
        }
    });

    document.addEventListener('paste', function (event) {
        if (event.target.classList.contains('rut')) {
            setTimeout(function () {
                event.target.value = formatearRut(event.target.value).toUpperCase();
            }, 0);
        }
    });
</script>
</body>

</html>