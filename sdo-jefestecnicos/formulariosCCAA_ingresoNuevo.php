<?php

include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
} else {
    session_destroy();
    session_start();
}

if (isset($_POST['seleccionAccesoNuevo'])) {

    $seleccionAccesoNuevo = $_POST['seleccionAccesoNuevo'];

    if ($seleccionAccesoNuevo > 0) {

        $dataPuertas = array();
        $dataPuertas = getPuertasFormulario($seleccionAccesoNuevo);

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
        header('Location: formulariosCCAA_menuSeleccion.php?errorCarga=true');
    }
}

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
    <!-- Navbar -->
    <?php
    include '../sdo-templates/jefes-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
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

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">

                                <a href="#" class="h4"><b>Solicitud de Accesos - <?php echo $unidadFormCCAA; ?></b></a>
                                <br>
                                <br>
                                <a href="formulariosCCAA_menuSeleccion.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">

                                <form action="../sdo-funciones/formulariosCCAA_agregarNuevo.php" id="miFormulario" method="post" enctype="multipart/form-data">
                                    <hr>
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


                                    <button type="button" id="agregarFila" class="btn bg-success"><i class="fas fa-plus-circle"></i>&nbsp;&nbsp;Agregar Funcionario</button>
                                    <br><br>
                                    <div class="table-responsive" style="overflow-y: scroll; height: 300px;">
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
                                            </tbody>
                                        </table>
                                    </div>
                                    <br>
                                    <br>
                                    <input type="hidden" name="unidadFormCCAA" id="unidadFormCCAA" value="<?php echo $idFormCCAA ?>"/>
                                    <input type="hidden" name="cantidadPuertas" id="cantidadPuertas" value="<?php echo $numPuertas; ?>"/>
                                    <input type="hidden" name="cantidadFilas" id="cantidadFilas" value="1"/>
                                    <button type="submit" style="text-align: center;" id="enviarFormulario" class="btn btn-block bg-success">Generar Solicitud de Acceso</button>

                                </form>
                                <script>
                                    const tabla = document.getElementById('miTabla');
                                    const btnAgregarFila = document.getElementById('agregarFila');
                                    let correlativo = 2;
                                    var tbody = document.getElementById('miTbody');

                                    btnAgregarFila.addEventListener('click', () => {
                                        contenido = '';
                                        const nuevaFila = document.createElement('tr');
                                        contenido += '<td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="text" id="nomFuncionario' + correlativo + '" required name="nomFuncionario' + correlativo + '" class="form-control" placeholder="Nombre"></td>';

                                        contenido += '<td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="text" id="rutFuncionario' + correlativo + '" required name="rutFuncionario' + correlativo + '" class="form-control rut" placeholder="RUT"></td>';

                                        contenido += '<td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;"><select class="form-control" id="calidadJuridica' + correlativo + '" name="calidadJuridica' + correlativo + '"><option value="Seleccione..." disabled selected>Seleccione</option><option value="Alumno en Practica">Alumno en Practica</option><option value="Becado">Becado</option><option value="Contrata">Contrata</option><option value="Honorarios">Honorarios</option><option value="Medico PAO">Medico PAO</option><option value="Reemplazo">Reemplazo</option><option value="Titular">Titular</option></select></td>';

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
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <strong>Copyright &copy; 2022 - ∞. Derechos Reservados</strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
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
<!-- Page specific script -->
<script>
    $(function () {
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": false,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "search": "Buscar:",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            }
        });
    });
</script>

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