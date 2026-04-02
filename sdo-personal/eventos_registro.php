<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Eventos en Sistema CCAA</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script>
        $(document).ready(function () {
            $(".show-regPuerta").click(function () {
                $("#rowPuertaAcceso").show();
                $("#rowFuncionarioAcceso").hide();
            });
            $(".show-regPersona").click(function () {
                $("#rowPuertaAcceso").hide();
                $("#rowFuncionarioAcceso").show();

            });
        });
    </script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/user-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Listado de Eventos en Sistema CCAA</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
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
                            <!-- /.card-header -->
                            <div class="card-header">
                                <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>
                            </div>
                            <div class="card-body">
                                <div class="row text-center" style="display: flex; justify-content:center;">
                                    <div class="col-lg-3 col-12" id="divRegPuerta" name="divRegPuerta">
                                        <!-- small box -->
                                        <button class="btn btn-block bg-gradient-primary btn-md show-regPuerta" id="regPuerta" name="regPuerta">
                                            <i class="fas fa-door-closed"></i><br>Ver Registro por Puerta de Acceso
                                        </button>
                                    </div>
                                    <div class="col-lg-3 col-12" id="divRegPersona" name="divRegPersona">
                                        <!-- small box -->
                                        <button class="btn btn-block bg-gradient-purple btn-md show-regPersona" id="regPersona" name="regPersona">
                                            <i class="fas fa-user"></i><br>Ver Registro por Funcionario
                                        </button>
                                    </div>
                                </div>

                                <hr>

                                <div class="row" id="rowPuertaAcceso" style="display: none;">
                                    <div class="col-md-12">
                                        <form action="../sdo-pdf/SDO-Historial-Eventos-CCAA.php" method="post" target="_blank">
                                            <div class="card">
                                                <div class="card-header bg-gradient-primary" style="text-align:center;">
                                                    <h5>Registro por Puerta de Acceso</h5>
                                                </div>
                                                <div class="card-body" style="text-align:center;">
                                                    <div class="form-row">
                                                        <div class="form-group col-xl-4 col-lg-4 col-md-6 col-12">
                                                            <label for="proveedorSelect">Seleccione Puerta de Acceso:<code>*</code></label>
                                                            <select class="form-control select2" id="selectCriterio" name="selectCriterio" style="width: 100%; height: 300px">
                                                                <option value="Seleccione...">Seleccione...</option>
                                                                <?php
                                                                $pisosArray = array("-P0", "-P1", "-P2", "-P3", "-P4", "-P5", "-P6");

                                                                foreach ($pisosArray as $piso) {

                                                                    $queryUCA = 'SELECT * FROM ucas_listado WHERE name LIKE "%' . $piso . '%" ORDER BY name ASC';

                                                                    $resUCA = mysqli_query($conexion, $queryUCA);


                                                                    while ($rowUCA = $resUCA->fetch_assoc()) {
                                                                        $query = 'SELECT * FROM ACS_DOOR_GROUP_DOOR WHERE deviceid = ' . $rowUCA['idUnique'] . ' ORDER BY doorid ASC';

                                                                        $res = mysqli_query($conexion, $query);

                                                                        $nomUCA = $rowUCA['name'];

                                                                        $nomUCA = mb_convert_case($nomUCA, MB_CASE_UPPER, "UTF-8");
                                                                        $numAcceso = 1;
                                                                        ?>
                                                                        <option value="<?php echo $nomUCA ?>" disabled><?php echo $nomUCA; ?></option>

                                                                        <?php

                                                                        while ($row = $res->fetch_assoc()) {
                                                                            if ($row['deviceid'] > 0 && $row['doorid'] > -1) {
                                                                                $nomPuerta = obtenerNomPuerta($row['deviceid'], $row['doorid']);

                                                                                $nuevoNom = mb_convert_case($nomPuerta, MB_CASE_UPPER, "UTF-8");

                                                                                ?>
                                                                                <option value="<?php echo $nuevoNom ?>">&nbsp;&nbsp;&nbsp;<?php echo $numAcceso . '.- ' . $nuevoNom; ?></option>

                                                                                <?php
                                                                            }

                                                                            $numAcceso++;
                                                                        }
                                                                    }

                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-xl-4 col-lg-4 col-md-6 col-12">
                                                            <label for="fechaInicioPuerta">Fecha de Inicio:<code>*</code></label>
                                                            <input type="datetime-local" class="form-control" id="fechaInicioRegistro" name="fechaInicioRegistro" required>
                                                        </div>
                                                        <div class="form-group col-xl-4 col-lg-4 col-md-6 col-12">
                                                            <label for="fechaTerminoPuerta">Fecha de Termino:<code>*</code></label>
                                                            <input type="datetime-local" class="form-control" id="fechaTerminoRegistro" name="fechaTerminoRegistro" required>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="form-row text-center">
                                                        <div class="form-group col-12">
                                                            <input type="hidden" name="tipoHistorial" id="tipoHistorial" value="revisarPuerta">
                                                            <button type="submit" class="btn btn-block btn-success"><i class="fas fa-download"></i>&nbsp;Generar Historial</button>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="row" id="rowFuncionarioAcceso" style="display: none;">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header bg-gradient-purple" style="text-align:center;">
                                                <h5>Registro por Funcionario</h5>
                                            </div>
                                            <form action="../sdo-pdf/SDO-Historial-Eventos-CCAA-Funcionarios.php" target="_blank" method="POST">
                                                <div class="card-body" style="text-align: center">
                                                    <div class="form-row">
                                                        <div class="form-group col-xl-4 col-lg-4 col-md-6 col-12">
                                                            <label for="proveedorSelect">Seleccione a un funcionario:<code>*</code></label>
                                                            <select class="form-control select2" id="selectFuncionario" name="selectFuncionario" style="width: 100%; height: 300px">
                                                                <option value="Seleccione...">Seleccione...</option>
                                                                <?php
                                                                $sqlFuncionario = "SELECT * FROM ACM_owner ORDER BY OWNER_Name ASC";
                                                                $resFuncionario = mysqli_query($conexion,$sqlFuncionario);
                                                                while($rowFuncionario = $resFuncionario->fetch_assoc()){
                                                                    $work_id = $rowFuncionario['OWNER_CODE'];
                                                                    $name = $rowFuncionario['OWNER_Name'];
                                                                    $DEPT_ID = $rowFuncionario['DEPT_ID'];

                                                                    if(empty($DEPT_ID)){
                                                                        $departament = 'NO APLICA';
                                                                    }else{

                                                                        $departament = getNomDepartamentoCCAA($DEPT_ID);
                                                                        $departament = mb_convert_case($departament, MB_CASE_UPPER, "UTF-8");
                                                                    }
                                                                    ?>
                                                                    <option data-nit2="<?php echo $departament?>" data-nit="<?php echo $work_id ?>" value="<?php echo $name?>"><?php echo $name?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-xl-4 col-lg-4 col-md-6 col-12">
                                                            <label for="fechaInicioPuerta">Fecha de Inicio:<code>*</code></label>
                                                            <input type="datetime-local" class="form-control" id="fechaInicioRegistroFuncionario" name="fechaInicioRegistroFuncionario" required>
                                                        </div>
                                                        <div class="form-group col-xl-4 col-lg-4 col-md-6 col-12">
                                                            <label for="fechaTerminoPuerta">Fecha de Termino:<code>*</code></label>
                                                            <input type="datetime-local" class="form-control" id="fechaTerminoRegistroFuncionario" name="fechaTerminoRegistroFuncionario" required>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="form-row text-center">
                                                        <div class="form-group col-12">
                                                            <input type="hidden" name="nit" id="nit" value="">
                                                            <input type="hidden" name="nit2" id="nit2" value="">
                                                            <input type="hidden" name="tipoHistorial" id="tipoHistorial" value="revisarFuncionario">
                                                            <button type="submit" class="btn btn-block btn-success"><i class="fas fa-download"></i>&nbsp;Generar Historial Funcionario</button>
                                                        </div>
                                                    </div>

                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
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
<script src="../plugins/select2/js/select2.full.min.js"></script>

<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<script src="js/dark-mode.js"></script>
<script>
    $(document).ready(function(){
        $('#selectFuncionario').change(function(){
            var selectedOption = $(this).find('option:selected');
            var nitValue = selectedOption.data('nit');
            var nit2Value = selectedOption.data('nit2');

            // Encontrar la posición del último slash
            var ultimoSlashIndex = nit2Value.lastIndexOf('\\');

            // Extraer el texto después del último slash
            var textoDespuesUltimoSlash = nit2Value.substring(ultimoSlashIndex + 1);

            $('#nit').val(nitValue);
            $('#nit2').val(textoDespuesUltimoSlash);
        });
    });
</script>
<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })

        //Datemask dd/mm/yyyy
        $('#datemask').inputmask('dd/mm/yyyy', {
            'placeholder': 'dd/mm/yyyy'
        })
        //Datemask2 mm/dd/yyyy
        $('#datemask2').inputmask('mm/dd/yyyy', {
            'placeholder': 'mm/dd/yyyy'
        })
        //Money Euro
        $('[data-mask]').inputmask()

        //Date picker
        $('#reservationdate').datetimepicker({
            format: 'L'
        });

        //Date and time picker
        $('#reservationdatetime').datetimepicker({
            icons: {
                time: 'far fa-clock'
            }
        });

        //Date range picker
        $('#reservation').daterangepicker()
        //Date range picker with time picker
        $('#reservationtime').daterangepicker({
            timePicker: true,
            timePickerIncrement: 30,
            locale: {
                format: 'MM/DD/YYYY hh:mm A'
            }
        })
        //Date range as a button
        $('#daterange-btn').daterangepicker({
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment().subtract(29, 'days'),
                endDate: moment()
            },
            function (start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
            }
        )

        //Timepicker
        $('#timepicker').datetimepicker({
            format: 'LT'
        })

        //Bootstrap Duallistbox
        $('.duallistbox').bootstrapDualListbox()

        //Colorpicker
        $('.my-colorpicker1').colorpicker()
        //color picker with addon
        $('.my-colorpicker2').colorpicker()

        $('.my-colorpicker2').on('colorpickerChange', function (event) {
            $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
        })

        $("input[data-bootstrap-switch]").each(function () {
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        })

    })
    // BS-Stepper Init
    document.addEventListener('DOMContentLoaded', function () {
        window.stepper = new Stepper(document.querySelector('.bs-stepper'))
    })

    // DropzoneJS Demo Code Start
    Dropzone.autoDiscover = false

    // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
    var previewNode = document.querySelector("#template")
    previewNode.id = ""
    var previewTemplate = previewNode.parentNode.innerHTML
    previewNode.parentNode.removeChild(previewNode)

    var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
        url: "/target-url", // Set the url
        thumbnailWidth: 80,
        thumbnailHeight: 80,
        parallelUploads: 20,
        previewTemplate: previewTemplate,
        autoQueue: false, // Make sure the files aren't queued until manually added
        previewsContainer: "#previews", // Define the container to display the previews
        clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
    })

    myDropzone.on("addedfile", function (file) {
        // Hookup the start button
        file.previewElement.querySelector(".start").onclick = function () {
            myDropzone.enqueueFile(file)
        }
    })

    // Update the total progress bar
    myDropzone.on("totaluploadprogress", function (progress) {
        document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
    })

    myDropzone.on("sending", function (file) {
        // Show the total progress bar when upload starts
        document.querySelector("#total-progress").style.opacity = "1"
        // And disable the start button
        file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
    })

    // Hide the total progress bar when nothing's uploading anymore
    myDropzone.on("queuecomplete", function (progress) {
        document.querySelector("#total-progress").style.opacity = "0"
    })

    // Setup the buttons for all transfers
    // The "add files" button doesn't need to be setup because the config
    // `clickable` has already been specified.
    document.querySelector("#actions .start").onclick = function () {
        myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
    }
    document.querySelector("#actions .cancel").onclick = function () {
        myDropzone.removeAllFiles(true)
    }
    // DropzoneJS Demo Code End
</script>
</body>

</html>