<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];
$idDepartamento = $_SESSION['idDepartamento'];
$userUsuario = $_SESSION['userUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);
$nomUsuario = $_SESSION['nomUsuario'];


date_default_timezone_set('America/Santiago');

if (isset($_POST['idContrato'])) {
    $idCategoria = $_POST['idContrato'];
    $idCategoria2 = $_POST['idCategoria'];

    $sqlContratoRecepcionConforme = "SELECT * FROM `datos_contrato_recepcion_conforme` WHERE id = $idCategoria";
    $resContratoRecepcionConforme = mysqli_query($conexion, $sqlContratoRecepcionConforme);
    while($rowContratoRecepcionConforme = mysqli_fetch_array($resContratoRecepcionConforme)){
        $idContrato = $rowContratoRecepcionConforme['id'];
        $Licitacion = $rowContratoRecepcionConforme['licitacion'];
        $servicioContratado = $rowContratoRecepcionConforme['servicioContratado'];
        $centroCosto = $rowContratoRecepcionConforme['centroCosto'];
        $nContrato = $rowContratoRecepcionConforme['nContrato'];
        $prorroga = $rowContratoRecepcionConforme['prorroga'];
        $idOrdenCompra = $rowContratoRecepcionConforme['idOrdenCompra'];
        $fechaPrestacionServicio = $rowContratoRecepcionConforme['fechaPrestacionServicio'];
        $unidadMedida = $rowContratoRecepcionConforme['unidadMedida'];
        $vigenciaConvenio = $rowContratoRecepcionConforme['vigenciaConvenio'];
        $vigenciaConvenioDetalle = $rowContratoRecepcionConforme['vigenciaConvenioDetalle'];
        $fechaGeneral = $rowContratoRecepcionConforme['fechaGeneral'];
        $unidad = $rowContratoRecepcionConforme['unidad'];
        $titulo = $rowContratoRecepcionConforme['titulo'];


        $sqlMontos = "SELECT * FROM `datos_montos_recepcion_conforme` WHERE idContrato = $idContrato";
        $resMontos = mysqli_query($conexion, $sqlMontos);
        while($rowMontos = $resMontos->fetch_assoc()){
            $saldoDisponible = $rowMontos['saldoDisponible'];
            $aplicaMulta = $rowMontos['aplicaMulta'];
            $valorMensual = $rowMontos['valorMensual'];
            $detalleMultas = $rowMontos['detalleMulta'];
        }

        $sqlProveedor = "SELECT * FROM `datos_proveedor_recepcion_conforme` WHERE idContrato = $idContrato";
        $resProveedor = mysqli_query($conexion, $sqlProveedor);
        while($rowProveedor = $resProveedor->fetch_assoc()){
            $rutProveedor = $rowProveedor['rut'];
            $nombreProveedor = $rowProveedor['nombreProveedor'];
            $nombreAdministrador = $rowProveedor['nombreAdministrador'];

        }
    }


}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recepcion Conforme</title>

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
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"/>
    <!-- include summernote css-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- include summernote js-->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/mesaayuda-sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Recepcion Conforme - <?php echo $nombre?></h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">

                                <div class="col-lg-3 col-md-6 col-12 float-right" id="volverAtras2" name="volverAtras2">
                                    <!-- small box -->
                                    <a href="detalles-categorias-recepciones-conformes.php?idCategoria=<?php echo $idCategoria2; ?>"
                                        style="float: right;"
                                        class="btn bg-gradient-red btn-md text-white">
                                        <i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atrás
                                    </a>
                                </div>
                                <br>
                                <br>

                            </div>

                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <div class="row" id="rowMenu2" name="rowMenu2">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="../sdo-funciones/modificar-recepcion-conforme.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="idContrato" value="<?php echo $idContrato?>">
                                    <input type="hidden" name="idCategoria" value="<?php echo $idCategoria2?>">
                                    <fieldset class="border p-3">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Unidad: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="text" required class="form-control" id="unidad" value="<?php echo $unidad?>" name="unidad">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Establecimiento: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="text" required class="form-control" id="Establecimiento" name="Establecimiento" value="HOSPITAL SAN MARTIN DE QUILLOTA - HOSPITAL BIPROVINCIAL QUILLOTA PETORCA" READONLY placeholder="Nombre del proceso">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Fecha: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="month" required class="form-control" id="Fecha" name="Fecha" placeholder="Numero del Memo"
                                                            value="<?php echo date('Y-m', strtotime($fechaGeneral)); ?>"
                                                    >
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Titulo Recepcion Conforme: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="text" value="<?php echo $titulo?>" required class="form-control" id="titulo" name="titulo" placeholder="Titulo">
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <br>
                                    <fieldset class="border p-3">
                                        <h5>Datos del Contrato:</h5>
                                        <br>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Licitacion: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="text"  class="form-control" id="Licitacion" name="Licitacion" value="<?php echo $Licitacion?>" placeholder="Licitacion">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Descripción del Servicio Contratado: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="text"  class="form-control" id="servicioContratado" name="servicioContratado" value="<?php echo $servicioContratado?>" placeholder="Servicio Contratado">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Centro de costo que recibe el servicio: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="text"  class="form-control" id="centroCosto" name="centroCosto" value="<?php echo $centroCosto?>" placeholder="Centro de Costo">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">N° Contrato: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="text"  class="form-control" id="nContrato" name="nContrato" value="<?php echo $nContrato?>" placeholder="Numero de Contrato">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Prórroga: <code>*</code></label>

                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-pen-alt"></i>
                                                        </span>
                                                    </div>
                                                    <div class="input-group-text">
                                                        <?php
                                                        if($prorroga == 'on'){
                                                            ?>
                                                            <input type="checkbox" checked id="prorroga" name="prorroga" >
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <input type="checkbox" id="prorroga" name="prorroga" >
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">ID Orden de Compra: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="text"  class="form-control" id="idOrdenCompra" name="idOrdenCompra" value="<?php echo $idOrdenCompra?>" placeholder="ID Orden de Compra">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Fecha de Prestación del Servicio: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="month" class="form-control" id="FechaPrestacion"
                                                        name="FechaPrestacion"
                                                        value="<?php echo date('Y-m', strtotime($fechaPrestacionServicio)); ?>">

                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Unidad de Medida (Pesos Chilenos): <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <?php
                                                    if($saldoDisponible == 0){
                                                        ?>
                                                        <input type="number" class="form-control" id="unidadMedida" value="<?php echo $unidadMedida ?>" name="unidadMedida">
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <!--<input type="number" class="form-control" id="unidadMedida" value="<?php echo $saldoDisponible ?>" name="unidadMedida">-->
                                                        <input type="number" class="form-control" id="unidadMedida" value="<?php echo $unidadMedida ?>" name="unidadMedida">
                                                        <?php
                                                    }
                                                    ?>

                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Saldo Disponible Anterior (Pesos Chilenos): <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="number" class="form-control" id="saldoDisponibleAnterior" value="<?php echo $saldoDisponible ?>" name="saldoDisponibleAnterior">


                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Vigencia del Convenio: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="text"  class="form-control" id="vigenciaConvenio" value="<?php echo $vigenciaConvenio?>" name="vigenciaConvenio">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Vigencia del Convenio Detallado: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="text"  class="form-control" id="vigenciaConvenio2 " value="<?php echo $vigenciaConvenioDetalle?>" name="vigenciaConvenio2">
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <br>
                                    <fieldset class="border p-3">
                                        <h5>Datos del Proveedor:</h5>
                                        <br>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">RUT (Escriba el rut sin puntos ni guion): <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="text" required class="form-control" id="rutProveedor" value="<?php echo $rutProveedor?>" name="rutProveedor">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Nombre del Proveedor: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="text" required class="form-control" id="nombreProveedor" value="<?php echo $nombreProveedor?>" name="nombreProveedor">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Nombre del administrador de contrato o representante legal: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="text" required class="form-control" id="nombreAdministrador" value="<?php echo $nombreAdministrador?>" name="nombreAdministrador">
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <br>
                                    <fieldset class="border p-3">
                                        <h5>Datos de la contraparte técnica:</h5>
                                        <br>
                                        <button type="button" id="btnAgregarFuncionario" style="float: right" class="bg-info btn">
                                            + Agregar Funcionario
                                        </button>
                                        <br>
                                        <br>
                                        <div class="row" id="contenedorFuncionarios">

                                            <!-- Funcionario inicial -->
                                            <div class="funcionario border rounded p-3 mb-3">
                                                <?php
                                                $sqlTecnica = "SELECT nombreFuncionario, cargo, id
               FROM datos_tecnica_recepcion_contrato 
               WHERE idContrato = $idContrato";

                                                $resTecnica = mysqli_query($conexion, $sqlTecnica);

                                                /* SI HAY REGISTROS */
                                                if ($resTecnica && $resTecnica->num_rows > 0) {

                                                    while ($fila = $resTecnica->fetch_assoc()) {
                                                        $nombreFuncionario = $fila['nombreFuncionario'];
                                                        $cargo = $fila['cargo'];
                                                        $idTecnico = $fila['id'];
                                                        ?>

                                                        <div class="funcionario border rounded p-3 mb-3">
                                                            <input type="hidden" name="idTecnico[]" value="<?php echo htmlspecialchars($idTecnico); ?>"
                                                            >
                                                            <div class="row">
                                                                <div class="form-group col-md-6">
                                                                    <label>Nombre del Funcionario <code>*</code></label>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                                        </div>
                                                                        <input type="text"
                                                                            class="form-control"
                                                                            name="nombreContraparteTecnica[]"
                                                                            value="<?php echo htmlspecialchars($nombreFuncionario); ?>"
                                                                            required>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group col-md-6">
                                                                    <label>Cargo del Funcionario <code>*</code></label>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                                        </div>
                                                                        <input type="text"
                                                                            class="form-control"
                                                                            name="cargoContraparteTecnica[]"
                                                                            value="<?php echo htmlspecialchars($cargo); ?>"
                                                                            required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <?php
                                                    }

                                                    /* SI NO HAY REGISTROS */
                                                } else {
                                                    ?>
                                                    <div class="funcionario border rounded p-3 mb-3">
                                                        <div class="row">
                                                            <div class="form-group col-md-6">
                                                                <label>Nombre del Funcionario <code>*</code></label>
                                                                <input type="text" class="form-control" name="nombreContraparteTecnica[]" required>
                                                            </div>

                                                            <div class="form-group col-md-6">
                                                                <label>Cargo del Funcionario <code>*</code></label>
                                                                <input type="text" class="form-control" name="cargoContraparteTecnica[]" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                                ?>


                                            </div>
                                            <input type="hidden" name="contadorFuncionarios" id="contadorFuncionarios" value="1">
                                        </div>
                                    </fieldset>
                                    <br>
                                    <fieldset class="border p-3">
                                        <h5>Ejecución del servicio en caso de contrato:</h5>
                                        <br>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Valor Mensual: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="number" required class="form-control" value="<?php echo $valorMensual?>" id="valorMensual" name="valorMensual">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Saldo Disponible: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <input type="number" required class="form-control" value="<?php echo $saldoDisponible?>"  readonly id="saldoDisponible" name="saldoDisponible">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Aplica Multas: <code>*</code></label>

                                                <div class="input-group">
                                                    <div class="input-group-prepend">
            <span class="input-group-text">
                <i class="fas fa-pen-alt"></i>
            </span>
                                                    </div>
                                                    <?php
                                                    if($aplicaMulta == 0){
                                                        ?>
                                                        <div class="input-group-text">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="aplicaMultas" id="siMultas" value="1" required>
                                                                <label class="form-check-label" for="siMultas">Sí</label>
                                                            </div>

                                                            <div class="form-check form-check-inline ml-3">
                                                                <input class="form-check-input" checked type="radio" name="aplicaMultas" id="noMultas" value="0">
                                                                <label class="form-check-label" for="noMultas">No</label>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }else if($aplicaMulta == 1){
                                                        ?>
                                                        <div class="input-group-text">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" checked type="radio" name="aplicaMultas" id="siMultas" value="1" required>
                                                                <label class="form-check-label" for="siMultas">Sí</label>
                                                            </div>

                                                            <div class="form-check form-check-inline ml-3">
                                                                <input class="form-check-input"  type="radio" name="aplicaMultas" id="noMultas" value="0">
                                                                <label class="form-check-label" for="noMultas">No</label>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }else{
                                                        ?>
                                                        <div class="input-group-text">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="aplicaMultas" id="siMultas" value="1" required>
                                                                <label class="form-check-label" for="siMultas">Sí</label>
                                                            </div>

                                                            <div class="form-check form-check-inline ml-3">
                                                                <input class="form-check-input"  type="radio" name="aplicaMultas" id="noMultas" value="0">
                                                                <label class="form-check-label" for="noMultas">No</label>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>

                                                </div>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Detalle Multas: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                                    </div>
                                                    <textarea class="form-control" name="detalleMultas" style="resize: none" rows="5"><?php echo $detalleMultas; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <br>
                                    <button type="submit" id="btnRegistrar" class="btn btn-block bg-gradient-success btn-md text-black text-center">Modificar</button>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
</body>
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
<script src="../plugins/select2/js/select2.full.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<script>
    let contador = 1;

    const inputContador = document.getElementById('contadorFuncionarios');
    const contenedor = document.getElementById('contenedorFuncionarios');

    document.getElementById('btnAgregarFuncionario').addEventListener('click', function () {
        contador++;
        inputContador.value = contador;

        const html = `
        <div class="funcionario border rounded p-3 mb-3">
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Nombre del Funcionario <code>*</code></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                        </div>
                        <input type="text" class="form-control" name="nombreContraparteTecnica[]" required>
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label>Cargo del Funcionario <code>*</code></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                        </div>
                        <input type="text" class="form-control" name="cargoContraparteTecnica[]" required>
                    </div>
                </div>

                <div class="col-12 text-right">
                    <button type="button" class="btn btn-danger btn-sm btnEliminarFuncionario">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </div>
            </div>
        </div>
        `;

        contenedor.insertAdjacentHTML('beforeend', html);
    });

    // Eliminar funcionario (delegación de eventos)
    contenedor.addEventListener('click', function (e) {
        if (e.target.closest('.btnEliminarFuncionario')) {
            e.target.closest('.funcionario').remove();

            if (contador > 1) {
                contador--;
                inputContador.value = contador;
            }
        }
    });
</script>

<input type="hidden" name="contadorFuncionarios" id="contadorFuncionarios" value="1">
<script>
    function calcularSaldo() {
        const unidadInput = document.getElementById('saldoDisponibleAnterior');
        const valorInput  = document.getElementById('valorMensual');
        const saldoInput  = document.getElementById('saldoDisponible');

        const unidad = unidadInput.value !== '' ? parseInt(unidadInput.value, 10) : 0;
        const valor  = valorInput.value  !== '' ? parseInt(valorInput.value, 10)  : 0;

        let saldo = unidad - valor;

        // Evitar NaN y negativos
        if (isNaN(saldo) || saldo < 0) {
            saldo = 0;
        }

        saldoInput.value = saldo; // entero
    }

    document.getElementById('saldoDisponibleAnterior').addEventListener('input', calcularSaldo);
    document.getElementById('valorMensual').addEventListener('input', calcularSaldo);
</script>


</html>
