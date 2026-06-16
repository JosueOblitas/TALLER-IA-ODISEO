const modalGuardarUsuario = new bootstrap.Modal('#mdlGuardarUsuario')
const modalEliminarUsuario = new bootstrap.Modal('#mdlEliminarUsuario')
const accionFormulario = document.getElementById('accionFormulario')

let tabla = new DataTable('#tbl-usuarios', {
    language: {
        "decimal": "",
        "emptyTable": "No hay información",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Entradas",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "Sin resultados encontrados",
        "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior"
        }
    },
});

document.addEventListener('DOMContentLoaded', () => {
    obtenerUsuarios()
})

toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}

function abrirModalAgregarUsuario()
{
    document.getElementById('id').value             = ''
    document.getElementById('inputNombres').value   = ''
    document.getElementById('inputApellidos').value = ''
    document.getElementById('inputCorreo').value    = ''

    accionFormulario.innerHTML = 'Agregar'
    modalGuardarUsuario.show()
}

async function abrirModalEditarUsuario(id)
{
    document.getElementById('id').value             = ''
    document.getElementById('inputNombres').value   = ''
    document.getElementById('inputApellidos').value = ''
    document.getElementById('inputCorreo').value    = ''

    const response = await axios.get('funciones.php', {
        params: {
            accion: 'obtener',
            id : id
        }
    })

    if(response.data.ok){
        accionFormulario.innerHTML = 'Editar'

        document.getElementById('id').value             = response.data.datos.id
        document.getElementById('inputNombres').value   = response.data.datos.nombres
        document.getElementById('inputApellidos').value = response.data.datos.apellidos
        document.getElementById('inputCorreo').value    = response.data.datos.correo

        modalGuardarUsuario.show()
    }else{
        toastr.error(response.data.mensaje)
    }
}

function abrirModalEliminar(id)
{
    document.getElementById('idEliminar').value = id
    modalEliminarUsuario.show()
}

async function guardarUsuario()
{
    let accion = (document.getElementById('id').value != '') ? 'editar' : 'agregar'

    const response = await axios.post("funciones.php", {
        accion      : accion,
        id          : document.getElementById('id').value,
        nombres     : document.getElementById('inputNombres').value,
        apellidos   : document.getElementById('inputApellidos').value,
        correo      : document.getElementById('inputCorreo').value
    });

    if(response.data.ok){
        obtenerUsuarios()
        modalGuardarUsuario.hide();
        toastr.success(response.data.mensaje)
    }else{
        toastr.error(response.data.mensaje)
    }
}

async function obtenerUsuarios()
{
    const response = await axios.get("funciones.php", {
        params: {
            accion: 'todos'
        }
    })

    if(response.data){
        tabla.clear().draw();

        for(let i = 0; i < response.data.datos.length; i++){
            tabla.row.add([
                i + 1,
                response.data.datos[i].nombres,
                response.data.datos[i].apellidos,
                response.data.datos[i].correo,
                `
                <button 
                    type="button" 
                    class="btn btn-warning" 
                    onclick="abrirModalEditarUsuario(${response.data.datos[i].id})"
                ><i class="fa-solid fa-pen"></i></button>
                <button 
                    type="button" 
                    class="btn btn-danger"
                    onclick="abrirModalEliminar(${response.data.datos[i].id})"
                ><i class="fa-solid fa-trash"></i></button>
                `
            ]).draw()
        }
    }
}

async function eliminarUsuario()
{
    let idEliminar = document.getElementById('idEliminar').value

    const response = await axios.post('funciones.php', {
        accion: 'eliminar',
        id: idEliminar
    })

    if(response.data.ok){
        document.getElementById('idEliminar').value = ''
        obtenerUsuarios()
        toastr.success(response.data.mensaje)
        modalEliminarUsuario.hide()
    }else{
        toastr.error(response.data.mensaje)
    }
}