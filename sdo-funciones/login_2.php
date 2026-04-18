<?php
include("conexion.php");
include("funciones.php");

if (isset($_POST['usuarioRegistrado'], $_POST['passwordRegistrado'])) {
    $usuario = trim($_POST['usuarioRegistrado']);
    $password = trim($_POST['passwordRegistrado']);

    if (!empty($usuario) && !empty($password)) {

        $sqlUsuarios = "
            SELECT *
            FROM (
                SELECT idAdministrador AS idUsuario, nomAdministrador AS nomUsuario, userAdministrador AS userUsuario,
                       passAdministrador AS passUsuario, emailAdministrador AS emailUsuario, rolAdministrador AS cargoUsuario,
                       'Administrador' AS rolUsuario, 13 AS idDepartamento, 0 AS idSubDepartamento, 'true' AS login
                FROM administracion WHERE adminActivo = 1

                UNION ALL
                SELECT idAdmTecnicos, nomAdmTecnicos, userAdmTecnicos, passAdmTecnicos, emailAdmTecnicos,
                       'Administrativo', 'Administrativo', idDepartamento, 0, 'true'
                FROM adm_tecnicos

                UNION ALL
                SELECT idBodegaPañol, nomBodegaPañol, userBodegaPañol, passBodegaPañol, emailBodegaPañol,
                       'Bodega Pañol', 'Bodega Pañol', 0, 0, 'true'
                FROM bodega_pañol

                UNION ALL
                SELECT idDirectivo, nomDirectivo, userDirectivo, passDirectivo, emailDirectivo,
                       cargoDirectivo, 'Directiva', 0, 0, 'true'
                FROM directivos

                UNION ALL
                SELECT idJefe, nomJefe, userJefe, passJefe, emailJefe,
                       cargoJefe, 'Jefe Tecnico', idDepartamento, idSubdepartamento, 'true'
                FROM jefes_tecnicos WHERE userActivado = 1

                UNION ALL
                SELECT idMesaAyuda, nomMesaAyuda, userMesaAyuda, passMesaAyuda, emailMesaAyuda, 
                       'Mesa Ayuda', 'Mesa Ayuda', idDepartamento, 0, 'true'
                FROM mesa_ayuda

                UNION ALL
                SELECT idOficina, nomOfi, userOfi, passOfi, emailOfi,
                       'Oficina Tecnica', 'Oficina Tecnica', 0, 0, 'true'
                FROM oficina_tecnica

                UNION ALL
                SELECT idPersonal, nomPersonal, userPersonal, passPersonal, emailPersonal,
                       cargoPersonal, 'Personal', departamentoPersonal, idSubdepartamento, 'true'
                FROM personal WHERE activoPersonal = 1

                UNION ALL
                SELECT idUnidadPersonal, nomUnidadPersonal, userUnidadPersonal, passUnidadPersonal, emailUnidadPersonal,
                       rolUnidadPersonal, 'RRHH', 0, 0, 'true'
                FROM unidad_personal WHERE activoUnidadPersonal = 1
            ) AS usuarios
            WHERE userUsuario = ? AND passUsuario = ?
            LIMIT 1
        ";

        $stmt = $conexion->prepare($sqlUsuarios);
        $stmt->bind_param("ss", $usuario, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {

            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
                ini_set('session.cache_expire', 200000);
                ini_set('session.cache_limiter', 'none');
                ini_set('session.cookie_lifetime', 2000000);
                ini_set('session.gc_maxlifetime', 200000);
            }

            // Variables base
            $_SESSION['userUsuario'] = $row['userUsuario'];
            $_SESSION['nomUsuario'] = $row['nomUsuario'];
            $_SESSION['idUsuario'] = $row['idUsuario'];
            $_SESSION['login'] = true;
            $_SESSION['rolUsuario'] = $row['rolUsuario'];
            $_SESSION['idUsuarioParaSubida'] = $row['idUsuario'];

            // Variables adicionales comunes
            $_SESSION['cargoUsuario'] = $row['cargoUsuario'];
            $_SESSION['idDepartamento'] = $row['idDepartamento'];
            $_SESSION['idSubDepartamento'] = $row['idSubDepartamento'];
            $_SESSION['emailUsuario'] = $row['emailUsuario'];
            $_SESSION['correoUsuario'] = $row['emailUsuario'];
            $_SESSION['cargoJefe'] = $row['cargoUsuario'];
            $_SESSION['departamentoPersonal'] = $row['idDepartamento'];
            $_SESSION['emailJefe'] = $row['emailUsuario'];

            // Nombre del departamento
            $_SESSION['nomDepartamento'] = ($row['idDepartamento'] > 0)
                ? getNomDepartamento($row['idDepartamento'])
                : 'Subdirección de Operaciones';

            // Verificar contraseña temporal
            $clavesTemporales = ['@Sdo2025', '@SDO2025', '@sdo2025', '@Sdo2026', '@SDO2026', '@sdo2026'];
            if (in_array($password, $clavesTemporales)) {
                header('Location: ../sdo-flujo/cambiarPassword.php?cambiarPassword=true');
                exit;
            }

            // Verificar fuerza de contraseña
            $regexFuerte = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";
            if (!preg_match($regexFuerte, $password)) {
                header('Location: ../sdo-flujo/cambiarPassword.php?cambiarPassword=true');
                exit;
            }

            // Redirección según rol
            $rol = strtolower($row['rolUsuario']);
            $rol = str_replace(' ', '', $rol); // elimina espacios
            $rutas = [
                'personal'        => 'sdo-personal',
                'administrativo'  => 'sdo-admtec',
                'bodegapañol'     => 'sdo-bodega-panol',
                'jefetecnico'     => 'sdo-jefestecnicos',
                'administrador'   => 'sdo-administrador',
                'directiva'       => 'sdo-directiva',
                'oficinatecnica'  => 'sdo-oficinaTecnica',
                'mesaayuda'       => 'sdo-mesa-ayuda',
                'rrhh'            => 'sdo-rrhh'
            ];

            if (isset($rutas[$rol])) {
                header('Location: ../'.$rutas[$rol].'/index.php?loginExito=true');
                exit;
            } else {
                header('Location: ../sdo-flujo/login_index.php?errorSesionData=true');
                exit;
            }

        } else {
            header('Location: ../sdo-flujo/login_index.php?errorLogin=true');
            exit;
        }

    } else {
        header('Location: ../sdo-flujo/login_index.php?errorLogin=true');
        exit;
    }

} else {
    header('Location: ../sdo-flujo/login_index.php?errorLogin=true');
    exit;
}
