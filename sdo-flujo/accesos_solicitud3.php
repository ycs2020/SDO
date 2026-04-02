<?php

include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
} else {
    session_destroy();
    session_start();
}

if (isset($_POST['seleccionAcceso'])) {

    $seleccionAcceso = $_POST['seleccionAcceso'];

    if ($seleccionAcceso > 0) {

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


<div class="login-box w-75">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="#" class="h2"><b>Solicitud de Accesos</b></a>
            <br>
            <br>
            <input type="image" src="../dist/img/controlCent-HBQP-80.png">

        </div>
        <div class="card-body" style="height: 700px; overflow: scroll;">
            <p class="login-box-msg">
                Ingrese Informaci&oacute;n de Funcionarios seg&uacute;n los campos solicitados.
            </p>


            <hr>

            <form action="procesar.php" id="miFormulario" method="post" enctype="multipart/form-data">
                <table id="miTabla" class="table table-bordered" style="font-size: 13px;">
                    <thead class="bg-secondary">
                    <tr>
                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">NOMBRE DE FUNCIONARIO:</td>
                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">RUT DE FUNCIONARIO:</td>
                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">CALIDAD JURIDICA:</td>
                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">UNIDAD DE FUNCIONARIO:</td>
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
                    <tr class="fieldGroup">
                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                            <input type="text" id="nomFuncionario[]" required name="nomFuncionario[]" class="form-control" placeholder="Nombre de Funcionario">
                        </td>
                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                            <input type="text" id="rutFuncionario[]" required name="rutFuncionario[]" class="form-control" placeholder="RUT de Funcionario">
                        </td>
                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                            <input type="text" id="calidadFuncionario[]" required name="calidadFuncionario[]" class="form-control" placeholder="Calidad de Funcionario">
                        </td>
                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                            <input type="text" id="unidadFuncionario[]" required name="unidadFuncionario[]" class="form-control" placeholder="Unidad de Funcionario">
                        </td>
                        <?php
                        if (!empty($door1FormCCAA)) {
                            ?>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                <input type="checkbox" style="transform: scale(1.5);" id="chkPuerta1[]" name="puertas[]" value="Door 1">
                            </td>
                            <?php
                        }
                        if (!empty($door2FormCCAA)) {
                            ?>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                <input type="checkbox" style="transform: scale(1.5);" id="chkPuerta2[]" name="puertas[]" value="Door 2">
                            </td>
                            <?php
                        }
                        if (!empty($door3FormCCAA)) {
                            ?>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                <input type="checkbox" style="transform: scale(1.5);" id="chkPuerta3[]" name="puertas[]" value="Door 3">
                            </td>
                            <?php
                        }
                        if (!empty($door4FormCCAA)) {
                            ?>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                <input type="checkbox" style="transform: scale(1.5);" id="chkPuerta4[]" name="puertas[]" value="Door 4">
                            </td>
                            <?php
                        }
                        if (!empty($door5FormCCAA)) {
                            ?>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                <input type="checkbox" style="transform: scale(1.5);" id="chkPuerta5[]" name="puertas[]" value="Door 5">
                            </td>
                            <?php
                        }
                        if (!empty($door6FormCCAA)) {
                            ?>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                <input type="checkbox" style="transform: scale(1.5);" id="chkPuerta6[]" name="puertas[]" value="Door 6">
                            </td>
                            <?php
                        }
                        if (!empty($door7FormCCAA)) {
                            ?>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                <input type="checkbox" style="transform: scale(1.5);" id="chkPuerta7[]" name="puertas[]" value="Door 7">
                            </td>
                            <?php
                        }
                        if (!empty($door8FormCCAA)) {
                            ?>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                <input type="checkbox" style="transform: scale(1.5);" id="chkPuerta8[]" name="puertas[]" value="Door 8">
                            </td>
                            <?php
                        }
                        if (!empty($door9FormCCAA)) {
                            ?>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                <input type="checkbox" style="transform: scale(1.5);" id="chkPuerta9[]" name="puertas[]" value="Door 9">
                            </td>
                            <?php
                        }
                        if (!empty($door10FormCCAA)) {
                            ?>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                <input type="checkbox" style="transform: scale(1.5);" id="chkPuerta10[]" name="puertas[]" value="Door 10">
                            </td>
                            <?php
                        }
                        if (!empty($door11FormCCAA)) {
                            ?>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                <input type="checkbox" style="transform: scale(1.5);" id="chkPuerta11[]" name="puertas[]" value="Door 11">
                            </td>
                            <?php
                        }
                        if (!empty($door12FormCCAA)) {
                            ?>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                <input type="checkbox" style="transform: scale(1.5);" id="chkPuerta12[]" name="puertas[]" value="Door 12">
                            </td>
                            <?php
                        }
                        if (!empty($door13FormCCAA)) {
                            ?>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                <input type="checkbox" style="transform: scale(1.5);" id="chkPuerta13[]" name="puertas[]" value="Door 13">
                            </td>
                            <?php
                        }
                        if (!empty($door14FormCCAA)) {
                            ?>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                <input type="checkbox" style="transform: scale(1.5);" id="chkPuerta14[]" name="chkPuerta14[]" value="Door 14">
                            </td>
                            <?php
                        }
                        if (!empty($door15FormCCAA)) {
                            ?>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                <input type="checkbox" style="transform: scale(1.5);" id="chkPuerta15[]" name="chkPuerta15[]" value="Door 15">
                            </td>
                            <?php
                        }
                        if (!empty($door16FormCCAA)) {
                            ?>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                <input type="checkbox" style="transform: scale(1.5);" id="chkPuerta16[]" name="chkPuerta16[]" value="Door 16">
                            </td>
                            <?php
                        }
                        if (!empty($door17FormCCAA)) {
                            ?>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                <input type="checkbox" style="transform: scale(1.5);" id="chkPuerta17[]" name="chkPuerta17[]" value="Door 17">
                            </td>
                            <?php
                        }
                        if (!empty($door18FormCCAA)) {
                            ?>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                <input type="checkbox" style="transform: scale(1.5);" id="chkPuerta18[]" name="chkPuerta18[]" value="Door 18">
                            </td>
                            <?php
                        }
                        ?>
                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">

                            <button onclick="repetirFila(this)" class="btn btn-block btn-success"><span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>Agregar Otro Funcionario</button>
                        </td>
                    </tr>
                </table>
                <script>
                    function repetirFila(button) {
                        var table = document.getElementById("miTabla");
                        var originalRow = button.parentNode.parentNode;
                        var clonedRow = originalRow.cloneNode(true);

                        // Desmarca los checkboxes en la fila clonada
                        var checkboxes = clonedRow.querySelectorAll('input[type="checkbox"]');
                        for (var i = 0; i < checkboxes.length; i++) {
                            checkboxes[i].checked = false;
                        }

                        // Cambia el botón a "Eliminar" y asocia la función "eliminarFila"
                        var buttonAgregar = clonedRow.querySelector("button");
                        buttonAgregar.textContent = "Eliminar";
                        buttonAgregar.className = "btn btn-block btn-danger";
                        buttonAgregar.onclick = function() {
                            eliminarFila(this);
                        };

                        // Limpia los valores de los campos si los hubiera
                        var inputs = clonedRow.querySelectorAll('input[type="text"], textarea');
                        for (var i = 0; i < inputs.length; i++) {
                            inputs[i].value = '';
                        }

                        // Agrega la fila clonada a la tabla
                        table.appendChild(clonedRow);
                    }

                    function eliminarFila(button) {
                        var row = button.parentNode.parentNode;
                        var table = row.parentNode;

                        // Asegúrate de que la primera fila no se elimine
                        if (table.rows.length > 1) {
                            table.removeChild(row);
                        }
                    }
                </script>
                <button type="submit" class="btn btn-primary">Enviar</button>
            </form>
            <script>
                document.getElementById("miFormulario").addEventListener("submit", function(event) {
                    var checkboxes = document.querySelectorAll('input[type="checkbox"][name="puertas[]"]');

                    checkboxes.forEach(function(checkbox) {
                        if (!checkbox.checked) {
                            checkbox.value = "no";
                        }
                    });
                });
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
</body>

</html>