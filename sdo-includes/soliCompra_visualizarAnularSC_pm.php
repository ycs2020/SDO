<?php
ob_start();
$LogoSSVQ = "TCPDF/examples/images/LogoSSVQ.png";
$LogoSSVQ2 = "data:image/png;base64," . base64_encode(file_get_contents($LogoSSVQ));

$LogoGob = "../dist/img/GOB.png";
$LogoGob2 = "data:image/png;base64," . base64_encode(file_get_contents($LogoGob));

$controlCent = "TCPDF/examples/images/controlCent-HBQP-120.jpg";
$controlCent2 = "data:image/png;base64," . base64_encode(file_get_contents($controlCent));

$controlCentFull = "TCPDF/examples/images/controlCent-HBQP-Full-2.jpg";
$controlCentFull2 = "data:image/png;base64," . base64_encode(file_get_contents($controlCentFull));

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);
$ruta = "";

if ($rolUsuario2 == "personal") {
    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $ruta = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $ruta = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $ruta = "sdo-directiva";
} else if ($rolUsuario2 == "mesa ayuda") {
    $ruta = "sdo-mesa-ayuda";
} else if($rolUsuario2 == "oficina tecnica"){
    $ruta = "sdo-oficinaTecnica";
}else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if (isset($_POST['idSoliCompra'])) {

    $idSoliCompra = trim($_POST['idSoliCompra']);

    if ($idSoliCompra == 0 || empty($idSoliCompra)) {
        header('Location: solicitudes_recibidas.php?errorCargarSolicitud=true');
    } else {
        $datosSoliCompra = array();
        $datosSoliCompra = getSoliCompra_pm($idSoliCompra);

        $idSoliCompra2 = $datosSoliCompra['idSoliCompra'];
        $numSoliCompra = $datosSoliCompra['numSoliCompra'];
        $folioSoliCompra = $datosSoliCompra['folioSoliCompra'];
        $fechaSoliCompra = $datosSoliCompra['fechaSoliCompra'];
        $personalSoliCompra = $datosSoliCompra['personalSoliCompra'];
        $unidadSoliCompra = $datosSoliCompra['unidadSoliCompra'];
        $emailSoliCompra = $datosSoliCompra['emailSoliCompra'];

        $argumentosSoliCompra = $datosSoliCompra['argumentosSoliCompra'];

        $adjuntaCotizacion = $datosSoliCompra['adjuntaCotizacion'];

        $siCotiza = '';

        $noCotiza = '';

        if ($adjuntaCotizacion == 1) {

            $siCotiza = 'X';
        } else if ($adjuntaCotizacion == 0) {

            $noCotiza = 'X';
        } else {

            $noCotiza = 'X';
        }

        $estadoSoliCompra = $datosSoliCompra['estadoSoliCompra'];

        $fechaSC = strtotime($fechaSoliCompra);
        $fechaSC = fechaEspañol2($fechaSC);

        $horaSC = date('H:i', strtotime($fechaSoliCompra));

        $_SESSION['idSoliCompra'] = $idSoliCompra;

        $datosAnulacion = array();

        $datosAnulacion = getAnulacionSCPM($idSoliCompra);

        $idAnulacion2 = $datosAnulacion['idAnulacion'];
        $detalleAnulacion = $datosAnulacion['detalleAnulacion'];
        $personalAnulacion = $datosAnulacion['personalAnulacion'];
        $unidadAnulacion = $datosAnulacion['unidadAnulacion'];
        $fechaAnulacion = $datosAnulacion['fechaAnulacion'];
        $idSoliCompraPM = $datosAnulacion['idSoliCompraPM'];
    }
} else if (isset($_SESSION['idSoliCompra'])) {
    $idSoliCompra = trim($_SESSION['idSoliCompra']);

    if ($idSoliCompra == 0 || empty($idSoliCompra)) {
        header('Location: solicitudes_recibidas.php?errorCargarSolicitud=true');
    } else {
        $datosSoliCompra = array();
        $datosSoliCompra = getSoliCompra_pm($idSoliCompra);

        $idSoliCompra2 = $datosSoliCompra['idSoliCompra'];
        $numSoliCompra = $datosSoliCompra['numSoliCompra'];
        $folioSoliCompra = $datosSoliCompra['folioSoliCompra'];
        $fechaSoliCompra = $datosSoliCompra['fechaSoliCompra'];
        $personalSoliCompra = $datosSoliCompra['personalSoliCompra'];
        $unidadSoliCompra = $datosSoliCompra['unidadSoliCompra'];
        $emailSoliCompra = $datosSoliCompra['emailSoliCompra'];

        $argumentosSoliCompra = $datosSoliCompra['argumentosSoliCompra'];

        $adjuntaCotizacion = $datosSoliCompra['adjuntaCotizacion'];

        $siCotiza = '';

        $noCotiza = '';

        if ($adjuntaCotizacion == 1) {

            $siCotiza = 'X';
        } else if ($adjuntaCotizacion == 0) {

            $noCotiza = 'X';
        } else {

            $noCotiza = 'X';
        }

        $estadoSoliCompra = $datosSoliCompra['estadoSoliCompra'];

        $fechaSC = strtotime($fechaSoliCompra);
        $fechaSC = fechaEspañol2($fechaSC);

        $fechaSC = trim($fechaSC);

        $horaSC = date('H:i', strtotime($fechaSoliCompra));

        $_SESSION['idSoliCompra'] = $idSoliCompra;

        $datosAnulacion = array();

        $datosAnulacion = getAnulacionSCPM($idSoliCompra2);

        $idAnulacion2 = $datosAnulacion['idAnulacion'];
        $detalleAnulacion = $datosAnulacion['detalleAnulacion'];
        $personalAnulacion = $datosAnulacion['personalAnulacion'];
        $unidadAnulacion = $datosAnulacion['unidadAnulacion'];
        $fechaAnulacion = $datosAnulacion['fechaAnulacion'];
        $idSoliCompraPM = $datosAnulacion['idSoliCompraPM'];
    }
} else {
    header('Location: soliCompra_pm_administracion.php?errorSolicitudCompra=true');
}


if ($numSoliCompra == 0) {
    $numSoliCompra = "";
}
?>
<!DOCTYPE html>
<html>
<table cellpadding="10" border="1">
    <tr>
        <td style="text-align: center;" rowspan="3" width="20%;">
            <img src="../sdo-pdf/TCPDF/examples/images/LogoSSVQ.png" style="text-align: center; width:100px; height:100px; margin-top:100px;">
        </td>
        <td width="55%;" style="text-align: center; vertical-align: middle;">
            <b>HOSPITAL BIPROVINCIAL QUILLOTA - PETORCA</b>
        </td>
        <td rowspan="3" width="25%;" style="text-align: center; vertical-align: middle;">
            <h2 style="text-align: center; vertical-align: middle;"><center>Folio N°<br></center></h2>

            <h5 style="text-align: center; vertical-align: middle;"><?php echo $folioSoliCompra;?></h5>
        </td>
    </tr>
    <tr>
        <td style="text-align: center; vertical-align: middle;">
            SUBDIRECCIÓN DE OPERACIONES
            <br>
            CONTROL CENTRALIZADO
        </td>
    </tr>
    <tr>
        <td style="text-align: center; vertical-align: middle;">
            ANULACI&Oacute;N DE SOLICITUD DE COMPRA
        </td>
    </tr>
</table>
<br>
<br>
<br>';
</html>
