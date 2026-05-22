<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Control de seguridad básico para evitar Notice de PHP
$idUsuario   = $_SESSION['idUsuario'] ?? null;
$userUsuario = $_SESSION['userUsuario'] ?? null;
$rolUsuario  = $_SESSION['rolUsuario'] ?? null;
$rolUsuario2 = $rolUsuario ? strtolower($rolUsuario) : '';
$nomUsuario  = $_SESSION['nomUsuario'] ?? null;
$descargaConfirma = "";

date_default_timezone_set('America/Santiago');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Trabajos</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <style>
        #tablaOTEjecucion tbody td {
            vertical-align: middle !important;
            text-align: center !important;
            padding: 10px 8px;
        }

        #tablaOTEjecucion thead th {
            vertical-align: middle !important;
            letter-spacing: 0.5px;
        }

        .btn-circle {
            width: 32px;
            height: 32px;
            padding: 6px 0;
            border-radius: 50%;
            text-align: center;
            font-size: 12px;
            line-height: 1.42857;
        }

        .ot-asignada-usuario {
            background-color: rgba(255, 193, 7, 0.08) !important;
            border-left: 4px solid #ffc107 !important;
        }

        .table-hover tbody tr.ot-asignada-usuario:hover {
            background-color: rgba(255, 193, 7, 0.15) !important;
        }

        .fila-filtros th {
            padding: 5px 8px !important;
            background-color: #343a40 !important;
        }

        .fila-filtros select {
            height: calc(1.8rem + 2px) !important;
            font-size: 0.85rem !important;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">

    <?php include '../sdo-templates/bodega-panol-sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-9 col-lg-9 col-md-6 col-12 mb-3 d-flex">
                        <div class="card w-100 m-0 rounded-3 overflow-hidden border-0 evaluation-card">
                            <div class="card-body d-flex align-items-center bg-gradient-primary p-3">
                                <h5 class="mb-0 text-white font-weight-bold">Listado de Ordenes de Trabajo</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-12 mb-3 d-flex">
                        <div class="card w-100 m-0 rounded-3 overflow-hidden border-0 bg-gradient-danger p-0 shadow-sm">
                            <a href="index.php" class="text-white w-100 h-100 d-flex align-items-center justify-content-center p-3 text-decoration-none btn-block transition-all" style="transition: background 0.2s ease;">
                                <i class="fa fa-chevron-left mr-2"></i> Volver Atrás
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row" id="rowMenu1" name="rowMenu1">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="tablaOTEjecucion" class="table table-bordered table-striped table-hover cell-border w-100" style="font-size:85%">
                                        <thead class="thead-dark text-center">
                                        <tr>
                                            <th>Fecha de Trabajo</th>
                                            <th>Folio de Trabajo</th>
                                            <th>Turno de Trabajo</th>
                                            <th>Departamento</th>
                                            <th>Solicitante</th>
                                            <th>Técnico Responsable</th>
                                            <th>Estado</th>
                                            <th>Descripción</th>
                                            <th>Detalles</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot class="thead-dark text-center">
                                        <tr>
                                            <th>Fecha de Trabajo</th>
                                            <th>Folio de Trabajo</th>
                                            <th>Turno de Trabajo</th>
                                            <th>Departamento</th>
                                            <th>Solicitante</th>
                                            <th>Técnico Responsable</th>
                                            <th></th>
                                            <th>Descripción</th>
                                            <th>Detalles</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <footer class="main-footer">
        <strong>Copyright &copy; 2022 - <?= date('Y'); ?>. Derechos Reservados</strong>
    </footer>
</div>

<script src="../plugins/jquery/jquery.min.js"></script>
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../dist/js/adminlte.min.js"></script>
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script src="js/dark-mode.js"></script>

<script src="../dist/js/datatables-config.js"></script>

<script>
    toastr.options = {
        "positionClass": "toast-top-right",
        "timeOut": "4000",
        "closeButton": true,
        "progressBar": true,
        "preventDuplicates": true
    };

    $(document).ready(function() {
        // Clonar la fila del header para los selectores de filtros superiores
        $('#tablaOTEjecucion thead tr').clone(true).addClass('fila-filtros').appendTo('#tablaOTEjecucion thead');

        $('#tablaOTEjecucion').DataTable({
            ...dataTableConfig,
            "ordering": false,
            "order": [[0, "desc"]],
            "ajax": {
                "url": "../sdo-includes/ordenes_trabajos/ot_ejecucion_listado.php",
                "type": "POST",
                "dataSrc": "data"
            },
            "columns": [
                { "data": "fecha_programacion" },
                { "data": "folio_trabajo", "className": "text-center" },
                { "data": "turno_trabajo" },
                {
                    "data": "departamento",
                    "render": function(data, type, row) {
                        return data;
                    }
                },
                { "data": "solicitante" },
                { "data": "tecnico_responsable" },
                {
                    "data": "estado",
                    "className": "text-center",
                    "render": function(data, type, row) {
                        return data;
                    }
                },
                { "data": "descripcion" },
                { "data": "detalles", "className": "text-center" }
            ],
            initComplete: function () {
                var api = this.api();

                api.columns().eq(0).each(function (colIdx) {
                    var cell = $('.fila-filtros th').eq(colIdx);

                    // Filtro para Departamento (Columna índice 3) o Estado (Columna índice 6)
                    if (colIdx === 3 || colIdx === 6) {
                        var placeholder = colIdx === 3 ? "🔍 DEPARTAMENTO" : "🔍 ESTADO";

                        var select = $('<select class="form-control text-center font-weight-bold text-uppercase"><option value="">' + placeholder + '</option></select>')
                            .appendTo(cell.empty())
                            .on('change', function () {
                                var val = $(this).val();
                                // Búsqueda parcial limpia para que no se rompa por los tags HTML internos
                                api.column(colIdx).search(val ? val : '', false, false).draw();
                            });

                        var elementosAgregados = [];

                        api.column(colIdx).data().unique().sort().each(function (d, j) {
                            if (d) {
                                // Crear un contenedor temporal en memoria para extraer el string limpio
                                var tmp = document.createElement("DIV");
                                tmp.innerHTML = d;
                                var textClean = (tmp.textContent || tmp.innerText || "").trim();

                                if (textClean && elementosAgregados.indexOf(textClean) === -1) {
                                    elementosAgregados.push(textClean);
                                    select.append('<option value="' + textClean + '">' + textClean + '</option>');
                                }
                            }
                        });
                    } else {
                        // Limpiar las celdas donde no aplica ningún selector de filtro
                        cell.empty();
                    }
                });
            }
        });
    });
</script>

</body>
</html>