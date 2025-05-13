<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

if (isset($_POST['id_producto'], $_POST['cantidad'])) {
    $id_producto = $_POST['id_producto'];
    $nueva_cantidad = (int) $_POST['cantidad'];

    // Obtener stock del producto
    $stock = mysqli_query($conexion, "SELECT stock FROM producto WHERE id_producto = $id_producto");
    $row = mysqli_fetch_assoc($stock);
    $stock_disponible = $row['stock'];

    if ($nueva_cantidad > $stock_disponible) {
        $_SESSION['error'] = "No hay suficiente stock. Máximo permitido: $stock_disponible unidades.";
    } elseif ($nueva_cantidad <= 0) {
        // Si la cantidad es 0 o menor, eliminar el producto del carrito
        $stmt = mysqli_prepare($conexion,
            "DELETE FROM carrito WHERE id_usuario = ? AND id_producto = ?");
        mysqli_stmt_bind_param($stmt, "ii", $id_usuario, $id_producto);
        mysqli_stmt_execute($stmt);
        $_SESSION['mensaje'] = "Producto eliminado del carrito.";
    } else {
        // Actualizar cantidad
        $stmt = mysqli_prepare($conexion,
            "UPDATE carrito SET cantidad = ? WHERE id_usuario = ? AND id_producto = ?");
        mysqli_stmt_bind_param($stmt, "iii", $nueva_cantidad, $id_usuario, $id_producto);
        mysqli_stmt_execute($stmt);
        $_SESSION['mensaje'] = "Cantidad actualizada correctamente.";
    }
}

header("Location: ver_carrito.php");
exit;
?>
