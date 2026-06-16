<?php
$host           = "localhost";
$usuario        = "root";
$contrasena     = "";
$baseDatos      = "simple_crud";

$conexion = new mysqli($host, $usuario, $contrasena, $baseDatos);

if ($conexion->connect_error) {
    die("Error de conexión: ". $conexion->connect_error);
}
