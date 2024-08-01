<?php include_once 'Views/template/header-admin.php'; ?>

<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#listaCitas" type="button" role="tab" aria-controls="listaCitas" aria-selected="true">Citas</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#nuevaCita" type="button" role="tab" aria-controls="nuevaCita" aria-selected="false">Nueva</button>
    </li>
</ul>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="listaCitas" role="tabpanel" aria-labelledby="home-tab">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle" style="width: 100%;" id="tblCitas">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Servicio</th>
                                <th>Nombre Completo</th>
                                <th>Número Celular</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Visita</th>
                                <th>Dirección</th>
                                <th>Estado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="nuevaCita" role="tabpanel" aria-labelledby="profile-tab">
        <div class="card">
            <div class="card-body p-5">
                <form id="frmRegistro">
                    <div class="row">
                        <input type="hidden" id="id" name="id">
                        <div class="col-md-6">
                            <label for="servicio_id">Servicio</label>
                            <div class="input-group input-group-outline my-3">
                                <select id="servicio_id" class="form-control" name="servicio_id">
                                    <option value="">Seleccionar</option>
                                    <?php foreach ($data['servicios'] as $servicio) { ?>
                                        <option value="<?php echo $servicio['id']; ?>"><?php echo $servicio['nombre']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="nombre_completo">Nombre Completo</label>
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label" for="nombre_completo">Nombre Completo</label>
                                <input id="nombre_completo" class="form-control" type="text" name="nombre_completo">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="numero_celular">Número Celular</label>
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label" for="numero_celular">Número Celular</label>
                                <input id="numero_celular" class="form-control" type="text" name="numero_celular">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="fecha">Fecha</label>
                            <div class="input-group input-group-outline my-3">
                                <input id="fecha" class="form-control" type="date" name="fecha">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="hora">Hora</label>
                            <div class="input-group input-group-outline my-3">
                                <input id="hora" class="form-control" type="time" name="hora">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="visita">Visita</label>
                            <div class="input-group input-group-outline my-3">
                                <select id="visita" class="form-control" name="visita">
                                    <option value="">Seleccionar</option>
                                    <option value="vis_tec">Visita Técnica</option>
                                    <option value="inst">Instalación</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="direccion_servicio">Dirección de Servicio</label>
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label" for="direccion_servicio">Dirección de Servicio</label>
                                <input id="direccion_servicio" class="form-control" type="text" name="direccion_servicio">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="estado">Estado de la Cita</label>
                            <div class="input-group input-group-outline my-3">
                                <input id="estado" class="form-control" type="text" name="estado" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="text-end">
                        <button class="btn btn-primary" type="submit" id="btnAccion">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once 'Views/template/footer-admin.php'; ?>

<script src="<?php echo BASE_URL . 'assets/js/modulos/citas.js'; ?>"></script>

</body>

</html>