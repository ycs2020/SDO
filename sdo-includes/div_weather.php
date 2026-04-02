<div class="row d-flex align-items-stretch">
    <div class="col-lg-12 col-md-12 col-12 mb-3">
        <div class="card h-100">
            <div class="card-header bg-primary text-center">
                <h3 class="card-title">Pronóstico Meteorológico</h3>
            </div>

            <div class="card-body" style="min-height: 100px;">

                <!-- WIDGET DE TOMORROW.IO -->
                <script>
                    (function (d, s, id) {
                        if (d.getElementById(id)) {
                            if (window.__TOMORROW__) {
                                window.__TOMORROW__.renderWidget();
                            }
                            return;
                        }
                        const fjs = d.getElementsByTagName(s)[0];
                        const js = d.createElement(s);
                        js.id = id;
                        js.src = "https://www.tomorrow.io/v1/widget/sdk/sdk.bundle.min.js";
                        fjs.parentNode.insertBefore(js, fjs);
                    })(document, 'script', 'tomorrow-sdk');
                </script>

                <div class="tomorrow"
                    data-location-id="019092"
                    data-language="ES"
                    data-unit-system="METRIC"
                    data-skin="dark"
                    data-widget-type="upcoming"
                    style="padding-bottom: 10px; position:relative; width: 100%; max-width: none;">
                </div>

                <style>
                    .tomorrow {
                        width: 100% !important;
                        max-width: 100% !important;
                    }
                </style>

            </div>
        </div>
    </div>
</div>
<hr>