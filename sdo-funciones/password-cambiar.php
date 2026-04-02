<?php
include("conexion.php");
include("funciones.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['nuevaPassword'], $_POST['idUsuario'], $_POST['rolUsuario'])) {

    $nuevaPassword = trim($_POST['nuevaPassword']);
    $idUsuario     = $_POST['idUsuario'];
    $rolUsuario    = $_POST['rolUsuario'];

    // Validaciones iniciales
    if (empty($nuevaPassword) || empty($idUsuario) || empty($rolUsuario)) {
        header('Location: ../sdo-flujo/login_index.php?errorCambioPassword=true');
        exit;
    }

// Verificar si es una clave temporal tipo @sdo2025
    if (
        preg_match('/^@sdo[0-9]{4}$/', $nuevaPassword) &&
        !preg_match('/^@sdo202[3-9]$/', $nuevaPassword)
    ) {
        header('Location: ../sdo-flujo/login_index.php?errorCambioPassword=true');
        exit;
    }

// Verificar fuerza de contraseña
    $regexFuerte = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";
    if (!preg_match($regexFuerte, $nuevaPassword)) {
        header('Location: ../sdo-flujo/login_index.php?errorCambioPassword=true');
        exit;
    }

    // Mapeo de roles a sus respectivas tablas, campos y rutas
    $roles = [
        'Administrador'      => ['tabla' => 'administracion',        'id' => 'idAdministrador',     'campo' => 'passAdministrador',   'ruta' => 'sdo-administrador'],
        'Personal'           => ['tabla' => 'personal',              'id' => 'idPersonal',           'campo' => 'passPersonal',        'ruta' => 'sdo-personal'],
        'Directiva'          => ['tabla' => 'directivos',            'id' => 'idDirectivo',          'campo' => 'passDirectivo',       'ruta' => 'sdo-directiva'],
        'Jefe Tecnico'       => ['tabla' => 'jefes_tecnicos',        'id' => 'idJefe',               'campo' => 'passJefe',            'ruta' => 'sdo-jefestecnicos'],
        'Administrativo'     => ['tabla' => 'adm_tecnicos',          'id' => 'idAdmTecnicos',        'campo' => 'passAdmTecnicos',     'ruta' => 'sdo-admtec'],
        'Mesa Ayuda'         => ['tabla' => 'mesa_ayuda',            'id' => 'idMesaAyuda',          'campo' => 'passMesaAyuda',       'ruta' => 'sdo-mesa-ayuda'],
        'Bodega Pañol'       => ['tabla' => 'bodega_pañol',          'id' => 'idBodegaPañol',        'campo' => 'passBodegaPañol',     'ruta' => 'sdo-bodega-panol'],
        'Gestión Documental' => ['tabla' => 'gestion_documental',    'id' => 'idGestionDoc',         'campo' => 'passGestionDoc',      'ruta' => 'sdo-gestionDocumental'],
        'Oficina Tecnica'    => ['tabla' => 'oficina_tecnica',       'id' => 'idOficina',            'campo' => 'passOfi',             'ruta' => 'sdo-oficinaTecnica']
    ];

    if (!isset($roles[$rolUsuario])) {
        header('Location: ../sdo-flujo/login_index.php?errorCambioPassword=true');
        exit;
    }

    $info = $roles[$rolUsuario];
    $tabla       = $info['tabla'];
    $campoPass   = $info['campo'];
    $campoID     = $info['id'];
    $rutaRedir   = $info['ruta'];

    // Usar prepared statement para mayor seguridad
    $sql = "UPDATE $tabla SET $campoPass = ? WHERE $campoID = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("si", $nuevaPassword, $idUsuario);

    if ($stmt->execute()) {
        header("Location: ../$rutaRedir/index.php?exitoCambioPassword=true");
    } else {
        header('Location: ../sdo-flujo/login_index.php?errorCambioPassword=true');
    }

    $stmt->close();
    $conexion->close();

} else {
    header('Location: ../sdo-flujo/login_index.php?errorCambioPassword=true');
    exit;
}
