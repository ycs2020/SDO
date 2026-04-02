<style>
    /* Centralizar la lista de tabs */
    .sub-tab-container .nav-pills {
        justify-content: center;
    }

    .sub-tab-container .nav-pills .nav-link {
        border: 1px solid #dee2e6;
        border-radius: 4px;
        background-color: #f8f9fa;
        color: #495057;
        margin: 0 5px; /* Margen equilibrado a ambos lados */
        transition: all 0.3s ease;
        min-width: 200px; /* Ancho mínimo para que se vean como bloques robustos */
        text-align: center;
    }

    .sub-tab-container .nav-pills .nav-link.active {
        background-color: #007bff !important;
        border-color: #007bff;
        color: #fff !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* Ajuste para las cards de hospitales */
    .card-hospital {
        max-width: 350px; /* Evita que en pantallas grandes se estiren demasiado */
        margin: 0 auto;   /* Centra la card en su columna */
    }
</style>

<div class="sub-tab-container">
    <ul class="nav nav-pills mb-3" id="pills-tab-luz" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="pills-menu-tab" data-toggle="pill" href="#pills-menu" role="tab">
                <i class="fas fa-th-large mr-2"></i> Menú Hospitales
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pills-dash-tab" data-toggle="pill" href="#pills-dash" role="tab">
                <i class="fas fa-chart-line mr-2"></i> Dashboard de Consumo
            </a>
        </li>
    </ul>

    <div class="tab-content border-top pt-4" id="pills-tabContent">

        <div class="tab-pane fade show active" id="pills-menu" role="tabpanel">
            <div class="row justify-content-center"> <div class="col-xl-4 col-lg-5 col-md-6 col-12 mb-4">
                    <div class="card h-100 shadow-sm border-primary card-hospital">
                        <div class="card-body text-center d-flex flex-column">
                            <img src="../dist/img/HSMQ.jpg" class="img-fluid rounded mb-3" style="height: 160px; width: 100%; object-fit: cover;">
                            <h6 class="font-weight-bold">Hospital San Martín de Quillota</h6>
                            <p class="small text-muted flex-grow-1">Monitoreo de consumo</p>
                            <a href="electricidad_administracion_hsmq.php" class="btn btn-info btn-block shadow-sm">
                                <i class="fas fa-external-link-alt mr-1"></i> Visualizar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-5 col-md-6 col-12 mb-4">
                    <div class="card h-100 shadow-sm border-primary card-hospital">
                        <div class="card-body text-center d-flex flex-column">
                            <img src="../dist/img/HBQP.jpg" class="img-fluid rounded mb-3" style="height: 160px; width: 100%; object-fit: cover;">
                            <h6 class="font-weight-bold">Hospital Biprovincial Quillota Petorca</h6>
                            <p class="small text-muted flex-grow-1">Monitoreo de consumo</p>
                            <a href="electricidad_administracion_hbqp.php" class="btn btn-info btn-block shadow-sm">
                                <i class="fas fa-external-link-alt mr-1"></i> Visualizar
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="tab-pane fade" id="pills-dash" role="tabpanel">
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-8"> <div class="card bg-light shadow-none border">
                        <div class="card-body text-center">
                            <h5 class="mb-4"><i class="fas fa-info-circle text-primary mr-2"></i> Resumen de Indicadores</h5>

                            <div class="row mt-4">
                                <div class="col-6 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header text-primary" style="font-size: 1.5rem;">
                                            $<?php echo number_format($totalMontoElec, 0, ',', '.'); ?>
                                        </h5>
                                        <span class="description-text">GASTO TOTAL RED</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="description-block">
                                        <h5 class="description-header text-success" style="font-size: 1.5rem;">
                                            <?php echo number_format($totalEnergia, 0, ',', '.'); ?> <small>kWh</small>
                                        </h5>
                                        <span class="description-text">CONSUMO TOTAL RED</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>