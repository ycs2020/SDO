<div class="text-center mb-4">
    <h4 class="font-weight-bold text-uppercase text-primary">
        <i class="fas fa-ticket-alt"></i> Nuevo Requerimiento de Plataforma
    </h4>
    <p class="text-muted">Detalla tu solicitud para que el equipo de desarrollo pueda gestionarla.</p>
</div>

<hr>

<form id="formNuevoRequerimiento" method="POST">
    <div class="row">
        <div class="col-md-6 col-12">
            <div class="form-group">
                <label for="nombre_completo">
                    <i class="fas fa-user-circle text-secondary"></i> Solicitante
                </label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-id-badge"></i></span>
                    </div>
                    <input type="text" name="nombre_completo" id="nombre_completo" class="form-control"
                            value="<?= $_SESSION['nomUsuario']; ?>" readonly
                            style="background-color: #f4f6f9; font-weight: 600;">
                </div>
            </div>
        </div>

        <div class="col-md-6 col-12">
            <div class="form-group">
                <label for="tipo_solicitud">
                    <i class="fas fa-list-ul text-secondary"></i> Tipo de Solicitud
                </label>
                <select name="tipo_solicitud" id="tipo_solicitud" class="form-control custom-select" required>
                    <option value="" selected disabled>Seleccione una opción...</option>
                    <option value="Error/Bug">Error / Bug (Algo no funciona)</option>
                    <option value="Mejora UI">Mejora (Optimizar módulo existente)</option>
                    <option value="Nueva Funcionalidad">Nueva Funcionalidad (Sugerencia)</option>
                    <option value="Accesos">Accesos y Permisos</option>
                    <option value="Capacitación">Capacitación</option>
                    <option value="Otro">Otro / Consulta General</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <label for="asunto">
                    <i class="fas fa-comment-dots text-secondary"></i> Asunto
                </label>
                <input type="text" name="asunto" id="asunto" class="form-control"
                        placeholder="Ej: Error al intentar exportar el inventario de gases" required>
            </div>
        </div>

        <div class="col-12">
            <div class="form-group">
                <label for="requerimiento">
                    <i class="fas fa-align-left text-secondary"></i> Descripción detallada
                </label>
                <textarea name="requerimiento" id="requerimiento" class="form-control" rows="6"
                        placeholder="Describe paso a paso lo que sucede o lo que necesitas implementar..." required></textarea>
                <small class="form-text text-muted">
                    <i class="fas fa-info-circle"></i> Sea lo más descriptivo posible para una respuesta rápida.
                </small>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12 text-right">
            <button type="reset" class="btn btn-outline-secondary btn-flat mr-2">
                <i class="fas fa-eraser"></i> Limpiar Campos
            </button>
            <button type="submit" class="btn btn-primary btn-flat px-4 shadow-sm">
                <i class="fas fa-paper-plane"></i> Enviar Requerimiento
            </button>
        </div>
    </div>
</form>