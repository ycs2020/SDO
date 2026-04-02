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


$rutaDireccion = "";

if ($rolUsuario2 == "personal") {
    $rutaDireccion = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $rutaDireccion = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $rutaDireccion = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $rutaDireccion = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $rutaDireccion = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $rutaDireccion = "sdo-directiva";
} else if ($rolUsuario2 == "mesa ayuda"){
    $rutaDireccion = "sdo-mesa-ayuda";
}else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if(isset($_POST['idCategoria'])) {
    $idCategoria = $_POST['idCategoria'];
    $Establecimiento = $_POST['Establecimiento'];
    $Fecha = $_POST['Fecha'];
    $FechaDB = $Fecha . '-01 00:00:00';

    $Licitacion = $_POST['Licitacion'];
    $servicioContratado = $_POST['servicioContratado'];
    $centroCosto = $_POST['centroCosto'];
    $nContrato = $_POST['nContrato'];
    $prorroga = $_POST['prorroga'];
    $idOrdenCompra = $_POST['idOrdenCompra'];
    $FechaPrestacion = $_POST['FechaPrestacion'];
    $FechaDBPrestacion = $FechaPrestacion . '-01 00:00:00';

    $unidadMedida = $_POST['unidadMedida'];
    $vigenciaConvenio = $_POST['vigenciaConvenio'];
    $vigenciaConvenio2 = $_POST['vigenciaConvenio2'];

    $rutProveedor = $_POST['rutProveedor'];
    $nombreProveedor = $_POST['nombreProveedor'];
    $nombreAdministrador = $_POST['nombreAdministrador'];

    $valorMensual = $_POST['valorMensual'];
    $saldoDisponible = $_POST['saldoDisponible'];
    $aplicaMultas = $_POST['aplicaMultas'];
    $detalleMultas = $_POST['detalleMultas'];
    $unidad = $_POST['unidad'];
    $titulo = $_POST['titulo'];

    //$contadorFuncionarios = $_POST['contadorFuncionarios'];

    $sql = "INSERT INTO `datos_contrato_recepcion_conforme`
(`nombreEstablecimiento`, `fechaGeneral`, `licitacion`, `servicioContratado`, 
`centroCosto`, `nContrato`, `prorroga`, `idOrdenCompra`, 
`fechaPrestacionServicio`, `unidadMedida`, `vigenciaConvenio`, 
`vigenciaConvenioDetalle`,titulo, unidad, `idCategoria`) 
VALUES 
('$Establecimiento','$FechaDB','$Licitacion','$servicioContratado',
'$centroCosto','$nContrato','$prorroga','$idOrdenCompra',
'$FechaDBPrestacion',$unidadMedida,'$vigenciaConvenio',
'$vigenciaConvenio2','$titulo','$unidad',$idCategoria)";

    $res = mysqli_query($conexion,$sql);

    if(!$res){
        //header("Location: ../'. $rutaDireccion .'/detalles-categorias-recepciones-conformes.php?status=error&etapa=contrato");
        header('Location: ../'.$rutaDireccion.'/detalles-categorias-recepciones-conformes.php?status=error&etapa=contrato&idCategoria='.$idCategoria);

        exit();
    }

    $idContrato = mysqli_insert_id($conexion);


    /* ===================== PROVEEDOR ===================== */

    $sqlProveedor = "INSERT INTO `datos_proveedor_recepcion_conforme`
(`rut`, `nombreProveedor`, `nombreAdministrador`, `idContrato`) 
VALUES 
('$rutProveedor','$nombreProveedor','$nombreAdministrador',$idContrato)";

    $resProveedor = mysqli_query($conexion,$sqlProveedor);

    if(!$resProveedor){
        header('Location: ../'.$rutaDireccion.'/detalles-categorias-recepciones-conformes.php?error=true&etapa=proveedor&idCategoria='.$idCategoria);
        exit();
    }


    /* ===================== CONTRAPARTE TÉCNICA ===================== */

    $nombre = $_POST['nombreContraparteTecnica'];
    $cargo  = $_POST['cargoContraparteTecnica'];

    for ($i = 0; $i < count($nombre); $i++) {

        if (empty($nombre[$i]) || empty($cargo[$i])) {
            continue;
        }

        $sqlTecnica = "INSERT INTO datos_tecnica_recepcion_contrato 
    (nombreFuncionario, cargo, idContrato)
    VALUES (?, ?, ?)";

        $stmt = $conexion->prepare($sqlTecnica);

        if(!$stmt){
            header('Location: ../'.$rutaDireccion.'/detalles-categorias-recepciones-conformes.php?status=error&etapa=tecnica&idCategoria='.$idCategoria);
            exit();
        }

        $stmt->bind_param("ssi", $nombre[$i], $cargo[$i], $idContrato);

        if(!$stmt->execute()){
            header('Location: ../'.$rutaDireccion.'/detalles-categorias-recepciones-conformes.php?status=error&etapa=tecnica&idCategoria='.$idCategoria);
            exit();
        }
    }


    /* ===================== MONTOS ===================== */

    $sqlMontos = "INSERT INTO `datos_montos_recepcion_conforme`
(`valorMensual`, `saldoDisponible`, `aplicaMulta`, 
`detalleMulta`, `idContrato`, `idCategoria`) 
VALUES 
($valorMensual,$saldoDisponible,'$aplicaMultas',
'$detalleMultas',$idContrato,$idCategoria)";

    $resMontos = mysqli_query($conexion,$sqlMontos);

    if(!$resMontos){
        header('Location: ../'.$rutaDireccion.'/detalles-categorias-recepciones-conformes.php?status=error&etapa=montos&idCategoria='.$idCategoria);

        exit();
    }


    /* ===================== TODO OK ===================== */
    header('Location: ../'.$rutaDireccion.'/detalles-categorias-recepciones-conformes.php?status=ok&idCategoria='.$idCategoria);
    exit();


}else{
    header('Location: ../'. $rutaDireccion .'/detalles-categorias-recepciones-conformes.php?errorGeneral=true');
    exit();
}

