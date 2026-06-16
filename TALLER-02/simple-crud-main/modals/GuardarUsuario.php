<div class="modal fade" id="mdlGuardarUsuario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-modal aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">
                    <span id="accionFormulario"></span> Usuario
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row" id="formularioUsuario" autocomplete="off">
                    <input type="hidden" name="id" id="id" />
                    <div class="col-md-12 mb-3">
                        <label for="inputNombres" class="form-label">Nombres</label>
                        <input type="text" class="form-control" name="inputNombres" id="inputNombres" placeholder="">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="inputApellidos" class="form-label">Apellidos</label>
                        <input type="text" class="form-control" name="inputApellidos" id="inputApellidos" placeholder="">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="inputCorreo" class="form-label">Correo</label>
                        <input type="email" class="form-control" name="inputCorreo" id="inputCorreo" placeholder="">
                    </div>
               
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="guardarUsuario()">Guardar</button>
            </div>
        </div>
    </div>
</div>