<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];


date_default_timezone_set('America/Santiago');
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de Humedad de Pabellones</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- AdminLTE css -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>


    <script>
        $(document).ready(function () {


            // Show div by removing inline display none style rule
            $(".show-realizar").click(function () {
                $("#rowMenu2").show();
                $("#rowMenu1").hide();
                $("#listarSC").show();
                $("#realizarSC").hide();
            });

            // Show div by removing inline display none style rule
            $(".show-listar").click(function () {
                $("#rowMenu2").hide();
                $("#rowMenu1").show();
                $("#listarSC").hide();
                $("#realizarSC").show();
            });
        });
    </script>
    <script>
        function DoCheckUncheckDisplay(d, dchecked, dunchecked) {
            if (d.checked == true) {
                document.getElementById(dchecked).style.display = "block";
                document.getElementById(dunchecked).style.display = "none";
            } else {
                document.getElementById(dchecked).style.display = "none";
                document.getElementById(dunchecked).style.display = "block";
            }
        }
    </script>


    <script>
        function descargarArchivos($id) {
            alert($id);
        }
    </script>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<!-- Site wrapper -->
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
                        <h4>Registro de Humedad de Pabellones</h4>

                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <?php

        if ($descargaConfirma == "Y" || $descargaConfirma == "y") {
            ?>
            <script>
                Swal.fire({
                    title: 'Descarga de Cotizaciones con &Eacute;xito.',
                    confirmButtonText: 'Cerrar',
                    icon: 'success',
                });
            </script>
        <?php
        } else if ($descargaConfirma == "N" || $descargaConfirma == "n") {
        ?>
            <script>
                Swal.fire({
                    title: 'Error al Descargar Cotizaci&oacute;n.',
                    confirmButtonText: 'Cerrar',
                    icon: 'error',
                });
            </script>
            <?php
        }


        ?>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <!-- Timelime example  -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">

                                <a href="pabellones_administracion.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>

                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body" style="text-align:center;">
                                <button class="btn bg-yellow show-realizar" id="realizarSC" name="realizarSC">Registrar Humedades de Pabellones</button>
                                <button class="btn bg-yellow show-listar" id="listarSC" style="display:none;" name="listarSC">Listar Humedad de Pabellones</button>

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>

                <div class="row" id="rowMenu1" name="rowMenu1">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>1.- Listado de Humedad en Pabellones</h5>
                                <hr>

                                <table style="font-size:90%" id="miTabla" class="table table-bordered table-hover">
                                    <thead class="bg-secondary">
                                    <tr>
                                        <th style="vertical-align:middle; text-align: center;">
                                            FECHA DE REGISTRO
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PERSONAL
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 2
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 3
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 4
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 5
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 6
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 7
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 8
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 9
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 10
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot class="bg-secondary">

                                    <tr>
                                        <th style="vertical-align:middle; text-align: center;">
                                            FECHA DE REGISTRO
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PERSONAL
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 2
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 3
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 4
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 5
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 6
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 7
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 8
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 9
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 10
                                        </th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>

                <div class="row" id="rowMenu2" name="rowMenu2" style="display:none;">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>2.- Solicitar Compra de Materiales</h5>
                                <hr>
                                <form action="../sdo-funciones/soliCompra_solicitarCompraSDOUsers.php" method="post" enctype="multipart/form-data">

                                    <div class="form-group">
                                        <label for="exampleInputArgumentos">Defina Descripci&oacute;n de Solicitud (Ejemplo: Solicitud de Herramientas, Solicitud de Petroleo):<code>*</code></label>
                                        <input type="text" class="form-control" maxlength="60" id="descripcionSoliCompra" name="descripcionSoliCompra" required placeholder="Descripción Solicitud">
                                    </div>
                                    <br>

                                    <!-- AQUI PABLO CARACTERES -->
                                    <div class="form-group">
                                        <label for="exampleInputArgumentos">Argumente los motivos y necesidad de la compra:<code>*</code> (Maximo 1271 Caracteres)</label>
                                        <textarea id="argumentosNecesidad" style="resize:none;" onkeydown="bloquearSaltoDeLinea(event)" oninput="limitarCaracteres(this)" maxlength="1271" required name="argumentosNecesidad" class="form-control" rows="10" placeholder="Argumentos y Motivos de la Solicitud (Maximo 1271 Caracteres)"></textarea>

                                        <script>
                                            function limitarCaracteres(element) {
                                                var maxLength = parseInt(element.getAttribute("maxlength"));
                                                if (element.value.length > maxLength) {
                                                    element.value = element.value.substring(0, maxLength);
                                                }

                                            }
                                            function bloquearSaltoDeLinea(event) {
                                                if (event.keyCode === 13) {
                                                    event.preventDefault();
                                                    // También puedes mostrar un mensaje de advertencia al usuario si lo deseas
                                                    console.log("No se permite el salto de línea.");
                                                }
                                            }
                                        </script>
                                    </div>


                                    <div class="form-row fieldGroup">
                                        <div class="form-group col-md-2">
                                            <label for="exampleInputCoordenadas">Cantidad:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                                </div>
                                                <input type="number" min="1" class="form-control" id="cantidadSC[]" name="cantidadSC[]" placeholder="Cantidad">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="exampleInputRecinto">Articulo:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-list"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="articuloSC[]" name="articuloSC[]" placeholder="Articulo">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="exampleInputCoordenadas">Medida:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fa fa-comment"></i></span>
                                                </div>
                                                <input type="text" min="1" class="form-control" id="medidaSC[]" name="medidaSC[]" placeholder="Medida">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label for="exampleInputCoordenadas">Valor Total Referencial:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                </div>
                                                <input type="number" min="0" class="form-control" id="valorSC[]" name="valorSC[]" placeholder="Valor Total Referencial">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="exampleInputCoordenadas">&nbsp;</label>
                                            <div class="input-group mb-3">
                                                <a href="javascript:void(0)" class="btn btn-block btn-success addMore"><span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>Agregar Otro Material</a>
                                            </div>
                                        </div>
                                    </div>


                                    <br>


                                    <div class="form-group">
                                        <!-- <label for="customFile">Custom File</label> -->
                                        <label for="exampleInputTareasControlCent">Subir Archivos Adjuntos: </label><br>
                                        <label for="exampleInputTareasControlCent">Formatos Permitidos: .pdf , .doc , .docx , .xls , .xlsx, .eml</label>
                                        <div class="custom-file">
                                            <input type="file" max-uploads="40" class="custom-file-input" onchange="mostrarRadio(this)" multiple="" id="adjuntosSC[]" name="adjuntosSC[]" accept=".pdf, .doc, .docx, .xls, .xlsx, .eml">
                                            <label class="custom-file-label" for="customFile">Seleccionar Archivo...</label>
                                        </div>
                                    </div>

                                    <fieldset class="border p-3" id="radios" style="display: none;">
                                        <h6>¿Es una cotización el archivo adjunto?</h6>
                                        <div class="form-group">

                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input " type="radio" name="isCotizacion" id="isCotizacion1" value="1">
                                                <label class="form-check-label" for="inlineRadio1">Si</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input " type="radio" name="isCotizacion" id="isCotizacion2" value="0">
                                                <label class="form-check-label" for="inlineRadio2">No</label>
                                            </div>

                                        </div>
                                    </fieldset>
                                    <script type="text/javascript">
                                        $('.custom-file input').change(function (e) {
                                            var files = [];
                                            for (var i = 0; i < $(this)[0].files.length; i++) {
                                                files.push($(this)[0].files[i].name);
                                            }
                                            $(this).next('.custom-file-label').html(files.join(', '));
                                        });
                                    </script>

                                    <br>
                                    <hr>
                                    <br>

                                    <div class="form-group">
                                        <input type="hidden" name="personalSC" id="personalSC" value="<?php echo $nomUsuario; ?>">
                                        <input type="hidden" name="cargoSC" id="cargoSC" value="<?php echo $cargoJefe; ?>">
                                        <input type="hidden" name="deptoSC" id="deptoSC" value="<?php echo $nomDepartamento; ?>">
                                        <input type="hidden" name="emailSC" id="emailSC" value="<?php echo $emailJefe; ?>">
                                        <input type="hidden" name="idSolicitudSoporte" id="idSolicitudSoporte" value="0">
                                        <input type="submit" class="btn btn-block btn-primary" value="Guardar"/>

                                    </div>
                                </form>

                                <div class="form-row fieldGroupCopy" style="display: none;">

                                    <div class="form-group col-md-2">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                            </div>
                                            <input type="number" min="1" class="form-control" id="cantidadSC[]" name="cantidadSC[]" placeholder="Cantidad">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-list"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="articuloSC[]" name="articuloSC[]" placeholder="Articulo">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-comment"></i></span>
                                            </div>
                                            <input type="text" min="1" class="form-control" id="medidaSC[]" name="medidaSC[]" placeholder="Medida">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-building"></i></span>
                                            </div>
                                            <input type="number" min="0" class="form-control" id="valorSC[]" name="valorSC[]" placeholder="Valor Total Referencial">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <div class="input-group mb-3">
                                            <a href="javascript:void(0)" class="btn btn-block btn-danger remove"><span class="glyphicon glyphicon glyphicon-remove" aria-hidden="true"></span> Eliminar</a>
                                        </div>
                                    </div>
                                </div>


                                <script type="text/javascript">
                                    $(document).ready(function () {
                                        //group add limit
                                        var maxGroup = "100";

                                        //add more fields group
                                        $(".addMore").click(function () {
                                            if ($('body').find('.fieldGroup').length < maxGroup) {
                                                var fieldHTML = '<div class="form-row fieldGroup">' + $(".fieldGroupCopy").html() + '</div>';
                                                $('body').find('.fieldGroup:last').after(fieldHTML);

                                            } else {
                                                alert('Limite de Materiales Completados. Solo se permiten ' + maxGroup + ' Materiales.');
                                            }
                                        });

                                        //remove fields group
                                        $("body").on("click", ".remove", function () {
                                            $(this).parents(".fieldGroup").remove();
                                        });
                                    });
                                </script>
                            </div>

                        </div>

                    </div>

                </div>
            </div>
            <!-- /.timeline -->

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
<script src="../plugins/jquery/jquery.min.js"></script>
<script src="js/dark-mode.js"></script>
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
    $(document).ready(function () {
        var miTabla = $('#miTabla').DataTable({
            "lengthChange": false,
            "searching": true,
            "autoWidth": false,
            "responsive": true,
            "ordering": false,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "search": "Buscar:",
                "processing": "Procesando...",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
            "ajax": {
                "url": "ajax-humedades-pabellones.php", // Archivo PHP que contiene la consulta SQL
                "type": "POST",
                "data": function (d) {

                },
                "dataSrc": ""
            },
            "columns": [
                {"data": "fechaHumedad"},
                {"data": "tecnicoHumedad"},
                {"data": "pabellon2Humedad"},
                {"data": "pabellon3Humedad"},
                {"data": "pabellon4Humedad"},
                {"data": "pabellon5Humedad"},
                {"data": "pabellon6Humedad"},
                {"data": "pabellon7Humedad"},
                {"data": "pabellon8Humedad"},
                {"data": "pabellon9Humedad"},
                {"data": "pabellon10Humedad"},




                // Agrega más columnas según sea necesario
            ],
            "createdRow": function (row, data, dataIndex) {
                $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                $('td', row).css('text-align', 'center'); // Aplica el estilo de centrado vertical a todas las celdas
            }
        });
    });
</script>

</body>

</html>

