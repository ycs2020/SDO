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
    <title>Listado de Hojas Tecnicas</title>

    <link rel="icon" href="../dist/img/controlCent-HBQP-120.png" type="image/icon type">
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
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
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
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-xl-10 col-lg-9 col-md-8 col-12">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <a style="pointer-events: none;" class="btn font-weight-bold">Listado de Hojas Tecnicas</a>
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
                            <!-- /.card-header -->
                            <div class="card-body">


                                <div class="row">
                                    <div class="col-12">
                                        <input type="hidden" id="idDeptoHT" name="idDeptoHT" value="<?php echo $idDepartamento; ?>">

                                        <table id="miTabla" class="table table-bordered text-center table-sm" data-depto="<?php echo $idDepartamento; ?>">
                                            <thead class="bg-secondary">
                                            <tr>
                                                <th>N°</th>
                                                <th>FOLIO</th>
                                                <th>PERSONAL</th>
                                                <th>SOLICITANTE</th>
                                                <?php if ($idDepartamento == 6): ?>
                                                    <th>UNIDAD SOLICITANTE</th>
                                                    <th>N° SERIE EQUIPO ASOCIADO</th>
                                                    <th>NOMBRE DEL EQUIPO ASOCIADO</th>
                                                    <th>N° INVENTARIO EQUIPO ASOCIADO</th>
                                                <?php endif; ?>
                                                <th>TICKET ASOCIADO</th>
                                                <th>FECHA</th>
                                                <th>TIEMPO</th>
                                                <th>ARCHIVO ADJUNTO</th>
                                                <th>VISUALIZAR HT</th>
                                                <th>MODIFICAR HT</th>
                                            </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot class="bg-secondary">
                                            <tr>
                                                <th>N°</th>
                                                <th>FOLIO</th>
                                                <th>PERSONAL</th>
                                                <th>SOLICITANTE</th>
                                                <?php if ($idDepartamento == 6): ?>
                                                    <th>UNIDAD SOLICITANTE</th>
                                                    <th>N° SERIE EQUIPO ASOCIADO</th>
                                                    <th>NOMBRE DEL EQUIPO ASOCIADO</th>
                                                    <th>N° INVENTARIO EQUIPO ASOCIADO</th>
                                                <?php endif; ?>
                                                <th>TICKET ASOCIADO</th>
                                                <th>FECHA</th>
                                                <th>TIEMPO</th>
                                                <th>ARCHIVO ADJUNTO</th>
                                                <th>VISUALIZAR HT</th>
                                                <th>MODIFICAR HT</th>
                                            </tr>
                                            </tfoot>
                                        </table>

                                        <script>
                                            var nomUsuario = <?php echo json_encode($nomUsuario); ?>;
                                            nomUsuario = nomUsuario.toUpperCase();

                                            $(document).ready(function () {
                                                var idDepto = $('#miTabla').data('depto');

                                                var columnas = [
                                                    {
                                                        data: null,
                                                        render: function (data, type, row, meta) {
                                                            return meta.row + 1;
                                                        }
                                                    },
                                                    { data: "folioOrden" },
                                                    { data: "nomTecnicoRecepcionador" },
                                                    { data: "nomSolicitante" }
                                                ];

                                                if (idDepto == 6) {
                                                    columnas.push(
                                                        { data: "recintoOrden" },
                                                        { data: "numeroSerie" },
                                                        { data: "nombreEquipo" },
                                                        { data: "numeroInventario" }
                                                    );
                                                }

                                                columnas.push(
                                                    { data: "ticketAsociado" },
                                                    { data: "fechaOrden" },
                                                    { data: "diferencia" },
                                                    {
                                                        data: null,
                                                        render: function (data, type, row) {
                                                            if (row.cantidad == 0) {
                                                                return 'Sin Archivos Adjuntos';
                                                            } else {
                                                                return `
                        <form style="margin: 0;" action="ordenes-listado-general.php" method="post">
                            <input type="hidden" name="idOrden" value="${row.idOrden}">
                            <input type="hidden" name="folio" value="${row.folioOrden}">
                            <button type="submit" class="btn btn-warning btn-block"><i class="fas fa-download"></i></button>
                        </form>`;
                                                            }
                                                        }
                                                    },
                                                    {
                                                        data: null,
                                                        render: function (data, type, row) {
                                                            var actionPDF = (idDepto == 6)
                                                                ? "../sdo-pdf/SDO-OrdenTrabajoGeneral-Inge-Clinica.php"
                                                                : "../sdo-pdf/SDO-OrdenTrabajoGeneral.php";

                                                            return `
                                                            <form style="margin: 0; text-align: center;" target="_blank" action="${actionPDF}" method="post">
                                                                <input type="hidden" name="idOrden" value="${row.idOrden}">
                                                                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></button>
                                                            </form>`;
                                                        }
                                                    },
                                                    {
                                                        data: null,
                                                        render: function (data, type, row) {
                                                            if (row.isVisado === 0 && row.nomTecnicoRecepcionador.includes(nomUsuario)) {
                                                                var modificarURL = (idDepto == 6)
                                                                    ? "ordenes-modificar-general-inge-clinica.php"
                                                                    : "ordenes-modificar-general.php";

                                                                return `
                                                                        <form style="margin: 0; text-align: center;" action="${modificarURL}" method="post">
                                                                            <input type="hidden" name="idOrden" value="${row.idOrden}">
                                                                            <button type="submit" class="btn btn-warning btn-sm"><i class="fas fa-pen"></i></button>
                                                                        </form>`;
                                                            } else {
                                                                return 'Deshabilitado' + (row.isVisado ? '<br>HT Visada.' : '');
                                                            }
                                                        }
                                                    }
                                                );

                                                var tabla = $('#miTabla').DataTable({
                                                    lengthChange: false,
                                                    searching: true,
                                                    autoWidth: false,
                                                    responsive: true,
                                                    ordering: false,
                                                    language: {
                                                        lengthMenu: "Visualizando _MENU_ Registros por Página",
                                                        zeroRecords: "No se han encontrado Registros.",
                                                        info: "Mostrando página _PAGE_ de _PAGES_",
                                                        infoEmpty: "No existen Registros.",
                                                        infoFiltered: "(Filtrado desde _MAX_ Registros Totales)",
                                                        search: "Buscar:",
                                                        processing: "Procesando...",
                                                        paginate: {
                                                            previous: "Anterior",
                                                            next: "Siguiente"
                                                        }
                                                    },
                                                    ajax: {
                                                        url: "/sdo-includes/ht_cargar_listado_personal.php",
                                                        type: "POST",
                                                        data: function (d) {
                                                            d.idDepartamento = $('#idDeptoHT').val();
                                                        },
                                                        dataSrc: ""
                                                    },
                                                    columns: columnas,
                                                    createdRow: function (row, data, dataIndex) {
                                                        $('td', row).css('vertical-align', 'middle');
                                                        $('td:eq(0)', row).html(dataIndex + 1);
                                                    },
                                                    initComplete: function () {
                                                        // Filtro solo para Depto 6 en columna "numeroSerie"
                                                        if (idDepto == 6) {
                                                            this.api().columns([5]).every(function () {
                                                                var column = this;
                                                                var select = $('<select style="width: 100%;"><option value="">Numero de Serie</option></select>')
                                                                    .appendTo($(column.header()).empty())
                                                                    .on('change', function () {
                                                                        var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                                                        column.search(val ? '^' + val + '$' : '', true, false).draw();
                                                                    });
                                                                column.data().unique().sort().each(function (d) {
                                                                    select.append('<option value="' + d + '">' + d + '</option>');
                                                                });
                                                            });
                                                        }
                                                    }
                                                });
                                            });

                                        </script>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


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
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<script src="js/dark-mode.js"></script>
</body>

</html>