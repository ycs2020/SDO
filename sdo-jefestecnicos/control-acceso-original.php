<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Sistema de Control de Accesos</title>

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
    <link rel="stylesheet" href="../dist/css/cuadro-cantidad.css">

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
        </section>

        <?php
        if(isset($_GET['mensajeVar'])){

            if(!empty($_SESSION['mensajeAlerta']) && !empty($_SESSION['iconAlerta'])){
                $mensajeAlerta = $_SESSION['mensajeAlerta'];
                $iconAlerta = $_SESSION['iconAlerta'];

                if($iconAlerta == 'Error' || $iconAlerta == 'Correcto'){
                    if($iconAlerta == 'Error'){
                        $iconoFinal = 'danger';
                    }else if($iconAlerta == 'Correcto'){
                        $iconoFinal = 'success';
                    }


                    ?>
                    <script>
                        Swal.fire({
                            icon: '<?php echo $iconoFinal; ?>', // Asegúrate de que esto sea una cadena
                            title: '<?php echo $mensajeAlerta; ?>',
                            text: '',
                        });
                    </script>

                    <?php
                }
            }
        }
        ?>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">

                    <!-- left column -->
                    <div class="col-xl-10 col-lg-9 col-md-8 col-12">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <a style="pointer-events: none;" class="btn font-weight-bold">Sistema de Control de Acceso</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                        <div class="card">
                            <div class="card-header bg-danger">
                                <a href="index.php" style="float: right;" class="btn btn-block btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <!-- Custom Tabs -->
                        <div class="card">
                            <div class="card-body" style="text-align:center;">

                                <div class="row text-center" style="display: flex; justify-content:center;">
                                    <div class="col-lg-2 col-12">

                                        <form style="margin: 0; text-align: center;" class="" action="../sdo-funciones/db_actualizarCA.php" method="post" enctype="multipart/form-data">

                                            <button type="button" data-toggle="modal" data-target="#modalSubirExcel" id="subirExcel" class="btn bg-gradient-success btn-block"><i class="fa fa-upload"></i>&nbsp;&nbsp;Subir Datos</button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="modalSubirExcel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-success">
                                                            <h5 class="modal-title" id="exampleModalLabel">Subir Archivo DB</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-12 ml-auto">
                                                                    <div style="text-align:left;" class="form-group">
                                                                        <label for="nombreDatos">Seleccione la Tabla a Actualizar...</label>
                                                                        <select name="nombreDatos" id="nombreDatos" class="form-control">
                                                                            <option value="Seleccione">Seleccione...</option>
                                                                            <option value="ACM_owner">ACM_owner</option>
                                                                            <option value="ACS_DOOR_GROUP">ACS_DOOR_GROUP</option>
                                                                            <option value="ACS_DOOR_GROUP_DOOR">ACS_DOOR_GROUP_DOOR</option>
                                                                            <option value="ACS_DOOR_GROUP_USER">ACS_DOOR_GROUP_USER</option>
                                                                            <option value="Department_table">Department_table</option>
                                                                            <option value="DepartmentInter">Intercom - Departamento</option>
                                                                            <option value="PersonInter">Intercom - Persona</option>
                                                                        </select>
                                                                    </div>
                                                                    <br>
                                                                    <div style="text-align:left;" class="form-group">
                                                                        <label for="data">Seleccione el Archivo DB...</label>
                                                                        <div class="custom-file">
                                                                            <input type="file" class="custom-file-input" id="archivo_db" name="archivo_db" accept=".db">
                                                                            <label class="custom-file-label" for="customFile">Seleccionar Archivo...</label>
                                                                        </div>
                                                                        <script>
                                                                            document.getElementById('archivo_db').addEventListener('change', function () {
                                                                                var fileName = this.value.split('\\').pop(); // Obtener solo el nombre del archivo
                                                                                var label = document.querySelector('.custom-file-label');
                                                                                label.textContent = fileName;
                                                                            });
                                                                        </script>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i>&nbsp;&nbsp;Agregar</button>
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-lg-2 col-12">

                                        <form method="post" action="../sdo-pdf/SDO-Estadistica-Actas.php">

                                            <button type="submit" style="float: right;" id="inputMozi" class="btn btn-primary btn-block"><i class="fa fa-download"></i>&nbsp;&nbsp;Descargar Estadistica</button>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-body">
<!--                                <table id="example2" class="table table-bordered table-hover">-->
<!--                                    <thead class="bg-secondary">-->
<!--                                    <tr>-->
<!--                                        <th>-->
<!--                                            <center>N°</center>-->
<!--                                        </th>-->
<!--                                        <th>-->
<!--                                            <center>Nombre<center>-->
<!--                                        </th>-->
<!--                                        <th>-->
<!--                                            <center>RUT<center>-->
<!--                                        </th>-->
<!--                                        <th>-->
<!--                                            <center>N° Credencial<center>-->
<!--                                        </th>-->
<!--                                        <th>-->
<!--                                            <center>Calidad Juridica<center>-->
<!--                                        </th>-->
<!--                                        <th>-->
<!--                                            <center>Departamento<center>-->
<!--                                        </th>-->
<!--                                        <th>-->
<!--                                            <center>Estado Credencial<center>-->
<!--                                        </th>-->
<!--                                        <th>-->
<!--                                            <center>Intercom<center>-->
<!--                                        </th>-->
<!--                                        <th>-->
<!--                                            <center>Fecha Bloqueo<br>Automatico<center>-->
<!--                                        </th>-->
<!--                                    </tr>-->
<!--                                    </thead>-->
<!--                                    <tbody>-->
<!--                                    </tbody>-->
<!--                                    <tfoot class="bg-secondary">-->
<!--                                    <tr>-->
<!--                                        <th>-->
<!--                                            <center>N°</center>-->
<!--                                        </th>-->
<!--                                        <th>-->
<!--                                            <center>Nombre<center>-->
<!--                                        </th>-->
<!--                                        <th>-->
<!--                                            <center>RUT<center>-->
<!--                                        </th>-->
<!--                                        <th>-->
<!--                                            <center>N° Credencial<center>-->
<!--                                        </th>-->
<!--                                        <th>-->
<!--                                            <center>Calidad Juridica<center>-->
<!--                                        </th>-->
<!--                                        <th>-->
<!--                                            <center>Departamento<center>-->
<!--                                        </th>-->
<!--                                        <th>-->
<!--                                            <center>Estado Credencial<center>-->
<!--                                        </th>-->
<!--                                        <th>-->
<!--                                            <center>Intercom<center>-->
<!--                                        </th>-->
<!--                                        <th>-->
<!--                                            <center>Fecha Bloqueo<br>Automatico<center>-->
<!--                                        </th>-->
<!--                                    </tr>-->
<!--                                    </tfoot>-->
<!--                                </table>-->
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
    $(document).ready(function () {
        var example2 = $('#example2').DataTable({
            "lengthChange": false,
            "searching": true,
            "autoWidth": false,
            "responsive": true,
            "processing": true,
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
                "url": "sdo-includes/ccaa_datos.php",
                "type": "POST",
                "dataSrc": "",
                "data": function(d) {
                },
                "complete": function(jqXHR, textStatus) {
                    console.log("Datos recibidos del servidor:", jqXHR.responseJSON);
                },
                "error": function(jqXHR, textStatus, errorThrown) {
                    console.error("Error en la petición AJAX:", textStatus, errorThrown);
                }
            },
            "columns": [
                {
                    "data": null,
                    "render": function (data, type, row, meta) {
                        return meta.row + 1; // Devuelve el número de fila (empezando desde 1)
                    }
                },
                {"data": "OWNER_Name"},
                {"data": "OWNER_CODE"},
                {"data": "CARD_NUMBER"},
                {"data": "calidad_contractual"},
                {"data": "DEPT_ID"},
                {
                    "data": null,
                    "render": function(data, type, row) {
                        if (row.estadoAcceso === "CREDENCIAL ACTIVADA" || row.estadoAcceso === "CREDENCIAL ACTIVADA EN SMARTPSS") {
                            return '<span style="color: #00A100; font-weight: bold;">' + row.estadoAcceso + '</span>';
                        } else if (row.estadoAcceso === "CREDENCIAL SERÁ BLOQUEADA. NOTIFICAR.") {
                            return '<span style="color: #bda400; font-weight: bold;">' + row.estadoAcceso + '</span>';
                        } else {
                            return '<span style="color: #ff0707; font-weight: bold;">' + row.estadoAcceso + '</span>';
                        }
                    }
                },
                {
                    "data": null,
                    "render": function(data, type, row) {
                        if (row.intercom === "ESTACIONAMIENTO ACTIVADO") {
                            return '<span style="color: #00A100; font-weight: bold;">' + row.intercom + '</span>';
                        } else {
                            return '<span style="color: #ff0707; font-weight: bold;">' + row.intercom + '</span>';
                        }
                    }
                },
                {"data": "bloqueoAutomatico"}



                // Agrega más columnas según sea necesario
            ],
            "createdRow": function (row, data, dataIndex) {
                $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                $('td', row).css('text-align', 'center'); // Aplica el estilo de centrado vertical a todas las celdas
                $('td:eq(0)', row).html(dataIndex + 1); // Actualiza el contenido de la primera celda (columna "N°")
            },
            initComplete: function() {
                // Filtro para la columna intercom (columna 7)
                this.api().columns([7]).every(function() {
                    var column = this;
                    var select = $('<select style="width: 100%;"><option value="">¿Existe en Intercom?...</option></select>')
                        .appendTo($(column.header()).empty())
                        .on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                    // Utiliza Set para asegurar valores únicos
                    var uniqueValues = new Set();
                    column.data().each(function(d, j) {
                        var intercomValue = d.intercom || d;
                        if (intercomValue) {
                            uniqueValues.add(intercomValue);
                        }
                    });

                    // Agrega opciones únicas al select
                    uniqueValues.forEach(function(value) {
                        select.append('<option value="' + value + '">' + value + '</option>');
                    });
                });

                // Filtro para la columna intercom (columna 5)
                this.api().columns([5]).every(function() {
                    var column = this;
                    var select = $('<select style="width: 100%;"><option value="">Departamento...</option></select>')
                        .appendTo($(column.header()).empty())
                        .on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                    // Utiliza Set para asegurar valores únicos
                    var uniqueValues = new Set();
                    column.data().each(function(d, j) {
                        var intercomValue = d.intercom || d;
                        if (intercomValue) {
                            uniqueValues.add(intercomValue);
                        }
                    });

                    // Agrega opciones únicas al select
                    uniqueValues.forEach(function(value) {
                        select.append('<option value="' + value + '">' + value + '</option>');
                    });
                });

                // Filtro para la columna calidad_contractual (columna 4)
                this.api().columns([4]).every(function() {
                    var column = this;
                    var select = $('<select style="width: 100%;"><option value="">Calidad Juridica</option></select>')
                        .appendTo($(column.header()).empty())
                        .on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                    var uniqueValues = new Set();
                    column.data().each(function(d, j) {
                        var calidadValue = d.calidad_contractual || d;
                        if (calidadValue) {
                            uniqueValues.add(calidadValue);
                        }
                    });

                    uniqueValues.forEach(function(value) {
                        select.append('<option value="' + value + '">' + value + '</option>');
                    });
                });

                // Filtro para la columna estadoAcceso (columna 6)
                this.api().columns([6]).every(function() {
                    var column = this;
                    var select = $('<select style="width: 100%;"><option value="">Estado Credencial</option></select>')
                        .appendTo($(column.header()).empty())
                        .on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                    var uniqueValues = new Set();
                    column.data().each(function(d, j) {
                        var estadoValue = d.estadoAcceso || d;
                        if (estadoValue) {
                            uniqueValues.add(estadoValue);
                        }
                    });

                    uniqueValues.forEach(function(value) {
                        select.append('<option value="' + value + '">' + value + '</option>');
                    });
                });
            }



        });
    });
</script>
</body>

</html>

