<?php
session_start();
include 'conexion.php';

// Verificar que sea administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] !== 'administrador') {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: admin_productos.php");
    exit;
}

$id = $_GET['id'];

// Eliminar el producto
mysqli_query($conexion, "DELETE FROM producto WHERE id_producto = $id");

header("Location: admin_productos.php");
exit;
?>
