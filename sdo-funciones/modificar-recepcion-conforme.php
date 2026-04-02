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



if(isset($_POST['idContrato'])) {
    $idContrato = $_POST['idContrato'];
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

    $sql = "UPDATE datos_contrato_recepcion_conforme 
SET unidad = '$unidad',
`nombreEstablecimiento`='$Establecimiento',
`fechaGeneral`='$FechaDB',
`licitacion`='$Licitacion',
`servicioContratado`='$servicioContratado',
`centroCosto`='$centroCosto',
`nContrato`='$nContrato',
`prorroga`='$prorroga',
`idOrdenCompra`='$idOrdenCompra',
`fechaPrestacionServicio`='$FechaDBPrestacion',
`unidadMedida`='$unidadMedida',
`vigenciaConvenio`='$vigenciaConvenio',
`vigenciaConvenioDetalle`='$vigenciaConvenio2'
WHERE id ='$idContrato'";

    $res = mysqli_query($conexion,$sql);

    if(!$res){
        header('Location: ../'.$rutaDireccion.'/detalles-categorias-recepciones-conformes.php?status=error&etapa=contrato&idCategoria='.$idCategoria);
        exit();
    }


    /* ===================== PROVEEDOR ===================== */

    $sqlUpdate = "UPDATE `datos_proveedor_recepcion_conforme`
SET `rut`='$rutProveedor',
`nombreProveedor`='$nombreProveedor',
`nombreAdministrador`='$nombreAdministrador'
WHERE idContrato = '$idContrato'";

    $resUpdate = mysqli_query($conexion,$sqlUpdate);

    if(!$resUpdate){
        header('Location: ../'.$rutaDireccion.'/detalles-categorias-recepciones-conformes.php?status=error&etapa=proveedor&idCategoria='.$idCategoria);
        exit();
    }


    /* ===================== CONTRAPARTE TÉCNICA ===================== */

    $ids     = $_POST['idTecnico'];
    $nombre  = $_POST['nombreContraparteTecnica'];
    $cargo   = $_POST['cargoContraparteTecnica'];

    for ($i = 0; $i < count($ids); $i++) {

        if (empty($ids[$i]) || empty($nombre[$i]) || empty($cargo[$i])) {
            continue;
        }

        $sqlTecnica = "UPDATE datos_tecnica_recepcion_contrato
                   SET nombreFuncionario = ?, cargo = ?
                   WHERE id = ?";

        $stmt = $conexion->prepare($sqlTecnica);

        if(!$stmt){
            header('Location: ../'.$rutaDireccion.'/detalles-categorias-recepciones-conformes.php?status=error&etapa=tecnica&idCategoria='.$idCategoria);
            exit();
        }

        $stmt->bind_param("ssi", $nombre[$i], $cargo[$i], $ids[$i]);

        if(!$stmt->execute()){
            header('Location: ../'.$rutaDireccion.'/detalles-categorias-recepciones-conformes.php?status=error&etapa=tecnica&idCategoria='.$idCategoria);
            exit();
        }
    }


    /* ===================== MONTOS ===================== */

    $sqlUpdateMontos = "UPDATE `datos_montos_recepcion_conforme`
SET `valorMensual`='$valorMensual',
`saldoDisponible`='$saldoDisponible',
`aplicaMulta`='$aplicaMultas',
`detalleMulta`='$detalleMultas'
WHERE idContrato = '$idContrato'";

    $resUpdateMontos = mysqli_query($conexion,$sqlUpdateMontos);

    if(!$resUpdateMontos){
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