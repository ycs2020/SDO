<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$idCategoria1 = 0;


$nomUsuario = trim($_SESSION['nomUsuario']);
$idDepartamento = $_SESSION['idDepartamento'];
$nomDepartamento = getNomDepartamento($idDepartamento);
$idSubDepartamento = $_SESSION['idSubDepartamento'];


if(isset($_POST['idPresup'])){
    $idPresup = $_POST['idPresup'];
    $sumatotalequipos = sumatotalequipos($idPresup);
    $_SESSION['idPresup'] = $idPresup;

    $sql = "SELECT * FROM presupuesto_equipos WHERE idPresup = $idPresup";
    $res = mysqli_query($conexion,$sql);
    while($row = $res->fetch_assoc()){
        $descripcion = $row['descripcion'];
    }

}else if ($_SESSION['idPresup']){
    $idPresup = $_SESSION['idPresup'];
    $sumatotalequipos = sumatotalequipos($idPresup);

    $sql = "SELECT * FROM presupuesto_equipos WHERE idPresup = $idPresup";
    $res = mysqli_query($conexion,$sql);
    while($row = $res->fetch_assoc()){
        $descripcion = $row['descripcion'];
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modificacion del Presupuestos</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- daterange picker -->
    <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="../plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- Bootstrap4 Duallistbox -->
    <link rel="stylesheet" href="../plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
    <!-- BS Stepper -->
    <link rel="stylesheet" href="../plugins/bs-stepper/css/bs-stepper.min.css">
    <!-- dropzonejs -->
    <link rel="stylesheet" href="../plugins/dropzone/min/dropzone.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <style>
        #tablaEquipos th:nth-child(10),
        #tablaEquipos td:nth-child(10) {
            display: none;
        }
    </style>
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
                        <h4>Modificar presupuesto del equipo</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                        </ol>
                       <?php
                            if($_GET['exitoModificarEquiposPresu']){
                                ?>
                                    <script>
                                        Swal.fire({
                                            title: "Exito!",
                                            text: "Se ha modificado con exito!",
                                            icon: "success"
                                        });
                                    </script>
                                <?php
                            }
                       ?>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-header">
                                <a href="presupuesto-equipo.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>
                            </div>

                        </div>

                    </div>
                </div>
                <div class="row" style="text-align:center;" id="rowCrearPresupuesto">
                    <div class="col-12" style="text-align:center;">
                        <div class="card" style="text-align:center;">
                            <div class="card-body" style="text-align:center;">
                                <fieldset class="border p-3">
                                    <h5 style="float: left">Modificar Equipos:</h5>
                                    <br>
                                    <br>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-12 col-12">
                                        <label for="noOrdenTrabajo">Descripcion:</label>
                                        <div class="input-group mb-3">
                                            <input type="text" name="descripcion_visible" id="descripcion_visible" value="<?php echo $descripcion?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-row">

                                        <div class="form-group col-xl-4 col-lg-4 col-md-6 col-12">
                                            <label for="noOrdenTrabajo">Escriba el nombre del equipo:</label>
                                            <div class="input-group mb-3">
                                                <select class="form-control select2" id="equipoSelect" style="width: 100%">
                                                    <option value="Seleccione...">Seleccione...</option>
                                                    <?php
                                                    $sqlInventario = "SELECT * FROM inventario_equipos ORDER BY equipoInventario";
                                                    $resInventario = mysqli_query($conexion, $sqlInventario);
                                                    if ($resInventario->num_rows > 0) {
                                                        while ($rowInventario = $resInventario->fetch_assoc()) {
                                                            ?>
                                                            <option value="<?php echo $rowInventario['idInventario'] ?>" data-valor-unitario="<?php echo $rowInventario['valorUnitario'] ?>" data-marca="<?php echo $rowInventario['marcaInventario'] ?>" data-modelo="<?php echo $rowInventario['modeloInventario'] ?>" data-unidad="<?php echo $rowInventario['unidad'] ?>"><?php echo $rowInventario['equipoInventario'] ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-xl-4 col-lg-4 col-md-6 col-12">
                                            <label for="noOrdenTrabajo">Cantidad:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                </div>
                                                <input type="number" style="text-transform:capitalize;" min="1" class="form-control" id="cantidad" name="cantidad" value="">
                                            </div>
                                        </div>
                                        <div class="form-group col-xl-4 col-lg-4 col-md-6 col-12">
                                            <label for="noOrdenTrabajo">&nbsp;</label>
                                            <button type="button" class="btn btn-block bg-gradient-success" style="text-transform:capitalize" id="aumentar" name="aumentar"><i class="fas fa-plus"></i> Añadir equipo a la tabla</button>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="table-responsive">
                                        <form id="formEquipos" action="../sdo-funciones/presupuesto_modificar.php" method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="descripcion" id="descripcion" value="<?php echo $descripcion?>">
                                            <input type="hidden" name="idPresup1" id="idPresup1" value="<?php echo $idPresup?>">
                                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                            <script>
                                                // Esperar a que el documento esté completamente cargado
                                                $(document).ready(function() {
                                                    // Detectar cambios en el campo de entrada visible
                                                    $('#descripcion_visible').on('input', function() {
                                                        // Obtener el valor del campo de entrada visible
                                                        var descripcion_visible_value = $(this).val();
                                                        // Actualizar el valor del campo de entrada oculto
                                                        $('#descripcion').val(descripcion_visible_value);
                                                    });
                                                });
                                            </script>
                                            <div class="table-responsive">
                                                <table id="tablaEquipos" class="table table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>Codigo Equipo</th>
                                                        <th>Equipo</th>
                                                        <th>Unidad</th>
                                                        <th>Marca</th>
                                                        <th>Modelo</th>
                                                        <th>Cantidad</th>
                                                        <th>Valor Unitario (Sin IVA)</th>
                                                        <th>Valor Total (Sin IVA)</th>
                                                        <th>Eliminar</th>

                                                    </tr>
                                                    </thead>
                                                    <tbody id="tablaEquiposBody">

                                                    </tbody>
                                                    <tfoot>
                                                    <tr>
                                                        <td colspan="7" style="text-align: right; font-weight: bold;">Total General:</td>
                                                        <td id="totalSum" style="font-weight: bold;"><?php echo '$' . number_format($sumatotalequipos, 0, ",", "."); ?></td>
                                                        <td style="font-weight: bold;"></td>
                                                    </tr>
                                                    </tfoot>

                                                </table>
                                            </div>

                                        <br>

                                        <input type="hidden" id="dataRows" name="dataRows">
                                        <input type="hidden" id="unidadCrea" name="unidadCrea" value="<?php echo $nomDepartamento; ?>">
                                        <input type="submit" id="btn-agregar-presup" value="Modificar" class="btn btn-block bg-gradient-success">
                                        </form>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <footer class="main-footer">
        <strong>Copyright &copy; 2022 - ∞. Derechos Reservados</strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
</div>
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="../plugins/select2/js/select2.full.min.js"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="../plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<!-- InputMask -->
<script src="../plugins/moment/moment.min.js"></script>
<script src="../plugins/inputmask/jquery.inputmask.min.js"></script>
<!-- date-range-picker -->
<script src="../plugins/daterangepicker/daterangepicker.js"></script>
<!-- bootstrap color picker -->
<script src="../plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Bootstrap Switch -->
<script src="../plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- BS-Stepper -->
<script src="../plugins/bs-stepper/js/bs-stepper.min.js"></script>
<!-- dropzonejs -->
<script src="../plugins/dropzone/min/dropzone.min.js"></script>
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
    $(document).ready(function () {
        $('#tablaEquipos').DataTable({
            "lengthMenu": [ [25, 50, 100, -1], [25, 50, 100, "Todos"] ],
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
                "url": "ajax-buscar-presupuestos.php",
                "type": "POST",
                "data": function (d) {
                    d.idPresup1 = $('#idPresup1').val();
                },
                "dataSrc": ""
            },
            "columns": [
                { "data": null,
                    "render": function (data, type, row, meta) {
                        return '#' + row.idInventarioEquipo;
                    }
                },
                { "data": "nomEquipo"},
                { "data": "unidadEquipo"},
                { "data": "marcaEquipo"},
                { "data": "modeloEquipo"},
                { "data": "cantidadEquipo"},
                {
                    "data": "valorUnitarioEquipo",
                    "render": function (data, type, row) {
                        // Formatear el valorPresup como moneda chilena
                        return (parseFloat(data)).toLocaleString('es-CL', { style: 'currency', currency: 'CLP' });

                    }
                },
                {
                    "data": "valorTotalEquipo",
                    "render": function (data, type, row) {
                        // Formatear el valorPresup como moneda chilena
                        return (parseFloat(data)).toLocaleString('es-CL', { style: 'currency', currency: 'CLP' });
                    }
                },
                {
                    "data": null,
                    "render": function (data, type, row) {
                        // Agrega el formulario y el botón de visualización para cada fila
                        return '' +
                            '<form style="margin: 0; text-align: center;" class="" action="../sdo-funciones/equipos-presupuesto-eliminar.php" method="post">' +
                            '<input type="hidden" name="idPresupEliminar" value="' + row.idInventarioEquipo + '">' +
                            '<input type="hidden" name="idEquipo" value="' + row.idEquipo + '">' +
                            '<center>' +
                            '<button type="button" class="btn btn-danger delete-btn"><i class="fas fa-trash"></i>&nbsp;&nbsp;Eliminar De La Tabla</button>' +
                            '</center>' +
                            '</form>';
                    }
                }
            ],
            "createdRow": function (row, data, dataIndex) {
                $(row).find('td').css({
                    'vertical-align': 'middle',
                    'text-align': 'center'
                });
            }
        });


// Agregar evento click para los botones de eliminaryca
        // Inicializar el select2
        // Inicializar el select2
        $('.select2').select2();

        verificarCantidadFilas();

        // Verificar cada vez que se agrega o elimina una fila
        $('#tablaEquipos').on('DOMNodeInserted DOMNodeRemoved', function () {
            verificarCantidadFilas();
        });



        function verificarCantidadFilas() {
            var cantidadFilas = $('#tablaEquipos tbody tr').length;
            if (cantidadFilas === 0) {
                $('#btn-agregar-presup').prop('disabled', true);
            } else {
                $('#btn-agregar-presup').prop('disabled', false);
            }
        }

        $('#formEquipos').submit(function () {
            // Obtener las filas de la tabla
            var rows = [];
            $('#tablaEquipos tbody tr').each(function () {
                var rowData = [];
                // Iterar sobre las primeras 4 columnas solamente (sin la columna "Eliminar")
                $(this).find('td:not(:last-child)').each(function () {
                    rowData.push($(this).text());
                });
                rows.push(rowData);
            });

            // Convertir los datos de las filas a JSON y asignarlo al campo oculto
            $('#dataRows').val(JSON.stringify(rows));
        });

        function updateTotal() {
            var totalSum = 0;

            // Loop through each row in the table
            $('#tablaEquipos tbody tr').each(function () {
                // Extract the total value from the last column of each row
                var totalValue = $(this).find('td:eq(7)').text();
                // Remove currency formatting and convert to a float
                totalValue = parseFloat(totalValue.replace(/[^\d.-]/g, '').replace(/[.,]/g, '').replace('CLP', ''));

                // Add the total value to the sum
                totalSum += totalValue;
            });

            // Display the sum in the corresponding element (you can replace '#totalSum' with the ID of your total display element)
            $('#totalSum').text(totalSum.toLocaleString('es-CL', {style: 'currency', currency: 'CLP'}));
        }


        // Manejar el clic en el botón "Añadir equipo a la tabla"
        $('#aumentar').click(function () {
            // Obtener el valor seleccionado del select
            var equipoId = $('#equipoSelect').val();
            var equipoNombre = $('#equipoSelect option:selected').text();
            // Obtener la cantidad ingresada
            var cantidad = $('#cantidad').val();
            var valorUnitario = $('#equipoSelect option:selected').data('valor-unitario');
            var marca = $('#equipoSelect option:selected').data('marca');
            var modelo = $('#equipoSelect option:selected').data('modelo');
            var unidad = $('#equipoSelect option:selected').data('unidad');


            // Validar que se haya seleccionado un equipo y se haya ingresado una cantidad
            if (equipoId !== 'Seleccione...' && cantidad !== '') {
                // Calcular el total
                var valorUnitario2 = parseFloat(valorUnitario);

                var total = parseFloat(valorUnitario2) * parseInt(cantidad);

                var valorUnitario3 = valorUnitario2.toLocaleString('es-CL', {style: 'currency', currency: 'CLP'});

                var totalFormateado = total.toLocaleString('es-CL', {style: 'currency', currency: 'CLP'});


                // Crear una nueva fila para la tabla
                var fila = '<tr><td>#' + equipoId + '</td><td>' + equipoNombre + '</td><td>' + unidad + '</td><td>' + marca + '</td><td>' + modelo + '</td><td>' + cantidad + '</td><td>' + valorUnitario3 + '</td><td style="font-weight: bold;">' + totalFormateado + '</td><td><button class="btn btn-danger eliminar"><i class="fas fa-trash"></i> Eliminar</button></td></tr>';
                // Agregar la fila a la tabla
                $('#tablaEquipos tbody').append(fila);
                // Limpiar los campos después de agregar la fila
                $('#equipoSelect').val('Seleccione...').trigger('change');
                $('#cantidad').val('');
                updateTotal();

            } else {
                alert('Por favor, seleccione un equipo y especifique la cantidad.');
            }
        });
        // Manejar el clic en el botón "Eliminar"
        $('#tablaEquipos').on('click', '.eliminar', function () {
            // Obtener el valor total de la fila antes de eliminarla
            var totalValue = $(this).closest('tr').find('td:eq(7)').text();
            // Remover el formato de moneda y convertir a un número
            totalValue = parseFloat(totalValue.replace(/[^\d.,-]/g, '').replace(',', '.'));

            // Eliminar la fila
            $(this).closest('tr').remove();

            // Actualizar el total restando el valor eliminado
            updateTotal(-totalValue);
        });


        $('#tablaEquipos').on('click', '.delete-btn', function () {
            var idPresupuesto = $(this).closest('form').find('input[name="idPresupEliminar"]').val();

            // Mostrar SweetAlert para confirmar la eliminación
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción no se puede revertir.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma, enviar el formulario para eliminar el presupuesto
                    $(this).closest('form').submit();
                }
            });
        });
    });
</script>

</body>
</html>

