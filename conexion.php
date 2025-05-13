<?php
$servidor = "localhost:3307";
$usuario = "root";
$clave = "";
$bd = "tienda_virtual";

$conexion = mysqli_connect($servidor, $usuario, $clave, $bd);
if (!$conexion) {
    die("Error al conectar: " . mysqli_connect_error());
}
?>
