<?php

$accesosRapidos = [
    [
        "id" => 1,
        "nombre" => "Informes Control Centralizado",
        "ruta" => "controlCent_informes.php",
        "icono" => '<i class="fas fa-file"></i>',
        "departamentos" => [1, 13] // Control Centralizado
    ],
    [
        "id" => 2,
        "nombre" => "Organigrama HBQP",
        "ruta" => "hbqp_organigrama.php",
        "icono" => '<i class="fas fa-project-diagram"></i>',
        "departamentos" => [1, 8, 10, 13] // Varios departamentos
    ],
    [
        "id" => 3,
        "nombre" => "Turnos Control Centralizado",
        "ruta" => "turnos_calendario.php",
        "icono" => '<i class="fas fa-users"></i>',
        "departamentos" => [1, 13]
    ],
    [
        "id" => 4,
        "nombre" => "Asistencia",
        "ruta" => "asistencia_registro.php",
        "icono" => '<i class="fas fa-calendar-check"></i>',
        "departamentos" => [1, 6, 10, 13]
    ],
    [
        "id" => 5,
        "nombre" => "Listado Funcionarios",
        "ruta" => "../sdo-pdf/SDO-Excel-Funcionarios.php",
        "icono" => '<i class="fas fa-users"></i>',
        "departamentos" => [1, 2, 6, 13]
    ]
];

function renderAccesosRapidos($accesos, $idDepartamento)
{
    // Ordenar alfabéticamente por nombre
    $nombres = array_column($accesos, 'nombre');
    array_multisort($nombres, SORT_ASC, $accesos);

    $contador = 1;

    foreach ($accesos as $fila) {

        // Verificar permisos
        if (!in_array($idDepartamento, $fila['departamentos'])) {
            continue; // Si no pertenece al departamento, no lo mostramos
        }

        // Determinar color según el contador
        switch ($contador % 4) {
            case 1:
                $color = 'bg-gradient-primary';
                break;
            case 2:
                $color = 'bg-gradient-secondary';
                break;
            case 3:
                $color = 'bg-gradient-info';
                break;
            default:
                $color = 'bg-gradient-warning';
        }

        // Render del botón
        echo '
        <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
            <a href="' . $fila['ruta'] . '" style="font-size: 12pt;" class="btn btn-block ' . $color . ' btn-md">
                ' . $fila['icono'] . '<br>' . $fila['nombre'] . '
            </a>
        </div>';

        $contador++;
    }
}

?>

<div class="card-body">

    <div class="row">
        <?php renderAccesosRapidos($accesosRapidos, $idDepartamento); ?>
    </div>
</div>