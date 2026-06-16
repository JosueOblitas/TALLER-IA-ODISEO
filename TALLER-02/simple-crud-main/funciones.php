<?php
$method = $_SERVER['REQUEST_METHOD'];

if($method == 'POST'){
    $data = json_decode(file_get_contents('php://input'),true);

    if(isset($data['accion'])){
        $accion = mb_strtolower($data['accion']);
    
        $response = match($accion){
            'agregar'   => agregarUsuario($data),
            'editar'    => editarUsuario($data),
            'eliminar'  => eliminarUsuario($data['id']),
            default     => die("No existe la acción: ".$data['accion'])
        };
    
        echo json_encode($response);
    }
}else{
    if($method == 'GET'){
        $data = $_GET;

        if(isset($data['accion'])){
            $accion = mb_strtolower($data['accion']);

            $response = match($accion){
                'todos'     => obtenerTodosUsuarios(),
                'obtener'   => obtenerUsuarioPorId($data['id']),
                default     => die("No existe la acción: ".$data['accion'])
            };
        
            echo json_encode($response);
        }
    }
}

function obtenerTodosUsuarios(): array
{
    include('./config/Conexion.php');

    $sql = "SELECT * FROM usuarios";

    $resultado = $conexion->query($sql);

    $datos = [];

    if($resultado = $conexion->query($sql)){
        while ($row = $resultado->fetch_assoc()) {
            $datos[] = [
                'id'        => $row['id'],
                'nombres'   => $row['nombres'],
                'apellidos' => $row['apellidos'],
                'correo'    => $row['correo'],
            ];
        }
    
        $resultado->free();
    }

    return [
        'datos' => $datos
    ];
}

function agregarUsuario(array $post): array
{
    include('./config/Conexion.php');

    $nombres    = trim($post['nombres']);
    $apellidos  = trim($post['apellidos']);
    $correo     = trim($post['correo']);

    $sql = "INSERT INTO usuarios(nombres, apellidos, correo) VALUES ('".$nombres."', '".$apellidos."', '".$correo."')";

    if($conexion->query($sql)){
        return [
            'ok' => true,
            'mensaje' => 'Se ha guardado el registro correctamente'
        ];
    }else{
        return [
            'ok' => false,
            'mensaje' => 'Ha ocurrido un error al guardar el registro'
        ];
    }
}

function obtenerUsuarioPorId($id): array
{
    include('./config/Conexion.php');

    $sql = "SELECT * FROM usuarios WHERE id = ".$id;

    $datos = [];

    if($resultado = $conexion->query($sql)){
        while ($row = $resultado->fetch_assoc()) {
            $datos = [
                'id'        => $row['id'],
                'nombres'   => $row['nombres'],
                'apellidos' => $row['apellidos'],
                'correo'    => $row['correo'],
            ];
        }
    
        $resultado->free();
    }

    if(count($datos) > 0){
        return [
            'ok' => true,
            'datos' => $datos
        ];
    }else{
        return [
            'ok' => false,
            'mensaje' => 'No se encontraron datos con el valor seleccionado'
        ];
    }
}

function editarUsuario(array $post): array
{
    include('./config/Conexion.php');

    $id         = trim($post['id']);
    $nombres    = trim($post['nombres']);
    $apellidos  = trim($post['apellidos']);
    $correo     = trim($post['correo']);

    $sql = "UPDATE usuarios SET nombres='".$nombres."', apellidos='".$apellidos."', correo='".$correo."' WHERE id=".$id;

    if($conexion->query($sql)){
        return [
            'ok' => true,
            'mensaje' => 'Se ha guardado el registro correctamente'
        ];
    }else{
        return [
            'ok' => false,
            'mensaje' => 'Ha ocurrido un error al guardar el registro'
        ];
    }
}

function eliminarUsuario($id): array
{
    include('./config/Conexion.php');

    $id = trim($id);

    $sql = "DELETE FROM usuarios WHERE id=".$id;

    if($conexion->query($sql)){
        return [
            'ok' => true,
            'mensaje' => 'Se ha eliminado el registro correctamente'
        ];
    }else{
        return [
            'ok' => false,
            'mensaje' => 'Ha ocurrido un error al eliminar el registro'
        ];
    }
}