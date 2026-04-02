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
    <title>Boletin de Incendio</title>

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
        $(document).ready(function() {


            // Show div by removing inline display none style rule
            $(".show-realizar").click(function() {
                $("#rowMenu2").show();
                $("#rowMenu1").hide();
                $("#listarBoletin").show();
                $("#realizarBoletin").hide();
            });

            // Show div by removing inline display none style rule
            $(".show-listar").click(function() {
                $("#rowMenu2").hide();
                $("#rowMenu1").show();
                $("#listarBoletin").hide();
                $("#realizarBoletin").show();
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
                            <h4>Boletin de Incendio</h4>

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

            if (isset($_GET['exitoCrearSC'])) {
            ?>
                <script>
                    Swal.fire({
                        title: 'Solicitud de Compra Ingresada con &Eacute;xito.',
                        confirmButtonText: 'Cerrar',
                        icon: 'success',
                    }).then((result) => {
                        <?php
                        if ($_GET['material'] == "Con") {
                        ?>
                            Swal.fire('Materiales solicitados con &eacute;xito.', '', 'success')
                        <?php
                        } else if ($_GET['material'] == "Sin") {
                        ?>
                            Swal.fire('Solicitud sin Materiales espec&iacute;ficos.', '', 'success')
                        <?php
                        } else {
                        ?>
                            Swal.fire('Error al Solicitar Materiales.', '', 'error')
                        <?php
                        }
                        ?>
                    })
                </script>
            <?php
            }
            if (isset($_GET['errorCrearSC'])) {
            ?>
                <script>
                    Swal.fire({
                        title: 'Error al Ingresar Solicitud de Compra.',
                        confirmButtonText: 'Cerrar',
                        icon: 'error',
                    });
                </script>
            <?php
            }

            if (isset($_GET['exitoAnularSolicitud'])) {
            ?>
                <script>
                    Swal.fire({
                        title: 'Anulaci&oacute;n de Solicitud de Compra con &Eacute;xito.',
                        confirmButtonText: 'Cerrar',
                        icon: 'success',
                    });
                </script>
            <?php
            }

            if (isset($_GET['errorAnularSolicitud'])) {
            ?>
                <script>
                    Swal.fire({
                        title: 'Error al Anular Solicitud de Compra.',
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

                                    <a href="index.php" style="float: right;" class="btn bg-gradient-danger text-white text-center"><i class="nav-icon fas fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>

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
                                    <button class="btn bg-yellow show-realizar" id="realizarBoletin" name="realizarBoletin">Realizar Boletin de Incendio</button>
                                    <button class="btn bg-yellow show-listar" id="listarBoletin" style="display:none;" name="listarBoletin">Listar Boletines de Incendio</button>

                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>

                    <div class="row" id="rowMenu1" name="rowMenu1"></div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>1.- Listado de Boletines de Incendio</h5>
                                <hr>

                                <table style="font-size:90%" id="example2" class="table table-bordered table-hover">
                                    <thead class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha Boletin</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Folio Boletion</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Categoria Boletin</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Servicio Boletin</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>PMA Boletin</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Creador Boletin</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Ver Boletin</center>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        $query = "SELECT * FROM boletin_incendio ORDER BY fechaBoletinIncendio DESC";

                                        $res = mysqli_query($conexion, $query);

                                        $numBoletin = 1;

                                        while ($row = $res->fetch_assoc()) {

                                        ?>
                                            <tr>
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <center>
                                                        <?php if ($row['fechaBoletinIncendio'] == 0) {
                                                            echo "No registra fecha";
                                                        } else {

                                                            $inicioFecha =  date('d/m/Y', (strtotime(str_replace('/', '-', $row['fechaBoletinIncendio']))));
                                                            $inicioHora = date('H:i', strtotime($row['fechaBoletinIncendio']));

                                                            echo $inicioFecha . '<br>' . $inicioHora;
                                                        } ?>
                                                    </center>
                                                </td>
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <?php
                                                    $folioBoletinIncendio = mb_strtoupper($row['folioBoletinIncendio']);
                                                    echo $folioBoletinIncendio;

                                                    ?>
                                                </td>
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <?php
                                                    $categoriaBoletinIncendio = mb_strtoupper($row['categoriaBoletinIncendio']);
                                                    echo $categoriaBoletinIncendio;

                                                    ?>
                                                </td>
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <?php
                                                    $servicioBoletinIncendio = mb_strtoupper($row['servicioBoletinIncendio']);
                                                    echo $servicioBoletinIncendio;

                                                    ?>
                                                </td>
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <?php
                                                    $pmaBoletinIncendio = mb_strtoupper($row['pmaBoletinIncendio']);
                                                    echo $pmaBoletinIncendio;

                                                    ?>
                                                </td>

                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <?php
                                                    $creadorBoletinIncendio = mb_strtoupper($row['creadorBoletinIncendio']);

                                                    $creadorBoletinIncendio = explode(' (', $creadorBoletinIncendio);


                                                    echo $creadorBoletinIncendio[0] . '<br>(' . $creadorBoletinIncendio[1];


                                                    ?>
                                                </td>
                                                <td style="vertical-align:middle;">
                                                    <center>
                                                        <?php
                                                        $idBoletinIncendio = $row['idBoletinIncendio'];
                                                        $idBoletinIncendio = trim($idBoletinIncendio);
                                                        if ($idBoletinIncendio == 0 || $idBoletinIncendio == "" || $idBoletinIncendio == " " || $idBoletinIncendio == "  ") {
                                                        ?>
                                                            <center><?php echo "Error al Cargar Datos."; ?></center>
                                                        <?php
                                                        } else {

                                                        ?>

                                                            <form target="_blank" style="margin: 0; text-align: center;" class="" action="../sdo-pdf/SDO-Boletin-Incendio.php" method="post">

                                                                <input type="hidden" name="idBoletinIncendio" value="<?php echo $idBoletinIncendio; ?>">
                                                                <button type="submit" id="inputMozi" class="btn btn-block bg-gradient-primary btn-lg"><i class="fa fa-eye"></i></button>

                                                            </form>

                                                        <?php

                                                        }
                                                        ?>


                                                    </center>
                                                </td>


                                            <?php

                                            $numSolicitud = $numSolicitud + 1;
                                        }
                                            ?>

                                            </tr>
                                    </tbody>
                                    <tfoot class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha Boletin</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Folio Boletion</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Categoria Boletin</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Servicio Boletin</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>PMA Boletin</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Creador Boletin</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Ver Boletin</center>
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
                                        <label for="exampleInputArgumentos">Argumente los motivos y necesidad de la compra:<code>*</code></label>
                                        <textarea id="argumentosNecesidad" style="resize:none;" required name="argumentosNecesidad" class="form-control" rows="10" placeholder="Argumentos y Motivos de la Solicitud"></textarea>
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
                                        $('.custom-file input').change(function(e) {
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
                                        <input type="submit" class="btn btn-block btn-primary" value="Guardar" />

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
                                    $(document).ready(function() {
                                        //group add limit
                                        var maxGroup = "100";

                                        //add more fields group
                                        $(".addMore").click(function() {
                                            if ($('body').find('.fieldGroup').length < maxGroup) {
                                                var fieldHTML = '<div class="form-row fieldGroup">' + $(".fieldGroupCopy").html() + '</div>';
                                                $('body').find('.fieldGroup:last').after(fieldHTML);

                                            } else {
                                                alert('Limite de Materiales Completados. Solo se permiten ' + maxGroup + ' Materiales.');
                                            }
                                        });

                                        //remove fields group
                                        $("body").on("click", ".remove", function() {
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
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "pageLength": 10,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": false,
                "autoWidth": true,
                "responsive": true,
                "language": {
                    "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                    "zeroRecords": "No se han encontrado Registros.",
                    "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                    "infoEmpty": "No existen Registros.",
                    "search": "Buscar :",
                    "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                    "paginate": {
                        "previous": "Anterior",
                        "next": "Siguiente"
                    }
                }
            });
        });
    </script>
    <script>
        function mostrarRadio(inputFile) {
            radios = document.getElementById("radios");
            inputRadio1 = document.getElementById("isCotizacion1");
            inputRadio2 = document.getElementById("isCotizacion2");
            if (inputFile.files.length > 0) {
                radios.style.display = "block";
                inputRadio1.required = true;
                inputRadio2.required = true;
            } else {
                radios.style.display = "none";
                inputRadio1.required = false;
                inputRadio2.required = false;
            }
        }
    </script>
    <!-- <script>
        function myFunction(name) {
            alert(name)
        }
    </script> -->
</body>

</html>