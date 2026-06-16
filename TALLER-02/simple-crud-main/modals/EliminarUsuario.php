<div class="modal fade" id="mdlEliminarUsuario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-modal aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">
                    Eliminar Usuario
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row" id="eliminarUsuario" autocomplete="off">
                    <input type="hidden" name="idEliminar" id="idEliminar" />
                    <p>¿Está seguro de eliminar el registro?</p>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-danger" onclick="eliminarUsuario()">Eliminar</button>
            </div>
        </div>
    </div>
</div>