<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

if (isset($_POST['id_producto'])) {
    $id_producto = $_POST['id_producto'];

    $stmt = mysqli_prepare($conexion, "DELETE FROM carrito WHERE id_usuario = ? AND id_producto = ?");
    mysqli_stmt_bind_param($stmt, "ii", $id_usuario, $id_producto);
    mysqli_stmt_execute($stmt);
}

header("Location: ver_carrito.php");
exit;
?>
